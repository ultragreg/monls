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
    
        $id_joueur=$_SESSION['id_joueur'];
        $jeu_id     = getIDJeuCourant();
        $erreur=setMiseAJourPronostic($jeu_id,$id_joueur,$tableauPronostic);
        $ok=!$erreur;  

        ferme_base($db_link);
    }
    if ($ok)    echo "1";
    else        echo "0";
    return 0;	
?>
