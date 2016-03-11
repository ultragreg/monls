<?php

$url_du_site = $_SERVER['SERVER_NAME'];

$sql_serveur=getenv('DB_HOSTNAME');
$sql_user=getenv('DB_USERNAME');
$sql_passwd=getenv('DB_PASSWORD');
$sql_bdd=getenv('DB_NAME');
      

// Date et heure courantes, ne pas modifier
$now=date("Y-m-d H:i:s");
$maintenant=date("d-m-Y H:i");
$date_jour=formatte_date($now,4);
$date_jour_simple=date("d/m/Y");


// Mail de l'administrateur
$mail_admin="monls@free.fr";


// Définition du nom du cookie
$nom_cookie = 'auth';
$sepCookie  = 'aicd45ez432dsf43d432';

function isCookieOk()
{

	global $nom_cookie;
	global $sepCookie;
	// Test du cookie sur toutes les pages du site
	if (isset($_COOKIE[$nom_cookie]) && !isset($_SESSION["id_joueur"]) ) 
	{
		$monLSCookie = $_COOKIE[$nom_cookie];
		$monLSCookie = explode($sepCookie, $monLSCookie);
		// Ouverture de la base de données
		$db_link = ouvre_base();
		if ($db_link && count($monLSCookie)==3)
		{

	        // Requete pour retrouver cette utilisateur
	        $joueur=getUtilisateurID($monLSCookie[0]);

			// Lecture des propriétés du joueur
			$joueur_id      = $joueur["joueur_id"];
			$nom            = $joueur["nom"];
			if ( (sha1($nom)==$monLSCookie[1])  &&  (sha1($_SERVER['REMOTE_ADDR']== $monLSCookie[2])) )
			{

			  $der_cnx        = $joueur["der_cnx"];
			  $administrateur = $joueur["administrateur"];
			  // Enregistrer en base
			  setCnxUtilisateur($joueur_id);
			  // Création de la session 
			  $_SESSION["id_joueur"]        = $joueur_id;
			  $_SESSION["nom_joueur"]       = $nom;
			  $_SESSION["der_cnx_joueur"]   = $der_cnx;
			  $_SESSION["admin"]            = $administrateur;
			  // Raffraichissement du cookie
					setcookie($nom_cookie,$joueur_id.$sepCookie.sha1($nom).$sepCookie.sha1($_SERVER['REMOTE_ADDR']), time() + 10*24*3600,'/');

			}
			else
			{
					setcookie($nom_cookie,'',0,'/');
			}

	        ferme_base($db_link);
		}		
		else
		{
		setcookie($nom_cookie,'',0,'/');
		}
	}
}



function getmicrotime()
{
    $tmp = explode( ' ', microtime() );
    return $tmp[1] . substr( $tmp[0], 1 );
}




function getPosition($i)
{
  if ($i==0)      return "Premier";
  if ($i==1)      return "Deuxième";
  if ($i==2)      return "Troisième";
  if ($i==3)      return "Quatrième";
  if ($i==4)      return "Cinquième";
  if ($i==5)      return "Sixième";
  if ($i==6)      return "Septième";
  if ($i==7)      return "Huitième";
  if ($i==8)      return "Neuvième";
  if ($i==9)      return "Dixième";
  if ($i==10)      return "Onzième";
  if ($i==11)      return "Douzième";
  if ($i==12)      return "Treizième";
  if ($i==13)      return "Quatorzième";
  if ($i==14)      return "Quinzième";
  if ($i==15)      return "Seizième";
  if ($i==16)      return "Dix-Septième";
  if ($i==17)      return "Dix-Huitième";
  if ($i==18)      return "Dix-neuvième";
  if ($i==19)      return "Vingtième";
  if ($i==20)      return "Vingt-et-unième";
  return "...";
}

function stripslashes_r( $string ) {
    if(get_magic_quotes_gpc()) {
        return stripslashes($string);
    } else {
        return $string;
    }
}

