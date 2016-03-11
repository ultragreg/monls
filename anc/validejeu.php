<?php 
session_start();
include ("scripts/debutpage.php");
// Initialisation des varaibles
$joueur_id="";
$jeu_id="";
// Initalisation d'un tableau de pronostics (14 lignes avec des valeurs 1N2)
// Lecture du formulaire et remplissage du tableau
$tableauPronostic = array(14);
for($i=1; $i <= 15 ; $i++)
{
  $tableauPronostic[$i]="";
  for($j=1; $j <= 3 ; $j++)
  {
      $temp="";
      // Lecture du pronostic
      $nomChamps="tl".$i."c".$j;
      if (isset($_POST["$nomChamps"]))
      {
          $temp=$_POST["$nomChamps"];
          if ($temp=="1")
          {
              if ($j==1)    $tableauPronostic[$i]="1";
              if ($j==2)    $tableauPronostic[$i]=$tableauPronostic[$i]."N";
              if ($j==3)    $tableauPronostic[$i]=$tableauPronostic[$i]."2";  
          }  
      } 
   }
}
    
$erreur=true;
// Ouverture de la base de données
$db_link = ouvre_base();
if ($db_link)
{
              
  // Si choix d'un autre jeu, on renseigne l'identifiant 
   if (isset($_GET['idjeu']))
   {
        $jeu_ident = $_GET['idjeu'];
        
        // Requete de lecture du prochain jeu
        // $jeu = getJeuCourant();
        // LC On lit maintenant le jeu en paramètre
        $jeu          = getJeu($jeu_ident);
       
        
        if ($jeu)
 	      {
              // Lecture de l'id du jeu
              $jeu_id         = $jeu["jeu_id"];
      
  		        //echo "identifiant du jeu : ". $jeu_id;  
  		
              // On recherche l'identifiant du joueur dans la session
  		        $joueur_id=$_SESSION['id_joueur'];
  		
  		        //echo "identifiant joueur : ". $joueur_id;  
  		        $erreur=setMiseAJourPronostic($jeu_id,$joueur_id,$tableauPronostic);
  		        // Dans le cas de saisie, on supprime les images contenant les graphiquesq
  		        if (!$erreur)
  		        {
  		            $fichierImage="data1.json";
                  // Suppression des graphiques sur le classement 
                  if (file_exists($fichierImage))
                  { 
                      unlink($fichierImage);
                  }  		
  		            $fichierImage="data2.json";
                  // Suppression des graphiques sur le classement 
                  if (file_exists($fichierImage))
                  { 
                      unlink($fichierImage);
                  }  		
              }
          }
    }
    ferme_base($db_link);
}
else
{   
  // Authentification correcte
  header('Location: index.php');
}


if ($erreur)
{
  // Authentification correcte
  header('Location: index.php');
}
else
{
  $redirection="Location: prochainjeu.php?idjeu=".$jeu_ident;
  header($redirection);
}
?>