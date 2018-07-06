<form action="<?php $site->urlTo('/payments/charge/conekta', true); ?>" method="post" id="card-form">
	<input type="hidden" name="custom" value="<?php echo $site->payments->cart->uid; ?>">
	<div class="form-fields">
		<div class="row row-md">
			<div class="col col-md-8 col-6">
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
								<div class="col col-sm-5">
									<input type="text" maxlength="2" data-conekta="card[exp_month]" class="form-control input-block">
								</div>
								<div class="col col-sm-5">
									<input type="text" maxlength="4" data-conekta="card[exp_year]" class="form-control input-block">
								</div>
							</div>
						</div>
					</div>
					<div class="col col-md-3 col-md-offset-2">
						<div class="form-group">
							<label class="control-label">CVC</label>
							<input type="text" maxlength="4" data-conekta="card[cvc]" class="form-control input-block">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Pago a meses sin intereses</label>
					<select name="installments" class="form-control input-block">
						<option value="">Selecciona (opcional)</option>
						<option value="3">3 meses sin intereses</option>
						<option value="6">6 meses sin intereses</option>
						<option value="9">9 meses sin intereses</option>
						<option value="12">12 meses sin intereses</option>
					</select>
				</div>
			</div>
		</div>
	</div>
	<span class="card-errors"></span>
	<div class="form-actions">
		<button type="submit" class="button button-primary">Procesar pago</button>
	</div>
</form>