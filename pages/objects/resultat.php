<?php
class Resultat {
 
    private $conn;

    // object properties
    public $resultat_id;
    public $jeu_id;
    public $resultat1;
    public $resultat2;
    public $resultat3;
    public $resultat4;
    public $resultat5;
    public $resultat6;
    public $resultat7;
    public $resultat8;
    public $resultat9;
    public $resultat10;
    public $resultat11;
    public $resultat12;
    public $resultat13;
    public $resultat14;
    public $resultat15;
    public $nom;
    public $date;

 
    public function __construct($db){
        $this->conn = $db;
    }


    function chargeResultat()
    {
      // Requete pour retrouver l'ID du dernier jeu
      $query = "select * from resultat where jeu_id={$this->jeu_id} Limit 0,1";
      $stmt = $this->conn->prepare( $query );
      $stmt->execute();
      $nbr = $stmt->rowCount();
      if ($nbr>0) {
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          extract($row);

          $this->resultat_id=$resultat_id;
          $this->jeu_id=$jeu_id;
          $this->resultat1=$resultat1;
          $this->resultat2=$resultat2;
          $this->resultat3=$resultat3;
          $this->resultat4=$resultat4;
          $this->resultat5=$resultat5;
          $this->resultat6=$resultat6;
          $this->resultat7=$resultat7;
          $this->resultat8=$resultat8;  
          $this->resultat9=$resultat9;  
          $this->resultat10=$resultat10;  
          $this->resultat11=$resultat11;
          $this->resultat12=$resultat12;
          $this->resultat13=$resultat13;
          $this->resultat14=$resultat14;
          $this->resultat15=$resultat15;
          $this->nom=$nom;
          $this->date=$date;
      }
      return $this;
    }



    function ajoute()
    {
        try {
          $jeu_id     =$this->jeu_id;
          $resultat1  =$this->resultat1;
          $resultat2  =$this->resultat2;
          $resultat3  =$this->resultat3;
          $resultat4  =$this->resultat4;
          $resultat5  =$this->resultat5;
          $resultat6  =$this->resultat6;
          $resultat7  =$this->resultat7;
          $resultat8  =$this->resultat8;
          $resultat9  =$this->resultat9;
          $resultat10 =$this->resultat10;
          $resultat11 =$this->resultat11;
          $resultat12 =$this->resultat12;
          $resultat13 =$this->resultat13;
          $resultat14 =$this->resultat14;
          $resultat15 =$this->resultat15;
          $nom        =$this->nom;
          $date       =$this->date; 
          // Ajout de ce résultat
          $query= "insert into resultat ( jeu_id, resultat1, resultat2, 
                          resultat3, resultat4, resultat5, resultat6, resultat7, 
                          resultat8, resultat9, resultat10, resultat11, resultat12, 
                          resultat13, resultat14, resultat15, nom, date) 
                          values 
                          ('{$jeu_id}', '{$resultat1}', '{$resultat2}', '{$resultat3}', '{$resultat4}',
                          '{$resultat5}', '{$resultat6}', '{$resultat7}', '{$resultat8}', '{$resultat9}',
                          '{$resultat10}', '{$resultat11}', '{$resultat12}', '{$resultat13}', '{$resultat14}',
                          '{$resultat15}', '{$nom}', '{$date}')";          
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
            echo "Ajout d'un résultat  : " . $this->host . " : " . $exception->getMessage();
        }
    }

    
    function modifie()
    {
        try {
          $resultat_id=$this->resultat_id;
          $jeu_id     =$this->jeu_id;
          $resultat1  =$this->resultat1;
          $resultat2  =$this->resultat2;
          $resultat3  =$this->resultat3;
          $resultat4  =$this->resultat4;
          $resultat5  =$this->resultat5;
          $resultat6  =$this->resultat6;
          $resultat7  =$this->resultat7;
          $resultat8  =$this->resultat8;
          $resultat9  =$this->resultat9;
          $resultat10 =$this->resultat10;
          $resultat11 =$this->resultat11;
          $resultat12 =$this->resultat12;
          $resultat13 =$this->resultat13;
          $resultat14 =$this->resultat14;
          $resultat15 =$this->resultat15;
          $nom        =$this->nom;
          $date       =$this->date; 
          // Modification de ce résultat
          $query= "update resultat set resultat1='$resultat1', 
                    resultat2='$resultat2', 
                    resultat3='$resultat3', 
                    resultat4='$resultat4', 
                    resultat5='$resultat5', 
                    resultat6='$resultat6', 
                    resultat7='$resultat7', 
                    resultat8='$resultat8', 
                    resultat9='$resultat9', 
                    resultat10='$resultat10', 
                    resultat11='$resultat11', 
                    resultat12='$resultat12', 
                    resultat13='$resultat13', 
                    resultat14='$resultat14', 
                    resultat15='$resultat15', 
                    nom='$nom', 
                    date='$date'
                    where resultat_id='$resultat_id'";          
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
            echo "Modification d'un résultat : " . $this->host . " : " . $exception->getMessage();
        }
    }


}
?>