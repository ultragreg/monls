$(document).ready(function(){
	// Lance le chat dès que document chargé
	chat.init();

});




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
	init : function(){
		// Sauvegarde du nom du joueur
		chat.data.name = $('#logUser').val();
		console.log('CHAT : Méthode init');
	    // Vidage du tableau
	    $('#chat_area > li').remove();	

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
						$('div.chat-'+tempID).remove();
						// Changement de l'identifiant du post
						params['webchat_id'] = r.insertID;
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
/*		
		// Checking whether the user is already logged (browser refresh)
		$.tzGET('checkLogged',function(r){
			if(r.logged){
				chat.login(r.nom);
			}

		});*/
		
		// Self executing timeout functions	
		(function getChatsTimeoutFunction(){
			chat.getChats(getChatsTimeoutFunction);
		})();
		
		(function getUsersTimeoutFunction(){
			chat.getUsers(getUsersTimeoutFunction);
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
				$('.logButton').text('Déconnexion').removeClass("login").addClass("logout");
				// Le formulaire apparait
				$('#submitForm').fadeIn();
				// Le texte a le focus
				$('#chatText').focus();
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
					$('.logButton').text('Connexion').removeClass("logout").addClass("login");
					// Le formulaire disparait
					$('#submitForm').fadeOut();
					$( "div[title='"+chat.data.name+"']").remove();
				}
			});
		}
	},
	
	
	// La méthode ajoute une ligne dans la listview avec le code erreur
	displayError : function (error) {
		var elem = $('<div>',{
			id		: 'chatErrorMessage',
			html	: msg
		});
		
		elem.click(function(){
			$(this).fadeOut(function(){
				$(this).remove();
			});
		});
		
		setTimeout(function(){
			elem.click();
		},5000);
		
		elem.hide().appendTo('body').slideDown();
	},

	// La méthode retourne une ligne de la listview correctement formatté
	render : function(template,params){
		var arr = [];
		switch(template){
			case 'chatLine':
				arr = [
					'<div class="chat chat-',params.webchat_id,' rounded"><span class="author">',params.nom,
					':</span><span class="text">',params.message,'</span><span class="time">',params.ts,'</span></div>'];
				break;
			
			case 'user':
				arr = [
					'<div class="user" title="',params.nom,'">',params.nom,'</div>'
				];
				break;
		}
		// Un seul tableau est plus rapide que de multiples concatenations
		return arr.join('');	
	},

	// La méthode addChatLine ajoute un message en haut de la page
	addChatLine : function(params){
		var markup = chat.render('chatLine',params),
			exists = $('#chat_area .chat-'+params.webchat_id);

		if(exists.length) {
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
		
	},
	
	// Cette méthode demande les derniers messages 
	// (depuis lastID), et les ajoute en haut de la page
	getChats : function(callback){
		$.tzGET('getChats',{lastID: chat.data.lastID},function(r){
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
	
	// Retourne la liste des utilisateurs
	getUsers : function(callback){
		$.tzGET('getUsers',function(r){
			
			var users = [];
			
			for(var i=0; i< r.length;i++){
				if(r[i]){
					users.push(chat.render('user',r[i]));
				}
			}
			
			var message = '';
			
			if(i<1){
				message = "Personne n'est en ligne";
			}
			else {
				message = i+' '+(i == 1 ? 'personne':'personnes')+' en ligne';
			}
			
			users.push('<p class="count">'+message+'</p>');
			
			$('#chatUsers').html(users.join(''));
			
			setTimeout(callback,15000);
		});
	},

	destroy : function() {
		// Si connecté, on déconnecte
		if (chat.data.cnx == true) {
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
	}
};


// GET & POST wrappers :
$.tzPOST = function(action,data,callback){
	$.post('scripts/ajax-webchat.php?action='+action,data,callback,'json');
}

$.tzGET = function(action,data,callback){
	$.get('scripts/ajax-webchat.php?action='+action,data,callback,'json');
}
