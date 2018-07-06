<?php

	abstract class PaymentsConnector {

		abstract function process($order);
	}

?>