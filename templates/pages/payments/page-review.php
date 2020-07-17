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
							<li class="menu-item menu-item-info"><a href="#" class="active"><?php $i18n->translate('tabs.menu.item-info'); ?></a></li>
							<li class="menu-item menu-item-payment"><a href="#"><?php $i18n->translate('tabs.menu.item-payment'); ?></a></li>
						</ul>
					</div>
				</div>
			</nav>

			<div class="payment-content">
				<div class="inner boxfix-vert">
					<div class="margins-horz">
						<div class="row row-md">
							<div class="col col-6 col-md-6">
								<div class="the-content">
									<?php $site->partial('payments/payment', ['form' => $form, 'order' => $order, 'processors' => $processors]); ?>
								</div>
							</div>
							<div class="col col-6 col-md-6">
								<?php $site->partial('payments/product', ['form' => $form, 'order' => $order]); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="inner boxfix-vert">
				<div class="margins-horz">
					<div class="row row-md">
						<div class="col col-12">
							<hr>
						</div>
					</div>
					<?php
						$extra = $form->getMeta('extra');
						if ($extra):
							echo $extra;
						else:
					?>
						<div class="the-content">
							<div class="row row-md">
								<div class="col col-2 col-md-2">
									<div class="the-content">
									<img src="<?php $site->img('payment-form/garantia100.png'); ?>" class="img-responsive">
									</div>
								</div>
								<div class="col col-6 col-md-6">
									<br>
									<br>
									<h3>Satisfacción 100% Garantizada</h3>
									<p>Si al tomar alguno de nuestros programas sientes que no es para ti, no te ayuda en tus desafíos ni te brinda información inédita, tienes 21 días para pedir tu reembolso. Nos escribes un correo y recibirás tu inversión de regreso. Así de fácil.</p>
									<p>100% GARANTIZADO. 100% SIN RIESGOS.</p>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php $this->partial('payments/features'); ?>
		</section>

	<?php $this->partial('payments/footer'); ?>
<?php $this->partial('payments/footer-html'); ?>