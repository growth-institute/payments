<?php $this->partial('header-html'); ?>
	<?php $this->partial('header'); ?>

		<section class="section section-managers">

			<div class="action-bar">
				<div class="inner">
					<div class="row row-md">
						<div class="col col-6 col-md-6">
							<h2 class="bar-title">
								<a href="<?php $site->urlTo("/backend/managers/", true); ?>" class="action-button button-back"><i class="fa fa-fw fa-angle-left"></i></a>
								<span>Create Meta Form</span>
							</h2>
						</div>
					</div>
				</div>
			</div>

			<div class="block block-content">
				<div class="inner boxfix-vert">
					<div class="margins-horz">
						<?php
							$data = array();
							$data['item'] = null;
							$site->partial('backend/formsmeta/editor', $data, $site->baseDir('/templates/pages'));
						?>
					</div>
				</div>
			</div>

		</section>

	<?php $this->partial('footer'); ?>
<?php $this->partial('footer-html'); ?>