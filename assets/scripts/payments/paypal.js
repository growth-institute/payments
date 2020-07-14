jQuery(document).ready(function($) {

	$('#quantity').on('change blur', function(event) {
		var el = $(this),
			val = el.val(),
			extraSeats = typeof quantity.extraSeatPrice !== 'undefined',
			discounts = typeof quantity.discounts !== 'undefined',
			discount = 1;

		if(val) {
			if(discounts) {

				for(x = 0; x < quantity.discounts.length; x++) {
					if(val >= parseInt(quantity.discounts[x].from) && val <= parseInt(quantity.discounts[x].to)) {
						if(quantity.discounts[x].type == 'percentage') {

							discount = 1-(quantity.discounts[x].val/100);
							$('.js-total-price').html('$' + ((quantity.price*val)*discount).toFixed(2) + ' ' + quantity.currency);
							$('.js-quantity').html(val + ' (' + (quantity.discounts[x].val) + '% off)');
						}
					}
				}

				if(discount == 1) {
					$('.js-total-price').html('$' + (quantity.price*val).toFixed(2) + ' ' + quantity.currency);
					$('.js-quantity').html(val);
				}
			}
			else if(extraSeats) {
				$('.js-total-price').html('$' + (parseFloat(quantity.price)+(quantity.extraSeatPrice*parseFloat(val))).toFixed(2) + ' ' + quantity.currency);
				$('.js-quantity').html(val + ' × $' + parseFloat(quantity.extraSeatPrice).toFixed(2) + ' ' + quantity.currency);
			} else {
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
	});
});