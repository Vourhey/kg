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

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css">

    <script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>
    <script src="js/main.js"></script>
  </head>
  <body>

  <nav class="navbar navbar-fixed-top">
    <div class="container">
      <div class="form-group">
        <div class="col-sm-2">
          <select class="form-control" onchange="top.location=this.value">
            <option value="/" <?php if($tablename == 'filmlist') echo 'selected'; ?> >Filmlist</option>
            <option value="/?watched" <?php if($tablename == 'watched') echo 'selected'; ?> >Watched</option>
          </select>
        </div>
        <div class="col-sm-2">
          <span class="btn-group">
            <button type="button" class="btn" onclick="deleteFilms('<?=$tablename?>')">delete</button> 

            <?php if($tablename == 'filmlist') { ?>
              <button class="btn" onclick="moveFilm()">move</button>
            <?php } ?>
          </span>
        </div>
        <div class="col-sm-8">
          <form class="form-inline">
            <div class="form-group">
              <textarea id="addfilmstextarea" row="1" class="form-control"></textarea>
            </div>
            <div class="btn-group" style="vertical-align: top;">
              <button class="btn" onclick="addFilms('<?=$tablename?>')">Add</button> 
              <button class="btn" onclick="replaceFilm('<?=$tablename?>')">Replace</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </nav>

  

  <br><br>

  <?php
    printAll($tablename);

    $conn->close();
  ?>
  </body>
</html>
