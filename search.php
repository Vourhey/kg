<?php 
// search?query=<query>&table=<table>&page=<page>
// if there's no query, display all films
// answer
// json = {
//   pagination: [
//     1: "<li>...</li>",
//     2: "<li>...</li>",
//     ...
//     ],
//   tbody: "..."
// }

require_once ('core.php');
$conn = Database::getConnection();

$table = $_GET['table'];
$per_page = 50;
$page = 0;
if(!empty($_GET['page']) && $_GET['page'] > 0) {
  $page = $_GET['page'] - 1;
}
$start = $page * $per_page;
errorLog('pa', 'page: '.$page.' start: '.$start, __FILE__, __LINE__);

errorLog('900', $table, __FILE__, __LINE__);

if(empty($_GET['query'])) {
  $sql = "SELECT COUNT(*) FROM `$table`";
  $result = $conn->query($sql);
  $row = $result->fetch_row();
  $total_rows = $row[0];
  $num_pages = ceil($total_rows / $per_page);

  errorLog(11, $num_pages, __FILE__, __LINE__);

  $pagination = "";
  for($i = 1; $i <= $num_pages; ++$i) {
    if($i-1 == $page) {
      $pagination .= "<li class='active'><a href='#'>$i</a></li>";
    } else {
      $pagination .= "<li><a href='#' data-table='$table' data-page='$i'>$i</a></li>";
    }
  }

  $sql = "SELECT * FROM `$table` ";
  if($table == 'filmlist') {
    $sql .= "ORDER BY name";
  } else if($table == 'watched') {
    $sql .= "ORDER BY id DESC";
  }
  $sql .= " LIMIT ".$start.",".$per_page; 
   
  $result = $conn->query($sql);
  $tbody = "";
  if($result->num_rows > 0) { 
  
    while($row = $result->fetch_assoc()) {
      $film = Film::fromRow($row);

      $tbody .= printRow($film);
    }

  } else {
    $tbody = "<tr id='nofilmstr'><td colspan='10'>There's no films!</td></tr>";
  }
} else {  // there's query
  $query = $conn->real_escape_string($_GET['query']);
  errorLog('909', $query, __FILE__, __LINE__);

  $sql = "SELECT COUNT(*) FROM `$table` WHERE name LIKE '%$query%' OR englishName LIKE '%$query%'".
                " OR countries LIKE '%$query%' OR genres LIKE '%$query%'";
  errorLog("search", $sql, __FILE__, __LINE__);
  $result = $conn->query($sql);
  $row = $result->fetch_row();
  $total_rows = $row[0];
  $num_pages = ceil($total_rows / $per_page);
  errorLog('pagination', $total_rows, __FILE__, __LINE__);
  errorLog('pagination', $num_pages, __FILE__, __LINE__);

  $sql = str_replace('COUNT(*)', '*', $sql);
  if($table == 'filmlist') {
    $sql .= " ORDER BY name";
  }
  $sql .= " LIMIT ".$start.", ".$per_page;
  errorLog("search", $sql, __FILE__, __LINE__);
  $result = $conn->query($sql);
  
  if($result && $result->num_rows > 0) {
    
    $pagination = "";
    for($i = 1; $i <= $num_pages; ++$i) {
      if($i-1 == $page) {
        $pagination .= "<li class='active'><a href='#'>$i</a></li>";
      } else {
        $pagination .= "<li><a href='#' data-table='$table' data-page='$i' data-query='$query'>$i</a></li>";
      }
    }

//    errorLog('pagination', $pagination, __FILE__, __LINE__);
  
    $tbody = "";
    while($row = $result->fetch_assoc()) {
      $film = Film::fromRow($row);

      $tbody .= printRow($film);
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

      $pagination = '';
      $tbody = printRow($film, "plus");  
    }    
  }
}

$jsonanswer = array("pagination" => $pagination, "tbody" => $tbody);
echo json_encode($jsonanswer);
