<?php $this->partial('header-html'); ?>
	<?php $this->partial('header'); ?>
		<section class="section section-dashboard">
			<div class="action-bar">
				<div class="inner">
					<div class="margins-horz">
						<div class="row row-md">
							<div class="col col-6 col-md-6">
								<h2 class="bar-title">
									<a href="#" class="action-button button-back"><i class="fa fa-fw fa-home"></i></a>
									<span>Dashboard</span>
								</h2>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="block block-content">
				<div class="inner boxfix-vert">
					<div class="margins-horz">
						<div class="panel-wrapper">
							<div class="panel-fixed">
								<div class="metabox">
									<div class="metabox-header">
										<span>Modules</span>
									</div>
									<div class="metabox-body body-simple">
										<div class="item-list">
											<div class="item item-module">
												<div class="item-name">
													<a href="<?php $site->urlTo('/backend/forms', true); ?>">Payment Forms</a>
												</div>
												<div class="item-details">
													<div class="details">
														<span class="details-name">Payment Forms</span>
													</div>
												</div>
											</div>
											
											<div class="item item-module">
												<div class="item-name">
													<a href="<?php $site->urlTo('/backend/managers', true); ?>">Managers</a>
												</div>
												<div class="item-details">
													<div class="details">
														<span class="details-name">Manage Managers</span>
													</div>
												</div>
											</div>
										
										</div>
									</div>
								</div>
							</div>
							<div class="panel-fluid">
								<div class="metabox">
									<div class="metabox-header">
										<span>General statistics</span>
									</div>
									<div class="metabox-body">
										<textarea name="stats" class="hide"><?php echo json_encode($stats) ?></textarea>
										<canvas id="chart-stats" width="720" height="400"></canvas>
									</div>
								</div>
								<!--  -->
								<div class="metabox hide">
									<div class="metabox-header">
										<span>Client activity</span>
									</div>
									<div class="metabox-body">
										<textarea name="detailed" class="hide">[]<?php #echo json_encode($detailed) ?></textarea>
										<canvas id="chart-detailed" width="720" height="400"></canvas>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php $this->partial('footer'); ?>
<?php $this->partial('footer-html'); ?>