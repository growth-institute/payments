<form action="<?php $site->urlTo('/payments/charge/stripe', true); ?>" method="post" id="payment-form">
	<input type="hidden" name="custom" value="<?php echo $site->payments->cart->uid; ?>">

	<?php if($form->getMeta('quantity')): ?>
	<div class="form-group">
		<label for="quantity" class="control-label">
			<?php echo $form->getMeta('extra_seats_price') && !$form->getMeta('discounts') ? 'Extra seats' : 'Quantity'; ?>
			<?php if($form->getMeta('extra_seats_price') && !$form->getMeta('discounts')): ?>
				<small>(<?php echo '$' .  number_format($form->getMeta('extra_seats_price'), 2) ?> for each extra seat)</small>
			<?php endif; ?>
		</label>
		<input type="number" min="<?php echo $form->getMeta('extra_seats_price') && !$form->getMeta('discounts') ? 0 : 1; ?>" name="quantity" id="quantity" value="<?php echo $form->getMeta('extra_seats_price') && !$form->getMeta('discounts') ? 0 : 1; ?>" class="input-block form-control">
	</div>
	<?php endif; ?>

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