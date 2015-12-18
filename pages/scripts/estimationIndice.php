<?php 
	session_start();
/*
Ce script lit les pronostics des matchs pour une journée donné sur le site de pronosoft
Il est dans le cron du serveur et est exécuté toutes les 4 heures
Le résultat est stocké dans la table 'indicateur'
*/

function clean($string) {
	$retour=preg_replace('/[^A-Za-z0-9\- ,]/', '', $string);
	return str_replace("nbspeuro","",$retour); // Removes special chars.
}


function CalculGains ($debug, $jeu, $pronostic)
{	
	$resultat="";
	if ($debug) {
		echo "jeu : \n";
		var_dump($jeu);
		echo "pronostic : \n";
		var_dump($pronostic);
	}
	/*
	$jeu_id      = $statistique->jeu_id;
	$saison_id   = $pronostic->saison_id;
	if ($debug) {
		echo "CalculGains : Saison id :".$saison_id.", Stat jeu :".$jeu_id."\n";
	}
	*/
	if ($debug) {
		echo "Code Jeu chez pronosoft : " . $jeu->idSite . "\n";
	}

	//interrogation du site avec l'idSite via POST par curl
	$url='http://www.pronosoft.com/fr/lotofoot/estimateur.php?id715=' . $jeu->idSite;
	//$nameForm = 'estimateur_' . $idSite;
	$postfields = array();
	//$postfields["action"] = $url;
	$postfields["action"] = "submit";
	if ($debug) {
		echo "Url chez pronosoft : " . $url . "\n";
	}
	
	//on remplit dynamiquement les champs nécessaire au formulaire
	$j = 0;
	for($i=1; $i <= 15 ; $i++) 
	{
		$prono = getProno($pronostic, $i);
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
	$postfields["id715"] = $jeu->idSite;
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0");
	curl_setopt($ch, CURLOPT_REFERER, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	//récupération du résultat html
	$result = curl_exec($ch);
	curl_close($ch);

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
				$rangs[$j]=clean($rang->find('td',0)->plaintext);
				$rapports[$j]=clean($rang->find('td',1)->plaintext);
				$j++;
			}
			//$estim['titre']= clean($titre);
			$estim['rangs']=$rangs;
			$estim['rapports']=$rapports;
			if ($debug) {
				echo $titre. "\n";
				echo "Rangs : \n";
				var_dump($rangs);
				echo "rapports : \n";
				var_dump($rapports);
			}
			
			//on remplit le tableau
			$estimation[$i]=$estim;
			$i++;
		}
		$i=0;
		if(empty($estimation)) {
  			$resultat["resultat"]=false;
			$resultat["commentaire"]="Le chargement des estimations a échoué";
		} else {
			$resultat=$estimation;
		}

		/*
		//on déroule le tableau, pour afficher les estimations (LF7 / LF15)
		$nbProno = getNbPronosticJoueur($pronostic);
		if ($debug) {
			echo "Nombre de prono. :" . $nbProno ;
		}
		foreach($estimation as $estim) {
			if ($nbProno == 7 && $i>0) break;
			$rangs=$estim['rangs'];
			$rapports=$estim['rapports'];
			//parcours des rangs
			$j=0;
			foreach($rangs as $index)
			{
				//affichage des rangs
				echo $rangs[$j] . '<br>';
				echo $rapports[$j] . '<br>';
				//echo "<br>";
				$j++;
			}
			$i++;
		}
		*/
	}
	else {
		$resultat["resultat"]=false;
		$resultat["commentaire"]="Le chargement des estimations a échoué";
	}	
	return $resultat;
}


header('Content-Type: application/json');

// Script de saisie de pronostics ou d'un résultat

// include database and object files
include_once('../simplehtmldom_1_5/simple_html_dom.php');
include_once '../config/database.php';
include_once '../config/util.php';
include_once '../objects/saison.php';
include_once '../objects/jeu.php';
include_once '../objects/pronostic.php';

if (isset($_SESSION['id_joueur'])) {

    $debug=false;
    if (isset($_GET['debug']))
    {
      // Mode debug
      $debug=true;
    }

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

	// Pronostic de ce joueur
	$pronostic = new Pronostic($db);
	$pronostic->jeu_id = $jeu->jeu_id; 
	$pronostic->joueur_id = $_SESSION['id_joueur']; 
	$pronostic->chargePronostic();
	  
	$json["resultat"]="";

	if (isset($_GET['debug']))
	{
	  // Mode debug
	  $debug=true;
	}
	// Recherche des informations sur l'indice de gains 
	$json= CalculGains($debug, $jeu, $pronostic);
}
// Erreur si pas de session
else
{
  $json["resultat"]=false;
  $json["commentaire"]="Pas connecté !";
}

echo json_encode($json);
?>
