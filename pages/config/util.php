<?php

// include database and object files
include_once 'database.php';
 

// Définition du nom du cookie
$nom_cookie = 'auth';
$sepCookie  = 'aicd45ez432dsf43d432';


function deleteCookie() {
    global $nom_cookie;
    setcookie($nom_cookie,'',0,'/');
}

function addCookie($joueur_id, $nom) {
    global $nom_cookie;
    global $sepCookie;
    setcookie($nom_cookie,$joueur_id.$sepCookie.sha1($nom).$sepCookie.sha1($_SERVER['REMOTE_ADDR']), time() + 10*24*3600,'/');
}


function isCookieOk() {
    global $nom_cookie;
    global $sepCookie;
    // Test du cookie sur toutes les pages du site
    if (isset($_COOKIE[$nom_cookie]) && !isset($_SESSION["id_joueur"]) ) 
    {
        include_once 'objects/joueur.php';
        $monLSCookie = $_COOKIE[$nom_cookie];
        $monLSCookie = explode($sepCookie, $monLSCookie);

        // instantiate database and product object
        $database = new Database();
        $db = $database->getConnection();

        // Joueur
        $joueur = new Joueur($db);
        $joueur->joueur_id=$monLSCookie[0];
        $joueur->chargeJoueur();

        if ($joueur->joueur_nom<>"")
        {
            if ((sha1($joueur->joueur_nom)==$monLSCookie[1])  &&  
                (sha1($_SERVER['REMOTE_ADDR']== $monLSCookie[2])) )  
            {
                // Ok, Ce cookie correspond bien à ce joueur
                $joueur_id      = $joueur->joueur_id;
                $nom            = $joueur->joueur_nom;
                $der_cnx        = $joueur->joueur_der_cnx;
                $administrateur = $joueur->joueur_administrateur;

                // Enregistrer en base
                $retour = $joueur->connecteJoueur();
                if ($retour)
                {
                   // Création de la session 
                    $_SESSION["id_joueur"]        = $joueur_id;
                    $_SESSION["nom_joueur"]       = $nom;
                    $_SESSION["der_cnx_joueur"]   = $der_cnx;
                    $_SESSION["admin"]            = $administrateur;
                    setcookie($nom_cookie,$joueur_id.$sepCookie.sha1($nom).$sepCookie.sha1($_SERVER['REMOTE_ADDR']), time() + 10*24*3600,'/');
                }
            }
        }   
    }
}

// Fonction utilisée par un tri sur la moyenne puis sur le nom du joueur si même moyenne 
function compareMoyenne($a, $b)
{
  return $b["moyenne"] > $a["moyenne"] ? true : ($b["moyenne"] == $a["moyenne"] && $b["nom"] < $a["nom"] ? true : false) ;
}

// Fonction utilisée par un tri sur la position puis le nom
function comparePosition($a, $b)
{
  return $b["position"] < $a["position"] ? true : ($b["position"] == $a["position"] && $b["nom"] < $a["nom"] ? true : false);
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
  if ($i==21)      return "Vingt-deuxième";
  if ($i==22)      return "Vingt-troisième";
  if ($i==23)      return "Vingt-quatrième";
  if ($i==24)      return "Vingt-cinquième";
  if ($i==25)      return "Vingt-sixième";
  if ($i==26)      return "Vingt-septième";
  if ($i==27)      return "Vingt-huitième";
  if ($i==28)      return "Vingt-neuvième";
  if ($i==29)      return "Trentième";
  return "...";
}

function getPositionChiffre($i)
{
  if ($i==0)      return "1er";
  if ($i==1)      return "2ème";
  if ($i==2)      return "3ème";
  if ($i==3)      return "4ème";
  if ($i==4)      return "5ème";
  if ($i==5)      return "6ème";
  if ($i==6)      return "7ème";
  if ($i==7)      return "8ème";
  if ($i==8)      return "9ème";
  if ($i==9)      return "10ème";
  if ($i==10)      return "11ème";
  if ($i==11)      return "12ème";
  if ($i==12)      return "13ème";
  if ($i==13)      return "14ème";
  if ($i==14)      return "15ème";
  if ($i==15)      return "16ème";
  if ($i==16)      return "17ème";
  if ($i==17)      return "18ème";
  if ($i==18)      return "19ème";
  if ($i==19)      return "20ème";
  if ($i==20)      return "21ème";
  if ($i==21)      return "22ème";
  if ($i==22)      return "23ème";
  if ($i==23)      return "24ème";
  if ($i==24)      return "25ème";
  if ($i==25)      return "26ème";
  if ($i==26)      return "27ème";
  if ($i==27)      return "28ème";
  if ($i==28)      return "29ème";
  if ($i==29)      return "30ème";
  return "...";
}


