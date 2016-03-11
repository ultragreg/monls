<div class="menu">
<?php 
if (!isset($_SESSION['id_joueur'])) 
{
?>
  <ul id="menu">
    <li><a class="home" href="index.php"></a></li>
    <li><a href="connexion.php" class="active item">Connexion</a></li>
  	<li><span class="inactive item">Vos Pronostics</span></li>
  	<li><a href="classementv2.php" class="active item">Classement</a></li>
  </ul>  
<?php 
}
else
{
?>  
  <ul id="menu">
    <li><a class="home" href="index.php"></a></li>
  	<li><a href="deconnexion.php" class="active item">Déconnexion</a></li>
  	<li><a href="prochainjeu.php" class="active item">Vos Pronostics</a></li>
    <li><a href="classementv2.php" class="active item">Classement</a></li>
    <li><a href="statistiques.php" class="active item">Statistiques</a></li>
    <li><a href="javascript:affichageChat();" class="active item">Chat (beta)</a></li>
  	<?php 
  	if (isset($_SESSION['admin']) && $_SESSION['admin']=='O')
  	{
  	?>
    <li><a href="requeteur.php" class="active item">Requeteur</a></li>
  	<li><a href="administration.php" class="active item">Administration</a></li>
  	<?php 
    }
    ?>  
  </ul>
  <span class="accueilutilisateur">
  Bienvenue <?php  echo $_SESSION['nom_joueur']; ?> 
  </span>  
  <span class="connexionutilisateur">
  (dernière connexion le <?php  echo formatte_date($_SESSION['der_cnx_joueur'],1); ?> )
  </span>
<?php 
}
?>
</div>
