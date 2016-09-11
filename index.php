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
            <option value="/" <?php if($tablename == 'filmlist') echo 'selected'; ?> >Filmlist</option>
            <option value="/?watched" <?php if($tablename == 'watched') echo 'selected'; ?> >Watched</option>
          </select>
        </div>
      </form>

      <form class="navbar-form navbar-right">
        <div class="form-group">
          <input id="searchinput" type="text" placeholder="Search, add or replace" class="form-control" data-table= <?php echo "'$tablename'"; ?> >
        </div>
      </form>
    </div>
  </nav>

  <!-- Bottom navbar -->
  <!-- under construction
  <nav class="navbar navbar-default navbar-fixed-bottom">
    <div class="container-fluid">
      <form class="navbar-form navbar-left">
        <div class="checkbox">
          <label><input type="checkbox"> Check all</label>
        </div>
      </form>
    </div>
  </nav> -->

  <div class="container-fluid" style="margin-top: 70px">
    <table id='filmtable' class='table table-striped table-hover'>
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
      <tbody id='tablebody'>
      </tbody>
    </table>
    <div class="loader"></div>
  </div>
  
  </body>
</html>
