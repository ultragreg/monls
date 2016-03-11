<?php 
session_start();
ini_set('zlib.output_compression_level', 6);
//ob_start('ob_gzhandler');
?>
	<?php  include("scripts/head.php");	?>

  <script type="text/javascript" src="js/swfobject.js"></script>	
  
  
	<body>    
  <?php include ("scripts/debutpage.php"); ?>
  <?php $debut=getmicrotime(); ?>

	<div id="conteneur">		

    <?php 
    include ("scripts/header.php");
		
    include ("scripts/menu.php"); 
		
    // On affiche cette page uniquement si on est connecté 
    //if (!isset($_SESSION['id_joueur'])) 
    //{
    //  // Authentification correcte
    //  header('Location: index.php');
    //}
    include ("scripts/classementgeneralv2.php");
    ?>

<script type="text/javascript">
swfobject.embedSWF(
  "open-flash-chart.swf", "my_chart", "850", "600",
  "9.0.0", "expressInstall.swf",
  {"data-file":"data2.json"}
  );
</script>


</script>
 


    <?php		
    include ("scripts/footer.php"); 
     ?>
  <?php 
  $fin=getmicrotime();
  echo "<p style='color:gray;font-size=6px'>Temps de génération : ".($fin-$debut)."</p>"; 
  ?>
     
	</div>
	</body>
	
</html>
