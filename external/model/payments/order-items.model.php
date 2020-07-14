<?php

	/**
	 * PaymentsOrderItem Class
	 *
	 * Payments Order Item
	 *
	 * @version  1.0
	 * @author   Raul Vera <raul.vera@chimp.mx>
	 */
	class PaymentsOrderItem extends CROOD {

		public $id;
		public $id_order;
		public $sku;
		public $details;
		public $price;
		public $quantity;

		/**
		 * Initialization callback
		 * @return nothing
		 */
		function init($args = false) {

			$now = date('Y-m-d H:i:s');

			$this->table = 					'payments_order_item';
			$this->table_fields = 			array('id', 'id_order', 'sku', 'details', 'price', 'quantity');
			$this->update_fields = 			array('id_order', 'sku', 'details', 'price', 'quantity');
			$this->singular_class_name = 	'PaymentsOrderItem';
			$this->plural_class_name = 		'PaymentsOrderItems';

			if (! $this->id ) {

				$this->id = '';
				$this->id_order = 0;
				$this->sku = '';
				$this->details = '';
				$this->price = '';
				$this->quantity = '';
			}

			else {

				$args = $this->preInit($args);

				# Enter your logic here
				# ----------------------------------------------------------------------------------



				# ----------------------------------------------------------------------------------

				$args = $this->postInit($args);
			}
		}
	}

	# ==============================================================================================

	/**
	 * PaymentsOrderItems Class
	 *
	 * Payments Order Items
	 *
	 * @version 1.0
	 * @author  Raul Vera <raul.vera@chimp.mx>
	 */
	class PaymentsOrderItems extends NORM {

		protected static $table = 					'payments_order_item';
		protected static $table_fields = 			array('id', 'id_order', 'sku', 'details', 'price', 'quantity');
		protected static $singular_class_name = 	'PaymentsOrderItem';
		protected static $plural_class_name = 		'PaymentsOrderItems';
	}
?>