<?php
class Language {
	private $default = 'en-gb';
	private $directory;
	private $data = [];

	public function __construct($directory = '') {
		$this->directory = $directory;
	}

	public function get(string $key) {
		return $this->data[$key] ?? $key;
	}

	public function set(string $key, string $value): void {
		$this->data[$key] = $value;
	}

	// Please dont use the below function i'm thinking getting rid of it.
	public function all(): array {
		return $this->data;
	}

	// Please dont use the below function i'm thinking getting rid of it.
	public function merge(&$data): void {
		array_merge($this->data, $data);
	}

	public function load(string $filename, &$data = []) {
		$_ = [];

		$file = DIR_LANGUAGE . 'english/' . $filename . '.php';

		// Compatibility code for old extension folders
		$old_file = DIR_LANGUAGE . 'english/' . str_replace('extension/', '', $filename) . '.php';

		if (is_file($file)) {
			require($file);
		} elseif (is_file($old_file)) {
			require($old_file);
		}

		$file = DIR_LANGUAGE . $this->default . '/' . $filename . '.php';

		// Compatibility code for old extension folders
		$old_file = DIR_LANGUAGE . $this->default . '/' . str_replace('extension/', '', $filename) . '.php';

		if (is_file($file)) {
			require($file);
		} elseif (is_file($old_file)) {
			require($old_file);
		}

		$file = DIR_LANGUAGE . $this->directory . '/' . $filename . '.php';

		// Compatibility code for old extension folders
		$old_file = DIR_LANGUAGE . $this->directory . '/' . str_replace('extension/', '', $filename) . '.php';

		if (is_file($file)) {
			require($file);
		} elseif (is_file($old_file)) {
			require($old_file);
		}

		$this->data = array_merge($this->data, $_);

		return $this->data;
	}
}
