<?php

require_once 'Snoopy.class.php';
require_once 'errorhandler.php';
require_once 'dbconnect.php';

// represents a row from a filmlist table
class Film {
  public $id;
  public $kpid;               // could be null
  public $name;               // film name
  public $original_name = ""; // foreign film name
  public $directors = "";     // list of directors
  public $year;               // year when film was published 
  public $countries = "";     // where the film was made, holds ids of countries
  public $genres = "";        // that's obvious, holds ids of genres
  public $rating;             // it's the most important value
  public $imdb;               // rating by imdb
  public $runtime;            // how long the film lasts in min
  public $description;
  public $trailer;            // youtube video_id
  public $actors;             // id of actors
  public $needs_approval;     // 

  public $error = "";

  private $xpath;
  private $conn;

  function __construct() {
    $this->conn = Database::getConnection();
  }

  public function fillFromXPath($xpath) {
    $this->xpath = $xpath;

    $this->kpid = explode('/', $xpath->document->documentURI);
    $this->kpid = $this->kpid[count($this->kpid) - 2];

    $this->name = $this->queryName();
    $this->original_name = $this->queryOriginalName();
    $this->directors = $this->queryDirectors();
    $this->year = $this->queryYear();
    $this->rating = $this->queryRating();
    $this->imdb = $this->queryImdb();
    $this->runtime = $this->queryRuntime();
    $this->countries = $this->queryCountries();
    $this->genres = $this->queryGenres();
    $this->description = $this->queryDescription();
    $this->trailer = "";  // TOOD link to youtube
    $this->actors = $this->queryActors();
    $this->needs_approval = 1;

    $this->id = $this->writeToDB();
    $this->downloadPoster();
  }

/*
  public function fromRow($row) {
    $this->kpid = $row['kpid'];
    $this->filmlink = "https://www.kinopoisk.ru/film/".$this->kpid."/";
    $this->img_link = "posters/".$this->kpid.".jpg";
    $this->name = $row['name'];
    $this->englishName = $row['englishName'];
    $this->directors = $row['directors'];
    $this->year = $row['year'];
    $this->countries = $row['countries'];
    $this->genres = $row['genres'];
    $this->rating = $row['rating'];
    $this->imdb = $row['imdb'];
    $this->runtime = $row['runtime'];
  } */

  private function queryName() {
    return $this->xpath->query("//h1")->item(0)->textContent;
  }

  private function queryOriginalName() {
    return $this->xpath->query('//*[@id="headerFilm"]/span')->item(0)->textContent;
  }

  private function queryDirectors() {
    return $this->xpath->query('//td[@itemprop="director"]')->item(0)->textContent;
  }

  private function queryYear() {
    return $this->xpath->query('//a[contains(@href,"m_act%5Byear%5D")]')->item(0)->textContent;
  }

  private function queryRating() {
    return $this->xpath->query('//span[contains(@class, "rating_ball")]')->item(0)->textContent;
  }

  private function queryImdb() {
    $str = $this->xpath->query('//*[@id="block_rating"]/div[1]/div[2]')->item(0)->textContent; 
    return substr($str, 6, 4);
  }

  private function queryRuntime() {
    $time = $this->xpath->query('//td[@id="runtime"]')->item(0)->textContent;
    $time = explode("мин.", $time); 
    return (int) $time[0];
  }

  private function queryCountries() {
    $_countries = $this->xpath->query('//td/div/a[contains(@href,"m_act%5Bcountry%5D")]');

    $to_return = "";
    foreach ($_countries as $c) {
      $sql = "SELECT id FROM countries WHERE country='".$c->textContent."';";
      $result = $this->conn->query($sql);

      errorLog(257, "$sql", __FILE__, __LINE__);

      if($result->num_rows == 1) {    // there's the country in the db
        $id = $result->fetch_assoc()['id'];

        errorLog(257, "there's the country yet $c->textContent with id $id", __FILE__, __LINE__);
      } else {  // there's not
        $sql = "INSERT INTO countries (country) VALUES ('$c->textContent')";
        $result = $this->conn->query($sql);
        $id = $this->conn->insert_id;

        errorLog(257, "Added new country $c->textContent with id $id", __FILE__, __LINE__);
      }

      $to_return .= $id.' ';
    }

    errorLog(257, "The result is $to_return", __FILE__, __LINE__);

    return substr($to_return, 0, -1);
  }

  private function queryGenres() {
    $genres = $this->xpath->query('//span[@itemprop="genre"]')->item(0)->textContent;

    $to_return = "";
    // convert to array
    $genres = explode(', ', $genres);
    foreach ($genres as $g) {
      $sql = "SELECT id FROM genres WHERE genre='$g'";
      $result = $this->conn->query($sql);

      if($result->num_rows == 1) {
        $id = $result->fetch_assoc()['id'];
      } else {
        $sql = "INSERT INTO genres (genre) VALUES ('$g')";
        $result = $this->conn->query($sql);
        $id = $this->conn->insert_id;
      }

      $to_return .= $id.' ';
    }

    return substr($to_return, 0, -1);
  }

  private function queryDescription() {
    $d = $this->xpath->query('//div[@itemprop="description"]')->item(0)->textContent;

    //errorLog(7575757, $d, __FILE__, __LINE__);
    return $d;
  }

  private function queryActors() {
    $actorslist = $this->xpath->query('//*[@id="actorList"]/ul[1]')->item(0)->childNodes;

    $actors = "";
    for($i = 0; $i < $actorslist->length - 1; ++$i) {
      $a = $actorslist->item($i)->textContent;

     // errorLog(2333, $a, __FILE__, __LINE__);

      $sql = "SELECT id FROM actors WHERE name='$a'";
      $result = $this->conn->query($sql);

      if($result->num_rows == 1) {
        $id = $result->fetch_assoc()['id'];
      } else {
        $sql = "INSERT INTO actors (name) VALUES ('$a')";
        $result = $this->conn->query($sql);
        $id = $this->conn->insert_id;
      }

      $actors .= $id.' ';
    }

    errorLog(2334, var_export($actors, true), __FILE__, __LINE__);
    return substr($actors, 0, -1);
  }

  private function writeToDB() {
    $sql = "INSERT INTO filmlist (kpid, name, original_name, directors, year, countries, genres, rating, imdb, runtime, description, trailer, actors, needs_approval) VALUES ('".$this->kpid."', '".$this->name."', '".$this->original_name."', '".$this->directors."', '".$this->year."', '".$this->countries."', '".$this->genres."', '".$this->rating."', '".$this->imdb."', '".$this->runtime."', '".$this->description."', '".$this->trailer."', '".$this->actors."', '".$this->needs_approval."')";

    $result = $this->conn->query($sql);
    if($result) {
      return $this->conn->insert_id;
    }
  }

  private function downloadPoster() {
    $img_link = $this->queryImage();

    file_put_contents("posters/".$this->id.".jpg", fopen($img_link, "r"));
  }

  private function queryImage() {
    $nodelist = $this->xpath->query('//*[@id="photoBlock"]/div[1]/a/img');
    if($nodelist->length == 0) {
      $nodelist = $this->xpath->query('//*[@id="photoBlock"]/div[1]/img');
    }

    $node = $nodelist->item(0);
    foreach ($node->attributes as $attr) {
      if($attr->name == "src") {
        return $attr->textContent;
      }
    }
  }
}
