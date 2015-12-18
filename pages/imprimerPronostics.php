<html>
<head>
	<title>Impression des jeux</title>
</head>
<body>
<div style="font-family:Courier New;font-size:1em;font-style:normal;font-weight:bold;line-height:13px;">
<?php
      
  // include database and object files
  include_once 'config/database.php';
  include_once 'config/util.php';
  include_once 'objects/saison.php';
  include_once 'objects/jeu.php';
  include_once 'objects/joueur.php';
  include_once 'objects/pronostic.php';
   
  // instantiate database and product object
  $database = new Database();
  $db = $database->getConnection();
   
  // Saison courante
  $saison = new Saison($db);
  $saison = $saison->chargeSaisonCourante();


  // Jeu courant
  $jeu = new Jeu($db);
  $jeu->saison_id = $saison->saison_id;
  $jeu->chargeDernierJeu();

  // Recherche la liste des joueurs
  $joueurs = new Joueur($db);
  $stmtJoueurs = $joueurs->litJoueurs();
  $listeJoueurs = $stmtJoueurs->fetchAll(PDO::FETCH_ASSOC);

  // Pronostics des joueurs
  $pronostic = new Pronostic($db);
  $pronostic->jeu_id = $jeu->jeu_id; 
  $stmtPronostics = $pronostic->litPronostics();
  $listePronostics = $stmtPronostics->fetchAll(PDO::FETCH_ASSOC);

  if (isset($_GET['page']))
  {
      $nbpassage       = $_GET['page'];
      $nbColonneMax = 8;
	    $car="&hearts;";

      if ($jeu)
      {
           $jeu_id            = $jeu->jeu_id;
           $dernier_jeu_id    = $jeu_id;
  		     echo "<table class=\"jeu\" id=\"tableauresultat\">";
           
           // Affiche les initiales de tous les joueurs
           $ListepronosticJoueur  = array();
           
           for($i=0;$i<sizeof($listeJoueurs);$i++)
           {
              $joueur=$listeJoueurs[$i];
              $joueur_id=$joueur["joueur_id"];
              // Recherche les pronostics de ces joueurs
              if ($listePronostics && isset($listePronostics[$i]) ) {
                    $ListepronosticJoueur[$i]=$listePronostics[$i];
                }                
           }
 
           // Nombre de passage à faire = NbJoueurs / nb de colonnes
           $maxPassage =(int) ((sizeof($listeJoueurs)) / $nbColonneMax );
           $reste = (sizeof($listeJoueurs)) % $nbColonneMax ;
           if ($reste>0) $maxPassage = $maxPassage + 1;

           // Jeu à 14 ou 15 matchs ?
           $nbMatchsDeCeJeu  = getNbMatchsDeCeJeu($jeu);
           
            // echo "<div style=\"height:1.5cm\">&nbsp;</div>";            
            $numjoueur = (($nbpassage-1)*$nbColonneMax);                                                    
            // Affiche tous les matchs
            for($i=1; $i <= $nbMatchsDeCeJeu ; $i++) 
            {                         

               // Affichage du premier résultat des tous les joueurs, du deuxième résultat, du troisième, ...
              $nbJoueursQuiOntJoués=0;
              $nbjoueurtraite = 0;
              for($ii=$numjoueur;$ii<sizeof($listeJoueurs);$ii++)
              {
                $nbjoueurtraite = $nbjoueurtraite + 1;
                if (isset($ListepronosticJoueur[$ii])) {
                  $pronostic = $ListepronosticJoueur[$ii];
                  $pronosticJeu = getPronostic($pronostic,$i);
                  
                  // Affichage du pronostic   
                  if ($pronosticJeu == "1")
                      echo $car."<span style=\"font-size:0.2em;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
                  else if ($pronosticJeu =="N")
                      echo "<span style=\"font-size:0.2em;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>".$car."<span style=\"font-size:0.2em;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
                  else if ($pronosticJeu == "2")
                      echo "<span style=\"font-size:0.2em;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>".$car."<span style=\"font-size:0.2em;\">&nbsp;</span>";
                  else
                      echo "<span style=\"font-size:0.2em;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style=\"font-size:0.1em;\">&nbsp;&nbsp;</span>";   
                  
                  if ($nbjoueurtraite == $nbColonneMax)      break;     
                echo "<span style=\"font-size:0.1em;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
                }
              }
              echo "<br />";                    
            }  
  	    }
    }
?>    
</div>
</body>
</html>