<?php 
 
   session_start();
    header('Content-Type: text/javascript; charset: UTF-8');


    include("inclusions.php");
    if ( (isset($_GET['idj'])) && ($_GET['idj']!=0)  && ($_GET['idj']!='') )
    {        
        $idjr = $_GET['idj'];
        $type=1;
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
           $bloque      = $jeu["bloque"];
                                              
      	   // Affichage du titre du prochain jeu
      	   $titre        = $jeu["titre"];
      		 // echo "<h2>".$titre."</h2>\n";
    
           
           // Lecture des informations du joueur
           $joueur    = getJoueur($idjr); 
           $joueur_id = $joueur["joueur_id"];
           $joueur_nom = $joueur["nom"];
           // Lecture des informations de son dernier pronostic
           $pronostic=getPronosticJoueur($joueur_id,$jeu_id);             
           // Recherche du résultat de ce jeu
           $Resultat=getResultatJeu($jeu_id);  
           // Jeu à 14 ou 15 matchs ?
           $nbMatchsDeCeJeu  = getNbMatchsDeCeJeu($jeu);
/*
echo "[{\"n\":\"Test\", \"t\":\"1\"},{\"i\":\"2\",\"m\":\"c-d\",\"p\":\"2\"},{\"i\":\"2\",\"m\":\"Nancy-Paris&nbsp;(1)\",\"p\":\"N\"},{\"i\":\"1\",\"m\":\"Reims-Troyes&nbsp;(1N2)\",\"p\":\"2\"},{\"i\":\"1\",\"m\":\"Sochaux-Evian&nbsp;(N)\",\"p\":\"N\"},{\"i\":\"1\",\"m\":\"Toulouse-Brest&nbsp;(2)\",\"p\":\"2\"},{\"i\":\"2\",\"m\":\"AC Milan-Genoa&nbsp;(1)\",\"p\":\"N\"},{\"i\":\"2\",\"m\":\"Gil Vicente-Benfica&nbsp;(N)\",\"p\":\"2\"},{\"i\":\"1\",\"m\":\"Chelsea-Manchester United&nbsp;(N)\",\"p\":\"N\"},{\"i\":\"2\",\"m\":\"V&eacute;rone-Ajaccio&nbsp;(N)\",\"p\":\"2\"},{\"i\":\"3\",\"m\":\"Bastia-Bordeaux\",\"p\":\"N\"},{\"i\":\"2\",\"m\":\"Feyenoord-Ajax Amsterdam&nbsp;(N)\",\"p\":\"2\"},{\"i\":\"2\",\"m\":\"Fiorentina-Lazio Rome&nbsp;(1)\",\"p\":\"N\"},{\"i\":\"2\",\"m\":\"Naples-Chievo V&eacute;rone&nbsp;(N)\",\"p\":\"2\"},{\"i\":\"3\",\"m\":\"Marseille-Lyon\",\"p\":\"N\"}]\n";
return;
*/      
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
                if ( ($bloque==1) || ( (isset($_SESSION['id_joueur'])) && ($_SESSION['id_joueur']==$joueur_id) ) || (isset($_SESSION['admin']) && $_SESSION['admin']=='O') )
                {
                    echo "\"p\":\"".$pronosticJeu."\"";
                }
                else
                {
                    echo "\"p\":\"*\"";
                  }

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
