<?php $this->partial('header-html'); ?>
	<?php $this->partial('header'); ?>

		<section class="section section-forms">

			<div class="action-bar">
				<div class="inner">
					<div class="margins-horz">
						<div class="row row-md">
							<div class="col col-6 col-md-6">
								<h2 class="bar-title">
									<a href="<?php $site->urlTo("/backend/dashboard/", true); ?>" class="action-button button-back"><i class="fa fa-fw fa-angle-left"></i></a>
									<span>All Forms</span>
								</h2>
							</div>
							<div class="col col-6 col-md-6">
								<div class="bar-buttons">
									<a href="<?php $site->urlTo("/backend/forms/new", true); ?>" class="button button-secondary" title="New Form"><i class="fa fa-fw fa-plus"></i><span class="hide-mobile-inline"> New Form</span></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="block block-content">
				<div class="inner boxfix-vert">
					<div class="margins-horz">
						<div class="panel-wrapper fixed-left">
							<div class="panel-fixed">
								<div class="metabox">
									<div class="metabox-header">Filter</div>
									<div class="metabox-body">
										<form action="" class="form-buscar" method="get" data-submit="validate">
											<div class="form-fields">
												<div class="form-group">
													<label for="search" class="control-label">Name</label>
													<input type="text" name="search" id="search" class="form-control input-block" value="<?php sanitized_print($search); ?>">
												</div>
												<div class="form-group">
													<label for="search_products" class="control-label">Products</label>
													<input type="text" name="search_products" id="search_products" class="form-control input-block" value="<?php sanitized_print($search_products); ?>">
												</div>
												<div class="form-group">
													<label for="language" class="control-label">Language</label>
													<select class="form-control input-block" name="language">
														<option  selected name="all">All Items</option>
														<option name="language"  value="English" <?php echo($search_language == 'English' ? 'selected="selected"' : ''); ?>>English</option>
														<option name="language"  value="Spanish" <?php echo($search_language == 'Spanish' ? 'selected="selected"' : ''); ?>>Spanish</option>
													</select>
												</div>
												<div class="form-group">
													<label for="currency" class="control-label">Currency</label>
													<label><input type="checkbox" name="currency[]" id="currency" value="mxn" class="form-control" <?php echo($search_currency && in_array('mxn', $search_currency) ? 'checked="checked"' : ''); ?> >MXN</label>
													<label><input type="checkbox" name="currency[]" id="currency" value="usd" class="form-control" <?php echo($search_currency && in_array('usd', $search_currency) ? 'checked="checked"' : ''); ?> >USD</label>
												</div>	
												<div class="form-group">
													<label for="subscription" class="control-label">Has subscription?</label>
													<select class="form-control input-block" name="subscription">
														<option name="all" selected>All Items</option>
														<option name="subscription" value="Yes" <?php echo($search_subscription == 'Yes' ? 'selected="selected"' : ''); ?> >Yes</option>
														<option name="subscription" value="No" <?php echo($search_subscription == 'No' ? 'selected="selected"' : ''); ?> >No</option>
													</select>
												</div>
											</div>
											<div class="form-actions text-right">
												<?php if ($search): ?>
													<a href="<?php $site->urlTo('/backend/forms', true); ?>" class="button button-link">Reset</a>
												<?php endif; ?>
												
												<button type="submit" class="button button-primary">Apply filter</button>
											</div>
									
										</form>
									</div>
								</div>
							</div>
							<div class="panel-fluid">
								<?php if ($search || $search_products || $search_language || $search_currency || $search_subscription): ?>
									<div class="message message-info">Showing filtered all reults <strong><?php sanitized_print($search)  ?></strong>. <a href="<?php $site->urlTo('/backend/forms', true); ?>">Click here</a> to reset the filters.</div>
								<?php endif; ?>
								<div class="metabox">
									<div class="metabox-header">Available forms</div>
									<div class="metabox-body body-simple">
										<div class="table-wrapper">
											<table class="table">
												<thead>
													<tr>
														<th class="column-id">ID</th>
														<th>Name Form</th>
													</tr>
												</thead>
												<tfoot>
													<tr>
														<th class="column-id">ID</th>
														<th>Name Form</th>
													</tr>
												</tfoot>
												<tbody>
													<?php
														if ($items):
															foreach ($items as $item):

													?>
														<tr>
															<td class="column-id"><?php echo $item->id; ?></td>
															<td>
																<div class="item-name"><a href="<?php $site->urlTo("/backend/forms/edit/{$item->id}", true) ?>"><?php sanitized_print($item->name); ?></a></div>
																<div class="item-details"><?php sanitized_print($item->slug); ?></div>
																<div class="item-actions">
																	<a href="<?php $site->urlTo("/backend/forms/edit/{$item->id}", true) ?>">Edit</a>
																	<span class="divider">|</span>
																	<a href="<?php $site->urlTo("/backend/forms/delete/{$item->id}", true) ?>" class="action-delete">Delete</a>
																	<span class="divider">|</span>
																	<a href="<?php $site->urlTo("/form/{$item->slug}", true) ?>" target="_blank">Link Form</a>
																</div>
															</td>
														</tr>
													<?php
															endforeach;
														else:
													?>
														<tr>
															<td colspan="2">No items available yet</td>
														</tr>
													<?php
														endif;
													?>
												</tbody>
											</table>
										</div>
									</div>
									<?php if ($total > $show): ?>
										<div class="metabox-footer">
											<?php Pagination::paginate($total); ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

	<?php $this->partial('footer'); ?>
<?php $this->partial('footer-html'); ?>