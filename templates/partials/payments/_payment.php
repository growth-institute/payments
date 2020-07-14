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