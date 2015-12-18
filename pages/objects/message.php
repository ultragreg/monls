<?php

class Message {
 
    // database connection and table name
    private $conn;
 
    // object properties
    public $joueur_id;
    public $joueur_nom;
    public $jeu_id;
    public $jeu_nom;
 
    public function __construct($db){
        $this->conn = $db;
    }


    function appelDeFondsEnCours()
    {
        if (isset($this->jeu_id)) {
            return -1;
        }
        $query = "select count(*) total from joueur j where joueur_id={$this->joueur_id} and actif='O' and joueur_id not in (select joueur_id from appeljoueurs where appel_id = (select max(appel_id) from appeldefonds))";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $rows = $stmt->fetch(PDO::FETCH_NUM);
        return $rows[0];
    }


    function pronosticEnCours()
    {
        if (isset($this->jeu_id)) {
            return -1;
        }
        $query = "select count(*) total from joueur j where joueur_id={$this->joueur_id} and actif='O' and joueur_id not in (select joueur_id from pronostic where jeu_id=(select max(jeu_id) from jeu) AND LENGTH(pronostic1)>0)";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $rows = $stmt->fetch(PDO::FETCH_NUM);
        return $rows[0];
    }


}
?>