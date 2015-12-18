<?php 
session_start();
/*
Ce script lit les rapports des matchs pour une journée donné sur le site de pronosoft
Il est exécuté à la demande par la page d'administration des rapports
Le résultat est renvoyé sous la forme d'un tableau json 
*/


function lectureRapports($debug, $type) {
	$jeux=array();
	$i=0;
	//parcours de la première page
	$fic = file_get_html('http://www.pronosoft.com/fr/lotofoot/livescore.php', false);
	if (!empty($fic)) 
	{
		// on récupère toutes les grilles existantes qu'on ajoute une à une dans un tableau
		foreach($fic->find('div[class=box_live]') as $listeJeux) 
		{
			//création d'un tableau contenant un jeu
			$jeu = array();
			
			//Récupération du Titre du Jeu
			$titre = $listeJeux->find('h2',0)->plaintext;		
			$comment = $listeJeux->find('thead',0)->plaintext;			
			$rapports = array();
			$rapport = $listeJeux->find('div[class=rapports]');
			
			$j=0;
			//récupération des rangs de gain
			foreach($rapport as $grille)
			{
				foreach($grille->find('tr') as $rang)
				{
					$rapports[$j]['rang']=trim($rang->find('td',0)->plaintext);
					$rapports[$j]['gagnant']=trim($rang->find('td',1)->plaintext);
					$rapports[$j]['rapport']=trim($rang->find('td',2)->plaintext);
					$j++;
				}
				//si moins de 4 rangs, on garde la grille car LF7
				//si au moins 4 rangs, on garde la grille car LF15
				if ( ($type=="7" && $j<4 && $j>0) ||
					($type=="15" && $j>3) )
				{
					$jeu['titre'] = trim($titre);
					$jeu['commentaire'] = trim($comment);
					$jeu['rapports'] = $rapports;
					//ajout du jeu en cours dans le tableau global des jeux
					$jeux[$i]=$jeu;
					$i++;
				}
				$j=0;
			}
						
		}
	}
	
	//parcours de la seconde page
	$fic = file_get_html('http://www.pronosoft.com/fr/lotofoot/resultats-et-rapports.php', false);
	if (!empty($fic)) 
	{
		// on récupère toutes les grilles existantes qu'on ajoute une à une dans un tableau
		foreach($fic->find('div[class=box_live]') as $listeJeux) 
		{
			//création d'un tableau contenant un jeu
			$jeu = array();
			
			//Récupération du Titre du Jeu
			$titre = $listeJeux->find('h2',0)->plaintext;
			$comment = $listeJeux->find('thead',0)->plaintext;
			$rapports = array();
			$rapport = $listeJeux->find('div[class=rapports]');
			
			//récupération des rangs de gain
			foreach($rapport as $grille)
			{
				foreach($grille->find('tr') as $rang)
				{
					$rapports[$j]['rang']=trim($rang->find('td',0)->plaintext);
					$rapports[$j]['gagnant']=trim($rang->find('td',1)->plaintext);
					$rapports[$j]['rapport']=trim($rang->find('td',2)->plaintext);
					$j++;
				}
				//si moins de 4 rangs, on garde la grille car LF7
				//si au moins 4 rangs, on garde la grille car LF15
				if ( ($type=="7" && $j<4 && $j>0) ||
					($type=="15" && $j>3) )
				{
					$jeu['titre'] = trim($titre);
					$jeu['comment'] = trim($comment);
					$jeu['rapports'] = $rapports;
					//ajout du jeu en cours dans le tableau global des jeux
					$jeux[$i]=$jeu;
					$i++;
				}
				$j=0;
			}
		}
	}
	return($jeux);
}

header('Content-Type: application/json');

// include database and object files
include_once('../simplehtmldom_1_5/simple_html_dom.php');

if (isset($_SESSION['id_joueur'])) {

    $debug=false;
    if (isset($_GET['debug']))
    {
      // Mode debug
      $debug=true;
    }
    if (isset($_GET['type']))
    {
    	$type=$_GET['type'];
		// Recherche des informations sur l'indice de gains 
		$json["resultat"]=true;
		$json["rapports"]=lectureRapports($debug, $type);
    } else {
		$json["resultat"]=false;
		$json["commentaire"]="Type non renseigné";	
    }

}
// Erreur si pas de session
else
{
  $json["resultat"]=false;
  $json["commentaire"]="Pas connecté !";
}

echo json_encode($json);
?>
