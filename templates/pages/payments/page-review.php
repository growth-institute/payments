<?php $this->partial('payments/header-html'); ?>
	<?php $this->partial('payments/header'); ?>

		<section class="section section-form">

			<nav class="payment-navigation">
				<div class="inner boxfix-vert">
					<div class="margins-horz">
						<?php if(get_item($_GET, 'error')): ?>
							<div class="message message-error"><strong><?php echo "Error: ".$_GET['error']; ?></strong></div>
						<?php endif; ?>
						<ul class="menu">
							<li class="menu-item menu-item-info"><a href="#"><?php $i18n->translate('form.title.student'); ?></a></li>
							<li class="menu-item menu-item-payment"><a href="#"  class="active"><?php $i18n->translate('form.title.menu-payment'); ?></a></li>
						</ul>
					</div>
				</div>
			</nav>

			<div class="payment-content">
				<div class="inner boxfix-vert">
					<div class="margins-horz">
						<div class="row row-md">
							<div class="col col-8 col-md-8">
								<div class="the-content">
									<?php $site->partial('payments/payment', ['form' => $form, 'order' => $order, 'processors' => $processors]); ?>
								</div>
							</div>
							<div class="col col-4 col-md-4">
								<?php $site->partial('payments/product', ['form' => $form, 'order' => $order]); ?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<?php $this->partial('payments/features'); ?>
		</section>

	<?php $this->partial('payments/footer'); ?>
<?php $this->partial('payments/footer-html'); ?>