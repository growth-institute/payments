<div class="product-total">
	<?php if($form->getMeta('quantity')): ?>
		<div class="row row-md">
			<div class="col col-6 total-total">
				<h3><?php $i18n->translate('sidebar.product.h3-price'); ?></h3>
			</div>
			<div class="col col-6 total-prices">
				<?php if($form->getMeta('exchange_rate')): ?>
					<span class="total-price">$<?php echo number_format($form->total / $form->getMeta('exchange_rate'), 2); ?> USD</span>
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
					<span class="total-price js-quantity"><?php echo $form->getMeta('extra_seats_price') && !$form->getMeta('discounts') && isset($order)  ? $order->getMeta('quantity') : (isset($order) && $order->getMeta('quantity') ? ( $order->getMeta('quantity_info') ?: $order->getMeta('quantity') ) : 1); ?><?php if($form->getMeta('extra_seats_price') && !$form->getMeta('discounts')): ?>  &times;  <?php echo number_format($form->getMeta('extra_seats_price') / $form->getMeta('exchange_rate'), 2); ?> <?php echo 'USD'; ?><?php endif; ?></span>
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
					<span class="total-price js-total-price">$<?php echo number_format(isset($order) ? (float)$order->total / $form->getMeta('exchange_rate') : (float)$form->total / $form->getMeta('exchange_rate'), 2); ?> USD</span>
			<?php else: ?>
				<span class="total-price js-total-price">$<?php echo number_format(isset($order) ? $order->total : $form->total, 2); ?> <?php echo strtoupper($form->currency); ?></span>
			<?php endif; ?>
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
	<hr>
<?php if ($processors): ?>
	<?php asort($processors); ?>
	<h2><?php $i18n->translate('form.title.payment'); ?></h2>
	<?php if(count($processors) > 0): ?>
		<ul class="tab-list payment-method <?php echo count($processors) == 1 ? 'hide' : ''; ?>">
			<?php foreach ($processors as $name => $processor): ?>
				<li><a href="#tab-<?php sanitized_print( strtolower($name) ); ?>"><?php sanitized_print( $processor->getTitle() ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	<div class="tabs">
		<?php
			if ($processors):
				foreach ($processors as $name => $processor):
		?>
			<div class="tab" id="tab-<?php sanitized_print( strtolower($name) ); ?>">
				<h3><?php sanitized_print( $processor->getTitle() ); ?></h3>
				<?php $processor->getMarkup($form, $order); ?>
			</div>
		<?php
				endforeach;
			endif;
		?>
	</div>
<?php endif; ?>

<hr>
<div class="form-actions text-center">
	<img src="../assets/images/payment-form/credit-cards.jpg"  class="img-responsive">
</div>