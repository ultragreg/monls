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
        include_once 'objects/gain.php';
        include_once 'objects/joueur.php';

        // instantiate database and product object
        $database = new Database();
        $db = $database->getConnection();

        // Recherche de toute les saisons
        $saison = new Saison($db);
        $saison = $saison->chargeSaisonCourante();

        // Nombre de Gains
        $gain = new Gain($db);
        $gain->saison_id = $saison->saison_id;
        $stmtGains = $gain->litGainsDistincts();
        $num = $stmtGains->rowCount();

        // Recherche la liste des joueurs
        $joueurs = new Joueur($db);
        $stmtJoueurs = $joueurs->litJoueurs();
        $listeJoueurs = $stmtJoueurs->fetchAll(PDO::FETCH_ASSOC);


        include_once 'composants/nav.php';

        ?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Administration des gains</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <!-- /.row -->
            <div class="row">

                <div class="col-lg-12">
                    <button type="button" class="btn btn-primary" id="nouveauGain">
                        Nouveau Gain
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
                                            <th>Joueur</th>
                                            <th>Date</th>
                                            <th>Somme</th>
                                            <th class='text-center' >MAJ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total=0;
                                        if($num>0){
                                            while ($row = $stmtGains->fetch(PDO::FETCH_ASSOC)) {
                                                extract($row);
                                                echo "<tr>";
                                                    echo "<td>{$gain_id}</td>";
                                                    echo "<td>{$nom}</td>";
                                                    echo "<td>".formatte_date($date,1)."</td>";
                                                    echo "<td>".number_format($total,2)."</td>";
                                                    echo "<td class='text-center ' ><a href='#' data-id={$gain_id}  class='rechercheGain btn btn-primary btn-xs' title='Modification'>";
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

    <!-- Modal - MAJ des gains -->
   <div class="modal fade" id="myGainPopup" tabindex="-1">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="gainTitreOperation">Modification d'un gain</h4>
          </div>
            <form>
            <div class="modal-body">
                <input type="hidden" class="form-control" id="idGain">
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Joueur</label><br>
                    <select id="joueur_id" name="joueur_id" class="form-control">
                    <?php
                       for($i=0;$i<sizeof($listeJoueurs);$i++)
                       {
                          $joueur=$listeJoueurs[$i];                              
                          echo "<option id='jid".$joueur["joueur_id"]."' value='".$joueur["joueur_id"]."''>";
                          echo $joueur["nom"];
                          echo "</option>";
                        }
                    ?>  
                    </select>                  
                </div>
                <div class="form-group">
                    <label for="message-text" class="control-label">Somme</label>
                    <input type="text" class="form-control" id="sommeGain">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="sauverGain">Sauver</button>
            </div>
        </form>
        </div>
      </div>
    </div>


</body>
</html>

