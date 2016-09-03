<?php

require_once('core.php');

$method = $_GET['method'];
$tablename = $_GET['table'];

if($method == 'delete') {
  $sql = "DELETE FROM $tablename WHERE id in (".$_GET['ids'].")";
  $conn->query($sql);

  printAll($tablename);
}

if($method == 'move') {
  $logfile = fopen("log.txt", 'a');
  $timestamp = date("H:i:s");
  fwrite($logfile, "$timestamp move method\n");

  $sql = "SELECT * FROM filmlist WHERE id=".$_GET['ids'];
  
  $film = new Film;

  $timestamp = date("H:i:s");
  fwrite($logfile, "$timestamp created film\n");

  $row = $conn->query($sql);

  $timestamp = date("H:i:s");
  fwrite($logfile, "$timestamp got ".print_r($row,1)."\n");

  $film->fromRow($row->fetch_assoc());

  
  $timestamp = date("H:i:s");
  fwrite($logfile, "$timestamp $sql\n$film\n");
  fclose($logfile);

  $sql = "INSERT INTO watched (img_link, film_url, name, englishName, directors, year, countries, genres, rating, imdb, runtime) VALUES ('".$film->img_link."','".$film->filmlink."','".$film->name."','".$film->englishName."','".$film->directors."','".$film->year."','".$film->countries."','".$film->genres."','".$film->rating."','".$film->imdb."','".$film->runtime."')";
  $conn->query($sql);

  $sql = "DELETE FROM filmlist WHERE id=".$_GET['ids'];
  $conn->query($sql);
}

$conn->close();