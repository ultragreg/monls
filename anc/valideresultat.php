<?php 
session_start();

include ("scripts/debutpage.php"); 

// Initialisation des varaibles
$jeu_id="";

// Initalisation d'un tableau de resultat (14 lignes avec des valeurs 1N2)
// Lecture du formulaire et remplissage du tableau
$tableauResultat = array(14);
for($i=1; $i <= 15 ; $i++)
{
  $tableauResultat[$i]="";
  for($j=1; $j <= 3 ; $j++)
  {
      $temp="";
      // Lecture du resultat
      $nomChamps="tl".$i."c".$j;
      if (isset($_POST["$nomChamps"]))
      {
          $temp=$_POST["$nomChamps"];
          if ($temp=="1")
          {
              if ($j==1)    $tableauResultat[$i]="1";
              if ($j==2)    $tableauResultat[$i]=$tableauResultat[$i]."N";
              if ($j==3)    $tableauResultat[$i]=$tableauResultat[$i]."2";  
          }  
      } 
   }
}
    
//echo "<html><head><title>aaa</title></head><body>";

$erreur=true;

// Ouverture de la base de données
$db_link = ouvre_base();
if ($db_link)
{
              
  // Si choix d'un autre jeu, on renseigne l'identifiant 
   if (isset($_GET['idjeu']))
   {
        $jeu_ident = $_GET['idjeu'];
   }
   else          
   {
      // Authentification correcte
      header('Location: index.php');
   }
      
    // Requete de lecture du prochain jeu
	  // $jeu = getJeuCourant();
	  // LC On lit maintenant le jeu en paramètre
    $jeu          = getJeu($jeu_ident);
        
   if ($jeu)
 	 {
      // Lecture de l'id du jeu
      $jeu_id         = $jeu["jeu_id"];
      $saison_id      = $jeu["saison_id"];
      
  	  // echo "identifiant du jeu : ". $jeu_id;  
  		$nom_Joueur = $_SESSION['nom_joueur'];
  		$date_saisie = date("Y/m/d H:i:s"); 
  		$erreur=setMiseAJourResultat($jeu_id,$tableauResultat, $nom_Joueur, $date_saisie);
  		
   		if (!$erreur)
  		{
        		// Dans le cas de saisie, on supprime les images contenant les graphiques
            $fichierImage="graph/data1-".$saison_id.".json";
            if (file_exists($fichierImage))   {    unlink($fichierImage);     }  
            $fichierImage="graph/data2-".$saison_id.".json";
            if (file_exists($fichierImage))   {    unlink($fichierImage);     }
             
        		// Recalcul des statistiques
            RecalculStatistiquesUnejournee($jeu_id);
         
      }
   }
   ferme_base($db_link);
}

//echo "5 erreur Modification : ". $erreur;
if ($erreur)
{
  // Authentification correcte
  header('Location: index.php');
}
else
{
  $redirection="Location: saisieresultat.php?idjeu=".$jeu_ident;
  header($redirection);
}
?>


