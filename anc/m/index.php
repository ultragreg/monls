<?php
session_start();
ini_set('zlib.output_compression_level', 6);
//ob_start('ob_gzhandler');
?>	
<?php include("scripts/head.php");	?>      
<body>  
    
  <!-- Page principale id=#pageAccueil -->          
  <div data-role="page" data-theme="a" id="pageAccueil" data-cache="never" >            
    <div data-role="content" style="padding:0">			         
      <img src="../img/logomini.jpg" alt="monls.free.fr/m"/ width=300 height=125 onClick="window.alert(userNom);"> 
      <ul data-role="listview" data-inset="true" id="ulMenu"></ul>
      <?php include ("scripts/accueil.php"); ?>        
    </div>            
    <div data-role="footer" data-theme="b" >               
      <font style="font-weight:bold;align:center;">Version mobile
      </font> - 
      <a href="http://monls.free.fr">Version standard</a>
      </p>            
    </div>         
  </div>     
  
  <!-- Page de connexion id=#pageConnexion --> 
  <div data-role="page" id="pageConnexion">	
      <div data-role="header" data-theme="d">
          <h1>Connexion</h1>	
      </div>	
      <div data-role="content" data-theme="a">	 	
            <div class="ui-field-contain">
                <label for="loginConnexion">Login</label>
                <input type="text" name="login" id="loginConnexion" value=""  />
            </div>    
            <div class="ui-field-contain">
                <label for="motDePasseConnexion">Password</label>
                <input type="password" name="password" id="motDePasseConnexion" value="" />
            </div>    
            <div class="ui-field-contain">
                    <label for="auto">Je reste connecté</label>
                    <input name="auto" id="auto" type="checkbox">
            </div>
             <h5 id="notificationConnexion"></h5>
            <a href="#" id="boutonConnexion" data-role="button" data-theme="e">Connexion</a> 
      </div>    
  </div> 
  
  <!-- Page mes résultats id=#pageResultats -->          
  <div data-role="page" data-theme="a" id="pageResultats">            
    <div data-role="header" data-position="inline" data-theme="b" >
			<a href="#pageAccueil" data-direction="reverse">Accueil</a>
			<a href="#pageResultatsJoueurs" data-direction="reverse" id="retourPageResultatsJoueurs">Retour</a>
			<h1>R&eacute;sultats</h1>
		</div>	
    <div data-role="content">
      <div id="contenuPageResultats">
        <h2 id="titreResultats"></h2>
        <ul data-role="listview" data-inset="true" id="ulResultats" data-count-theme="b"></ul>
      </div>			           
    </div>            
    <div data-role="footer" data-theme="b" >               
      <font style="font-weight:bold;align:center;">Version mobile</font> - 
      <a href="http://monls.free.fr">Version standard</a>
      </p>            
    </div>        
  </div>     
                
  <!-- Page résultats joueurs id=#pageResultatsJoueurs -->          
  <div data-role="page" data-theme="a" id="pageResultatsJoueurs">            
    <div data-role="header" data-position="inline" data-theme="b" >
			<a href="#pageAccueil" data-direction="reverse">Accueil</a>
			<h1>R&eacute;sultats des joueurs</h1>
		</div>	
    <div data-role="content">
      <div id="contenuPageResultats">
        <ul data-role="listview" data-inset="true" id="ulResultatsJoueurs" data-count-theme="b"></ul>
      </div>			           
    </div>            
    <div data-role="footer" data-theme="b" >               
      <font style="font-weight:bold;align:center;">Version mobile</font> - 
      <a href="http://monls.free.fr">Version standard</a>
      </p>            
    </div>        
  </div>     
              
  <!-- Page classement général id=#pageClassement -->          
  <div data-role="page" data-theme="a" id="pageClassement">            
    <div data-role="header" data-position="inline" data-theme="b" >
			<a href="#pageAccueil" data-direction="reverse">Accueil</a>
			<h1>Classement</h1>
		</div>	
    <div data-role="content">
      <div id="contenuPageClassement">
        <ul data-role="listview" data-inset="true" id="ulClassement" data-divider-theme="e" data-count-theme="b"></ul>
      </div>			           
    </div>            
    <div data-role="footer" data-theme="b" >               
      <font style="font-weight:bold;align:center;">Version mobile</font> - 
      <a href="http://monls.free.fr">Version standard</a>
      </p>            
    </div>        
  </div> 
                
  <!-- Page gain id=#pageGain -->          
  <div data-role="page" data-theme="a" id="pageGain">            
    <div data-role="header" data-position="inline" data-theme="b" >
			<a href="#pageAccueil" data-direction="reverse">Accueil</a>
			<h1>Gain</h1>
		</div>	
    <div data-role="content">
      <div id="contenuPageGain">
        <ul data-role="listview" data-inset="true" id="ulGain" data-divider-theme="e" data-count-theme="b"></ul>
      </div>			           
    </div>            
    <div data-role="footer" data-theme="b" >               
      <font style="font-weight:bold;align:center;">Version mobile</font> - 
      <a href="http://monls.free.fr">Version standard</a>
      </p>            
    </div>        
  </div> 
                
  <!-- Page saisie jeu id=#pageProchainJeu -->          
  <div data-role="page" data-theme="a" id="pageProchainJeu"  data-cache="never">            
    <div data-role="header" data-position="inline" data-theme="b" >
			<a href="#pageAccueil" data-direction="reverse">Accueil</a>
			<h1>Prochain Jeu</h1>
		</div>	
    <div data-role="content">
        <div data-role="fieldcontain" id="divProchainJeu"></div>
        <a href="#" id="boutonValideProno" data-role="button">Valider</a>		           
    </div>            
    <div data-role="footer" data-theme="b" >               
      <font style="font-weight:bold;align:center;">Version mobile</font> - 
      <a href="http://monls.free.fr">Version standard</a>
      </p>            
    </div>        
  </div> 
                           
  <!-- Page saisie jeu id=#pageSaisieResultats -->          
  <div data-role="page" data-theme="b" id="pageSaisieResultats" data-cache="never">            
    <div data-role="header" data-position="inline" data-theme="b" >
			<a href="#pageAccueil" data-direction="reverse">Accueil</a>
			<h1>Saisie r&eacute;sultat</h1>
		</div>	
    <div data-role="content">      
        <h2 id="titreSaisieResultats"></h2>
        <div data-role="fieldcontain" id="divSaisieResultats"></div>
        <a href="#" id="boutonValideResultats" data-role="button">Valider</a>		           
    </div>            
    <div data-role="footer" data-theme="b" >               
      <font style="font-weight:bold;align:center;">Version mobile</font> - 
      <a href="http://monls.free.fr">Version standard</a>
      </p>            
    </div>        
  </div> 

  <!-- Page de chat --> 
  <div data-role="page" id="pageChat"> 
      <div data-role="header" data-theme="b"  data-position="fixed" id="chatHeader">
        <a href="#pageAccueil" data-direction="reverse">Accueil</a>
        <a href="" id="chatLogIn" class="login">Connexion</a>
        <h1 id="chatNomJoueur">Chat béta</h1>
      </div>  
      <div data-role="content" data-theme="a" id="chatContent">    
        <ul data-role="listview" data-inset="true" data-count-theme="b" data-theme="c" data-divider-theme="d" id="chat_area" />
      </div> 
    <div data-theme="a" data-role="footer" data-position="fixed" id="chatFooter">
      <div data-role="fieldcontain" id="chatSubmitForm" >
       <fieldset class="ui-grid-a">
          <div class="ui-block-a" style="margin-left: 5%;">
              <input name="chat_message" id="chatText" maxlength="180" placeholder="Votre message ..." value="" type="text" class="required"/>
          </div>
          <div class="ui-block-b">
              <a data-theme="a" id="chatSendButton" data-role="button">Go</a>
          </div>
        </fieldset>        
      </div>
    </div>
  </div> 
  

                
</body>     
</html>	 