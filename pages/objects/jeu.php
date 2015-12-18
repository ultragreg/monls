<?php
class Jeu {
 
    private $conn;
 
    // object properties
    public $jeu_id;
    public $saison_id;
    public $jeu_titre;
    public $jeu_bloque;
    public $jeu_commentaire;
    public $jeu_equipe1d;
    public $jeu_equipe1v;
    public $jeu_equipe2d;
    public $jeu_equipe2v;
    public $jeu_equipe3d;  
    public $jeu_equipe3v;  
    public $jeu_equipe4d;  
    public $jeu_equipe4v;
    public $jeu_equipe5d;
    public $jeu_equipe5v;
    public $jeu_equipe6d;
    public $jeu_equipe6v;
    public $jeu_equipe7d;
    public $jeu_equipe7v;
    public $jeu_equipe8d;
    public $jeu_equipe8v;
    public $jeu_equipe9d;
    public $jeu_equipe9v;
    public $jeu_equipe10d;
    public $jeu_equipe10v;
    public $jeu_equipe11d;
    public $jeu_equipe11v;
    public $jeu_equipe12d;
    public $jeu_equipe12v;
    public $jeu_equipe13d;
    public $jeu_equipe13v;
    public $jeu_equipe14d;
    public $jeu_equipe14v;
    public $jeu_equipe15d;
    public $jeu_equipe15v;
    public $jeu_nombre;
    public $idSite;

 
    public function __construct($db){
        $this->conn = $db;
    }



    function litJeuxAvecRapports()
    {
        $query = "select distinct jeu.jeu_id, jeu.titre, jeu.commentaire from jeu, rapport where saison_id={$this->saison_id} and jeu.jeu_id=rapport.jeu_id order by jeu.jeu_id asc";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }


    function litJeux()
    {
        $query = "select * from jeu where saison_id={$this->saison_id} order by jeu_id asc";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }


