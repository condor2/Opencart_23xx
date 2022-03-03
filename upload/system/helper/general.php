<?php
function token($length = 32) {
	if (!isset($length) || intval($length) <= 8) {
		$length = 32;
	}

	$token = bin2hex(random_bytes(($length + 1) / 2));

	return substr($token, 0, $length);
}

/**
 * Backwards support for timing safe hash string comparisons
 *
 * http://php.net/manual/en/function.hash-equals.php
 */

if (!function_exists('hash_equals')) {
	function hash_equals($known_string, $user_string) {
		$known_string = (string)$known_string;
		$user_string = (string)$user_string;

		if (strlen($known_string) != strlen($user_string)) {
			return false;
		} else {
			$res = $known_string ^ $user_string;
			$ret = 0;

			for ($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);

			return !$ret;
		}
	}
}