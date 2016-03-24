<?php

// ===================================================== //
//                L E     W E B C H A T                  //
// ===================================================== //


// Retourne tous les chats
function getListeChats($lastID, $type)
{
  $saisonCourante = getIDSaisonCourante();

  // Récupère tous les messages (y compris les avis de connexion/déconnexion)
  if ($type=='T') {
    // Requete pour retrouver toutes les saisons dans l'ordre d'insertion en base
    $sql = "select w.webchat_id, j.nom, w.message, DATE_FORMAT(w.ts,'%d/%m %H:%i:%S') ts, w.type from webchat w, joueur j 
            where w.saison_id = '$saisonCourante' 
            and j.joueur_id = w.joueur_id 
            and webchat_id > '$lastID'  
            order by webchat_id asc";
  }
  else {
    // Requete pour retrouver toutes les saisons dans l'ordre d'insertion en base
    $sql = "select w.webchat_id, j.nom, w.message, DATE_FORMAT(w.ts,'%d/%m %H:%i:%S') ts, w.type from webchat w, joueur j 
            where w.saison_id = '$saisonCourante' 
            and j.joueur_id = w.joueur_id 
            and webchat_id > '$lastID'  
            and w.type = 'M'
            order by webchat_id asc";
  }
  
  $webchats = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
  {

      // Lecture de ce jeu
      $cpt=0;
      while( $webchat = mysql_fetch_assoc($result) )
      {         
          if ($webchat)
          {
            $webchats[$cpt]=$webchat;
            $cpt++;
          } 
      }
      return $webchats;
  }
  return "";
}



// Ajout d'un message sur la saison courante
function insertMessageWebChat($joueur_id, $message)
{
  return insertInformationWebChat($joueur_id, $message, 'M');
}

// Ajout d'un avis de connexion d'un joueur sur la saison courante
function insertConnexionWebChat($joueur_id, $message)
{
  return insertInformationWebChat($joueur_id, '', 'C');
}

// Ajout d'un avis de déconnexion d'un joueur sur la saison courante
function insertDeconnexionWebChat($joueur_id, $message)
{
  return insertInformationWebChat($joueur_id, '', 'D');
}


// Ajout d'un message sur la saison courante
function insertInformationWebChat($joueur_id, $message,$type)
{
  // Pour l'ajout, on recherche l'id de la dernière saison
  $saison_id = getIDSaisonCourante();
  // Date système
  date_default_timezone_set('Europe/Paris'); 
  $dateSysteme= date("Y/m/d H:i:s");       
  // Ajout de ce message
  $requete= "insert into webchat (saison_id, joueur_id, message, ts, type) 
                  values ('$saison_id', '$joueur_id', '$message', '$dateSysteme','$type')";
  $result = mysql_query($requete);
  if (mysql_errno() == 0) 
  {
        return  mysql_insert_id();
  }
  return "-1";
}
 

// ======================================================== //
//                L E     R E Q U E T E U R                 //
// ======================================================== //

// Retourne les données demandées 
function getRequete($saison_id, $joueur_id, $equipe)
{

  $sql="";
  if ($saison_id!="-1" && $joueur_id!="-1")
  {
     // Critères : Saison et Joueur 
     $sql = "SELECT sa.nom sanom, jo.joueur_id joid, jo.nom jonom, je.jeu_id jeid, je.titre jetitre,
          equipe1d, equipe1v, equipe2d, equipe2v, equipe3d, equipe3v, equipe4d, 
          equipe4v, equipe5d, equipe5v, equipe6d, equipe6v, equipe7d, equipe7v,
          equipe8d, equipe8v, equipe9d, equipe9v, equipe10d, equipe10v, equipe11d, 
          equipe11v, equipe12d, equipe12v, equipe13d, equipe13v, equipe14d,
          equipe14v, equipe15d, equipe15v,
          resultat1, resultat2, resultat3, resultat4, resultat5, resultat6, resultat7, 
          resultat8, resultat9, resultat10, resultat11, resultat12, resultat13, resultat14, resultat15, 
          pronostic1, pronostic2, pronostic3, pronostic4, pronostic5, pronostic6, pronostic7, 
          pronostic8, pronostic9, pronostic10, pronostic11, pronostic12, pronostic13, pronostic14, pronostic15  
          FROM `saison` sa,`joueur` jo,`jeu` je,`resultat` re,`pronostic` pr 
          WHERE sa.saison_id='$saison_id'  
          and re.jeu_id=je.jeu_id
          and pr.jeu_id=je.jeu_id
          and jo.joueur_id='$joueur_id' 
          and jo.joueur_id=pr.joueur_id
          and sa.saison_id=je.saison_id";
  }
  elseif ($saison_id!="-1" && $joueur_id=="-1")
  {
    // Critère : Saison
    $sql = "SELECT sa.nom sanom, jo.joueur_id joid, jo.nom jonom, je.jeu_id jeid, je.titre jetitre, 
          equipe1d, equipe1v, equipe2d, equipe2v, equipe3d, equipe3v, equipe4d, 
          equipe4v, equipe5d, equipe5v, equipe6d, equipe6v, equipe7d, equipe7v,
          equipe8d, equipe8v, equipe9d, equipe9v, equipe10d, equipe10v, equipe11d, 
          equipe11v, equipe12d, equipe12v, equipe13d, equipe13v, equipe14d,
          equipe14v, equipe15d, equipe15v,
          resultat1, resultat2, resultat3, resultat4, resultat5, resultat6, resultat7, 
          resultat8, resultat9, resultat10, resultat11, resultat12, resultat13, resultat14, resultat15, 
          pronostic1, pronostic2, pronostic3, pronostic4, pronostic5, pronostic6, pronostic7, 
          pronostic8, pronostic9, pronostic10, pronostic11, pronostic12, pronostic13, pronostic14, pronostic15   
          FROM `saison` sa,`joueur` jo,`jeu` je,`resultat` re,`pronostic` pr  
          WHERE sa.saison_id='$saison_id'  
          and re.jeu_id=je.jeu_id
          and pr.jeu_id=je.jeu_id 
          and jo.joueur_id=pr.joueur_id
          and sa.saison_id=je.saison_id";
  }
  elseif ($saison_id=="-1" && $joueur_id!="-1")
  {
      // Critère : Joueur
      $sql = "SELECT sa.nom sanom, jo.joueur_id joid, jo.nom jonom, je.jeu_id jeid, je.titre jetitre, 
          equipe1d, equipe1v, equipe2d, equipe2v, equipe3d, equipe3v, equipe4d, 
          equipe4v, equipe5d, equipe5v, equipe6d, equipe6v, equipe7d, equipe7v,
          equipe8d, equipe8v, equipe9d, equipe9v, equipe10d, equipe10v, equipe11d, 
          equipe11v, equipe12d, equipe12v, equipe13d, equipe13v, equipe14d,
          equipe14v, equipe15d, equipe15v,
          resultat1, resultat2, resultat3, resultat4, resultat5, resultat6, resultat7, 
          resultat8, resultat9, resultat10, resultat11, resultat12, resultat13, resultat14, resultat15, 
          pronostic1, pronostic2, pronostic3, pronostic4, pronostic5, pronostic6, pronostic7, 
          pronostic8, pronostic9, pronostic10, pronostic11, pronostic12, pronostic13, pronostic14, pronostic15   
          FROM `saison` sa,`joueur` jo,`jeu` je,`resultat` re,`pronostic` pr  
          WHERE jo.joueur_id='$joueur_id' 
          and re.jeu_id=je.jeu_id
          and pr.jeu_id=je.jeu_id 
          and jo.joueur_id=pr.joueur_id
          and sa.saison_id=je.saison_id";
  }
  else
  {
      // Critère : Aucun
      $sql = "SELECT sa.nom sanom, jo.joueur_id joid, jo.nom jonom, je.jeu_id jeid, je.titre jetitre, 
          equipe1d, equipe1v, equipe2d, equipe2v, equipe3d, equipe3v, equipe4d, 
          equipe4v, equipe5d, equipe5v, equipe6d, equipe6v, equipe7d, equipe7v,
          equipe8d, equipe8v, equipe9d, equipe9v, equipe10d, equipe10v, equipe11d, 
          equipe11v, equipe12d, equipe12v, equipe13d, equipe13v, equipe14d,
          equipe14v, equipe15d, equipe15v,
          resultat1, resultat2, resultat3, resultat4, resultat5, resultat6, resultat7, 
          resultat8, resultat9, resultat10, resultat11, resultat12, resultat13, resultat14, resultat15, 
          pronostic1, pronostic2, pronostic3, pronostic4, pronostic5, pronostic6, pronostic7, 
          pronostic8, pronostic9, pronostic10, pronostic11, pronostic12, pronostic13, pronostic14, pronostic15   
          FROM `saison` sa,`joueur` jo,`jeu` je,`resultat` re,`pronostic` pr  
          WHERE re.jeu_id=je.jeu_id
          and pr.jeu_id=je.jeu_id 
          and jo.joueur_id=pr.joueur_id
          and sa.saison_id=je.saison_id";
  }

  // Critère equipe
  $critereEquipe="";
  if (trim($equipe) != "" && trim($sql) != "")
  {
    $equipe=strtoupper($equipe);
    $critereEquipe=" AND (UPPER(equipe1d) LIKE '%{$equipe}%' Or 
                     UPPER(equipe1v) LIKE '%{$equipe}%' Or 
                     UPPER(equipe2d) LIKE '%{$equipe}%' Or 
                     UPPER(equipe2v) LIKE '%{$equipe}%' Or 
                     UPPER(equipe3d) LIKE '%{$equipe}%' Or 
                     UPPER(equipe3v) LIKE '%{$equipe}%' Or 
                     UPPER(equipe4d) LIKE '%{$equipe}%' Or 
                     UPPER(equipe4v) LIKE '%{$equipe}%' Or 
                     UPPER(equipe5d) LIKE '%{$equipe}%' Or 
                     UPPER(equipe5v) LIKE '%{$equipe}%' Or 
                     UPPER(equipe6d) LIKE '%{$equipe}%' Or 
                     UPPER(equipe6v) LIKE '%{$equipe}%' Or 
                     UPPER(equipe7d) LIKE '%{$equipe}%' Or 
                     UPPER(equipe7v) LIKE '%{$equipe}%' Or 
                     UPPER(equipe8d) LIKE '%{$equipe}%' Or 
                     UPPER(equipe8v) LIKE '%{$equipe}%' Or 
                     UPPER(equipe9d) LIKE '%{$equipe}%' Or 
                     UPPER(equipe9v) LIKE '%{$equipe}%' Or 
                     UPPER(equipe10d) LIKE '%{$equipe}%' Or 
                     UPPER(equipe10v) LIKE '%{$equipe}%' Or 
                     UPPER(equipe11d) LIKE '%{$equipe}%' Or 
                     UPPER(equipe11v) LIKE '%{$equipe}%' Or 
                     UPPER(equipe12d) LIKE '%{$equipe}%' Or 
                     UPPER(equipe12v) LIKE '%{$equipe}%' Or 
                     UPPER(equipe13d) LIKE '%{$equipe}%' Or 
                     UPPER(equipe13v) LIKE '%{$equipe}%' Or 
                     UPPER(equipe14d) LIKE '%{$equipe}%' Or 
                     UPPER(equipe14v) LIKE '%{$equipe}%' Or 
                     UPPER(equipe15d) LIKE '%{$equipe}%' Or 
                     UPPER(equipe15v) LIKE '%{$equipe}%' 
                     )";
    $sql= $sql . $critereEquipe;
  }

  if (trim($sql) != "")
  {
    $sql= $sql . " order by sa.saison_id, jo.joueur_id, je.jeu_id";
  }
  //echo $sql;

  $enreg = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
  {
      // Lecture de ces données
      $cpt=0;
      while( $e = mysql_fetch_array($result) )
      {         
          if ($e)
          {
            $enreg[$cpt]=$e;
            $cpt++;
          } 
      }
      return $enreg;
  }
  return "";

}


// ======================================================== //
//          L E S    U T I L I S A T E U R S                //
// ======================================================== //

// Retourne l'utilisateur pour un login et password donnés
function getUtilisateur($pseudo, $motpasse)
{
    // Requete pour retrouver l'ID du dernier jeu
    $sql = "select * from joueur where `actif` = 'O' and `pseudo`='$pseudo' and `mdp`='$motpasse'";

    // Exécution de la requête 
    if($result = mysql_query($sql))
    {
          // Nombre de ligne trouvée
          $nbenreg = mysql_num_rows($result);
          if ($nbenreg==1)
          {
                $joueur = mysql_fetch_array($result);
                if ($joueur)
                {
                      return $joueur;
                }
          }
    }
    return "";
}

