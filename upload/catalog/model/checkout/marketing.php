<?php
class ModelCheckoutMarketing extends Model {
	public function getMarketingByCode(string $code): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "marketing` WHERE `code` = '" . $this->db->escape($code) . "'");

		return $query->row;
	}
}
