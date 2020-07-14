<?php

	class ConektaProcessor extends PaymentsProcessor {

		function getTitle() {
			return 'Tarjeta de crédito';
		}

		function getMarkup($form, $order) {
			global $site;
			$data = [];
			$data['form'] = $form;
			//print_a($data);
			$site->partial('payments/form-conekta', $data);
		}

		function includeDependencies($form, $order) {
			global $site;
			$site->registerStyle('conekta-front', 'payments/conekta.css', false);
			$site->enqueueStyle('conekta-front');
			$site->registerScript('conekta-js', 'https://cdn.conekta.io/js/latest/conekta.js', true);
			$site->registerScript('conekta-front', 'payments/conekta.js', false);
			$site->enqueueScript('conekta-js');
			$site->enqueueScript('conekta-front');
			#
			$conekta_opts = $site->getOption('conekta');
			$conekta_opts = get_item($conekta_opts, $order->sandbox ? 'sandbox' : 'production');
			$conekta_public = get_item($conekta_opts, 'public_key');
			$site->addScriptVar('conektaPublicKey', $conekta_public);
		}

		function process($order, $fields = []) {
			global $site;
			#
			$token = get_item($fields, 'conektaTokenId');
			$installments = get_item($fields, 'installments');
			#
			$conekta_opts = $site->getOption('conekta');
			$conekta_opts = get_item($conekta_opts, $order->sandbox ? 'sandbox' : 'production');
			$conekta_private = get_item($conekta_opts, 'private_key');
			#
			include $site->baseDir('/external/lib/Conekta/Conekta.php');
			\Conekta\Conekta::setApiKey($conekta_private);
			\Conekta\Conekta::setApiVersion('2.0.0');
			#
			$first_name = $order->getMeta('first_name');
			$last_name = $order->getMeta('last_name');
			$email = $order->getMeta('email');
			$phone = $order->getMeta('phone');
			#
			$payment_method = array(
				'type' => 'default'
			);

			if ($installments) {
				$payment_method['monthly_installments'] = $installments;
			}
			try {
				#
				$options = [
					'name' => "{$first_name} {$last_name}",
					'email' => $email,
					'phone' => $phone,
					'payment_sources' => [
						[
							'type' => 'card',
							'token_id' => $token
						]
					]
				];
				$customer = \Conekta\Customer::create($options);
				#
				$options = [
					'line_items' => [
						[
							'name' => $order->getMeta('concept'),
							'unit_price' => $order->total * 100,
							'quantity' => 1
						]
					],
					'currency' => 'MXN',
					'customer_info' => ['customer_id' => $customer->id],
					'metadata' => [
						'order' => $order->uid
					],
					'charges' => [
						[
							'payment_method' => $payment_method
						]
					]
				];
				$charge = \Conekta\Order::create($options);
				#
				if ($charge && $charge->payment_status == 'paid') {
					$order->payment_status = 'Paid';
					$order->payment_processor = 'Conekta';
					$order->payment_ticket = $charge->id;
					$order->payment_date = date('Y-m-d H:i:s');
					$order->save();
					$order->updateMeta('installments', $installments);
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

					if ($form->getMeta('event')) {

						$site->payments->enableConnector('notifications', new NotificationsConnector);
						$site->payments->notifyConnector($order, 'notifications');
						$site->payments->enableConnector('events', new EventsConnector);
						$site->payments->notifyConnector($order, 'events');
					}
					#
					$form = PaymentsForms::getById( $order->getMeta('form', 0) );
					$url = '';
					if ($form) {
						$url = $form->getMeta('thank_you_page');
					}
					$url = $url ?: $site->urlTo("/thanks/{$order->uid}");
					$site->redirectTo($url);
				}
			} catch (Exception $e) {
				log_to_file($e->getMessage(), 'conekta_error');
				log_to_file($e->getCode(), 'conekta_error');
				log_to_file($e->getLine(), 'conekta_error');
				$site->redirectTo( $site->urlTo("/review/{$order->uid}?error=".$e->getMessage()) );// TBD: Show proper error page
			}
		}
	}

?>