<?php

class Film {
  public $id;
  public $filmlink;
  public $img_link;           // contains url to film's poster
  public $name;               // film name
  public $englishName = "";   // foreign film name
  public $directors = "";     // list of directors
  public $year;               // year when film was published 
  public $countries = "";     // where the film was made
  public $genres = "";        // that's obvious
  public $rating;             // it's the most important value
  public $imdb;               // rating by imdb
  public $runtime;            // how long the film lasts

  private $xpath;

  public function fromXPath($xpath) {
    $this->xpath = $xpath;

    $this->img_link = $this->queryImage();
    $this->name = $this->queryName();
    $this->englishName = $this->queryEnglishName();
    $this->directors = $this->queryDirectors();
    $this->year = $this->queryYear();
    $this->countries = $this->queryCountries();
    $this->genres = $this->queryGenres();
    $this->rating = $this->queryRating();
    $this->imdb = $this->queryImdb();
    $this->runtime = $this->queryRuntime();
  }

  public function fromRow($row) {
    $this->id = $row['id'];
    $this->filmlink = $row['film_url'];
    $this->img_link = $row['img_link'];
    $this->name = $row['name'];
    $this->englishName = $row['englishName'];
    $this->directors = $row['directors'];
    $this->year = $row['year'];
    $this->countries = $row['countries'];
    $this->genres = $row['genres'];
    $this->rating = $row['rating'];
    $this->imdb = $row['imdb'];
    $this->runtime = $row['runtime'];
  }

  private function queryImage() {
    $nodelist = $this->xpath->query('//*[@id="photoBlock"]/div[1]/a/img');
    if($nodelist->length == 0) {
      $nodelist = $this->xpath->query('//*[@id="wrap"]/a/img');
    }

    $node = $nodelist->item(0);
    foreach ($node->attributes as $attr) {
      if($attr->name == "src") {
        return $attr->textContent;
      }
    }
  }

  private function queryName() {
    return $this->xpath->query("//h1")->item(0)->textContent;
  }

  private function queryEnglishName() {
    return $this->xpath->query('//*[@id="headerFilm"]/span')->item(0)->textContent;
  }

  private function queryDirectors() {
    return $this->xpath->query('//td[@itemprop="director"]')->item(0)->textContent;
  }

  private function queryYear() {
    return $this->xpath->query('//a[contains(@href,"m_act%5Byear%5D")]')->item(0)->textContent;
  }

  private function queryCountries() {
    $_countries = $this->xpath->query('//td/div/a[contains(@href,"m_act%5Bcountry%5D")]');
    $result = "";
    foreach ($_countries as $c) {
      $result .= $c->textContent.', ';
    }
    return substr($result, 0, -2);
  }

  private function queryGenres() {
    return $this->xpath->query('//span[@itemprop="genre"]')->item(0)->textContent;
  }

  private function queryRating() {
    return $this->xpath->query('//span[contains(@class, "rating_ball")]')->item(0)->textContent;
  }

  private function queryImdb() {
    $str = $this->xpath->query('//*[@id="block_rating"]/div[1]/div[2]')->item(0)->textContent; 
    return substr($str, 6, 4);
  }

  private function queryRuntime() {
    return $this->xpath->query('//td[@id="runtime"]')->item(0)->textContent;
  }
}
