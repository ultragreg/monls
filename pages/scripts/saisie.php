<?php 
session_start();
/*
Ce script enregistre la saisie d'un pronostic ou d'un résultat
Dans le cas de la saisie d'un résultat, il met à jour la table des statistiques de ce jeu
*/

function MAJStatistiquesJeu($debug, $statistique, $resultats, $listeJoueurs, $pronostics)
{

	$jeu_id      = $statistique->jeu_id;
	$saison_id   = $statistique->saison_id;
	if ($debug) {
		echo "MAJStatistiquesJournee : Saison id :".$saison_id.", Stat jeu :".$jeu_id."\n";
	}
	// Purge la table des statistiques avec ce jeu
	if ($statistique->efface() != true)   {  return false;  }

	// Si pas de résultat sur le premier match, on n'intègre pas ce jeu dans la table des statistiques !
	if (sizeof($resultats)==0 || getResultat($resultats,1)=="")   {   return false;   }

	// initialisation des variables
	$ListeresultatOk = array();
	$NbMatchsJoues   = array();

	// Pour ce jeu, on va rechercher calculer la moyenne de tous les joueurs
	$meilleur=0;
	$mauvais=100;
	for($j=0;$j<sizeof($listeJoueurs);$j++)
	{
		$joueur=$listeJoueurs[$j];
		$joueur_id=$joueur["joueur_id"];
		$joueur_nom=$joueur["nom"];
		$pronostic=getPronosticJoueur($joueur_id, $pronostics);
		$flash = $pronostic["flash"];
		// Initialisation des bons résultats à 0
		$ListeresultatOk[$j]=0;

		// Initialisation du nombre de matchs joués à 0
		$NbMatchsJoues[$j]=0;

		// Pour ce joueur et ce jeu, on va lire tous les pronostics pour les comparer aux résultats 
		for($k=1; $k <= 15 ; $k++)
		{
			// Résultat de ce match pour ce jeu
			$resultatJeu=getResultat($resultats,$k);
			// Pronostic du joueur pour ce match pour ce jeu
			$pronosticJeu = getPronostic($pronostic,$k);

			// Le résultat est bon ? on incrémente le nombre de résultat bon pour ce joueur et ce jeu
			$posresultat = isResultatBon($pronosticJeu, $resultatJeu);
			if ($posresultat)
			{
				$ListeresultatOk[$j]=$ListeresultatOk[$j]+1;
			}
			// Le joueur a joué ce match ? on incrémente son nombre de match joués
			if ($pronosticJeu)    $NbMatchsJoues[$j]=$NbMatchsJoues[$j]+1;
		}
		if ($debug) {
			echo "Nombre de Matchs : ".$NbMatchsJoues[$j]."\n";
		}
		// Calcul de sa moyenne
		if ($NbMatchsJoues[$j])
		{
			$moyenne=$ListeresultatOk[$j]/$NbMatchsJoues[$j]*100;
			$moyenne=round($moyenne,1);
			// Enregistrement en base de la moyenne de ce joueur pour ce jeu
			$statistique->joueur_id = $joueur_id;
			$statistique->valeur = $moyenne;
			$statistique->flash = $flash;
			$retour = $statistique->ajoute();
			if ($debug) {
				echo "Statistique mis à jour avec la moyenne de ".$moyenne."\n";
			}
			//setMiseAJourStat($saison_id, $jeu_id, $joueur_id, $moyenne, $flash);
			//echo "<h2>Joueur:".$joueur_nom.", moyenne:".$moyenne."</h2>";
		}
	}
	return true;
}



header('Content-Type: application/json');

// Script de saisie de pronostics ou d'un résultat

// include database and object files
include_once '../config/database.php';
include_once '../config/util.php';
include_once '../objects/saison.php';
include_once '../objects/jeu.php';
include_once '../objects/resultat.php';
include_once '../objects/statistique.php';
include_once '../objects/pronostic.php';
include_once '../objects/joueur.php';
include_once '../objects/indicateur.php';


