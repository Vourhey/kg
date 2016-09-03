<?php
require_once('dbconnect.php');
require_once('Film.class.php');

function errorLog($errno, $errstr, $errfile, $errline) {
  $logfile = fopen("log.txt", 'a');
  $timestamp = date("H:i:s");
  fwrite($logfile, "$timestamp Error [$errno]: $errstr on line $errline in $errfile\n");
  fclose($logfile);
}

set_error_handler("errorLog");

function printRow($film) {
  echo "<tr>
          <td><input class='editbox' type='checkbox' value='".$film->id."'></td>
          <td><img src='".$film->img_link."' class='poster'></td>
          <td><a href='".$film->filmlink."' target='_blank'>".$film->name."</a>";
      if(!empty($film->englishName)) {
        echo "<br>(".$film->englishName.")";
      }
   echo   "</td>
          <td>".$film->directors."</td>
          <td>".$film->year."</td>
          <td>".$film->countries."</td>
          <td>".$film->genres."</td>
          <td>".$film->rating."</td>
          <td>".$film->imdb."</td>
          <td>".$film->runtime."</td>
        </tr>";
}

function printAll($tablename) {
  global $conn;
  $sql = "SELECT * FROM $tablename";
  if($tablename == "filmlist") {
    $sql .= " ORDER BY name";
  }

//  $sql .= " LIMIT 0,10";

  $result = $conn->query($sql);
  if($result->num_rows > 0) { 
    echo "<table id='filmtable'>
      <thead>
        <tr>
          <td></td>
          <td>Постер</td>
          <td>Название</td>
          <td>Режисер</td>
          <td>Год</td>
          <td>Страна</td>
          <td>Жанр</td>
          <td>Рейтинг</td>
          <td>IMDb</td>
          <td>Время</td>
        </tr>
      </thead>
      <tbody>";
    while($row = $result->fetch_assoc()) {
      $film = new Film;
      $film->fromRow($row);

      printRow($film);
    }
    echo "</tbody></table>";
  } else {
    echo "<table id='filmtable'><tbody><tr id='nofilmstr'><td>You don't have watched films!</td></tr></tbody></table>";
  }
}