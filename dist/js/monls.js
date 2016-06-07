(function($) {
	'use strict';
		
	/**
	 * Initialisation de l'application 
	 * 
	 */
	function initApplication() {
		toastr.options = {
		  "closeButton": false,
		  "debug": false,
		  "newestOnTop": false,
		  "progressBar": false,
		  "positionClass": "toast-top-center",
		  "preventDuplicates": false,
		  "onclick": null,
		  "showDuration": "300",
		  "hideDuration": "1000",
		  "timeOut": "5000",
		  "extendedTimeOut": "1000",
		  "showEasing": "swing",
		  "hideEasing": "linear",
		  "showMethod": "fadeIn",
		  "hideMethod": "fadeOut"
		}

		// Bug Safari sur iphone : 
		//http://stackoverflow.com/questions/2898740/iphone-safari-web-app-opens-links-in-new-window
		$("a").click(function (event) {
		    event.preventDefault();
	    	window.location = $(this).attr("href");
		});
	};

	// Scroll vers une ancre
	function scrollToAnchor(id){
	    var aTag = $(id);
	    $('html,body').animate({scrollTop: aTag.offset().top},'slow');
	};

	// Indice 
	function calculIndice() {

        // Calcul l'indice
        var indice=0;
        var cpt=0;
		for (var numLigne = 0; numLigne <= 15; numLigne++) {
	        if ($('#btn'+numLigne+'1').hasClass('btn-primary') ) 
        	{
        		cpt++;
		     	indice=indice+parseFloat($('#ind'+numLigne+'1').text().replace(",","."));
		    }
	        if ($('#btn'+numLigne+'N').hasClass('btn-primary') ) 
        	{
        		cpt++;
		     	indice=indice+parseFloat($('#ind'+numLigne+'N').text().replace(",","."));
		    }
	        if ($('#btn'+numLigne+'2').hasClass('btn-primary') ) 
        	{
        		cpt++;
		     	indice=indice+parseFloat($('#ind'+numLigne+'2').text().replace(",","."));
		    }
		}
		var moyenne=indice/cpt;
		indice=(100-moyenne)/10;
		indice=Math.round(indice * 100) / 100
		if (indice >= 0) {
	        $("#indiceCourant").html(" - Votre indice de gain : <strong>" +indice+"<strong>");
		} else {
	        $("#indiceCourant").text("");
	    }		
	}

	// Flash les matchs 
	function flashMatchs(ligneMax) {
		// Toutes les lignes sont remises à zéros (boutons et indicateurs déselectionnés)
		for (var numLigne = 1; numLigne <= 15; numLigne++) {
			// Pour cette ligne, tous les indicateurs visibles
			$('#ind'+numLigne+'1').removeClass("deselection");
			$('#ind'+numLigne+'N').removeClass("deselection");
			$('#ind'+numLigne+'2').removeClass("deselection");
			// Pour cette lignes, tous les boutons désélectionnés
			$('#btn'+numLigne+'1').removeClass("btn-primary").addClass("btn-default");
			$('#btn'+numLigne+'N').removeClass("btn-primary").addClass("btn-default");
			$('#btn'+numLigne+'2').removeClass("btn-primary").addClass("btn-default");
		}
		for (var numLigne = 1; numLigne <= ligneMax; numLigne++) {
			var valRandom = getRandomInt(1,3);
		    if (valRandom=="1") {
		    	$('#btn'+numLigne+'1').removeClass("btn-default").addClass("btn-primary");
		        $('#ind'+numLigne+'N').addClass("deselection");
		        $('#ind'+numLigne+'2').addClass("deselection");
		    } 
		    else if (valRandom=="2") {
		    	$('#btn'+numLigne+'N').removeClass("btn-default").addClass("btn-primary");
		        $('#ind'+numLigne+'1').addClass("deselection");
		        $('#ind'+numLigne+'2').addClass("deselection");
		    } 
		    else if (valRandom=="3") {
		    	$('#btn'+numLigne+'2').removeClass("btn-default").addClass("btn-primary");
		        $('#ind'+numLigne+'N').addClass("deselection");
		        $('#ind'+numLigne+'1').addClass("deselection");
		    } 
		}
	};

	// On renvoie un entier aléatoire entre une valeur min (incluse)
	// et une valeur max (exclue).
	// Attention : si on utilisait Math.round(), on aurait une distribution
	// non uniforme !
	function getRandomInt(min, max) {
	  return Math.floor(Math.random() * (max - min +1)) + min;
	};

	/**
	 * Ouverture d'une url dans un nouveau onglet
	 */
	function OpenInNewTab(url) {
		var win = window.open(url, '_blank');
		win.focus();
	};
	
	/**
	 * Vérification de la saisie 
	 */
	function verificationSaisie() {
		var retour =true;

		// Regle 1 : Les 7 premiers matchs ont toujours un prono
		for (var numLigne = 1; numLigne <= 7; numLigne++) {
			if (!($('#btn'+numLigne+'1').hasClass('btn-primary')||
				$('#btn'+numLigne+'N').hasClass('btn-primary')||
				$('#btn'+numLigne+'2').hasClass('btn-primary'))) {
				retour=false;
				break;
			}
		}
		// Regle 2 : si le 8 eme a un prono, Les 7 suivants en ont un aussi
		if (($('#btn81').hasClass('btn-primary')||
			$('#btn8N').hasClass('btn-primary')||
			$('#btn82').hasClass('btn-primary'))) {
			for (var numLigne = 8; numLigne <= 14; numLigne++) {
				if (!($('#btn'+numLigne+'1').hasClass('btn-primary')||
					$('#btn'+numLigne+'N').hasClass('btn-primary')||
					$('#btn'+numLigne+'2').hasClass('btn-primary'))) {
					retour=false;
					break;
				}
			}
			// Et même le 15 eme si il existe 
			if($('#btn151').length != 0) {
				var numLigne = 15; 
				if (!($('#btn'+numLigne+'1').hasClass('btn-primary')||
					$('#btn'+numLigne+'N').hasClass('btn-primary')||
					$('#btn'+numLigne+'2').hasClass('btn-primary'))) {
					retour=false;
				}
			}
		} else {
			// Regle 3 : si le 8 eme n'a pas de prono, Les 7 suivants en ont pas non plus
			for (var numLigne = 8; numLigne <= 14; numLigne++) {
				if (($('#btn'+numLigne+'1').hasClass('btn-primary')||
					$('#btn'+numLigne+'N').hasClass('btn-primary')||
					$('#btn'+numLigne+'2').hasClass('btn-primary'))) {
					retour=false;
					break;
				}
			}
			// Et même le 15 eme si il existe 
			if($('#btn151').length != 0) {
				var numLigne = 15; 
				if (($('#btn'+numLigne+'1').hasClass('btn-primary')||
					$('#btn'+numLigne+'N').hasClass('btn-primary')||
					$('#btn'+numLigne+'2').hasClass('btn-primary'))) {
					retour=false;
				}
			}
		}

		if (retour==false) {
			toastr.error("Saisie incomplète", "Monls");
		return false;
		}				
		return true;
	};


	
	
	/**
	 * Sauvegarde de la BD
	 */
	function sauveDB(m) {
		$.ajax({
			cache: false,
			data: {	mail : m },
			url : "scripts/sauveDataBase.php",
			success : function( msg, status,xhr ) {
				var p = msg.resultat;
				var com = msg.commentaire;
				if (p!=true) {
					toastr.error(com, "Monls");
				} else {
					toastr.success("Le mail a été correctement envoyé", "Monls");
				}				
			},
			error : function( msg, status,xhr ) {
				toastr.error(msg + "("+status+")", "Monls");
			}
		});
	};

	/**
	 * Appel pour bloquer/débloquer un jeu
	 */
	function flashLesJoueurs() {
		toastr.options = {
		  "positionClass": "toast-bottom-center"
		}
		$.ajax({
			cache: false,
			url : "scripts/flashJoueurs.php",
			success : function( msg, status,xhr ) {
				var p = msg.resultat;
				var com = msg.commentaire;
				var er = msg.erreur;
				if (p!=true) {
					toastr.error(com + "("+p+").", "Monls");
				} else {
					if (er=="0") {
						toastr.success(com, "Monls");
						// Flash ok => Raffraichissement de la page
						window.location.reload();
					} else {
						toastr.warning(com + " et "+er+" erreur(s)", "Monls");
					}
				}
					
			},
			error : function( msg, status,xhr ) {
				toastr.error(msg + "("+status+")", "Monls");
			}
		});
	};




	/**
	 * Appel  pour bloquer/débloquer un jeu
	 */
	function setBlocage(t) {

		toastr.options = {
		  "positionClass": "toast-bottom-center"
		}
		$.ajax({
			cache: false,
			data: {	type : t },
			url : "scripts/blocageJeu.php",
			success : function( msg, status,xhr ) {
				var p = msg.resultat;
				if (p!=true) {
					toastr.error(p, "Monls");
				} else {
					if (t=="B") {
						$('#libelleSaisie').html('<i class="fa fa-edit fa-fw"></i>Saisir résultat');
						$('#deblocageJeu').show();
						$('#blocageJeu').hide();
						$('.impressionJeu').show();
						$('.impressionJeuDivider').show();
					} else {
						$('#libelleSaisie').html('<i class="fa fa-edit fa-fw"></i>Prochain Jeu');	
						$('#blocageJeu').show();
						$('#deblocageJeu').hide();
						$('.impressionJeu').hide();
						$('.impressionJeuDivider').hide();
					}
					if (t=="B") {
						toastr.success("Le jeu est bloqué", "Monls");
					} else {
						toastr.success("Le jeu est débloqué", "Monls");
					}
					// Blocage ok => Raffraichissement de la page
					//window.location.reload();
					window.location.href="index.php"
				}
					
			},
			error : function( msg, status,xhr ) {
				toastr.error(msg + "("+status+")", "Monls");
			}
		});
	};


	/**
	 * Appel pour mettre à jour une saisie d'un résultat ou d'un pronostic
	 */
	function setSaisie(t, l, i) {

		toastr.options = {
		  "positionClass": "toast-top-center"
		};

		$.ajax({
			cache: false,
			data: {	type : t, 
					param: l,
					idjeu : i
				},
			url : "scripts/saisie.php",
			success : function( msg, status,xhr ) {
				var p = msg.resultat;
				if (p!=true) {
					toastr.error("Echec de la mise à jour : "+p, "Monls");
				} else {
					// Saisie du résultat : Mise à jour du classement
					if (t=="R") {
						toastr.success("Saisie enregistrée.", "Monls");
						$.ajax({
							cache: false,
							url : "scripts/recalcul_classement_general.php",
							error : function( msg, status,xhr ) {
								toastr["error"]("Echec de mise à jour du classement", "Monls");
							}
						});
					}

					// Saisie des pronostics : Affichage des indices
					if (t=="P") {
						toastr.options = {
			  			  "timeOut": "6000",
			  			  "positionClass": "toast-top-center"
						};
						toastr.success("Saisie enregistrée.<br> Votre indice de gain est de <strong>"+msg.indice+"</strong>", "Monls");
						
						$.ajax({
							cache: false,
							url : "scripts/estimationIndice.php",
							success : function( msg, status,xhr ) {
								if (typeof msg.resultat !== 'undefined' && msg.resultat!=true) {
									toastr.error(msg.commentaire, "Monls");
								} else {
									$( '#zoneEstimationRapports' ).show();
									$( '#tbodyEstimationRapports7' ).empty();
									$( '#tbodyEstimationRapports15' ).empty();

									var jsonData = JSON.parse(JSON.stringify(msg));
									// DI Greg Lafforgue 10/12/2015
									//L’estimation des gains ne fonctionnait pas avec une grille à 7.
									//if (jsonData.length==2) {
									if (jsonData.length==1 || jsonData.length==2) {
									
										var i=0;
									    $( '#titreEstimationRapports7' ).text(jsonData[i].titre);
							    		for (var j = 0; j < jsonData[i].rangs.length; j++) {
									    	var rang = jsonData[i].rangs[j];
									    	var rapport = jsonData[i].rapports[j];
									    	if (rapport !="-") {
									    		rapport=rapport+'&nbsp;&euro;';
									    	}
									    	$('#tbodyEstimationRapports7').append('<tr class="text-center"><td>'+rang+'</td><td>'+rapport+'</td></tr>');
							    		}
							    		i=1;
									    $( '#titreEstimationRapports15' ).text(jsonData[i].titre);
							    		for (var j = 0; j < jsonData[i].rangs.length; j++) {
									    	var rang = jsonData[i].rangs[j];
									    	var rapport = jsonData[i].rapports[j];
									    	if (rapport !="-") {
									    		rapport=rapport+'&nbsp;&euro;';
									    	}
									    	$('#tbodyEstimationRapports15').append('<tr class="text-center"><td>'+rang+'</td><td>'+rapport+'</td></tr>');
							    		}
										scrollToAnchor("#idBaseDePageSaisie");
									}

								}
							},
							error : function( msg, status,xhr ) {
								console.log('Echec complet');
							}
						});
					}
				}
			},
			error : function( msg, status,xhr ) {
				console.log('Echec complet');
			}
		});
	};


	/**
	 * Appel pour demander la connexion d'un joueur
	 */
	function getAuthentification( l, p, c ) {

		$.ajax({
			cache: false,
			data: {	login: l,
					password: p,
					auto: c
				},
			url : "scripts/connexion.php",
			success : function( msg, status,xhr ) {
				var p = msg.error;
				if (p) {
					$('#loginMessage').text(p);
				} else {
					console.log("Connexion ok");
					window.location.replace("index.php");
				}
			},
			error : function( msg, status,xhr ) {
				$('#loginMessage').text(xhr+" ("+msg.status+")");
			}
		});
	};


	/**
	* Demande d'impression d'un jeu
	*/
  	$('.impressionJeu').click( function(e) {
		OpenInNewTab("imprimerPronostics.php?page="+$(this).data("num"));
	});


	/**
	* Demande de connexion d'un joueur
	*/
  	$('.form-signin').submit( function(e) {
		e.preventDefault();
		var uCode = $( '#efLoginConnexion' ).val().toUpperCase();
		var password = $('#efPassWordConnexion').val().toUpperCase();
		var chkBox = $('#chkAuto').is(':checked');
		if (uCode && password) {
			getAuthentification( uCode, password, (chkBox ? "T" : ""));
		}
	});


	/**
	* Demande de déconnexion d'un joueur
	*/
  	$('#btnAnnulerConnexion').click( function(e) {
		e.preventDefault();
		console.log("Annulation de connexion");
        document.location.href="index.php";
	});


	/**
	* Demande de blocage d'un jeu
	*/
  	$('#blocageJeu').click( function(e) {
		e.preventDefault();
		console.log("Blocage du jeu");
		setBlocage("B");
	});


	/**
	* Demande de déblocage d'un jeu
	*/
  	$('#deblocageJeu').click( function(e) {
		e.preventDefault();
		console.log("Délocage du jeu");
		setBlocage("D");
	});

	/**
	* Flash les joueurs
	*/
  	$('#flashJoueurs').click( function(e) {
		e.preventDefault();
		console.log("Flash les joueurs");
		flashLesJoueurs();
	});



	/**
	* Sauve les rapports pour le dernier jeu 
	*/
  	 $(document).on( "click", ".btnEnregNouvRapport", function(e) {
		e.preventDefault();
		var chaineRapport = $(this).attr("data-val"), 
			typeRapport = $("#idJeuTypeRapport").val();
		$.ajax({
			type: "POST",
			cache: false,
			data: {	op : 'M', chaine : chaineRapport, type : typeRapport },
			url : "scripts/adminRapport.php",
			success : function( msg, status,xhr ) {
				var p = msg.resultat;
				if (p==true) {
					toastr.success("Modification enregistrée", "Monls");
					}	
					else {
					toastr.error(p, "Monls");
					}			
			},
			error : function( msg, status,xhr ) {
				toastr.error(msg + "("+status+")", "Monls");
			}
		});  		
	});

	/**
	* Recherche Rapport en base pour un jeu donné
	*/
  	$('.rechercheRapport').click( function(e) {
		e.preventDefault();
		$('#idDivNouveauRapport').hide();
		$('#idDivRapportExistant').show();						
		var idJeu = $(this).attr("data-id"),
			jeuCommentaire = $(this).attr("data-nom");
		$.ajax({
			cache: false,
			data: {	op : 'R', id : idJeu },
			url : "scripts/adminRapport.php",
			success : function( msg, status,xhr ) {
				var p = msg.resultat;
				if (p==true) {
						$('#nomJeuDetailRapport').text(jeuCommentaire);
						$('#bodyTableRapports7 tr').remove();
						$('#bodyTableRapports15 tr').remove();
						$.each(msg.rapport, function(i, item) {
							if (item.type=="7") {
	    						$('#bodyTableRapports7').append('<tr><td>'+item.rang+'</td><td>'+item.nombre+'</td><td>'+item.rapport+'</td></tr>');
							} else {
    							$('#bodyTableRapports15').append('<tr><td>'+item.rang+'</td><td>'+item.nombre+'</td><td>'+item.rapport+'</td></tr>');								
							}
						});
						$('#myRapportPopup').modal();
					}				
			},
			error : function( msg, status,xhr ) {
				toastr.error(msg + "("+status+")", "Monls");
			}
		});  		
	});


	/**
	 * Lecture des rapports sur le site de pronosoft
	 */
	function lectureRapports(t) {

		toastr.options = {
		  "positionClass": "toast-bottom-center"
		}
		$('#myPleaseWait').modal('show');
		$.ajax({
			cache: false,
			data: {	type : t },
			url : "scripts/lectureRapports.php",
			success : function( msg, status,xhr ) {
				var p = msg.resultat;
				if (p!=true) {
					toastr.error("Impossible de lire les rapports", "Monls");
				} else {
					$.each(msg.rapports, function(i, item) {
						var $chaine="<br><p><button type='button' data-val='"+JSON.stringify(item.rapports)+"' class='btn btn-primary btnEnregNouvRapport'>Choisir "+item.titre+"</button></p><ul>";
						$.each(item.rapports, function(i, item2) {
							var $nbg =item2.gagnant;
							var $temp=" gagnants";
							if ($nbg<=1) {
								$temp=" gagnant";
							}
	    					$chaine=$chaine+"<li>Rang "+item2.rang+", "+$nbg+$temp+" de "+item2.rapport+"</li>";
						});	
						$chaine=$chaine+"</ul>";
						$("#idDivNouveauRapport").append($chaine);
					});
				}
				// Cache la fenêtre d'attente, montre la fenêtre avec les rapports
				$('#myPleaseWait').modal('hide');	
				$('#myRapportPopup').modal();
			},
			error : function( msg, status,xhr ) {
				$('#myPleaseWait').modal('hide');	
				toastr.error("Impossible de lire les rapports", "Monls");
			}
		});
	};

	/**
	* 
	Nouveau Rapport à 7
	*/
  	$('#nouveauRapport7').click( function(e) {
		e.preventDefault();
		lectureRapports(7);
		$('#rapportTitreOperation').text("Nouveaux Rapports à 7 pour le dernier jeu");		
		$('#idDivNouveauRapport').empty().show();
		$('#idDivRapportExistant').hide();
		$('#idJeuTypeRapport').val("7");
	});
	/**
	* 
	Nouveau Rapport à 15
	*/
  	$('#nouveauRapport15').click( function(e) {
		e.preventDefault();
		lectureRapports(15);
		$('#rapportTitreOperation').text("Nouveaux Rapports à 15 pour le dernier jeu");		
		$('#idDivNouveauRapport').empty().show();
		$('#idDivRapportExistant').hide();
		$('#idJeuTypeRapport').val("15");
	});





	/**
	* Sauver Saison
	*/
	$(function() {
	  	$('#sauverSaison').click( function(e) {
			e.preventDefault();
			var idSaison = $('#idSaison').val(), 
				nom=$('#nomSaison').val(), 
				commentaire=$('#commentaireSaison').val();
			$.ajax({
				type: "POST",
				cache: false,
				data: {	op : 'M', id : idSaison, nom : nom, commentaire : commentaire },
				url : "scripts/adminSaison.php",
				success : function( msg, status,xhr ) {
					var p = msg.resultat;
					if (p==true) {
						toastr.success("Modification enregistrée", "Monls");
						}	
						else {
						toastr.error(p, "Monls");
						}			
				},
				error : function( msg, status,xhr ) {
					toastr.error(msg + "("+status+")", "Monls");
				}
			});  		
		});
	});
	/**
	* Recherche Saison
	*/
  	$('.rechercheSaison').click( function(e) {
		e.preventDefault();
		var idSaison = $(this).attr("data-id");
		$.ajax({
			cache: false,
			data: {	op : 'R', id : idSaison },
			url : "scripts/adminSaison.php",
			success : function( msg, status,xhr ) {
				var p = msg.resultat;
				if (p==true) {
						$('#idSaison').val(msg.id);
						$('#nomSaison').val(msg.nom);
						$('#commentaireSaison').val(msg.commentaire);
						$('#mySaisonPopup').modal();
					}				
			},
			error : function( msg, status,xhr ) {
				toastr.error(msg + "("+status+")", "Monls");
			}
		});  		
	});
	/**
	* 
	Nouvelle Saison
	*/
  	$('#nouvelleSaison').click( function(e) {
		e.preventDefault();
		$('#saisonTitreOperation').text("Nouvelle Saison");
		$('#nomSaison').val("");
		$('#commentaireSaison').val("");
		$('#idSaison').val("");
		$('#mySaisonPopup').modal();
	});



	/**
	* Sauver Caisse
	*/
	$(function() {
	  	$('#sauverCaisse').click( function(e) {
			e.preventDefault();
			var idCaisse = $('#idCaisse').val(), 
				libelle=$('#libelleCaisse').val(), 
				debit=$('#debitCaisse').val(),
				credit=$('#creditCaisse').val();

			$.ajax({
				type: "POST",
				cache: false,
				data: {	op : 'M', id : idCaisse, libelle : libelle, debit : debit, credit : credit },
				url : "scripts/adminCaisse.php",
				success : function( msg, status,xhr ) {
					var p = msg.resultat;
					if (p==true) {
						toastr.success("Modification enregistrée", "Monls");
						}	
						else {
						toastr.error(p, "Monls");
						}			
				},
				error : function( msg, status,xhr ) {
					toastr.error(msg + "("+status+")", "Monls");
				}
			});  		
		});
	});
	/**
	* Recherche Caisse
	*/
  	$('.rechercheCaisse').click( function(e) {
		e.preventDefault();
		var idCaisse = $(this).attr("data-id");
		$.ajax({
			cache: false,
			data: {	op : 'R', id : idCaisse },
			url : "scripts/adminCaisse.php",
			success : function( msg, status,xhr ) {
				var p = msg.resultat;
				if (p==true) {
						$('#idCaisse').val(msg.id);
						$('#libelleCaisse').val(msg.libelle);
						if (msg.debit>0) 	$('#debitCaisse').val(msg.debit); else $('#debitCaisse').val("");
						if (msg.credit>0) 	$('#creditCaisse').val(msg.credit); else $('#creditCaisse').val("");
						$('#myCaissePopup').modal();
					}				
			},
			error : function( msg, status,xhr ) {
				toastr.error(msg + "("+status+")", "Monls");
			}
		});  		
	});
	/**
	* Nouvelle Ligne en caisse pour la saison courante
	*/
  	$('#nouvelleLigneEnCaisse').click( function(e) {
		e.preventDefault();
		$('#caisseTitreOperation').text("Nouvelle Ligne en caisse");
		$('#libelleCaisse').val("");
		$('#debitCaisse').val("");
		$('#creditCaisse').val("");
		$('#myCaissePopup').modal();
	});


	/**
	* Sauver Gain
	*/
	$(function() {
	  	$('#sauverGain').click( function(e) {
			e.preventDefault();
			var idGain=$('#idGain').val(), 
				idJoueur=$('#joueur_id').children(":selected").attr("value"), 
				sommeGain=$('#sommeGain').val();

			$.ajax({
				type: "POST",
				cache: false,
				data: {	op : 'M', id : idGain, idJoueur : idJoueur, sommeGain : sommeGain },
				url : "scripts/adminGain.php",
				success : function( msg, status,xhr ) {
					var p = msg.resultat;
					if (p==true) {
						toastr.success("Modification enregistrée", "Monls");
						}	
					else {
						toastr.error(p, "Monls");
						}			
				},
				error : function( msg, status,xhr ) {
					toastr.error(msg + "("+status+")", "Monls");
				}
			});  		
		});
	});
	/**
	* Recherche Gain
	*/
  	$('.rechercheGain').click( function(e) {
		e.preventDefault();
		var idGain = $(this).attr("data-id");
		$.ajax({
			cache: false,
			data: {	op : 'R', id : idGain },
			url : "scripts/adminGain.php",
			success : function( msg, status,xhr ) {
				var p = msg.resultat;
				if (p==true) {
						$('#idGain').val(msg.id);
						$('#jid' + msg.jid).prop('selected', true);
						$('#sommeGain').val(msg.somme);
						$('#myGainPopup').modal();
					}				
			},
			error : function( msg, status,xhr ) {
				toastr.error(msg + "("+status+")", "Monls");
			}
		});  		
	});
	/**
	* Nouveau gain pour la saison courante
	*/
  	$('#nouveauGain').click( function(e) {
		e.preventDefault();
		$('#gainTitreOperation').text("Nouveau gain");
		$('#sommeGain').val("");
		$('#myGainPopup').modal();
	});





	/**
	* Click sur le bouton flash 7  de la page de saisie des pronos ou des résultats
	*/
  	$('.btnflash7').click( function(e) {
		e.preventDefault();
		flashMatchs(7);
		calculIndice();       
	});




	/**
	* Click sur le bouton flash 7  de la page de saisie des pronos ou des résultats
	*/
  	$('.btnflash15').click( function(e) {
		e.preventDefault();
		flashMatchs(15);
		calculIndice();       
	});



	/**
	* Click sur le bouton flash 7  de la page de saisie des pronos ou des résultats
	*/
  	$('.btnflashEffacer').click( function(e) {
		e.preventDefault();
		flashMatchs(0);
		calculIndice();      
	});

	/**
	* Sauvegarde de la BD
	*/
  	$('#btnSauveDB').click( function(e) {
		e.preventDefault();
		var mail = $("#mailDestinatairesSauveDB").val();
		sauveDB(mail);
	});




	/**
	* Click sur une cellule de la page de saisie des pronos ou des résultats
	*/
  	$('.btnSaisie').click( function(e) {
		e.preventDefault();
		var $this = $(this),
           idBouton = $this.attr("id"),
           numLigne = idBouton.substring(3,idBouton.length-1); // Numéro de 1 à 15 donc
        console.log(idBouton + " = " + e.target.id + " Donne ligne ["+numLigne+ "].");
        if ($this.hasClass('btn-primary')) {
        	// Click sur le bouton Coché => Bouton repasse en fond blanc
 			$this.removeClass("btn-primary").addClass("btn-default");
        } else {
        	// Click sur le bouton décoché => Bouton passe en fond bleu
 			$this.removeClass("btn-default").addClass("btn-primary");
        }
        // Grise ou pas les indicateurs
		$('#ind'+numLigne+'1').removeClass("deselection");
		$('#ind'+numLigne+'N').removeClass("deselection");
		$('#ind'+numLigne+'2').removeClass("deselection");
        if ($('#btn'+numLigne+'1').hasClass('btn-primary') ||
        	$('#btn'+numLigne+'N').hasClass('btn-primary') ||
        	$('#btn'+numLigne+'2').hasClass('btn-primary') ) 
        	{
		        if (!($('#btn'+numLigne+'1').hasClass('btn-primary'))) {
		         	$('#ind'+numLigne+'1').addClass("deselection");
		        }
		        if (!($('#btn'+numLigne+'N').hasClass('btn-primary'))) {
		         	$('#ind'+numLigne+'N').addClass("deselection");
		         }
		        if (!($('#btn'+numLigne+'2').hasClass('btn-primary'))) {
		         	$('#ind'+numLigne+'2').addClass("deselection");
		        }
        	}

        // Calcul de l'indice 
		calculIndice();       

	});


	/**
	* Bouton de validation de la page de saisie des pronos ou des résultats
	*/
  	$('#btnValider').click( function(e) {
		e.preventDefault();
		var type = $( '#typeOperation' ).text().toUpperCase();
		var id = $( '#idJeuSaisie' ).text().toUpperCase();
		if ( (type=="R") || (type=="P" &&verificationSaisie())) {
			var param='';
			/* Appel de la fct setSaisie avec les boutons ayant la classe primary (saisie donc) */
			$('.btnSaisie').each(function(i, obj) {
			    var $this = $(this);
			    if ($this.hasClass('btn-primary')) {
			    	param=param+":"+$this.attr("id");
			    }
			});
			setSaisie(type, param+":", id);		
		}
	});

	
	/**
	 * Initialisation de l'application dès que le DOM est chargé
	 */
	$(document).ready(initApplication);


})(jQuery);
