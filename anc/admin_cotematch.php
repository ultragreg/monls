<?php
session_start();
ini_set('zlib.output_compression_level', 6);
//ob_start('ob_gzhandler');
?>
	<?php include("scripts/head.php");	?>
	
	<body>    
  <?php include ("scripts/debutpage.php"); ?>
            
	<div id="conteneur">	

    <?php include ("scripts/header.php"); ?>
    
    <?php include ("scripts/menu.php"); ?>
    
    <?php
    // On affiche cette page uniquement si on est connecté et administrateur
    if (!isset($_SESSION['id_joueur']) || $_SESSION['admin']!='O') 
    {
      // Authentification correcte
      header('Location: index.php');
    }
    
    // Ouverture de la base de données
    $db_link = ouvre_base();
    if ($db_link)
    {
        // Dans l'url se trouve éventuellement l'identifiant de l'objet à modifier
        if (isset($_GET['RAFRAICHIR'])) 
        {
            getCoteMatchSiteFDJ();
        }
    }
    ?>
    
		<div id="contenu">
			
  		<h2>Administration des cotés matchs</h2>
		  <p>Les cotés matchs sont lus sur le site de la Française des jeux et stockés en base par l'action 'Rafraichir'.</p>

      <a href="admin_cotematch.php?RAFRAICHIR" class="miniAction">Rafraichir</a>
      
      <h3>Liste des cotés matchs actuellement stockés en base de données</h3>
      
       <table class="jeu">
       <td class="infotitre">Id.</td>
       <td class="infotitre">Equipe D.</td>
       <td class="infotitre">Equipe V.</td>
       <td class="infotitre">Cote 1</td>
       <td class="infotitre">Cote 2</td>
       <td class="infotitre">Cote 3</td>
       <td class="infotitre">Cote 4</td>
       </tr>
       <?php 
       $cotematchs = getListeCoteMatchs();
       for($i=0;$i<sizeof($cotematchs);$i++)
       {
          $cotematch=$cotematchs[$i];
          // Lecture des propriétés du joueur
          $cote_id    = stripslashes($cotematch["cote_id"]);
          $equiped    = stripslashes($cotematch["equiped"]);
          $equipev    = stripslashes($cotematch["equipev"]);
          $cote1      = stripslashes($cotematch["cote1"]);
          $cote2      = stripslashes($cotematch["cote2"]);
          $cote3      = stripslashes($cotematch["cote3"]);
          $cote4      = stripslashes($cotematch["cote4"]);
      
          if ($i%2 == 1) 
              echo "<tr class='enreg impaire'>";
          else
              echo "<tr class='enreg'>";
      
          $j=$i+1;
          echo "<td width=\"40\">".$cote_id."</td>\n";   
          echo "<td width=\"100\">".$equiped."</td>\n";
          echo "<td width=\"100\">".$equipev."</td>\n";
          echo "<td width=\"100\">".$cote1."</td>\n";
          echo "<td width=\"100\">".$cote2."</td>\n";
          echo "<td width=\"100\">".$cote3."</td>\n";
          echo "<td width=\"100\">".$cote4."</td>\n";
          echo "</tr>\n";
       }
       ?>
       </table>

    </div>
		
   <?php include ("scripts/footer.php"); ?>
	</div>
	</body>
	
</html>
