<?php $this->partial('header-html'); ?>
	<section class="section section-dashboard">
		<div class="inner boxfix-vert">
			<div class="margins">
				<div class="row row-md">
					<div class="col col-4 col-md-6 col-offset-4 col-md-offset-3">
						<div class="metabox">
							<div class="metabox-body">
								<div class="the-content text-center">
									<h1 class="section-title has-tagline"><img src="<?php $site->img('backend/logo-Blue.png'); ?>" alt="Growth Institute" class="img-responsive" width="250"></h1>
									<p class="section-tagline">Please enter your credentials below to continue.</p>
									<form action="" method="post">
										<div class="form-fields margin-bottom">
											<div class="form-group">
												<label for="user" class="control-label hide">User</label>
												<input type="text" name="user" id="user" class="form-control input-block">
											</div>
											<div class="form-group">
												<label for="pass" class="control-label hide">Password</label>
												<input type="password" name="pass" id="pass" class="form-control input-block">
											</div>
										</div>
										<div class="form-actions text-right">
											<button type="submit" class="button button-primary button-block">Continue</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

<?php $this->partial('footer-html'); ?>