<script src="http://code.jquery.com/jquery-latest.js"></script>

<script language="javascript" type="text/javascript">
function pop_it(the_form) {
   my_form = eval(the_form)
   window.open("classementpersonnalise.php", "popup", "height=740,width=740,menubar='no',toolbar='no',location='no',status='no',scrollbars='no'");
   my_form.target = "popup";
   my_form.submit();
}
</script>    
<script type="text/javascript">
    <!--                                                           
       function soumissionFormulaire(champ) 
       {
          if(champ.selectedIndex>=0)
             document.monform.submit()
       }
    //-->
    </script>   		
<div id="contenu">		  
<?php 

  
function getTDclassement($premierepasse, $titre)
{
    if ($premierepasse)
    {
       echo "<td colspan=\"2\" class=\"eqg\"><span id='valuePremiereLigne' style='font-size:15px; font-weight:900'>(+)&nbsp;&nbsp;</span>".$titre."</td>\n";
    } 
    else
    {
       echo "<td colspan=\"2\" class=\"eqg\"><span id='valuePremiereLigne' style='font-size:15px; font-weight:900'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>".$titre."</td>\n";
    } 
}

function getTRclassement($i, $premierepasse)
{
    if ($i%2 == 1) 
    {
        if ($premierepasse)
        {
            return "<tr id='premiereLigne' class='enreg impaire'>";
        } 
        else
        {
            return "<tr class='autresLignes enreg impaire'>";
        }
    }
    else
    {
        if ($premierepasse)
        {
            return "<tr id='premiereLigne' class='enreg'>";
        } 
        else
        {
            return "<tr class='autresLignes enreg'>";
        }
    }
}


		  $db_link = ouvre_base();
		  if ($db_link)
      {
           $saison_id            = getIDSaisonCourante();
  			   // Si choix d'un autre jeu, on renseigne l'identifiant 
  			   if (isset($_POST['numSaison']))
           {
                $saison_id       = $_POST['numSaison'];
                
           }   
  	       $listeSaisons         = getListeSaisons();
  	       
             ?> 	  		  
             <h2>Choix de la saison</h2>          
              <form method="post" name="monform" action="classementv2.php">                  
                <label class="texte" for="jeuform">Saison : 
                </label>                  
                <select class="selection" name="numSaison" onchange="soumissionFormulaire(this)" STYLE="width:200px">                    
            <?php
                     for($i=0;$i<sizeof($listeSaisons);$i++)
                     {
                        $saison=$listeSaisons[$i];  
                        // Lecture des propriétés du joueur
                        $ident       = $saison["saison_id"];
                        $titre       = $saison["nom"];
                        
                        echo "<option value=\"".$ident."\"";
                        if ($saison_id==$ident)
                            echo " selected>";
                        else
                            echo ">";
                        echo $titre;
                        echo "</option>";
                      }
                    
                          ?>                  
              </select>              
            </form>                        
          <?php
         // Gestion des gains !
	  		 echo "<h2>Classement par gains </h2>";
	  		 
         $listeJoueurs    = getListeJoueurs();
         $listeGains    = getListeMeilleursGains($saison_id);       
         if (sizeof($listeGains)>0)
         {
             echo "<table class='jeu'>";
             $sommetotale=0;
             for($i=0;$i<sizeof($listeGains);$i++)
             {
                $gain=$listeGains[$i];
                // Lecture des propriétés du joueur
                $joueur_id    = stripslashes($gain["joueur_id"]);
                $somme        = stripslashes($gain["total"]);
    
                if ($i%2 == 1) 
                    echo "<tr class='enreg impaire'>";
                else
                    echo "<tr class='enreg'>";
    
                $j=$i+1;
                echo "<td width=\"150\"><b>".getPosition($i)."</b></td>\n";   
                echo "<td width=\"150\">".getNomJoueurTab($listeJoueurs, $joueur_id)."</td>\n";
                echo "<td width=\"200\">".round($somme,1)." Euros</td>\n";
                $sommetotale+=$somme;
                echo "</tr>\n";
             }
              echo "</table>";
    	  		  echo "<h4>Soit un total de ".$sommetotale." Euros</h4>";
         }
         else
    	  		 echo "<h4>Pas le moindre Gain !</h4>";
      
      
           
         // Tableau pour le graphique 
         $G_ListeJeux           = array();
         $G_ListeJoueurs        = array();
         $G_ListeResultats      = array();    
      
          
         $TotalMoyenneGenerale  = array();
         $TotalMatchJoue        = array();
                 
         $TotalMoyenneGeneraleSF  = array();
         $TotalMatchJoueSF        = array();
         
         $TotalMoyenneGeneraleAF  = array();
         $TotalMatchJoueAF        = array();
         
         $TotalMoyenneGeneraleJM1  = array();
         $TotalMatchJoueJM1        = array();
         
         $TotalMoyenneGeneraleJM1SF  = array();
         $TotalMatchJoueJM1SF        = array();
         
         $TotalMoyenneGeneraleJM1AF  = array();
         $TotalMatchJoueJM1AF        = array();
       
 			   // Affichage du titre du prochain jeu
	  		 echo "<h2>Classement général</h2>";
	  		 
	  		 // Affichage des commentaires
	  		 //if($commentaire!=null)
         //{
         //   echo "<p>".$commentaire."</p>";
         //}
         echo "<h5>(*) Flash = fond rouge, Gain  = fond jaune</h5>";
  		     ?>  
               
        <table class="jeu">
          <tr>
            <td class="eqg">&nbsp;</td>
            <td class="eqg">&nbsp;</td>         
      <?php 
         // On recherche tous les jeux de la saison demandée et la liste des joueurs
         $listeJeux       = getListeJeuxSaison($saison_id);
         
         // Affichage des initiales de tous les joueurs
         for($j=0;$j<sizeof($listeJoueurs);$j++)
         {
            $joueur=$listeJoueurs[$j];
            $initiale=$joueur["initiale"];
            $joueur_id=$joueur["joueur_id"];
            $nom=$joueur["nom"];
            echo "<td class=\"infotitre\" title=\"$nom\">".$initiale."</td>";
            
            // Pour le graphique, on récupère les initiales du joueurs
            $G_ListeJoueurs[$j]=$initiale;
            
            // Initialisation des tableaux
            $TotalMoyenneGenerale[$j] =0;
            $TotalMatchJoue[$j] = 0;
            $TotalMoyenneGeneraleSF[$j] =0;
            $TotalMatchJoueSF[$j] = 0;
            $TotalMoyenneGeneraleAF[$j] =0;
            $TotalMatchJoueAF[$j] = 0;
            $TotalMoyenneGeneraleJM1[$j] =0;
            $TotalMatchJoueJM1[$j] = 0;
            $TotalMoyenneGeneraleJM1SF[$j] =0;
            $TotalMatchJoueJM1SF[$j] = 0;
            $TotalMoyenneGeneraleJM1AF[$j] =0;
            $TotalMatchJoueJM1AF[$j] = 0;            
            
            
         }
         echo "<td class=\"infotitre\" title=\"My\">Moy.</td></tr>";

         // Récupération des jeux de la table des statistiques
         $jeuxStat   = getJeuStat($saison_id);
          
         // Affichage de tous les jeux par ordre croissant
         $premierepasse=true;
         for($i=sizeof($listeJeux)-1; $i >= 0 ; $i--) 
         {
             $moyennejeu  = 0;
             $nbjeu       = 0;
             $jeu         = $listeJeux[$i];
             $titre       = $jeu['titre'];             
             $jeu_id      = $jeu['jeu_id'];
             
             if (!getJeuPresent($jeu_id,$jeuxStat))
             {      
                  continue;
             }
             echo getTRclassement($i, $premierepasse);          
             echo getTDclassement($premierepasse, $titre);
             
               $ListeFlashJoueur = array();
                     
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

                echo "<td class=\"info";
                $temp = getValeur($jeu_id, $joueur_id,$jeuxStat);
                if ($temp)  
                {
                    $moyenne =$temp["valeur"];
                    $flash   =$temp["flash"];
                   $ListeFlashJoueur[$j]=$flash;
                   // Meilleur score ? Pire score ?
                    if ($meilleur==$moyenne)     echo " meilleur";              
                    if ($mauvais==$moyenne)      echo " mauvais";
                    if (round($moyenne,1)>75)    echo " gain"; 
                    // Flash ou pas ?
                    if ($flash == "1")     echo " flash";
                    echo "\">".round($moyenne,1)."</td>\n";
                    $moyennejeu  = $moyennejeu+round($moyenne,1);
                    $nbjeu       = $nbjeu+1;

                    $TotalMoyenneGenerale[$j] = $TotalMoyenneGenerale[$j]+round($moyenne,1);   
                    $TotalMatchJoue[$j] = $TotalMatchJoue[$j]+1;
                    // Pronostic non flashé 
                    if ($ListeFlashJoueur[$j] != "1")
                    {   
                        $TotalMoyenneGeneraleSF[$j] = $TotalMoyenneGeneraleSF[$j]+round($moyenne,1);  
                        $TotalMatchJoueSF[$j]=$TotalMatchJoueSF[$j]+1;
                    }
                    // Pronostic flashé 
                    if ($ListeFlashJoueur[$j] == "1")
                    {   
                        $TotalMoyenneGeneraleAF[$j] = $TotalMoyenneGeneraleAF[$j]+round($moyenne,1);  
                        $TotalMatchJoueAF[$j]=$TotalMatchJoueAF[$j]+1;
                    }

                    if (!$premierepasse)
                    {
                          $TotalMoyenneGeneraleJM1[$j] = $TotalMoyenneGeneraleJM1[$j]+round($moyenne,1);   
                          $TotalMatchJoueJM1[$j] = $TotalMatchJoueJM1[$j]+1;
                          // Total sans les flash 
                          if ($ListeFlashJoueur[$j] != "1")
                          {   
                              $TotalMoyenneGeneraleJM1SF[$j] = $TotalMoyenneGeneraleJM1SF[$j]+round($moyenne,1);   
                              $TotalMatchJoueJM1SF[$j] = $TotalMatchJoueJM1SF[$j]+1; 
                          }
                          // Total que les flash 
                          if ($ListeFlashJoueur[$j] == "1")
                          {   
                              $TotalMoyenneGeneraleJM1AF[$j] = $TotalMoyenneGeneraleJM1AF[$j]+round($moyenne,1);   
                              $TotalMatchJoueJM1AF[$j] = $TotalMatchJoueJM1AF[$j]+1; 
                          }
                    }
                    
                }
                else
                {
                    $moyenne="";
                    echo "\">&nbsp;</td>\n";                
                }
                
                // Ce tableau sert pour le graphique
                if (is_numeric($moyenne))
                  $G_ListeResultats[$i][$j]= round($moyenne,1); 
                else     
                  $G_ListeResultats[$i][$j]= null; 
            }
            echo "<td class=\"info\">".round($moyennejeu/$nbjeu)."</td></tr>\n";
            $premierepasse=false;
        }
        
        // Première passe pour trouver le meilleur et le plus mauvais résultat de la saison
        $meilleur=0;
        $mauvais=100;          
        $meilleurSF=0;
        $mauvaisSF=100; 
        $meilleurAF=0;
        $mauvaisAF=100; 
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
            // echo "joueur:".$j."::::nb match joué".$TotalMatchJoue[$j]."<br>";
            if ($TotalMatchJoue[$j])
            {
                // Moyenne tous matchs
                $moyenne=0;
                if ($TotalMatchJoue[$j]!=0)   $moyenne=$TotalMoyenneGenerale[$j]/$TotalMatchJoue[$j]; 
                $moyenne=round($moyenne,1);
                if ($moyenne>$meilleur)   $meilleur=$moyenne;
                if ($moyenne<$mauvais)    $mauvais=$moyenne;
                // Moyenne matchs sans flash
                $moyenne=0;
                if ($TotalMatchJoueSF[$j]!=0)   $moyenne=$TotalMoyenneGeneraleSF[$j]/$TotalMatchJoueSF[$j]; 
                $moyenne=round($moyenne,1);
                if ($moyenne>$meilleurSF)   $meilleurSF=$moyenne;
                if ($moyenne<$mauvaisSF)    $mauvaisSF=$moyenne;
                // Moyenne matchs avec flash
                $moyenne=0;
                if ($TotalMatchJoueAF[$j]!=0)   $moyenne=$TotalMoyenneGeneraleAF[$j]/$TotalMatchJoueAF[$j]; 
                $moyenne=round($moyenne,1);
                if ($moyenne>$meilleurAF)   $meilleurAF=$moyenne;
                if ($moyenne<$mauvaisAF)    $mauvaisAF=$moyenne;
             }
        }
         
        
        echo "<tr><td>&nbsp;</td></tr>";
        
        // Noms des joueurs en bas
        echo "<tr><td colspan=\"2\" class=\"eqg\">&nbsp;</td>\n";
        // reAffichage les initiales de tous les joueurs
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
          $joueur=$listeJoueurs[$j];
          $initiale=$joueur["initiale"];
          $joueur_id=$joueur["joueur_id"];
          $nom=$joueur["nom"];
          echo "<td class=\"infotitre\" title=\"$nom\">".$initiale."</td>";
        }
        echo "<td class=\"infotitre\" title=\"My\">Moy.</td></tr>";
        echo "</tr>\n";




        // ***************** Moyenne générale totale  ***************** //

        echo "<tr class=\"impaire\"><td colspan=\"2\" class=\"eqg\">Moyenne générale (tous les pronostics)</td>\n";
        
        // Affichage des moyennes générales sur l'ensemble des jeux pour chacun des joueurs
        $MoyenneGeneraleParJoueur = array();
        $MoyenneGeneraleParJoueurJM1 = array();
		$RapportMoyenneIndiceParJoueur = array();
        $moyennejeu=0;
        $nbjeu=0;
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
              echo "<td class=\"infotitre";
              if ($TotalMatchJoue[$j])
              {
                  $moyenne=$TotalMoyenneGenerale[$j]/$TotalMatchJoue[$j]; 
                  if ($meilleur==round($moyenne,1))     echo " meilleur";              
                  if ($mauvais==round($moyenne,1))      echo " mauvais";   
              }
              else
                  $moyenne=0;
              echo "\">".round($moyenne,1)."</td>\n";   
              $MoyenneGeneraleParJoueur[$j]=round($moyenne,1);   
                                 
              $moyennejeu  = $moyennejeu+round($moyenne,1);
              $nbjeu       = $nbjeu+1;
			  
			  //Rapport entre la moyenne du joueur et son indice de gain moyen (prise de risque)
			  $joueur=$listeJoueurs[$j];
			  
			  //ancienne version, prenait en compte l'indice global, prono faux compris, ce qui n'est pas très juste, car la moyenne du joueur ne comprend que les résultats corrects.
			  //$res = getIndiceGainMoyenJoueurAF($saison_id,$joueur["joueur_id"]);
			  //$element=$res[0];
			  //$indiceMoyen = stripslashes($element["moyenne"]);
			  //$rapport = round($moyenne/(100-floatval($indiceMoyen)*10),2);
			  //$RapportMoyenneIndiceParJoueur[$j]=$rapport;
			  
			  $res = getMoyenneJusteJoueurAF($saison_id,$joueur["joueur_id"]);
			  $element=$res[0];
			  $moyenneJusteSaison = stripslashes($element["moyenneJuste"]);
			  $rapport = round($moyenne/$moyenneJusteSaison,2);
			  $RapportMoyenneIndiceParJoueur[$j]=$rapport;
        }
        echo "<td class=\"infotitre\">".round($moyennejeu/$nbjeu)."</td></tr>\n";
  
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
        
        
        echo "<tr>";
        echo "<td colspan=\"2\" class=\"eqg\">Classement</td>\n";
        
        // Affichage des moyennes générales sur l'ensemble des jeux pour chacun des joueurs
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
              echo "<td class=\"infotitre";
              $MoyenneJoueur = $MoyenneGeneraleParJoueur[$j];
              $positionJoueur=1;
              for($k=0;$k<sizeof($listeJoueurs);$k++)
              {
                  if ($MoyenneGeneraleParJoueur[$k]>$MoyenneJoueur)
                  {
                      $positionJoueur++;
                  }
              }
              // Si Première place, ce joueur est le meilleur, sinon, si c'est le dernier, c'est le plus mauvais
              if ($positionJoueur==1)                         echo " meilleur";              
              if ($positionJoueur==sizeof($listeJoueurs))     echo " mauvais";   
              echo "\">".$positionJoueur."</td>\n";   
        }
       
        echo "</tr><tr class=\"impaire\">";
        echo "<td colspan=\"2\" class=\"eqg\">Evolution</td>\n";
        
        // Affichage des moyennes générales sur l'ensemble des jeux pour chacun des joueurs
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
              echo "<td class=\"infotitre\">";
              $MoyenneJoueur = $MoyenneGeneraleParJoueur[$j];
              $positionJoueur=1;
              for($k=0;$k<sizeof($listeJoueurs);$k++)
              {
                  if ($MoyenneGeneraleParJoueur[$k]>$MoyenneJoueur)
                  {
                      $positionJoueur++;
                  }
              }
              
              $MoyenneJoueur = $MoyenneGeneraleParJoueurJM1[$j];
              $positionJoueurJM1=1;
              $nbjournee=0;
              for($k=0;$k<sizeof($listeJoueurs);$k++)
              {
                  if ($MoyenneGeneraleParJoueurJM1[$k]>$MoyenneJoueur)
                  {
                      $positionJoueurJM1++;
                      $nbjournee=$nbjournee+1;
                  }
              }
              if ($nbjournee>0)
              {
                // Si Première place, ce joueur est le meilleur, sinon, si c'est le dernier, c'est le plus mauvais
                if ($positionJoueur<$positionJoueurJM1)         echo "<img src=\"img/stand_up.gif\" alt=\"evolution\"  title=\"evolution\"/><span class='evol'>+".($positionJoueurJM1-$positionJoueur)."</span>";              
                else if ($positionJoueur>$positionJoueurJM1)    echo "<img src=\"img/stand_down.gif\" alt=\"evolution\"  title=\"evolution\"/><span class='evol'>-".($positionJoueur-$positionJoueurJM1)."</span>";    
                else                                            echo "<img src=\"img/stand_equal.gif\" alt=\"evolution\"  title=\"evolution\"/>"; 
              }
              else
              {
                                                               echo "<img src=\"img/stand_equal.gif\" alt=\"evolution\"  title=\"evolution\"/>"; 
              }
              echo "</td>\n";   
        }
        
        echo "</tr>";
		
		// Affichage des rapports entre la moyenne du joueur et son indice de gain moyen (prise de risque)
		echo "<tr>";
		echo "<td colspan=\"2\" class=\"eqg\">Rapport Moy. Joueur/Moy. % Joueurs = </td>\n";
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
              echo "<td class=\"infotitre\">".$RapportMoyenneIndiceParJoueur[$j]."</td>\n";   
        }
        echo "</tr>\n";

		echo "<tr class=\"impaire\">";
        echo "<td colspan=\"2\" class=\"eqg\">Classement Prise de Risque</td>\n";
        
        // Affichage du classement par rapport à la prise de risque
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
              echo "<td class=\"infotitre";
              $MoyenneJoueur = $RapportMoyenneIndiceParJoueur[$j];
              $positionJoueur=1;
              for($k=0;$k<sizeof($listeJoueurs);$k++)
              {
                  if ($RapportMoyenneIndiceParJoueur[$k]>$MoyenneJoueur)
                  {
                      $positionJoueur++;
                  }
              }
              // Si Première place, ce joueur est le meilleur, sinon, si c'est le dernier, c'est le plus mauvais
              if ($positionJoueur==1)                         echo " meilleur";              
              if ($positionJoueur==sizeof($listeJoueurs))     echo " mauvais";   
              echo "\">".$positionJoueur."</td>\n";   
        }
		
		
        // ******************************** SANS LES FLASHS ************************************************ //
		 echo "<tr><td colspan=2>&nbsp;</td><td colspan=24><font color='#6F2229' size=2><b>Explication du classement en fonction de la prise de risque :</b>
				<br>Rapport entre la moyenne de bons pronostics du joueur et le pourcentage de joueurs FDJ ayant fait les mêmes pronostics que lui, sur l'ensemble de la saison. 
				(Pourcentage stocké à chaque journée pour chaque joueur, se basant sur la Répartition de la page des pronostics).
				<br>Un joueur qui a 50% de bons pronostics, là où 70% des personnes ont trouvés les mêmes résultats, sera moins bien classé qu'un joueur ayant 50% de bons pronostics là où seulement 40% des personnes ont trouvés les mêmes résultat que lui.</font></td></tr>";
         
        echo "<tr><td>&nbsp;</td></tr>";

        // Noms des joueurs en bas
        echo "<tr><td colspan=\"2\" class=\"eqg\">&nbsp;</td>\n";
        // reAffichage les initiales de tous les joueurs
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
          $joueur=$listeJoueurs[$j];
          $initiale=$joueur["initiale"];
          $joueur_id=$joueur["joueur_id"];
          $nom=$joueur["nom"];
          echo "<td class=\"infotitre\" title=\"$nom\">".$initiale."</td>";
        }
        echo "<td class=\"infotitre\" title=\"My\">Moy.</td></tr>";
        echo "</tr>\n";
		       
        echo "<tr class=\"impaire\"><td colspan=\"2\" class=\"eqg\">Moyenne générale sans les flashs</td>\n";
        
        // Affichage des moyennes générales sur l'ensemble des jeux pour chacun des joueurs
        $MoyenneGeneraleParJoueur = array();
        $MoyenneGeneraleParJoueurJM1SF = array();
		$RapportMoyenneIndiceParJoueur = array();
        $moyennejeu=0;
        $nbjeu=0;
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
              echo "<td class=\"infotitre";
              if ($TotalMatchJoueSF[$j])
              {
                  $moyenne=$TotalMoyenneGeneraleSF[$j]/$TotalMatchJoueSF[$j]; 
                  if ($meilleurSF==round($moyenne,1))     echo " meilleur";     
                  if ($mauvaisSF==round($moyenne,1))     echo " mauvais";     
              }
              else
              {
                  echo " mauvais";
                  $moyenne=0;
              }
                  
              echo "\">".round($moyenne,1)."</td>\n";   
              $MoyenneGeneraleParJoueur[$j]=round($moyenne,1);   
                                 
              $moyennejeu  = $moyennejeu+round($moyenne,1);
              $nbjeu       = $nbjeu+1;
			  
			  //Rapport entre la moyenne du joueur et son indice de gain moyen (prise de risque)
			  $joueur=$listeJoueurs[$j];
			  
			  
			  
			  //ancienne version, prenait en compte l'indice global, prono faux compris, ce qui n'est pas très juste, car la moyenne du joueur ne comprend que les résultats corrects.
			  //$res = getIndiceGainMoyenJoueur($saison_id,$joueur["joueur_id"]);
			  //$element=$res[0];
			  //$indiceMoyen = stripslashes($element["moyenne"]);
			  //$rapport = round($moyenne/(100-floatval($indiceMoyen)*10),2);
			  //$RapportMoyenneIndiceParJoueur[$j]=$rapport;
			  
			  $res = getMoyenneJusteJoueur($saison_id,$joueur["joueur_id"]);
			  $element=$res[0];
			  $moyenneJusteSaison = stripslashes($element["moyenneJuste"]);
			  $rapport = round($moyenne/$moyenneJusteSaison,2);
			  $RapportMoyenneIndiceParJoueur[$j]=$rapport;
      
        }
        echo "<td class=\"infotitre\">".round($moyennejeu/$nbjeu)."</td></tr>\n";
  

        // Affichage du nombre de matchs non flashé pour chacun des joueurs
        echo "<tr><td colspan=\"2\" class=\"eqg\">Nombre de jeux</td>\n";
        $TotalGeneralMatchJoueSF=0;
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
              echo "<td class=\"infotitre\">".$TotalMatchJoueSF[$j]."</td>\n";                    
                  $TotalGeneralMatchJoueSF=$TotalGeneralMatchJoueSF+$TotalMatchJoueSF[$j];
        }
        echo "<td class=\"infotitre\">".$TotalGeneralMatchJoueSF."</td></tr>\n";
         
        

        // Calcul des moyennes générales par joueur avant la dernière journée ! cela permettra de calculer l'évolution
        $moyennejeu=0;
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
              if ($TotalMatchJoueJM1SF[$j])
              {
                  $moyenne=$TotalMoyenneGeneraleJM1SF[$j]/$TotalMatchJoueJM1SF[$j]; 
              }
              else
                  $moyenne=0;
              $MoyenneGeneraleParJoueurJM1SF[$j]=round($moyenne,1);       

        }
         
        
        // Affichage des moyennes générales sur l'ensemble des jeux pour chacun des joueurs
        echo "<tr class=\"impaire\"><td colspan=\"2\" class=\"eqg\">Classement</td>\n";
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
              echo "<td class=\"infotitre";
              $MoyenneJoueur = $MoyenneGeneraleParJoueur[$j];
              $positionJoueur=1;
              for($k=0;$k<sizeof($listeJoueurs);$k++)
              {
                  if ($MoyenneGeneraleParJoueur[$k]>$MoyenneJoueur)
                  {
                      $positionJoueur++;
                  }
              }
              // Si Première place, ce joueur est le meilleur, sinon, si c'est le dernier, c'est le plus mauvais
              if ($positionJoueur==1)                         echo " meilleur";              
              if ($positionJoueur==sizeof($listeJoueurs))     echo " mauvais";   
              echo "\">".$positionJoueur."</td>\n";   
        }
  
        echo "</tr>";
 
       
        echo "<tr><td colspan=\"2\" class=\"eqg\">Evolution</td>\n";
        
        // Affichage des moyennes générales sur l'ensemble des jeux pour chacun des joueurs
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
              echo "<td class=\"infotitre\">";
              $MoyenneJoueur = $MoyenneGeneraleParJoueur[$j];
              $positionJoueur=1;
              for($k=0;$k<sizeof($listeJoueurs);$k++)
              {
                  if ($MoyenneGeneraleParJoueur[$k]>$MoyenneJoueur)
                  {
                      $positionJoueur++;
                  }
              }
              
              $MoyenneJoueur = $MoyenneGeneraleParJoueurJM1SF[$j];
              $positionJoueurJM1SF=1;
              $nbjournee=0;
              for($k=0;$k<sizeof($listeJoueurs);$k++)
              {
                  if ($MoyenneGeneraleParJoueurJM1SF[$k]>$MoyenneJoueur)
                  {
                      $positionJoueurJM1SF++;
                      $nbjournee=$nbjournee+1;
                  }
              }
 
              if ($nbjournee>0)
              {
                  // Si Première place, ce joueur est le meilleur, sinon, si c'est le dernier, c'est le plus mauvais
                  if ($positionJoueur<$positionJoueurJM1SF)         echo "<img src=\"img/stand_up.gif\" alt=\"evolution\"  title=\"evolution\"/><span class='evol'>+".($positionJoueurJM1SF-$positionJoueur)."</span>";
                  else if ($positionJoueur>$positionJoueurJM1SF)    echo "<img src=\"img/stand_down.gif\" alt=\"evolution\"  title=\"evolution\"/><span class='evol'>-".($positionJoueur-$positionJoueurJM1SF)."</span>";    
                  else                                            echo "<img src=\"img/stand_equal.gif\" alt=\"evolution\"  title=\"evolution\"/>"; 
              }
              else
              {
                                                             echo "<img src=\"img/stand_equal.gif\" alt=\"evolution\"  title=\"evolution\"/>"; 
              }              
              echo "</td>\n";   
        }
             
        echo "</tr>";

		// Affichage des rapports entre la moyenne du joueur et son indice de gain moyen (prise de risque)
		echo "<tr class=\"impaire\">";
		echo "<td colspan=\"2\" class=\"eqg\">Rapport Moy. Joueur/Moy. % Joueurs = </td>\n";
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
              echo "<td class=\"infotitre\">".$RapportMoyenneIndiceParJoueur[$j]."</td>\n";   
        }
        echo "</tr>\n";

		echo "<tr>";
        echo "<td colspan=\"2\" class=\"eqg\">Classement Prise de Risque</td>\n";
        
        // Affichage du classement par rapport à la prise de risque
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
              echo "<td class=\"infotitre";
              $MoyenneJoueur = $RapportMoyenneIndiceParJoueur[$j];
              $positionJoueur=1;
              for($k=0;$k<sizeof($listeJoueurs);$k++)
              {
                  if ($RapportMoyenneIndiceParJoueur[$k]>$MoyenneJoueur)
                  {
                      $positionJoueur++;
                  }
              }
              // Si Première place, ce joueur est le meilleur, sinon, si c'est le dernier, c'est le plus mauvais
              if ($positionJoueur==1)                         echo " meilleur";              
              if ($positionJoueur==sizeof($listeJoueurs))     echo " mauvais";   
              echo "\">".$positionJoueur."</td>\n";   
        }


        
  
        // ******************************** AVEC LES FLASHS ************************************************ //
       
  
         
        echo "<tr><td>&nbsp;</td></tr>";

        // Noms des joueurs en bas
        echo "<tr><td colspan=\"2\" class=\"eqg\">&nbsp;</td>\n";
        // reAffichage les initiales de tous les joueurs
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
          $joueur=$listeJoueurs[$j];
          $initiale=$joueur["initiale"];
          $joueur_id=$joueur["joueur_id"];
          $nom=$joueur["nom"];
          echo "<td class=\"infotitre\" title=\"$nom\">".$initiale."</td>";
        }
        echo "<td class=\"infotitre\" title=\"My\">Moy.</td></tr>";
        echo "</tr>\n";


      
        // Affichage des moyennes générales sur l'ensemble des jeux pour chacun des joueurs
        echo "<tr class=\"impaire\"><td colspan=\"2\" class=\"eqg\">Moyenne générale que les flashs</td>\n";
        $MoyenneGeneraleParJoueur = array();
        $MoyenneGeneraleParJoueurJM1AF = array();
        $moyennejeu=0;
        $nbjeu=0;
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
              echo "<td class=\"infotitre";
              if ( isset($TotalMatchJoueAF[$j]) AND ($TotalMatchJoueAF[$j]!=0) )
              {
                  $moyenne=$TotalMoyenneGeneraleAF[$j]/$TotalMatchJoueAF[$j]; 
                  if ($meilleurAF==round($moyenne,1))     echo " meilleur";     
                  if ($mauvaisAF==round($moyenne,1))     echo " mauvais";     
                  echo "\">".round($moyenne,1)."</td>\n";   
                  $MoyenneGeneraleParJoueur[$j]=round($moyenne,1);   
                  $moyennejeu  = $moyennejeu+round($moyenne,1);
                  $nbjeu       = $nbjeu+1;
              }
              else
              {
                  $MoyenneGeneraleParJoueur[$j]=-1;   
                  echo "\"></td>\n";   
              }
        }
        echo "<td class=\"infotitre\">";
        if ($nbjeu!=0)
          echo round($moyennejeu/$nbjeu);
        else
          echo "";
        echo "</td></tr>\n";
  
  
        // Affichage du nombre de match flashé pour chacun des joueurs
        echo "<tr><td colspan=\"2\" class=\"eqg\">Nombre de jeux</td>\n";
        $TotalGeneralMatchJoueAF=0;
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
              if ($TotalMatchJoueAF[$j])
              {
                  echo "<td class=\"infotitre\">".$TotalMatchJoueAF[$j]."</td>\n";   
                  $TotalGeneralMatchJoueAF=$TotalGeneralMatchJoueAF+$TotalMatchJoueAF[$j];
              }
              else
              {
                  echo "<td class=\"infotitre\">&nbsp;</td>\n";   
              }
        }
        echo "<td class=\"infotitre\">".$TotalGeneralMatchJoueAF."</td></tr>\n";
       
         
 
        // Calcul des moyennes générales par joueur avant la dernière journée ! cela permettra de calculer l'évolution
        $moyennejeu=0;
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
              if ($TotalMatchJoueJM1AF[$j])
              {
                  $moyenne=$TotalMoyenneGeneraleJM1AF[$j]/$TotalMatchJoueJM1AF[$j]; 
              }
              else
                  $moyenne=0;
              $MoyenneGeneraleParJoueurJM1AF[$j]=round($moyenne,1);       
        }
         

        /*
        // Affichage du nombre de match flashé pour chacun des joueurs
        echo "<tr><td colspan=\"2\" class=\"eqg\">Pouet !</td>\n";
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
              echo "<td class=\"infotitre\">".$MoyenneGeneraleParJoueurJM1AF[$j]."</td>\n";   
              
        }
        echo "</tr>";
        */
       
               
        // Affichage des moyennes générales sur l'ensemble des jeux pour chacun des joueurs
        echo "<tr class=\"impaire\"><td colspan=\"2\" class=\"eqg\">Classement</td>\n";
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
              $MoyenneJoueur = $MoyenneGeneraleParJoueur[$j];
              if ($MoyenneJoueur>0)
              {
                echo "<td class=\"infotitre";
                $positionJoueur=1;
                for($k=0;$k<sizeof($listeJoueurs);$k++)
                {
                    if ($MoyenneGeneraleParJoueur[$k]>0)
                    {
                      if ($MoyenneGeneraleParJoueur[$k]>$MoyenneJoueur)
                      {
                          $positionJoueur++;
                      }
                    }
                }
                // Si Première place, ce joueur est le meilleur, sinon, si c'est le dernier, c'est le plus mauvais
                if ($positionJoueur==1)                         echo " meilleur";              
                if ($positionJoueur==sizeof($listeJoueurs))     echo " mauvais";   
                echo "\">".$positionJoueur."</td>\n";   
              }
              else
              {
                echo "<td class=\"infotitre\">&nbsp;</td>\n";   
              }
        }
  
        echo "</tr>";
 
       
        // Affichage des moyennes générales sur l'ensemble des jeux pour chacun des joueurs
        echo "<tr><td colspan=\"2\" class=\"eqg\">Evolution</td>\n";     
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
              $MoyenneJoueur = $MoyenneGeneraleParJoueur[$j];
              if ($MoyenneJoueur>0)
              {
                  echo "<td class=\"infotitre\">";
                  $positionJoueur=1;
                  for($k=0;$k<sizeof($listeJoueurs);$k++)
                  {
                      if ($MoyenneGeneraleParJoueur[$k]>$MoyenneJoueur)
                      {
                          $positionJoueur++;
                      }
                  }
                  
                  $MoyenneJoueur = $MoyenneGeneraleParJoueurJM1SF[$j];
                  $positionJoueurJM1SF=1;
                  $nbjournee=0;
                  for($k=0;$k<sizeof($listeJoueurs);$k++)
                  {
                      if ($MoyenneGeneraleParJoueurJM1SF[$k]>$MoyenneJoueur)
                      {
                          $positionJoueurJM1SF++;
                          $nbjournee=$nbjournee+1;
                      }
                  }

              if ($nbjournee>0)
              {
                  // Si Première place, ce joueur est le meilleur, sinon, si c'est le dernier, c'est le plus mauvais
                  if ($positionJoueur<$positionJoueurJM1SF)         echo "<img src=\"img/stand_up.gif\" alt=\"evolution\"  title=\"evolution\"/><span class='evol'>+".($positionJoueurJM1SF-$positionJoueur)."</span>";
                  else if ($positionJoueur>$positionJoueurJM1SF)    echo "<img src=\"img/stand_down.gif\" alt=\"evolution\"  title=\"evolution\"/><span class='evol'>-".($positionJoueur-$positionJoueurJM1SF)."</span>";
                  else                                            echo "<img src=\"img/stand_equal.gif\" alt=\"evolution\"  title=\"evolution\"/>"; 
              }
              else
              {
                                                              echo "<img src=\"img/stand_equal.gif\" alt=\"evolution\"  title=\"evolution\"/>"; 
              }              
                  echo "</td>\n";
              }
              else
              {
                echo "<td class=\"infotitre\">&nbsp;</td>\n";   
              }
   
        }
             
        echo "</tr></table>\n";
        
       
       
       
        
               
        // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@S T A T I S T I Q U E S @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ //
        
       
         // Affichage des statistiques
         echo "<h2>Statistiques</h2>\n";
         
         
         // CLASSEMENT PERSONNALISE
         
         
