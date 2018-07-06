<?php

	class StripeProcessor extends PaymentsProcessor {

		function getTitle() {
			return 'Credit card';
		}

		function getMarkup($form) {
			global $site;
			$data = [];
			$data['form'] = $form;
			$site->partial('payments/form-stripe');
		}

		function includeDependencies($form) {
			global $site;
			$site->registerStyle('stripe-front', 'payments/stripe.css', false);
			$site->enqueueStyle('stripe-front');
			$site->registerScript('stripe-js', 'https://js.stripe.com/v3/', true);
			$site->registerScript('stripe-front', 'payments/stripe.js', false);
			$site->enqueueScript('stripe-js');
			$site->enqueueScript('stripe-front');
			#
			$stripe_opts_cur = $site->getOption('stripe');
			$stripe_opts = get_item($stripe_opts_cur, $form->currency);
			$stripe_publishable = get_item($stripe_opts, 'publishable_key');
			$site->addScriptVar('stripePublishableKey', $stripe_publishable);
		}

		function process($order, $fields = []) {
			global $site;
			#
			$token = get_item($fields, 'stripeToken');
			#
			$stripe_opts_cur = $site->getOption('stripe');
			$stripe_opts = get_item($stripe_opts_cur, $order->currency);
			$stripe_secret = get_item($stripe_opts, 'secret_key');
			#
			include $site->baseDir('/external/lib/Stripe/init.php');
			\Stripe\Stripe::setApiKey($stripe_secret);
			# Charge the user's card
			$options = array(
				'amount' => $order->total,
				'currency' => $order->currency,
				'description' => $order->getMeta('concept'),
				'source' => $token
			);
			try {
				$charge = \Stripe\Charge::create($options);
				if ($charge && $charge->status == 'succeeded') {
					$order->payment_status = 'Paid';
					$order->payment_processor = 'Stripe';
					$order->payment_ticket = $charge->id;
					$order->payment_date = date('Y-m-d H:i:s');
					$order->save();
					$order->updateMeta('installments', 0);
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