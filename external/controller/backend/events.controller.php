<?php

	class BackendEventsController  extends Controller {

		function init() {
			//
		}

		function getSubControllerName($base_name) {
			return "BackendEvents{$base_name}";
		}

		function indexAction() {
			global $site;
			$request = $site->getRequest();
			$response = $site->getResponse();
			#
			$this->requireUser();
			#
			$dbh = $site->getDatabase();
			#
			$search = $request->param('search', '');
			$show = $request->param('show', 30);
			$page = $request->param('page', 1);
			#
			$search_s = $dbh->quote("%{$search}%");
			#
			$conditions = '1';
			$conditions .= $search ? " AND (name LIKE {$search_s})" : '';
			#
			$params = [];
			$params['show'] = $show;
			$params['page'] = $page;
			$params['conditions'] = $conditions;
			$items = Events::all($params);
			$total = Events::count($conditions);
			#
			$data = [];
			$data['items'] = $items;
			$data['total'] = $total;
			$data['search'] = $search;
			$data['show'] = $show;
			#
			$site->render('backend/events/page-index', $data);
			return $response->respond();
		}

		function newAction() {
			global $site;
			$request = $site->getRequest();
			$response = $site->getResponse();
			#
			$this->requireUser();
			#
			switch($request->type) {
				case 'get':
					$site->render('backend/events/page-new');
				break;
				case 'post':

					$name = $request->post('name');
					$description = $request->post('description');
					#
					$event = new Event();
					$event->name = $name;
					$event->description = $description;
					$event->save();
					$site->redirectTo( $site->urlTo('/backend/events') ); # MSG220 - SAVED_OK
				break;
			}
			return $response->respond();
		}

		function editAction($id) {
			global $site;
			$request = $site->getRequest();
			$response = $site->getResponse();
			#
			$this->requireUser();
			#
			$event = Events::getById($id);
			if (! $event ) {
				$site->redirectTo( $site->urlTo('/backend/events') );
			}
			#
			switch($request->type) {
				case 'get':
					$data = array();
					$data['item'] = $event;
					$site->render('backend/events/page-edit', $data);
				break;
				case 'post':
					$name = $request->post('name');
					$description = $request->post('description');

					#
					$event->name = $name;
					$event->description = $description;

					$event->save();
					$site->redirectTo( $site->urlTo('/backend/events') ); # MSG220 - SAVED_OK
				break;
			}
			return $response->respond();
		}

		function deleteAction($id) {
			global $site;
			$request = $site->getRequest();
			$response = $site->getResponse();
			#
			$this->requireUser();
			#
			$event = Events::getById($id);
			if (! $event ) {
				$site->redirectTo( $site->urlTo('/backend/events') );
			}
			#
			switch($request->type) {
				case 'get':
					$data = array();
					$data['item'] = $event;
					$site->render('backend/events/page-delete', $data);
				break;
				case 'post':
				$event->delete();
					$site->redirectTo( $site->urlTo('/backend/events') ); # MSG220 - DELETED_OK
				break;
			}
			return $response->respond();
		}

		function unknownAction() {
			global $site;
			$request = $site->getRequest();
			$response = $site->getResponse();
			$ret = false;
			#
			$id_parent = get_item($request->parts, 1, 0);
			if ($id_parent) {
				$delegate = get_item($request->parts, 2);
				$parent = get_item($request->parts, 0);
				if ($delegate) {
					$action = get_item($request->parts, 3, 'index');
					$id = get_item($request->parts, 4, 0);
					$request->mvc = new stdClass;
					$request->mvc->parent_controller = $parent;
					$request->mvc->parent_action = $delegate;
					$request->mvc->parent_id = $id_parent;
					$ret = Controller::routeRequest("backend-{$delegate}", $action, $id);
				} else {
					$ret = parent::unknownAction();
				}
			} else {
				$ret = parent::unknownAction();
			}
			return $ret;
		}
	}

?>