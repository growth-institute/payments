<?php
	$parsedown = new Parsedown();
?>
	<h1 class="section-title"><?php sanitized_print($form->name); ?></h1>
	<div class="row row-md">
		<div class="col col-md-9">
			<?php echo $parsedown->text( get_item($form->metas, 'product_description') ); ?>
		</div>
		<div class="col col-md-3">
			<div class="text-right-responsive">
				<div class="h1">$<?php echo $form->total; ?> <?php echo strtoupper($form->currency); ?></div>
			</div>
		</div>
	</div>