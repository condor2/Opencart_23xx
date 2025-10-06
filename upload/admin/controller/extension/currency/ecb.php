<?php
class ControllerExtensionCurrencyEcb extends Controller {
	private $error = array();

	public function index() {
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
		$data['text_information'] = $this->language->get('text_information');

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

		$data['breadcrumbs'] = array();

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
		$data['text_information'] = str_replace('%1', $this->url->link('localisation/currency', 'token=' . $this->session->data['token'], true), $data['text_information']);
		$data['text_information'] = str_replace('%2', $this->url->link('setting/store', 'token=' . $this->session->data['token'], true), $data['text_information']);

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
		$this->load->model('extension/currency/ecb');
		$this->model_extension_currency_ecb->refresh();

		return null;
	}
}
