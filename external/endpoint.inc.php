<?php

	/**
	 * Endpoint class
	 *
	 * A simple wrapper for endpoints.
	 * You must override the init() method.
	 */
	abstract class Endpoint {

		/**
		 * Constructor
		 */
		function __construct() {
			$this->init();
		}

		protected function requireAuth($request, $response) {
			$client = null;
			$client_token = $request->param('token');
			$authorized = false;
			if ( Tokenizr::checkToken($client_token) ) {
				$uid = Tokenizr::getData($client_token);
				$client = Clients::getByUid($uid);
				if ($client && $client->status == 'Active') {
					$authorized = true;
					// log_to_file(print_r($_SERVER, 1), 'server');
					// Log access, etc.
				}
			}
			if (! $authorized ) {
				$response->setStatus(403);
				$response->respond();
				exit;
			}
			return $client;
		}

		protected function requireBearer($request, $response) {
			$bearer = $request->param('bearer');
			if (! Tokenizr::checkToken($bearer) ) {
				$response->setStatus(403);
				$response->respond();
				exit;
			} else {
				// Log access, etc.
			}
		}

		/**
		 * Initialization callback, must be overriden in your extended classes
		 */
		abstract function init();
	}

?>