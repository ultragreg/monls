<?php
class Saison {
 
    // database connection and table name
    private $conn;
 
    // object properties
    public $saison_id;
    public $saison_nom;
    public $saison_commentaire;
 
    public function __construct($db){
        $this->conn = $db;
    }


    // Retourne la saison courante
    function chargeSaison()
    {
      // Requete pour retrouver l'ID de la dernière saison
      $query = "select * from saison where saison_id=:id LIMIT 0, 1";
      $stmt = $this->conn->prepare( $query );
      $stmt->bindParam(':id', $this->saison_id);
      $stmt->execute();
      if ($stmt->rowCount()>0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);
        $this->saison_id=$saison_id;
        $this->saison_nom=$nom;
        $this->saison_commentaire=$commentaire;
        return $this;
      }
      return false;
    }

    // Retourne la saison courante
    function chargeSaisonCourante()
    {
      // Requete pour retrouver l'ID de la dernière saison
      $query = "select * from saison order by saison_id desc LIMIT 0, 1";
      $stmt = $this->conn->prepare( $query );
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $this->saison_id=$saison_id;
      $this->saison_nom=$nom;
      $this->saison_commentaire=$commentaire;
      return $this;
    }


    // Retourne toutes les saisons
    function litSaisons()
    {
      // Requete pour retrouver toutes les saisons dans l'ordre d'insertion en base
      $query = "select * from saison order by saison_id desc";
      $stmt = $this->conn->prepare( $query );
      $stmt->execute();
      return $stmt;
    }

   // Ajoute une saison
    function ajoute(){
         try {
          $query= "insert into saison ( nom, commentaire) 
                  values (:nom,  :commentaire)";
          $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nom', $this->saison_nom);
        $stmt->bindParam(':commentaire', $this->saison_commentaire);
        if ($stmt->execute()) {
            return true;
        }   else{
            return $stmt->errorInfo();
        }
        }
        catch(PDOException $exception) {
            echo "Ajoute une saison : " . $this->host . " : " . $exception->getMessage();
        }
    }


    // Modifie une saison
    function modifie(){ 
        try {
        $query = "update saison set
                    nom = :nom,
                    commentaire = :commentaire
                WHERE
                    saison_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->saison_id);
        $stmt->bindParam(':nom', $this->saison_nom);
        $stmt->bindParam(':commentaire', $this->saison_commentaire);
        if ($stmt->execute()) {
            return true;
        }   else{
            return $stmt->errorInfo();
        }
        }catch(PDOException $exception){
            echo "Modifie une saison : " . $this->host . " : " . $exception->getMessage();
        }
    }

}
?>