<?php
class ControllerExtensionPaymentOpayo extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/opayo');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('opayo', $this->request->post);

			$this->session->data['success'] = $this->language->get('success_save');
			
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['vendor'])) {
			$data['error_vendor'] = $this->error['vendor'];
		} else {
			$data['error_vendor'] = '';
		}
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_tab_general'] = $this->language->get('text_tab_general');
		$data['text_tab_cron'] = $this->language->get('text_tab_cron');
		
		$data['text_test'] = $this->language->get('text_test');
		$data['text_live'] = $this->language->get('text_live');
		$data['text_defered'] = $this->language->get('text_defered');
		$data['text_authenticate'] = $this->language->get('text_authenticate');
		$data['text_payment'] = $this->language->get('text_payment');
				
		$data['entry_vendor'] = $this->language->get('entry_vendor');
		$data['entry_environment'] = $this->language->get('entry_environment');
		$data['entry_transaction_method'] = $this->language->get('entry_transaction_method');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_cron_last_run'] = $this->language->get('entry_cron_last_run');
		$data['entry_card_save'] = $this->language->get('entry_card_save');
		$data['entry_cron_token'] = $this->language->get('entry_cron_token');
		$data['entry_cron_url'] = $this->language->get('entry_cron_url');
		$data['entry_debug'] = $this->language->get('entry_debug');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
				
		$data['help_total'] = $this->language->get('help_total');
		$data['help_debug'] = $this->language->get('help_debug');
		$data['help_transaction_method'] = $this->language->get('help_transaction_method');
		$data['help_card_save'] = $this->language->get('help_card_save');
		$data['help_cron_token'] = $this->language->get('help_cron_token');
		$data['help_cron_url'] = $this->language->get('help_cron_url');
						
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extensions'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/opayo', 'token=' . $this->session->data['token'], true)
		);
						
		$data['action'] = $this->url->link('extension/payment/opayo', 'token=' . $this->session->data['token'], true);
		
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = HTTPS_SERVER;
			$catalog = HTTPS_CATALOG;
		} else {
			$server = HTTP_SERVER;
			$catalog = HTTP_CATALOG;
		}
		
		// Setting 		
		$_config = new Config();
		$_config->load('opayo');
		
		$data['setting'] = $_config->get('opayo_setting');
		
		if (isset($this->request->post['opayo_setting'])) {
			$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->request->post['opayo_setting']);
		} else {
			$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('opayo_setting'));
		}
		
		if (isset($this->request->post['opayo_vendor'])) {
			$data['vendor'] = $this->request->post['opayo_vendor'];
		} else {
			$data['vendor'] = $this->config->get('opayo_vendor');
		}

		if (isset($this->request->post['opayo_total'])) {
			$data['total'] = $this->request->post['opayo_total'];
		} else {
			$data['total'] = $this->config->get('opayo_total');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['opayo_geo_zone_id'])) {
			$data['geo_zone_id'] = $this->request->post['opayo_geo_zone_id'];
		} else {
			$data['geo_zone_id'] = $this->config->get('opayo_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['opayo_status'])) {
			$data['status'] = $this->request->post['opayo_status'];
		} else {
			$data['status'] = $this->config->get('opayo_status');
		}

		if (isset($this->request->post['opayo_sort_order'])) {
			$data['sort_order'] = $this->request->post['opayo_sort_order'];
		} else {
			$data['sort_order'] = $this->config->get('opayo_sort_order');
		}
		
		if (!$data['setting']['cron']['token']) {
			$data['setting']['cron']['token'] = sha1(uniqid(mt_rand(), 1));
		}

		if (!$data['setting']['cron']['url']) {
			$data['setting']['cron']['url'] = $catalog . 'index.php?route=extension/payment/opayo/cron&token=' . $data['setting']['cron']['token'];
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/opayo/opayo', $data));
	}

	public function install() {
		$this->load->model('extension/payment/opayo');
		
		$this->model_extension_payment_opayo->install();
	}

	public function uninstall() {
		$this->load->model('extension/payment/opayo');
		
		$this->model_extension_payment_opayo->uninstall();
	}

	public function order() {
		if ($this->config->get('opayo_status')) {
			$this->load->model('extension/payment/opayo');

			$opayo_order = $this->model_extension_payment_opayo->getOrder($this->request->get['order_id']);

			if (!empty($opayo_order)) {
				$this->load->language('extension/payment/opayo');
				
				$data['text_payment_info'] = $this->language->get('text_payment_info');
				$data['text_order_ref'] = $this->language->get('text_order_ref');
				$data['text_order_total'] = $this->language->get('text_order_total');
				$data['text_total_released'] = $this->language->get('text_total_released');
				$data['text_release_status'] = $this->language->get('text_release_status');
				$data['text_void_status'] = $this->language->get('text_void_status');
				$data['text_rebate_status'] = $this->language->get('text_rebate_status');
				$data['text_transactions'] = $this->language->get('text_transactions');
				$data['text_yes'] = $this->language->get('text_yes');
				$data['text_no'] = $this->language->get('text_no');
				$data['text_column_amount'] = $this->language->get('text_column_amount');
				$data['text_column_type'] = $this->language->get('text_column_type');
				$data['text_column_date_added'] = $this->language->get('text_column_date_added');
				$data['button_release'] = $this->language->get('button_release');
				$data['button_rebate'] = $this->language->get('button_rebate');
				$data['button_void'] = $this->language->get('button_void');
				$data['text_confirm_void'] = $this->language->get('text_confirm_void');
				$data['text_confirm_release'] = $this->language->get('text_confirm_release');
				$data['text_confirm_rebate'] = $this->language->get('text_confirm_rebate');

				$opayo_order['total_released'] = $this->model_extension_payment_opayo->getTotalReleased($opayo_order['opayo_order_id']);

				$opayo_order['total_formatted'] = $this->currency->format($opayo_order['total'], $opayo_order['currency_code'], false, false);
				$opayo_order['total_released_formatted'] = $this->currency->format($opayo_order['total_released'], $opayo_order['currency_code'], false, false);

				$data['opayo_order'] = $opayo_order;

				$data['auto_settle'] = $opayo_order['settle_type'];

				$data['order_id'] = $this->request->get['order_id'];
				
				$data['token'] = $this->request->get['token'];

				return $this->load->view('extension/payment/opayo/order', $data);
			}
		}
	}

	public function void() {
		$this->load->language('extension/payment/opayo');
		
		$json = array();

		if (!empty($this->request->post['order_id'])) {
			$this->load->model('extension/payment/opayo');

			$opayo_order = $this->model_extension_payment_opayo->getOrder($this->request->post['order_id']);

			$void_response = $this->model_extension_payment_opayo->void($this->request->post['order_id']);

			$this->model_extension_payment_opayo->log('Void result', $void_response);

			if (!empty($void_response) && $void_response['Status'] == 'OK') {
				$this->model_extension_payment_opayo->addOrderTransaction($opayo_order['opayo_order_id'], 'void', 0.00);
				$this->model_extension_payment_opayo->updateVoidStatus($opayo_order['opayo_order_id'], 1);

				$json['msg'] = $this->language->get('success_void_ok');

				$json['data'] = array();
				$json['data']['date_added'] = date('Y-m-d H:i:s');
				$json['error'] = false;
			} else {
				$json['error'] = true;
				$json['msg'] = isset($void_response['StatuesDetail']) && !empty($void_response['StatuesDetail']) ? (string)$void_response['StatuesDetail'] : 'Unable to void';
			}
		} else {
			$json['error'] = true;
			$json['msg'] = 'Missing data';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function release() {
		$this->load->language('extension/payment/opayo');
		
		$json = array();

		if (!empty($this->request->post['order_id']) && !empty($this->request->post['amount']) && $this->request->post['amount'] > 0) {
			$this->load->model('extension/payment/opayo');

			$opayo_order = $this->model_extension_payment_opayo->getOrder($this->request->post['order_id']);

			$release_response = $this->model_extension_payment_opayo->release($this->request->post['order_id'], $this->request->post['amount']);

			$this->model_extension_payment_opayo->log('Release result', $release_response);

			if (!empty($release_response) && $release_response['Status'] == 'OK') {
				$this->model_extension_payment_opayo->addOrderTransaction($opayo_order['opayo_order_id'], 'payment', $this->request->post['amount']);

				$total_released = $this->model_extension_payment_opayo->getTotalReleased($opayo_order['opayo_order_id']);

				if ($total_released >= $opayo_order['total'] || $opayo_order['settle_type'] == 0) {
					$this->model_extension_payment_opayo->updateReleaseStatus($opayo_order['opayo_order_id'], 1);
					$release_status = 1;
					$json['msg'] = $this->language->get('success_release_ok_order');
				} else {
					$release_status = 0;
					$json['msg'] = $this->language->get('success_release_ok');
				}

				$json['data'] = array();
				$json['data']['date_added'] = date('Y-m-d H:i:s');
				$json['data']['amount'] = $this->request->post['amount'];
				$json['data']['release_status'] = $release_status;
				$json['data']['total'] = (float)$total_released;
				$json['error'] = false;
			} else {
				$json['error'] = true;
				$json['msg'] = isset($release_response['StatusDetail']) && !empty($release_response['StatusDetail']) ? (string)$release_response['StatusDetail'] : 'Unable to release';
			}
		} else {
			$json['error'] = true;
			$json['msg'] = $this->language->get('error_data_missing');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function rebate() {
		$this->load->language('extension/payment/opayo');
		
		$json = array();

		if (!empty($this->request->post['order_id'])) {
			$this->load->model('extension/payment/opayo');

			$opayo_order = $this->model_extension_payment_opayo->getOrder($this->request->post['order_id']);

			$rebate_response = $this->model_extension_payment_opayo->rebate($this->request->post['order_id'], $this->request->post['amount']);

			$this->model_extension_payment_opayo->log('Rebate result', $rebate_response);

			if (!empty($rebate_response) && $rebate_response['Status'] == 'OK') {
				$this->model_extension_payment_opayo->addOrderTransaction($opayo_order['opayo_order_id'], 'rebate', $this->request->post['amount'] * -1);

				$total_rebated = $this->model_extension_payment_opayo->getTotalRebated($opayo_order['opayo_order_id']);
				$total_released = $this->model_extension_payment_opayo->getTotalReleased($opayo_order['opayo_order_id']);

				if (($total_released <= 0) && ($opayo_order['release_status'] == 1)) {
					$this->model_extension_payment_opayo->updateRebateStatus($opayo_order['opayo_order_id'], 1);
					$rebate_status = 1;
					$json['msg'] = $this->language->get('success_rebate_ok_order');
				} else {
					$rebate_status = 0;
					$json['msg'] = $this->language->get('success_rebate_ok');
				}

				$json['data'] = array();
				$json['data']['date_added'] = date('Y-m-d H:i:s');
				$json['data']['amount'] = $this->request->post['amount'] * -1;
				$json['data']['total_released'] = (float)$total_released;
				$json['data']['total_rebated'] = (float)$total_rebated;
				$json['data']['rebate_status'] = $rebate_status;
				$json['error'] = false;
			} else {
				$json['error'] = true;
				$json['msg'] = isset($rebate_response['StatusDetail']) && !empty($rebate_response['StatusDetail']) ? (string)$rebate_response['StatusDetail'] : 'Unable to rebate';
			}
		} else {
			$json['error'] = true;
			$json['msg'] = 'Missing data';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/opayo')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->request->post['opayo_vendor'] = trim($this->request->post['opayo_vendor']);

		if (!$this->request->post['opayo_vendor']) {
			$this->error['vendor'] = $this->language->get('error_vendor');
			$this->error['warning'] = $this->language->get('error_warning');
		} 

		return !$this->error;
	}
}
