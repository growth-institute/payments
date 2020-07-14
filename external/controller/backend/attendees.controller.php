<?php
	class BackendAttendeesController extends Controller {

		function init() {
			//
		}

		function getSubControllerName($base_name) {
			return "BackendAttendees{$base_name}";
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
			$id_event = get_item($request->parts, 1);
			$event = Events::getById($id_event);
			#
			if ($id_event && $event) {
				/*print_a("TBD: Add logic here for '{$request->mvc->action}Action'");
				print_a("Book: {$id_book}");
				print_a($request);*/
				$dbh = $site->getDatabase();
				#
				$search = $request->param('search', '');
				$show = $request->param('show', 30);
				$page = $request->param('page', 1);
				$csv = $request->param('csv');

				#
				$search_s = $dbh->quote("%{$search}%");
				#
				$conditions = ' AND 1';
				$conditions .= $search ? " AND (user_name LIKE {$search_s} OR user_email LIKE {$search_s})" : '';
				#
				$params = [];
				$params['show'] = $show;
				$params['page'] = $page;
				$params['conditions'] = $conditions;
				$items = EventOrders::allByIdEvent($id_event, $params);
				$total = EventOrders::count();
				$items_csv = EventOrders::allByIdEvent($id_event);
				if ($csv) {
					// You provide the labels
					$labels = array(
						'id',
						'Order',
						'Attendant',
						'Email',
						'Ticket Number',
						'Code',
						'Status'
					);
					// And the rows (with your own logic of course)
					$rows = [];
					foreach ($items_csv as $key => $value) {
						$rows[$key] = [
							'id' => $value->id,
							'Order' => $value->id_order,
							'Attendant' => $value->user_name,
							'Email' => $value->user_email,
							'Ticket Number' => $value->ticket_number,
							'Code' => $value->event_code,
							'Status' => $value->status
						];
					}
					// Create a CSBuddy instance, set the labels, the rows and generate it
					$csvv = CSBuddy::newInstance()
					  ->setLabels($labels)
					  ->setRows($rows)
					  ->generate(time().'.csv');
					exit();
				}
				#
				$data = [];
				$data['parent'] = $event;
				$data['items'] = $items;
				$data['total'] = $total;
				$data['search'] = $search;
				$data['show'] = $show;
				#
				$site->render('backend/attendees/page-index', $data);
			}
			#
			return $response->respond();
		}

		function newAction($id) {
			global $site;
			$request = $site->getRequest();
			$response = $site->getResponse();
			#
			$this->requireUser();
			#
			$dbh = $site->getDatabase();
			#
			$id_event = get_item($request->parts, 1);
			$event = Events::getById($id_event);
			include_once $site->baseDir('/external/lib/hashids/Math.php');
			include_once $site->baseDir('/external/lib/hashids/HashidsException.php');
			include_once $site->baseDir('/external/lib/hashids/HashidsInterface.php');
			include_once $site->baseDir('/external/lib/hashids/Hashids.php');

			#
			if ($id_event && $event) {
				/*print_a("TBD: Add logic here for '{$request->mvc->action}Action'");
				print_a("event: {$id_event}");
				print_a($request);*/
				switch($request->type) {
					case 'get':
						$data = [];
						$data['parent'] = $event;
						$site->render('backend/attendees/page-new', $data);
					break;
					case 'post':
						$user_name = $request->post('user_name');
						$user_email = $request->post('user_email');
						$random = rand(1,1000);
						$hashids = new Hashids\Hashids($site->hashToken($name), 8, 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789');
						$codigo = $hashids->encode($id_event.$random);

						$event_order = new EventOrder();
						$event_order->id_event = $id_event;
						$event_order->event_name = $event->name;
						$event_order->user_name = $user_name;
						$event_order->user_email = $user_email;
						$event_order->ticket_number = 1;
						$event_order->event_code = $codigo;
						$event_order->status = 'pending';
						$event_order->save();
						//Flasher::alert(['message' => "Resource {$name} has been saved succesfully", 'type' => 'success']);
						$site->redirectTo( $site->urlTo("/backend/events/{$id_event}/attendees") ); # MSG220 - SAVED_OK
					break;
				}
			}
			#
			return $response->respond();
		}

		function editAction($id) {
			global $site;
			$request = $site->getRequest();
			$response = $site->getResponse();
			#
			$this->requireUser();
			#
			$dbh = $site->getDatabase();
			#
			$id_event = get_item($request->parts, 1);
			$event = Events::getById($id_event);
			$attendant = EventOrders::getByEventCode($id);
			#
			if ($id_event && $event && $attendant) {

				switch ($request->type) {
					case 'get':
						$data = [];
						$data['parent'] = $event;
						$data['item'] = $attendant;
						$site->render('backend/attendees/page-edit', $data);
					break;
						break;

					case 'post':
						$burned = $request->post('burned');
						$unburned = $request->post('unburned');
						$user_name = $request->post('user_name');
						$user_email = $request->post('user_email');

						$attendant->user_name = $user_name;
						$attendant->user_email = $user_email;
						$attendant->save();

						if ($burned) {

							$attendant->status = 'burned';
							$attendant->save();
							Flasher::alert(['message' => "This ticket has been burned succesfully", 'type' => 'success']);
						} else if($unburned) {

							$attendant->status = 'pending';
							$attendant->save();
						}

						$site->redirectTo( $site->urlTo("/backend/events/{$id_event}/attendees") );
						break;
				}
			}

			return $response->respond();
		}
}