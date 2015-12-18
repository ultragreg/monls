<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Mails aux retardataires</title>  
</head>
<body>
  <?php


        // include database and object files
        include_once '../config/database.php';
        include_once '../config/util.php';
        include_once '../objects/saison.php';
        include_once '../objects/jeu.php';
        include_once '../objects/pronostic.php';
        include_once '../objects/joueur.php';

      
        // instantiate database and product object
        $database = new Database();
        $db = $database->getConnection();
         
        // Saison courante
        $saison = new Saison($db);
        $saison = $saison->chargeSaisonCourante();

        // Nombre de Jeu
        $jeu = new Jeu($db);
        $jeu->saison_id = $saison->saison_id;
        $jeu->chargeDernierJeu();

        // Recherche la liste des joueurs
        $joueurs = new Joueur($db);
        $stmtJoueurs = $joueurs->litJoueurs();
        $listeJoueurs = $stmtJoueurs->fetchAll(PDO::FETCH_ASSOC);
        $stmtJoueursRetardataires = $joueurs->litJoueursRetardatairesAppelDeFond();
        $listeJoueursRetardataires = $stmtJoueursRetardataires->fetchAll(PDO::FETCH_ASSOC);

        // Pronostics des joueurs
        $pronostic = new Pronostic($db);
        $pronostic->jeu_id = $jeu->jeu_id; 
        $stmtPronostics = $pronostic->litPronostics();
        $listePronostics = $stmtPronostics->fetchAll(PDO::FETCH_ASSOC);

        // Calcul des retardataires des pronos
        $compteurRetardatairesPronostics=0;
        $retardatairesPronostics="";
        for($j=0;$j<sizeof($listeJoueurs);$j++) {

            $prono="";
            $joueur_id=$listeJoueurs[$j]["joueur_id"];
            // Pronostic de ce joueur
            if ($listePronostics) {
               $prono=getPronoJoueur($listePronostics, $joueur_id);
              if (!isset($prono)) {
                $retardatairesPronostics = $retardatairesPronostics . $listeJoueurs[$j]["mail"] . "; ";
                $compteurRetardatairesPronostics++;
                }                                                
            }
            
        }
 
        // Calcul des retardataires des cotisations
        $compteurRetardatairesCotisations=0;
        $retardatairesCotisations="";
        for($j=0;$j<sizeof($listeJoueursRetardataires);$j++) {
                $retardatairesCotisations = $retardatairesCotisations .$listeJoueursRetardataires[$j]["mail"] . "; ";
                $compteurRetardatairesCotisations++;
            }

  ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Mails aux retardataires</h4>
            </div>
            <div class="modal-body">
              <form>
                <div class="form-group">
                <label for="recipient-name" class="control-label">Retardataires des pronostics : <?php echo $compteurRetardatairesPronostics; ?> joueur(s)</label>
                <input type="text" class="form-control" value="<?php echo $retardatairesPronostics; ?>">
                <?php
                    $subject="[MONLS] Pronostic sur votre prochain jeu";
                    $body = "Bonjour%0DPour rappel, un Jeu est en ligne, Merci de faire vos pronostics,%0DL'équipe Monls";
                    $cc="capdecomme.l@mipih.fr;lafforgue.g@mipih.fr;fiore.p@mipih.fr";
                    echo "<a HREF=\"MAILTO:".$retardatairesPronostics."?subject=".$subject."&body=".$body."&cc=".$cc."\">Générer ce mail dans OutLook </a> ";
                    
                ?>
                </div>
                <div class="form-group">
                <label for="message-text" class="control-label">Retardataires des cotisations : <?php echo $compteurRetardatairesCotisations; ?> joueur(s)</label>
                <input type="text" class="form-control" value="<?php echo $retardatairesCotisations; ?>">
                <?php
                    $subject="[MONLS] Appel de fond";
                    $body = "Bonjour%0DPour rappel, un appel de fond est en-cours,%0DMerci de vous rapprocher de Christophe M. ou Gregory L.,%0DL'équipe Monls";
                    $cc="capdecomme.l@mipih.fr;molinier.c@mipih.fr;lafforgue.g@mipih.fr;fiore.p@mipih.fr";
                    echo "<a HREF=\"MAILTO:".$retardatairesCotisations."?subject=".$subject."&body=".$body."&cc=".$cc."\">Générer ce mail dans OutLook </a> ";
                    
                ?>
                
                </div>
              </form>
            </div>			<!-- /modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
            </div>			<!-- /modal-footer -->
</body>
</html>