<?php
class ControllerEventTheme extends Controller {
	public function index(string &$view, array &$data): void {
		// This is only here for compatibility with old templates
		if (substr($view, -3) == 'tpl') {
			$view = substr($view, 0, -3);
		}
	}
}
