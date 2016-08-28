<?php 
 require_once('dbconnect.php');
?>

<html>
  <head>
    <meta charset="utf-8">
    <title>KinopoiskGrabber</title>

    <style>
      table {
        width: 80%;
        table-layout: fixed;
        border-collapse: collapse;
      }
      table, td {
        border: 1px solid black;
      }

      td {
        vertical-align: middle;
        text-align: center;
        padding: 2px;
        word-wrap: break-word;
      }
    </style>

    <script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>

    <script>
      function getIds() {
        var ids = $('.editbox:checked').map(function() { return this.value; }).get();
        console.log(ids);
        return ids.join(',');
      }

      function deleteFilms() {
        $.get("edit.php?method=delete&ids=" + getIds(), function(data){
          $('#filmtable > tbody').html(data);
        });
      }

      function addFilms() {
        var filmlist = $('#addfilmstextarea').val();
        console.log(filmlist);

        var filmlist = filmlist.split('\n');
        console.log(filmlist);

        filmlist.forEach(function(f) {
          $.post("add.php", {addfilm: f}, function(data) {
            var tmp = $('#filmtable > tbody').html();
            console.log(data);

            $('#filmtable > tbody').html(data + tmp);
          });
        });
      }
    </script>
  </head>
  <body>

  <button onclick="deleteFilms()">delete</button> <button>edit</button> <button>move</button>

  <textarea id="addfilmstextarea" rows=1 cols=80></textarea> 
  <button onclick="addFilms()">Add</button> <br><br>

  <?php
    if(isset($_GET['watched'])) {
      $tablename = 'watched';
    } else {
      $tablename = 'filmlist';
    }

    $sql = "SELECT * FROM $tablename ORDER BY name";
    $result = $conn->query($sql);
    if($result->num_rows > 0) { 
      echo "<table id='filmtable'><tbody>";
      while($row = $result->fetch_assoc()) {
        echo "
          <tr>
            <td width='2%'><input class='editbox' type='checkbox' value='".$row['id']."'>
            <td><img src='".$row['img_link']."' height=120 width=auto></td>
            <td><a href='".$row['film_url']."' target='_blank'>".$row['name']."</a><br>(".$row['englishName'].")</td>
            <td>".$row['directors']."</td>
            <td>".$row['year']."</td>
            <td>".$row['countries']."</td>
            <td>".$row['genres']."</td>
            <td>".$row['rating']."</td>
            <td>".$row['imdb']."</td>
            <td>".$row['runtime']."</td>
          </tr>";
      }
      echo "</tbody></table>";
    } else {
      echo "You don't have watched films!";
    }

    $conn->close();
  ?>
  </body>
</html>
