<?php 
    session_start();
    header('Content-Type: text/javascript; charset: UTF-8');
    include("inclusions.php");
    $db_link = ouvre_base();
    
    
    if ($db_link)
    {    
        $saison_id            = getIDSaisonCourante();
         // On recherche tous les jeux de la saison demandée et la liste des joueurs
         $listeJeux       = getListeJeuxSaison($saison_id);
         $listeJoueurs    = getListeJoueurs();
         // Affichage les initiales de tous les joueurs
         for($j=0;$j<sizeof($listeJoueurs);$j++)
         {
            $joueur=$listeJoueurs[$j];
            $initiale=$joueur["initiale"];
            
            // Pour le graphique, on récupère les initiales du joueurs
            $G_ListeJoueurs[$j]=$initiale;
            
            // Initialisation des tableaux
            $TotalMoyenneGenerale[$j] =0;
            $TotalMatchJoue[$j] = 0;
            $TotalMoyenneGeneraleJM1[$j] =0;
            $TotalMatchJoueJM1[$j] = 0;
         }     
  
         // Récupération des jeux de la table des statistiques
         $jeuxStat   = getJeuStat($saison_id);
               
         // Affichage de tous les jeux par ordre croissant
         $premierepasse=true;
         for($i=sizeof($listeJeux)-1; $i >= 0 ; $i--) 
         {
             $moyennejeu  = 0;
             $nbjeu       = 0;
             $jeu         = $listeJeux[$i];
             $jeu_id      = $jeu['jeu_id'];
             $titre       = $jeu['titre'];           
             $G_ListeJeux[$i]=$titre;
             
             if (!getJeuPresent($jeu_id,$jeuxStat))
             {      
                  continue;
             }
                          
             // Affichage de la moyenne des résultats pour ce jeu et pour chacun des joueurs 
             
             // Pour un jeu, on va rechercher tous les pronostics de ce joueur
             $meilleur=0;
             $mauvais=100;       
             // Première passe pour trouver le meilleur et le plus mauvais
             for($j=0;$j<sizeof($listeJoueurs);$j++)
             {
                $joueur=$listeJoueurs[$j];
                $joueur_id=$joueur["joueur_id"];

                $temp = getValeur($jeu_id, $joueur_id,$jeuxStat);
                if ($temp)  
                {
                    $moyenne =$temp["valeur"];
                    if ($moyenne>$meilleur)
                    {
                        $meilleur=$moyenne;
                    }
                    if ($moyenne<$mauvais)
                    {
                        $mauvais=$moyenne;
                    }                    
                }
              }
             // Affichage des moyennes de ce jeu pour chacun des joueurs
             for($j=0;$j<sizeof($listeJoueurs);$j++)
             {
                $joueur=$listeJoueurs[$j];
                $joueur_id=$joueur["joueur_id"];
                $temp = getValeur($jeu_id, $joueur_id,$jeuxStat);
                if ($temp)  
                {
                    $moyenne     = $temp["valeur"];
                    $moyennejeu  = $moyennejeu+round($moyenne,1);
                    $nbjeu       = $nbjeu+1;
                    $TotalMoyenneGenerale[$j] = $TotalMoyenneGenerale[$j]+round($moyenne,1);   
                    $TotalMatchJoue[$j] = $TotalMatchJoue[$j]+1;
                    if (!$premierepasse)
                    {
                          $TotalMoyenneGeneraleJM1[$j] = $TotalMoyenneGeneraleJM1[$j]+round($moyenne,1);   
                          $TotalMatchJoueJM1[$j] = $TotalMatchJoueJM1[$j]+1;
                    }
                }
                else
                {
                    $moyenne="";
                }                
             }
            $premierepasse=false;
        }
        
        // Affichage des moyennes générales sur l'ensemble des jeux pour chacun des joueurs
        $MoyenneGeneraleParJoueur = array();
        $MoyenneGeneraleParJoueurJM1 = array();
        $moyennejeu=0;
        $nbjeu=0;
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
              if ($TotalMatchJoue[$j])
              {
                  $moyenne=$TotalMoyenneGenerale[$j]/$TotalMatchJoue[$j]; 
              }
              else
                  $moyenne=0;
              $MoyenneGeneraleParJoueur[$j]=round($moyenne,1);   
                                 
              $moyennejeu  = $moyennejeu+round($moyenne,1);
              $nbjeu       = $nbjeu+1;
      
        }
  
        // Calcul des moyennes générales par joueur avant la dernière journée ! cela permettra de calculer l'évolution
        $moyennejeu=0;
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
              if ($TotalMatchJoueJM1[$j])
              {
                  $moyenne=$TotalMoyenneGeneraleJM1[$j]/$TotalMatchJoueJM1[$j]; 
              }
              else
                  $moyenne=0;
              $MoyenneGeneraleParJoueurJM1[$j]=round($moyenne,1);       
        }
        
        // Affichage des moyennes générales sur l'ensemble des jeux pour chacun des joueurs
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
              $MoyenneJoueur = $MoyenneGeneraleParJoueur[$j];
              $positionJoueur=1;
              for($k=0;$k<sizeof($listeJoueurs);$k++)
              {
                  if ($MoyenneGeneraleParJoueur[$k]>$MoyenneJoueur)
                  {
                      $positionJoueur++;
                  }
              }
              // echo "\">".$positionJoueur."</td>\n";   
        }
       
       // Evolution
        if (sizeof($listeJeux)>1)
        {  
                       
              $TableauClassementFinal = array();
              // Affichage des moyennes générales sur l'ensemble des jeux pour chacun des joueurs
              for($j=0;$j<sizeof($listeJoueurs);$j++)
              {
                    $joueur=$listeJoueurs[$j];
                    $nom=$joueur["nom"];
            
                    $MoyenneJoueur = $MoyenneGeneraleParJoueur[$j];
                    $positionJoueur=1;
                    for($k=0;$k<sizeof($listeJoueurs);$k++)
                    {
                        if ($MoyenneGeneraleParJoueur[$k]>$MoyenneJoueur)
                        {
                            $positionJoueur++;
                        }
                    }
                    
                    $MoyenneJoueurJM1 = $MoyenneGeneraleParJoueurJM1[$j];
                    $positionJoueurJM1=1;
                    for($k=0;$k<sizeof($listeJoueurs);$k++)
                    {
                        if ($MoyenneGeneraleParJoueurJM1[$k]>$MoyenneJoueurJM1)
                        {
                            $positionJoueurJM1++;
                        }
                    }
                    if ($positionJoueur<10)   $tempJSON="{\"pos\":\"0".$positionJoueur."\",\"moy\":\"".$MoyenneJoueur."\",\"jou\":\"".$nom."\",\"evo\":";
                    else                      $tempJSON="{\"pos\":\"".$positionJoueur."\",\"moy\":\"".$MoyenneJoueur."\",\"jou\":\"".$nom."\",\"evo\":";                            
                    // Si Première place, ce joueur est le meilleur, sinon, si c'est le dernier, c'est le plus mauvais
                    if ($positionJoueur<$positionJoueurJM1)         
                      {
                        $tempJSON=$tempJSON."\"U\",\"pl\":\"+".($positionJoueurJM1-$positionJoueur)."\"";
                        }              
                    else if ($positionJoueur>$positionJoueurJM1)    
                      {
                        $tempJSON=$tempJSON."\"D\",\"pl\":\"-".($positionJoueur-$positionJoueurJM1)."\"";   
                      }
                    else                                            
                      {
                        $tempJSON=$tempJSON."\"S\"";   
                      }
                    $tempJSON=$tempJSON."}";
                    $TableauClassementFinal[]=$tempJSON;
              } 
              // Tri et affichage
              echo "[";
              asort($TableauClassementFinal);
              $count = count($TableauClassementFinal);
              $j=0;
              foreach ($TableauClassementFinal as $key => $val) {
                  echo $val;
                  if ($j < $count-1) echo ",";
                  $j++;
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
