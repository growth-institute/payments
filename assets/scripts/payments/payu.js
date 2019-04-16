console.log(payuPublicKey);
console.log(payuUrl);
payU.setURL(payuUrl);
payU.setPublicKey(payuPublicKey);
payU.setAccountID("626437");
payU.setListBoxID("mylistID");
payU.setLanguage("es"); // optional
payU.getPaymentMethods();


// function de respuesta
var responseHandler = function(response) {
	var $form = $('#card-payu');

	if (response.error) {
		// Show the errors on the form
		$form.find('.create-errors').text(response.error);
		$form.find('button').prop('disabled', false);
	} else {
		// token contains id, last4, and card type
		var token = response.token;
		//alert(token);
		$form.find('button').prop('disabled', false);
		$form.append($('<input type="hidden" name="payuTokenId" id="payuTokenId">').val(token));
		$form.get(0).submit(); //Hace submit
	}
};

jQuery(function($) {
	$('#card-payu').submit(function(event) {
		var $form = $(this);
		// Disable the submit button to prevent repeated clicks
		$form.find('button').prop('disabled', true);
		payU.createToken(responseHandler, $form);
		// Prevent the form from submitting with the default action
		return false;
	});
});