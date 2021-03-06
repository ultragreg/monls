<?php
/*
Ce script lit les pronostics des matchs pour une journée donné sur le site de pronosoft
Il est dans le cron du serveur et est exécuté toutes les 4 heures
Le résultat est stocké dans la table 'indicateur'
*/

	include_once('../simplehtmldom_1_5/simple_html_dom.php');
	include_once '../config/database.php';
	include_once '../config/util.php';
	include_once '../objects/saison.php';
	include_once '../objects/jeu.php';
	include_once '../objects/indicateur.php';
	include_once '../objects/joueur.php';
	include_once '../objects/pronostic.php';

	// instantiate database and product object
	$database = new Database();
	$db = $database->getConnection();

	// Recherche de la saison courante
	$saison = new Saison($db);
	$saison = $saison->chargeSaisonCourante();

	// Dernier Jeu
	$jeu = new Jeu($db);
	$jeu->saison_id = $saison->saison_id;
	$jeu->chargeDernierJeu();
      
	// Recherche la liste des joueurs
	$joueurs = new Joueur($db);
	$stmtJoueurs = $joueurs->litJoueurs();
	$listeJoueurs = $stmtJoueurs->fetchAll(PDO::FETCH_ASSOC);

	// Indicateurs
	$indicateur = new Indicateur($db);

	if (isset($_GET['debug']))
	{
		// Mode debug
		$debug=true;
	}

	// Jeu à 14 ou 15 matchs ?
	$nbMatchsDeCeJeu  = getNbMatchsDeCeJeu($jeu);
	//Récupératon de l'id Jeu Pronosoft
	$idSite = $jeu->idSite;


	if ($debug) {
		echo "<br>--- PHASE 1 : Import en base des indices --- <br><br>\n";
	}
	//récupération des répartitions des matchs des joueurs pronosoft
	$url="http://www.pronosoft.com/fr/concours/repartition_lotofoot.php?id15=" . $idSite;
	if (isset($_GET['id']))
	{
		// Mode debug
		$url="http://www.pronosoft.com/fr/concours/repartition_lotofoot.php?id15=" . $_GET['id'];
	}

	if ($debug) {
		echo "<br>url:".$url."<br>\n";
	}
	$fic = file_get_html($url, false);
	$match=array();
	$repartition=array();
	$pourcentage=array();
	
	if (!empty($fic)) 
	{
		// Suppression des indicateurs de ce jeu
		$indicateur->jeu_id = $jeu->jeu_id;
		$indicateur->efface();

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
					$pourcent['CN']=trim(str_replace ("pourcent_", "", $choix[3]->find('span[class*=pourcent]',0)->class));
					$pourcent['C2']=trim(str_replace ("pourcent_", "", $choix[4]->find('span[class*=pourcent]',0)->class));
					$pourcent['1'] = trim(str_replace (" %", "" , $pourcent['1'] ));
					$pourcent['N'] = trim(str_replace (" %", "" , $pourcent['N'] ));
					$pourcent['2'] = trim(str_replace (" %", "" , $pourcent['2'] ));
					
					$choix1=$choix[2];
					$choixN=$choix[3];
					$choix2=$choix[4];
					$match['choix1']=trim($choix1);
					$match['choixN']=trim($choixN);
					$match['choix2']=trim($choix2);
					$repartition[$i]=$match;
					$pourcentage[$i]=$pourcent;
					if ($debug) {
						echo "Match ".$choix[0] . " - " .$choix[1] . "<br>\n";
						//echo "Choix  => 1 : [" . htmlspecialchars($match['choix1']) . "]<br>";
						//echo "Choix  => N : [" . htmlspecialchars($match['choixN']) . "]<br>";
						//echo "Choix  => 2 : [" . htmlspecialchars($match['choix2']) . "]<br>";
						echo "Pourcentage C1 : [" . $pourcent['C1'] . "], CN : [" . $pourcent['CN'] . "], C2 : [" . $pourcent['C2'] . "]<br>\n";
						echo "Pourcentage 1 : [" . $pourcent['1'] . "], N : [" . $pourcent['N'] . "],  2 : [" . $pourcent['2'] . "]<br>\n";
					}		
					$i++;
					$indicateur->match_num=$i;
					$indicateur->choix1= $match['choix1'];
					$indicateur->choixN= $match['choixN'];
					$indicateur->choix2= $match['choix2'];
					$indicateur->pourcentageC1= $pourcent['C1'];
					$indicateur->pourcentageCN= $pourcent['CN'];
					$indicateur->pourcentageC2= $pourcent['C2'];
					$indicateur->pourcentage1= $pourcent['1'];
					$indicateur->pourcentageN= $pourcent['N'];
					$indicateur->pourcentage2= $pourcent['2'];
					$indicateur->ajoute();			
					if ($i==$nbMatchsDeCeJeu) break;					
				}

			}
		}


		if ($debug) {
			echo "<br><br><br>--- PHASE 2 : Calcul des indices de chaque joueurs --- <br>\n";
		}
		// Une fois les indicateurs des x matchs sont stockés en BD,
		// On va les lire pour mettre à jour les indices des joueurs

		// Lecture de tous les indices de gains de ce match
	  	$stmtIndicateur = $indicateur->litIndicateurs();
	  	$listeIndicateurs = $stmtIndicateur->fetchAll(PDO::FETCH_ASSOC);

		// Pronostic de tous les joueurs
		$pronostic = new Pronostic($db);
		$pronostic->jeu_id = $jeu->jeu_id; 
		$stmtPronostic = $pronostic->litPronostics();
		$pronostics = $stmtPronostic->fetchAll(PDO::FETCH_ASSOC);

		//var_dump($listeIndicateurs);

		if ($debug) {
			echo "Nb Joueurs :  " . sizeof($listeJoueurs)."<br>\n";
		}
		for($j=0;$j<sizeof($listeJoueurs);$j++)
		{
			// Déclare et initialise la moyenne des indices à 0
			$moyenne = 0;
			$nbCasesCoches = 0;
			$joueur=$listeJoueurs[$j];
			$joueur_id=$joueur["joueur_id"];
			$joueur_nom=$joueur["nom"];
			$prono=getPronoJoueur($pronostics,$joueur_id);
			$tableauChoix = array("1", "N", "2");
			//echo "Pronostics du Joueur {$joueur_id} : <br>";
			//var_dump($pronostic);
			// Pour les 15 pronostics possibles
			for ($i=1;$i<=15;$i++) {
				// Ce pronostic existe-t-il ? 
				if (strlen(trim($prono['pronostic'.$i])) != 0) {
					// Oui ? bon on regarde si c'est 1, N ou 2
					foreach ($tableauChoix as &$choix) {
			 			if (strpos($prono['pronostic'.$i], $choix) !== false) {
							$moyenne=$moyenne+getIndicateursMatchChoix($listeIndicateurs, $i, $choix);
					        $nbCasesCoches = $nbCasesCoches +1;
							//echo "La moyenne vaut {$moyenne} et {$nbCasesCoches} cases cochees<br>";
					    }
					}
				}
			}

			// Calcul de l'indice 		          
			$indice=$moyenne/$nbCasesCoches;
			$indice=round((100-$indice)/10, 2);

			// Pronostic de tous les joueurs
			$pronostic2 = new Pronostic($db);
			$pronostic2->jeu_id = $jeu->jeu_id; 
			$pronostic2->joueur_id = $joueur_id; 
			$pronostic2->chargePronostic();
			if ($debug) {
				echo "Joueur {$joueur_id} :  {$joueur_nom}<br>\n";
			}
			// MAJ Si pronostics !
			if ($nbCasesCoches>0) {
				if ($debug) {
					echo "Indice calcul :  {$indice} a partir de {$nbCasesCoches} pronostics<br>\n";
					echo "Avant => indice 7 : ".$pronostic2->IndiceGain7.", indice 15 : ".$pronostic2->IndiceGain15."<br>\n";
				}					
	 	  		$pronostic2->IndiceGain7=0;
				$pronostic2->IndiceGain15=0;
				if (strlen(trim($pronostic2->pronostic1))!=0) {
					if (strlen(trim($pronostic2->pronostic8))==0) {
				 		$pronostic2->IndiceGain7=$indice;
					} else {
				  		$pronostic2->IndiceGain15=$indice;
					}
				}

				if ($debug) {
					echo "Apres => indice 7 : ".$pronostic2->IndiceGain7.", indice 15 : ".$pronostic2->IndiceGain15."<br>\n";
				}
				$retour = $pronostic2->modifie();
				if ($debug && $retour !=true) {
					echo "Anomalie dans la mise à jour : ".$retour."<br>\n";
				}
			}
			else {
				if ($debug) {
					echo "Pas de pronostic !<br>\n";
				}					

			}
 		}

	}
?>