<?php 
session_start();
ini_set('zlib.output_compression_level', 6);
//ob_start('ob_gzhandler');
?>
	<?php  include("scripts/head.php");	?>
	
	<body>    
  <?php include ("scripts/debutpage.php"); ?>
            
	<script type="text/javascript" src="js/calendar.js"></script>
	
	<div id="conteneur">	

    <?php include ("scripts/header.php"); ?>
    
    <?php include ("scripts/menu.php"); ?>
    
    <?php 
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
        $caisse_id="";
        $caisse_libelle="";
        $caisse_date="";
        $caisse_somme_debit="";
        $caisse_somme_credit="";
        // Dans l'url se trouve éventuellement l'identifiant de l'objet à modifier
        if (isset($_GET['caisse'])) 
        {
          if (is_numeric($_GET['caisse']))
          {
            // Authentification correcte
            $caisse_id=$_GET['caisse'];
            $caisse = getCaisse($caisse_id);
            if ($caisse)
            {
                // Lecture des propri‚t‚s du joueur
                $caisse_id           = stripslashes($caisse["caisse_id"]);
                $caisse_libelle      = stripslashes($caisse["caisse_libelle"]);
                $caisse_date         = stripslashes($caisse["caisse_date"]);
                $caisse_somme_debit  = stripslashes($caisse["caisse_somme_debit"]);
                $caisse_somme_credit = stripslashes($caisse["caisse_somme_credit"]);
                $date_annee = substr($caisse_date,0,4);
		            $date_mois = substr($caisse_date,5,2);
		            $date_jour = substr($caisse_date,8,2);
		            $caisse_date = $date_jour."/".$date_mois."/".$date_annee;
		            if ($caisse_date="0000-00-00")
		                $caisse_date=$date_jour_simple;
            }
          }
        }
        // Dans le cas contraire, on lit les données depuis le formulaire
        else
        {
            // Récupération des variables
            if (isset($_POST["caisse_id"]))
            {
              $caisse_id=stripslashes($_POST["caisse_id"]);
            }
            if (isset($_POST["caisse_libelle"]))
            {
              $caisse_libelle=stripslashes($_POST["caisse_libelle"]);
            }    
            if (isset($_POST["caisse_date"]))
            {
              $caisse_date=stripslashes($_POST["caisse_date"]);
            }    
            if (isset($_POST["caisse_somme_debit"]))
            {
              $caisse_somme_debit=stripslashes($_POST["caisse_somme_debit"]);
            }        
            if (isset($_POST["caisse_somme_credit"]))
            {
              $caisse_somme_credit=stripslashes($_POST["caisse_somme_credit"]);
            }                
            if (isset($_POST["operation"]))
            {
              $operation=stripslashes($_POST["operation"]);
            }
        }
        if ($operation)
        {
          $caisse_somme_credit = strtr($caisse_somme_credit, ",", ".");
          $caisse_somme_debit = strtr($caisse_somme_debit, ",", ".");
          $date_jour = substr($caisse_date,0,2);
		      $date_mois = substr($caisse_date,3,2);
		      $date_annee = substr($caisse_date,6,4);
		      $dateOk = $date_annee."/".$date_mois."/".$date_jour;
          $caisse_id = setMiseAJourCaisse($caisse_id,$caisse_libelle,$dateOk,$caisse_somme_debit,$caisse_somme_credit,$operation);
        }
        if ($caisse_id)
          $operation="M";
        else
        {
          $operation="C";
          $caisse_id="";
          $caisse_libelle="";
          $caisse_date=$date_jour_simple;
          $caisse_somme_credit="";
          $caisse_somme_debit="";
        }
    }
    ?>
    
		<div id="contenu">
			
  		<h2>Administration des caisses</h2>
		  <p>Cette page permet de gérer la caisse pour la saison en-cours</p>

      <a href="admin_caisse.php" class="miniAction">Ajout d'une opération en caisse</a>
      
      <?php        
      if ($caisse_id) 
      {
  		    echo "<h3>Modification d'une opération en caisse</h3>";
      }
      else
      {
  		    echo "<h3>Ajout d'une opération en caisse</h3>";
      } 
      ?>
		  
      <div id="mini">
      <form method="post" action="admin_caisse.php" class="formconnexion" >
        <p>
        <input name="operation" type="hidden" value="<?php  echo $operation ?>" />
        <input name="caisse_id" type="hidden" value="<?php  echo $caisse_id ?>" />
        </p>
        <table>                  
        <tr><td><label for="caisse_date">date:</label></td>
        <td><input id="date" name="caisse_date" type="text" value="<?php echo $caisse_date ?>" onfocus="this.select();lcs(this)" onclick="event.cancelBubble=true;this.select();lcs(this)" /></td>
        </tr>
        <tr><td><label for="caisse_libelle">libell&eacute; :</label></td>
        <td><input id="date" name="caisse_libelle" type="text" value="<?php echo $caisse_libelle ?>"  /></td>
        </tr>
        <tr><td><label for="caisse_somme_debit">somme d&eacute;bit :</label></td>
        <td><input id="caisse_somme_debit" name="caisse_somme_debit" type="text" size="10" maxlength="10" value="<?php  echo $caisse_somme_debit ?>" /></td>
        </tr>
        <tr><td><label for="caisse_somme_credit">somme cr&eacute;dit :</label></td>
        <td><input id="caisse_somme_credit" name="caisse_somme_credit" type="text" size="10" maxlength="10" value="<?php  echo $caisse_somme_credit ?>" /></td>
        </tr>
        </table>
        <p>
        <input type="submit" value="Valider" class="bouton" />
        </p>
      </form>
      </div>
    </div>
		
    <?php include ("scripts/footer.php"); ?>
	</div>
	</body>
	
</html>
