<?php

	class PaymentsCartItem {

		function __construct() {
			$this->name = '';
			$this->quantity = 0;
			$this->price = 0;
			$this->details = '';
			$this->extras = '';
		}

		public $name;
		public $quantity;
		public $price;
		public $details;
		public $extras;
	}

	class PaymentsCart {

		private static $instance;

		public $uid;
		public $total;
		public $items;

		public static function getInstance() {
			if (null === static::$instance) {
				static::$instance = new static();
			}
			return static::$instance;
		}

		protected function __construct() {
			session_start();
			$this->sync(true);
		}

		function getItems() {
			return $this->items();
		}

		function addItem($sku, $item, $replace = false) {
			if (! isset( $this->items[$sku] ) || $replace = true) {
				$this->items[$sku] = $item;
			} else {
				$this->items[$sku]->quantity += $item->quantity;
			}
			$this->sync(false);
		}

		function removeItem($sku) {
			if ( isset( $this->items[$sku] ) ) {
				unset( $this->items[$sku] );
			}
			$this->sync(false);
		}

		function clear() {
			$this->items = [];
			$this->total = 0;
			$this->sync(false);
		}

		function reset() {
			$this->uid = hash_hmac('sha256', uniqid().microtime(), 'Y1a0ZhTSfHp6qvo');
			$this->items = [];
			$this->total = 0;
			$this->sync(false);
		}

		function sync($load = false) {
			if ($load) {
				$temp = @json_decode( $_SESSION['paymentsCart'] );
				if ($temp) {
					$this->uid = $temp->uid;
					$this->total = $temp->total;
					$this->items = (array) $temp->items;
				} else {
					$this->reset();
				}
			} else {
				$_SESSION['paymentsCart'] = json_encode($this);
			}
		}

		private function __clone() {}
		private function __wakeup() {}
	}

?>