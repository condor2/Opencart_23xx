<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2023, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
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
		return (isset($this->data[$key]) ? $this->data[$key] : null);
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
