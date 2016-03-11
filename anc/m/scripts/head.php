<!DOCTYPE html>    
<html>    
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />  
    <title>Site officiel des joueurs du LS du Mypyh</title>       
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="mobile-web-app-capable" content="yes">
<!--     <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" /> -->
    <link rel="stylesheet" href="style/jquery.mobile-1.4.2.min.css" />
    <script src="scripts/jquery-1.9.1.min.js"></script>
    <script src="scripts/chatmobile.js"></script>
        
    <!-- config de jquery mobile (doit être placé avec le chargement de jquery mobile) -->
    <script type="text/javascript">		
     var userConnecte="0";
     var userId="";
     var userNom="";
     var userDerConnexion="";
     var userAdmin="";

     $(document).bind("mobileinit", function(){			
          $.mobile.pageLoading = true;	
          $.mobile.loadingMessage = "Chargement en cours";
          $.mobile.pageLoadErrorMessage = "Erreur durant le chargement";
          $.mobile.defaultPageTransition = "slide";
          $.mobile.defaultDialogTransition = "pop";
          $.mobile.touchOverflowEnabled = true;
      });
      
      $.ajaxSetup({ 
      timeout: 10000
      });

    function estConnecte () 
    {       
        userConnecte="0";
        userId="";
        userNom="";
        userDerConnexion="";
        userAdmin="";
        
        $.ajax({
            url: 'scripts/estConnecte.php',
            async: false,
            dataType: 'json',
            success: function (data) {
              var items = [];
              $.each(data, function(key, val) 
              {   
                  if (key == "id_joueur")  { userId=val; }
                  if (key == "nom_joueur") { userNom=val;}
                  if (key == "der_cnx_joueur") { userDerConnexion=val; }
                  if (key == "admin")     { userAdmin=val; }

                  if (userId.length > 0)  { userConnecte="1"; }
              });
              }
        });
/*        console.log('connecté [' + userId.length+"]");
        console.log('connecté ? ' + userConnecte);*/
        return userConnecte;
    }	      		

    // Fonction d'affichage de la page d'accueil
		function construitPageAccueil() 
    {	
        var i = estConnecte (); 
        $('#ulMenu > li').remove();
				if (i != "1") 
        {   
	    	  	$("#ulMenu").append('<li><a href="#pageConnexion" data-rel="dialog">Connexion</a></li>');    
	    	  	$("#ulMenu").append('<li><a href="#" id="boutonResultatsJoueurs">R&eacute;sultats Joueurs</a></li>');
	    	  	$("#ulMenu").append('<li><a href="#" id="boutonClassement">Classement</a></li>');
            $("#ulMenu").append('<li><a href="#" id="boutonGain">Gain</a></li>');
	    	    $("#bienvenue1").text('');  
            $("#bienvenue2").text('');  
				} 
        else 
        {   
	    	  	$("#ulMenu").append('<li><a href="#" id="boutonDeconnexion" >D&eacute;connexion</a></li>');  
	    	  	$("#ulMenu").append('<li><a href="#" id="boutonProchainJeu">Prochain Jeu</a></li>');
	    	  	$("#ulMenu").append('<li><a href="#" id="boutonSaisieResultats">Saisie R&eacute;sultats</a></li>');
	    	  	$("#ulMenu").append('<li><a href="#" id="boutonMesResultats">Mes r&eacute;sultats</a></li>');
	    	  	$("#ulMenu").append('<li><a href="#" id="boutonResultatsJoueurs">R&eacute;sultats Joueurs</a></li>');
	    	  	$("#ulMenu").append('<li><a href="#" id="boutonClassement">Classement</a></li>');         
	    	  	$("#ulMenu").append('<li><a href="#" id="boutonGain">Gain</a></li>');
            $("#ulMenu").append('<li><a href="#" id="boutonChat">Chat (béta)</a></li>');
	    	    $("#bienvenue1").text('Bienvenue ' + userNom);  
            $("#bienvenue2").text('Dernière connexion le : ' + userDerConnexion);
	    	}
	    	$("#ulMenu").listview("refresh");
			}


		// --- Actions --- 
		function actionProchainJeu() {	  
     $.mobile.loading('show');	
     $.ajax({
          type: "POST",
          url: "scripts/listeMatchProno.php", 					
          contentType: "application/json; charset=utf-8",						
          dataType: "json",	
          cache: false,
          success: onSuccessProchainJeu,
          error: siErreur
      });
			return false;
		}
		
    function actionSaisieResultats() {	  
     $.mobile.loading('show');		 
     $.ajax({
          type: "POST",
          url: "scripts/listeMatchResultats.php", 					
          contentType: "application/json; charset=utf-8",						
          dataType: "json",	
          cache: false,
          success: onSuccessSaisieResultats,
          error: siErreur
      });
			return false;
		}
		
		function actionClassement() {	   
      $.mobile.loading('show');			
      $.ajax({
          type: "POST",
          url: "scripts/classement.php", 					
          contentType: "application/json; charset=utf-8",						
          dataType: "json",	
          cache: false,
          success: onSuccessClassement,
          error: siErreur
      });
			return false;
		}
			
    function actionGain() {    
      $.mobile.loading('show');     
      $.ajax({
          type: "POST",
          url: "scripts/gain.php",          
          contentType: "application/json; charset=utf-8",           
          dataType: "json", 
          cache: false,
          success: onSuccessGain,
          error: siErreur
      });
      return false;
    }
    
    function actionChat() {    
      $("#chatNomJoueur").text(userNom); 
      $.mobile.changePage("#pageChat"); 
      chat.init(userNom);
     return false;
    }
    
    
    function actionDestroyChat() {    
      chat.destroy();
    }
    
    function actionMesResultats(id_joueur) {   
      $.mobile.loading('show');   
      $.ajax({
          type: "POST",
          url: "scripts/mesResultats.php",          
          contentType: "application/json; charset=utf-8",           
          dataType: "json", 
          cache: false,
          success: onSuccessMesResultats,
          error: siErreur
      });
   
      return false;
    }   

    function actionJoueurResultats(idjo) {   
      $.mobile.loading('show');   
      $.ajax({
          type: "POST",
          url: "scripts/joueurResultats.php?idj="+idjo,          
          contentType: "application/json; charset=utf-8",           
          dataType: "json", 
          cache: false,
          success: onSuccessMesResultats,
          error: siErreur
      });
      return false;
    }
				
		function actionResultatsJoueurs() {	 
      $.mobile.loading('show');			
      $.ajax({
          type: "POST",
          url: "scripts/resultatsJoueurs.php", 					
          contentType: "application/json; charset=utf-8",						
          dataType: "json",	
          cache: false,
          success: onSuccessResultatsJoueurs,
          error: siErreur
      });

			return false;
		}
		
		function actionDeconnexion() {	 
			$.mobile.loading('show');			
      $.ajax({
          type: "POST",
          url: "scripts/deconnexion.php", 				
          cache: false,
          success: onSuccessDeconnexion,
          error: siErreur
      });
			return false;
		}
		
		function actionConnexion() {		
			var login      = $("#loginConnexion");
      var password   = $("#motDePasseConnexion");
      var automatique = $("#auto").is(":checked");
			if (login.val() == "" || password.val() == "") {
				alert("Veuillez indiquer votre login et mot de passe !");				
			} 
      else 
      {				
				$.mobile.loading('show');
        $.ajax({
            type: "POST",
            url: "scripts/connexion.php", 				
            cache: false,
            data: "login=" + login.val() + "&motpasse=" + password.val()+ "&auto=" + automatique,
            success: onSuccessConnexion,
            error: siErreur
        });
			}
			return false;
		}
		
		function actionValideProno() {
      var chainePost="";	
      var compteur=0;
      for (var l=1; l<=15; l++) 
      {              
          for (var c=1; c<=3; c++) 
          {      
    					var tmp = $("#tl"+l+"c"+c);  
              if ( tmp.is(":checked") ) 
              { 
                chainePost=chainePost + "tl" + l + "c" + c + "=1";
    					  chainePost=chainePost + "&";
                compteur++;
    					}
					}
			}	
      if (compteur === 7 || compteur === 14 || compteur === 15) {
      chainePost=chainePost + "fin=0";      
			$.mobile.loading('show');
      $.ajax({
         type: "POST",
         url: "scripts/valideProno.php", 				
         cache: false,
         data: chainePost,
         success: onSuccessValideProno,
         error: siErreur
         });
      }
      else {
      alert("Tiens, il semblerait que vous aillez oublié de pronostiquer un ou plusieurs matchs non ? ");     
      }
			return false;
		}
		
		function actionValideResultats() {
      var chainePost="";	
      for (var l=1; l<=15; l++) 
      {              
          for (var c=1; c<=3; c++) 
          {      
    					var tmp = $("#rl"+l+"c"+c);  
              if ( tmp.is(":checked") ) 
              { 
                chainePost=chainePost + "rl" + l + "c" + c + "=1";
    					  chainePost=chainePost + "&";
    					}
					}
			}	
      chainePost=chainePost + "fin=0";      
			$.mobile.loading('show');
      $.ajax({
         type: "POST",
         url: "scripts/valideResultats.php", 				
         cache: false,
         data: chainePost,
         success: onSuccessValideResultats,
         error: siErreur
         });
			return false;
		}

    // --- Succes / Echec des appels Ajax ---               
    function onSuccessClassement(json, status)
    {         
      $.mobile.loading('hide');	  
      if (siRetourErreur(json)===true)  return 1;
		  $.mobile.changePage("#pageClassement");	
			$('#ulClassement > li').remove();	                         
      for (var i=0; i<json.length; i++) 
      {         
					var retour = json[i];
					var position = retour.pos;      		
					var moyenne = retour.moy;    		
					var joueur = retour.jou;  	  
          var evol = retour.evo;  
          var pl = retour.pl; 
          if (typeof pl == 'undefined') {  pl="" } else { pl="("+pl+")"};
					if (joueur==userNom) { joueur = '<span style="color:red";>' + joueur + '</span>'; }
					if       (evol=="U") { img ="../img/stand_up.gif"; style="evolp"}
					else if  (evol=="D") { img ="../img/stand_down.gif"; style="evoln"}
					else                 { img ="../img/stand_equal.gif"; style=""}
					var cls='e';
          if (i<3)                   { cls='d'; }
					else if (i>json.length-3)  { cls='c'; }
          $("#ulClassement").append('<li data-theme=\"'+cls+'\"><img style="margin:0" src="' + img + '" alt="ok" class="ui-li-icon ui-li-thumb ui-corner-tl"><span style="padding-right: 15px;">'+position+"</span>" + joueur + "&nbsp;<span class='"+style+"'>"+pl+'</span><span class="ui-li-count">&nbsp;'+moyenne+'&nbsp;%</span></li>');             								
			}								
			$("#ulClassement").listview("refresh");
    }

    // --- Succes / Echec des appels Ajax ---               
    function onSuccessGain(json, status)
    {         
      $.mobile.loading('hide');	 
      if (siRetourErreur(json)===true)  return 1;
		  $.mobile.changePage("#pageGain");	
			$('#ulGain > li').remove();	                         
      for (var i=0; i<json.length; i++) 
      {         
					var retour = json[i];
					var position = retour.pos;      		
					var montant = retour.mon;    		
					var joueur = retour.jou;  	
          var cls='e';
          if (i<3)                   { cls='d'; }	
					if (joueur==userNom) { joueur = '<span style="color:red";>' + joueur + '</span>'; }
          $("#ulGain").append('<li data-theme=\"'+cls+'\"><span style="padding-right: 15px;">'+position+"</span>" + joueur + '<span class="ui-li-count">&nbsp;'+montant+'&nbsp;&euro;</span></li>');             								
			}								
			$("#ulGain").listview("refresh");
    }
    
    function onSuccessProchainJeu(json, status)
    {   	           
      $.mobile.loading('hide');   
      if (siRetourErreur(json)===true)  return 1;
		  $.mobile.changePage("#pageProchainJeu");
      $('#divProchainJeu > p').remove();
      $('#divProchainJeu > fieldset').remove();
      var l=1;            
      var c=1;
      var bloque = json[0].bloque;
      if (bloque && bloque=="1") 
      {
            $("#divProchainJeu").html('<p class="jeubloque">Les pronostics sont fermés.</p>');
            $("#boutonValideProno").hide();
      }
      else
      {
            $("#boutonValideProno").show();
            for (var i=0; i<json.length; i++) 
            {  
      					var retour = json[i];
      					var eqd = retour.d;      		
      					var eqv = retour.v;    		
      					var pro1 = retour.p1;  		
      					var proN = retour.pN;  		
      					var pro2 = retour.p2; 
                //alert (eqd + " - " + eqv + " : " + pro1 + ", " + proN + ", " + pro2 + ".")        
                var idtemp="id_pro"+i;
                var style="";
                if (l==7) style='style="border-bottom:4px solid red"'
                $("#divProchainJeu").append('<fieldset data-role="controlgroup" data-type="horizontal" data-role="fieldcontain" id="'+idtemp+'" '+style+'></fieldset>');  
                var idtemp="#"+idtemp;
                check=""; if (pro1!="") check='checked="checked"';
                $(idtemp).append('<input type=\"checkbox\" name=\"tl'+l+'c'+c+'\" id=\"tl'+l+'c'+c+'\" class="custom" '+check+'/><label for=\"tl'+l+'c'+c+'\">'+eqd+'</label>');  c++;   
                check=""; if (proN!="") check='checked="checked"';
                $(idtemp).append('<input type=\"checkbox\" name=\"tl'+l+'c'+c+'\" id=\"tl'+l+'c'+c+'\" class="custom" '+check+'/><label class="centre" for=\"tl'+l+'c'+c+'\">Nul</label>');      c++; 
                check=""; if (pro2!="") check='checked="checked"';
                $(idtemp).append('<input type=\"checkbox\" name=\"tl'+l+'c'+c+'\" id=\"tl'+l+'c'+c+'\" class="custom" '+check+'/><label for=\"tl'+l+'c'+c+'">'+eqv+'</label>');    
                l++;    
                c=1;
                $(idtemp).append('<span >&nbsp</span>'); 
      			}	           	 
      }
      $('#pageProchainJeu').page('destroy').page();
    }    
    
    
    function onSuccessSaisieResultats(json, status)
    {   	                             
      $.mobile.loading('hide');  
      if (siRetourErreur(json)===true)  return 1;
		  $.mobile.changePage("#pageSaisieResultats");
      $('#divSaisieResultats > fieldset').remove();
      var l=1;            
      var c=1;
      var txt="";
      var bloque = json[0].bloque;
      if (bloque && bloque=="1") 
      {
            $("#divSaisieResultats").html('<p class="jeubloque">Les pronostics sont encore ouverts.</p>');
            $("#boutonValideResultats").hide();
      }
      else
      {

          for (var i=0; i<json.length; i++) 
          {  
    					var retour = json[i];
              if (i==0)
              {
                  var txt = retour.titre;
              }
              else
              {
        					var eqd = retour.d;      		
        					var eqv = retour.v;    		
        					var res1 = retour.r1;  		
        					var resN = retour.rN;  		
        					var res2 = retour.r2; 
                  //alert (eqd + " - " + eqv + " : " + res1 + ", " + resN + ", " + res2 + ".")        
                  var idtemp="id_res"+i;
                  var style="";
                  if (l==7) style='style="border-bottom:4px solid red"'
                  $("#divSaisieResultats").append('<fieldset data-role="controlgroup" data-type="horizontal" data-role="fieldcontain" id="'+idtemp+'" '+style+'></fieldset>');  
                  var idtemp="#"+idtemp;
                  check=""; if (res1!="") check='checked="checked"';
                  $(idtemp).append('<input type=\"checkbox\" name=\"rl'+l+'c'+c+'\" id=\"rl'+l+'c'+c+'\" class="custom" '+check+'/><label for=\"rl'+l+'c'+c+'\">'+eqd+'</label>');  c++;   
                  check=""; if (resN!="") check='checked="checked"';
                  $(idtemp).append('<input type=\"checkbox\" name=\"rl'+l+'c'+c+'\" id=\"rl'+l+'c'+c+'\" class="custom" '+check+'/><label  class="centre" for=\"rl'+l+'c'+c+'\">Nul</label>');      c++; 
                  check=""; if (res2!="") check='checked="checked"';
                  $(idtemp).append('<input type=\"checkbox\" name=\"rl'+l+'c'+c+'\" id=\"rl'+l+'c'+c+'\" class="custom" '+check+'/><label for=\"rl'+l+'c'+c+'">'+eqv+'</label>');    
                  l++;    
                  c=1;
                  $(idtemp).append('<span >&nbsp</span>'); 
              } 
    			}	    
          $('#titreSaisieResultats').text(txt);	
      }             
      $('#pageSaisieResultats').page('destroy').page(); 
    }    
    
    function onSuccessMesResultats(json, status)
    { 
      $.mobile.loading('hide');    
      if (siRetourErreur(json)===true)  return 1;
      $.mobile.changePage("#pageResultats");	
      $('#ulResultats > li').remove();	
      var nbresok=0;	
      var nbrestot=0;
      for (var i=0; i<json.length; i++) 
      {   
      	var retour = json[i];
        if (i==0)
        {
            var nom = retour.n;
            var typ = retour.t;
        }
        else
        {
      			var img = retour.i;      		
      			var match = retour.m;    		
      			var pronostic = retour.p; 
      			var image="";
      			if       (img=="1") { image = "../img/ok.png"; nbresok=nbresok+1; }
      			else if  (img=="2") { image = "../img/pb.png"; }
      			else                { image = "../img/na.png"; }
      			
            if (pronostic!="")				
            {
                nbrestot=nbrestot+1;
                $("#ulResultats").append('<li><img style="margin:0" src="' + image + '" alt="ok" class="ui-li-icon ui-li-thumb ui-corner-tl" >' + match + '<span class="ui-li-count">&nbsp;'+pronostic+'&nbsp;</span></li>');
            }              								
            else
            {
                $("#ulResultats").append('<li><img style="margin:0" src="' + image + '" alt="ok" class="ui-li-icon ui-li-thumb ui-corner-tl" >' + match + '</li>');
            }              								
        }              								
      }	
      if (typ==0)   $('#retourPageResultatsJoueurs').hide();
      else          $('#retourPageResultatsJoueurs').show();          	
      txt=' bon resultat sur ';
      if (nbresok>1)   txt=' bons resultats sur ';
      $('#titreResultats').text(nom+" : "+nbresok+txt+nbrestot);							
      $("#ulResultats").listview("refresh");  
    }	
    
    function onSuccessResultatsJoueurs(json, status)
    { 
      $.mobile.loading('hide');    
      if (siRetourErreur(json)===true)  return 1;
 		  $.mobile.changePage("#pageResultatsJoueurs");	
 			$('#ulResultatsJoueurs > li').remove();	
      for (var i=0; i<json.length; i++) 
      {
    					var retour = json[i];
    					var id = retour.id;   
    					var joueur = retour.jo;    		
    					var nombre = retour.no;   	  
              var moyenne = retour.mo;  
              var flash = retour.fl; 
              if (moyenne > 75) {
              var gagnant=" gagnant"
              }
              else
              {
              var gagnant=""
              }


   					
              $("#ulResultatsJoueurs").append('<li><a href="" class="boutonDetailResultats '+ gagnant+'" id=idj_'+id+' data-rel="dialog" data-transition="slideup">' + joueur + '&nbsp;(' + moyenne + ' %)'  +'<span class="ui-li-count rflash'+flash+'">&nbsp;'+nombre+'&nbsp;</span></a></li>');
                         								
 			}						
			$("#ulResultatsJoueurs").listview("refresh");  
    }	
    
    function onSuccessConnexion(json, status)
    { 
      $.mobile.loading('hide');
      if (siRetourErreur(json)===true)  return 1;
      var retour = json[0];
      if (retour != "1")    $("#notificationConnexion").text("Probleme de connexion a votre compte");
     else
      { 
        construitPageAccueil();
       $.mobile.changePage("#pageAccueil");
      }
    }
		
    function onSuccessValideProno(json, status)
    {   
      $.mobile.loading('hide');
      if (siRetourErreur(json)===true)  return 1;
      if (json[0] != "1") alert ("Probl&egrave;me de sauvegarde de vos pronostics");
      else                alert ("Vos pronostics ont été sauvegardés");
    }
				
    function onSuccessValideResultats(json, status)
    {   
      $.mobile.loading('hide');
      if (siRetourErreur(json)===true)  return 1;
      if (json[0] != "1") alert ("Probl&egrave;me de sauvegarde des résultats");
      else                alert ("Les résultats ont été sauvegardés");
    }
		
    function onSuccessDeconnexion(json, status)
    {                        
      $.mobile.loading('hide');
      if (siRetourErreur(json)===true)  return 1;
      var retour = json[0];
      json = $.trim(json);
      if (json == "0")
      {
         $("#notificationConnexion").text("Probl&egrave;me de connexion à votre compte");
      } 
      else
      {     
          construitPageAccueil();
          $.mobile.changePage("#pageAccueil");
      }
    }
         
    function siErreur (xhr, textStatus, errorThrown)
    {   
        $.mobile.loading('hide');
        if(textStatus=="timeout")         alert("Connexion Internet trop lente.\n");
        else if(errorThrown=="Not Found") alert("Script introuvable.\n");
        else alert("Erreur : " + textStatus);
    }   
    function siRetourErreur(data) 
    {
      if (data == null)   
      {
        alert("Problème de recuperation de vos informations");
        return true;
      } 
      var err = data[0].err;
      if (err && err.length>1) 
      {
            alert(err);
            return true;
      }     
      return false;
    }


    </script>
    	
    <script src="scripts/jquery.mobile-1.4.2.min.js"></script>

 <link rel="stylesheet" type="text/css" media="screen" title="Style de l'utilisateur" href="style/monls.css" />

	<!-- prevent cache ?????????? TODO -->
	<meta http-equiv="CACHE-CONTROL" content="NO-CACHE" />
	<meta http-equiv="EXPIRES" content="Mon, 01 Jan 2000 00:00:01 GMT" />	    
    
    <script type="text/javascript"> 	
	
  		$(document).ready(function() {	
  			$.mobile.loading('show');		
  			construitPageAccueil();			  
  		  $.mobile.loading('hide');	
  		}); 
  		$(document).on('click', '#boutonConnexion',function(event) {	
  			event.preventDefault();
  			actionConnexion();
  		});
  		$(document).on('click', '#boutonValideProno',function(event) {	
  			event.preventDefault();
  			actionValideProno();
  		});
  		$(document).on('click', '#boutonValideResultats',function(event) {	
  			event.preventDefault();
  			actionValideResultats();
  		});
      $(document).on('click', '#boutonMesResultats',function(event) { 
        event.preventDefault();
        actionMesResultats();
      });
      $(document).on('click', '.boutonDetailResultats',function(event) { 
        event.preventDefault();
        var tmp_id_joueur = this.id.replace("idj_","");
       actionJoueurResultats(tmp_id_joueur);
      });
  		$(document).on('click', '#boutonResultatsJoueurs',function(event) {	
  			event.preventDefault();
  			actionResultatsJoueurs();
  		});
  		$(document).on('click', '#boutonClassement',function(event) {	
  			event.preventDefault();
  			actionClassement();
  		});
  		$(document).on('click', '#boutonGain',  function(event) {	
  			event.preventDefault();
  			actionGain();
  		});
  		$(document).on('click', '#boutonProchainJeu',function(event) {	
  			event.preventDefault();
  			actionProchainJeu();
  		});
  		$(document).on('click', '#boutonSaisieResultats',function(event) {	
  			event.preventDefault();
  			actionSaisieResultats();
  		});
  		$(document).on('click', '#boutonDeconnexion',function(event) {	
  			event.preventDefault();
  			actionDeconnexion();
  		});
      $(document).on('click', '#boutonChat',  function(event) { 
        event.preventDefault();
        actionChat();
      });

$( document ).on( "pagebeforechange" , function(e, data) {
  var actualPageId = data.toPage ? data.toPage[0].id : "";
  var prevPage = $.mobile.pageContainer.pagecontainer("getActivePage");
  var prevPageId = prevPage[0].id;
  if(prevPageId == "pageChat" && actualPageId == "pageAccueil" ) {
       actionDestroyChat();
  }
});


	</script>
</head> 