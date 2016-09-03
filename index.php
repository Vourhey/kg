<?php 
  require_once('core.php');

  if(isset($_GET['watched'])) {
    $tablename = 'watched';
  } else {
    $tablename = 'filmlist';
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>KinopoiskGrabber</title>

    <link rel="stylesheet" href="css/main.css">

    <script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>
    <script src="js/main.js"></script>
  </head>
  <body>

  <select onchange="top.location=this.value">
    <option value="index.php" <?php if($tablename == 'filmlist') echo 'selected'; ?> >Filmlist</option>
    <option value="index.php?watched" <?php if($tablename == 'watched') echo 'selected'; ?> >Watched</option>
  </select>

  <button onclick="deleteFilms('<?=$tablename?>')">delete</button> 

  <?php if($tablename == 'filmlist') { ?>
    <button onclick="moveFilm()">move</button>
  <?php } ?>

  <textarea id="addfilmstextarea" rows=1 cols=80></textarea> 
  <button onclick="addFilms('<?=$tablename?>')">Add</button> 
  <button onclick="replaceFilm('<?=$tablename?>')">Replace</button><br><br>

  <?php
    printAll($tablename);

    $conn->close();
  ?>
  </body>
</html>
