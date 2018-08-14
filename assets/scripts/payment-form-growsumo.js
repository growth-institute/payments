
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
	growSumoSignup: function(event) {
		// prevent the form from submitting temporarily
		event.preventDefault();
		// find the name and email input fields by Name and store
		var first_name = $('#first_name').value();
		var last_name = $('#last_name').value();
		var email = $('#email').value();
		var phone = $('#phone').value;
		var company = $('#company').value;
		// assign values to growsumo object
		growsumo.data.first_name = first_name;
		growsumo.data.last_name= last_name;
		growsumo.data.email = email;
		growsumo.data.phone = phone;
		growsumo.data.company= company;
		growsumo.data.customer_key = email;
		growsumo.createSignup(function() {
		// use callback function to print to console to debug
		console.log(first_name);
		console.log(last_name);
		console.log(email);
		console.log(phone);
		console.log(company);
		console.log('signup successful');
		});
		return true;
	},
	onDomReady: function($) {
		var obj = this;
		obj.growSumoSignup();
		var growSumoPartnerKey = obj.getCookie('growSumoPartnerKey');
			if (typeof growSumoPartnerKey !== 'undefined') {
				$('[name=growsumo-partner-key]').val(growSumoPartnerKey);
			}
	}
});

var growsumo = new GrowSumo();