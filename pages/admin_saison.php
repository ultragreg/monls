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

        // instantiate database and product object
        $database = new Database();
        $db = $database->getConnection();

        // Recherche de toute les saisons
        $saison = new Saison($db);
        $stmt = $saison->LitSaisons();
        $num = $stmt->rowCount();

        include_once 'composants/nav.php';
        ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Administration des saisons</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <!-- /.row -->
            <div class="row">

                <div class="col-lg-12">
                    <button type="button" class="btn btn-primary" id="nouvelleSaison">
                        Nouvelle Saison
                    </button>
                    <br><br>
                </div>
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
                                            <th>Commentaire</th>
                                            <th class='text-center' >MAJ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total=0;
                                        if($num>0) {
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                extract($row);
                                                echo "<tr>";
                                                    echo "<td>{$saison_id}</td>";
                                                    echo "<td>{$nom}</td>";
                                                    echo "<td>{$commentaire}</td>";
                                                    echo "<td class='text-center' ><a href='#' data-id={$saison_id}  class='rechercheSaison btn btn-primary btn-xs' title='Modification'>";
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


    <!-- Modal - MAJ des saisons -->
   <div class="modal fade" id="mySaisonPopup" tabindex="-1">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="saisonTitreOperation">Modification d'une saison</h4>
          </div>
            <form>
            <div class="modal-body">
                <input type="hidden" class="form-control" id="idSaison">
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Nom</label>
                    <input type="text" class="form-control" id="nomSaison">
                </div>
                <div class="form-group">
                    <label for="message-text" class="control-label">Commentaires</label>
                    <input type="text" class="form-control" id="commentaireSaison">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="sauverSaison">Sauver</button>
            </div>
        </form>
        </div>
      </div>
    </div>

</body>
</html>
