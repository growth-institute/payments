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
				<h1 class="section-title"><?php sanitized_print($form->name); ?></h1>
				<?php echo $parsedown->text( get_item($form->metas, 'product_description') ); ?>
			</div>

			<div class="product-total">

				<?php if($form->getMeta('quantity')): ?>
					<div class="row row-md">
						<div class="col col-6 total-total">
							<h3><?php $i18n->translate('sidebar.product.h3-price'); ?></h3>
						</div>
						<div class="col col-6 total-prices">
							<span class="total-price">$<?php echo number_format($form->total, 2); ?> <?php echo strtoupper($form->currency); ?></span>
						</div>
					</div>
					<div class="row row-md">
						<div class="col col-6 total-total">
							<h3><?php echo $form->getMeta('extra_seats_price') && !$form->getMeta('discounts') ? 'Extra Seats' : 'Quantity'; ?>:</h3>
						</div>
						<div class="col col-6 total-prices">
							<span class="total-price js-quantity"><?php echo $form->getMeta('extra_seats_price') && !$form->getMeta('discounts') && isset($order)  ? $order->getMeta('quantity') : (isset($order) && $order->getMeta('quantity') ? ( $order->getMeta('quantity_info') ?: $order->getMeta('quantity') ) : 1); ?><?php if($form->getMeta('extra_seats_price') && !$form->getMeta('discounts')): ?>  &times;  <?php echo number_format($form->getMeta('extra_seats_price'), 2); ?> <?php echo strtoupper($form->currency); ?><?php endif; ?></span>
						</div>
					</div>
				<?php endif; ?>
				<div class="row row-md">
					<div class="col col-6 total-total">
						<h3><?php $i18n->translate('sidebar.product.h3-total'); ?></h3>
					</div>
					<div class="col col-6 total-prices">
						<span class="total-price js-total-price">$<?php echo number_format(isset($order) ? $order->total : $form->total, 2); ?> <?php echo strtoupper($form->currency); ?></span>
					</div>
				</div>
			</div>
		</div>
	</div>