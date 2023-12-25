<?php
namespace Session;
class File extends \SessionHandler {
	#[\ReturnTypeWillChange]
	public function open($path, $name) {
		return true;
	}

	#[\ReturnTypeWillChange]
	public function close() {
		return true;
	}

	#[\ReturnTypeWillChange]
	public function read($session_id) {
		$file = session_save_path() . '/sess_' . $session_id;

		if (is_file($file)) {
			$handle = fopen($file, 'r');

			flock($handle, LOCK_SH);

			$data = fread($handle, filesize($file));

			flock($handle, LOCK_UN);

			fclose($handle);

			return $data;
		}

		return null;
	}

	#[\ReturnTypeWillChange]
	public function write($session_id, $data) {
		$file = session_save_path() . '/sess_' . $session_id;

		$handle = fopen($file, 'w');

		flock($handle, LOCK_EX);

		fwrite($handle, $data);

		fflush($handle);

		flock($handle, LOCK_UN);

		fclose($handle);

		return true;
    }

	#[\ReturnTypeWillChange]
	public function destroy($session_id) {
		$file = session_save_path() . '/sess_' . $session_id;

		if (is_file($file)) {
			unlink($file);
		}

		return true;
    }
}
