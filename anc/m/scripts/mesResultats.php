<?php 
    session_start();
    header('Content-Type: text/javascript; charset: UTF-8');
    include("inclusions.php");
    if (isset($_SESSION['id_joueur']))
    {        
        $idjj=$_SESSION['id_joueur'];
        $type=0;
    }    
    else      return 0;
     
    $db_link = ouvre_base();
    
    if ($db_link)
    {
        $jeu = getJeuCourant();
        if ($jeu)
        {   
           $jeu_id            = $jeu["jeu_id"];
           $dernier_jeu_id    = $jeu_id;
                                              
      	   // Affichage du titre du prochain jeu
      	   $titre        = $jeu["titre"];
      		 // echo "<h2>".$titre."</h2>\n";
           
           // Lecture des informations du joueur
           $joueur    = getJoueur($idjj); 
           $joueur_id = $joueur["joueur_id"];
           $joueur_nom = $joueur["nom"];
           // Lecture des informations de son dernier pronostic
           $pronostic=getPronosticJoueur($joueur_id,$jeu_id);             
           // Recherche du résultat de ce jeu
           $Resultat=getResultatJeu($jeu_id);  
           // Jeu à 14 ou 15 matchs ?
           $nbMatchsDeCeJeu  = getNbMatchsDeCeJeu($jeu);
      
           // Affiche tous les matchs
           echo "[";
           echo "{\"n\":\"".$joueur_nom."\", \"t\":\"".$type."\"},"; 
           for($i=1; $i <= $nbMatchsDeCeJeu ; $i++) 
           {    
              $stylejeutrouve="";
              $nbBonResultats=0;             
              $equiped      = $jeu["equipe".$i."d"];
              $equipev      = $jeu["equipe".$i."v"];    
              // Résultat de ce jeu
              $resultatJeu  = getResultatNumero($Resultat,$i);
              // Pronostic de ce jeu
              $pronosticJeu = getPronosticNumero($pronostic,$i);
              // Le pronostic est bon ?
              $posresultat = isResultatBon($pronosticJeu, $resultatJeu);          
              echo "{";                                 
              // Affichage d'un icone pour montrer si résultat ok ou pas                           
              if ($posresultat)           
                  echo "\"i\":\"1\",";
              else if ((!$posresultat) && ($resultatJeu!="") && ($pronosticJeu!=""))
                  echo "\"i\":\"2\",";
              else                        
                  echo "\"i\":\"3\",";                                                                                                             
              // Affichage du match (nom des deux équipes et du résultat)  
              if ($resultatJeu!="")       echo "\"m\":\"".htmlentities($equiped, ENT_QUOTES, "UTF-8")."-".htmlentities($equipev, ENT_QUOTES, "UTF-8")."&nbsp;(".$resultatJeu.")\",";
              else                        echo "\"m\":\"".htmlentities($equiped, ENT_QUOTES, "UTF-8")."-".htmlentities($equipev, ENT_QUOTES, "UTF-8")."\",";            
              // Affichage du pronostic du joueur                           
              if ($pronosticJeu) 
              {
                echo "\"p\":\"".$pronosticJeu."\"";
              }
              else
              {
                echo "\"p\":\"\"";
              }
              echo "}";
              if ($i < $nbMatchsDeCeJeu)  echo ",";
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
    return 0;	
?>
