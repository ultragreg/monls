<?php 

session_start();

header('Content-Type: application/json');

// Script pour voir/modifier une saison

// include database and object files
include_once '../config/database.php';
include_once '../objects/saison.php';


if (isset($_SESSION['id_joueur'])) {

    $debug=false;
    if (isset($_GET['debug']))
    {
      // Mode debug
      $debug=true;
    }

    // instantie la base
    $database = new Database();
    $db = $database->getConnection();

    // Nouvel objet 
    $saison = new Saison($db);
   
    // Recherche (GET) -> op=R et id présent
    if ( isset($_GET['op']) && $_GET['op']=='R' && isset($_GET['id']) && strlen($_GET['id'])>0   )
    {
      $saison->saison_id = $_GET['id'];
      if ($debug==true)
      {
        echo "Recherche de la saison {$saison->saison_id}<br>\n";
      }
      if ($saison->chargeSaison()) {
        $json["resultat"]=true;
        $json["id"]=$saison->saison_id;
        $json["nom"]=$saison->saison_nom;
        $json["commentaire"]=$saison->saison_commentaire;
      }
      else {
        $json["resultat"]=false;
        $json["commentaire"]="Saison introuvable";
      }
    }
    // Modification (POST) -> OP=M et ID non nul
    else if ( isset($_POST['op']) && $_POST['op']=='M' && isset($_POST['id']) && strlen($_POST['id'])>0  )
    {
      $saison->saison_id = $_POST['id'];
      $saison->saison_nom = $_POST['nom'];
      $saison->saison_commentaire = $_POST['commentaire'];
      if ($debug==true)
      {
        echo "Modification de la saison {$saison->saison_id}<br>\n";
      }
      $json["resultat"]=$saison->modifie();
    }
    // Ajout (POST) -> OP=M et peut importe l'id
    else if ( isset($_POST['op']) && $_POST['op']=='M' )
    {
      $saison->saison_nom = $_POST['nom'];
      $saison->saison_commentaire = $_POST['commentaire'];
      if ($debug==true)
      {
        echo "Ajout de la saison {$saison->saison_id}<br>\n";
      }
      $json["resultat"]=$saison->ajoute();
    }
    // Autre cas -> Erreur
    else {
      $json["resultat"]=false;
      $json["commentaire"]="Parametres absents";      
    }

} 
// Erreur si pas de session
else
{
  $json["resultat"]=false;
  $json["commentaire"]="Pas connecté !";
}

echo json_encode($json);
?>

