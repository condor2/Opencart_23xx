<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2023, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* URL class
*/
class Url {
	private $url;
	private $ssl;
	private $rewrite = [];

	/**
	 * Constructor
	 *
	 * @param	string	$url
	 * @param	string	$ssl
	 *
	 */
	public function __construct($url, $ssl = '') {
		$this->url = $url;
		$this->ssl = $ssl;
	}

	/**
	 * addRewrite
	 *
	 * Add a rewrite method to the URL system
	 *
	 * @param object $rewrite
	 *
	 * @return void
	 */
	public function addRewrite($rewrite): void {
		$this->rewrite[] = $rewrite;
	}

	/**
	 * Link
	 *
	 * Generates a URL
	 *
	 * @param string $route
	 * @param mixed  $args
	 * @param bool   $secure
	 *
	 * @return string
	 */
	public function link(string $route, $args = '', bool $secure = false): string {
		if ($this->ssl && $secure) {
			$url = $this->ssl . 'index.php?route=' . $route;
		} else {
			$url = $this->url . 'index.php?route=' . $route;
		}

		if ($args) {
			if (is_array($args)) {
				$url .= '&amp;' . http_build_query($args);
			} else {
				$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
			}
		}

		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}

		return $url; 
	}
}
