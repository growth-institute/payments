<?php
	/**
	 * Form Class
	 *
	 * Payments Form
	 *
	 * @version  3.0
	 * @author   Miguel Escamilla <miguelangel.escamilla@thewebchi.mp>
	 */
	class Form extends CROOD {

		public $id;
		public $name;
		public $slug;
		public $products;
		public $language;
		public $processor;
		public $currency;
		public $total;
		public $subscription;

		/**
		 * Initialization callback
		 * @return nothing
		 */
		function init($args = false) {

			$now = date('Y-m-d H:i:s');

			$this->table = 					'payments_form';
			$this->table_fields = 			array('id', 'name', 'slug', 'products', 'language', 'processor', 'currency', 'total', 'subscription');
			$this->update_fields = 			array('name', 'slug', 'products', 'language', 'processor', 'currency', 'total', 'subscription');
			$this->singular_class_name = 	'Form';
			$this->plural_class_name = 		'Forms';
			

			#metaModel
			$this->meta_id = 				'id_form';
			$this->meta_table = 			'payments_form_meta';

			if (! $this->id ) {

				$this->id = '';
				$this->name = '';
				$this->slug = '';
				$this->products = '';
				$this->language = '';
				$this->processor = '';
				$this->currency = '';
				$this->total = '';
				$this->subscription = '';
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
	 * PaymentsForms Class
	 *
	 * Payments Forms
	 *
	 * @version 1.0
	 * @author  Miguel Escamilla <miguelangel.escamilla@thewebchi.mp>
	 */
	class Forms extends NORM {

		protected static $table = 					'payments_form';
		protected static $table_fields = 			array('id', 'name', 'slug', 'products', 'language', 'processor', 'currency', 'total', 'subscription');
		protected static $singular_class_name = 	'Form';
		protected static $plural_class_name = 		'Forms';
		
	}
?>