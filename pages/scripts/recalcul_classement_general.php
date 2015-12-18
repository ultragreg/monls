<?php 
session_start();

// BATCH de calcul du classement général

// include database and object files
include_once '../config/database.php';
include_once '../config/util.php';
include_once '../objects/saison.php';
include_once '../objects/statistique.php';
include_once '../objects/joueur.php';
include_once '../objects/jeu.php';
include_once '../objects/classement.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// Recherche de la saison courante
$saison = new Saison($db);
$saison = $saison->chargeSaisonCourante();
 
// Lecture des statistiques
$stat = new Statistique($db);
$stat->saison_id = $saison->saison_id; 
$stmtStat = $stat->litJeuStat();
$jeuxStat = $stmtStat->fetchAll(PDO::FETCH_ASSOC);
 
// Lecture des jeux de la saison courante
$jeux = new Jeu($db);
$jeux->saison_id = $saison->saison_id; 
$stmtJeux = $jeux->litJeux();
$listeJeux = $stmtJeux->fetchAll(PDO::FETCH_ASSOC);


// Recherche la liste des joueurs
$joueurs = new Joueur($db);
$stmtJoueurs = $joueurs->litJoueurs();
$numJoueurs = $stmtJoueurs->rowCount();
$listeJoueurs = $stmtJoueurs->fetchAll(PDO::FETCH_ASSOC);

// Classement
$classement = new Classement($db);
$classement->saison_id = $saison->saison_id; 


$debug=false;
if (isset($_GET['debug']))
{
	// Mode debug
	$debug=true;
}

// initialise les divers tableaux de données
$TotalMoyenneGenerale  = array();
$TotalMatchJoue        = array();
$TotalMoyenneGeneraleSF  = array();
$TotalMatchJoueSF        = array();
$TotalMoyenneGeneraleAF  = array();
$TotalMatchJoueAF        = array();
$TotalMoyenneGeneraleJM1  = array();
$TotalMatchJoueJM1        = array();
$TotalMoyenneGeneraleJM1SF  = array();
$TotalMatchJoueJM1SF        = array();
$TotalMoyenneGeneraleJM1AF  = array();
$TotalMatchJoueJM1AF        = array();        
for ($j = 0; $j<$numJoueurs; $j++) {    
    $TotalMoyenneGenerale[$j] =0;
    $TotalMatchJoue[$j] = 0;
    $TotalMoyenneGeneraleSF[$j] =0;
    $TotalMatchJoueSF[$j] = 0;
    $TotalMoyenneGeneraleAF[$j] =0;
    $TotalMatchJoueAF[$j] = 0;
    $TotalMoyenneGeneraleJM1[$j] =0;
    $TotalMatchJoueJM1[$j] = 0;
    $TotalMoyenneGeneraleJM1SF[$j] =0;
    $TotalMatchJoueJM1SF[$j] = 0;
    $TotalMoyenneGeneraleJM1AF[$j] =0;
    $TotalMatchJoueJM1AF[$j] = 0;  
}    


// Calcul des tableaux de données
$premierepasse=true;

if ($debug) {
	echo "Il y a ". (sizeof($listeJeux)-1) . " jeu(x)<br>";
}

