<?php
session_start();
ini_set('zlib.output_compression_level', 6);
//ob_start('ob_gzhandler');
?>
	<?php include("scripts/head.php");	?>
	

<!-- 
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-20550032-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
 -->

	<body>
  
  <?php include ("scripts/debutpage.php"); ?>
  

	<div id="conteneur">		
    
    <?php include ("scripts/header.php"); ?>
		
    <?php include ("scripts/menu.php"); ?>
    <?php include ("scripts/accueil.php"); ?>

    
    <?php include ("scripts/footer.php"); ?>

	</div>
	</body>
	
</html>
