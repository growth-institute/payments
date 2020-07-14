<?php

	/**
	 * Controller class
	 *
	 * A simple wrapper for controllers.
	 * You must override the init() method.
	 */
	abstract class Controller {

		protected $aliases;

		/**
		 * Constructor
		 */
		function __construct() {
			$this->aliases = array();
			$this->init();
		}

		/**
		 * Initialization callback, must be overriden in your extended classes
		 */
		abstract function init();

		abstract function getSubControllerName($base_name);

		static function routeRequest($controller, $action, $id) {
			global $site;
			$request = $site->getRequest();
			$response = $site->getResponse();
			$ret = false;
			if ($controller) {
				$controller_class = ucfirst( dash_to_camel("{$controller}-controller") );
				# Default controller
				if (! class_exists($controller_class) ) {
					$action = $controller;
					$controller_class = 'FrontendController';
				}
				if ( class_exists($controller_class) ) {
					$controller_inst = new $controller_class();
					$action_method = dash_to_camel("{$action}-action");
					# Check for aliased action
					$alias = $controller_inst->getAliasedAction($action);
					if (! method_exists($controller_inst, $action_method) && $alias ) {
						$action_method = $alias;
					}
					# Default action
					if (! method_exists($controller_inst, $action_method) ) {
						$id = $action;
						$action = 'unknown';
						$action_method = dash_to_camel('unknown-action');
					}
					if ($action_method == 'unknownAction') {
						$ret = call_user_func( array($controller_inst, $action_method), $id );
					} else if ( method_exists($controller_inst, $action_method) ) {
						$format = 'html';
						if ( preg_match('/(\S+)\.(\S+)$/', $id, $matches) === 1 ) {
							$id = $matches[1];
							$format = $matches[2];
						}
						$request->mvc = new stdClass();
						$request->mvc->controller = $controller;
						$request->mvc->action = $action;
						$request->mvc->id = $id;
						$request->mvc->format = $format;
						#
						$controller_slug = camel_to_dash($controller);
						$site->addBodyClass([ $controller_slug, "{$controller_slug}-{$action}", $id ]);
						#
						$site->executeHook('mvc.beforeHandler', $request->mvc);
						#
						$ret = call_user_func( array($controller_inst, $action_method), $id );
						#
						$site->executeHook('mvc.afterHandler', $request->mvc);
					}
				}
			}
			return $ret;
		}

		function addActionAlias($action, $handler) {
			$this->aliases[$action] = $handler;
		}

		function getAliasedAction($action) {
			return isset( $this->aliases[$action] ) ? $this->aliases[$action] : false;
		}

		function requireUser($redirect = '/backend/login') {
			global $site;
			if (! $site->manager ) {
				$_SESSION['login_redirect'] = $site->getRouter()->getCurrentUrl();
				$site->redirectTo( $site->urlTo($redirect) );
			}
		}

		function unknownAction() {
			global $site;
			$request = $site->getRequest();
			$response = $site->getResponse();
			$handled = false;
			$ret = false;
			#
			array_shift($request->parts);
			#
			$controller = get_item($request->parts, 0);
			$action = get_item($request->parts, 1, 'index');
			$id = get_item($request->parts, 2, 0);
			#
			$controller = $this->getSubControllerName( ucfirst( dash_to_camel($controller) ) );
			if ( class_exists("{$controller}Controller") ) {
				$handled = Controller::routeRequest($controller, $action, $id);
			}
			#
			if ($handled) {
				$ret = true;
			} else {
				if ( method_exists($this, 'showAction') ) {
					$name = $this->getSubControllerName('');
					$ret = Controller::routeRequest($name, 'show', $id);
				}
			}
			#
			return $ret;
		}
	}

?>