for($i=sizeof($listeJeux)-1; $i >= 0 ; $i--) 
{

    // Pour chaque jeu de cette saison
    $moyennejeu  = 0;
    $nbjeu       = 0;
    $jeu         = $listeJeux[$i];       
    $jeu_id      = $jeu['jeu_id'];

    // Jeu présent dans les statistiques ?
    if (!getJeuPresent($jeu_id,$jeuxStat))
    {      
        continue;
    }

    if ($debug) {
    	echo "jeu : ". $i . " : " .$jeu['titre']."<br>";
    }
    // Première passe pour trouver le meilleur et le plus mauvais résultat pour ce jeu
    $meilleur=0;
    $mauvais=100;       
    for($j=0;$j<sizeof($listeJoueurs);$j++)
    {
        $joueur=$listeJoueurs[$j];
        $joueur_id=$joueur["joueur_id"];

        $temp = getStatistique($jeu_id, $joueur_id,$jeuxStat);
        if ($temp)  
        {
        $moyenne =$temp["valeur"];
        if ($moyenne>$meilleur)
        {
        $meilleur=$moyenne;
        }
        if ($moyenne<$mauvais)
        {
        $mauvais=$moyenne;
        }                    
        }
    }


    // Calcul des moyennes pour ce jeu et pour chacun des joueurs
    for($j=0;$j<sizeof($listeJoueurs);$j++)
    {
        $joueur=$listeJoueurs[$j];
        $joueur_id=$joueur["joueur_id"];

        $temp = getStatistique($jeu_id, $joueur_id,$jeuxStat);
        if ($temp)  
        {
            $moyenne =$temp["valeur"];
            $flash   =$temp["flash"];

            $moyennejeu  = $moyennejeu+round($moyenne,1);
            $nbjeu       = $nbjeu+1;

            $TotalMoyenneGenerale[$j] = $TotalMoyenneGenerale[$j]+round($moyenne,1);   
            $TotalMatchJoue[$j] = $TotalMatchJoue[$j]+1;
            // Pronostic non flashé 
            if ($flash != "1")
            {   
                $TotalMoyenneGeneraleSF[$j] = $TotalMoyenneGeneraleSF[$j]+round($moyenne,1);  
                $TotalMatchJoueSF[$j]=$TotalMatchJoueSF[$j]+1;
            }
            // Pronostic flashé 
            if ($flash == "1")
            {   
                $TotalMoyenneGeneraleAF[$j] = $TotalMoyenneGeneraleAF[$j]+round($moyenne,1);  
                $TotalMatchJoueAF[$j]=$TotalMatchJoueAF[$j]+1;
            }

            // Ce n'est pas la première passe ? on renseigne les moyennes pour la journée précédente
            if (!$premierepasse)
            {
                $TotalMoyenneGeneraleJM1[$j] = $TotalMoyenneGeneraleJM1[$j]+round($moyenne,1);   
                $TotalMatchJoueJM1[$j] = $TotalMatchJoueJM1[$j]+1;
                // Total sans les flash 
                if ($flash!= "1")
                {   
                    $TotalMoyenneGeneraleJM1SF[$j] = $TotalMoyenneGeneraleJM1SF[$j]+round($moyenne,1);   
                    $TotalMatchJoueJM1SF[$j] = $TotalMatchJoueJM1SF[$j]+1; 
                }
                // Total que les flash 
                if ($flash == "1")
                {   
                    $TotalMoyenneGeneraleJM1AF[$j] = $TotalMoyenneGeneraleJM1AF[$j]+round($moyenne,1);   
                    $TotalMatchJoueJM1AF[$j] = $TotalMatchJoueJM1AF[$j]+1; 
                }
            }
        }
        else
        {
            $moyenne="";              
        }
        

        
        
    }
    $premierepasse=false;
}


// ***************** Moyenne générale totale  ***************** //

$MoyenneGeneraleParJoueur = array();
$MoyenneGeneraleParJoueurJM1 = array();
$RapportMoyenneIndiceParJoueur = array();
$EvolutionParJoueur = array();
$PositionDuJoueur = array();
$PositionDuJoueurPriseDeRisque = array();

// Calcul des moyennes générales sur l'ensemble des jeux pour chacun des joueurs
$moyennejeu=0;
$nbjeu=0;
for($j=0;$j<sizeof($listeJoueurs);$j++)
{
    if ($TotalMatchJoue[$j]) {
      $moyenne=$TotalMoyenneGenerale[$j]/$TotalMatchJoue[$j]; 
    }
    else {
      $moyenne=0;
    }
    $MoyenneGeneraleParJoueur[$j]=round($moyenne,1);   
                     
    $moyennejeu  = $moyennejeu+round($moyenne,1);
    $nbjeu       = $nbjeu+1;

    //Rapport entre la moyenne du joueur et son indice de gain moyen (prise de risque)
    $joueur=$listeJoueurs[$j];
    $stat->joueur_id = $joueur["joueur_id"];           
    $stmtRes = $stat->litIndiceGainMoyenJoueurAF();
    //$stmtRes = $stat->litMoyenneJusteJoueurAF();
    $row = $stmtRes->fetch(PDO::FETCH_ASSOC);
    extract($row);
    $indiceMoyen = stripslashes($indMoyen);
    //$moyenneJusteSaison = stripslashes($moyJuste);
    $rapport = round($moyenne/(100-floatval($indiceMoyen)*10),2);
    
    //$rapport = round($moyenne/$moyenneJusteSaison,2);
    $RapportMoyenneIndiceParJoueur[$j]=$rapport;
}

