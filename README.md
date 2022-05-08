# OpenCart

## Overview

OpenCart is a free open source ecommerce platform for online merchants. OpenCart provides a professional and reliable foundation from which to build a successful online store.

# Informations

## Added
- Bug fixes found on opencart forum and github
- Currency module from Master Branch - 3.1.0.0b
- Timezone from Master Branch - 3.1.0.0b
- Integrated Cron module from Master Branch - 3.1.0.0b
- Vendor folder for some payments
- Filter on Zone List - 4.0.0.0_b
- Filter on Country List - 4.0.0.0_b
- <a href="https://www.opencart.com/index.php?route=marketplace/extension/info&extension_id=18892">No FTP on Ocmod Installer</a>
- Admin SEO URL like in Opencart 3.0.x.x

## Updates
- Bootstrap 3.4.1
- jQuery 3.6.0
- Summernote v0.8.20

## Removed
- OpenBay
- Deprecated Klarna Payment
- FTP settings from admin

## Features ##
- Option to show and hide/reveal password. Code used from <a href="https://github.com/opencartbrasil/opencartbrasil">Opencart Brasil</a>

## Compatibility
- PHP 8.0 and above

## Language patch for non English

<b>Currency module & Timezone</b>

- Edit <b>admin/language/your_language/setting.php</b> and add this values:

$_['entry_timezone']               = 'Time Zone';\
$_['entry_currency_engine']        = 'Currency Rate Engine';

- Copy <b>currency.php</b> from <b>admin/language/en-gb/extension/extension</b> in the same location of your language.
- Copy <b>currency</b> folder from <b>admin/language/en-gb/extension/</b> in the same location of your language.

<b>Cron & SEO URL</b>

- Edit <b>admin/language/your_language/column_left.php</b> and add this values:

$_['text_cron']                      = 'Cron Jobs';
$_['text_seo_url']                   = 'SEO URL';

- Copy <b>cron.php from admin/language/en-gb/extension</b> in the same location of your language.

<b>Multilanguage Summernote</b>
- Edit <b>admin/language/your_language/xx-yy.php</b> and add this value:

$_['summernote']                    = 'xx-YY';

## Patching standard version of Opencart 2.3.0.2

<b>Cron Module</b>

- If you had standard Opencart 2.3.0.2 and you have replaced with this version then you need to create in Database "oc_cron" table from opencart.sql

