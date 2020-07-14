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

	if(!$('.stripe-installments').length) {

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
			$('.button').prop('disabled', true);
			$('.button').text('Please wait while we process your payment');

			stripe.createToken(card).then(function(result) {
				if (result.error) {
					// Inform the user if there was an error
					var errorElement = document.getElementById('card-errors');
					errorElement.textContent = result.error.message;
					$('.button').prop('disabled', false);
					$('.button').text('Procesar pago');

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
	} else {

		//INSTALLMENTS (MESES SIN INTERESES)

		var stripe = Stripe(stripePublishableKey);
		console.log('key', stripe);
		var elements = stripe.elements();
		var cardElement = elements.create('card', { hidePostalCode: true });
		cardElement.mount('#card-element');

		var cardholderName = document.getElementById('cardholder-name');
		var cardButton = document.getElementById('card-button');

		cardButton.addEventListener('click', function(ev) {
			$('#card-button').prop('disabled', true);
			$('#card-button').text('Wait a moment.');

			stripe.createPaymentMethod({
				type: 'card',
				card: cardElement,
				billing_details: { name: cardholderName.value }
			}).then(function(result) {

				var errorElement = document.getElementById('card-errors');
				errorElement.textContent = '';

				if (result.error) {
					// Show error in payment form
					errorElement.textContent = result.error.message;

				} else {
					console.log(result);

					// Otherwise send paymentMethod.id to your server (see Step 2)
					fetch(constants.siteUrl + '/stripe/collect-details', {
						method: 'POST',
						headers: { 'Content-Type': 'application/json' },
						body: JSON.stringify({ payment_method_id: result.paymentMethod.id, id_order: payments.id_order })
					}).then(function(result) {
						// Handle server response (see Step 3)
						result.json().then(function(json) {
							if(json.result == 'success') {
								/*$('#confirm-button').prop('disabled', true);
								$('#confirm-button').text('Espera un momento..');*/
								handleInstallmentPlans(json.data);
							} else {
								errorElement.textContent = 'Ha occurrido un error al procesar tus datos. Por favor intenta más tarde.';
							}
						})
					});
				}
			});
		});

		var selectPlanForm = $('#installment-plan-form');
		var availablePlans = [];

		var handleInstallmentPlans = function(response) {
			if (response.error) {
			// Show error from server on payment form
			} else {
				// Store the payment intent ID.
				$('#payment-intent-id').val(response.intent_id);
				availablePlans = response.available_plans;

				// Show available installment options
				_.each(availablePlans, function(plan, k) {

					var newInput = $('#immediate-plan').clone();
					newInput.attr('value', plan.option_index);
					newInput.attr('id', 'plan-' + plan.option_index);
					var label = $('<label></label>');
					label.append(newInput);
					label.append(document.createTextNode(plan.count + ' ' + plan.interval + 's'),);

					$('.installments-options-rows').append(label);
				});

				$('#details').addClass('hide');
				$('#plans').removeClass('hide');
			}
		};
	}
});