<?php include("composants/head.php"); ?>

<body>

    <div id="wrapper">

        <?php 

        // include database and object files
        include_once 'config/database.php';
        include_once 'config/util.php';
        include_once 'objects/saison.php';
        include_once 'objects/chat.php';
         
        // instantiate database and product object
        $database = new Database();
        $db = $database->getConnection();
         
        // Recherche de la saison courante
        $saison = new Saison($db);
        $saison = $saison->chargeSaisonCourante();

        // Liste des opérations en caisse
        $chat = new Chat($db);
        $chat->saison_id = $saison->saison_id; 
        $stmtChat = $chat->litChatMessages();
        $chatMessages = $stmtChat->fetchAll(PDO::FETCH_ASSOC);

        include_once 'composants/nav.php';
        ?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Chat du Ls</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>


            <div class="row formulaire">
                <div class="col-xs-12">
                    <h1 style="color:#E18C8A;text-align:center"><i>Prochainement ...</i></h1>
                </div>
            </div>


            <div class="row formulaire">
                <form  class="form-horizontal" action="resultats.php" method="post">
                    <div class="col-xs-10">
                        <input type="text" class="form-control" id="inputMessage" placeholder="Votre message">                
                    </div>
                    <div class="col-xs-2">
                        <span class="pull-right">
                            <button type="submit" class="btn btn-primary">Go</button>    
                        </span>                         
                    </div>
                </form>
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
                            <ul class="chat">

                                <?php
                                for($j=0;$j<sizeof($chatMessages);$j++)  {     
                                if (sizeof($chatMessages)>$j) {
                                   $message=$chatMessages[$j];
                                   if ($message['ts'] !="") {
                                   ?>
                                    <li class="left clearfix">
                                        <?php if ($j%2) {  ?>
                                            <span class="chat-img pull-right">
                                                <img class="img-circle" alt="User Avatar" src="../dist/css/i1.png">
                                            </span>
                                        <?php } else {  ?>
                                            <span class="chat-img pull-left">
                                                <img class="img-circle" alt="User Avatar" src="../dist/css/i2.png">
                                            </span>
                                        <?php }  ?>
                                        <div class="chat-body clearfix">
                                            <div class="header">
                                            <?php if ($j%2) {  ?>
                                                <small class=" text-muted"> 
                                                <i class="fa fa-clock-o fa-fw"></i> <?php echo $message['ts'] ?></small>
                                            <strong class="pull-right primary-font"><?php echo $message['nom'] ?></strong>  
                                            <?php } else {  ?>
                                                <strong class="primary-font"><?php echo $message['nom'] ?></strong>
                                                <small class="pull-right text-muted">
                                                    <i class="fa fa-clock-o fa-fw"></i> <?php echo $message['ts'] ?>
                                                </small>
                                            <?php }  ?>                                              
                                            </div>
                                            <br>
                                            <p>
                                                <?php echo $message['message'] ?>
                                            </p>
                                        </div>
                                    </li>                                   
                                <?php  
                                    }                                  
                                }  
                                }  
                                ?> 

                            </ul>
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
