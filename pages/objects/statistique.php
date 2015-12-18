<?php

// ======================================================== //
//            L E S    S T A T I S T I Q U E S              //
// ======================================================== //


class Statistique {
 
    // database connection and table name
    private $conn;
 
    public $saison_id;
    public $joueur_id;
    public $jeu_id;

    public function __construct($db){
        $this->conn = $db;
    }
 

    function litIndiceGainMoyenJoueurAF()
    {
        $query = "SELECT joueur_id, format((sum(pronostic.IndiceGain15)+sum(pronostic.IndiceGain7))/(select count(*) 
                  from pronostic p left join jeu on p.jeu_id=jeu.jeu_id where joueur_id='{$this->joueur_id}' 
                  and jeu.saison_id='{$this->saison_id}'),2) 'indMoyen'
                  FROM pronostic left join jeu on pronostic.jeu_id=jeu.jeu_id where jeu.saison_id='{$this->saison_id}' 
                  and joueur_id='{$this->joueur_id}'";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }

    function litMoyenneJusteJoueurAF()
    {
    	$query = "SELECT joueur_id, format(sum(pronostic.MoyenneJuste)/(select count(*) 
    	from pronostic p left join jeu on p.jeu_id=jeu.jeu_id where p.MoyenneJuste is not null and joueur_id='{$this->joueur_id}' and jeu.saison_id='{$this->saison_id}'),1) 'moyJuste'
    	FROM pronostic left join jeu on pronostic.jeu_id=jeu.jeu_id where pronostic.MoyenneJuste is not null and jeu.saison_id='{$this->saison_id}' and joueur_id='{$this->joueur_id}' ";
    	
    	$stmt = $this->conn->prepare( $query );
    	$stmt->execute();
    	return $stmt;
    }
    
    
	/*
	 * Ajoute une ligne dans la table statistique
	 * Une ligne correspond à la moyenne d'un joueur pour un jeu et une saison.
	 */
    function ajoute()
    {
    	try {
    		$jeu_id     = $this->jeu_id;
    		$saison_id  = $this->saison_id;
    		$joueur_id  = $this->joueur_id;
    		$valeur  	= $this->valeur;
    		$flash  	= $this->flash;
    		
    		// Ajout des statistiques de ce joueur
    		$query="insert into stat (saison_id, jeu_id, joueur_id, valeur, flash) 
    		values ('{$saison_id}', '{$jeu_id}', '{$joueur_id}', '{$valeur}', '{$flash}')";
    		
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
    		echo "Ajout des statistiques de ce joueur  : " . $this->host . " : " . $exception->getMessage();
    	}
    }
    

    /*
     * Efface les statistiques pour un jeu donné
     */
    function efface()
    {
    	try {
    		$jeu_id = $this->jeu_id;
    		// Supression des statistiques de ce jeu
    		$query= "delete from stat where jeu_id ='{$jeu_id}'";
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
    		echo "Suppression des statistiques de ce jeu : " . $this->host . " : " . $exception->getMessage();
    	}
    }
    
    
    
    
    function litJeuStat()
    {
        $query = "select jeu_id, joueur_id, flash, valeur from stat 
                  where saison_id='{$this->saison_id}' order by jeu_id, joueur_id";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }


    function litSommeGainsParSaison()
    {
        $query = "SELECT saison.saison_id 'saison_id', nom 'saison_nom', format(sum(somme),2) 'total',  
          (SELECT COUNT(*) FROM gain g where g.saison_id = saison.saison_id) 'nbgain', 
          (SELECT COUNT(*) FROM jeu j where j.saison_id = saison.saison_id) 'nbmatch',
          (SELECT COUNT(*) FROM pronostic p, jeu j where p.jeu_id = j.jeu_id and j.saison_id = saison.saison_id) 'nbprono'
          FROM saison left join gain on gain.saison_id=saison.saison_id group by saison_id";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }


    function litSommeGainsToutesSaisons()
    {
      $query = "SELECT nom,  format(sum(somme),2) 'total', (select count(*) from gain g 
                where g.joueur_id=gain.joueur_id) 'nbgain'
                FROM joueur left join  gain on gain.joueur_id=joueur.joueur_id 
                group by joueur.joueur_id order by sum(somme) desc, nom ";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }

  
    function litTopMeilleursGainsToutesSaisons()
    {
        $query = "SELECT j.nom 'jnom', format(somme,2) 'total', s.nom 'snom' 
          FROM gain g, joueur j, saison s
          where j.joueur_id=g.joueur_id and g.saison_id=s.saison_id
          order by somme desc limit 0,25";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }


    function litIndiceJeuxSaison()
    {
      $query = "SELECT titre, resultat.IndiceGain7 jeu7, resultat.IndiceGain15 jeu15 
          FROM resultat left join jeu on resultat.jeu_id=jeu.jeu_id
          where saison_id={$this->saison_id}
          order by jeu.jeu_id asc";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }


    function litMoyenneIndiceJeuxSaison()
    {
      $query = "SELECT format((sum(IndiceGain7)/(select count(*) 
          from resultat left join jeu on resultat.jeu_id=jeu.jeu_id   
          where IndiceGain7 <> 0  and saison_id='{$this->saison_id}')),2) 'moy7', 
          format((sum(IndiceGain15)/(select count(*) from resultat left join jeu on resultat.jeu_id=jeu.jeu_id  
          where IndiceGain15 <> 0 and saison_id='{$this->saison_id}')),2) 'moy15'  
          FROM resultat left join jeu on resultat.jeu_id=jeu.jeu_id  
          where saison_id='{$this->saison_id}'";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }


    function litCouleurSaison()
    {
      $query = "SELECT titre, vert, jaune, rouge, vert7, jaune7, rouge7
          FROM resultat left join jeu on resultat.jeu_id=jeu.jeu_id
          where saison_id='{$this->saison_id}'
          order by jeu.jeu_id asc";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }


    function litMoyenneCouleurSaison()
    {
      $query = "SELECT 
      format((sum(vert)/(select count(*) from resultat left join jeu on resultat.jeu_id=jeu.jeu_id where saison_id='{$this->saison_id}')),1) 'vert', 
      format((sum(jaune)/(select count(*) from resultat left join jeu on resultat.jeu_id=jeu.jeu_id where saison_id='{$this->saison_id}')),1) 'jaune',
      format((sum(rouge)/(select count(*) from resultat left join jeu on resultat.jeu_id=jeu.jeu_id where saison_id='{$this->saison_id}')),1) 'rouge',
      format((sum(vert7)/(select count(*) from resultat left join jeu on resultat.jeu_id=jeu.jeu_id where saison_id='{$this->saison_id}')),1) 'vert7', 
      format((sum(jaune7)/(select count(*) from resultat left join jeu on resultat.jeu_id=jeu.jeu_id where saison_id='{$this->saison_id}')),1) 'jaune7',
      format((sum(rouge7)/(select count(*) from resultat left join jeu on resultat.jeu_id=jeu.jeu_id where saison_id='{$this->saison_id}')),1) 'rouge7'
          FROM resultat left join jeu on resultat.jeu_id=jeu.jeu_id
          where saison_id='{$this->saison_id}'";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }


    function litResultatSaison()
    {
      $query = "select *
                from resultat 
              left join jeu on resultat.jeu_id=jeu.jeu_id 
              left join saison on jeu.saison_id=saison.saison_id 
              where saison.saison_id='{$this->saison_id}'";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }



    function litStatIndiceGain7Joueurs()
    {
      $query = "SELECT joueur.joueur_id jid, nom,  (select count(*) 
            from pronostic p left join jeu on p.jeu_id=jeu.jeu_id 
            where p.IndiceGain7 <> 0.00 
            and p.flash = 0 and p.joueur_id=joueur.joueur_id and jeu.saison_id='{$this->saison_id}') 'nbindice', 
            format(sum(pronostic.IndiceGain7)/(select count(*) 
            from pronostic p left join jeu on p.jeu_id=jeu.jeu_id where p.IndiceGain7 <> 0.00 and p.flash = 0 
            and p.joueur_id=joueur.joueur_id and jeu.saison_id='{$this->saison_id}'),2) 'moyenne'
            FROM pronostic left join jeu on pronostic.jeu_id=jeu.jeu_id left join joueur on pronostic.joueur_id=joueur.joueur_id 
            where jeu.saison_id='{$this->saison_id}' and flash = 0 and pronostic.IndiceGain7 <> 0 
            group by joueur.joueur_id order by moyenne desc, nom";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }


    function litStatIndiceGain15Joueurs()
    {
      $query = "SELECT joueur.joueur_id jid, nom,  (select count(*) 
              from pronostic p left join jeu on p.jeu_id=jeu.jeu_id 
              where p.IndiceGain15 <> 0.00 and p.flash = 0 and p.joueur_id=joueur.joueur_id and jeu.saison_id='{$this->saison_id}') 'nbindice', 
              format(sum(pronostic.IndiceGain15)/(select count(*) from pronostic p left join jeu on p.jeu_id=jeu.jeu_id 
              where p.IndiceGain15 <> 0.00 and p.flash = 0 and p.joueur_id=joueur.joueur_id and jeu.saison_id='{$this->saison_id}'),2) 'moyenne' 
              FROM pronostic left join jeu on pronostic.jeu_id=jeu.jeu_id left join joueur on pronostic.joueur_id=joueur.joueur_id 
              where jeu.saison_id='{$this->saison_id}' and flash = 0 and pronostic.IndiceGain15 <> 0 
              group by joueur.joueur_id order by moyenne desc, nom";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }


    function litStatIndiceGainJoueurs()
    {
      $query = "SELECT joueur.joueur_id jid, nom,  (select count(*) 
            from pronostic p left join jeu on p.jeu_id=jeu.jeu_id 
            where p.flash = 0 and p.joueur_id=joueur.joueur_id 
            and jeu.saison_id='{$this->saison_id}') 'nbindice', 
            format((sum(pronostic.IndiceGain15)+sum(pronostic.IndiceGain7))/(select count(*) 
            from pronostic p left join jeu on p.jeu_id=jeu.jeu_id where p.flash = 0 
            and p.joueur_id=joueur.joueur_id and jeu.saison_id='{$this->saison_id}'),2) 'moyenne'
            FROM pronostic left join jeu on pronostic.jeu_id=jeu.jeu_id left join joueur on pronostic.joueur_id=joueur.joueur_id 
            where jeu.saison_id='{$this->saison_id}' and flash = 0 group by joueur.joueur_id order by moyenne desc, nom";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }



}
?>