CREATE TABLE `oc_cron` (<br>
  `cron_id` int(11) NOT NULL AUTO_INCREMENT,<br>
  `code` varchar(64) NOT NULL,<br>
  `cycle` varchar(12) NOT NULL,<br>
  `action` text NOT NULL,<br>
  `status` tinyint(1) NOT NULL,<br>
  `date_added` datetime NOT NULL,<br>
  `date_modified` datetime NOT NULL,<br>
  PRIMARY KEY (`cron_id`)<br>
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;<br>

Then insert CRON values

INSERT INTO `oc_cron` (`cron_id`, `code`, `cycle`, `action`, `status`, `date_added`, `date_modified`) VALUES<br>
(1, 'currency', 'day', 'cron/currency', 1, '2014-09-25 14:40:00', '2019-08-25 21:12:59');<br>

Change <b>oc_</b> with your database prefix


<b>Event database values</b>

INSERT INTO `oc_event` (`code`, `trigger`, `action`, `status`, `date_added`) VALUES<br>
('admin_currency_add', 'admin/model/localisation/currency/addCurrency/after', 'event/currency', 1, '2022-03-24 14:00:00');<br>
INSERT INTO `oc_event` (`code`, `trigger`, `action`, `status`, `date_added`) VALUES<br>
('admin_currency_edit', 'admin/model/localisation/currency/editCurrency/after', 'event/currency', 1, '2022-03-24 14:00:00');<br>
INSERT INTO `oc_event` (`code`, `trigger`, `action`, `status`, `date_added`) VALUES<br>
('admin_setting', 'admin/model/setting/setting/editSetting/after', 'event/currency', 1, '2022-03-24 14:00:00');<br>


<b>Admin config</b>

Replace old structure

// DIR<br>
define('DIR_APPLICATION', '/your_path/admin/');<br>
define('DIR_SYSTEM', '/your_path/system/');<br>
define('DIR_LANGUAGE', '/your_path/admin/language/');<br>
define('DIR_TEMPLATE', '/your_path/admin/view/template/');<br>
define('DIR_CONFIG', '/your_path/system/config/');<br>
define('DIR_IMAGE', '/your_path/image/');<br>
define('DIR_CACHE', '/your_path/system/storage/cache/');<br>
define('DIR_DOWNLOAD', '/your_path/system/storage/download/');<br>
define('DIR_LOGS', '/your_path/system/storage/logs/');<br>
define('DIR_MODIFICATION', '/your_path/system/storage/modification/');<br>
define('DIR_UPLOAD', '/your_path/system/storage/upload/');<br>
define('DIR_CATALOG', '/your_path/catalog/');<br>

With the new one

// DIR<br>
define('DIR_APPLICATION', '/your_path/admin/');<br>
define('DIR_SYSTEM', '/your_path/system/');<br>
define('DIR_IMAGE', '/your_path/image/');<br>
define('DIR_STORAGE', DIR_SYSTEM . 'storage/');<br>
define('DIR_CATALOG', '/your_path/catalog/');<br>
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');<br>
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/template/');<br>
define('DIR_CONFIG', DIR_SYSTEM . 'config/');<br>
define('DIR_CACHE', DIR_STORAGE . 'cache/');<br>
define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');<br>
define('DIR_LOGS', DIR_STORAGE . 'logs/');<br>
define('DIR_MODIFICATION', DIR_STORAGE . 'modification/');<br>
define('DIR_SESSION', DIR_STORAGE . 'session/');<br>
define('DIR_UPLOAD', DIR_STORAGE . 'upload/');<br>

<b>Catalog config</b>

Replace old structure

// DIR<br>
define('DIR_APPLICATION', '/your_path/catalog/');<br>
define('DIR_SYSTEM', '/your_path/system/');<br>
define('DIR_LANGUAGE', '/your_path/catalog/language/');<br>
define('DIR_TEMPLATE', '/your_path/catalog/view/theme/');<br>
define('DIR_CONFIG', '/your_path/system/config/');<br>
define('DIR_IMAGE', '/your_path/image/');<br>
define('DIR_CACHE', '/your_path/system/storage/cache/');<br>
define('DIR_DOWNLOAD', '/your_path/system/storage/download/');<br>
define('DIR_LOGS', '/your_path/system/storage/logs/');<br>
define('DIR_MODIFICATION', '/your_path/system/storage/modification/');<br>
define('DIR_UPLOAD', '/your_path/system/storage/upload/');<br>

With the new one

// DIR<br>
define('DIR_APPLICATION', '/your_path/catalog/');<br>
define('DIR_SYSTEM', '/your_path/system/');<br>
define('DIR_IMAGE', '/your_path/image/');<br>
define('DIR_STORAGE', DIR_SYSTEM . 'storage/');<br>
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');<br>
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/theme/');<br>
define('DIR_CONFIG', DIR_SYSTEM . 'config/');<br>
define('DIR_CACHE', DIR_STORAGE . 'cache/');<br>
define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');<br>
define('DIR_LOGS', DIR_STORAGE . 'logs/');<br>
define('DIR_MODIFICATION', DIR_STORAGE . 'modification/');<br>
define('DIR_SESSION', DIR_STORAGE . 'session/');<br>
define('DIR_UPLOAD', DIR_STORAGE . 'upload/');<br>

<b>Database</b>
 - Using phpMyAdmin run this SQL command to patch the database:
 
INSERT INTO `oc_setting` (`store_id`, `code`, `key`, `value`, `serialized`) VALUES<br>
(0, 'config', 'config_timezone', 'UTC', 0),<br>
(0, 'config', 'config_currency_engine', 'ecb', 0),<br>
(0, 'ecb', 'ecb_status', '1', 0);<br>