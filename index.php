<?php

session_start();
require_once ('errorhandler.php');

$is_entered = false;

if(isset($_SESSION['userid']) && isset($_SESSION['email'])) {
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

<?php if($is_entered) { ?>
  <!-- side navigation menu -->
  <div id="sidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeSidenav()">&times;</a>
    <a href="#">Watched</a>
    <a href="import_export.php">Import/Export</a>
  </div>
<?php } ?>

  <!-- Top navbar -->
  <nav id="top-navbar" class="navbar navbar-default navbar-fixed-top text-center">
    <div class="container-fluid">

        <div>
        <?php if($is_entered) { ?>
          <ul class="nav navbar-nav">
            <li class="open-menu-btn">
              <span class="glyphicon glyphicon-menu-hamburger" onclick="openSidenav()"></span>
            </li>
          </ul>
        <?php } ?>

          <form class="navbar-form" role="search" style="display: inline-block; width: 60%;">
            <div class="form-group" style="width: 100%;">
              <input type="text" class="form-control" placeholder="Search" style="width: 100%;">
            </div>
          </form>

          <ul class="nav navbar-nav navbar-right">

          <?php if($is_entered) { ?>
          
            <li><a href="logout.php"><strong>Log out</strong></a></li>

          <?php } else { ?>

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><strong>Login</strong> <span class="caret"></span></a>
              <ul id="login-dp" class="dropdown-menu">
                <li>
                  <div class="row">
                    <div class="col-md-12">

                      <form class="form" role="form" method="post" action="login.php" accept-charset="UTF-8" id="login-nav">
                        <div class="form-group">
                          <label class="sr-only" for="input-email">Email address</label>
                          <input type="email" class="form-control" id="input-email" placeholder="Email address" name="email" required>
                        </div>
                        <div class="form-group">
                          <label class="sr-only" for="input-password">Password</label>
                          <input type="password" class="form-control" id="input-password" placeholder="Password" name="password" required>
                         <!-- <div class="help-block text-right"><a href="">Forget the password ?</a></div> -->
                        </div>
                        <div class="form-group">
                          <button type="submit" class="btn btn-primary btn-block">Sign in</button>
                        </div>
                        
                        <!-- <div class="checkbox">
                          <label>
                            <input type="checkbox"> keep me logged-in
                          </label>
                        </div> -->

                      </form>

                    </div>
                    <div class="bottom text-center">

                      New here? <a href="register.php"><strong>Register</strong></a>

                    </div>
                  </div>
                </li>
              </ul>
            </li>

          <?php } ?>

          </ul>

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
  <nav id="bottom-navbar" class="navbar navbar-default navbar-fixed-bottom text-center">
    <div class="container-fluid">
      <ul class="pagination">

      </ul>
    </div>
  </nav>


  </body>
</html>
