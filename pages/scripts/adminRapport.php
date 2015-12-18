<?php 

session_start();

header('Content-Type: application/json');

// Script pour voir/modifier une saison

// include database and object files
include_once '../config/database.php';
include_once '../objects/rapport.php';
include_once '../objects/saison.php';
include_once '../objects/jeu.php';


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
    $rapport = new Rapport($db);
   
    // Recherche (GET) -> op=R et id présent
    if ( isset($_GET['op']) && $_GET['op']=='R' && isset($_GET['id']) && strlen($_GET['id'])>0   )
    {
      $rapport->jeu_id = $_GET['id'];
      $rapport->type = "";
      if ($debug==true)
      {
        echo "Recherche des rapports pour le jeu id {$rapport->jeu_id}<br>\n";
      }
      $stmtRapports = $rapport->litRapports();
      $listeRapports = $stmtRapports->fetchAll(PDO::FETCH_ASSOC);
      $rows = count($listeRapports);
      if ($debug==true)
      {
        echo "Nombre de lignes :  {$rows}<br>\n";
      }
      if ($listeRapports>0) {
        $json["resultat"]=true;
        $json["rapport"]=$listeRapports;
      }
      else {
        $json["resultat"]=false;
        $json["commentaire"]="Jeu introuvable";
      }
    }
    // Remplacement (POST) -> OP=M et chaine contenant le message json
    else if ( isset($_POST['op']) && $_POST['op']=='M' && 
              isset($_POST['chaine']) && strlen($_POST['chaine'])>0 &&
              isset($_POST['type']) && strlen($_POST['type'])>0 )
    {    
      // Saison courante
      $saison = new Saison($db);
      $saison = $saison->chargeSaisonCourante();
 
      // Dernier Jeu
      $jeu = new Jeu($db);
      $jeu->saison_id = $saison->saison_id;
      $jeu->chargeDernierJeu();

      $rapport->jeu_id = $jeu->jeu_id;
      $rapport->type = $_POST['type'];
      $rapport->commentaire = date("Y-m-d");

      $retourSuppression = $rapport->efface();
      if ($retourSuppression)
      {
          $erreur=false;
          $listeRapportsPost= json_decode($_POST['chaine']);
          foreach ( $listeRapportsPost as $rapportPost) {
              $rapport->rang = $rapportPost->rang;
              $rapport->nombre = $rapportPost->gagnant;
              $rapport->rapport = $rapportPost->rapport;
              $resultatAjout = $rapport->ajoute();
              if ($resultatAjout!=true) {
                $erreur=true;
                $json["resultat"]=$resultatAjout;
                break;
              }    
          }
          if ($erreur==false) {
              $json["resultat"]=true;
          }    
     } else {
        echo $retourSuppression;
        $json["resultat"]="Impossible de supprimer les rapports";      
      }
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

