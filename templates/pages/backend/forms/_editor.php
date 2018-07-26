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
								<div class="margins">
									<form action="" method="post" enctype="multipart/form-data" class="form-upload hide">
										<input type="file" name="file" id="file" class="hide">
									</form>
									<div class="uploader-area" data-input="[name=file]" data-target="process.php">
										<span class="cue-text">Drag and drop your files or click to add them</span>
									</div>
									<div class="attachments"></div>
									<a href="#" class="button button-primary button-block js-clear hide"><i class="fa fa-fw fa-trash"></i> Limpiar lista</a>

									<script type="text/template" id="partial-attachment">
										<div class="attachment" id="<%= item.uid %>">
											<span class="attachment-name"><%= item.name %></span>
											<small class="attachment-size">(<%= (item.size / 1024).toFixed(2) %> KiB)</small>
											<span class="attachment-percent">0%</span>
											<div class="attachment-progress">
												<div class="progress-bar"></div>
											</div>
										</div>
									</script>
								</div>
							</div>
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
			<div class="form-group">
				<label for="name" class="control-label">Name</label>
				<input type="text" name="name" id="email" data-validate="required" class="form-control input-block" value="<?php sanitized_print($item ? $item->name : ''); ?>">
			</div>
			<div class="form-group">
				<label for="slug" class="control-label">Slug</label>
				<input type="text" id="login" name="slug" class="form-control input-block" value="<?php sanitized_print($item ? $item->slug : ''); ?>">
			</div>
			<ul class="tab-list">
				<li class="selected">
					<a href="#tab-one">General Information</a>
				</li>
				<li>
					<a href="#tab-two">Additional Information</a>
				</li>
				<li>
					<a href="#tab-three" class="hide" id="tab-installment">Installments</a>
				</li>
				<li>
					<a href="#tab-four">Discounts</a>
				</li>
			</ul>
			<div class="tabs tabs-border">
				<div class="tab" id="tab-one">
					<div class="metabox">
						<div class="metabox-header">Generals</div>
							<div class="metabox-body">
								
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
								<div class="form-group">
								<label for="currency" class="control-label">Currency</label>
								<select class="form-control input-block" name="currency" id="currency">
									<option selected disabled>Select</option>
									<option name="usd" value="usd" <?php echo($item && $item->currency == 'usd' ? 'selected="selected"' : ''); ?> >USD</option>
									<option name="mxn" value="mxn" <?php echo($item && $item->currency == 'mxn' ? 'selected="selected"' : ''); ?> >MXN</option>
								</select>
								</div>
								
								<div class="form-group">
									<label for="subscription" class="control-label">Subscription</label>
									<select class="form-control input-block js-toggle-periodicity" name="subscription" id="subscription">
										<option value="">No</option>
										<option value="Yes" <?php echo( $item && $item->subscription == 'Yes' ? 'selected="selected"' :  ''); ?> >Yes</option>
									</select>
								</div>
								<div class="hide" id="periodicity-group">
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
									<?php
										$obj = isset($item) ? json_decode($item->processor) : false;
									?>
									<label for="processor" class="control-label">Processor</label>
									<label><input type="radio" name="processor[]" id="PayPal" value="PayPal" <?php echo( $obj && in_array('PayPal', $obj) ? 'checked="checked"' : ''); ?> class="form-control">PayPal</label>
									<label><input type="radio" name="processor[]" id="Stripe" value="Stripe" <?php echo( $obj && in_array('Stripe', $obj) ? 'checked="checked"' : ''); ?>  class="form-control">Stripe</label>
									<label><input type="radio" name="processor[]" id="conekta" value="Conekta" <?php echo($obj && in_array('Conekta', $obj) ? 'checked="checked"' : ''); ?> class="form-control">Conekta</label>
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
										<option value="Yes" <?php echo( $item && $item->getMeta('extra_seats') == 'Yes' ? 'selected="selected"' :  ''); ?> >Yes</option>
										<option value="No" <?php echo( $item && $item->getMeta('extra_seats') == 'No' ? 'selected="selected"' : ''); ?> >No</option>
									</select>
								</div>
								<div class="form-group">
									<label for="time_to_live" class="control-label">Time to live in days - leave empty for no limit</label>
									<input type="text" name="time_to_live" id="time_to_live" class="form-control input-block" value="<?php sanitized_print($item ? $item->getMeta('time_to_live') : ''); ?>">
								</div>
								<div class="form-group">
									<label for="thank_you_page" class="control-label">Thank you Page</label>
									<input type="url" name="thank_you_page" id="thank_you_page" class="form-control input-block" value="<?php sanitized_print($item ? $item->getMeta('thank_you_page') : '');?>">
								</div>
								<div class="form-group">
									<label for="product_description" class="control-label">Product Description</label>
									<textarea name="product_description" id="product_description"  class="form-control input-block" rows="10"><?php sanitized_print($item ? $item->getMeta('product_description') : '');?></textarea> 
								</div>
							</div>
					</div>
				</div>
				<div class="tab" id="tab-three">
					<div class="metabox" >
						<div class="metabox-header">Installments</div>
							<div class="metabox-body">
								<div class="form-group">
									<label class="control-label">Select your payment option: </label>
									<?php
										$installments = isset($item) ? $item->getMeta('installments') : false;
									?>
									<label class="control-label"><input class="form-control" type="checkbox" name="installments[]" value="3" <?php echo( $installments && in_array('3',$installments) ? 'checked="checked"' : ''); ?> >Every 3 months</label>
									<label class="control-label"><input type="checkbox" class="form-control" name="installments[]" value="6" <?php echo( $installments && in_array('6', $installments) ? 'checked="checked"' : ''); ?> >Every 6 months</label>
									<label class="control-label"><input type="checkbox" class="form-control" name="installments[]" value="9" <?php echo( $installments && in_array('9', $installments) ? 'checked="checked"' : ''); ?>>Every 9 months</label>
									<label class="control-label"><input type="checkbox" class="form-control" name="installments[]" value="12" <?php echo( $installments && in_array('12', $installments) ? 'checked="checked"' : '' );?> >Every 12 months</label>
								</div>
							</div>
					</div>
				</div>
				<div class="tab" id="tab-four">
					<div class="metabox">
						<div class="metabox-header">Discounts</div>
							<div class="metabox-body">
								<div class="repeater repeater-discount" data-partial="#partial-repeater-discount">
									<div class="repeater-items">
									</div>
									<div class="repeater-actions">
										<a href="#" class="button button-primary js-repeater-add">Add</a>
									</div>
									<script type="text/template" id="partial-repeater-discount">
										<div class="repeater-item">
											<div class="item-grip">
												<div class="grip-number"><span><%= number %></span></div>
											</div>
											<div class="item-actions">
												<a href="#" class="item-action action-insert js-repeater-insert"><i class="fa fa-plus"></i></a>
												<a href="#" class="item-action action-delete js-repeater-delete"><i class="fa fa-minus"></i></a>
											</div>
											<div class="item-controls">
												<div class="row row-md row-5">
													<div class="col col-md-4 ">
														<div class="form-group">
															<label for="range_<%= number %>" class="control-label">Range</label>
															<input type="text" name="range[]" id="range_<%= number %>" class="form-control input-block" value="">
														</div>
													</div>
													<div class="col col-md-4">
														<div class="form-group">
															<label for="val_<%= number %>" class="control-label">val</label>
															<input type="text" name="val[]" id="val_<%= number %>" class="form-control input-block" value="">
														</div>
													</div>
													<div class="col col-md-4">
														<div class="form-group">
															<label for="type_<%= number %>" class="control-label">Type</label>
															<input type="text" name="type[]" id="type_<%= number %>" class="form-control input-block" value="">
														</div>
													</div>
												</div>
											</div>
										</div>
									</script>
								</div>
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>