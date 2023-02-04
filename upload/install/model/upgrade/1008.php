<?php
class ModelUpgrade1008 extends Model {
	public function upgrade() {
		//  Option
		$this->db->query("UPDATE `" . DB_PREFIX . "option` SET `type` = 'radio' WHERE `type` = 'image'");

		// Event
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "event' AND COLUMN_NAME = 'status'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "event` ADD `status` TINYINT(1) NOT NULL AFTER `action`");
		}

		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "event' AND COLUMN_NAME = 'date_added'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "event` ADD `date_added` DATETIME NOT NULL AFTER `status`");
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `type` = 'dashboard'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `type` = 'dashboard', `code` = 'activity'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `type` = 'dashboard', `code` = 'sale'"); 
			$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `type` = 'dashboard', `code` = 'recent'"); 
			$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `type` = 'dashboard', `code` = 'order'"); 
			$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `type` = 'dashboard', `code` = 'online'"); 
			$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `type` = 'dashboard', `code` = 'map'"); 
			$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `type` = 'dashboard', `code` = 'customer'"); 
			$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `type` = 'dashboard', `code` = 'chart'"); 

			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_activity', `key` = 'dashboard_activity_status', `value` = '1', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_activity', `key` = 'dashboard_activity_sort_order', `value` = '7', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_sale', `key` = 'dashboard_sale_status', `value` = '1', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_sale', `key` = 'dashboard_sale_width', `value` = '3', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_chart', `key` = 'dashboard_chart_status', `value` = '1', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_chart', `key` = 'dashboard_chart_width', `value` = '6', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_customer', `key` = 'dashboard_customer_status', `value` = '1', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_customer', `key` = 'dashboard_customer_width', `value` = '3', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_map', `key` = 'dashboard_map_status', `value` = '1', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_map', `key` = 'dashboard_map_width', `value` = '6', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_online', `key` = 'dashboard_online_status', `value` = '1', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_online', `key` = 'dashboard_online_width', `value` = '3', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_order', `key` = 'dashboard_order_sort_order', `value` = '1', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_order', `key` = 'dashboard_order_status', `value` = '1', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_order', `key` = 'dashboard_order_width', `value` = '3', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_sale', `key` = 'dashboard_sale_sort_order', `value` = '2', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_customer', `key` = 'dashboard_customer_sort_order', `value` = '3', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_online', `key` = 'dashboard_online_sort_order', `value` = '4', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_map', `key` = 'dashboard_map_sort_order', `value` = '5', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_chart', `key` = 'dashboard_chart_sort_order', `value` = '6', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_recent', `key` = 'dashboard_recent_status', `value` = '1', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_recent', `key` = 'dashboard_recent_sort_order', `value` = '8', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_activity', `key` = 'dashboard_activity_width', `value` = '4', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_recent', `key` = 'dashboard_recent_width', `value` = '8', `serialized` = '0'");
		}

		// Modification
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "modification' AND COLUMN_NAME = 'extension_install_id'");
		
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "modification` ADD `extension_install_id` INT(11) NOT NULL AFTER `modification_id`");
		}

		// Event
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "event` WHERE `action` = 'event/currency'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "event` (code, trigger, action, status, date_added) VALUES ('admin_currency_add', 'admin/model/localisation/currency/addCurrency/after', 'event/currency', 1, '2022-03-24 14:00:00');");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "event` (code, trigger, action, status, date_added) VALUES ('admin_currency_edit', 'admin/model/localisation/currency/editCurrency/after', 'event/currency', 1, '2022-03-24 14:00:00');");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "event` (code, trigger, action, status, date_added) VALUES ('admin_setting', 'admin/model/setting/setting/editSetting/after', 'event/currency', 1, '2022-03-24 14:00:00');");
		}

		// Setting - Time Zone
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_timezone'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (store_id, code, key, value, serialized) VALUES (0, 'config', 'config_timezone', 'UTC', 0);");
		}

		// Setting - ECB
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_currency_engine'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (store_id, code, key, value, serialized) VALUES (0, 'config', 'config_currency_engine', 'ecb', 0);");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (store_id, code, key, value, serialized) VALUES (0, 'ecb', 'ecb_status', '1', 0);");
		}

		// Update Affiliate `password` column Length
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "affiliate' AND COLUMN_NAME = 'affiliate_id'");

		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "affiliate` MODIFY `password` VARCHAR(255)");
		}

		// Update Customer `password` column Length
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "customer' AND COLUMN_NAME = 'customer_id'");

		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer` MODIFY `password` VARCHAR(255)");
		}

		// Update User `password` column Length
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "user' AND COLUMN_NAME = 'user_id'");

		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "user` MODIFY `password` VARCHAR(255)");
		}

		// Remove Affiliate `salt` column
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "affiliate' AND COLUMN_NAME = 'salt'");

		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "affiliate` DROP COLUMN `salt`");
		}

		// Remove Customer `salt` column
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "customer' AND COLUMN_NAME = 'salt'");

		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer` DROP COLUMN `salt`");
		}

		// Remove User `salt` column
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "user' AND COLUMN_NAME = 'salt'");

		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "user` DROP COLUMN `salt`");
		}
	}
}