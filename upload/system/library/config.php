<?php
/**
 * @package		OpenCart
 *
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2024, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 *
 * @see			https://www.opencart.com
 */

/**
 * Config class
 */
class Config {
	private $data = [];

	/**
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get($key) {
		return $this->data[$key] ?? null;
	}

	/**
	 * @param string $key
	 * @param string $value
	 */
	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function has($key) {
		return isset($this->data[$key]);
	}

	/**
	 * @param string $filename
	 */
	public function load($filename) {
		$file = DIR_CONFIG . $filename . '.php';

		if (file_exists($file)) {
			$_ = [];

			require($file);

			$this->data = array_merge($this->data, $_);
		} else {
			trigger_error('Error: Could not load config ' . $filename . '!');
			exit();
		}
	}
}
