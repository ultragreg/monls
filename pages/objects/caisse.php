<?php

// ======================================================== //
//                  L E S    C A I S S E S                  //
// ======================================================== //


class Caisse {
 
    // database connection and table name
    private $conn;
 
    // object properties
    public $caisse_id;
    public $saison_id;
    public $caisse_libelle;
    public $caisse_date;
    public $caisse_somme_debit;
    public $caisse_somme_credit;
    public $caisse_total;


    public function __construct($db){
        $this->conn = $db;
    }
 
    // Retourne la caisse courante
    function chargeCaisse()
    {
      // Requete pour retrouver l'ID de la dernière saison
      $query = "select * from caisse where caisse_id=:id LIMIT 0, 1";
      $stmt = $this->conn->prepare( $query );
      $stmt->bindParam(':id', $this->caisse_id);
      $stmt->execute();
      if ($stmt->rowCount()>0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);
        $this->caisse_id=$caisse_id;
        $this->saison_id=$saison_id;
        $this->caisse_libelle=$caisse_libelle;
        $this->caisse_date=$caisse_date;
        $this->caisse_somme_debit=$caisse_somme_debit;
        $this->caisse_somme_credit=$caisse_somme_credit;
      }
      return $this;
    }

    function chargeSoldeSaison()
    {
      // Requete pour retrouver l'ID du dernier jeu
      $query = "select sum(caisse_somme_credit)-sum(caisse_somme_debit) total from caisse where saison_id={$this->saison_id}";
      $stmt = $this->conn->prepare( $query );
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      extract($row);
      $this->caisse_total=$total;
      return $this;
    }

    // Retourne toutes les opérations de la caisse pour une saison
    function litOperationsCaisseSaison()
    {
        // Requete pour retrouver tous les jeux de la saison courante dans l'ordre d'insertion en base
        $query = "select * from caisse where saison_id={$this->saison_id} order by caisse_date asc";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }

   // Ajoute une caisse
    function ajoute(){
         try {
          $dateDuJour=date("Y-m-d");
          $query= "insert into caisse (saison_id, caisse_libelle, caisse_date,caisse_somme_debit, caisse_somme_credit) 
                  values (:saison_id, :caisse_libelle, :caisse_date, :caisse_somme_debit, :caisse_somme_credit)";
          $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':caisse_date', $dateDuJour);
        $stmt->bindParam(':saison_id', $this->saison_id);
        $stmt->bindParam(':caisse_libelle', $this->caisse_libelle);
        $stmt->bindParam(':caisse_somme_debit', $this->caisse_somme_debit);
        $stmt->bindParam(':caisse_somme_credit', $this->caisse_somme_credit);
        if ($stmt->execute()) {
            return true;
        }   else{
            return $stmt->errorInfo();
        }
        }
        catch(PDOException $exception) {
            echo "Ajoute une caisse : " . $this->host . " : " . $exception->getMessage();
        }
    }


    // Modifie une caisse
    function modifie(){ 
        try {
        $query = "update caisse set
                    caisse_libelle = :caisse_libelle,
                    caisse_somme_debit = :caisse_somme_debit,
                    caisse_somme_credit = :caisse_somme_credit
                WHERE
                    caisse_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->caisse_id);
        $stmt->bindParam(':caisse_libelle', $this->caisse_libelle);
        $stmt->bindParam(':caisse_somme_debit', $this->caisse_somme_debit);
        $stmt->bindParam(':caisse_somme_credit', $this->caisse_somme_credit);
        if ($stmt->execute()) {
            return true;
        }   else{
            return $stmt->errorInfo();
        }
        }catch(PDOException $exception){
            echo "Modifie une caisse : " . $this->host . " : " . $exception->getMessage();
        }
    }

}
?>