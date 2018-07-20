<form action="" method="post" enctype="multipart/form-data">
	<div class="panel-wrapper fixed-right">
		<div class="panel-fixed">
			<div class="metabox">
				<div class="metabox-header">Properties</div>
				<div class="metabox-body" id="formgeneral">
					<div class="form-group">
						
						<?php
							$id_attachment = $item ? $item->getMeta("product_image") : false;
							if ($id_attachment):
								$attachment_image = Attachments::getById($id_attachment);
						?>
							<label for="image" class="control-label">Current Image</label>
							<img class="img-responsive" src="<?php echo($attachment_image->url);?>" alt="image_products">
						<?php endif; ?>
						<div class="form-group">
							<label for="quantity" class="control-label">Quantity</label>
							<input type="number" name="quantity" id="quantity" value="<?php sanitized_print($item ? $item->getMeta('quantity') : '');?>" class="form-control input-block">
						</div>
						<div class="form-group">
							<label for="total" class="control-label">Total</label>
							<input type="number" name="total" id="total" class="form-control input-block" value="<?php sanitized_print($item ? $item->total : ''); ?>">
						</div>
						<div class="form-group">
							<?php
								$obj = isset($item) ? json_decode($item->processor) : false;
							?>
							<label for="processor" class="control-label">Processor</label>
							<label><input type="checkbox" name="processor[]" id="processor" value="PayPal" <?php echo( $obj && in_array('PayPal', $obj) ? 'checked="checked"' : ''); ?> class="form-control">PayPal</label>
							<label><input type="checkbox" name="processor[]" id="processor" value="Stripe" <?php echo( $obj && in_array('Stripe', $obj) ? 'checked="checked"' : ''); ?>  class="form-control">Stripe</label>
							<label><input type="checkbox" name="processor[]" id="processor" value="Conekta" <?php echo($obj && in_array('Conekta', $obj) ? 'checked="checked"' : ''); ?> class="form-control">Conekta</label>
						</div>
						<div class="form-group">
							<label for="currency" class="control-label">Currency</label>
							<select class="form-control input-block" name="currency">
								<option selected disabled>Select</option>
								<option name="usd" value="usd" <?php echo($item && $item->currency == 'usd' ? 'selected="selected"' : ''); ?> >USD</option>
								<option name="mxn" value="mxn" <?php echo($item && $item->currency == 'mxn' ? 'selected="selected"' : ''); ?> >MXN</option>
							</select>
						</div>
						<div class="form-group">
							<?php
								$slug = $item ? $item->slug : false;
									if($slug):
							?>
										<div class="field">
											<span class="field-name">Link</span>
											<span class="field-value"><input type="text" readonly  class="form-control input" value="<?php echo('https://payments.growthinstitute.com/form/'.$item->slug); ?>"> <a href="#" class="button button-primary js-copy"><i class="fa fa-fw fa-copy"></i></a></span>
											<span class="span">Text copied!</span>
										</div>
							<?php endif; ?>
						</div>
						
					</div>
					<div class="text-right">
						
						<a href="<?php $site->urlTo("/backend/forms/", true); ?>" class="button button-link">Go back</a>
						<button type="submit" class="button button-primary">Save changes</button>
					</div>
				</div>
			</div>
		</div>
		<div class="panel-fluid">
			<ul class="tab-list">
				<li class="selected">
					<a href="#tab-one">General Information</a>
				</li>
				<li>
					<a href="#tab-two">Additional Information</a>
				</li>
				<li>
					<a href="#tab-three">Conekta</a>
				</li>
			</ul>
			<div class="tabs tabs-border">
				<div class="tab" id="tab-one">
					<div class="metabox">
						<div class="metabox-header">Generals</div>
							<div class="metabox-body">
								<div class="form-group">
									<label for="name" class="control-label">Name</label>
									<input type="text" name="name" id="email" class="form-control input-block" value="<?php sanitized_print($item ? $item->name : ''); ?>">
								</div>
									<div class="form-group">
										<label for="slug" class="control-label">Slug</label>
										<input type="text" id="login" name="slug" class="form-control input-block" value="<?php sanitized_print($item ? $item->slug : ''); ?>">
									</div>
								<div class="form-group">
									<?php 
										$decode = isset($item) ? json_decode($item->products) : false;
										$implode = isset($item) ? implode(',', $decode) : false;
									?>
									<label for="products" class="control-label">Products</label>
									<input type="text" name="products" id="products" class="form-control input-block" value="<?php sanitized_print($decode && $implode ? $implode : '');?>">
								</div>
								<div class="form-group">
									<label for="language" class="control-label">Language</label>
									<select class="form-control input-block" name="language">
										<option disabled selected>Select</option>
										<option name="English"  value="English" <?php echo( $item && $item->language == 'English' ? 'selected="selected"' :  ''); ?> >English</option>
										<option name="Spanish"  value="Spanish" <?php echo( $item && $item->language == 'Spanish' ? 'selected="selected"' : ''); ?> >Spanish</option>
									</select>
								</div>
							</div>
					</div>
				</div>
				<div class="tab" id="tab-two">
					<div class="metabox">
						<div class="metabox-header">Additional Information</div>
							<div class="metabox-body">
								<div class="form-group">
									<label for="extra_seats" class="control-label">Extra Seats</label>
									<select class="form-control input-block" name="extra_seats">
										<option disabled selected>Select</option>
										<option name="Yes"  value="Yes" <?php echo( $item && $item->getMeta('extra_seats') == 'Yes' ? 'selected="selected"' :  ''); ?> >Yes</option>
										<option name="No"  value="No" <?php echo( $item && $item->getMeta('extra_seats') == 'No' ? 'selected="selected"' : ''); ?> >No</option>
									</select>
								</div>
								<div class="form-group">
									<label for="time_to_live" class="control-label">Time to live in days - leave empty for no limit</label>
									<input type="text" name="time_to_live" id="time_to_live" class="form-control input-block" value="<?php sanitized_print($item ? $item->getMeta('time_to_live') : ''); ?>">
								</div>
								<div class="form-group">
									<label for="subscription" class="control-label">Subscription</label>
									<select class="form-control input-block" name="subscription" id="subscription">
										<option disabled selected>Select</option>
										<option name="Yes" id="Yes" value="Yes" <?php echo( $item && $item->subscription == 'Yes' ? 'selected="selected"' :  ''); ?> >Yes</option>
										<option name="No"  value="No" <?php echo( $item && $item->subscription == 'No' ? 'selected="selected"' : ''); ?> >No</option>
									</select>
								</div>
								<div id="periodicity-group">
									<div class="form-group">
										<label for="periodicity"  id="label_periodicity" class="control-label">Periodicity</label>
										<select name="periodicity" id="periodicity" class="form-control input-block">
											<option disabled selected>Select</option>
											<option name="monthly" value="monthly" <?php echo( $item && $item->getMeta('periodicity') == 'monthly' ? 'selected="selected"' : ''); ?> >Monthly</option>
											<option name="3_months" value="3_months" <?php echo( $item && $item->getMeta('periodicity') == '3_months' ? 'selected="slected"' : ''); ?> >Every 3 months</option>
											<option name="6_months" value="6_months" <?php echo( $item && $item->getMeta('periodicity') == '6_months' ? 'selected="selected"' : ''); ?> >Every 6 months</option>
											<option name="annual" value="annual" <?php echo( $item && $item->getMeta('periodicity') == 'annual' ? 'selected="selected"' : ''); ?> >Annual</option>
										</select>
									</div>
									<div class="form-group">

										<label for="ocurrency" id="label_ocurrency"class="control-label">Ocurrency</label>
										<input type="number" min="0" name="ocurrency" id="ocurrency" value="<?php sanitized_print($item ? $item->getMeta('ocurrency') : ''); ?>" class="form-control input-block">
										<div class="help-block" id="ocurrency_message">Zero ocurrency is unlimited</div>
									</div>
								</div>
								<div class="form-group">
									<label for="thank_you_page" class="control-label">Thank you Page</label>
									<input type="url" name="thank_you_page" id="thank_you_page" class="form-control input-block" value="<?php sanitized_print($item ? $item->getMeta('thank_you_page') : '');?>">
								</div>
								<div class="form-group">
									<label for="product_description" class="control-label">Product Description</label>
									<textarea name="product_description" id="product_description"  class="form-control input-block" rows="10"><?php sanitized_print($item ? $item->getMeta('product_description') : '');?></textarea> 
								</div>
								<div class="form-group">
									<label for="product_image" class="control-label">Product Image</label>
									<input type="file" name="product_image" id="product_image" class="form-control input-block">
									<?php 
										$name_image = isset($attachment_image) ? $attachment_image->name : false;
										 echo($name_image);
									?>
								</div>
							</div>
					</div>
				</div>
				<div class="tab" id="tab-three">
					<div class="metabox">
						<div class="metabox-header">Conekta</div>
							<div class="metabox-body">
								<div class="form-group">
									<label>TabConekta</label>
								</div>
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>