// Calcul des moyennes générales par joueur avant la dernière journée ! cela permettra de calculer l'évolution
$moyennejeu=0;
for($j=0;$j<sizeof($listeJoueurs);$j++)
{
      if ($TotalMatchJoueJM1[$j])
      {
          $moyenne=$TotalMoyenneGeneraleJM1[$j]/$TotalMatchJoueJM1[$j]; 
      }
      else
          $moyenne=0;
      $MoyenneGeneraleParJoueurJM1[$j]=round($moyenne,1);       
}

// Calcul de la position du joueur
for($j=0;$j<sizeof($listeJoueurs);$j++)
{
      $MoyenneJoueur = $MoyenneGeneraleParJoueur[$j];
      $positionJoueur=1;
      for($k=0;$k<sizeof($listeJoueurs);$k++)
      {
          if ($MoyenneGeneraleParJoueur[$k]>$MoyenneJoueur)
          {
              $positionJoueur++;
          }
      }
      $PositionDuJoueur[$j]=$positionJoueur;   
}

// Comparaison de la moyenne générale avec la moyenne précédente pour trouver l'évolution
for($j=0;$j<sizeof($listeJoueurs);$j++)
{
      $MoyenneJoueur = $MoyenneGeneraleParJoueur[$j];
      $positionJoueur=1;
      for($k=0;$k<sizeof($listeJoueurs);$k++)
      {
          if ($MoyenneGeneraleParJoueur[$k]>$MoyenneJoueur)
          {
              $positionJoueur++;
          }
      }
      
      $MoyenneJoueur = $MoyenneGeneraleParJoueurJM1[$j];
      $positionJoueurJM1=1;
      $nbjournee=0;
      for($k=0;$k<sizeof($listeJoueurs);$k++)
      {
          if ($MoyenneGeneraleParJoueurJM1[$k]>$MoyenneJoueur)
          {
              $positionJoueurJM1++;
              $nbjournee=$nbjournee+1;
          }
      }
      if ($nbjournee>0)
      {
         $EvolutionParJoueur[$j]=$positionJoueurJM1-$positionJoueur; 
      }
      else
      {
        $EvolutionParJoueur[$j]=0;  
      }
}

// Calcul de la position du joueur en fonction de la prise de risque
for($j=0;$j<sizeof($listeJoueurs);$j++)
{
      $MoyenneJoueur = $RapportMoyenneIndiceParJoueur[$j];
      $positionJoueur=1;
      for($k=0;$k<sizeof($listeJoueurs);$k++)
      {
          if ($RapportMoyenneIndiceParJoueur[$k]>$MoyenneJoueur)
          {
              $positionJoueur++;
          }
      }
      $PositionDuJoueurPriseDeRisque[$j]=$positionJoueur;
}

// Enregistrement en base 

if ($debug) {
	echo "Suppression du classement saison ".$classement->saison_id ."<br>";
}
$classement->efface();

for($j=0;$j<sizeof($listeJoueurs);$j++)
{
    $joueur    = $listeJoueurs[$j];
    $classement->joueur_id = $joueur["joueur_id"];
    $classement->moyenne = $MoyenneGeneraleParJoueur[$j];
    $classement->rapport = $RapportMoyenneIndiceParJoueur[$j];
    $classement->evolution = $EvolutionParJoueur[$j];
    $classement->posRisque = $PositionDuJoueurPriseDeRisque[$j];
    if ($debug) {
    	echo "Ajout du joueur id:".$classement->joueur_id." => Moyenne:".$classement->moyenne."<br>";
    }
    $classement->ajoute();
}


?>
