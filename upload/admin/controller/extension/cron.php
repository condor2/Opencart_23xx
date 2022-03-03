<?php
class ControllerExtensionCron extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/cron');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/cron');

		$this->getList();
	}

	public function delete() {
		$this->load->language('extension/cron');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/cron');

		if (isset($this->request->post['selected']) && $this->validate()) {
			foreach ($this->request->post['selected'] as $cron_id) {
				$this->model_setting_cron->deleteCron($cron_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/cron', 'token=' . $this->session->data['token'] . $url));
		}

		$this->getList();
	}

	public function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'code';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/cron', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['delete'] = $this->url->link('extension/cron/delete', 'token=' . $this->session->data['token'] . $url, true);
		$data['cron'] = $this->url->link('common/cron');

		$data['crons'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$cron_total = $this->model_setting_cron->getTotalCrons();

		$results = $this->model_setting_cron->getCrons($filter_data);

		foreach ($results as $result) {
			$data['crons'][] = array(
				'cron_id'       => $result['cron_id'],
				'code'          => $result['code'],
				'cycle'         => $this->language->get('text_' . $result['cycle']),
				'action'        => $result['action'],
				'status'        => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'date_added'    => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('datetime_format'), strtotime($result['date_modified'])),
				'enabled'       => $result['status']
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_instruction'] = $this->language->get('text_instruction');
		$data['text_cron_1'] = $this->language->get('text_cron_1');
		$data['text_cron_2'] = $this->language->get('text_cron_2');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_code'] = $this->language->get('column_code');
		$data['column_cycle'] = $this->language->get('column_cycle');
		$data['column_action'] = $this->language->get('column_action');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_date_modified'] = $this->language->get('column_date_modified');

		$data['entry_cron'] = $this->language->get('entry_cron');

		$data['button_run'] = $this->language->get('button_run');
		$data['button_enable'] = $this->language->get('button_enable');
		$data['button_disable'] = $this->language->get('button_disable');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_copy'] = $this->language->get('button_copy');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_code'] = $this->url->link('extension/cron', 'token=' . $this->session->data['token'] . '&sort=code' . $url, true);
		$data['sort_cycle'] = $this->url->link('extension/cron', 'token=' . $this->session->data['token'] . '&sort=cycle' . $url, true);
		$data['sort_action'] = $this->url->link('extension/cron', 'token=' . $this->session->data['token'] . '&sort=action' . $url, true);
		$data['sort_status'] = $this->url->link('extension/cron', 'token=' . $this->session->data['token'] . '&sort=status' . $url, true);
		$data['sort_date_added'] = $this->url->link('extension/cron', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, true);
		$data['sort_date_modified'] = $this->url->link('extension/cron', 'token=' . $this->session->data['token'] . '&sort=date_modified' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $cron_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/cron', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($cron_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($cron_total - $this->config->get('config_limit_admin'))) ? $cron_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $cron_total, ceil($cron_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/cron', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/cron')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function run() {
		$this->load->language('extension/cron');

		$json = array();

		if (isset($this->request->get['cron_id'])) {
			$cron_id = (int)$this->request->get['cron_id'];
		} else {
			$cron_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'extension/cron')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('setting/cron');

			$cron_info = $this->model_setting_cron->getCron($cron_id);

			if ($cron_info) {
				$this->load->controller($cron_info['action'], $cron_id, $cron_info['code'], $cron_info['cycle'], $cron_info['date_added'], $cron_info['date_modified']);

				$this->model_setting_cron->editCron($cron_info['cron_id']);

			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function enable() {
		$this->load->language('extension/cron');

		$json = array();

		if (isset($this->request->get['cron_id'])) {
			$cron_id = (int)$this->request->get['cron_id'];
		} else {
			$cron_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'extension/cron')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('setting/cron');

			$this->model_setting_cron->editStatus($cron_id, 1);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function disable() {
		$this->load->language('extension/cron');

		$json = array();

		if (isset($this->request->get['cron_id'])) {
			$cron_id = (int)$this->request->get['cron_id'];
		} else {
			$cron_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'extension/cron')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('setting/cron');

			$this->model_setting_cron->editStatus($cron_id, 0);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}