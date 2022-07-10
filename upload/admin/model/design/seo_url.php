<?php
class ModelDesignSeoUrl extends Model {
	public function addSeoUrl(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET query = '" . $this->db->escape($data['query']) . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
	}

	public function editSeoUrl(int $url_alias_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "url_alias` SET query = '" . $this->db->escape($data['query']) . "', keyword = '" . $this->db->escape($data['keyword']) . "' WHERE url_alias_id = '" . (int)$url_alias_id . "'");
	}

	public function deleteSeoUrl(int $url_alias_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE url_alias_id = '" . (int)$url_alias_id . "'");
	}
	
	public function getSeoUrl(int $url_alias_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE url_alias_id = '" . (int)$url_alias_id . "'");

		return $query->row;
	}

	public function getSeoUrls(array $data = array()): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "url_alias`";

		$implode = array();

		if (!empty($data['filter_query'])) {
			$implode[] = "`query` LIKE '" . $this->db->escape($data['filter_query']) . "'";
		}
		
		if (!empty($data['filter_keyword'])) {
			$implode[] = "`keyword` LIKE '" . $this->db->escape($data['filter_keyword']) . "'";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}	
		
		$sort_data = array(
			'query',
			'keyword',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY query";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalSeoUrls(array $data = array()): int {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "url_alias`";
		
		$implode = array();

		if (!empty($data['filter_query'])) {
			$implode[] = "query LIKE '" . $this->db->escape($data['filter_query']) . "'";
		}
		
		if (!empty($data['filter_keyword'])) {
			$implode[] = "keyword LIKE '%" . $this->db->escape($data['filter_keyword']) . "%'";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}		
		
		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}
	
	public function getSeoUrlsByKeyword(string $keyword): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE keyword = '" . $this->db->escape($keyword) . "'");

		return $query->rows;
	}	
	
	public function getSeoUrlsByQuery(string $query): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE query = '" . $this->db->escape($query) . "'");

		return $query->rows;
	}
	
	public function getSeoUrlsByQueryId(int $url_alias_id, string $query): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE query = '" . $this->db->escape($query) . "' AND url_alias_id != '" . (int)$url_alias_id . "'");

		return $query->rows;
	}	

	public function getSeoUrlsByKeywordId(int $url_alias_id, string $keyword): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE keyword = '" . $this->db->escape($keyword) . "' AND url_alias_id != '" . (int)$url_alias_id . "'");

		return $query->rows;
	}	
}