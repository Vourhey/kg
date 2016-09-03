<?php
if(!empty($_POST['addfilm'])) {
  require_once ('Snoopy.class.php');
  require_once('core.php');

  $url = $_POST['addfilm'];
  $tablename = $_POST['table'];
  $client = new Snoopy();
  $client->referer = "http://kinopoisk.ru/";
  $client->agent = "Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0";

  if(substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://") {
    // so $url is a film name
    $url = "https://www.kinopoisk.ru/index.php?first=yes&kp_query=".urlencode($url);
  }

  for($i = 0; $i < 5; ++$i) {
    $html = $client->fetch($url)->results;

    if(!empty($client->error)) {
      errorLog("666", $client->error, "add.php", 36);
    }

    if(!empty($html)) {
      break;
    } else {
      // hack against 302 error
      $url = $client->_redirectaddr;
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
  if(empty($client->lastredirectaddr)) {
    $film->filmlink = $url;
  } else {
    $film->filmlink = $client->lastredirectaddr;  
  }

  if(!empty($_POST['replace'])) {
    $film->id = $_POST['replace'];
    errorLog(321, "Replace ".$film->id, "add.php", 48);

    $sql = "UPDATE $tablename SET img_link='".$film->img_link."', film_url='".$film->filmlink.
           "', name='".$film->name."', englishName='".$film->englishName.
           "', directors='".$film->directors."', year='".$film->year.
           "', countries='".$film->countries."', genres='".$film->genres.
           "', rating='".$film->rating."', imdb='".$film->imdb."', runtime='".$film->runtime."' WHERE id=".$film->id;

    $result = $conn->query($sql);
  } else {
    $sql = "INSERT INTO $tablename (img_link, film_url, name, englishName, directors,".
           " year, countries, genres, rating, imdb, runtime) VALUES ('".$film->img_link.
           "','".$film->filmlink."','".$film->name."','".$film->englishName."','".$film->directors.
           "','".$film->year."','".$film->countries."','".$film->genres."','".$film->rating.
           "','".$film->imdb."','".$film->runtime."')";

    $result = $conn->query($sql);
    $film->id = $conn->insert_id;
  }

  printRow($film);
}