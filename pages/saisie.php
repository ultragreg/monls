<?php include("composants/head.php"); ?>

<body>
    <div id="wrapper">
        <?php 
        if ( !isset($_SESSION['id_joueur']) ) 
        {
            header('Location: index.php');
        }

        // include database and object files
        include_once 'config/database.php';
        include_once 'config/util.php';
        include_once 'objects/saison.php';
        include_once 'objects/jeu.php';
        include_once 'objects/resultat.php';
        include_once 'objects/pronostic.php';
        include_once 'objects/indicateur.php';
         
        // instantiate database and product object
        $database = new Database();
        $db = $database->getConnection();
         
        // Recherche de la saison courante
        $saison = new Saison($db);
        $saison = $saison->chargeSaisonCourante();

        // Dernier Jeu
        $jeu = new Jeu($db);
        $jeu->saison_id = $saison->saison_id;
        $jeu->chargeDernierJeu();

        if ($jeu->jeu_bloque=="1") {
            // Résultat de ce jeu
            $resultat = new Resultat($db);
            $resultat->jeu_id = $jeu->jeu_id; 
            $resultat->chargeResultat();
        } else {
            // Pronostics des joueurs
            $pronostic = new Pronostic($db);
            $pronostic->jeu_id = $jeu->jeu_id; 
            if (isset($_SESSION['id_joueur'])) {
                $pronostic->joueur_id = $_SESSION['id_joueur']; 
            }
            $pronostic->chargePronostic();

            // Indicateurs
            $indicateur = new Indicateur($db);
            $indicateur->jeu_id = $jeu->jeu_id; 
            $stmtIndicateurs = $indicateur->litIndicateurs();
            $indicateurs = $stmtIndicateurs->fetchAll(PDO::FETCH_ASSOC);
        }
		//include 'scripts/repartition.php'; 
        include_once 'composants/nav.php';
       ?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header" id="titreFenetreSaisie">
                    <?php 
                    if ($jeu->jeu_bloque=="1") {
                        echo "Saisie Résultat";
                        echo "<span style='display:none;' id='typeOperation'>R</span>";
                    } else {
                        echo "Prochain Jeu";
                        echo "<span style='display:none;'  id='typeOperation'>P</span>";
                    }
                    ?>
                    </h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-futbol-o fa-fw"></i>
                            <?php 
                            if ($jeu->jeu_bloque=="1") {
                                echo "Résultats du jeu " . $jeu->jeu_titre; 
                            } else {
                                echo "Pronostics du jeu " . $jeu->jeu_titre;
                                echo "<span id='indiceCourant'>";
                                if ($pronostic->IndiceGain7>0) {
                                    echo " - Votre indice de gain : <strong>" . $pronostic->IndiceGain7 . "</strong>";
                                } else if ($pronostic->IndiceGain15>0) {
                                    echo " - Votre indice de gain : <strong>" . $pronostic->IndiceGain15 . "</strong>";
                                }
                                echo "</span>";
                            }
                           ?>
                        </div>
                        <!-- .panel-heading -->
                        <div class="panel-body">
                            <div id="accordion" class="panel-group">
                                <?php
                                if ($jeu->jeu_bloque=="1") {
                                        /* CAS 1 : Saisie du résultat du jeu */
                                       for($i=1; $i <= 15 ; $i++) 
                                       {
                                        	if ($i==8) {
                                        		echo "<hr style='width:100%;color:firebrick;margin:5px 0;background-color:firebrick;height:3px;' />";
                                        	}
                                        	 
                                            if (getEquipe($jeu, $i, "D")!="") {
                                                $res= getResultat($resultat, $i);
                                                ?>
                                                <div class="btnGroupeSaisie btn-group btn-group-justified" role="group" >
                                                
                                                  <div class="btn-group" role="group">
                                                    <?php 
                                                        echo '<button type="button" class="btnSaisie btn ';
                                                        if (strpos($res,"1")!== false) echo 'btn-primary'; else echo 'btn-default';
                                                        echo '" id="btn'.$i.'1" ';
                                                        echo '>' . getEquipe($jeu, $i, "D") . '</button>'; 
                                                    ?>
                                                  </div>

                                                  <div class="btn-group" role="group">
                                                    <?php 
                                                        echo '<button type="button" class="btnSaisie btn ';
                                                        if (strpos($res,"N")!== false) echo 'btn-primary'; else echo 'btn-default';
                                                        echo '" id="btn'.$i.'N" ';
                                                        echo '>NUL</button>'; 
                                                    ?>
                                                  </div>

                                                  <div class="btn-group" role="group">
                                                    <?php 
                                                        echo '<button type="button" class="btnSaisie btn ';
                                                        if (strpos($res,"2")!== false) echo 'btn-primary'; else echo 'btn-default';
                                                        echo '" id="btn'.$i.'2" ';
                                                        echo '>' . getEquipe($jeu, $i, "E") . '</button>'; 
                                                    ?>
                                                  </div>
                                                  
                                                </div>
                                             <?php
                                            }

                                        }  
                                } else {
                                    ?>
                                    <div class="btnGroupeSaisie btn-group btn-group-justified" role="group" >
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btnflash7 btn btn-success btn-lg btn-block">Flash 7</button>
                                        </div>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btnflash15 btn btn-success btn-lg btn-block">Flash 15</button>
                                        </div>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btnflashEffacer btn btn-success btn-lg btn-block">Effacer</button>
                                        </div>
                                    </div>   
                                    <br> 
                           
                                    <?php
                                        /* CAS 2 : Saisie du pronostic du joueur */
                                        for($i=1; $i <= 15 ; $i++) 
                                        {
                                        	if ($i==8) {
                                        		echo "<hr style='width:100%;color:firebrick;margin:5px 0;background-color:firebrick;height:3px;' />";
                                        	}
                                        	 
                                            if (getEquipe($jeu, $i, "D")!="") {
                                                $res= getProno($pronostic, $i);
                                                // 2a : Ligne Match aves les trois choix
                                                ?>
                                                <div class="btnGroupeSaisie btn-group btn-group-justified" role="group" >
                                                  <div class="btn-group" role="group">
                                                    <?php 
                                                        echo '<button type="button" class="btnSaisie btn ';
                                                        if (strpos($res,"1")!== false) echo 'btn-primary'; else echo 'btn-default';
                                                        echo '" id="btn'.$i.'1" ';
                                                        echo '>' . getEquipe($jeu, $i, "D") . '</button>'; 
                                                    ?>
                                                  </div>

                                                  <div class="btn-group" role="group">
                                                    <?php 
                                                        echo '<button type="button" class="btnSaisie btn ';
                                                        if (strpos($res,"N")!== false) echo 'btn-primary'; else echo 'btn-default';
                                                        echo '" id="btn'.$i.'N" ';
                                                        echo '>NUL</button>'; 
                                                    ?>
                                                  </div>

                                                  <div class="btn-group" role="group">
                                                    <?php 
                                                        echo '<button type="button" class="btnSaisie btn ';
                                                        if (strpos($res,"2")!== false) echo 'btn-primary'; else echo 'btn-default';
                                                        echo '" id="btn'.$i.'2" ';
                                                       echo '>' . getEquipe($jeu, $i, "E") . '</button>'; 
                                                    ?>
                                                  </div>
                                                  
                                                </div>

                                                 <?php
                                                // 2b : Ligne Pourcentage Cote
                                                echo '<div class="progress">';
                                                $indicateurMatch= getIndicateursMatch($indicateurs, $i);
                                                if (isset($indicateurMatch)) {
                                                  // Indicateur 1
                                                  $classDeselection="";
                                                  if (strlen($res)>0 && strpos($res,"1")===false) {
                                                    $classDeselection="deselection";
                                                  }
                                                  echo '<div class="progress-bar '.$indicateurMatch["pourcentageC1"].' '.$classDeselection.'" style="width:'.str_replace(',','.',$indicateurMatch["pourcentage1"]).'%"';
                                                  echo '" id="ind'.$i.'1" >';
                                                  echo $indicateurMatch["pourcentage1"].'%';
                                                  echo '</div>';
                                                  // Indicateur 2
                                                  $classDeselection="";
                                                  if (strlen($res)>0 && strpos($res,"N")===false) {
                                                    $classDeselection="deselection";
                                                  }
                                                  echo '<div class="progress-bar '.$indicateurMatch["pourcentageCN"].' '.$classDeselection.'" style="width:'.str_replace(',','.',$indicateurMatch["pourcentageN"]).'%"';
                                                  echo '" id="ind'.$i.'N" >';
                                                  echo $indicateurMatch["pourcentageN"].'%';
                                                  echo '</div>';
                                                  // Indicateur 3
                                                  $classDeselection="";
                                                  if (strlen($res)>0 && strpos($res,"2")===false) {
                                                    $classDeselection="deselection";
                                                  }
                                                  $difference= 100-str_replace(',','.',$indicateurMatch["pourcentage1"])-str_replace(',','.',$indicateurMatch["pourcentageN"]);
                                                  echo '<div class="progress-bar '.$indicateurMatch["pourcentageC2"].' '.$classDeselection.'" style="width:'.str_replace(',','.',$difference).'%"';
                                                  echo '" id="ind'.$i.'2" >';
                                                  echo $indicateurMatch["pourcentage2"].'%';
                                                  echo '</div>';
                                              }
                                                echo '</div>';
                                                     
                                            }
                                        }  
                                }
                                ?>   
      
                            </div>
                            <br>
                            <?php
                                if (getEquipe($jeu, 1, "D")!="") {
                            ?>
                                <button id="btnValider" class="btn btn-success btn-lg btn-block" type="button">Valider</button>
                            <?php
                                }
                            ?>
                        </div>
                        <!-- .panel-body -->
                    </div>

                    <!-- /.panel -->
                </div>
            </div>


           <div id="zoneEstimationRapports" class="row" style="display:none">
                <div class="col-lg-6">
                    <div class="panel panel-default">                       
                        <div class="panel-heading">
                            <i class="fa fa-cc-visa"></i>
                            <span id="titreEstimationRapports7">Estimation Rapports Loto Foot 7</span>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">     
                            <table class="table table-striped table-bordered table-hover" id="dataTables-caisse">
                                <thead class="bg-primary ">
                                    <tr>
                                        <th class="text-center">Rang</th>
                                        <th class="text-center">Rapport</th>
                                    </tr>
                                </thead> 
                                <tbody id="tbodyEstimationRapports7">
                                </tbody>
                            </table>
              
                        </div>    
                    </div>   
                    <!-- /.panel-body -->   
                </div>   
                <div class="col-lg-6">
                    <div class="panel panel-default">                       
                        <div class="panel-heading">
                            <i class="fa fa-cc-visa"></i>
                            <span id="titreEstimationRapports15">Estimation Rapports Loto Foot 15</span>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">     
                            <table class="table table-striped table-bordered table-hover" id="dataTables-caisse">
                                <thead class="bg-primary ">
                                    <tr>
                                        <th class="text-center">Rang</th>
                                        <th class="text-center">Rapport</th>
                                    </tr>
                                </thead> 
                                <tbody id="tbodyEstimationRapports15">
                                </tbody>
                            </table>
              
                        </div>    
                    </div>   
                    <!-- /.panel-body -->   
                </div> 
            </div>
            <div id="idBaseDePageSaisie"></div>
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

