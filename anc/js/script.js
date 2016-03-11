$(document).ready(function(){
	
	// Run the init method on document ready:
	chat.init();
	
});

var chat = {
	
	// data holds variables for use in the class:
	data : {
		lastID 		: 0,
		noActivity	: 0
	},
	
	// Init binds event listeners and sets up timers:
	init : function(){


		// Converting the #chatLineHolder div into a jScrollPane,
		// and saving the plugin's API in chat.data:
		
		chat.data.jspAPI = $('#chatLineHolder').jScrollPane({
			verticalDragMinHeight: 12,
			verticalDragMaxHeight: 12
		}).data('jsp');
		
		// Variable working pour éviter plusieurs soumissions pour un même utilisateur
		var working = false;

		// Log/Delog une personne au chat
		$('.logButton').click(function(){
			if(working) return false;
			working = true;
			if ($(".logButton").hasClass("login")) {
				// Déconnexion
				$.tzPOST('login',$(this).serialize(),function(r) {
					working = false;
					if(r.error){
						chat.displayError(r.error);
					}
					else {
						chat.login(r.nom);
					}
				});
			}
			else
			{
				// Connexion
				// Using our tzPOST wrapper function
				$.tzPOST('logout',$(this).serialize(),function(r){
					working = false;
					if(r.error){
						chat.displayError(r.error);
					}
					else {
						chat.logout();
					}
				});
			}
			return false;
		});
		
		
		// Soumission d'un nouveau POST
		$('#submitForm').submit(function(){
			
			var text = $('#chatText').val();
			if(text.length == 0){
				return false;
			}
			
			if(working) return false;
			working = true;
			// 3 Variables : Date système, ID Temporaire à ce post, constitution du POST
			var d = new Date(),
				tempID = 't'+Math.round(Math.random()*1000000),
				params = {
					webchat_id	: tempID,
					nom			: chat.data.name,
					message		: text.replace(/</g,'&lt;').replace(/>/g,'&gt;'),
					ts 			: (d.getHours() < 10 ? '0' : '' ) + d.getHours()+':'+
								  (d.getMinutes() < 10 ? '0':'') + d.getMinutes()+':'+					  
								  (d.getSeconds() < 10 ? '0':'') + d.getSeconds()
				};

			// Ajout du post dans la liste sans attendre le retour du POST
			chat.addChatLine($.extend({},params));

			
			// POST AJAX pour soumettre le nouveau POST
			$.tzPOST('submitChat',$(this).serialize(),function(r){
				// Retour du POST 
				working = false;
				// Effacement du texte dans la zone de saisie
				$('#chatText').val('');
				// Suppression du post temporaire précédent
				$('div.chat-'+tempID).remove();
				// Changement de l'identifiant du post
				params['webchat_id'] = r.insertID;
				chat.addChatLine($.extend({},params));
			});
			
			return false;
		});
		
		// Checking whether the user is already logged (browser refresh)
		$.tzGET('checkLogged',function(r){
			if(r.logged){
				chat.login(r.nom);
			}

		});
		
		// Self executing timeout functions	
		(function getChatsTimeoutFunction(){
			chat.getChats(getChatsTimeoutFunction);
		})();
		
		(function getUsersTimeoutFunction(){
			chat.getUsers(getUsersTimeoutFunction);
		})();
		
	},
	
	// Cette méthode 'login' montre le formulaire des messages et donne le focus sur le texte
	login : function(nom){
		chat.data.name = nom;
		$('.logButton').text('Déconnexion').removeClass("login").addClass("logout");
		$('#submitForm').fadeIn();
		$('#chatText').focus();
	},
		
	// Cette méthode 'logout' cache le formulaire des messages
	logout : function(){
		$('.logButton').text('Connexion').removeClass("logout").addClass("login");
		$('#submitForm').fadeOut();
		$( "div[title='"+chat.data.name+"']").remove();
	},
	
	// The render method generates the HTML markup 
	// that is needed by the other methods:
	
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
		
		// A single array join is faster than
		// multiple concatenations
		
		return arr.join('');
		
	},
	
	// The addChatLine method ads a chat entry to the page
	
	addChatLine : function(params){
		
		var markup = chat.render('chatLine',params),
			exists = $('#chatLineHolder .chat-'+params.webchat_id);


		if(exists.length){
			exists.remove();
		}
		
		if(!chat.data.lastID){
			// If this is the first chat, remove the
			// paragraph saying there aren't any:
			
			$('#chatLineHolder p').remove();
		}
		
		// If this isn't a temporary chat:
		if(params.webchat_id && params.webchat_id.toString().charAt(0) != 't'){
			var previous = $('#chatLineHolder .chat-'+(+params.id - 1));
			if(previous.length){
				previous.after(markup);
			}
			else chat.data.jspAPI.getContentPane().append(markup);
		}
		else chat.data.jspAPI.getContentPane().append(markup);
		
		// As we added new content, we need to
		// reinitialise the jScrollPane plugin:
		
		chat.data.jspAPI.reinitialise();
		chat.data.jspAPI.scrollToBottom(true);
		
	},
	
	// This method requests the latest chats
	// (since lastID), and adds them to the page.
	
	getChats : function(callback){
		$.tzGET('getChats',{lastID: chat.data.lastID},function(r){
			
			for(var i=0;i<r.length;i++){
				chat.addChatLine(r[i]);
			}
			
			if(r.length){
				chat.data.noActivity = 0;
				chat.data.lastID = r[i-1].webchat_id;
			}
			else{
				// If no chats were received, increment
				// the noActivity counter.
				
				chat.data.noActivity++;
			}
			
			if(!chat.data.lastID){
				chat.data.jspAPI.getContentPane().html('<p class="noChats">Aucun post pour le moment</p>');
			}
			
			// Setting a timeout for the next request,
			// depending on the chat activity:
			
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
		
			setTimeout(callback,nextRequest);
		});
	},
	
	// Requesting a list with all the users.
	
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
	

	// This method displays an error message on the top of the page:
	
	displayError : function(msg){
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
	}
};

// Custom GET & POST wrappers:

$.tzPOST = function(action,data,callback){
	$.post('scripts/ajax-webchat.php?action='+action,data,callback,'json');
}

$.tzGET = function(action,data,callback){
	$.get('scripts/ajax-webchat.php?action='+action,data,callback,'json');
}

// A custom jQuery method for placeholder text:

$.fn.defaultText = function(value){
	
	var element = this.eq(0);
	element.data('defaultText',value);
	
	element.focus(function(){
		if(element.val() == value){
			element.val('').removeClass('defaultText');
		}
	}).blur(function(){
		if(element.val() == '' || element.val() == value){
			element.addClass('defaultText').val(value);
		}
	});
	
	return element.blur();
}