	<h2>User details</h2>
	<div class="tabs">
		<div class="tab active">
			<form data-submit="validate" action="" method="post">
				<div class="form-fields">
					<div class="row row-md row-5">
						<div class="col col-md-6">
							<div class="form-group">
								<label for="first_name" class="control-label">First name <span class="required">*</span></label>
								<input type="text" name="first_name" id="first_name" class="form-control input-block" value="<?php sanitized_print($order ? $order->getMeta('first_name') : ''); ?>" data-validate="required">
							</div>
						</div>
						<div class="col col-md-6">
							<div class="form-group">
								<label for="last_name" class="control-label">Last name <span class="required">*</span></label>
								<input type="text" name="last_name" id="last_name" class="form-control input-block" value="<?php sanitized_print($order ? $order->getMeta('last_name') : ''); ?>" data-validate="required">
							</div>
						</div>
					</div>
					<div class="row row-md row-5">
						<div class="col col-md-6">
							<div class="form-group">
								<label for="email" class="control-label">Email <span class="required">*</span></label>
								<input type="text" name="email" id="email" class="form-control input-block" value="<?php sanitized_print($order ? $order->getMeta('email') : ''); ?>" data-validate="required">
							</div>
						</div>
						<div class="col col-md-6">
							<div class="form-group">
								<label for="phone" class="control-label">Phone <span class="required">*</span></label>
								<input type="text" name="phone" id="phone" class="form-control input-block" value="<?php sanitized_print($order ? $order->getMeta('phone') : ''); ?>" data-validate="required">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="company" class="control-label">Company <span class="required">*</span></label>
						<input type="text" name="company" id="company" class="form-control input-block" value="<?php sanitized_print($order ? $order->getMeta('company') : ''); ?>" data-validate="required">
					</div>
					<?php if($form->getMeta('growsumo')): ?>
							<input type="hidden" name="growsumo" value="1">
							<input type="hidden" name="growsumo-partner-key" value="">
					<?php endif; ?>
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
				</div>
				<div class="form-actions">
					<button type="submit" class="button button-primary">Continue</button>
				</div>
			</form>
		</div>
	</div>