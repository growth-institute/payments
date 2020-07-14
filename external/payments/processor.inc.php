<?php

	abstract class PaymentsProcessor {

		abstract function getTitle();
		abstract function getMarkup($form, $order);
		abstract function includeDependencies($form, $order);
		abstract function process($order, $fields = []);

		function webhook($fields = []) {
			return false;
		}
	}

?>