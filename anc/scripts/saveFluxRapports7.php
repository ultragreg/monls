<?php

$date="";
$rapport7_1="";
$rapport7_2="";
$rapport7_3="";
$rapport7_4="";
$prono7_1="";
$prono7_2="";
$prono7_3="";
$prono7_4="";
$gagnant7_1="";
$gagnant7_2="";
$gagnant7_3="";
$gagnant7_4="";
// Chemin du fichier à enregistrer
$cheminFichier = './rapports_generes_7.php';

if (isset($_POST["date"]))
{
$date=stripslashes($_POST["date"]);
}
if (isset($_POST["rapport7_1"]))
{
$rapport7_1=stripslashes($_POST["rapport7_1"]);
}
if (isset($_POST["rapport7_2"]))
{
$rapport7_2=stripslashes($_POST["rapport7_2"]);
}
if (isset($_POST["rapport7_3"]))
{
$rapport7_3=stripslashes($_POST["rapport7_3"]);
}
if (isset($_POST["rapport7_4"]))
{
$rapport7_4=stripslashes($_POST["rapport7_4"]);
}


if (isset($_POST["prono7_1"]))
{
$prono7_1=stripslashes($_POST["prono7_1"]);
}
if (isset($_POST["prono7_2"]))
{
$prono7_2=stripslashes($_POST["prono7_2"]);
}
if (isset($_POST["prono7_3"]))
{
$prono7_3=stripslashes($_POST["prono7_3"]);
}
if (isset($_POST["prono7_4"]))
{
$prono7_4=stripslashes($_POST["prono7_4"]);
}


if (isset($_POST["gagnant7_1"]))
{
$gagnant7_1=stripslashes($_POST["gagnant7_1"]);
}
if (isset($_POST["gagnant7_2"]))
{
$gagnant7_2=stripslashes($_POST["gagnant7_2"]);
}
if (isset($_POST["gagnant7_3"]))
{
$gagnant7_3=stripslashes($_POST["gagnant7_3"]);
}
if (isset($_POST["gagnant7_4"]))
{
$gagnant7_4=stripslashes($_POST["gagnant7_4"]);
}

function getLigne($rapport,$gagnant,$prono,$type)
{
  if ($prono=="")
      return ""; 
  return "<tr class=\"enreg $type\"><td class=\"eqg\">$prono</td><td class=\"eqd\">$gagnant</td><td class=\"eqd\">$rapport</td></tr>";
}



$handle = fopen($cheminFichier, 'w');
fwrite($handle,"<h3>Jeu a 7</h3>");
fwrite($handle,"<p>Rapports du ".$date."</p>");
fwrite($handle,"<table class=\"jeu\" id=\"tableauresultat\"><tr><td class=\"infotitre\">Rapport</td><td class=\"infotitre\">Nombre de grille gagnantes</td><td class=\"infotitre\">Rapports par grille gagnante</td></tr>");
fwrite($handle,getLigne($rapport7_1,$gagnant7_1,$prono7_1,"impaire"));
fwrite($handle,getLigne($rapport7_2,$gagnant7_2,$prono7_2,"paire"));
fwrite($handle,getLigne($rapport7_3,$gagnant7_3,$prono7_3,"impaire"));
fwrite($handle,getLigne($rapport7_4,$gagnant7_4,$prono7_4,"paire"));
fwrite($handle,"</table></br>");
fclose($handle);
?>