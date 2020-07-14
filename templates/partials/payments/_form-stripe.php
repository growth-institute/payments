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

	<button type="submit" class="button button-primary js-process-payment"><?php echo $form->getMeta('form_stripe_submit', $i18n->translate('form.stripe.submit', false)) ; ?></button>

	<?php if($copy = $form->getMeta('before_payment_copy')): ?>
		<div class="the-content margins-vert">
			<?php echo $copy; ?>
		</div>
	<?php endif; ?>

</form>