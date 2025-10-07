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
 * Request class
 */
class Request {
	public $get = [];
	public $post = [];
	public $request = [];
	public $cookie = [];
	public $files = [];
	public $server = [];

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->get = $this->clean($_GET);
		$this->post = $this->clean($_POST);
		$this->request = $this->clean($_REQUEST);
		$this->cookie = $this->clean($_COOKIE);
		$this->files = $this->clean($_FILES);
		$this->server = $this->clean($_SERVER);
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function clean($data) {
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				unset($data[$key]);

				$data[$this->clean($key)] = $this->clean($value);
			}
		} else {
			$data = isset($data) ? trim(htmlspecialchars($data, ENT_COMPAT, 'UTF-8')) : '';
		}

		return $data;
	}
}
