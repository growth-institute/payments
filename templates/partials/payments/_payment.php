	<h2>Payment Detail</h2>
	<ul class="tab-list payment-method">
		<?php
			if ($processors):
				foreach ($processors as $name => $processor):
		?>
			<li><a href="#tab-<?php sanitized_print( strtolower($name) ); ?>"><?php sanitized_print( $processor->getTitle() ); ?></a></li>
		<?php
				endforeach;
			endif;
		?>
	</ul>
	<div class="tabs">
		<?php
			if ($processors):
				foreach ($processors as $name => $processor):
		?>
			<div class="tab" id="tab-<?php sanitized_print( strtolower($name) ); ?>">
				<h3><?php sanitized_print( $processor->getTitle() ); ?></h3>
				<?php $processor->getMarkup($form); ?>
			</div>
		<?php
				endforeach;
			endif;
		?>
	</div>