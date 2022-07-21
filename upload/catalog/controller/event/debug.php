<?php
class ControllerEventDebug extends Controller {
	public function before(string &$route, array &$data): void {
		if ($route == '') { // add the route you want to test
			$this->session->data['debug'][$route] = microtime();
		}
	}
	
	public function after(string &$route, array &$data, mixed &$output): void {
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