// Retourne l'utilisateur pour un id donné
function getUtilisateurID($joueur_id)
{
    // Requete pour retrouver l'ID du dernier jeu
    $sql = "select * from joueur where `actif` = 'O' and joueur_id='$joueur_id'";

    // Exécution de la requête 
    if($result = mysql_query($sql))
    {
          // Nombre de ligne trouvée
          $nbenreg = mysql_num_rows($result);
          if ($nbenreg==1)
          {
                $joueur = mysql_fetch_array($result);
                if ($joueur)
                {
                      return $joueur;
                }
          }
    }
    return "";
}

function setUtilisateurStat($joueur_id,$stat)
{
    $requete= "update joueur set stat='$stat' where joueur_id='$joueur_id'"; 
    $result = mysql_query($requete);
    if (mysql_errno() == 0) 
    {
        return true;
    }
    return false;
}


function setCnxUtilisateur($joueur_id)
{
      // Enregistrement de la date de dernière connexion
    $dateDerCnx= date("Y/m/d H:i:s"); 
    // Enregistrement du navigateur utilisé 
    $Browser=getBrowser();
    $requete= "update joueur set der_cnx='$dateDerCnx', der_navigateur='$Browser' where joueur_id='$joueur_id'"; 
    $result = mysql_query($requete);
    return "";
}

function setUtilisateurChatActif($joueur_id)
{
    // Essaye d'insérer un avis de connexion de l'utilisateur
    if (!insertConnexionWebChat($joueur_id, 'C'))    return false;
    $requete= "update joueur set log_chat='O' where joueur_id='$joueur_id'"; 
    $result = mysql_query($requete);
    if (mysql_errno() == 0) 
    {
        return true;
    }
    return false;
}

function setUtilisateurChatInactif($joueur_id)
{
    // Essaye d'insérer un avis de déconnexion de l'utilisateur
    if (!insertDeconnexionWebChat($joueur_id, 'D'))  return false;
    $requete= "update joueur set log_chat='N' where joueur_id='$joueur_id'"; 
    $result = mysql_query($requete);
    if (mysql_errno() == 0) 
    {
        return true;
    }
    return false;
}


// Retourne les utilisateurs actifs sur le chat
function getUtilisateursActifsChat()
{
    // Requete pour retrouver l'ID du dernier jeu
    $sql = "select joueur_id, nom from joueur where `log_chat` = 'O' order by nom";
  
    $utilisateurs = array();
    // Exécution de la requête 
    if($result = mysql_query($sql))
    {

        // Lecture de ce jeu
        $cpt=0;
        while( $utilisateur = mysql_fetch_assoc($result) )
        {         
            if ($utilisateur)
            {
              $utilisateurs[$cpt]=$utilisateur;
              $cpt++;
            } 
        }
        return $utilisateurs;
    }
    return "";
}


// ======================================================== //
//               L E S    S A I S O N S                     //
// ======================================================== //

// Retourne la saison courante
function getSaison($saison_id)
{
  // Requete pour retrouver l'ID du dernier jeu
  $sql = "select * from saison where saison_id=$saison_id";
  
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{
	    //echo "==================>".mysql_num_rows($result);
  	 // Lecture de ce jeu
   	 $saison = mysql_fetch_array($result);
     if ($saison)
   	 {
   	    return $saison;
   	 }
  }
  return "";
}

// Retourne la saison courante
function getSaisonCourante()
{
  // Requete pour retrouver l'ID de la dernière saison
  $sql = "select * from saison order by saison_id desc";
  
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{
  	 // Lecture de ce jeu
   	 $saison = mysql_fetch_array($result);
     if ($saison)
   	 {
   	    return $saison;
   	 }
  }
  return "";
}

// Retourne l'ID de la saison courante
function getIDSaisonCourante()
{
 	 $saison = getSaisonCourante();
   if ($saison)
   {
        // Lecture de l'id du jeu
        $saison_id         = $saison["saison_id"];
        return $saison_id;
   }
  return "";
}


// Retourne toutes les saisons
function getListeSaisons()
{
  // Requete pour retrouver toutes les saisons dans l'ordre d'insertion en base
  $sql = "select * from saison order by saison_id asc";
  
  $saisons = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{

  	  // Lecture de ce jeu
   	  $cpt=0;
   	  while( $saison = mysql_fetch_array($result) )
 			{        	
 			    if ($saison)
 			    {
 			      $saisons[$cpt]=$saison;
            $cpt++;
          } 
 			}
 			return $saisons;
  }
  return "";
}

function setMiseAJourSaison($saison_id,$nom,$commentaire,$operation)
{
    $nom=addslashes($nom);
    $commentaire=addslashes($commentaire);
    if($operation=="C")
    {
       	// Ajout de cette saison
    		$requete=	"insert into saison ( nom, commentaire) 
                  values ('$nom',  '$commentaire')";
    }
    else if ($operation=="M")
    {
       	// MAJ de cette saison
  	    $requete=	"update saison set nom='$nom', 
                  commentaire='$commentaire'
                  where saison_id='$saison_id'";
    }
    else 
      return "";
      
   $result = mysql_query($requete);
   
   if (mysql_errno() == 0) 
   {
      if($operation=="C")
      {
          return getIDSaisonCourante();
      }
      else
      {
          return $saison_id;
      }
   }
   return "";
}




// ======================================================== //
//                  L E S    C A I S S E S                  //
// ======================================================== //


// Retourne une caisse donnée
function getCaisse($caisse_id)
{
  // Requete pour retrouver l'ID du dernier jeu
  $sql = "select * from caisse where caisse_id=$caisse_id";
  
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{
  	 // Lecture de ce jeu
   	 $caisse = mysql_fetch_array($result);
     if ($caisse)
   	 {
   	    return $caisse;
   	 }
  }
  return "";
}


// Maj de la caisse : En ajout, sur la dernière saison.
function setMiseAJourCaisse($caisse_id,$caisse_libelle,$caisse_date,$caisse_somme_debit,$caisse_somme_credit,$operation)
{
    if($operation=="C")
    {
        // Pour l'ajout, on recherche l'id de la dernière saison
        $saison_id = getIDSaisonCourante();
        
       	// Ajout de ce joueur
    		$requete=	"insert into caisse ( caisse_libelle, saison_id, caisse_date, caisse_somme_debit, caisse_somme_credit) 
                  values ('$caisse_libelle', '$saison_id', '$caisse_date', '$caisse_somme_debit', '$caisse_somme_credit')";
    }
    else if ($operation=="M")
    {
       	// MAJ de ce joueur
  	    $requete=	"update caisse set caisse_libelle='$caisse_libelle', 
                  caisse_date='$caisse_date',
                  caisse_somme_debit='$caisse_somme_debit',
                  caisse_somme_credit='$caisse_somme_credit'
                  where caisse_id='$caisse_id'";
    }
    else 
      return "";

   $result = mysql_query($requete);
	 //echo "==>".mysql_affected_rows()."<==>".mysql_error()."<==";
   //echo "{".$requete."}";
   
   if (mysql_errno() == 0) 
   {
      if($operation=="C")
      {
          return getIDDerniereCaisse();
      }
      else
      {
          return $caisse_id;
      }
   }
   return "";
}
 
 
// Retourne la derniere caisse saisie en base
function getDerniereCaisse()
{
  // Requete pour retrouver l'ID du dernier jeu
  $sql = "select * from caisse order by caisse_id desc";
  
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{
  	 // Lecture de ce jeu
   	 $caisse = mysql_fetch_array($result);
     if ($caisse)
   	 {
   	    return $caisse;
   	 }
  }
  return "";
}
 
 
// Retourne l'ID de la dernière caisse
function getIDDerniereCaisse()
{
 	 $caisse = getDerniereCaisse();
   if ($caisse)
   {
        // Lecture de l'id du jeu
        $caisse_id         = $caisse["caisse_id"];
        return $caisse_id;
   }
  return "";
}

  
// Retourne toutes les opérations de la caisse pour la saison en-cours
function getListeOperationsCaisse()
{
  // Recherche de la saison courante
  $saison_id = getIDSaisonCourante();
  return getListeOperationsCaisseSaison($saison_id);
}


// Retourne toutes les opérations de la caisse pour une saison
function getListeOperationsCaisseSaison($idsaison)
{
  // Requete pour retrouver tous les jeux de la saison courante dans l'ordre d'insertion en base
  $sql = "select * from caisse where saison_id=$idsaison order by caisse_date asc";
  
  $caisses = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
  {
      // Lecture de ce jeu
      $cpt=0;
      while( $caisse = mysql_fetch_array($result) )
      {
          if ($caisse)
          {
            $caisses[$cpt]=$caisse;
            $cpt++;
          } 
      }
      return $caisses;
  }
  return "";
}




// Retourne les soldes des caisses par saison
function getCaisseSaison()
{
    // Requete pour retrouver tous les jeux de la saison courante dans l'ordre d'insertion en base
  $sql = " select c.saison_id id, nom, sum(caisse_somme_credit) - sum(caisse_somme_debit) total from caisse c, saison s 
          where c.saison_id=s.saison_id 
          group by c.saison_id";
  
  $caisses = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
  {
      // Lecture de ce jeu
      $cpt=0;
      while( $caisse = mysql_fetch_array($result) )
      {
          if ($caisse)
          {
            $caisses[$cpt]=$caisse;
            $cpt++;
          } 
      }
      return $caisses;
  }
  return "";
}



// ======================================================== //
//                   L E S    G A I N S                     //
// ======================================================== //


// Retourne un gain donn‚
function getGain($gain_id)
{
  // Requete pour retrouver l'ID du dernier jeu
  $sql = "select * from gain where gain_id=$gain_id";
  
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{
  	 // Lecture de ce jeu
   	 $gain = mysql_fetch_array($result);
     if ($gain)
   	 {
   	    return $gain;
   	 }
  }
  return "";
}

// Retourne tous les jeux de la saison courante 
function getListeMeilleursGains($saison_id)
{
  // Recherche de la saison courante
  //$saison_id = getIDSaisonCourante();
  
  // Requete pour retrouver tous les jeux de la saison courante dans l'ordre d'insertion en base
  $sql = "select joueur_id, sum(somme) as total from gain where saison_id=$saison_id group by joueur_id order by total desc";
  
  $gains = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{
  	  // Lecture de ce jeu
   	  $cpt=0;
   	  while( $gain = mysql_fetch_array($result) )
 			{
 			    if ($gain)
 			    {
 			      $gains[$cpt]=$gain;
            $cpt++;
          } 
 			}
 			return $gains;
  }
  return "";
}

// Retourne tous les jeux de la saison courante 
function getListeGains()
{
  // Recherche de la saison courante
  $saison_id = getIDSaisonCourante();
  
  // Requete pour retrouver tous les jeux de la saison courante dans l'ordre d'insertion en base
  $sql = "select * from gain where saison_id=$saison_id order by gain_id asc";
  
  $gains = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{
  	  // Lecture de ce jeu
   	  $cpt=0;
   	  while( $gain = mysql_fetch_array($result) )
 			{
 			    if ($gain)
 			    {
 			      $gains[$cpt]=$gain;
            $cpt++;
          } 
 			}
 			return $gains;
  }
  return "";
}


// Retourne le dernier gain saisi en base
function getDernierGain()
{
  // Requete pour retrouver l'ID du dernier jeu
  $sql = "select * from gain order by gain_id desc";
  
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{
  	 // Lecture de ce jeu
   	 $gain = mysql_fetch_array($result);
     if ($gain)
   	 {
   	    return $gain;
   	 }
  }
  return "";
}

// Retourne l'ID du dernier gain
function getIDDernierGain()
{
 	 $gain = getDernierGain();
   if ($gain)
   {
        // Lecture de l'id du jeu
        $gain_id         = $gain["gain_id"];
        return $gain_id;
   }
  return "";
}

function setMiseAJourGain($gain_id,$joueur_id,$date,$somme,$operation)
{
    if($operation=="C")
    {
        // Pour l'ajout, on recherche l'id de la dernière saison
        $saison_id = getIDSaisonCourante();
        
       	// Ajout de ce joueur
    		$requete=	"insert into gain ( joueur_id,saison_id, date, somme) 
                  values ('$joueur_id', '$saison_id', '$date', '$somme')";
    }
    else if ($operation=="M")
    {
       	// MAJ de ce joueur
  	    $requete=	"update gain set joueur_id='$joueur_id', 
                  date='$date',
                  somme='$somme'
                  where gain_id='$gain_id'";
    }
    else 
      return "";

   $result = mysql_query($requete);
	 //echo "==>".mysql_affected_rows()."<==>".mysql_error()."<==";
   //echo "{".$requete."}";
   
   if (mysql_errno() == 0) 
   {
      if($operation=="C")
      {
          return getIDDernierGain();
      }
      else
      {
          return $gain_id;
      }
   }
   return "";
}
  


