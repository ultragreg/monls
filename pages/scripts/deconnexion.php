<?php 
session_start();
session_unset();
session_destroy();

include_once '../config/util.php';
deleteCookie();
header('Location: ../index.php');

?>
