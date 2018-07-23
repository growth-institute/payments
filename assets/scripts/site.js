/**
 * site.js
 * Base logic, feel free to replace with your own and/or use the libraries of your choice
 */
Site = Class.extend({
	init: function(options) {
		var obj = this,
			opts = _.defaults(options, {
				// Add options here
			});
		jQuery(document).ready(function($) {
			obj.onDomReady($);
		});
	},
	showPeriodicity: function(val) {
		if ( val ) {
			$('#periodicity-group').removeClass('hide');
		} else {
			$('#periodicity-group').addClass('hide');
		}
	},
	installments: function(val) {

		if (val){
			$('#metabox-installment').removeClass('hide');
		} else {
			$('#metabox-installment').addClass('hide');
		}
	},
	onDomReady: function($) {
		var obj = this;
		
		// Tabs Miniplugin
		$('.tab-list li a').on('click', function(e) {
			e.preventDefault();
			var el = $(this),
				li = el.closest('li'),
				target = $( el.attr('href') );
				li.addClass('selected').siblings('li').removeClass('selected');
				target.addClass('active').siblings('.tab').removeClass('active');
		});
		
		$('.tab-list').each(function() {
			var el = $(this);
			el.find('li a').first().trigger('click');
		});
		/**Clipboard function */
		$('.span').css("display","none");
		$('.js-copy').on('click', function(e) {
			e.preventDefault();
			var copy = $('.input').select();
			document.execCommand("copy");
			$( '.span' ).css( "display", "inline" ).fadeOut( 2000 );
		});
		//set default 0 value in ocurrency
		$('#ocurrency').val('0');
		//call function show periodicty
		$('.js-toggle-periodicity').on('click', function() {
			var el = $(this),
				val = el.val();
			obj.showPeriodicity(val);
		}).trigger('change');
		$('#conekta:checked').on('change', function(){
			var el = $(this),
				val = el.val();
			obj.installments(val);
		}).trigger('change');
		//validation forms fronend
		$('#form-test').on('submit', function() {
			return $(this).validate({
				callbacks: {
					fail: function(field, type, message) {
						/* An item has failed validation, field has the jQuery object, type is the rule and message its description */
					},
					success: function() {
						/* Everything is OK, continue */
					},
					error: function(fields) {
						/* Missing info! 'fields' is a jQuery object with the offending fields */
					}
				}
			});
		});
	}
});

var site = new Site();
