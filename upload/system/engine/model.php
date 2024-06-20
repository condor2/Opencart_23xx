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
 * Model class
 *
 * @mixin Registry
 */
class Model {
	/**
	 * @var Registry
	 */
	protected $registry;

	/**
	 * Constructor
	 *
	 * @param Registry $registry
	 */
	public function __construct($registry) {
		$this->registry = $registry;
	}

	/**
	 * __get
	 *
	 * @param string $key
	 *
	 * @return object
	 */
	public function __get($key) {
		return $this->registry->get($key);
	}

	/**
	 * __set
	 *
	 * @param string $key
	 * @param string $value
	 *
	 * @return void
	 */
	public function __set(string $key, object $value): void {
		$this->registry->set($key, $value);
	}
}
