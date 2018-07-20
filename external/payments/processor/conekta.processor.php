<?php

	class ConektaProcessor extends PaymentsProcessor {

		function getTitle() {
			return 'Tarjeta de crédito';
		}

		function getMarkup($form) {
			global $site;
			$data = [];
			$data['form'] = $form;
			$site->partial('payments/form-conekta', $data);
		}

		function includeDependencies($form) {
			global $site;
			$site->registerStyle('conekta-front', 'payments/conekta.css', false);
			$site->enqueueStyle('conekta-front');
			$site->registerScript('conekta-js', 'https://cdn.conekta.io/js/latest/conekta.js', true);
			$site->registerScript('conekta-front', 'payments/conekta.js', false);
			$site->enqueueScript('conekta-js');
			$site->enqueueScript('conekta-front');
			#
			$conekta_opts = $site->getOption('conekta');
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
							'payment_method' => ['type' => 'default']
						]
					]
				];
				if ($installments) {
					$options['monthly_installments'] = $installments;
				}
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
					$site->payments->notifyProcessed($order);
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
				$site->redirectTo( $site->urlTo('/error') ); // TBD: Show proper error page
			}
		}
	}

?>