function getPositionClassement($idj, $listeClassement)
{
  for($i=0;$i<sizeof($listeClassement);$i++) {
      $c=$listeClassement[$i];
      if ($idj == $c["joueur_id"]) {
        return $i+1;
      }
  } 
  return;
}

// Retourne 1 si gagnant ou 0 si non
// Prend en paramètre :
// 1. Le nombre de jeu de ce joueur : 7, 14 (ou 15)
// 2. Le nombre de bons résultats de ce joueur
// 3. un tableau de rapport à 7
// 4. un tableau de rapport à 15
// => Dans ce tableau, l'attribut rang porte les grilles gagnantes (15, 14, 13, ... ou 7, 6, ...)
function isGagnant($nbjeu, $nbBonResultats,$jeuxRapport7,$jeuxRapport15) 
{

   //echo "$j:".$listeJoueurs[$j]["nom"].":".$nbjeu."<BR>";
  // C'est un jeu à 7 et les rapports à 7 sont saisis
  if ($nbjeu==7 && sizeof($jeuxRapport7) > 0) 
  {
      //echo "cas 1:$nbBonResultats<BR>";
      for($i=0;$i<sizeof($jeuxRapport7);$i++)  
      {                        
          $rap7=$jeuxRapport7[$i];  
          if ($rap7['rang'] == $nbBonResultats && $rap7['nombre']>0) 
          {
              return 1;
              break;
          }               
      }
  }
  // C'est un jeu à 14 ou 15 et les rapports à 15 sont saisis
  else if ( ($nbjeu==14||$nbjeu==15) && sizeof($jeuxRapport15) > 0) 
  {
  //echo "cas 2:$nbBonResultats<BR>";
      for($i=0;$i<sizeof($jeuxRapport15);$i++)  
      {                  
          $rap15=$jeuxRapport15[$i];   
          if ($rap15['rang'] == $nbBonResultats  && $rap15['nombre']>0) 
          {
              return 1;
              break;
          }                           
      }
  }
  // Pas de rapports saisis mais un score de plus de 75%, c'est un jeu gagnant forcément !
  else 
  {
  //echo "cas 3<BR>";
      $moyenne=$nbBonResultats/$nbjeu*100;
      if (round($moyenne,1)>75) 
      {
              return 1;
      }
  }
  return 0;
}


// Retourne le pronostic d'un joueur
function getPronoJoueur($listePronos, $joueur_id) {
  //echo "<br>+++".$joueur_id."+++";
  for($j=0;$j<sizeof($listePronos);$j++) {
    //echo "===".$listePronos[$j]["joueur_id"]."====";
    if ($joueur_id==$listePronos[$j]["joueur_id"]) {
      return $listePronos[$j];
    }
  }
  return null;
}

// Retourne les indicateurs d'un match
function getIndicateursMatch($listeIndicateurs, $num_match) {
  for($j=0;$j<sizeof($listeIndicateurs);$j++) {
    if ($num_match==$listeIndicateurs[$j]["match_num"]) {
      return $listeIndicateurs[$j];
    }
  }
  return null;
}

// Retourne l'indicateur d'un match pour un choix donné
function getIndicateursMatchChoix($listeIndicateurs, $num_match, $choix) {
  for($j=0;$j<sizeof($listeIndicateurs);$j++) {
    if ($num_match==$listeIndicateurs[$j]["match_num"]) {
      if ($choix=="1") {
        return str_replace(",",".",$listeIndicateurs[$j]["pourcentage1"]);
      } else if ($choix=="N") {
        return str_replace(",",".",$listeIndicateurs[$j]["pourcentageN"]);
      } else if ($choix=="2") {
        return str_replace(",",".",$listeIndicateurs[$j]["pourcentage2"]);
      } 
    }
  }
  return null;
}

// Est-ce qu'il existe des statistiques pour ce jeu ? 
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

