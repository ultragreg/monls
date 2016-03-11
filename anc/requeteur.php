<?php 
session_start();
ini_set('zlib.output_compression_level', 6);
//ob_start('ob_gzhandler');

function getReqResultat ($e, $pos)
{
    if ($pos==1)  return   stripslashes($e["resultat1"]);
    if ($pos==2)  return   stripslashes($e["resultat2"]);
    if ($pos==3)  return   stripslashes($e["resultat3"]);
    if ($pos==4)  return   stripslashes($e["resultat4"]);
    if ($pos==5)  return   stripslashes($e["resultat5"]);
    if ($pos==6)  return   stripslashes($e["resultat6"]);
    if ($pos==7)  return   stripslashes($e["resultat7"]);
    if ($pos==8)  return   stripslashes($e["resultat8"]);
    if ($pos==9)  return   stripslashes($e["resultat9"]);
    if ($pos==10)  return   stripslashes($e["resultat10"]);
    if ($pos==11)  return   stripslashes($e["resultat11"]);
    if ($pos==12)  return   stripslashes($e["resultat12"]);
    if ($pos==13)  return   stripslashes($e["resultat13"]);
    if ($pos==14)  return   stripslashes($e["resultat14"]);
    if ($pos==15)  return   stripslashes($e["resultat15"]);
    return "";
}

function getReqPronostic ($e, $pos)
{
    if ($pos==1)  return   stripslashes($e["pronostic1"]);
    if ($pos==2)  return   stripslashes($e["pronostic2"]);
    if ($pos==3)  return   stripslashes($e["pronostic3"]);
    if ($pos==4)  return   stripslashes($e["pronostic4"]);
    if ($pos==5)  return   stripslashes($e["pronostic5"]);
    if ($pos==6)  return   stripslashes($e["pronostic6"]);
    if ($pos==7)  return   stripslashes($e["pronostic7"]);
    if ($pos==8)  return   stripslashes($e["pronostic8"]);
    if ($pos==9)  return   stripslashes($e["pronostic9"]);
    if ($pos==10)  return   stripslashes($e["pronostic10"]);
    if ($pos==11)  return   stripslashes($e["pronostic11"]);
    if ($pos==12)  return   stripslashes($e["pronostic12"]);
    if ($pos==13)  return   stripslashes($e["pronostic13"]);
    if ($pos==14)  return   stripslashes($e["pronostic14"]);
    if ($pos==15)  return   stripslashes($e["pronostic15"]);
    return "";
}

