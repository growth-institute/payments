<?php
	$parsedown = new Parsedown();
?>

	<div class="product-info boxfix-vert">
		<div class="margins">
			<div class="the-content">
				<h1 class="section-title"><?php sanitized_print($form->name); ?></h1>
				<?php echo $parsedown->text( get_item($form->metas, 'product_description') ); ?>
			</div>

			<div class="product-total">
				<div class="row row-md">
					<div class="col col-6 total-total">
						<h3>Total:</h3>
					</div>
					<div class="col col-6 total-prices">
						<span class="new-price total-price">$<?php echo number_format($form->total, 2); ?> <?php echo strtoupper($form->currency); ?></span>
					</div>
				</div>
			</div>
		</div>
	</div>