// Connexion à la base 
function ouvre_base()
{
  global $sql_serveur;
  global $sql_user;
  global $sql_passwd;
  global $sql_bdd;
	// Connexion à la base de donnée
	$db_link = mysql_connect($sql_serveur,$sql_user,$sql_passwd);
	if (!$db_link) 
  {
	 //  die ('Impossible de se connecter : ' . mysql_error()); 
     return False;
  }

	// Sélection de la base de données db
	$db_selected = mysql_select_db($sql_bdd);	
	if (!$db_selected) 
  {
	 //  die ('Impossible d\'utiliser la base : ' . mysql_error()); 
     return False;
  }
	
	mysql_query("SET NAMES UTF8"); 

  return $db_link;
}

// Déconnexion à la base 
function ferme_base($db_link)
{
		mysql_close($db_link);
}

// Formate une date 
function formatte_date($date,$style)
{
	if ($style == 1)
	{
		// aaaa/mm/jj -> jj/mm/aaaa ou jj/mm/aaaa Hhm
		$a=substr($date,0,4);
		$m=substr($date,5,2);
		$j=substr($date,8,2);
		if(($len_date=strlen($date)) == 10)
	  	{
        	        $date=$j."/".$m."/".$a;
	  	}
		elseif($len_date == 19)
  		{
			$h=substr($date,11,2);
			$n=substr($date,14,2);
                	$date=$j."/".$m."/".$a." à ".$h.":".$n;
	  	}
		return $date;
	}
	if ($style == 2)
	{
		// jj/mm/aaaa ->aaaa/mm/jj
		$j=substr($date,0,2);
		$m=substr($date,3,2);
		$a=substr($date,6,4);
               	$date=$a."/".$m."/".$j;
		return $date;
  	}
	if ($style == 4)
	{
		// aaaa/mm/jj ->jour jj mois aaaa
		$a=substr($date,0,4);
		$m=substr($date,5,2);
		$j=substr($date,8,2);
		$timestamp=mktime(0,0,0,$m,$j,$a);
		$en_jour = date("l",$timestamp);
		if($en_jour == "Monday")	{$jour = "Lundi";}
		elseif($en_jour == "Tuesday")	{$jour = "Mardi";}
		elseif($en_jour == "Wednesday")  {$jour = "Mercredi";}
		elseif($en_jour == "Thursday")   {$jour = "Jeudi";}
		elseif($en_jour == "Friday")	{$jour = "Vendredi";}
		elseif($en_jour == "Saturday")	{$jour = "Samedi";}
		else				{$jour = "Dimanche";}
		switch($m)
		{
			case "01":	$mois="Janvier";break;					
			case "02":	$mois="Février";break;
			case "03":	$mois="Mars";break;
			case "04":	$mois="Avril";break;
			case "05":	$mois="Mai";break;
			case "06":	$mois="Juin";break;
			case "07":	$mois="Juillet";break;
			case "08":	$mois="Août";break;
			case "09":	$mois="Septembre";break;
			case "10":	$mois="Octobre";break;
			case "11":	$mois="Novembre";break;
			case "12":	$mois="Décembre";break;
			default: 	$mois="";break;
		}
		switch($j)
		{
			case "01":	$njour="1er";break;					
			case "02":	$njour="2";break;
			case "03":	$njour="3";break;
			case "04":	$njour="4";break;
			case "05":	$njour="5";break;
			case "06":	$njour="6";break;
			case "07":	$njour="7";break;
			case "08":	$njour="8";break;
			case "09":	$njour="9";break;
			default:	$njour=$j;
		}
		$date=$jour." ".$njour." ".$mois." ".$a;
		return $date;
  	}
	if ($style == 5)
	{
		// aaaa/mm/jj ->jour jj mois
		$a=substr($date,0,4);
		$m=substr($date,5,2);
		$j=substr($date,8,2);
		$timestamp=mktime(0,0,0,$m,$j,$a);
		$en_jour = date("l",$timestamp);
		if($en_jour == "Monday")	{$jour = "lundi";}
		elseif($en_jour == "Tuesday")	{$jour = "mardi";}
		elseif($en_jour == "Wednesday")  {$jour = "mercredi";}
		elseif($en_jour == "Thursday")   {$jour = "jeudi";}
		elseif($en_jour == "Friday")	{$jour = "vendredi";}
		elseif($en_jour == "Saturday")	{$jour = "samedi";}
		else				{$jour = "dimanche";}
		switch($m)
		{
			case "01":	$mois="janvier";break;					
			case "02":	$mois="février";break;
			case "03":	$mois="mars";break;
			case "04":	$mois="avril";break;
			case "05":	$mois="mai";break;
			case "06":	$mois="juin";break;
			case "07":	$mois="juillet";break;
			case "08":	$mois="août";break;
			case "09":	$mois="septembre";break;
			case "10":	$mois="octobre";break;
			case "11":	$mois="novembre";break;
			case "12":	$mois="décembre";break;
			default: 	$mois="";break;
		}
		switch($j)
		{
			case "01":	$njour="1er";break;					
			case "02":	$njour="2";break;
			case "03":	$njour="3";break;
			case "04":	$njour="4";break;
			case "05":	$njour="5";break;
			case "06":	$njour="6";break;
			case "07":	$njour="7";break;
			case "08":	$njour="8";break;
			case "09":	$njour="9";break;
			default:	$njour=$j;
		}
		$date=$jour." ".$njour." ".$mois;
		return $date;
  	}
	if ($style == 6)
	{
		// JJmm -> jj mois
		$m=substr($date,3,2);
		$j=substr($date,0,2);
		switch($m)
		{
			case "01":	$mois="Janvier";break;					
			case "02":	$mois="Février";break;
			case "03":	$mois="Mars";break;
			case "04":	$mois="Avril";break;
			case "05":	$mois="Mai";break;
			case "06":	$mois="Juin";break;
			case "07":	$mois="Juillet";break;
			case "08":	$mois="Août";break;
			case "09":	$mois="Septembre";break;
			case "10":	$mois="Octobre";break;
			case "11":	$mois="Novembre";break;
			case "12":	$mois="Décembre";break;
			default: 	$mois="";break;
		}
		switch($j)
		{
			case "01":	$njour="1er";break;					
			case "02":	$njour="2";break;
			case "03":	$njour="3";break;
			case "04":	$njour="4";break;
			case "05":	$njour="5";break;
			case "06":	$njour="6";break;
			case "07":	$njour="7";break;
			case "08":	$njour="8";break;
			case "09":	$njour="9";break;
			default:	$njour=$j;
		}
		$date=$njour." ".$mois;
		return $date;
  	}
	if ($style == 7)
	{
		// aaaa/mm -> mois aaaa
		$a=substr($date,0,4);
		$m=substr($date,5,2);
		switch($m)
		{
			case "01":	$mois="Janvier";break;					
			case "02":	$mois="Février";break;
			case "03":	$mois="Mars";break;
			case "04":	$mois="Avril";break;
			case "05":	$mois="Mai";break;
			case "06":	$mois="Juin";break;
			case "07":	$mois="Juillet";break;
			case "08":	$mois="Août";break;
			case "09":	$mois="Septembre";break;
			case "10":	$mois="Octobre";break;
			case "11":	$mois="Novembre";break;
			case "12":	$mois="Décembre";break;
			default: 	$mois="";break;
		}
		$date=$mois." ".$a;
		return $date;
  	}
}


