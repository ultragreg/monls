<?php 
session_start();
ini_set('zlib.output_compression_level', 6);
//ob_start('ob_gzhandler');
?>
	<?php  include("scripts/head.php");	?>
	
	<body>    
  <?php include ("scripts/debutpage.php"); ?>

	<script type="text/javascript" src="js/saisiejeu.js"></script>
	
	<div id="conteneur">		

    <?php include ("scripts/header.php"); ?>
    
    <?php include ("scripts/menu.php"); ?>
    
    <?php 
    // On affiche cette page uniquement si on est connecté 
    if (!isset($_SESSION['id_joueur']) || $_SESSION['admin']!='O') 
    {
      // Authentification correcte
      header('Location: index.php');
    }
    $joueur_id=$_SESSION['id_joueur'];
    ?>
    		    	
		<div id="contenu">
		  <?php 
		  $db_link = ouvre_base();
		  if ($db_link)
      {
          ?>          	  		 	
         <h2 id="top">Administration du site (<?php echo $url_du_site; ?>)</h2>
         
         <a href="#saison" class="miniAction">Les saisons</a>  	
         <a href="#jeu" class="miniAction">Les jeux</a>  	
         <a href="#gain" class="miniAction">Les gains</a>  	
         <a href="#caisse" class="miniAction">La caisse</a>  	
         <a href="#appel" class="miniAction">Les appels de fond</a> 
         <a href="#joueur" class="miniAction">Les joueurs</a>  	
         <a href="#sauvegarde" class="miniAction">Sauvegarde</a>  
         <a href="#statistique" class="miniAction">Statistiques</a>  	
         		 
         <?php        
			   // ==== Gestion des saisons ===== //
	  		 ?>
	  		 	
         <h2 id="saison">Les saisons</h2>
	        <p>Le concept de saison permet d'éditer le classement général pour lun championnat. Ainsi tous les jeux sont associés 
          à une saison; à chaque fois que l'on ajoute un jeu, il sera automatiquement associé à la saison la plus récente. </p>
         <a href="admin_saison.php" class="miniAction">Nouvelle saison</a>
         <table class="jeu">
         <tr><td>&nbsp;</td>
         <td class="infotitre">Id.</td>
         <td class="infotitre">Nom</td>
         <td class="infotitre">Commentaire</td>
         </tr>
         <?php 
         $listeSaisons = getListeSaisons();
         for($i=0;$i<sizeof($listeSaisons);$i++)
         {
            $saison=$listeSaisons[$i];
            // Lecture des propriétés du joueur
            $saison_id   = stripslashes($saison["saison_id"]);
            $nom         = stripslashes($saison["nom"]);
            $commentaire = stripslashes($saison["commentaire"]);

            if ($i%2 == 1) 
                echo "<tr class='enreg impaire'>";
            else
                echo "<tr class='enreg'>";

            $j=$i+1;
            echo "<td width=\"50\"><a href=\"admin_saison.php?saison=$saison_id\"><img src='img/edit.png' alt='edit'/></a></td>\n";   
            echo "<td width=\"050\">".$saison_id."</td>\n";   
            echo "<td width=\"200\">".$nom."</td>\n";
            echo "<td width=\"400\">".$commentaire."</td>\n";
            echo "</tr>\n";
         }
         ?>
         </table>
         <a href="#top" class="navigation">Haut</a> 
        
			   <?php 
			   // ==== Gestion des jeux ===== //
	  		 ?>
         <h2 id="jeu">Les jeux</h2> 
         <p>Les jeux proposés correspondent au Loto Sportif de la Française des jeux; il n'y a donc qu'un jeu à la fois et les 
         joueurs n'ont la possibilité que de jouer sur le dernier jeu mis en ligne. Les jeux précédents ne sont plus accessible.</p>
		 <a href="recup_grille.php" class="miniAction">Import d'un jeu</a>
         <a href="admin_jeu.php" class="miniAction">Mise en ligne d'un nouveau jeu</a>
         <a href="admin_rapports7.php" class="miniAction">Gestion des rapports à 7</a>
         <a href="admin_rapports15.php" class="miniAction">Gestion des rapports à 15</a>
         <table class="jeu">
         <tr><td>Edition</td>
         <td>Résultat</td>
         <td class="infotitre">Id.</td>
         <td class="infotitre">Titre</td>
         <td class="infotitre">Commentaire</td>
         </tr>
         <?php 
         $listeJeux = getListeJeux();
         for($i=0;$i<sizeof($listeJeux);$i++)
         {
            $jeu=$listeJeux[$i];
            // Lecture des propriétés du joueur
            $jeu_id      = stripslashes($jeu["jeu_id"]);
            $titre       = stripslashes($jeu["titre"]);
            $commentaire = stripslashes($jeu["commentaire"]);

            if ($i%2 == 1) 
                echo "<tr class='enreg impaire'>";
            else
                echo "<tr class='enreg'>";

            $j=$i+1;
            echo "<td width=\"50\"><a href=\"admin_jeu.php?jeu=$jeu_id\"><img src='img/edit.png' alt='édition'/></a></td>\n";   
            echo "<td width=\"50\"><a href=\"saisieresultat.php?idjeu=$jeu_id\"><img src='img/resultat.gif' alt='Résultat'/></a></td>\n";   
            echo "<td width=\"50\">".$jeu_id."</td>\n";   
            echo "<td width=\"200\">".$titre."</td>\n";
            echo "<td width=\"400\">".$commentaire."</td>\n";
            echo "</tr>\n";
         }
         ?>
         </table>
         <a href="#top" class="navigation">Haut</a> 
        
         <?php 
			   // ==== Gestion des gains ===== //
	  		 ?>
         <h2 id="gain">Les gains</h2> 
         <p>Cette partie concernent la gestion des gains de LS</p>
         <a href="admin_gain.php" class="miniAction">Ajout d'un nouveau gain</a>
         <table class="jeu">
         <tr><td>&nbsp;</td>
         <td class="infotitre">Id.</td>
         <td class="infotitre">Joueur</td>
         <td class="infotitre">Date</td>
         <td class="infotitre">Somme</td>
         </tr>
         <?php 
         $listeGains = getListeGains();
         for($i=0;$i<sizeof($listeGains);$i++)
         {
            $gain=$listeGains[$i];
            // Lecture des propriétés du joueur
            $gain_id      = stripslashes($gain["gain_id"]);
            $joueur_id    = stripslashes($gain["joueur_id"]);
            $date         = stripslashes($gain["date"]);
            $somme        = stripslashes($gain["somme"]);
                                   
            if ($i%2 == 1) 
                echo "<tr class='enreg impaire'>";
            else
                echo "<tr class='enreg'>";

            $j=$i+1;
            echo "<td width=\"50\"><a href=\"admin_gain.php?gain=$gain_id\"><img src='img/edit.png' alt='edit'/></a></td>\n";   
            echo "<td width=\"50\">".$gain_id."</td>\n";   
            echo "<td width=\"150\">".getNomJoueur($joueur_id)."</td>\n";
            echo "<td width=\"150\">".formatte_date($date,1)."</td>\n";
            echo "<td width=\"300\">".$somme."</td>\n";
            echo "</tr>\n";
         }
         ?>
         </table>
         <a href="#top" class="navigation">Haut</a> 


         <?php 
			   // ==== Gestion de la caisse ===== //
	  		 ?>
         <h2 id="caisse">La caisse</h2> 
         <h3>Etat de la caisse Saison par saison </h3>
         <table class="jeu">
         <tr>
         <td>&nbsp;</td>
         <td width="50" class="infotitre">Id. Saison</td>
         <td width="300" class="infotitre">Libell&eacute;</td>
         <td width="100" class="infotitre">Total</td>
         </tr>
         <?php 
         $listeOperations = getCaisseSaison();
         for($i=0;$i<sizeof($listeOperations);$i++)
         {
            $caisse=$listeOperations[$i];
            // Lecture des propri‚t‚s du joueur
            $id        = stripslashes($caisse["id"]);
            $nom       = stripslashes($caisse["nom"]);
            $total     = stripslashes($caisse["total"]);
            if ($total==0)
            {
              $total="";
            }    
            else
            {
              $total=$total." &euro;";
            }    
                        
            if ($i%2 == 1) 
                echo "<tr class='enreg impaire'>";
            else
                echo "<tr class='enreg'>";

            $j=$i+1;
            echo "<td width=\"50\"><a href=\"admin_voir_caisse.php?saison=$id\"><img src='img/edit.png' alt='edit'/></a></td>\n";            
            echo "<td width=\"100\">".$id."</td>\n";   
            echo "<td width=\"350\">".$nom."</td>\n";
            echo "<td width=\"150\">".$total."</td>\n";
            echo "</tr>\n";
         }
         ?>
         </table>


         <h3>Cette partie concernent la gestion de la caisse</h3>
         <a href="admin_caisse.php" class="miniAction">Ajout d'une nouvelle operation en caisse</a>
         <table class="jeu">
         <tr><td>&nbsp;</td>
         <td width="50" class="infotitre">Id.</td>
         <td width="300" class="infotitre">Libell&eacute;</td>
         <td width="100" class="infotitre">Date</td>
         <td width="200" class="infotitre">Somme au d&eacute;bit</td>
         <td width="200" class="infotitre">Somme au cr&eacute;dit</td>
         </tr>
         <?php 
         $listeOperations = getListeOperationsCaisse();
         $total = 0;
         for($i=0;$i<sizeof($listeOperations);$i++)
         {
            $caisse=$listeOperations[$i];
            // Lecture des propri‚t‚s du joueur
            $caisse_id            = stripslashes($caisse["caisse_id"]);
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
            
            if ($i%2 == 1) 
                echo "<tr class='enreg impaire'>";
            else
                echo "<tr class='enreg'>";

            $j=$i+1;
            echo "<td width=\"50\"><a href=\"admin_caisse.php?caisse=$caisse_id\"><img src='img/edit.png' alt='edit'/></a></td>\n";   
            echo "<td width=\"50\">".$caisse_id."</td>\n";   
            echo "<td width=\"150\">".$caisse_libelle."</td>\n";
            echo "<td width=\"100\">".formatte_date($caisse_date,1)."</td>\n";
            echo "<td width=\"200\">".$caisse_somme_debit."</td>\n";
            echo "<td width=\"200\">".$caisse_somme_credit."</td>\n";
            echo "</tr>\n";
         }
         $tdebit="";
         $tcredit="";
         if ($total<0)    $tdebit=" - ".$total. " &euro;";
         if ($total>=0)   $tcredit=$total." &euro;";
         ?>
         <tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td>
         <td width="450"  colspan="3">Total</td>
         <td width="200"><?php echo $tdebit; ?></td>
         <td width="200"><?php echo $tcredit; ?></td>
         </tr>
         </table>

         
         <a href="#top" class="navigation">Haut</a> 

        
         <?php 
			   // ==== Gestion des appels de fond ===== //
	  		 ?>
         <h2 id="appel">Les appels de fond</h2> 
         <p>Cette partie concernent la gestion des appels de fond du LS</p>
         <a href="admin_appel.php" class="miniAction">Ajout d'un nouvel appel de fond</a>
         <table class="jeu">
         <tr><td>&nbsp;</td>
         <td class="infotitre">Id.</td>
         <td class="infotitre">Libellé</td>
         <td class="infotitre">Date</td>
         </tr>
         <?php 
         $listeAppels = getListeAppelsDeFonds();
         for($i=0;$i<sizeof($listeAppels);$i++)
         {
            $appel=$listeAppels[$i];
            // Lecture des propriétés du joueur
            $appel_id      = stripslashes($appel["appel_id"]);
            $libelle        = stripslashes($appel["libelle"]);
            $date           = stripslashes($appel["date"]);

            if ($i%2 == 1) 
                echo "<tr class='enreg impaire'>";
            else
                echo "<tr class='enreg'>";

            $j=$i+1;
            echo "<td width=\"50\"><a href=\"admin_appel.php?appel=$appel_id\"><img src='img/edit.png' alt='edit'/></a></td>\n";   
            echo "<td width=\"50\">".$appel_id."</td>\n";   
            echo "<td width=\"350\">".$libelle."</td>\n";
            echo "<td width=\"100\">".formatte_date($date,1)."</td>\n";
            echo "</tr>\n";
         }
         ?>
         </table>
         <a href="#top" class="navigation">Haut</a> 

        
         <?php 
         // ==== Gestion des joueurs ===== //
	  		 ?>
         <h2 id="joueur">Les joueurs</h2>
	        <p>Il est nécessaire d'être enregistré pour pouvoir pronostiquer sur le LS. Un joueur a donc un nom (qui sera affiché sous 
          le menu dans la zone de bienvenue), un pseudo et un mot de passe (pour se connecter), des initiales qui seront utilisés dans
          le tableau récapitulatif et le classement général, un mail et si il est administrateur ou pas (O/N).</p>           
         <a href="admin_joueur.php" class="miniAction">Ajout d'un joueur</a>
         <table class="jeu">
         <tr><td>&nbsp;</td>
         <td class="infotitre">Id.</td>
         <td class="infotitre">Nom</td>
         <td class="infotitre">Pseudo</td>
         <td class="infotitre">Mdp</td>
         <td class="infotitre">Dernière Conn.</td>
         <td class="infotitre">Nav. utilisé</td>
         <td class="infotitre">Admin.</td>
         <td class="infotitre">Actif</td>
         </tr>
         <?php 
         $listeJoueurs = getListeTousJoueurs();
         for($i=0;$i<sizeof($listeJoueurs);$i++)
         {
            $joueur=$listeJoueurs[$i];
            // Lecture des propriétés du joueur
            $joueur_id      = stripslashes($joueur["joueur_id"]);
            $nom            = stripslashes($joueur["nom"]);
            $pseudo         = stripslashes($joueur["pseudo"]);
            $mdp            = stripslashes($joueur["mdp"]);
            $der_cnx        = stripslashes($joueur["der_cnx"]);
            $administrateur = stripslashes($joueur["administrateur"]);
            $actif          = stripslashes($joueur["actif"]);
            $der_navigateur = stripslashes($joueur["der_navigateur"]);
            
            if ($i%2 == 1) 
                echo "<tr class='enreg impaire'>";
            else
                echo "<tr class='enreg'>";

            $j=$i+1;
            echo "<td width=\"30\"><a href=\"admin_joueur.php?joueur=$joueur_id\"><img src='img/edit.png' alt='edit'/></a></td>\n";   
            echo "<td width=\"30\">".$joueur_id."</td>\n";   
            echo "<td width=\"200\">".$nom."</td>\n";
            echo "<td width=\"80\">".$pseudo."</td>\n";
            echo "<td width=\"30\">".$mdp."</td>\n";
            echo "<td width=\"150\">".formatte_date($der_cnx,1)."</td>\n";
            echo "<td width=\"50\">".$der_navigateur."</td>\n";
            echo "<td width=\"30\">".$administrateur."</td>\n";
            echo "<td width=\"30\">".$actif."</td>\n";
            echo "</tr>\n";
        }
        ?>
        </table>
        <a href="#top" class="navigation">Haut</a> 
          
        <?php 
        // ==== Gestion des sauvegardes ===== //
        ?>
        
        <?php              
        // Sauvegarde demandée ?
        if (isset($_POST['mailSauvegarde']))
        {                         
            // Initialisation des variables
            $mailSauvegarde=$_POST["mailSauvegarde"];
            
            // Protège les caractères qui mettent le bousin (par exemple les cotes) et supprimer les balises html par sécurité
            $mailSauvegarde=strip_tags (stripslashes ($mailSauvegarde));
            
            // Sauve la base et envoie le tout par mail à $mailSauvegarde
            SauveBaseEtMail($mailSauvegarde);    
        }
        ?>
        <h2 id="sauvegarde">Sauvegarde</h2>
        <p>Cette option permet d'envoyer vers une adresse mail une sauvegarde complète de la base de données (structure et 
        données).</p>
        <form method="post" action="administration.php" class="formsauvegarde" name="formsauvegarde">
            <label for="mailSauvegarde">Mail destinataire :</label>
            <input name="mailSauvegarde" id="mailSauvegarde" type="text" size="44" />
            <input type="submit" value="Envoyer Maintenant" class="bouton" />
        </form>
        <a href="#top" class="navigation">Haut</a> 

          
        <?php 
        // ==== Gestion des statistiques ===== //
        ?>
        
        <?php              
        // Sauvegarde demandée ?
        if (isset($_POST['mailSauvegarde']))
        {                         
            // Initialisation des variables
            $mailSauvegarde=$_POST["mailSauvegarde"];
            
            // Protège les caractères qui mettent le bousin (par exemple les cotes) et supprimer les balises html par sécurité
            $mailSauvegarde=strip_tags (stripslashes ($mailSauvegarde));
            
            // Sauve la base et envoie le tout par mail à $mailSauvegarde
            SauveBaseEtMail($mailSauvegarde);    
        }
        ?>
        <h2 id="statistique">Statistiques</h2>
        <p>Cette option permet de recalculer les statistiques saison par saison.</p>
        <?php
           $saison_id            = getIDSaisonCourante();
  			   // Si choix d'un autre jeu, on renseigne l'identifiant 
  			   if (isset($_POST['numSaison']))
           {
                $saison_id       = $_POST['numSaison'];
                RecalculStatistiquesSaison($saison_id);
                
           }   
  	       $listeSaisons         = getListeSaisons();
           ?> 	  		  
             <h3>Choix de la saison</h3>          
              <form method="post"  class="formsauvegarde" name="formsauvegarde" action="administration.php#statistique">                  
                <label class="texte" for="jeuform">Saison : 
                </label>                  
                <select class="selection" name="numSaison" onchange="soumissionFormulaire(this)" STYLE="width:200px">                    
            <?php
                     for($i=0;$i<sizeof($listeSaisons);$i++)
                     {
                        $saison=$listeSaisons[$i];  
                        // Lecture des propriétés du joueur
                        $ident       = $saison["saison_id"];
                        $titre       = $saison["nom"];
                        
                        echo "<option value=\"".$ident."\"";
                        if ($saison_id==$ident)
                            echo " selected>";
                        else
                            echo ">";
                        echo $titre;
                        echo "</option>";
                      }
                    
                          ?>                  
              </select>              
             <input type="submit" value="Calculer Maintenant" class="bouton" />
            </form>           
        
        
        
        
        
        <a href="#top" class="navigation">Haut</a> 
        

        
        <?php 
        ferme_base($db_link);
      } 
	    ?>

    </div>
		
    <?php include ("scripts/footer.php"); ?>
	</div>
	</body>
	
</html>
