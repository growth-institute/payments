		<form action="<?php $site->urlTo('/payments/charge/payu', true); ?>" method="post" id="card-payu">
		<input type="hidden" name="custom" value="<?php echo $site->payments->cart->uid; ?>">
		<h1 class="title-payu">Javascript de tokenización</h1>
		<div class="form-fields">
			<div class="row row-md">
				<div class="col col-md-12 col-12">
					<div class="form-group">
						<label class="control-label"> <span>Número de la tarjeta de crédito</span></label>
						<input class="form-control input-block" type="text" size="20" payu-content="number" onkeyup="payU.validateCard(this.value);">
						<div id="mylistID" style=""></div>
					</div>
				</div>
			</div>
		</div>
		<div class="row row-md">
			<div class="col col-md-4 col-4">
				<div class="form-group">
					<label class="control-label">Expiración (MM/AAAA)</label>
					<input type="text" size="2" class="form-control" payu-content="exp_month"><span> / </span>  <input type="text" size="4" class="form-control" payu-content="exp_year">
				</div>
			</div>
			<div class="col col-md-8 col-8">
				<div class="form-group">
					<label class="control-label">Documento</label>
					<input type="text" class="form-control input-block" payu-content="document">
					<input payu-content="payer_id" value="MI PAYER ID" type="hidden">
				</div>
			</div>
		</div>
		<div class="row row-md">
			<div class="col col-md-8 col-8">
				<div class="form-group">
					<label class="control-label"> <span>Nombre</span></label>
					<input type="text" class="form-control input-block" payu-content="name_card">
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
		<div class="clear"></div>

		<button class="button-payu" type="submit" height:50px; width:50px;></button>

		</form>