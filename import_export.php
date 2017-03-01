<?php

session_start();

if(!isset($_SESSION['userid']) && !isset($_SESSION['username'])) {
  header('location: index.php');
  exit;
}

?>

<html>
<head>
  <title>Import/Export</title>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/main.css">
  <script src="js/jquery-3.1.0.min.js"></script>

  <script>

    function processKPids() {
      var list = $('#idlist').val().split('\n');

    //  console.log(list);

      sendRequest(list);
    }

    function sendRequest(list) {
      console.log("Length: " + list.length);
      if(list.length > 0) {
        item = list.pop();
        console.log(item);
        $.post('addfilm.php', { kpid: item }, function(data) {
          // insert into table 
          $('#tablebody').html(data + $('#tablebody').html());
          sendRequest(list);
        })
      }

    }

  </script>
</head>
<body>
<!--  <textarea id="idlist"></textarea><br>
  <button onclick="processKPids()">Add kpids</button><br>

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
  </table> -->

  <script type="text/javascript">
    $(function() {
      $('#radio-file').click(function() {
        $('#input-select-file-to-upload').show();
        $('#textarea-list-of-films').hide();
        $('#div-manual').hide();
      });

      $('#radio-list').click(function() {
        $('#input-select-file-to-upload').hide();
        $('#textarea-list-of-films').show();
        $('#div-manual').hide();
      });

      $('#radio-manual').click(function() {
        $('#input-select-file-to-upload').hide();
        $('#textarea-list-of-films').hide();
        $('#div-manual').show();
      });
    });
  </script>

  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <h2>Import</h2>

        <form action="addfilm.php" method="post" enctype="multipart/form-data">
          <input id="radio-file" type="radio" name="import-radio" value="file" checked> Файл 
          <input id="input-select-file-to-upload" type="file" name=""><br>

          <input id="radio-list" type="radio" name="import-radio" value="list"> Список<br>
          <textarea id="textarea-list-of-films" style="width: 100%; display: none;"></textarea><br>

          <input id="radio-manual" type="radio" name="import-radio" value="manual">В ручную<br>
          <div id="div-manual" style="display: none;">
            <input type="file" name="poster"><br>
            <input type="text" name="name" placeholder="Name"><br>
            <input type="text" name="original_name" placeholder="Original name"><br>
            <input type="text" name="directors" placeholder="Directors"><br>
            <input type="text" name="year" placeholder="Year"><br>
          </div>
          <input type="submit" name="submit"><br>
        </form>
      </div>

      <div class="col-sm-6">
        <h2>Export</h2>

  <!--      <script type="text/javascript">
          function runexample() {

            $.post("longtask.php", function(data) {
              $('#example_div').html($('#example_div').html() + data);
            });
          }
        </script>

        <button onclick="runexample()">Go</button>
        <div id="example_div">
          
        </div> -->
      </div>
    </div>
  </div>

</body>
</html>
