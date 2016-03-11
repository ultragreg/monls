<?php 
session_start();
ini_set('zlib.output_compression_level', 6);
//ob_start('ob_gzhandler');
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title>Chat | Monls</title>

<link rel="stylesheet" type="text/css" href="js/jScrollPane/jScrollPane.css" />
<link rel="stylesheet" type="text/css" href="css/page.css" />
<link rel="stylesheet" type="text/css" href="css/chat.css" />

</head>

<body>

<div id="chatContainer">

    <div id="chatTopBar" class="rounded">
        <span>
            <span class="name" id="logUser"><?php if (isset($_SESSION['nom_joueur'])) echo $_SESSION['nom_joueur']; ?> </span>
            <a href="" id="chatLogIn" class="logButton rounded login">Connexion</a>
        </span>
    </div>
    <div id="chat_area"></div>
    
    <div id="chatUsers" class="rounded"></div>
    <div id="chatBottomBar" class="rounded">
    	<div class="tip"></div>
        
        <form id="submitForm" method="post" action="">
            <input id="chatText" name="chatText" class="rounded" maxlength="180" placeholder="Votre message ..." value="" />
            <input type="submit"  id="chatSendButton" class="blueButton rounded" value="Envoyer" />
        </form>
        
    </div>
    
</div>

<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/jScrollPane/jquery.mousewheel.js"></script>
<script src="js/chat.js"></script>

</body>
</html>
