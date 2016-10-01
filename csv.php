<?php

// /csv.php?method=(import|export)&table=(tablename)&file=(filename)
// if method, table, file are not set
// backup both tables 

require_once ('dbconnect.php');
$conn = Database::getConnection();

function exportTable($table, $file) {
  global $conn;
  $export = fopen($file, "w") or die("Can't open file $file");
  
  $q_export = "SELECT * FROM `$table`";
  if($table == 'filmlist') {
    $q_export .= " ORDER BY name";
  }
  $result = $conn->query($q_export);

  while($row = $result->fetch_assoc()) {
    $fields = array($row['kpid'], $row['name'], $row['englishName'], 
                    $row['directors'], $row['year'], $row['countries'], $row['genres'], 
                    str_replace('.', ',', $row['rating']), 
                    str_replace('.', ',', $row['imdb']), $row['runtime']);

    fputcsv($export, $fields);
  }

  fclose($export);
}

function importFromFile($table, $file) {
  global $conn;
  $import = fopen($file, "r") or die("Can't open file $filename");

  while( !feof($import) ) {
    $row = fgetcsv($import);
    $row[2] = $conn->real_escape_string($row[2]);
    $row[7] = str_replace(',', '.', $row[7]);
    $row[8] = str_replace(',', '.', $row[8]);

    $sql = "UPDATE `$table` SET name='".$row[1].
           "', englishName='".$row[2]."', directors='".$row[3]."', year='".$row[4].
           "', countries='".$row[5]."', genres='".$row[6]."', rating='".$row[7].
           "', imdb='".$row[8]."', runtime='".$row[9]."' WHERE kpid=".$row[0];
    $result = $conn->query($sql);

    if($conn->affected_rows == 0) {
      $row = implode("','", $row);
      $sql = "INSERT INTO `$table` (kpid, name, englishName, directors, year, countries, genres, rating, imdb, runtime) VALUES ('".$row."')";
      $result = $conn->query($sql);
      // TODO download the poster to posters/ folder
    }
  }
  fclose($import);
}

$method = $_GET['method'];
$tablename = $_GET['table'];
$filename = $_GET['file'];

if(!isset($method, $tablename, $filename)) { // export all
  exportTable('filmlist', dirname(__FILE__).'/backup/filmlist_'.date("d_m_Y").'.csv');
  exportTable('watched', dirname(__FILE__).'/backup/watched_'.date("d_m_Y").'.csv');
} else if($method == 'export') {
  exportTable($tablename, $filename);
} else if($method == 'import') {
  importFromFile($tablename, $filename);
}
