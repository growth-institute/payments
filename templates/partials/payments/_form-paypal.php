	<form action="<?php echo $paypal_url; ?>" method="post" id="paypal-form">
		<div class="form-group">
			<label for="" class="control-label">Pay safely and easily with PayPal</label>
		</div>

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


		<input type="hidden" name="cmd" value="_xclick">
		<input type="hidden" name="business" value="<?php echo $paypal_account[$form->currency]; ?>">
		<input type="hidden" name="item_name" value="<?php echo $form->name; ?>">
		<input type="hidden" name="amount" value="<?php echo $form->total; ?>">
		<input type="hidden" name="no_shipping" value="2">
		<input type="hidden" name="no_note" value="0">
		<input type="hidden" name="currency_code" value="<?php echo strtoupper($form->currency); ?>">
		<input type="hidden" name="country" value="MX">
		<input type="hidden" name="bn" value="PP-BuyNowBF">
		<input type="hidden" name="custom" value="<?php echo $site->payments->cart->uid; ?>">
		<input type="hidden" name="notify_url" value="<?php $site->urlTo('/paypal/webhook', true); ?>">
		<input type="hidden" name="return" value="<?php $site->urlTo("/thanks/{$site->payments->cart->uid}", true); ?>">
		<div class="form-actions">
			<button type="submit" class="button button-primary">Pay now with PayPal</button>
		</div>
	</form>