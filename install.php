<?php
  require_once('dbconnect.php');

  $sql = "CREATE TABLE `filmlist` 
          (
            kpid INT(11) NOT NULL,
            name VARCHAR(255) NOT NULL,
            englishName VARCHAR(255) NOT NULL,
            directors TEXT NOT NULL,
            year INT(6) NOT NULL,
            countries TEXT NOT NULL,
            genres TEXT NOT NULL,
            rating FLOAT NOT NULL,
            imdb FLOAT NOT NULL,
            runtime VARCHAR(50) NOT NULL,
            UNIQUE KEY (kpid)
          );";
  $conn->query($sql);

  $sql = str_replace('filmlist', 'temp', $sql);
  $conn->query($sql);

  $sql = "CREATE TABLE `watched` 
          (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            kpid INT(11) NOT NULL,
            name VARCHAR(255) NOT NULL,
            englishName VARCHAR(255) NOT NULL,
            directors TEXT NOT NULL,
            year INT(6) NOT NULL,
            countries TEXT NOT NULL,
            genres TEXT NOT NULL,
            rating FLOAT NOT NULL,
            imdb FLOAT NOT NULL,
            runtime VARCHAR(50) NOT NULL,
            UNIQUE KEY (kpid)
          );";
  $conn->query($sql);

  echo 'done';