function getBrowser() 
{ 	
  // récupération du navigateur et de l'OS du client
	if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Gecko') )
	{
   		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape') )
   		{
     			$browser = 'Netscape ';
   		}
   		else if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') )
   		{
     			$browser = 'Firefox';
   		}
   		else
   		{
     			$browser = 'Mozilla';
   		}
	}	
	else if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') )
	{
   		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') )
   		{
     			$browser = 'Opera';
   		}
   		else
   		{
     			$browser = 'IE';
   		}
	}
	else
	{
   		$browser = 'Autres';
	}	
  return $browser;
}



function SauveBaseEtMail($destinataire)
{
	  $db_link = ouvre_base();
	  global $sql_bdd;
	  if ($db_link)
    {
      $tables = mysql_list_tables($sql_bdd);
      while ($donnees = mysql_fetch_array($tables))
        {
        $table = $donnees[0];
        $res = mysql_query("SHOW CREATE TABLE $table");
        if ($res)
          {
          $insertions = "";
          $tableau = mysql_fetch_array($res);

          //$tableau[1] .= ";";
          $valTab = "\nDROP TABLE IF EXISTS `$table`;";
          $valTab .= "\n".str_replace("\n", "", $tableau[1]);
          $dumpsql[] = str_replace(",", ",\n", $valTab);
          
          // Ajout de la notion de l'auto-incrmement 
          $req_autoincrement = mysql_query('SHOW TABLE STATUS FROM '.$sql_bdd.' LIKE \'' . $table. '\'');
          if ($req_autoincrement != FALSE) 
          {
              $ligne_autoincrement = mysql_fetch_array($req_autoincrement);
              if (!empty($ligne_autoincrement['Auto_increment'])) 
              {
                  $dumpsql[].= ' AUTO_INCREMENT=' . $ligne_autoincrement['Auto_increment'] . ";\n";
              }
          }
          $req_table = mysql_query("SELECT * FROM $table");
          $nbr_champs = mysql_num_fields($req_table);
          while ($ligne = mysql_fetch_array($req_table))
            {
            $insertions .= "INSERT INTO $table VALUES(";
            for ($i=0; $i<=$nbr_champs-1; $i++)
              {
              $insertions .= "'" . mysql_real_escape_string($ligne[$i]) . "', ";
              }
            $insertions = substr($insertions, 0, -2);
            $insertions .= ");\n";
            }
          if ($insertions != "")
            {
            $dumpsql[] = $insertions;
            }
          }
        }
      $contenucomplet =  implode("\r", $dumpsql);
      
    
      // Entete du mail ..
      $entetedate  = date("D, j M Y H:i:s -0600"); // Offset horaire
      $entetemail  = "From: AdministrateurMonLS \n"; // Adresse expéditeur
      $entetemail .= "Cc: \n";
    
      $entetemail .= "Bcc: \n"; // Copies cachées
      $entetemail .= "Reply-To: monls@free.fr \n"; // Adresse de retour
      $entetemail .= "X-Mailer: PHP/" . phpversion() . "\n" ;
    
      $entetemail .= "Date: $entetedate";
    	      
      mail($destinataire, "Sauvegarde de la BD monls", $contenucomplet,$entetemail);
      
      ferme_base($db_link);
    }
}


function getJeuPresent($jeu_id,$jeuxStat)
{
    // Affichage des initiales de tous les joueurs
    for($p=0;$p<sizeof($jeuxStat) && !empty($jeuxStat);$p++)
    {
        $t=$jeuxStat[$p];
        if (isset($t))
        {
            $idje=$t["jeu_id"];
            if ($idje==$jeu_id )  
            {
                return True;
            }
        }
   }
    return False;
}


function getValeur($jeu_id, $joueur_id,$jeuxStat)
{
    // Affichage des initiales de tous les joueurs
    for($p=0;$p<sizeof($jeuxStat) && !empty($jeuxStat);$p++)
    {
        $t=$jeuxStat[$p];
        $idjo=$t["joueur_id"];
        $idje=$t["jeu_id"];
        if ($idje==$jeu_id AND $idjo==$joueur_id)  
        {
            return $t;
        }
    }
    return False;
}


?>
