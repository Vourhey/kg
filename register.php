<?php

if(isset($_POST['username']) && isset($_POST['password'])) {
  require_once ('dbconnect.php');
  $conn = Database::getConnection();

  $username = $conn->real_escape_string($_POST['username']);
  $token = hash('ripemd128', $_POST['password']);

  // TODO check if the user exists

  $sql = "INSERT INTO users (name, password) VALUES ('$username', '$token')";
  echo $sql;
  $result = $conn->query($sql);

  // TODO check $result for errors
  if($result) {
    echo "Success! <a href='index.php'>Go to home page</a>";
  }
} else {

?>

<form action="register.php" method="post">
  <label>Enter name:</label>
  <input type="text" name="username"><br>
  <label>Enter password:</label>
  <input type="password" name="password"><br>
  <input type="submit" name="submit">
</form>

<?php } ?>
