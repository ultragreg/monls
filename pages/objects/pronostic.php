<?php
class Pronostic {
 
    private $conn;
 
    // object properties
    public $pronostic_id;
    public $flash;
    public $joueur_id;
    public $jeu_id;
    public $pronostic1;
    public $pronostic2;
    public $pronostic3;
    public $pronostic4;
    public $pronostic5;
    public $pronostic6;
    public $pronostic7;
    public $pronostic8;
    public $pronostic9;
    public $pronostic10;
    public $pronostic11;
    public $pronostic12;
    public $pronostic13;
    public $pronostic14;
    public $pronostic15;
    public $IndiceGain7;
    public $IndiceGain15;
    public $MoyenneJuste;


    public function __construct($db){
        $this->conn = $db;
    }

    function litPronostics()
    {
        $query = "select * from pronostic where jeu_id={$this->jeu_id} order by joueur_id asc";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }



    function chargePronostic()
    {
      $query = "select * from pronostic where jeu_id={$this->jeu_id} and joueur_id={$this->joueur_id} Limit 0,1";
      $stmt = $this->conn->prepare( $query );
      $stmt->execute();
      $nbr = $stmt->rowCount();
      if ($nbr>0) {
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          extract($row);

          $this->pronostic_id=$pronostic_id;
          $this->jeu_id=$jeu_id;
          $this->joueur_id=$joueur_id;
          $this->pronostic1=$pronostic1;
          $this->pronostic2=$pronostic2;
          $this->pronostic3=$pronostic3;
          $this->pronostic4=$pronostic4;
          $this->pronostic5=$pronostic5;
          $this->pronostic6=$pronostic6;
          $this->pronostic7=$pronostic7;
          $this->pronostic8=$pronostic8;  
          $this->pronostic9=$pronostic9;  
          $this->pronostic10=$pronostic10;  
          $this->pronostic11=$pronostic11;
          $this->pronostic12=$pronostic12;
          $this->pronostic13=$pronostic13;
          $this->pronostic14=$pronostic14;
          $this->pronostic15=$pronostic15;
          $this->flash=$flash;
          $this->IndiceGain7=$IndiceGain7;
          $this->IndiceGain15=$IndiceGain15;
      }
      return $this;
    }


    function ajoute()
    {
        try {
          $jeu_id       =$this->jeu_id;
          $joueur_id    =$this->joueur_id;
          $pronostic1   =$this->pronostic1;
          $pronostic2   =$this->pronostic2;
          $pronostic3   =$this->pronostic3;
          $pronostic4   =$this->pronostic4;
          $pronostic5   =$this->pronostic5;
          $pronostic6   =$this->pronostic6;
          $pronostic7   =$this->pronostic7;
          $pronostic8   =$this->pronostic8;
          $pronostic9   =$this->pronostic9;
          $pronostic10  =$this->pronostic10;
          $pronostic11  =$this->pronostic11;
          $pronostic12  =$this->pronostic12;
          $pronostic13  =$this->pronostic13;
          $pronostic14  =$this->pronostic14;
          $pronostic15  =$this->pronostic15;
          $flash        =$this->flash;
          $IndiceGain7  =$this->IndiceGain7;
          $IndiceGain15 =$this->IndiceGain15;
          // Ajout de ce résultat
          $query= "insert into pronostic (jeu_id, joueur_id, pronostic1, pronostic2, 
                          pronostic3, pronostic4, pronostic5, pronostic6, pronostic7, 
                          pronostic8, pronostic9, pronostic10, pronostic11, pronostic12, 
                          pronostic13, pronostic14, pronostic15, flash, IndiceGain7, IndiceGain15) 
                          values 
                          ('{$jeu_id}', '{$joueur_id}', '{$pronostic1}', '{$pronostic2}', '{$pronostic3}', '{$pronostic4}',
                          '{$pronostic5}', '{$pronostic6}', '{$pronostic7}', '{$pronostic8}', '{$pronostic9}',
                          '{$pronostic10}', '{$pronostic11}', '{$pronostic12}', '{$pronostic13}', '{$pronostic14}',
                          '{$pronostic15}', '{$flash}', '{$IndiceGain7}', '{$IndiceGain15}')";          
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
          $pronostic_id =$this->pronostic_id;
          $pronostic1   =$this->pronostic1;
          $pronostic2   =$this->pronostic2;
          $pronostic3   =$this->pronostic3;
          $pronostic4   =$this->pronostic4;
          $pronostic5   =$this->pronostic5;
          $pronostic6   =$this->pronostic6;
          $pronostic7   =$this->pronostic7;
          $pronostic8   =$this->pronostic8;
          $pronostic9   =$this->pronostic9;
          $pronostic10  =$this->pronostic10;
          $pronostic11  =$this->pronostic11;
          $pronostic12  =$this->pronostic12;
          $pronostic13  =$this->pronostic13;
          $pronostic14  =$this->pronostic14;
          $pronostic15  =$this->pronostic15;
          $flash        =$this->flash;
          $IndiceGain7  =$this->IndiceGain7;
          $IndiceGain15 =$this->IndiceGain15;
          // Modification de ce résultat
          $query= "update pronostic set 
                    pronostic1='$pronostic1', 
                    pronostic2='$pronostic2', 
                    pronostic3='$pronostic3', 
                    pronostic4='$pronostic4', 
                    pronostic5='$pronostic5', 
                    pronostic6='$pronostic6', 
                    pronostic7='$pronostic7', 
                    pronostic8='$pronostic8', 
                    pronostic9='$pronostic9', 
                    pronostic10='$pronostic10', 
                    pronostic11='$pronostic11', 
                    pronostic12='$pronostic12', 
                    pronostic13='$pronostic13', 
                    pronostic14='$pronostic14', 
                    pronostic15='$pronostic15', 
                    IndiceGain7='$IndiceGain7', 
                    IndiceGain15='$IndiceGain15' 
                    where pronostic_id='$pronostic_id' ";          
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