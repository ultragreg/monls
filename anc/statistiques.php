<?php 
session_start();
ini_set('zlib.output_compression_level', 6);
//ob_start('ob_gzhandler');
?>
	<?php  include("scripts/head.php");	?>

	<body>    
  <?php include ("scripts/debutpage.php"); ?>
  <?php $debut=getmicrotime(); ?>

	<div id="conteneur">		

    <?php 
    include ("scripts/header.php");
		
    include ("scripts/menu.php"); 
		
    // On affiche cette page uniquement si on est connecté 
    if (!isset($_SESSION['id_joueur']) || $_SESSION['admin']!='O') 
    {
      // Authentification correcte
      header('Location: index.php');
    }
    ?>

    <div id="contenu">
      <?php 
      $db_link = ouvre_base();
      if ($db_link)
      {
		 $saison_id            = getIDSaisonCourante();
		 
		  echo "<table><tr>";
		  
		  echo "<td width=350  align=center>";
		 echo "<h2 id='top'>Indices de Gain des Grilles de la Saison</h2>";
		 	 
		 echo "<table class='jeu'>";
		 echo "<tr><td></td><td class='infotitre' colspan=2>&nbsp;</td></tr>";
		 echo "<tr><td class='infotitre'>Journée</td>";
         echo "<td class='infotitre'>Jeu 7</td>";
         echo "<td class='infotitre'>Jeu 15</td></tr>";
         
         $listes = getIndiceJeuxSaison($saison_id);
         for($i=0;$i<sizeof($listes);$i++)
         {
            $element=$listes[$i];
            // Lecture des propriétés du joueur
            $titre        = stripslashes($element["titre"]);
            $IndiceGain7      = stripslashes($element["IndiceGain7"]);
            $IndiceGain15     = stripslashes($element["IndiceGain15"]);

            if ($i%2 == 1) 
                echo "<tr class='enreg impaire'>";
            else
                echo "<tr class='enreg'>";

            $j=$i+1;
            echo "<td width=\"100\" align=center>".$titre."</td>\n";   
            echo "<td width=\"100\" align=center>".$IndiceGain7."</td>\n";
            echo "<td width=\"100\" align=center>".$IndiceGain15."</td>\n";
            echo "</tr>\n";
         }
		 $listes = getMoyenneIndiceJeuxSaison($saison_id);
         for($i=0;$i<sizeof($listes);$i++)
         {
            $element=$listes[$i];
            // Lecture des propriétés du joueur
            //$titre        = stripslashes($element["titre"]);
            $IndiceGain7      = stripslashes($element["moy7"]);
            $IndiceGain15     = stripslashes($element["moy15"]);

            if ($i%2 == 1) 
                echo "<tr class='enreg impaire'>";
            else
                echo "<tr class='enreg'>";

            $j=$i+1;
            echo "<td width=\"100\" align=center><b>Moyenne</b></td>\n";   
            echo "<td width=\"100\" align=center><b>".$IndiceGain7."</b></td>\n";
            echo "<td width=\"100\" align=center><b>".$IndiceGain15."</b></td>\n";
            echo "</tr>\n";
         }
         echo "</table>";
		 echo "</td>";
		 echo "<td width=10  align=center></td>";
		 echo "<td width=410  align=center>";
		 
		 echo "<h2 id='top'>Répartition Couleur Pronostics/Journée</h2>";
		 echo "<table class='jeu'><tr><td></td><td class='infotitre' colspan=3>Jeu 7</td><td></td><td class='infotitre' colspan=3>Jeu 15</td></tr>";
         echo "<tr><td class='infotitre'>Journée</td>";
         echo "<td class='infotitre' bgcolor='#70a745'>Vert</td>";
         echo "<td class='infotitre' bgcolor='#e4b704'>Jaune</td>";
		 echo "<td class='infotitre' bgcolor='#cb281c'>Rouge</td>";
		 echo "<td bgcolor='black'></td>";
		 echo "<td class='infotitre' bgcolor='#70a745'>Vert</td>";
         echo "<td class='infotitre' bgcolor='#e4b704'>Jaune</td>";
		 echo "<td class='infotitre' bgcolor='#cb281c'>Rouge</td></tr>";
         
         $listes = getCouleurSaison($saison_id);
         for($i=0;$i<sizeof($listes);$i++)
         {
            $element=$listes[$i];
            // Lecture des propriétés du joueur
            $titre        = stripslashes($element["titre"]);
            $vert      = stripslashes($element["vert"]);
            $jaune     = stripslashes($element["jaune"]);
			$rouge     = stripslashes($element["rouge"]);
			$vert7      = stripslashes($element["vert7"]);
            $jaune7     = stripslashes($element["jaune7"]);
			$rouge7     = stripslashes($element["rouge7"]);

            if ($i%2 == 1) 
                echo "<tr class='enreg impaire'>";
            else
                echo "<tr class='enreg'>";

            $j=$i+1;
            echo "<td width=\"80\" align=center>".$titre."</td>\n";   
            echo "<td width=\"50\" align=center>".$vert7."</td>\n";
            echo "<td width=\"50\" align=center>".$jaune7."</td>\n";
			echo "<td width=\"50\" align=center>".$rouge7."</td>\n";
			echo "<td width=\"5\" bgcolor='black'></td>\n";
			echo "<td width=\"50\" align=center>".$vert."</td>\n";
            echo "<td width=\"50\" align=center>".$jaune."</td>\n";
			echo "<td width=\"50\" align=center>".$rouge."</td>\n";
            echo "</tr>\n";
         }
		 $listes = getMoyenneCouleurSaison($saison_id);
         for($i=0;$i<sizeof($listes);$i++)
         {
            $element=$listes[$i];
            // Lecture des propriétés du joueur
            $vert      = stripslashes($element["vert"]);
            $jaune     = stripslashes($element["jaune"]);
			$rouge     = stripslashes($element["rouge"]);
			$vert7      = stripslashes($element["vert7"]);
            $jaune7     = stripslashes($element["jaune7"]);
			$rouge7     = stripslashes($element["rouge7"]);

            if ($i%2 == 1) 
                echo "<tr class='enreg impaire'>";
            else
                echo "<tr class='enreg'>";

            $j=$i+1;
            echo "<td width=\"80\" align=center><b>Moyenne</b></td>\n";   
            echo "<td width=\"50\" align=center><b>".$vert7."</b></td>\n";
            echo "<td width=\"50\" align=center><b>".$jaune7."</b></td>\n";
			echo "<td width=\"50\" align=center><b>".$rouge7."</b></td>\n";
			echo "<td width=\"5\" bgcolor='black'></td>\n";
			echo "<td width=\"50\" align=center><b>".$vert."</b></td>\n";
            echo "<td width=\"50\" align=center><b>".$jaune."</b></td>\n";
			echo "<td width=\"50\" align=center><b>".$rouge."</b></td>\n";
            echo "</tr>\n";
         }
         echo "</table>";
		 
		  echo "</td>";
		 echo "<td width=10  align=center></td>";
		 echo "<td width=410  align=center>";
		 
		 echo "<h2 id='top'>Répartition 1N2/Journée</h2>";
		 echo "<table class='jeu'><tr><td></td><td class='infotitre' colspan=3>Jeu 7</td><td></td><td class='infotitre' colspan=3>Jeu 15</td></tr>";
         echo "<tr><td class='infotitre'>Journée</td>";
         echo "<td class='infotitre' >1</td>";
         echo "<td class='infotitre' >N</td>";
		 echo "<td class='infotitre' >2</td>";
		 echo "<td bgcolor='black'></td>";
		 echo "<td class='infotitre' >1</td>";
         echo "<td class='infotitre' >N</td>";
		 echo "<td class='infotitre' >2</td></tr>";
         
         $listes = getResultatSaison($saison_id);
		 $compteur=array();
         for($i=0;$i<sizeof($listes);$i++)
         {
            $element=$listes[$i];
			
			
			$cpt=array();
			$cpt["17"]=0;
			$cpt["N7"]=0;
			$cpt["27"]=0;
			$cpt["1"]=0;
			$cpt["N"]=0;
			$cpt["2"]=0;
			for($j=1;$j<16;$j++) {
				$res=getResultatNumero($element,$j);
				if ($res=="1") {
					$cpt["1"] = $cpt["1"] + 1 ;
					if ($j<8) {
						$cpt["17"] = $cpt["17"] + 1;
					}
				}
				if ($res=="N") {
					$cpt["N"] = $cpt["N"]+1;
					if ($j<8) {
						$cpt["N7"] = $cpt["N7"] + 1;
					}
				}
				if ($res=="2") {
					$cpt["2"] = $cpt["2"] + 1;
					if ($j<8) {
						$cpt["27"] = $cpt["27"] + 1;
					}
				}
				if ($res=="1N2") {
					$cpt["1"] = $cpt["1"] + 1;
					//$cpt["N"] = $cpt["N"] + 1;
					//$cpt["2"] = $cpt["2"] + 1;
					if ($j<8) {
						$cpt["17"] = $cpt["17"] + 1;
						//$cpt["N7"] = $cpt["N7"] + 1;
						//$cpt["27"] = $cpt["27"] + 1;
					}
				}
			}
			$compteur[$i]=$cpt;
			
            // Lecture des propriétés du joueur
            $titre        = stripslashes($element["titre"]);

            if ($i%2 == 1) 
                echo "<tr class='enreg impaire'>";
            else
                echo "<tr class='enreg'>";

            $j=$i+1;
            echo "<td width=\"80\" align=center>".$titre."</td>\n";   
            echo "<td width=\"50\" align=center>".$cpt["17"]."</td>\n";
            echo "<td width=\"50\" align=center>".$cpt["N7"]."</td>\n";
			echo "<td width=\"50\" align=center>".$cpt["27"]."</td>\n";
			echo "<td width=\"5\" bgcolor='black'></td>\n";
			echo "<td width=\"50\" align=center>".$cpt["1"]."</td>\n";
            echo "<td width=\"50\" align=center>".$cpt["N"]."</td>\n";
			echo "<td width=\"50\" align=center>".$cpt["2"]."</td>\n";
            echo "</tr>\n";
         }
		 
		 $cpt17=0;
		 $cptN7=0;
		 $cpt27=0;
		 $cpt1=0;
		 $cptN=0;
		 $cpt2=0;
		 
         for($i=0;$i<sizeof($compteur);$i++)
         {
            $cpt=$compteur[$i];
            // Lecture des propriétés du joueur
            $cpt17      = $cpt17 + $cpt["17"];
            $cptN7     = $cptN7 + $cpt["N7"];
			$cpt27     = $cpt27 + $cpt["27"];
			$cpt1      = $cpt1 + $cpt["1"];
            $cptN     = $cptN + $cpt["N"];
			$cpt2     = $cpt2 + $cpt["2"];
			
         }
		 echo "<tr class='enreg'>";
		echo "<td width=\"80\" align=center><b>Moyenne</b></td>\n";   
		echo "<td width=\"50\" align=center><b>".number_format($cpt17/sizeof($compteur),1)."</b></td>\n";
		echo "<td width=\"50\" align=center><b>".number_format($cptN7/sizeof($compteur),1)."</b></td>\n";
		echo "<td width=\"50\" align=center><b>".number_format($cpt27/sizeof($compteur),1)."</b></td>\n";
		echo "<td width=\"5\" bgcolor='black'></td>\n";
		echo "<td width=\"50\" align=center><b>".number_format($cpt1/sizeof($compteur),1)."</b></td>\n";
		echo "<td width=\"50\" align=center><b>".number_format($cptN/sizeof($compteur),1)."</b></td>\n";
		echo "<td width=\"50\" align=center><b>".number_format($cpt2/sizeof($compteur),1)."</b></td>\n";
            echo "</tr>\n";
         echo "</table>";
		 
		 
		 
		 
		 echo "</td>";
		 echo "</tr></table>";
		 
		 
		 
		 echo "<h2 id='top'>Prise de risque - Saison en cours</h2>";
		 echo "<table><tr>";
		 echo "<td width=405  align=center>";
		 echo "<h3 id='top'>Jeu à 7 (hors flash)</h3>";
         echo "<table class='jeu'><tr>";
         echo "<td class='infotitre'>Nom du Joueur</td>";
         echo "<td class='infotitre'>Indice Moyen</td>";
         echo "<td class='infotitre'>Nombre de jeu</td></tr>";
         
         $listes = getStatIndiceGain7Joueurs($saison_id);
         for($i=0;$i<sizeof($listes);$i++)
         {
            $element=$listes[$i];
            // Lecture des propriétés du joueur
            $nom        = stripslashes($element["nom"]);
            $moyenne      = stripslashes($element["moyenne"]);
            $nbindice     = stripslashes($element["nbindice"]);

            if ($i%2 == 1) 
                echo "<tr class='enreg impaire'>";
            else
                echo "<tr class='enreg'>";

            $j=$i+1;
            echo "<td width=\"200\">".$nom."</td>\n";   
            echo "<td width=\"100\" align=center>".$moyenne."</td>\n";
            echo "<td width=\"100\" align=center>".$nbindice."</td>\n";
            echo "</tr>\n";
         }
         echo "</table>";
		 echo "</td>";
		 
		 
		 echo "<td width=405  align=center>";
		 echo "<h3 id='top'>Jeu à 15</h3>";
         echo "<table class='jeu'><tr>";
         echo "<td class='infotitre'>Nom du Joueur</td>";
         echo "<td class='infotitre'>Indice Moyen</td>";
         echo "<td class='infotitre'>Nombre de jeu</td></tr>";
         
         $listes = getStatIndiceGain15Joueurs($saison_id);
         for($i=0;$i<sizeof($listes);$i++)
         {
            $element=$listes[$i];
            // Lecture des propriétés du joueur
            $nom        = stripslashes($element["nom"]);
            $moyenne      = stripslashes($element["moyenne"]);
            $nbindice     = stripslashes($element["nbindice"]);

            if ($i%2 == 1) 
                echo "<tr class='enreg impaire'>";
            else
                echo "<tr class='enreg'>";

            $j=$i+1;
            echo "<td width=\"200\">".$nom."</td>\n";   
            echo "<td width=\"100\" align=center>".$moyenne."</td>\n";
            echo "<td width=\"100\" align=center>".$nbindice."</td>\n";
            echo "</tr>\n";
         }
         echo "</table>";
		 echo "</td>";
		 
		 echo "<td width=405  align=center>";
		 echo "<h3 id='top'>Tout Jeu confondu</h3>";
         echo "<table class='jeu'><tr>";
         echo "<td class='infotitre'>Nom du Joueur</td>";
         echo "<td class='infotitre'>Indice Moyen</td>";
         echo "<td class='infotitre'>Nombre de jeu</td></tr>";
         
         $listes = getStatIndiceGainJoueurs($saison_id);
         for($i=0;$i<sizeof($listes);$i++)
         {
            $element=$listes[$i];
            // Lecture des propriétés du joueur
            $nom        = stripslashes($element["nom"]);
            $moyenne      = stripslashes($element["moyenne"]);
            $nbindice     = stripslashes($element["nbindice"]);

            if ($i%2 == 1) 
                echo "<tr class='enreg impaire'>";
            else
                echo "<tr class='enreg'>";

            $j=$i+1;
            echo "<td width=\"200\">".$nom."</td>\n";   
            echo "<td width=\"100\" align=center>".$moyenne."</td>\n";
            echo "<td width=\"100\" align=center>".$nbindice."</td>\n";
            echo "</tr>\n";
         }
         echo "</table>";
		 echo "</td>";
		 echo "</tr></table>";
		  
		  
         echo "<h2 id='top'>10 meilleurs gains toutes saisons confondues</h2>";
         echo "<table class='jeu'><tr>";
         echo "<td class='infotitre'>Nom du Joueur</td>";
         echo "<td class='infotitre'>Somme</td>";
         echo "<td class='infotitre'>Saison</td></tr>";
         
         $listes = getStatMeilleursGains();
         for($i=0;$i<sizeof($listes);$i++)
         {
            $element=$listes[$i];
            // Lecture des propriétés du joueur
            $jnom        = stripslashes($element["jnom"]);
            $total        = stripslashes($element["total"]);
            $snom         = stripslashes($element["snom"]);

            if ($i%2 == 1) 
                echo "<tr class='enreg impaire'>";
            else
                echo "<tr class='enreg'>";

            $j=$i+1;
            echo "<td width=\"300\">".$jnom."</td>\n";   
            echo "<td width=\"100\">".$total."</td>\n";
            echo "<td width=\"300\">".$snom."</td>\n";
            echo "</tr>\n";
         }
         echo "</table>";


         echo "<h2 id='top'>Somme des Gains / saison</h2>";
         echo "<table class='jeu'><tr>";
         echo "<td class='infotitre'>Saison</td>";
         echo "<td class='infotitre'>Somme</td>";
         echo "<td class='infotitre'>Nb de Gains</td>";
         echo "<td class='infotitre'>Nb de Jeu</td>";
         echo "<td class='infotitre'>Moyenne gain/jeu</td></tr>";
         
         $listes = getStatGainsSaison();
         for($i=0;$i<sizeof($listes);$i++)
         {
            $element=$listes[$i];
            // Lecture des propriétés du joueur
            $snom        = stripslashes($element["snom"]);
            $total       = stripslashes($element["total"]);
            $nbgain      = stripslashes($element["nbgain"]);
            $nbmatch     = stripslashes($element["nbmatch"]);
            $moyenne     = "";
            if ($nbmatch)  
            {
              $moyenne=$nbgain/$nbmatch*100;
              $moyenne=round($moyenne,1);
            }

            if ($i%2 == 1) 
                echo "<tr class='enreg impaire'>";
            else
                echo "<tr class='enreg'>";

            $j=$i+1;
            echo "<td width=\"300\">".$snom."</td>\n";   
            echo "<td width=\"100\">".$total."</td>\n";
            echo "<td width=\"100\">".$nbgain."</td>\n";
            echo "<td width=\"100\">".$nbmatch."</td>\n";
            echo "<td width=\"100\">".round($moyenne,1)." %</td>\n";
            
            echo "</tr>\n";
         }
         echo "</table>";


         echo "<h2 id='top'>Somme des gains et Nombre de gain par joueur toutes saisons confondues</h2>";
         echo "<table class='jeu'><tr>";
         echo "<td class='infotitre'>Nom du Joueur</td>";
         echo "<td class='infotitre'>Somme</td>";
         echo "<td class='infotitre'>Nombre de gain</td></tr>";
         
         $listes = getStatGainsJoueurs();
         for($i=0;$i<sizeof($listes);$i++)
         {
            $element=$listes[$i];
            // Lecture des propriétés du joueur
            $nom        = stripslashes($element["nom"]);
            $total      = stripslashes($element["total"]);
            $nbgain     = stripslashes($element["nbgain"]);

            if ($i%2 == 1) 
                echo "<tr class='enreg impaire'>";
            else
                echo "<tr class='enreg'>";

            $j=$i+1;
            echo "<td width=\"500\">".$nom."</td>\n";   
            echo "<td width=\"100\">".$total."</td>\n";
            echo "<td width=\"100\">".$nbgain."</td>\n";
            echo "</tr>\n";
         }
         echo "</table>";



    }
    echo "</div>";
    include ("scripts/footer.php"); 
     ?>
  <?php 
  $fin=getmicrotime();
  echo "<p style='color:gray;font-size=6px'>Temps de génération : ".($fin-$debut)."</p>"; 
  ?>
     
	</div>
	</body>
	
</html>
