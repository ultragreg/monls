<html>
<head>
	<title>Impression des jeux</title>
	<link href="../style/monlsfonddepage.css" title="Style de l'utilisateur" media="screen" type="text/css" rel="stylesheet">
</head>
<body>
<div style="font-family:Courier New;font-size:1em;font-style:normal;font-weight:bold;line-height:13px;">
<?php
      
  include("../admin/config.php");
  
  include("../admin/fonctionnel.php");
   if (isset($_GET['debug']))
   {
       echo "<div id='avecfonddepage'>";
   }	
   else
   {
       echo "<div id='sansfonddepage'>";
   }	
   
   if (isset($_GET['page']))
   {
       $nbpassage       = $_GET['page'];
      $nbColonneMax = 8;
	  $car="&hearts;";
  	  $db_link = ouvre_base();
  	  if ($db_link)
      {
  		  $jeu = getJeuCourant();
  		  if ($jeu)
  		  {
 		  
           $jeu_id            = $jeu["jeu_id"];
           $dernier_jeu_id    = $jeu_id;
  		     echo "<table class=\"jeu\" id=\"tableauresultat\">";
           
           // Affiche les initiales de tous les joueurs
           $listeJoueurs    = getListeJoueurs();
           $Listepronostic  = array();
           
           for($i=0;$i<sizeof($listeJoueurs);$i++)
           {
              $joueur=$listeJoueurs[$i];
              $joueur_id=$joueur["joueur_id"];
              // Recherche les pronostics de ces joueurs
              $Listepronostic[$i]=getPronosticJoueur($joueur_id,$jeu_id);   
           }
 
           // Nombre de passage à faire = NbJoueurs / nb de colonnes
           $maxPassage =(int) ((sizeof($listeJoueurs)) / $nbColonneMax );
           $reste = (sizeof($listeJoueurs)) % $nbColonneMax ;
           if ($reste>0) $maxPassage = $maxPassage + 1;

           // Jeu à 14 ou 15 matchs ?
           $nbMatchsDeCeJeu  = getNbMatchsDeCeJeu($jeu);
           
                   // echo "<div style=\"height:1.5cm\">&nbsp;</div>";            
                   $numjoueur = (($nbpassage-1)*$nbColonneMax);                                                    
                   // Affiche tous les matchs
                   for($i=1; $i <= $nbMatchsDeCeJeu ; $i++) 
                   {                         
                   // echo "<div style=\"margin-left:2.5cm\">";            
         
                      $pronosticOrdi = getPronostic($i);
                      
                       // Affichage du premier résultat des tous les joueurs, du deuxième résultat, du troisième, ...
                      $nbJoueursQuiOntJoués=0;
                      $nbjoueurtraite = 0;
                      for($ii=$numjoueur;$ii<sizeof($listeJoueurs);$ii++)
                      {
                        $nbjoueurtraite = $nbjoueurtraite + 1;
                        $pronostic = $Listepronostic[$ii];
                        $pronosticJeu = getPronosticNumero($pronostic,$i);
                        
                        // Affichage du pronostic   
                        if ($pronosticJeu == "1")
                            echo $car."<span style=\"font-size:0.2em;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
                        else if ($pronosticJeu =="N")
                            echo "<span style=\"font-size:0.2em;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>".$car."<span style=\"font-size:0.2em;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
                        else if ($pronosticJeu == "2")
                            echo "<span style=\"font-size:0.2em;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>".$car."<span style=\"font-size:0.2em;\">&nbsp;</span>";
                        else
                            echo "<span style=\"font-size:0.2em;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style=\"font-size:0.1em;\">&nbsp;&nbsp;</span>";   
                        
                        if ($nbjoueurtraite == $nbColonneMax)      break;     
                      echo "<span style=\"font-size:0.1em;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
                      }
                      echo "<br />";                    
                  }  

  	    }
        ferme_base($db_link);
    }

   }
   
   if (isset($_GET['debug']))
   {
       echo "</div>";
   }	
   
     ?>    
</div>
</body>
</html>