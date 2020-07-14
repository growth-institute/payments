<?php $this->partial('header-html'); ?>
	<?php $this->partial('header'); ?>

		<section class="section section-events">

			<div class="action-bar">
				<div class="inner">
					<div class="margins-horz">
						<div class="row row-md">
							<div class="col col-6 col-md-6">
								<h2 class="bar-title">
									<a href="<?php $site->urlTo("/backend/events/", true); ?>" class="action-button button-back"><i class="fa fa-fw fa-angle-left"></i></a>
									<span>Delete event</span>
								</h2>
							</div>
							<div class="col col-6 col-md-6">
								<div class="bar-buttons">
									<a href="<?php $site->urlTo("/backend/events/new", true); ?>" class="button button-secondary" title="New event"><i class="fa fa-fw fa-plus"></i><span class="hide-mobile-inline"> New event</span></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="block block-content">
				<div class="inner boxfix-vert">
					<div class="margins-horz">
						<div class="metabox">
							<div class="metabox-header">
								<span>Confirm deletion</span>
							</div>
							<div class="metabox-body">
								<div class="the-content">
									<h3 class="has-tagline">Are you sure you want to delete <strong><?php sanitized_print($item->name) ?></strong>?</h3>
									<p class="tagline">Warning: This action can not be undone, all the item data will be lost if you continue.</p>
									<form action="" method="post">
										<div class="form-actions">
											<a href="<?php $site->urlTo('/backend/events', true); ?>" class="button button-link">&laquo; No, go back to list</a>
											<button type="submit" class="button button-primary">Yes, delete item</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</section>

	<?php $this->partial('footer'); ?>
<?php $this->partial('footer-html'); ?>