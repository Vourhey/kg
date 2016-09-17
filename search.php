<?php 
  // search?query=<query>
  // if there's no query, display all films

require_once ('core.php');
global $conn;

$table = $_GET['table'];

errorLog('900', $table, __FILE__, __LINE__);

if(empty($_GET['query'])) {
  $sql = "SELECT * FROM `$table` ";
  if($table == 'filmlist') {
    $sql .= "ORDER BY name";
  } else if($table == 'watched') {
    $sql .= "ORDER BY id DESC";
  }
  $sql .= " LIMIT 0,50"; 
   
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
  errorLog('909', $query, __FILE__, __LINE__);

  $sql = "SELECT * FROM `$table` WHERE name LIKE '%$query%' OR englishName LIKE '%$query%'".
                " OR countries LIKE '%$query%' OR genres LIKE '%$query%'";
  if($table == 'filmlist') {
    $sql .= " ORDER BY name";
  }
  $sql .= " LIMIT 0, 50";

  $result = $conn->query($sql);
  errorLog("search", $sql, __FILE__, __LINE__);
  if($result && $result->num_rows > 0) { 
  
    while($row = $result->fetch_assoc()) {
      $film = Film::fromRow($row);

      printRow($film);
    }

  } else {
    // try to get from kinopoisk.ru
    errorLog("9009", "getting from kp", __FILE__, __LINE__);
    $film = new Film($query);

    if($film->error != "") {
      echo "<tr><td colspan='10'>No film</td></tr>";
    } else {
      $sql = "INSERT INTO `temp` (kpid, name, englishName, directors, year, ".
             "countries, genres, rating, imdb, runtime) VALUES ('".$film->kpid.
             "','".$film->name."','".$conn->real_escape_string($film->englishName).
             "','".$film->directors."','".$film->year."','".$film->countries.
             "','".$film->genres."','".$film->rating."','".$film->imdb."','".$film->runtime."')";

      $conn->query($sql); // add to temp table, so there'll be possible to move it to filmlist 

      printRow($film, "plus");  
    }    
  }
}
