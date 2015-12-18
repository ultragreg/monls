<?php 
session_start();
header('Content-Type: application/json');
include_once '../config/database.php';
include_once(dirname(__FILE__) . '/../config/Mysqldump.php');
// BATCH de sauvegarde de la BD
if (isset($_SESSION['id_joueur'])) {
  $debug=false;
  if (isset($_GET['debug']))
  {
    // Mode debug
    $debug=true;
    echo "Sauvegarde de la BD" . "\n";
  }
  if (isset($_GET['mail']) && strlen($_GET['mail'])>0)
  {
    $json="";
    $destinataire=$_GET['mail'];
    $date = new DateTime();
    $fichier='dump.sql'.$date->format('YmdHis');
    $fichierComplet='savDB/'.$fichier;
    if ($debug==true) {
      echo "Nom du fichier produit : " . $fichierComplet . "\n";
    }
    // 1. Dump
    $database = new Database();
    $dump = new Ifsnop\Mysqldump\Mysqldump("mysql:host=".$database->getHost().";dbname=".$database->getDb_name(), $database->getUsername(), $database->getPassword());
    $dump->start($fichierComplet);

    // 2. Read File
    $file_size = filesize($fichierComplet);
    if ($debug==true) {
      echo "Lecture du fichier, taille : " . $file_size . "\n";
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $ftype = finfo_file( $finfo, $fichierComplet);
    if ($debug==true) {
      echo "Type : " . $ftype . "\n";
    }
    if ($debug==true) {
      echo "Basename : " . basename($fichierComplet) . "\n";
    }
    $handle = fopen($fichierComplet, "r");
    $content = fread($handle, $file_size);
    fclose($handle);

    $contentBase64 = chunk_split(base64_encode($content));

    // carriage return type (we use a PHP end of line constant)
    $eol = PHP_EOL;
    // a random hash will be necessary to send mixed content
    $separator = md5(time());

    // 3. Mail
    $entetedate  = date("D, j M Y H:i:s -0600"); // Offset horaire
    $entetemail  = "From: AdministrateurMonLS". $eol; // Adresse expéditeur
    $entetemail .= "Cc: ". $eol;
  
    $entetemail .= "Bcc: ". $eol; // Copies cachées
    $entetemail .= "Reply-To: ". $eol; // Adresse de retour
    $entetemail .= "X-Mailer: PHP/" . phpversion(). $eol;
    // attachment
    /*
    //$entetemail .= "--" . $separator . $eol;
    $entetemail .= "Content-Type: " . $ftype . "; name=\"" . $fichier . "\"" . $eol;
    $entetemail .= "Content-Transfer-Encoding: base64" . $eol;
    $entetemail .= 'Content-ID: <' . basename($fichierComplet) . '>' . $eol;    
    //$entetemail .= "Content-Disposition: attachment" . $eol . $eol;
    $entetemail .= $eol . $contentBase64 . $eol;
    //$entetemail .= "--" . $separator . "--";
  */
    $entetemail .= "Date: $entetedate";
    if (!@mail($destinataire, "Sauvegarde de la BD monls", $content,$entetemail)) {
      $json["resultat"]=false;
      $json["commentaire"]=error_get_last(); 
    } else {
      if ($debug==true) {
        echo "Mail envoyé à : " . $destinataire . "\n";
      }
      $json["resultat"]=true;
    }

  } else {
    $json["resultat"]=false;
    $json["commentaire"]="Le mail des destinataires n'est pas renseigné"; 
  }
} 
else
{
  $json["resultat"]=false;
  $json["commentaire"]="Pas connecté !";
}
echo json_encode($json);
?>

