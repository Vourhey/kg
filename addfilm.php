<?php

session_start();

if(!isset($_SESSION['userid']) && !isset($_SESSION['username'])) {
  header('location: index.php');
  exit;
}

require_once 'errorhandler.php';
require_once 'Snoopy.class.php';
require_once 'Film.class.php';

function getXPath($item) {
  $client = new Snoopy();
  $client->referer = "http://kinopoisk.ru/";
  $client->agent = "Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0";

  $url = "https://www.kinopoisk.ru/film/$item/";
  $direct = true;
  /*
  if(substr($query, 0, 7) != "http://" && substr($query, 0, 8) != "https://") {
    $url = "https://www.kinopoisk.ru/index.php?first=yes&kp_query=".urlencode($query);
    $direct = false;
  } else {
    $url = $query;
    $direct = true;
  } */

  errorLog('2', $url, __FILE__, __LINE__);

  $html = $client->fetch($url)->results;

  // TODO если изначально вставлена ссылка, то lastredirectaddr тоже == ''
  if(!$direct && $client->lastredirectaddr == '') {
    errorLog('2', "Can't fetch film", __FILE__, __LINE__);
   // errorLog('2', var_export($client, true), __FILE__, __LINE__);
  } else {
    if($client->error) {
      errorLog("666", $client->error, __FILE__, __LINE__);
    }

    if(empty($html)) {
      // hack against 302 error
      $url = $client->_redirectaddr;
      $html = $client->fetch($url)->results;
    }

    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    if(!$dom->loadHTML($html))
      errorLog(35698, "Fail on loading", __FILE__, __LINE__);

   // errorLog(785123, var_export($dom, true), __FILE__, __LINE__);

    $xpath = new DOMXPath($dom);
    $xpath->document->documentURI = $url;
    return $xpath;
  }
}

if(!isset($_POST['kpid'])) {
  exit;
}

$item = $_POST['kpid'];

errorLog(909090, $item, 'addfilm.php', 18);

$xpath = getXPath($item);

//errorLog(909090, var_export($xpath->document->documentURI, true), __FILE__, __LINE__);
$film = new Film();
$film->fillFromXpath($xpath);

echo "<tr><td></td><td><img src='posters/".$film->id.".jpg'></td>
        <td>".$film->name."</td>
        <td>".$film->directors."</td>
        <td>".$film->year."</td>
        <td>".$film->countries."</td>
        <td>".$film->genres."</td>
        <td>".$film->rating."</td>
        <td>".$film->imdb."</td>
        <td>".$film->runtime." мин.</td></tr>";
