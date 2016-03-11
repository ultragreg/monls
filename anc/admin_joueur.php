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
        $joueur_id="";
        $nom="";
        $initiale="";
        $pseudo="";
        $mdp="";
        $mail="";
        $administrateur="";
        $actif="";
        // Dans l'url se trouve éventuellement l'identifiant de l'objet à modifier
        if (isset($_GET['joueur'])) 
        {
          if (is_numeric($_GET['joueur']))
          {
            // Authentification correcte
            $joueur_id=$_GET['joueur'];
            $joueur = getJoueur($joueur_id);
            if ($joueur)
            {
              $nom            = stripslashes($joueur["nom"]);
              $initiale       = stripslashes($joueur["initiale"]);
              $pseudo         = stripslashes($joueur["pseudo"]);
              $mdp            = stripslashes($joueur["mdp"]);
              $mail           = stripslashes($joueur["mail"]);
              $administrateur = stripslashes($joueur["administrateur"]);
              $actif          = stripslashes($joueur["actif"]);
            }
          }
        }
        // Dans le cas contraire, on lit les données depuis le formulaire
        else
        {
            // Récupération des variables
            if (isset($_POST["joueur_id"]))
            {
              $joueur_id=stripslashes($_POST["joueur_id"]);
            }    
            if (isset($_POST["nom"]))
            {
              $nom=stripslashes($_POST["nom"]);
            }
            if (isset($_POST["initiale"]))
            {
              $initiale=stripslashes($_POST["initiale"]);
            }    
            if (isset($_POST["pseudo"]))
            {
              $pseudo=stripslashes($_POST["pseudo"]);
            }    
            if (isset($_POST["mdp"]))
            {
              $mdp=stripslashes($_POST["mdp"]);
            }    
            if (isset($_POST["mail"]))
            {
              $mail=stripslashes($_POST["mail"]);
            }    
            if (isset($_POST["administrateur"]))
            {
              $administrateur=stripslashes($_POST["administrateur"]);
            }     
            if (isset($_POST["actif"]))
            {
              $actif=stripslashes($_POST["actif"]);
            }    
            if (isset($_POST["operation"]))
            {
              $operation=stripslashes($_POST["operation"]);
            }
        }
        if ($operation)
        {
          $joueur_id = setMiseAJourJoueur($joueur_id,$nom, $initiale,$pseudo, $mdp,$mail,$administrateur,$actif,$operation);
          
          // Dans le cas de saisie, on supprime les images contenant les graphiquesq
	        if ($joueur_id!="")
	        {
	            $fichierImage="data1.json";
              // Suppression des graphiques sur le classement 
              if (file_exists($fichierImage))
              { 
                  unlink($fichierImage);
              }  		
	            $fichierImage="data2.json";
              // Suppression des graphiques sur le classement 
              if (file_exists($fichierImage))
              { 
                  unlink($fichierImage);
              }  		
          }
          
        }
        if ($joueur_id)
          $operation="M";
        else
        {
          $operation="C";
          $nom="";
          $initiale="";
          $pseudo="";
          $mdp="";
          $mail="";
          $administrateur="";
          $actif="";
        }
    }
    ?>
    
		<div id="contenu">
			
  		<h2>Administration des joueurs</h2>
		  <p>Il est nécessaire d'être enregistré pour pouvoir pronostiquer sur le LS. Un joueur a donc un nom (qui sera affiché sous 
      le menu dans la zone de bienvenue), un pseudo et un mot de passe (pour se connecter), des initiales qui seront utilisés dans
      le tableau récapitulatif et le classement général, un mail et si il est administrateur ou pas (O/N).</p>

      <a href="admin_joueur.php" class="miniAction">Ajout d'un joueur</a>
      
      <?php  
      if ($joueur_id) 
      {
  		    echo "<h3>Modification d'un joueur</h3>";
      }
      else
      {
  		    echo "<h3>Ajout d'un joueur</h3>";
      } 
      ?>
		  
      <div id="mini">
      <form method="post" action="admin_joueur.php" class="formconnexion" name="form" >
        <input name="operation" type="hidden" value="<?php  echo $operation ?>">
        <input name="joueur_id" type="hidden" value="<?php  echo $joueur_id ?>">
        <table>
        <tr><td><label for="nom">nom:</label></td>
        <td><input name="nom" id="nom" type="text" size="30" maxlength="30" value="<?php  echo $nom ?>"></td>
        </tr>
        <tr><td><label for="initiale">initiales:</label></td>
        <td><input name="initiale" id="initiale" type="text" size="5" maxlength="5" value="<?php  echo $initiale ?>"></td>
        </tr>
        <tr><td><label for="pseudo">pseudo:</label></td>
        <td><input name="pseudo" id="pseudo" type="text" size="20" maxlength="20" value="<?php  echo $pseudo ?>"></td>
        </tr>
        <tr><td><label for="mdp">Mot de passe :</label></td>
        <td><input name="mdp" id="mdp" type="text" size="20" maxlength="20" value="<?php  echo $mdp ?>"></td>
        </tr>
        <tr><td><label for="mail">mail:</label></td>
        <td><input name="mail" id="mail" type="text" size="50" maxlength="50" value="<?php  echo $mail ?>"></td>
        </tr>

        <tr><td><label for="administrateur">administrateur:</label></td>
        <td><input name="administrateur" id="administrateur" type="text" size="1" maxlength="1" value="<?php  echo $administrateur ?>"></td>
        </tr>

        <tr><td><label for="actif">actif:</label></td>
        <td><input name="actif" id="actif" type="text" size="1" maxlength="1" value="<?php  echo $actif ?>"></td>
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
