<?php

class AppelDeFonds {
 
    // database connection and table name
    private $conn;
 
    // object properties
    public $appel_id;
    public $libelle;
    public $date;

 
    public function __construct($db){
        $this->conn = $db;
    }



    function litAppelDeFonds()
    {
      $query = "SELECT * 
                FROM appeldefonds
                ORDER BY appel_id DESC";
      $stmt = $this->conn->prepare( $query );
      $stmt->execute();
      return $stmt;
    }



}
?>