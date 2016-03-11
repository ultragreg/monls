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
        $operation="";
        $appel_id="";
        $libelle="";
        $date="";
        // Dans l'url se trouve éventuellement l'identifiant de l'objet à modifier
        if (isset($_GET['appel'])) 
        {
          if (is_numeric($_GET['appel']))
          {
            // Authentification correcte
            $appel_id=$_GET['appel'];
            $appel = getAppel($appel_id);
            if ($appel)
            {
                // Lecture des propriétés du appel de fond
                $appel_id      = stripslashes($appel["appel_id"]);
                $libelle       = stripslashes($appel["libelle"]);
                $date          = stripslashes($appel["date"]);
                $date_annee = substr($date,0,4);
		            $date_mois = substr($date,5,2);
		            $date_jour = substr($date,8,2);
		            $date = $date_jour."/".$date_mois."/".$date_annee;
		            if ($date="0000-00-00")
		                $date=$date_jour_simple;
            }
          }
        }
        // Dans le cas contraire, on lit les données depuis le formulaire
        else
        {
            $appel_id="";
            $libelle="";
            $date="";
            $operation="";
            $paiements="";

            // Récupération des variables
            if (isset($_POST["appel_id"]))
            {
              $appel_id=stripslashes($_POST["appel_id"]);
            }
            if (isset($_POST["libelle"]))
            {
              $libelle=stripslashes($_POST["libelle"]);
            }    
            if (isset($_POST["date"]))
            {
              $date=stripslashes($_POST["date"]);
            }  
            if (isset($_POST["operation"]))
            {
              $operation=stripslashes($_POST["operation"]);
            }  
            if (isset($_POST["paiements"]))
            {
              $paiements=$_POST["paiements"];
            }
        }
        if ($operation)
        {
          $date_jour = substr($date,0,2);
		      $date_mois = substr($date,3,2);
		      $date_annee = substr($date,6,4);
		      $dateOk = $date_annee."/".$date_mois."/".$date_jour;
		      
          $appel_id = setMiseAJourAppel($appel_id,$libelle,$dateOk,$operation);
          $appel_id = setMiseAJourAppelJoueurs ($appel_id,$paiements);
        }
        if ($appel_id)
          $operation="M";
        else
        {
          $operation="C";
          $appel_id="";
          $libelle="";
          $date=$date_jour_simple;
        }
    }
    ?>
    
		<div id="contenu">
			
  		<h2>Administration des appels de fond</h2>
		  <p>Cette page permet d'ajouter ou modifier un appel de fond.</p>

      <a href="admin_appel.php" class="miniAction">Ajout d'un appel de fond</a>
      
      <?php        
      if ($appel_id) 
      {
  		    echo "<h3>Modification d'un appel de fond</h3>";
      }
      else
      {
  		    echo "<h3>Ajout d'un appel de fond</h3>";
      } 

      ?>
		  
      <div id="miniff">
      <form method="post" action="admin_appel.php" class="formconnexion" >
        <p>
        <input name="operation" type="hidden" value="<?php  echo $operation ?>" />
        <input name="appel_id" type="hidden" value="<?php  echo $appel_id ?>" />
        </p>
        
        <table>                  
        <tr><td><label for="libelle">libelle : </label></td> 
        <td><input id="libelle" name="libelle" type="text" size="50" maxlength="50" value="<?php  echo $libelle ?>" /></td>
        </tr>
        <tr><td><label for="date">date:</label></td>
        <td><input id="date" name="date" type="text" value="<?php echo $date ?>" onfocus="this.select();lcs(this)" onclick="event.cancelBubble=true;this.select();lcs(this)" /></td>
        </tr>
        </table>

         <table class="jeu">
         <tr>
         <td class="infotitre">Nom</td>
         <td class="infotitre">Paiement</td>
         </tr>
         <?php 
         $appelJoueurs = getListeAppelJoueurs($appel_id);
         $listeJoueurs = getListeJoueurs();
         
         
         for($i=0;$i<sizeof($listeJoueurs);$i++)
         {
            $joueur=$listeJoueurs[$i];
            // Lecture des propriétés du joueur
            $joueur_id      = stripslashes($joueur["joueur_id"]);
            $nom            = stripslashes($joueur["nom"]);
            $pseudo         = stripslashes($joueur["pseudo"]);
            $paiement       = "";
            
            if ($i%2 == 1) 
                echo "<tr class='enreg impaire'>";
            else
                echo "<tr class='enreg'>";

            $j=$i+1; 
            echo "<td width=\"200\">".$nom."</td>\n";
            echo "<td width=\"30\"><input type=\"Checkbox\" name=\"paiements[]\" value=\"".$joueur_id."\" ";
            if (isAppelJoueurs($joueur_id, $appelJoueurs)) 
                echo " checked "; 
            echo "></td>\n";
            echo "</tr>\n";
        }
        ?>
        </table>

        
        <p>
        <input type="submit" value="Valider" class="bouton" />
        </p>
      </form>
      </div>
    </div>
		
    <?php include ("scripts/footer.php"); ?>
	</div>
	</body>
	
</html>
