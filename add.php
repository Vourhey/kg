<?php

require_once('dbconnect.php');

if(!empty($_POST['addfilm'])) {
  require_once ('Snoopy.class.php');
  require_once ('Film.class.php');

  $film = $_POST['addfilm'];
  //$films = explode(PHP_EOL, $films);

//  echo $film.'\n';
  
  $client = new Snoopy();
  $client->referer = "http://kinopoisk.ru/";
  $client->agent = "Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0";


  if(substr($film, 0, 7) != "http://" && substr($film, 0, 8) != "https://") {
    $film = "https://www.kinopoisk.ru/index.php?first=yes&kp_query=".urlencode($film);
//    echo $film;
  }

  for($i = 0; $i < 5; ++$i) {
    $html = $client->fetch($film)->results;

    if(!empty($html)) {
      break;
    } else {
    //  $client->agent = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.0 Safari/537.36";
      $film = $client->_redirectaddr;
    //  var_dump($client);
    }
  }
    

  $filmlink = $client->lastredirectaddr;

  if(empty($html)) {
    var_dump($client);
    die("Oh");
  }
  libxml_use_internal_errors(true);
  $dom = new DOMDocument();
  if(!$dom->loadHTML($html))
    die("Fail on loading");

  $xpath = new DOMXPath($dom);
  $film = new Film($xpath);

  $sql = "INSERT INTO filmlist (img_link, film_url, name, englishName, directors, year, countries, genres, rating, imdb, runtime) VALUES ('".$film->img."','".$filmlink."','".$film->name."','".$film->englishName."','".$film->directors."','".$film->year."','".$film->countries."','".$film->genres."','".$film->rating."','".$film->imdb."','".$film->runtime."')";

//  echo $sql;

  $conn->query($sql);
  $id = $conn->insert_id;

  echo "<tr>
          <td width='2%'><input class='editbox' type='checkbox' value='".$id."'>
          <td><img src='".$film->img."' height=120 width=auto></td>
          <td><a href='".$filmlink."' target='_blank'>".$film->name."</a><br>(".$film->englishName.")</td>
          <td>".$film->directors."</td>
          <td>".$film->year."</td>
          <td>".$film->countries."</td>
          <td>".$film->genres."</td>
          <td>".$film->rating."</td>
          <td>".$film->imdb."</td>
          <td>".$film->runtime."</td>
        </tr>";
}