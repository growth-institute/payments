<?php

	/**
	 * CSBuddy
	 * @author 	biohzrdmx <github.com/biohzrdmx>
	 * @version 1.0
	 * @license MIT
	 * @example Basic usage:
	 *
	 *     // You provide the labels
	 *     $labels = array(
	 *        'foo',
	 *        'bar',
	 *        'baz',
	 *        'qux'
	 *     );
	 *
	 *     // And the rows (with your own logic of course)
	 *     $rows = $database->fetchStuff();
	 *
	 *     // Create a CSBuddy instance, set the labels, the rows and generate it
	 *     $csv = CSBuddy::newInstance()
	 *       ->setLabels($labels)
	 *       ->setRows($rows)
	 *       ->generate('foo.csv');
	 *
	 */

	class CSBuddy {

		protected $labels;
		protected $rows;

		/**
		 * Constructor
		 */
		function __construct() {
			$this->labels = array();
			$this->rows = array();
		}

		/**
		 * Create a new instance and return it
		 * @return object The new instance
		 */
		static function newInstance() {
			$new = new self();
			return $new;
		}

		/**
		 * Escape an string (note: be careful with magic quotes)
		 * @param  string $string The input string
		 * @return string         The escaped string
		 */
		static function escape($string) {
			return '"' . $string . '"';
		}

		/**
		 * Set the labels
		 * @param array $labels An array with the labels
		 */
		function setLabels($labels) {
			$this->labels = $labels;
			return $this;
		}

		/**
		 * Set the rows
		 * @param array $rows An array of objects/arrays containing the row fields
		 */
		function setRows($rows) {
			$this->rows = $rows;
			return $this;
		}

		/**
		 * Add a label to the labels array
		 * @param string $label The new label
		 */
		function addLabel($label) {
			$this->labels[] = $label;
			return $this;
		}

		/**
		 * Add a row to the rows array
		 * @param array $row Object/array with row data
		 */
		function addRow($row) {
			$this->rows[] = $row;
			return $this;
		}

		/**
		 * Generate the CSV file and send it to the browser
		 * @param  string $name CSV file name
		 * @return nothing
		 */
		function generate($name) {
			$nl = "\n";
			#
			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename={$name}");
			header("Pragma: no-cache");
			header("Expires: 0");
			#
			if ($this->labels) {
				$line = implode(',', $this->labels);
			}
			echo utf8_decode("{$line}{$nl}");
			if ($this->rows) {
				foreach ($this->rows as $row) {
					$line = implode(',', array_map('CSBuddy::escape', (array) $row));
					echo utf8_decode("{$line}{$nl}");
				}
			}
			return $this;
		}
	}

?>