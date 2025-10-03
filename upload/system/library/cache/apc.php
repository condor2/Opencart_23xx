<?php
namespace Cache;
class APC {
	private $expire;
	private $active = false;

	public function __construct($expire) {
		$this->expire = $expire;
		$this->active = function_exists('apc_cache_info') && ini_get('apc.enabled');
	}

	public function get($key) {
		return $this->active ? apc_fetch(CACHE_PREFIX . $key) : false;
	}

	public function set($key, $value, $expire = '') {
		if (!$expire) {
			$expire = $this->expire;
		}

		return $this->active ? apc_store(CACHE_PREFIX . $key, $value, $expire) : false;
	}

	public function delete($key) {
		if (!$this->active) {
			return false;
		}

		$cache_info = apc_cache_info('user');

		$cache_list = $cache_info['cache_list'];

		foreach ($cache_list as $entry) {
			if (str_starts_with($entry['info'], CACHE_PREFIX . $key)) {
				apcu_delete($entry['info']);
			}
		}
	}
}
