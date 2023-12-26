<?php
namespace DB;
class MySQLi {
	private $connection;

	public function __construct($hostname, $username, $password, $database, $port = '', $ssl_key = '', $ssl_cert = '', $ssl_ca = '') {
		if (!$port) {
			$port = '3306';
		}

		// MYSQLI SSL connection
		$temp_ssl_key_file = '';

		if ($ssl_key) {
			$temp_ssl_key_file = tempnam(sys_get_temp_dir(), 'mysqli_key_');

			$handle = fopen($temp_ssl_key_file, 'w');

			fwrite($handle, $ssl_key);

			fclose($handle);
		}

		$temp_ssl_cert_file = '';

		if ($ssl_cert) {
			$temp_ssl_cert_file = tempnam(sys_get_temp_dir(), 'mysqli_cert_');

			$handle = fopen($temp_ssl_cert_file, 'w');

			fwrite($handle, $ssl_cert);

			fclose($handle);
		}

		$temp_ssl_ca_file = '';

		if ($ssl_ca) {
			$temp_ssl_ca_file = tempnam(sys_get_temp_dir(), 'mysqli_ca_');

			$handle = fopen($temp_ssl_ca_file, 'w');

			fwrite($handle, '-----BEGIN CERTIFICATE-----' . PHP_EOL . $ssl_ca . PHP_EOL . '-----END CERTIFICATE-----');

			fclose($handle);
		}

		try {
			$this->connection = mysqli_init();

			if ($temp_ssl_key_file || $temp_ssl_cert_file || $temp_ssl_ca_file) {
				$this->connection->ssl_set($temp_ssl_key_file, $temp_ssl_cert_file, $temp_ssl_ca_file, null, null);
				$this->connection->real_connect($hostname, $username, $password, $database, $port, null, MYSQLI_CLIENT_SSL);
			} else {
				$this->connection->real_connect($hostname, $username, $password, $database, $port, null);
			}

			$this->connection->set_charset('utf8');

			$this->query("SET SESSION sql_mode = 'NO_ZERO_IN_DATE,NO_ENGINE_SUBSTITUTION'");
			$this->query("SET FOREIGN_KEY_CHECKS = 0");

			// Sync PHP and DB time zones
			$this->query("SET `time_zone` = '" . $this->escape(date('P')) . "'");
		} catch (\mysqli_sql_exception $e) {
			throw new \Exception('Error: Could not make a database link using ' . $username . '@' . $hostname . '!<br>Message: ' . $e->getMessage());
		}
	}

	public function query($sql) {
		try {
			$query = $this->connection->query($sql);

			if ($query instanceof \mysqli_result) {
				$data = [];

				while ($row = $query->fetch_assoc()) {
					$data[] = $row;
				}

				$result = new \stdClass();
				$result->num_rows = $query->num_rows;
				$result->row = isset($data[0]) ? $data[0] : [];
				$result->rows = $data;

				$query->close();

				unset($data);

				return $result;
			} else {
				return true;
			}
		} catch (\mysqli_sql_exception $e) {
			throw new \Exception('Error: ' . $this->connection->error . '<br/>Error No: ' . $this->connection->errno . '<br/>' . $sql);
		}
	}

	public function escape($value) {
		return $this->connection->real_escape_string($value);
	}

	public function countAffected() {
		return $this->connection->affected_rows;
	}

	public function getLastId() {
		return $this->connection->insert_id;
	}

	public function isConnected() {
		if ($this->connection) {
			return $this->connection->ping();
		} else {
			return false;
		}
	}

	/**
	 * __destruct
	 *
	 * Closes the DB connection when this object is destroyed.
	 *
	 */
	public function __destruct() {
		if ($this->connection) {
			$this->connection->close();

			$this->connection = null;
		}
	}
}