// ======================================================== //
//      L E S   A P P E L S   D E   F O N D S               //
// ======================================================== //


// Retourne un appel donné
function getAppel($appel_id)
{
  // Requete pour retrouver l'ID du dernier jeu
  $sql = "select * from appeldefonds where appel_id=$appel_id";
  
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{
  	 // Lecture de ce jeu
   	 $appel = mysql_fetch_array($result);
     if ($appel)
   	 {
   	    return $appel;
   	 }
  }
  return "";
}

// Retourne tous les appels de fond 
function getListeAppelsDeFonds()
{  
  // Requete pour retrouver tous les jeux de la saison courante dans l'ordre d'insertion en base
  $sql = "select * from appeldefonds order by appel_id asc";
  
  $appels = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{
  	  // Lecture de ce jeu
   	  $cpt=0;
   	  while( $appel = mysql_fetch_array($result) )
 			{
 			    if ($appel)
 			    {
 			      $appels[$cpt]=$appel;
            $cpt++;
          } 
 			}
 			return $appels;
  }
  return "";
}


// Retourne le dernier appel de fonds saisi en base
function getDernierAppel()
{
  // Requete pour retrouver le dernier appel de fonds
  $sql = "select * from appeldefonds order by appel_id desc";
  
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{
  	 // Lecture de ce jeu
   	 $appel = mysql_fetch_array($result);
     if ($appel)
   	 {
   	    return $appel;
   	 }
  }
  return "";
}

// Retourne l'ID de la saison courante
function getIDDernierAppel()
{
 	 $appel = getDernierAppel();
   if ($appel)
   {
        // Lecture de l'id du jeu
        $appel_id         = $appel["appel_id"];
        return $appel_id;
   }
  return "";
}

function setMiseAJourAppel($appel_id,$libelle,$date, $operation)
{
    if($operation=="C")
    {
        
       	// Ajout de cet appel de fonds
    		$requete=	"insert into appeldefonds (libelle, date) 
                  values ('$libelle', '$date')";
    }
    else if ($operation=="M")
    {
       	// MAJ de ceT appel de fonds
  	    $requete=	"update appeldefonds set date='$date', 
                  libelle='$libelle'
                  where appel_id='$appel_id'";
    }
    else 
      return "";

   $result = mysql_query($requete);
	 //echo "==>".mysql_affected_rows()."<==>".mysql_error()."<==";
   //echo "{".$requete."}";
   
   if (mysql_errno() == 0) 
   {
      if($operation=="C")
      {
          return getIDDernierAppel();
      }
      else
      {
          return $appel_id;
      }
   }
   return "";
}
    
function setMiseAJourAppelJoueurs($appel_id,$paiements)
{
    // Suppression des anciennes valeurs
    $requete=	"delete from appeljoueurs where appel_id='$appel_id'";
    $result = mysql_query($requete);
    if (mysql_errno() == 0) 
    {
          if ($paiements) 
          {
              for($i=0;$i<count($paiements);$i++)
              {
                  $retour = setMiseAJourAppelUnJoueur($appel_id, $paiements[$i]);
                  if ($retour == "")                return "";
              }
          }
          return $appel_id;
            
    }
    return "";
}

    
function setMiseAJourAppelUnJoueur($appel_id, $joueur_id)
{
    if (!$appel_id)   return "";
    if (!$joueur_id)  return "";
    // Ajout de ce joueur
    $requete=	"insert into appeljoueurs (appel_id, joueur_id) values ('$appel_id', '$joueur_id')";
    
    $result = mysql_query($requete);
   
    if (mysql_errno() == 0) 
    {
        return $appel_id;
    }
    return "";
}


// Retourne tous les identifiants des joueurs qui ont répondu à un appel de fond 
function getListeAppelJoueurs($appel_id)
{
  // Requete pour retrouver tous les jeux de la saison courante dans l'ordre d'insertion en base
  $sql = "select joueur_id from appeljoueurs where appel_id=$appel_id order by joueur_id asc";
  
  $appelJoueurs = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{
  	  // Lecture de ce jeu
   	  $cpt=0;
   	  while( $appelJoueur = mysql_fetch_array($result) )
 			{
 			    if ($appelJoueur)
 			    {
 			      $appelJoueurs[$cpt]=$appelJoueur;
            $cpt++;
          } 
 			}
 			return $appelJoueurs;
  }
  return "";
}    

// Retourne tous les identifiants des joueurs qui onté répondu à un appel de fond 
function getListeAppelJoueursRetardataires($appel_id)
{
  // Requete pour retrouver tous les jeux de la saison courante dans l'ordre d'insertion en base
  $sql = "select * from joueur where actif='O' and joueur_id not in (select joueur_id from appeljoueurs where appel_id = $appel_id)";
  
  $joueurs = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{
  	  // Lecture de ce jeu
   	  $cpt=0;
   	  while( $joueur = mysql_fetch_array($result) )
 			{
 			    if ($joueur)
 			    {
 			      $joueurs[$cpt]=$joueur;
            $cpt++;
          } 
 			}
 			return $joueurs;
  }
  return "";

}    



// Retourne vrai/faux selon qu'un joueur a payé sa cotisation pour un appel de fond
function isAppelJoueurs($joueur_id, $appelJoueurs)
{                  
    if (!$joueur_id)                  return false;                 
    if (!$appelJoueurs)               return false;                 
    if (sizeof($appelJoueurs)<1)    return false; 
    for($i=0;$i<sizeof($appelJoueurs);$i++)
    {
        if ($joueur_id == $appelJoueurs[$i]["joueur_id"])   {
          return true;
        } 
    }
    return false;
}

  
  
  
// ======================================================== //
//                   L E S    J E U X                       //
// ======================================================== //

// Retourne le jeu courant
function getJeu($jeu_id)
{
  // Requete pour retrouver l'ID du dernier jeu
  $sql = "select * from jeu where jeu_id=$jeu_id";
  
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{
	    //echo "==================>".mysql_num_rows($result);
  	 // Lecture de ce jeu
   	 $jeu = mysql_fetch_array($result);
     if ($jeu)
   	 {
   	    return $jeu;
   	 }
  }
  return "";
}

// Retourne le jeu courant
function getJeuCourant()
{
  // Recherche de la saison courante
  $saison_id = getIDSaisonCourante();
  

  // Requete pour retrouver l'ID du dernier jeu
  $sql = "select * from jeu where saison_id=$saison_id order by jeu_id desc";
  
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{
  	 // Lecture de ce jeu
   	 $jeu = mysql_fetch_array($result);
     if ($jeu)
   	 {
   	    return $jeu;
   	 }
  }
  return "";
}

// Retourne l'ID du jeu courant
function getIDJeuCourant()
{
   $jeu = getJeuCourant();
   if ($jeu)
   {
        // Lecture de l'id du jeu
        $jeu_id         = $jeu["jeu_id"];
        return $jeu_id;
   }
  return "";
}

// Retourne l'idSite d'un jeu
function getIdSiteJeu($jeu_id)
{
  // Requete pour retrouver l'ID du dernier jeu
  $sql = "select idSite from jeu where jeu_id=$jeu_id";
  
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{
	    //echo "==================>".mysql_num_rows($result);
  	 // Lecture de cet id
   	 $id = mysql_fetch_array($result);
     if ($id)
   	 {
   	    return $id['idSite'];
   	 }
  }
  return 0;
}

function getNbMatchsDeCeJeu($jeu)
{
   // Jeu à 14 ou 15 matchs ?
  $nbMatchsDeCeJeu  = 7;
  $equiped          = $jeu["equipe14d"];
  $equipev          = $jeu["equipe14v"];
  if ($equiped && $equipev)   $nbMatchsDeCeJeu  = 14;
  $equiped          = $jeu["equipe15d"];
  $equipev          = $jeu["equipe15v"];           
  if ($equiped && $equipev)   $nbMatchsDeCeJeu  = 15;
  return $nbMatchsDeCeJeu;
}


// Retourne tous les jeux de la saison courante 
function getListeJeux()
{
  // Recherche de la saison courante
  $saison_id = getIDSaisonCourante();
  
  // Recherche des jeux
  return getListeJeuxSaison($saison_id);
}



// Retourne tous les jeux de la saison courante 
function getListeJeuxSaison($saison_id)
{
  // Requete pour retrouver tous les jeux de la saison courante dans l'ordre d'insertion en base
  $sql = "select * from jeu where saison_id=$saison_id order by jeu_id asc";
  
  $jeux = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{
  	  // Lecture de ce jeu
   	  $cpt=0;
   	  while( $jeu = mysql_fetch_array($result) )
 			{
 			    if ($jeu)
 			    {
 			      $jeux[$cpt]=$jeu;
            $cpt++;
          } 
 			}
 			return $jeux;
  }
  return "";

}



// Retourne tous les jeux de la saison courante 
function getListeTousJeux()
{
  // Requete pour retrouver tous les jeux de la saison courante dans l'ordre d'insertion en base
  $sql = "select * from jeu order by jeu_id asc";
  
  $jeux = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{
  	  // Lecture de ce jeu
   	  $cpt=0;
   	  while( $jeu = mysql_fetch_array($result) )
 			{
 			    if ($jeu)
 			    {
 			      $jeux[$cpt]=$jeu;
            $cpt++;
          } 
 			}
 			return $jeux;
  }
  return "";

}

function setBlocageJeu($jeu_id,$bloque)
{
    // MAJ de ce jeu
    $requete= "update jeu set bloque='$bloque' where jeu_id='$jeu_id'";
    $result = mysql_query($requete);
}


