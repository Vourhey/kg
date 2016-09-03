<?php

require_once ('dbconnect.php');

$q_export = "SELECT * FROM `filmlist`";
$export = fopen("export.csv", "w");

$result = $conn->query($q_export);
var_dump($result);

while($row = $result->fetch_assoc()) {
  $fields = array($row['img_link'], $row['film_url'], 
                  $row['name'], $row['englishName'], 
                  $row['directors'], $row['year'],
                  $row['countries'], $row['genres'], 
                  $row['rating'], $row['imdb'], $row['runtime']);

  fputcsv($export, $fields);
}

// $gz = fopen("export.csv", "r");
// $compressed = gzcompress(fread($gz, filesize("export.csv")), 7);

// file_put_contents("export.zip", $compressed);
