<?php

	class BackendManagersController  extends Controller {

		function init() {
			//
		}

		function getSubControllerName($base_name) {
			return "BackendManagers{$base_name}";
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
			$conditions .= $search ? " AND (nicename LIKE {$search_s} OR email LIKE {$search_s})" : '';
			#
			$params = [];
			$params['show'] = $show;
			$params['page'] = $page;
			$params['conditions'] = $conditions;
			$items = Managers::all($params);
			$total = Managers::count($conditions);
			#
			$data = [];
			$data['items'] = $items;
			$data['total'] = $total;
			$data['search'] = $search;
			$data['show'] = $show;
			#
			$site->render('backend/managers/page-index', $data);
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
					$site->render('backend/managers/page-new');
				break;
				case 'post':

					$login = $request->post('login');
					$email = $request->post('email');
					$nicename = $request->post('nicename');
					$password = $request->post('password');
					$confirm = $request->post('confirm');
					$status = $request->post('status');
					#
					$manager = new Manager();
					$manager->login = $login;
					$manager->email = $email;
					$manager->nicename = $nicename;
					$manager->password = $password;
					$manager->status = $status;
					$manager->save();
					$site->redirectTo( $site->urlTo('/backend/managers?msg=220') ); # MSG220 - SAVED_OK
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
			$manager = Managers::getById($id);
			if (! $manager ) {
				$site->redirectTo( $site->urlTo('/backend/managers') );
			}
			#
			switch($request->type) {
				case 'get':
					$data = array();
					$data['item'] = $manager;
					$site->render('backend/managers/page-edit', $data);
				break;
				case 'post':
					$login = $request->post('login');
					$status = $request->post('status');
					$email = $request->post('email');
					$nicename = $request->post('nicename');
					$password = $request->post('password');
					$confirm = $request->post('confirm');
					#
					$manager->login = $login;
					$manager->status = $status;
					$manager->email = $email;
					$manager->nicename = $nicename;
					if ($password && $password == $confirm) {
						$manager->password = $password;
					}
					$manager->save();
					$site->redirectTo( $site->urlTo('/backend/managers?msg=220') ); # MSG220 - SAVED_OK
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
			$manager = Managers::getById($id);
			if (! $manager ) {
				$site->redirectTo( $site->urlTo('/backend/managers') );
			}
			#
			switch($request->type) {
				case 'get':
					$data = array();
					$data['item'] = $manager;
					$site->render('backend/managers/page-delete', $data);
				break;
				case 'post':
				$manager->delete();
					$site->redirectTo( $site->urlTo('/backend/managers?msg=230') ); # MSG220 - DELETED_OK
				break;
			}
			return $response->respond();
		}
	}

?>