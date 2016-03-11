<?php
session_start();
ini_set('zlib.output_compression_level', 6);
//ob_start('ob_gzhandler');
?>
	<?php include("scripts/head.php");	?>
	
	<body>    
  <?php include ("scripts/debutpage.php"); ?>
            
	<div id="conteneur">	

    <?php include ("scripts/header.php"); ?>
    
    <?php include ("scripts/menu.php"); ?>
    <?php include ("scripts/fichierJeux.php"); ?>
    
    <?php
    
    
    
    function stripAccents($string){
	return strtr($string,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}
    
    
    // On affiche cette page uniquement si on est connecté et administrateur
    if (!isset($_SESSION['id_joueur']) || $_SESSION['admin']!='O') 
    {
      // Authentification correcte
      header('Location: index.php');
    }
    
    // Ouverture de la base de données
    $db_link = ouvre_base();
    if ($db_link)
    {
        $operation="";
        $jeu_id="";
        $titre="";
        $bloque="";
		$invisible=0;
		$idSite="";
        $commentaire="";
        $equipe1d="";
        $equipe1v="";
        $equipe2d="";
        $equipe2v="";
        $equipe3d="";
        $equipe3v="";
        $equipe4d="";
        $equipe4v="";
        $equipe5d="";
        $equipe5v="";
        $equipe6d="";
        $equipe6v="";
        $equipe7d="";
        $equipe7v="";
        $equipe8d="";
        $equipe8v="";
        $equipe9d="";
        $equipe9v="";
        $equipe10d="";
        $equipe10v="";
        $equipe11d="";
        $equipe11v="";
        $equipe12d="";
        $equipe12v="";
        $equipe13d="";
        $equipe13v="";
        $equipe14d="";
        $equipe14v="";
        $equipe15d="";
        $equipe15v="";
		

        // Dans l'url se trouve éventuellement l'identifiant de l'objet à modifier
        if (isset($_GET['jeu'])) 
        {
          if (is_numeric($_GET['jeu']))
          {
            // Authentification correcte
            $jeu_id=$_GET['jeu'];
            $jeu = getJeu($jeu_id);
            if ($jeu)
            {
              $titre     = stripslashes($jeu["titre"]);
              $bloque     = stripslashes($jeu["bloque"]);
			  $invisible     = stripslashes($jeu["invisible"]);
			  $idSite     = stripslashes($jeu["idSite"]);
              $commentaire = stripslashes($jeu["commentaire"]);
              $equipe1d  = stripslashes($jeu["equipe1d"]);
              $equipe1v  = stripslashes($jeu["equipe1v"]);
              $equipe2d  = stripslashes($jeu["equipe2d"]);
              $equipe2v  = stripslashes($jeu["equipe2v"]);
              $equipe3d  = stripslashes($jeu["equipe3d"]);
              $equipe3v  = stripslashes($jeu["equipe3v"]);
              $equipe4d  = stripslashes($jeu["equipe4d"]);
              $equipe4v  = stripslashes($jeu["equipe4v"]);
              $equipe5d  = stripslashes($jeu["equipe5d"]);
              $equipe5v  = stripslashes($jeu["equipe5v"]);
              $equipe6d  = stripslashes($jeu["equipe6d"]);
              $equipe6v  = stripslashes($jeu["equipe6v"]);
              $equipe7d  = stripslashes($jeu["equipe7d"]);
              $equipe7v  = stripslashes($jeu["equipe7v"]);
              $equipe8d  = stripslashes($jeu["equipe8d"]);
              $equipe8v  = stripslashes($jeu["equipe8v"]);
              $equipe9d  = stripslashes($jeu["equipe9d"]);
              $equipe9v  = stripslashes($jeu["equipe9v"]);
              $equipe10d = stripslashes($jeu["equipe10d"]);
              $equipe10v = stripslashes($jeu["equipe10v"]);
              $equipe11d = stripslashes($jeu["equipe11d"]);
              $equipe11v = stripslashes($jeu["equipe11v"]);
              $equipe12d = stripslashes($jeu["equipe12d"]);
              $equipe12v = stripslashes($jeu["equipe12v"]);
              $equipe13d = stripslashes($jeu["equipe13d"]);
              $equipe13v = stripslashes($jeu["equipe13v"]);
              $equipe14d = stripslashes($jeu["equipe14d"]);
              $equipe14v = stripslashes($jeu["equipe14v"]);
              $equipe15d = stripslashes($jeu["equipe15d"]);
              $equipe15v = stripslashes($jeu["equipe15v"]);
            }
          }
        }
        // Dans le cas contraire, on lit les données depuis le formulaire
        else
        {     
            // Récupération des variables
            if (isset($_POST["jeu_id"]))
            {
              $jeu_id=stripslashes($_POST["jeu_id"]);
            }    
            if (isset($_POST["titre"]))
            {
              $titre=stripslashes($_POST["titre"]);
            }
            if (isset($_POST["bloque"]))
            {
              $bloque=0;
              if ($_POST["bloque"]=='on')
              {
                  $bloque=1;
              }
            }
			 if (isset($_POST["invisible"]))
            {
              $invisible=0;
              if ($_POST["invisible"]=='on')
              {
                  $invisible=1;
              }
            }
			 if (isset($_POST["idSite"]))
            {
              $idSite=stripslashes($_POST["idSite"]);
            } 
            if (isset($_POST["commentaire"]))
            {
              $commentaire=stripslashes($_POST["commentaire"]);
            }    
            if (isset($_POST["equipe1d"]))
            {
              $equipe1d=stripslashes($_POST["equipe1d"]);
            }    
            if (isset($_POST["equipe1v"]))
            {
              $equipe1v=stripslashes($_POST["equipe1v"]);
            }    
            if (isset($_POST["equipe2d"]))
            {
              $equipe2d=stripslashes($_POST["equipe2d"]);
            }    
            if (isset($_POST["equipe2v"]))
            {
              $equipe2v=stripslashes($_POST["equipe2v"]);
            }    
            if (isset($_POST["equipe3d"]))
            {
              $equipe3d=stripslashes($_POST["equipe3d"]);
            }    
            if (isset($_POST["equipe3v"]))
            {
              $equipe3v=stripslashes($_POST["equipe3v"]);
            }    
            if (isset($_POST["equipe4d"]))
            {
              $equipe4d=stripslashes($_POST["equipe4d"]);
            }    
            if (isset($_POST["equipe4v"]))
            {
              $equipe4v=stripslashes($_POST["equipe4v"]);
            }    
            if (isset($_POST["equipe5d"]))
            {
              $equipe5d=stripslashes($_POST["equipe5d"]);
            }    
            if (isset($_POST["equipe5v"]))
            {
              $equipe5v=stripslashes($_POST["equipe5v"]);
            }    
            if (isset($_POST["equipe6d"]))
            {
              $equipe6d=stripslashes($_POST["equipe6d"]);
            }    
            if (isset($_POST["equipe6v"]))
            {
              $equipe6v=stripslashes($_POST["equipe6v"]);
            }      
            if (isset($_POST["equipe7d"]))
            {
              $equipe7d=stripslashes($_POST["equipe7d"]);
            }           
            if (isset($_POST["equipe7v"]))
            {
              $equipe7v=stripslashes($_POST["equipe7v"]);
            }
            if (isset($_POST["equipe8d"]))
            {
              $equipe8d=stripslashes($_POST["equipe8d"]);
            } 
            if (isset($_POST["equipe8v"]))
            {
              $equipe8v=stripslashes($_POST["equipe8v"]);
            }  
            if (isset($_POST["equipe9d"]))
            {
              $equipe9d=stripslashes($_POST["equipe9d"]);
            }    
            if (isset($_POST["equipe9v"]))
            {
              $equipe9v=stripslashes($_POST["equipe9v"]);
            }    
            if (isset($_POST["equipe10d"]))
            {
              $equipe10d=stripslashes($_POST["equipe10d"]);
            }    
            if (isset($_POST["equipe10v"]))
            {
              $equipe10v=stripslashes($_POST["equipe10v"]);
            }    
            if (isset($_POST["equipe11d"]))
            {
              $equipe11d=stripslashes($_POST["equipe11d"]);
            }    
            if (isset($_POST["equipe11v"]))
            {
              $equipe11v=stripslashes($_POST["equipe11v"]);
            }    
            if (isset($_POST["equipe12d"]))
            {
              $equipe12d=stripslashes($_POST["equipe12d"]);
            }    
            if (isset($_POST["equipe12v"]))
            {
              $equipe12v=stripslashes($_POST["equipe12v"]);
            }    
            if (isset($_POST["equipe13d"]))
            {
              $equipe13d=stripslashes($_POST["equipe13d"]);
            }    
            if (isset($_POST["equipe13v"]))
            {
              $equipe13v=stripslashes($_POST["equipe13v"]);
            }    
            if (isset($_POST["equipe14d"]))
            {
              $equipe14d=stripslashes($_POST["equipe14d"]);
            }    
            if (isset($_POST["equipe14v"]))
            {
              $equipe14v=stripslashes($_POST["equipe14v"]);
            }   
            if (isset($_POST["equipe15d"]))
            {
              $equipe15d=stripslashes($_POST["equipe15d"]);
            }    
            if (isset($_POST["equipe15v"]))
            {
              $equipe15v=stripslashes($_POST["equipe15v"]);
            }     
            if (isset($_POST["operation"]))
            {
              $operation=stripslashes($_POST["operation"]);
            }
            if (!$operation)
            {
            
                    $titre=$titretmp;
                    $commentaire=$commentairetmp;
                    $equipe1d=$equipe1dtmp;
                    $equipe2d=$equipe2dtmp;
                    $equipe3d=$equipe3dtmp;
                    $equipe4d=$equipe4dtmp;
                    $equipe5d=$equipe5dtmp;
                    $equipe6d=$equipe6dtmp;
                    $equipe7d=$equipe7dtmp;
                    $equipe8d=$equipe8dtmp;
                    $equipe9d=$equipe9dtmp;
                    $equipe10d=$equipe10dtmp;
                    $equipe11d=$equipe11dtmp;
                    $equipe12d=$equipe12dtmp;
                    $equipe13d=$equipe13dtmp;
                    $equipe14d=$equipe14dtmp;
                    $equipe15d=$equipe15dtmp;
                   
                    $equipe1v=$equipe1vtmp;
                    $equipe2v=$equipe2vtmp;
                    $equipe3v=$equipe3vtmp;
                    $equipe4v=$equipe4vtmp;
                    $equipe5v=$equipe5vtmp;
                    $equipe6v=$equipe6vtmp;
                    $equipe7v=$equipe7vtmp;
                    $equipe8v=$equipe8vtmp;
                    $equipe9v=$equipe9vtmp;
                    $equipe10v=$equipe10vtmp;
                    $equipe11v=$equipe11vtmp;
                    $equipe12v=$equipe12vtmp;
                    $equipe13v=$equipe13vtmp;
                    $equipe14v=$equipe14vtmp;
                    $equipe15v=$equipe15vtmp;
 
            }
        }
        
        if ($operation)
        {
			 if ($operation <> "N")
			 {
				  $jeu_id = setMiseAJourJeu($jeu_id,$titre, $bloque, $invisible, $commentaire,$equipe1d,$equipe1v,$equipe2d,$equipe2v,
							$equipe3d,$equipe3v,$equipe4d,$equipe4v,$equipe5d,$equipe5v,$equipe6d,$equipe6v,$equipe7d,
							$equipe7v,$equipe8d,$equipe8v,$equipe9d,$equipe9v,$equipe10d,$equipe10v,$equipe11d,$equipe11v,
							$equipe12d,$equipe12v,$equipe13d,$equipe13v,$equipe14d,$equipe14v,$equipe15d,$equipe15v,$operation,$idSite);                 
			}	
        }
        if ($jeu_id)
          $operation="M";
        else
        {
          $operation="C";
        }
		
		//supprimeUnJeu($jeu_id);
		
        ferme_base($db_link);
    }
    ?>
    
		<div id="contenu">
			
  		<h2>Administration des jeux</h2>
		  <p>Les jeux proposés correspondent au Loto Sportif de la Française des jeux; il n'y a donc qu'un jeu à la fois et les joueurs 
      n'ont la possibilité que de jouer sur le dernier jeu mis en ligne. Les jeux précédents ne sont plus accessible.</p>

      <a href="admin_jeu.php" class="miniAction">Mise à jour d'un jeu</a>
      
      <?php 
      if ($jeu_id) 
      {
  		    echo "<h3>Modification d'un jeu</h3>";
      }
      else
      {
  		    echo "<h3>Ajout d'un jeu</h3>";
      } 
          

      ?>
		  
      <form method="post" action="admin_jeu.php" class="formconnexion" name="form" >
        <input name="operation" type="hidden" value="<?php echo $operation ?>" /> 
        <input name="jeu_id" type="hidden" value="<?php echo $jeu_id ?>" />
        <table>
        <tr><td><label for="titre">titre:</label></td>
        <td><input name="titre" id="titre" type="text" size="20" maxlength="20" value="<?php echo $titre ?>" /></td>
        </tr>
        <tr><td><label for="bloque">Bloqué ? :</label></td>
        <td><input name="bloque" id="bloque" type="checkbox" <?php if ($bloque==1) echo 'checked' ?> /></td>
        </tr>
		<tr><td><label for="invisible">Jeu Invisible par tous ? :</label></td>
        <td><input name="invisible" id="invisible" type="checkbox" <?php if ($invisible==1) echo 'checked' ?> /></td>
        </tr>
		<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
        <tr><td><label for="commentaire">commentaire:</label></td>
        <td colspan="4"><input name="commentaire" id="commentaire" type="text" size="100" maxlength="100" value="<?php echo $commentaire ?>" /></td>
        </tr>
		<tr><td><label for="idSite">idSite:</label></td>
        <td colspan="4"><input name="idSite" id="idSite" type="text" size="5" maxlength="5" value="<?php echo $idSite ?>" /></td>
        </tr>
        <tr><td><label for="equipe1d">Match 1:</label></td>
        <td><input name="equipe1d" id="equipe1d" type="text" size="40" maxlength="40" value="<?php echo $equipe1d ?>" /></td>
        <td><label for="equipe1v"> &nbsp; </label></td>
        <td><input name="equipe1v" id="equipe1v" type="text" size="40" maxlength="40" value="<?php echo $equipe1v ?>" /></td>
        </tr>
        <tr><td><label for="equipe2d">Match 2:</label></td>
        <td><input name="equipe2d" id="equipe2d" type="text" size="40" maxlength="40" value="<?php echo $equipe2d ?>" /></td>
        <td><label for="equipe2v"> &nbsp; </label></td>
        <td><input name="equipe2v" id="equipe2v" type="text" size="40" maxlength="40" value="<?php echo $equipe2v ?>" /></td>
        </tr>
        <tr><td><label for="equipe3d">Match 3:</label></td>
        <td><input name="equipe3d" id="equipe3d" type="text" size="40" maxlength="40" value="<?php echo $equipe3d ?>" /></td>
        <td><label for="equipe3v"> &nbsp; </label></td>
        <td><input name="equipe3v" id="equipe3v" type="text" size="40" maxlength="40" value="<?php echo $equipe3v ?>" /></td>
        </tr>
        <tr><td><label for="equipe4d">Match 4:</label></td>
        <td><input name="equipe4d" id="equipe4d" type="text" size="40" maxlength="40" value="<?php echo $equipe4d ?>" /></td>
        <td><label for="equipe4v"> &nbsp; </label></td>
        <td><input name="equipe4v" id="equipe4v" type="text" size="40" maxlength="40" value="<?php echo $equipe4v ?>" /></td>
        </tr>
        <tr><td><label for="equipe5d">Match 5:</label></td>
        <td><input name="equipe5d" id="equipe5d" type="text" size="40" maxlength="40" value="<?php echo $equipe5d ?>" /></td>
        <td><label for="equipe5v"> &nbsp; </label></td>
        <td><input name="equipe5v" id="equipe5v" type="text" size="40" maxlength="40" value="<?php echo $equipe5v ?>" /></td>
        </tr>
        <tr><td><label for="equipe6d">Match 6:</label></td>
        <td><input name="equipe6d" id="equipe6d" type="text" size="40" maxlength="40" value="<?php echo $equipe6d ?>" /></td>
        <td><label for="equipe6v"> &nbsp; </label></td>
        <td><input name="equipe6v" id="equipe6v" type="text" size="40" maxlength="40" value="<?php echo $equipe6v ?>" /></td>
        </tr>
        <tr><td><label for="equipe7d">Match 7:</label></td>
        <td><input name="equipe7d" id="equipe7d" type="text" size="40" maxlength="40" value="<?php echo $equipe7d ?>" /></td>
        <td><label for="equipe7v"> &nbsp; </label></td>
        <td><input name="equipe7v" id="equipe7v" type="text" size="40" maxlength="40" value="<?php echo $equipe7v ?>" /></td>
        </tr>
        <tr><td><label for="equipe8d">Match 8:</label></td>
        <td><input name="equipe8d" id="equipe8d" type="text" size="40" maxlength="40" value="<?php echo $equipe8d ?>" /></td>
        <td><label for="equipe8v"> &nbsp; </label></td>
        <td><input name="equipe8v" id="equipe8v" type="text" size="40" maxlength="40" value="<?php echo $equipe8v ?>" /></td>
        </tr>
        <tr><td><label for="equipe9d">Match 9:</label></td>
        <td><input name="equipe9d" id="equipe9d" type="text" size="40" maxlength="40" value="<?php echo $equipe9d ?>" /></td>
        <td><label for="equipe9v"> &nbsp; </label></td>
        <td><input name="equipe9v" id="equipe9v" type="text" size="40" maxlength="40" value="<?php echo $equipe9v ?>" /></td>
        </tr>
        <tr><td><label for="equipe10d">Match 10:</label></td>
        <td><input name="equipe10d" id="equipe10d" type="text" size="40" maxlength="40" value="<?php echo $equipe10d ?>" /></td>
        <td><label for="equipe11v"> &nbsp; </label></td>
        <td><input name="equipe10v" id="equipe10v" type="text" size="40" maxlength="40" value="<?php echo $equipe10v ?>" /></td>
        </tr>
        <tr><td><label for="equipe11d">Match 11:</label></td>
        <td><input name="equipe11d" id="equipe11d" type="text" size="40" maxlength="40" value="<?php echo $equipe11d ?>" /></td>
        <td><label for="equipe1d"> &nbsp; </label></td>
        <td><input name="equipe11v" id="equipe11v" type="text" size="40" maxlength="40" value="<?php echo $equipe11v ?>" /></td>
        </tr>
        <tr><td><label for="equipe12d">Match 12:</label></td>
        <td><input name="equipe12d" id="equipe12d" type="text" size="40" maxlength="40" value="<?php echo $equipe12d ?>" /></td>
        <td><label for="equipe12v"> &nbsp; </label></td>
        <td><input name="equipe12v" id="equipe12v" type="text" size="40" maxlength="40" value="<?php echo $equipe12v ?>" /></td>
        </tr>
        <tr><td><label for="equipe13d">Match 13:</label></td>
        <td><input name="equipe13d" id="equipe13d" type="text" size="40" maxlength="40" value="<?php echo $equipe13d ?>" /></td>
        <td><label for="equipe13v"> &nbsp; </label></td>
        <td><input name="equipe13v" id="equipe13v" type="text" size="40" maxlength="40" value="<?php echo $equipe13v ?>" /></td>
        </tr>
        <tr><td><label for="equipe14d">Match 14:</label></td>
        <td><input name="equipe14d" id="equipe14d" type="text" size="40" maxlength="40" value="<?php echo $equipe14d ?>" /></td>
        <td><label for="equipe14v"> &nbsp; </label></td>
        <td><input name="equipe14v" id="equipe14v" type="text" size="40" maxlength="40" value="<?php echo $equipe14v ?>" /></td>
        </tr>
        <tr><td><label for="equipe15d">Match 15:</label></td>
        <td><input name="equipe15d" id="equipe15d" type="text" size="40" maxlength="40" value="<?php echo $equipe15d ?>" /></td>
        <td><label for="equipe15v"> &nbsp; </label></td>
        <td><input name="equipe15v" id="equipe15v" type="text" size="40" maxlength="40" value="<?php echo $equipe15v ?>" /></td>
        </tr>
        </table>
        <input type="submit" value="Valider" class="bouton" />
      </form>
    </div>
	
	
		
   <?php include ("scripts/footer.php"); ?>
	</div>
	</body>
	
</html>
