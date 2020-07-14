<?php

	class Upload {

		public $name;
		public $type;
		public $tmp_name;
		public $error;
		public $size;

		const RENAME_ADD_UNIQID = 0;
		const RENAME_ADD_NUMBER = 1;
		const RENAME_ADD_TIME   = 2;

		protected function __construct($arr) {
			$this->name = $arr['name'];
			$this->type = $arr['type'];
			$this->tmp_name = $arr['tmp_name'];
			$this->error = $arr['error'];
			$this->size = $arr['size'];
		}

		public static function fromFile($arg) {
			$ret = false;
			if ( is_string($arg) ) {
				# A string, so let's try to get the item from $_FILES
				if ( isset( $_FILES[$arg] ) && is_array( $_FILES[$arg] ) ) {
					$ret = self::fromFile( $_FILES[$arg] );
				}
			} else if ( is_array( $arg ) ) {
				$ret = new Upload( $arg );
			}
			return $ret;
		}

		public function isValid() {
			return !!trim($this->name);
		}

		public function checkExtension($exts) {
			$ret = false;
			if ( is_string($exts) ) {
				$exts = explode('|', $exts);
				$ret = $this->checkExtension($exts);
			} else if ( is_array($exts) ) {
				$ext = substr($this->name, strrpos($this->name, '.') + 1);
				$ret = in_array( strtolower($ext), $exts);
			}
			return $ret;
		}

		public function checkMime($types) {
			$ret = false;
			if ( is_string($types) ) {
				$types = explode('|', $types);
				$ret = $this->checkMime($types);
			} else if ( is_array($types) ) {
				$ret = in_array($this->type, $types);
			}
			return $ret;
		}

		public function checkSize($max, $min = 0) {
			$ret = false;
			$ret = $this->size >= $min && $this->size < $max;
			return $ret;
		}

		public function moveFile($destination, $overwrite = false, $rename = Upload::RENAME_ADD_UNIQID) {
			$ret = false;
			$destination = preg_replace('/(\/|\\\)$/', '', $destination);
			$dest_path = "{$destination}/{$this->name}";
			$exists = file_exists($dest_path);
			if (!$exists || ($exists && $overwrite)) {
				move_uploaded_file($this->tmp_name, $dest_path);
				$ret = true;
			} else {
				$name = substr($dest_path, 0, strrpos($dest_path, '.'));
				$ext = substr($dest_path, strrpos($dest_path, '.') + 1);
				switch ($rename) {
					case Upload::RENAME_ADD_UNIQID:
						$suffix = uniqid();
						$dest_path = "{$name}_{$suffix}.{$ext}";
					break;
					case Upload::RENAME_ADD_NUMBER:
						$res = glob("{$name}_*.{$ext}");
						$suffix = count($res) + 1;
						$dest_path = "{$name}_{$suffix}.{$ext}";
					break;
					case Upload::RENAME_ADD_TIME:
						$suffix = time();
						$dest_path = "{$name}_{$suffix}.{$ext}";
					break;
				}
				if ($dest_path) {
					move_uploaded_file($this->tmp_name, $dest_path);
					$ret = true;
				}
			}
			return $ret;
		}

		public function asFile() {
			return (array) $this;
		}
	}

?>