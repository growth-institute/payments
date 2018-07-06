<?php
	/**
	 * Form Class
	 *
	 * Payments Form
	 *
	 * @version  3.0
	 * @author   Miguel Escamilla <miguelangel.escamilla@thewebchi.mp>
	 */
	class FormMeta extends CROOD {

		public $id;
		public $id_form;
		public $name;
		public $value;
	

		/**
		 * Initialization callback
		 * @return nothing
		 */
		function init($args = false) {

			$now = date('Y-m-d H:i:s');

			$this->table = 					'payments_form_meta';
			$this->table_fields = 			array('id','id_form', 'name', 'value');
			$this->update_fields = 			array('id_form', 'name', 'value');
			$this->singular_class_name = 	'FormMeta';
			$this->plural_class_name = 		'FormsMeta';
			

			#metaModel
			$this->meta_id = 				'id_payments_form';
			$this->meta_table = 			'payments_form_meta';

			if (! $this->id ) {

				$this->id = '';
				$this->id_form = '';
				$this->name = '';
				$this->value = '';
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
	class FormsMeta extends NORM {

		protected static $table = 					'payments_form_meta';
		protected static $table_fields = 			array('id', 'id_form', 'name', 'value');
		protected static $singular_class_name = 	'FormMeta';
		protected static $plural_class_name = 		'FormsMeta';
	
		
	}
?>