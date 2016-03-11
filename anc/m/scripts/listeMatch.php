<?php 
    session_start();
    header('Content-Type: text/javascript; charset: UTF-8');
    include("inclusions.php");
    if (!isset($_SESSION['id_joueur'])) 
    {
      // Authentification correcte
      header('Location: index.php');
    }
    $joueur_id=$_SESSION['id_joueur'];
    $db_link = ouvre_base();
    if ($db_link)
    {
        $jeu_id     = getIDJeuCourant();        
        $pronostic  = getPronosticJoueur($joueur_id,$jeu_id); 
        $jeu        = getJeuCourant();
 			  if ($jeu)
 			  {
 			     // Lecture des champs de la ligne
  			   $titre        = $jeu["titre"];
  			   $commentaire  = $jeu["commentaire"];
  			   
           $nbMatchsDeCeJeu  = getNbMatchsDeCeJeu($jeu);
           $compteur = 0;  
           echo "[";
           for($i=1; $i <= $nbMatchsDeCeJeu ; $i++) 
           {
                $equiped     = $jeu["equipe".$i."d"];
                $equipev     = $jeu["equipe".$i."v"];
                
                // Retrouve si la colonne 1, 2 et 3 est pronostiqué par le joueur
                $jeuColonne1 = getPronosticEstJoue($pronostic,$i,'1');
                $jeuColonne2 = getPronosticEstJoue($pronostic,$i,'N');
                $jeuColonne3 = getPronosticEstJoue($pronostic,$i,'2');
                echo "{\"eqd\":\"".$equiped."\",\"eqv\":\"".$equipev."\",\"pro1\":\"".$jeuColonne1."\",\"proN\":\"".$jeuColonne2."\",\"pro2\":\"".$jeuColonne3."\"}";
                if ($i < $nbMatchsDeCeJeu)  echo ",";
           }
           echo "]\n";     
        }
        ferme_base($db_link); 
    }	
?>