// Recherche la statistique pour un joueur et un jeu donné
function getStatistique($jeu_id, $joueur_id,$jeuxStat)
{
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


// Recherche la statistique pour un joueur et un jeu donné
function getPronosticJoueur($joueur_id,$pronostics)
{
	for($p=0;$p<sizeof($pronostics) && !empty($pronostics);$p++)
	{
		$prono=$pronostics[$p];
		$idjo=$prono["joueur_id"];
		if ($idjo==$joueur_id)
		{
			return $prono;
		}
	}
	return False;
}

 // Retourne le pronostic d'un joueur pour un jeu donné
function getNbPronosticJoueur($pronostic)
{
    $j=0;
  for($i=1;$i<=15;$i++) {
    if (getProno($pronostic, $i) <> "") {
      $j++;
    }
  }
  return $j;
}

function getNbMatchsDeCeJeu($jeu)
{
   // Jeu à 14 ou 15 matchs ?
  $nbMatchsDeCeJeu  = 14;
  $equiped          = $jeu->jeu_equipe15d;
  $equipev          = $jeu->jeu_equipe15v;
  if ($equiped && $equipev)   $nbMatchsDeCeJeu  = 15;
  return $nbMatchsDeCeJeu;
}


function getEquipe($j, $i, $type) {
  if ($i==1 && $type=='D') { return $j->jeu_equipe1d; }
  if ($i==1 && $type=='E') { return $j->jeu_equipe1v; }
  if ($i==2 && $type=='D') { return $j->jeu_equipe2d; }
  if ($i==2 && $type=='E') { return $j->jeu_equipe2v; }
  if ($i==3 && $type=='D') { return $j->jeu_equipe3d; }
  if ($i==3 && $type=='E') { return $j->jeu_equipe3v; }
  if ($i==4 && $type=='D') { return $j->jeu_equipe4d; }
  if ($i==4 && $type=='E') { return $j->jeu_equipe4v; }
  if ($i==5 && $type=='D') { return $j->jeu_equipe5d; }
  if ($i==5 && $type=='E') { return $j->jeu_equipe5v; }
  if ($i==6 && $type=='D') { return $j->jeu_equipe6d; }
  if ($i==6 && $type=='E') { return $j->jeu_equipe6v; }
  if ($i==7 && $type=='D') { return $j->jeu_equipe7d; }
  if ($i==7 && $type=='E') { return $j->jeu_equipe7v; }
  if ($i==8 && $type=='D') { return $j->jeu_equipe8d; }
  if ($i==8 && $type=='E') { return $j->jeu_equipe8v; }
  if ($i==9 && $type=='D') { return $j->jeu_equipe9d; }
  if ($i==9 && $type=='E') { return $j->jeu_equipe9v; }
  if ($i==10 && $type=='D') { return $j->jeu_equipe10d; }
  if ($i==10 && $type=='E') { return $j->jeu_equipe10v; }
  if ($i==11 && $type=='D') { return $j->jeu_equipe11d; }
  if ($i==11 && $type=='E') { return $j->jeu_equipe11v; }
  if ($i==12 && $type=='D') { return $j->jeu_equipe12d; }
  if ($i==12 && $type=='E') { return $j->jeu_equipe12v; }
  if ($i==13 && $type=='D') { return $j->jeu_equipe13d; }
  if ($i==13 && $type=='E') { return $j->jeu_equipe13v; }
  if ($i==14 && $type=='D') { return $j->jeu_equipe14d; }
  if ($i==14 && $type=='E') { return $j->jeu_equipe14v; }
  if ($i==15 && $type=='D') { return $j->jeu_equipe15d; }
  if ($i==15 && $type=='E') { return $j->jeu_equipe15v; }
}


function getResultat($r, $i) {
  if ($i==1) { return $r->resultat1; }
  if ($i==2) { return $r->resultat2; }
  if ($i==3) { return $r->resultat3; }
  if ($i==4) { return $r->resultat4; }
  if ($i==5) { return $r->resultat5; }
  if ($i==6) { return $r->resultat6; }
  if ($i==7) { return $r->resultat7; }
  if ($i==8) { return $r->resultat8; }
  if ($i==9) { return $r->resultat9; }
  if ($i==10) { return $r->resultat10; }
  if ($i==11) { return $r->resultat11; }
  if ($i==12) { return $r->resultat12; }
  if ($i==13) { return $r->resultat13; }
  if ($i==14) { return $r->resultat14; }
  if ($i==15) { return $r->resultat15; }
}


function getProno($p, $i) {
  if ($i==1) { return $p->pronostic1; }
  if ($i==2) { return $p->pronostic2; }
  if ($i==3) { return $p->pronostic3; }
  if ($i==4) { return $p->pronostic4; }
  if ($i==5) { return $p->pronostic5; }
  if ($i==6) { return $p->pronostic6; }
  if ($i==7) { return $p->pronostic7; }
  if ($i==8) { return $p->pronostic8; }
  if ($i==9) { return $p->pronostic9; }
  if ($i==10) { return $p->pronostic10; }
  if ($i==11) { return $p->pronostic11; }
  if ($i==12) { return $p->pronostic12; }
  if ($i==13) { return $p->pronostic13; }
  if ($i==14) { return $p->pronostic14; }
  if ($i==15) { return $p->pronostic15; }
}


function getPronostic($p, $i) {
  if ($i==1 and isset($p["pronostic1"]) ) { return $p["pronostic1"]; }
  if ($i==2 and isset($p["pronostic2"]) ) { return $p["pronostic2"]; }
  if ($i==3 and isset($p["pronostic3"]) ) { return $p["pronostic3"]; }
  if ($i==4 and isset($p["pronostic4"]) ) { return $p["pronostic4"]; }
  if ($i==5 and isset($p["pronostic5"]) ) { return $p["pronostic5"]; }
  if ($i==6 and isset($p["pronostic6"]) ) { return $p["pronostic6"]; }
  if ($i==7 and isset($p["pronostic7"]) ) { return $p["pronostic7"]; }
  if ($i==8 and isset($p["pronostic8"]) ) { return $p["pronostic8"]; }
  if ($i==9 and isset($p["pronostic9"]) ) { return $p["pronostic9"]; }
  if ($i==10 and isset($p["pronostic10"]) ) { return $p["pronostic10"]; }
  if ($i==11 and isset($p["pronostic11"]) ) { return $p["pronostic11"]; }
  if ($i==12 and isset($p["pronostic12"]) ) { return $p["pronostic12"]; }
  if ($i==13 and isset($p["pronostic13"]) ) { return $p["pronostic13"]; }
  if ($i==14 and isset($p["pronostic14"]) ) { return $p["pronostic14"]; }
  if ($i==15 and isset($p["pronostic15"]) ) { return $p["pronostic15"]; }
}


function getListePronostic($r, $i) {
  if ($i==1) { return $r["pronostic1"]; }
  if ($i==2) { return $r["pronostic2"]; }
  if ($i==3) { return $r["pronostic3"]; }
  if ($i==4) { return $r["pronostic4"]; }
  if ($i==5) { return $r["pronostic5"]; }
  if ($i==6) { return $r["pronostic6"]; }
  if ($i==7) { return $r["pronostic7"]; }
  if ($i==8) { return $r["pronostic8"]; }
  if ($i==9) { return $r["pronostic9"]; }
  if ($i==10) { return $r["pronostic10"]; }
  if ($i==11) { return $r["pronostic11"]; }
  if ($i==12) { return $r["pronostic12"]; }
  if ($i==13) { return $r["pronostic13"]; }
  if ($i==14) { return $r["pronostic14"]; }
  if ($i==15) { return $r["pronostic15"]; }
}

// Retourne l'indice à 7 ou à 8 d'un pronostic
function getIndice($r) {
  if ($r["IndiceGain7"]>0) {
    return $r["IndiceGain7"];
  } else if ($r["IndiceGain15"]>0) {
    return "<br/>".$r["IndiceGain15"];
  } else {
    return "";
  }
}

function getPronoAleatoire() {
    $characters = '1N2';
    $charactersLength = strlen($characters);
    return $characters[rand(0, $charactersLength - 1)];
}

function isResultatBon($pronosticJeu, $resultatJeu)
{
	for($i=0;$i<strlen($resultatJeu);$i++)
	{
		$car = substr($resultatJeu,$i,1);
		$boolean=strpos($pronosticJeu, $car);
		// echo "=".$pronosticJeu."-".$car." donne ".is_int($boolean);
		if (!(is_int($boolean) ==  false))
		{
			return true;
		}
	}
	return false;
}


// Commence par une voyelle ? 
function isBeginByVowel($chaine) 
{
  $vowels = array('A', 'E', 'I', 'O', 'U');  
  if (in_array(substr($chaine,0,1), $vowels)) {  
    return true;  
  } else {  
    return false;  
  }  
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
    if($en_jour == "Monday")  {$jour = "Lundi";}
    elseif($en_jour == "Tuesday") {$jour = "Mardi";}
    elseif($en_jour == "Wednesday")  {$jour = "Mercredi";}
    elseif($en_jour == "Thursday")   {$jour = "Jeudi";}
    elseif($en_jour == "Friday")  {$jour = "Vendredi";}
    elseif($en_jour == "Saturday")  {$jour = "Samedi";}
    else        {$jour = "Dimanche";}
    switch($m)
    {
      case "01":  $mois="Janvier";break;          
      case "02":  $mois="Février";break;
      case "03":  $mois="Mars";break;
      case "04":  $mois="Avril";break;
      case "05":  $mois="Mai";break;
      case "06":  $mois="Juin";break;
      case "07":  $mois="Juillet";break;
      case "08":  $mois="Août";break;
      case "09":  $mois="Septembre";break;
      case "10":  $mois="Octobre";break;
      case "11":  $mois="Novembre";break;
      case "12":  $mois="Décembre";break;
      default:  $mois="";break;
    }
    switch($j)
    {
      case "01":  $njour="1er";break;         
      case "02":  $njour="2";break;
      case "03":  $njour="3";break;
      case "04":  $njour="4";break;
      case "05":  $njour="5";break;
      case "06":  $njour="6";break;
      case "07":  $njour="7";break;
      case "08":  $njour="8";break;
      case "09":  $njour="9";break;
      default:  $njour=$j;
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
    if($en_jour == "Monday")  {$jour = "lundi";}
    elseif($en_jour == "Tuesday") {$jour = "mardi";}
    elseif($en_jour == "Wednesday")  {$jour = "mercredi";}
    elseif($en_jour == "Thursday")   {$jour = "jeudi";}
    elseif($en_jour == "Friday")  {$jour = "vendredi";}
    elseif($en_jour == "Saturday")  {$jour = "samedi";}
    else        {$jour = "dimanche";}
    switch($m)
    {
      case "01":  $mois="janvier";break;          
      case "02":  $mois="février";break;
      case "03":  $mois="mars";break;
      case "04":  $mois="avril";break;
      case "05":  $mois="mai";break;
      case "06":  $mois="juin";break;
      case "07":  $mois="juillet";break;
      case "08":  $mois="août";break;
      case "09":  $mois="septembre";break;
      case "10":  $mois="octobre";break;
      case "11":  $mois="novembre";break;
      case "12":  $mois="décembre";break;
      default:  $mois="";break;
    }
    switch($j)
    {
      case "01":  $njour="1er";break;         
      case "02":  $njour="2";break;
      case "03":  $njour="3";break;
      case "04":  $njour="4";break;
      case "05":  $njour="5";break;
      case "06":  $njour="6";break;
      case "07":  $njour="7";break;
      case "08":  $njour="8";break;
      case "09":  $njour="9";break;
      default:  $njour=$j;
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
      case "01":  $mois="Janvier";break;          
      case "02":  $mois="Février";break;
      case "03":  $mois="Mars";break;
      case "04":  $mois="Avril";break;
      case "05":  $mois="Mai";break;
      case "06":  $mois="Juin";break;
      case "07":  $mois="Juillet";break;
      case "08":  $mois="Août";break;
      case "09":  $mois="Septembre";break;
      case "10":  $mois="Octobre";break;
      case "11":  $mois="Novembre";break;
      case "12":  $mois="Décembre";break;
      default:  $mois="";break;
    }
    switch($j)
    {
      case "01":  $njour="1er";break;         
      case "02":  $njour="2";break;
      case "03":  $njour="3";break;
      case "04":  $njour="4";break;
      case "05":  $njour="5";break;
      case "06":  $njour="6";break;
      case "07":  $njour="7";break;
      case "08":  $njour="8";break;
      case "09":  $njour="9";break;
      default:  $njour=$j;
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
      case "01":  $mois="Janvier";break;          
      case "02":  $mois="Février";break;
      case "03":  $mois="Mars";break;
      case "04":  $mois="Avril";break;
      case "05":  $mois="Mai";break;
      case "06":  $mois="Juin";break;
      case "07":  $mois="Juillet";break;
      case "08":  $mois="Août";break;
      case "09":  $mois="Septembre";break;
      case "10":  $mois="Octobre";break;
      case "11":  $mois="Novembre";break;
      case "12":  $mois="Décembre";break;
      default:  $mois="";break;
    }
    $date=$mois." ".$a;
    return $date;
    }


}

?>