<?php include("composants/head.php"); ?>

<body>

    <div id="wrapper">

        <?php 

        // include database and object files
        include_once 'config/database.php';
        include_once 'config/util.php';
        include_once 'objects/caisse.php';
        include_once 'objects/saison.php';
        include_once 'objects/jeu.php';
      
        // instantiate database and product object
        $database = new Database();
        $db = $database->getConnection();
        
        // Recherche de la saison courante
        $saison = new Saison($db);
        $saison = $saison->chargeSaisonCourante();

        // Nombre de Jeu
        $jeu = new Jeu($db);
        $jeu->saison_id = $saison->saison_id;
        $jeu->chargeNombreJeux();
        $jeu_nombre = $jeu->jeu_nombre;
        $jeu->chargeDernierJeu();
   
        // Liste des opérations en caisse
        $caisse = new Caisse($db);
        $caisse->saison_id = $saison->saison_id;
        $caisse->chargeSoldeSaison();
        $stmt = $caisse->litOperationsCaisseSaison();
        $num = $stmt->rowCount();

        include_once 'composants/nav.php';
        ?>

        <div id="page-wrapper">

            <div class="row">

                <div class="col-lg-12">

                    <h1 class="page-header hidden-xs">Caisse
                        <?php
                        if ($caisse->caisse_total>0) {
                            echo " positive de ".$caisse->caisse_total."&nbsp;&euro;";
                        } 
                        else {
                            echo " négative de ".$caisse->caisse_total."&nbsp;&euro;";
                        }
                        ?>
                    </h1>
                    <h1 class="page-header visible-xs">
                        <?php
                        if ($caisse->caisse_total>0) {
                            echo "+".$caisse->caisse_total."&nbsp;&euro;";
                        } 
                        else {
                            echo $caisse->caisse_total."&nbsp;&euro;";
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
                            <?php echo $saison->saison_nom; ?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-caisse">
                                    <thead class="bg-primary ">
                                        <tr>
                                            <th class="hidden-xs hidden-sm">Opérations</th>
                                            <th>Date</th>
                                            <th>Débit</th>
                                            <th>Crédit</th>
                                            <th>Solde</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total=0;
                                        if($num>0){
                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    extract($row);
                                                    $total = $total - $caisse_somme_debit + $caisse_somme_credit;
                                                    echo "<tr>";
                                                        echo "<td class='hidden-xs hidden-sm'>{$caisse_libelle}</td>";
                                                        echo "<td>".formatte_date($caisse_date,'1')."</td>";
                                                        if ($caisse_somme_debit==0) {
                                                            echo "<td></td>";
                                                        } else {
                                                            echo "<td>".number_format($caisse_somme_debit,2)."</td>";
                                                        }
                                                        if ($caisse_somme_credit==0) {
                                                            echo "<td></td>";
                                                        } else {
                                                            echo "<td>".number_format($caisse_somme_credit,2)."</td>";
                                                        }
                                                        echo "<td>".number_format($total,2)."</td>";
                                                    echo "</tr>";
                                                }   
                                        } 
                                        ?>                                    

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
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

    <!-- DataTables JavaScript -->
    <script src="../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
    
    <!-- Toast MonLs -->
    <script src="../dist/js/toastr.min.js"></script>

    <!-- Javascript MonLs -->
    <script src="../dist/js/monls.js"></script>

</body>
</html>