// MAJ d'un jeu 
function setMiseAJourJeu($jeu_id,$titre,$bloque,$invisible,$commentaire,$equipe1d,$equipe1v,$equipe2d,$equipe2v,
                    $equipe3d,$equipe3v,$equipe4d,$equipe4v,$equipe5d,$equipe5v,$equipe6d,$equipe6v,$equipe7d,
                    $equipe7v,$equipe8d,$equipe8v,$equipe9d,$equipe9v,$equipe10d,$equipe10v,$equipe11d,$equipe11v,
                    $equipe12d,$equipe12v,$equipe13d,$equipe13v,$equipe14d,$equipe14v,$equipe15d,$equipe15v,$operation,$idSite)
{
    $titre=addslashes($titre);
    $commentaire=addslashes($commentaire);
    $$bloque=$bloque;
	$$invisible=$invisible;
    $equipe1d=addslashes($equipe1d);
    $equipe1v=addslashes($equipe1v);
    $equipe2d=addslashes($equipe2d);
    $equipe2v=addslashes($equipe2v);
    $equipe3d=addslashes($equipe3d);
    $equipe3v=addslashes($equipe3v);
    $equipe4d=addslashes($equipe4d);
    $equipe4v=addslashes($equipe4v);
    $equipe5d=addslashes($equipe5d);
    $equipe5v=addslashes($equipe5v);
    $equipe6d=addslashes($equipe6d);
    $equipe6v=addslashes($equipe6v);
    $equipe7d=addslashes($equipe7d);
    $equipe7v=addslashes($equipe7v);
    $equipe8d=addslashes($equipe8d);
    $equipe8v=addslashes($equipe8v);
    $equipe9d=addslashes($equipe9d);
    $equipe9v=addslashes($equipe9v);
    $equipe10d=addslashes($equipe10d);
    $equipe10v=addslashes($equipe10v);
    $equipe11d=addslashes($equipe11d);
    $equipe11v=addslashes($equipe11v);
    $equipe12d=addslashes($equipe12d);
    $equipe12v=addslashes($equipe12v);
    $equipe13d=addslashes($equipe13d);
    $equipe13v=addslashes($equipe13v);
    $equipe14d=addslashes($equipe14d);
    $equipe14v=addslashes($equipe14v);
    $equipe15d=addslashes($equipe15d);
    $equipe15v=addslashes($equipe15v);
    if($operation=="C")
    {
        // Un jeu est automatiquement associé à la dernière saison
        $saison_id = getIDSaisonCourante();
       	// Ajout de ce joueur
    		$requete=	"insert into jeu ( saison_id,titre,bloque,invisible,commentaire,equipe1d,equipe1v,equipe2d,equipe2v,
                    equipe3d,equipe3v,equipe4d,equipe4v,equipe5d,equipe5v,equipe6d,equipe6v,equipe7d,
                    equipe7v,equipe8d,equipe8v,equipe9d,equipe9v,equipe10d,equipe10v,equipe11d,equipe11v,
                    equipe12d,equipe12v,equipe13d,equipe13v,equipe14d,equipe14v,equipe15d,equipe15v,idSite) 
                  values ('$saison_id','$titre','$bloque','$invisible','$commentaire','$equipe1d','$equipe1v','$equipe2d','$equipe2v',
                    '$equipe3d','$equipe3v','$equipe4d','$equipe4v','$equipe5d','$equipe5v','$equipe6d','$equipe6v','$equipe7d',
                    '$equipe7v','$equipe8d','$equipe8v','$equipe9d','$equipe9v','$equipe10d','$equipe10v','$equipe11d','$equipe11v',
                    '$equipe12d','$equipe12v','$equipe13d','$equipe13v','$equipe14d','$equipe14v','$equipe15d','$equipe15v','$idSite')";
    }
    else if ($operation=="M")
    {
       	// MAJ de ce joueur
  	    $requete=	"update jeu set titre='$titre', 
                  commentaire='$commentaire',
                  bloque='$bloque',
				  invisible='$invisible',
                  equipe1d='$equipe1d',
                  equipe1v='$equipe1v',
                  equipe2d='$equipe2d',
                  equipe2v='$equipe2v',
                  equipe3d='$equipe3d',
                  equipe3v='$equipe3v',
                  equipe4d='$equipe4d',
                  equipe4v='$equipe4v',
                  equipe5d='$equipe5d',
                  equipe5v='$equipe5v',
                  equipe6d='$equipe6d',
                  equipe6v='$equipe6v',
                  equipe7d='$equipe7d',
                  equipe7v='$equipe7v',
                  equipe8d='$equipe8d',
                  equipe8v='$equipe8v',
                  equipe9d='$equipe9d',
                  equipe9v='$equipe9v',
                  equipe10d='$equipe10d',
                  equipe10v='$equipe10v',
                  equipe11d='$equipe11d',
                  equipe11v='$equipe11v',
                  equipe12d='$equipe12d',
                  equipe12v='$equipe12v',
                  equipe13d='$equipe13d',
                  equipe13v='$equipe13v',
                  equipe14d='$equipe14d',
                  equipe14v='$equipe14v',
                  equipe15d='$equipe15d',
                  equipe15v='$equipe15v',
				  idSite='$idSite'
                  where jeu_id='$jeu_id'";
    }
    else 
      return "";

   $result = mysql_query($requete);
	 //echo "==>".mysql_affected_rows()."<==>".mysql_error()."<==";
   //echo "{".$requete."}";
   
   if (mysql_errno() == 0) 
   {
      if($operation=="C")
      {
          return getIDJeuCourant();
      }
      else
      {
          return $jeu_id;
      }
   }
   return "";
}
                    

           
// ======================================================== //
//               L E S    J O U E U R S                     //
// ======================================================== //

// Retourne les informations sur un joueur
function getJoueur($joueur_id)
{
  // Requete pour retrouver les informations sur un joueur
  $sql = "select * from joueur where joueur_id=$joueur_id";
  
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{
  	 // Lecture de ce jeu
   	 $joueur = mysql_fetch_array($result);
     if ($joueur)
   	 {
   	    return $joueur;
   	 }
  }
  return "";
}


// Retourne tous les joueurs 
function getListeTousJoueurs()
{
  // Requete pour retrouver tous les joueurs dans l'ordre d'insertion en base
  $sql = "select * from joueur order by joueur_id asc";
  
  $joueurs = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{
  	  // Lecture de ce jeu
   	  $cpt=0;
   	  while( $joueur = mysql_fetch_array($result) )
 			{
 			    if ($joueur)
 			    {
 			      $joueurs[$cpt]=$joueur;
            $cpt++;
          } 
 			}
 			return $joueurs;
  }
  return "";
}


function getNomJoueurTab($listeJoueur, $id)
{
    for($i=0;$i<sizeof($listeJoueur);$i++)
    {
        $j=$listeJoueur[$i];
        // Lecture des propriétés du joueur
        $joueur_id = $j['joueur_id'];
        if ($joueur_id==$id)   return $j['nom'];
    }
    return ""; 
}


// Retourne tous les joueurs actifs
function getListeJoueurs()
{
  // Requete pour retrouver tous les joueurs dans l'ordre d'insertion en base
  $sql = "select * from joueur where actif = 'O' order by joueur_id asc";
  
  $joueurs = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{
  	  // Lecture de ce jeu
   	  $cpt=0;
   	  while( $joueur = mysql_fetch_array($result) )
 			{
 			    if ($joueur)
 			    {
 			      $joueurs[$cpt]=$joueur;
            $cpt++;
          } 
 			}
 			return $joueurs;
  }
  return "";
}

// Retourne le dernier joueur saisi en base
function getDernierJoueur()
{
  // Requete pour retrouver l'ID du dernier jeu
  $sql = "select * from joueur order by joueur_id desc";
  
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{
  	 // Lecture de ce jeu
   	 $joueur = mysql_fetch_array($result);
     if ($joueur)
   	 {
   	    return $joueur;
   	 }
  }
  return "";
}

// Retourne l'ID du dernier joueur saisi en base
function getIDDernierJoueur()
{
 	 $joueur = getDernierJoueur();
   if ($joueur)
   {
        // Lecture de l'id du jeu
        $joueur_id         = $joueur["joueur_id"];
        return $joueur_id;
   }
  return "";
}

// Retourne le nom d'un joueur
function getNomJoueur($joueur_id)
{
    $joueur=getJoueur($joueur_id);
   if ($joueur)
   {
        // Lecture de l'id du jeu
        $nom         = $joueur["nom"];
        return $nom;
   }
  return "";    
}

function setMiseAJourJoueur($joueur_id,$nom, $initiale,$pseudo, $mdp,$mail,$administrateur, $actif, $operation)
{
    if($operation=="C")
    {
       	// Ajout de ce joueur
      $requete= "insert into joueur ( nom, initiale, pseudo, mdp, mail, administrateur, actif, log_chat)
                  values ('$nom', '$initiale', '$pseudo', '$mdp', '$mail', '$administrateur', '$actif', 'O')";

    }
    else if ($operation=="M")
    {
       	// MAJ de ce joueur
  	    $requete=	"update joueur set nom='$nom', 
                  initiale='$initiale',
                  pseudo='$pseudo',
                  mdp='$mdp',
                  mail='$mail',
                  administrateur='$administrateur',
                  actif='$actif'
                  where joueur_id='$joueur_id'";
    }
    else 
      return "";

   $result = mysql_query($requete);
	 //echo "==>".mysql_affected_rows()."<==>".mysql_error()."<==";
  //echo "{".$requete."}";
   
   if (mysql_errno() == 0) 
   {
      if($operation=="C")
      {
          return getIDDernierJoueur();
      }
      else
      {
          return $joueur_id;
      }
   }
   return "";
}



// ======================================================== //
//          L E S    S T A T I S T I Q U E S                //
// ======================================================== //

// classement des joueurs ayant pris le plus de risque au jeu à 7 (sans flash) en fonction de l'indice de gain.
function getStatIndiceGain7Joueurs($saison_id)
{
  // Requete pour retrouver tous les joueurs dans l'ordre d'insertion en base
  $sql = "SELECT joueur.joueur_id jid, nom,  (select count(*) from pronostic p left join jeu on p.jeu_id=jeu.jeu_id where p.IndiceGain7 <> 0.00 and p.flash = 0 and p.joueur_id=joueur.joueur_id and jeu.saison_id='$saison_id') 'nbindice', format(sum(pronostic.IndiceGain7)/(select count(*) from pronostic p left join jeu on p.jeu_id=jeu.jeu_id where p.IndiceGain7 <> 0.00 and p.flash = 0 and p.joueur_id=joueur.joueur_id and jeu.saison_id='$saison_id'),2) 'moyenne'
 FROM pronostic left join jeu on pronostic.jeu_id=jeu.jeu_id left join joueur on pronostic.joueur_id=joueur.joueur_id where jeu.saison_id='$saison_id' and flash = 0 and pronostic.IndiceGain7 <> 0 group by joueur.joueur_id order by moyenne desc, nom";
  
  $listes = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
  {
      // Lecture de ce jeu
      $cpt=0;
      while( $element = mysql_fetch_array($result) )
      {
          if ($element)
          {
            $listes[$cpt]=$element;
            $cpt++;
          } 
      }
      return $listes;
  }
  return "";
}


// classement des joueurs ayant pris le plus de risque au jeu à 15 (sans flash) en fonction de l'indice de gain.
function getStatIndiceGain15Joueurs($saison_id)
{
  // Requete pour retrouver tous les joueurs dans l'ordre d'insertion en base
 $sql = "SELECT joueur.joueur_id jid, nom,  (select count(*) from pronostic p left join jeu on p.jeu_id=jeu.jeu_id where p.IndiceGain15 <> 0.00 and p.flash = 0 and p.joueur_id=joueur.joueur_id and jeu.saison_id='$saison_id') 'nbindice', format(sum(pronostic.IndiceGain15)/(select count(*) from pronostic p left join jeu on p.jeu_id=jeu.jeu_id where p.IndiceGain15 <> 0.00 and p.flash = 0 and p.joueur_id=joueur.joueur_id and jeu.saison_id='$saison_id'),2) 'moyenne'
 FROM pronostic left join jeu on pronostic.jeu_id=jeu.jeu_id left join joueur on pronostic.joueur_id=joueur.joueur_id where jeu.saison_id='$saison_id' and flash = 0 and pronostic.IndiceGain15 <> 0 group by joueur.joueur_id order by moyenne desc, nom";
  
  $listes = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
  {
      // Lecture de ce jeu
      $cpt=0;
      while( $element = mysql_fetch_array($result) )
      {
          if ($element)
          {
            $listes[$cpt]=$element;
            $cpt++;
          } 
      }
      return $listes;
  }
  return "";
}

// classement des joueurs ayant pris le plus de risque tout jeu confondu (sans flash) en fonction de l'indice de gain.
function getStatIndiceGainJoueurs($saison_id)
{
  // Requete pour retrouver tous les joueurs dans l'ordre d'insertion en base
 $sql = "SELECT joueur.joueur_id jid, nom,  (select count(*) from pronostic p left join jeu on p.jeu_id=jeu.jeu_id where p.flash = 0 and p.joueur_id=joueur.joueur_id and jeu.saison_id='$saison_id') 'nbindice', format((sum(pronostic.IndiceGain15)+sum(pronostic.IndiceGain7))/(select count(*) from pronostic p left join jeu on p.jeu_id=jeu.jeu_id where p.flash = 0 and p.joueur_id=joueur.joueur_id and jeu.saison_id='$saison_id'),2) 'moyenne'
 FROM pronostic left join jeu on pronostic.jeu_id=jeu.jeu_id left join joueur on pronostic.joueur_id=joueur.joueur_id where jeu.saison_id='$saison_id' and flash = 0 group by joueur.joueur_id order by moyenne desc, nom";
  
  $listes = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
  {
      // Lecture de ce jeu
      $cpt=0;
      while( $element = mysql_fetch_array($result) )
      {
          if ($element)
          {
            $listes[$cpt]=$element;
            $cpt++;
          } 
      }
      return $listes;
  }
  return "";
}

// récupère l'indice moyen d'un joueur sur la saison donnée. (Hors Flash) 
function getIndiceGainMoyenJoueur($saison_id,$joueur_id)
{
  // Requete pour retrouver tous les joueurs dans l'ordre d'insertion en base
 $sql = "SELECT joueur_id, format((sum(pronostic.IndiceGain15)+sum(pronostic.IndiceGain7))/(select count(*) from pronostic p left join jeu on p.jeu_id=jeu.jeu_id where p.flash = 0 and joueur_id='$joueur_id' and jeu.saison_id='$saison_id'),2) 'moyenne'
 FROM pronostic left join jeu on pronostic.jeu_id=jeu.jeu_id where jeu.saison_id='$saison_id' and joueur_id='$joueur_id'  and flash = 0";
  
  $listes = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
  {
      // Lecture de ce jeu
      $cpt=0;
      while( $element = mysql_fetch_array($result) )
      {
          if ($element)
          {
            $listes[$cpt]=$element;
            $cpt++;
          } 
      }
      return $listes;
  }
  return "";
}

