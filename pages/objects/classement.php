<?php

class Classement {
 
    private $conn;
 
    // object properties
    public $saison_id;
    public $joueur_id;
    public $moyenne;
    public $evolution;
    public $rapport;
    public $posRisque;

 
    public function __construct($db){
        $this->conn = $db;
    }

    function litClassement()
    {
        $query = "select j.nom,c.moyenne,c.evolution, c.rapport, c.posRisque, j.joueur_id 
                from classement c, joueur j 
                where c.joueur_id=j.joueur_id 
                and saison_id={$this->saison_id} 
                order by moyenne desc";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }

    function litTroisMeilleursClassement()
    {
        $query = "select nom from classement, joueur 
                where classement.saison_id={$this->saison_id} 
                and classement.joueur_id = joueur.joueur_id
                order by moyenne desc 
                Limit 0,3";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }



    function ajoute()
    {
    	// Met à jour le classement
        try {
        	
        	$saison_id = $this->saison_id;
        	$joueur_id = $this->joueur_id;
        	$moyenne   = $this->moyenne;
        	$evolution = $this->evolution;
        	$rapport   = $this->rapport;
        	$posRisque = $this->posRisque;
        			
          // Ajout de ce joueur
          $query= "insert into classement ( saison_id, joueur_id, moyenne, evolution, rapport, posRisque)  
                    values ('{$saison_id}','{$joueur_id}','{$moyenne}', '{$evolution}','{$rapport}','{$posRisque}')";
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
            echo "Ajoute un enregistrement au classement : " . $exception->getMessage();
        }
    }

    function efface()
    {
        try {
        	$saison_id = $this->saison_id;
          // Supression du classement pour cette saison
          $query= "delete from classement where saison_id ='{$saison_id}'";
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
            echo "Suppression un enregistrement au classement : " . $this->host . " : " . $exception->getMessage();
        }
    }

}
?>