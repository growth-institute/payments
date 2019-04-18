<?php if ($order->sandbox): ?>
	<div class="message message-error"><strong><?php $i18n->translate('section.message-error'); ?></strong></div>
<?php endif; ?>
	<h2><?php $i18n->translate('form.title.student'); ?></h2>
	<div class="tabs">
		<div class="tab active">
			<form data-submit="validate" id="user-data-form" action="" method="post">
				<div class="form-fields">
					<div class="row row-md row-5">
						<div class="col col-md-6">
							<div class="form-group">
								<label for="first_name" class="control-label"><?php $i18n->translate('form.label.first-name'); ?> <span class="required">*</span></label>
								<input type="text" name="first_name" id="first_name" class="form-control input-block" value="<?php sanitized_print($order ? $order->getMeta('first_name') : ''); ?>" data-validate="required">
							</div>
						</div>
						<div class="col col-md-6">
							<div class="form-group">
								<label for="last_name" class="control-label"><?php $i18n->translate('form.label.last-name'); ?><span class="required">*</span></label>
								<input type="text" name="last_name" id="last_name" class="form-control input-block" value="<?php sanitized_print($order ? $order->getMeta('last_name') : ''); ?>" data-validate="required">
							</div>
						</div>
					</div>
					<div class="row row-md row-5">
						<div class="col col-md-6">
							<div class="form-group">
								<label for="email" class="control-label"><?php $i18n->translate('form.label.email'); ?> <span class="required">*</span></label>
								<input type="text" name="email" id="email" class="form-control input-block" value="<?php sanitized_print($order ? $order->getMeta('email') : ''); ?>" data-validate="required">
							</div>
						</div>
						<div class="col col-md-6">
							<div class="form-group">
								<label for="phone" class="control-label"><?php $i18n->translate('form.label.phone'); ?> <span class="required">*</span></label>
								<input type="tel" name="phone" id="phone" class="form-control input-block" value="<?php sanitized_print($order ? $order->getMeta('phone') : ''); ?>" data-validate="required">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="company" class="control-label"><?php $i18n->translate('form.label.company'); ?> <span class="required">*</span></label>
						<input type="text" name="company" id="company" class="form-control input-block" value="<?php sanitized_print($order ? $order->getMeta('company') : ''); ?>" data-validate="required">
					</div>
					<?php if ($form->getMeta('growsumo')): ?>
							<input type="hidden" name="growsumo" value="1">
							<input type="hidden" name="growsumo-partner-key" value="">
					<?php endif; ?>
					<?php if ($form->getMeta('quantity')): ?>
						<div class="form-group">
							<label for="quantity" class="control-label">
								<?php if ($form->getMeta('quantity_label')): ?>
									<?php echo $form->getMeta('extra_seats_price') && !$form->getMeta('discounts') && $form->getMeta('quantity_label') ? 'Extra seats' : $form->getMeta('quantity_label'); ?>
								<?php else: ?>
									<?php echo $form->getMeta('extra_seats_price') && !$form->getMeta('discounts')? 'Extra seats' : 'Quantity' ?>
								<?php endif; ?>
								<?php if($form->getMeta('extra_seats_price') && !$form->getMeta('discounts')): ?>
									<small>(<?php echo '$' .  number_format($form->getMeta('extra_seats_price'), 2) ?> <?php sanitized_print($form->getMeta('quantity_label') ); ?>)</small>
								<?php endif; ?>
							</label>
							<?php
							?>
							<!-- <input type="number" min="<?php echo $form->getMeta('extra_seats_price') && !$form->getMeta('discounts') ? 0 : 1; ?>" name="quantity" id="quantity" value="<?php echo $form->getMeta('extra_seats_price') && !$form->getMeta('discounts') ? 0 : 1; ?>" class="input-block form-control"> -->
							<input type="number" min="<?php sanitized_print($form->getMeta('quantity_value') ? $form->getMeta('quantity_value') : 1); ?>" name="quantity" id="quantity" value="<?php sanitized_print($form->getMeta('quantity_value') ? $form->getMeta('quantity_value') : 1); ?>" class="input-block form-control">
						</div>
					<?php endif; ?>
					<?php if ($form->getMeta('gdpr')): ?>
						<div class="form-group">
							<label class="control-label">&nbsp;</label>
							<div class="input-checkbox"><input type="checkbox" name="gdpr" id="gdpr" class="form-control" data-validate="required"> <span><?php $i18n->translate('form.span.gdpr'); ?></span></div>
						</div>
					<?php endif; ?>
				</div>
				<div class="form-actions text-right">
					<button type="submit" class="button button-primary"><?php $i18n->translate('globals.button'); ?></button>
				</div>
			</form>
			<div class="form-actions text-center">
					<img src="../assets/images/payment-form/credit-cards.jpg"  class="img-responsive">
			</div>
		</div>
	</div>