// récupère l'indice moyen d'un joueur sur la saison donnée. (Flash Compris) 
function getIndiceGainMoyenJoueurAF($saison_id,$joueur_id)
{
  // Requete pour retrouver tous les joueurs dans l'ordre d'insertion en base
 $sql = "SELECT joueur_id, format((sum(pronostic.IndiceGain15)+sum(pronostic.IndiceGain7))/(select count(*) from pronostic p left join jeu on p.jeu_id=jeu.jeu_id where joueur_id='$joueur_id' and jeu.saison_id='$saison_id'),2) 'moyenne'
 FROM pronostic left join jeu on pronostic.jeu_id=jeu.jeu_id where jeu.saison_id='$saison_id' and joueur_id='$joueur_id' ";
  
  $listes = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
  {
      // Lecture de ce jeu
      $cpt=0;
      while( $element = mysql_fetch_array($result) )
      {
          if ($element)
          {
            $listes[$cpt]=$element;
            $cpt++;
          } 
      }
      return $listes;
  }
  return "";
}

// récupère la répartition moyenne juste d'un joueur sur la saison donnée. (Hors Flash) 
// A améliorer car devrait tenir compte du nombre de match juste
function getMoyenneJusteJoueur($saison_id,$joueur_id)
{
  // Requete pour retrouver tous les joueurs dans l'ordre d'insertion en base
 $sql = "SELECT joueur_id, format(sum(pronostic.MoyenneJuste)/(select count(*) from pronostic p left join jeu on p.jeu_id=jeu.jeu_id where p.MoyenneJuste is not null and p.flash = 0 and joueur_id='$joueur_id' and jeu.saison_id='$saison_id'),1) 'moyenneJuste'
 FROM pronostic left join jeu on pronostic.jeu_id=jeu.jeu_id where jeu.saison_id='$saison_id' and joueur_id='$joueur_id'  and flash = 0 and pronostic.MoyenneJuste is not null";
  
  $listes = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
  {
      // Lecture de ce jeu
      $cpt=0;
      while( $element = mysql_fetch_array($result) )
      {
          if ($element)
          {
            $listes[$cpt]=$element;
            $cpt++;
          } 
      }
      return $listes;
  }
  return "";
}

// récupère la répartition moyenne juste d'un joueur sur la saison donnée. (Flash Compris) 
// A améliorer car devrait tenir compte du nombre de match juste
function getMoyenneJusteJoueurAF($saison_id,$joueur_id)
{
  // Requete pour retrouver tous les joueurs dans l'ordre d'insertion en base
 $sql = "SELECT joueur_id, format(sum(pronostic.MoyenneJuste)/(select count(*) from pronostic p left join jeu on p.jeu_id=jeu.jeu_id where p.MoyenneJuste is not null and joueur_id='$joueur_id' and jeu.saison_id='$saison_id'),1) 'moyenneJuste'
 FROM pronostic left join jeu on pronostic.jeu_id=jeu.jeu_id where pronostic.MoyenneJuste is not null and jeu.saison_id='$saison_id' and joueur_id='$joueur_id' ";
  
  $listes = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
  {
      // Lecture de ce jeu
      $cpt=0;
      while( $element = mysql_fetch_array($result) )
      {
          if ($element)
          {
            $listes[$cpt]=$element;
            $cpt++;
          } 
      }
      return $listes;
  }
  return "";
}

// Recap des repartitions des couleurs d'une saison  
function getCouleurSaison($saison_id)
{
  // Requete pour retrouver tous les joueurs dans l'ordre d'insertion en base
  $sql = "SELECT titre, vert, jaune, rouge, vert7, jaune7, rouge7
          FROM resultat left join jeu on resultat.jeu_id=jeu.jeu_id
          where saison_id='$saison_id'
          order by jeu.jeu_id asc";
  
  $listes = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
  {
      // Lecture de la saison
      $cpt=0;
      while( $element = mysql_fetch_array($result) )
      {
          if ($element)
          {
            $listes[$cpt]=$element;
            $cpt++;
          } 
      }
      return $listes;
  }
  return "";
}


// Recap des Indices de gains la saison  
function getMoyenneCouleurSaison($saison_id)
{
  // Requete pour retrouver tous les joueurs dans l'ordre d'insertion en base
  $sql = "SELECT format((sum(vert)/(select count(*) from resultat left join jeu on resultat.jeu_id=jeu.jeu_id where saison_id='$saison_id')),1) 'vert', 
			format((sum(jaune)/(select count(*) from resultat left join jeu on resultat.jeu_id=jeu.jeu_id where saison_id='$saison_id')),1) 'jaune',
			format((sum(rouge)/(select count(*) from resultat left join jeu on resultat.jeu_id=jeu.jeu_id where saison_id='$saison_id')),1) 'rouge',
			format((sum(vert7)/(select count(*) from resultat left join jeu on resultat.jeu_id=jeu.jeu_id where saison_id='$saison_id')),1) 'vert7', 
			format((sum(jaune7)/(select count(*) from resultat left join jeu on resultat.jeu_id=jeu.jeu_id where saison_id='$saison_id')),1) 'jaune7',
			format((sum(rouge7)/(select count(*) from resultat left join jeu on resultat.jeu_id=jeu.jeu_id where saison_id='$saison_id')),1) 'rouge7'
          FROM resultat left join jeu on resultat.jeu_id=jeu.jeu_id
          where saison_id='$saison_id'";
  
  $listes = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
  {
      // Lecture de la saison
      $cpt=0;
      while( $element = mysql_fetch_array($result) )
      {
          if ($element)
          {
            $listes[$cpt]=$element;
            $cpt++;
          } 
      }
      return $listes;
  }
  return "";
}



// Recap des Indices de gains la saison  
function getIndiceJeuxSaison($saison_id)
{
  // Requete pour retrouver tous les joueurs dans l'ordre d'insertion en base
  $sql = "SELECT titre, resultat.IndiceGain7, resultat.IndiceGain15 
          FROM resultat left join jeu on resultat.jeu_id=jeu.jeu_id
          where saison_id='$saison_id'
          order by jeu.jeu_id asc";
  
  $listes = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
  {
      // Lecture de la saison
      $cpt=0;
      while( $element = mysql_fetch_array($result) )
      {
          if ($element)
          {
            $listes[$cpt]=$element;
            $cpt++;
          } 
      }
      return $listes;
  }
  return "";
}


// Recap des Indices de gains la saison  
function getMoyenneIndiceJeuxSaison($saison_id)
{
  // Requete pour retrouver tous les joueurs dans l'ordre d'insertion en base
  $sql = "SELECT format((sum(IndiceGain7)/(select count(*) from resultat left join jeu on resultat.jeu_id=jeu.jeu_id where IndiceGain7 <> 0 and saison_id='$saison_id')),2) 'moy7', format((sum(IndiceGain15)/(select count(*) from resultat left join jeu on resultat.jeu_id=jeu.jeu_id where IndiceGain15 <> 0 and saison_id='$saison_id')),2) 'moy15'
          FROM resultat left join jeu on resultat.jeu_id=jeu.jeu_id
          where saison_id='$saison_id'";
  
  $listes = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
  {
      // Lecture de la saison
      $cpt=0;
      while( $element = mysql_fetch_array($result) )
      {
          if ($element)
          {
            $listes[$cpt]=$element;
            $cpt++;
          } 
      }
      return $listes;
  }
  return "";
}


// Somme des gains et Nombre de gain par joueur toutes saisons confondues :
function getStatGainsJoueurs()
{
  // Requete pour retrouver tous les joueurs dans l'ordre d'insertion en base
  $sql = "SELECT joueur.joueur_id jid, nom,  format(sum(somme),2) 'total', (select count(*) from gain g where g.joueur_id=gain.joueur_id) 'nbgain'
          FROM joueur left join  gain on gain.joueur_id=joueur.joueur_id group by joueur.joueur_id order by sum(somme) desc, nom ";
  
  $listes = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
  {
      // Lecture de ce jeu
      $cpt=0;
      while( $element = mysql_fetch_array($result) )
      {
          if ($element)
          {
            $listes[$cpt]=$element;
            $cpt++;
          } 
      }
      return $listes;
  }
  return "";
}


// 10 meilleurs gains toutes saisons confondues  
function getStatMeilleursGains()
{
  // Requete pour retrouver tous les joueurs dans l'ordre d'insertion en base
  $sql = "SELECT j.nom 'jnom', format(somme,2) 'total', s.nom 'snom' 
          FROM gain g, joueur j, saison s
          where j.joueur_id=g.joueur_id and g.saison_id=s.saison_id
          order by somme desc limit 0,10";
  
  $listes = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
  {
      // Lecture de ce jeu
      $cpt=0;
      while( $element = mysql_fetch_array($result) )
      {
          if ($element)
          {
            $listes[$cpt]=$element;
            $cpt++;
          } 
      }
      return $listes;
  }
  return "";
}



// Somme des Gains / saison  
function getStatGainsSaison()
{
  // Requete pour retrouver tous les joueurs dans l'ordre d'insertion en base
  $sql = "SELECT saison.saison_id, nom 'snom', format(sum(somme),2) 'total',  
          (SELECT COUNT(*) FROM gain g where g.saison_id = saison.saison_id) 'nbgain', 
          (SELECT COUNT(*) FROM jeu j where j.saison_id = saison.saison_id) 'nbmatch'
          FROM saison left join gain on gain.saison_id=saison.saison_id group by saison_id";
  
  $listes = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
  {
      // Lecture de ce jeu
      $cpt=0;
      while( $element = mysql_fetch_array($result) )
      {
          if ($element)
          {
            $listes[$cpt]=$element;
            $cpt++;
          } 
      }
      return $listes;
  }
  return "";
}




// Purge la table des stat
function supprimeToutesStatistiques()
{
    // Suppression des statistiques
    $requete=	"delete from stat";
    $result = mysql_query($requete);
    echo mysql_errno();
    if (mysql_errno() == 0)  {  return True;  }
    return False;
}


// Supprime un jeu
function supprimeUneStatistique($jeu_id)
{
    // Suppression des statistiques
    $requete=	"delete from stat where jeu_id='$jeu_id'";
    $result = mysql_query($requete);
    if (mysql_errno() == 0)  {  return True;   }
    return False;
}

// Supprime un jeu
function supprimeUnJeu($jeu_id)
{
    // Suppression des statistiques
    $requete=	"delete from jeu where jeu_id='$jeu_id'";
    $result = mysql_query($requete);
    if (mysql_errno() == 0)  {  return True;   }
    return False;
}

// Ajoute en table les statistiques pour ce joueur, ce jeu et cette saison
function setMiseAJourStat($saison_id, $jeu_id, $joueur_id, $valeur, $flash)
{
   // Ajout de cette stat
	 $requete=	"insert into stat (saison_id, jeu_id, joueur_id, valeur, flash) values ($saison_id, $jeu_id, $joueur_id, $valeur, $flash)";
   $result = mysql_query($requete);
   if (mysql_errno() == 0) 
   {
      return True;
   }
   return False;
}

    

// Retourne les statistiques de tous les jeux pour une saison donnée
function getJeuStat($saison_id)
{
    // Requete pour voir si le prono existe
    $sql = "select jeu_id, joueur_id, flash, valeur from stat where saison_id='$saison_id' order by jeu_id, joueur_id ";
    
    $jeuStat = array();
    // Exécution de la requête 
    if($result = mysql_query($sql))
    {
   	  $cpt=0;
  	  // Lecture 
   	  while( $temp = mysql_fetch_array($result) )
 			{
 			    if ($temp)
 			    {
 			      $jeuStat[$cpt]=$temp;
            $cpt++;
          } 
 			}
 			return $jeuStat;
    }
    return "";
}


