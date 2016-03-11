var chat = {

	// Variables utilisées dans cette classe
	data : {
		name		: '',
		timerGetChats : 0,
		cnx 		: false,
		lastID 		: 0,
		noActivity	: 0
	},
	
	// Init binds event listeners and sets up timers:
	init : function(name){
		console.log('CHAT : Méthode init');
		if (!name) return false;
		if (name==="") return false;
		// Sauvegarde du nom du joueur
		chat.data.name = name;
	    // Vidage du tableau
	    $('#chat_area > li').remove();	
	    // Cache la zone de saisie du chat
	    $('#chatText').textinput( "disable" );
	    $('#chatSendButton').addClass('ui-disabled');
		// Variable working pour éviter plusieurs soumissions pour un même utilisateur
		var working = false;

		// EVENEMENT 1 : Log/Delog une personne au chat
		$('#chatLogIn').click(function(){
			if(working) return false;
			working = true;
			if ($("#chatLogIn").hasClass("login")) {				
				chat.login();		
			}
			else
			{
				chat.logout();
			}
			working = false;
			return false;
		});
		
		
		// EVENEMENT 2 : Soumission d'un nouveau POST
		$('#chatSendButton').click(function(){
			var text = $('#chatText').val();
			console.log('CHAT : Méthode chatSendButton : ' + text);
			if(text.length == 0){
				return false;
			}
			
			if(working) return false;
			working = true;
			// 3 Variables : Date système, ID Temporaire à ce post, constitution du POST dans params
			var d = new Date(),
				tempID = 't'+Math.round(Math.random()*1000000),
				params = {
					webchat_id	: tempID,
					nom			: chat.data.name,
					message		: text.replace(/</g,'&lt;').replace(/>/g,'&gt;'),
					ts 			: (d.getHours() < 10 ? '0' : '' ) + d.getHours()+':'+
								  (d.getMinutes() < 10 ? '0':'') + d.getMinutes()+':'+					  
								  (d.getSeconds() < 10 ? '0':'') + d.getSeconds(),
					type 		: 'M'

				};

			// Ajout du post dans la liste sans attendre le retour du POST
			chat.addChatLine($.extend({},params));

			// POST AJAX pour soumettre le nouveau POST
			$.tzPOST('submitChat',"chatText="+params.message,function(r){
				if(r.error)
				{
					chat.displayError(r.error);
				}
				else 
				{
					// Retour du POST 
					working = false;
					// Effacement du texte dans la zone de saisie
					$('#chatText').val('');
					if (r.insertID) {
						// Suppression du post temporaire précédent
						$('#chat_area .chat-'+tempID).remove();
						// Changement de l'identifiant du post
						params['webchat_id'] = r.insertID;
						// Ajoute ce message dans la liste
						chat.addChatLine($.extend({},params));
					}
					else {
						// Echec de maj
						chat.displayError(r);
					}
				}
			});
			
			return false;
		});
		
		// Check si l'utilisateur est logged
/*		$.tzGET('checkLogged',function(r){
			if(r.logged){
				chat.login(r.nom);
			}

		});
		*/

		// Lance la fonction de recherche
		(function getChatsTimeoutFunction(){
			chat.getChats(getChatsTimeoutFunction);
		})();
		
	},
	
	// Cette méthode 'login' montre le formulaire des messages et donne le focus sur le texte
	login : function(){
		console.log('CHAT : Méthode login');
		// Connexion
		$.tzPOST('login',$(this).serialize(),function(r) {
			if(r.error)
			{
				chat.displayError(r.error);
			}
			else 
			{
				// Nom de l'utilisateur connecté
				chat.data.name = r.nom;
				// Change le text du bouton de connexion/déconnexion
				$('#chatLogIn').text('Déconnexion').removeClass("login").addClass("logout");
				// Le formulaire apparait
	      $('#chatText').textinput( "enable" );
	      $('#chatSendButton').removeClass('ui-disabled');
				// Le texte a le focus
				//$('#chatText').focus();
				// L'utilisateur est connecté 
				chat.data.cnx = true;
				// Surtout ne pas l'écrire dans la liste, cela va être fait automatiquement lors du prochain appel serveur pour avoir les derniers messages
			}
		});
	},
		
	// Cette méthode 'logout' cache le formulaire des messages
	logout : function(){
		// Si connecté, on déconnecte
		if (chat.data.cnx == true) {
			console.log('CHAT : Méthode logout');
			chat.data.cnx = false;
			// Déconnexion
			$.tzPOST('logout',$(this).serialize(),function(r){
				if(r.error){
					chat.displayError(r.error);
				}
				else {
					// Change le texte du bouton de connexion/déconnexion
					$('#chatLogIn').text('Connexion').removeClass("logout").addClass("login");
					// Le formulaire disparait
	       $('#chatText').textinput( "disable" );
	         $('#chatSendButton').addClass('ui-disabled');
					// Surtout ne pas l'écrire dans la liste, cela va être fait automatiquement lors du prochain appel serveur pour avoir les derniers messages
				}
			});
		}
	},
	
	// La méthode ajoute une ligne dans la listview avec le code erreur
	displayError : function (error) {
		var d = new Date(),
			params = {
					nom			: error,
					ts 			: (d.getHours() < 10 ? '0' : '' ) + d.getHours()+':'+
								  (d.getMinutes() < 10 ? '0':'') + d.getMinutes()+':'+					  
								  (d.getSeconds() < 10 ? '0':'') + d.getSeconds(),
					type 		: 'E'
				};
		// Ajout de l'avis de déconnexion de l'utilisateur dans la liste
		chat.addChatLine($.extend({},params));

	},

	// La méthode retourne une ligne de la listview correctement formatté
	render : function(template,params){
		var arr = [];
		switch(params.type){
			case 'M':
				// Message
				arr = ['<li class="chat-',params.webchat_id,'">',
					params.nom,
					' : ',
					params.message,
					'<span class="ui-li-count">',
					params.ts,
					'</span></li>'];
				break;
			case 'C':
				// Connexion
				arr = ['<li class="chatConnecte">Connexion de ',
					params.nom,
					'<span class="ui-li-count">',
					params.ts,
					'</span></li>'];
				break;
			case 'D':
				// Déconnexion
				arr = ['<li class="chatDeconnecte">Déconnexion de ',
					params.nom,
					'<span class="ui-li-count">',
					params.ts,
					'</span></li>'];
				break;
			case 'E':
				// Déconnexion
				arr = ['<li class="chatErreur">Erreur : ',
					params.nom,
					'<span class="ui-li-count">',
					params.ts,
					'</span></li>'];
				break;
		}
		// Un seul tableau est plus rapide que de multiples concatenations
		return arr.join('');	
	},
	
	// La méthode addChatLine ajoute un message en haut de la page
	addChatLine : function(params){		
		var markup = chat.render('chatLine',params),
			exists = $('#chat_area .chat-'+params.webchat_id);

		if(exists.length){
			exists.remove();
		}
		
		// Message temporaire ? Faut un id, un type de message 'M' et une premiere lettre à 't'
		if(params.webchat_id  && params.type && params.type=='M' && params.webchat_id.toString().charAt(0) != 't'){
			var previous = $('#chat_area .chat-'+(+params.id - 1));
			if(previous.length) {
				previous.after(markup);
			}
			else {
				$('#chat_area').prepend(markup);
			}
		}
		else 
		{
			// Ajout en début de liste du message
			$('#chat_area').prepend(markup);
		}

		// Raffraichissement de la liste suite au chargment des chats
     	$("#chat_area").listview("refresh");  
	
	},
	
	// Cette méthode demande les derniers messages 
	// (depuis lastID), et les ajoute en haut de la page
	getChats : function(callback){
		$.tzGET('getChatsEtInformations',{lastID: chat.data.lastID},function(r){
			
			if(r.error)
			{
				chat.displayError(r.error);
				nextRequest = 15000;
			}
			else 
			{
				for(var i=0;i<r.length;i++){
					chat.addChatLine(r[i]);
				}

				if(r.length){
					// Evènements trouvés	
					chat.data.noActivity = 0;
					chat.data.lastID = r[i-1].webchat_id;
				}
				else{
					// Sinon on augmente le compteur d'inactivité.
					chat.data.noActivity++;
				}
				
				// Définition du temps d'attente avant le lancement de la prochaine requete,
				// Dépend de l'activité du chat
				var nextRequest = 1000;
				// 2 seconds
				if(chat.data.noActivity > 3){
					nextRequest = 2000;
				}
				if(chat.data.noActivity > 10){
					nextRequest = 5000;
				}
				// 15 seconds
				if(chat.data.noActivity > 20){
					nextRequest = 15000;
				}
			}
			// Rappelle la fonction dans x seconde
			chat.data.timerGetChats = setTimeout(callback,nextRequest);
		});
	},

	destroy : function() {
		// Déconnexion de l'utilisateur si connecté
		chat.logout();
		console.log('CHAT : Méthode Destroy');
		// Unbind des évènements
		$('#chatLogIn').unbind();
		$('#chatSendButton').unbind();
		// Initialisation des variables
		chat.data.noActivity=0;
		chat.data.lastID=0;
		// Suppression du timer
		if (chat.data.timerGetChats) {
			console.log('Fin du timeOut');
			clearTimeout(chat.data.timerGetChats);
		}
	}
	
};

// GET & POST wrappers :
$.tzPOST = function(action,data,callback){
	$.post('../scripts/ajax-webchat.php?action='+action,data,callback,'json');
}

$.tzGET = function(action,data,callback){
	$.get('../scripts/ajax-webchat.php?action='+action,data,callback,'json');
}

