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
        include_once 'objects/jeu.php';

        // instantiate database and product object
        $database = new Database();
        $db = $database->getConnection();

        // Recherche de toute les saisons
        $saison = new Saison($db);
        $saison = $saison->chargeSaisonCourante();

        include_once 'composants/nav.php';

        ?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Administration</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>


            <!-- /.row -->
            <div class="row ">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Sauvegarde de la base de données
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <p>Cette option permet d'envoyer vers une adresse mail une sauvegarde complète de la base de données (structure et données).</p>
                            <form>
                                <div class="form-group">
                                    <label for="mail" class="control-label">Mails des destinataires</label>
                                    <input type="mail" class="form-control" value="" name="mail" id="mailDestinatairesSauveDB" required>                       
                                </div>
                                <button type="submit" class="btn btn-primary" id="btnSauveDB">Ok</button>    
                            </form>                            
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

