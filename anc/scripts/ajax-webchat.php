<?php 
    session_start();
    header('Content-Type: text/javascript; charset: UTF-8');
include("../admin/config.php");
include("../admin/fonctionnel.php");

require 'jsonwrapper.php';

if(get_magic_quotes_gpc()){

    // If magic quotes is enabled, strip the extra slashes
    array_walk_recursive($_GET,create_function('&$v,$k','$v = stripslashes($v);'));
    array_walk_recursive($_POST,create_function('&$v,$k','$v = stripslashes($v);'));
}

if (!isset($_SESSION['id_joueur'])) 
{
   // Authentification incorrecte
   die(json_encode(array('Authentification incorrecte')));
}
$joueur_id=$_SESSION['id_joueur'];

try
{
    // Connecting to the database
    $db_link = ouvre_base();
    if ($db_link!=False && !isset($_POST['action']))
    {
        $response = array();

        // Actions supportées :
        switch($_GET['action']){

            case 'checkLogged':
                $response = array("nom" => $_SESSION['nom_joueur']);
                break;


            case 'login':
                if (!setUtilisateurChatActif($joueur_id)) {
                    throw new Exception('Impossible de vous logguer');
                }
                else {
                    $response = array("nom" => $_SESSION['nom_joueur']);
                }
                break;


            case 'logout':
                if (!setUtilisateurChatInactif($joueur_id)) {
                    throw new Exception('Impossible de vous délogguer');
                }
                break;


            case 'submitChat':
                if (isset($_POST['chatText'])) {
                    $message  = addslashes ($_POST['chatText']);
                    $response = array("insertID" => insertMessageWebChat($joueur_id, $message));
                }
                else {
                    throw new Exception('Pas de message');
                }

                break;

            case 'getUsers':
                $response = getUtilisateursActifsChat();
                break;

            // Que les messages (utilisé sur la version DeskTop)
            case 'getChats':
                $response = getListeChats($_GET['lastID'],'M');
                break;

            // Messages et avis de conn./déconn (utilisé sur la version mobile)
            case 'getChatsEtInformations':
                $response = getListeChats($_GET['lastID'],'T');
                break;

            default:
               throw new Exception('Action inconnue ('+$_GET['action']+')');
        }

        echo json_encode($response);
    }
    else
    {
        throw new Exception('Erreur de connexion a la BD');
    }
}
catch(Exception $e){
    die(json_encode(array('error' => $e->getMessage())));
}

?>