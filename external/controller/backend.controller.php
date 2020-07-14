<?php

	class BackendController extends Controller {

		function init() {
			global $site;
			$this->addActionAlias('dashboard', 'indexAction');
			#
			$site->enqueueStyle('backend');
			$site->enqueueScript('backend');
		}

		function getSubControllerName($base_name) {
			return "Backend{$base_name}";
		}

		function indexAction() {
			global $site;
			$request = $site->getRequest();
			$response = $site->getResponse();

			$dbh = $site->getDatabase();
			#
			$this->requireUser();
			#
			$site->addBodyClass('has-graphs');
			$stats = [];
			#
			try {
				# Per day
				$sql = "SELECT COUNT(id) AS items, DATE(created) AS `date` FROM `entry`  WHERE created > DATE_SUB(CURDATE(), INTERVAL 15 DAY) GROUP BY DATE(created) ORDER BY `date`";
				$stmt = $dbh->prepare($sql);
				$stmt->execute();
				$rows = $stmt->fetchAll();
				if ($rows) {
					foreach ($rows as $row) {
						$stats[$row->date] = $row->items;
					}
				}
			} catch (PDOException $e) {
				error_log( $e->getMessage() );
			}
			#
			$data = [];
			$data['stats'] = $stats;
			$site->render('backend/page-dashboard', $data);
			#
			return $response->respond();
		}

		function loginAction() {
			global $site;
			$request = $site->getRequest();
			$response = $site->getResponse();
			#
			/*if ( Managers::count() == 0 ) {
				$site->redirectTo( $site->urlTo('/backend/setup') );
			}*/
			#
			if ($site->manager) {
				$site->redirectTo( $site->urlTo('/backend/dashboard') );

			}
			#
			switch ($request->type) {
				case 'get':
					$site->render('backend/page-login');
				break;
				case 'post':
					$user = $request->post('user');
					$password = $request->post('pass');
					if ( Managers::login($user, $password) ) {
						$site->redirectTo( $site->urlTo( $request->session('login_redirect', '/backend/dashboard') ) );
					} else {
						$site->redirectTo( $site->urlTo('/backend/login?msg=MSG_INVALID_CREDENTIALS') );
					}
				break;
			}
			#
			return $response->respond();
		}

		function logoutAction() {
			global $site;
			#
			Managers::logout();
			$site->redirectTo( $site->urlTo('/backend/login') );
			#
			return $response->respond();
		}
	}

?>