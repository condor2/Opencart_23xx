<?php
class ControllerStartupSass extends Controller {
	public function index() {
		$file = DIR_APPLICATION . 'view/stylesheet/bootstrap.css';

		if (!is_file($file)) {
			$scss = new \ScssPhp\ScssPhp\Compiler();
			$scss->setImportPaths(DIR_APPLICATION . 'view/stylesheet/sass/');

			$output = $scss->compileString('@import "_bootstrap.scss"')->getCss();

			$handle = fopen($file, 'w');

			flock($handle, LOCK_EX);

			fwrite($handle, $output);

			fflush($handle);

			flock($handle, LOCK_UN);

			fclose($handle);
		}
	}
}