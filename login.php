<?php

session_start();

require_once 'errorhandler.php';

errorLog('ll', var_export($_POST, true), __FILE__, __LINE__);

if(isset($_POST['email']) && isset($_POST['password'])) {
  require_once ('dbconnect.php');
  $conn = Database::getConnection();

  $email = $conn->real_escape_string($_POST['email']);
  $token = hash('ripemd128', $_POST['password']);

  $sql = "SELECT id FROM users WHERE email='$email' AND password='$token';";
  $result = $conn->query($sql);

  errorLog('ll', "result->num_rows is $result->num_rows", __FILE__, __LINE__);

  if($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $_SESSION['userid'] = $row['id'];
    $_SESSION['email'] = $email;

    header('location: index.php');
  } else {

  header("Refresh: 3; url=index.php");
  echo "<h3 style='color: red'>Wrong email or password</h3>";

  }
} else {
  header("location: index.php");
}
