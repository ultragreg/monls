<?php 
session_start();
header('Content-Type: application/json');
// BATCH de calcul du classement général
// include database and object files
include_once '../config/database.php';
include_once '../config/util.php';
include_once '../objects/saison.php';
include_once '../objects/jeu.php';
include_once '../objects/joueur.php';
include_once '../objects/pronostic.php';
include_once '../objects/indicateur.php';

if (isset($_SESSION['id_joueur'])) {
    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection(); 

    // Recherche de la saison courante
    $saison = new Saison($db);
    $saison = $saison->chargeSaisonCourante();

    // Lecture des jeux de la saison courante
    $jeu = new Jeu($db);
    $jeu->saison_id = $saison->saison_id; 
    $jeu->chargeDernierJeu();


    // Pronostics des joueurs
    $pronostic = new Pronostic($db);
    $pronostic->jeu_id = $jeu->jeu_id; 
    $stmtPronostics = $pronostic->litPronostics();
    $listePronostics = $stmtPronostics->fetchAll(PDO::FETCH_ASSOC);

    // Recherche la liste des joueurs
    $joueurs = new Joueur($db);
    $stmtJoueurs = $joueurs->litJoueurs();
    $numJoueurs = $stmtJoueurs->rowCount();
    $listeJoueurs = $stmtJoueurs->fetchAll(PDO::FETCH_ASSOC);
 
    // Indicateur de ce joueur
    $indicateur = new Indicateur($db);
    $indicateur->jeu_id = $jeu->jeu_id; 

    $debug=false;
    $nbMatchs=14;
    if ($jeu->jeu_equipe15d!="" && $jeu->jeu_equipe15v!="") {
      $nbMatchs=15;
    }

    if (isset($_GET['debug']))
    {
    	// Mode debug
    	$debug=true;
    	echo "Il y a ". (sizeof($listeJoueurs)-1) . " joueurs<br>";
      echo "Le jeu en-cours a pour id : ".$jeu->jeu_id."<br>\n";
      echo "Le jeu en-cours propose {$nbMatchs} matchs<br>\n";
    }

    $nbJoueurFlash=0;
    $nbJoueurFlashErreur=0;

    // Pour chaque joueur
    for($j=0;$j<sizeof($listeJoueurs);$j++) {
      $joueur_id=$listeJoueurs[$j]["joueur_id"];
      // Pronostic de ce joueur
      $prono=array();
      if ($listePronostics) {
        $prono=getPronoJoueur($listePronostics, $joueur_id);
      }
      if ($debug && isset($prono)) {
        echo "Joueur {$joueur_id} a le prono ".getPronostic($prono, 1)." pour le premier match<br>\n";
      }
      // Pas de pronostic ou alors pronostic vide (car effacé)
      if (!isset($prono) || (isset($prono) && getPronostic($prono, 1)=="") ) {
          if ($debug) {
           echo "=> Pas de pronostic pour ".$listeJoueurs[$j]["nom"]."<br>\n";
         }
         // Lecture des indices de gains 
         $stmtIndicateur = $indicateur->litIndicateurs();
         $listeIndicateurs = $stmtIndicateur->fetchAll(PDO::FETCH_ASSOC);

         $moyenne = 0;
         $nbCasesCoches = 7;
         $nbJoueurFlash++;
         // Génération d'un flash
         $newPronostic = new Pronostic($db);
         $newPronostic->jeu_id     = $jeu->jeu_id; 
         $newPronostic->joueur_id  = $listeJoueurs[$j]["joueur_id"];
         $newPronostic->pronostic1 = getPronoAleatoire();
         $moyenne=$moyenne+getIndicateursMatchChoix($listeIndicateurs, 1, $newPronostic->pronostic1);
         $newPronostic->pronostic2 = getPronoAleatoire();
         $moyenne=$moyenne+getIndicateursMatchChoix($listeIndicateurs, 2, $newPronostic->pronostic2);
         $newPronostic->pronostic3 = getPronoAleatoire();
         $moyenne=$moyenne+getIndicateursMatchChoix($listeIndicateurs, 3, $newPronostic->pronostic3);
         $newPronostic->pronostic4 = getPronoAleatoire();
         $moyenne=$moyenne+getIndicateursMatchChoix($listeIndicateurs, 4, $newPronostic->pronostic4);
         $newPronostic->pronostic5 = getPronoAleatoire();
         $moyenne=$moyenne+getIndicateursMatchChoix($listeIndicateurs, 5, $newPronostic->pronostic5);
         $newPronostic->pronostic6 = getPronoAleatoire();
         $moyenne=$moyenne+getIndicateursMatchChoix($listeIndicateurs, 6, $newPronostic->pronostic6);
         $newPronostic->pronostic7 = getPronoAleatoire();
         $moyenne=$moyenne+getIndicateursMatchChoix($listeIndicateurs, 7, $newPronostic->pronostic7);
         $newPronostic->flash      = "1";  

         // Calcul de l'indice               
         $indice=$moyenne/$nbCasesCoches;
         $indice=round((100-$indice)/10, 2);
         if (strlen(trim($pronostic->pronostic8))==0) {
            $newPronostic->IndiceGain7=$indice;
         } else {
            $newPronostic->IndiceGain15=$indice;
         }


         $return=$newPronostic->ajoute();
         if (!$return) {
              $nbJoueurFlashErreur++;
         } else {
            if ($debug) {
              var_dump($newPronostic);
            }
         }
       }
    }
    $json["resultat"]=true;
    $json["erreur"]=$nbJoueurFlashErreur;
    if ($nbJoueurFlash>1) {
      $json["commentaire"]=$nbJoueurFlash." joueurs flashés";
    } else {
      $json["commentaire"]=$nbJoueurFlash." joueur flashé";
    }
} 
else
{
  $json["resultat"]=false;
  $json["commentaire"]="Pas connecté !";
}
echo json_encode($json);

?>
