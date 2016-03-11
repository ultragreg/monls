<?php

$date="";
$rapport15_1="";
$rapport15_2="";
$rapport15_3="";
$rapport15_4="";
$rapport15_5="";
$prono15_1="";
$prono15_2="";
$prono15_3="";
$prono15_4="";
$prono15_5="";
$gagnant15_1="";
$gagnant15_2="";
$gagnant15_3="";
$gagnant15_4="";
$gagnant15_5="";
// Chemin du fichier à enregistrer
$cheminFichier = './rapports_generes_15.php';

if (isset($_POST["date"]))
{
$date=stripslashes($_POST["date"]);
}
if (isset($_POST["rapport15_1"]))
{
$rapport15_1=stripslashes($_POST["rapport15_1"]);
}
if (isset($_POST["rapport15_2"]))
{
$rapport15_2=stripslashes($_POST["rapport15_2"]);
}
if (isset($_POST["rapport15_3"]))
{
$rapport15_3=stripslashes($_POST["rapport15_3"]);
}
if (isset($_POST["rapport15_4"]))
{
$rapport15_4=stripslashes($_POST["rapport15_4"]);
}
if (isset($_POST["rapport15_5"]))
{
$rapport15_5=stripslashes($_POST["rapport15_5"]);
}

if (isset($_POST["prono15_1"]))
{
$prono15_1=stripslashes($_POST["prono15_1"]);
}
if (isset($_POST["prono15_2"]))
{
$prono15_2=stripslashes($_POST["prono15_2"]);
}
if (isset($_POST["prono15_3"]))
{
$prono15_3=stripslashes($_POST["prono15_3"]);
}
if (isset($_POST["prono15_4"]))
{
$prono15_4=stripslashes($_POST["prono15_4"]);
}
if (isset($_POST["prono15_5"]))
{
$prono15_5=stripslashes($_POST["prono15_5"]);
}

if (isset($_POST["gagnant15_1"]))
{
$gagnant15_1=stripslashes($_POST["gagnant15_1"]);
}
if (isset($_POST["gagnant15_2"]))
{
$gagnant15_2=stripslashes($_POST["gagnant15_2"]);
}
if (isset($_POST["gagnant15_3"]))
{
$gagnant15_3=stripslashes($_POST["gagnant15_3"]);
}
if (isset($_POST["gagnant15_4"]))
{
$gagnant15_4=stripslashes($_POST["gagnant15_4"]);
}
if (isset($_POST["gagnant15_5"]))
{
$gagnant15_5=stripslashes($_POST["gagnant15_5"]);
}

function getLigne($rapport,$gagnant,$prono,$type)
{
  if ($prono=="")
      return ""; 
  return "<tr class=\"enreg $type\"><td class=\"eqg\">$prono</td><td class=\"eqd\">$gagnant</td><td class=\"eqd\">$rapport</td></tr>";
}

function writeUTF8File($filename,$content) { 
        $f=fopen($filename,"w"); 
        # Now UTF-8 - Add byte order mark 
        fwrite($f, pack("CCC",0xef,0xbb,0xbf)); 
        fwrite($f,$content); 
        fclose($f); 
} 


$temp="\xEF\xBB\xBF"."<h3>Jeu a 15</h3>";
$temp=$temp."<p>Rapports du ".$date."</p>";
$temp=$temp."<table class=\"jeu\" id=\"tableauresultat\"><tr><td class=\"infotitre\">Rapport</td><td class=\"infotitre\">Nombre de grille gagnantes</td><td class=\"infotitre\">Rapports par grille gagnante</td></tr>";
$temp=$temp.getLigne($rapport15_1,$gagnant15_1,$prono15_1,"impaire");
$temp=$temp.getLigne($rapport15_2,$gagnant15_2,$prono15_2,"paire");
$temp=$temp.getLigne($rapport15_3,$gagnant15_3,$prono15_3,"impaire");
$temp=$temp.getLigne($rapport15_4,$gagnant15_4,$prono15_4,"paire");    
$temp=$temp.getLigne($rapport15_5,$gagnant15_5,$prono15_5,"impaire");
$temp=$temp."</table></br>";

if (file_exists($cheminFichier))   {    unlink($cheminFichier);    } 
writeUTF8File($cheminFichier, $temp); 

?>