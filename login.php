<?php

session_start();

require_once ('core.php');

if(isset($_POST['username']) && isset($_POST['password'])) {
  require_once ('dbconnect.php');
  $conn = Database::getConnection();

  $username = $conn->real_escape_string($_POST['username']);
  $token = hash('ripemd128', $_POST['password']);

  $sql = "SELECT id FROM users WHERE name='$username' AND password='$token';";
  $result = $conn->query($sql);

  errorLog('ll', "result->num_rows is $result->num_rows", "login.php", 17);

  if($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $_SESSION['userid'] = $row['id'];
    $_SESSION['username'] = $username;

    header('location: index.php');
  }
} else {

?>

Login form
<form action="login.php" method="post">
  <label>Enter name:</label>
  <input type="text" name="username"><br>
  <label>Enter password:</label>
  <input type="password" name="password"><br>
  <input type="submit" name="submit">
</form>

<?php } ?>
