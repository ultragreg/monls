<?php

class Gain {
 
    // database connection and table name
    private $conn;
 
    // object properties
    public $gain_id;
    public $saison_id;
    public $joueur_id;
    public $gain_date;
    public $gain_somme;

 
    public function __construct($db){
        $this->conn = $db;
    }


    function chargeGain()
    {
      // Requete pour retrouver l'ID du dernier jeu
      $query = "select * from gain where gain_id={$this->gain_id}";
      $stmt = $this->conn->prepare( $query );
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $this->gain_id=$gain_id;
      $this->saison_id=$saison_id;
      $this->joueur_id=$joueur_id;
      $this->gain_date=$date;
      $this->gain_somme=$somme;
      return $this;
    }


    function litGains()
    {
      $query = "SELECT gain_id, gain.joueur_id, joueur.nom, sum( somme ) AS total, date 
                FROM gain, joueur
                WHERE saison_id = {$this->saison_id}
                AND gain.joueur_id = joueur.joueur_id
                GROUP BY joueur_id
                ORDER BY total DESC";
      $stmt = $this->conn->prepare( $query );
      $stmt->execute();
      return $stmt;
    }



    function litGainsDistincts()
    {
      $query = "SELECT gain_id, gain.joueur_id, joueur.nom,  somme AS total, date 
                FROM gain, joueur
                WHERE saison_id = {$this->saison_id}
                AND gain.joueur_id = joueur.joueur_id
                ORDER BY total DESC";
      $stmt = $this->conn->prepare( $query );
      $stmt->execute();
      return $stmt;
    }



    function litGainsJson()
    {
      $query = "SELECT gain.joueur_id, joueur.nom, sum( somme ) AS total
                FROM gain, joueur
                WHERE saison_id = {$this->saison_id}
                AND gain.joueur_id = joueur.joueur_id
                GROUP BY joueur_id
                ORDER BY total DESC";
      $stmt = $this->conn->prepare( $query );
      $stmt->execute();
      $tmp="[";
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (strlen($tmp)>1) {
            $tmp=$tmp . ",";
        }
        extract($row);
        $tmp = $tmp . '{"label": "' . $nom . '","data": ' . $total . '}';
      }  
      $tmp=$tmp . "]";
      return $tmp;
    }

   // Ajoute un gain
    function ajoute(){
         try {
          $dateDuJour=date("Y-m-d");
          $query= "insert into gain (saison_id, joueur_id, date, somme) 
                  values (:saison_id, :joueur_id, :date, :somme)";
          $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':saison_id', $this->saison_id);
        $stmt->bindParam(':joueur_id', $this->joueur_id);
        $stmt->bindParam(':date', $dateDuJour);
        $stmt->bindParam(':somme', $this->gain_somme);
        if ($stmt->execute()) {
            return true;
        }   else{
            return $stmt->errorInfo();
        }
        }
        catch(PDOException $exception) {
            echo "Ajoute un gain : " . $this->host . " : " . $exception->getMessage();
        }
    }


    // Modifie un gain
    function modifie(){ 
        try {
        $query = "update gain set
                    joueur_id = :joueur_id,
                    somme = :somme
                WHERE
                    gain_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->gain_id);
        $stmt->bindParam(':joueur_id', $this->joueur_id);
        $stmt->bindParam(':somme', $this->gain_somme);
        if ($stmt->execute()) {
            return true;
        }   else{
            return $stmt->errorInfo();
        }
        }catch(PDOException $exception){
            echo "Modifie un gain : " . $this->host . " : " . $exception->getMessage();
        }
    }


}
?>