// Retourne les statistiques d'un joueur pour un jeu donné
function getMoyenneStat($saison_id, $type, $jour)
{
    // Classement général
    if ($type=="C")
    {
        if ($jour=="T")
        {
            // Moyenne générale
            $sql = "SELECT joueur_id, round(AVG(valeur),1) moyenne, COUNT(*) nombre FROM stat WHERE saison_id='$saison_id' GROUP BY joueur_id";
        }
        else
        {
            // Moyenne générale sans la dernière journée
            $sql = "SELECT joueur_id, round(AVG(valeur),1) moyenne, COUNT(*) nombre FROM stat WHERE saison_id='$saison_id' 
                    and jeu_id <> (select max(jeu_id) from stat WHERE saison_id = '$saison_id') GROUP BY joueur_id";
        }
    }
 
    // Classement général Sans les flashs
    if ($type=="CSF")
    {
        if ($jour=="T")
        {
            // Moyenne générale Sans les flashs
            $sql = "SELECT joueur_id, round(AVG(valeur),1) moyenne, COUNT(*) nombre  FROM stat WHERE saison_id='$saison_id' AND flash = '0' GROUP BY joueur_id";
        }
        else
        {
            // Moyenne générale Sans les flashs sans la dernière journée
            $sql = "SELECT joueur_id, round(AVG(valeur),1) moyenne, COUNT(*) nombre FROM stat WHERE saison_id='$saison_id' 
                    AND flash = '0' and jeu_id <> (select max(jeu_id) from stat WHERE saison_id = '$saison_id' AND flash = '0') GROUP BY joueur_id";
        }
    }
  
    // Classement général Avec les flashs
    if ($type=="CAF")
    {
        if ($jour=="T")
        {
            // Moyenne générale Avec les flashs
            $sql = "SELECT joueur_id, round(AVG(valeur),1) moyenne, COUNT(*) nombre FROM stat WHERE saison_id='$saison_id' AND flash = '0' GROUP BY joueur_id";
        }
        else
        {
            // Moyenne générale Avec les flashs sans la dernière journée
            $sql = "SELECT joueur_id, round(AVG(valeur),1) moyenne, COUNT(*) nombre FROM stat WHERE saison_id='$saison_id' 
                    AND flash = '0' and jeu_id <> (select max(jeu_id) from stat WHERE saison_id = '$saison_id' AND flash = '0') GROUP BY joueur_id";
        }
    }

    $classement = array();
    // Exécution de la requête 
    if($result = mysql_query($sql))
    {
   	  $cpt=0;
  	  // Lecture 
   	  while( $temp = mysql_fetch_array($result) )
 			{
 			    if ($temp)
 			    {
 			      $classement[$cpt]=$temp;
            $cpt++;
          } 
 			}
 			return $classement;
    }
    return "";

} 



function MAJStatistiquesJeu($jeu, $listeJoueurs)
{
     $jeu_id      = $jeu['jeu_id'];  
     $saison_id   = $jeu['saison_id'];
//     echo "<p>MAJStatistiquesJournee : Saison id :".$saison_id.", Stat jeu :".$jeu_id."</p>";
  
    // Purge la table des statistiques avec ce jeu 
    if (supprimeUneStatistique($jeu_id) != True)   {  return False;  }

    // Recherche le résultat de ce jeu  1, N ou 2
    $Resultat=getResultatJeu($jeu_id);
    // Si pas de résultat sur le premier match, on n'intègre pas ce jeu dans la table des statistiques ! 
    if (sizeof($Resultat)==0 || getResultatNumero($Resultat,1)=="")   {   return False;   }

             
    // initialisation des variables 
    $ListeresultatOk = array();
    $NbMatchsJoues   = array();
                         
    // Pour ce jeu, on va rechercher calculer la moyenne de tous les joueurs
    $meilleur=0;
    $mauvais=100;    
    for($j=0;$j<sizeof($listeJoueurs);$j++)
    {
          $joueur=$listeJoueurs[$j];
          $joueur_id=$joueur["joueur_id"];
          $joueur_nom=$joueur["nom"];
                  
          // Recherche les pronostics de ce joueur
          $pronostic=getPronosticJoueur($joueur_id,$jeu_id);   
          $flash = $pronostic["flash"];                 
          // Initialisation des bons résultats à 0
          $ListeresultatOk[$j]=0;
                   
          // Initialisation du nombre de matchs joués à 0
          $NbMatchsJoues[$j]=0;
                  
          // Pour ce joueur et ce jeu, on va lire tous les pronostics pour comparer au résultat final   
          for($k=1; $k <= 15 ; $k++) 
          {
              // Résultat de ce match pour ce jeu
              $resultatJeu=getResultatNumero($Resultat,$k);
              // Pronostic du joueur pour ce match et ce jeu
              $pronosticJeu = getPronosticNumero($pronostic,$k);
              
              // Le résultat est bon ? on incrémente le nombre de résultat bon pour ce joueur et ce jeu
              $posresultat = isResultatBon($pronosticJeu, $resultatJeu);
              if ($posresultat) 
              {
              $ListeresultatOk[$j]=$ListeresultatOk[$j]+1;
              }                        
              // Le joueur a joué ce match ? on incrémente son nombre de match joués
              if ($pronosticJeu)    $NbMatchsJoues[$j]=$NbMatchsJoues[$j]+1;
          }
          
          // Calcul de sa moyenne 
          if ($NbMatchsJoues[$j])  
          {
              $moyenne=$ListeresultatOk[$j]/$NbMatchsJoues[$j]*100;
              $moyenne=round($moyenne,1);
              // Enregistrement en base de la moyenne de ce joueur pour ce jeu
              setMiseAJourStat($saison_id, $jeu_id, $joueur_id, $moyenne, $flash);
              //echo "<h2>Joueur:".$joueur_nom.", moyenne:".$moyenne."</h2>";      
          }
    }
}



function RecalculStatistiquesComplet()
{
     echo "Fonction RecalculStatistiquesComplet"; 
     // Suppression de la table des statistiques
     if (supprimeToutesStatistiques() != True)
            return False;
     
     echo "Fonction RecalculStatistiquesComplet"; 
     
     // Récupération de la liste des joueurs et la liste des jeux
     $listeJoueurs    = getListeJoueurs();
     $listeJeux       = getListeTousJeux();
     for($i=sizeof($listeJeux)-1; $i >= 0 ; $i--)
     {
             $jeu         = $listeJeux[$i];
             echo "Stat:".$jeu['jeu_id']."<br>"; 
             MAJStatistiquesJeu($jeu, $listeJoueurs);
     } 

}
 
function RecalculStatistiquesSaisonCourante()
{
     // Récupération de la saison courante 
     $saison_id            = getIDSaisonCourante();    
     // Récupération de la liste des joueurs et la liste des jeux
     $listeJoueurs    = getListeJoueurs();
     $listeJeux       = getListeJeuxSaison($saison_id);
     for($i=sizeof($listeJeux)-1; $i >= 0 ; $i--)
     {
             $jeu         = $listeJeux[$i];
             MAJStatistiquesJeu($jeu, $listeJoueurs);
     } 

}
 
function RecalculStatistiquesSaison($saison_id)
{
     // Récupération de la liste des joueurs et la liste des jeux
     $listeJoueurs    = getListeJoueurs();
     $listeJeux       = getListeJeuxSaison($saison_id);
     for($i=sizeof($listeJeux)-1; $i >= 0 ; $i--)
     {
             $jeu         = $listeJeux[$i];
             MAJStatistiquesJeu($jeu, $listeJoueurs);
     } 

}
  
 
function RecalculStatistiquesUnejournee($jeu_id)
{
     // Récupération du jeu 
     $jeu = getJeu($jeu_id);          

     // Récupération de la liste des joueurs et la liste des jeux
     $listeJoueurs    = getListeJoueurs();
     MAJStatistiquesJeu($jeu, $listeJoueurs);
}
 






   
  

// ======================================================== //
//             L E S    P R O N O S T I C S                 //
// ======================================================== //


    
function extraitPronosticJoueur($ListeCompletPronostic, $joueurID)
{
   for($i=0;$i<sizeof($ListeCompletPronostic) && !empty($ListeCompletPronostic);$i++)
    {
        if ($ListeCompletPronostic[$i]['joueur_id']==$joueurID) 
        {
             return $ListeCompletPronostic[$i];
        }            
    }
    return null;
}





// Retourne le pronostic d'un jeu donné
function getPronosticJeu($jeu_id)
{
    // Requete pour voir si ce joueur a déjà fait un pronostic pour ce jeu
    $sql = "select * from pronostic where jeu_id='$jeu_id'";

    $pronos = array();
    // Exécution de la requête 
    if($result = mysql_query($sql))
	 {
  	  // Lecture de ce jeu
   	  $cpt=0;
   	  while( $prono = mysql_fetch_array($result) )
 			{
 			    if ($prono)
 			    {
 			      $pronos[$cpt]=$prono;
            $cpt++;
          } 
 			}
 			return $pronos;
  }
}

// Retourne le pronostic d'un joueur pour un jeu donné
function getPronosticJoueur($joueur_id, $jeu_id)
{
    // Requete pour voir si ce joueur a déjà fait un pronostic pour ce jeu
    $sql = "select * from pronostic where jeu_id='$jeu_id' and joueur_id='$joueur_id'";
        
    // Exécution de la requête et lecture de la première ligne
    if($result = mysql_query($sql))
    {
        // Nombre de ligne trouvée
        $nbenreg = mysql_num_rows($result);
            
        if ($nbenreg==1)
        {
          	// Lecture de cet utilisateur
           	$pronostic = mysql_fetch_array($result);
       		 
       		   if ($pronostic)
       		   {
  	             return $pronostic;
  	         }
      	  
      	}
    }
}



// Cette fonction permet de retrouver un pronostic précis dans une liste de pronostic
function getPronosticNumero($prono, $i)
{
    if ($i == 1)       return $prono["pronostic1"];
    else if ($i == 2)  return $prono["pronostic2"];
    else if ($i == 3)  return $prono["pronostic3"];
    else if ($i == 4)  return $prono["pronostic4"];
    else if ($i == 5)  return $prono["pronostic5"];
    else if ($i == 6)  return $prono["pronostic6"];
    else if ($i == 7)  return $prono["pronostic7"];
    else if ($i == 8)  return $prono["pronostic8"];
    else if ($i == 9)  return $prono["pronostic9"];
    else if ($i == 10)  return $prono["pronostic10"];
    else if ($i == 11)  return $prono["pronostic11"];
    else if ($i == 12)  return $prono["pronostic12"];
    else if ($i == 13)  return $prono["pronostic13"];
    else if ($i == 14)  return $prono["pronostic14"];
    else if ($i == 15)  return $prono["pronostic15"];
    return "";
}

// Retourne le pronostic d'un joueur pour un jeu donné
function getNbPronosticJoueur($pronostic)
{
    $j=0;
	for($i=1;$i<=15;$i++) {
		if (getPronosticNumero($pronostic, $i) <> "") {
			$j++;
		}
	}
	return $j;
}

// Retourne si un pronostic est correct.
function getPronosticEstJoue($pronostic, $i, $chaineaRechercher)
{
    if ($pronostic)
    {            
        // Lecture des pronostics du joueur
        $pronostic = getPronosticNumero($pronostic, $i);

        // Recherche 
        $pos = strpos($pronostic, $chaineaRechercher);

        if (is_int($pos) == false) 
        {
          return false;
        } 
        else 
        {
          return true;
        }
    }
		return false;
}



function setMiseAJourPronostic($jeu_id,$joueur_id,$tableauPronostic)
{
    $erreur=true;
    
    if ($joueur_id && $jeu_id)
    {
        // Requete pour voir si ce joueur a déjà fait un pronostic pour ce jeu
        $sql = "select * from pronostic where jeu_id='$jeu_id' and joueur_id='$joueur_id'";
        
        // Exécution de la requête et lecture de la première ligne
        if($result = mysql_query($sql))
      	{
          // echo "ok !".mysql_error(); 
      	   // Combien de pronostic : 0 il n'a pas encore joué (faire insert), 1 il a déjà joué (faire update))
      	    $nbprono = mysql_num_rows($result);
    
           //echo "nb prono: ". $nbprono; 
     	    if ($nbprono==0)
      	    {
      	    		$requete=	"insert into pronostic ( jeu_id, flash, joueur_id, pronostic1, pronostic2, 
                          pronostic3, pronostic4, pronostic5, pronostic6, pronostic7, 
                          pronostic8, pronostic9, pronostic10, pronostic11, pronostic12, 
                          pronostic13, pronostic14, pronostic15) 
    		                  values 
                          ('$jeu_id', '0',  '$joueur_id',  '$tableauPronostic[1]',  '$tableauPronostic[2]',  '$tableauPronostic[3]', 
                          '$tableauPronostic[4]',  '$tableauPronostic[5]',  '$tableauPronostic[6]',  '$tableauPronostic[7]',
                          '$tableauPronostic[8]',  '$tableauPronostic[9]',  '$tableauPronostic[10]', '$tableauPronostic[11]', 
                          '$tableauPronostic[12]', '$tableauPronostic[13]', '$tableauPronostic[14]', '$tableauPronostic[15]')";
                          
               $result = mysql_query($requete);
               
               //echo "erreur". mysql_error();
               
               if (mysql_affected_rows() == 1) 
               {
                  $erreur=false;
               }
    
            }
            else if ($nbprono==1)
            {
         	    // MAJ de ce prono
         	    $requete=	"update pronostic set flash='0', 
         	          pronostic1='$tableauPronostic[1]', 
                    pronostic2='$tableauPronostic[2]', 
                    pronostic3='$tableauPronostic[3]', 
                    pronostic4='$tableauPronostic[4]', 
                    pronostic5='$tableauPronostic[5]', 
                    pronostic6='$tableauPronostic[6]', 
                    pronostic7='$tableauPronostic[7]', 
                    pronostic8='$tableauPronostic[8]', 
                    pronostic9='$tableauPronostic[9]', 
                    pronostic10='$tableauPronostic[10]', 
                    pronostic11='$tableauPronostic[11]', 
                    pronostic12='$tableauPronostic[12]', 
                    pronostic13='$tableauPronostic[13]', 
                    pronostic14='$tableauPronostic[14]', 
                    pronostic15='$tableauPronostic[15]'
                    where jeu_id='$jeu_id' and joueur_id='$joueur_id'";
                   
               $result = mysql_query($requete);
              // echo "erreur". mysql_error();
               
               if (mysql_affected_rows() == 1) 
               {
                  $erreur=false;
               }
    
        	    
            }
        }  
    }
    return $erreur;
}

