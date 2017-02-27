<?php

require_once 'dbconnect.php';

$conn = Database::getConnection();

$sql = "CREATE TABLE IF NOT EXISTS `actors`
        (
          id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          name VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB;";
$result = $conn->query($sql);
if($result) {
  echo "success";
} else {
  echo $conn->error;
}

$sql = "CREATE TABLE IF NOT EXISTS `countries`
        (
          id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          country VARCHAR(100) NOT NULL
        ) ENGINE=InnoDB;";
$result = $conn->query($sql);
if($result) {
  echo "success";
} else {
  echo $conn->error;
}

$sql = "CREATE TABLE IF NOT EXISTS `directors`
        (
          id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          director VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB;";
$result = $conn->query($sql);
if($result) {
  echo "success";
} else {
  echo $conn->error;
}

$sql = "CREATE TABLE IF NOT EXISTS `filmlist`
        (
          id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          kpid INT(10) UNSIGNED NOT NULL,
          name VARCHAR(255) NOT NULL,
          original_name VARCHAR(255) NOT NULL,
          directors VARCHAR(255) NOT NULL,
          year INT(4) NOT NULL,
          countries VARCHAR(255) NOT NULL,
          genres VARCHAR(255) NOT NULL,
          rating FLOAT NOT NULL,
          imdb FLOAT NOT NULL,
          runtime INT(11) NOT NULL,
          description TEXT NOT NULL,
          trailer VARCHAR(30) NOT NULL,
          actors VARCHAR(255) NOT NULL,
          needs_approval INT(11) NOT NULL DEFAULT '1',
          UNIQUE KEY kpid (kpid),
          KEY original_name (original_name),
          KEY name (name)
        ) ENGINE=InnoDB;";
$result = $conn->query($sql);
if($result) {
  echo "success";
} else {
  echo $conn->error;
}

$sql = "CREATE TABLE IF NOT EXISTS `genres`
        (
          id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          genre VARCHAR(100) NOT NULL
        ) ENGINE=InnoDB;";
$result = $conn->query($sql);
if($result) {
  echo "success";
} else {
  echo $conn->error;
}

$sql = "CREATE TABLE IF NOT EXISTS `users`
        (
          id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          name VARCHAR(255) NOT NULL,
          email VARCHAR(255) NOT NULL,
          password VARCHAR(100) NOT NULL,
          registered_date DATE NOT NULL,
          UNIQUE KEY email (email)
        ) ENGINE=InnoDB;";
$result = $conn->query($sql);
if($result) {
  echo "success";
} else {
  echo $conn->error;
}

$sql = "CREATE TABLE IF NOT EXISTS `watched`
        (
          userid INT(10) UNSIGNED NOT NULL,
          filmid INT(10) UNSIGNED NOT NULL,
          watched_date DATE NOT NULL
        ) ENGINE=InnoDB;";

$result = $conn->query($sql);
if($result) {
  echo "success";
} else {
  echo $conn->error;
}
