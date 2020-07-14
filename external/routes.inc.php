<?php
	/**
	 * routes.inc.php
	 * Add your additional routes here
	 */

	# MVC route -----------------------------------------------------------------------------------
	// function route_mvc() {
	// 	global $site;
	// 	$request = $site->getRequest();
	// 	$response = $site->getResponse();
	// 	#
	// 	$ret = false;
	// 	$handled = false;
	// 	#
	// 	$parent = 'client';
	// 	if ( count($request->parts) == 4 ) {
	// 		$parent = array_shift($request->parts);
	// 	}
	// 	$controller = get_item($request->parts, 0);
	// 	$action = get_item($request->parts, 1, 'index');
	// 	$id = get_item($request->parts, 2, 0);
	// 	$controller = $controller ?: 'client';
	// 	#
	// 	if ($parent == $controller) {
	// 		$parent = null;
	// 	}
	// 	#
	// 	if ($controller) {
	// 		$controller_class = ucfirst( dash_to_camel(($parent ? "{$parent}-" : '') . "{$controller}-controller") );
	// 		# Default controller
	// 		if (! class_exists($controller_class) ) {
	// 			$action = $controller;
	// 			$controller_class = 'ClientController';
	// 		}
	// 		if ( class_exists($controller_class) ) {
	// 			$controller_inst = new $controller_class();
	// 			$action_method = dash_to_camel("{$action}-action");
	// 			# Check for aliased action
	// 			$alias = $controller_inst->getAliasedAction($action);
	// 			if (! method_exists($controller_inst, $action_method) && $alias ) {
	// 				$action_method = $alias;
	// 			}
	// 			# Default action
	// 			if (! method_exists($controller_inst, $action_method) ) {
	// 				$id = $action;
	// 				$action = 'show';
	// 				$action_method = dash_to_camel('show-action');
	// 			}
	// 			if ( method_exists($controller_inst, $action_method) ) {
	// 				#
	// 				$format = 'html';
	// 				if ( preg_match('/(\S+)\.(\S+)$/', $id, $matches) === 1 ) {
	// 					$id = $matches[1];
	// 					$format = $matches[2];
	// 				}
	// 				$request->mvc = new stdClass();
	// 				$request->mvc->parent = $parent;
	// 				$request->mvc->controller = $controller;
	// 				$request->mvc->action = $action;
	// 				$request->mvc->id = $id;
	// 				$request->mvc->format = $format;
	// 				#
	// 				$site->addBodyClass([ $parent, $controller, "{$controller}-{$action}", $id ]);
	// 				#
	// 				$site->executeHook('mvc.beforeHandler', $request->mvc);
	// 				#
	// 				$ret = call_user_func( array($controller_inst, $action_method), $id );
	// 				#
	// 				$site->executeHook('mvc.afterHandler', $request->mvc);
	// 				#
	// 				$handled = true;
	// 			}
	// 		}
	// 	}
	// 	if (! $handled ) {
	// 		$response->setStatus(404);
	// 		$ret = false;
	// 	}
	// 	#
	// 	return $ret;
	// }
	// $site->getRouter()->addRoute('/:controller/:action/*id', 'route_mvc', true);
	// $site->getRouter()->addRoute('/:controller/:action', 'route_mvc', true);
	// $site->getRouter()->addRoute('/:controller', 'route_mvc', true);

	# API route -----------------------------------------------------------------------------------
	// function route_api() {
	// 	global $site;
	// 	$request = $site->getRequest();
	// 	$response = $site->getResponse();
	// 	#
	// 	$ret = false;
	// 	$handled = false;
	// 	#
	// 	$endpoint = get_item($request->parts, 1);
	// 	$action = get_item($request->parts, 2, 'index');
	// 	$id = get_item($request->parts, 3, 0);
	// 	$endpoint = $endpoint ?: 'app';
	// 	if ($endpoint) {
	// 		$endpoint_class = ucfirst( dash_to_camel("{$endpoint}-endpoint") );
	// 		if ( class_exists($endpoint_class) ) {
	// 			$endpoint_inst = new $endpoint_class();
	// 			$action_method = dash_to_camel("{$action}-action");
	// 			if ( method_exists($endpoint_inst, $action_method) ) {
	// 				$response->setHeader('Access-Control-Allow-Origin', '*');
	// 				$request->api = new stdClass();
	// 				$request->api->endpoint = $endpoint;
	// 				$request->api->action = $action;
	// 				$request->api->id = $id;
	// 				#
	// 				$site->executeHook('api.beforeHandler', $endpoint_inst, $action_method, $id);
	// 				#
	// 				$ret = call_user_func( array($endpoint_inst, $action_method), $id );
	// 				#
	// 				$site->executeHook('api.afterHandler', $endpoint_inst, $action_method, $id);
	// 				#
	// 				$handled = true;
	// 			}
	// 		}
	// 	}
	// 	if (! $handled ) {
	// 		$response->setStatus(404);
	// 		$ret = $response->respond();
	// 	}
	// 	#
	// 	return $ret;
	// }
	// $site->getRouter()->addRoute('/api/:endpoint/:action/:id', 'route_api', true);
	// $site->getRouter()->addRoute('/api/:endpoint/:action', 'route_api', true);
	// $site->getRouter()->addRoute('/api/:endpoint', 'route_api', true);

	# MVC route -----------------------------------------------------------------------------------
	function route_mvc() {
		global $site;
		$request = $site->getRequest();
		$response = $site->getResponse();
		#
		$ret = false;
		$handled = false;
		#
		$parents = ['frontend', 'backend'];
		$parent = get_item($request->parts, 0, 'frontend');
		if ( in_array($parent, $parents) ) {
			$controller = $parent;
		} else {
			$controller = 'frontend';
			array_unshift($request->parts, $controller);
		}
		// Now get the controller, which may be an action of its parent
		$controller = $controller ?: get_item($request->parts, 0);
		$action = get_item($request->parts, 1, 'index');
		$id = get_item($request->parts, 2, 0);
		$controller = $controller ?: 'frontend';
		#
		$handled = Controller::routeRequest($controller, $action, $id);
		#
		if (! $handled ) {
			$response->setStatus(404);
			$ret = false;
		} else {
			$ret = true;
		}
		#
		return $ret;
	}
	$site->getRouter()->addRoute('/:controller/:action/*id', 'route_mvc', true);
	$site->getRouter()->addRoute('/:controller/:action', 'route_mvc', true);
	$site->getRouter()->addRoute('/:controller', 'route_mvc', true);

?>