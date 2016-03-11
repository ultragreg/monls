



<?php
     // Plusieurs destinataires
     //$to  = 'lafforgue.g@mipih.fr' . ', '; // notez la virgule
     $to = 'lafforgue.g@mipih.fr';

     // Sujet
     $subject = '[LS] [J31] A vous de jouer .... fin des pronos Vendredi 15h00';

     // message
     $message = "
			 <center>
		<FONT COLOR='red' size=5 face='Verdana'><b>RAPPEL : APPEL DE FOND EN COURS</b></font>
		<br><br>
		<FONT COLOR='#0070C0' size=4 face='Verdana'>31ème journée</font>
		<br><br>
		<img SRC='http://monls.tk/img/logo_mail.png'>
		<br>

		<br>
		<FONT size=4 face='Calibri Light'>L'équipe LS a le plaisir de vous informer du jeu, à jouer <b>avant <FONT COLOR='red'>Vendredi 15h00</font></b>.</font>
		<br><br>
		<a href='http://monls.tk/prochainjeu.php'><FONT size=5 COLOR='#1F497D' face='Calibri Light'><b>Accès à « votre grille »</b></font></a>
		<br><br>
		<a href='http://monls.tk'><FONT size=5 COLOR='#31859C' face='Calibri Light'><b>Connexion à monls</b></font></a> 
		&nbsp;&nbsp;&nbsp;&nbsp; 
		<a href='http://monls.tk/m'><FONT size=5 COLOR='#31859C' face='Calibri Light'><b>Connexion à monls mobile</b></font></a> 
		<br>
		<img SRC='http://monls.tk/img/flashcode1.jpg'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img SRC='http://monls.tk/img/flashcode2.jpg'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</center>

     ";

     // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
     $headers  = 'MIME-Version: 1.0' . "\r\n";
     $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

     // En-têtes additionnels
     // $headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
     $headers .= 'From: MONLS <lafforgue.g@mipih.fr>' . "\r\n";
     //$headers .= 'Cc: anniversaire_archive@example.com' . "\r\n";
     //$headers .= 'Bcc: anniversaire_verif@example.com' . "\r\n";

     // Envoi
     mail($to, $subject, $message, $headers);
?>