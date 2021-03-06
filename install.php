<html>
  <head>
    <title>Install</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
  </head>
  <body>
<?php 
  if(empty($_POST['hiddeninput'])) {
?>
    <form method="post" action="install.php">
      <input type="hidden" name="hiddeninput" value="gotit">
      <div class="form-group">
        <label>Server name: </label>
        <input type="text" class="form-control" name="servername">
      </div>
      <div class="form-group">
        <label>User name: </label>
        <input type="text" class="form-control" name="username">
      </div>
      <div class="form-group">
        <label>Password: </label>
        <input type="text" class="form-control" name="password">
      </div>
      <div class="form-group">
        <label>DB name: </label>
        <input type="text" class="form-control" name="dbname">
      </div>
      <div class="form-group"> 
        <button type="submit" class="btn btn-default">Submit</button>
      </div>
    </form>
<?php
  } else {
    $servername = $_POST['servername'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $dbname = $_POST['dbname'];

    $dbconnfile = fopen("dbconnect.php", "w") or die("can't open file for write");

    $content = "
<?php 
class Database {
  private \$servername = '$servername';
  private \$username = '$username';
  private \$password = '$password';
  private \$dbname = '$dbname';
  private static \$db;
  private \$connection;

  private function __construct() {
    \$this->connection = new mysqli(\$this->servername, \$this->username, \$this->password, \$this->dbname);
    if(\$this->connection->connect_error) {
      // TODO output error message
    }
  }

  public function __destruct() {
    \$this->connection->close();
  }

  public static function getConnection() {
    if(self::\$db == null) {
      self::\$db = new Database();
    }
    return self::\$db->connection;
  }
}

";
    fwrite($dbconnfile, $content);
    fclose($dbconnfile);

    require_once('dbconnect.php');
    $conn = Database::getConnection();

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
    echo $conn->connect_error;

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

  } ?>
  </body>
</html>
