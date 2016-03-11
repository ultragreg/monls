<?php 
session_start();
ini_set('zlib.output_compression_level', 6);
//ob_start('ob_gzhandler');
?>
<?php  include("scripts/head.php");	?>

<body>    
  <?php include ("scripts/debutpage.php"); ?>

  <script type="text/javascript" src="js/calendar.js"></script>

  <div id="conteneur">	

    <?php include ("scripts/header.php"); ?>
    
    <?php include ("scripts/menu.php"); ?>
    
    <?php 
    // On affiche cette page uniquement si on est connect  et administrateur
    if (!isset($_SESSION['id_joueur']) || $_SESSION['admin']!='O') 
    {
      // Authentification correcte
      header('Location: index.php');
    }
    
    $chaine15 = "";
    if (isset($_POST['rapports15']))
    {

				$db_link = ouvre_base();
				if ($db_link)
				{
			          $jeu        = getJeuCourant();
			          $saison_id  = getIDSaisonCourante();
			          if ($jeu) {

			            $jeu_id     = $jeu["jeu_id"];
			            // Suppression des statistiques
					    $requete=	"delete from rapport where jeu_id='$jeu_id' and type='15'";
					    $result = mysql_query($requete);

				    	echo "****************************************". $jeu_id . "<br>\n";
				      $chaine15 = $_POST['rapports15'];
				      $array = explode(PHP_EOL, $chaine15);
				      foreach ($array as &$value) {
				 	   	echo $value . "<br>\n";

				 	   	$finRang = strpos ($value, ']Gagnant:[');
				 	   	$rang= substr($value, 6, $finRang - 6);

				 	   	$finGagnant = strpos ($value, ']Rapport:[');
				 	   	$gagnant= substr($value, $finRang+10, $finGagnant - $finRang -10);

				 	   	$finRapport = strpos ($value, '].');
				 	   	$rapport= substr($value, $finGagnant+10, $finRapport - $finGagnant -10);
				 	   	if (isset($rang) && $rang!=0) {
							 $requete=	"insert into rapport (jeu_id, type, rang, nombre, rapport, commentaire) values ('$jeu_id', '15', '$rang', '$gagnant', '$rapport', now())";
   							$result = mysql_query($requete);
						}

					  }
		          }
		              ferme_base($db_link);
			}




    	echo "****************************************". "<br>\n";
      /*
      $chaine15 = str_replace("\\","",$chaine15);
      $chaine15 = stripslashes($chaine15);
      $fd = @fopen("scripts/rapports_generes_15.php", "w"); // on ouvre le fichier en  criture
      if ($fd) 
      {
        fwrite($fd,$chaine15); 
        fclose($fd);
      }
      */
    }
    else
    {
      $fp = @fopen("scripts/rapports_generes_15.php","r"); //lecture du fichier
      if ($fp) 
      {
          while (!feof($fp)) 
          {
            $chaine15 .= fgets($fp, 4096); 
          }  
          fclose($fp);
          $chaine15 = str_replace("\\","",$chaine15);
          $chaine15 = stripslashes($chaine15);
        }
      }          
      ?>
      <div id="contenu">
        <h2>Rapports à 15</h2>
        <p>Cette page permet de modifier le fichier contenant les rapports des jeux visible sur la page d'accueil</p>
		<h3>Import des rapports (PRONOSOFT)</h3>
		
		<?php
		include_once('simplehtmldom_1_5/simple_html_dom.php');
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
				
				$rangs = array();
				$gagnants = array();
				$rapports = array();
				$rapport = $listeJeux->find('div[class=rapports]');
				
				$j=0;
				//récupération des rangs de gain
				foreach($rapport as $grille)
				{
					foreach($grille->find('tr') as $rang)
					{
						$rangs[$j]=$rang->find('td',0)->plaintext;
						$gagnants[$j]=$rang->find('td',1)->plaintext;
						$rapports[$j]=$rang->find('td',2)->plaintext;
						$j++;
					}
					//si au moins 4 rangs, on garde la grille car LF15
					if ($j>3) {
						$jeu['titre'] = $titre;
						$jeu['comment'] = $comment;
						$jeu['rangs'] = $rangs;
						$jeu['gagnants'] = $gagnants;
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
				
				$rangs = array();
				$gagnants = array();
				$rapports = array();
				$rapport = $listeJeux->find('div[class=rapports]');
				
				$j=0;
				//récupération des rangs de gain
				foreach($rapport as $grille)
				{
					foreach($grille->find('tr') as $rang)
					{
						$rangs[$j]=$rang->find('td',0)->plaintext;
						$gagnants[$j]=$rang->find('td',1)->plaintext;
						$rapports[$j]=$rang->find('td',2)->plaintext;
						$j++;
					}
					//si au moins 4 rangs, on garde la grille car LF15
					if ($j>3) {
						$jeu['titre'] = $titre;
						$jeu['comment'] = $comment;
						$jeu['rangs'] = $rangs;
						$jeu['gagnants'] = $gagnants;
						$jeu['rapports'] = $rapports;
						//ajout du jeu en cours dans le tableau global des jeux
						$jeux[$i]=$jeu;
						$i++;
					}
					$j=0;
				}
				
				
				
			}
		}
		
		
		// Pour chaque jeu, on l'affiche à l'écran avec possibilité de le choisir
		foreach($jeux as $jeu) 
		{
			$texte="";
			//écriture du titre
			// LCA echo '<h4>' . $jeu['titre'] . ' - ' . $jeu['comment'] .'</h4>';
			// LCA $texte = '<h3>Jeu à 15</h3><p>Rapports officiels - MAJ le ' . date('j M Y à H:i:s') . '</p>';
			//echo "<br>";
			$rangs = $jeu['rangs'];
			$gagnants = $jeu['gagnants'];
			$rapports = $jeu['rapports'];
			$j=0;
			
			//affichage du tableau
			$texte_p = '<table class="jeu" id="tableauresultat">
								<tr>
									<td class="infotitre">Rang</td>
									<td class="infotitre">Nombre de grille gagnantes</td>
									<td class="infotitre">Rapports par grille gagnante</td>
								</tr>';
			// LCA echo $texte_p;
			// LCA $texte = $texte . $texte_p;
						
			//parcours des rangs de la grille en cours
			foreach($rangs as $index)
			{
				$k=$j+1;
				if ($k%2) $texte_p = '<tr class=\'enreg paire\'>';
				else $texte_p = '<tr class=\'enreg impaire\'>';
				
				// LCA echo $texte_p;
				// LCA $texte = $texte . $texte_p;
				
				//affichage du tableau
				// $texte_p =  '<td align=center>' . $rangs[$j] . '</td><td class="eqd">' . $gagnants[$j] . '</td><td class="eqd">' . $rapports[$j] . '</td></tr>';

				$texte_p =  'Rang:[' . $rangs[$j] . ']';
				echo $texte_p;
				$texte = $texte . $texte_p;
				$texte_p =  'Gagnant:[' . $gagnants[$j] . ']';
				echo $texte_p;
				$texte = $texte . $texte_p;
				$texte_p =  'Rapport:[' . trim($rapports[$j]) . '].';
				echo $texte_p;
				$texte = $texte . $texte_p."\n";
				
				// echo $texte_p;
				// $texte = $texte . $texte_p;
				
				$j++;
			}
			// $texte_p =  "</table>";
			// echo $texte_p;
			// $texte = $texte . $texte_p;
			
			
			//formulaire caché pour sélection de la grille en cours à afficher
			echo '<form method="post" action="admin_rapports15.php" class="formconnexion" >';
			echo '<textarea name="rapports15" style="display:none;">'. $texte .'</textarea>';
			echo '<input type="submit" value="Importer" class="bouton" /></form>';
			
			echo "<br><hr color='#2F74C3' size='1'>";
					
		}
		
		
		?>
		
		
		<h3>Modification des rapports</h3>
        <div id="mini">
          <form method="post" action="admin_rapports15.php" class="formconnexion" >
            <textarea cols="80" rows="15" name="rapports15"><?php echo $chaine15; ?></textarea>
            <br />
            <input type="submit" value="Valider" class="bouton" />
          </form>
        </div>
      </div>


      <?php include ("scripts/footer.php"); ?>
    </div>
  </body>

  </html>
