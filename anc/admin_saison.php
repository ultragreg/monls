<?php 
session_start();
ini_set('zlib.output_compression_level', 6);
//ob_start('ob_gzhandler');
?>
	<?php  include("scripts/head.php");	?>
	
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
        $operation="";
        $saison_id="";
        $commentaire="";
        $nom="";
        // Dans l'url se trouve éventuellement l'identifiant de l'objet à modifier
        if (isset($_GET['saison'])) 
        {
          if (is_numeric($_GET['saison']))
          {
            // Authentification correcte
            $saison_id=$_GET['saison'];
            $saison = getSaison($saison_id);
            if ($saison)
            {
              $nom= stripslashes($saison["nom"]);
              $commentaire= stripslashes($saison["commentaire"]);
            }
          }
        }
        // Dans le cas contraire, on lit les données depuis le formulaire
        else
        {
            // Récupération des variables
            if (isset($_POST["saison_id"]))
            {
              $saison_id=stripslashes($_POST["saison_id"]);
            }    
            if (isset($_POST["nom"]))
            {
              $nom=stripslashes($_POST["nom"]);
            }
            if (isset($_POST["commentaire"]))
            {
              $commentaire=stripslashes($_POST["commentaire"]);
            }    
            if (isset($_POST["operation"]))
            {
              $operation=stripslashes($_POST["operation"]);
            }
        }
        if ($operation)
        {
          $saison_id = setMiseAJourSaison($saison_id,$nom,$commentaire,$operation);
        }
        if ($saison_id)
          $operation="M";
        else
        {
          $operation="C";
          $commentaire="";
          $nom="";
        }
    }
    ?>
    
		<div id="contenu">
			
  		<h2>Administration des saisons</h2>
		  <p>Le concept de saison permet d'éditer le classement général pour lun championnat. Ainsi tous les jeux sont associés à une 
      saison; à chaque fois que l'on ajoute un jeu, il sera automatiquement associé à la saison la plus récente. </p>
      
      <a href="admin_saison.php" class="miniAction">Nouvelle saison</a>
      <?php  
      if ($saison_id) 
      {
  		    echo "<h3>Modification d'une saison</h3>";
      }
      else
      {
  		    echo "<h3>Ajout d'une saison</h3>";
      } 
          

      ?>
		  
      <div id="mini">
      <form method="post" action="admin_saison.php" class="formconnexion" name="form" >
        <input name="operation" type="hidden" value="<?php  echo $operation ?>">
        <input name="saison_id" type="hidden" value="<?php  echo $saison_id ?>">
        <table>
        <tr><td><label for="nom">nom:</label></td>
        <td><input name="nom" id="nom" type="text" size="30" maxlength="20" value="<?php  echo $nom ?>"></td>
        </tr>
        <tr><td><label for="commentaire">Commentaire:</label></td>
        <td><input name="commentaire" id="commentaire" type="text" size="60" maxlength="200" value="<?php  echo $commentaire ?>"></td>
        </tr>
        </table>
        <input type="submit" value="Valider" class="bouton" />
      </form>
      </div>
    </div>
		
    <?php include ("scripts/footer.php"); ?>
	</div>
	</body>
	
</html>
