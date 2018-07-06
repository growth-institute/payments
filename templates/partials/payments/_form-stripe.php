<form action="<?php $site->urlTo('/payments/charge/stripe', true); ?>" method="post" id="payment-form">
	<input type="hidden" name="custom" value="<?php echo $site->payments->cart->uid; ?>">
	<div class="form-row">
		<label for="card-element"> Credit or debit card</label>
		<div id="card-element">
			<!-- a Stripe Element will be inserted here. -->
		</div>
		<!-- Used to display form errors -->
		<div id="card-errors" role="alert"></div>
	</div>
	<button type="submit" class="button button-primary">Process payment</button>
</form>