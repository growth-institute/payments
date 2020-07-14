<?php
	$parsedown = new Parsedown();
?>
	<div class="product-info boxfix-vert">
		<div class="margins">

			<?php
				$id_attachment = $form->getMeta("product_image");
				if ($id_attachment):
					$attachment_image = Attachments::getById($id_attachment);
			?>
				<img class="img-responsive product-image" src="<?php echo($attachment_image->url);?>" alt="<?php sanitized_print($form->name); ?>">
			<?php endif; ?>

			<div class="the-content">
				<h1 class="section-title"><?php $form->getMeta('public_name') ? sanitized_print($form->getMeta('public_name')) : sanitized_print($form->name); ?></h1>
				<?php echo $parsedown->text( get_item($form->metas, 'product_description') ); ?>
			</div>
		</div>
	</div>