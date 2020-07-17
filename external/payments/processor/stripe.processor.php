<?php

	class StripeProcessor extends PaymentsProcessor {

		function getTitle() {
			return 'Credit card';
		}

		function getMarkup($form, $order) {
			global $site;
			$data = [];
			$data['order'] = $order;
			$data['metadata'] = $order->getMetas();

			$data['form'] = $form;
			if(get_item($_GET, 'getdata')) print_a($data);
			$site->partial('payments/form-stripe', $data);
		}

		function includeDependencies($form, $order) {
			global $site;
			$site->registerStyle('stripe-front', 'payments/stripe.css', false);
			$site->enqueueStyle('stripe-front');
			$site->registerScript('stripe-js', 'https://js.stripe.com/v3/', true);
			$site->registerScript('stripe-front', 'payments/stripe.js', false);
			$site->enqueueScript('stripe-js');
			$site->enqueueScript('stripe-front');
			#
			if ($form->getMeta('custom_fields')) {
				$stripe_opts_cur = $form->getMeta('custom_fields');
				foreach($stripe_opts_cur as $index => $subArray) {
					foreach($subArray as $key => $val) {
						if ($key == 'sandbox_stripe_public_key' && $order->sandbox == 1) {
							$stripe_publishable_sandbox = $val;
							$site->addScriptVar('stripePublishableKey', $stripe_publishable_sandbox);
						} else if($key == 'production_stripe_public_key' && $order->sandbox == 0) {
							$stripe_publishable_production = $val;
							$site->addScriptVar('stripePublishableKey', $stripe_publishable_production);
						}
					}
				}
				//$stripe_opts = get_item($stripe_opts_cur, $order->sandbox ? 'sandbox' : 'production');
			} else {

				$stripe_opts_cur = $site->getOption('stripe');
				$stripe_opts = get_item($stripe_opts_cur, $order->sandbox ? 'sandbox' : 'production');
				$stripe_opts = get_item($stripe_opts, $order->currency);
				$stripe_publishable = get_item($stripe_opts, 'publishable_key');
				$site->addScriptVar('stripePublishableKey', $stripe_publishable);
			}
		}

		function process($order, $fields = []) {
			global $site;
			$form = PaymentsForms::getById($order->getMeta('form'));

			/*
				Here we select the stripe account in two ways:
					1. Specific account passed by Meta data
					2. Account in config file
			*/
			if ($form->getMeta('custom_fields')) {
				$stripe_opts_cur = $form->getMeta('custom_fields');
				foreach($stripe_opts_cur as $index => $subArray) {
					foreach($subArray as $key => $val) {
						if ($key == 'sandbox_stripe_secret_key' && $order->sandbox == 1) {
							$stripe_secret_sandbox = $val;
							include $site->baseDir('/external/lib/Stripe/init.php');
							\Stripe\Stripe::setApiKey($stripe_secret_sandbox);
						} else if($key == 'production_stripe_secret_key' && $order->sandbox == 0) {
							$stripe_secret_production = $val;
							include $site->baseDir('/external/lib/Stripe/init.php');
							\Stripe\Stripe::setApiKey($stripe_secret_production);
						}
					}
				}
			} else {

				$stripe_opts_cur = $site->getOption('stripe');
				$stripe_opts = get_item($stripe_opts_cur, $order->sandbox ? 'sandbox' : 'production');
				$stripe_opts = get_item($stripe_opts, $order->currency);
				$stripe_secret = get_item($stripe_opts, 'secret_key');
				include $site->baseDir('/external/lib/Stripe/init.php');
				\Stripe\Stripe::setApiKey($stripe_secret);
			}

			//Check if Installments or Normal Pay
			$payment_intent_id = get_item($fields, 'payment_intent_id');
			$selected_plan = get_item($fields, 'installment_plan');
			#
			$token = get_item($fields, 'stripeToken');
			$quantity = get_item($fields, 'quantity', 0);

			$extra_seats_price = $form->getMeta('extra_seats_price');
			$discounts = $form->getMeta('discounts');

			# Charge the user's card
			$charge_amount = ($order->total)*($quantity ?: 1);
			$charge_description = $order->getMeta('concept') . ($quantity > 1 ? " × {$quantity}" : '');

			if($discounts) {
				foreach($discounts as $discount) {
					if($quantity >= $discount['from'] && $quantity <= $discount['to']) {
						if($discount['type'] == 'percentage') {

							$discount_amount = 1-($discount['val']/100);
							$charge_amount *= $discount_amount;
							$charge_description .= " ({$discount['val']}% off)";
						}
					}
				}
			} elseif($extra_seats_price) {
				$charge_amount = $order->total+($quantity*$extra_seats_price);
				$charge_description = $order->getMeta('concept') . ($quantity > 1 ? " + {$quantity} Extra Seats" : '');
			}

			$user_metadata = array(
				'name' => $order->getMeta('first_name'),
				'last_name' => $order->getMeta('last_name'),
				'phone_number' => $order->getMeta('phone'),
				'company' => $order->getMeta('company')
			);

			if($form->getMeta('growsumo')) {

				if($order->getMeta('growsumo-customer-key')) $user_metadata['customer_key'] = $order->getMeta('growsumo-customer-key');
				if($order->getMeta('growsumo-partner-key')) $user_metadata['partner_key'] = $order->getMeta('growsumo-partner-key');
			}

			// INSTALLMENTS PAYMENT (MSI)
			if($payment_intent_id) {

				try {

					$intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);
					$selected_plan = $intent->payment_method_options->card->installments->available_plans[$selected_plan];

					$plan = [
						'count' => $selected_plan->count,
						'interval' => $selected_plan->interval,
						'type' => $selected_plan->type
					];

					$confirm_data = ['payment_method_options' => [
							'card' => [
								'installments' => [
									'plan' => $plan
								]
							]
						]
					];

					$intent->confirm($confirm_data);

				} catch (Exception $e) {
					log_to_file($e->getMessage(), 'stripe_error');
					log_to_file($e->getCode(), 'stripe_error');

					echo $e->getMessage();
					echo $e->getCode();
				}

				//succeeded
				if ($intent && $intent->status == 'succeeded') {


					$order->payment_status = 'Paid';
					$order->payment_processor = 'Stripe';
					$order->payment_ticket = $subscription->id;
					$order->payment_date = date('Y-m-d H:i:s');
					$order->total = $charge_amount;
					$order->save();
					$order->updateMeta('installments', $selected_plan);
					$order->updateMeta('quantity', $quantity);
					# Reset the cart
					$site->payments->cart->reset();
					# Notify the payments system
					$class_name = $form->getMeta('connector') == 'ti' ? 'TIConnector' : 'HummingbirdConnector';
					if (!$form->getMeta('connector')) {

						$site->payments->enableConnector('hummingbird', new HummingbirdConnector);
						$site->payments->notifyProcessed($order);
					} else {

						$site->payments->enableConnector($form->getMeta('connector'), new $class_name);
						$site->payments->notifyProcessed($order);
					}
					#
					$form = PaymentsForms::getById( $order->getMeta('form', 0) );

					if ($form->getMeta('event')) {

						$site->payments->enableConnector('notifications', new NotificationsConnector);
						$site->payments->notifyConnector($order, 'notifications');
						$site->payments->enableConnector('events', new EventsConnector);
						$test = $site->payments->notifyConnector($order, 'events');
					}
					$url = '';
					if ($form) {
						$url = $form->getMeta('thank_you_page');
					}
					$url = $url ?: $site->urlTo("/thanks/{$order->uid}");
					$site->redirectTo($url);
				}

			// NORMAL PAYMENT
			} else {

				$options = array(
					'name' => $order->getMeta('first_name') . ' ' . $order->getMeta('last_name'),
					'email' => $order->getMeta('email'),
					'source' => $token,
					'metadata' => $user_metadata,
					'description' => $order->getMeta('first_name') . ' ' . $order->getMeta('last_name') . ' (' . $order->getMeta('email') . ')'
				);

				try {

					$customer = \Stripe\Customer::create($options);

				} catch (Exception $e) {
					log_to_file($e->getMessage(), 'stripe_error');
					log_to_file($e->getCode(), 'stripe_error');
					$site->redirectTo( $site->urlTo("/review/{$order->uid}?error=".$e->getMessage()) ); // TBD: Show proper error page
				}

				try {

					// ITS A SUBSCRIPTION
					if ($form->subscription) {
						/*try{
							$plan = \Stripe\Plan::retrieve($form->slug);
						} catch (\Stripe\Error\InvalidRequest $e) {*/

							if (! isset($plan) ) {
									$amount = $charge_amount*100;
								/*if ($form->getMeta('ocurrency') == '0') {
									$amount = $charge_amount*100;
									//echo $form->getMeta('ocurrency');
								} else {
									$amount = round(($charge_amount*100)/$form->getMeta('ocurrency'));
								}*/
								$options_plan = array(
									'id' => "{$form->id}-{$form->slug}-{$order->id}",
									//'object' => 'plan',
									'active' => true,
									'amount' => $amount,
									'currency' => $form->currency,
									'interval' => 'month',
									'interval_count' => $form->getMeta('periodicity'),
									'name' => "{$form->name} (Form: {$form->id}, Order: {$order->id})",
									'metadata' => [
										'form_id' => $form->id,
										'form_slug' => $form->slug,
										'form_name' => $form->name
									]
								);

								$plan = \Stripe\Plan::create($options_plan);
							}
						//}
						if( $form->getMeta('coupon_subscription') ) {
							/*try {
								$coupon = \Stripe\Coupon::retrieve($form->slug);
							} catch (\Stripe\Error\InvalidRequest $e) {*/
								//if(! isset($coupon) ) {
									$options_coupon = array(
										'id' => "{$form->id}-{$form->slug}-{$order->id}",
										'percent_off' => $form->getMeta('coupon_subscription'),
										'currency' => $form->currency,
										'duration' => 'once'
									);
									$coupon = \Stripe\Coupon::create($options_coupon);
								//}
							//}
						}
						$options_subscription = array(
							'items' => [['plan' => $plan->id]],
							'customer' => $customer->id,
							'metadata' => [
								'order_id' => $order->id,
								'first_name' => $order->getMeta('first_name'),
								'last_name' => $order->getMeta('last_name'),
								'email' => $order->getMeta('email'),
							],
						);

						if(isset($coupon) && isset($coupon->id)) $options_subscription['coupon'] = $coupon->id;

						if($form->getMeta('trial_days')) {

							$options_subscription['trial_period_days'] = $form->getMeta('trial_days');
						}

						log_to_file(print_r($options_subscription,1), 'subscription');
						$subscription = \Stripe\Subscription::create($options_subscription);
						if ($subscription && $subscription->status == 'active') {
							$order->payment_status = 'Paid';
							$order->payment_processor = 'Stripe';
							$order->payment_ticket = $subscription->id;
							$order->payment_date = date('Y-m-d H:i:s');
							$order->total = $charge_amount;
							$order->save();
							/*$order->updateMeta('installments', 0);
							$order->updateMeta('quantity', $quantity);*/
							# Reset the cart
							$site->payments->cart->reset();
							# Notify the payments system
							$class_name = $form->getMeta('connector') == 'ti' ? 'TIConnector' : 'HummingbirdConnector';
							if (!$form->getMeta('connector')) {

								$site->payments->enableConnector('hummingbird', new HummingbirdConnector);
								$site->payments->notifyProcessed($order);
							} else {

								$site->payments->enableConnector($form->getMeta('connector'), new $class_name);
								$site->payments->notifyProcessed($order);
							}
							#
							$form = PaymentsForms::getById( $order->getMeta('form', 0) );

							if ($form->getMeta('event')) {

								$site->payments->enableConnector('notifications', new NotificationsConnector);
								$site->payments->notifyConnector($order, 'notifications');
								$site->payments->enableConnector('events', new EventsConnector);
								$site->payments->notifyConnector($order, 'events');
							}
							$url = '';
							if ($form) {
								$url = $form->getMeta('thank_you_page');
							}
							$url = $url ?: $site->urlTo("/thanks/{$order->uid}");
							$site->redirectTo($url);
						}

					// ITS NOT A SUBSCRIPTION
					} else {

						$order_metadata = (array)$order->getMetas();
						$order_metadata = array_filter($order_metadata);
						$order_metadata['order_id'] = $order->id;

						if($form->getMeta('growsumo')) {

							if($order->getMeta('growsumo-customer-key')) $order_metadata['customer_key'] = $order->getMeta('growsumo-customer-key');
							if($order->getMeta('growsumo-partner-key')) $order_metadata['partner_key'] = $order->getMeta('growsumo-partner-key');
						}

						$options = array(
							'amount' => round($charge_amount*100),
							'currency' => $order->currency,
							'description' => $charge_description,
							//'source' => $token,
							'customer' => $customer->id,
							'metadata' => $order_metadata
						);

						$charge = \Stripe\Charge::create($options);
						if ($charge && $charge->status == 'succeeded') {
							$order->payment_status = 'Paid';
							$order->payment_processor = 'Stripe';
							$order->payment_ticket = $charge->id;
							$order->payment_date = date('Y-m-d H:i:s');
							$order->total = $charge_amount;
							$test = self::calculateTotal($order->id);
							$order->save();
							/*$order->updateMeta('installments', 0);
							$order->updateMeta('quantity', $quantity);*/
							# Reset the cart
							$site->payments->cart->reset();
							# Notify the payments system
							$class_name = $form->getMeta('connector') == 'ti' ? 'TIConnector' : 'HummingbirdConnector';
							if (!$form->getMeta('connector')) {

								$site->payments->enableConnector('hummingbird', new HummingbirdConnector);
								$site->payments->notifyProcessed($order);
							} else {

								$site->payments->enableConnector($form->getMeta('connector'), new $class_name);
								$site->payments->notifyProcessed($order);
							}
							#
							$form = PaymentsForms::getById( $order->getMeta('form', 0) );

							if ($form->getMeta('event')) {

								$site->payments->enableConnector('notifications', new NotificationsConnector);
								$site->payments->notifyConnector($order, 'notifications');
								$site->payments->enableConnector('events', new EventsConnector);
								$site->payments->notifyConnector($order, 'events');
							}

							$url = '';
							if ($form) {
								$url = $form->getMeta('thank_you_page');
							}
							$url = $url ?: $site->urlTo("/thanks/{$order->uid}");
							$site->redirectTo($url);
						} else {
							//
						}
					}
				} catch (Exception $e) {
					log_to_file($e->getMessage(), 'stripe_error');
					log_to_file($e->getCode(), 'stripe_error');
					$site->redirectTo( $site->urlTo("/review/{$order->uid}?error=".$e->getMessage()) ); // TBD: Show proper error page
				}
			}
		}

		static function calculateTotal($id_order) {
			$final_total = false;
			$order = $id_order ? PaymentsOrders::getById($id_order) : false;
			$form = PaymentsForms::getById( $order->getMeta('form', 0) );

			$quantity = $order->getMeta('quantity');
			$final_total = $form->total*$quantity;
			$total_seats = $form->total;

			# Applying discounts based on quantity (Range discounts)
			if($discounts = get_item($form->metas, 'discounts')) {

				foreach($discounts as $discount) {

					if($quantity >= $discount['from'] && $quantity <= $discount['to']) {

						if($discount['type'] == 'percentage') {

							$final_total = $final_total*(1-($discount['val']/100));
						} else {
							$final_total -= $discount['val'];
						}
					}
				}
			} else if ($seats = get_item($form->metas, 'extra_seats_price')) {

				$final_total = $total_seats+($seats*$quantity);

			} else if($codes = $form->getMeta('coupon_codes')) {

			# Apply Coupon code discount

				$code = isset($code) && $code ? $code : '';
				$code_trim = str_replace(' ','', $code);

				foreach ($codes as $code) {

					if ($code['coupon'] == $code_trim) {

						if ($code['type_code'] == 'percentage') {

							$final_total = $final_total*(1-($code['value_code']/100));
						} else {

							$final_total -= $code['value_code'];
						}

					}
				}
			} else {

				$final_total = $form->total;
			}
			return $final_total;
		}

		static function collectDetails() {
			global $site;
			$request = $site->getRequest();
			$response = $site->getResponse();
			#
			$result = 'error';
			$data = [];
			$message = '';

			# retrieve json from POST body
			$json_str = file_get_contents('php://input');
			$json_obj = json_decode($json_str);

			$id_order = get_item($json_obj, 'id_order');
			$order = $id_order ? PaymentsOrders::getById($id_order) : false;
			$form = PaymentsForms::getById( $order->getMeta('form', 0) );

			if($order) {

				if ($form->getMeta('custom_fields')) {
					$stripe_opts_cur = $form->getMeta('custom_fields');

					foreach($stripe_opts_cur as $index => $subArray) {
						foreach($subArray as $key => $val) {
							if ($key == 'sandbox_stripe_secret_key' && $order->sandbox == 1) {
								$stripe_secret_sandbox = $val;
								include $site->baseDir('/external/lib/Stripe/init.php');
								\Stripe\Stripe::setApiKey($stripe_secret_sandbox);
							} else if($key == 'production_stripe_secret_key' && $order->sandbox == 0) {
								$stripe_secret_production = $val;
								include $site->baseDir('/external/lib/Stripe/init.php');
								\Stripe\Stripe::setApiKey($stripe_secret_production);
							}
						}
					}
				} else {

					$stripe_opts_cur = $site->getOption('stripe');
					$stripe_opts = get_item($stripe_opts_cur, $order->sandbox ? 'sandbox' : 'production');
					$stripe_opts = get_item($stripe_opts, $order->currency);
					$stripe_secret = get_item($stripe_opts, 'secret_key');

					include $site->baseDir('/external/lib/Stripe/init.php');
					\Stripe\Stripe::setApiKey($stripe_secret);
				}

				$order_metadata = (array)$order->getMetas();
				$order_metadata = array_filter($order_metadata);
				$order_metadata['order_id'] = $order->id;

				if($form->getMeta('growsumo')) {

					if($order->getMeta('growsumo-customer-key')) $order_metadata['customer_key'] = $order->getMeta('growsumo-customer-key');
					if($order->getMeta('growsumo-partner-key')) $order_metadata['partner_key'] = $order->getMeta('growsumo-partner-key');
				}

				try {

					$options = array(
						'name' => $order->getMeta('first_name') . ' ' . $order->getMeta('last_name'),
						'email' => $order->getMeta('email'),
						'description' => $order->getMeta('first_name') . ' ' . $order->getMeta('last_name') . ' (' . $order->getMeta('email') . ')'
					);

					$customer = \Stripe\Customer::create($options);

				} catch (Exception $e) {
					log_to_file($e->getMessage(), 'stripe_error');
					log_to_file($e->getCode(), 'stripe_error');
					$site->redirectTo( $site->urlTo("/review/{$order->uid}?error=".$e->getMessage()) ); // TBD: Show proper error page
				}


				$intent = \Stripe\PaymentIntent::create([
					'payment_method' => $json_obj->payment_method_id,
					'amount' => $order->total*100,
					'currency' => 'mxn',
					'customer' => $customer->id,
					'description' => $form->name,
					'metadata' => $order_metadata,
					'payment_method_options' => [
						'card' => [
							'installments' => [
								'enabled' => true
							]
						]
					],
				]);

				$data['intent_id'] = $intent->id;
				$available_plans = $intent->payment_method_options->card->installments->available_plans;

				$available_plans_arre = [];

				foreach($available_plans as $k => $available_plan) {

					//Check what installments are permitted
					//if($available_plan->count != 12) continue;
					if(! in_array($available_plan->count , $form->getMeta('array_stripe_installments'))) continue;

					$available_plans_arre[] = [
						'option_index' => $k,
						'count' => $available_plan->count,
						'interval' => $available_plan->interval,
						'type' => $available_plan->type
					];
				}

				$data['available_plans'] = $available_plans_arre;
				$result = 'success';
			}

			return $response->ajaxRespond($result, $data, $message);
		}
	}

	$site->getRouter()->addRoute('/stripe/collect-details', 'StripeProcessor::collectDetails', true);
?>