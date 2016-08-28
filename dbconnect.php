<?php 
$servername = "kg.test";
$username = "vourhey";
$password = "Ea8yeeb0";
$dbname = "kg";

$conn = new mysqli($servername, $username, $password, $dbname);
//var_dump($conn);
if($conn->connect_error) {
  die("Connection failed: ".$conn->connect_error);
}