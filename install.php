<?php
  require_once('dbconnect.php');

 /* $sql = "CREATE TABLE filmlist 
          (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            img_link VARCHAR(100) NOT NULL,
            film_url VARCHAR(100) NOT NULL,
            name VARCHAR(255) NOT NULL,
            englishName VARCHAR(255) NOT NULL,
            directors TEXT NOT NULL,
            year INT(6) NOT NULL,
            countries TEXT NOT NULL,
            genres TEXT NOT NULL,
            rating FLOAT NOT NULL,
            imdb FLOAT NOT NULL,
            runtime VARCHAR(50) NOT NULL,
            UNIQUE KEY (film_url)
          );";

  $conn->query($sql);

  $sql = str_replace('filmlist', 'watched', $sql); */
  
  $sql = "CREATE TABLE testdb 
          (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            img_link VARCHAR(100) NOT NULL,
            film_url VARCHAR(100) NOT NULL,
            name VARCHAR(255) NOT NULL,
            englishName VARCHAR(255) NOT NULL,
            directors TEXT NOT NULL,
            year INT(6) NOT NULL,
            countries TEXT NOT NULL,
            genres TEXT NOT NULL,
            rating FLOAT NOT NULL,
            imdb FLOAT NOT NULL,
            runtime VARCHAR(50) NOT NULL,
            UNIQUE KEY (film_url)
          );";
  $conn->query($sql);

  echo 'done';
