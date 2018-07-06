<?php
	/**
	 * functions.inc.php
	 * Add your additional initialization routines here
	 */

	# Include additional Hummingbird dependencies
	include $site->baseDir('/external/utilities.inc.php');
	include $site->baseDir('/external/routes.inc.php');
	include $site->baseDir('/external/hooks.inc.php');
	include $site->baseDir('/external/endpoint.inc.php');
	include $site->baseDir('/external/controller.inc.php');
	include $site->baseDir('/external/model.inc.php');
	include $site->baseDir('/external/norm.inc.php');
	include $site->baseDir('/external/crood.inc.php');
	include $site->baseDir('/external/oppai.inc.php');
	include $site->baseDir('/external/tokenizr.inc.php');
	include $site->baseDir('/external/upload.inc.php');
	include $site->baseDir('/external/pagination.inc.php');
	include $site->baseDir('/external/cacher.inc.php');
	include $site->baseDir('/external/curly.inc.php');
	# Include Google Fonts
	$fonts = array(
		'Open Sans' => array(400, '400italic', 700, '700italic'),
		'Roboto' => array(400, '400italic',700,'700italic')
	);
	$site->registerStyle('google-fonts', get_google_fonts($fonts), true );
	//$site->registerStyle('chimplate', 'chimplate.css', false );
	$site->registerStyle('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', true);
	$site->registerStyle('reset', 'reset.css', false );
	$site->registerStyle('print', 'print.css', false, array(), array('media' => 'print') );
	$site->registerStyle('backend', 'backend.less', false, array('reset', 'google-fonts', 'font-awesome') );
	$site->enqueueStyle('print');
	$site->enqueueStyle('backend');
	//$site->registerScript('site', 'site.js', false);
	$site->registerScript('backend', 'backend.js', false);
	$site->registerScript('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js', true);
	$site->registerScript('underscore', 'https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js', true);
	$site->registerScript('class', 'class.js', false, array('jquery', 'underscore'));
	$site->registerScript('site', 'site.js', false, array('class'));
	$site->enqueueScript('site');
	$site->registerScript('backend', 'backend.js',false);

	# General meta tags
	$site->addMeta('UTF-8', '', 'charset');
	$site->addMeta('x-ua-compatible', 'ie=edge', 'http-equiv');
	$site->addMeta('viewport', 'width=device-width, initial-scale=1');
	$site->addMeta('keywords', '');
	$site->addMeta('description', '');

	# OpenGraph meta tags
	$site->addMeta('og:title', $site->getPageTitle(), 'property');
	$site->addMeta('og:site_name', $site->getSiteTitle(), 'property');
	$site->addMeta('og:description', $site->getSiteTitle(), 'property');
	$site->addMeta('og:image', $site->img('branding/site-share.png', false), 'property');
	$site->addMeta('og:type', 'website', 'property');
	$site->addMeta('og:url', $site->urlTo('/'), 'property');

	$site->removePage('home');
	$site->getRouter()->removeRoute('/:page');

	# Dependencies
	include $site->baseDir('/external/lib/Parsedown.php');
	include $site->baseDir('/external/lib/PasswordHash.php');
	include $site->baseDir('/external/lib/Random.php');

	# Modules
	include $site->baseDir('/external/hubspot.inc.php');

	# Controllers
	// include $site->baseDir('/external/controller/client.controller.php');
	include $site->baseDir('/external/controller/backend.controller.php');
	include $site->baseDir('/external/controller/backend/managers.controller.php');
	include $site->baseDir('/external/controller/backend/forms.controller.php');
	include $site->baseDir('/external/controller/backend/formsmetacontroller.php');
	
	# Models
	include $site->baseDir('/external/model/attachment.model.php');
	include $site->baseDir('/external/model/user.model.php');
	include $site->baseDir('/external/model/manager.model.php');
	include $site->baseDir('/external/model/form.model.php');
	include $site->baseDir('/external/model/formmeta.model.php');
	#Validator Class
	include $site->baseDir('/external/validator.inc.php');
	#Flasher Library
	include $site->baseDir('/external/flasher.inc.php');
	# Endpoints
	// include $site->baseDir('/external/endpoint/app.endpoint.php');
	# Payments
	include $site->baseDir('/external/payments/payments.inc.php');

	$site->payments->enableConnector('hubspot', new HubSpotConnector);
	
	# Start session
	//session_start();

	# Restore manager session (check for Managers module first)
	if ( class_exists('Managers') ) {
	 Managers::init();
	 Managers::checkLogin();
	 $site->manager = Managers::getCurrentUser();
	} else {
	 $site->manager = null;
	}
	//Saving user with the next code
	/*$user = Managers::getByLogin('user');
		if (!$user) {
		$user = new Manager();
		$user->login = 'user';
		$user->email = 'miguel@thewebchi.mp';
		$user->password = 'megamanx';
		$user->type = 'Administrador';
		$user->nicename = 'Administrador';
		$user->save();
	}*/
?>