//met en base la moyenne des pourcentages de répartition où le prono est juste
function setMoyenneJuste($jeu_id,$joueur_id,$moyenneJuste)
{
	$requete= "update pronostic set MoyenneJuste='$moyenneJuste' where jeu_id='$jeu_id' and joueur_id='$joueur_id'"; 
    $result = mysql_query($requete);
    if (mysql_errno() == 0) 
    {
        // return true;
    }
    return false;
}

function setIndiceGainProno7($jeu_id,$joueur_id,$indice)
{
	$requete= "update pronostic set IndiceGain7='$indice' where jeu_id='$jeu_id' and joueur_id='$joueur_id'"; 
    $result = mysql_query($requete);
    if (mysql_errno() == 0) 
    {
        return true;
    }
    return false;
}

function setIndiceGainProno15($jeu_id,$joueur_id,$indice)
{
	$requete= "update pronostic set IndiceGain15='$indice' where jeu_id='$jeu_id' and joueur_id='$joueur_id'"; 
    $result = mysql_query($requete);
    if (mysql_errno() == 0) 
    {
        return true;
    }
    return false;
}


function setIndiceGainJeu7($jeu_id,$indice)
{
	$requete= "update resultat set IndiceGain7='$indice' where jeu_id='$jeu_id'"; 
    $result = mysql_query($requete);
    if (mysql_errno() == 0) 
    {
        return true;
    }
    return false;
}

function setIndiceGainJeu15($jeu_id,$indice)
{
	$requete= "update resultat set IndiceGain15='$indice' where jeu_id='$jeu_id'"; 
    $result = mysql_query($requete);
    if (mysql_errno() == 0) 
    {
        return true;
    }
    return false;
}

function setCouleurJeu($jeu_id,$compteurVert,$compteurJaune,$compteurRouge)
{
	$requete= "update resultat set vert='$compteurVert', jaune='$compteurJaune', rouge='$compteurRouge' where jeu_id='$jeu_id'"; 
    $result = mysql_query($requete);
    if (mysql_errno() == 0) 
    {
        return true;
    }
    return false;
}

function setCouleurJeu7($jeu_id,$compteurVert,$compteurJaune,$compteurRouge)
{
	$requete= "update resultat set vert7='$compteurVert', jaune7='$compteurJaune', rouge7='$compteurRouge' where jeu_id='$jeu_id'"; 
    $result = mysql_query($requete);
    if (mysql_errno() == 0) 
    {
        return true;
    }
    return false;
}

function setMiseAJourPronosticAleatoire7($jeu_id,$joueur_id)
{
    // Génération des nombres aléatoires
    srand((float) microtime()*1000000);
    for ($i=1; $i <= 7; $i++)
    {
         $tableauPronostic[$i]=mt_rand(0,2);
         if ($tableauPronostic[$i]=='0') $tableauPronostic[$i]='N';
         if ($tableauPronostic[$i]=='3') $tableauPronostic[$i]='1';
    }
    for ($i=8; $i <= 15; $i++)
    {
         $tableauPronostic[$i]="";
    }
    
    $erreur=true;
    if ($joueur_id && $jeu_id)
    {
        // Requete pour voir si ce joueur a déjà fait un pronostic pour ce jeu
        $sql = "select * from pronostic where jeu_id='$jeu_id' and joueur_id='$joueur_id'";
        
        // Exécution de la requête et lecture de la première ligne
        if($result = mysql_query($sql))
      	{
          //echo "ok !".mysql_error(); 
      	  // Combien de pronostic : 0 il n'a pas encore joué (faire insert), 1 il a déjà joué (faire update))
      	 $nbprono = mysql_num_rows($result);
          //echo "nb prono: ". $nbprono; 
     	    if ($nbprono==0)
      	    {
      	    		$requete=	"insert into pronostic ( jeu_id, flash, joueur_id, pronostic1, pronostic2, 
                          pronostic3, pronostic4, pronostic5, pronostic6, pronostic7, 
                          pronostic8, pronostic9, pronostic10, pronostic11, pronostic12, 
                          pronostic13, pronostic14, pronostic15) 
    		                  values 
                          ('$jeu_id', '1', '$joueur_id',  '$tableauPronostic[1]',  '$tableauPronostic[2]',  '$tableauPronostic[3]', 
                          '$tableauPronostic[4]',  '$tableauPronostic[5]',  '$tableauPronostic[6]',  '$tableauPronostic[7]',
                          '$tableauPronostic[8]',  '$tableauPronostic[9]',  '$tableauPronostic[10]', '$tableauPronostic[11]', 
                          '$tableauPronostic[12]', '$tableauPronostic[13]', '$tableauPronostic[14]', '$tableauPronostic[15]')";
                          
               $result = mysql_query($requete);
               
               //echo "erreur". mysql_error();
               
               if (mysql_affected_rows() == 1) 
               {
                  $erreur=false;
               }
    
            }
      
            
            
            
            else if ($nbprono==1)
            {
         	    // MAJ de ce prono
         	    $requete=	"update pronostic set flash='1', 
         	          pronostic1='$tableauPronostic[1]',
                    pronostic2='$tableauPronostic[2]', 
                    pronostic3='$tableauPronostic[3]', 
                    pronostic4='$tableauPronostic[4]', 
                    pronostic5='$tableauPronostic[5]', 
                    pronostic6='$tableauPronostic[6]', 
                    pronostic7='$tableauPronostic[7]', 
                    pronostic8='$tableauPronostic[8]', 
                    pronostic9='$tableauPronostic[9]', 
                    pronostic10='$tableauPronostic[10]', 
                    pronostic11='$tableauPronostic[11]', 
                    pronostic12='$tableauPronostic[12]', 
                    pronostic13='$tableauPronostic[13]', 
                    pronostic14='$tableauPronostic[14]', 
                    pronostic15='$tableauPronostic[15]'
                    where jeu_id='$jeu_id' and joueur_id='$joueur_id'";
                   
               $result = mysql_query($requete);
              // echo "erreur". mysql_error();
               
               if (mysql_affected_rows() == 1) 
               {
                  $erreur=false;
               }
    
        	    
            }
        }  
    }
    return $erreur;
}




// ======================================================== //
//             L E S    R E S U L T A T S                   //
// ======================================================== //


function setMiseAJourResultat($jeu_id,$tableauResultat, $nom, $date)
{  		
    $erreur=true;
		if ($jeu_id)
		{
        // Requete pour voir s'il existe  déjà fait un resultat pour ce jeu
        $sql = "select * from resultat where jeu_id='$jeu_id'";
        
        // Exécution de la requête et lecture de la première ligne
        if($result = mysql_query($sql))
      	{
 	          //echo "erreur select resultat :". mysql_error();
 	          
      	   // Combien de resultat : 0 il n'a pas encore joué (faire insert), 1 il a déjà joué (faire update))
      	    $nbresultat = mysql_num_rows($result);

      	    if ($nbresultat==0)
      	    {
      	    		$requete=	"insert into resultat ( jeu_id, resultat1, resultat2, 
                          resultat3, resultat4, resultat5, resultat6, resultat7, 
                          resultat8, resultat9, resultat10, resultat11, resultat12, 
                          resultat13, resultat14, resultat15, nom, date) 
    		                  values 
		                      ($jeu_id, '$tableauResultat[1]',  '$tableauResultat[2]',  '$tableauResultat[3]', 
                          '$tableauResultat[4]',  '$tableauResultat[5]',  '$tableauResultat[6]',  '$tableauResultat[7]',
		                      '$tableauResultat[8]',  '$tableauResultat[9]',  '$tableauResultat[10]', '$tableauResultat[11]', 
                          '$tableauResultat[12]', '$tableauResultat[13]', '$tableauResultat[14]', '$tableauResultat[15]',
                          '$nom', '$date')";
                          
 	             $result = mysql_query($requete);
 	             
 	             //echo "erreur Insertion :". mysql_error();
 	             
 	             if (mysql_affected_rows() == 1) 
	             {
                  $erreur=false;
	             }

            }
            else if ($nbresultat==1)
            {
         	    // MAJ de ce prono
         	    $requete=	"update resultat set resultat1='$tableauResultat[1]', 
                    resultat2='$tableauResultat[2]', 
                    resultat3='$tableauResultat[3]', 
                    resultat4='$tableauResultat[4]', 
                    resultat5='$tableauResultat[5]', 
                    resultat6='$tableauResultat[6]', 
                    resultat7='$tableauResultat[7]', 
                    resultat8='$tableauResultat[8]', 
                    resultat9='$tableauResultat[9]', 
                    resultat10='$tableauResultat[10]', 
                    resultat11='$tableauResultat[11]', 
                    resultat12='$tableauResultat[12]', 
                    resultat13='$tableauResultat[13]', 
                    resultat14='$tableauResultat[14]', 
                    resultat15='$tableauResultat[15]', 
                    nom='$nom', 
                    date='$date'
                    where jeu_id='$jeu_id'";
                   
 	             $result = mysql_query($requete);
 	             
 	             //echo "erreur Modification : ". $requete;
 	             //echo "erreur Modification : ". mysql_error();
 	             //echo "mysql_affected_rows : ".mysql_affected_rows();
 	             
 	             if (mysql_affected_rows() == 1) 
	             {
                  $erreur=false;
	             }
            }
  	    }  
    }
    return $erreur;
}

// Retourne le résultat pour un jeu donné
function getResultatSaison($saison_id)
{
    /* Requete pour lire l'éventuel résultat (éventuel car peut-être pas encore saisie) sur ce jeu */
    $sql = "select * from resultat left join jeu on resultat.jeu_id=jeu.jeu_id left join saison on jeu.saison_id=saison.saison_id where saison.saison_id='$saison_id'";
    
	$listes = array();
	  // Exécution de la requête 
	  if($result = mysql_query($sql))
	  {
		  // Lecture de ce jeu
		  $cpt=0;
		  while( $element = mysql_fetch_array($result) )
		  {
			  if ($element)
			  {
				$listes[$cpt]=$element;
				$cpt++;
			  } 
		  }
		  return $listes;
	  }
	  return "";

}

// Retourne le résultat pour un jeu donné
function getResultatJeu($jeu_id)
{
    /* Requete pour lire l'éventuel résultat (éventuel car peut-être pas encore saisie) sur ce jeu */
    $sql = "select * from resultat where jeu_id='$jeu_id'";
        
    // Exécution de la requête et lecture de l'éventuelle seule ligne de résultat !
    if($result = mysql_query($sql))
    {
        // Nombre de ligne trouvée
        $nbenreg = mysql_num_rows($result);
            
        if ($nbenreg==1)
        {
          	// Lecture de cet utilisateur
           	$resultat = mysql_fetch_array($result);
       		 
       		   if ($resultat)
       		   {
  	             return $resultat;
  	         }
      	  
      	}
    }
}


// Cette fonction permet de retrouver un résultat précis dans une liste de résultat
function getResultatNumero($result, $i)
{
    if ($i == 1)       return $result["resultat1"];
    else if ($i == 2)  return $result["resultat2"];
    else if ($i == 3)  return $result["resultat3"];
    else if ($i == 4)  return $result["resultat4"];
    else if ($i == 5)  return $result["resultat5"];
    else if ($i == 6)  return $result["resultat6"];
    else if ($i == 7)  return $result["resultat7"];
    else if ($i == 8)  return $result["resultat8"];
    else if ($i == 9)  return $result["resultat9"];
    else if ($i == 10)  return $result["resultat10"];
    else if ($i == 11)  return $result["resultat11"];
    else if ($i == 12)  return $result["resultat12"];
    else if ($i == 13)  return $result["resultat13"];
    else if ($i == 14)  return $result["resultat14"];
    else if ($i == 15)  return $result["resultat15"];
    return "";
}

