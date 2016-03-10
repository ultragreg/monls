<?php
class Database{
 
    // specify your own database credentials
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;


    public function __construct(){
        $url_du_site = $_SERVER['SERVER_NAME'];
          // Connexion : Serveur, User, Password, BD
          $this->host= getenv('DB_HOSTNAME');
          $this->username = getenv('DB_USERNAME');
          $this->password= getenv('DB_PASSWORD');
          $this->db_name= getenv('DB_NAME');


    }
 

    // get the database connection
    public function getConnection(){
        $this->conn = null;
        try{
            if ($this->host=="localhost") {
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf-8", $this->username, $this->password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
            }
            else {
               $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8", $this->username, $this->password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
             }

        }catch(PDOException $exception){
            echo "Connection error : " . $this->host . " : " . $exception->getMessage();
        }
 
        return $this->conn;
    }

    public function getHost() {
      return $this->host;
    }
    public function getDb_name() {
      return $this->db_name;
    }
    public function getUsername() {
      return $this->username;
    }
    public function getPassword() {
      return $this->password;
    }
}
?>