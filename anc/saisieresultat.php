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
    if (!isset($_SESSION['id_joueur'])) 
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
      
         // Si choix d'un autre jeu, on renseigne l'identifiant 
    	   if (isset($_GET['idjeu']))
         {
              $jeu_id = $_GET['idjeu'];
         }
         else          
         {
           // Identifiant jeu obligatoire
           $jeu_id    = getIDJeuCourant();
         }
        
        /* Pronostic de ce joueur */
        $resultat=getResultatJeu($jeu_id);
      
        // Requete de lecture du prochain jeu
 			  // $jeu = getJeuCourant();
 			  // LC On lit maintenant le jeu en paramètre
        $jeu          = getJeu($jeu_id);
        
        
                $html="";
                $posd=1;
                $posv=1;
                $posr=1;
                $equipe1d="";
                $equipe2d="";
                $equipe3d="";
                $equipe4d="";
                $equipe5d="";
                $equipe6d="";
                $equipe7d="";
                $equipe8d="";
                $equipe9d="";
                $equipe10d="";
                $equipe11d="";
                $equipe12d="";
                $equipe13d="";
                $equipe14d="";
                $equipe15d="";
                $equipe1v="";
                $equipe2v="";
                $equipe3v="";
                $equipe4v="";
                $equipe5v="";
                $equipe6v="";
                $equipe7v="";
                $equipe8v="";
                $equipe9v="";
                $equipe10v="";
                $equipe11v="";
                $equipe12v="";
                $equipe13v="";
                $equipe14v="";
                $equipe15v="";
                $resultat1fj="";
                $resultat2fj="";
                $resultat3fj="";
                $resultat4fj="";
                $resultat5fj="";
                $resultat6fj="";
                $resultat7fj="";
                $resultat8fj="";
                $resultat9fj="";
                $resultat10fj="";
                $resultat11fj="";
                $resultat12fj="";
                $resultat13fj="";
                $resultat14fj="";
                $resultat15fj="";
      
                
 			  if ($jeu)
 			  {
 			     // Lecture des champs de la ligne
           $bloque        = $jeu["bloque"];
  			   $titre        = $jeu["titre"];
  			   $commentaire  = $jeu["commentaire"];
  			   
  			   // Affichage du titre du prochain jeu
		  		 echo "<h2>Saisie du résultat du jeu ".$titre."</h2>";

              if ($bloque==1)
              {



		  		 if ($jeu_id != getIDJeuCourant())
           {
             echo "<p class=\"jeubloque\">ATTENTION, ce n'est pas le jeu courant !</p>";
           }
		  		  echo "<span class='connexionutilisateur'>";
                echo "Dernière saisie par ".$resultat["nom"]." le ".formatte_date($resultat["date"],1);
            echo "</span>";

           // Affichage du tableau	
                 ?>			            
      <form method="post" class="formjeu" name="formjeu" action="valideresultat.php?idjeu=<?php echo $jeu_id ?>" >              
        <table class="jeu">           
<?php 
           // Jeu à 14 ou 15 matchs ?
           $nbMatchsDeCeJeu  = getNbMatchsDeCeJeu($jeu);
          // echo "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td colspan=6>Française des Jeux</td></tr>\n";
           echo "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td colspan=6></td></tr>\n";
      
           for($i=1; $i <= $nbMatchsDeCeJeu ; $i++) 
           {
            $equiped     = $jeu["equipe".$i."d"];
            $equipev     = $jeu["equipe".$i."v"];
            if ($i%2 == 1) 
              echo "<tr class='impaire'>";
            else
              echo "<tr>";
            // Retrouve si la colonne 1, 2 et 3 est pronostiqué par le joueur
            $resultatColonne1 = getResultatEstJoue($resultat,$i,'1');
            $resultatColonne2 = getResultatEstJoue($resultat,$i,'N');
            $resultatColonne3 = getResultatEstJoue($resultat,$i,'2');
            echo "<td><img src='img/".$i.".png' alt='img/".$i."' /></td>\n";        
            echo "<td class=\"eqd\">".$equiped."</td>\n";
            
            echo "<td class=\"choix\"><span id=\"vl".$i."c1\" class=\"";
            if ($resultatColonne1)    echo "resultats";
            else                 echo "resultat1";
            echo "\" onclick=\"Change('l".$i."c1','R');\" /></td>\n";
            echo "<td class=\"choix\"><span id=\"vl".$i."c2\" class=\"";
            if ($resultatColonne2)    echo "resultats";
            else                 echo "resultatn";
            echo "\" onclick=\"Change('l".$i."c2','R');\" /></td>\n";
            echo "<td class=\"choix\"><span id=\"vl".$i."c3\" class=\"";
            if ($resultatColonne3)    echo "resultats";
            else                 echo "resultat2";
            echo "\" onclick=\"Change('l".$i."c3','R');\" /></td>\n";
            
            if ($i==1)
            {
                  $equipedfj=$equipe1d;
                  $equipevfj=$equipe1v;
                  $resultatfj=$resultat1fj;
            }            
            if ($i==2)
            {
                  $equipedfj=$equipe2d;
                  $equipevfj=$equipe2v;
                  $resultatfj=$resultat2fj;
            }            
            if ($i==3)
            {
                  $equipedfj=$equipe3d;
                  $equipevfj=$equipe3v;
                  $resultatfj=$resultat3fj;
            }            
            if ($i==4)
            {
                  $equipedfj=$equipe4d;
                  $equipevfj=$equipe4v;
                  $resultatfj=$resultat4fj;
            }            
            if ($i==5)
            {
                  $equipedfj=$equipe5d;
                  $equipevfj=$equipe5v;
                  $resultatfj=$resultat5fj;
            }               
            if ($i==6)
            {
                  $equipedfj=$equipe6d;
                  $equipevfj=$equipe6v;
                  $resultatfj=$resultat6fj;
            }               
            if ($i==7)
            {
                  $equipedfj=$equipe7d;
                  $equipevfj=$equipe7v;
                  $resultatfj=$resultat7fj;
            }               
            if ($i==8)
            {
                  $equipedfj=$equipe8d;
                  $equipevfj=$equipe8v;
                  $resultatfj=$resultat8fj;
            }               
            if ($i==9)
            {
                  $equipedfj=$equipe9d;
                  $equipevfj=$equipe9v;
                  $resultatfj=$resultat9fj;
            }               
            if ($i==10)
            {
                  $equipedfj=$equipe10d;
                  $equipevfj=$equipe10v;
                  $resultatfj=$resultat10fj;
            }               
            if ($i==11)
            {
                  $equipedfj=$equipe11d;
                  $equipevfj=$equipe11v;
                  $resultatfj=$resultat11fj;
            }               
            if ($i==12)
            {
                  $equipedfj=$equipe12d;
                  $equipevfj=$equipe12v;
                  $resultatfj=$resultat12fj;
            }               
            if ($i==13)
            {
                  $equipedfj=$equipe13d;
                  $equipevfj=$equipe13v;
                  $resultatfj=$resultat13fj;
            }               
            if ($i==14)
            {
                  $equipedfj=$equipe14d;
                  $equipevfj=$equipe14v;
                  $resultatfj=$resultat14fj;
            }               
            if ($i==15)
            {
                  $equipedfj=$equipe15d;
                  $equipevfj=$equipe15v;
                  $resultatfj=$resultat15fj;
            }           
            echo "<td class=\"eqg\">".$equipev."</td>\n"; 
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td><input id=\"tl".$i."c1\" name=\"tl".$i."c1\" type=\"hidden\" value=\"";
            if ($resultatColonne1)    echo "1";
            else                 echo "0";
            echo "\"/></td>\n";
            echo "<td><input id=\"tl".$i."c2\" name=\"tl".$i."c2\" type=\"hidden\" value=\"";
            if ($resultatColonne2)    echo "1";
            else                 echo "0";
            echo "\"/></td>\n";
            echo "<td><input id=\"tl".$i."c3\" name=\"tl".$i."c3\" type=\"hidden\" value=\"";
            if ($resultatColonne3)    echo "1";
            else                 echo "0";
            echo "\"/></td>\n";
            echo "</tr>\n";          }
                    ?>          
        </table>          
        <input type="submit" value="Valider" class="bouton" />          
      </form>          
      <?php 
		    }
      else
      {
            echo "<p class=\"jeubloque\">Les pronostics sont encore ouverts.</p>";
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