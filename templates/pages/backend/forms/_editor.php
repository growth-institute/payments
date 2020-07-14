<?php
	$id_attachment = $item ? $item->getMeta('product_image') : false;
	$attachment_image = $id_attachment ? Attachments::getById($id_attachment) : false;
?>
<form id="payment-form" action="" method="post" data-submit="validate" enctype="multipart/form-data" >
	<div class="panel-wrapper fixed-right">
		<div class="panel-fixed">
			<div class="metabox">
				<div class="metabox-header">Properties</div>
				<div class="metabox-body" id="formgeneral">
					<div class="form-group">
						<input type="hidden" name="product_image" value="<?php echo $attachment_image ? $attachment_image->id : ''; ?>">
						<div class="form-group">
								<div class="margins-vert">
									<form action="" method="post" enctype="multipart/form-data" class="form-upload hide">
										<input type="file" name="file" id="file" class="hide">
									</form>
									<label for="image" class="control-label">Product Image</label>
									<div class="uploader-area <?php echo $attachment_image ? 'has-loaded' : ''; ?>" data-input="[name=file]" data-target="<?php $site->urlTo('/backend/forms/check-image', true); ?>">
										<?php if($attachment_image): ?>
											<img class="img-responsive" src="<?php echo($attachment_image->url);?>" alt="image_products">
										<?php else: ?>
											<span class="cue-text">Drag and drop your files or click to add them</span>
										<?php endif; ?>
									</div>
									<div class="attachments"></div>

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
							<select name="quantity" id="quantity" class="form-control input-block">
								<option value="">No</option>
								<option value="Yes" <?php echo $item && $item->getMeta('quantity') == 'Yes' ? 'selected="selected"' : ''; ?>>Yes</option>
							</select>
						</div>
						<div class="form-group">
							<label for="connector" class="control-label">Connector</label>
							<select name="connector" id="connector" class="form-control input-block">
								<option value="">Select</option>
								<option value="hummingbird" <?php echo $item && $item->getMeta('connector') == 'hummingbird' ? 'selected="selected"' : ''; ?>>Hummingbird</option>
								<option value="ti" <?php echo $item && $item->getMeta('connector') == 'ti' ? 'selected="selected"' : ''; ?>>Thought Industries</option>
							</select>
						</div>
						<div class="form-group">
							<label for="quantity_value" class="control-label">Quantity Value</label>
							<input type="text" name="quantity_value" id="quantity_value" class="form-control input-block" value="<?php sanitized_print($item ? $item->getMeta('quantity_value') : ''); ?>">
						</div>
						<div class="form-group">
							<label for="exchange_rate" class="control-label">Exchange Rate <small>(MXN to USD)</small></label>
							<input type="text" name="exchange_rate" id="exchange_rate" class="form-control input-block" value="<?php sanitized_print($item ? $item->getMeta('exchange_rate') : ''); ?>">
						</div>
						<div class="form-group">
							<label for="event" class="control-label">Event</label>
							<select name="event" id="event" class="form-control input-block" data-value="<?php sanitized_print($item ? $item->getMeta('event') : ''); ?>">
								<option value="">No</option>
								<?php
									if ($events) :
										foreach ($events as $event):
								?>
											<option value="<?php sanitized_print($event->id); ?>"><?php sanitized_print($event->name); ?></option>
								<?php
										endforeach;
									endif;
								?>
							</select>
						</div>
						<div class="form-group">
							<label for="total" class="control-label">Total</label>
							<input type="text" name="total" id="total" class="form-control input-block" data-validate="required" value="<?php sanitized_print($item ? $item->total : ''); ?>">
						</div>
					</div>
					<div class="text-right">

						<a href="<?php $site->urlTo('/backend/forms/', true); ?>" class="button button-link">Go back</a>
						<button type="submit" id='submit' class="button button-primary">Save changes</button>
					</div>
				</div>
			</div>
		</div>
		<div class="panel-fluid">
			<div class="form-group">
				<input placeholder="Add the name of your form" type="text" name="name" id="name" data-validate="required" class="form-control input-block form-control-xlarge" value="<?php sanitized_print($item ? $item->name : ''); ?>">
			</div>

			<div class="form-group">
				<p><?php $site->urlTo('/', true); ?> <input type="text" id="slug" name="slug" data-validate="required" class="form-control" size="30" value="<?php sanitized_print($item ? $item->slug : ''); ?>">
				<input type="text" id="slugroot" name="slugroot" class="linkcopy" value="<?php $site->urlTo("/form/", true); sanitized_print($item ? $item->slug : ''); ?>">
				<?php
					$slug = $item ? $item->slug : false;
						if($slug):
				?>
						<span class="field-value"><a href="#" class="button button-primary js-copy" data-clipboard-target="#slugroot"><i class="fa fa-fw fa-copy"></i></a></span>
						<span class="field-value"><a href="<?php $site->urlTo("/form/{$item->slug}", true) ?>" target="_blank" class="button button-primary"><i class="fa fa-fw fa-external-link"></i></a></span>
						<span class="field-value"><a href="<?php $site->urlTo("/form/{$item->slug}?test=ggi2018", true) ?>" target="_blank" class="button button-error"><i class="fa fa-fw fa-external-link"></i></a></span>
				<?php endif; ?>
				<span class="span">Text copied!</span>
				</p>
			</div>
			<ul class="tab-list">
				<li class="selected"><a href="#tab-section-general">General Information</a></li>
				<li><a href="#tab-section-additional">Additional Information</a></li>
				<li><a href="#tab-section-installments" class="hide" id="tab-installment">Installments</a></li>
				<li><a href="#tab-section-discounts">Discounts</a></li>
				<li><a href="#tab-section-fields">Custom Fields</a></li>
			</ul>
			<div class="tabs">
				<div class="tab tab-simple" id="tab-section-general">
					<div class="metabox metabox-simple">
						<div class="metabox-header">Generals</div>
							<div class="metabox-body">

								<div class="form-group">
									<label for="public_name" class="control-label">Public Name</label>
									<input type="text" name="public_name" id="public_name" class="form-control input-block" value="<?php sanitized_print($item ? $item->getMeta('public_name') : ''); ?>">
								</div>
								<div class="form-group">
									<?php
										$decode = isset($item) ? json_decode($item->products) : false;
										$implode = isset($item) ? implode(',', $decode) : false;
									?>
									<label for="products" class="control-label">Products (SKU) </label>
									<input type="text" name="products" id="products" class="form-control input-block" value="<?php sanitized_print($decode && $implode ? $implode : '');?>">
								</div>
								<div class="row row-md">
									<div class="col col-6 col-md-6">
										<div class="form-group">
											<label for="language" class="control-label">Language</label>
											<select class="form-control input-block" name="language" data-validate="required">
												<option value="">Select</option>
												<option value="en" <?php echo( $item && $item->language == 'en' ? 'selected="selected"' : ''); ?> >English</option>
												<option value="es" <?php echo( $item && $item->language == 'es' ? 'selected="selected"' : ''); ?> >Spanish</option>
											</select>
										</div>
									</div>
									<div class="col col-6 col-md-6">
										<div class="form-group">
										<label for="currency" class="control-label">Currency</label>
										<select class="form-control input-block" name="currency" id="currency" data-validate="required">
											<option value="">Select</option>
											<option value="usd" <?php echo($item && $item->currency == 'usd' ? 'selected="selected"' : ''); ?> >USD</option>
											<option value="mxn" <?php echo($item && $item->currency == 'mxn' ? 'selected="selected"' : ''); ?> >MXN</option>
										</select>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="subscription" class="control-label">Subscription</label>
									<select class="form-control input-block js-toggle-periodicity" name="subscription" id="subscription">
										<option value="">No</option>
										<option value="Yes" <?php echo( $item && $item->subscription == 'Yes' ? 'selected="selected"' : ''); ?>>Yes</option>
									</select>
								</div>
								<div class="hide" id="periodicity-group">
									<div class="form-group">
										<label for="coupon_subscription" class="control-label">Coupon Subscription (applies only in the first month %off)</label>
										<input type="text" name="coupon_subscription" id="coupon_subscription" class="form-control input-block" value="<?php sanitized_print($item ? $item->getMeta('coupon_subscription') : ''); ?>">
									</div>
									<div class="form-group">
										<label for="periodicity" id="label_periodicity" class="control-label">Periodicity</label>
										<select name="periodicity" id="periodicity" class="form-control input-block">
											<option value="">Select</option>
											<option name="monthly" value="1" <?php echo( $item && $item->getMeta('periodicity') == '1' ? 'selected="selected"' : ''); ?> >Monthly</option>
											<option name="3_months" value="3" <?php echo( $item && $item->getMeta('periodicity') == '3' ? 'selected="slected"' : ''); ?> >Every 3 months</option>
											<option name="6_months" value="6" <?php echo( $item && $item->getMeta('periodicity') == '6' ? 'selected="selected"' : ''); ?> >Every 6 months</option>
											<option name="annual" value="12" <?php echo( $item && $item->getMeta('periodicity') == '12' ? 'selected="selected"' : ''); ?> >Annual</option>
										</select>
									</div>
									<!-- <div class="form-group">
										<label for="ocurrency" id="label_ocurrency" class="control-label">Ocurrency</label>
										<input type="number" min="0" name="ocurrency" id="ocurrency" value="<?php sanitized_print($item ? $item->getMeta('ocurrency') : ''); ?>" class="form-control input-block">
										<div class="help-block" id="ocurrency_message">Zero ocurrency is unlimited</div>
									</div> -->
								</div>
								<div class="form-group form-group-processors">
									<?php
										$obj = isset($item) ? json_decode($item->processor) : false;
									?>
									<label for="processor" class="control-label">Processor</label>
									<label id="lbstripe" class="hide"><input type="checkbox" name="processor[]" id="stripe" value="Stripe" <?php echo( $obj && in_array('Stripe', $obj) ? 'checked="checked"' : ''); ?> class="form-control ">Stripe</label>
									<label id="lbconekta" class="hide"><input type="checkbox" name="processor[]" id="conekta" value="Conekta" <?php echo($obj && in_array('Conekta', $obj) ? 'checked="checked"' : ''); ?> class="form-control ">Conekta</label>
									<label id="lbpaypal" class="hide"><input type="checkbox" name="processor[]" id="paypal" value="PayPal" <?php echo( $obj && in_array('PayPal', $obj) ? 'checked="checked"' : ''); ?> class="form-control ">PayPal</label>
									<label id="lbpayu" class="hide"><input type="checkbox" name="processor[]" id="payu" value="Payu" <?php echo( $obj && in_array('Payu', $obj) ? 'checked="checked"' : ''); ?> class="form-control ">Payu</label>
								</div>
							</div>
					</div>
				</div>
				<div class="tab tab-simple" id="tab-section-additional">
					<div class="metabox metabox-simple">
						<div class="metabox-header">Additional Information</div>
							<div class="metabox-body">
								<div class="row row-md row-5">
									<div class="col col-md-12">
										<div class="form-group">
											<label for="extra_seats_price" class="control-label">Extra Seats Price</label>
											<input type="text" name="extra_seats_price" id="extra_seats_price" class="form-control input-block" value="<?php sanitized_print($item ? $item->getMeta('extra_seats_price') : ''); ?>">
										</div>
									</div>
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
									<label for="contact_list" class="control-label">ID Hubspot List</label>
									<input type="text" class="form-control" name="id_list" id="id_list" value="<?php sanitized_print($item ? $item->getMeta('id_list') : ''); ?>">
									<!-- <select class="form-control input-block" name="id_list" id="id_list" data-value="<?php sanitized_print($item ? $item->getMeta('id_list') : ''); ?>">
										<option value="">Select</option>
										<?php
										if(isset($hubspot_list) && $hubspot_list):
											foreach($hubspot_list->lists as $list):
										?>
												<option value="<?php sanitized_print($list->listId); ?>"><?php sanitized_print($list->name); ?></option>
										<?php
											endforeach;
										endif;
									?>
									</select> -->
								</div>
								<div class="form-group">
									<label for="quantity_label" class="control-label">Quantity Label</label>
									<input name="quantity_label" id="quantity_label" class="form-control input-block" value="<?php sanitized_print($item ? $item->getMeta('quantity_label') : '');?>" >
								</div>
								<div class="form-group">
									<label for="product_description" class="control-label">Product Description</label>
									<textarea name="product_description" id="product_description" class="form-control input-block" rows="10"><?php sanitized_print($item ? $item->getMeta('product_description') : '');?></textarea>
								</div>
								<div class="form-group">
									<label for="extra" class="control-label">Extra</label>
									<textarea name="extra" id="extra" class="form-control input-block" rows="10"><?php sanitized_print($item ? $item->getMeta('extra') : '');?></textarea>
								</div>
								<div class="form-group">
									<label class="control-label"><input class="form-control" type="checkbox" name="growsumo" <?php echo(isset($item) && $item->getMeta('growsumo') == 'on' ? 'checked="checked"' : false); ?> >Activate GrowSumo</label>
								</div>
								<div class="form-group">
									<label class="control-label"><input class="form-control" type="checkbox" name="gdpr" <?php echo(isset($item) && $item->getMeta('gdpr') == 'on' ? 'checked="checked"' : false); ?> >Activate GDPR</label>
								</div>
								<div class="form-group">
									<label class="control-label"><input class="form-control" type="checkbox" name="send_notification" <?php echo(isset($item) && $item->getMeta('send_notification') == 'on' ? 'checked="checked"' : false); ?> >Send Notification</label>
								</div>
							</div>
					</div>
				</div>
				<div class="tab tab-simple" id="tab-section-installments">
					<div class="metabox metabox-simple">
						<div class="metabox-header">Installments</div>
							<div class="metabox-body">

								<div class="conekta-installments">
									<div class="form-group">
										<label class="control-label">Select your payment option: </label>
										<?php
											$installments = isset($item) ? $item->getMeta('installments') : false;
										?>
										<label class="control-label"><input disabled type="checkbox" class="form-control" name="installments[]" value="3" data-validate="checked" data-param="at least 1" <?php echo( $installments && in_array('3',$installments) ? 'checked="checked"' : ''); ?> > 3 months</label>
										<label class="control-label"><input disabled type="checkbox" class="form-control" name="installments[]" value="6" <?php echo( $installments && in_array('6', $installments) ? 'checked="checked"' : ''); ?> > 6 months</label>
										<label class="control-label"><input disabled type="checkbox" class="form-control" name="installments[]" value="9" <?php echo( $installments && in_array('9', $installments) ? 'checked="checked"' : ''); ?>> 9 months</label>
										<label class="control-label"><input disabled type="checkbox" class="form-control" name="installments[]" value="12" <?php echo( $installments && in_array('12', $installments) ? 'checked="checked"' : '' );?> > 12 months</label>
									</div>
								</div>

								<div class="stripe-installments">
									<div class="form-group">
										<label class="control-label"><input <?php echo isset($item) && $item && $item->getMeta('stripe_installments') ? 'checked="checked"' : ''; ?> type="checkbox" name="stripe-installments" id="stripe-installments" value="Yes">Activate Stripe Installments?</label>
										<div id="section-stripe-installments" class="form-group hide">
											<label class="control-label">Select your payment option: </label>
											<?php
												$stripe_installments = isset($item) ? $item->getMeta('array_stripe_installments') : false;
											?>
											<label class="control-label"><input disabled type="checkbox" class="form-control" name="array_stripe_installments[]" value="3" data-validate="checked" data-param="at least 1" <?php echo( $stripe_installments && in_array('3',$stripe_installments) ? 'checked="checked"' : ''); ?> > 3 months</label>
											<label class="control-label"><input disabled type="checkbox" class="form-control" name="array_stripe_installments[]" value="6" <?php echo( $stripe_installments && in_array('6', $stripe_installments) ? 'checked="checked"' : ''); ?> > 6 months</label>
											<label class="control-label"><input disabled type="checkbox" class="form-control" name="array_stripe_installments[]" value="9" <?php echo( $stripe_installments && in_array('9', $stripe_installments) ? 'checked="checked"' : ''); ?>> 9 months</label>
											<label class="control-label"><input disabled type="checkbox" class="form-control" name="array_stripe_installments[]" value="12" <?php echo( $stripe_installments && in_array('12', $stripe_installments) ? 'checked="checked"' : '' );?> > 12 months</label>
											<label class="control-label"><input disabled type="checkbox" class="form-control" name="array_stripe_installments[]" value="18" <?php echo( $stripe_installments && in_array('18', $stripe_installments) ? 'checked="checked"' : '' );?> > 18 months</label>
									</div>
									</div>
								</div>
							</div>
					</div>
				</div>
				<div class="tab tab-simple" id="tab-section-discounts">
					<div class="metabox metabox-simple">
						<div class="metabox-header">Range Discounts</div>
							<div class="metabox-body">
								<div class="repeater repeater-discount" data-partial="#partial-repeater-discount">

									<div class="repeater-items">

											<?php
												$discounts = isset($item) ? $item->getMeta("discounts") : false;
												$counter = 1;
												if ($discounts) :
													foreach ($discounts as $discount => $value) :
											?>
													<div class="repeater-item" id="repeater-item-ranges">
														<div class="item-grip">
															<div class="grip-number"><span><?php echo($counter++); ?></span></div>
														</div>
														<div class="item-actions">
															<a href="#" class="item-action action-insert js-repeater-insert"><i class="fa fa-plus"></i></a>
															<a href="#" class="item-action action-delete js-repeater-delete"><i class="fa fa-minus"></i></a>
														</div>
														<div class="item-controls">
															<div class="row row-md row-5">
																<div class="col col-md-2">
																	<div class="form-group">
																		<label for="range_<?php echo $counter; ?>" class="control-label">Range From</label>
																		<input type="number" data-validate="required" min="0" name="from[]" id="from_<?php echo $counter; ?>" class="form-control input-block" value="<?php echo $value['from']; ?>">
																	</div>
																</div>
																<div class="col col-md-2 ">
																	<div class="form-group">
																		<label for="range_<?php echo $counter; ?>" class="control-label">Range To</label>
																		<input type="number" data-validate="required" min="0" name="to[]" id="to_<?php echo $counter; ?>" class="form-control input-block" value="<?php echo $value['to']; ?>">
																	</div>
																</div>
																<div class="col col-md-4">
																	<div class="form-group">
																		<label for="val_<?php echo $counter; ?>" class="control-label">Value</label>
																		<input type="number" data-validate="required" min="0" name="val[]" step='Any' id="val_<?php echo $counter; ?>" class="form-control input-block" value="<?php echo $value['val']; ?>">
																	</div>
																</div>
																<div class="col col-md-4">
																	<div class="form-group">
																		<label for="type_<?php echo $counter; ?>" class="control-label">Type</label>
																		<select name="type[]" data-validate="required" id="type_<?php echo $counter; ?>" class="form-control input-block">
																			<option value="percentage" <?php echo $value['type'] == 'percentage' ? 'selected' : ''; ?>>Percentage</option>
																			<option value="amount" <?php echo $value['type'] == 'amount' ? 'selected' : ''; ?>>Fixed Amount</option>
																		</select>
																	</div>
																</div>
															</div>
														</div>
													</div>
											<?php
													endforeach;
												endif;
											?>
									</div>
									<div class="repeater-actions">
										<a href="#" class="button button-primary js-repeater-add">Add</a>
									</div>
									<script type="text/template" id="partial-repeater-discount">
										<div class="repeater-item" id="repeater-item-ranges">
											<div class="item-grip">
												<div class="grip-number"><span><%= number %></span></div>
											</div>
											<div class="item-actions">
												<a href="#" class="item-action action-insert js-repeater-insert"><i class="fa fa-plus"></i></a>
												<a href="#" class="item-action action-delete js-repeater-delete"><i class="fa fa-minus"></i></a>
											</div>
											<div class="item-controls">
												<div class="row row-md row-5">
													<div class="col col-md-2">
														<div class="form-group">
															<label for="range_<%= number %>" class="control-label">Range From</label>
															<input type="number" min="0" name="from[]" id="from_<%= number %>" class="form-control input-block" value="">
														</div>
													</div>
													<div class="col col-md-2 ">
														<div class="form-group">
															<label for="range_<%= number %>" class="control-label">Range To</label>
															<input type="number" min="0" name="to[]" id="to_<%= number %>" class="form-control input-block" value="">
														</div>
													</div>
													<div class="col col-md-4">
														<div class="form-group">
															<label for="val_<%= number %>" class="control-label">Value</label>
															<input type="number" min="0" name="val[]" step='Any' id="val_<%= number %>" class="form-control input-block" value="">
														</div>
													</div>
													<div class="col col-md-4">
														<div class="form-group">
															<label for="type_<%= number %>" class="control-label">Type</label>
															<select name="type[]" id="type_<%= number %>" class="form-control input-block">
																<option value="percentage">Percentage</option>
																<option value="amount">Fixed Amount</option>
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>
									</script>
								</div>

							</div>
						<div class="metabox-header">Coupon Discounts</div>
							<div class="metabox-body">
								<div class="repeater repeater-discount" data-partial="#partial-repeater-coupon">

									<div class="repeater-items">

											<?php
												$coupon_codes = isset($item) ? $item->getMeta("coupon_codes") : false;
												$counter_coupon = 1;
												if ($coupon_codes) :
													foreach ($coupon_codes as $coupon_code => $value) :
											?>
													<div class="repeater-item">
														<div class="item-grip">
															<div class="grip-number"><span><?php echo$counter_coupon++; ?></span></div>
														</div>
														<div class="item-actions">
															<a href="#" class="item-action action-insert js-repeater-insert"><i class="fa fa-plus"></i></a>
															<a href="#" class="item-action action-delete js-repeater-delete"><i class="fa fa-minus"></i></a>
														</div>
														<div class="item-controls">
															<div class="row row-md row-5">
																<div class="col col-md-4">
																	<div class="form-group">
																		<label for="range_<?php echo $counter_coupon; ?>" class="control-label">Code</label>
																		<input type="text" data-validate="required" name="code[]" id="code_<?php echo $counter_coupon; ?>" class="form-control input-block" value="<?php echo $value['coupon']; ?>">
																	</div>
																</div>
																<div class="col col-md-4">
																	<div class="form-group">
																		<label for="range_<?php echo $counter_coupon; ?>" class="control-label">Value</label>
																		<input type="text" data-validate="required" name="value_code[]" id="value_code_<?php echo $counter_coupon; ?>" class="form-control input-block" value="<?php echo $value['value_code']; ?>">
																	</div>
																</div>
																<div class="col col-md-4">
																	<div class="form-group">
																		<label for="type_code_<?php echo $counter_coupon; ?>" class="control-label">Type</label>
																		<select name="type_code[]" data-validate="required" id="type_code_<?php echo $counter_coupon; ?>" class="form-control input-block">
																			<option value="percentage" <?php echo $value['type_code'] == 'percentage' ? 'selected' : ''; ?>>Percentage</option>
																			<option value="amount" <?php echo $value['type_code'] == 'amount' ? 'selected' : ''; ?>>Fixed Amount</option>
																		</select>
																	</div>
																</div>
															</div>
														</div>
													</div>
											<?php
													endforeach;
												endif;
											?>
									</div>
									<div class="repeater-actions">
										<a href="#" class="button button-primary js-repeater-add">Add</a>
									</div>
									<script type="text/template" id="partial-repeater-coupon">
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
													<div class="col col-md-4">
														<div class="form-group">
															<label for="range_<%= number %>" class="control-label">Code</label>
															<input type="text" name="code[]" id="code_<%= number %>" class="form-control input-block" value="">
														</div>
													</div>
													<div class="col col-md-4">
														<div class="form-group">
															<label for="range_<%= number %>" class="control-label">Value</label>
															<input type="text" name="value_code[]" id="value_code<%= number %>" class="form-control input-block" value="">
														</div>
													</div>
													<div class="col col-md-4">
														<div class="form-group">
															<label for="type_code_<%= number %>" class="control-label">Type</label>
															<select name="type_code[]" id="type_code_<%= number %>" class="form-control input-block">
																<option value="percentage">Percentage</option>
																<option value="amount">Fixed Amount</option>
															</select>
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
				<div class="tab tab-simple" id="tab-section-fields">
					<div class="metabox metabox-simple">
						<div class="metabox-header">Fields</div>
							<div class="metabox-body">
								<div class="repeater repeater-fields" data-partial="#partial-repeater-fields">

									<div class="repeater-items">

											<?php
												$custom_fields = isset($item) ? $item->getMeta("custom_fields") : false;
												/*foreach($custom_fields as $index => $subArray) {
													foreach($subArray as $key => $val) {
														if ($key == 'sandbox_stripe_public_key') {
															print_a($val);
														}
													}
												}
*/												$counter = 1;
												if ($custom_fields) :
													foreach ($custom_fields as $index => $subArray) :
														foreach($subArray as $key => $val):
											?>
													<div class="repeater-item" id="repeater-item-fields">
														<div class="item-grip">
															<div class="grip-number"><span><?php echo($counter++); ?></span></div>
														</div>
														<div class="item-actions">
															<a href="#" class="item-action action-insert js-repeater-insert"><i class="fa fa-plus"></i></a>
															<a href="#" class="item-action action-delete js-repeater-delete"><i class="fa fa-minus"></i></a>
														</div>
														<div class="item-controls">
															<div class="row row-md row-5">
																<div class="col col-md-6">
																	<div class="form-group">
																		<label for="range_<?php echo $counter; ?>" class="control-label">Name</label>
																		<input type="text" data-validate="required" name="input_name[]" id="input_name_<?php echo $counter; ?>" class="form-control input-block" value="<?php echo $key; ?>">
																	</div>
																</div>
																<div class="col col-md-6">
																	<div class="form-group">
																		<label for="range_<?php echo $counter; ?>" class="control-label">Value</label>
																		<input type="text" data-validate="required" name="input_value[]" id="input_value_<?php echo $counter; ?>" class="form-control input-block" value="<?php echo $val; ?>">
																	</div>
																</div>
																
																
															</div>
														</div>
													</div>
											<?php
														endforeach;
													endforeach;
												endif;
											?>
									</div>
									<div class="repeater-actions">
										<a href="#" class="button button-primary js-repeater-add">Add</a>
									</div>
									<script type="text/template" id="partial-repeater-fields">
										<div class="repeater-item" id="repeater-item-fields">
											<div class="item-grip">
												<div class="grip-number"><span><%= number %></span></div>
											</div>
											<div class="item-actions">
												<a href="#" class="item-action action-insert js-repeater-insert"><i class="fa fa-plus"></i></a>
												<a href="#" class="item-action action-delete js-repeater-delete"><i class="fa fa-minus"></i></a>
											</div>
											<div class="item-controls">
												<div class="row row-md row-5">
													<div class="col col-md-6">
														<div class="form-group">
															<label for="name_<%= number %>" class="control-label">Name</label>
															<input type="text" name="input_name[]" id="input_name_<%= number %>" class="form-control input-block" value="">
														</div>
													</div>
													<div class="col col-md-6 ">
														<div class="form-group">
															<label for="range_<%= number %>" class="control-label">Value</label>
															<input type="text" name="input_value[]" id="input_value_<%= number %>" class="form-control input-block" value="">
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
