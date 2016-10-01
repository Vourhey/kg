<?php 
  require_once('core.php');

  if(isset($_GET['watched'])) {
    $tablename = 'watched';
  } else {
    $tablename = 'filmlist';
  }
  //$tablename = 'testdb';
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>KinopoiskGrabber</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">

    <script src="js/jquery-3.1.0.min.js"></script>
    <script src="js/main.js"></script>
  </head>
  <body>

  <!-- Top navbar -->
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
      <form class="navbar-form navbar-left">
        <div class="form-group">
          <select class="form-control" onchange="window.location.href = this.value">
            <option value="index.php" <?php if($tablename == 'filmlist') echo 'selected'; ?> >Filmlist</option>
            <option value="?watched" <?php if($tablename == 'watched') echo 'selected'; ?> >Watched</option>
          </select>
          <div id="editButtons" class="btn-group" style="display: none;">
          <?php if($tablename == 'filmlist') { ?>
            <button id="movebtn" class="btn btn-default">Move</button>
          <?php } ?>
            <button id="deletebtn" class="btn btn-default">Delete</button>  
          </div>
        </div>
      </form>

      <form class="navbar-form navbar-right">
        <div class="form-group">
          <input id="searchinput" type="search" placeholder="Search, add or replace" class="form-control" data-table= <?php echo "'$tablename'"; ?> >
        </div>
      </form>
    </div>
  </nav>

  <!-- Bottom navbar -->
  <nav class="navbar navbar-default navbar-fixed-bottom text-center">
    <div class="container-fluid">
      <div class="navbar-form navbar-left">
        <button id='checkallbtn' class='btn btn-default' data-checked='false'>
          <span class="glyphicon glyphicon-unchecked"></span> Check All
        </button>
      </div>
      <ul class="pagination">
        <!-- fills from js -->
      </ul>
      <div class="navbar-form navbar-right">
        <div class="btn-group">
          <button id="scrollDown" class="btn btn-default">Down</button>
          <button id="scrollUp" class="btn btn-default">Up</span></button>  
        </div>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <table id='filmtable' class='table table-striped table-hover'>
      <thead class="text-center">
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
      <tbody id='tablebody'>
      </tbody>
    </table>
    <div class="loader"></div>
  </div>
  
  </body>
</html>
