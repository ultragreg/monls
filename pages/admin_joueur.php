<?php include("composants/head.php"); ?>
<body>
    <div id="wrapper">

        <?php 
        if ( ( !isset($_SESSION['id_joueur']) ) || ( isset($_SESSION['id_joueur']) && $_SESSION['admin']<>'O' )  )
        {
            header('Location: index.php');
        }

        // include database and object files
        include_once 'config/database.php';
        include_once 'objects/saison.php';
        include_once 'objects/joueur.php';

        // instantiate database and product object
        $database = new Database();
        $db = $database->getConnection();

        // Recherche de toute les saisons
        $saison = new Saison($db);
        $saison = $saison->chargeSaisonCourante();

        // Nombre de Jeu

        $joueur = new Joueur($db);
        $joueur->saison_id = $saison->saison_id;
        $stmtJoueurs = $joueur->litTousLesJoueurs();
        $num = $stmtJoueurs->rowCount();

        include_once 'composants/nav.php';

        ?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Administration des joueurs</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-caisse">
                                    <thead class="bg-primary ">
                                        <tr>
                                            <th>Id</th>
                                            <th>Nom</th>
                                            <th>Pseudo</th>
                                            <th>Mdp</th>
                                            <th>Der. Connexion</th>
                                            <th>Nav. utilisé</th>
                                            <th>Admin</th>
                                            <th>Actif</th>
                                            <th class='text-center'>MAJ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total=0;
                                        if($num>0){
                                                while ($row = $stmtJoueurs->fetch(PDO::FETCH_ASSOC)) {
                                                    extract($row);
                                                    echo "<tr>";
                                                        echo "<td>{$joueur_id}</td>";
                                                        echo "<td>{$nom}</td>";
                                                        echo "<td>{$pseudo}</td>";
                                                        echo "<td>{$mdp}</td>";
                                                        echo "<td>".formatte_date($der_cnx,1)."</td>";
                                                        echo "<td>{$der_navigateur}</td>";
                                                        echo "<td>{$administrateur}</td>";
                                                        echo "<td>{$actif}</td>";
                                                        echo "<td class='text-center ' ><a href='admin_joueur.php?id={$joueur_id}' class='btn btn-primary btn-xs' title='Modification'>";
                                                        echo "<span class='glyphicon glyphicon-ok' aria-hidden='true'></span></a></td>";
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

