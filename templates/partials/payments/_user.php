<?php if ($order->sandbox): ?>
	<div class="message message-error"><strong><?php $i18n->translate('section.message-error'); ?></strong></div>
<?php endif; ?>
	<h2><?php $i18n->translate('form.title.student'); ?></h2>
	<div class="tabs">
		<div class="tab active">
			<form data-submit="validate" id="user-data-form" action="" method="post">
				<div class="form-fields">
					<div class="row row-md row-5">
						<div class="col col-md-12">
							<div class="form-group">
								<input type="text" name="first_name" id="first_name" placeholder="<?php $i18n->translate('form.label.first-name'); ?>*" class="form-control input-block" value="<?php sanitized_print($order ? $order->getMeta('first_name') : ''); ?>" data-validate="required">
							</div>
						</div>
						<div class="col col-md-12">
							<div class="form-group">
								<input type="text" name="last_name" id="last_name" placeholder="<?php $i18n->translate('form.label.last-name'); ?>*" class="form-control input-block" value="<?php sanitized_print($order ? $order->getMeta('last_name') : ''); ?>" data-validate="required">
							</div>
						</div>
					</div>
					<div class="row row-md row-5">
						<div class="col col-md-12">
							<div class="form-group">
								<input type="text" name="email" id="email" placeholder="<?php $i18n->translate('form.label.email'); ?>*" class="form-control input-block" value="<?php sanitized_print($order ? $order->getMeta('email') : ''); ?>" data-validate="required">
							</div>
						</div>
						<div class="col col-md-12">
							<div class="form-group">
								<input type="tel" name="phone" id="phone" placeholder="<?php $i18n->translate('form.label.phone'); ?>*" class="form-control input-block" value="<?php sanitized_print($order ? $order->getMeta('phone') : ''); ?>" data-validate="required">
							</div>
						</div>
					</div>
					<div class="form-group">
						<input type="text" name="company" id="company" placeholder="<?php $i18n->translate('form.label.company'); ?>*" class="form-control input-block" value="<?php sanitized_print($order ? $order->getMeta('company') : ''); ?>" data-validate="required">
					</div>
					<?php if ($form->id == 123 || $form->id == 124 || $form->id == 125 || $form->id == 132 || $form->id == 133 || $form->id == 134 || $form->id == 142 || $form->id == 143 || $form->id == 144): ?>
						<hr>
						<div class="form-group">
						<input type="text" name="coach" id="coach" placeholder="Scaling Up Coach name (if applicable)" class="form-control input-block">
						</div>
					<?php endif; ?>
					<?php if ($form->getMeta('growsumo')): ?>
							<input type="hidden" name="growsumo" value="1">
							<input type="hidden" name="growsumo-partner-key" value="">
					<?php endif; ?>
					<?php if ($form->getMeta('quantity')): ?>
						<div class="form-group">
							<label for="quantity" class="control-label">
								<?php if ($form->getMeta('quantity_label')): ?>
									<?php echo $form->getMeta('extra_seats_price') && !$form->getMeta('discounts') && $form->getMeta('quantity_label') ? 'Asientos extra' : $form->getMeta('quantity_label'); ?>
								<?php else: ?>
									<?php echo $form->getMeta('extra_seats_price') && !$form->getMeta('discounts')? 'Asientos extra' : 'Cantidad' ?>
								<?php endif; ?>
								<?php if($form->getMeta('extra_seats_price') && !$form->getMeta('discounts')): ?>
									<small>(<?php echo '$' .  ($form->getMeta('exchange_rate') ? number_format($form->getMeta('extra_seats_price') / $form->getMeta('exchange_rate'),2) : number_format($form->getMeta('extra_seats_price'), 2)) ?> )</small>
								<?php endif; ?>
							</label>
							<input type="number" min="<?php echo $form->getMeta('extra_seats_price') && !$form->getMeta('discounts') ? 0 : 1; ?>" name="quantity" id="quantity" value="<?php echo $form->getMeta('extra_seats_price') && !$form->getMeta('discounts') ? 0 : 1; ?>" class="input-block form-control">
							<!-- <input type="number" min="<?php sanitized_print($form->getMeta('quantity_value') ? $form->getMeta('quantity_value') : 1); ?>" name="quantity" id="quantity" value="<?php sanitized_print($form->getMeta('quantity_value') ? $form->getMeta('quantity_value') : 1); ?>" class="input-block form-control"> -->
						</div>
					<?php endif; ?>
					<?php if ($form->getMeta('gdpr')): ?>
						<div class="form-group">
							<div class="input-checkbox">
								<label><input type="checkbox" name="gdpr" id="gdpr" class="form-control"> <span><?php $i18n->translate('form.span.gdpr'); ?></span></label>
							</div>
						</div>
					<?php endif; ?>
					<?php if ($form->getMeta('coupon_codes')): ?>
						<div class="row row-md">
							<div class="col col-12">
								<div class="form-group">
									<label class="control-label">Code (If applicable)</label>
								</div>
							</div>
						</div>
						<div class="row row-md">
							<div class="col col-6">
								<div class="form-group">
									<input type="text" name="code" id="code" class="form-control input-block" value="">
									<input type="hidden" name="coupon_code" id="coupon_code" class="form-control input-block" value="">
								</div>
							</div>
							<div class="col col-6">
								<div class="form-group">
									<input type="button" class="button button-primary" id="check" value="Check Code">
								</div>
							</div>
						</div>
					<?php endif; ?>

				</div>

				<div class="product-total">

				<?php if($form->getMeta('quantity')): ?>
					<div class="row row-md">
						<div class="col col-6 total-total">
							<h3><?php $i18n->translate('sidebar.product.h3-price'); ?></h3>
						</div>
						<div class="col col-6 total-prices">
							<?php if($form->getMeta('exchange_rate')): ?>
								<span class="total-price">$<?php echo number_format((float)$form->total / $form->getMeta('exchange_rate'), 2); ?> USD</span>
							<?php else:?>
								<span class="total-price">$<?php echo number_format($form->total, 2); ?> <?php echo strtoupper($form->currency); ?></span>
							<?php endif;?>
						</div>
					</div>
					<div class="row row-md">
						<div class="col col-6 total-total">
							<h3><?php $form->getMeta('extra_seats_price') && !$form->getMeta('discounts') ? $i18n->translate('sidebar.product.h3-extra-seat') : $i18n->translate('sidebar.product.h3-quantity'); ?>:</h3>
						</div>
						<div class="col col-6 total-prices">
							<?php if($form->getMeta('exchange_rate')): ?>
								<span class="total-price js-quantity"><?php echo $form->getMeta('extra_seats_price') && !$form->getMeta('discounts') && isset($order)  ? $order->getMeta('quantity') : (isset($order) && $order->getMeta('quantity') ? ( $order->getMeta('quantity_info') ?: $order->getMeta('quantity') ) : 1); ?><?php if($form->getMeta('extra_seats_price') && !$form->getMeta('discounts')): ?>  &times;  <?php echo number_format($form->getMeta('extra_seats_price_usd'), 2); ?> <?php echo 'USD'; ?><?php endif; ?></span>
							<?php else:?>
								<span class="total-price js-quantity"><?php echo $form->getMeta('extra_seats_price') && !$form->getMeta('discounts') && isset($order)  ? $order->getMeta('quantity') : (isset($order) && $order->getMeta('quantity') ? ( $order->getMeta('quantity_info') ?: $order->getMeta('quantity') ) : 1); ?><?php if($form->getMeta('extra_seats_price') && !$form->getMeta('discounts')): ?>  &times;  <?php echo number_format($form->getMeta('extra_seats_price'), 2); ?> <?php echo strtoupper($form->currency); ?><?php endif; ?></span>
							<?php endif;?>
						</div>
					</div>
				<?php endif; ?>
				<div class="row row-md">
					<div class="col col-6 total-total">
						<h3><?php $i18n->translate('sidebar.product.h3-total'); ?></h3>
					</div>
					<div class="col col-6 total-prices">
						<?php if ($form->currency == 'mxn' && $form->getMeta('exchange_rate')): ?>
								<span class="total-price js-total-price-mxn">$<?php echo number_format(isset($form) ? (float) $form->total / $form->getMeta('exchange_rate') : (float) $form->total, 2); ?> USD</span>
						<?php else: ?>
							<span class="total-price js-total-price">$<?php echo number_format(isset($order) ? $order->total : $form->total, 2); ?> <?php echo strtoupper($form->currency); ?></span>
						<?php endif; ?>
							<!-- <span class="total-price js-quantity2"></span> -->
					</div>
				</div>
				<div class="row row-md">
					<div class="col col-12 price-mxn">
						<?php if ($form->currency == 'mxn' && $form->getMeta('exchange_rate')): ?>
							<span class="js-price-mxn"> equivale a:$<?php echo number_format(isset($order) ? $order->total : $form->total, 2); ?> <?php echo strtoupper($form->currency); ?></span>
						<?php endif; ?>
					</div>
				</div>
			</div>
				<div class="form-actions text-right">
					<button type="submit" class="button button-primary"><?php $i18n->translate('globals.button'); ?></button>
				</div>
				<?php if($form->id == 113 ): ?>
					<div class="form-actions">
							<p>Recuerda que tu primer cargo será por tan solo $1 USD y comenzarás a pagar $24.99 USD a partir del segundo mes.</p>
					</div>
				<?php endif; ?>
			</form>

			<hr>
			<?php if($form->id == 119 || $form->id == 123 || $form->id == 124 || $form->id == 125 || $form->id == 128 || $form->id == 129 || $form->id == 130 || $form->id == 132 || $form->id == 133 || $form->id == 134 || $form->id == 142 || $form->id == 143 || $form->id == 144): ?>
			<div class="form-actions text-center">
				<img src="../assets/images/payment-form/formas-de-pago.png"  class="img-responsive">
			</div>
			<?php else: ?>
			<div class="form-actions text-center">
				<img src="../assets/images/payment-form/credit-cards2.png"  class="img-responsive">
			</div>
			<?php endif; ?>
			<?php if($form->language == 'es'):?>
				<div class="form-actions">
					<p class="bank-note">
						De conformidad con la disposición transitoria octava de la Ley para Regular las Instituciones de Tecnología Financiera ("Ley Fintech") se hace constar que, previo a la fecha de entrada en vigor de la Ley Fintech, Stripe se encuentra realizando actividades reguladas para instituciones de fondos de pago electrónico ("IFPE"). 
						Por lo anterior, Stripe solicitó la autorización para operar como IFPE dentro del periodo transitorio permitido bajo la Ley Fintech y, por este medio Stripe comunica que dicha autorización se encuentra en trámite. Lo anterior en el entendido de que las actividades realizadas actualmente y durante el periodo de trámite de dicha autorización no son supervisadas por las autoridades mexicanas competentes.
					</p>
				</div>
			<?php endif;?>
		</div>
	</div>