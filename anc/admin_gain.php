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
        $gain_id="";
        $ident_joueur="";
        $date="";
        $nom="";
        $somme="";
        // Dans l'url se trouve éventuellement l'identifiant de l'objet à modifier
        if (isset($_GET['gain'])) 
        {
          if (is_numeric($_GET['gain']))
          {
            // Authentification correcte
            $gain_id=$_GET['gain'];
            $gain = getGain($gain_id);
            if ($gain)
            {
                // Lecture des propriétés du joueur
                $gain_id      = stripslashes($gain["gain_id"]);
                $ident_joueur = stripslashes($gain["joueur_id"]);
                $date         = stripslashes($gain["date"]);
                $somme        = stripslashes($gain["somme"]);
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
            // Récupération des variables
            if (isset($_POST["gain_id"]))
            {
              $gain_id=stripslashes($_POST["gain_id"]);
            }
            if (isset($_POST["joueur_id"]))
            {
              $joueur_id=stripslashes($_POST["joueur_id"]);
            }    
            if (isset($_POST["date"]))
            {
              $date=stripslashes($_POST["date"]);
            }    
            if (isset($_POST["somme"]))
            {
              $somme=stripslashes($_POST["somme"]);
            }                
            if (isset($_POST["operation"]))
            {
              $operation=stripslashes($_POST["operation"]);
            }
        }
        if ($operation)
        {
          $somme = strtr($somme, ",", ".");
          $date_jour = substr($date,0,2);
		      $date_mois = substr($date,3,2);
		      $date_annee = substr($date,6,4);
		      $dateOk = $date_annee."/".$date_mois."/".$date_jour;
          $gain_id = setMiseAJourGain($gain_id,$joueur_id,$dateOk,$somme,$operation);
          $ident_joueur=$joueur_id;
        }
        if ($gain_id)
          $operation="M";
        else
        {
          $operation="C";
          $gain_id="";
          $ident_joueur="";
          $date=$date_jour_simple;
          $somme="";
          $nom="";
        }
    }
    ?>
    
		<div id="contenu">
			
  		<h2>Administration des gains</h2>
		  <p>Cette page permet d'ajouter ou modifier un gain pour un joueur inscrit.</p>

      <a href="admin_gain.php" class="miniAction">Ajout d'un gain</a>
      
      <?php        
      if ($ident_joueur) 
      {
  		    echo "<h3>Modification d'un gain</h3>";
      }
      else
      {
  		    echo "<h3>Ajout d'un gain</h3>";
      } 
      
      // On précharge la liste des joueurs !
  	  $listeJoueurs = getListeJoueurs();
      ?>
		  
      <div id="mini">
      <form method="post" action="admin_gain.php" class="formconnexion" >
        <p>
        <input name="operation" type="hidden" value="<?php  echo $operation ?>" />
        <input name="gain_id" type="hidden" value="<?php  echo $gain_id ?>" />
        </p>
        <table>                  
        <tr><td><label for="joueur_id">Joueur : </label></td>
        <td><select id="joueur_id" name="joueur_id">
        <?php
           for($i=0;$i<sizeof($listeJoueurs);$i++)
           {
              $joueur=$listeJoueurs[$i];
              // Lecture des propriétés du joueur
              $joueur_id = $joueur["joueur_id"];
              $nom       = $joueur["nom"];
              
              echo "<option value=\"".$joueur_id."\"";
              if ($joueur_id==$ident_joueur)
                  echo ' selected="selected" >';
              else
                  echo ">";
              echo $nom;
              echo "</option>";
            }
        ?>
        </select></td>
        </tr>
        <tr><td><label for="date">date:</label></td>
        <td><input id="date" name="date" type="text" value="<?php echo $date ?>" onfocus="this.select();lcs(this)" onclick="event.cancelBubble=true;this.select();lcs(this)" /></td>
        </tr>
        <tr><td><label for="somme">somme gagnée:</label></td>
        <td><input id="somme" name="somme" type="text" size="10" maxlength="10" value="<?php  echo $somme ?>" /></td>
        </tr>
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
