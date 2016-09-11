<?php 
  // search?query=<query>
  // if there's no query, display all films

require_once ('core.php');
global $conn;

$table = $_GET['table'];

errorLog('900', $table, "search.php", 8);

if(empty($_GET['query'])) {
  $sql = "SELECT * FROM `$table` ORDER BY name LIMIT 0,50"; // 
  $result = $conn->query($sql);

  if($result->num_rows > 0) { 
  
    while($row = $result->fetch_assoc()) {
      $film = Film::fromRow($row);

      printRow($film);
    }

  } else {
    echo "<tr id='nofilmstr'><td colspan='10'>There's no films!</td></tr>";
  }
} else {  // there's query
  $query = $conn->real_escape_string($_GET['query']);
  errorLog('909', $query, 'search.php', 30);

  $sql = "SELECT * FROM `$table` WHERE name LIKE '%$query%' OR englishName LIKE '%$query%'".
                " OR countries LIKE '%$query%' OR genres LIKE '%$query%' LIMIT 0, 50";

  $result = $conn->query($sql);
  //errorLog("search", $sql, "search.php", 36);
  if($result && $result->num_rows > 0) { 
  
    while($row = $result->fetch_assoc()) {
      $film = Film::fromRow($row);

      printRow($film);
    }

  } else {
    // try to get from kinopoisk.ru
    errorLog("9009", "getting from kp", 'search.php', 48);
    $film = new Film($query);

    $sql = "INSERT INTO `temp` (kpid, name, englishName, directors, year, ".
           "countries, genres, rating, imdb, runtime) VALUES ('".$film->kpid.
           "','".$film->name."','".$conn->real_escape_string($film->englishName).
           "','".$film->directors."','".$film->year."','".$film->countries.
           "','".$film->genres."','".$film->rating."','".$film->imdb."','".$film->runtime."')";

    $conn->query($sql); // add to temp table, so there'll be possible to move it to filmlist 

    printRow($film, "plus");
  }
}
