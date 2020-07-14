<?php $this->partial('header-html'); ?>
	<?php $this->partial('header'); ?>

		<section class="section section-events">

			<div class="action-bar">
				<div class="inner">
					<div class="margins-horz">
						<div class="row row-md">
							<div class="col col-6 col-md-6">
								<h2 class="bar-title">
									<a href="<?php $site->urlTo("/backend/dashboard/", true); ?>" class="action-button button-back"><i class="fa fa-fw fa-angle-left"></i></a>
									<span>All events</span>
								</h2>
							</div>
							<div class="col col-6 col-md-6">
								<div class="bar-buttons">
									<a href="<?php $site->urlTo("/backend/events/new", true); ?>" class="button button-secondary" title="New Event"><i class="fa fa-fw fa-plus"></i><span class="hide-mobile-inline"> New Event</span></a>
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
											</div>
											<div class="form-actions text-right">
												<?php if ($search): ?>
													<a href="<?php $site->urlTo('/backend/events', true); ?>" class="button button-link">Reset</a>
												<?php endif; ?>
												<button type="submit" class="button button-primary">Apply filter</button>
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="panel-fluid">
								<?php if ($search): ?>
									<div class="message message-info">Showing filtered results for <strong><?php sanitized_print($search) ?></strong>. <a href="<?php $site->urlTo('/backend/clients', true); ?>">Click here</a> to reset the filters.</div>
								<?php endif; ?>
								<div class="metabox">
									<div class="metabox-header">Available events</div>
									<div class="metabox-body body-simple">
										<div class="table-wrapper">
											<table class="table">
												<thead>
													<tr>
														<th class="column-id">ID</th>
														<th>Name</th>
													</tr>
												</thead>
												<tfoot>
													<tr>
														<th class="column-id">ID</th>
														<th>Name</th>
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
																<div class="item-name"><a href="<?php $site->urlTo("/backend/events/edit/{$item->id}", true) ?>"><?php sanitized_print($item->name); ?></a></div>
																<div class="item-details"><?php sanitized_print($item->description); ?></div>
																<div class="item-actions">
																	<a href="<?php $site->urlTo("/backend/events/edit/{$item->id}", true) ?>">Edit</a>
																	<span class="divider">|</span>
																	<a href="<?php $site->urlTo("/backend/events/delete/{$item->id}", true) ?>" class="action-delete">Delete</a>
																	<span class="divider">|</span>
																	<a href="<?php $site->urlTo("/backend/events/{$item->id}/attendees", true) ?>" class="action-error">Attendees</a>
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