<?php
	$parsedown = new Parsedown();
?>
	<div class="product-info boxfix-vert">
		<div class="margins">

			<?php
				$id_attachment = $form->getMeta("product_image");
				if ($id_attachment):
					$attachment_image = Attachments::getById($id_attachment);
			?>
				<img class="img-responsive product-image" src="<?php echo($attachment_image->url);?>" alt="<?php sanitized_print($form->name); ?>">
			<?php endif; ?>

			<div class="the-content">
				<h1 class="section-title"><?php $form->getMeta('public_name') ? sanitized_print($form->getMeta('public_name')) : sanitized_print($form->name); ?></h1>
				<?php echo $parsedown->text( get_item($form->metas, 'product_description') ); ?>
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

				<?php $periodicity = $form->getMeta('periodicity') ? $i18n->translate('periodicity.option' . $form->getMeta('periodicity'), false) : false; ?>

				<div class="row row-md">
					<div class="col col-6 total-total">
						<h3><?php $i18n->translate('sidebar.product.h3-total'); ?></h3>
					</div>
					<div class="col col-6 total-prices">
						<?php if ($form->currency == 'mxn' && $form->getMeta('exchange_rate')): ?>
								<span class="total-price js-total-price-mxn">$<?php echo number_format(isset($form) ? (float) $form->total / $form->getMeta('exchange_rate') : (float) $form->total, 2); ?> USD <?php echo $periodicity; ?></span>
						<?php else: ?>
							<span class="total-price js-total-price">$<?php echo number_format(isset($order) ? $order->total : $form->total, 2); ?> <?php echo strtoupper($form->currency); ?> <?php echo $periodicity; ?></span>
						<?php endif; ?>
							<!-- <span class="total-price js-quantity2"></span> -->
					</div>
				</div>
				<div class="row row-md">
					<div class="col col-12 price-mxn">
						<?php if ($form->currency == 'mxn' && $form->getMeta('exchange_rate')): ?>
							<span class="js-price-mxn"> equivale a:$<?php echo number_format(isset($order) ? $order->total : $form->total, 2); ?> <?php echo strtoupper($form->currency); ?> <?php echo $periodicity; ?></span>
						<?php endif; ?>
					</div>
				</div>
					<div class="row row-md row-collapse">

					<?php if($form->getMeta('trial_days')): ?>

						<div class="col col-6 total-total">
							<h3>Ahora</h3>
						</div>
						<div class="col col-6 total-prices">
							<span class="total-price js-total-price">$<?php echo number_format(0, 2); ?> <?php echo strtoupper($form->currency); ?></span>
						</div>
						<br><br>

						<div class="col col-6 total-total">
							<h3><small>Al final del periodo de pruebas</small></h3>
						</div>
						<div class="col col-6 total-prices">
							<span class="total-price js-total-price"><small>$<?php echo number_format(isset($order) ? $order->total : $form->total, 2); ?> <?php echo strtoupper($form->currency); ?> / mensual</small></span>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>