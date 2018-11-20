<form action="<?php $site->urlTo('/payments/charge/conekta', true); ?>" method="post" id="card-form">
	<input type="hidden" name="custom" value="<?php echo $site->payments->cart->uid; ?>">
	<div class="form-fields">
		<div class="row row-md">
			<div class="col col-md-12 col-12">
				<div class="form-group">
					<label class="control-label">Nombre del tarjetahabiente</label>
					<input type="text" size="20" data-conekta="card[name]" class="form-control input-block">
				</div>
				<div class="form-group">
					<label class="control-label">Número de tarjeta de crédito</label>
					<input type="text" maxlength="20" data-conekta="card[number]" class="form-control input-block">
				</div>
				<div class="row row-md row-5">
					<div class="col col-md-7">
						<div class="form-group">
							<label class="control-label">Fecha de expiración (MM/AAAA)</label>
							<div class="row row-sm row-5">
								<div class="col col-sm-6">
									<input type="text" maxlength="2" data-conekta="card[exp_month]" class="form-control input-block">
								</div>
								<div class="col col-sm-6">
									<input type="text" maxlength="4" data-conekta="card[exp_year]" class="form-control input-block">
								</div>
							</div>
						</div>
					</div>
					<div class="col col-md-5">
						<div class="form-group">
							<label class="control-label">CVC</label>
							<input type="text" maxlength="4" data-conekta="card[cvc]" class="form-control input-block">
						</div>
					</div>
				</div>
				<?php if(isset($form->metas->installments) && $form->metas->installments): ?>
					<div class="form-group">
						<label class="control-label">Pago a meses sin intereses</label>
						<select name="installments" class="form-control input-block">
							<option value="">Selecciona (opcional)</option>
							<?php foreach($form->metas->installments as $installment): ?>
								<option value="<?php echo $installment; ?>"><?php echo $installment; ?> meses sin intereses</option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<span class="card-errors"></span>
	<div class="form-actions">
		<button type="submit" class="button button-primary">Procesar pago</button>
	</div>
	<img class="center" src="https://learn.growthinstitute.com/images/payment-form/credit-cards.jpg" alt="payment">
</form>