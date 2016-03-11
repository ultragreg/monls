<?php 
session_start();
// pour la buter
session_unset();
session_destroy();

 include("admin/config.php");  
// suppression du cookie
setcookie($nom_cookie,'',0,'/');
// On redirige vers la page principale
header('Location: index.php');

?>
