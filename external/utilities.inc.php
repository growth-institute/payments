<?php
	/**
	 * utilities.inc.php
	 * Add your additional functions here
	 */

	function sanitized_print($expr) {
		echo htmlspecialchars($expr);
	}

	function limited_print($expr, $chars = 100, $reverse = false) {
		echo htmlspecialchars( substr($expr, $reverse ? strlen($expr) - $chars : 0, $chars) ) . (strlen($expr) > $chars ? '...' : '');
	}

	function sanitized_object($object, $whitelist) {
		foreach ($object as $property => $value) {
			if (! in_array($property, $whitelist) ) {
				unset($object->$property);
			}
		}
		return $object;
	}

	function get_user_avatar($user, $size = 64, $echo = true) {
		$hash = $user ? md5($user->email) : md5('');
		$ret = "https://www.gravatar.com/avatar/{$hash}?s={$size}&amp;d=mm";
		if ($echo) {
			echo $ret;
		}
		return $ret;
	}

	function relative_time_en($date, $postfix = ' ago', $fallback = 'F Y') {
		$diff = time() - strtotime($date);
		if ($diff == 0) return 'just now';
		if ($diff < 60) return $diff . ' second' . ($diff != 1 ? 's' : '') . $postfix;
		$diff = round($diff / 60);
		if ($diff < 60) return $diff . ' minute' . ($diff != 1 ? 's' : '') . $postfix;
		$diff = round($diff / 60);
		if ($diff < 24) return $diff . ' hour' . ($diff != 1 ? 's' : '') . $postfix;
		$diff = round($diff / 24);
		if ($diff < 7) return $diff . ' day' . ($diff != 1 ? 's' : '') . $postfix;
		$diff = round($diff / 7);
		if ($diff < 4) return $diff . ' week' . ($diff != 1 ? 's' : '') . $postfix;
		$diff = round($diff / 4);
		if ($diff < 12) return $diff . ' month' . ($diff != 1 ? 's' : '') . $postfix;
		return date($fallback, strtotime($date));
	}

	function relative_time_es($date, $prefix = 'hace ', $fallback = 'd/m/Y') {
		$diff = time() - strtotime($date);
		if ($diff == 0) return 'justo ahora';
		if ($diff < 60) return $prefix . $diff . ' segundo' . ($diff != 1 ? 's' : '');
		$diff = round($diff / 60);
		if ($diff < 60) return $prefix . $diff . ' minuto' . ($diff != 1 ? 's' : '');
		$diff = round($diff / 60);
		if ($diff < 24) return $prefix . $diff . ' hora' . ($diff != 1 ? 's' : '');
		$diff = round($diff / 24);
		if ($diff < 7) return $prefix . $diff . ' día' . ($diff != 1 ? 's' : '');
		$diff = round($diff / 7);
		if ($diff < 4) return $prefix . $diff . ' semana' . ($diff != 1 ? 's' : '');
		$diff = round($diff / 4);
		if ($diff < 12) return $prefix . $diff . ' mes' . ($diff != 1 ? 'es' : '');
		return date($fallback, strtotime($date));
	}

	/**
	 * Get the client's IP address
	 * @return string The client's IP address
	 */
	function get_client_ip() {
		$ret = "0.0.0.0";
		if (isset($_SERVER)) {
			$ret = $_SERVER["REMOTE_ADDR"];
			if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ) {
				$ret = $_SERVER["HTTP_X_FORWARDED_FOR"];
			}
			if ( isset($_SERVER["HTTP_CLIENT_IP"]) ) {
				$ret = $_SERVER["HTTP_CLIENT_IP"];
			}
		}
		return $ret;
	}

	/**
	 * Get the client's user-agent
	 * @return string The client's user-agent
	 */
	function get_client_ua() {
		$ret = "Unknown";
		if (isset($_SERVER)) {
			if ( isset($_SERVER["HTTP_USER_AGENT"]) ) {
				$ret = $_SERVER["HTTP_USER_AGENT"];
			}
		}
		return $ret;
	}

	function table($array, $labels = array()) {
		$ret = '';
		$rows = '';
		foreach ($array as $indice => $dato) {
			$indice = isset($labels[$indice]) ? $labels[$indice] : $indice;
			$rows .= "<tr><td>{$indice}</td><td>{$dato}</td></tr>";
		}
		$ret = "<table><tbody>{$rows}</tbody></table>";
		return $ret;
	}

	/**
	 * Generate a password of the given length
	 * @param  integer $length Password length
	 * @return string          Generated password on success, False otherwise
	 */
	function generate_password($length) {
		global $site;
		$ret = false;
		try {
			require_once $site->baseDir('/external/lib/Random.php');
			$random = new Random(false);
			$ret = $random->token($length);
		} catch (Exception $e) {
			error_log( $e->getMessage() );
		}
		return $ret;
	}
?>