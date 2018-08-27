<?php $this->partial('payments/header-html'); ?>
	<?php $this->partial('payments/header'); ?>

		<section class="section section-thanks">
			<div class="inner boxfix-vert">
				<div class="margins">
					<div class="the-content">
						<h1 class="section-title"><?php $i18n->translate('thanks.h1'); ?></h1>
						<h3><?php $i18n->translate('thanks.h3'); ?></h3>
						<p><?php $i18n->translate('thanks.p'); ?></p>
						<p><?php $i18n->translate('thanks.p.title'); ?></p>
						<ul>
							<!-- <li>Order #<?php sanitized_print($order->id); ?></li> -->
							<li><?php $i18n->translate('thanks.li.concept'); ?> <?php sanitized_print( $order->getMeta('concept') ); ?></li>
							<li><?php $i18n->translate('thanks.li.amount'); ?> $<?php sanitized_print(number_format($order->total, 2)); ?> <?php sanitized_print( strtoupper($order->currency) ); ?></li>
						</ul>
					</div>
				</div>
			</div>
		</section>

	<?php $this->partial('payments/footer'); ?>
<?php $this->partial('payments/footer-html'); ?>