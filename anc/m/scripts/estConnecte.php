<?php
    session_start();
    // Free utilise php 1.7 et n'implémente pas encore cette fonction echo json_encode($_SESSION);

    $joueur_id      = "";
    $nom            = "";
    $der_cnx        = "";
    $administrateur = "";
    if (isset($_SESSION["id_joueur"]))
    {
        $joueur_id      = $_SESSION["id_joueur"];
        $nom            = $_SESSION["nom_joueur"];
        $der_cnx        = $_SESSION["der_cnx_joueur"];
        $administrateur = $_SESSION["admin"];
    } 
    $retour = "{\"id_joueur\":\"".$joueur_id."\",\"nom_joueur\":\"";
    $retour=$retour.$nom."\",\"der_cnx_joueur\":\"".$der_cnx."\",\"admin\":\"".$administrateur."\"}";
    echo $retour;
?>
                                                                                 