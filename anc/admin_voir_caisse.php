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
    ?>    

		<div id="contenu">
			
  		<h2>Administration des caisses</h2>

      <?php

        // Ouverture de la base de données
        $db_link = ouvre_base();
        if ($db_link)
        {
          // Dans l'url se trouve éventuellement l'identifiant de l'objet à modifier
          if (isset($_GET['saison'])) 
          {
              if (is_numeric($_GET['saison']))
              {
                  $caisse_id=$_GET['saison'];
                  echo "<h3>Visu de la caisse pour la saison " . $caisse_id . "</h3>";
                  // Authentification correcte
                  $listeOperations = getListeOperationsCaisseSaison($caisse_id);
                  ?>
                  <table class="jeu">
                   <tr>
                     <td width="310" class="infotitre">Libell&eacute;</td>
                     <td width="150" class="infotitre">Date</td>
                     <td width="150" class="infotitre">D&eacute;bit</td>
                     <td width="150" class="infotitre">Cr&eacute;dit</td>
                   </tr>
                   <?php 
                   $total = 0;
                   for($i=0;$i<sizeof($listeOperations) && !empty($listeOperations);$i++)
                   {
                    $caisse=$listeOperations[$i];
                // Lecture des propri‚t‚s du joueur
                    $caisse_libelle       = stripslashes($caisse["caisse_libelle"]);
                    $caisse_date          = stripslashes($caisse["caisse_date"]);
                    $caisse_somme_debit   = stripslashes($caisse["caisse_somme_debit"]);
                    $caisse_somme_credit  = stripslashes($caisse["caisse_somme_credit"]);
                    $total = $total -  $caisse_somme_debit +  $caisse_somme_credit;

                    if ($caisse_somme_debit==0)
                    {
                      $caisse_somme_debit="";
                    }    
                    else
                    {
                      $caisse_somme_debit=$caisse_somme_debit." &euro;";
                    }    

                    if ($caisse_somme_credit==0)   
                    {
                      $caisse_somme_credit="";
                    }    
                    else
                    {
                      $caisse_somme_credit=$caisse_somme_credit." &euro;";
                    }    

                    if ($i%2 != 1) 
                      echo "<tr class='enreg impaire'>";
                    else
                      echo "<tr class='enreg'>";

                    $j=$i+1;
                    echo "<td width=\"310\">".$caisse_libelle."</td>\n";
                    echo "<td width=\"150\">".formatte_date($caisse_date,1)."</td>\n";
                    echo "<td width=\"150\" style=\"padding-right: 10px;text-align: right;\">".$caisse_somme_debit."</td>\n";
                    echo "<td width=\"150\" style=\"padding-right: 10px;text-align: right;\">".$caisse_somme_credit."</td>\n";
                    echo "</tr>\n";
                  }
                  $tdebit="";
                  $tcredit="";
                  if ($total<0)    $tdebit=" - ".$total. " &euro;";
                  if ($total>=0)   $tcredit=$total." &euro;";
                  ?>
                  <tr><td>&nbsp;</td></tr>
                  <tr>
                   <td width="460" colspan="2">Total</td>
                   <td width="150" style="padding-right: 10px;text-align: right;"><?php echo $tdebit; ?></td>
                   <td width="150" style="padding-right: 10px;text-align: right;"><?php echo $tcredit; ?></td>
                 </tr>
               </table>
               <?php
            }
        }
        ferme_base($db_link);
      }

      ?>
		  

    </div>
		
    <?php include ("scripts/footer.php"); ?>
	</div>
	</body>
	
</html>
