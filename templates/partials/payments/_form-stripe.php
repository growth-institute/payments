<form action="<?php $site->urlTo('/payments/charge/stripe', true); ?>" method="post" id="payment-form">
	<input type="hidden" name="custom" value="<?php echo $site->payments->cart->uid; ?>">

	<div class="form-row">
		<label for="card-element"><?php $i18n->translate('form.stripe.card-element'); ?></label>
		<div id="card-element">
			<!-- a Stripe Element will be inserted here. -->
		</div>
		<!-- Used to display form errors -->
		<div id="card-errors" role="alert"></div>
	</div>
	<button type="submit" class="button button-primary"><?php $i18n->translate('form.stripe.submit'); ?></button>
	<img class="center" src="https://learn.growthinstitute.com/images/payment-form/credit-cards.jpg" alt="payment">
</form>