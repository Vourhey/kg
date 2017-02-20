<?php 
class Database {
  private $servername = 'kg.test';
  private $username = 'vourhey';
  private $password = 'Ea8yeeb0';
  private $dbname = 'kg_newdesign';
  private static $db;
  private $connection;

  private function __construct() {
    $this->connection = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
    if($this->connection->connect_error) {
      // TODO output error message
    }
  }

  public function __destruct() {
    $this->connection->close();
  }

  public static function getConnection() {
    if(self::$db == null) {
      self::$db = new Database();
    }
    return self::$db->connection;
  }
}

