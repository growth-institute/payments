	<form action="<?php $site->urlTo('/payments/charge/payu', true); ?>" method="post" id="card-payu">
		<input type="hidden" name="custom" value="<?php echo $site->payments->cart->uid; ?>">
		<input payu-content="payer_id" value="<?php echo $site->payments->cart->uid; ?>" type="hidden">
		<div class="form-fields">
			<div class="row row-md row-5">
				<div class="col col-md-12 col-12">
					<div class="form-group">
						<label class="control-label"> <span>Nombre como aparece en la tarjeta</span></label>
						<input type="text" class="form-control input-block" payu-content="name_card">
					</div>
				</div>
			</div>
		</div>
		<div class="row row-md row-5">
			<div class="col col-md-6 col-6">
				<div class="form-group">
					<label class="control-label">Fecha de Expiración</label>
					<div class="row row-md row-5">
						<div class="col col-md-6">
							<input type="text" size="2" class="input-block form-control" payu-content="exp_month" placeholder="MM">
						</div>
						<div class="col col-md-6">
							<input type="text" size="4" class="input-block form-control" payu-content="exp_year" placeholder="AAAA">
						</div>
					</div>
				</div>
			</div>
			<div class="col col-md-6 col-6">
				<div class="form-group">
					<label for="installments" class="control-label">Meses sin intereses</label>
					<select name="installments" id="installments" class="input-block form-control">
						<option value="">Selecciona</option>
						<option value="0">No deseo meses sin intereses</option>
						<option value="3">3 meses</option>
						<option value="6">6 meses</option>
						<option value="9">9 meses</option>
						<option value="12">12 meses</option>
					</select>
				</div>
			</div>
			<div class="col col-md-12 col-12">
				<div class="form-group">
					<label for="bank" class="control-label">Banco</label>
					<select name="bank" id="bank" class="input-block form-control">
						<option value="">Selecciona tu banco</option>
						<option value="BANAMEX">BANAMEX</option>
						<option value="BANCO REGIONAL DE MONTERREY S.A">BANCO REGIONAL DE MONTERREY S.A</option>
						<option value="BANCOPPEL">BANCOPPEL</option>
						<option value="BANCO AZTECA">BANCO AZTECA</option>
						<option value="SCOTIABANK">SCOTIABANK</option>
						<option value="HSBC">HSBC</option>
						<option value="INBURSA">INBURSA</option>
						<option value="BANCA MIFEL SA">BANCA MIFEL SA</option>
						<option value="BANCO MULTIVA">BANCO MULTIVA</option>
						<option value="BAJIO">BAJIO</option>
						<option value="CI BANCO">CI BANCO</option>
						<option value="Afirme">Afirme</option>
						<option value="Banregio">Banregio</option>
						<option value="Banjercito">Banjercito</option>
						<option value="Banorte">Banorte</option>
						<option value="Famsa">Famsa</option>
						<option value="Invex">Invex</option>
						<option value="Premium Card Liverpool">Premium Card Liverpool</option>
						<option value="Santander">Santander</option>
						<option value="Bancomer">Bancomer</option>
					</select>
				</div>
			</div>
		</div>
		<div class="row row-md row-5">
			<div class="col col-md-8 col-8">
				<div class="form-group">
					<label class="control-label"> <span>Número de la tarjeta de crédito</span></label>
					<input class="form-control input-block" type="text" maxlength="16" payu-content="number" onkeyup="payU.validateCard(this.value);">
					<div id="mylistID"></div>
				</div>
			</div>
			<div class="col col-md-4 col-4">
				<div class="form-group">
					<label class="control-label"> <span>CVV</span></label>
					<input type="text" class="form-control input-block" payu-content="cvc">
				</div>
			</div>
		</div>
		<span class="form-row-payu create-errors" style="color:red"></span>

		<button class="button button-primary" type="submit">Realizar pago</button>
	</form>