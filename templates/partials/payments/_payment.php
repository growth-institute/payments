<?php if ($processors): ?>
	<?php asort($processors); ?>

	<h2><?php echo $form->getMeta('form_title_payment', $i18n->translate('form.title.payment', false)) ; ?></h2>

	<?php if(count($processors) > 0): ?>
		<ul class="tab-list payment-method <?php echo count($processors) == 1 ? 'hide' : ''; ?>">
			<?php foreach ($processors as $name => $processor): ?>
				<li><a href="#tab-<?php sanitized_print( strtolower($name) ); ?>"><?php echo $form->getMeta("{$processor->name}_tab_title", $processor->getTitle()) ; ?></a></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	<div class="tabs">
		<?php
			if ($processors):
				foreach ($processors as $name => $processor):
		?>
			<div class="tab" id="tab-<?php sanitized_print( strtolower($name) ); ?>">
				<h3><?php echo $form->getMeta(strtolower("{$name}_tab_title"), $processor->getTitle()) ; ?></h3>

				<?php if($copy = $form->getMeta('pre_payment_copy')): ?>
					<div class="the-content">
						<?php echo $copy; ?>
					</div>
				<?php endif; ?>


				<?php $processor->getMarkup($form, $order); ?>
			</div>
		<?php
				endforeach;
			endif;
		?>
	</div>
<?php endif; ?>