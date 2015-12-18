<?php include("composants/head.php"); ?>



<body>



    <div id="wrapper">
        <?php 

        // include database and object files
        include_once 'config/database.php';
        include_once 'config/util.php';
        include_once 'objects/saison.php';
        include_once 'objects/caisse.php';
        include_once 'objects/statistique.php';
        include_once 'objects/jeu.php';

         

        // instantiate database and product object

        $database = new Database();

        $db = $database->getConnection();

         

        // Recherche de la saison courante

        $saison = new Saison($db);

        $saison = $saison->chargeSaisonCourante();

    

        // Recherche de la saison courante

        $stat = new Statistique($db);

        $stat->saison_id = $saison->saison_id;



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

                    <h1 class="page-header">Statistiques

                    </h1>

                </div>

                <!-- /.col-lg-12 -->

            </div>



            <?php

            $stmt = $stat->litSommeGainsParSaison();

            $num = $stmt->rowCount();

            if($num>0){

            ?>

            <div class="row">

                <div class="col-lg-12">

                    <div class="panel panel-primary">

                        <div class="panel-heading">

                            Somme des Gains / saison

                        </div>

                        <div class="panel-body">

                                <table class="table table-striped table-bordered table-hover" id="dataTables-caisse">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Saison</th>
                                            <th class="text-center">Somme</th>
                                            <th class="text-center">Nb de Gains</th>
                                            <th class="text-center">Nb de Jeu</th>
                                            <th class="text-center">Nb de Prono</th>
                                            <th class="text-center">Moy. nbre gain / nbre jeu</th>
                                            <th class="text-center">Moy. montant gain / montant total </th>
                                            <th class="text-center">Gain moyen / Nb jeu</th>                                           
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php

                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            extract($row);
                                            $moyenneNombreGain     = "";
                                            $moyenneGain     = "";
                                            if ($nbmatch)  
                                            {
                                              $moyenneNombreGain=$nbgain/$nbmatch*100;
                                              $moyenneNombreGain=round($moyenneNombreGain,1);
                                            }
                                            if ($nbprono)  
                                            {
                                              $totaltemp = str_replace(",","",$total);
                                              $moyenneGain=$totaltemp/$nbprono*100;
                                              $moyenneGain=round($moyenneGain,1);
                                            }
                                            if ($nbmatch)  
                                            {
                                              $totaltemp = str_replace(",","",$total);
                                              $moyenneGainMatch=$totaltemp/$nbmatch;
                                              $moyenneGainMatch=round($moyenneGainMatch,1);
                                            }
                                            echo "<tr>";
                                                echo "<td>{$saison_nom}</td>";
                                                if ($total!=0) {
                                                    echo "<td class='text-center'>{$total} &euro;</td>";
                                                } else {
                                                    echo "<td class='text-center'>&nbsp;</td>";
                                                }
                                                echo "<td class='text-center'>{$nbgain}</td>";
                                                echo "<td class='text-center'>{$nbmatch}</td>";
                                                echo "<td class='text-center'>{$nbprono}</td>";
                                                echo "<td class='text-center'>{$moyenneNombreGain} %</td>";
                                                echo "<td class='text-center'>{$moyenneGain} %</td>";
                                                if ($total!=0) {
                                                    echo "<td class='text-center'>{$moyenneGainMatch} &euro;</td>";
                                                } else {
                                                    echo "<td class='text-center'>&nbsp;</td>";
                                                }
                                            echo "</tr>";
                                        }    
                                        ?>                                    
                                    </tbody>
                                </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <?php
            }

            $stmt1 = $stat->litSommeGainsToutesSaisons();

            $stmt2 = $stat->litTopMeilleursGainsToutesSaisons();

            ?>

            <div class="row">

                <div class="col-lg-6">

                    <div class="panel panel-danger">

                        <div class="panel-heading">

                            Somme des gains et Nombre de gain par joueur toutes saisons confondues

                        </div>

                        <div class="panel-body">

                                <table class="table table-striped table-bordered table-hover" id="dataTables-caisse">

                                    <thead>

                                        <tr>

                                            <th class="text-center">Nom du Joueur</th>

                                            <th class="text-center">Somme</th>

                                            <th class="text-center">Nb de Gains</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php

                                        while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {

                                            extract($row);

                                            echo "<tr>";

                                                echo "<td>{$nom}</td>";

                                                echo "<td class='text-center'>{$total}</td>";

                                                echo "<td class='text-center'>{$nbgain}</td>";

                                            echo "</tr>";

                                        }    

                                        ?>                                    



                                    </tbody>

                                </table>

                        </div>

                    </div>

                </div>



                <div class="col-lg-6">

                    <div class="panel panel-success">

                        <div class="panel-heading">

                            25 meilleurs gains toutes saisons confondues

                        </div>

                        <div class="panel-body">

                                <table class="table table-striped table-bordered table-hover" id="dataTables-caisse">

                                    <thead>

                                        <tr>

                                            <th class="text-center">Nom du Joueur</th>

                                            <th class="text-center">Somme</th>

                                            <th class="text-center">Saison</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php

                                        while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {

                                            extract($row);

                                            echo "<tr>";

                                                echo "<td>{$jnom}</td>";

                                                echo "<td class='text-center'>{$total}</td>";

                                                echo "<td>{$snom}</td>";

                                            echo "</tr>";

                                        }    

                                        ?>                                    



                                    </tbody>

                                </table> 

                            </div>

                    </div>

                </div>

            </div>

            <!-- /.row -->







            <!-- /.row -->

            <?php

            $stmt1 = $stat->litIndiceJeuxSaison();

            $stmt2 = $stat->litMoyenneIndiceJeuxSaison();

            $stmt3 = $stat->litCouleurSaison();

            $stmt4 = $stat->litMoyenneCouleurSaison();

            $stmt5 = $stat->litResultatSaison();

            $stmt6 = $stat->litMoyenneCouleurSaison();

            ?>

            <div class="row">

                <div class="col-lg-4">

                    <div class="panel panel-green">

                        <div class="panel-heading">

                            Indices de Gain des Grilles de la Saison

                        </div>

                            <div class="panel-body">

                                <table class="table table-striped table-bordered table-hover" id="dataTables-caisse">

                                    <thead>

                                        <tr>

                                            <th rowspan="2" class="text-center">Journée</th>

                                            <th class="text-center">Jeu 7</th>

                                            <th class="text-center">Jeu 15</th>

                                        </tr>

                                        <tr>

                                            <th>&nbsp;</th>

                                            <th>&nbsp;</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php

                                        while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {

                                            extract($row);

                                            echo "<tr>";

                                                echo "<td>{$titre}</td>";

                                                echo "<td class='text-center'>{$jeu7}</td>";

                                                echo "<td class='text-center'>{$jeu15}</td>";

                                            echo "</tr>";

                                        }    

                                        while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {

                                            extract($row);

                                            echo "<tr>";

                                                echo "<td>Moyenne</td>";

                                                echo "<td class='text-center'>{$moy7}</td>";

                                                echo "<td class='text-center'>{$moy15}</td>";

                                            echo "</tr>";

                                        }    

                                        ?>                                    



                                    </tbody>

                                </table>

                            </div>

                    </div>

                </div>

                <!-- /.col-lg-4 -->

                <div class="col-lg-4">

                    <div class="panel panel-yellow">

                        <div class="panel-heading">

                            Répartition Couleur Pronostics/Journée

                        </div>

                        <div class="panel-body">

                                <table class="table table-striped table-bordered table-hover" id="dataTables-caisse">

                                    <thead>

                                        <tr>

                                            <th rowspan="2" class="text-center">Journée</th>

                                            <th colspan="3" class="text-center">Jeu 7</th>

                                            <th colspan="3" class="text-center">Jeu 15</th>

                                        </tr>

                                        <tr>

                                            <th class="text-center bg-success">V.</th>

                                            <th class="text-center bg-warning">J.</th>

                                            <th class="text-center bg-danger">R.</th>

                                            <th class="text-center bg-success">V.</th>

                                            <th class="text-center bg-warning">J.</th>

                                            <th class="text-center bg-danger">R.</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php

                                        while ($row = $stmt3->fetch(PDO::FETCH_ASSOC)) {

                                            extract($row);

                                            echo "<tr>";

                                                echo "<td>{$titre}</td>";

                                                echo "<td class='text-center'>{$vert7}</td>";

                                                echo "<td class='text-center'>{$jaune7}</td>";

                                                echo "<td class='text-center'>{$rouge7}</td>";

                                                echo "<td class='text-center'>{$vert}</td>";

                                                echo "<td class='text-center'>{$jaune}</td>";

                                                echo "<td class='text-center'>{$rouge}</td>";

                                            echo "</tr>";

                                        }      

                                        while ($row = $stmt4->fetch(PDO::FETCH_ASSOC)) {

                                            extract($row);

                                            echo "<tr>";

                                                echo "<td>Moyenne</td>";

                                                echo "<td class='text-center'>{$vert7}</td>";

                                                echo "<td class='text-center'>{$jaune7}</td>";

                                                echo "<td class='text-center'>{$rouge7}</td>";

                                                echo "<td class='text-center'>{$vert}</td>";

                                                echo "<td class='text-center'>{$jaune}</td>";

                                                echo "<td class='text-center'>{$rouge}</td>";

                                            echo "</tr>";

                                        }   

                                        ?>                                    



                                    </tbody>

                                </table>

                            </div>

                    </div>

                </div>

                <!-- /.col-lg-4 -->

                <div class="col-lg-4">

                    <div class="panel panel-red">

                        <div class="panel-heading">

                            Répartition 1N2/Journée

                        </div>

                        <div class="panel-body">

                                <table class="table table-striped table-bordered table-hover" id="dataTables-caisse">

                                    <thead>

                                        <tr>

                                            <th rowspan="2" class="text-center">Journée</th>

                                            <th colspan="3" class="text-center">Jeu 7</th>

                                            <th colspan="3" class="text-center">Jeu 15</th>

                                        </tr>

                                        <tr>

                                            <th class="text-center bg-success">1</th>

                                            <th class="text-center bg-warning">N</th>

                                            <th class="text-center bg-danger">2</th>

                                            <th class="text-center bg-success">1</th>

                                            <th class="text-center bg-warning">N</th>

                                            <th class="text-center bg-danger">2</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php

                                        $compteur=array();

                                        $i=0;

                                        while ($row = $stmt5->fetch(PDO::FETCH_ASSOC)) {

                                            extract($row);

                                            $cpt=array();

                                            $cpt["17"]=0;

                                            $cpt["N7"]=0;

                                            $cpt["27"]=0;

                                            $cpt["1"]=0;

                                            $cpt["N"]=0;

                                            $cpt["2"]=0;     

                                            for($j=1;$j<16;$j++) {

                                                if ($j == 1)       $res= $resultat1;

                                                else if ($j == 2)  $res= $resultat2;

                                                else if ($j == 3)  $res= $resultat3;

                                                else if ($j == 4)  $res= $resultat4;

                                                else if ($j == 5)  $res= $resultat5;

                                                else if ($j == 6)  $res= $resultat6;

                                                else if ($j == 7)  $res= $resultat7;

                                                else if ($j == 8)  $res= $resultat8;

                                                else if ($j == 9)  $res= $resultat9;

                                                else if ($j == 10)  $res= $resultat10;

                                                else if ($j == 11)  $res= $resultat11;

                                                else if ($j == 12)  $res= $resultat12;

                                                else if ($j == 13)  $res= $resultat13;

                                                else if ($j == 14)  $res= $resultat14;

                                                else if ($j == 15)  $res= $resultat15;

                                                else $res = "";

                                                if ($res=="1") {

                                                    $cpt["1"] = $cpt["1"] + 1 ;

                                                    if ($j<8) {

                                                        $cpt["17"] = $cpt["17"] + 1;

                                                    }

                                                }

                                                if ($res=="N") {

                                                    $cpt["N"] = $cpt["N"]+1;

                                                    if ($j<8) {

                                                        $cpt["N7"] = $cpt["N7"] + 1;

                                                    }

                                                }

                                                if ($res=="2") {

                                                    $cpt["2"] = $cpt["2"] + 1;

                                                    if ($j<8) {

                                                        $cpt["27"] = $cpt["27"] + 1;

                                                    }

                                                }

                                                if ($res=="1N2") {

                                                    $cpt["1"] = $cpt["1"] + 1;

                                                    if ($j<8) {

                                                        $cpt["17"] = $cpt["17"] + 1;

                                                    }

                                                }

                                            }

                                            $compteur[$i]=$cpt;

                                            $j=$i+1;

            

                                            echo "<tr>";

                                                echo "<td>{$titre}</td>";

                                                echo "<td>{$cpt['17']}</td>";

                                                echo "<td>{$cpt['N7']}</td>";

                                                echo "<td>{$cpt['27']}</td>";

                                                echo "<td>{$cpt['1']}</td>";

                                                echo "<td>{$cpt['N']}</td>";

                                                echo "<td>{$cpt['2']}</td>";

                                            echo "</tr>";

                                            $i=$i+1;

                                        }



                                        $cpt17=0;

                                        $cptN7=0;

                                        $cpt27=0;

                                        $cpt1=0;

                                        $cptN=0;

                                        $cpt2=0;



                                        for($i=0;$i<sizeof($compteur);$i++)

                                        {

                                            $cpt=$compteur[$i];

                                            // Lecture des propriétés du joueur

                                            $cpt17      = $cpt17 + $cpt["17"];

                                            $cptN7     = $cptN7 + $cpt["N7"];

                                            $cpt27     = $cpt27 + $cpt["27"];

                                            $cpt1      = $cpt1 + $cpt["1"];

                                            $cptN     = $cptN + $cpt["N"];

                                            $cpt2     = $cpt2 + $cpt["2"];

                                        }

                                        if (sizeof($compteur)!=0) {

                                            echo "<tr>";

                                                echo "<td>Moyenne</td>";

                                                echo "<td class='text-center'>".number_format($cpt17/sizeof($compteur),1)."</td>";

                                                echo "<td class='text-center'>".number_format($cptN7/sizeof($compteur),1)."</td>";

                                                echo "<td class='text-center'>".number_format($cpt27/sizeof($compteur),1)."</td>";

                                                echo "<td class='text-center'>".number_format($cpt1/sizeof($compteur),1)."</td>";

                                                echo "<td class='text-center'>".number_format($cptN/sizeof($compteur),1)."</td>";

                                                echo "<td class='text-center'>".number_format($cpt2/sizeof($compteur),1)."</td>";

                                            echo "</tr>";

                                        }



                                        ?>                                    

                                    </tbody>

                                </table>

                            </div>

                    </div>

                </div>        

                <!-- /.col-lg-4 -->

            </div>

            <!-- /.row -->





            <!-- /.row -->

            <?php

            $stmt1 = $stat->litStatIndiceGain7Joueurs();

            $stmt2 = $stat->litStatIndiceGain15Joueurs();

            $stmt3 = $stat->litStatIndiceGainJoueurs();

            ?>





            <!-- /.row -->

            <div class="row">

                <div class="col-lg-4">

                    <div class="panel panel-default">

                        <div class="panel-heading">

                            Prise de risque à 7 hors flash

                        </div>

                        <div class="panel-body">

                                <table class="table table-striped table-bordered table-hover" id="dataTables-caisse">

                                    <thead>

                                        <tr>

                                            <th class="text-center">Nom du Joueur</th>

                                            <th class="text-center">Indice Moyen</th>

                                            <th class="text-center">Nombre de jeu</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php

                                        while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {

                                            extract($row);

                                            echo "<tr>";

                                                echo "<td>{$nom}</td>";

                                                echo "<td class='text-center'>{$moyenne}</td>";

                                                echo "<td class='text-center'>{$nbindice}</td>";

                                            echo "</tr>";

                                        }    

                                        ?>                                    



                                    </tbody>

                                </table>                        

                            </div>

                    </div>

                </div>

                <!-- /.col-lg-4 -->

                <div class="col-lg-4">

                    <div class="panel panel-danger">

                        <div class="panel-heading">

                            Prise de risque à 15

                        </div>

                        <div class="panel-body">

                                 <table class="table table-striped table-bordered table-hover" id="dataTables-caisse">

                                    <thead>

                                        <tr>

                                            <th class="text-center">Nom du Joueur</th>

                                            <th class="text-center">Indice Moyen</th>

                                            <th class="text-center">Nombre de jeu</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php

                                        while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {

                                            extract($row);

                                            echo "<tr>";

                                                echo "<td>{$nom}</td>";

                                                echo "<td class='text-center'>{$moyenne}</td>";

                                                echo "<td class='text-center'>{$nbindice}</td>";

                                            echo "</tr>";

                                        }    

                                        ?>                                    



                                    </tbody>

                                </table>

                             </div>

                    </div>

                </div>

                <!-- /.col-lg-4 -->

                <div class="col-lg-4">

                    <div class="panel panel-warning">

                        <div class="panel-heading">

                            Prise de risque tout jeu

                        </div>

                        <div class="panel-body">

                            <table class="table table-striped table-bordered table-hover" id="dataTables-caisse">

                                    <thead>

                                        <tr>

                                            <th class="text-center">Nom du Joueur</th>

                                            <th class="text-center">Indice Moyen</th>

                                            <th class="text-center">Nombre de jeu</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php

                                        while ($row = $stmt3->fetch(PDO::FETCH_ASSOC)) {

                                            extract($row);

                                            echo "<tr>";

                                                echo "<td>{$nom}</td>";

                                                echo "<td class='text-center'>{$moyenne}</td>";

                                                echo "<td class='text-center'>{$nbindice}</td>";

                                            echo "</tr>";

                                        }    

                                        ?>                                    



                                    </tbody>

                                </table>

                            </div>

                    </div>

                </div>        

                <!-- /.col-lg-4 -->

            </div>

            <!-- /.row -->



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