function getReqEquipes ($e, $search, $pos)
{
  $search=strtoupper($search);
  $e1="";
  $e2="";
  $e1d          = stripslashes($e["equipe1d"]);
  $e1v          = stripslashes($e["equipe1v"]);
  $e2d          = stripslashes($e["equipe2d"]);
  $e2v          = stripslashes($e["equipe2v"]);
  $e3d          = stripslashes($e["equipe3d"]);
  $e3v          = stripslashes($e["equipe3v"]);
  $e4d          = stripslashes($e["equipe4d"]);
  $e4v          = stripslashes($e["equipe4v"]);
  $e5d          = stripslashes($e["equipe5d"]);
  $e5v          = stripslashes($e["equipe5v"]);
  $e6d          = stripslashes($e["equipe6d"]);
  $e6v          = stripslashes($e["equipe6v"]);
  $e7d          = stripslashes($e["equipe7d"]);
  $e7v          = stripslashes($e["equipe7v"]);
  $e8d          = stripslashes($e["equipe8d"]);
  $e8v          = stripslashes($e["equipe8v"]);
  $e9d          = stripslashes($e["equipe9d"]);
  $e9v          = stripslashes($e["equipe9v"]);
  $e10d          = stripslashes($e["equipe10d"]);
  $e10v          = stripslashes($e["equipe10v"]);
  $e11d          = stripslashes($e["equipe11d"]);
  $e11v          = stripslashes($e["equipe11v"]);
  $e12d          = stripslashes($e["equipe12d"]);
  $e12v          = stripslashes($e["equipe12v"]);
  $e13d          = stripslashes($e["equipe13d"]);
  $e13v          = stripslashes($e["equipe13v"]);
  $e14d          = stripslashes($e["equipe14d"]);
  $e14v          = stripslashes($e["equipe14v"]);
  $e15d          = stripslashes($e["equipe15d"]);
  $e15v          = stripslashes($e["equipe15v"]);  
  if ( ($pos==1) AND ( ($search=="") OR (strpos(strtoupper($e1d),$search) !== false OR strpos(strtoupper($e1v),$search) !== false) ) ) {
      return array($e1d, $e1v,1);
  }  
  if ( ($pos==2) AND ( ($search=="") OR (strpos(strtoupper($e2d),$search) !== false OR strpos(strtoupper($e2v),$search) !== false) ) ) {
      return array($e2d, $e2v,2);
  } 
  if ( ($pos==3) AND ( ($search=="") OR (strpos(strtoupper($e3d),$search) !== false OR strpos(strtoupper($e3v),$search) !== false) ) ) {
      return array($e3d, $e3v,3);
  }
  if ( ($pos==4) AND ( ($search=="") OR (strpos(strtoupper($e4d),$search) !== false OR strpos(strtoupper($e4v),$search) !== false) ) ) {
      return array($e4d, $e4v,4);
  }
  if ( ($pos==5) AND ( ($search=="") OR (strpos(strtoupper($e5d),$search) !== false OR strpos(strtoupper($e5v),$search) !== false) ) ) {
      return array($e5d, $e5v,5);
  }
  if ( ($pos==6) AND ( ($search=="") OR (strpos(strtoupper($e6d),$search) !== false OR strpos(strtoupper($e6v),$search) !== false) ) ) {
      return array($e6d, $e6v,6);
  }
  if ( ($pos==7) AND ( ($search=="") OR (strpos(strtoupper($e7d),$search) !== false OR strpos(strtoupper($e7v),$search) !== false) ) ) {
      return array($e7d, $e7v,7);
  }
  if ( ($pos==8) AND ( ($search=="") OR (strpos(strtoupper($e8d),$search) !== false OR strpos(strtoupper($e8v),$search) !== false) ) ) {
      return array($e8d, $e8v,8);
  }
  if ( ($pos==9) AND ( ($search=="") OR (strpos(strtoupper($e9d),$search) !== false OR strpos(strtoupper($e9v),$search) !== false) ) ) {
      return array($e9d, $e9v,9);
  }
  if ( ($pos==10) AND ( ($search=="") OR (strpos(strtoupper($e10d),$search) !== false OR strpos(strtoupper($e10v),$search) !== false) ) ) {
      return array($e10d, $e10v,10);
  }
  if ( ($pos==11) AND ( ($search=="") OR (strpos(strtoupper($e11d),$search) !== false OR strpos(strtoupper($e11v),$search) !== false) ) ) {
      return array($e11d, $e11v,11);
  }
  if ( ($pos==12) AND ( ($search=="") OR (strpos(strtoupper($e12d),$search) !== false OR strpos(strtoupper($e12v),$search) !== false) ) ) {
      return array($e12d, $e12v,12);
  }
  if ( ($pos==13) AND ( ($search=="") OR (strpos(strtoupper($e13d),$search) !== false OR strpos(strtoupper($e13v),$search) !== false) ) ) {
      return array($e13d, $e13v,13);
  }
  if ( ($pos==14) AND ( ($search=="") OR (strpos(strtoupper($e14d),$search) !== false OR strpos(strtoupper($e14v),$search) !== false) ) ) {
      return array($e14d, $e14v,14);
  }
  if ( ($pos==15) AND ( ($search=="") OR (strpos(strtoupper($e15d),$search) !== false OR strpos(strtoupper($e15v),$search) !== false) ) ) {
      return array($e15d, $e15v,15);
  }
  return array("","",0);
}


