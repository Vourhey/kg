<?php 
  // search?query=<query>

require_once ('core.php');
global $conn;

if(empty($_GET['query'])) {
  $sql = "SELECT * FROM `testdb` LIMIT 0,50"; // 
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
  $query = $_GET['query'];

  $sql = "SELECT * FROM `testdb` WHERE name LIKE '%$query%' OR englishName LIKE '%$query%'".
                " OR countries LIKE '%$query%' OR genres LIKE '%$query%' LIMIT 0, 50";

  errorLog("search", $sql, "search.php", 13);

  $result = $conn->query($sql);
  errorLog("search", $conn->error, "search.php", 16);
  if($result->num_rows > 0) { 
  
    while($row = $result->fetch_assoc()) {
      $film = new Film;
      $film->fromRow($row);

      printRow($film);
    }

  } else {
    echo "<tr id='nofilmstr'><td colspan='10'>There's no such films!</td></tr>";
  }
}