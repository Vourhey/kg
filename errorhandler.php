<?php
// require_once('dbconnect.php');
// require_once('Film.class.php');

function errorLog($errno, $errstr, $errfile, $errline) {
  $logfile = fopen("log.txt", 'a');
  $timestamp = date("H:i:s");
  fwrite($logfile, "$timestamp Error [$errno]: $errstr on line $errline in $errfile\n");
  fclose($logfile);
}

set_error_handler("errorLog");

/*
function printRow($film, $button = null) {
  $out = "<tr><td class='tdclickable'>"; 

  if($button) {
    $out .= "<button class='plusbutton' value='".$film->kpid."'>".
         "<span class='glyphicon glyphicon-plus-sign'></span></button>";
  } else {
    $out .= "<input class='editbox' type='checkbox' value='".$film->kpid."'>";
  }

  $out .= "</td><td><img src='".$film->img_link."' class='poster'></td>".
       "<td><a href='".$film->filmlink."' target='_blank'>".$film->name."</a>";
  
  if(!empty($film->englishName)) {
    $out .= "<br>(".$film->englishName.")";
  }
  
  $out .= "</td><td>".$film->directors."</td><td>".$film->year."</td>".
          "<td>".$film->countries."</td><td>".$film->genres."</td>".
          "<td>".$film->rating."</td><td>".$film->imdb."</td><td>".$film->runtime."</td></tr>";

  return $out;
}

function printAll($tablename) {
  global $conn;
  $sql = "SELECT * FROM $tablename";
  if($tablename == "filmlist") {
    $sql .= " ORDER BY name";
  }

  $sql .= " LIMIT 0,50";

  $result = $conn->query($sql);
  if($result->num_rows > 0) { 
    
    while($row = $result->fetch_assoc()) {
      $film = new Film;
      $film->fromRow($row);

      printRow($film);
    }

  } else {
    echo "<tr id='nofilmstr'><td colspan='10'>You don't have watched films!</td></tr>";
  }
}*/

