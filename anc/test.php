<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Pof !</title>
</head>
<body>
<?php 
	// Dans l'url se trouve éventuellement l'identifiant de l'objet à modifier
  if (isset($_POST['url']) && (isset($_POST['url']))) 
  {               
      // Initialisation des variables
      $url=$_POST["url"];
      //"r" ou "rb" ??
	  $variable = fopen($url, "rb");  
	  echo "Site :".$url;
	  echo stream_get_contents($variable);  
	  echo "-------";
  }
  ?>
  <form method="post" action="test.php">
  <label for="url">Url :</label>
  <input name="url" id="url" type="text" size="30" maxlength="30" value="http://www.youtube.fr" />
  <input type="submit" value="Valider" />
  </form>
</body>
</html>
