<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2024, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @see			https://www.opencart.com
*/

/**
 * Registry class
 */
class Registry {
	private $data = [];

	/**
	 * Get
	 *
	 * @param string $key
	 *
	 * @return ?object
	 */
	public function get(string $key): ?object {
		return $this->data[$key] ?? null;
	}

	/**
	 * Set
	 *
	 * @param string $key
	 * @param object $value
	 *
	 * @return void
	 */
	public function set(string $key, object $value): void {
		$this->data[$key] = $value;
	}

	/**
	 * Has
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function has(string $key): bool {
		return isset($this->data[$key]);
	}
}
