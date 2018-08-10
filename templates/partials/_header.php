	<header class="site-header">
		<div class="action-bar">
			<div class="row row-md row-collapse">
				<div class="col col-6 col-md-6">
					<div class="">
						<img class="header-logo" src="<?php $site->img('backend/logo-white.svg'); ?>" alt="Growth Institute">
					</div>
				</div>	
				<div class="col col-6 col-md-6">
					<div class="bar-buttons">
						<a href="<?php $site->urlTo("/backend/logout", true); ?>" class="button button-secondary" title="Logout"><i class="fa fa-fw fa-sign-out"></i><span class="hide-mobile-inline">Logout</span></a>
					</div>
				</div>
			</div>
		</div>
	</header>