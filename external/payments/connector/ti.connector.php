<?php

	class TIConnector extends PaymentsConnector {

		function process($order) {
			global $site;
			$order->fetchMetas();
			$form = PaymentsForms::getById($order->getMeta('form'));
			$form->fetchMetas();


			$res = $curly->getResponse('json');
		}
	}
?>