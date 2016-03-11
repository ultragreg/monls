<?php 
    session_start();
    header('Content-Type: text/javascript; charset: UTF-8');
    include("inclusions.php");
    $db_link = ouvre_base();
    
    
    if ($db_link)
    {    
        $saison_id        = getIDSaisonCourante();
         // On recherche tous les jeux de la saison demandée et la liste des joueurs
         $listeJoueurs    = getListeJoueurs();
         $listeGains      = getListeMeilleursGains($saison_id);       

         echo "[";
         if (sizeof($listeGains)>0)
         {
             for($i=0;$i<sizeof($listeGains);$i++)
             {
                $gain=$listeGains[$i];
                // Lecture des propriétés du joueur
                $joueur_id    = stripslashes($gain["joueur_id"]);
                $somme        = stripslashes($gain["total"]);
                $nom = getNomJoueurTab($listeJoueurs, $joueur_id);
               
                $tmp=($i+1);
                if ($i < 9) $tmp="0".($i+1);
                $tempJSON="{\"pos\":\"".$tmp."\",\"mon\":\"".round($somme,1)."\",\"jou\":\"".$nom."\"}";
                if ($i < sizeof($listeGains)-1) $tempJSON=$tempJSON. ",";
 
                echo $tempJSON;            
             }
         }
         else
         {
              echo "{\"err\":\"Pas de gain\"}"; 
         }

         echo "]\n";
        ferme_base($db_link);        
}
    else
    {
        echo "[{\"err\":\"Problème technique\"}]"; 
    }?>
