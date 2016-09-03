<?php
if(!empty($_POST['addfilm'])) {
  require_once ('Snoopy.class.php');
  require_once('core.php');

  $film = $_POST['addfilm'];
  $client = new Snoopy();
  $client->referer = "http://kinopoisk.ru/";
  $client->agent = "Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0";

  if(substr($film, 0, 7) != "http://" && substr($film, 0, 8) != "https://") {
    $film = "https://www.kinopoisk.ru/index.php?first=yes&kp_query=".urlencode($film);
  }

  for($i = 0; $i < 5; ++$i) {
    $html = $client->fetch($film)->results;

    if(!empty($client->error)) {
      errorLog("666", $client->error, "add.php", 36);
    }

    if(!empty($html)) {
      break;
    } else {
      // hack against 302 error
      $film = $client->_redirectaddr;
    }
  }

  if(empty($html)) {
    var_dump($client);
    die("Oh");
  }
     
  libxml_use_internal_errors(true);
  $dom = new DOMDocument();
  if(!$dom->loadHTML($html))
    die("Fail on loading");

  $xpath = new DOMXPath($dom);
  $film = new Film;
  $film->fromXPath($xpath);
  $film->filmlink = $client->lastredirectaddr;

  $sql = "INSERT INTO filmlist (img_link, film_url, name, englishName, directors, year, countries, genres, rating, imdb, runtime) VALUES ('".$film->img_link."','".$film->filmlink."','".$film->name."','".$film->englishName."','".$film->directors."','".$film->year."','".$film->countries."','".$film->genres."','".$film->rating."','".$film->imdb."','".$film->runtime."')";


  $result = $conn->query($sql);

  $film->id = $conn->insert_id;

  printRow($film);
}