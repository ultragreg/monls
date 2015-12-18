<?php
include_once('../simple_html_dom.php');
$auth = base64_encode('user:motdepasse');

$aContext = array(
	'http' => array(
       'proxy' => 'fw-t-net.mipih.fr:3128', // This needs to be the server and the port of the NTLM Authentication Proxy Server. 
       'request_fulluri' => true,
       'header' => "Proxy-Authorization: Basic $auth",
       ),
	);

$context = stream_context_create($aContext); 

// get DOM from URL or file
$fic = file_get_html('http://www.sport4tune.com/lotofoot_grille.php', false, $context);

$jeux=array();

if (!empty($fic)) 
{
	// Liste des jeux
	$i=0;
	foreach($fic->find('div h4.titre-liste-loto') as $listeJeux) 
	{
		$jeu = array();
		$jeu['titre'] = iconv( "UTF-8","ISO-8859-1",$listeJeux->plaintext);
		$jeux[$i]=$jeu;
		$i++;
	}

// Description
	$i=0;
	foreach($fic->find('div p.texte-competition') as $description) 
	{
		$jeu=$jeux[$i];
		$jeu['description'] = iconv( "UTF-8","ISO-8859-1",$description->plaintext);
		$jeux[$i]=$jeu;
		$i++;
	}

	echo '<h1>Liste des jeux</h1>';
	foreach($jeux as $jeu) {
		echo $jeu['titre'] . ", Desc=" . $jeu['description'];
		echo "<br>";
	}
}


// do something... 
$fic->clear(); 
unset($fic);
// find all link
//foreach($html->find('ul.liste-lotofoot li div div.equipe_A') as $equipeA) 
//    echo $e->plaintext . '<br>';

?>

