<?php 
//session_start();
require_once "artichow/LinePlot.class.php";

// Le niveau de transparence est null
define("NIVEAU_TRANSPARENCE",100);

function CreeGraphClassementGeneral($fichier)
{ 
    // Initialement, ces variables étaient passés dans la session 
    //$G_listeJeux=$_SESSION['G_listeJeux'];
    //$G_listeJoueurs=$_SESSION['G_listeJoueurs'];
    //$G_listeResultats=$_SESSION['G_listeResultats'];
    //$G_MesCouleurs=$_SESSION['G_mesCouleurs'];
    
    global $G_ListeJeux;
    global $G_MesCouleurs;
    global $G_ListeResultatsMoyens;
    global $G_ListeJoueurs;


    
    // Ici, le graphique mesurera 620 x 700 pixels.
    $graph = new Graph(620, 700);
    
    // L'anti-aliasing permet d'afficher des courbes plus naturelles,
    // mais cette option consomme beaucoup de ressources sur le serveur.
    $graph->setAntiAliasing(TRUE);
    // Titre du graphe !
    $graph->title->set("Classement général");   
    
    // L'objet group permet de gérer plusieurs courbes sur un même grpahiques
    $group = new PlotGroup;
    
    // Le style des lignes des courbes est dashed
    $group->grid->setType(LINE_DASHED);
    // La marge gauche est fixée à 40px du bord, droite à 20, haut à 40px et basse à 120px
    $group->setPadding(40, 20, 40, 120);
    // Le titre sur les absisses est : % Réussite
    $group->axis->left->title->set("% Reussite");
    
    // Les libellés sur les absisses sont inclinés de 45%
    $group->axis->bottom->label->setAngle(45);
    // Affiche 10 marques entre 2 marques majeures
    $group->axis->left->setNumberByTick('minor', 'major', 10);
    // Titre des ordonnées 
    //$group->axis->bottom->title->set("Journées");
    
    // La légende est affiché en bas du graphe
    $group->legend->setModel(LEGEND_MODEL_BOTTOM);
    // Position de la légénde par rapport au graphe
    $group->legend->setPosition(NULL, 0.9);
    // Nb de colonnes
    $group->legend->setRows(2);
    //$group->legend->shadow->hide();
    
    // On créé autant de courbes qu'il y a de joueurs !
    for($j=0;$j<sizeof($G_ListeJoueurs);$j++)
    {
        // Recherche des résultats du joueur $j
        $G_ResultatJoueur=array();
        for($k=0;$k<sizeof($G_ListeJeux);$k++)
        {
            if (isset($G_ListeResultatsMoyens[$k][$j]))
                $G_ResultatJoueur[$k]=$G_ListeResultatsMoyens[$k][$j];
            else
                $G_ResultatJoueur[$k]=null;
        }
        // Création d'une courbe pour ce joueur
        $plot = new LinePlot($G_ResultatJoueur);
        $plot->setColor(new Color($G_MesCouleurs[$j][0],$G_MesCouleurs[$j][1],$G_MesCouleurs[$j][2]));
        $plot->setFillColor(new Color($G_MesCouleurs[$j][0],$G_MesCouleurs[$j][1],$G_MesCouleurs[$j][2],NIVEAU_TRANSPARENCE));

        $plot->mark->setType(MARK_CIRCLE);
        if ( $j % 3 == 0)
            $plot->mark->setType(MARK_TRIANGLE);
        if ($j % 4 == 0)
            $plot->mark->setType(MARK_CROSS);
        $plot->mark->setFill(new Color($G_MesCouleurs[$j][0],$G_MesCouleurs[$j][1],$G_MesCouleurs[$j][2]));
        $plot->mark->setSize(7);
        
        $plot->yAxis->setLabelNumber(0.5);
       // $plot->xAxis->label->setAngle(45); 
        $plot->setPadding(10, 10,10,10); 
    
        // Ajoute d'une légende pour cette courbe et ce joueur
        $group->legend->add($plot, $G_ListeJoueurs[$j], LEGEND_MARK);
        // Ajoute cette courbe au group
        $group->add($plot);
    }
 
    // Fonction qui retourne les Abscisses    
    function setAbscisseGeneral($value) {
    	global $G_ListeJeux;
      return $G_ListeJeux[$value];
    }
    $group->axis->bottom->label->setCallbackFunction('setAbscisseGeneral');
    
    // Fonction qui retourne les Ordonnés    
    function setOrdonneGeneral($value) {
    	return round($value);
    }
    $group->axis->left->label->setCallbackFunction('setOrdonneGeneral');
    
    // Ajout de ce groupe au graphique
    $graph->add($group);

    $graph->draw($fichier);
}


