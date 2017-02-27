<?php

//$errors = array();
//$success = true;

if(isset($_POST['email']) && isset($_POST['password'])) {
  require_once 'dbconnect.php';
  require_once 'errorhandler.php';
  $conn = Database::getConnection();

  errorLog(55, var_export($_POST, true), __FILE__, __LINE__);

  $email = $conn->real_escape_string($_POST['email']);
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {  // wrong email
    errorLog(55, "wrong email $email", __FILE__, __LINE__);
    array_push($errors, "Wrong email");
    //$success = false;
  }
  $username = explode('@', $email);
  $username = $username[0];
  errorLog(55, "username: $username", __FILE__, __LINE__);
  $token = hash('ripemd128', $_POST['password']);
  $registered_date = date('Y-m-d');

  $sql = "SELECT id FROM users WHERE email='$email';";
  $result = $conn->query($sql);

  if($result) {
    // there's the user
    errorLog(55, "Duplicate user ".$result->num_rows, __FILE__, __LINE__);
    array_push($errors, "Email is taken");
    //$success = false;
  }
} 

if(count($errors) == 0) {
  $sql = "INSERT INTO users (name, email, password, registered_date) VALUES ('$username', '$email', '$token', '$registered_date')";
  //errorLog(55, $sql, __FILE__, __LINE__);
  $result = $conn->query($sql);
  if($result) {
    header('location: index.php');
  }
} else {
  foreach ($errors as $e) {
    echo "<p style='color: red;'>$e</p>";
  }
?>

<form action="register.php" method="post">
  <label>Enter email:</label>
  <input type="text" name="email"><br>
  <label>Enter password:</label>
  <input type="password" name="password"><br>
  <input type="submit" name="submit">
</form>

<?php } ?>
