<?php if($form->getMeta('stripe_installments') != 'Yes'): ?>
	<form id="payment-form" action="<?php $site->urlTo('/payments/charge/stripe', true); ?>" method="post">
		<input type="hidden" name="custom" value="<?php echo $site->payments->cart->uid; ?>">

		<div class="form-row">
			<label for="card-element"><?php $i18n->translate('form.stripe.card-element'); ?></label>

			<div id="card-element"></div>
			<!-- Used to display form errors -->
			<div id="card-errors" role="alert"></div>
			<?php if($form->id == 113 ): ?>
				<div class="form-actions">
						<p>Recuerda que tu primer cargo será por tan solo $1 USD y comenzarás a pagar $24.99 USD a partir del segundo mes.</p>
				</div>
			<?php endif; ?>
			<button type="submit" class="button button-primary"><?php $i18n->translate('form.stripe.submit'); ?></button>
		</div>
	</form>
<?php else: ?>

	<h4>Paga con meses sin interéses</h4>

	<div class="stripe-installments" id="details">
		<div class="form-row" id="payment-form">
			<div class="form-group">
				<input class="input-block form-control" id="cardholder-name" type="text" placeholder="Nombre en la tarjeta">
			</div>
			<!-- placeholder for Elements -->
			<div id="card-element"></div>
			<div id="card-errors" role="alert"></div>
			<button id="card-button" class="button button-primary">Siguiente Paso</button>
		</div>
	</div>

	<div id="plans" class="hide">
		<form id="installment-plan-form" class="installments-options" action="<?php $site->urlTo('/payments/charge/stripe', true); ?>" method="post">
			<input id="payment-intent-id" name="payment_intent_id" type="hidden" />
			<input type="hidden" name="custom" value="<?php echo $site->payments->cart->uid; ?>">
			<div class="installments-options-rows">
				<label><input id="immediate-plan" type="radio" name="installment_plan" value="-1" /> Pago inmediato</label>
			</div>
			<button type="submit" id="confirm-button" class="button button-primary">Confirmar pago</button>
		</form>
	</div>

	<div id="result" class="hide">
		<p id="status-message"></p>
	</div>

<?php endif; ?>