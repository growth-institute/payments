<?php $this->partial('header-html'); ?>
	<?php $this->partial('header'); ?>

		<section class="section section-attendess">

			<div class="action-bar">
				<div class="inner">
					<div class="row row-md">
						<div class="col col-6 col-md-6">
							<h2 class="bar-title">
								<a href="<?php $site->urlTo("/backend/events/{$parent->id}/attendees", true); ?>" class="action-button button-back"><i class="fa fa-fw fa-angle-left"></i></a>
								<span><?php echo $parent->name; ?>: New Attendant</span>
							</h2>
						</div>
					</div>
				</div>
			</div>

			<div class="block block-content">
				<div class="inner boxfix-vert">
					<div class="margins-horz">
						<?php
							$data = [];
							$data['item'] = null;
							$data['parent'] = $parent;
							$site->partial('backend/attendees/editor', $data, $site->baseDir('/templates/pages'));
						?>
					</div>
				</div>
			</div>

		</section>

	<?php $this->partial('footer'); ?>
<?php $this->partial('footer-html'); ?>