<?php

	class ClientController extends Controller {

		function init() {
			$this->addActionAlias('home', 'indexAction');
		}

		function indexAction() {
			global $site;
			$request = $site->getRequest();
			$response = $site->getResponse();
			#
			$site->render('page-home');
			#
			return $response->respond();
		}
	}

?>