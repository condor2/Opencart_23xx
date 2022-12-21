<?php
class ModelDesignSeoUrl extends Model {
	public function addSeoUrl($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET query = '" . $this->db->escape($data['query']) . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
	}

	public function editSeoUrl($url_alias_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "url_alias` SET query = '" . $this->db->escape($data['query']) . "', keyword = '" . $this->db->escape($data['keyword']) . "' WHERE url_alias_id = '" . (int)$url_alias_id . "'");
	}

	public function deleteSeoUrl($url_alias_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE url_alias_id = '" . (int)$url_alias_id . "'");
	}
	
	public function getSeoUrl($url_alias_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE url_alias_id = '" . (int)$url_alias_id . "'");

		return $query->row;
	}

	public function getSeoUrls($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "url_alias`";

		$implode = array();

		if (!empty($data['filter_query'])) {
			$implode[] = "`query` LIKE '" . $this->db->escape((string)$data['filter_query']) . "'";
		}

		if (!empty($data['filter_keyword'])) {
			$implode[] = "`keyword` LIKE '%" . $this->db->escape((string)$data['filter_keyword']) . "%'";
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

	public function getTotalSeoUrls($data = array()) {
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
	
	public function getSeoUrlsByKeyword($keyword) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE keyword = '" . $this->db->escape($keyword) . "'");

		return $query->rows;
	}	
	
	public function getSeoUrlsByQuery($query) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE query = '" . $this->db->escape($query) . "'");

		return $query->rows;
	}
	
	public function getSeoUrlsByQueryId($url_alias_id, $query) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE query = '" . $this->db->escape($query) . "' AND url_alias_id != '" . (int)$url_alias_id . "'");

		return $query->rows;
	}	

	public function getSeoUrlsByKeywordId($url_alias_id, $keyword) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE keyword = '" . $this->db->escape($keyword) . "' AND url_alias_id != '" . (int)$url_alias_id . "'");

		return $query->rows;
	}	
}