?>
  <?php  include("scripts/head.php"); ?>
  
  <body>    
  <?php include ("scripts/debutpage.php"); ?>

  <?php 
    // On affiche cette page uniquement si on est connecté 
    if (!isset($_SESSION['id_joueur']) || $_SESSION['admin']!='O') 
    {
      // Authentification correcte
      header('Location: index.php');
    }
  ?>          
  
  <div id="conteneur">  

    <?php include ("scripts/header.php"); ?>
    
    <?php include ("scripts/menu.php"); ?>
    
     <div id="contenu">
      
      <h2>Requeteur</h2>
      <p>Consultation des données en BD</p>

      <?php        
      $listeJoueurs = "";
      $listeSaisons = "";
      // On précharge la liste des joueurs !
         $db_link = ouvre_base();
         if ($db_link)
         {
            $listeJoueurs = getListeJoueurs();
            $listeSaisons = getListeSaisons();
            ferme_base($db_link);
          }
        $saison_id="-1";
        $joueur_id="-1";
        $eq="";
         // Récupération des variables
        if (isset($_POST["saison_id"]))
        {
          $saison_id=stripslashes($_POST["saison_id"]);
        }
        if (isset($_POST["joueur_id"]))
        {
          $joueur_id=stripslashes($_POST["joueur_id"]);
        } 
        if (isset($_POST["equipe"]))
        {
          $eq=stripslashes($_POST["equipe"]);
        }             
      ?>
      
      <div id="mini">
      <form method="post" action="requeteur.php" class="formconnexion" >
        <table>                  
        <tr><td><label for="saison_id">Saison : </label></td>
        <td><select id="saison_id" name="saison_id" style="width:200px" >
        <?php
           echo "<option value=\"-1\">Toutes les saisons</option>";

           for($i=0;$i<sizeof($listeSaisons);$i++)
           {
              $saison=$listeSaisons[$i];
              // Lecture des propriétés du joueur
              $sid       = $saison["saison_id"];
              $nom       = $saison["nom"];
              
              echo "<option value=\"".$sid."\"";
              if ($sid==$saison_id)
                  echo ' selected="selected" >';
              else
                  echo ">";
              echo $nom;
              echo "</option>";
            }
        ?>
        </select></td>
        </tr>
        <tr><td><label for="joueur_id">Joueur : </label></td>
        <td><select id="joueur_id" name="joueur_id" style="width:200px" >
        <?php
           echo "<option value=\"-1\">Tous les joueurs</option>";
           for($i=0;$i<sizeof($listeJoueurs);$i++)
           {
              $joueur=$listeJoueurs[$i];
              // Lecture des propriétés du joueur
              $jid       = $joueur["joueur_id"];
              $nom       = $joueur["nom"];
              
              echo "<option value=\"".$jid."\"";
              if ($jid==$joueur_id)
                  echo ' selected="selected" >';
              else
                  echo ">";
              echo $nom;
              echo "</option>";
            }
        ?>
        </select></td>
        </tr>
        <tr><td><label for="equipe">Equipe :</label></td>
        <td><input id="equipe" name="equipe" type="text"  value="<?php echo $eq ?>" /></td>
        </tr>
        <?php
          if ($saison_id==-1 AND $joueur_id==-1) {
             echo "<tr><td>&nbsp;</td><td>Il faut au moins faire un choix entre une saison ou un joueur</td></tr>";
          }
        ?>
        </table>
        <p>
        <input type="submit" value="Lancer" class="bouton" />
        </p>
      </form>
      </div>

   <?php 
    
    // Ouverture de la base de données
    $db_link = ouvre_base();
    if ($db_link)
    {
     
        if ($saison_id!="-1" OR $joueur_id!="-1") {

          $listeEnreg = getRequete($saison_id, $joueur_id, $eq);
          ?>
          <table class="jeu">
          <td class="infotitre">Saison</td>
          <td class="infotitre">Nom du joueur</td>
          <td class="infotitre">Nom du jeu</td>
          <td class="infotitre">Num. Match</td>
          <td class="infotitre">Equipe Domicile</td>
          <td class="infotitre">Equipe Visiteuse</td>
          <td class="infotitre">Pronostic</td>
          <td class="infotitre">Résultat</td>
          </tr>
          <?php 
          $saison_nomz="";
          $joueur_nomz="";          
          $affs="";
          $affj="";
          for($i=0;$i<sizeof($listeEnreg);$i++)
          {
                $e=$listeEnreg[$i];
                // Lecture des propriétés 
                $saison_nom   = stripslashes($e["sanom"]);
                $joueur_id    = stripslashes($e["joid"]);
                $joueur_nom   = stripslashes($e["jonom"]);
                $jeu_id       = stripslashes($e["jeid"]);
                $jeu_titre    = stripslashes($e["jetitre"]);
                for($j=0;$j<=15;$j++)
                {
                    // Recherche des noms des équipes du match dont l'une des deux équipes est le critère
                    $equipes = getReqEquipes($e, $eq,$j);
                    if ($equipes[0]!="" AND $equipes[1]!= "" AND $equipes[2]!= 0)
                    {
                        $res = getReqResultat($e, $equipes[2]);
                        $pro = getReqPronostic($e, $equipes[2]);

                        if ($joueur_nom == $joueur_nomz) { $affj = ""; } else { $joueur_nomz = $joueur_nom; $affj = $joueur_nom; }
                        if ($saison_nom == $saison_nomz) { $affs = ""; } else { $saison_nomz = $saison_nom; $affs = $saison_nom; $affj = $joueur_nom;}

                        if ($i%2 == 1) 
                            echo "<tr class='enreg impaire'>";
                        else
                            echo "<tr class='enreg'>";
                        echo "<td width=\"180\">".$affs."</td>\n";   
                        echo "<td width=\"180\">".$affj."</td>\n";
                        echo "<td width=\"200\">".$jeu_titre."</td>\n";
                        echo "<td width=\"60\">".$j."</td>\n";
                        echo "<td width=\"250\">".$equipes[0]."</td>\n";
                        echo "<td width=\"250\">".$equipes[1]."</td>\n";
                        echo "<td width=\"60\">".$pro."</td>\n";
                        echo "<td width=\"60\">".$res."</td>\n";
                        echo "</tr>\n";
                    }
              }
          }
          ?>
          </table>
          <?php
        }
        ferme_base($db_link);
    }
    ?>

    </div>
    <?php include ("scripts/footer.php"); ?>
  </div>
  </body>
  
</html>
