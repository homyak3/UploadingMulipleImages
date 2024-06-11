<?php
class Connection
{
    private $dbhost = "localhost";
    private $dbuser = "root";
    private $dbpassword = "";
    private $dbname = "tester";
    public $conn;

    public function connect(){
        try {
            return $this->conn = new PDO("mysql:host=$this->dbhost;dbname=$this->dbname", $this->dbuser, $this->dbpassword);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

}




