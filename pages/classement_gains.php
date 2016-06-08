<?php include("composants/head.php"); ?>

<body>
    <div id="wrapper">
        <?php 
        // include database and object files
        include_once 'config/database.php';
        include_once 'config/util.php';
        include_once 'objects/gain.php';
        include_once 'objects/classement.php';
        include_once 'objects/saison.php';
        include_once 'objects/joueur.php';
        include_once 'objects/jeu.php';
         
        // instantiate database and product object
        $database = new Database();
        $db = $database->getConnection();
         
        // Quelle saison à charger ? 
        $saison = new Saison($db);
        if (isset($_POST['saisonForm']))
         {
            $saison->saison_id = $_POST['saisonForm'];
            $saison = $saison->chargeSaison();
        } else {
            // Recherche de la saison courante
            $saison = $saison->chargeSaisonCourante();
        }

   
        // Nombre de Jeu
        $jeu = new Jeu($db);
        $jeu->saison_id = $saison->saison_id;
        $jeu->chargeNombreJeux();
        $jeu_nombre = $jeu->jeu_nombre;
        $jeu->chargeDernierJeu();

        // Liste des opérations en caisse
        $gain = new Gain($db);
        $gain->saison_id = $saison->saison_id; 
        $gainsJson = $gain->litGainsJson();
        $stmt = $gain->litGains();
        $num = $stmt->rowCount();

        // Liste des saisons
        $stmtSaisonsGain = $saison->litSaisons();
        $listeSaisonsGain = $stmtSaisonsGain->fetchAll(PDO::FETCH_ASSOC);

        // Recherche la liste des joueurs
        $joueurs = new Joueur($db);
        $stmtJoueurs = $joueurs->litJoueurs();
         
        // Classement
        $classement = new Classement($db);
        $classement->saison_id = $saison->saison_id; 
        $stmtClassement = $classement->litClassement();
        $listeClassementGeneral = $stmtClassement->fetchAll(PDO::FETCH_ASSOC);
    
        include_once 'composants/nav.php';
        ?>

        <span id="dataGains" style="display:none;">
            <?php echo $gainsJson; ?>
        </span>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header hidden-xs">Classement par gains</h1>
                    <h1 class="page-header visible-xs">Class. Gains</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>


            <div class="row formulaire">
                <form  class="form-horizontal" action="classement_gains.php" method="post">
                    <div class="col-xs-10">
                        <select class="form-control" name="saisonForm">
                        <?php
                            for($i=0;$i<sizeof($listeSaisonsGain);$i++) {
                                $s=$listeSaisonsGain[$i];
                                echo "<option value='".$s["saison_id"]."'";
                                if ($saison->saison_id==$s["saison_id"]) {
                                    echo " selected>";
                                } else {
                                    echo ">";
                                }
                                echo $s["nom"]."</option>\n";
                            }
                        ?>
                        </select>                                   
                    </div>
                    <div class="col-xs-2">
                        <span class="pull-right">
                            <button type="submit" class="btn btn-primary">Ok</button>    
                        </span>                         
                    </div>
                </form>
            </div>


            <!-- /.row -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-caisse">
                                <thead class="bg-primary ">
                                    <tr>
                                        <th class="text-center">Position</th>
                                        <th class="text-center">Nom (Classement Gen.)</th>
                                        <th class="text-center">Gain</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    <?php
                                    if($num>0) {
                                        $pos=0;
                                        $cpt=0;
                                        $totalprec=-1;
                                        $tab=array();
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            extract($row);
                                            if ($total!=$totalprec) {
                                                $pos=$pos+1;
                                            }
                                            $positionClassementGen = getPositionClassement($joueur_id,$listeClassementGeneral);
                                            $tab[$cpt]=array( 'nom'=> $nom, 
                                                            'total' => $total, 
                                                            'position' =>(($pos)*100)+$positionClassementGen,
                                                            'positionClassement' =>$positionClassementGen);
                                            $totalprec=$total;
                                            $cpt=$cpt+1;
                                        }
                                        // Tableau trié ? 
                                        if ($tab) {
                                           usort($tab, "comparePosition");
                                           $i=0;
                                           $totalGeneral=0;
                                           foreach ($tab as &$value) {
                                                echo "<tr>";
                                                echo "<td class='visible-xs'>".getPositionChiffre($i)."</td>";
                                                echo "<td class='hidden-xs'>".getPosition($i)."</td>";
                                                if (!isset($value['positionClassement']))  {
                                                    $posClassement="";
                                                } elseif ($value['positionClassement'] == 1)  {
                                                    $posClassement=" (1 er)";
                                                } else {
                                                    $posClassement=" (".$value['positionClassement']." ème)";
                                                }

                                                echo "<td>" . $value['nom'] . $posClassement . "</td>";
                                                echo "<td class='text-center'>".number_format($value['total'],2)."&nbsp;&euro;</td>";
                                                echo "</tr>";
                                                $totalGeneral=$totalGeneral+$total;
                                                $i=$i+1;
                                            }
                                        }
                                    }
                                    ?>                                    

                                </tbody>
                                <tfoot class="bg-primary ">
                                    <tr>
                                        <th colspan=2 class="text-center">Total</th>
                                        <th class="text-center"><?php echo number_format($totalGeneral,2); ?> &euro;</th>
                                    </tr>
                                </tfoot> 
                            </table>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
                <div class="col-lg-6">
                     <div class="panel-body">
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="flot-pie-gains"></div>
                            </div>
                        </div>
                    <!-- /.panel -->
                </div>
            </div>

            <!-- /.row -->
            <div class="row hidden-xs hidden-sm">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Règle de répartition des gains (décision modificative du 22/06/2015)
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <p>Quel que soit le gain, la répartition se fait par tranche :</p>
                            <ul>
                                <li>Tranche 1 : 0 à 420€ => remise en caisse</li>
                                <li>Tranche 2 : 420€ à 840€ => au joueur ayant gagné</li>
                                <li>Tranche 3 : Au-dessus de 840€ => partage entre l’ensemble des joueurs</li>
                            </ul>
                            <p>La valeur de référence est fixé par cette règle : 15 * nombre de joueur actifs à ce jour 
                                (<?php echo $stmtJoueurs->rowCount(); ?>) </p>
                            <p>La somme restant en caisse à la fin de la saison sera partagée (équitablement) par décision du Président.</p>
                            <p>Exemple : Gain de 1200€, la répartition sera la suivante :</p>
                            <ul>
                                <li>420€ en caisse</li>
                                <li>420€ au joueur gagnant</li>
                                <li>360€ à partager soit 12,85€/joueur</li>
                            </ul>
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

    <!-- Flot Charts JavaScript -->
    <script src="../bower_components/flot/excanvas.min.js"></script>
    <script src="../bower_components/flot/jquery.flot.js"></script>
    <script src="../bower_components/flot/jquery.flot.pie.js"></script>
    <script src="../bower_components/flot/jquery.flot.resize.js"></script>
    <script src="../bower_components/flot/jquery.flot.time.js"></script>
    <script src="../bower_components/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>
    <!--
    <script src="../js/flot-data.js"></script>
-->

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
    
    <!-- Toast MonLs -->
    <script src="../dist/js/toastr.min.js"></script>

    <!-- Javascript MonLs -->
    <script src="../dist/js/monls.js"></script>

<script>

//Flot Line Chart
$(document).ready(function() {
    //Flot Pie Chart
    $(function() {    
        var div = document.getElementById("dataGains");
        var data = JSON.parse(div.textContent);
        if (data.length) {
                var plotObj = $.plot($("#flot-pie-gains"), data, {
                    series: {
                        pie: {
                            show: true
                        }
                    },
                    grid: {
                        hoverable: true
                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: "%s : %p.0%", // show percentages, rounding to 2 decimal places
                        shifts: {
                            x: 20,
                            y: 0
                        },
                        defaultTheme: false
                    }
                });
        }

    });
});
</script>

</body>

</html>
