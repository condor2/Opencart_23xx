<?php
class ControllerExtensionCurrencyEcb extends Controller {
	private $error = [];

	public function index(): void {
		$this->load->language('extension/currency/ecb');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('ecb', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=currency', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_support'] = $this->language->get('text_support');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_ip'] = $this->language->get('entry_ip');
		$data['entry_cron'] = $this->language->get('entry_cron');

		$data['button_currency'] = $this->language->get('button_currency');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['help_ip'] = $this->language->get('help_ip');
		$data['help_cron'] = $this->language->get('help_cron');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['ip'])) {
			$data['error_ip'] = $this->error['ip'];
		} else {
			$data['error_ip'] = '';
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=currency', true)
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/currency/ecb', 'token=' . $this->session->data['token'], true)
		];

		$data['action'] = $this->url->link('extension/currency/ecb', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=currency', true);
		$data['refresh'] = $this->url->link('localisation/currency', 'token=' . $this->session->data['token'], true);

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_edit'] = str_replace('%1', $this->url->link('localisation/currency', 'token=' . $this->session->data['token'], true), $data['text_edit']);
		$data['text_edit'] = str_replace('%2', $this->url->link('setting/store', 'token=' . $this->session->data['token'], true), $data['text_edit']);

		$data['ecb_cron'] = 'curl -s &quot;' . HTTPS_CATALOG . 'index.php?route=extension/currency/ecb/refresh&quot;';

		if (isset($this->request->post['ecb_ip'])) {
			$data['ecb_ip'] = $this->request->post['ecb_ip'];
		} else {
			$data['ecb_ip'] = (string)$this->config->get('ecb_ip');
		}

		if (isset($this->request->post['ecb_status'])) {
			$data['ecb_status'] = $this->request->post['ecb_status'];
		} else {
			$data['ecb_status'] = $this->config->get('ecb_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/currency/ecb', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/currency/ecb')) {
			$this->error['warning'] = $this->language->get('error_permission');
		} else {
			if (!empty($this->request->post['ecb_status'])) {
				$this->load->model('localisation/currency');
				$euro_currency = $this->model_localisation_currency->getCurrencyByCode('EUR');
				if (empty($euro_currency)) {
					$this->error['warning'] = $this->language->get('error_euro');
				}
			}
			if (!empty($this->request->post['ecb_ip'])) {
				if (!filter_var($this->request->post['ecb_ip'], FILTER_VALIDATE_IP)) {
					$this->error['ip'] = $this->language->get('error_ip');
				}
			}
		}

		return !$this->error;
	}

	public function currency() {
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

				curl_close($curl);

				if ($response) {
					$dom = new \DOMDocument('1.0', 'UTF-8');
					$dom->loadXml($response);

					$cube = $dom->getElementsByTagName('Cube')->item(0);

					$currencies = [];

					$currencies['EUR'] = 1.0000;

					foreach ($cube->getElementsByTagName('Cube') as $currency) {
						if ($currency->getAttribute('currency')) {
							$currencies[$currency->getAttribute('currency')] = $currency->getAttribute('rate');
						}
					}

					if (count($currencies) > 1) {
						$this->load->model('localisation/currency');
						$this->load->model('extension/currency/ecb');

						$default = $this->config->get('config_currency');

						$results = $this->model_localisation_currency->getCurrencies();

						foreach ($results as $result) {
							if (isset($currencies[$result['code']])) {
								$from = $currencies['EUR'];

								$to = $currencies[$result['code']];

								$this->model_extension_currency_ecb->editValueByCode($result['code'], 1 / ($currencies[$default] * ($from / $to)));
							}
						}
					}

					$default = $this->config->get('config_currency');

					$this->model_extension_currency_ecb->editValueByCode($default, '1.00000');

					$this->cache->delete('currency');
				}

				return true;
			}
		}

		return null;
	}
}
