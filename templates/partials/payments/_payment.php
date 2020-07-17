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