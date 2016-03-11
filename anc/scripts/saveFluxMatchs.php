<?php

$titre="";
$commentaire="";
$eq1d="";
$eq1v="";
$eq2d="";
$eq2v="";
$eq3d="";
$eq3v="";
$eq4d="";
$eq4v="";
$eq5d="";
$eq5v="";
$eq6d="";
$eq6v="";
$eq7d="";
$eq7v="";
$eq8d="";
$eq8v="";
$eq9d="";
$eq9v="";
$eq10d="";
$eq10v="";
$eq11d="";
$eq11v="";
$eq12d="";
$eq12v="";
$eq13d="";
$eq13v="";
$eq14d="";
$eq14v="";
$eq15d="";
$eq15v="";
        
        
// Chemin du fichier à enregistrer
$cheminFichier = './fichierJeux.php';

if (isset($_POST["titre"]))
{
  $titre=stripslashes($_POST["titre"]);
}
if (isset($_POST["commentaire"]))
{
  $commentaire=stripslashes($_POST["commentaire"]);
}    
if (isset($_POST["eq1d"]))
{
  $eq1d=stripslashes($_POST["eq1d"]);
}    
if (isset($_POST["eq1v"]))
{
  $eq1v=stripslashes($_POST["eq1v"]);
}    
if (isset($_POST["eq2d"]))
{
  $eq2d=stripslashes($_POST["eq2d"]);
}    
if (isset($_POST["eq2v"]))
{
  $eq2v=stripslashes($_POST["eq2v"]);
}    
if (isset($_POST["eq3d"]))
{
  $eq3d=stripslashes($_POST["eq3d"]);
}    
if (isset($_POST["eq3v"]))
{
  $eq3v=stripslashes($_POST["eq3v"]);
}    
if (isset($_POST["eq4d"]))
{
  $eq4d=stripslashes($_POST["eq4d"]);
}    
if (isset($_POST["eq4v"]))
{
  $eq4v=stripslashes($_POST["eq4v"]);
}    
if (isset($_POST["eq5d"]))
{
  $eq5d=stripslashes($_POST["eq5d"]);
}    
if (isset($_POST["eq5v"]))
{
  $eq5v=stripslashes($_POST["eq5v"]);
}    
if (isset($_POST["eq6d"]))
{
  $eq6d=stripslashes($_POST["eq6d"]);
}    
if (isset($_POST["eq6v"]))
{
  $eq6v=stripslashes($_POST["eq6v"]);
}      
if (isset($_POST["eq7d"]))
{
  $eq7d=stripslashes($_POST["eq7d"]);
}           
if (isset($_POST["eq7v"]))
{
  $eq7v=stripslashes($_POST["eq7v"]);
}
if (isset($_POST["eq8d"]))
{
  $eq8d=stripslashes($_POST["eq8d"]);
} 
if (isset($_POST["eq8v"]))
{
  $eq8v=stripslashes($_POST["eq8v"]);
}  
if (isset($_POST["eq9d"]))
{
  $eq9d=stripslashes($_POST["eq9d"]);
}    
if (isset($_POST["eq9v"]))
{
  $eq9v=stripslashes($_POST["eq9v"]);
}    
if (isset($_POST["eq10d"]))
{
  $eq10d=stripslashes($_POST["eq10d"]);
}    
if (isset($_POST["eq10v"]))
{
  $eq10v=stripslashes($_POST["eq10v"]);
}    
if (isset($_POST["eq11d"]))
{
  $eq11d=stripslashes($_POST["eq11d"]);
}    
if (isset($_POST["eq11v"]))
{
  $eq11v=stripslashes($_POST["eq11v"]);
}    
if (isset($_POST["eq12d"]))
{
  $eq12d=stripslashes($_POST["eq12d"]);
}    
if (isset($_POST["eq12v"]))
{
  $eq12v=stripslashes($_POST["eq12v"]);
}    
if (isset($_POST["eq13d"]))
{
  $eq13d=stripslashes($_POST["eq13d"]);
}    
if (isset($_POST["eq13v"]))
{
  $eq13v=stripslashes($_POST["eq13v"]);
}    
if (isset($_POST["eq14d"]))
{
  $eq14d=stripslashes($_POST["eq14d"]);
}    
if (isset($_POST["eq14v"]))
{
  $eq14v=stripslashes($_POST["eq14v"]);
}   
if (isset($_POST["eq15d"]))
{
  $eq15d=stripslashes($_POST["eq15d"]);
}    
if (isset($_POST["eq15v"]))
{
  $eq15v=stripslashes($_POST["eq15v"]);
}     


