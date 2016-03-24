<?php 
session_start();
ini_set('zlib.output_compression_level', 6);
//ob_start('ob_gzhandler');
?>
  <?php  include("scripts/head.php"); ?>
  
  <body>    
  <?php include ("scripts/debutpage.php"); ?>
  
  <?php              
  // La variable de test si l'authentification fonctionne est à faux par défaut */
  $authentification= false;

  // Dans l'url se trouve éventuellement l'identifiant de l'objet à modifier
  if (isset($_POST['pseudo']) && (isset($_POST['motpasse']))) 
  {               
      // Initialisation des variables
      $pseudo=$_POST["pseudo"];
      $motpasse=$_POST["motpasse"];
      
      // Protège les caractères qui mettent le bousin (par exemple les cotes) et supprimer les balises html par sécurité
      $pseudo=strip_tags (stripslashes ($pseudo));
      $motpasse=strip_tags (stripslashes ($motpasse));
      
      // Ouverture de la base de données
      $db_link = ouvre_base();
      if ($db_link)
      {
        // Requete pour retrouver cette utilisateur
        $joueur=getUtilisateur($pseudo, $motpasse);
         
        if ($joueur)
        {
              // Lecture des propriétés du joueur
              $joueur_id      = $joueur["joueur_id"];
              $nom            = $joueur["nom"];
              $pseudo         = $joueur["pseudo"];
              $mdp            = $joueur["mdp"];
              $mail           = $joueur["mail"];
              $der_cnx        = $joueur["der_cnx"];
              $administrateur = $joueur["administrateur"];
              // Enregistrer en base
              setCnxUtilisateur($joueur_id);
              // Création de la session 
              $_SESSION["id_joueur"]        = $joueur_id;
              $_SESSION["nom_joueur"]       = $nom;
              $_SESSION["der_cnx_joueur"]   = $der_cnx;
              $_SESSION["admin"]            = $administrateur;
              // Connexion automatique ?
              if (isset($_POST['auto']))
              {
                  setcookie($nom_cookie,$joueur_id.$sepCookie.sha1($nom).$sepCookie.sha1($_SERVER['REMOTE_ADDR']), time() + 10*24*3600,'/');
              }
              // L'authentification de l'utilicateur a réussi ! 
              $authentification= true;
        }  
        ferme_base($db_link);
      } 
  }
  ?>
  <script type="text/javascript" src="js/saisieconnexion.js"></script>
            
  <div id="conteneur">  

    <?php 
      
    include ("scripts/header.php"); 
    
    include ("scripts/menu.php"); 
    
    if ($authentification)
    {
/*        include ("scripts/accueil.php"); */
          // Authentification correcte
          header('Location: index.php');
      }
    else
    {
    
     ?>
          <div id="contenu">
          <h2>Connexion de l'utilisateur</h2>
          <div id="mini">
          <form method="post" action="connexion.php" class="formconnexion" name="formconnexion" onSubmit="return verificationSaisie();">
            <table>
            <tr>
            <td>
            <label for="pseudo">Pseudo:</label>
            </td>
            <td>
            <input name="pseudo" id="pseudo" type="text" size="30" maxlength="30" />
            </tr>
            <tr>
            <td>
            <label for="motpasse">Mot de passe:</label>
            </td>
            <td>
            <input name="motpasse" id="motpasse" type="password" size="30" maxlength="12" />
            </td>
            </tr>
            <tr>
            <td>
            </td>
            <td>
             <input type="checkbox" name="auto" /> rester connecté
            </td>
            </tr>
           
            </table>
            <input type="submit" value="Valider" class="boutonvalider" />
          </form>
          </div>
          </div>
          <?php 
    }
    ?>

        
        <?php include ("scripts/footer.php"); ?>
      </div>

  </body>
  
</html>
