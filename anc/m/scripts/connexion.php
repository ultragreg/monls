<?php
    session_start();
    include("inclusions.php");
 
       // La variable de test si l'authentification fonctionne est à faux par défaut */
      $authentification= false;
      
      $_SESSION["connecte"]         = "0";
      $_SESSION["id_joueur"]        = "";
      $_SESSION["nom_joueur"]       = "";
      $_SESSION["der_cnx_joueur"]   = "";
      $_SESSION["admin"]            = "";
                                    
      // Dans l'url se trouve éventuellement l'identifiant de l'objet à modifier
      if (isset($_POST['login']) && (isset($_POST['motpasse']))) 
      {
            // Initialisation des variables
            $pseudo=$_POST["login"];
            $motpasse=$_POST["motpasse"];
            
            // Protège les caractères qui mettent le bousin (par exemple les cotes) et supprimer les balises html par sécurité
            $pseudo=strip_tags (stripslashes ($pseudo));
            $motpasse=strip_tags (stripslashes ($motpasse));
            
            // Le mot de passe est hashé md5 */
            //$motDePasseCrypté = md5($motDePasse);
            
            
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
                        $_SESSION["connecte"]         = "1";
                        $_SESSION["id_joueur"]        = $joueur_id;
                        $_SESSION["nom_joueur"]       = $nom;
                        $_SESSION["der_cnx_joueur"]   = formatte_date($der_cnx,1);
                        $_SESSION["admin"]            = $administrateur;
                        // Connexion automatique ?
                        if (isset($_POST['auto']) && $_POST['auto']=='true')
                        {
                              setcookie($nom_cookie,$joueur_id.$sepCookie.sha1($nom).$sepCookie.sha1($_SERVER['REMOTE_ADDR']), time() + 10*24*3600,'/');
                        }
                        // L'authentification de l'utilicateur a réussi ! 
                        $authentification= true;
                  }  
                  ferme_base($db_link);
            }
      } 
      if ($authentification)    echo "1";
      else                      echo "0";
?>      