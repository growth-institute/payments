<?php

	class AppEndpoint extends Endpoint {

		function init() {
			#
		}

		function statusAction() {
			global $site;
			$request = $site->getRequest();
			$response = $site->getResponse();
			#
			$result = 'success';
			$data = array();
			$message = '';
			#
			$data['api'] = $request->api;
			#
			return $response->ajaxRespond($result, $data, $message);
		}
	}

?>