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
           $Resultat   = getResultatJeu($jeu_id);   
 			     // Lecture des champs de la ligne
  			   $titre        = $jeu["titre"];
           $bloque        = $jeu["bloque"];
  			   $commentaire  = $jeu["commentaire"];
  			   
           $nbMatchsDeCeJeu  = getNbMatchsDeCeJeu($jeu);
           $compteur = 0;  
           echo "[";
           if ($bloque<>'1')
           {
                    echo "{\"bloque\":\"1\"}";
           }
           else
           {
               echo "{\"titre\":\"Dernière saisie par ".$Resultat["nom"]." le ".formatte_date($Resultat["date"],1)."\"},\n";
               for($i=1; $i <= $nbMatchsDeCeJeu ; $i++) 
               {
                    $equiped     = $jeu["equipe".$i."d"];
                    $equipev     = $jeu["equipe".$i."v"];
                    
                    $resultatJeu  = getResultatNumero($Resultat,$i);
                    $jeuColonne1 = "";
                    $jeuColonneN = "";
                    $jeuColonne2 = "";
                    if (is_int(strpos($resultatJeu,"1"))!=false) $jeuColonne1 = "1";
                    if (is_int(strpos($resultatJeu,"N"))!=false) $jeuColonneN = "N";
                    if (is_int(strpos($resultatJeu,"2"))!=false) $jeuColonne2 = "2";
                    echo "{\"d\":\"".htmlentities($equiped, ENT_QUOTES, "UTF-8")."\",\"v\":\"".htmlentities($equipev, ENT_QUOTES, "UTF-8")."\",\"r1\":\"".$jeuColonne1."\",\"rN\":\"".$jeuColonneN."\",\"r2\":\"".$jeuColonne2."\"}";
                    if ($i < $nbMatchsDeCeJeu)  echo ",";
               }
           }
           echo "]\n";     
        }
        else
        {
           echo "[{\"err\":\"Pas de résultat\"}]"; 
        }
        ferme_base($db_link);
        return 1;
    }
    else
    {
        echo "[{\"err\":\"Problème technique\"}]"; 
    } 
?>
