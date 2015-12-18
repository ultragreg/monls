<?php

class Rapport {
 
    // database connection and table name
    private $conn;
 
    // object properties
    public $jeu_id;
    public $type;
    public $rang;
    public $nombre;
    public $rapport;
    public $commentaire;
 
    public function __construct($db){
        $this->conn = $db;
    }


    function litRapports()
    {
      if (strlen(trim($this->type))>0) {
      $query = "SELECT jeu_id, type, rang, nombre, rapport, commentaire
                FROM rapport
                WHERE jeu_id = {$this->jeu_id}
                and type = {$this->type}
                ORDER BY rang DESC";
      } else {
      $query = "SELECT jeu_id, type, rang, nombre, rapport, commentaire
                FROM rapport
                WHERE jeu_id = {$this->jeu_id}
                ORDER BY type, rang DESC";
      }
      $stmt = $this->conn->prepare( $query );
      $stmt->execute();
      return $stmt;
    }


    function ajoute()
    {
        try {
          $jeu_id       =$this->jeu_id;
          $type         =$this->type;
          $rang         =$this->rang;
          $nombre       =$this->nombre;
          $rapport      =$this->rapport;
          $commentaire  =$this->commentaire;

          // Ajout de ce rapport
          $query= "insert into rapport (jeu_id, type, rang, nombre, rapport, commentaire) 
                   values 
                   ('{$jeu_id}', '{$type}', '{$rang}', '{$nombre}', '{$rapport}', '{$commentaire}')";          
          $stmt = $this->conn->prepare($query);
          if (!$stmt) {
            return($stmt->errorInfo());
          }
          if ($stmt->execute()) {
            return true;
          }   
          else {
            return($stmt->errorInfo());
          }
        }
        catch(PDOException $exception) {
            echo "Ajout d'un rapport  : " . $this->host . " : " . $exception->getMessage();
        }
    }

    

    function efface()
    {
        try {
          // Supression des rapports
          $query= "delete from rapport WHERE jeu_id = {$this->jeu_id} and  type = {$this->type}";
          $stmt = $this->conn->prepare($query);
          if ($stmt->execute()) {
            return true;
          }
          else {
            return($stmt->errorInfo());
          }
        }
        catch(PDOException $exception) {
            echo "Suppression des rapports de ce jeu : " . $this->host . " : " . $exception->getMessage();
        }
    }


}
?>