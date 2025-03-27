<?php
class ModelExtensionCurrencyEcb extends Model {
	public function editValueByCode($code, $value) {
		$this->db->query("UPDATE `" . DB_PREFIX . "currency` SET `value` = '" . (float)$value . "', `date_modified` = NOW() WHERE `code` = '" . $this->db->escape((string)$code) . "'");

		$this->cache->delete('currency');
	}

	public function refresh() {
		if ($this->config->get('ecb_status')) {
			if ($this->config->get('config_currency_engine') == 'ecb') {
				$curl = curl_init();

				curl_setopt($curl, CURLOPT_URL, 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml');
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_HEADER, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
				curl_setopt($curl, CURLOPT_TIMEOUT, 30);

				$response = curl_exec($curl);

				$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

				curl_close($curl);

				if ($status == 200) {
					$dom = new \DOMDocument('1.0', 'UTF-8');
					$dom->loadXml($response);

					$cube = $dom->getElementsByTagName('Cube')->item(0);

					// Compile all the rates into an array
					$currencies = [];

					$currencies['EUR'] = 1.0000;

					foreach ($cube->getElementsByTagName('Cube') as $currency) {
						if ($currency->getAttribute('currency')) {
							$currencies[$currency->getAttribute('currency')] = $currency->getAttribute('rate');
						}
					}

					if (isset($currencies[$default])) {
						$value = $currencies[$default];
					} else {
						$value = $currencies['EUR'];
					}

					if (count($currencies) > 1) {
						$this->load->model('localisation/currency');

						$results = $this->model_localisation_currency->getCurrencies();

						foreach ($results as $result) {
							if (isset($currencies[$result['code']])) {
								$from = $currencies['EUR'];

								$to = $currencies[$result['code']];

								$this->model_extension_currency_ecb->editValueByCode($result['code'], 1 / ($value * ($from / $to)));
							}
						}
					}

					$this->model_extension_currency_ecb->editValueByCode($default, '1.00000');
				}

				return true;
			}
		}

		return false;
	}
}
