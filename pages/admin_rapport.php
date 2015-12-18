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
        include_once 'objects/rapport.php';

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
        $stmtJeux = $jeu->litJeuxAvecRapports();
        $num = $stmtJeux->rowCount();


        include_once 'composants/nav.php';
        ?>
       <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Administration des rapports</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <!-- /.row -->
            <div class="row">

                <div class="col-lg-12">
                    <button type="button" class="btn btn-primary" id="nouveauRapport7">
                        Nouveau Rapport à 7 
                    </button>
                    <button type="button" class="btn btn-primary" id="nouveauRapport15">
                        Nouveau Rapport à 15
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
                                            <th>Titre</th>
                                            <th>Commentaire</th>
                                            <th class='text-center' >Voir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total=0;
                                        if($num>0){
                                                while ($row = $stmtJeux->fetch(PDO::FETCH_ASSOC)) {
                                                    extract($row);
                                                    echo "<tr>";
                                                        echo "<td>{$jeu_id}</td>";
                                                        echo "<td>{$titre}</td>";
                                                        echo "<td>{$commentaire}</td>";
                                                        echo "<td class='text-center' ><a href='#' data-id='{$jeu_id}''  data-nom='{$titre}:{$commentaire}'  class='rechercheRapport btn btn-primary btn-xs' title='Modification'>";
                                                        echo "<span class='glyphicon glyphicon-zoom-in' aria-hidden='true'></span></a></td>";
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


    <!-- Modal - MAJ des rapports -->
   <div class="modal fade" id="myRapportPopup" tabindex="-1">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="rapportTitreOperation">Voir les rapports d'un jeu</h4>
            <input type="hidden" class="form-control" id="idJeuTypeRapport">
          </div>
            <form>
            <div class="modal-body">
                <div id="idDivRapportExistant">
                    <input type="hidden" class="form-control" id="idJeuRapport">
                    <div class="form-group">
                        <label class="control-label" id="nomJeuDetailRapport">Nom</label>
                    </div>                
                    <div class="panel-body">
                        <div class="dataTable_wrapper" id="tableRapports7">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-caisse">
                                <thead class="bg-primary ">
                                    <tr>
                                        <th>Rang</th>
                                        <th>Nombre</th>
                                        <th>Rapport</th>
                                    </tr>
                                </thead>
                                <tbody id="bodyTableRapports7">
                                </tbody>
                            </table>
                        </div>
                        <div class="dataTable_wrapper"  id="tableRapports15">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-caisse">
                                <thead class="bg-primary ">
                                    <tr>
                                        <th>Rang</th>
                                        <th>Nombre</th>
                                        <th>Rapport</th>
                                    </tr>
                                </thead>
                                <tbody id="bodyTableRapports15">
                                </tbody>
                            </table>
                        </div>                    
                    </div> 
                </div> 
                <div id="idDivNouveauRapport"></div>               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary " data-dismiss="modal" id="fermerPopupRapport">Fermer</button>
            </div>
        </form>
        </div>
      </div>
    </div>

    <!-- Modal Start here-->
    <div class="modal fade bs-example-modal-sm" id="myPleaseWait" tabindex="-1"
        role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Merci de patienter
                     </h4>
                </div>
                <div class="modal-body">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped active"
                        style="width: 100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal ends Here -->


</body>
</html>
