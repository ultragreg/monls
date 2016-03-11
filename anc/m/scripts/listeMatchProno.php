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
        $jeu        = getJeuCourant();
 			  if ($jeu)
 			  {
           $jeu_id     = getIDJeuCourant();        
           $pronostic  = getPronosticJoueur($joueur_id,$jeu_id); 
           // Lecture des champs de la ligne
           $titre        = $jeu["titre"];
           $bloque        = $jeu["bloque"];
  			   $commentaire  = $jeu["commentaire"];
  			   
           $nbMatchsDeCeJeu  = getNbMatchsDeCeJeu($jeu);
           $compteur = 0;  
           echo "[";
           if ($bloque=='1')
           {
                    echo "{\"bloque\":\"1\"}";
           }
           else
           {
               for($i=1; $i <= $nbMatchsDeCeJeu ; $i++) 
               {
                    $equiped     = $jeu["equipe".$i."d"];
                    $equipev     = $jeu["equipe".$i."v"];
                    
                    // Retrouve si la colonne 1, 2 et 3 est pronostiqué par le joueur
                    $jeuColonne1 = getPronosticEstJoue($pronostic,$i,'1');
                    $jeuColonne2 = getPronosticEstJoue($pronostic,$i,'N');
                    $jeuColonne3 = getPronosticEstJoue($pronostic,$i,'2');
                    echo "{\"d\":\"".htmlentities($equiped, ENT_QUOTES, "UTF-8")."\",\"v\":\"".htmlentities($equipev, ENT_QUOTES, "UTF-8")."\",\"p1\":\"".$jeuColonne1."\",\"pN\":\"".$jeuColonne2."\",\"p2\":\"".$jeuColonne3."\"}";
                    if ($i < $nbMatchsDeCeJeu)  echo ",";
               }
           }
           echo "]\n";     
        }
        else
        {
           echo "[{\"err\":\"Pas de match en-cours\"}]"; 
        }
        ferme_base($db_link);
        return 1;
    }
    else
    {
        echo "[{\"err\":\"Problème technique\"}]"; 
    }	
?>
