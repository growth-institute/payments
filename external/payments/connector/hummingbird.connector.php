<?php

	class HummingbirdConnector extends PaymentsConnector {

		function process($order) {
			global $site;
			$order->fetchMetas();

			$form = PaymentsForms::getById($order->getMeta('form'));
			$form->fetchMetas();

			$params = [];
			$params['firstname'] = $order->metas->first_name;
			$params['lastname'] = $order->metas->last_name;
			$params['email'] = $order->metas->email;
			$params['processor'] = $order->payment_processor;
			$params['sku'] = json_decode($form->products);
			$params['locale'] = $form->language;
			$lang = $form->language;

			$curly = Curly::newInstance(false)
					->setMethod('get')
					->setURL("https://growthinstitute.com/{$lang}/payments/generic/")
					->setOptions([CURLOPT_FOLLOWLOCATION => true])
					->setParams($params)
					->execute();

			$res = $curly->getResponse('json');

			log_to_file(print_r($curly, 1), 'HummingbirdConnector');
			log_to_file(print_r($res, 1), 'HummingbirdConnector');
		}
	}
?>