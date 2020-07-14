<?php

	/**
	 * EventOrder Class
	 *
	 * Event Order
	 *
	 * @version  1.0
	 * @author   Miguel Escamilla <miguelangel.escamilla@thewebchi.mp>
	 */
	class EventOrder extends CROOD {

		public $id;
		public $id_order;
		public $id_event;
		public $event_name;
		public $user_name;
		public $user_email;
		public $ticket_number;
		public $event_code;
		public $status;
		public $created;
		public $modified;

		/**
		 * Initialization callback
		 * @return nothing
		 */
		function init($args = false) {

			$now = date('Y-m-d H:i:s');

			$this->table = 					'event_order';
			$this->table_fields = 			array('id', 'id_order', 'id_event', 'event_name', 'user_name', 'user_email', 'ticket_number', 'event_code', 'status', 'created', 'modified');
			$this->update_fields = 			array('id_order', 'id_event', 'event_name', 'user_name', 'user_email', 'ticket_number', 'event_code', 'status', 'modified');
			$this->singular_class_name = 	'EventOrder';
			$this->plural_class_name = 		'EventOrders';


			#metaModel
			$this->meta_id = 				'id_event_order';
			$this->meta_table = 			'event_order_meta';

			if (! $this->id ) {

				$this->id = '';
				$this->id_order = 0;
				$this->id_event = 0;
				$this->event_name = '';
				$this->user_name = '';
				$this->user_email = '';
				$this->ticket_number = '';
				$this->event_code = '';
				$this->status = '';
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
	 * EventOrders Class
	 *
	 * Event Orders
	 *
	 * @version 1.0
	 * @author  Miguel Escamilla <miguelangel.escamilla@thewebchi.mp>
	 */
	class EventOrders extends NORM {

		protected static $table = 					'event_order';
		protected static $table_fields = 			array('id', 'id_order', 'id_event', 'event_name', 'user_name', 'user_email', 'ticket_number', 'event_code', 'status', 'created', 'modified');
		protected static $singular_class_name = 	'EventOrder';
		protected static $plural_class_name = 		'EventOrders';
	}
?>