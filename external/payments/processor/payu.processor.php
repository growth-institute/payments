<?php
	class PayuProcessor extends PaymentsProcessor {

		function getTitle() {
			return 'PayU';
		}

		function getMarkup($form, $order) {
			global $site;
			$data = [];
			$data['form'] = $form;

			$site->partial('payments/form-payu', $data);
		}

		function includeDependencies($form, $order) {
			global $site;
			$site->registerStyle('payu-front', 'payments/payu.css', false);
			$site->enqueueStyle('payu-front');
			$site->registerScript('payu-js', 'https://gateway.payulatam.com/ppp-web-gateway/javascript/PayU.js', true);
			$site->registerScript('payu-front', 'payments/payu.js', false);
			$site->enqueueScript('payu-js');
			$site->enqueueScript('payu-front');
			$payu_opts = $site->getOption('payu');
			$payu_opts = get_item($payu_opts, $order->sandbox ? 'sandbox' : 'production');
			$payu_public = get_item($payu_opts, 'public_key');
			$payu_url = get_item($payu_opts, 'url');
			$site->addScriptVar('payuPublicKey', $payu_public);
			$site->addScriptVar('payuUrl', $payu_url);
		}

		function process($order, $fields = []) {
			global $site;
			$token = get_item($fields, 'payuTokenId');
			$payu_opts = $site->getOption('payu');
			$payu_opts = get_item($payu_opts, $order->sandbox ? 'sandbox' : 'production');
			//$payu_public = get_item($payu_opts, 'public_key');
			include $site->baseDir('/external/lib/Payu/PayU.php');

			$first_name = $order->getMeta('first_name');
			$last_name = $order->getMeta('last_name');
			$email = $order->getMeta('email');
			$phone = $order->getMeta('phone');

			try {
				$parameters = array(
					//Ingrese aquí el identificador de la cuenta.
					PayUParameters::ACCOUNT_ID => "512324",
					//Ingrese aquí el código de referencia.
					PayUParameters::REFERENCE_CODE => $reference,
					//Ingrese aquí la descripción.
					PayUParameters::DESCRIPTION => "payment test",

					// -- Valores --
					//Ingrese aquí el valor.
					PayUParameters::VALUE => $value,
					//Ingrese aquí la moneda.
					PayUParameters::CURRENCY => "MXN",

					// -- Comprador
					//Ingrese aquí el nombre del comprador.
					PayUParameters::BUYER_NAME => "{$first_name} {$last_name}",
					//Ingrese aquí el email del comprador.
					PayUParameters::BUYER_EMAIL => $email,
					//Ingrese aquí el teléfono de contacto del comprador.
					PayUParameters::BUYER_CONTACT_PHONE => $phone,
					//Ingrese aquí el documento de contacto del comprador.
					PayUParameters::BUYER_DNI => "5415668464654",
					//Ingrese aquí la dirección del comprador.
					/*PayUParameters::BUYER_STREET => "Calle Salvador Alvarado",
					PayUParameters::BUYER_STREET_2 => "8 int 103",
					PayUParameters::BUYER_CITY => "Guadalajara",
					PayUParameters::BUYER_STATE => "Jalisco",
					PayUParameters::BUYER_COUNTRY => "MX",
					PayUParameters::BUYER_POSTAL_CODE => "000000",
					PayUParameters::BUYER_PHONE => "7563126",*/

					// -- pagador --
					//Ingrese aquí el nombre del pagador.
					/*PayUParameters::PAYER_NAME => "First name and second payer name",
					//Ingrese aquí el email del pagador.
					PayUParameters::PAYER_EMAIL => "payer_test@test.com",
					//Ingrese aquí el teléfono de contacto del pagador.
					PayUParameters::PAYER_CONTACT_PHONE => "7563126",
					//Ingrese aquí el documento de contacto del pagador.
					PayUParameters::PAYER_DNI => "5415668464654",
					//OPCIONAL fecha de nacimiento del pagador YYYY-MM-DD, importante para autorización de pagos en México.
					PayUParameters::PAYER_BIRTHDATE => '1980-06-22',

					//Ingrese aquí la dirección del pagador.
					PayUParameters::PAYER_STREET => "Calle Zaragoza esquina",
					PayUParameters::PAYER_STREET_2 => "calle 5 de Mayo",
					PayUParameters::PAYER_CITY => "calle 5 de Mayo",
					PayUParameters::PAYER_STATE => "Nuevo Leon",
					PayUParameters::PAYER_COUNTRY => "MX",
					PayUParameters::PAYER_POSTAL_CODE => "64000",
					PayUParameters::PAYER_PHONE => "7563126",*/

					// DATOS DEL TOKEN
					PayUParameters::TOKEN_ID => $token,

					//Ingrese aquí el nombre de la tarjeta de crédito
					//VISA||MASTERCARD||AMEX
					PayUParameters::PAYMENT_METHOD => "VISA",

					//Ingrese aquí el número de cuotas.
					//PayUParameters::INSTALLMENTS_NUMBER => "1",
					//Ingrese aquí el nombre del pais.
					PayUParameters::COUNTRY => PayUCountries::MX,

					//Session id del device.
					/*PayUParameters::DEVICE_SESSION_ID => "vghs6tvkcle931686k1900o6e1",
					//IP del pagadador
					PayUParameters::IP_ADDRESS => "127.0.0.1",
					//Cookie de la sesión actual.
					PayUParameters::PAYER_COOKIE=>"pt1t38347bs6jc9ruv2ecpv7o2",
					//Cookie de la sesión actual.
					PayUParameters::USER_AGENT=>"Mozilla/5.0 (Windows NT 5.1; rv:18.0) Gecko/20100101 Firefox/18.0"*/
				);

				$charge = PayUPayments::doAuthorizationAndCapture($parameters);
				if ($charge && $charge->payment_status == 'paid') {
					$order->payment_status = 'Paid';
					$order->payment_processor = 'PayU';
					$order->payment_ticket = $charge->id;
					$order->payment_date = date('Y-m-d H:i:s');
					$order->save();
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
				$site->redirectTo( $site->urlTo('/error') );
			}
		}



	}
?>