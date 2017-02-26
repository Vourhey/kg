<?php

session_start();
require_once ('errorhandler.php');

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
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
  </head>
  <body>

  <!-- side navigation menu -->
  <div id="sidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeSidenav()">&times;</a>
    <a href="#">Watched</a>
    <a href="import_export.php">Import/Export</a>
    <a href="#">Statistics</a>
  </div>

  <!-- Top navbar -->
  <nav id="top-navbar" class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">

        <!-- <div class="collapse navbar-collapse "> -->
        <div>
          <ul class="nav navbar-nav">
            <li class="open-menu-btn">
              <span class="glyphicon glyphicon-menu-hamburger" onclick="openSidenav()"></span>
            </li>
          </ul>
        <!-- 
        <?php // if($is_entered) { ?>
          <span class="glyphicon glyphicon-menu-hamburger" onclick="openSidenav()"></span>
        <?php // } ?> -->
<!--
        <div class="col-xs-8 search">
          <input type="text" class="search-textbox" placeholder="Search or add">
        </div> -->

          <form class="navbar-form navbar-left" role="search" style="width: 80%; margin-left: auto; margin-right: auto; padding: 0px;">
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Search">
            </div>
          </form>

          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>Login</b> <span class="caret"></span></a>
              <ul id="login-dp" class="dropdown-menu">
                <li>
                  <div class="row">
                    <div class="col-md-12">
                      <form class="form" role="form" method="post" action="login.php" accept-charset="UTF-8" id="login-nav">
                        <div class="form-group">
                          <label class="sr-only" for="input-email">Email address</label>
                          <input type="email" class="form-control" id="input-email" placeholder="Email address" required>
                        </div>
                        <div class="form-group">
                          <label class="sr-only" for="exampleInputPassword2">Password</label>
                          <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Password" required>
                          <div class="help-block text-right"><a href="">Forget the password ?</a></div>
                        </div>
                        <div class="form-group">
                          <button type="submit" class="btn btn-primary btn-block">Sign in</button>
                        </div>
                        <div class="checkbox">
                          <label>
                            <input type="checkbox"> keep me logged-in
                          </label>
                        </div>
                      </form>
                    </div>
                    <div class="bottom text-center">
                      New here? <a href="register.php"><strong>Register</strong></a>
                    </div>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
<!--
        <div class="col-xs-3" style="text-align: right;">
          <div style="display: inline-block; margin: 5px;">

          <?php if($is_entered) { ?>
            <a href="logout.php" class="btn btn-default">Log out</a>
          <?php } else { ?>
            <a href="register.php" class="btn btn-default">Register</a>
            <a href="login.php" class="btn btn-default">Log in</a>
          <?php } ?>
          </div>
        </div> -->

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
  <nav id="bottom-navbar" class="navbar navbar-default navbar-fixed-bottom text-center">
    <div class="container-fluid">
      <ul class="pagination">

      </ul>
    </div>
  </nav>


  </body>
</html>
