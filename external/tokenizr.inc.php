<?php

	class Tokenizr {

		static function getToken($data) {
			global $site;
			$ret = false;
			$key = $site->getGlobal('token_salt');
			$hash = hash_hmac('sha256', $data, $key);
			$ret = "{$data}.{$hash}";
			return $ret;
		}

		static function checkToken($token) {
			global $site;
			$ret = false;
			$key = $site->getGlobal('token_salt');
			$parts = explode('.', $token);
			$data = get_item($parts, 0);
			$hash = get_item($parts, 1);
			if ($data && $hash) {
				$check = hash_hmac('sha256', $data, $key);
				$ret = $hash === $check;
			}
			return $ret;
		}

		static function getData($token) {
			global $site;
			$ret = false;
			$key = $site->getGlobal('token_salt');
			$parts = explode('.', $token);
			$ret = get_item($parts, 0);
			return $ret;
		}
	}

?>