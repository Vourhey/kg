<?php

// /csv.php?method=(import|export)&table=(tablename)&file=(filename)

require_once ('dbconnect.php');

$method = $_GET['method'];
$tablename = $_GET['table'];
$filename = $_GET['file'];

if($method == 'export') {
  $q_export = "SELECT * FROM `$tablename`";
  $export = fopen($filename, "w") or die("Can't open file $filename");

  $result = $conn->query($q_export);

  while($row = $result->fetch_assoc()) {
    $fields = array($row['kpid'], $row['name'], $row['englishName'], 
                    $row['directors'], $row['year'], $row['countries'], $row['genres'], 
                    str_replace('.', ',', $row['rating']), 
                    str_replace('.', ',', $row['imdb']), $row['runtime']);

    fputcsv($export, $fields);
  }
  echo "<a href='".$filename."'>Download</a>";
  fclose($export);
} else if($method == 'import') {
  $import = fopen($filename, "r") or die("Can't open file $filename");

  while( !feof($import) ) {
    $row = fgetcsv($import);
    $row[2] = $conn->real_escape_string($row[2]);
    $row[7] = str_replace(',', '.', $row[7]);
    $row[8] = str_replace(',', '.', $row[8]);

    $sql = "UPDATE `$tablename` SET name='".$row[1].
           "', englishName='".$row[2]."', directors='".$row[3]."', year='".$row[4].
           "', countries='".$row[5]."', genres='".$row[6]."', rating='".$row[7].
           "', imdb='".$row[8]."', runtime='".$row[9]."' WHERE kpid=".$row[0];
    $result = $conn->query($sql);

    if($conn->affected_rows == 0) {
      $row = implode("','", $row);
      $sql = "INSERT INTO `$tablename` (kpid, name, englishName, directors, year, countries, genres, rating, imdb, runtime) VALUES ('".$row."')";
      $result = $conn->query($sql);
    }
  }
  fclose($import);
}
