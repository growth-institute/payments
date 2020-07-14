<?php $this->partial('header-html'); ?>
	<?php $this->partial('header'); ?>

		<section class="section section-managers">

			<div class="action-bar">
				<div class="inner">
					<div class="margins-horz">
						<div class="row row-md">
							<div class="col col-6 col-md-6">
								<h2 class="bar-title">
									<a href="<?php $site->urlTo("/backend/forms/", true); ?>" class="action-button button-back"><i class="fa fa-fw fa-angle-left"></i></a>
									<span>Edit Forms</span>
								</h2>
							</div>
							<div class="col col-6 col-md-6">
								<div class="bar-buttons">
									<a href="<?php $site->urlTo("/backend/forms/new", true); ?>" class="button button-secondary" title="New Manager"><i class="fa fa-fw fa-plus"></i><span class="hide-mobile-inline"> New Form</span></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="block block-content">
				<div class="inner boxfix-vert">
					<div class="margins-horz">
						<?php if(isset($notice)): ?>
								<div class="message message-error"><strong><?php sanitized_print($notice); ?></strong></div>
						<?php endif; ?>
						<?php
							$data = array();
							$data['item'] = $item;
							$data['events'] = $events;
							//$data['hubspot_list'] = $hubspot_list;
							//print_a($data['item']);
							$site->partial('backend/forms/editor', $data, $site->baseDir('/templates/pages'));
						?>
					</div>
				</div>
			</div>

		</section>

	<?php $this->partial('footer'); ?>
<?php $this->partial('footer-html'); ?>