if (isset($_SESSION['id_joueur'])) {
 
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

	// Résultat de ce jeu
	$resultat = new Resultat($db);
	$resultat->jeu_id = $jeu->jeu_id; 

	// Pronostic de ce joueur
	$pronostic = new Pronostic($db);
	$pronostic->jeu_id = $jeu->jeu_id; 
	$pronostic->joueur_id = $_SESSION['id_joueur']; 
	$stmtPronostic = $pronostic->litPronostics();
	$pronostics = $stmtPronostic->fetchAll(PDO::FETCH_ASSOC);

	// Statistique
	$statistique = new Statistique($db);
	$statistique->jeu_id	= $jeu->jeu_id;
	$statistique->saison_id	= $saison->saison_id;

	// Recherche la liste des joueurs
	$joueurs = new Joueur($db);
	$stmtJoueurs = $joueurs->litJoueurs();
	$listeJoueurs = $stmtJoueurs->fetchAll(PDO::FETCH_ASSOC);
 
	// Indicateur de ce joueur
	$indicateur = new Indicateur($db);
	$indicateur->jeu_id = $jeu->jeu_id; 


	$json["resultat"]="";

	$debug=false;
	if (isset($_GET['type']) && isset($_GET['param']) ) 
	{               
	      // Initialisation des variables
	      $param=$_GET["param"];
	      $type=$_GET["type"];
	      
	      if (isset($_GET['debug']))
	      {
	      	// Mode debug
	      	$debug=true;
	      }
	      
	      // Protège les caractères qui mettent le bousin (par exemple les cotes) et supprimer les balises html par sécurité
	      $param=strip_tags (stripslashes ($param));
	      $type=strip_tags (stripslashes ($type));
	
	      $idMatchs = explode(":", $param);
		  if ($debug) {
		  	echo "Mode DEBUG\nParametres de saisie :\n";
		  	var_dump($idMatchs);
		  }
	
		  // MAJ du résultat
	      if ($type=="R") {
	      	  if ($jeu->jeu_bloque=="0") {
				  $json["resultat"]="Jeu débloqué, mise à jour du résultat impossible";	      	  	
	      	  }
	      	  else {     	
				  if ($debug) {
				  	echo "Mise à jour du résultat\n";
				  }
		          $resultat->chargeResultat();
		          $resultat->resultat1="";
		          $resultat->resultat2="";
		          $resultat->resultat3="";
		          $resultat->resultat4="";
		          $resultat->resultat5="";
		          $resultat->resultat6="";
		          $resultat->resultat7="";
		          $resultat->resultat8="";  
		          $resultat->resultat9="";  
		          $resultat->resultat10="";  
		          $resultat->resultat11="";
		          $resultat->resultat12="";
		          $resultat->resultat13="";
		          $resultat->resultat14="";
		          $resultat->resultat15="";
		          $resultat->nom= $_SESSION['nom_joueur'];
				  $resultat->date= date("Y/m/d H:i:s"); 
		          for($i=0; $i <= sizeof($idMatchs) ; $i++) {
		            if (isset($idMatchs[$i]) && strlen($idMatchs[$i])>0) {
		              $racine = substr($idMatchs[$i], 0, strlen($idMatchs[$i])-1);
		              $choix  = substr($idMatchs[$i], -1);
		              if ($racine=="btn1") {
		                $resultat->resultat1=$resultat->resultat1.$choix;
		              }
		              if ($racine=="btn2") {
		                $resultat->resultat2=$resultat->resultat2.$choix;
		              }
		              if ($racine=="btn3") {
		                $resultat->resultat3=$resultat->resultat3.$choix;
		              }
		              if ($racine=="btn4") {
		                $resultat->resultat4=$resultat->resultat4.$choix;
		              }
		              if ($racine=="btn5") {
		                $resultat->resultat5=$resultat->resultat5.$choix;
		              }
		              if ($racine=="btn6") {
		                $resultat->resultat6=$resultat->resultat6.$choix;
		              }
		              if ($racine=="btn7") {
		                $resultat->resultat7=$resultat->resultat7.$choix;
		              }
		              if ($racine=="btn8") {
		                $resultat->resultat8=$resultat->resultat8.$choix;
		              }
		              if ($racine=="btn9") {
		                $resultat->resultat9=$resultat->resultat9.$choix;
		              }
		              if ($racine=="btn10") {
		                $resultat->resultat10=$resultat->resultat10.$choix;
		              }
		              if ($racine=="btn11") {
		                $resultat->resultat11=$resultat->resultat11.$choix;
		              }
		              if ($racine=="btn12") {
		                $resultat->resultat12=$resultat->resultat12.$choix;
		              }
		              if ($racine=="btn13") {
		                $resultat->resultat13=$resultat->resultat13.$choix;
		              }
		              if ($racine=="btn14") {
		                $resultat->resultat14=$resultat->resultat14.$choix;
		              }
		              if ($racine=="btn15") {
		                $resultat->resultat15=$resultat->resultat15.$choix;
		              }
		            }
		          }
				  if ($debug) {
					echo "Objet Sauvegardé:\n";
				  	var_dump($resultat);
				  }
		          if (strlen($resultat->resultat_id)>=1)
		          {
						if ($debug) {
					  		echo "Le résultat existe déjà, on le modifie\n";
						}
		                $retour = $resultat->modifie();
		          }  
		          else
		          {
						if ($debug) {
		          			echo "Le résultat n'existe pas encore, on l'ajoute\n";
		          		}	
		                $retour = $resultat->ajoute();
		          }
				  $json["resultat"]=$retour;

				  // Etape finale => Recalculer les statistiques de ce jeu
				  $resultatMAJStat = MAJStatistiquesJeu($debug, $statistique, $resultat, $listeJoueurs, $pronostics);
				  $json["majStat"]=$resultatMAJStat;
			  }
	      }
   		  // Fin de MAJ du résultat

	
   		  // MAJ d'un pronostic d'un joueur
	      if ($type=="P") {
	      	  if ($jeu->jeu_bloque=="1") {
				  $json["resultat"]="Jeu bloqué, mise à jour du pronostic impossible";	      	  	
	      	  }
	      	  else {
				  if ($debug) {
				  	echo "Mise à jour d'un pronostic\n";
				  }
				  // Lecture des indices de gains 
				  $stmtIndicateur = $indicateur->litIndicateurs();
				  $listeIndicateurs = $stmtIndicateur->fetchAll(PDO::FETCH_ASSOC);

		          $pronostic->chargePronostic();
		          $pronostic->pronostic1="";
		          $pronostic->pronostic2="";
		          $pronostic->pronostic3="";
		          $pronostic->pronostic4="";
		          $pronostic->pronostic5="";
		          $pronostic->pronostic6="";
		          $pronostic->pronostic7="";
		          $pronostic->pronostic8="";  
		          $pronostic->pronostic9="";  
		          $pronostic->pronostic10="";  
		          $pronostic->pronostic11="";
		          $pronostic->pronostic12="";
		          $pronostic->pronostic13="";
		          $pronostic->pronostic14="";
		          $pronostic->pronostic15="";
				  $pronostic->IndiceGain7=0;
				  $pronostic->IndiceGain15=0;
		          // Déclare et initialise la moyenne des indices à 0
		          $moyenne = 0;
		          $nbCasesCoches = 0;

		          // Lecture des paramètres reçus !
		          // Attention, peut exister plusieurs pronostics pour le même match (vide, 1, N, 2, 1N, N2, 12, 1N2).
		          // On en profite pour remplir le tableau des moyennes pour chaque match
		          for($i=0; $i <= sizeof($idMatchs) ; $i++) {
		            if (isset($idMatchs[$i]) && strlen($idMatchs[$i])>0) {
		              $racine = substr($idMatchs[$i], 0, strlen($idMatchs[$i])-1);
		              $choix  = substr($idMatchs[$i], -1);
		              if ($racine=="btn1") {
		                $pronostic->pronostic1=$pronostic->pronostic1.$choix;
		                // Ajoute à la moyenne le pourcentage correspondant au choix 1, N ou 2
		                $moyenne=$moyenne+getIndicateursMatchChoix($listeIndicateurs, 1, $choix);
		                $nbCasesCoches = $nbCasesCoches +1;
		              }
		              if ($racine=="btn2") {
		                $pronostic->pronostic2=$pronostic->pronostic2.$choix;
		                // Ajoute à la moyenne le pourcentage correspondant au choix 1, N ou 2
		                $moyenne=$moyenne+getIndicateursMatchChoix($listeIndicateurs, 2, $choix);
		                $nbCasesCoches = $nbCasesCoches +1;
		              }
		              if ($racine=="btn3") {
		                $pronostic->pronostic3=$pronostic->pronostic3.$choix;
		                // Ajoute à la moyenne le pourcentage correspondant au choix 1, N ou 2
		                $moyenne=$moyenne+getIndicateursMatchChoix($listeIndicateurs, 3, $choix);
		                $nbCasesCoches = $nbCasesCoches +1;
		              }
		              if ($racine=="btn4") {
		                $pronostic->pronostic4=$pronostic->pronostic4.$choix;
		                // Ajoute à la moyenne le pourcentage correspondant au choix 1, N ou 2
		                $moyenne=$moyenne+getIndicateursMatchChoix($listeIndicateurs, 4, $choix);
		                $nbCasesCoches = $nbCasesCoches +1;
		              }
		              if ($racine=="btn5") {
		                $pronostic->pronostic5=$pronostic->pronostic5.$choix;
		                // Ajoute à la moyenne le pourcentage correspondant au choix 1, N ou 2
		                $moyenne=$moyenne+getIndicateursMatchChoix($listeIndicateurs, 5, $choix);
		                $nbCasesCoches = $nbCasesCoches +1;
		              }
		              if ($racine=="btn6") {
		                $pronostic->pronostic6=$pronostic->pronostic6.$choix;
		                // Ajoute à la moyenne le pourcentage correspondant au choix 1, N ou 2
		                $moyenne=$moyenne+getIndicateursMatchChoix($listeIndicateurs, 6, $choix);
		                $nbCasesCoches = $nbCasesCoches +1;
		              }
		              if ($racine=="btn7") {
		                $pronostic->pronostic7=$pronostic->pronostic7.$choix;
		                // Ajoute à la moyenne le pourcentage correspondant au choix 1, N ou 2
		                $moyenne=$moyenne+getIndicateursMatchChoix($listeIndicateurs, 7, $choix);
		                $nbCasesCoches = $nbCasesCoches +1;
		              }
		              if ($racine=="btn8") {
		                $pronostic->pronostic8=$pronostic->pronostic8.$choix;
		                // Ajoute à la moyenne le pourcentage correspondant au choix 1, N ou 2
		                $moyenne=$moyenne+getIndicateursMatchChoix($listeIndicateurs, 8, $choix);
		                $nbCasesCoches = $nbCasesCoches +1;
		              }
		              if ($racine=="btn9") {
		                $pronostic->pronostic9=$pronostic->pronostic9.$choix;
		                // Ajoute à la moyenne le pourcentage correspondant au choix 1, N ou 2
		                $moyenne=$moyenne+getIndicateursMatchChoix($listeIndicateurs, 9, $choix);
		                $nbCasesCoches = $nbCasesCoches +1;
		              }
		              if ($racine=="btn10") {
		                $pronostic->pronostic10=$pronostic->pronostic10.$choix;
		                // Ajoute à la moyenne le pourcentage correspondant au choix 1, N ou 2
		                $moyenne=$moyenne+getIndicateursMatchChoix($listeIndicateurs, 10, $choix);
		                $nbCasesCoches = $nbCasesCoches +1;
		              }
		              if ($racine=="btn11") {
		                $pronostic->pronostic11=$pronostic->pronostic11.$choix;
		                // Ajoute à la moyenne le pourcentage correspondant au choix 1, N ou 2
		                $moyenne=$moyenne+getIndicateursMatchChoix($listeIndicateurs, 11, $choix);
		                $nbCasesCoches = $nbCasesCoches +1;
		              }
		              if ($racine=="btn12") {
		                $pronostic->pronostic12=$pronostic->pronostic12.$choix;
		                // Ajoute à la moyenne le pourcentage correspondant au choix 1, N ou 2
		                $moyenne=$moyenne+getIndicateursMatchChoix($listeIndicateurs, 12, $choix);
		                $nbCasesCoches = $nbCasesCoches +1;
		              }
		              if ($racine=="btn13") {
		                $pronostic->pronostic13=$pronostic->pronostic13.$choix;
		                // Ajoute à la moyenne le pourcentage correspondant au choix 1, N ou 2
		                $moyenne=$moyenne+getIndicateursMatchChoix($listeIndicateurs, 13, $choix);
		                $nbCasesCoches = $nbCasesCoches +1;
		              }
		              if ($racine=="btn14") {
		                $pronostic->pronostic14=$pronostic->pronostic14.$choix;
		                // Ajoute à la moyenne le pourcentage correspondant au choix 1, N ou 2
		                $moyenne=$moyenne+getIndicateursMatchChoix($listeIndicateurs, 14, $choix);
		                $nbCasesCoches = $nbCasesCoches +1;
		              }
		              if ($racine=="btn15") {
		                $pronostic->pronostic15=$pronostic->pronostic15.$choix;
		                // Ajoute à la moyenne le pourcentage correspondant au choix 1, N ou 2
		                $moyenne=$moyenne+getIndicateursMatchChoix($listeIndicateurs, 15, $choix);
		                $nbCasesCoches = $nbCasesCoches +1;
		              }
		            }
		          }

				  // Calcul de l'indice 		          
				  $indice=$moyenne/$nbCasesCoches;
				  $indice=round((100-$indice)/10, 2);

				  if (strlen(trim($pronostic->pronostic8))==0) {
				  	$pronostic->IndiceGain7=$indice;
				  } else {
				  	$pronostic->IndiceGain15=$indice;
				  }

				  if ($debug) {
					echo "Objet Sauvegardé:\n";
				  	var_dump($pronostic);
				  }
		          if (strlen($pronostic->pronostic_id)>=1)
		          {
						if ($debug) {
					  		echo "Le pronostic existe déjà, on le modifie\n";
						}
		                $retour = $pronostic->modifie();
		          }  
		          else
		          {
						if ($debug) {
		          			echo "Le pronostic n'existe pas encore, on l'ajoute\n";
		          		}	
		                $retour = $pronostic->ajoute();
		          }

				  $json["resultat"]=$retour;	
				  $json["indice"]=$indice;	
	      	  }
	      }
   		  // Fin de MAJ d'un pronostic d'un joueur

	}  
	else
	{
	  $json["resultat"]="Il manque des paramètres";
	}
}	
else
{
  $json["resultat"]="Pas connecté !";
}
echo json_encode($json);

?>
