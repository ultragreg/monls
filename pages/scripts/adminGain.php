<?php 

session_start();

header('Content-Type: application/json');

// Script pour voir/modifier une saison

// include database and object files
include_once '../config/database.php';
include_once '../objects/saison.php';
include_once '../objects/gain.php';


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

    // Saison courante
    $saison = new Saison($db);
    $saison = $saison->chargeSaisonCourante();


    // Nouvel objet 
    $gain = new Gain($db);
   
    // Recherche (GET) -> op=R et id présent
    if ( isset($_GET['op']) && $_GET['op']=='R' && isset($_GET['id']) && strlen($_GET['id'])>0   )
    {
      $gain->gain_id = $_GET['id'];
      if ($debug==true)
      {
        echo "Recherche d'un gain {$gain->gain_id}<br>\n";
      }
      if ($gain->chargeGain()) {
        $json["resultat"]=true;
        $json["id"]=$gain->gain_id;
        $json["jid"]=$gain->joueur_id;
        $json["somme"]=$gain->gain_somme;
      }
      else {
        $json["resultat"]=false;
        $json["commentaire"]="Gain introuvable";
      }
    }
    // Modification (POST) -> OP=M et ID non nul
    else if ( isset($_POST['op']) && $_POST['op']=='M' && isset($_POST['id']) && strlen($_POST['id'])>0  && isset($_POST['idJoueur']) && strlen($_POST['idJoueur'])>0  )
    {
      $gain->gain_id = $_POST['id'];
      $gain->joueur_id = $_POST['idJoueur'];
      $gain->gain_somme = $_POST['sommeGain'];
      if ($debug==true)
      {
        echo "Modification du gain {$gain->gain_id}<br>\n";
      }
      $json["resultat"]=$gain->modifie();
    }
    // Ajout (POST) -> OP=M et peut importe l'id
    else if ( isset($_POST['op']) && $_POST['op']=='M' && isset($_POST['idJoueur']) && strlen($_POST['idJoueur'])>0 )
    {
      $gain->joueur_id = $_POST['idJoueur'];
      $gain->saison_id = $saison->saison_id;
      $gain->gain_somme = $_POST['sommeGain'];
      if ($debug==true)
      {
        echo "Ajout d'un gain pour le joueur {$gain->joueur_id}<br>\n";
      }
      $json["resultat"]=$gain->ajoute();
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

