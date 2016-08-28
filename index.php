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

    <script>
      function getIds() {
        var ids = document.getElementsByClassName('editbox');
        var ids = Array.prototype.filter.call(ids, function(id) {
          return id.checked == true;
        });
        ids.forEach(function(id, index, ids) {
          ids[index] = id.value;
        });
        // ids хранит id выделенных строк
        return ids;
      }

      function deleteFilms() {
        var ids = getIds();
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if(xhttp.readyState == 4 && xhttp.status == 200) {
            document.getElementById("filmtable").innerHTML = xhttp.responseText;
          }
        }

        console.log("edit.php?method=delete&ids=" + ids.join(','));
        xhttp.open("GET", "edit.php?method=delete&ids=" + ids.join(','), true);
        xhttp.send();
      }
    </script>
  </head>
  <body>

  <button onclick="deleteFilms()">delete</button> <button>edit</button> <button>move</button>

  <form method="post" action="index.php">
    <textarea name="addfilms" rows=1 cols=80></textarea> 
    <input type="submit" name="submit" value="Add">
  </form>

  <?php
    if(!empty($_POST['addfilms'])) {
      require_once ('Snoopy.class.php');
      require_once ('Film.class.php');

      $films = $_POST['addfilms'];
      $films = explode(PHP_EOL, $films);
      
      $client = new Snoopy();
      $client->referer = "http://kinopoisk.ru/";
      $client->agent = "Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0";

      foreach($films as $f) {
        if(substr($f, 0, 7) != "http://" && substr($f, 0, 8) != "https://") {
          $f = "https://www.kinopoisk.ru/index.php?first=yes&kp_query=".urlencode($f);
        }

        for($i = 0; $i < 5; ++$i) {
          $html = $client->fetch($f)->results;

          if(!empty($html)) {
            break;
          } else {
          //  $client->agent = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.0 Safari/537.36";
            $f = $client->_redirectaddr;
          //  var_dump($client);
          }
        }
        

        $filmlink = $client->lastredirectaddr;

        if(empty($html)) {
          var_dump($client);
          die("Oh");
        }
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        if(!$dom->loadHTML($html))
          die("Fail on loading");

        $xpath = new DOMXPath($dom);
        $film = new Film($xpath);

        $sql = "INSERT INTO filmlist (img_link, film_url, name, englishName, directors, year, countries, genres, rating, imdb, runtime) VALUES ('".$film->img."','".$filmlink."','".$film->name."','".$film->englishName."','".$film->directors."','".$film->year."','".$film->countries."','".$film->genres."','".$film->rating."','".$film->imdb."','".$film->runtime."')";
      //  echo $sql;
        $conn->query($sql);
        sleep(rand(10,25));
      }
    }

    if(isset($_GET['watched'])) {
      $tablename = 'watched';
    } else {
      $tablename = 'filmlist';
    }

    $sql = "SELECT * FROM $tablename ORDER BY name";
    $result = $conn->query($sql);
    if($result->num_rows > 0) { 
      echo "<table id='filmtable'>";
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
      echo "</table>";
    } else {
      echo "You don't have watched films!";
    }

    $conn->close();
  ?>
  </body>
</html>
