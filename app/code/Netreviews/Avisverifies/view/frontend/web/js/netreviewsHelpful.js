/*global define*/
define([
    'jquery'
], function($) {
    'use strict';

	var avHelpfulIdwebsite = document.getElementById('avisVerifiesIdwebsite').value;
	var avHelpfulURL = document.getElementById('avisverifiesHelpfulURL').value;
	var avHelpfulErrorMessage = document.getElementById('avisverifiesHelpfulErrorMsg').value;
	var avHelpfulSuccessMessage = document.getElementById('avisverifiesHelpfulSuccessMsg').value;
	var avhelpfulExec = false;
	var avInitialFingerPrint = '';
	var avHelpfulCookie = {};
		
		var avCallHelpfulWebservice = function(method,idProduct,vote,sign,fingerPrint) {
    // Si un vote existe déjà pour cet avis on récupère le fingerPrint existant
    var existingVote = getExistingVote(idProduct);
    if (typeof existingVote.fingerPrint != "undefined" && existingVote.fingerPrint != "") {
        fingerPrint = existingVote.fingerPrint;
    }
    // Appel au webservice
    var http = new XMLHttpRequest();
    var params = "method=" + method + "&idWebsite=" + avHelpfulIdwebsite + "&idProduct=" + idProduct + "&isHelpful=" + vote + "&fingerPrint=" + fingerPrint + "&sign=" + sign;
	http.open("POST", avHelpfulURL, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function() {
        if(http.readyState == 4 && http.status == 200) {
            try {
              var obj = JSON.parse(http.responseText);
              if (typeof obj.success !== "undefined") {
                // SUCCESS
                if (obj.success == '1') {
                    if (obj.method == 'create') {
                        // save cookie
                        avHelpfulCookie[obj.idProduct] = {};
                        avHelpfulCookie[obj.idProduct]["vote"] = obj.isHelpful;
                        avHelpfulCookie[obj.idProduct]["fingerPrint"] = obj.fingerPrint;
                        avSaveCookie();
                    }
                    if (obj.method == 'delete') {
                        // remove cookie
                        if (typeof avHelpfulCookie[obj.idProduct] !== "undefined") {
                            delete avHelpfulCookie[obj.idProduct];
                            avSaveCookie();
                        }
                    }
                }
                // ERROR
                if (obj.success == '0') {
                    avUnColorButtons(obj.idProduct);
                    avShowMessage(obj.idProduct,avHelpfulErrorMessage,'error');
                    console.log('[NetReviews] Error ' + obj.errorCode + ' : ' + obj.errorMessage);
                }
              }
            } catch (e) {
                console.error("Parsing error:", e); 
                avUnColorButtons(idProduct);
                avShowMessage(idProduct,avHelpfulErrorMessage,'error');
                console.log('[NetReviews] Unknown error.');
            }
        }
        avhelpfulExec = false;
    };
    http.send(params);
}

// Affiche les votes existants
 var getExistingVote = function (idProduct) {
    if (typeof avHelpfulCookie[idProduct] !== "undefined")
        return avHelpfulCookie[idProduct];
    else 
        return {};
}

var avSaveCookie = function() {
    var expiration_date = new Date();
    expiration_date.setFullYear(expiration_date.getFullYear() + 1);
    var cookie_value = JSON.stringify(avHelpfulCookie);
    document.cookie = "netreviews_helpful=" + cookie_value + "; path=/; expires=" + expiration_date.toUTCString();
}

// Masque le vote de l'internaute
 var avUnColorButtons = function(idProduct) {
    var link_yes = document.getElementById(idProduct + '_1');
    var link_no  = document.getElementById(idProduct + '_0');
    link_yes.classList.remove("active");
    link_no.classList.remove("active");
}		
   
 var avShowMessage = function(idProduct,message,type) {
    var p = document.getElementById(idProduct + '_msg');
    if (typeof p !== "undefined" && p != "null") {
        p.innerHTML = message;
        if (message != "") {p.style.display = 'block';}
        if (message == "") {p.style.display = 'none';}
        if (type == 'success') { p.style.color = '#0c9c5b'; }
        if (type == 'error') { p.style.color = '#bf2525'; }
    }
}
   
  return {  
// Au clic de l'internaute sur un vote
 avHelpfulClick: function(idProduct,vote,sign) {
    // Si double click pas d'action
    if (avhelpfulExec) { return false; }
    avhelpfulExec = true;
    // On recupère l'element <a>
    var link = document.getElementById(idProduct + '_' + vote);
    // On check si le lien est déjà actif ou non
    var linkIsActive = this.avHasClass(link,'active');
    // Affichage en direct de l'action
    if (!linkIsActive) {
        // Le lien n'est pas déjà actif > color
        this.avColorButton(idProduct,vote);
        avShowMessage(idProduct,avHelpfulSuccessMessage,'success');
    }
    else {
        // Le lien est déjà actif > uncolor
        avUnColorButtons(idProduct);
        avShowMessage(idProduct,'','');
    }
    // On calcul le fingerPrint de l'internaute
    new Fingerprint2().get(function(result, components){
        // On recharge les coockies
        var name = "netreviews_helpful=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		avHelpfulCookie = {};
		for(var i = 0; i <ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
        }
			if (c.indexOf(name) == 0) {
            avHelpfulCookie = JSON.parse(c.substring(name.length, c.length));
			}
		}
        // new vote > create
        if (!linkIsActive) {
            avCallHelpfulWebservice('create',idProduct,vote,sign,result);
        }
        // vote already sent > delete
        else {
            avCallHelpfulWebservice('delete',idProduct,vote,sign,result);
        }
    });
},
// Appel au webservice
 avCallHelpfulWebservice: avCallHelpfulWebservice,
// Met en avant le vote de l'internaute
 avColorButton:function(idProduct,isHelpful) {
    var link = document.getElementById(idProduct + '_' + isHelpful);
    var linkIsActive = this.avHasClass(link,'active');
     var otherLink;
    if (!linkIsActive) {
        link.classList.add("active");
    }
    if (isHelpful=='0') {
         otherLink = document.getElementById(idProduct + '_1')
    } else {
         otherLink = document.getElementById(idProduct + '_0')
    }
    otherLink.classList.remove("active");
},
// Masque le vote de l'internaute
 avUnColorButtons: avUnColorButtons,
// Affiche un message de confirmation ou d'erreur
 avShowMessage: avShowMessage,
// Test si un element possède une classe css
 avHasClass: function(element, cls) {
    return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
},
// Charge le cookie netreviews_helpful
 avLoadCookie:function() {
    var name = "netreviews_helpful=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    this.avHelpfulCookie = {};
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            avHelpfulCookie = JSON.parse(c.substring(name.length, c.length));
        }
    }
},
// Sauvegarde le cookie netreviews_helpful
 avSaveCookie: avSaveCookie,
// Affiche les votes existants
  avDisplayVotes: function() {
    var voteButtons = document.getElementsByClassName("netreviewsVote");
    for (var i = 0; i < voteButtons.length; i++) {
        var idProduct = voteButtons[i].getAttribute("data-review-id");
        if (typeof idProduct !== "undefined" && idProduct != "") {
            var existingVote = getExistingVote(idProduct);
            if (typeof existingVote.vote != "undefined") {
                this.avColorButton(idProduct,existingVote.vote);
            }
        }
    }
},

// Affiche les votes existants
 getExistingVote:getExistingVote
}
});
