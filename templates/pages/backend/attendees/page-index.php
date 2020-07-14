<?php $this->partial('header-html'); ?>
	<?php $this->partial('header'); ?>

		<section class="section section-attendees">

			<div class="action-bar">
				<div class="inner">
					<div class="margins-horz">
						<div class="row row-md">
							<div class="col col-6 col-md-6">
								<h2 class="bar-title">
									<a href="<?php $site->urlTo("/backend/events", true); ?>" class="action-button button-back"><i class="fa fa-fw fa-angle-left"></i></a>
									<span><?php echo $parent->name; ?>: All attendees</span>
								</h2>
							</div>
							<div class="col col-6 col-md-6">
								<?php $site->partial('backend/attendees/action-bar-actions', ['parent' => $parent], $site->baseDir('/templates/pages')); ?>
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
													<label for="search" class="control-label">Name or Email</label>
													<input type="text" name="search" id="search" class="form-control input-block" value="<?php sanitized_print($search); ?>">
												</div>
											</div>
											<div class="form-actions text-right">
												<?php if ($search): ?>
													<a href="<?php $site->urlTo("/backend/events/{$parent->id}/attendees", true); ?>" class="button button-link">Reset</a>
												<?php endif; ?>
												<button type="submit" class="button button-primary">Apply filter</button>
												<input type="submit" name="csv" class="button button-danger" value="Download CSV">
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="panel-fluid">
								<?php message(Flasher::alert()); ?>
								<div class="metabox">
									<div class="metabox-header">Available attendees</div>
									<div class="metabox-body body-simple">
										<div class="table-wrapper">
											<table class="table">
												<thead>
													<tr>
														<th class="column-id">ID</th>
														<th>Order</th>
														<th>Attendant</th>
														<th>Email</th>
														<th>Ticket Number</th>
														<th>Code</th>
														<th>Status</th>
													</tr>
												</thead>
												<tfoot>
													<tr>
														<th class="column-id">ID</th>
														<th>Order</th>
														<th>Attendant</th>
														<th>Email</th>
														<th>Ticket Number</th>
														<th>Code</th>
														<th>Status</th>
													</tr>
												</tfoot>
												<tbody>
													<?php
														if ($items):
															foreach ($items as $item):
													?>
														<tr>
															<td class="column-id"><?php echo $item->id; ?></td>
															<td><?php echo $item->id_order; ?></td>
															<td>
																<div class="item-name"><a href="<?php $site->urlTo("/backend/events/{$parent->id}/attendees/edit/{$item->event_code}", true) ?>"><?php sanitized_print($item->user_name); ?></a></div>
																<div class="item-details"><i class="fa fa-fw fa-<?php echo $item->type; ?>"></i> <?php sanitized_print(ucfirst($item->user_name)); ?></div>
																<div class="item-actions">
																	<a href="<?php $site->urlTo("/backend/events/{$parent->id}/attendees/edit/{$item->event_code}", true) ?>">Edit</a>
																</div>
															</td>
															<td><?php echo $item->user_email; ?></td>
															<td><?php echo $item->ticket_number; ?></td>
															<td><?php echo $item->event_code; ?></td>
															<td><?php echo $item->status; ?></td>

														</tr>
													<?php
															endforeach;
														else:
													?>
														<tr>
															<td colspan="3">No items available yet</td>
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