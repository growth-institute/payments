<?php $this->partial('header-html'); ?>
	<?php $this->partial('header'); ?>

		<section class="section section-home">
			<div class="inner boxfix-vert">
				<div class="margins">
					<div class="the-content">
						<h1 class="section-title">Thanks</h1>
						<h3>Your payment is being validated</h3>
						<p>We will send you an email once the payment has been verified.</p>
						<p>Payment details</p>
						<ul>
							<li>Order #<?php sanitized_print($order->id); ?></li>
							<li>Concept: <?php sanitized_print( $order->getMeta('concept') ); ?></li>
							<li>Amount: <?php sanitized_print( strtoupper($order->currency) ); ?> <?php sanitized_print($order->total); ?></li>
						</ul>
					</div>
				</div>
			</div>
		</section>

	<?php $this->partial('footer'); ?>
<?php $this->partial('footer-html'); ?>