    <script type="text/javascript">
    <!--
    function soumissionFormulaire(champ) 
    {
      if(champ.selectedIndex>=0)
       document.monform.submit()
   }
   function bascule(elem)
   {
   etat=document.getElementById(elem).style.display;
   if(etat=="none"){
   document.getElementById(elem).style.display="block";
   }
   else{
   document.getElementById(elem).style.display="none";
   }
   }
    //-->
    </script>

    <div id="contenu">		


      <?php
      
	  

      $db_link = ouvre_base();
      if ($db_link)
      {

         // Si choix d'un autre jeu, on renseigne l'identifiant 
         $courant           = True;
		 $stat = 'N';
         if (isset($_POST['jeuform']))
         {
          $jeu_id       = $_POST['jeuform'];
          $jeu          = getJeu($jeu_id);
          $saison_id    = $jeu["saison_id"];
          if ($jeu_id != getIDJeuCourant())
          {
            $courant      = False;
          }
        }
        else
        {
          $jeu        = getJeuCourant();
          $saison_id  = getIDSaisonCourante();
          if ($jeu)
            $jeu_id     = $jeu["jeu_id"];
          else
            $jeu_id="";
        }

        if (!$jeu)
        {
          echo "<h2>Pas de jeu en-cours</h2>";
        }
        else
        {

              // Génération d'un flash pour un joueur si on est administrateur 
              if (isset($_SESSION['id_joueur']) && $_SESSION['admin']=='O' && isset($_GET['flash']))
              {
                    $joueur_id_flash = $_GET['flash'];
                    $pronostic = getPronosticJoueur($joueur_id_flash, $jeu_id);
                    if (getPronosticNumero($pronostic,1)=="" && getPronosticNumero($pronostic,2)=="" && getPronosticNumero($pronostic,3)=="" &&
                    getPronosticNumero($pronostic,4)=="" && getPronosticNumero($pronostic,5)=="" && getPronosticNumero($pronostic,6)=="" &&
                    getPronosticNumero($pronostic,7)=="")
                    {  
                        setMiseAJourPronosticAleatoire7($jeu_id,$joueur_id_flash);

                        // Dans le cas de saisie, on supprime les images contenant les graphiques
                        $fichierImage="graph/data1-".$saison_id.".json";
                        if (file_exists($fichierImage))   {    unlink($fichierImage);     }  
                        $fichierImage="graph/data2-".$saison_id.".json";
                        if (file_exists($fichierImage))   {    unlink($fichierImage);     }
                    }
              }


              // Gestion des blocages/déblocages du jeu courant pour un joueur si on est administrateur 
              if (isset($_SESSION['id_joueur']) && $_SESSION['admin']=='O' && isset($_GET['bloque']))
              {
                    $tempBloqueVal = $_GET['bloque'];
                    $pronostic = setBlocageJeu($jeu_id, $tempBloqueVal);
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
              $bloque        = $jeu["bloque"];
			  $invisible      = $jeu["invisible"];

        			   // Affichage du titre du prochain jeu
              echo "<h2>".$titre."</h2>";

        	  		 // Affichage des commentaires
              if($commentaire!=null)
              {
                echo "<p>".$commentaire."</p>\n";
              }
              if (isset($_SESSION['id_joueur']))
              {

        	           // Si autorisé, on précharge la liste des jeux !
                $listeJeux = getListeJeux();
                ?>
                <form method="post" name="monform" action="index.php">
                  <?php  
                  echo "<a href=\"prochainjeu.php?idjeu=".$jeu_id."\" class=\"miniAction\">Vos Pronostics</a>";
                  ?>
                  <label class="texte" for="jeuform">Choix de la journée : </label>
                  <select class="selection" name="jeuform" onchange="soumissionFormulaire(this)">
                    <?php
                    for($i=0;$i<sizeof($listeJeux);$i++)
                    {
                      $jeu2=$listeJeux[$i];
                            // Lecture des propriétés du joueur
                      $ident       = $jeu2["jeu_id"];
                      $titre       = $jeu2["titre"];

                      echo "<option value=\"".$ident."\"";
                      if ($jeu_id==$ident) {
                        echo " selected>";
                      }
                      else
                      {
                        echo ">";
                      }
                      echo $titre;
                      echo "</option>";
                    }

                    ?>
                  </select>
                </form>
                <?php
				// recup stat joueur.
				$joueur_id = $_SESSION['id_joueur'];
				$joueurSession    = getUtilisateurID($joueur_id);
				$stat = $joueurSession['stat'];
              }

           // Affiche les initiales de tous les joueurs
        $listeJoueurs    = getListeJoueurs();
        $ListeCompletPronostic  = getPronosticJeu($jeu_id);
        $Listepronostic  = array();
        $ListeresultatOk = array();
        $NbMatchsJoues   = array();
		
		

           // Fonctions réservé à l'administrateur
           // 1. Faire des éditions
           // 2. Possibilités de faire des flashs pour les retardataires
        if (isset($_SESSION['id_joueur']) && $_SESSION['admin']=='O' && $courant==True) 
        {
          $retouralaligne=0;
          echo "<div id='groupeflash'>";

          echo "<h6>Administration de ce jeu</h6>";

          echo "<h7>Joueurs à flasher</h7>";
		  $nbJoueurAFlasher=0;
          for($i=0;$i<sizeof($listeJoueurs);$i++)
          {
            $joueur=$listeJoueurs[$i];
            $joueur_id=$joueur["joueur_id"];
            $nom=$joueur["nom"];
            $pronostic = extraitPronosticJoueur($ListeCompletPronostic,$joueur_id);
            if (getPronosticNumero($pronostic,1)=="" && getPronosticNumero($pronostic,2)=="" && getPronosticNumero($pronostic,3)=="" &&
             getPronosticNumero($pronostic,4)=="" && getPronosticNumero($pronostic,5)=="" && getPronosticNumero($pronostic,6)=="" &&
             getPronosticNumero($pronostic,7)=="")
            {  
              echo "<a href=\"index.php?flash=".$joueur_id."\" class=\"miniAction\">$nom</a>";
			  $nbJoueurAFlasher++;
            }
          }

          echo "<br /><h7>Impression</h7>";
            // Possibilité d'imprimer si on est administrateur 
          echo "<a href=\"scripts/imprimerPronostics.php?page=1\" class=\"miniAction\">Impression Page:1</a>";
          echo "<a href=\"scripts/imprimerPronostics.php?page=2\" class=\"miniAction\">Impression Page:2</a>";
          echo "<a href=\"scripts/imprimerPronostics.php?page=3\" class=\"miniAction\">Impression Page:3</a>";
          echo "<a href=\"scripts/imprimerPronostics.php?page=4\" class=\"miniAction\">Impression Page:4</a>";

          echo "<br /><h7>Fin des pronostics?</h7>";
          if ($bloque=='1')
          {
            $actionBloque='0';
            $tempBloque='Déblocage';
          }
          else
          {
            $actionBloque='1';
            $tempBloque='Blocage';
          }
		  if ($nbJoueurAFlasher==0) {
			echo "<a href=\"index.php?bloque=".$actionBloque."\" class=\"miniAction\">$tempBloque</a>";
			}
			else
			{
				echo "<a href=\"index.php?bloque=0\" class=\"miniAction\">Blocage impossible, manque joueur(s)</a>";
			}

          echo "</div>";
        }


           // Recherche les pronostics de ces joueurs
        echo "<h5>(*) Flash joué sur les colonnes avec un fond rouge</h5>";
        echo "<table class='jeu' id='tableauresultat'><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";

        for($i=0;$i<sizeof($listeJoueurs);$i++)
        {
          $joueur=$listeJoueurs[$i];
          $initiale=$joueur["initiale"];
          $joueur_id=$joueur["joueur_id"];
          $nom=$joueur["nom"];


          echo "<td title=\"$nom\" class=\"infotitre";
          $Listepronostic[$i]=extraitPronosticJoueur($ListeCompletPronostic,$joueur_id);
          $tempprono = $Listepronostic[$i];

              // On affiche en couleur rouge que le joueur a joué un flash
          if ($tempprono["flash"] == "1") 
            echo "2";

          echo "\" >".$initiale."</td>\n";


              // Initialisation des bons résultats à 0
          $ListeresultatOk[$i]=0;

              // Initialisation du nombre de matchs joués à 0
          $NbMatchsJoues[$i]=0;
        }

           // Recherche du résultat de ce jeu
        $Resultat=getResultatJeu($jeu_id);   
        $nbResultats=0;
        $nbResultats7=0;
        $tmpResultat="Saisi par ".$Resultat["nom"]." le ".formatte_date($Resultat["date"],1);
        echo "<td class=\"infotitre\" title=\"$tmpResultat\" style=\"width:35px\">Res.</td>";
        echo "<td class=\"infotitre\" title=\"Moyenne\" style=\"width:35px\">% Bon</td>";

        echo "</tr>";

           // Jeu à 14 ou 15 matchs ?
        $nbMatchsDeCeJeu  = getNbMatchsDeCeJeu($jeu);



           // Affiche tous les matchs
        for($i=1; $i <= $nbMatchsDeCeJeu ; $i++) 
        {

          $equiped     = $jeu["equipe".$i."d"];
          $equipev     = $jeu["equipe".$i."v"];
          $bloque      = $jeu["bloque"];
		  

          if ($i%2 == 1) 
            echo "<tr class='enreg impaire'>";
          else
            echo "<tr class='enreg'>";

          echo "<td><img src='img/".$i.".png' alt='img/".$i."' /></td>\n";    
//              $pronosticOrdi = getPronostic($i);
//              $pronosticOrdi = getPronostic($i);

//              if  ($pronosticOrdi=='1' or $pronosticOrdi=='N')   
//              {
//                  echo "<td class=\"eqd pronostic\">".$equiped."</td>";
//                  }
//              else
          echo "<td class=\"eqd\">".$equiped."</td>";
//              if  ($pronosticOrdi=='2' or $pronosticOrdi=='N')   
//                  echo "<td class=\"eqg pronostic \">".$equipev."</td>\n";   
//              else
          echo "<td class=\"eqg\">".$equipev."</td>\n";

              // Affichage du premier résultat des tous les joueurs, du deuxième résultat, du troisième, ...
          $resultatJeu=getResultatNumero($Resultat,$i);
          $nbBonResultats=0;
          $nbJoueursQuiOntJoués=0;
          for($ii=0;$ii<sizeof($listeJoueurs);$ii++)
          {
            $jj=$listeJoueurs[$ii];
            $jjid=$jj["joueur_id"];

            $pronostic = $Listepronostic[$ii];
            $pronosticJeu = getPronosticNumero($pronostic,$i);
            echo "<td class=\"";
            // Si le jeu est bloqué, on affiche les pronostics
            if ($i==$nbMatchsDeCeJeu)     echo "infobas";
            else                          echo "info";    

            // Le pronostic est bon ?
            $posresultat = isResultatBon($pronosticJeu, $resultatJeu);
            if ($posresultat) 
            {
              $ListeresultatOk[$ii]=$ListeresultatOk[$ii]+1;
              // Si le jeu est bloqué ou 
              // Si c'est le joueur de ce jeu qui est connecté ou 
              // Si le joueur connecté est administrateur, 
              // On affiche les pronostics
              if ( ($bloque==1) || ( (isset($_SESSION['id_joueur'])) && ($_SESSION['id_joueur']==$jjid) ) || (isset($_SESSION['admin']) && $_SESSION['admin']=='O') )
              {
                echo " jeutrouve";
              }
                  // On incrémente le nombre de bons résultats pour ce jeu !
              $nbBonResultats++;
            }

            // On incrémente le nombre de joueurs qui ont joué à ce jeu
            if ($pronosticJeu)      $nbJoueursQuiOntJoués++;        

            // Nombre de match joués
            if ($pronosticJeu)    $NbMatchsJoues[$ii]=$NbMatchsJoues[$ii]+1;

              // Si le jeu est bloqué ou 
              // Si c'est le joueur de ce jeu qui est connecté ou 
              // Si le joueur connecté est administrateur, 
              // On affiche les pronostics
             //if ( ($bloque==1) || ( (isset($_SESSION['id_joueur'])) && ($_SESSION['id_joueur']==$jjid) ) || (isset($_SESSION['admin']) && $_SESSION['admin']=='O') )
			 if ( ($bloque==0) && ($invisible==1) && ($_SESSION['id_joueur']<>$jjid) )
             {
                  // Affichage d'une étoile si jeu sinon espace
				  if ($pronosticJeu)      echo "\">*</td>\n";
                 else                    echo "\">&nbsp;</td>\n";
             }
            else
             {  
                /*if ($pronosticJeu=="1") {
					echo "\">".$pronosticJeu."&nbsp;&nbsp;&nbsp;</td>\n";
				}
				else if ($pronosticJeu=="N") {
					echo "\">".$pronosticJeu."</td>\n";
				}
				else if ($pronosticJeu=="2") {
					echo "\">&nbsp;&nbsp;&nbsp;".$pronosticJeu."</td>\n";
				}
				else if ($pronosticJeu=="1N") {
					echo "\">".$pronosticJeu."&nbsp;&nbsp;</td>\n";
				}
				else if ($pronosticJeu=="N2") {
					echo "\">&nbsp;&nbsp;".$pronosticJeu."</td>\n";
				}
				else if ($pronosticJeu=="12") {
					echo "\">1&nbsp;&nbsp;2</td>\n";
				}
				else if ($pronosticJeu=="1N2") {
					echo "\">".$pronosticJeu."</td>\n";
				}
				else {*/
					echo "\">".$pronosticJeu."</td>\n";
				//}
            }
          }




              // Affichage du résultat définitif
          echo "<td class=\"";
          if ($i==$nbMatchsDeCeJeu)       echo "infobas";
          else                            echo "info";
          if ($resultatJeu) 
            echo "\"><span class=\"prono\">".$resultatJeu."</span></td>\n";
          else
            echo "\"><span class=\"prono\">&nbsp;</span></td>\n";
          if ($resultatJeu)            
          {
            $nbResultats=$nbResultats+1;
            if ($i<=7) {
              $nbResultats7=$nbResultats7+1;

            }
          } 

              // Affichage du nombre moyen de résultat
          echo "<td class=\"";
          if ($i==$nbMatchsDeCeJeu)       echo "infobas";
          else                            echo "info";
          if ($nbJoueursQuiOntJoués && $resultatJeu)
            echo "\"><span class=\"prono\">".round(($nbBonResultats/$nbJoueursQuiOntJoués)*100,0)."&nbsp;%</span></td>\n";
          else if ($nbJoueursQuiOntJoués && !$resultatJeu)
            echo "\"><span class=\"prono\">&nbsp;</span></td>\n";
          else
            echo "\"><span class=\"prono\">&nbsp;</span></td>\n";

              //echo "\"><span class=\"prono\">".$nbBonResultats."&nbsp;%</span></td>\n";

          echo "</tr>\n";          

        }

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
            if ($NbMatchsJoues[$i] && $nbResultats<>0)
            {
              if ($NbMatchsJoues[$i] <= 7) 
                $moyenne=$ListeresultatOk[$i]/$nbResultats7*100;
              else
                $moyenne=$ListeresultatOk[$i]/$nbResultats*100;
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
        }

           // Affichage des moyennes
        echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td class=\"eqd\">Moyenne (en %)&nbsp;</td>";
        for($i=0;$i<sizeof($listeJoueurs);$i++)
        {
              //  $moyenne=$ListeresultatOk[$i]/$NbMatchsJoues[$i]*100;
          if ($NbMatchsJoues[$i] && $nbResultats<>0)
          {
            if ($NbMatchsJoues[$i] <= 7) 
              $moyenne=$ListeresultatOk[$i]/$nbResultats7*100;
            else
              $moyenne=$ListeresultatOk[$i]/$nbResultats*100;

          }
          else
          {
            $moyenne="";
          }
          echo "<td class=\"infotitre";
          if ($meilleur==round($moyenne,0))     echo " meilleur";              
          if ($mauvais==round($moyenne,0))      echo " mauvais";       
          if (round($moyenne,1)>75)             echo " gain"; 

          echo "\">".round($moyenne,0)."</td>";          
//              echo "\">".$NbMatchsJoues[$i]."</td>";
        }

        echo "</tr>\n";   
		
		include_once('simplehtmldom_1_5/simple_html_dom.php');
		// Jeu à 14 ou 15 matchs ?
		$nbMatchsDeCeJeu  = getNbMatchsDeCeJeu($jeu);
		//Récupératon de l'id Jeu Pronosoft
		$idSite = getIdSiteJeu($jeu_id);
		
		//récupération des répartitions des matchs des joueurs pronosoft
		//if ($_SESSION['id_joueur'] == '26');
		$url="http://www.pronosoft.com/fr/concours/repartition_lotofoot.php?id715=" . $idSite;
		$fic = file_get_html($url, false);
		$repartition=array();
		$pourcentage=array();
		
		if (!empty($fic) && $stat == 'O') 
		{
			
			$i=0;
			// on récupère la grille de répartition
			$liste = $fic->find('table[id=repartition]');
			$liste2 = $liste[0];
			foreach($liste2->find('tr') as $repart) 
			{
				$texte = $repart->outertext;
				//on ignore la première ligne du table, et on garde seulement les lignes où on a la classe matchs_av pour éviter de parcourir les sous-tableaux
				if (strpos($texte,'row_gmf') == FALSE && strpos($texte,'matchs_av') == TRUE) {
					$pourcent=array();
					$match = array();
					$choix = $repart->find('td[class=matchs_av]');

          if (isset($choix[2]) && isset($choix[3]) && isset($choix[4])) 
          {

  					$pourcent['1']=$choix[2]->plaintext;
  					$pourcent['N']=$choix[3]->plaintext;
  					$pourcent['2']=$choix[4]->plaintext;
  					//récupération de la couleur
  					$pourcent['C1']=str_replace ("pourcent_", "", $choix[2]->find('span[class*=pourcent]',0)->class);
  					$pourcent['CN']=str_replace ("pourcent_", "", $choix[3]->find('span[class*=pourcent]',0)->class);
  					$pourcent['C2']=str_replace ("pourcent_", "", $choix[4]->find('span[class*=pourcent]',0)->class);
  					$pourcent['1'] = str_replace (" %", "" , $pourcent['1'] );
  					$pourcent['N'] = str_replace (" %", "" , $pourcent['N'] );
  					$pourcent['2'] = str_replace (" %", "" , $pourcent['2'] );
  					
  					//$choix1=$choix[2];
  					//$choixN=$choix[3];
  					//$choix2=$choix[4];
  					//$match['choix1']=$choix1;
  					//$match['choixN']=$choixN;
  					//$match['choix2']=$choix2;
  					//$repartition[$i]=$match;
  					$pourcentage[$i]=$pourcent;
  					$i++;
  					if ($i==$nbMatchsDeCeJeu) break;
  				}
        }
			}
		}
		
		//Affichage des indices de gains.
		if ($stat == 'O') {
			echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td class=\"eqd\">Indice de gain</td>";
		
		
			//Si pronostic joué, on affiche l'indice de gain
			for($ii=0;$ii<sizeof($listeJoueurs);$ii++)
			{
				$pronostic = $Listepronostic[$ii];
				$jj=$listeJoueurs[$ii];
				$jjid=$jj["joueur_id"];
				if ($pronostic) {
					$total = 0.00;
					$totalJuste = 0.00;
					$compteur = 0;
					$compteurJuste = $ListeresultatOk[$ii];
					
					$j = 0;
					for($i=1; $i <= $nbMatchsDeCeJeu ; $i++) 
					{
						$prono = getPronosticNumero($pronostic, $i);
						
						$pourcent=$pourcentage[$j];
						$resultatJeu='';
						if ($Resultat) {
							$resultatJeu=getResultatNumero($Resultat,$i);
						}
						
						if($resultatJeu == '1N2') {
							$total = $total + 100;
							$compteur++;
							$totalJuste = $totalJuste + 100;
						}
						else {
							if (is_int(strpos($prono,'1')) == TRUE) {
								$total = $total + floatval(str_replace(',', '.',$pourcent['1']));
								$compteur++;
								if($resultatJeu == '1') {
									$totalJuste = $totalJuste + floatval(str_replace(',', '.',$pourcent['1']));
								}
								else {
									if ($resultatJeu == '2' || $resultatJeu == 'N') {
										$totalJuste = $totalJuste + (100-floatval(str_replace(',', '.',$pourcent['1'])));
									}
								}
							}
							if (is_int(strpos($prono,'N')) == TRUE) {
								$total = $total + floatval(str_replace(',', '.',$pourcent['N']));
								$compteur++;
								if($resultatJeu == 'N') {
									$totalJuste = $totalJuste + floatval(str_replace(',', '.',$pourcent['N']));
								}
								else {
									if ($resultatJeu == '2' || $resultatJeu == '1') {
										$totalJuste = $totalJuste + (100-floatval(str_replace(',', '.',$pourcent['N'])));
									}
								}
							}
							if(is_int(strpos($prono,'2')) == TRUE) {
								$total = $total + floatval(str_replace(',', '.',$pourcent['2']));
								$compteur++;
								if($resultatJeu == '2') {
									$totalJuste = $totalJuste + floatval(str_replace(',', '.',$pourcent['2']));
								}
								else {
									if ($resultatJeu == '1' || $resultatJeu == 'N') {
										$totalJuste = $totalJuste + (100-floatval(str_replace(',', '.',$pourcent['2'])));
									}
								}
							}
						}
						
						$j++;
					}
					
					//Calcul de l'indice de gain : On inverse le pourcentage, puis on le divise par 10, pour avoir un chiffre élévé pour un gain potentiel élevé.
					$indice=1.23;
					$indice=$total/$compteur;
					$indice=(100-$indice)/10;
					//Calcul de la moyenne des pourcentages de répartition où le prono est juste, sert ensuite au calcul du classement par rapport à la prise de risque
					//$moyenneJuste=$totalJuste/$compteurJuste;
					if ($Resultat) {
						$moyenneJuste=$totalJuste/$compteur;
						setMoyenneJuste($jeu_id,$jjid,$moyenneJuste);
					}
					
					//Affichage de l'indice gain et stockage en base
					if (($bloque==0) && ($invisible==1) && ($_SESSION['id_joueur']<>$jjid)) {
						if ($compteur ==7)
						{
							echo "<td class='infotitre' bgcolor='#CEF6CE'>" . number_format($indice,2) . "<br>&nbsp;</td>";
							setIndiceGainProno7($jeu_id,$jjid,$indice);
							setIndiceGainProno15($jeu_id,$jjid,0);
						}
						else
						{
							echo "<td class='infotitre' bgcolor='#CEE3F6'>&nbsp;<br>" . number_format($indice,2) . "</td>";
							setIndiceGainProno7($jeu_id,$jjid,0);
							setIndiceGainProno15($jeu_id,$jjid,$indice);
						}
					}
					else
					{
						if ($compteur ==7)
						{
							echo "<td class='infotitre' bgcolor='#CEF6CE'><a href='estimation.php?idjeu=".$jeu_id."&idjoueur=".$jjid."'>" . number_format($indice,2) . "</a><br>&nbsp;</td>";
							setIndiceGainProno7($jeu_id,$jjid,$indice);
							setIndiceGainProno15($jeu_id,$jjid,0);
						}
						else
						{
							echo "<td class='infotitre' bgcolor='#CEE3F6'>&nbsp;<br><a href='estimation.php?idjeu=".$jeu_id."&idjoueur=".$jjid."'>" . number_format($indice,2) . "</a></td>";
							setIndiceGainProno7($jeu_id,$jjid,0);
							setIndiceGainProno15($jeu_id,$jjid,$indice);
						}
					}
				}
				else {
					echo "<td class=\"infotitre\">&nbsp;</td>";
				}
			}
			if ($Resultat) {
				$total = 0.00;
				$total7 = 0.00;
				$compteur = 0;
				$compteur7 = 0;
				$j = 0;
				$compteurVert = 0;
				$compteurJaune = 0;
				$compteurRouge = 0;
				for($i=1; $i <= $nbMatchsDeCeJeu ; $i++) 
					{
						$resultatJeu=getResultatNumero($Resultat,$i);
						$pourcent=$pourcentage[$j];
						$couleur="";
						if ($resultatJeu == '1') {
							$total = $total + floatval(str_replace(',', '.',$pourcent['1']));
							$compteur++;
							if ($i<=7) {
								$total7 = $total7 + floatval(str_replace(',', '.',$pourcent['1']));
								$compteur7++;
							}
							$couleur=$pourcent['C1'];
						}
						elseif ($resultatJeu == 'N') {
							$total = $total + floatval(str_replace(',', '.',$pourcent['N']));
							$compteur++;
							if ($i<=7) {
								$total7 = $total7 + floatval(str_replace(',', '.',$pourcent['N']));
								$compteur7++;
							}
							$couleur=$pourcent['CN'];
						}
						elseif($resultatJeu == '2') {
							$total = $total + floatval(str_replace(',', '.',$pourcent['2']));
							$compteur++;
							if ($i<=7) {
								$total7 = $total7 + floatval(str_replace(',', '.',$pourcent['2']));
								$compteur7++;
							}
							$couleur=$pourcent['C2'];
						}
						elseif($resultatJeu == '1N2') {
							$total = $total + 100;
							$compteur++;
							if ($i<=7) {
								$total7 = $total7 + 100;
								$compteur7++;
							}
							$couleur=$pourcent['C1'];
						}
						if ($couleur == "vert") {
							$compteurVert++;
						}
						if ($couleur == "jaune") {
							$compteurJaune++;
						}
						if ($couleur == "rouge") {
							$compteurRouge++;
						}
						$j++;
						if ($i==7) {
							setCouleurJeu7($jeu_id,$compteurVert,$compteurJaune,$compteurRouge);
						}
					}
				$indice=$total/$compteur;
				$indice7 = $total7/$compteur7;
				$indice=(100-$indice)/10;
				$indice7=(100-$indice7)/10;
				setIndiceGainJeu7($jeu_id,$indice7);
				setIndiceGainJeu15($jeu_id,$indice);
				setCouleurJeu($jeu_id,$compteurVert,$compteurJaune,$compteurRouge);
				echo "<td colspan=2 class=\"infotitre\">LF7: " . number_format($indice7,2) . "<br>LF15: ". number_format($indice,2) ."</td>";
			}
			echo "</tr>";
		


		
		?>
		<tr><td colspan=20 align=right><font color='#6F2229' size=1><b>(*) Plus l'indice est grand, plus le gain sera potentiellement élevé. 
		(<a href="" onclick="bascule('1'); return false;">Voir la grille</a> pour situer l'indice si les pronos s'avèrent justes)</b></font></td></tr></table>
		<div id='1' style='display:none;' align=center><?php include ("scripts/indice_gain.php"); ?></div>

        
      <br>
	  
      <?php
		}
		else {
			echo "</table>";
		}
    }
    $listeOperations = getListeOperationsCaisse();
    ferme_base($db_link);
  } 

  if (isset($_SESSION['id_joueur']) && $jeu )
  {
   echo "<br><br><a href=\"saisieresultat.php?idjeu=".$jeu_id."\" class=\"miniAction\">Saisie du résultat</a><br>";
 }
 else
 {
    if ($jeu )
      echo "<br><br><br>";
}
if ($jeu )
{
      echo "<h2>Rapports</h2>";
	  echo "<table><tr>";
							
							
							
      clearstatcache();
      $fichierRapport7="scripts/rapports_generes_7.php";
      if (file_exists($fichierRapport7))   
      {
		echo "<td width=400  align=center>";
		 $tmprapport="";
          $fp = fopen($fichierRapport7,"r"); //lecture du fichier
          while (!feof($fp)) { //on parcourt toutes les lignes
          $tmprapport .= fgets($fp, 4096); // lecture du contenu de la ligne
          }  
          fclose($fp);
          echo $tmprapport;
		echo "</td>";
      }  
		echo "<td width=100  align=center></td>";
      clearstatcache();
      $fichierRapport7="scripts/rapports_generes_15.php";
      if (file_exists($fichierRapport7))   
      {
		echo "<td width=400  align=center>";
		  $tmprapport="";
		  $fp = fopen($fichierRapport7,"r"); //lecture du fichier
		  while (!feof($fp)) { //on parcourt toutes les lignes
		  $tmprapport .= fgets($fp, 4096); // lecture du contenu de la ligne
		  }  
		  fclose($fp);
		  echo $tmprapport;
		echo "</td>";
      }  
	  echo "</tr></table>";
}

if ($jeu )
{
?>
          <h2>Caisse</h2>
          <table class="jeu">
           <tr>
             <td width="310" class="infotitre">Libell&eacute;</td>
             <td width="150" class="infotitre">Date</td>
             <td width="150" class="infotitre">D&eacute;bit</td>
             <td width="150" class="infotitre">Cr&eacute;dit</td>
           </tr>
           <?php 
           $total = 0;
           for($i=0;$i<sizeof($listeOperations) && !empty($listeOperations);$i++)
           {
            $caisse=$listeOperations[$i];
        // Lecture des propri‚t‚s du joueur
            $caisse_libelle       = stripslashes($caisse["caisse_libelle"]);
            $caisse_date          = stripslashes($caisse["caisse_date"]);
            $caisse_somme_debit   = stripslashes($caisse["caisse_somme_debit"]);
            $caisse_somme_credit  = stripslashes($caisse["caisse_somme_credit"]);
            $total = $total -  $caisse_somme_debit +  $caisse_somme_credit;

            if ($caisse_somme_debit==0)
            {
              $caisse_somme_debit="";
            }    
            else
            {
              $caisse_somme_debit=$caisse_somme_debit." &euro;";
            }    

            if ($caisse_somme_credit==0)   
            {
              $caisse_somme_credit="";
            }    
            else
            {
              $caisse_somme_credit=$caisse_somme_credit." &euro;";
            }    

            if ($i%2 != 1) 
              echo "<tr class='enreg impaire'>";
            else
              echo "<tr class='enreg'>";

            $j=$i+1;
            echo "<td width=\"310\">".$caisse_libelle."</td>\n";
            echo "<td width=\"150\">".formatte_date($caisse_date,1)."</td>\n";
            echo "<td width=\"150\" style=\"padding-right: 10px;text-align: right;\">".$caisse_somme_debit."</td>\n";
            echo "<td width=\"150\" style=\"padding-right: 10px;text-align: right;\">".$caisse_somme_credit."</td>\n";
            echo "</tr>\n";
          }
          $tdebit="";
          $tcredit="";
          if ($total<0)    $tdebit=" - ".$total. " &euro;";
          if ($total>=0)   $tcredit=$total." &euro;";
          ?>
          <tr><td>&nbsp;</td></tr>
          <tr>
           <td width="460" colspan="2">Total</td>
           <td width="150" style="padding-right: 10px;text-align: right;"><?php echo $tdebit; ?></td>
           <td width="150" style="padding-right: 10px;text-align: right;"><?php echo $tcredit; ?></td>
         </tr>
       </table>

<?php
}
?>



       <div id="DivIE">
        <h2>Le saviez-vous ?</h2>

        <p>Visiblement, vous utilisez actuellement le navigateur Internet Explorer de Microsoft; ce navigateur ne respecte pas à
          ce jour les standards du W3C (World Wide Web Consortium), consortium international de normalisation du web. 
          Nous vous conseillons donc d'utiliser un navigateur conforme à ces standards tel  
          <a href="http://www.mozilla-europe.org/fr/products/firefox/"> FireFox</a>, pour lequel ce site a été optimisé.
        </p>
      </div>

      <script type="text/javascript" src="js/TestIE.js"></script>

    </div>