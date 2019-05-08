<?php $this->partial('payments/header-html'); ?>
	<?php $this->partial('payments/header'); ?>

		<section class="section section-form">

			<nav class="payment-navigation">
				<div class="inner boxfix-vert">
					<div class="margins-horz">
						<?php if(get_item($_GET, 'error')): ?>
							<div class="message message-error"><strong><?php echo "Error: ".$_GET['error']; ?></strong></div>
						<?php endif; ?>
						<div class="col col-12 menu">
							<h3><?php $i18n->translate('form.title.general'); ?></h3>
						</div>
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
			<div class="inner boxfix-vert">
				<div class="margins-horz">
					<div class="row row-md">
						<div class="col col-12">
							<hr>
						</div>
					</div>
					<div class="row row-md">
						<div class="col col-2 col-md-2">
						</div>
						<div class="col col-2 col-md-2">
							<div class="the-content">
							<img src="../assets/images/payment-form/garantia100.png"  class="img-responsive">
							</div>
						</div>
						<div class="col col-6 col-md-6">
							<h3>La única MasterClass 100% Garantizada</h3><br>
							<p>Si al tomar alguna de nuestras clases sientes que este programa no es para ti, no te ayuda en tus desafíos ni te brinda información que nunca antes habías escuchado, tienes 60 días para pedir tu reembolso. Nos escribes y recibirás tu inversión de regreso. Así de fácil. 100% GARANTIZADO. 100% SIN RIESGOS. </p>
						</div>
					</div>
				</div>
			</div>
			<?php $this->partial('payments/features'); ?>
		</section>

	<?php $this->partial('payments/footer'); ?>
<?php $this->partial('payments/footer-html'); ?>