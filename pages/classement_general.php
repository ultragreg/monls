 <?php include("composants/head.php"); ?>

<body>

    <div id="wrapper">

        <?php 


        // include database and object files
        include_once 'config/database.php';
        include_once 'objects/saison.php';
        include_once 'objects/classement.php';
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

   
        // Liste des saisons
        $stmtSaisonsGain = $saison->litSaisons();
        $listeSaisonsGain = $stmtSaisonsGain->fetchAll(PDO::FETCH_ASSOC);

        // Classement
        $classement = new Classement($db);
        $classement->saison_id = $saison->saison_id; 
        $stmtClassement = $classement->litClassement();
   
        // Nombre de Jeu
        $jeu = new Jeu($db);
        $jeu->saison_id = $saison->saison_id;
        $jeu->chargeNombreJeux();
        $jeu_nombre = $jeu->jeu_nombre;
        $jeu->chargeDernierJeu();
        
        include_once 'composants/nav.php';

        ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header hidden-xs">Classement sans les gains</h1>
                    <h1 class="page-header visible-xs">Class. sans gains</h1>                    
                </div>
                <!-- /.col-lg-12 -->
            </div>


            <div class="row formulaire">
                <form  class="form-horizontal" action="classement_general.php" method="post">
                    <div class="col-xs-12">
                        <select class="form-control" name="saisonForm" onchange="this.form.submit()">
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
                </form>
            </div>

            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-caisse">
                                    <thead class="bg-primary ">
                                        <tr>
                                            <th class="text-center">Pos.</th>
                                            <th class="text-center">Evol.</th>
                                            <th class="text-center">Nom</th>
                                            <th class="text-center">Moyenne</th>
                                            <th class="text-center hidden-xs hidden-sm">Moyenne Joueur /<br>Répartition Moy. Saison (*)</th>
                                            <th class="text-center hidden-xs hidden-sm">Position /<br>Prise de risque</th>
                                        </tr>
                                    </thead>                                    
                                    <tbody>
                                        <?php 
                                        $i=1;
                                        $posprec=0;
                                        while ($row = $stmtClassement->fetch(PDO::FETCH_ASSOC)) {
                                            extract($row);
                                            echo "<tr>";
                                            // On gère le cas où il y a égalité entre deux joueurs ou plus 
                                            if ($posprec!=$moyenne) {
                                                echo "<td class='text-center'>{$i}</td>";
                                            }
                                            else {
                                                echo "<td class='text-center'>&nbsp;</td>";
                                            }
                                                echo "<td class='text-center'>";
                                               if ($evolution>0) {
                                                        echo "&nbsp;&nbsp;<span class='fa  fa-caret-up medium vert'> +{$evolution}</span>";
                                                } elseif ($evolution<0) {
                                                        echo "&nbsp;&nbsp;<span class='fa  fa-caret-down medium rouge'> {$evolution}</span>";
                                                }                                                 
                                                echo "</td>";
                                                echo "<td>&nbsp;&nbsp;{$nom}</td>";
                                                echo "<td class='text-center'>".number_format($moyenne,2)."</td>";
                                                echo "<td class='text-center hidden-xs hidden-sm'>".number_format($rapport,2)."</td>";
                                                echo "<td class='text-center hidden-xs hidden-sm'>{$posRisque}</td>";
                                            echo "</tr>";
                                            $i=$i+1;
                                            $posprec=$moyenne;
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
 
            </div>

            <!-- /.row -->
            <div class="row hidden-xs hidden-sm">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Explication du classement en fonction de la prise de risque
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">							<p>L'indice de gain est calculé de cette façon en prenant la moyenne des répartitions de chaque match correspondant aux pronostics du joueur : (100-MoyRepart)/10<br>							Exemple : sur une journée, la répartition moyenne d'un joueur est de 44,5%. Son indice de gain sera (100-44,5)/10 = 5,55</p>							
                            <p>Plus l'indice est élevé, plus la prise de risque est grande.</p> 
                            <p>Pour calculer le classement de la prise de risque, on fait le rapport entre la moyenne du joueur et sa répartition moyenne de jeu sur la saison.<br>                            Exemple : Moyenne du joueur sur la saison : 51% - Répartition moyenne jouée sur la saison : 55% (Indice de gain moyen de 4,5) ==> Rapport 51/55=0,93</p>
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
