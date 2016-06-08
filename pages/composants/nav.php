
<?php 

// include database and object files
include_once 'config/database.php';
include_once 'config/util.php';
include_once 'objects/saison.php';
include_once 'objects/message.php';
include_once 'objects/jeu.php';

?>

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="index.php"  class="hidden-xs">
                    <img src="../dist/css/logo.png" alt="logo"/>
                </a>
                <?php 
                if (isset($_SESSION['nom_joueur'])) 
                {
                    echo '<a class="navbar-brand" href="index.php"><span class="hidden-xs">Bienvenue </span>'.$_SESSION['nom_joueur'].'</a>';
                }
                else 
                {
                    echo '<a class="navbar-brand" href="index.php">Mon LS v2</a>';
                }
                ?>
            </div>
            <!-- /.navbar-header -->

            <?php 
            if (isset($_SESSION['id_joueur'])) 
            {
                echo '<ul class="nav navbar-top-links navbar-right hidden-xs">';
                // instantiate database and product object
                $database = new Database();
                $db = $database->getConnection();

                // Saison courante
                $saisonNav = new Saison($db);
                $saisonNav = $saisonNav->chargeSaisonCourante();

                // Chargement du dernier Jeu
                $jeunav = new Jeu($db);
                $jeunav->saison_id = $saisonNav->saison_id;
                $jeunav->chargeDernierJeu();
         
                // Messages de la barre de notification
                $message = new Message($db);
                $message->joueur_id = $_SESSION['id_joueur'];
                $nbAppelDeFondsEnCours=$message->appelDeFondsEnCours();
                $nbPronosticEnCours=$message->pronosticEnCours();
                $nbMessageTotal=$nbAppelDeFondsEnCours+$nbPronosticEnCours; 
                if ($nbMessageTotal>0) {
                ?>
                <li class="dropdown ">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-envelope fa-fw"></i>  
                        <i class="fa fa-caret-down"></i>
                        <span class="badge"><?php echo $nbMessageTotal; ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <?php
                        if ($nbPronosticEnCours>0) {
                        ?>
                        <li>
                            <a href="saisie.php">
                                <div>
                                    <strong>Nouveau jeu <?php echo $jeunav->jeu_titre; ?> en ligne</strong>
                                </div>
                                <div>Merci de saisir vos pronostics</div>
                            </a>
                        </li>
                        <?php
                        }         
                        if ($nbPronosticEnCours>0 and $nbAppelDeFondsEnCours>0) {                
                            echo '<li class="divider"></li>';
                        }
                        if ($nbAppelDeFondsEnCours>0) {
                        ?>
                        <li>
                            <a href="#">
                                <div>
                                    <strong>Appel de fond en cours</strong>
                                </div>
                                <div>10 &euro; au trésorier</div>
                            </a>
                        </li>
                        <?php
                        }
                        ?>                            
                    </ul>
                    <!-- /.dropdown-messages -->
                </li>
                <!-- /.dropdown -->
                <?php 
                }
                ?>



                <?php 
                if (isset($_SESSION['id_joueur'])  && $_SESSION['admin']=='O' ) 
                {
                ?>
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="false">
                            <i class="fa fa-cogs fa-fw"></i>  <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-messages">
                            <li>
                                <a href="#" id="flashJoueurs">
                                    <div>
                                        <i class="fa fa-flash fa-fw"></i>&nbsp;Flash des joueurs
                                    </div>
                                </a>
                            </li>

                            <li class="divider"></li>
                            <li>
                                <?php
                                if ($jeunav->jeu_bloque=="0") {
                                    echo '<a href="#" id="blocageJeu"><div><i class="fa fa-lock fa-fw"></i>&nbsp;Blocage du jeu</div></a>';
                                    echo '<a href="#" id="deblocageJeu" style="display:none"><div><i class="fa fa-unlock fa-fw"></i>&nbsp;Déblocage du jeu</div></a>';
                                } else {
                                    echo '<a href="#" id="blocageJeu" style="display:none"><div><i class="fa fa-lock fa-fw"></i>&nbsp;Blocage du jeu</div></a>';
                                    echo '<a href="#" id="deblocageJeu"><div><i class="fa fa-unlock fa-fw"></i>&nbsp;Déblocage du jeu</div></a>';
                                }
                                ?>
                            </li>

                            <?php
                                // Si le jeu est bloqué, on propose l'impression
                                if ($jeunav->jeu_bloque=="1") {
                            ?>     
                            <li class="divider impressionJeuDivider"></li>
                            <li>
                                <a href="#" class="impressionJeu" data-num="1">
                                    <div>
                                        <i class="fa fa-print fa-fw"></i>&nbsp;Impression
                                        <span class="pull-right text-muted small">Page 1</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="impressionJeu" data-num="2">
                                    <div>
                                        <i class="fa fa-print fa-fw"></i>&nbsp;Impression
                                        <span class="pull-right text-muted small">Page 2</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="impressionJeu" data-num="3">
                                    <div>
                                        <i class="fa fa-print fa-fw"></i>&nbsp;Impression
                                        <span class="pull-right text-muted small">Page 3</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="impressionJeu" data-num="4">
                                    <div>
                                        <i class="fa fa-print fa-fw"></i>&nbsp;Impression
                                        <span class="pull-right text-muted small">Page 4</span>
                                    </div>
                                </a>
                            </li>
                            <?php
                                // Si le jeu est bloqué, on propose l'impression
                                }
                            ?>     

                            <li class="divider"></li>
                            <li>
                                <a href="#" data-target="#myMailRetardataires" data-toggle="modal">
                                    <div>
                                        <i class="fa fa-envelope fa-fw"></i>&nbsp;Mail aux retardataires
                                    </div>
                                </a>
                            </li>                            
                        </ul>
                        <!-- /.dropdown-alerts -->
                    </li>
                <?php 
                }
                ?>

                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#" data-toggle="modal" data-target="#myProfil"><i class="fa fa-user fa-fw"></i> Mon Profil</a>

                        </li>
                        <li class="divider"></li>
                        <li><a href="scripts/deconnexion.php"><i class="fa fa-power-off fa-fw"></i> Déconnexion</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->
            <?php 
            }
            else
            {
            ?>
            <ul class="nav navbar-top-links navbar-right hidden-xs">
                <li>
                    <a href="login.php"><i class="fa fa-sign-in fa-fw"></i> Connexion</a>
                </li>
            </ul>
            <?php
            }
            ?>


            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                   
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Tableau de bord</a>
                        </li>
                        <?php 
                        if (isset($_SESSION['id_joueur'])) 
                        {
                        ?>
                        <li>
                            <a href="saisie.php" id="libelleSaisie"><i class="fa fa-edit fa-fw"></i>
                                <?php
                                if ($jeunav->jeu_bloque == 0) {
                                    echo " Prochain Jeu";
                                } else {
                                    echo " Saisir résultat";
                                }
                                ?>
                            </a>
                        </li>
                        <?php
                        }
                        ?>
                        <li>
                            <a href="resultats.php"><i class="fa fa-futbol-o fa-fw"></i> Résultats</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Classement<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="classement_gains.php">Gains</a>
                                </li>
                                <li>
                                    <a href="classement_general.php">Général</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <?php 
                        if (isset($_SESSION['id_joueur'])) 
                        {
                            /*
                        <li>
                            <a href="chat.php"><i class="fa fa-comments"></i> Chat</a>
                        </li>
                        */
                        ?>
                        <li>
                            <a href="statistiques.php"><i class="fa fa-table fa-fw"></i> Statistiques</a>
                        </li>
                        <?php
                        }
                        ?>
                        <li>
                            <a href="caisse.php"><i class="fa fa-money fa-fw"></i> Caisse</a>
                            <!-- /.nav-second-level -->
                        </li>
                        <?php 
                        if (isset($_SESSION['id_joueur'])) 
                        {
                        ?>
                        <?php 
                        /*
                        ?>
                        <li>
                            <a href="#"><i class="fa fa-weixin fa-fw"></i> Chat</a>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-database fa-fw"></i> Requéteur</a>
                        </li>
                        <?php 
                        */
                        ?>
                        <?php
                        }
                        ?>
                        <?php 
                        if ( isset($_SESSION['id_joueur'])  && $_SESSION['admin']=='O' )
                        {
                        ?>
                        <li>
                            <a href="#"><i class="fa fa-wrench fa-fw"></i> Administration<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="admin_saison.php">Saisons</a>
                                </li>
                                <li>
                                    <a href="admin_jeu.php">Jeux</a>
                                </li>
                                <li>
                                    <a href="admin_gain.php">Gains</a>
                                </li>
                                <li>
                                    <a href="admin_rapport.php">Rapports</a>
                                </li>
                                <li>
                                    <a href="admin_caisse.php">Caisse</a>
                                </li>
                                <li>
                                    <a href="admin_appeldefonds.php">Appels de fond</a>
                                </li>
                                <li>
                                    <a href="admin_joueur.php">Joueurs</a>
                                </li>
                                <li>
                                    <a href="admin_divers.php">Divers</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        
                        <?php
                        }
                        echo '<li class="visible-xs">';
                        if (isset($_SESSION['id_joueur'])) 
                        {
                            echo '<a href="scripts/deconnexion.php"><i class="fa fa-power-off fa-fw"></i> Déconnexion</a>';
                        }
                        else
                        {
                            echo '<a href="login.php"><i class="fa fa-sign-in fa-fw"></i> Connexion</a>';
                        }
                        ?>
                        </li>


                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>



<!-- Modal - Mon profil -->
<div class="modal fade" id="myProfil" tabindex="-1" role="dialog" aria-labelledby="myProfilLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $_SESSION['nom_joueur']?></h4>
      </div>
      <div class="modal-body">
       Prochainement ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal - Mails retardataires -->
<div class="modal fade" id="myMailRetardataires" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-remote="composants/mailsretardataires.php" >
    <div class="modal-dialog">
        <div class="modal-content">
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div>