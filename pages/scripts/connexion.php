<?php 
session_start();

header('Content-Type: application/json');

// Script de connexion d'un joueur

// include database and object files


include_once '../config/database.php';
include_once '../config/util.php';
include_once '../objects/joueur.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// Joueur
$joueur = new Joueur($db);

$json["error"]=""; 

if (isset($_GET['login']) && (isset($_GET['password']))) 
{               
      // Initialisation des variables
      $pseudo=$_GET["login"];
      $motpasse=$_GET["password"];
      
      // Protège les caractères qui mettent le bousin (par exemple les cotes) et supprimer les balises html par sécurité
      $joueur->joueur_pseudo=strip_tags (stripslashes ($pseudo));
      $joueur->joueur_mdp=strip_tags (stripslashes ($motpasse));

      // Recherche ce joueur
      $joueur->authentifieJoueur();
      if ($joueur->joueur_id<>"")
      {
            // Lecture des propriétés du joueur
            $joueur_id      = $joueur->joueur_id;
            $nom            = $joueur->joueur_nom;
            $pseudo         = $joueur->joueur_pseudo;
            $mdp            = $joueur->joueur_mdp;
            $mail           = $joueur->joueur_mail;
            $der_cnx        = $joueur->joueur_der_cnx;
            $administrateur = $joueur->joueur_administrateur;

            // Enregistrer en base
            $retour = $joueur->connecteJoueur();
            if ($retour)
            {
                // Création de la session 
                $_SESSION["id_joueur"]        = $joueur_id;
                $_SESSION["nom_joueur"]       = $nom;
                $_SESSION["der_cnx_joueur"]   = $der_cnx;
                $_SESSION["admin"]            = $administrateur;

              // Connexion automatique ?
              if (isset($_GET['auto']))
              {
                  addCookie($joueur_id, $nom);
              }

            }
            else
            {
              $json["error"]=$retour;
            }
      }  
      else
      {
        $json["error"]="Login ou Mot de passe incorrect";
      }
}  
else
{
  $json["error"]="Login ou Mot de passe non renseigné";
}

echo json_encode($json);

?>
