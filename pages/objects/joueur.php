<?php

// ======================================================== //
//               L E S    J O U E U R S                     //
// ======================================================== //

class Joueur {
 
    // database connection and table name
    private $conn;
 
    // object properties
    public $joueur_id;
    public $joueur_nom;
    public $joueur_initiales;
    public $joueur_pseudo;
    public $joueur_mdp;
    public $joueur_mail;
    public $joueur_der_cnx;
    public $joueur_der_navigateur;
    public $joueur_administrateur;  
    public $joueur_actif;
    public $joueur_log_chat;
    public $joueur_stat;
 
    public function __construct($db){
        $this->conn = $db;
    }

    // Retourne une saison dont l'id est précisé en paramètre
    function chargeJoueur()
    {
      // Requete pour retrouver l'ID de la dernière saison
      try {
            $query = "select * from joueur where actif = 'O' and joueur_id=:id LIMIT 0, 1";
            $stmt = $this->conn->prepare( $query );
            $stmt->bindParam(':id', $this->joueur_id);
            $stmt->execute();
            $numJoueur = $stmt->rowCount();            
            if ($numJoueur==1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $this->joueur_id=$joueur_id;
                $this->joueur_nom=$nom;
                $this->joueur_initiales=$initiale;
                $this->joueur_pseudo=$pseudo;
                $this->joueur_mdp=$mdp;
                $this->joueur_mail=$mail;
                $this->joueur_der_cnx=$der_cnx;
                $this->joueur_der_navigateur=$der_navigateur;
                $this->joueur_administrateur=$administrateur;  
                $this->joueur_actif=$actif;
                $this->joueur_log_chat=$log_chat;
                $this->joueur_stat=$stat;
            }
            else {
                return "Aucun enregistrement trouvé";
            }
        }
        catch(PDOException $exception) {
            return $exception->getMessage();
        }   
        return true;      
    }


    function litTousLesJoueurs()
    {
        $query = "select * from joueur order by joueur_id asc";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }


    function litJoueurs()
    {
        $query = "select * from joueur where actif = 'O' order by joueur_id asc";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }

    function litJoueursRetardatairesAppelDeFond()
    {
        $query = "select j.mail from joueur j where actif='O' and joueur_id not in (select joueur_id from appeljoueurs where appel_id = (select max(appel_id) from appeldefonds))";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }


    function authentifieJoueur()
    {
        try {
            $query = "select * from joueur where actif = 'O' and pseudo=:pseudo and mdp=:mdp LIMIT 0, 1";
            $stmt = $this->conn->prepare( $query );
            $stmt->bindParam(':pseudo', $this->joueur_pseudo);
            $stmt->bindParam(':mdp', $this->joueur_mdp);
            $stmt->execute();
            $numJoueur = $stmt->rowCount();            
            if ($numJoueur==1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $this->joueur_id=$joueur_id;
                $this->joueur_nom=$nom;
                $this->joueur_initiales=$initiale;
                $this->joueur_pseudo=$pseudo;
                $this->joueur_mdp=$mdp;
                $this->joueur_mail=$mail;
                $this->joueur_der_cnx=$der_cnx;
                $this->joueur_der_navigateur=$der_navigateur;
                $this->joueur_administrateur=$administrateur;  
                $this->joueur_actif=$actif;
                $this->joueur_log_chat=$log_chat;
                $this->joueur_stat=$stat;
            }
            else {
                return "Aucun enregistrement trouvé";
            }
        }
        catch(PDOException $exception) {
            return $exception->getMessage();
        }   
        return true;
    }         

    function connecteJoueur()
    {
        // Enregistrement de la date de dernière connexion
        $dateDerCnx= date("Y/m/d H:i:s"); 
        // Enregistrement du navigateur utilisé 
        $Browser=getBrowser();
        try {
            $query= "update joueur set der_cnx=:dateDerCnx, der_navigateur=:browser where joueur_id=:id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':dateDerCnx', $this->joueur_der_cnx);
            $stmt->bindParam(':browser', $this->joueur_pseudo);
            $stmt->bindParam(':id', $this->joueur_id);
            if ($stmt->execute()) {
                return true;
            }   
            else {
                return false;
            }
        }
        catch(PDOException $exception) {
            echo "Ajoute une saison : " . $this->host . " : " . $exception->getMessage();
        }        
    }

}
?>