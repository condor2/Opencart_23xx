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
 * Response class
 */
class Response {
	private $headers = [];
	private $level = 0;
	private $output;

	/**
	 * Constructor
	 *
	 * @param string $header
	 */
	public function addHeader($header) {
		$this->headers[] = $header;
	}

	/**
	 * Redirect
	 *
	 * @param string $url
	 * @param int    $status
	 *
	 * @return void
	 */
	public function redirect($url, $status = 302) {
		header('Location: ' . str_replace(['&amp;', "\n", "\r"], ['&', '', ''], $url), true, $status);
		exit();
	}

	/**
	 * setCompression
	 *
	 * @param int $level
	 *
	 * @return void
	 */
	public function setCompression($level) {
		$this->level = $level;
	}

	/**
	 * getOutput
	 *
	 * @return string
	 */
	public function getOutput() {
		return $this->output;
	}

	/**
	 * setOutput
	 *
	 * @param string $output
	 *
	 * @return void
	 */
	public function setOutput($output) {
		$this->output = $output;
	}

	/**
	 * Compress
	 *
	 * @param string $data
	 * @param int    $level
	 *
	 * @return string
	 */
	private function compress($data, $level = 0) {
		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (str_contains($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))) {
			$encoding = 'gzip';
		}

		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (str_contains($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip'))) {
			$encoding = 'x-gzip';
		}

		if (!isset($encoding) || ($level < -1 || $level > 9)) {
			return $data;
		}

		if (!extension_loaded('zlib') || ini_get('zlib.output_compression')) {
			return $data;
		}

		if (headers_sent()) {
			return $data;
		}

		if (connection_status()) {
			return $data;
		}

		$this->addHeader('Content-Encoding: ' . $encoding);

		return gzencode($data, (int)$level);
	}

	/**
	 * Output
	 *
	 * Displays the set HTML output
	 *
	 * @return void
	 */
	public function output() {
		if ($this->output) {
			$output = $this->level ? $this->compress($this->output, $this->level) : $this->output;

			if (!headers_sent()) {
				foreach ($this->headers as $header) {
					header($header, true);
				}
			}

			echo $output;
		}
	}
}
