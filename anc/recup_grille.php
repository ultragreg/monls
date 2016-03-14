<?php 
session_start();
ini_set('zlib.output_compression_level', 6);
//ob_start('ob_gzhandler');
?>
	<?php  include("scripts/head.php");	?>
	
	<body>    
  <?php include ("scripts/debutpage.php"); ?>

	<script type="text/javascript" src="js/saisiejeu.js"></script>
	
	<div id="conteneur">		

    <?php include ("scripts/header.php"); ?>
    
    <?php include ("scripts/menu.php"); ?>
    
    <?php 
    // On affiche cette page uniquement si on est connecté 
    if (!isset($_SESSION['id_joueur']) || $_SESSION['admin']!='O') 
    {
      // Authentification correcte
      header('Location: index.php');
    }
    $joueur_id=$_SESSION['id_joueur'];
    ?>
    		    	
		<div id="contenu">
		  <?php 
		  $db_link = ouvre_base();
		  if ($db_link)
      {
          ?>          	  		 	
		  
		   <h2 id="top">Liste des Jeux (PRONOSOFT)</h2>

<?php
include_once('simplehtmldom_1_5/simple_html_dom.php');
//$auth = base64_encode('user:motdepasse');

//$aContext = array(
//	'http' => array(
       //'proxy' => 'fw-t-net.mipih.fr:3128', // This needs to be the server and the port of the NTLM Authentication Proxy Server. 
       //'request_fulluri' => true,
       //'header' => "Proxy-Authorization: Basic $auth",
       //),
	//);

//$context = stream_context_create($aContext); 

// get DOM from URL or file
$fic = file_get_html('http://www.pronosoft.com/fr/lotofoot/prochaines-grilles.htm', false);
$fic_est = file_get_html('http://www.pronosoft.com/fr/lotofoot/estimateur.php', false);

$jeux=array();

if (!empty($fic)) 
{
	// Liste des jeux
	$i=0;
	// on récupère toutes les grilles existantes qu'on ajoute une à une dans un tableau
	foreach($fic->find('table[class*=grilles]') as $listeJeux) 
	{
		//création d'un tableau contenant un jeu
		$jeu = array();
		$numero=0;
		$numero_est=0;
		$id=0;
		//Récupération du Titre du Jeu et de la Description
		$titre = $listeJeux->find('th',0)->plaintext;
		
		//On récupère le numéro officiel de la grille LF7 pour ensuite s'en servir pour chercher l'id pronosoft
		$Ttitre=explode(' ',$titre);
		foreach($Ttitre as $mot)
		{
			if (strpos($mot,'&deg;') == TRUE) {
				$Tnumero=explode('&deg;',$mot);
				$numero=$Tnumero[1];
				break;
			}
		}
		
		
		foreach($fic_est->find('form[name*=estimateur]') as $listePronosoft)
		{		
			$titre_est = $listePronosoft->find('th',0)->plaintext;
			
			//On récupère le numéro officiel de la grille LF7 pour ensuite s'en servir pour chercher l'id pronosoft
			$Ttitre_est=explode(' ',$titre_est);
			foreach($Ttitre_est as $mot)
			{
				if (strpos($mot,'&deg;') == TRUE) {
					$Tnumero=explode('&deg;',$mot);
					$numero_est=$Tnumero[1];
					break;
				}
			}
			
			//On récupère l'id Pronosoft de la grille
			if ($numero == $numero_est)
			{
				$e = $listePronosoft->outertext;
				$id = substr($e,0,45);
				$ID=explode('"',$id);
				$id = $ID[3];
				$id = str_replace ("estimateur_","",$id);
				//echo $id;
				break;
			}
		}
		
		$comment = $listeJeux->find('th',1)->plaintext;
		$comment = $comment . ' ' . $listeJeux->find('th',2)->plaintext;
		//$titre = str_replace ("LF7 avant", "- LF7 avant" , $titre);
		//$titre = str_replace ("LF15 avant", "- LF15 avant " , $titre);
		//$titre = str_replace ("Avant", "- Avant" , $titre);
		
		//si non grille officielle, rajout libellé
		if (strpos($titre,'n&deg;') == FALSE) {
			$comment = $comment . " <span style='color:red'>--- NON OFFICIELLE ---</span>";
		}
		$jeu['titre'] = $titre;
		$jeu['comment'] = $comment;
		$jeu['idSite'] = $id;
		
		$grille = array();
		$equipe1 = array();
		$equipe2 = array();
		$j=0;
		//récupération de la liste des équipes domicile
		foreach($listeJeux->find('td[class*=home]') as $equipe)
		{
			$equipe1[$j]=$equipe->plaintext;
			$equipe1[$j] = str_replace (" -", "" , $equipe1[$j] );
			$j++;
		}
		$j=0;
		//récupération de la liste des équipes extérieur
		foreach($listeJeux->find('td[class*=ext]') as $equipe)
		{
			$equipe2[$j]=$equipe->plaintext;
			$j++;
		}
		$grille['equipe1'] = $equipe1;
		$grille['equipe2'] = $equipe2;
		
		$jeu['grille'] = $grille;
		
		//ajout du jeu en cours dans le tableau global des jeux
		$jeux[$i]=$jeu;
		$i++;
	}


	// Pour chaque jeu, on l'affiche à l'écran avec possibilité de le choisir
	foreach($jeux as $jeu) {
		//écriture du titre
		echo '<h3>' . $jeu['titre'] . ' - ' . $jeu['comment'] .' (id : ' . $jeu['idSite'] . ')</h3>';
		$titre = $jeu['titre'];
		$comment = str_replace (" <span style='color:red'>--- NON OFFICIELLE ---</span>", "" , $jeu['comment'] );
		//echo "<br>";
		$id=$jeu['idSite'];
		$grille = $jeu['grille'];
		$equipe1 = $grille['equipe1'];
		$equipe2 = $grille['equipe2'];
		$j=0;
		
		//formulaire caché pour sélection de la grille en cours et renvoi vers la page admin_jeu.php
		echo "<form method=\"post\" action=\"admin_jeu.php\" class=\"formconnexion\" name=\"form\" >";
		echo '<input name="operation" type="hidden" value="N" />';
        echo '<input name="jeu_id" type="hidden" value="" />';
		echo '<input name="titre" id="titre" type="hidden" value="' . $titre . '" />';
		echo '<input name="idSite" id="idSite" type="hidden" value="' . $id . '" />';
		echo '<input name="bloque" id="bloque" type="hidden" value="off" />';
		echo '<input name="commentaire" id="commentaire" type="hidden" value="' . $comment . '" />';
		
		//affichage du tableau
		echo '<table class="jeu" id="tableauresultat">
							<tr>
								<td class="infotitre">Pos.</td>
								<td class="infotitre">Equipe domicile</td>
								<td class="infotitre">Equipe extérieure</td>
							</tr>';
		
		//parcours des équipes de la grille en cours
		foreach($equipe1 as $index)
		{
			$k=$j+1;
			if ($k%2) echo '<tr class=\'enreg paire\'>';
			else echo '<tr class=\'enreg impaire\'>';
			
			//affichage du tableau, et préparation du formulaire dynamiquement
			echo '<td align=center>' . $k . '</td>';
			echo '<td class="eqd"><input name="equipe' . $k . 'd" id="equipe1d" type="hidden" value="' . $equipe1[$j] . '" />' . $equipe1[$j] . '</td>';
			echo '<td class="eqg"><input name="equipe' . $k . 'v" id="equipe1d" type="hidden" value="' . $equipe2[$j] . '" />' . $equipe2[$j] . '</td>';
			echo '</tr>';
			//echo $equipe1[$j] . ' - ' . $equipe2[$j];
			//echo "<br>";
			$j++;
		}
		echo "</table>";
		//rajout du bouton Choisir
		echo '<input type="submit" value="Choisir" class="bouton" /></form>';
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

	<?php 
        ferme_base($db_link);
      } 
	    ?>
	</div>
		
    <?php include ("scripts/footer.php"); ?>
	</div>
	</body>
	
</html>
