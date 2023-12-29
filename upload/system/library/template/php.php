<?php
namespace Template;
class PHP {
	private $data = [];

	public function set($key, $value): void {
		$this->data[$key] = $value;
	}

	public function render(string $template) {
		$file = DIR_TEMPLATE . $template;

		if (is_file($file)) {
			extract($this->data);

			ob_start();

			require($file);

			return ob_get_clean();
		}

		trigger_error('Error: Could not load template ' . $file . '!');
		exit();
	}
}
