<?php
class ModelLocalisationCountry extends Model {
	public function getCountry(int $country_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE `country_id` = '" . (int)$country_id . "' AND status = '1'");

		return $query->row;
	}

	public function getCountries(): array {
		$country_data = $this->cache->get('country.catalog');

		if (!$country_data) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE `status` = '1' ORDER BY name ASC");

			$country_data = $query->rows;

			$this->cache->set('country.catalog', $country_data);
		}

		return $country_data;
	}
}
