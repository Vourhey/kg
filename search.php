<?php 
  // search?query=<query>
  // if there's no query, display all films

require_once ('core.php');
global $conn;

$table = $_GET['table'];

errorLog('900', $table, "search.php", 8);

if(empty($_GET['query'])) {
  $sql = "SELECT * FROM `$table` LIMIT 0,50"; // 
  $result = $conn->query($sql);

  if($result->num_rows > 0) { 
  
    while($row = $result->fetch_assoc()) {
      $film = new Film;
      $film->fromRow($row);

      printRow($film);
    }

  } else {
    echo "<tr id='nofilmstr'><td colspan='10'>There's no films!</td></tr>";
  }
} else {  // there's query
  $query = $conn->real_escape_string($_GET['query']);
  errorLog('909', $query, 'search.php', 26);

  $sql = "SELECT * FROM `testdb` WHERE name LIKE '%$query%' OR englishName LIKE '%$query%'".
                " OR countries LIKE '%$query%' OR genres LIKE '%$query%' LIMIT 0, 50";

  $result = $conn->query($sql);
  //errorLog("search", $conn->error, "search.php", 16);
  if($result && $result->num_rows > 0) { 
  
    while($row = $result->fetch_assoc()) {
      $film = new Film;
      $film->fromRow($row);

      printRow($film);
    }

  } else {
    echo "<tr id='nofilmstr'><td colspan='10'>There's no such film!</td></tr>";

    // try to get from kinopoisk.ru
    
  }
}