// Retourne si un resultat est correct.
function getResultatEstJoue($resultat, $i, $chaineaRechercher)
{
    if ($resultat)
    {            
        // Lecture des resultats du joueur
        $resultat = getResultatNumero($resultat, $i);

        // Recherche 
        $pos = strpos($resultat, $chaineaRechercher);

        if (is_int($pos) == false) 
        {
          return false;
        } 
        else 
        {
          return true;
        }
    }
		return false;
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



// ======================================================== //
//        L E S    P R O N O S T I C S                      //
// ======================================================== //

// Nombre de jeux antérieurs à analyser à partir de la journée courante
$nbJeuxAAnalyser = 3;

// On applique une décote au pt obtenu sur des matchs + anciens
$decote = 20;

// On applique un taux de majoration si l'équipe joue à domicile !
$tauxMajorationDomicile = 100;

// Ecart limite qui donne le match nul (en nombre de points)
$ecartPoint = 2;

// Calcul des points 
// Match précédent à domicile ==========> a gagné : 3 pts, a fait nul : 1 pt, a perdu : 0 pt
// Match précédent à l'extérieur =======> a gagné : 4,5 pts, a fait nul : 1,5 pt, a perdu : 0 pt  

// Matchs précédents pareil avec un pourcentage de 20% ($decote) en moins à chaque journée ..
// donc Match précédent à domicile ==========> a gagné : 3 * 90% pts, a fait nul : 1 * 90%  pt, a perdu : 0 pt
// et Match précédent à l'extérieur =======> a gagné : 4,5 * 90%  pts, a fait nul : 1,5 * 90% pt, a perdu : 0 pt  
$Dom1 = 3;
$DomN = 1;
$Dom2 = 0;
$Ext1 = 4.5;
$ExtN = 1.5;
$Ext2 = 0;


function getAnalyse($equipe, $listeJeux)
{
    global $Dom1;
    global $DomN;
    global $Dom2;
    global $Ext1;
    global $ExtN;
    global $Ext2;

    global $nbJeuxAAnalyser;
    global $decote;

    $NbJourneeOuEquipeAEteTrouve = 0;
    $Score=0;
    $decoteDepart=0;
    $decoteInitiale=$decote;
    
    $analyse=array();
    
    //echo "Analyse de l'équipe : ".$equipe."<br />";
    
    // Calcul du dernier jeu à retrouver !
    $finRecherche= sizeof($listeJeux)-1-$nbJeuxAAnalyser;
    if ($finRecherche<0)
        $finRecherche=0;
   
    // Affichage des jeux par ordre croissant
    for($j=sizeof($listeJeux)-2; $j >=$finRecherche ; $j--) 
    {
        $jeuSuivant   = $listeJeux[$j];

        // Recherche des résultats de ce jeu
        $jeu_id         = $jeuSuivant['jeu_id'];
        $Resultat       = getResultatJeu($jeu_id);
        
        //echo "la décote est à : $decoteDepart<br />";
        //echo "Decote : ".((100-$decoteDepart)/100)."<br />";   
        
        // Affiche tous les matchs
        $nbMatchsDuJeuSuivant  = getNbMatchsDeCeJeu($jeuSuivant);
        for($i=1; $i <= $nbMatchsDuJeuSuivant ; $i++) 
        {
          $equiped     = $jeuSuivant["equipe".$i."d"];
          $equipev     = $jeuSuivant["equipe".$i."v"];
          
          if (($equiped==$equipe) || ($equipev==$equipe))
          {   
              $NbJourneeOuEquipeAEteTrouve++;
              
              //echo "Sur le match ".$jeuSuivant['titre'].", ".$equipe;
              $ResultatMatch  = getResultatNumero($Resultat,$i);
              if ($equiped==$equipe)
              {
                  //echo " a joué à domicile";
                  if ($ResultatMatch=="1")       $Score=$Score+($Dom1* ((100-$decoteDepart)/100) );
                  if ($ResultatMatch=="N")       $Score=$Score+($DomN* ((100-$decoteDepart)/100) );
                  if ($ResultatMatch=="2")       $Score=$Score+($Dom2* ((100-$decoteDepart)/100) );
              }                 
              if ($equipev==$equipe)
              {
                  //echo " a joué à l'extérieur";
                  if ($ResultatMatch=="2")       $Score=$Score+($Ext1* ((100-$decoteDepart)/100) );
                  if ($ResultatMatch=="N")       $Score=$Score+($ExtN* ((100-$decoteDepart)/100) );
                  if ($ResultatMatch=="1")       $Score=$Score+($Ext2* ((100-$decoteDepart)/100) );                   
              } 
              break;
           }  
        }
        
        //echo $equipe." : $Score points.<br />";        
                
        // On augmente la décote
        $decoteDepart = $decoteDepart+$decoteInitiale;
    }

    if ($NbJourneeOuEquipeAEteTrouve!=$nbJeuxAAnalyser)
    {
        
        // Il manque des jours, on fait la moyenne des matchs trouvés
        if ($NbJourneeOuEquipeAEteTrouve>=1)

            $Score = $Score/$NbJourneeOuEquipeAEteTrouve*$nbJeuxAAnalyser;

        // Rien n'a été trouvé ! On met le score à -1 pour qu'il ne soit pas pris en compte !!
        if ($NbJourneeOuEquipeAEteTrouve==0)
            $Score=-1;
    }    
//    else 
//        echo "Total de l'équipe ". $equipe." : $Score points.<br />";

    return $Score;
}


function getPronostics()
{
    global $tauxMajorationDomicile;
    global $ecartPoint;
    
    // Il faut au moins 2 jeux !
    $listeJeux = getListeJeux();
    if (sizeof($listeJeux)<=1)          break;

    // Recherche le jeu courant 
	  $jeu = getJeuCourant();
	  if ($jeu)
	  {
        $jeu_id = $jeu["jeu_id"];
        
        // Jeu à 14 ou 15 matchs ?
        $nbMatchsDeCeJeu  = getNbMatchsDeCeJeu($jeu);
        
        // Affiche tous les matchs
        for($i=1; $i <= $nbMatchsDeCeJeu ; $i++) 
        {
          // Lecture de l'équipe qui reçoit de ce match  
          $equiped     = $jeu["equipe".$i."d"];
          $analysed     = getAnalyse($equiped, $listeJeux) * ((100+$tauxMajorationDomicile)/100); 
          
          // Lecture de l'équipe qui se déplace de ce match  
          $equipev     = $jeu["equipe".$i."v"];
          $analysev     = getAnalyse($equipev, $listeJeux);


          echo "$i : $equiped : $analysed pts / $equipev : $analysev pts.";
          $resultatPronostic="";
          if (($analysed-$ecartPoint)>$analysev)            $resultatPronostic="1";
          else if (($analysev-$ecartPoint)>$analysed)       $resultatPronostic="2";
          else                                              $resultatPronostic="N";
          if ($analysev>-1 && $analysed>-1)
                echo " donne $resultatPronostic<br>";
          else 
                echo "<br>";
        }
	  }
}



function getPronostic($i)
{
    global $tauxMajorationDomicile;
    global $ecartPoint;
    // Il faut au moins 2 jeux !
    $listeJeux = getListeJeux();   
    return "";
    echo "1". $listeJeux."<br />";
    return "";
    if (sizeof($listeJeux)<=1)          return "";

    // Recherche le jeu courant 
	  $jeu = getJeuCourant();
	  if ($jeu)
	  {
        $jeu_id = $jeu["jeu_id"];
        
        // Jeu à 14 ou 15 matchs ?
        $nbMatchsDeCeJeu  = getNbMatchsDeCeJeu($jeu);
        
        // Affiche tous les matchs
//        for($i=1; $i <= $nbMatchsDeCeJeu ; $i++) 
//        {
          // Lecture de l'équipe qui reçoit de ce match  
          $equiped     = $jeu["equipe".$i."d"];
          $analysed     = getAnalyse($equiped, $listeJeux) * ((100+$tauxMajorationDomicile)/100); 
          
          // Lecture de l'équipe qui se déplace de ce match  
          $equipev     = $jeu["equipe".$i."v"];
          $analysev     = getAnalyse($equipev, $listeJeux);


//          echo "$i : $equiped : $analysed pts / $equipev : $analysev pts.";
          $resultatPronostic="";
          if (($analysed-$ecartPoint)>$analysev)            $resultatPronostic="1";
          else if (($analysev-$ecartPoint)>$analysed)       $resultatPronostic="2";
          else                                              $resultatPronostic="N";
          if ($analysev>-1 && $analysed>-1)
                return $resultatPronostic;
          else 
                return "";
//        }
	  }
}


function getCoteMatch($equiped, $equipev)
{
  // Requete pour retrouver tous les joueurs dans l'ordre d'insertion en base
  $sql = "select * from cotematch where equiped='$equiped' and equipev='$equipev'";
  
  $listeCoteMatch = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{
  	  // Lecture de ce coté match
   	  $CoteMatch = mysql_fetch_array($result);
 			if ($CoteMatch)
 			{
 			    $listeCoteMatch[0]=$CoteMatch["cote1"];
 			    $listeCoteMatch[1]=$CoteMatch["cote2"];
 			    $listeCoteMatch[2]=$CoteMatch["cote3"];
 			    $listeCoteMatch[3]=$CoteMatch["cote4"];
 			}
 			return $listeCoteMatch;
  }
  return "";
}


function getListeCoteMatchs()
{
  
  // Requete pour retrouver tous les jeux de la saison courante dans l'ordre d'insertion en base
  $sql = "select * from cotematch order by cote_id asc";
  
  $cotematchs = array();
  // Exécution de la requête 
  if($result = mysql_query($sql))
	{
  	  // Lecture de ce jeu
   	  $cpt=0;
   	  while( $cotematch = mysql_fetch_array($result) )
 			{
 			    if ($cotematch)
 			    {
 			      $cotematchs[$cpt]=$cotematch;
            $cpt++;
          } 
 			}
 			return $cotematchs;
  }
  return "";
}


function getCoteMatchSiteFDJ()
{
      // Suppression de toutes les lignes 
      mysql_query("DELETE FROM cotematch");

      $html="";
     $fp = fopen("http://www.fdjeux.com/jeux/cotematch/cotematch_s_resultats.php", "r"); // lecture de la page
  
      while (false !== ($char = fgetc($fp))) {
          $html .= "$char";
      }
      
      fclose($fp);
      
      $output = array();
      $output = explode("\n", $html);
      
      $match1="";
      $match2="";
      $resultat1="";
      $resultat2="";
      $resultat3="";
      $resultat4="";
      $cpt=1;
      for ($i=1;$i<sizeof($output);$i++)
      {
  
          if ( substr($output[$i],0,44)=="<td align='left'><a href='stats.php?idmatch=" )
          {
              $tmp = substr($output[$i],71,strlen($output[$i])-80);
              $tmp=addslashes($tmp);
              if ($match1 == "")
                  $match1=$tmp;
              else
                  $match2=$tmp;
          }
          if ($match1!="" && $match2!="" && substr($output[$i],0,19)=="<td align='center'>" )
          {
              $tmp = substr($output[$i],19,strlen($output[$i])-24);
              $tmp = ereg_replace("&nbsp;"," ",$tmp); 
              if ($resultat1 == "")
                  $resultat1=$tmp;
              else if ($resultat2 == "")
                  $resultat2=$tmp; 
              else if ($resultat3 == "")
                  $resultat3=$tmp;                 
              else 
                  $resultat4=$tmp;   
          }
          if ($match1!="" && $match2!="" && $resultat1!="" && $resultat2!="" && $resultat3!="" && $resultat4!="")
          {
              // echo "+++$cpt++++[".$match1." vs ".$match2."  (1:".$resultat1.")(2:".$resultat2.")(3:".$resultat3.")(4:".$resultat4.")]<br />\n";
              mysql_query("insert into cotematch ( cote_id, equiped, equipev, cote1, cote2, cote3, cote4 ) 
                  values ($cpt, '$match1', '$match2', '$resultat1', '$resultat2', '$resultat3', '$resultat4')");
              $cpt++;
              $match1="";
              $match2="";
              $resultat1="";
              $resultat2="";
              $resultat3="";
              $resultat4="";
          }

      }            
}


?>
