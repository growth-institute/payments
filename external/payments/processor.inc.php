<?php

	abstract class PaymentsProcessor {

		abstract function getTitle();
		abstract function getMarkup($form);
		abstract function includeDependencies($form);
		abstract function process($order, $fields = []);

		function webhook($fields = []) {
			return false;
		}
	}

?>