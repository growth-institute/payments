	<form action="<?php echo $paypal_url; ?>" method="post" id="paypal-form">
		<div class="form-group">
			<label for="" class="control-label"><?php $i18n->translate('form.paypal.label-title'); ?></label>
		</div>

		<input type="hidden" name="cmd" value="_xclick">
		<input type="hidden" name="business" value="<?php echo $paypal_account[$order->currency]; ?>">
		<input type="hidden" name="item_name" value="<?php echo $form->name; ?>">
		<input type="hidden" name="amount" value="<?php echo $order->total; ?>">
		<input type="hidden" name="no_shipping" value="2">
		<input type="hidden" name="no_note" value="0">
		<input type="hidden" name="currency_code" value="<?php echo strtoupper($order->currency); ?>">
		<input type="hidden" name="country" value="MX">
		<input type="hidden" name="bn" value="PP-BuyNowBF">
		<input type="hidden" name="custom" value="<?php echo $site->payments->cart->uid; ?>">
		<input type="hidden" name="notify_url" value="<?php $site->urlTo('/paypal/webhook', true); ?>">
		<input type="hidden" name="return" value="<?php $site->urlTo("/thanks/{$site->payments->cart->uid}", true); ?>">
		<div class="form-actions">
			<button type="submit" class="button button-primary"><?php $i18n->translate('form.paypal.button'); ?></button>
		</div>
	</form>