<?php
session_start();
ini_set('zlib.output_compression_level', 6);
//ob_start('ob_gzhandler');
?>
	<?php include("scripts/head.php");	?>
	

	<body>
  
  <?php include ("scripts/debutpage.php"); ?>
  

	<div id="conteneur">		
    
    <?php echo phpinfo(); ?>

    
    <?php include ("scripts/footer.php"); ?>

	</div>
	</body>
	
</html>
