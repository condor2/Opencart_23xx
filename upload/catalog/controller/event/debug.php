<?php
class ControllerEventDebug extends Controller {
	public function before(&$route, &$data) {
		if ($route == '') {
			// Add the route you want to test
			$this->session->data['debug'][$route] = microtime();
		}
	}

	public function after(&$route, &$data, &$output) {
		if ($route == '') {
			// Add the route you want to test
			if (isset($this->session->data['debug'][$route])) {
				$data = array(
					'route' => $route,
					'time'  => microtime() - $this->session->data['debug'][$route]
				);

				$this->log->write($data);
			}
		}
	}
}
