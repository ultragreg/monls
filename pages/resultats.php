<?php include("composants/head.php"); ?>

<body>

    <div id="wrapper">

        <?php 
        // include database and object files
        include_once 'config/database.php';
        include_once 'config/util.php';
        include_once 'objects/saison.php';
        include_once 'objects/resultat.php';
        include_once 'objects/pronostic.php';
        include_once 'objects/joueur.php';
        include_once 'objects/jeu.php';
        include_once 'objects/rapport.php';
         
        // instantiate database and product object
        $database = new Database();
        $db = $database->getConnection();
  
        // Recherche de la saison courante
        $saison = new Saison($db);
        $saison = $saison->chargeSaisonCourante();

        // Dernier Jeu
        $jeu = new Jeu($db);
        if (isset($_POST['jeuform']))
         {
            $jeu->jeu_id = $_POST['jeuform'];
            $jeu->chargeJeu();
        } else {
            $jeu->saison_id = $saison->saison_id;
            $jeu->chargeDernierJeu();
        }

        $stmtJeux = $jeu->litJeux();
        $listeJeux = $stmtJeux->fetchAll(PDO::FETCH_ASSOC);
        

        // Recherche la liste des joueurs
        $joueurs = new Joueur($db);
        $stmtJoueurs = $joueurs->litJoueurs();
        $listeJoueurs = $stmtJoueurs->fetchAll(PDO::FETCH_ASSOC);

        // Résultat de ce jeu
        $resultat = new Resultat($db);
        $resultat->jeu_id = $jeu->jeu_id; 
        $resultat->chargeResultat();

        // Pronostics des joueurs
        $pronostic = new Pronostic($db);
        $pronostic->jeu_id = $jeu->jeu_id; 
        $stmtPronostics = $pronostic->litPronostics();
        $listePronostics = $stmtPronostics->fetchAll(PDO::FETCH_ASSOC);

        // Rapport
        $rapport = new Rapport($db);
        $rapport->jeu_id = $jeu->jeu_id;
        $rapport->type = 7;
        $stmtRapport7 = $rapport->litRapports();
        $jeuxRapport7 = $stmtRapport7->fetchAll(PDO::FETCH_ASSOC);
        $rapport->type = 15;
        $stmtRapport15 = $rapport->litRapports();
        $jeuxRapport15 = $stmtRapport15->fetchAll(PDO::FETCH_ASSOC);



        include_once 'composants/nav.php';

        ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Résultats des joueurs</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>


            <div class="row formulaire">
                <form  class="form-horizontal" action="resultats.php" method="post">
                    <div class="col-xs-10">
                        <select class="form-control" name="jeuform">
                        <?php
                            for($i=0;$i<sizeof($listeJeux);$i++) {
                                $jeu2=$listeJeux[$i];
                                echo "<option value='".$jeu2["jeu_id"]."'";
                                if ($jeu->jeu_id==$jeu2["jeu_id"]) {
                                    echo " selected>";
                                } else {
                                    echo ">";
                                }
                                echo $jeu2["titre"]."</option>\n";
                            }
                        ?>
                        </select>                                   
                    </div>
                    <div class="col-xs-2">
                        <span class="pull-right">
                            <button type="submit" class="btn btn-primary">Ok</button>    
                        </span>                         
                    </div>
                </form>
            </div>

            <!-- /.row  Résultats joueur par joueur => Pas d'affichage sur grand écran -->
            <div class="row hidden-lg">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-futbol-o fa-fw"></i>
                            <?php echo "Résultats du jeu {$jeu->jeu_titre} par joueur"   ?>
                        </div>
                        <!-- .panel-heading -->
                        <div class="panel-body">
                            <div id="accordion" class="panel-group">

                                <?php
                                        $tab = array();
                                        //  Premier passage : calcul des moyennes
                                        for($j=0;$j<sizeof($listeJoueurs);$j++) {
                                            // Ce joueur n'a pas de bon résultats
											$flash="";
											$nbBonResultats=0;
                                            $nbjeu=0;
                                            $nbResultats=0;
                                            $nbResultats7=0;
                                            $joueur_id=$listeJoueurs[$j]["joueur_id"];
                                            // Pronostic de ce joueur
                                            if ($listePronostics) {
                                               $prono=getPronoJoueur($listePronostics, $joueur_id);
                                            }
                                            // Pour les 15 matchs potentiels, on compare le prono et le résultat
                                            for($i=1; $i <= 15 ; $i++) 
                                            {
                                                // Pour ce joueur, lecture du résultat du match $i 
                                                $resultatMatch= getResultat($resultat, $i);
                                                // Et lecture du pronostics de ce joueur et de ce match
                                                if (isset($prono)) {
                                                    $valeurProno=getListePronostic($prono, $i);
                                                    $flash=$prono["flash"];
                                                    if (strlen($valeurProno)>0) {
                                                        $nbjeu++;
                                                        if (strpos($resultatMatch,$valeurProno)!==false) {
                                                            $nbBonResultats++;
                                                        }
                                                    }
                                                }
                                                if ($resultatMatch)            
                                                {
                                                    $nbResultats=$nbResultats+1;
                                                    if ($i<=7) {
                                                        $nbResultats7=$nbResultats7+1;
                                                    }
                                                }
                                            }
                                            // Tableau de données
                                            $moyenne=0;
                                            if ($nbjeu>0) {
                                                if ($nbjeu <= 7 && $nbResultats7!=0) {
                                                    $moyenne=$nbBonResultats/$nbResultats7*100;
                                                } else {
                                                    if ($nbResultats!=0) {
                                                        $moyenne=$nbBonResultats/$nbResultats*100;
                                                    }
                                                }
                                                $tab[$j]=array('nom'=> $listeJoueurs[$j]["nom"], 
                                                			'joueur_id'=> $listeJoueurs[$j]["joueur_id"],
                                                            'nbResultat' => $nbBonResultats, 
                                                            'nbjeu' =>$nbjeu,
                                                            'flash'=> $flash,
                                                            'moyenne' =>  number_format($moyenne,2));
                                            }
                                        }
                                        if ($tab) {
                                            // Tri !
                                            usort($tab, "compareMoyenne");
                                            //  Second passage : affichage des résultats
                                            for($j=0;$j<sizeof($tab);$j++) {
                                            	if (isset($tab[$j]) && $tab[$j]['nbjeu']!=0) {
                                            		
                                             	$joueur_id=$tab[$j]["joueur_id"];
                                                $classe="panel-default";
                                                if ($tab[$j]['moyenne']>75) {
                                                    $classe="panel-success";
                                                }
                                                if ($tab[$j]['nbResultat']==0) {
                                                    $classe="panel-danger";
                                                }
                                                if ($nbResultats==0) {
                                                	$classe="panel-default";
                                                }
                                                ?>
                                                <div class="panel  <?php echo $classe; ?>">
                                                    <div class="panel-heading info">
                                                        <h4 class="panel-title">
                                                            <a href="#collapsejo<?php echo $j; ?>" data-parent="#accordion" 
                                                            data-toggle="collapse" aria-expanded="false" class="collapsed">
                                                            <?php
																$k=$j+1;
																$flashIcon="";
                                                                if ($tab[$j]['flash']=="1") {
                                                                    $flashIcon='&nbsp;&nbsp;<i class="fa fa-flash"></i>';
                                                                }
																echo "{$k} : ".$tab[$j]['nom']." (".$tab[$j]['nbResultat']." / ".$tab[$j]['nbjeu'];
																echo " soit " . $tab[$j]['moyenne']." %)".$flashIcon;
                                                            ?>
                                                            </a>
                                                        </h4>
                                                    </div>

                                                    <div class="panel-collapse collapse" id="collapsejo<?php echo $j; ?>" aria-expanded="false" style="height: 0px;">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                            <?php
                                                                $fermeBalise=false;
                                                                for($i=1; $i <= 15 ; $i++) 
                                                                {
                                                                     if (getEquipe($jeu, $i, "D")!="") {
                                                                        //$prono=$listePronostics[$j];
                                                    					$prono=getPronoJoueur($listePronostics, $joueur_id);
                                                                        $valeurProno=getListePronostic($prono, $i);
                                                                        if ($valeurProno!="") {
                                                                            $resultatMatch= getResultat($resultat, $i);
                                                                            if (strpos($resultatMatch,$valeurProno)!==false) {
                                                                                $classe="resultatSucces";
                                                                            }
                                                                            else {
                                                                                $classe="resultatEchec";
                                                                            }
																			//echo "<div class='col-sm-4 text-center'><div class='{$classe}'>";
                                                                            //if ($resultatMatch) {
                                                                            //    echo  $valeurProno . " : " . getEquipe($jeu, $i, "D"). " - ".getEquipe($jeu, $i, "E")."(".$resultatMatch.")";
                                                                            //} else {
                                                                            //    echo  $valeurProno . " : " . getEquipe($jeu, $i, "D"). " - ".getEquipe($jeu, $i, "E");
                                                                            //}
                                                                            //echo "</div></div>"; 
                                                                            if ($i==1) {
                                                                            	echo "<div class='col-sm-6 text-center'>";
                                                                                $fermeBalise=true;
                                                                            }
                                                                            if ($i==8) {
                                                                            	echo "</div><div class='col-sm-6 text-center'>";
                                                                            }
                                                                       	    
                                                                            if ($resultatMatch) {
                                                                                echo "<div class='col-xs-10 text-center' style='padding-left:1px; padding-right:1px'><div class='{$classe}'>" . getEquipe($jeu, $i, "D") . " - " . getEquipe($jeu, $i, "E") . "</div></div><div class='col-xs-2 text-center' style='padding-left:1px; padding-right:1px'><div class='{$classe}'>" . $valeurProno . " (".$resultatMatch. ")</div></div>";
                                                                            	echo "<div class='clearfix visible-xs-block'></div>";
                                                                            } else {
                                                                                echo "<div class='col-xs-10 text-center' style='padding-left:1px; padding-right:1px'><div class='resultatAttente'>" . getEquipe($jeu, $i, "D") . " - " . getEquipe($jeu, $i, "E") . "</div></div><div class='col-xs-2 text-center' style='padding-left:1px; padding-right:1px'><div class='resultatAttente'>" . $valeurProno . "</div></div>";
                                                                            	echo "<div class='clearfix visible-xs-block'></div>";
                                                                            }                                                                                                                                         
                                                                        }                                                                  
                                                                     }
                                                                }
                                                                if ($fermeBalise) {
                                                                    echo "</div>";
                                                                }
                                                            ?>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            <?php
                                            	}
                                            
                                            }
                                        }
                                        ?>                                    

                            </div>
                        </div>
                        <!-- .panel-body -->
                    </div>
                     <!-- /.panel -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.row Résultats joueur par joueur -->






            <!-- /.row Résultats jeu par jeu => Pas d'affichage sur grand écran -->
            <div class="row hidden-lg">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-futbol-o fa-fw"></i>
                            <?php echo "Résultats du jeu {$jeu->jeu_titre} par match" ?>
                        </div>
                        <!-- .panel-heading -->
                        <div class="panel-body">
                            <div id="accordion" class="panel-group">

                                <?php
                                for($i=1; $i <= 15 ; $i++) 
                                {
                                	if ($i==8) {
                                		echo "<hr style='width:100%;color:firebrick;margin:5px 0;background-color:firebrick;height:3px;' />";
                                	}
                                    if (getEquipe($jeu, $i, "D")!="") {
                                    ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading info">
                                            <h4 class="panel-title">
                                                <a href="#collapseje<?php echo $i; ?>" data-parent="#accordion" 
                                                data-toggle="collapse" aria-expanded="false" class="collapsed">
                                                <?php 
                                                $resultatMatch= getResultat($resultat, $i);
                                                echo "{$i}. ".getEquipe($jeu, $i, "D"). " - ";
                                                echo getEquipe($jeu, $i, "E")." : ".$resultatMatch;
                                                $nbJoueurs=0;
                                                $nbBonREsultats=0;
                                                for($j=0;$j<sizeof($listeJoueurs);$j++)  {
                                                    	$joueur_id=$listeJoueurs[$j]["joueur_id"];
                                                    	// Pronostic de ce joueur
                                                    	$prono=null;
                                                    	if ($listePronostics) {
                                                    		$prono=getPronoJoueur($listePronostics, $joueur_id);
                                                    	}
                                                        if ($prono && isset($prono)) {
                                                        	$valeurProno=getListePronostic($prono, $i);
                                                        	if (strlen($valeurProno)>0) {
                                                            	$nbJoueurs++;
                                                            	if (strpos($resultatMatch,$valeurProno)!==false) {
                                                            	    $nbBonREsultats++;
                                                            	}
                                                        	}
                                                        }
                                                    }
													if (isset($nbJoueurs) && $nbJoueurs>0) {
                                                        echo "<span class='label label-primary pull-right hidden-xs'> ".$nbJoueurs. " joueurs" . ",  " .number_format(($nbBonREsultats/$nbJoueurs)*100) . "% bons</span>";
                                                    }
                                                ?>
                                                </a>
                                            </h4>
                                        </div>
                                        <div class="panel-collapse collapse" id="collapseje<?php echo $i; ?>" aria-expanded="false" style="height: 0px;">
                                            <div class="panel-body">
                                                <div class="row">
                                                <?php
                                                    for($j=0;$j<sizeof($listeJoueurs);$j++)
                                                    {
                                                    	$joueur_id=$listeJoueurs[$j]["joueur_id"];
                                                    	// Pronostic de ce joueur
                                                    	$prono=null;
                                                    	if ($listePronostics) {
                                                    		$prono=getPronoJoueur($listePronostics, $joueur_id);
                                                    	}
                                                    	if ($prono && isset($prono)) {
                                                    		$valeurProno=getListePronostic($prono, $i);
	                                                        if (strlen($valeurProno)>0) {
	                                                            $joueur_nom=$listeJoueurs[$j]["nom"];
	                                                            if  ($resultatMatch) {
	                                                            	if (strpos($resultatMatch,$valeurProno)!==false) {
		                                                                $classe="resultatSucces";
		                                                            }
		                                                            else {
		                                                                $classe="resultatEchec";
		                                                            }
	                                                            }
	                                                            else {
	                                                            	$classe="resultatAttente";
	                                                            }
	                                                            
	                                                            echo "<div class='col-sm-4 text-center'><div class='{$classe}'>";
	                                                                echo "{$joueur_nom} : {$valeurProno}";
	                                                            echo "</div></div>";
	                                                        }
                                                    	}
                                                    }
                                                ?>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                     <?php
                                    }
                                }    
                                ?>   


                                
     
                            </div>
                        </div>
                        <!-- .panel-body -->
                    </div>
                     <!-- /.panel -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.row Résultats jeu par jeu -->






            <!-- /.row Résultats tableau complet => Affichage uniquement sur grand écran -->
            <div class="row visible-lg-block">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading clearfix">
                            <i class="fa fa-futbol-o fa-fw"></i>
                            <?php echo "Résultats Complet du jeu {$jeu->jeu_titre}" ?>
                            
                        </div>
                        <!-- .panel-heading -->
                        <div class="panel-body">
                            <table class="table table-striped table-bordered table-hover" id="tableResultats">
                                <thead>
                                    <tr>
                                        <td>Match</td>
                                        <?php
                                        for($j=0;$j<sizeof($listeJoueurs);$j++)  {
                                            $joueur_id=$listeJoueurs[$j]["joueur_id"];
                                            $initiale=$listeJoueurs[$j]["initiale"];
                                            $nom=$listeJoueurs[$j]["nom"];
                                            $classJoueur = "text-center";
                                            // Pronostic de ce joueur
                                            if ($listePronostics) {
                                               $prono=getPronoJoueur($listePronostics, $joueur_id);
                                               $flash=$prono["flash"];
                                               if ($flash=="1") {
                                                    $classJoueur = $classJoueur." flashJoueur";
                                               }
                                            }
                                            echo "<td class='{$classJoueur}' title='{$nom}'>{$initiale}</td>";
                                        }
                                        ?>  
                                        <td class='text-center'>Res.</td>                                      
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tableauNbMatchJoueur = array();
                                    $nombreTotalDeResultat=0;
                                    $nombreTotalDeResultat7=0;
                                    $tableauNbBonsResultatsJoueur = array();
                                    for($j=0;$j<sizeof($listeJoueurs);$j++)  {
                                        $tableauNbBonsResultatsJoueur[$j]=0;
                                        $tableauNbMatchJoueur[$j]=0;
                                    }
                                    for($i=1; $i <= 15 ; $i++) {
                                        if (getEquipe($jeu, $i, "D")!="") {

                                            echo "<tr>";
                                            $resultatMatch= getResultat($resultat, $i);
                                            if ($resultatMatch) {
                                                $nombreTotalDeResultat++;
                                                $nbResultats=$nbResultats+1;
                                                if ($i<=7) {
                                                    $nombreTotalDeResultat7++;
                                                }                                                
                                            }
                                            echo "<td class='titreMatch'>{$i}. ".getEquipe($jeu, $i, "D"). " - ";
                                            echo getEquipe($jeu, $i, "E")."</td>";
                                            for($j=0;$j<sizeof($listeJoueurs);$j++)  {
                                                $joueur_id=$listeJoueurs[$j]["joueur_id"];
                                                // Pronostic de ce joueur
                                                $prono=null;
                                                $classJeu="";
                                                if ($listePronostics) {
                                                    $prono=getPronoJoueur($listePronostics, $joueur_id);
                                                }

                                                if ($prono && isset($prono)) {
                                                    $valeurProno=getListePronostic($prono, $i);
                                                    if (strlen($valeurProno)>0) {
                                                        $tableauNbMatchJoueur[$j]++;
                                                        if (strpos($resultatMatch,$valeurProno)!==false) {
                                                            $classJeu="gagnant";
                                                            $tableauNbBonsResultatsJoueur[$j]++;
                                                        }
                                                    }
                                                } else {
                                                    $valeurProno=" ";
                                                }
                                                echo "<td class='{$classJeu} text-center'>{$valeurProno}</td>";
                                            }
                                            echo "<td class='text-center'>{$resultatMatch}</td>";
                                            echo "</tr>";
                                        }
                                    }
                                    // Le nombre de bons résultats 
                                    echo "<tr><td>Total</td>";
                                    for($j=0;$j<sizeof($listeJoueurs);$j++)  {
                                        if (isset($tableauNbBonsResultatsJoueur[$j]) && $tableauNbBonsResultatsJoueur[$j]>0 ) {
                                            echo "<td class='text-center'>".$tableauNbBonsResultatsJoueur[$j]."</td>";
                                        } else {
                                            echo "<td class='text-center'>&nbsp;</td>";
                                        }
                                    }
                                    echo "<td></td></tr>";
                                    // Les moyennes des totaux 
                                    if ($nombreTotalDeResultat>0) {
                                        echo "<tr><td>Moyenne (en %)</td>";
                                        $moyenne=0;
                                        // Premier passage pour trouver la meilleure et la plus mauvaise moyenne
										$meilleurMoyenne=0;
										$pireMoyenne=100;
                                        for($j=0;$j<sizeof($listeJoueurs);$j++)  {
                                        	if ($tableauNbMatchJoueur[$j] <= 7 && $nombreTotalDeResultat7!=0) {
                                        		$moyenne=$tableauNbBonsResultatsJoueur[$j]/$nombreTotalDeResultat7*100;
                                        	} else {
                                        		if ($nombreTotalDeResultat!=0) {
                                        			$moyenne=$tableauNbBonsResultatsJoueur[$j]/$nombreTotalDeResultat*100;
                                        		}
                                        	}
                                            	if ($moyenne>$meilleurMoyenne) {
                                        		$meilleurMoyenne=$moyenne;
                                        	}
                                        	if ($moyenne<$pireMoyenne) {
                                        		$pireMoyenne=$moyenne;
                                        	}
                                        }
                                        for($j=0;$j<sizeof($listeJoueurs);$j++)  {
                                            if ($tableauNbMatchJoueur[$j] <= 7 && $nombreTotalDeResultat7!=0) {
                                                $moyenne=$tableauNbBonsResultatsJoueur[$j]/$nombreTotalDeResultat7*100;
                                            } else {
                                                if ($nombreTotalDeResultat!=0) {
                                                    $moyenne=$tableauNbBonsResultatsJoueur[$j]/$nombreTotalDeResultat*100;
                                                }
                                            } 
                                            $classMoyenne="";
	                                        if ($moyenne==$meilleurMoyenne) {
	                                            	$classMoyenne="meilleureMoyenne";
	                                        }
	                                        if ($moyenne==$pireMoyenne) {
	                                            	$classMoyenne="pireMoyenne";
	                                        }                                       
                                            if ($moyenne>75) {
	                                            	$classMoyenne=$classMoyenne." gainMoyenne";
	                                        }   
                                            echo "<td class='text-center ".$classMoyenne."'>".round($moyenne,0)."</td>";
                                        }
                                        echo "<td></td></tr>";
                                    }
                                    // Indices
                                    echo "<tr><td>";
                                    echo "<a class='btn btn-primary' role='button' data-toggle='collapse' href='#collapseExplicationIndice' aria-expanded='false' aria-controls='collapseExplicationIndice'>";
                                    echo "Indice de gain</a></td>";
                                    for($j=0;$j<sizeof($listeJoueurs);$j++)  {
                                        $joueur_id=$listeJoueurs[$j]["joueur_id"];   // Pronostic de ce joueur
                                        $prono=null;
                                        if ($listePronostics) {
                                            $prono=getPronoJoueur($listePronostics, $joueur_id);
                                        }
                                        $valeurIndice="";
                                        if ($prono && isset($prono)) {
                                            $valeurIndice=getIndice($prono);
                                        }
                                        echo "<td class='text-center'>{$valeurIndice}</td>";
                                    }
                                    echo "<td></td></tr>";
                                    
                                    ?>

                                </tbody>
                            </table>
                                    <div class="collapse" id="collapseExplicationIndice">
                                      <div class="row">

                                        <div class="col-lg-6">
                                            <h4>Indice de gain - Jeu à 7</h4>
                                            <table class="table table-condensed">
                                            <thead><tr><td>Indice</td>
                                            <td>Commentaires</td><td></td></tr>
                                            </thead><tbody>
                                            <tr><td>&lt;4</td><td>Très peu fréquent, gain minime &lt;20€</td></tr>
                                            <tr><td>4 - 5</td><td>100€ max, peu de surprise</td></tr>
                                            <tr><td>5 - 6</td><td>Frequent, 500€ max, 1-2 petites surprises</td></tr>
                                            <tr><td>6 - 7</td><td>Gains intéressants (500-3000€), 2-3 suprises</td></tr>
                                            <tr><td>7 - 7.5</td><td>Très beaux gains ! Au moins 3 suprises</td></tr>
                                            <tr><td>7.5 - 8</td><td>Rare, gains elevés ! Peu de favori gagnant</td></tr>
                                            <tr><td>&gt;8</td><td>Très rare, gain explosif, 1 fois par saison</td></tr>
                                            </tbody></table>
                                        </div>                            
                                        <div class="col-lg-6">
                                            <h4>Indice de gain - Jeu à 15</h4>
                                            <table class="table table-condensed">
                                            <thead><tr><td>Indice</td>
                                            <td>Commentaires</td><td></td></tr>
                                            </thead><tbody>
                                            <tr><td>&lt;3.5</td><td>Très peu fréquent, gain minime &lt;100€</td></tr>
                                            <tr><td>3.5 - 5</td><td>Peu fréquent, gain élevé uniquement 14/14</td></tr>
                                            <tr><td>5 - 5.5</td><td>Pactole partagé, autres gains &lt;1000€</td></tr>
                                            <tr><td>5.5 - 6</td><td>Fréquent, Pactole &gt;100 000€, gains intéressants</td></tr>
                                            <tr><td>6 - 7</td><td>Gros Pactole ! Très beaux gains !</td></tr>
                                            <tr><td>&gt;7</td><td>Rare, mais Pactole assuré !</td></tr>
                                            </tbody></table>
                                        </div>                            
                            
                                      </div>
                                    </div>
                        </div>
                        <!-- .panel-body -->
                    </div>
                     <!-- /.panel -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.row Résultats jeu par jeu -->

            <!-- /.row -->
             <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                       <div class="panel-heading">
                            <i class="fa fa-cc-visa"></i>
                            Rapports à 7
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                           <p class="pull-left">
                            <?php
                            if ($jeuxRapport7) {
                                $rapport7=$jeuxRapport7[0];
                                echo "Saisi le " . $rapport7['commentaire'];
                            }
                            ?>
                            </p>
                            <table class="table table-striped table-bordered table-hover" id="dataTables-caisse">
                                <thead class="bg-primary ">
                                    <tr>
                                        <th class="text-center">Rang</th>
                                        <th class="text-center">Nombre de grilles gagnantes</th>
                                        <th class="text-center">Gain</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                <?php
                                for($j=0;$j<sizeof($jeuxRapport7);$j++)  {                              
                                   $rapport7=$jeuxRapport7[$j];
                                   echo "<tr>";
                                        echo "<td>{$rapport7['rang']}</td>";
                                        echo "<td>{$rapport7['nombre']}</td>";
                                        echo "<td>{$rapport7['rapport']}</td>";
                                    echo "</tr>";
                                }    
                                ?> 
                                </tbody>
                            </table>
              
                        </div>    
                    </div>   
                    <!-- /.panel-body -->   
                </div>      

                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-cc-visa"></i>
                            Rapports à 15
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">

                            <p class="pull-left">
                            <?php
                            if ($jeuxRapport15) {
                                $rapport15=$jeuxRapport15[0];
                                echo "Saisi le " . $rapport15['commentaire'];
                            }
                            ?>
                            </p>

                             <table class="table table-striped table-bordered table-hover" id="dataTables-caisse">
                                <thead class="bg-primary ">
                                    <tr>
                                        <th class="text-center">Rang</th>
                                        <th class="text-center">Nombre de grilles gagnantes</th>
                                        <th class="text-center">Gain</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                <?php
                                for($j=0;$j<sizeof($jeuxRapport15);$j++)  {                              
                                   $rapport15=$jeuxRapport15[$j];
                                   echo "<tr>";
                                        echo "<td>{$rapport15['rang']}</td>";
                                        echo "<td>{$rapport15['nombre']}</td>";
                                        echo "<td>{$rapport15['rapport']}</td>";
                                    echo "</tr>";
                                }    
                                ?> 
                                </tbody>
                            </table>                        
                        </div>
                        <!-- /.panel-body -->
                    </div>                 
                </div>      
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

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
    
    <!-- Toast MonLs -->
    <script src="../dist/js/toastr.min.js"></script>

    <!-- Javascript MonLs -->
    <script src="../dist/js/monls.js"></script>

</body>

</html>
