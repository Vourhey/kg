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
  <textarea id="idlist"></textarea><br>
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
  </table>
</body>
</html>
