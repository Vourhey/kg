<?php

require_once('core.php');

$method = $_GET['method'];

if($method == 'moveToFilmlist') {
  $sql = "SELECT * FROM `temp` WHERE kpid=".$_GET['ids'];
  $result = $conn->query($sql);

  $film = Film::fromRow($result->fetch_assoc());

  $sql = "INSERT INTO `filmlist` (kpid, name, englishName, directors, year, countries, ".
         "genres, rating, imdb, runtime) VALUES ('".$film->kpid."','".$film->name.
         "','".$conn->real_escape_string($film->englishName)."','".$film->directors.
         "','".$film->year."','".$film->countries."','".$film->genres."','".$film->rating.
         "','".$film->imdb."','".$film->runtime."')";
  $conn->query($sql);

  $sql = "DELETE FROM `temp` WHERE kpid=".$_GET['ids'];
  $conn->query($sql);

  printRow($film);
} else if($method == 'move') {
  $sql = "SELECT * FROM filmlist WHERE kpid in(".$_GET['ids'].")";
  errorLog('77', $sql, "edit.php", 26);
  $result = $conn->query($sql);

  errorLog('77', "Result ".$conn->error, "edit.php", 29);

  if($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $film = Film::fromRow($row);

      $sql = "INSERT INTO watched (kpid, name, englishName, directors, year, countries, genres, ".
             "rating, imdb, runtime) VALUES ('".$film->kpid."','".$film->name.
             "','".$conn->real_escape_string($film->englishName)."','".$film->directors.
             "','".$film->year."','".$film->countries."','".$film->genres."','".$film->rating.
             "','".$film->imdb."','".$film->runtime."')";
      $conn->query($sql);
    }

    $sql = "DELETE FROM filmlist WHERE kpid in (".$_GET['ids'].")";
    $conn->query($sql);
  }
} else if($method == 'delete') {
  $tablename = $_GET['table'];  
  errorLog('77', "Delete films from ".$tablename, "edit.php", 48);  
  $sql = "DELETE FROM `$tablename` WHERE kpid in (".$_GET['ids'].")";
  $conn->query($sql);
}
