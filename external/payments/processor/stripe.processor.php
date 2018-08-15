<?php

	class StripeProcessor extends PaymentsProcessor {

		function getTitle() {
			return 'Credit card';
		}

		function getMarkup($form, $order) {
			global $site;
			$data = [];
			$data['form'] = $form;
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
			$stripe_opts_cur = $site->getOption('stripe');
			$stripe_opts = get_item($stripe_opts_cur, $order->sandbox ? 'sandbox' : 'production');
			$stripe_opts = get_item($stripe_opts, 'usd');
			$stripe_publishable = get_item($stripe_opts, 'publishable_key');
			$site->addScriptVar('stripePublishableKey', $stripe_publishable);
		}

		function process($order, $fields = []) {
			global $site;
			#
			$token = get_item($fields, 'stripeToken');
			$quantity = get_item($fields, 'quantity', 0);
			#
			$stripe_opts_cur = $site->getOption('stripe');
			$stripe_opts = get_item($stripe_opts_cur, $order->sandbox ? 'sandbox' : 'production');
			$stripe_opts = get_item($stripe_opts, $order->currency);
			$stripe_secret = get_item($stripe_opts, 'secret_key');

			$form = Forms::getById($order->getMeta('form'));
			$extra_seats_price = $form->getMeta('extra_seats_price');
			$discounts = $form->getMeta('discounts');
			#
			include $site->baseDir('/external/lib/Stripe/init.php');
			\Stripe\Stripe::setApiKey($stripe_secret);

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

			$options = array(
				'amount' => $charge_amount*100,
				'currency' => $order->currency,
				'description' => $charge_description,
				'source' => $token,
				'metadata' => (array)$order->getMetas()
			);
			try {
				$charge = \Stripe\Charge::create($options);
				if ($charge && $charge->status == 'succeeded') {
					$order->payment_status = 'Paid';
					$order->payment_processor = 'Stripe';
					$order->payment_ticket = $charge->id;
					$order->payment_date = date('Y-m-d H:i:s');
					$order->total = $charge_amount;
					$order->save();
					$order->updateMeta('installments', 0);
					$order->updateMeta('quantity', $quantity);
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
				} else {
					//
				}
			} catch (Exception $e) {
				log_to_file($e->getMessage(), 'stripe_error');
				$site->redirectTo( $site->urlTo('/error') ); // TBD: Show proper error page
			}
		}
	}

?>