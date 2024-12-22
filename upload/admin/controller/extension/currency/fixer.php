<?php
class ControllerExtensionCurrencyFixer extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/currency/fixer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('fixer', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=currency', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_api'] = $this->language->get('entry_api');
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

		if (isset($this->error['api'])) {
			$data['error_api'] = $this->error['api'];
		} else {
			$data['error_api'] = '';
		}

		if (isset($this->error['ip'])) {
			$data['error_ip'] = $this->error['ip'];
		} else {
			$data['error_ip'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=currency', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/currency/fixer', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/currency/fixer', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=currency', true);
		$data['refresh'] = $this->url->link('localisation/currency', 'token=' . $this->session->data['token'], true);

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_edit'] = str_replace('%1', $this->url->link('localisation/currency', 'token=' . $this->session->data['token'], true), $data['text_edit']);
		$data['text_edit'] = str_replace('%2', $this->url->link('setting/store', 'token=' . $this->session->data['token'], true), $data['text_edit']);

		$data['fixer_cron'] = 'curl -s &quot;' . HTTPS_CATALOG . 'index.php?route=extension/currency/fixer/refresh&quot;';

		if (isset($this->request->post['fixer_api'])) {
			$data['fixer_api'] = $this->request->post['fixer_api'];
		} else {
			$data['fixer_api'] = (string)$this->config->get('fixer_api');
		}

		if (isset($this->request->post['fixer_ip'])) {
			$data['fixer_ip'] = $this->request->post['fixer_ip'];
		} else {
			$data['fixer_ip'] = (string)$this->config->get('fixer_ip');
		}

		if (isset($this->request->post['fixer_status'])) {
			$data['fixer_status'] = $this->request->post['fixer_status'];
		} else {
			$data['fixer_status'] = $this->config->get('fixer_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/currency/fixer', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/currency/fixer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		} else {
			if (empty($this->request->post['fixer_api'])) {
				$this->error['api'] = $this->language->get('error_api');
			}
			if (!empty($this->request->post['fixer_ip'])) {
				if (!filter_var($this->request->post['fixer_ip'], FILTER_VALIDATE_IP)) {
					$this->error['ip'] = $this->language->get('error_ip');
				}
			}
		}

		return !$this->error;
	}

	public function install() {}

	public function uninstall() {}

	public function currency() {
		$this->load->model('extension/currency/fixer');

		$this->model_extension_currency_fixer->refresh();

		return null;
	}
}
