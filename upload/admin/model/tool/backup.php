<?php
class ModelToolBackup extends Model {
	public function getTables() {
		$table_data = [];

		$query = $this->db->query("SHOW TABLES FROM `" . DB_DATABASE . "`");

		foreach ($query->rows as $result) {
			$table = reset($result);
			if ($table && utf8_substr($table, 0, strlen(DB_PREFIX)) == DB_PREFIX) {
				$table_data[] = $table;
			}
		}

		return $table_data;
	}

	public function backup(array $tables) {
		$output = '';

		foreach ($tables as $table) {
			if (DB_PREFIX) {
				if (!str_contains($table, DB_PREFIX)) {
					$status = false;
				} else {
					$status = true;
				}
			} else {
				$status = true;
			}

			if ($status) {
				$output .= 'TRUNCATE TABLE `' . $table . '`;' . "\n\n";

				$query = $this->db->query("SELECT * FROM `" . $table . "`");

				foreach ($query->rows as $result) {
					$fields = '';

					foreach (array_keys($result) as $value) {
						$fields .= '`' . $value . '`, ';
					}

					$values = '';

					foreach (array_values($result) as $value) {
						if ($value !== null) {
							$value = str_replace(['\\', "\x00", "\n", "\r", "\x1a", '\'', '"'], ['\\\\', '\0', '\n', '\r', '\Z', '\\\'', '\"'], $value);
							$values .= '\'' . $value . '\', ';
						} else {
							$values .= 'NULL, ';
						}
					}

					$output .= 'INSERT INTO `' . $table . '` (' . preg_replace('/, $/', '', $fields) . ') VALUES (' . preg_replace('/, $/', '', $values) . ');' . "\n";
				}

				$output .= "\n\n";
			}
		}

		return $output;
	}
}