function CreeGraphClassementPerso($fichier)
{ 
    // Initialement, ces variables étaient passés dans la session 
    //$G_listeJeux=$_SESSION['G_listeJeux'];
    //$G_listeJoueurs=$_SESSION['G_listeJoueurs'];
    //$G_listeResultats=$_SESSION['G_listeResultats'];
    //$G_MesCouleurs=$_SESSION['G_mesCouleurs'];
    
    global $G_ListeJeux;
    global $G_MesCouleurs;
    global $G_ListeResultatsMoyens;
    global $G_ListeJoueurs;

    // Ici, le graphique mesurera 620 x 700 pixels.
    $graph = new Graph(620, 700);
    
    // L'anti-aliasing permet d'afficher des courbes plus naturelles,
    // mais cette option consomme beaucoup de ressources sur le serveur.
    $graph->setAntiAliasing(TRUE);
    // Titre du graphe !
    $graph->title->set("Classement personnalisé");   
    
    // L'objet group permet de gérer plusieurs courbes sur un même grpahiques
    $group = new PlotGroup;
    
    // Le style des lignes des courbes est dashed
    $group->grid->setType(LINE_DASHED);
    // La marge gauche est fixée à 40px du bord, droite à 20, haut à 40px et basse à 120px
    $group->setPadding(40, 20, 40, 120);
    // Le titre sur les absisses est : % Réussite
    $group->axis->left->title->set("% Reussite");

   
    // Les libellés sur les absisses sont inclinés de 45%
    $group->axis->bottom->label->setAngle(45);
    // Affiche 10 marques entre 2 marques majeures
    $group->axis->left->setNumberByTick('minor', 'major', 10);
    // Titre des ordonnées 
    //$group->axis->bottom->title->set("Journées");
    
    // La légende est affiché en bas du graphe
    $group->legend->setModel(LEGEND_MODEL_BOTTOM);
    // Position de la légénde par rapport au graphe
    
    
    // Nb de colonnes
    $group->legend->setRows(2);
    //$group->legend->shadow->hide();

    // On créé autant de courbes qu'il y a de joueurs !
    for($j=0;$j<sizeof($G_ListeJoueurs);$j++)
    {

        // Recherche des résultats du joueur $j
        $G_ResultatJoueur=array();
        for($k=0;$k<sizeof($G_ListeJeux);$k++)
        {
            if (isset($G_ListeResultatsMoyens[$k][$j]))
                $G_ResultatJoueur[$k]=$G_ListeResultatsMoyens[$k][$j];
            else
                $G_ResultatJoueur[$k]=null;
        }
        // Création d'une courbe pour ce joueur
        $plot = new LinePlot($G_ResultatJoueur);
        $plot->setColor(new Color($G_MesCouleurs[$j][0],$G_MesCouleurs[$j][1],$G_MesCouleurs[$j][2]));
        $plot->setFillColor(new Color($G_MesCouleurs[$j][0],$G_MesCouleurs[$j][1],$G_MesCouleurs[$j][2],NIVEAU_TRANSPARENCE));
        $plot->mark->setType(MARK_CIRCLE);
        if ( $j % 3 == 0)
            $plot->mark->setType(MARK_TRIANGLE);
        if ($j % 4 == 0)
            $plot->mark->setType(MARK_CROSS);
        $plot->mark->setFill(new Color($G_MesCouleurs[$j][0],$G_MesCouleurs[$j][1],$G_MesCouleurs[$j][2]));
        $plot->mark->setSize(7);
        
        $plot->yAxis->setLabelNumber(0.5);
       // $plot->xAxis->label->setAngle(45); 
        $plot->setPadding(10, 10,10,10); 
    
        // Ajoute d'une légende pour cette courbe et ce joueur
        $group->legend->add($plot, $G_ListeJoueurs[$j], LEGEND_MARK);
        // Ajoute cette courbe au group
        $group->add($plot);
    }
 
    // Fonction qui retourne les Abscisses    
    function setAbscisseGeneral($value) {
    	global $G_ListeJeux;
      return $G_ListeJeux[$value];
    }
    $group->axis->bottom->label->setCallbackFunction('setAbscisseGeneral');
    
    // Fonction qui retourne les Ordonnés    
    function setOrdonneGeneral($value) {
    	return round($value);
    }
    $group->axis->left->label->setCallbackFunction('setOrdonneGeneral');
    
    // Ajout de ce groupe au graphique
    $graph->add($group);

    $graph->draw($fichier);
}