/*         echo "<h3>Classement personnalisé</h3><p style=\"color:red; font-weight: bold;\" >Choisissez un ou plusieurs joueurs</p>\n";
         echo "<form method=\"post\" action=\"classementpersonnalise.php\" name=\"statperso\">";
         echo "<table class='jeu'>";
         echo "<tr>";
         $compteur=0;
         for($j=0;$j<sizeof($listeJoueurs);$j++)
         {
            $compteur=$compteur+1;
            $joueur=$listeJoueurs[$j];
            $joueur_id=$joueur["joueur_id"];
            $nom=$joueur["nom"];
            echo "<td width=\"30%\"><input type=\"checkbox\" name=\"choix_joueur[]\" value=\"$joueur_id\">&nbsp;".$nom."</td>\n";
            if ($compteur ==3)
            {
              echo "</tr><tr>";
              $compteur=0;
            }
         }
         echo "</tr></table>"; 
         echo "<input type=\"button\" onclick=\"pop_it(statperso);\" value=\"Stat. Personnalisé\" />";
         echo "</form>";
*/
         // CLASSEMENT GENERAL 
         echo "<h3>Classement général</h3>\n";
        
                 
        include 'graph/php-ofc-library/open-flash-chart.php';
       
           $fichierImage= "graph/data1-".$saison_id.".json";
          // Si le graphique n'existe pas, on le génère
        $valmin=100;
        $valmax=0;  
          if (!file_exists($fichierImage)) 
          {
        
        $G_ListeResultatsMoyens = array();         
        //Recalcul des moyennes pour faire apparaitre les évolutions 
        for($i=0;$i<sizeof($listeJeux);$i++) 
        {
        
             $jeu         = $listeJeux[$i];
             $jeu_id      = $jeu['jeu_id'];
             // Recherche également des résultats de ce jeu
             $Resultat=getResultatJeu($jeu_id);
             // Si pas de résultat sur le premier match, on n'intègre pas ce pronostic dans le calcul ! 
             if (sizeof($Resultat)==0 || getResultatNumero($Resultat,1)=="")
             {
                continue;
             }
             
            for($j=0;$j<sizeof($listeJoueurs);$j++)
            {
                $nbMatch=0;
                $valeur=0;
                for($k=0;$k<=$i;$k++)
                {
                    if (isset($G_ListeResultats[$k][$j]) AND is_numeric($G_ListeResultats[$k][$j]))
                    {
                        $valeur=$valeur+$G_ListeResultats[$k][$j];
                        $nbMatch++;
                    }
                }
                if ($nbMatch>0)            
                {
                    $tempo=round($valeur/$nbMatch,1);
                    $G_ListeResultatsMoyens[$i][$j]=$tempo;
                    if ($tempo>$valmax)     $valmax=$tempo;
                    if ($tempo<$valmin)     $valmin=$tempo;
                    // echo $j . ":" . $valmin. " - " . $valmax . "<br>";
                }
                else
                    $G_ListeResultatsMoyens[$i][$j]=0;
                //echo "Jeux:".$i." joueur:".$j." : nbmatch:".$nbMatch." : total:".$G_ListeResultats[$i][$j]."<br>";
            }   
            //$G_ListeResultatsMoyens[$i+1][$j]=70;
        }
        
        
        
        $valmax=$valmax+5;
        $valmin=$valmin-5;
        $valmin=0;
      
        /*
        // Pour debugage
        for($i=0;$i<sizeof($listeJeux);$i++) 
        {
            echo "Jeux : ".$i.":";
            for($j=0;$j<sizeof($listeJoueurs);$j++)
            {
                    echo "Joueur:".$j.":".$G_ListeResultatsMoyens[$i][$j];
            }
            echo "<br />";
        }
       */
        
        
               $G_MesCouleurs = array('#D080C0','#EF00FF','#F04040','#008080','#104080',
                                      '#2F8080','#30FF80','#4080FF','#5F80C0','#6F0000',
                                      '#7080FF','#800040','#9F0080','#A04000','#BF8000',
                                      '#C08000','#0A00FF','#1A0040','#2A0080','#3A00FF',
                                      '#4A0000','#5A4000','#6A4000','#7A4040','#8A0080',
                                      '#9A0040','#AA0080','#BA0000','#CA8000','#DA8040',
                                      '#EA8080');
        
             
              $tab_libelles = array();      
              $data_val = array();       
              for( $i=0; $i<sizeof($listeJeux); $i++ )
              {
                  $chaine = preg_replace("#[^a-zA-Z]#", "", $listeJeux[$i]['titre']);                                                       

                  $tab_libelles[]= $chaine;
                  for($j=0;$j<sizeof($listeJoueurs);$j++)    
                  {
                      if (isset($G_ListeResultatsMoyens[$i][$j]))    $data_val[$j][] = $G_ListeResultatsMoyens[$i][$j];
                      //else $data_val[$j][] = 0;
                  }
              }
        
                       
              // Ordonnées                                 
              $y = new y_axis();
              $y->set_range( $valmin, $valmax, 5 ); 
              $y->set_stroke( 2 ); 
              $y->set_colour( "#d000d0" ); 
        
              // ToolTip      
              $t = new tooltip();
              $t->set_shadow( true );
              $t->set_stroke( 2 );
              $t->set_colour( "#6E604F" );
              $t->set_background_colour( "#BDB396" );
              $t->set_title_style( "{font-size: 14px; color: #CC2A43;}" );
              $t->set_body_style( "{font-size: 10px; font-weight: bold; color: #000000;}" );
        
              // titre des absisses                                 
              //$x_labels = new x_axis_labels();
              //$x_labels->set_steps( 1 );
              //$x_labels->set_vertical();
              //$x_labels->set_labels( $tab_libelles ); 
              //$x_labels->set_vertical();
        
              $labels = new x_axis_labels();
              
              $labels->set_labels ($tab_libelles);
              $labels->rotate(270);
        
              // Absisses                                 
              $x = new x_axis();
              //$x->set_labels( $x_labels );
              $x->set_stroke( 2 ); 
              $x->set_tick_height( 2 ); 
              $x->set_colour( "#d000d0" );
              $x->set_labels ( $labels );
        
              // Nouveau grAphique
              $chart = new open_flash_chart();
             
              $title = new title("Classement General");
              $chart->set_title( $title );
              $chart->set_y_axis( $y );
              $chart->set_x_axis( $x );
                    
              //$posy=0;
              
              for($j=0;$j<sizeof($listeJoueurs);$j++)
              {
                $type = new dot();
                   // if      ($posy==0)    $type = new dot();  
                   // else if ($posy==1)    $type = new solid_dot(); 
                   // else if ($posy==2)    $type = new hollow_dot();  
                   // else if ($posy==3)    $type = new star();  
                   // else if ($posy==4)    $type = new anchor();  
                   // else if ($posy==5)    $type = new bow();                                              
                $type->tooltip($listeJoueurs[$j]['nom']."<br>Jeu:#x_label#<br>Moy. #val#");
                
                $line = new line();
                $line->set_default_dot_style($type);
                $line->set_width( 1 );
                $line->set_colour( $G_MesCouleurs[$j] );        
                $line->set_values( $data_val[$j] );                                                           
                $line->set_key( $listeJoueurs[$j]["nom"], 10 ); 
                $chart->add_element( $line );
                //$posy=$posy+1;
                //if ($posy==6) $posy=0;
        
              }     
              
              // Tooltip du graphe !
              $chart->set_tooltip( $t );
                      
             $fp = fopen($fichierImage,"w"); // ouverture du fichier en écriture
             fputs($fp, $chart->toPrettyString()); // on écrit les données
             fclose($fp);
        }
        echo "<div id=\"my_chart1\"> </div>"; 


      
         echo "<h3>Evolution des joueurs sur les trois derniers jeux</h3>\n";
        
        
        $valmax=$valmax+5;
        $valmin=$valmin-5;
        $valmin=0;
      
        /*
        // Pour debugage
        for($i=0;$i<sizeof($listeJeux);$i++) 
        {
            echo "Jeux : ".$i.":";
            for($j=0;$j<sizeof($listeJoueurs);$j++)
            {
                    echo "Joueur:".$j.":".$G_ListeResultatsMoyens[$i][$j];
            }
            echo "<br />";
        }
       */
      
       
           $fichierImage= "graph/data2-".$saison_id.".json";
          // Si le graphique n'existe pas, on le génère
          if (!file_exists($fichierImage)) 
          {
              
        
        
               $G_MesCouleurs = array('#D080C0','#80C0D0','#D0B880');
              
              
              $tab_libelles2 = array();      
              $data_val2 = array();    
              if (sizeof($listeJeux) >3)
              {
                  for($j=0;$j<sizeof($listeJoueurs);$j++)    
                  {                                   
                      $tab_libelles2[]= $listeJoueurs[$j]['nom'];
                      for( $i=sizeof($listeJeux)-3; $i<sizeof($listeJeux); $i++ )
                      {                                    
                            if (isset($G_ListeResultatsMoyens[$i][$j]))   $data_val2[$i][] = $G_ListeResultatsMoyens[$i][$j];
                      }
                  }         
              }  
        
        
                      
              // Ordonnées                                 
              $y2 = new y_axis();
              $y2->set_range( $valmin, $valmax, 5 ); 
              $y2->set_stroke( 2 ); 
              $y2->set_colour( "#d000d0" ); 
        
              // ToolTip      
              $t2 = new tooltip();
              $t2->set_shadow( true );
              $t2->set_stroke( 2 );
              $t2->set_colour( "#6E604F" );
              $t2->set_background_colour( "#BDB396" );
              $t2->set_title_style( "{font-size: 14px; color: #CC2A43;}" );
              $t2->set_body_style( "{font-size: 10px; font-weight: bold; color: #000000;}" );
              
              
            // Nouveau grAphique
             $chart2 = new open_flash_chart();
                   
              //$posy=0;
                if (sizeof($listeJeux) >3)
             {
              $compteur=0;
              for( $i=sizeof($listeJeux)-3; $i<sizeof($listeJeux); $i++ )
              {       
                  // $tab_libelles2[]  = $listeJeux[$i]['titre'];;
               // $type2 = new dot();
                   // if      ($posy==0)    $type = new dot();  
                   // else if ($posy==1)    $type = new solid_dot(); 
                   // else if ($posy==2)    $type = new hollow_dot();  
                   // else if ($posy==3)    $type = new star();  
                   // else if ($posy==4)    $type = new anchor();  
                   // else if ($posy==5)    $type = new bow();                                              
               // $type2->tooltip($listeJoueurs[$j]['nom']."<br>Jeu:#x_label#<br>Moy. #val#");
                
                $bar_glass = new bar_glass();
               // $bar->set_default_dot_style($type2);
                //$bar->set_width( 1 );
                $bar_glass->set_colour( $G_MesCouleurs[$compteur] );    
                if (isset($data_val2[$i]))  $bar_glass->set_values( $data_val2[$i] );                                                           
                $bar_glass->set_key( $listeJeux[$i]["titre"], 10 ); 
                $chart2->add_element( $bar_glass );
                $compteur=$compteur+1;

                //$posy=$posy+1;
                //if ($posy==6) $posy=0;
              }
              }  
         
              // titre des absisses                                 
              //$x_labels = new x_axis_labels();
              //$x_labels->set_steps( 1 );
              //$x_labels->set_vertical();
              //$x_labels->set_labels( $tab_libelles ); 
              //$x_labels->set_vertical();
        
              $labels2 = new x_axis_labels();
              $labels2->set_labels ($tab_libelles2);
              $labels2->rotate(270);
 
              // Absisses                                 
              $x2 = new x_axis();
              //$x->set_labels( $x_labels );
              $x2->set_stroke( 2 ); 
              $x2->set_tick_height( 2 ); 
              $x2->set_colour( "#d000d0" );
              $x2->set_labels ( $labels2 );
       
              // Tooltip du graphe !
              $chart2->set_tooltip( $t );
             
              $title2 = new title("Evolution par joueur");
              $chart2->set_title( $title2 );
              $chart2->set_y_axis( $y2 );
              $chart2->set_x_axis( $x2 );
              
             $fp = fopen($fichierImage,"w"); // ouverture du fichier en écriture
             fputs($fp, $chart2->toPrettyString()); // on écrit les données
             fclose($fp);
        }
        echo "<div id=\"my_chart2\"> </div>"; 
        ferme_base($db_link);
      } 
      ?>     
      
      
      
      <script type="text/javascript" src="js/swfobject.js"></script>
<script type="text/javascript">
 
swfobject.embedSWF(
  "graph/open-flash-chart.swf", "my_chart1",
  "100%", "600", "9.0.0", "graph/expressInstall.swf",
  {"data-file":"<?php echo 'graph/data1-'.$saison_id.'.json' ?>"} );
 
 
swfobject.embedSWF(
  "graph/open-flash-chart.swf", "my_chart2",
  "75%", "600", "9.0.0", "graph/expressInstall.swf",
  {"data-file":"<?php echo 'graph/data2-'.$saison_id.'.json' ?>"} );


$("#premiereLigne").click(function(event) { pliageTableau() });

$(".autresLignes").click(function(event) { pliageTableau() });

function pliageTableau () {
    $(".autresLignes").toggle("fast");    
    
    $("#premiereLigne").toggleClass("vu");
    if ( $("#premiereLigne").hasClass("vu") ) {
      $('#valuePremiereLigne').html('(-)&nbsp;&nbsp;');
    } 
    else  {
      $('#valuePremiereLigne').html('(+)&nbsp;&nbsp;');
    }
}

</script>

</div>