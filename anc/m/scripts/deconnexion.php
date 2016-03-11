<?php 
session_start();     
$_SESSION["connecte"]         = "0";
$_SESSION["id_joueur"]        = "";
$_SESSION["nom_joueur"]       = "";
$_SESSION["der_cnx_joueur"]   = "";
$_SESSION["admin"]            = "";
session_unset();
session_destroy();

include("../../admin/config.php");
// suppression du cookie
setcookie($nom_cookie,'',0,'/');
echo "1";
?>