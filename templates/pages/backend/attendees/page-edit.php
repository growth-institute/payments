<?php $this->partial('header-html'); ?>
	<?php $this->partial('header'); ?>

		<section class="section section-attendees">

			<div class="action-bar">
				<div class="inner">
					<div class="margins-horz">
						<div class="row row-md">
							<div class="col col-9 col-md-9">
								<h2 class="bar-title">
									<a href="<?php $site->urlTo("/backend/events/{$parent->id}/attendees", true); ?>" class="action-button button-back"><i class="fa fa-fw fa-angle-left"></i></a>
									<span><?php echo $parent->name; ?>: Edit Attendant</span>
								</h2>
							</div>
							<div class="col col-3 col-md-3">
								<?php $site->partial('backend/attendees/action-bar-actions', ['parent' => $parent], $site->baseDir('/templates/pages')); ?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="block block-content">
				<div class="inner boxfix-vert">
					<div class="margins-horz">
						<?php
							$data = [];
							$data['item'] = $item;
							$data['parent'] = $parent;
							$site->partial('backend/attendees/editor', $data, $site->baseDir('/templates/pages'));
						?>
					</div>
				</div>
			</div>

		</section>

	<?php $this->partial('footer'); ?>
<?php $this->partial('footer-html'); ?>