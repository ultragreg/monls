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
            $joueur_id=$joueur["joueur_id"];
            $nom=$joueur["nom"];
            
            // Pour le graphique, on récupère les initiales du joueurs
            $G_ListeJoueurs[$j]=$initiale;
            
            // Initialisation des tableaux
            $TotalMoyenneGenerale[$j] =0;
            $TotalMatchJoue[$j] = 0;
            $TotalMoyenneGeneraleJM1[$j] =0;
            $TotalMatchJoueJM1[$j] = 0;
         }     
                 
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
             // Recherche également des résultats de ce jeu
             $Resultat=getResultatJeu($jeu_id);
             
             // Si pas de résultat sur le premier match, on n'intègre pas ce pronostic dans le calcul ! 
             if (sizeof($Resultat)==0 || getResultatNumero($Resultat,1)=="")
             {
                continue;
             }              
             
             // Affichage de la moyenne des résultats pour ce jeu et pour chacun des joueurs 
             $Listepronostic  = array();
             $ListeresultatOk = array();
             $NbMatchsJoues   = array();
             $ListeFlashJoueur = array();
             
             // Pour un jeu, on va rechercher tous les pronostics de ce joueur
             $meilleur=0;
             $mauvais=100;
             for($j=0;$j<sizeof($listeJoueurs);$j++)
             {
                $joueur=$listeJoueurs[$j];
                $joueur_id=$joueur["joueur_id"];
                
                // Recherche les pronostics de ces joueurs
                $Listepronostic[$j]=getPronosticJoueur($joueur_id,$jeu_id);   
                
                // Flash ou pas ?              
                $tempprono = $Listepronostic[$j];
                $ListeFlashJoueur[$j]=$tempprono["flash"];
                
                // Initialisation des bons résultats à 0
                $ListeresultatOk[$j]=0;
                 
                // Initialisation du nombre de matchs joués à 0
                $NbMatchsJoues[$j]=0;
                
                // Pour ce joueur et ce jeu, on va lire tous les pronostics pour comparer au résultat final   
                for($k=1; $k <= 15 ; $k++) 
                {
                    $pronostic = $Listepronostic[$j];
                    $resultatJeu=getResultatNumero($Resultat,$k);
                    
                    $pronosticJeu = getPronosticNumero($pronostic,$k);
                    
                    // Le résultat est bon
                    $posresultat = isResultatBon($pronosticJeu, $resultatJeu);
                    if ($posresultat) 
                    {
                      $ListeresultatOk[$j]=$ListeresultatOk[$j]+1;
                    }
                    
                    // Nombre de match joués
                    if ($pronosticJeu)    $NbMatchsJoues[$j]=$NbMatchsJoues[$j]+1;
                
                }
                  
                // Calcul du meilleur ou le plus mauvais résultat 
                if ($NbMatchsJoues[$j])  
                {
                    $moyenne=$ListeresultatOk[$j]/$NbMatchsJoues[$j]*100;
                    $moyenne=round($moyenne,0);
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
                if ($NbMatchsJoues[$j])  
                {
                    $moyenne=$ListeresultatOk[$j]/$NbMatchsJoues[$j]*100;                       
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
                
                // Ce tableau sert pour le graphique
                if (is_numeric($moyenne))
                  $G_ListeResultats[$i][$j]= round($moyenne,1); 
                else     
                  $G_ListeResultats[$i][$j]= null; 
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
                    if ($positionJoueur<$positionJoueurJM1)         $tempJSON=$tempJSON."\"U\"";              
                    else if ($positionJoueur>$positionJoueurJM1)    $tempJSON=$tempJSON."\"D\"";    
                    else                                            $tempJSON=$tempJSON."\"S\"";   
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
        ferme_base($db_link);        
    }	
?>
