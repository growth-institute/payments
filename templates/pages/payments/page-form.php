<?php $this->partial('payments/header-html'); ?>
	<?php $this->partial('payments/header'); ?>

		<section class="section section-form">
			<nav class="payment-navigation">
				<div class="inner boxfix-vert">
					<div class="margins-horz">
						<ul class="menu">
							<li class="menu-item menu-item-info"><a href="#" class="active"><?php $i18n->translate('form.title.student'); ?></a></li>
							<li class="menu-item menu-item-payment"><a href="#"><?php $i18n->translate('form.title.menu-payment'); ?></a></li>
						</ul>
					</div>
				</div>
			</nav>

			<div class="payment-content">
				<div class="inner boxfix-vert">
					<div class="margins-horz">
						<div class="row row-md">
							<div class="col col-2 col-md-2">
							</div>
							<div class="col col-4 col-md-4">
								<div class="the-content">
									<?php $site->partial('payments/user', ['form' => $form, 'order' => $order]); ?>
								</div>
							</div>
							<div class="col col-4 col-md-4">
								<?php $site->partial('payments/product', ['form' => $form ]); ?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<?php $this->partial('payments/features'); ?>
		</section>

	<?php $this->partial('payments/footer'); ?>
<?php $this->partial('payments/footer-html'); ?>