<?php
    session_start();
    header('Content-Type: text/javascript; charset: UTF-8');
    include("inclusions.php");
    if (!isset($_SESSION['id_joueur']))
    {        
        echo "0";
        return 0;
    }    
    $db_link = ouvre_base();
    $ok=false;
    if ($db_link)
    {   
        $tableauResultat = array(14);
        for($i=1; $i <= 15 ; $i++)
        {
            $tableauResultat[$i]="";
            for($j=1; $j <= 3 ; $j++)
            {
                $temp="";
                // Lecture du pronostic
                $nomChamps="rl".$i."c".$j;
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
    
        $id_joueur    = $_SESSION['id_joueur'];
    	$nom_Joueur   = $_SESSION['nom_joueur'];
 	    $date_saisie  = date("Y/m/d H:i:s"); 
        $jeu_id     = getIDJeuCourant();
        $erreur=setMiseAJourResultat($jeu_id,$tableauResultat, $nom_Joueur, $date_saisie);

        if (!$erreur)
        {
            $saison_id      = getIDSaisonCourante();

                // Dans le cas de saisie, on supprime les images contenant les graphiques
            $fichierImage="../../graph/data1-".$saison_id.".json";
            if (file_exists($fichierImage))   {    unlink($fichierImage);     }  
            $fichierImage="../../graph/data2-".$saison_id.".json";
            if (file_exists($fichierImage))   {    unlink($fichierImage);     }
             
                // Recalcul des statistiques
            RecalculStatistiquesUnejournee($jeu_id);
         
      }

        $ok=!$erreur;  

        ferme_base($db_link);
    }
    if ($ok)    echo "1";
    else        echo "0";
    return 0;	
?>
