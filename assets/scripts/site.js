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
	numberWithCommas: function(x) {
		var parts = x.toString().split(".");
		parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		return parts.join(".");
	},
	onDomReady: function($) {
		var obj = this;

		$('#quantity').on('change blur', function(event) {
			var el = $(this),
				val = el.val(),
				extraSeats = typeof quantity.extraSeatPrice !== 'undefined',
				discounts = typeof quantity.discounts !== 'undefined',
				discount = 1,
				totalPrice = 0;

			if(val) {
				if(discounts) {

					for(x = 0; x < quantity.discounts.length; x++) {
						if(val >= parseInt(quantity.discounts[x].from) && val <= parseInt(quantity.discounts[x].to)) {

							if(quantity.discounts[x].type == 'percentage') {

								discount = 1-(quantity.discounts[x].val/100);
								totalPrice = obj.numberWithCommas(((quantity.price*val)*discount).toFixed(2));

								$('.js-total-price').html('$' + totalPrice + ' ' + quantity.currency);
								$('.js-quantity').html(val + ' (' + (quantity.discounts[x].val) + '% off)');

							}

							if(quantity.discounts[x].type == 'amount') {

								totalPrice = obj.numberWithCommas(((quantity.price*val)-quantity.discounts[x].val).toFixed(2));
								discount = 0;

								$('.js-total-price').html('$' + totalPrice + ' ' + quantity.currency);
								$('.js-quantity').html(val + ' ($' + (quantity.discounts[x].val) + ' ' + quantity.currency + ' off)');
							}

							if(quantity.discounts[x].type == 'fixed') {

								totalPrice = obj.numberWithCommas((parseFloat(quantity.discounts[x].val)).toFixed(2));
								discount = 0;

								$('.js-total-price').html('$' + totalPrice + ' ' + quantity.currency);
								$('.js-quantity').html(val + ' (Fixed price for ' + val + ' items)');
							}
						}
					}

					if(discount == 1) {

						totalPrice = obj.numberWithCommas((quantity.price*val).toFixed(2));

						$('.js-total-price').html('$' + totalPrice + ' ' + quantity.currency);
						$('.js-quantity').html(val);

					}
				}
				else if(extraSeats) {

					totalPrice = obj.numberWithCommas((parseFloat(quantity.price)+(quantity.extraSeatPrice*parseFloat(val))).toFixed(2));

					$('.js-total-price').html('$' + totalPrice + ' ' + quantity.currency);
					$('.js-quantity').html(val + ' × $' + parseFloat(quantity.extraSeatPrice).toFixed(2) + ' ' + quantity.currency);
				} else {

					totalPrice = obj.numberWithCommas((quantity.price*val).toFixed(2));

					$('.js-total-price').html('$' + (quantity.price*val).toFixed(2) + ' ' + quantity.currency);
					$('.js-quantity').html(val);
				}
			} else {
				if(discounts) {
					el.val(1);
				} else if(extraSeats) {
					el.val(0);
				} else {
					el.val(1);
				}
				el.trigger('change');
			}
		}).trigger('blur');

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
						$.alert('Please complete all the mandatory fileds to continue');
					}
				}
			});
		});
	}
});

var site = new Site();