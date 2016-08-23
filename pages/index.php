<?php include("composants/head.php");  ?>

<body>

    <div id="wrapper">
        <?php 
        // include database and object files
        include_once 'config/database.php';
        include_once 'config/util.php';
        include_once 'objects/caisse.php';
        include_once 'objects/saison.php';
        include_once 'objects/jeu.php';
        include_once 'objects/resultat.php';
        include_once 'objects/gain.php';
        include_once 'objects/classement.php';
        include_once 'objects/rapport.php';
        include_once 'objects/pronostic.php';
        include_once 'objects/joueur.php';
        include_once 'objects/chat.php';
         
        // instantiate database and product object
        $database = new Database();
        $db = $database->getConnection();
         
        // Saison courante
        $saison = new Saison($db);
        $saison = $saison->chargeSaisonCourante();
   
        // Solde de la caisse
        $caisse = new Caisse($db);
        $caisse->saison_id = $saison->saison_id;
        $caisse->chargeSoldeSaison();

        // Nombre de Jeu
        $jeu = new Jeu($db);
        $jeu->saison_id = $saison->saison_id;
        $jeu->chargeNombreJeux();
        $jeu_nombre = $jeu->jeu_nombre;
        $jeu->chargeDernierJeu();

        // Resultat
        $resultat = new Resultat($db);
        $resultat->jeu_id = $jeu->jeu_id;
        $resultat->chargeResultat();

        // Classement
        $classement = new Classement($db);
        $classement->saison_id = $saison->saison_id;
        $stmtClassement = $classement->litTroisMeilleursClassement();
        $stmtClassementGeneral = $classement->litClassement();
        $listeClassementGeneral = $stmtClassementGeneral->fetchAll(PDO::FETCH_ASSOC);
   
        // Liste des opérations en caisse
        $gain = new Gain($db);
        $gain->saison_id = $saison->saison_id; 
        $stmtGain = $gain->litGains();

        // Liste des opérations en caisse
        $chat = new Chat($db);
        $chat->saison_id = $saison->saison_id; 
        $stmtChat = $chat->litChatMessages();
        $chatMessages = $stmtChat->fetchAll(PDO::FETCH_ASSOC);

        // Rapport
        $rapport = new Rapport($db);
        $rapport->jeu_id = $jeu->jeu_id;
        $rapport->type = 7;
        $stmtRapport7 = $rapport->litRapports();
        $jeuxRapport7 = $stmtRapport7->fetchAll(PDO::FETCH_ASSOC);
        $rapport->type = 15;
        $stmtRapport15 = $rapport->litRapports();
        $jeuxRapport15 = $stmtRapport15->fetchAll(PDO::FETCH_ASSOC);

        // Recherche la liste des joueurs
        $joueurs = new Joueur($db);
        $stmtJoueurs = $joueurs->litJoueurs();
        $listeJoueurs = $stmtJoueurs->fetchAll(PDO::FETCH_ASSOC);

        // Pronostics des joueurs
        $pronostic = new Pronostic($db);
        $pronostic->jeu_id = $jeu->jeu_id; 
        $stmtPronostics = $pronostic->litPronostics();
        $listePronostics = $stmtPronostics->fetchAll(PDO::FETCH_ASSOC);


        // Calcul du nombre de gagnants 
        $nombreDeGagnants=0;
        for($j=0;$j<sizeof($listeJoueurs);$j++)
        {
            // Phase 1 : Pour ce joueur, on recherche le nombre de bons résultats
            // Ce joueur n'a pas de bon résultats par défaut
            $nbBonResultats=0;
            $nbjeu=0;
            for($i=1; $i <= 15 ; $i++) 
            {
                // Pour ce joueur, lecture du résultat du match $i 
                $resultatMatch= getResultat($resultat, $i);
                // Et lecture du pronostics de ce joueur et de ce match
                if ($listePronostics && isset($listePronostics[$j]) ) {
                    $prono=$listePronostics[$j];
                    $valeurProno=getListePronostic($prono, $i);
                    if (strlen($valeurProno)>0) {
                        $nbjeu++;
                        if ($resultatMatch==$valeurProno) {
                            $nbBonResultats++;
                        }
                    }
                }
            }
            // Phase 2 : On recherche si c'est un gagnant !
            $moyenne=0;
            if ($nbjeu>0) {
                $nombreDeGagnants=$nombreDeGagnants+isGagnant($nbjeu, $nbBonResultats, $jeuxRapport7, $jeuxRapport15);
            }
        }
        include_once 'composants/nav.php';
        ?>        
        
        <div id="page-wrapper">
            <br>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <a href="resultats.php">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-futbol-o fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">
                                        <?php 
                                        if($nombreDeGagnants<=1) {
                                            echo $nombreDeGagnants . " gain"; 
                                        } else {
                                            echo $nombreDeGagnants . " gains"; 
                                        }
                                        ?></div>
                                        <div>
                                            <?php 
                                            if($jeu_nombre==0) {
                                                echo "Aucune journée"; 
                                            } elseif($jeu_nombre==1) {
                                                echo $jeu_nombre . "ère journée"; 
                                            } else {
                                                echo $jeu_nombre . "ème journée"; 
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="pull-left">Détail des résultats</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <a href="classement_gains.php">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-users fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <?php
                                        $i=1;
                                    // Si il y a au moins un jeu
                                    if($jeu_nombre>0) {
                                        $pos=0;
                                        $cpt=0;
                                        $totalprec=-1;
                                        $tab=array();
                                        while ($row = $stmtGain->fetch(PDO::FETCH_ASSOC)) {
                                            extract($row);
                                            if ($total!=$totalprec) {
                                                $pos=$pos+1;
                                            }
                                            $positionClassementGen = getPositionClassement($joueur_id,$listeClassementGeneral)-1;
                                            $moyenneClassementGen = getMoyenneClassement($joueur_id,$listeClassementGeneral);
                                            $tab[$cpt]=array( 'nom'=> $nom, 
                                                            'total' => $total, 
                                                            'moyenne' => $moyenneClassementGen, 
                                                            'position' =>(($pos)*100)+$positionClassementGen,
                                                            'positionClassement' =>$positionClassementGen);
                                            $totalprec=$total;
                                            $cpt=$cpt+1;

                                        }
                                        // Tableau trié ? 
                                        if ($tab) {
                                           usort($tab, "comparePosition");
                                           $totalGeneral=0;
                                           $moyenneGeneral=0;
                                           foreach ($tab as &$value) {
                                                echo "<div class='medium'>" . $i . ". " . $value['nom'] . "</div>";
                                                $i=$i+1;
                                                if ($i>3) {
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                    // On complète si nécessaire en ajoutant des lignes blanches
                                    while ($i <= 3 ) {
                                        echo "<div class='medium'>{$i}. -</div>";
                                        $i=$i+1;
                                    }  

                                    ?>          
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="pull-left">Classement général</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <a href="caisse.php">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-eur fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">
                                            <?php 
                                                if ($caisse->caisse_total) {
                                                    echo $caisse->caisse_total; 
                                                } else {
                                                    echo "0"; 
                                                }
                                            ?>
                                        </div>
                                        <div>euros en caisse</div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <span class="pull-left">Voir le détail</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <a href="classement_general.php">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-flag fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <?php
                                        $i=1;
                                        while ($row = $stmtClassement->fetch(PDO::FETCH_ASSOC)) {
                                            extract($row);
                                            echo "<div class='medium'>{$i}. {$nom}</div>";
                                            $i=$i+1;
                                            if ($i>3) {
                                                break;
                                            }
                                        }    
                                        while ($i <= 3 ) {
                                            echo "<div class='medium'>{$i}. -</div>";
                                            $i=$i+1;
                                        }  
                                        ?> 
                                    </div>
                                </div>
                            </div>

                           <div class="panel-footer">
                                <span class="pull-left">Classement sans les gains</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <!-- /.row -->


                    
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-futbol-o fa-fw"></i>
                            <?php echo $jeu->jeu_titre . " : " . $jeu->jeu_commentaire; ?>
                        </div>
                         <div class="panel-body">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-caisse">
                                <thead class="bg-primary ">
                                    <tr>
                                        <th class="text-center  hidden-xs">Num.</th>
                                        <th class="text-center">Eq. domicile</th>
                                        <th class="text-center">Eq. extérieure</th>
                                        <th class="text-center">Res.</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    <?php
                                    for($i=1; $i <= 15 ; $i++) 
                                    {
                                        if (getEquipe($jeu, $i, "D")!="") {
                                            echo "<tr>";
                                                echo "<td class='text-center  hidden-xs'>{$i}</td>";
                                                echo "<td class='text-center'>".getEquipe($jeu, $i, "D")."</td>";
                                                echo "<td class='text-center'>".getEquipe($jeu, $i, "E")."</td>";
                                                echo "<td class='text-center'>".getResultat($resultat, $i)."</td>";
                                            echo "</tr>";
                                        }
                                    }    
                                    ?>                                    

                                </tbody>
                            </table>  

                            <?php 
                            if (isset($resultat->date) && isset($resultat->nom)) {
                                echo "<p>Résultat saisi le ".formatte_date($resultat->date,1)." par $resultat->nom</p>";
                            }
                            ?>
                        </div>
                        <!-- /.panel-body -->
                    </div>  

                    <div class="panel panel-default">
                       <div class="panel-heading">
                            <i class="fa fa-cc-visa"></i>
                            Derniers rapports à 7
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                           <p class="pull-left">
                            <?php
                            if ($jeuxRapport7) {
                                $rapport7=$jeuxRapport7[0];
                                echo "Saisi le " . $rapport7['commentaire'];
                            }
                            ?>
                            </p>
                            <table class="table table-striped table-bordered table-hover" id="dataTables-caisse">
                                <thead class="bg-primary ">
                                    <tr>
                                        <th class="text-center">Rang</th>
                                        <th class="text-center">Nombre de grilles gagnantes</th>
                                        <th class="text-center">Gain</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                <?php
                                for($j=0;$j<sizeof($jeuxRapport7);$j++)  {                              
                                   $rapport7=$jeuxRapport7[$j];
                                   echo "<tr>";
                                        echo "<td>{$rapport7['rang']}</td>";
                                        echo "<td>{$rapport7['nombre']}</td>";
                                        echo "<td>{$rapport7['rapport']}</td>";
                                    echo "</tr>";
                                }    
                                ?> 
                                </tbody>
                            </table>
              
                        </div>    
                    </div>   
                    <!-- /.panel-body -->   
              
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-cc-visa"></i>
                            Derniers rapports à 15
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">

                            <p class="pull-left">
                            <?php
                            if ($jeuxRapport15) {
                                $rapport15=$jeuxRapport15[0];
                                echo "Saisi le " . $rapport15['commentaire'];
                            }
                            ?>
                            </p>

                             <table class="table table-striped table-bordered table-hover" id="dataTables-caisse">
                                <thead class="bg-primary ">
                                    <tr>
                                        <th class="text-center">Rang</th>
                                        <th class="text-center">Nombre de grilles gagnantes</th>
                                        <th class="text-center">Gain</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                <?php
                                for($j=0;$j<sizeof($jeuxRapport15);$j++)  {                              
                                   $rapport15=$jeuxRapport15[$j];
                                   echo "<tr>";
                                        echo "<td>{$rapport15['rang']}</td>";
                                        echo "<td>{$rapport15['nombre']}</td>";
                                        echo "<td>{$rapport15['rapport']}</td>";
                                    echo "</tr>";
                                }    
                                ?> 
                                </tbody>
                            </table>                        
                        </div>
                        <!-- /.panel-body -->
                    </div>                 
                </div>    

                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-home fa-fw"></i>
                            Joueurs
                        </div>
                         <div class="panel-body">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-caisse">
                                <thead class="bg-primary ">
                                    <tr>
                                        <th class="text-center">Joueur</th>
                                        <th class="text-center">Score</th>
                                        <th class="text-center">Moyenne</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    <?php
                                        $tab=array();
                                        //  Premier passage : calcul des moyennes
                                        for($j=0;$j<sizeof($listeJoueurs);$j++) {
                                            // Ce joueur n'a pas de bon résultats
                                            $flash="";
                                            $nbBonResultats=0;
                                            $nbjeu=0;
                                            $nbResultats=0;
                                            $nbResultats7=0;
                                            $joueur_id=$listeJoueurs[$j]["joueur_id"];
                                            // Pronostic de ce joueur
                                            if ($listePronostics) {
                                               $prono=getPronoJoueur($listePronostics, $joueur_id);
                                            }
                                            // Pour les 15 matchs potentiels, on compare le prono et le résultat
                                            for($i=1; $i <= 15 ; $i++) 
                                            {
                                                // Pour ce joueur, lecture du résultat du match $i 
                                                $resultatMatch= getResultat($resultat, $i);
                                                // Et lecture du pronostics de ce joueur et de ce match
                                                if (isset($prono)) {
                                                    $flash=$prono["flash"];
                                                    $valeurProno=getListePronostic($prono, $i);
                                                    if (strlen($valeurProno)>0) {
                                                        $nbjeu++;
                                                        if (strpos($resultatMatch,$valeurProno)!==false) {
                                                            $nbBonResultats++;
                                                        }
                                                    }
                                                }
                                                if ($resultatMatch)            
                                                {
                                                    $nbResultats=$nbResultats+1;
                                                    if ($i<=7) {
                                                        $nbResultats7=$nbResultats7+1;
                                                    }
                                                }                                                
                                            }
                                            // Remplissage du tableau de données
                                            $moyenne=0;
                                            if ($nbjeu>0  && $nbResultats!=0) {
                                                if ($nbjeu <= 7) {
                                                    $moyenne=$nbBonResultats/$nbResultats7*100;
                                                } else {
                                                    $moyenne=$nbBonResultats/$nbResultats*100;
                                                }
                                            }
                                            $tab[$j]=array('nom'=> $listeJoueurs[$j]["nom"], 
                                                            'nbResultat' => $nbBonResultats, 
                                                            'jeu' =>$nbjeu,
                                                            'flash'=> $flash,
                                                            'nbResultatSaisi'=> $nbResultats,
                                                            'moyenne' =>  number_format($moyenne,2));
                                        }
                                        // Tri !
                                        if ($tab) {

                                           usort($tab, "compareMoyenne");
                                            //  Second passage : affichage des résultats
                                            for($j=0;$j<sizeof($listeJoueurs);$j++) {
                                                $classe="";
                                                // Moyenne de plus de 75% ou dans la liste des rapports => succès en vert !
                                                if (1==isGagnant($tab[$j]['jeu'], $tab[$j]['nbResultat'], $jeuxRapport7, $jeuxRapport15)) 
                                                {
                                                    $classe="success";
                                                }
                                                // Moyenne de 0% et des resultats saisies et des pronostics faits => echec en rouge  !
                                                if ($tab[$j]['nbResultat']==0 && $tab[$j]['nbResultatSaisi']!=0 && $tab[$j]['jeu']!=0) {
                                                    $classe="danger";
                                                }
                                                $flashIcon="";
                                                if ($tab[$j]['flash']=="1") {
                                                    $flashIcon='&nbsp;&nbsp;<i class="fa fa-flash"></i>';
                                                }                                                
                                                echo "<tr class='{$classe}'>";
                                                echo "<td>".$tab[$j]['nom']." ".$flashIcon."</td>";
                                                echo "<td class='text-center'>".$tab[$j]['nbResultat']." / ".$tab[$j]['jeu']."</td>";
                                                echo "<td class='text-center'>".$tab[$j]['moyenne']." %</td>";
                                                echo "</tr>";
                                            }
                                        }
                                    ?>                                    

                                </tbody>
                            </table>
                        </div>
                        <!-- /.panel-body -->
                    </div>                    
                </div>          
            </div>
   
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
    <!-- Toast MonLs -->
    <script src="../dist/js/toastr.min.js"></script>

    <!-- Javascript MonLs -->
    <script src="../dist/js/monls.js"></script>
</body>
</html>

