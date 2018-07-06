<?php

	class PayPalProcessor extends PaymentsProcessor {

		function getTitle() {
			return 'PayPal';
		}

		function getMarkup($form) {
			global $site;
			#
			$paypal_opts = $site->getOption('paypal');
			$paypal_account = get_item($paypal_opts, 'account');
			$paypal_sandbox = get_item($paypal_opts, 'sandbox');
			$paypal_url = $paypal_sandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
			#
			$data = [];
			$data['form'] = $form;
			$data['paypal_account'] = $paypal_account;
			$data['paypal_url'] = $paypal_url;
			$site->partial('payments/form-paypal', $data);
		}

		function includeDependencies($form) {
			return false;
		}

		function process($order, $fields = []) {
			return false;
		}

		function webhook($fields = []) {
			global $site;
			#
			$paypal_opts = $site->getOption('paypal');
			$paypal_account = get_item($paypal_opts, 'account');
			#
			// print_a($fields); exit;
			$res = $this->validate($fields);
			# Get values
			$item_name = get_item($fields, 'item_name');
			$item_number = get_item($fields, 'item_number');
			$payment_status = get_item($fields, 'payment_status');
			$payment_amount = get_item($fields, 'mc_gross');
			$payment_currency = get_item($fields, 'mc_currency');
			$txn_id = get_item($fields, 'txn_id');
			$ipn_track_id = get_item($fields, 'ipn_track_id');
			$receiver_email = get_item($fields, 'receiver_email');
			$payer_email = get_item($fields, 'payer_email');
			$resend = get_item($fields, 'resend');
			$custom = get_item($fields, 'custom');
			# Check payment
			if ( strcmp($res, "VERIFIED") == 0 ) {
				# Decode payload
				$req_order = $custom;
				$order = PaymentsOrders::getByUid($req_order);
				if ($order) {
					# Process payment
					log_to_file("PayPal IPN notification {$ipn_track_id}::{$txn_id} ({$res})", 'paypal');
					log_to_file("Received verified payment from {$payer_email}", 'paypal');
					log_to_file(print_r($_POST, true), 'paypal');
					# Check payment status
					if ( $payment_status == 'Completed' && $receiver_email == $paypal_account && $payment_amount == $order->total && $payment_currency == strtoupper($order->currency) ) {
						log_to_file("Payment status from {$payer_email} is now \"Completed\"", 'paypal');
						$order->payment_status = 'Paid';
						$order->payment_processor = 'PayPal';
						$order->payment_ticket = $txn_id;
						$order->payment_date = date('Y-m-d H:i:s');
						$order->save();
						$order->updateMeta('installments', 0);
						# Notify the payments system
						$site->payments->notifyProcessed($order);
					}
					log_to_file('---------------------------------', 'paypal');
				} else {
					//
				}
			} else if ( strcmp($res, "INVALID") == 0 ) {
				# Log for manual investigation
				log_to_file("{$txn_id}::{$ipn_track_id} ({$res})", 'paypal');
				log_to_file("Received invalid payment from {$payer_email}", 'paypal');
				log_to_file(print_r($_POST, true), 'paypal');
				log_to_file('---------------------------------', 'paypal');
			}
		}

		protected function validate($fields) {
			global $site;
			#
			$paypal_opts = $site->getOption('paypal');
			$paypal_sandbox = get_item($paypal_opts, 'sandbox');
			$paypal_url = $paypal_sandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
			# Tell PayPal we're going to validate the payment
			$req = 'cmd=' . urlencode('_notify-validate');
			# Build the query string
			foreach ($fields as $key => $value) {
				$value = urlencode(stripslashes($value));
				$req .= "&$key=$value";
			}
			# Send request using cURL
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $paypal_url);
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
			curl_setopt($ch, CURLOPT_CAINFO, $site->baseDir('/cacert.pem'));
			$res = curl_exec($ch);
			curl_close($ch);
			# Check return code
			return $res;
		}
	}

?>