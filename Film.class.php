<?php

require_once ('Snoopy.class.php');
require_once('core.php');

class Film {
  public $kpid;
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

  public $error = "";

  private $xpath;

  function __construct() {
    $a = func_get_args(); 
    $i = func_num_args(); 
    if (method_exists($this,$f='__construct'.$i)) { 
        call_user_func_array(array($this,$f),$a); 
    } 
  }

  function __construct1($query) {
    errorLog('1', "Inside construct1 query: ".$query, __FILE__, __LINE__);

    // TODO check if there was the same request
    
    $client = new Snoopy();
    $client->referer = "http://kinopoisk.ru/";
    $client->agent = "Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0";

    if(substr($query, 0, 7) != "http://" && substr($query, 0, 8) != "https://") {
      $url = "https://www.kinopoisk.ru/index.php?first=yes&kp_query=".urlencode($query);
      $direct = false;
    } else {
      $url = $query;
      $direct = true;
    }

    errorLog('2', $url, __FILE__, __LINE__);

    $html = $client->fetch($url)->results;

    // TODO если изначально вставлена ссылка, то lastredirectaddr тоже == ''
    if(!$direct && $client->lastredirectaddr == '') {
      errorLog('2', "Can't fetch film", __FILE__, __LINE__);
     // errorLog('2', var_export($client, true), __FILE__, __LINE__);
      $this->error = "There were an error";
    } else {
      if($client->error) {
        errorLog("666", $client->error, __FILE__, __LINE__);
      }

      if(empty($html)) {
        // hack against 302 error
        $url = $client->_redirectaddr;
        $html = $client->fetch($url)->results;
      }

      if(empty($client->lastredirectaddr)) {
        $this->filmlink = $url;
      } else {
        $this->filmlink = $client->lastredirectaddr;  
      }

      errorLog("abc", var_export(explode('/', $this->filmlink), true), __FILE__, __LINE__);
      $this->kpid = explode('/', $this->filmlink);
      $this->kpid = $this->kpid[count($this->kpid) - 2];
/*
      $this->kpid = substr($this->filmlink, 34);
      $this->kpid = substr($this->kpid, 0, -1); */

      errorLog("abc", $this->kpid, __FILE__, __LINE__);

      libxml_use_internal_errors(true);
      $dom = new DOMDocument();
      if(!$dom->loadHTML($html))
        die("Fail on loading");

      $this->xpath = new DOMXPath($dom);

      $this->downloadPoster();
      $this->fillFromXPath();  
    }
  }

  private function downloadPoster() {
    $img_link = $this->queryImage();

    file_put_contents("posters/".$this->kpid.".jpg", fopen($img_link, "r"));
  }

  private function fillFromXPath() {
    $this->img_link = "posters/".$this->kpid.".jpg";
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

  public static function fromRow($row) {
    $film = new Film();
    
    $film->kpid = $row['kpid'];
    $film->filmlink = "https://www.kinopoisk.ru/film/".$film->kpid."/";
    $film->img_link = "posters/".$film->kpid.".jpg";
    $film->name = $row['name'];
    $film->englishName = $row['englishName'];
    $film->directors = $row['directors'];
    $film->year = $row['year'];
    $film->countries = $row['countries'];
    $film->genres = $row['genres'];
    $film->rating = $row['rating'];
    $film->imdb = $row['imdb'];
    $film->runtime = $row['runtime'];

    return $film;
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
