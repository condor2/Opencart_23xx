<?php
class ControllerExtensionShippingUspsOauth extends Controller {
	private $error = [];

	public function index() {
		$this->load->language('extension/shipping/usps_oauth');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('usps_oauth', $this->request->post);

			// Updated Service List for USPS v3 Mail Classes
			$services = [
				'usps_ground_advantage',
				'priority_mail',
				'priority_mail_express',
				'media_mail',
				'library_mail'
			];

			foreach ($services as $service) {
				if (isset($this->request->post['usps_oauth_' . $service])) {
					$this->config->set('usps_oauth_' . $service, $this->request->post['usps_oauth_' . $service]);
				} else {
					$this->config->set('usps_oauth_' . $service, 0);
				}
			}

			if (isset($this->request->post['usps_oauth_sort_by'])) {
				$this->config->set('usps_oauth_sort_by', $this->request->post['usps_oauth_sort_by']);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true));
		}

		// language variables for the template
		$data['text_services'] = $this->language->get('text_services');
		$data['text_usps_ground_advantage'] = $this->language->get('text_usps_ground_advantage');
		$data['text_priority_mail'] = $this->language->get('text_priority_mail');
		$data['text_priority_mail_express'] = $this->language->get('text_priority_mail_express');
		$data['text_media_mail'] = $this->language->get('text_media_mail');
		$data['text_retail'] = $this->language->get('text_retail');
		$data['text_commercial'] = $this->language->get('text_commercial');

		// Heading and Text
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		// Entries
		$data['entry_client_id'] = $this->language->get('entry_client_id');
		$data['entry_client_secret'] = $this->language->get('entry_client_secret');
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_price_type'] = $this->language->get('entry_price_type');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_debug_mode'] = $this->language->get('entry_debug_mode');

		// Buttons
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		// Error Handling
		$data['error_warning'] = $this->error['warning'] ?? '';
		$data['error_client_id'] = $this->error['client_id'] ?? '';
		$data['error_client_secret'] = $this->error['client_secret'] ?? '';

		// Breadcrumbs
		$data['breadcrumbs'] = [];
		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		];
		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_shipping'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true)
		];
		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/shipping/usps_oauth', 'token=' . $this->session->data['token'], true)
		];

		$data['action'] = $this->url->link('extension/shipping/usps_oauth', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true);

		// Pulling settings from config
		$fields = [
			'usps_oauth_client_id',
			'usps_oauth_client_secret',
			'usps_oauth_test',
			'usps_oauth_postcode',
			'usps_oauth_weight_class_id',
			'usps_oauth_handling_fee',
			'usps_oauth_price_type',
			'usps_oauth_geo_zone_id',
			'usps_oauth_status',
			'usps_oauth_sort_order',
			'usps_oauth_debug_mode',
			'usps_oauth_sort_by',
			'usps_oauth_usps_ground_advantage',
			'usps_oauth_priority_mail',
			'usps_oauth_priority_mail_express',
			'usps_oauth_media_mail'
		];

		foreach ($fields as $field) {
			if (isset($this->request->post[$field])) {
				$data[$field] = $this->request->post[$field];
			} else {
				$data[$field] = $this->config->get($field);
			}
		}

		$this->load->model('localisation/geo_zone');
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$this->load->model('localisation/weight_class');
		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/shipping/usps_oauth.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/usps_oauth')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['usps_oauth_client_id']) {
			$this->error['client_id'] = $this->language->get('error_client_id');
		}

		if (!$this->request->post['usps_oauth_client_secret']) {
			$this->error['client_secret'] = $this->language->get('error_client_secret');
		}

		return !$this->error;
	}
}
