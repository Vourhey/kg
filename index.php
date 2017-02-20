<?php

session_start();
require_once ('core.php');

$is_entered = false;

if(isset($_SESSION['userid']) && isset($_SESSION['username'])) {
  $is_entered = true;
}

errorLog('31', "is_entered = $is_entered", "index.php", 11);

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

  <!-- side navigation menu -->
  <div id="sidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="#">Watched</a>
    <a href="#">Import/Export</a>
  </div>

  <!-- Top navbar -->
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
      <div class="row">

        <div class="col-xs-1 open-menu-btn">
        <?php if($is_entered) { ?>
          <span class="glyphicon glyphicon-menu-hamburger" onclick="openNav()"></span>
        <?php } ?>
        </div>

        <div class="col-xs-8 search">
          <input type="text" class="search-textbox" placeholder="Search or add">
        </div>

        <div class="col-xs-3" style="text-align: right;">
          <div style="display: inline-block; margin: 5px;">

          <?php if($is_entered) { ?>
            <a href="logout.php" class="btn btn-default">Log out</a>
          <?php } else { ?>
            <a href="register.php" class="btn btn-default">Register</a>
            <a href="login.php" class="btn btn-default">Log in</a>
          <?php } ?>
          </div>
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

  <!-- Bottom navbar -->
  <nav class="navbar navbar-default navbar-fixed-bottom text-center">
    <div class="container-fluid">
      <ul class="pagination">

      </ul>
    </div>
  </nav>


  </body>
</html>
