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

		$.extend(true, $.alert.defaults, {
			markup: '<div class="alert-overlay"><div class="valign-wrapper"><div class="valign"><div class="alert"><div class="alert-message">{message}</div><div class="alert-buttons"></div></div></div></div></div>',
			buttonMarkup: '<button class="button button-primary"></button>',
			buttons: [
				{ text: 'Close', action: $.alert.close }
			]
		});

		jQuery(document).ready(function($) {
			obj.onDomReady($);
		});
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

		$('form[data-submit=validate]').on('submit', function() {
			var form = $(this);
			return form.validate({
				callbacks: {
					fail: function(field, type, message) {
						field.closest('.form-group').addClass('has-error');
						field.on('focus', function() {
							field.closest('.form-group').removeClass('has-error');
							field.off('focus');
						});
					},
					error: function(fields) {
						$.alert('Por favor llena todos los campos requeridos');
					}
				}
			});
		});
	}
});

var site = new Site();