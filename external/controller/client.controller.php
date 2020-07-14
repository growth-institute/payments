<?php

	class FrontendController extends Controller {

		function init() {
			global $site;
			$this->addActionAlias('home', 'indexAction');
			#
			$site->enqueueStyle('site');
			$site->enqueueScript('site');
		}

		function getSubControllerName($base_name) {
			return "Frontend{$base_name}";
		}

		function indexAction() {
			global $site;
			$request = $site->getRequest();
			$response = $site->getResponse();
			#
			$site->redirectTo($site->urlTo('/backend', false));
			#
			return $response->respond();
		}
	}

?>