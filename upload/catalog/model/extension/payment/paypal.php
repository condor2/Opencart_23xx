<?php
class ModelExtensionPaymentPayPal extends Model {
	
	public function getMethod($address, $total) {
		$method_data = array();
		
		$agree_status = $this->getAgreeStatus();
		
		if ($this->config->get('paypal_status') && $this->config->get('paypal_client_id') && $this->config->get('paypal_secret') && $agree_status) {
			$this->load->language('extension/payment/paypal');

			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('paypal_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

			if (($this->config->get('paypal_total') > 0) && ($this->config->get('paypal_total') > $total)) {
				$status = false;
			} elseif (!$this->config->get('paypal_geo_zone_id')) {
				$status = true;
			} elseif ($query->num_rows) {
				$status = true;
			} else {
				$status = false;
			}

			if ($status) {			
				$method_data = array(
					'code'       => 'paypal',
					'title'      => $this->language->get('text_paypal_title'),
					'terms'      => '',
					'sort_order' => $this->config->get('paypal_sort_order')
				);
			}
		}

		return $method_data;
	}
	
	public function hasProductInCart($product_id, $option = array(), $recurring_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cart WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' AND product_id = '" . (int)$product_id . "' AND recurring_id = '" . (int)$recurring_id . "' AND `option` = '" . $this->db->escape(json_encode($option)) . "'");
				
		return $query->row['total'];
	}
	
	public function getCountryByCode($code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE iso_code_2 = '" . $this->db->escape($code) . "' AND status = '1'");
				
		return $query->row;
	}
	
	public function getZoneByCode($country_id, $code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$country_id . "' AND (code = '" . $this->db->escape($code) . "' OR name = '" . $this->db->escape($code) . "') AND status = '1'");
		
		return $query->row;
	}
	
	public function addOrder($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "paypal_checkout_integration_order` SET `order_id` = '" . (int)$data['order_id'] . "', `transaction_id` = '" . $this->db->escape($data['transaction_id']) . "', `transaction_status` = '" . $this->db->escape($data['transaction_status']) . "', `environment` = '" . $this->db->escape($data['environment']) . "'");
	}
		
	public function deleteOrder($order_id) {
		$query = $this->db->query("DELETE FROM `" . DB_PREFIX . "paypal_checkout_integration_order` WHERE `order_id` = '" . (int)$order_id . "'");
	}
	
	public function getOrder($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "paypal_checkout_integration_order` WHERE `order_id` = '" . (int)$order_id . "'");
		
		if ($query->num_rows) {
			return $query->row;
		} else {
			return array();
		}
	}
	
	public function getAgreeStatus() {
		$agree_status = true;
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE status = '1' AND (iso_code_2 = 'CU' OR iso_code_2 = 'IR' OR iso_code_2 = 'SY' OR iso_code_2 = 'KP')");
		
		if ($query->rows) {
			$agree_status = false;
		}
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE country_id = '220' AND status = '1' AND (`code` = '43' OR `code` = '14' OR `code` = '09')");
		
		if ($query->rows) {
			$agree_status = false;
		}
		
		return $agree_status;
	}
	
	public function log($data, $title = null) {
		// Setting
		$_config = new Config();
		$_config->load('paypal');
			
		$config_setting = $_config->get('paypal_setting');
		
		$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('paypal_setting'));
			
		if ($setting['general']['debug']) {
			$log = new Log('paypal.log');
			$log->write('PayPal debug (' . $title . '): ' . json_encode($data));
		}
	}
	
	public function update() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "paypal_checkout_integration_order`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "paypal_checkout_integration_order` (`order_id` INT(11) NOT NULL, `transaction_id` VARCHAR(20) NOT NULL, `transaction_status` VARCHAR(20) NOT NULL, `environment` VARCHAR(20) NOT NULL, PRIMARY KEY (`order_id`, `transaction_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		
		$this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `code` = 'paypal_order_info'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `code` = 'paypal_header'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `code` = 'paypal_extension_get_extensions'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `code` = 'paypal_order_delete_order'");
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = 'paypal_order_info', `trigger` = 'admin/view/sale/order_info/before', `action` = 'extension/payment/paypal/order_info_before', `status` = '1'");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = 'paypal_header', `trigger` = 'catalog/controller/common/header/before', `action` = 'extension/payment/paypal/header_before', `status` = '1'");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = 'paypal_extension_get_extensions', `trigger` = 'catalog/model/extension/extension/getExtensions/after', `action` = 'extension/payment/paypal/extension_get_extensions_after', `status` = '1'");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = 'paypal_order_delete_order', `trigger` = 'catalog/model/checkout/order/deleteOrder/before', `action` = 'extension/payment/paypal/order_delete_order_before', `status` = '1'");
				
		// Setting
		$_config = new Config();
		$_config->load('paypal');
			
		$config_setting = $_config->get('paypal_setting');
						
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '0' AND `code` = 'paypal_version'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'paypal_version', `key` = 'paypal_version', `value` = '" . $this->db->escape($config_setting['version']) . "'");
	}
}