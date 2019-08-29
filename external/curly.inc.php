<?php

	class Curly {

		protected $method;
		protected $url;
		protected $params;
		protected $fields;
		protected $headers;
		protected $response;
		protected $options;
		protected $caching;

		function __construct() {
			$this->method = 'get';
			$this->url = 'http://localhost';
			$this->params = array();
			$this->fields = array();
			$this->headers = array();
			$this->options = array();
			$this->response = '';
			$this->caching = true;
		}

		static function newInstance($caching = true) {
			$new = new self();
			$new->caching = $caching;
			return $new;
		}

		function setMethod($method) {
			$this->method = $method;
			return $this;
		}

		function setURL($url) {
			$this->url = $url;
			return $this;
		}

		function setParams($params) {
			$this->params = $params;
			return $this;
		}

		function setFields($fields) {
			$this->fields = $fields;
			return $this;
		}

		function setHeaders($headers) {
			$this->headers = $headers;
			return $this;
		}

		function setOptions($options) {
			$this->options = $options;
			return $this;
		}

		function getResponse($format = 'html') {
			$ret = '';
			switch ($format) {
				case 'json':
					$ret = json_decode($this->response);
				break;
				default:
					$ret = $this->response;
				break;
			}
			return $ret;
		}

		function execute() {
			if ($this->caching) {
				$url = $this->url;
				$query = http_build_query($this->params);
				if ($query && $this->method == 'get') {
					$url = "{$this->url}?{$query}";
				}
				$hash = md5($url);
				$data = Cacher::getFromCache($hash, 900);
				if (! $data ) {
					$this->_execute();
					$data = json_decode($this->response);
					Cacher::saveToCache($hash, $data);
				} else {
					$this->response = json_encode( $data );
				}
			} else {
				$this->_execute();
			}
			return $this;
		}

		protected function _execute() {
			global $site;
			# Create query string
			$query = http_build_query($this->params);
			$url = $this->url;
			if ($query) {
				$url = "{$this->url}?{$query}";
			}
			# Open connection
			$ch = curl_init();
			# Set the url, number of POST vars, POST data, etc
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			# Extra options
			if ($this->options) {
				foreach ($this->options as $key => $value) {
					curl_setopt($ch, $key, $value);
				}
			}
			# Add headers
			if ($this->headers) {
				$headers = array();
				foreach ($this->headers as $key => $value) {
					$headers[] = "{$key}: {$value}";
				}
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			}
			# SSL
			if ( preg_match('/https:\/\//', $url) === 1 ) {
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
				curl_setopt($ch, CURLOPT_CAINFO, $site->baseDir('/cacert.pem'));
			}
			# POST/PUT/DELETE
			if ($this->method != 'get') {
				if ( is_array($this->fields) ) {
					$fields = http_build_query($this->fields);
					curl_setopt($ch, CURLOPT_POST, count($this->fields));
					curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
				} else {
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($this->method));
					curl_setopt($ch, CURLOPT_POSTFIELDS, $this->fields);
				}
			}
			# Execute request
			$this->response = curl_exec($ch);
			print_a( curl_getinfo($ch) );
			if ( curl_errno($ch) ) {
				log_to_file(curl_error($ch), 'curly');
			}
			# Close connection
			curl_close($ch);
		}
	}

?>