echo "<h1>Recuperation des matchs</h1>";

echo "<h1>".$titre."</h1>";

echo "<h2>".$commentaire."</h2>"; 
echo "<h2>Matchs trouves</h2>"; 

echo $eq1d."<br>";
echo $eq2d."<br>\n";
echo $eq3d."<br>\n";
echo $eq4d."<br>\n";
echo $eq5d."<br>\n";
echo $eq6d."<br>\n";
echo $eq7d."<br>\n";
echo $eq8d."<br>\n";
echo $eq9d."<br>\n";
echo $eq10d."<br>\n";
echo $eq11d."<br>\n";
echo $eq12d."<br>\n";
echo $eq13d."<br>\n";
echo $eq14d."<br>\n";
echo $eq15d."<br>\n";

echo $eq1v."<br>\n";
echo $eq2v."<br>\n";
echo $eq3v."<br>\n";
echo $eq4v."<br>\n";
echo $eq5v."<br>\n";
echo $eq6v."<br>\n";
echo $eq7v."<br>\n";
echo $eq8v."<br>\n";
echo $eq9v."<br>\n";
echo $eq10v."<br>\n";
echo $eq11v."<br>\n";
echo $eq12v."<br>\n";
echo $eq13v."<br>\n";
echo $eq14v."<br>\n";
echo $eq15v."<br>\n";   


  $handle = fopen($cheminFichier, 'w');
  fwrite($handle,"<?php ", 6);
  
  fwrite($handle,"\$titretmp=\"".$titre ."\";\n");
  fwrite($handle,"\$commentairetmp=\"".$commentaire ."\";\n");
  fwrite($handle,"\$equipe1dtmp=\"".$eq1d ."\";\n");
  fwrite($handle,"\$equipe2dtmp=\"".$eq2d ."\";\n");
  fwrite($handle,"\$equipe3dtmp=\"".$eq3d ."\";\n");
  fwrite($handle,"\$equipe4dtmp=\"".$eq4d ."\";\n");
  fwrite($handle,"\$equipe5dtmp=\"".$eq5d ."\";\n");
  fwrite($handle,"\$equipe6dtmp=\"".$eq6d ."\";\n");
  fwrite($handle,"\$equipe7dtmp=\"".$eq7d ."\";\n");
  fwrite($handle,"\$equipe8dtmp=\"".$eq8d ."\";\n");
  fwrite($handle,"\$equipe9dtmp=\"".$eq9d ."\";\n");
  fwrite($handle,"\$equipe10dtmp=\"".$eq10d ."\";\n");
  fwrite($handle,"\$equipe11dtmp=\"".$eq11d ."\";\n");
  fwrite($handle,"\$equipe12dtmp=\"".$eq12d ."\";\n");
  fwrite($handle,"\$equipe13dtmp=\"".$eq13d ."\";\n");
  fwrite($handle,"\$equipe14dtmp=\"".$eq14d ."\";\n");
  fwrite($handle,"\$equipe15dtmp=\"".$eq15d ."\";\n");
  
  fwrite($handle,"\$equipe1vtmp=\"".$eq1v ."\";\n");
  fwrite($handle,"\$equipe2vtmp=\"".$eq2v ."\";\n");
  fwrite($handle,"\$equipe3vtmp=\"".$eq3v ."\";\n");
  fwrite($handle,"\$equipe4vtmp=\"".$eq4v ."\";\n");
  fwrite($handle,"\$equipe5vtmp=\"".$eq5v ."\";\n");
  fwrite($handle,"\$equipe6vtmp=\"".$eq6v ."\";\n");
  fwrite($handle,"\$equipe7vtmp=\"".$eq7v ."\";\n");
  fwrite($handle,"\$equipe8vtmp=\"".$eq8v ."\";\n");
  fwrite($handle,"\$equipe9vtmp=\"".$eq9v ."\";\n");
  fwrite($handle,"\$equipe10vtmp=\"".$eq10v ."\";\n");
  fwrite($handle,"\$equipe11vtmp=\"".$eq11v ."\";\n");
  fwrite($handle,"\$equipe12vtmp=\"".$eq12v ."\";\n");
  fwrite($handle,"\$equipe13vtmp=\"".$eq13v ."\";\n");
  fwrite($handle,"\$equipe14vtmp=\"".$eq14v ."\";\n");
  fwrite($handle,"\$equipe15vtmp=\"".$eq15v ."\";\n");
  
  fwrite($handle,"?>", 2);
  fclose($handle);  

?>