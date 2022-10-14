<?php
class ControllerEventDebug extends Controller {
	public function before(string &$route, array &$data) {
		if ($route == '') { // add the route you want to test
			$this->session->data['debug'][$route] = microtime();
		}
	}
	
	public function after(string &$route, array &$data, mixed &$output) {
		if ($route == '') { // add the route you want to test
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