# OpenCart

## Overview

OpenCart is a free open source ecommerce platform for online merchants. OpenCart provides a professional and reliable foundation from which to build a successful online store.

# Informations

## Added
- Bug fixes found on opencart forum and github
- Currency module from Master Branch - 3.1.0.0b
- Timezone from Master Branch - 3.1.0.0b
- Vendor folder for some payments
- Filter on Zone List - 4.0.0.0_b
- Filter on Country List - 4.0.0.0_b
- <a href="https://www.opencart.com/index.php?route=marketplace/extension/info&extension_id=18892">No FTP on Ocmod Installer</a>
- Admin SEO URL like in Opencart 3.0.x.x

## Updates
- Bootstrap 3.4.1
- jQuery 3.6.1
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

<b>Multilanguage Summernote</b>
- Edit <b>admin/language/your_language/xx-yy.php</b> and add this value:

$_['summernote']                    = 'xx-YY';

## Patching standard version of Opencart 2.3.0.2

After you have replaced this version with your standard version run <b>your-store-url/install</b>

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