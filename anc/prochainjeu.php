<?php 
session_start();
ini_set('zlib.output_compression_level', 6);
//ob_start('ob_gzhandler');
?>
<?php  include("scripts/head.php");	?>
<?php  @include("scripts/fichierStats.php");	?>


<style type="text/css" media="all">
/* Ce style CSS ne dois pas être enlevé, sinon les divs ne se cacherons pas ... */
.cachediv {
	visibility: hidden;
	overflow: hidden;
	height: 1px;
	margin-top: -1px;
	position: absolute;
}
</style>

<script type="text/javascript">


	// Montre / Cache un div
	function DivStatus( nom , numDiv)
	{
		var divID = nom + numDiv;
			if ( document.getElementById && document.getElementById( divID ) ) // Pour les navigateurs récents
			{
				Pdiv = document.getElementById( divID );
				PcH = true;
			}
			else if ( document.all && document.all[ divID ] ) // Pour les veilles versions
			{
				Pdiv = document.all[ divID ];
				PcH = true;
			}
			else if ( document.layers && document.layers[ divID ] ) // Pour les très veilles versions
			{
				Pdiv = document.layers[ divID ];
				PcH = true;
			}
			else
			{

				PcH = false;
			}
			if ( PcH )
			{
				Pdiv.className = ( Pdiv.className == 'cachediv' ) ? '' : 'cachediv';
			}
		}
		
	/*
	* Cache tous les divs ayant le même préfixe
	*/
	
	function CacheMontreTout( Montre, Cache1, Cache2 )
	{
		MontreTout (Montre);
		CacheTout (Cache1);
		CacheTout (Cache2);
	}

	/*
	* Cache tous les divs ayant le même préfixe
	*/
	function CacheTout( nom )
	{	
		var NumDiv = 1;
			if ( document.getElementById ) // Pour les navigateurs récents
			{
				while ( document.getElementById( nom + NumDiv) )
				{
					SetDiv = document.getElementById( nom + NumDiv );
					if ( SetDiv && SetDiv.className != 'cachediv' )
					{
						DivStatus( nom, NumDiv );
					}
					NumDiv++;
				}
			}
			else if ( document.all ) // Pour les veilles versions
			{
				while ( document.all[ nom + NumDiv ] )
				{
					SetDiv = document.all[ nom + NumDiv ];
					if ( SetDiv && SetDiv.className != 'cachediv' )
					{
						DivStatus( nom, NumDiv );
					}
					NumDiv++;
				}
			}
			else if ( document.layers ) // Pour les très veilles versions
			{
				while ( document.layers[ nom + NumDiv ] )
				{
					SetDiv = document.layers[ nom + NumDiv ];
					if ( SetDiv && SetDiv.className != 'cachediv' )
					{
						DivStatus( nom, NumDiv );
					}
					NumDiv++;
				}
			}
		}

	/*
	* Montre tous les divs ayant le même préfixe
	*/
	function MontreTout( nom )
	{	
		var NumDiv = 1;
			if ( document.getElementById ) // Pour les navigateurs récents
			{
				while ( document.getElementById( nom + NumDiv) )
				{
					SetDiv = document.getElementById( nom + NumDiv );
					if ( SetDiv && SetDiv.className != '' )
					{
						DivStatus( nom, NumDiv );
					}
					NumDiv++;
				}
			}
			else if ( document.all ) // Pour les veilles versions
			{
				while ( document.all[ nom + NumDiv ] )
				{
					SetDiv = document.all[ nom + NumDiv ];
					if ( SetDiv && SetDiv.className != '' )
					{
						DivStatus( nom, NumDiv );
					}
					NumDiv++;
				}
			}
			else if ( document.layers ) // Pour les très veilles versions
			{
				while ( document.layers[ nom + NumDiv ] )
				{
					SetDiv = document.layers[ nom + NumDiv ];
					if ( SetDiv && SetDiv.className != '' )
					{
						DivStatus( nom, NumDiv );
					}
					NumDiv++;
				}
			}
		}

		function validate() {
			var compteur = 0;
			var nbMatch = document.querySelectorAll('.eqd').length;
			var elements = document.querySelectorAll('.pj');
			for (var i = 0; i < elements.length ;  i++)
			{
			  	var val = elements[i].value; 
			  	if (val !== "0") {
			  		compteur++;
			  	}
			}			
			if (compteur === 7 || compteur === 14 || compteur === 15) {
				return true;
			}
		      alert("Erreur sur votre grille. Merci de vérifier : prono manquant ou double joué");     
			return false;
		}

		</script>


		<body>    
			<?php include ("scripts/debutpage.php"); ?>

			<script type="text/javascript" src="js/saisiejeu.js"></script>

			<div id="conteneur">	

				<?php include ("scripts/header.php"); ?>

				<?php include ("scripts/menu.php"); ?>

				<?php 
    			// On affiche cette page uniquement si on est connecté 
				if (!isset($_SESSION['id_joueur'])) 
				{
      				// Authentification correcte
					header('Location: index.php');
				}
				$joueur_id=$_SESSION['id_joueur'];
				?>

				<div id="contenu">
					<?php 
					include_once('simplehtmldom_1_5/simple_html_dom.php');
					$db_link = ouvre_base();
					if ($db_link)
					{

						// Si choix d'un autre jeu, on renseigne l'identifiant 
						if (isset($_GET['idjeu']))
						{
							$ident_jeu = $_GET['idjeu'];
						}
						else          
						{
							// Identifiant jeu obligatoire
							$ident_jeu    = getIDJeuCourant();
						}
						
						// récup affichage stat ou pas
						if (isset($_GET['stat']))
						{
							$stat = $_GET['stat'];
							setUtilisateurStat ($joueur_id,$stat);
						}
						else          
						{
							// recup stat joueur
							$joueur    = getUtilisateurID($joueur_id);
							$stat = $joueur['stat'];
						}

						// LC On lit maintenant le jeu en paramètre
						$jeu_id=$ident_jeu;

						/* Pronostic de ce joueur */
						$pronostic=getPronosticJoueur($joueur_id,$jeu_id);
						
						//Récupératon de l'id Jeu Pronosoft
						$idSite = getIdSiteJeu($jeu_id);
												
						// Requete de lecture du prochain jeu
						// $jeu = getJeuCourant();
						// LC On lit maintenant le jeu en paramètre
						$jeu          = getJeu($jeu_id);
						$saison_id = getIDSaisonCourante();
						if ($jeu)
						{
							// Lecture des champs de la ligne
							$bloque        = $jeu["bloque"];
							$titre        = $jeu["titre"];
							$commentaire  = $jeu["commentaire"];

							// Affichage du titre du prochain jeu
							echo "<h2>".$titre."</h2>";
							
							  
							  if ($stat=='O') {
								echo "<a href='prochainjeu.php?idjeu=".$jeu_id."&stat=N' class='miniAction'>Masquer les stats</a>";
							  }
							  else {
								echo "<a href='prochainjeu.php?idjeu=".$jeu_id."&stat=O' class='miniAction'>Afficher les stats</a>";
							  }
							
							//Si pronostic joué, on affiche l'estimation des gains
							if ($pronostic && $stat=='O') {
								echo "<h3>Estimation des gains de la grille jouée</h3>";
								echo "<table><tr><td colspan=2 align=center><FONT COLOR='red'><b><FONT size=3>Grille enregistrée</font></font><br>Voici une estimation des gains potentiels si le jeu joué s'avère juste.</b></td></tr>";
								
								//interrogation du site avec l'idSite via POST par curl
								$url='http://www.pronosoft.com/fr/lotofoot/estimateur.php?id715=' . $idSite;
								//$nameForm = 'estimateur_' . $idSite;
								$postfields = array();
								//$postfields["action"] = $url;
								$postfields["action"] = "submit";
								
								$nbMatchsDeCeJeu  = getNbMatchsDeCeJeu($jeu);
								//on remplit dynamiquement les champs nécessaire au formulaire
								$j = 0;
								for($i=1; $i <= $nbMatchsDeCeJeu ; $i++) 
								{
									$prono = getPronosticNumero($pronostic, $i);
									$name = 'm_' . $j;
									if ($prono == '1') {
										$postfields[$name]='1';
									}
									elseif ($prono == 'N') {
										$postfields[$name]='N';
									}
									elseif($prono == '2') {
										$postfields[$name]='2';
									}
									else {
										$postfields[$name]='G';
									}
									$j++;
								}
								
								//initialisation du curl
								$postfields["id715"] = $idSite;
								$ch = curl_init($url);
								curl_setopt($ch, CURLOPT_POST, 1);
								curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
								curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0");
								curl_setopt($ch, CURLOPT_REFERER, $url);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
								
								//récupération du résultat html
								$result = curl_exec($ch);
								curl_close($ch);
								
								//echo $result;
								
								//convertion en objet html
								$html = str_get_html($result);
								if (!empty($html)) 
								{
									//echo $html;
									$estimation=array();
									$i=0;
									// on récupère le tableau des extimation
									foreach($html->find('div[class=box_estim]') as $listeJeux) 
									{
										//création d'un tableau contenant les estimations
										$estim = array();
										
										//Récupération du Titre 
										$titre = $listeJeux->find('h2',0)->plaintext;
										$rangs = array();
										$rapports = array();
										$j=0;
										//récupération des différents rangs de gain
										foreach($listeJeux->find('tr') as $rang)
										{
											$rangs[$j]=$rang->find('td',0)->plaintext;
											$rapports[$j]=$rang->find('td',1)->plaintext;
											$j++;
										}
										$estim['titre']=$titre;
										$estim['rangs']=$rangs;
										$estim['rapports']=$rapports;
										
										//on remplit le tableau
										$estimation[$i]=$estim;
										$i++;
									}
									$i=0;
									echo '<tr>';
									if(empty($estimation)) {
										echo "<td><br><font size=4 align=center><i>Chargement des estimations échoué, réactualisez la page...</i></font><br></td></tr></table>";
									}

									//on déroule le tableau, pour afficher les estimations (LF7 / LF15)
									$nbProno = getNbPronosticJoueur($pronostic);
									
									foreach($estimation as $estim) {
										if ($nbProno == 7 && $i>0) break;
										
										echo '<td width=350  align=center><h4>' . $estim['titre'] . '</h4>';
										
										$rangs=$estim['rangs'];
										$rapports=$estim['rapports'];
										//affichage du tableau
										echo '<table class="jeu" id="estimation">
															<tr>
																<td class="infotitre">Rang</td>
																<td class="infotitre">Gains</td>
															</tr>';
										
										//parcours des rangs
										$j=0;
										foreach($rangs as $index)
										{
											$k=$j+1;
											if ($j%2) echo '<tr class=\'enreg paire\'>';
											else echo '<tr class=\'enreg impaire\'>';
											
											//affichage des rangs
											echo '<td width=100 align=center>' . $rangs[$j] . '</td>';
											echo '<td width=100 align=center>' . $rapports[$j] . '</td>';
											echo '</tr>';
											//echo "<br>";
											$j++;
										}
										echo "</table></td>";
										$i++;
									}
									echo '</tr>';
									echo "<tr><td colspan=2 align=center><font size=1>Cette estimation se base sur les pronostics des membres du site Pronosoft,<br> dont la répartition est assez représentative des pronostics réellement validés à la FDJ.</font></td></tr>";
									echo '</table>';
								}
								else {
									echo "<td><br><font size=4 align=center><i>Chargement des estimations échoué, réactualisez la page...</i></font><br></td></tr></table>";
								}
								
							}
							
								echo "<h3>Jeu</h3>";
								
								// Affichage du tableau	
							if ($bloque<>1)
							{
								?>			
								<a href="javascript:jeuFlash(7);" class="miniAction" title="7 résultats au hasard" >Jeu Flash à 7</a>
								<a href="javascript:jeuFlash(15);" class="miniAction" title="15 résultats au hasard" >Jeu Flash à 15</a>
								<a href="javascript:efface();" class="miniAction">Efface le jeu</a>
								<a href="saisieresultat.php?idjeu=<?php echo $jeu_id ?>" class="miniAction">Saisie du résultat</a>

								<form onsubmit="return(validate());" method="post" class="formjeu" name="formjeu" action="validejeu.php?idjeu=<?php echo $jeu_id ?>" >
							<?php
							}
							?>
									<table class="jeu">

										<?php 
										include_once('simplehtmldom_1_5/simple_html_dom.php');
										// Jeu à 14 ou 15 matchs ?
										$nbMatchsDeCeJeu  = getNbMatchsDeCeJeu($jeu);
										
										
										//récupération des répartitions des matchs des joueurs pronosoft
										$url="http://www.pronosoft.com/fr/concours/repartition_lotofoot.php?id715=" . $idSite;
										$fic = file_get_html($url, false);
										$repartition=array();
										$pourcentage=array();
										if (!empty($fic)) 
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
													$pourcent['1']=$choix[2]->plaintext;
													$pourcent['N']=$choix[3]->plaintext;
													$pourcent['2']=$choix[4]->plaintext;
													$pourcent['1'] = str_replace (" %", "" , $pourcent['1'] );
													$pourcent['N'] = str_replace (" %", "" , $pourcent['N'] );
													$pourcent['2'] = str_replace (" %", "" , $pourcent['2'] );
													
													$choix1=$choix[2];
													$choixN=$choix[3];
													$choix2=$choix[4];
													$match['choix1']=$choix1;
													$match['choixN']=$choixN;
													$match['choix2']=$choix2;
													$repartition[$i]=$match;
													$pourcentage[$i]=$pourcent;
													$i++;
													if ($i==$nbMatchsDeCeJeu) break;
												}
											}
										}
										
										//Si pronostic joué, on affiche l'indice de gain
										if ($pronostic && $stat == 'O') {
											$total = 0.00;
											$compteur = 0;
											$j = 0;
											for($i=1; $i <= $nbMatchsDeCeJeu ; $i++) 
											{
												$prono = getPronosticNumero($pronostic, $i);
												$pourcent=$pourcentage[$j];
												
												if (is_int(strpos($prono,'1')) == TRUE) {
													$total = $total + floatval(str_replace(',', '.',$pourcent['1']));
													$compteur++;
												}
												elseif (is_int(strpos($prono,'N')) == TRUE) {
													$total = $total + floatval(str_replace(',', '.',$pourcent['N']));
													$compteur++;
												}
												elseif(is_int(strpos($prono,'2')) == TRUE) {
													$total = $total + floatval(str_replace(',', '.',$pourcent['2']));
													$compteur++;
												}
												
												$j++;
											}
											
											$indice=$total/$compteur;
											$indice=(100-$indice)/10;
											echo "<tr><td colspan=6>";
											// Affichage des commentaires
											if($commentaire!=null)
											{
												echo "<p>".$commentaire."</p>";
											}
											
											$listes = getMoyenneIndiceJeuxSaison($saison_id);
											$element=$listes[0];
											$IndiceGain7      = stripslashes($element["moy7"]);
											$IndiceGain15     = stripslashes($element["moy15"]);
											echo "</td><td colspan=3 align=center><br><font size=3>Indice de gain : <font color=red>" . number_format($indice,2) . "</font>/10</font><br>Indice gagnant moyen de la Saison - Jeu7 : ". $IndiceGain7 ." / Jeu15 : ". $IndiceGain15 ."<br></td></tr>";
										}
										if ($stat == 'O') {
											echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td class=\"infojeu\"><b>1</b></td><td class=\"infojeu\"><b>N</b></td><td class=\"infojeu\"><b>2</b></td></tr>";
										}
										$compteur = 0;
										for($i=1; $i <= $nbMatchsDeCeJeu ; $i++) 
										{
											$equiped     = $jeu["equipe".$i."d"];
											$equipev     = $jeu["equipe".$i."v"];

											// Lecture des cotés match	
											$listeCoteMatch = getCoteMatch($equiped, $equipev);

											if ($i%2 == 1) 
												if ($i == 7)  echo "<tr class='impaire' style='border-bottom:1px dotted blue'>";
											else          echo "<tr class='impaire' >";

											else
												echo "<tr>";

											// Retrouve si la colonne 1, 2 et 3 est pronostiqué par le joueur
											$jeuColonne1 = getPronosticEstJoue($pronostic,$i,'1');
											$jeuColonne2 = getPronosticEstJoue($pronostic,$i,'N');
											$jeuColonne3 = getPronosticEstJoue($pronostic,$i,'2');

											// Colonne 1 : Numéro du match
											echo "<td><img src='img/".$i.".png' alt='img/".$i."' /></td>\n";  

											// Colonne suivante : Equipe qui reçoit
											$pronosticOrdi = getPronostic($i);
											if  ($pronosticOrdi=='1' or $pronosticOrdi=='N')   
											{
												echo "<td class=\"eqd pronostic\">".$equiped."</td>";
											}
											else
												echo "<td class=\"eqd\">".$equiped."</td>";

											  


											// Colonne suivante : Les trois choix              
											echo "<td class=\"choix\"><span id=\"vl".$i."c1\" class=\"";
											if ($jeuColonne1)    echo "choixs";
											else                 echo "choix1";
											echo "\" onclick=\"Change('l".$i."c1','P');\" /></td>\n";
											echo "<td class=\"choix\"><span id=\"vl".$i."c2\" class=\"";
											if ($jeuColonne2)    echo "choixs";
											else                 echo "choixn";
											echo "\" onclick=\"Change('l".$i."c2','P');\" /></td>\n";
											echo "<td class=\"choix\"><span id=\"vl".$i."c3\" class=\"";
											if ($jeuColonne3)    echo "choixs";
											else                 echo "choix2";
											echo "\" onclick=\"Change('l".$i."c3','P');\" /></td>\n";


											// Colonne suivante : Equipe qui se déplace    
											if  ($pronosticOrdi=='2' or $pronosticOrdi=='N')   
												echo "<td class=\"eqg pronostic \">".$equipev."</td>\n";   
											else
												echo "<td class=\"eqg\">".$equipev."</td>\n";  
											
											//on affiche les répartitions
											if ($repartition && $stat == 'O') {
												$match = $repartition[$compteur];
												$choix1=$match['choix1'];
												$choixN=$match['choixN'];
												$choix2=$match['choix2'];
												$choix1 = str_replace ("td", "td id=repartition " , $choix1 );
												$choixN = str_replace ("td", "td id=repartition " , $choixN );
												$choix2 = str_replace ("td", "td id=repartition " , $choix2 );
												$prono = getPronosticNumero($pronostic, $i);
												if ($prono=="1") {
													$choixN = str_replace ("vert", "gris" , $choixN );
													$choixN = str_replace ("jaune", "gris" , $choixN );
													$choixN = str_replace ("rouge", "gris" , $choixN );
													$choix2 = str_replace ("vert", "gris" , $choix2 );
													$choix2 = str_replace ("jaune", "gris" , $choix2 );
													$choix2 = str_replace ("rouge", "gris" , $choix2 );
												}
												if ($prono=="N") {
													$choix1 = str_replace ("vert", "gris" , $choix1 );
													$choix1 = str_replace ("jaune", "gris" , $choix1 );
													$choix1 = str_replace ("rouge", "gris" , $choix1 );
													$choix2 = str_replace ("vert", "gris" , $choix2 );
													$choix2 = str_replace ("jaune", "gris" , $choix2 );
													$choix2 = str_replace ("rouge", "gris" , $choix2 );
												}
												if ($prono=="2") {
													$choixN = str_replace ("vert", "gris" , $choixN );
													$choixN = str_replace ("jaune", "gris" , $choixN );
													$choixN = str_replace ("rouge", "gris" , $choixN );
													$choix1 = str_replace ("vert", "gris" , $choix1 );
													$choix1 = str_replace ("jaune", "gris" , $choix1 );
													$choix1 = str_replace ("rouge", "gris" , $choix1 );
												}
												if ($prono=="1N") {
													$choix2 = str_replace ("vert", "gris" , $choix2 );
													$choix2 = str_replace ("jaune", "gris" , $choix2 );
													$choix2 = str_replace ("rouge", "gris" , $choix2 );
												}
												if ($prono=="N2") {
													$choix1 = str_replace ("vert", "gris" , $choix1 );
													$choix1 = str_replace ("jaune", "gris" , $choix1 );
													$choix1 = str_replace ("rouge", "gris" , $choix1 );
												}
												if ($prono=="12") {
													$choixN = str_replace ("vert", "gris" , $choixN );
													$choixN = str_replace ("jaune", "gris" , $choixN );
													$choixN = str_replace ("rouge", "gris" , $choixN );
												}
												echo $choix1;
												echo $choixN;
												echo $choix2;
											}
											

											if ($listeCoteMatch)
											{
												echo "<td class=\"eqd\">&nbsp;</td>";
												echo "<td class=\"eqd\">".$listeCoteMatch[0]."</td>";
												echo "<td class=\"eqd\">".$listeCoteMatch[1]."</td>";
												echo "<td class=\"eqd\">".$listeCoteMatch[2]."</td>";
											}
											echo "</tr>\n";   

											// Nouvelle ligne cachée pour stocker les pronos 
											echo "<tr>\n";
											echo "<td><input class='pj' id=\"tl".$i."c1\" name=\"tl".$i."c1\" type=\"hidden\" value=\"";
											if ($jeuColonne1)    echo "1";
											else                 echo "0";
											echo "\"/></td>\n";
											echo "<td><input class='pj' id=\"tl".$i."c2\" name=\"tl".$i."c2\" type=\"hidden\" value=\"";
											if ($jeuColonne2)    echo "1";
											else                 echo "0";
											echo "\"/></td>\n";
											echo "<td><input class='pj' id=\"tl".$i."c3\" name=\"tl".$i."c3\" type=\"hidden\" value=\"";
											if ($jeuColonne3)    echo "1";
											else                 echo "0";
											echo "\"/></td>\n";
											echo "</tr>\n";
											
											$compteur++;  
										}
										if ($stat == 'O') {
											$listes = getMoyenneCouleurSaison($saison_id);
											$element=$listes[0];
											$vert      = stripslashes($element["vert"]);
											$jaune     = stripslashes($element["jaune"]);
											$rouge     = stripslashes($element["rouge"]);
											$vert7      = stripslashes($element["vert7"]);
											$jaune7     = stripslashes($element["jaune7"]);
											$rouge7     = stripslashes($element["rouge7"]);
											echo "<tr><td colspan=9 align=right>Répartition moyenne de la Saison - <b>Jeu 7 : <font color=#70a745>".$vert7." V</font> - <font color=#e4b704>".$jaune7." J</font> - <font color=#cb281c>".$rouge7." R</font> / Jeu 15 : <font color=#70a745>".$vert." V</font> - <font color=#e4b704>".$jaune." J</font> - <font color=#cb281c>".$rouge." R</font></b><br>
											Répartition 1N2 représentatif des joueurs du Loto Foot FDJ (source Pronosoft)</td></tr>";
										?>
								
									
									
								<?php	
								}
								if ($bloque==1)
								{
									echo "<tr><td colspan=9><p class=\"jeubloque\">Les pronostics sont fermés.</p></td></tr></table>";
								}
								else {
								?>
									</table><input type="submit" value="Valider" class="bouton" id="boutonvalider" />         
									
								</form>
								
								
								<?php 
							
							
								}
								
								if ($pronostic && $stat == 'O') {
								include ("scripts/indice_gain.php"); 
								}
								?>
								
								<?php
								
							
							
               //     <div style="border:1px solid red;position:absolute;top:480px;right:80px">   
// <iframe width="670" height="482" MARGINWIDTH="0" MARGINHEIGHT="0" HSPACE="0" VSPACE="50" FRAMEBORDER="0" SCROLLING="no" 
// src="http://www.foot-national.com/classement_cache37.html"></iframe>
          // </div>
							
							//else
							
							//{
							//	echo "<p class=\"jeubloque\">Les pronostics sont fermés.</p>";
							//}
							?>
							
							
							
							<?php 
						}
						ferme_base($db_link);
					} 
					?>

				</div>

				<?php include ("scripts/footer.php"); ?>
			</div>
		</body>

		</html>
