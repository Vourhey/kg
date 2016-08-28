<?php

require_once('dbconnect.php');

$method = $_GET['method'];

if($method == 'delete') {
  $sql = "DELETE FROM filmlist WHERE id in (".$_GET['ids'].")";
  $conn->query($sql);
}

$sql = "SELECT * FROM filmlist";
$result = $conn->query($sql);
if($result->num_rows > 0) { 
  while($row = $result->fetch_assoc()) {
    echo "
      <tr>
        <td><input class='editbox' type='checkbox' value='".$row['id']."'>
        <td><img src='".$row['img_link']."' height=120 width=auto></td>
        <td>".$row['name']." (".$row['englishName'].")</td>
        <td>".$row['directors']."</td>
        <td>".$row['year']."</td>
        <td>".$row['countries']."</td>
        <td>".$row['genres']."</td>
        <td>".$row['rating']."</td>
        <td>".$row['imdb']."</td>
        <td>".$row['runtime']."</td>
      </tr>";
  }
} else {
  echo "You don't have films!";
}


$conn->close();