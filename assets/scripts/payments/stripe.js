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


	// Create a Stripe client
	var stripe = Stripe(stripePublishableKey);
	// Create an instance of Elements
	var elements = stripe.elements();
	// Create an instance of the card Element
	var card = elements.create('card', { hidePostalCode: true });
	// Add an instance of the card Element into the `card-element` <div>
	card.mount('#card-element');
	// Handle real-time validation errors from the card Element.
	card.addEventListener('change', function(event) {
		var displayError = document.getElementById('card-errors');
		if (event.error) {
			displayError.textContent = event.error.message;
		} else {
			displayError.textContent = '';
		}
	});
	// Handle form submission
	var form = document.getElementById('payment-form');
	form.addEventListener('submit', function(event) {
		event.preventDefault();
		stripe.createToken(card).then(function(result) {
			if (result.error) {
				// Inform the user if there was an error
				var errorElement = document.getElementById('card-errors');
				errorElement.textContent = result.error.message;
			} else {
				// Send the token to your server
				stripeTokenHandler(result.token);
			}
		});
	});
	//
	function stripeTokenHandler(token) {
		// Insert the token ID into the form so it gets submitted to the server
		var form = document.getElementById('payment-form');
		var hiddenInput = document.createElement('input');
		hiddenInput.setAttribute('type', 'hidden');
		hiddenInput.setAttribute('name', 'stripeToken');
		hiddenInput.setAttribute('value', token.id);
		form.appendChild(hiddenInput);
		// Submit the form
		form.submit();
	}
});