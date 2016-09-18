
<?php 
$servername = 'kg.test';
$username = 'vourhey';
$password = 'Ea8yeeb0';
$dbname = 'kg';

$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error) {
  die('Connection failed: '.$conn->connect_error);
}

