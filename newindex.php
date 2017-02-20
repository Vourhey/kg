<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>KinopoiskGrabber</title>

    <script src="js/jquery-3.1.0.min.js"></script>
    <script src="js/main.js"></script>

    <link rel="stylesheet" href="css/bootstrap.min.css">
<!--     <link rel="stylesheet" href="css/main.css"> -->

<style>
  .open-menu-btn {
    display: inline-block;
    vertical-align: middle;
  }

  .open-menu-btn span {
    padding: 15px;
    cursor: pointer;
  }
  
  .search {
    display: inline-block;
    text-align: center;
  }

  .search input {
    width: 100%;
    margin: 5px auto;
    height: 40px;
  }

  #filmtable {
  margin-top: 70px;
  margin-bottom: 60px;
}

.table tbody>tr>td { 
  vertical-align: middle;
  text-align: center;
}

td:last-child {
  min-width: 130px;
} 

thead {
  font-weight: bold;
}
</style>

  </head>
  <body>

  <!-- Top navbar -->
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
      <div class="row">
        <div class="col-xs-2 open-menu-btn">
          <span class="glyphicon glyphicon-menu-hamburger"></span>
        </div>
        <div class="col-xs-8 search">
          <input type="text" class="search-textbox" placeholder="Search or add">
        </div>
        <div class="col-xs-offset-2"></div>
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
        <li class="active"><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">1</a></li>
        <li><a href="#">1</a></li>
      </ul>
    </div>
  </nav>

  </body>
</html>
