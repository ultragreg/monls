<?php 
session_start();



header('Content-Type: application/json');

// Script de saisie de pronostics ou d'un résultat

// include database and object files
include_once '../config/database.php';
include_once '../config/util.php';
include_once '../objects/saison.php';
include_once '../objects/jeu.php';
include_once '../objects/pronostic.php';
include_once '../objects/joueur.php';


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

	$debug=false;
	if (isset($_GET['type'])) 
	{               
		// Initialisation des variables
		$type=$_GET["type"];
		$type=strip_tags (stripslashes ($type));
		// Mode debug
		if (isset($_GET['debug']))
		{
			$debug=true;
			echo "Mode DEBUG\nParametre : ".$type."\n";
		}
		// Déblocage
		if ($type=="D") {
			if ($jeu->jeu_bloque=="0") {
				$json["resultat"]="Le jeu est déjà débloqué";
			}
			else {     	
				if ($debug) {
					echo "Blocage du jeu\n";
				}
				$json["resultat"]=$jeu->debloque();				
			}
		}
		// Blocage
		else if ($type=="B") {
			if ($jeu->jeu_bloque=="1") {
				$json["resultat"]=false;
				$json["commentaire"]="Jeu déjà bloqué";
			} else {

				if ($debug) {
					echo "Déblocage du jeu\n";
				}

		        // Recherche la liste des joueurs
		        $joueurs = new Joueur($db);
		        $stmtJoueurs = $joueurs->litJoueurs();
		        $listeJoueurs = $stmtJoueurs->fetchAll(PDO::FETCH_ASSOC);

		        // Pronostics des joueurs
		        $pronostic = new Pronostic($db);
		        $pronostic->jeu_id = $jeu->jeu_id; 
		        $stmtPronostics = $pronostic->litPronostics();
		        $listePronostics = $stmtPronostics->fetchAll(PDO::FETCH_ASSOC);
				$nbJoueurs=0;
		        for($j=0;$j<sizeof($listeJoueurs);$j++)
		        {
      				$joueur_id=$listeJoueurs[$j]["joueur_id"];
					if ($listePronostics) {
        				$prono=getPronoJoueur($listePronostics, $joueur_id);
					}
					if ($debug && isset($prono)) {
						echo "Joueur {$joueur_id} a le prono ".getPronostic($prono, 1)." pour le premier match<br>\n";
					}
					// Pas de pronostic ou alors pronostic vide (car effacé)
					if (!isset($prono) || (isset($prono) && getPronostic($prono, 1)=="") ) {
						$nbJoueurs++;
		            }
		        }
		        if ($nbJoueurs==0) {
					$json["resultat"]=$jeu->bloque();
		        }
		        else {
					$json["resultat"]="Il manque ".$nbJoueurs." pronostic(s).";
		        }
			}
		}
		// Autre ? 
		else {
		  	$json["resultat"]="Paramètre inconnu";
		}
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
