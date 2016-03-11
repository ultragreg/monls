<?php 
    session_start();
    header('Content-Type: text/javascript; charset: UTF-8');
    include("inclusions.php");




    $db_link = ouvre_base();
    
    if ($db_link)
    {
        $jeu = getJeuCourant();
        if ($jeu)
        {   
            $jeu_id            = $jeu["jeu_id"];
            $dernier_jeu_id    = $jeu_id;

            $listeJoueurs    = getListeJoueurs(); 
            $nbMatchsDeCeJeu = getNbMatchsDeCeJeu($jeu);  
            $Resultat        = getResultatJeu($jeu_id);   
            

            echo "[";
           $ListeCompletPronostic=getPronosticJeu($jeu_id);   
            for($j=0;$j<sizeof($listeJoueurs);$j++)
            {
                  $joueur=$listeJoueurs[$j];
                  $joueur_id=$joueur["joueur_id"];
                  $nom=$joueur["nom"];             
//                  $pronostic=getPronosticJoueur($joueur_id,$jeu_id);
                  $pronostic=extraitPronosticJoueur($ListeCompletPronostic,$joueur_id);
                  $flash=$pronostic["flash"];
                  $nbResultats=0;
                  $nbResultats7=0;
                  $nbBonResultats = 0;
                  $NbMatchsJoues  = 0;
                  for($i=1; $i <= $nbMatchsDeCeJeu ; $i++) 
                  {
                      $resultatJeu  = getResultatNumero($Resultat,$i);
                      if ($resultatJeu)            
                      {
                      $nbResultats=$nbResultats+1;
                      if ($i<=7) {
                      $nbResultats7=$nbResultats7+1;

                      }
                      } 

                      $pronosticJeu = getPronosticNumero($pronostic,$i);
                      if ($pronosticJeu)      $NbMatchsJoues++;
                      $posresultat = isResultatBon($pronosticJeu, $resultatJeu);
                      if ($posresultat)       $nbBonResultats++;
                  }
                  /* 
                  if ($NbMatchsJoues!=0)  
                      $moyenne=round(($nbBonResultats/$NbMatchsJoues*100),0);
                  else
                      $moyenne="";   
                  */

                 //  $moyenne=$ListeresultatOk[$i]/$NbMatchsJoues[$i]*100;
                  if ($NbMatchsJoues!=0 && $nbResultats<>0 && $nbResultats7<>0)
                  {
                      if ($NbMatchsJoues <= 7) 
                          $moyenne=$nbBonResultats/$nbResultats7*100;
                      else
                          $moyenne=$nbBonResultats/$nbResultats*100;
                     
                  }
                  else
                  {
                      $moyenne="";
                  }

                  // JSon   
                  echo "{";                  
                  echo "\"id\":\"".$joueur_id."\",";
                  echo "\"jo\":\"".$nom."\",";
                  echo "\"mo\":\"".round($moyenne,0)."\",";
                  echo "\"no\":\"".$nbBonResultats." / ".$NbMatchsJoues."\",";
                  echo "\"fl\":\"".$flash."\"";
                  echo "}";

                  if ($j < sizeof($listeJoueurs)-1)  echo ",";
                  
            }     
            echo "]\n";          
        }
        else
        {
           echo "[{\"err\":\"Aucun résultat trouvé\"}]"; 
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
