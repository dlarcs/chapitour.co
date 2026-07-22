<?php

class Database
{
  private $servername = "localhost"; //propiedad
  private $dbname = "Chapitour.co";
  private $username = "root";
  private $password = "";
  private $connection; //se declara, se prepara

  public function __construct()
  {

    try {
      $this->connection = new PDO(
        "mysql:host={$this->servername};dbname={$this->dbname};charset=utf8mb4",
        $this->username,
        $this->password
      );

      $this->connection->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
      );

    } catch (PDOException $e) {
      $this->connection = null;

      echo "Connection failed: " . $e->getMessage();
    }
  }
  public function getConnection()
  {
    return $this->connection;
  }

  public function closeConnection()
  {
    $this->connection = null;
  }
}
?>