function CreeGraphClassementZoom($fichier)
{     
    // Initialement, ces variables étaient passés dans la session 
    // $G_listeJeux=$_SESSION['G_listeJeuxZoom'];
    // $G_listeJoueurs=$_SESSION['G_listeJoueursZoom'];
    // $G_listeResultats=$_SESSION['G_listeResultatsZoom'];
    // $G_MesCouleurs=$_SESSION['G_mesCouleurs'];
        
    global $G_ListeJeuxZoom;
    global $G_MesCouleurs;
    global $G_ListeResultatsMoyensZoom;
    global $G_ListeJoueurs; 

    // Le niveau de transparence est définit à 95%
    
    // Ici, le graphique mesurera 620 x 700 pixels.
    $graph = new Graph(620, 700);

    // L'anti-aliasing permet d'afficher des courbes plus naturelles,
    // mais cette option consomme beaucoup de ressources sur le serveur.
    $graph->setAntiAliasing(TRUE);
    // Titre du graphe !
    $graph->title->set("Zoom sur les quatre dernières moyennes");   
    
    // L'objet group permet de gérer plusieurs courbes sur un même grpahiques
    $group = new PlotGroup;
    
    // Le style des lignes des courbes est dashed
    $group->grid->setType(LINE_DASHED);
    // La marge gauche est fixée à 40px du bord, droite à 20, haut à 40px et basse à 120px
    $group->setPadding(40, 20, 40, 120);
    // Le titre sur les absisses est : % Réussite
    $group->axis->left->title->set("% Reussite");
    $group->setYAxisZero(false);
    
    // Les libellés sur les absisses sont inclinés de 45%
    $group->axis->bottom->label->setAngle(45);
    // Affiche 10 marques entre 2 marques majeures
    $group->axis->left->setNumberByTick('minor', 'major', 10);
    // Titre des ordonnées 
    //$group->axis->bottom->title->set("Journées");
    
    // La légende est affiché en bas du graphe
    $group->legend->setModel(LEGEND_MODEL_BOTTOM);
    // Position de la légénde par rapport au graphe
    $group->legend->setPosition(NULL, 0.9);
    // Nb de colonnes
    $group->legend->setRows(2);
    //$group->legend->shadow->hide();
    
    // On créé autant de courbes qu'il y a de joueurs !
    for($j=0;$j<sizeof($G_ListeJoueurs);$j++)
    {
        // Recherche des résultats du joueur $j
        $G_ResultatJoueur=array();
        for($k=0;$k<sizeof($G_ListeJeuxZoom);$k++)
        {
            if (isset($G_ListeResultatsMoyensZoom[$k][$j]))
                $G_ResultatJoueur[$k]=$G_ListeResultatsMoyensZoom[$k][$j];
            else
                $G_ResultatJoueur[$k]=null;
        }
        // Création d'une courbe pour ce joueur
        $plot = new LinePlot($G_ResultatJoueur);
        $plot->setColor(new Color($G_MesCouleurs[$j][0],$G_MesCouleurs[$j][1],$G_MesCouleurs[$j][2]));
        $plot->setFillColor(new Color($G_MesCouleurs[$j][0],$G_MesCouleurs[$j][1],$G_MesCouleurs[$j][2],NIVEAU_TRANSPARENCE));
        
        $plot->mark->setType(MARK_CIRCLE);
        if ( $j % 3 == 0)
            $plot->mark->setType(MARK_TRIANGLE);
        if ($j % 4 == 0)
            $plot->mark->setType(MARK_CROSS);

        $plot->mark->setFill(new Color($G_MesCouleurs[$j][0],$G_MesCouleurs[$j][1],$G_MesCouleurs[$j][2]));
        $plot->mark->setSize(7);
        
        $plot->yAxis->setLabelNumber(0.5);
       // $plot->xAxis->label->setAngle(45); 
        $plot->setPadding(10, 10,10,10); 
    
        // Ajoute d'une légende pour cette courbe et ce joueur
        $group->legend->add($plot, $G_ListeJoueurs[$j], LEGEND_MARK);
        // Ajoute cette courbe au group
        $group->add($plot);
    }
  
    // Fonction qui retourne les Abscisses    
    function setAbscisseZoom($value) {
    	global $G_ListeJeuxZoom;
      return $G_ListeJeuxZoom[$value];
    }
    $group->axis->bottom->label->setCallbackFunction('setAbscisseZoom');
    
    // Fonction qui retourne les Ordonnés    
    function setOrdonneZoom($value) {
    	return round($value);
    }
    $group->axis->left->label->setCallbackFunction('setOrdonneZoom');
   
    // Ajout de ce groupe au graphique
    $graph->add($group);
  
    $graph->draw($fichier);
}

?>