    function chargeNombreJeux()
    {
      // Requete pour retrouver l'ID du dernier jeu
      $query = "select count(*) nombre from jeu where saison_id={$this->saison_id}";
      $stmt = $this->conn->prepare( $query );
      $stmt->execute();
      $nbr = $stmt->rowCount();
      if ($nbr>0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);
        $this->jeu_nombre=$nombre;
      }
      return $this;
    }


    function chargeJeu()
    {
      // Requete pour retrouver l'ID du dernier jeu
      $query = "select * from jeu where jeu_id={$this->jeu_id} Limit 0,1";
      $stmt = $this->conn->prepare( $query );
      $stmt->execute();
      $nbr = $stmt->rowCount();
      if ($nbr>0) {
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          extract($row);

          $this->jeu_id=$jeu_id;
          $this->saison_id=$saison_id;
          $this->jeu_titre=$titre;
          $this->jeu_bloque=$bloque;
          $this->jeu_commentaire=$commentaire;
          $this->jeu_equipe1d=$equipe1d;
          $this->jeu_equipe1v=$equipe1v;
          $this->jeu_equipe2d=$equipe2d;
          $this->jeu_equipe2v=$equipe2v;
          $this->jeu_equipe3d=$equipe3d;  
          $this->jeu_equipe3v=$equipe3v;  
          $this->jeu_equipe4d=$equipe4d;  
          $this->jeu_equipe4v=$equipe4v;
          $this->jeu_equipe5d=$equipe5d;
          $this->jeu_equipe5v=$equipe5v;
          $this->jeu_equipe6d=$equipe6d;
          $this->jeu_equipe6v=$equipe6v;
          $this->jeu_equipe7d=$equipe7d;
          $this->jeu_equipe7v=$equipe7v;
          $this->jeu_equipe8d=$equipe8d;
          $this->jeu_equipe8v=$equipe8v;
          $this->jeu_equipe9d=$equipe9d;
          $this->jeu_equipe9v=$equipe9v;
          $this->jeu_equipe10d=$equipe10d;
          $this->jeu_equipe10v=$equipe10v;
          $this->jeu_equipe11d=$equipe11d;
          $this->jeu_equipe11v=$equipe11v;
          $this->jeu_equipe12d=$equipe12d;
          $this->jeu_equipe12v=$equipe12v;
          $this->jeu_equipe13d=$equipe13d;
          $this->jeu_equipe13v=$equipe13v;
          $this->jeu_equipe14d=$equipe14d;
          $this->jeu_equipe14v=$equipe14v;
          $this->jeu_equipe15d=$equipe15d;
          $this->jeu_equipe15v=$equipe15v;
          $this->idSite=$idSite;  
    }
      return $this;
    }         



    function chargeDernierJeu()
    {
      // Requete pour retrouver l'ID du dernier jeu
      $query = "select * from jeu where saison_id={$this->saison_id} order by jeu_id desc Limit 0,1";

      $stmt = $this->conn->prepare( $query );
      $stmt->execute();
      $nbr = $stmt->rowCount();
      if ($nbr>0) {
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          extract($row);

          $this->jeu_id=$jeu_id;
          $this->saison_id=$saison_id;
          $this->jeu_titre=$titre;
          $this->jeu_bloque=$bloque;
          $this->jeu_commentaire=$commentaire;
          $this->jeu_equipe1d=$equipe1d;
          $this->jeu_equipe1v=$equipe1v;
          $this->jeu_equipe2d=$equipe2d;
          $this->jeu_equipe2v=$equipe2v;
          $this->jeu_equipe3d=$equipe3d;  
          $this->jeu_equipe3v=$equipe3v;  
          $this->jeu_equipe4d=$equipe4d;  
          $this->jeu_equipe4v=$equipe4v;
          $this->jeu_equipe5d=$equipe5d;
          $this->jeu_equipe5v=$equipe5v;
          $this->jeu_equipe6d=$equipe6d;
          $this->jeu_equipe6v=$equipe6v;
          $this->jeu_equipe7d=$equipe7d;
          $this->jeu_equipe7v=$equipe7v;
          $this->jeu_equipe8d=$equipe8d;
          $this->jeu_equipe8v=$equipe8v;
          $this->jeu_equipe9d=$equipe9d;
          $this->jeu_equipe9v=$equipe9v;
          $this->jeu_equipe10d=$equipe10d;
          $this->jeu_equipe10v=$equipe10v;
          $this->jeu_equipe11d=$equipe11d;
          $this->jeu_equipe11v=$equipe11v;
          $this->jeu_equipe12d=$equipe12d;
          $this->jeu_equipe12v=$equipe12v;
          $this->jeu_equipe13d=$equipe13d;
          $this->jeu_equipe13v=$equipe13v;
          $this->jeu_equipe14d=$equipe14d;
          $this->jeu_equipe14v=$equipe14v;
          $this->jeu_equipe15d=$equipe15d;
          $this->jeu_equipe15v=$equipe15v;
		      $this->idSite=$idSite;	
    }


      return $this;
    }         


    function bloque()
    {
        try {
        $query= "update jeu set bloque='1' where jeu_id={$this->jeu_id}";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            return true;
        }   else {
            return $stmt->errorInfo();
        }
        }catch(PDOException $exception){
            echo "Bloque un jeu : " . $this->host . " : " . $exception->getMessage();
        }        
    }


    function debloque()
    {
        try {
        $query= "update jeu set bloque='0' where jeu_id={$this->jeu_id}";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            return true;
        }   else {
            return $stmt->errorInfo();
        }
        }catch(PDOException $exception){
            echo "Débloque un jeu : " . $this->host . " : " . $exception->getMessage();
        }        
    }


    function ajoute()
    {
        try {
            $titre=addslashes($this->titre);
            $commentaire=addslashes($this->commentaire);
            $$bloque=$this->bloque;
            $equipe1d=addslashes($this->equipe1d);
            $equipe1v=addslashes($this->equipe1v);
            $equipe2d=addslashes($this->equipe2d);
            $equipe2v=addslashes($this->equipe2v);
            $equipe3d=addslashes($this->equipe3d);
            $equipe3v=addslashes($this->equipe3v);
            $equipe4d=addslashes($this->equipe4d);
            $equipe4v=addslashes($this->equipe4v);
            $equipe5d=addslashes($this->equipe5d);
            $equipe5v=addslashes($this->equipe5v);
            $equipe6d=addslashes($this->equipe6d);
            $equipe6v=addslashes($this->equipe6v);
            $equipe7d=addslashes($this->equipe7d);
            $equipe7v=addslashes($this->equipe7v);
            $equipe8d=addslashes($this->equipe8d);
            $equipe8v=addslashes($this->equipe8v);
            $equipe9d=addslashes($this->equipe9d);
            $equipe9v=addslashes($this->equipe9v);
            $equipe10d=addslashes($this->equipe10d);
            $equipe10v=addslashes($this->equipe10v);
            $equipe11d=addslashes($this->equipe11d);
            $equipe11v=addslashes($this->equipe11v);
            $equipe12d=addslashes($this->equipe12d);
            $equipe12v=addslashes($this->equipe12v);
            $equipe13d=addslashes($this->equipe13d);
            $equipe13v=addslashes($this->equipe13v);
            $equipe14d=addslashes($this->equipe14d);
            $equipe14v=addslashes($this->equipe14v);
            $equipe15d=addslashes($this->equipe15d);
            $equipe15v=addslashes($this->equipe15v);
            $idSite=addslashes($this->idSite);			
            // Un jeu est automatiquement associé à la dernière saison
            $saison_id = $this->saison_id;
            // Ajout de ce joueur
            $query= "insert into jeu ( saison_id,titre,bloque,commentaire,equipe1d,equipe1v,equipe2d,equipe2v,
                        equipe3d,equipe3v,equipe4d,equipe4v,equipe5d,equipe5v,equipe6d,equipe6v,equipe7d,
                        equipe7v,equipe8d,equipe8v,equipe9d,equipe9v,equipe10d,equipe10v,equipe11d,equipe11v,
                        equipe12d,equipe12v,equipe13d,equipe13v,equipe14d,equipe14v,equipe15d,equipe15v,idSite) 
                      values ({$saison_id},{$titre},{$bloque},{$commentaire},{$equipe1d},{$equipe1v},{$equipe2d},{$equipe2v},
                        {$equipe3d},{$equipe3v},{$equipe4d},{$equipe4v},{$equipe5d},{$equipe5v},{$equipe6d},{$equipe6v},{$equipe7d},
                        {$equipe7v},{$equipe8d},{$equipe8v},{$equipe9d},{$equipe9v},{$equipe10d},{$equipe10v},{$equipe11d},{$equipe11v},
                        {$equipe12d},{$equipe12v},{$equipe13d},{$equipe13v},{$equipe14d},{$equipe14v},{$equipe15d},{$equipe15v},{$idSite})";
          $stmt = $this->conn->prepare($query);
          if ($stmt->execute()) {
            return true;
          }   
          else {
            return $stmt->errorInfo();
          }
        }
        catch(PDOException $exception) {
            echo "Ajout d'un jeu : " . $this->host . " : " . $exception->getMessage();
        }
    }

    
    function modifie()
    {
        try {
            $jeu_id=addslashes($this->jeu_id);
            $titre=addslashes($this->titre);
            $commentaire=addslashes($this->commentaire);
            $$bloque=$this->bloque;
            $equipe1d=addslashes($this->equipe1d);
            $equipe1v=addslashes($this->equipe1v);
            $equipe2d=addslashes($this->equipe2d);
            $equipe2v=addslashes($this->equipe2v);
            $equipe3d=addslashes($this->equipe3d);
            $equipe3v=addslashes($this->equipe3v);
            $equipe4d=addslashes($this->equipe4d);
            $equipe4v=addslashes($this->equipe4v);
            $equipe5d=addslashes($this->equipe5d);
            $equipe5v=addslashes($this->equipe5v);
            $equipe6d=addslashes($this->equipe6d);
            $equipe6v=addslashes($this->equipe6v);
            $equipe7d=addslashes($this->equipe7d);
            $equipe7v=addslashes($this->equipe7v);
            $equipe8d=addslashes($this->equipe8d);
            $equipe8v=addslashes($this->equipe8v);
            $equipe9d=addslashes($this->equipe9d);
            $equipe9v=addslashes($this->equipe9v);
            $equipe10d=addslashes($this->equipe10d);
            $equipe10v=addslashes($this->equipe10v);
            $equipe11d=addslashes($this->equipe11d);
            $equipe11v=addslashes($this->equipe11v);
            $equipe12d=addslashes($this->equipe12d);
            $equipe12v=addslashes($this->equipe12v);
            $equipe13d=addslashes($this->equipe13d);
            $equipe13v=addslashes($this->equipe13v);
            $equipe14d=addslashes($this->equipe14d);
            $equipe14v=addslashes($this->equipe14v);
            $equipe15d=addslashes($this->equipe15d);
            $equipe15v=addslashes($this->equipe15v);
            $idSite=addslashes($this->idSite);			
            // Un jeu est automatiquement associé à la dernière saison
            $saison_id = $this->saison_id;
            // Modification d'un jeu
           $requete= "update jeu set titre='$titre', 
                      commentaire='$commentaire',
                      bloque='$bloque',
                      equipe1d='$equipe1d',
                      equipe1v='$equipe1v',
                      equipe2d='$equipe2d',
                      equipe2v='$equipe2v',
                      equipe3d='$equipe3d',
                      equipe3v='$equipe3v',
                      equipe4d='$equipe4d',
                      equipe4v='$equipe4v',
                      equipe5d='$equipe5d',
                      equipe5v='$equipe5v',
                      equipe6d='$equipe6d',
                      equipe6v='$equipe6v',
                      equipe7d='$equipe7d',
                      equipe7v='$equipe7v',
                      equipe8d='$equipe8d',
                      equipe8v='$equipe8v',
                      equipe9d='$equipe9d',
                      equipe9v='$equipe9v',
                      equipe10d='$equipe10d',
                      equipe10v='$equipe10v',
                      equipe11d='$equipe11d',
                      equipe11v='$equipe11v',
                      equipe12d='$equipe12d',
                      equipe12v='$equipe12v',
                      equipe13d='$equipe13d',
                      equipe13v='$equipe13v',
                      equipe14d='$equipe14d',
                      equipe14v='$equipe14v',
                      equipe15d='$equipe15d',
                      equipe15v='$equipe15v',
                      idSite='$idSite'
                      where jeu_id='$jeu_id'";
          $stmt = $this->conn->prepare($query);
          if ($stmt->execute()) {
            return true;
          }   
          else {
            return $stmt->errorInfo();
          }
        }
        catch(PDOException $exception) {
            echo "Modification d'un jeu : " . $this->host . " : " . $exception->getMessage();
        }
    }   


}
?>