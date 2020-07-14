

<?php

	/**
	 * Event Class
	 *
	 * Event
	 *
	 * @version  1.0
	 * @author   Miguel Escamilla <miguelangel.escamilla@thewebchi.mp>
	 */
	class Event extends CROOD {

		public $id;
		public $name;
		public $description;
		public $created;
		public $modified;

		/**
		 * Initialization callback
		 * @return nothing
		 */
		function init($args = false) {

			$now = date('Y-m-d H:i:s');

			$this->table = 					'event';
			$this->table_fields = 			array('id', 'name', 'description', 'created', 'modified');
			$this->update_fields = 			array('name', 'description', 'modified');
			$this->singular_class_name = 	'Event';
			$this->plural_class_name = 		'Events';


			#metaModel
			$this->meta_id = 				'id_event';
			$this->meta_table = 			'event_meta';

			if (! $this->id ) {

				$this->id = '';
				$this->name = '';
				$this->description = '';
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
	 * Events Class
	 *
	 * Events
	 *
	 * @version 1.0
	 * @author  Miguel Escamilla <miguelangel.escamilla@thewebchi.mp>
	 */
	class Events extends NORM {

		protected static $table = 					'event';
		protected static $table_fields = 			array('id', 'name', 'description', 'created', 'modified');
		protected static $singular_class_name = 	'Event';
		protected static $plural_class_name = 		'Events';
	}
?>