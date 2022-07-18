<?php
class ModelSettingApi extends Model {
	public function login(string $username, string $password): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "api` WHERE `username` = '" . $this->db->escape($username) . "' AND `password` = '" . $this->db->escape($password) . "'");

		return $query->row;
	}
}