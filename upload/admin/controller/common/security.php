<?php
class ControllerCommonSecurity extends Controller {
	public function index() {
		$this->load->language('common/security');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_instruction'] = $this->language->get('text_instruction');
		$data['text_security'] = $this->language->get('text_security');
		$data['text_choose'] = $this->language->get('text_choose');
		$data['text_automatic'] = $this->language->get('text_automatic');
		$data['text_manual'] = $this->language->get('text_manual');
		$data['text_move'] = $this->language->get('text_move');
		$data['text_to'] = $this->language->get('text_to');
		$data['text_config'] = $this->language->get('text_config');
		$data['text_admin'] = $this->language->get('text_admin');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['entry_directory'] = $this->language->get('entry_directory');

		$data['path'] = $this->language->get('path');

		$data['button_move'] = $this->language->get('button_move');

		$data['token'] = $this->session->data['token'];

		$data['storage'] = DIR_SYSTEM . 'storage/';

		$path = '';

		$data['paths'] = array();

		$parts = explode('/', str_replace('\\', '/', rtrim(DIR_SYSTEM, '/')));

		foreach ($parts as $part) {
			$path .= $part . '/';

			$data['paths'][] = $path;
		}

		rsort($data['paths']);

		$data['document_root'] = str_replace('\\', '/', realpath($this->request->server['DOCUMENT_ROOT'] . '/../') . '/');

		return $this->load->view('common/security', $data);
	}

	public function move() {
		$this->load->language('common/security');

		$json = array();

		if ($this->request->post['path']) {
			$path = $this->request->post['path'];
		} else {
			$path = '';
		}

		if ($this->request->post['directory']) {
			$directory = $this->request->post['directory'];
		} else {
			$directory = '';
		}

		if (!$this->user->hasPermission('modify', 'common/security')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (DIR_STORAGE != DIR_SYSTEM . 'storage/') {
				$data['error'] = $this->language->get('error_path');
			}

			if (!$path || str_replace('\\', '/', realpath($path)) . '/' != str_replace('\\', '/', substr(DIR_SYSTEM, 0, strlen($path)))) {
				$json['error'] = $this->language->get('error_path');
			}

			if (!$directory || !preg_match('/^[a-zA-Z0-9_-]+$/', $directory)) {
				$json['error'] = $this->language->get('error_directory');
			}

			if (is_dir($path . $directory)) {
				$json['error'] = $this->language->get('error_exists');
			}

			if (!is_writable(realpath(DIR_APPLICATION . '/../') . '/config.php') || !is_writable(DIR_APPLICATION . 'config.php')) {
				$json['error'] = $this->language->get('error_writable');
			}

			if (!$json) {
				$files = array();

				// Make path into an array
				$source = [DIR_SYSTEM . 'storage/'];

				// While the path array is still populated keep looping through
				while (count($source) != 0) {
					$next = array_shift($source);

					foreach (glob($next) as $file) {
						// If directory add to path array
						if (is_dir($file)) {
							$source[] = $file . '/*';
						}

						// Add the file to the files to be deleted array
						$files[] = $file;
					}
				}

				// Create the new storage folder
				if (!is_dir($path . $directory)) {
					mkdir($path . $directory, 0777);
				}

				// Copy the
				foreach ($files as $file) {
					$destination = $path . $directory . substr($file, strlen(DIR_SYSTEM . 'storage/'));

					if (is_dir($file) && !is_dir($destination)) {
						mkdir($destination, 0777);
					}

					if (is_file($file)) {
						copy($file, $destination);
					}
				}

				// Modify the config files
				$files = [
					DIR_APPLICATION . 'config.php',
					realpath(DIR_APPLICATION . '/../') . '/config.php'
				];

				foreach ($files as $file) {
					$output = '';

					$lines = file($file);

					foreach ($lines as $line_id => $line) {
						if (str_contains($line, 'define(\'DIR_STORAGE')) {
							$output .= 'define(\'DIR_STORAGE\', \'' . $path . $directory . '/\');' . "\n";
						} else {
							$output .= $line;
						}
					}

					$file = fopen($file, 'w');

					fwrite($file, $output);

					fclose($file);
				}

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
