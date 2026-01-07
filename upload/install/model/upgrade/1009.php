<?php
class ModelUpgrade1009 extends Model {
	public function upgrade(): void {
		$dir_storage = str_replace('\\', '/', realpath(DIR_STORAGE));
		$dir_current_storage = str_replace('\\', '/', realpath($this->getCurrentStorageDirectory()));
		$dir_vendor = $dir_storage . '/vendor';
		$dir_current_vendor = $dir_current_storage . '/vendor';

		// remove obsolete files and folders from vendor directory
		$obsoletes = array(
			'cardinity/cardinity-sdk-php/spec/Method/Void/',
			'cardinity/cardinity-sdk-php/src/Method/Void/',
			'cardinity/cardinity-sdk-php/tests/VoidTest.php',
			'cardinity/cardinity-sdk-php/.gitignore',
			'guzzlehttp/guzzle/build/',
			'guzzlehttp/guzzle/docs/',
			'guzzlehttp/guzzle/src/Event/',
			'guzzlehttp/guzzle/src/Exception/CouldNotRewindStreamException.php',
			'guzzlehttp/guzzle/src/Exception/ParseException.php',
			'guzzlehttp/guzzle/src/Exception/SeekException.php',
			'guzzlehttp/guzzle/src/Exception/StateException.php',
			'guzzlehttp/guzzle/src/Exception/XmlParseException.php',
			'guzzlehttp/guzzle/src/Message/',
			'guzzlehttp/guzzle/src/Post/',
			'guzzlehttp/guzzle/src/Subscriber/',
			'guzzlehttp/guzzle/src/BatchResults.php',
			'guzzlehttp/guzzle/src/Collection.php',
			'guzzlehttp/guzzle/src/HasDataTrait.php',
			'guzzlehttp/guzzle/src/Mimetypes.php',
			'guzzlehttp/guzzle/src/Query.php',
			'guzzlehttp/guzzle/src/QueryParser.php',
			'guzzlehttp/guzzle/src/RequestFsm.php',
			'guzzlehttp/guzzle/src/RingBridge.php',
			'guzzlehttp/guzzle/src/ToArrayInterface.php',
			'guzzlehttp/guzzle/src/Transaction.php',
			'guzzlehttp/guzzle/src/UriTemplate.php',
			'guzzlehttp/guzzle/src/Url.php',
			'guzzlehttp/guzzle/tests/',
			'guzzlehttp/guzzle/.travis.yml',
			'guzzlehttp/guzzle/.php_cs',
			'guzzlehttp/guzzle/Dockerfile',
			'guzzlehttp/log-subscriber/',
			'guzzlehttp/oauth-subscriber/tests/',
			'guzzlehttp/oauth-subscriber/.gitignore',
			'guzzlehttp/oauth-subscriber/.travis.yml',
			'guzzlehttp/oauth-subscriber/README.rst',
			'guzzlehttp/oauth-subscriber/phpunit.xml.dist',
			'guzzlehttp/promises/src/functions.php',
			'guzzlehttp/promises/src/functions_include.php',
			'guzzlehttp/psr7/.github/',
			'guzzlehttp/psr7/src/functions.php',
			'guzzlehttp/psr7/src/functions_include.php',
			'guzzlehttp/psr7/.php_cs.dist',
			'guzzlehttp/ringphp/',
			'guzzlehttp/streams/',
			'symfony/polyfill-intl-idn/',
			'symfony/polyfill-intl-normalizer/',
			'symfony/polyfill-php72/',
			'symfony/translation/',
			'symfony/validator/Constraints/Collection/',
			'symfony/validator/Constraints/False.php',
			'symfony/validator/Constraints/FalseValidator.php',
			'symfony/validator/Constraints/Null.php',
			'symfony/validator/Constraints/NullValidator.php',
			'symfony/validator/Constraints/True.php',
			'symfony/validator/Constraints/TrueValidator.php',
			'symfony/validator/Context/LegacyExecutionContext.php',
			'symfony/validator/Context/LegacyExecutionContextFactory.php',
			'symfony/validator/Mapping/Cache/',
			'symfony/validator/Mapping/BlackholeMetadataFactory.php',
			'symfony/validator/Mapping/ClassMetadataFactory.php',
			'symfony/validator/Mapping/ElementMetadata.php',
			'symfony/validator/Test/ForwardCompatTestTrait.php',
			'symfony/validator/Tests/',
			'symfony/validator/Util/LegacyTranslatorProxy.php',
			'symfony/validator/Validator/LegacyValidator.php',
			'symfony/validator/Violation/LegacyConstraintViolationBuilder.php',
			'symfony/validator/.gitignore',
			'symfony/validator/ClassBasedInterface.php',
			'symfony/validator/DefaultTranslator.php',
			'symfony/validator/ExecutionContext.php',
			'symfony/validator/ExecutionContextInterface.php',
			'symfony/validator/GlobalExecutionContextInterface.php',
			'symfony/validator/MetadataFactoryInterface.php',
			'symfony/validator/MetadataInterface.php',
			'symfony/validator/PropertyMetadataContainerInterface.php',
			'symfony/validator/PropertyMetadataInterface.php',
			'symfony/validator/ValidationVisitor.php',
			'symfony/validator/ValidationVisitorInterface.php',
			'symfony/validator/Validator.php',
			'symfony/validator/ValidatorBuilderInterface.php',
			'symfony/validator/ValidatorInterface.php',
			'symfony/validator/phpunit.xml.dist'
		);

		if ($dir_current_vendor != $dir_vendor) {
			$this->mergeDirectories($dir_vendor, $dir_current_vendor);
		}

		$this->deleteObsoletes($dir_vendor, $obsoletes);

		if ($dir_current_vendor != $dir_vendor) {
			$this->deleteObsoletes($dir_current_vendor, $obsoletes);
		}

		// clear modification folder
		$this->deleteEntries($dir_storage . '/modification/*/*');

		if ($dir_current_storage != $dir_storage) {
			$this->deleteEntries($dir_current_storage . '/modification/*/*');
		}

		$query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "session';");

