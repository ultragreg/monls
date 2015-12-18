<?php
class Indicateur {
 
    private $conn;
 
    // object properties
    public $indicateur_id;
    public $match_num;
    public $jeu_id;
    public $choix1;
    public $choixN;
    public $choix2;
    public $pourcentageC1;
    public $pourcentageCN;
    public $pourcentageC2;
    public $pourcentage1;
    public $pourcentageN;
    public $pourcentage2;

 
    public function __construct($db){
        $this->conn = $db;
    }



    function litIndicateurs()
    {
        $query = "select * from indicateurs where jeu_id={$this->jeu_id} order by match_num asc";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }


    function ajoute()
    {
        try {
            $jeu_id=$this->jeu_id;
            $match_num=$this->match_num;
            $choix1=$this->choix1;
            $choixN=$this->choixN;
            $choix2=$this->choix2;
            $pourcentageC1=$this->pourcentageC1;
            $pourcentageCN=$this->pourcentageCN;
            $pourcentageC2=$this->pourcentageC2;
            $pourcentage1=$this->pourcentage1;
            $pourcentageN=$this->pourcentageN;
            $pourcentage2=$this->pourcentage2;   
            // Ajout dd'un indicateur pour un match d'un jeu donné
            $query= "insert into indicateurs ( jeu_id,match_num,choix1,choixN,choix2,
                        pourcentageC1,pourcentageCN,pourcentageC2,
                        pourcentage1,pourcentageN,pourcentage2, dateMAJ) 
                      values ('{$jeu_id}','{$match_num}','{$choix1}','{$choixN}','{$choix2}',
                        '{$pourcentageC1}','{$pourcentageCN}','{$pourcentageC2}',
                        '{$pourcentage1}','{$pourcentageN}','{$pourcentage2}',now())";
          $stmt = $this->conn->prepare($query);
          if ($stmt->execute()) {
            return true;
          }   
          else {            
            return ($stmt->errorInfo());
          }
        }
        catch(PDOException $exception) {
            echo "Ajout des indicateurs d'un match d'un jeu : " . $this->host . " : " . $exception->getMessage();
        }
    }


    function efface()
    {
        try {
          $jeu_id = $this->jeu_id;
          // Supression du classement pour cette saison
          $query= "delete from indicateurs where jeu_id ='{$jeu_id}'";
          $stmt = $this->conn->prepare($query);
          if (!$stmt) {
            echo $stmt->errorInfo();            
            print_r($stmt->errorInfo());
          }
          if ($stmt->execute()) {
            return true;
          }
          else {
            return($stmt->errorInfo());
          }
        }
        catch(PDOException $exception) {
            echo "Suppression des indicateurs d'un jeu : " . $this->host . " : " . $exception->getMessage();
        }
    }


   


}
?>