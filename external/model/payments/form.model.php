<?php

	/**
	 * PaymentsForm Class
	 *
	 * Payments Form
	 *
	 * @version  1.0
	 * @author   Raul Vera <raul.vera@chimp.mx>
	 */
	class PaymentsForm extends CROOD {

		public $id;
		public $name;
		public $slug;
		public $products;
		public $language;
		public $processor;
		public $currency;
		public $total;
		public $created;
		public $modified;

		/**
		 * Initialization callback
		 * @return nothing
		 */
		function init($args = false) {

			$now = date('Y-m-d H:i:s');

			$this->table = 					'payments_form';
			$this->table_fields = 			array('id', 'name', 'slug', 'products', 'language', 'processor', 'currency', 'total', 'created', 'modified');
			$this->update_fields = 			array('name', 'slug', 'products', 'language', 'processor', 'currency', 'total', 'modified');
			$this->singular_class_name = 	'PaymentsForm';
			$this->plural_class_name = 		'PaymentsForms';

			#metaModel
			$this->meta_id = 				'id_form';
			$this->meta_table = 			'payments_form_meta';

			if (! $this->id ) {

				$this->id = 0;
				$this->name = '';
				$this->slug = '';
				$this->products = '';
				$this->language = '';
				$this->processor = '';
				$this->currency = '';
				$this->total = 0;
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
	 * PaymentsForms Class
	 *
	 * Payments Forms
	 *
	 * @version 1.0
	 * @author  Raul Vera <raul.vera@chimp.mx>
	 */
	class PaymentsForms extends NORM {

		protected static $table = 					'payments_form';
		protected static $table_fields = 			array('id', 'name', 'slug', 'products', 'language', 'processor', 'currency', 'total', 'created', 'modified');
		protected static $singular_class_name = 	'PaymentsForm';
		protected static $plural_class_name = 		'PaymentsForms';
	}
?>