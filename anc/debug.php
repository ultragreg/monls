<?php 
session_start();
ini_set('zlib.output_compression_level', 6);
ob_start('ob_gzhandler');
?>
	<?php  include("scripts/head.php");	?>
	
	<body>    
        <?php include ("scripts/debutpage.php"); ?>
            
  <div id="conteneur">  

    <?php 
      
    include ("scripts/header.php"); 
    
    include ("scripts/menu.php"); 
    
  ?>
        <h3>Contenu de la session</h3>
        <?php if (isset($_SESSION)) echo var_dump($_SESSION); ?>

        <h3>Contenu des cookies</h3>
        <?php              
            echo var_dump($_COOKIE);
        ?>


        
        <?php include ("scripts/footer.php"); ?>
      </div>

  </body>

	
</html>
