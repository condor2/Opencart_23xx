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
 * Document class
 */
class Document {
	/**
	 * @var string
	 */
	private string $title = '';
	/**
	 * @var string
	 */
	private string $description = '';
	/**
	 * @var string
	 */
	private string $keywords = '';
	/**
	 * @var array
	 */
	private array $links = [];
	/**
	 * @var array
	 */
	private array $styles = [];
	/**
	 * @var array
	 */
	private array $scripts = [];

	/**
	 * setTitle
	 *
	 * @param string $title
	 *
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * getTitle
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * setDescription
	 *
	 * @param string $description
	 *
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * getDescription
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * setKeywords
	 *
	 * @param string $keywords
	 */
	public function setKeywords($keywords) {
		$this->keywords = $keywords;
	}

	/**
	 * getKeywords
	 *
	 * @return string
	 */
	public function getKeywords() {
		return $this->keywords;
	}

	/**
	 * addLink
	 *
	 * @param string $href
	 * @param string $rel
	 *
	 * @return void
	 */
	public function addLink($href, $rel) {
		$this->links[$href] = [
			'href' => $href,
			'rel'  => $rel
		];
	}

	/**
	 * getLinks
	 *
	 * @return array
	 */
	public function getLinks() {
		return $this->links;
	}

	/**
	 * addStyle
	 *
	 * @param string $href
	 * @param string $rel
	 * @param string $media
	 *
	 * @return void
	 */
	public function addStyle($href, $rel = 'stylesheet', $media = 'screen') {
		$this->styles[$href] = [
			'href'  => $href,
			'rel'   => $rel,
			'media' => $media
		];
	}

	/**
	 * getStyles
	 *
	 * @return array
	 */
	public function getStyles() {
		return $this->styles;
	}

	/**
	 * addScript
	 *
	 * @param string $href
	 * @param string $position
	 *
	 * @return void
	 */
	public function addScript($href, $position = 'header') {
		$this->scripts[$position][$href] = $href;
	}

	/**
	 * getScripts
	 *
	 * @param string $position
	 *
	 * @return array
	 */
	public function getScripts($position = 'header') {
		if (isset($this->scripts[$position])) {
			return $this->scripts[$position];
		} else {
			return [];
		}
	}
}
