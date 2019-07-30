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
	couponCode: null,
	changed: null,
	numberWithCommas: function(x) {
		var parts = x.toString().split(".");
		parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		return parts.join(".");
	},
	calculateRate: function(price) {
		var exchangeRate = quantity.exchangeRate; //exchange rates exist
		var usdPrice;

		usdPrice = price / exchangeRate;
		return usdPrice;
	},
	calculateTotal: function(qty) {
		var obj = this,

		extraSeats = typeof quantity.extraSeatPrice !== 'undefined', // Extraseats exist
		discounts = typeof quantity.discounts !== 'undefined', // Discounts exist
		codes = typeof quantity.codes !== 'undefined', // Coupons Exist
		exchangeRate = typeof quantity.exchangeRate !== 'undefined', //exchange rates exist
		discount = 1,
		totalPrice = 0,
		qtyInfo = '';

		totalPrice = quantity.price*qty;

		// Range discounts
		if(discounts) {

			for(x = 0; x < quantity.discounts.length; x++) {
				if(qty >= parseInt(quantity.discounts[x].from) && qty <= parseInt(quantity.discounts[x].to)) {
					if(quantity.discounts[x].type == 'percentage') {

						// Percentage Discount
						totalPrice = totalPrice*(1-(quantity.discounts[x].val/100));
						qtyInfo = qty + ' (' + quantity.discounts[x].val + '% off)'; //TODO
					} else {

						//Fixed amount discount
						totalPrice -= quantity.discounts[x].val;
						qtyInfo = qty + '($' + quantity.discounts[x].val + '% off)'; //TODO
					}


				} else if (qty == 1 ) {

					qtyInfo = '1' + ' (' + quantity.discounts[0].val + '% off)'; //TODO
				}
			}

		} else if(extraSeats) {

			console.log(qty);
			totalPrice = parseFloat(quantity.price) + (quantity.extraSeatPrice * parseFloat(qty));
			//qtyInfo = '1';
			qtyInfo = !exchangeRate ? qty + ' × $' + parseFloat(quantity.extraSeatPrice).toFixed(2) + ' ' + quantity.currency : qty + ' × $' + parseFloat(obj.calculateRate(quantity.extraSeatPrice)).toFixed(2) + ' ' + 'USD';
		} else {

			qtyInfo = qty;
		}

		// Coupons discounts
		if (obj.couponCode) {

			if (obj.couponCode['type_code'] == 'percentage') {

				totalPrice = totalPrice*(1-(obj.couponCode['value_code']/100));
				qtyInfo = qty + '(' + obj.couponCode['value_code'] + '% off) (coupon code ' + obj.couponCode['coupon'] + ' with ' + obj.couponCode['value_code'] + ' %off)';
			} else {

				totalPrice -= $obj.couponCode['value_code'];
				qtyInfo = qty + '($' + $obj.couponCode['value_code'] + '%off)(coupon code ' + obj.couponCode['coupon'] + ' with ' + quantity.codes[i]['value_code'] + ' %off)';
			}
		}
		//showing the princing on form view
		if (exchangeRate) {
			$('.total-price').html('$' + obj.numberWithCommas(parseFloat(obj.calculateRate(quantity.price)).toFixed(2)) + ' USD');
			$('.js-quantity').html(qtyInfo);
			$('.js-total-price-mxn').html('$' + obj.numberWithCommas(parseFloat(obj.calculateRate(totalPrice)).toFixed(2)) + ' ' + 'USD');
			$('.js-price-mxn').html('equivale a: $' + obj.numberWithCommas(parseFloat(totalPrice).toFixed(2)) + ' ' + quantity.currency);
		} else  if (!exchangeRate) {

			$('.js-quantity').html(qtyInfo);
			$('.js-total-price').html('$' + obj.numberWithCommas(parseFloat(totalPrice).toFixed(2)) + ' ' + quantity.currency);
		}
		return totalPrice;
	},

	checkCouponCode: function(coupon) {
		var codes = typeof quantity.codes !== 'undefined', // Codes Exist
			ret = false;

		if(codes) {

			for (var i = 0; i < quantity.codes.length; i++) {

				if (coupon == quantity.codes[i]['coupon']) {

					console.log('Coupon exits:', coupon);
					console.log(quantity.codes[i]['value_code']);
					ret = quantity.codes[i];
				}
			}
		}

		return ret;
	},
	onDomReady: function($) {
		var obj = this,
		extraSeats = typeof quantity.extraSeatPrice !== 'undefined',
		discounts = typeof quantity.discounts !== 'undefined';

		$('#quantity').on('change blur', function(event) {
			var el = $(this),
				val = el.val();

				console.log(val);
				obj.calculateTotal(val);
				changed = true;
		}).trigger('blur');

		if (changed && extraSeats) {

			$('#quantity').val(0);
		} else {

			$('#quantity').val(1);
		}

		// Tabs Miniplugin
		$('.tab-list li a').on('click', function(e) {
			e.preventDefault
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

		$('#check').on('click', function() {

			var code = $('#code').val();
			if (!code) {

				$.alert('Please enter your code number');
			} else {

				obj.couponCode = obj.checkCouponCode(code);
				console.log(obj.couponCode);

				if (!obj.couponCode) {

					$.alert('Your code is not valid');
				} else {

					obj.calculateTotal($('#quantity').val() || 1);
				}
			}
		});
	}
});

var site = new Site();