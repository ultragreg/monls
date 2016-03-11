<?php
  include("../admin/config.php");  
  include("../admin/fonctionnel.php");
  

  // Vérifie si le cookie de connexion existe
  isCookieOk();


		echo "<div id=\"contenu\">";		
    echo "<h4 id=\"bienvenue1\"></h4>"; 
    echo "<h4 id=\"bienvenue2\"></h4>";
    

  	  $db_link = ouvre_base();
  	  if ($db_link)
      {

  		  $jeu = getJeuCourant();
  		  if ($jeu)
  		  {
 		  
           $jeu_id            = $jeu["jeu_id"];
           $dernier_jeu_id    = $jeu_id;
  			   
  			   // Si choix d'un autre jeu, on renseigne l'identifiant 
  			   if (isset($_POST['jeuform']))
           {
                $jeu_id       = $_POST['jeuform'];
                $jeu          = getJeu($jeu_id);
           }          

  			   // Affichage de l'appel de fond
  			   $appel_id = getIDDernierAppel();
  			   $listeJoueursRetardataires = getListeAppelJoueursRetardataires($appel_id);
  
          if (sizeof($listeJoueursRetardataires)>0)
           {		   
  	  		     echo "<h2>Appel de fond en-cours !!!</h2>";
  	  		     
               if (sizeof($listeJoueursRetardataires)>1)
  	  		         echo "<p style=\"font-weight:900\">Avis aux retardataires : ";
  	  		     else
  	  		         echo "<p style=\"font-weight:900\">Avis au retardataire : ";
               for ($i=0;$i<sizeof($listeJoueursRetardataires);$i++)
               {
                  if ($i != 0)    echo ", ";
                  $joueur=$listeJoueursRetardataires[$i];
                  echo $joueur["nom"];
               }
           }
           echo "</p>\n";
                                  
  		     // Lecture des champs de la ligne
  			   $titre        = $jeu["titre"];
  			   $commentaire  = $jeu["commentaire"];
  
  			   // Affichage du titre du prochain jeu
  	  		 echo "<h2>".$titre."</h2>";
  	  		 
  	  		 // Affichage des commentaires
  	  		 if($commentaire!=null)
           {
              echo "<p>".$commentaire."</p>\n";
           }

           // Affiche les initiales de tous les joueurs
           $listeJoueurs    = getListeJoueurs();
           $Listepronostic  = array();
           $ListeresultatOk = array();
           $NbMatchsJoues   = array();


           echo "<div data-role=\"collapsible-set\">\n";
           $ListeCompletPronostic=getPronosticJeu($jeu_id);   
           for($i=0;$i<sizeof($listeJoueurs);$i++)
           {
              $joueur=$listeJoueurs[$i];
              $initiale=$joueur["initiale"];
              $joueur_id=$joueur["joueur_id"];
              $nom=$joueur["nom"];
              
              // Recherche les pronostics de ces joueurs
//              $Listepronostic[$i]=getPronosticJoueur($joueur_id,$jeu_id);   
              $Listepronostic[$i]=extraitPronosticJoueur($ListeCompletPronostic,$joueur_id);
              
              
              //    echo "<td title=\"$nom\" class=\"infotitre";
              
              $tempprono = $Listepronostic[$i];
              
              // On affiche en couleur rouge que le joueur a joué un flash
              // if ($tempprono["flash"] == "1") 
              //    echo "2";
              
              // echo "\" >".$initiale."</td>\n";

                            
              // Initialisation des bons résultats à 0
              $ListeresultatOk[$i]=0;
               
              // Initialisation du nombre de matchs joués à 0
              $NbMatchsJoues[$i]=0;
           }
              
           // Recherche du résultat de ce jeu
           $Resultat=getResultatJeu($jeu_id);  
           
           
           // Jeu à 14 ou 15 matchs ?
           $nbMatchsDeCeJeu  = getNbMatchsDeCeJeu($jeu);

            $bloque      = $jeu["bloque"];
           // Affiche tous les matchs
           for($i=1; $i <= $nbMatchsDeCeJeu ; $i++) 
           {
                
              $equiped     = $jeu["equipe".$i."d"];
              $equipev     = $jeu["equipe".$i."v"];
                                 
              $resultatJeu=getResultatNumero($Resultat,$i);
  
              echo "<div data-role=\"collapsible\" data-collapsed=\"true\">";
    
                           
              // Affichage du premier résultat des tous les joueurs, du deuxième résultat, du troisième, ...

              $nbBonResultats=0;
              $nbJoueursQuiOntJoués=0;
              $iicpt=0;

              echo "<p>";
              for($ii=0;$ii<sizeof($listeJoueurs);$ii++)
              {                
                $stylejeutrouve="";
                $pronostic = $Listepronostic[$ii];
                $pronosticJeu = getPronosticNumero($pronostic,$i);
            
                // Le pronostic est bon ?
                $posresultat = isResultatBon($pronosticJeu, $resultatJeu);
  
                if ($posresultat) 
                {
                  $ListeresultatOk[$ii]=$ListeresultatOk[$ii]+1;
                  $stylejeutrouve="class=\"jeutrouve\"";
                  // On incrémente le nombre de bons résultats pour ce jeu !
                  $nbBonResultats++;
                }
                else if ($resultatJeu!="")
                {
                   $stylejeutrouve="class=\"jeunontrouve\"";

                }
                else
                {
                   $stylejeutrouve="class=\"jeuenattente\"";

                }
                
                // On incrémente le nombre de joueurs qui ont joué à ce jeu
                if ($pronosticJeu)      $nbJoueursQuiOntJoués++;        

                // Nombre de match joués
                if ($pronosticJeu)    $NbMatchsJoues[$ii]=$NbMatchsJoues[$ii]+1;
                                
                // Affichage du pronostic    
                $joueur=$listeJoueurs[$ii];

                $jjid=$joueur["joueur_id"];
                $initiale=$joueur["initiale"];
                
                //echo $ii."->".($ii%5)." !";
                
                if ($pronosticJeu) 
                {
                    $iicpt=$iicpt+1;
                    // Si le jeu est bloqué ou 
                    // Si c'est le joueur de ce jeu qui est connecté ou 
                    // Si le joueur connecté est administrateur, 
                    // On affiche les pronostics
                    if ( ($bloque==1) || ( (isset($_SESSION['id_joueur'])) && ($_SESSION['id_joueur']==$jjid) ) || (isset($_SESSION['admin']) && $_SESSION['admin']=='O') )
                    {
                        echo "<span ".$stylejeutrouve.">".$initiale.":".$pronosticJeu."</span>";
                    }
                    else
                    {
                        // Affichage d'une étoile si jeu sinon espace
                        echo "<span ".$stylejeutrouve.">".$initiale.":*</span>";
                    }

                    if ($ii<sizeof($listeJoueurs)-1)         echo ", ";    
                    //if ($iicpt!=0 && $iicpt%5 == 0) echo "<br />";
                }
              }   
              echo "</p>\n";
                          
              echo "<h3>".htmlentities($equiped, ENT_QUOTES, "UTF-8")."-".htmlentities($equipev, ENT_QUOTES, "UTF-8")."\n";   
              // Affichage du résultat définitif
              if ($resultatJeu) 
              {
                echo " (".$resultatJeu.")";
                //echo "<span class=\"ui-li-count ui-btn-up-b ui-btn-corner-all compteur\" > ".round(($nbBonResultats/$nbJoueursQuiOntJoués)*100,0)."%</span>";
              }
              echo "</h3>\n";              


              echo "</div>\n";
                    
          }
 
 
              
          /*
          
          // Affichage des totaux
           echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td class=\"eqd\">Total&nbsp;</td>";
           for($i=0;$i<sizeof($listeJoueurs);$i++)
           {
              $resultatOk=$ListeresultatOk[$i];
              echo "<td class=\"infotitre\">".$resultatOk."</td>";
           }
           echo "<td>&nbsp;</td><td>&nbsp;</td>";
  
           // Premier passage pour retrouver le meilleur et le plus mauvais résultat
           $meilleur=0;
           $mauvais=100;
           for($i=0;$i<sizeof($listeJoueurs);$i++)
           {
              if ($NbMatchsJoues[$i])  
              {
                  $moyenne=$ListeresultatOk[$i]/$NbMatchsJoues[$i]*100;
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
  
           // Affichage des moyennes
           echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td class=\"eqd\">Moyenne (en %)&nbsp;</td>";
           for($i=0;$i<sizeof($listeJoueurs);$i++)
           {
              if ($NbMatchsJoues[$i])  
                  $moyenne=$ListeresultatOk[$i]/$NbMatchsJoues[$i]*100;
              else
                  $moyenne="";
              echo "<td class=\"infotitre";
              if ($meilleur==round($moyenne,0))     echo " meilleur";              
              if ($mauvais==round($moyenne,0))      echo " mauvais";              
              echo "\">".round($moyenne,0)."</td>";
           }
           
           echo "</tr>\n";
           
           
           
           
            */
// echo "</ul>";
            echo "</div>";
           
  	    }
        ferme_base($db_link);
      } 
      

      ?>
  
   </div>