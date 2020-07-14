
GrowSumo = Class.extend({
	init: function(options) {
		var obj = this,
			opts = _.defaults(options, {
				// Add options here
		});
		var gs = document.createElement('script');gs.src = 'https://snippet.growsumo.com/growsumo.min.js';gs.type = 'text/javascript';gs.async = 'true';gs.onload = gs.onreadystatechange = function() {var rs = this.readyState;if (rs && rs != 'complete' && rs != 'loaded') return;try {growsumo._initialize('pk_g1XHPqu4la1gXSlgfCeOwzvSumXgryX0');if (typeof(growsumoInit) === 'function') {growsumoInit();}} catch (e) {}};var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(gs, s);
		console.log('Payment form connected to Growsumo');
		jQuery(document).ready(function($) {
			obj.onDomReady($);
		});
	},
	getCookie: function(cname) {
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i <ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	},
	growSumoSignup: function() {
		// find the name and email input fields by Name and store
		var name = $('#first_name').val() + ' ' + $('#last_name').val();
		var email = $('#email').val();
		// assign values to growsumo object
		growsumo.data.name = name;
		growsumo.data.email = email;
 		growsumo.data.customer_key = email;
		growsumo.createSignup(function() {
			// use callback function to print to console to debug
			console.log(name);
			console.log(email);
			console.log('signup successful');
		});
		return true;
	},
	onDomReady: function($) {
		var obj = this;
		var growSumoPartnerKey = obj.getCookie('growSumoPartnerKey');
		if (typeof growSumoPartnerKey !== 'undefined') {
			$('[name=growsumo-partner-key]').val(growSumoPartnerKey);
		}

		$('#user-data-form').on('submit', function(event) {
			obj.growSumoSignup();
		});
	}
});

var growsumo = new GrowSumo();