<?php

	/**
	 * PaymentsOrder Class
	 *
	 * Payments Order
	 *
	 * @version  1.0
	 * @author   Raul Vera <raul.vera@chimp.mx>
	 */
	class PaymentsOrder extends CROOD {

		public $id;
		public $id_user;
		public $uid;
		public $total;
		public $currency;
		public $payment_status;
		public $payment_processor;
		public $payment_date;
		public $payment_ticket;
		public $sandbox;
		public $created;
		public $modified;

		/**
		 * Initialization callback
		 * @return nothing
		 */
		function init($args = false) {

			$now = date('Y-m-d H:i:s');

			$this->table = 					'payments_order';
			$this->table_fields = 			array('id', 'id_user', 'uid', 'total', 'currency', 'payment_status', 'payment_processor', 'payment_date', 'payment_ticket', 'sandbox', 'created', 'modified');
			$this->update_fields = 			array('id_user', 'uid', 'total', 'currency', 'payment_status', 'payment_processor', 'payment_date', 'payment_ticket', 'sandbox', 'modified');
			$this->singular_class_name = 	'PaymentsOrder';
			$this->plural_class_name = 		'PaymentsOrders';

			#metaModel
			$this->meta_id = 				'id_order';
			$this->meta_table = 			'payments_order_meta';

			if (! $this->id ) {

				$this->id = '';
				$this->id_user = 0;
				$this->uid = '';
				$this->total = 0;
				$this->currency = '';
				$this->payment_status = 'Pending';
				$this->payment_processor = '';
				$this->payment_date = '';
				$this->payment_ticket = '';
				$this->sandbox = false;
				$this->created = $now;
				$this->modified = $now;
				$this->metas = new stdClass();
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
	 * PaymentsOrders Class
	 *
	 * Payments Orders
	 *
	 * @version 1.0
	 * @author  Raul Vera <raul.vera@chimp.mx>
	 */
	class PaymentsOrders extends NORM {

		protected static $table = 					'payments_order';
		protected static $table_fields = 			array('id', 'id_user', 'uid', 'total', 'currency', 'payment_status', 'payment_processor', 'payment_date', 'payment_ticket', 'sandbox', 'created', 'modified');
		protected static $singular_class_name = 	'PaymentsOrder';
		protected static $plural_class_name = 		'PaymentsOrders';
	}
?>