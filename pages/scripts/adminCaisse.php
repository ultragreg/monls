<?php 

session_start();

header('Content-Type: application/json');

// Script pour voir/modifier une saison

// include database and object files
include_once '../config/database.php';
include_once '../objects/saison.php';
include_once '../objects/caisse.php';


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
    $caisse = new Caisse($db);
   
    // Recherche (GET) -> op=R et id présent
    if ( isset($_GET['op']) && $_GET['op']=='R' && isset($_GET['id']) && strlen($_GET['id'])>0   )
    {
      $caisse->caisse_id = $_GET['id'];
      if ($debug==true)
      {
        echo "Recherche de la caisse {$caisse->caisse_id}<br>\n";
      }
      if ($caisse->chargeCaisse()) {
        $json["resultat"]=true;
        $json["id"]=$caisse->caisse_id;
        $json["libelle"]=$caisse->caisse_libelle;
        $json["debit"]=$caisse->caisse_somme_debit;
        $json["credit"]=$caisse->caisse_somme_credit;
      }
      else {
        $json["resultat"]=false;
        $json["commentaire"]="Saison introuvable";
      }
    }
    // Modification (POST) -> OP=M et ID non nul
    else if ( isset($_POST['op']) && $_POST['op']=='M' && isset($_POST['id']) && strlen($_POST['id'])>0  )
    {
      $caisse->caisse_id = $_POST['id'];
      $caisse->saison_id = $saison->saison_id;
      $caisse->caisse_libelle = $_POST['libelle'];
      $caisse->caisse_somme_debit = $_POST['debit'];
      $caisse->caisse_somme_credit = $_POST['credit'];
      if ($debug==true)
      {
        echo "Modification de la caisse {$caisse->caisse_id}<br>\n";
      }
      $json["resultat"]=$caisse->modifie();
    }
    // Ajout (POST) -> OP=M et peut importe l'id
    else if ( isset($_POST['op']) && $_POST['op']=='M' )
    {
      $caisse->saison_id = $saison->saison_id;
      $caisse->caisse_libelle = $_POST['libelle'];
      $caisse->caisse_somme_debit = $_POST['debit'];
      $caisse->caisse_somme_credit = $_POST['credit'];
      if ($debug==true)
      {
        echo "Ajout de la caisse {$caisse->caisse_id}<br>\n";
      }
      $json["resultat"]=$caisse->ajoute();
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

