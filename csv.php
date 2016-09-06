<?php

// kg.test/csv.php?method=(import|export)&table=(tablename)&file=(filename)

require_once ('dbconnect.php');

$method = $_GET['method'];
$tablename = $_GET['table'];
$filename = $_GET['file'];

if($method == 'export') {
  $q_export = "SELECT * FROM $tablename";
  $export = fopen($filename, "w") or die("Can't open file $filename");

  $result = $conn->query($q_export);

  while($row = $result->fetch_assoc()) {
    $fields = array($row['img_link'], $row['film_url'], 
                    $row['name'], $row['englishName'], 
                    $row['directors'], $row['year'],
                    $row['countries'], $row['genres'], 
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
    $row[3] = $conn->real_escape_string($row[3]);
    $row[8] = str_replace(',', '.', $row[8]);
    $row[9] = str_replace(',', '.', $row[9]);
    $row = implode("','", $row);

    $sql = "INSERT INTO `$tablename` (img_link,film_url,name,englishName,directors,year,countries,genres,rating,imdb,runtime) VALUES ('".$row."')";
    $conn->query($sql);
  }
  fclose($import);
}
