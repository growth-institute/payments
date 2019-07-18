<?php

	# Include models
	include $site->baseDir('/external/model/payments/order.model.php');
	include $site->baseDir('/external/model/payments/order-items.model.php');

	# Include components
	include $site->baseDir('/external/payments/cart.inc.php');
	include $site->baseDir('/external/payments/connector.inc.php');
	include $site->baseDir('/external/payments/processor.inc.php');
	include $site->baseDir('/external/payments/connector/docebo.connector.php');
	include $site->baseDir('/external/payments/connector/hubspot.connector.php');
	include $site->baseDir('/external/payments/connector/hummingbird.connector.php');
	include $site->baseDir('/external/payments/processor/conekta.processor.php');
	include $site->baseDir('/external/payments/processor/paypal.processor.php');
	include $site->baseDir('/external/payments/processor/stripe.processor.php');
	include $site->baseDir('/external/payments/processor/payu.processor.php');

	class Payments {

		private static $instance;

		public $connectors;
		public $cart;

		public static function getInstance() {
			if (null === static::$instance) {
				static::$instance = new static();
			}
			return static::$instance;
		}

		static function init() {
			global $site;
			#
			$site->enqueueStyle('site');
			$site->enqueueStyle('plugins');
			$site->enqueueScript('site');

		}

		protected function __construct() {
			global $site;
			#
			$this->connectors = [];
			$this->cart = PaymentsCart::getInstance();
			# Register routes
			$site->getRouter()->addRoute('/form/:form', 'Payments::routeForm');
			$site->getRouter()->addRoute('/review/:uid', 'Payments::routeReview');
			$site->getRouter()->addRoute('/thanks/:uid', 'Payments::routeThanks');
			$site->getRouter()->addRoute('/:processor/webhook', 'Payments::routeWebhook');
			$site->getRouter()->addRoute('/:processor/charge/:uid', 'Payments::routeCharge');

		}

		static function routeForm($args) {
			global $site;
			global $i18n;
			#
			$request = $site->getRequest();
			$response = $site->getResponse();
			$test = $request->get('test');
			$test = $test && $test == $site->getGlobal('test_password');

			Payments::init();
			#
			$params = [];
			$params['pdoargs'] = ['fetch_metas'];
			$req_form = get_item($args, 1);
			$form = is_numeric($req_form) ? PaymentsForms::getById($req_form, $params) : PaymentsForms::getBySlug($req_form, $params);
			if ($form) {
				#
				switch ($request->type) {
					case 'get':

						# Changing locale bases on form language
						$i18n->setLocale($form->language);

						# Inyecting Growsimo script
						if($form->getMeta('growsumo')) {
							$site->registerScript('growsumo', 'payment-form-growsumo.js', false);
							$site->enqueueScript('growsumo');
						}
						print_a($form->getMeta('coupon_codes'));
						# Logic if we have quantity, since, with quantity, weird stuff happens
						if($form->getMeta('quantity')) {

							#  Preparing variables for JS front functionality
							$quantity_script = [];
							$quantity_script['price'] = $form->total;
							$quantity_script['usd'] = $form->getMeta('price_usd');
							$quantity_script['currency'] = strtoupper($form->currency);

							# If discounts are present, then we add the full discounts array to the variable
							if($form->getMeta('discounts')) {

								$quantity_script['discounts'] = $form->getMeta('discounts');

							# Variables for extra seats
							} elseif($form->getMeta('extra_seats_price')) {

								$quantity_script['extraSeatPrice'] = $form->getMeta('extra_seats_price');

								if ($form->getMeta('extra_seats_price_usd')) {

									$quantity_script['extraSeatPriceUsd'] = $form->getMeta('extra_seats_price_usd');
								}
							} else if($form->getMeta('coupon_codes') ) {
								$quantity_script['codes'] = $form->getMeta('coupon_codes');
							}
							$site->addScriptVar( 'quantity', $quantity_script );
						}

						# Initialize cart
						$products_json = json_decode($form->products);
						if ($products_json) {
							foreach ($products_json as $sku) {
								$product = new PaymentsCartItem();
								$product->name = $sku;
								$site->payments->cart->addItem($sku, $product, true);
							}
						}
						# Save or update the order
						$order = PaymentsOrders::getByUid($site->payments->cart->uid);
						if (! $order ) {
							$order = new PaymentsOrder();
							$order->uid = $site->payments->cart->uid;
							$order->total = $form->total;
							$order->currency = $form->currency;
							$order->sandbox = $test;
							$order->save();
							$order->updateMeta('form', $form->id);
							$order->updateMeta('concept', $form->name);
						} else {
							if ($order->payment_status == 'Paid') {
								$site->payments->cart->reset();
								$site->redirectTo( $site->urlTo("/form/{$form->slug}") );
							} else {
								$order->total = $form->total;
								$order->currency = $form->currency;
								$order->sandbox = $test;
								$order->save();
								$order->updateMeta('form', $form->id);
								$order->updateMeta('concept', $form->name);
							}
						}
						#
						$data = [];
						$data['form'] = $form;
						$data['order'] = $order;

						$site->setPageTitle( $site->getPageTitle($form->name) );
						$site->render('payments/page-form', $data);
					break;
					case 'post':
						$first_name = 	$request->post('first_name');
						$last_name = 	$request->post('last_name');
						$email = 		$request->post('email');
						$phone = 		$request->post('phone');
						$company = 		$request->post('company');
						$quantity = 	$request->post('quantity', 1);
						$gdpr = 		$request->post('gdpr');
						$growsumo = 	$request->post('growsumo');
						$partner_key = 	$request->post('growsumo-partner-key');
						$code = 		$request->post('code');
						#
						$order = PaymentsOrders::getByUid($site->payments->cart->uid);
						if($order) {

							//Updating total based on rules

							# The final total should be the total times the quantity
							$final_total = $form->total*$quantity;
							$total_seats = $form->total;
							$quantity_info = '';

							# Applying discounts based on quantity (Range discounts)
							if($discounts = get_item($form->metas, 'discounts')) {

								foreach($discounts as $discount) {

									if($quantity >= $discount['from'] && $quantity <= $discount['to']) {

										if($discount['type'] == 'percentage') {

											$final_total = $final_total*(1-($discount['val']/100));
											$quantity_info = "{$quantity} ({$discount['val']}% off)";
										} else {
											$final_total -= $discount['val'];
											$quantity_info = "{$quantity} (\${$discount['val']}% off)";
										}
									}
								}
							} else if ($seats = get_item($form->metas, 'extra_seats_price')) {
								$final_total = $total_seats+($seats*$quantity);
							}

							# Apply Coupon code discount
							if($codes = $form->getMeta('coupon_codes')) {
								$code_trim = str_replace(' ','',$code);
								print_a($codes);
								/*var_dump($code);
								var_dump($code_trim);*/

								foreach ($codes as $code) {

									if ($code['coupon'] == $code_trim) {

										if ($code['type_code'] == 'percentage') {

											$final_total = $final_total*(1-($code['value_code']/100));
											$quantity_info = "{$quantity} ({$code['value_code']}% off)(coupon code {$code['coupon']} with {$code['value_code']} % off)";
											var_dump($final_total);
											var_dump($quantity_info);
										} else {

											$final_total -= $code['value_code'];
											$quantity_info = "{$quantity} (\${$code['value_code']}% off)(coupon code {$code['coupon']} with {$code['value_code']} % off)";
										}

									}
								}
							}

							$order->total = $final_total;
							$order->save();

							if ($form->getMeta('price_usd')) {

								if ($form->getMeta('extra_seats_price_usd')) {

									$price_usd = $form->getMeta('price_usd');
									$seats_usd = $form->getMeta('extra_seats_price_usd');
									$total_seats_usd = $price_usd + ($seats_usd * $quantity);
									$order->updateMeta('total_usd', $total_seats_usd);
								} else {

									$total_usd = $form->getMeta('price_usd') * $quantity;
									$order->updateMeta('total_usd', $total_usd);
								}
							}
							$order->updateMeta('first_name', $first_name);
							$order->updateMeta('last_name', $last_name);
							$order->updateMeta('email', $email);
							$order->updateMeta('phone', $phone);
							$order->updateMeta('company', $company);
							$order->updateMeta('quantity', $quantity);

							if($quantity_info) $order->updateMeta('quantity_info', $quantity_info);

							$order->updateMeta('gdpr', $gdpr);
							$order->updateMeta('growsumo', $growsumo);
							$order->updateMeta('growsumo-partner-key', $partner_key);
							$order->updateMeta('growsumo-customer-key', $email);
							# Notify the abandonment payments system
							$site->payments->disableConnector('hummingbird');
							$site->payments->notifyRegister($order);
						}
						#
						$site->redirectTo( $site->urlTo("/review/{$order->uid}") );
					break;
				}
			} else {
				$site->redirectTo( $site->urlTo('/error') );
			}
			return $response->respond();
		}

		static function routeReview($args) {
			global $site;
			global $i18n;
			#
			$request = $site->getRequest();
			$response = $site->getResponse();
			Payments::init();
			# Getting the Order based on the UID
			$req_order = get_item($args, 1);
			$params = [];
			$params['pdoargs'] = ['fetch_metas'];
			$order = PaymentsOrders::getByUid($req_order, $params);
			$form = PaymentsForms::getById($order->getMeta('form', 0), $params);
			# Setting Language
			$i18n->setLocale($form->language);
			# If we have order and form
			if ($order && $form) {
				# If the form is pending of payment
				if ($order->payment_status == 'Pending') {
					switch ($request->type) {
						case 'get':
							#
							$processors_json = json_decode($form->processor);
							$processors = [];
							if ($processors_json) {
								foreach ($processors_json as $processor) {
									$processor_class = "{$processor}Processor";
									$processors[$processor] = new $processor_class;
									$processors[$processor]->includeDependencies($form, $order);
								}
							}
							#
							$data = [];
							$data['form'] = $form;
							$data['order'] = $order;

							ksort($processors);
							$data['processors'] = $processors;

							$site->setPageTitle( $site->getPageTitle($form->name) );
							$site->render('payments/page-review', $data);
						break;
					}
				} else {
					$site->payments->cart->reset();
					$site->redirectTo( $site->urlTo("/form/{$form->slug}") );
				}
			} else {
				$site->redirectTo( $site->urlTo('/error') );
			}
			#
			return $response->respond();
		}

		static function routeCharge($args) {
			global $site;
			#
			$request = $site->getRequest();
			$response = $site->getResponse();
			#
			$req_processor = get_item($args, 2);
			$processor_class = "{$req_processor}Processor";
			$processor_class = ucfirst($processor_class);
			if ( class_exists($processor_class) ) {
				$processor = new $processor_class;
				switch ($request->type) {
					case 'get':
						$site->redirectTo( $site->urlTo('/error') );
					break;
					case 'post':
						$fields = $request->post();
						$order = PaymentsOrders::getByUid( $fields['custom'] );
						if ($order) {
							$processor->process($order, $fields);
						} else {
							$site->redirectTo( $site->urlTo('/error') );
						}
					break;
				}
			} else {
				$site->redirectTo( $site->urlTo('/error') );
			}
			return $response->respond();
		}

		static function routeWebhook($args) {
			global $site;
			#
			$request = $site->getRequest();
			$response = $site->getResponse();
			#
			$req_processor = get_item($args, 1);
			$processor_class = "{$req_processor}Processor";
			$processor_class = ucfirst($processor_class);
			if ( class_exists($processor_class) ) {
				$processor = new $processor_class;
				switch ($request->type) {
					case 'get':
						$site->redirectTo( $site->urlTo('/error') );
					break;
					case 'post':
						$fields = $request->post();
						$processor->webhook($fields);
					break;
				}
			} else {
				$site->redirectTo( $site->urlTo('/error') );
			}
			return $response->respond();
		}

		static function routeThanks($args) {
			global $site;
			global $i18n;
			$request = $site->getRequest();
			$response = $site->getResponse();
			Payments::init();
			#
			$req_order = get_item($args, 1);
			$order = PaymentsOrders::getByUid($req_order);
			$params = [];
			$params['pdoargs'] = ['fetch_metas'];
			$form = PaymentsForms::getById($order->getMeta('form', 0), $params);
			if ($order) {
				#
				switch ($request->type) {
					case 'get':
						if ($form->language == 'es') {
								$i18n->setLocale('es');
							}else if ($form->language == 'en') {
								$i18n->setLocale('en');
						}
					case 'post':
						$data = [];
						$data['order'] = $order;
						$site->setPageTitle( $site->getPageTitle('Thank you') );
						$site->render('payments/page-thanks', $data);
					break;
				}
			} else {
				$site->redirectTo( $site->urlTo('/error') );
			}
			return $response->respond();
		}

		function notifyProcessed($order) {
			global $site;
			if ($this->connectors) {
				foreach ($this->connectors as $name => $instance) {
					$instance->process($order);
				}
			}
		}

		function notifyRegister($order) {
			global $site;
			if ($this->connectors) {
				foreach ($this->connectors as $name => $instance) {
					$instance->process($order);
				}
			}
		}

		function enableConnector($name, $instance) {
			$this->connectors[$name] = $instance;
		}

		function disableConnector($name) {
			unset( $this->connectors[$name] );
		}

		function getExpiryDate($expr) {
			$ret = '';
			$functions = array(
				array('fn' => 'Payments::fnExpiryTTL', 'regex' => '/^timeToLive(Days|Months|Years)\((\d+)\)$/'),
				array('fn' => 'Payments::fnExpiryDate', 'regex' => '/^dateSet\([\'\"](\d{2})\/(\d{2})\/(\d{4})[\'\"]\)$/')
			);
			foreach ($functions as $function) {
				if ( preg_match($function['regex'], $expr, $matches) === 1 ) {
					array_shift($matches);
					$ret = call_user_func($function['fn'], $matches);
					break;
				}
			}
			return $ret;
		}

		protected static function fnExpiryTTL($params) {
			$ret = '';
			$period = get_item($params, 0, 'Days');
			$amount = get_item($params, 1, 30);
			$expr = sprintf('+%d %s', $amount, $period);
			$ret = date('Y-m-d 23:59:59', strtotime($expr));
			return $ret;
		}

		protected static function fnExpiryDate($params) {
			$ret = '';
			$year = get_item($params, 2);
			$month = get_item($params, 1);
			$day = get_item($params, 0);
			$ret = "{$year}/{$month}/{$day} 23:59:59";
			return $ret;
		}

		private function __clone() {}
		private function __wakeup() {}
	}

	$site->payments = Payments::getInstance();

?>