		if (empty($query->row)) {
			$sql  = "CREATE TABLE `" . DB_PREFIX . "session` (";
			$sql .= "  `session_id` varchar(32) NOT NULL,";
			$sql .= "  `data` text NOT NULL,";
			$sql .= "  `expire` datetime NOT NULL,";
			$sql .= "  PRIMARY KEY (`session_id`)";
			$sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

			$this->db->query($sql);
		}

		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value`= CONCAT('INV-', YEAR(CURDATE()), '-00') WHERE `code`='config_invoice_prefix' AND `value`='INV-2013-00';");

		$this->db->query("UPDATE `" . DB_PREFIX . "zone` SET `name`='Abū Z̧aby', `code`='AZ' WHERE `name`='Abu Dhabi';");
		$this->db->query("UPDATE `" . DB_PREFIX . "zone` SET `name`='‘Ajmān' WHERE `name`='''Ajman';");
		$this->db->query("UPDATE `" . DB_PREFIX . "zone` SET `name`='Ash Shāriqah' WHERE `name`='Ash Shariqah';");
		$this->db->query("UPDATE `" . DB_PREFIX . "zone` SET `name`='Ra’s al Khaymah' WHERE `name`='R''as al Khaymah';");
		$this->db->query("UPDATE `" . DB_PREFIX . "country` SET `name`='North Macedonia' WHERE `name`='FYROM';");
		$this->db->query("UPDATE `" . DB_PREFIX . "country` SET `name`='Eswatini' WHERE `name`='Swaziland';");

		$dir_opencart = str_replace('\\', '/', realpath(DIR_OPENCART));
		$dir_excluded = $dir_opencart . '/install';

		// remove various obsolete extensions files
		$this->removeByName($dir_opencart, 'divido');
		$this->removeByNameFromDB('divido');
		$this->removeByName($dir_opencart, 'openbay');
		$this->removeByNameFromDB('openbay');
		$this->removeByName($dir_opencart, 'klarna_checkout');
		$this->removeByNameFromDB('klarna_checkout');
		$this->removeByName($dir_opencart, 'ups.php');
		$this->removeByName($dir_opencart, 'ups.tpl');
		$this->removeByNameFromDB('ups');
		$this->removeByName($dir_opencart, 'citylink');
		$this->removeByNameFromDB('citylink');

		// upgrade to character set to utf8mb4 and collation to utf8mb4_unicode_ci
		$this->upgradeCharacterSetAndCollation();
	}

	private function removeByName(string $dir, string $name): bool {
		if (!file_exists($dir)) {
			return true;
		}

		if (!is_dir($dir)) {
			$file = $dir;
			if (strpos($file, $name) === false) {
				return true;
			}

			return @unlink($file);
		}

		$items = @scandir($dir);
		if (!is_array($items)) {
			return true;
		}
		foreach ($items as $item) {
			if ($item == '.' || $item == '..') {
				continue;
			}
			if (!$this->removeByName($dir . '/' . $item, $name)) {
				return false;
			}
		}

		return (strpos($dir, $name) === false) ? true : @rmdir($dir);
	}

	private function deleteObsoletes(string $dir, array $obsoletes): void {
		foreach ($obsoletes as $obsolete) {
			$this->deleteEntry($dir . '/' . $obsolete);
		}
	}

	private function getCurrentStorageDirectory(): string {
		$current_dir_storage = '';
		if (is_file(DIR_OPENCART . 'config.php') && filesize(DIR_OPENCART . 'config.php') > 0) {
			$lines = file(DIR_OPENCART . 'config.php');
			foreach ($lines as $line) {
				if (strpos($line, "'DIR_STORAGE'") !== false) {
					$line = str_replace("'DIR_STORAGE'", "'CURRENT_DIR_STORAGE'", $line);
					eval($line);
					$current_dir_storage = CURRENT_DIR_STORAGE;
					break;
				}
			}
		}

		return ($current_dir_storage != '') ? $current_dir_storage : DIR_STORAGE;
	}

	private function mergeDirectories(string $source, string $target): bool {
		if (!is_dir($source)) {
			return false;
		}

		// Create the target directory if it doesn't exist
		if (!is_dir($target)) {
			if (!@mkdir($target, 0755, true)) {
				return false;
			}
		}

		// Open the source directory
		$dir = @opendir($source);
		if ($dir === false) {
			return false;
		}

		// Loop through the files and folders in the source
		while (($file = @readdir($dir)) !== false) {
			// Skip '.' and '..' entries
			if ($file === '.' || $file === '..') {
				continue;
			}

			$source_path = $source . '/' . $file;
			$target_path = $target . '/' . $file;

			// If the item is a directory, recurse
			if (is_dir($source_path)) {
				if (!$this->mergeDirectories($source_path, $target_path)) {
					@closedir($dir);

					return false;
				}
			} else {
				// Otherwise, copy the file
				if (!copy($source_path, $target_path)) {
					@closedir($dir);

					return false;
				}
			}
		}

		@closedir($dir);

		return true;
	}

	private function deleteEntries(string $dir): bool {
		$paths = glob($dir, 0);
		if ($paths === false) {
			return false;
		}

		foreach ($paths as $path) {
			$entry = str_replace('\\', '/', realpath($path));
			$this->deleteEntry($entry);
		}

		return true;
	}

	private function deleteEntry(string $entry): bool {
		if (!file_exists($entry)) {
			return true;
		}

		if (!is_dir($entry)) {
			return @unlink($entry);
		}

		$dir = $entry;
		$items = @scandir($dir);
		if (!is_array($items)) {
			return true;
		}
		foreach ($items as $item) {
			if ($item == '.' || $item == '..') {
				continue;
			}
			if (!$this->deleteEntry($dir . '/' . $item)) {
				return false;
			}
		}

		return @rmdir($dir);
	}

	private function removeByNameFromDB(string $name): void {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user_group`");
		foreach ($query->rows as $row) {
			try {
				$user_group_permission = json_decode($row['permission'], true);
			} catch (\Exception $e) {
				$user_group_permission = null;
			}
			$user_group_id = $row['user_group_id'];
			if (!empty($user_group_permission) && is_array($user_group_permission)) {
				$new_user_group_permission = $user_group_permission;
				foreach ($user_group_permission as $type => $permission) {
					if (($type == 'access') || ($type == 'modify')) {
						if (!empty($permission) && is_array($permission)) {
							foreach ($permission as $key => $val) {
								if (strpos($val, $name) === false) {
									continue;
								}
								unset($new_user_group_permission[$type][$key]);
							}
						}
					}
				}
				if (empty($new_user_group_permission['access']) && empty($new_user_group_permission['modify'])) {
					$this->db->query("UPDATE `" . DB_PREFIX . "user_group` SET `permission`='' WHERE user_group_id='" . (int)$user_group_id . "';");
				} else {
					$json_user_group_permission = json_encode($new_user_group_permission);
					$this->db->query("UPDATE `" . DB_PREFIX . "user_group` SET `permission`='" . $this->db->escape($json_user_group_permission) . "' WHERE user_group_id='" . (int)$user_group_id . "';");
				}
			}
		}
		$this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `code` LIKE '%" . $this->db->escape($name) . "%';");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code` LIKE '%" . $this->db->escape($name) . "%';");

		// remove some other obsolete core files
		$this->deleteEntry($dir_opencart . '/admin/view/javascript/jquery/jquery-2.1.1.min.js');
		$this->deleteEntry($dir_opencart . '/admin/view/javascript/jquery/jquery-2.1.1.min.map');
		$this->deleteEntry($dir_opencart . '/install/view/javascript/jquery/jquery-2.1.1.min.js');
		$this->deleteEntry($dir_opencart . '/install/view/javascript/jquery/jquery-2.1.1.min.map');
		$this->deleteEntry($dir_opencart . '/system/library/db/mpdo.php');
		$this->deleteEntry($dir_opencart . '/system/library/db/mssql.php');
		$this->deleteEntry($dir_opencart . '/system/library/db/mysql.php');
		$this->deleteEntry($dir_opencart . '/system/library/db/postgre.php');
		$this->deleteEntry($dir_opencart . '/system/library/template/twig.php');

	}

	private function endsWith( string $haystack, string $needle ): bool {
		if (strlen( $haystack ) < strlen( $needle )) {

			return false;
		}
		return (substr( $haystack, strlen($haystack)-strlen($needle), strlen($needle) ) == $needle);
	}

	private function startsWith( string $haystack, string $needle ): bool {
		if (strlen( $haystack ) < strlen( $needle )) {

			return false;
		}
		return (substr( $haystack, 0, strlen($needle) ) == $needle);
	}

	private function upgradeCharacterSetAndCollation(): void {
		// List of standard OpenCart DB tables
		$tables = array(
			DB_PREFIX . 'address',
			DB_PREFIX . 'affiliate',
			DB_PREFIX . 'affiliate_activity',
			DB_PREFIX . 'affiliate_login',
			DB_PREFIX . 'affiliate_transaction',
			DB_PREFIX . 'api',
			DB_PREFIX . 'api_ip',
			DB_PREFIX . 'api_session',
			DB_PREFIX . 'attribute',
			DB_PREFIX . 'attribute_description',
			DB_PREFIX . 'attribute_group',
			DB_PREFIX . 'attribute_group_description',
			DB_PREFIX . 'banner',
			DB_PREFIX . 'banner_image',
			DB_PREFIX . 'cart',
			DB_PREFIX . 'category',
			DB_PREFIX . 'category_description',
			DB_PREFIX . 'category_filter',
			DB_PREFIX . 'category_path',
			DB_PREFIX . 'category_to_layout',
			DB_PREFIX . 'category_to_store',
			DB_PREFIX . 'country',
			DB_PREFIX . 'coupon',
			DB_PREFIX . 'coupon_category',
			DB_PREFIX . 'coupon_history',
			DB_PREFIX . 'coupon_product',
			DB_PREFIX . 'currency',
			DB_PREFIX . 'customer',
			DB_PREFIX . 'customer_activity',
			DB_PREFIX . 'customer_group',
			DB_PREFIX . 'customer_group_description',
			DB_PREFIX . 'customer_history',
			DB_PREFIX . 'customer_ip',
			DB_PREFIX . 'customer_login',
			DB_PREFIX . 'customer_online',
			DB_PREFIX . 'customer_reward',
			DB_PREFIX . 'customer_search',
			DB_PREFIX . 'customer_transaction',
			DB_PREFIX . 'customer_wishlist',
			DB_PREFIX . 'custom_field',
			DB_PREFIX . 'custom_field_customer_group',
			DB_PREFIX . 'custom_field_description',
			DB_PREFIX . 'custom_field_value',
			DB_PREFIX . 'custom_field_value_description',
			DB_PREFIX . 'download',
			DB_PREFIX . 'download_description',
			DB_PREFIX . 'event',
			DB_PREFIX . 'extension',
			DB_PREFIX . 'filter',
			DB_PREFIX . 'filter_description',
			DB_PREFIX . 'filter_group',
			DB_PREFIX . 'filter_group_description',
			DB_PREFIX . 'geo_zone',
			DB_PREFIX . 'information',
			DB_PREFIX . 'information_description',
			DB_PREFIX . 'information_to_layout',
			DB_PREFIX . 'information_to_store',
			DB_PREFIX . 'language',
			DB_PREFIX . 'layout',
			DB_PREFIX . 'layout_module',
			DB_PREFIX . 'layout_route',
			DB_PREFIX . 'length_class',
			DB_PREFIX . 'length_class_description',
			DB_PREFIX . 'location',
			DB_PREFIX . 'manufacturer',
			DB_PREFIX . 'manufacturer_to_store',
			DB_PREFIX . 'marketing',
			DB_PREFIX . 'menu',
			DB_PREFIX . 'modification',
			DB_PREFIX . 'module',
			DB_PREFIX . 'option',
			DB_PREFIX . 'option_description',
			DB_PREFIX . 'option_value',
			DB_PREFIX . 'option_value_description',
			DB_PREFIX . 'order',
			DB_PREFIX . 'order_history',
			DB_PREFIX . 'order_option',
			DB_PREFIX . 'order_product',
			DB_PREFIX . 'order_recurring',
			DB_PREFIX . 'order_recurring_transaction',
			DB_PREFIX . 'order_status',
			DB_PREFIX . 'order_total',
			DB_PREFIX . 'order_voucher',
			DB_PREFIX . 'product',
			DB_PREFIX . 'product_attribute',
			DB_PREFIX . 'product_description',
			DB_PREFIX . 'product_discount',
			DB_PREFIX . 'product_filter',
			DB_PREFIX . 'product_image',
			DB_PREFIX . 'product_option',
			DB_PREFIX . 'product_option_value',
			DB_PREFIX . 'product_recurring',
			DB_PREFIX . 'product_related',
			DB_PREFIX . 'product_reward',
			DB_PREFIX . 'product_special',
			DB_PREFIX . 'product_to_category',
			DB_PREFIX . 'product_to_download',
			DB_PREFIX . 'product_to_layout',
			DB_PREFIX . 'product_to_store',
			DB_PREFIX . 'recurring',
			DB_PREFIX . 'recurring_description',
			DB_PREFIX . 'return',
			DB_PREFIX . 'return_action',
			DB_PREFIX . 'return_history',
			DB_PREFIX . 'return_reason',
			DB_PREFIX . 'return_status',
			DB_PREFIX . 'review',
			DB_PREFIX . 'session',
			DB_PREFIX . 'setting',
			DB_PREFIX . 'stock_status',
			DB_PREFIX . 'store',
			DB_PREFIX . 'tax_class',
			DB_PREFIX . 'tax_rate',
			DB_PREFIX . 'tax_rate_to_customer_group',
			DB_PREFIX . 'tax_rule',
			DB_PREFIX . 'upload',
			DB_PREFIX . 'url_alias',
			DB_PREFIX . 'user',
			DB_PREFIX . 'user_group',
			DB_PREFIX . 'voucher',
			DB_PREFIX . 'voucher_history',
			DB_PREFIX . 'voucher_theme',
			DB_PREFIX . 'voucher_theme_description',
			DB_PREFIX . 'weight_class',
			DB_PREFIX . 'weight_class_description',
			DB_PREFIX . 'zone',
			DB_PREFIX . 'zone_to_geo_zone'
		);

		// Change the default character set and collation for the database
		$this->db->query("ALTER DATABASE `" . DB_DATABASE . "` CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;");

		// Get the old collations from existing DB tables
		$query = $this->db->query("SHOW TABLE STATUS FROM `" . DB_DATABASE . "` WHERE `Name` LIKE '" . DB_PREFIX . "%';");

		$old_collations = array();

		foreach ($query->rows as $row) {
			$old_collations[$row['Name']] = $row['Collation'];
		}

		// Convert standard OpenCart DB tables to new character set and collation
		foreach ($tables as $table) {
			if (array_key_exists($table, $old_collations)) {

				$old_collation = $old_collations[$table];

				if (!$this->startsWith($old_collation, 'utf8mb4_')) {
					// convert table to character set 'utf8mb4' and collation 'utf8mb4_unicode_ci'
					$this->db->query("ALTER TABLE `{$table}` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
				}
			}
		}
	}
}
