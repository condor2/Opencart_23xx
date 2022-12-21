<?php
class ControllerDesignSeoUrl extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('design/seo_url');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/seo_url');

		$this->getList();
	}

	public function add() {
		$this->load->language('design/seo_url');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/seo_url');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_design_seo_url->addSeoUrl($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_query'])) {
				$url .= '&filter_query=' . urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_keyword'])) {
				$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('design/seo_url', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('design/seo_url');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/seo_url');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_design_seo_url->editSeoUrl($this->request->get['url_alias_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_query'])) {
				$url .= '&filter_query=' . urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_keyword'])) {
				$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('design/seo_url', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('design/seo_url');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/seo_url');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $url_alias_id) {
				$this->model_design_seo_url->deleteSeoUrl($url_alias_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_query'])) {
				$url .= '&filter_query=' . urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_keyword'])) {
				$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('design/seo_url', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_query'])) {
			$filter_query = $this->request->get['filter_query'];
		} else {
			$filter_query = '';
		}

		if (isset($this->request->get['filter_keyword'])) {
			$filter_keyword = $this->request->get['filter_keyword'];
		} else {
			$filter_keyword = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'query';
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

		if (isset($this->request->get['filter_query'])) {
			$url .= '&filter_query=' . urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_keyword'])) {
			$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
		}

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
			'href' => $this->url->link('design/seo_url', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('design/seo_url/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('design/seo_url/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['seo_urls'] = array();

		$filter_data = array(
			'filter_query'	     => $filter_query,
			'filter_keyword'	 => $filter_keyword,
			'sort'               => $sort,
			'order'              => $order,
			'start'              => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'              => $this->config->get('config_limit_admin')
		);

		$seo_url_total = $this->model_design_seo_url->getTotalSeoUrls($filter_data);

		$results = $this->model_design_seo_url->getSeoUrls($filter_data);

		foreach ($results as $result) {
			$data['seo_urls'][] = array(
				'url_alias_id' => $result['url_alias_id'],
				'query'        => $result['query'],
				'keyword'      => $result['keyword'],
				'edit'         => $this->url->link('design/seo_url/edit', 'token=' . $this->session->data['token'] . '&url_alias_id=' . $result['url_alias_id'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_query'] = $this->language->get('column_query');
		$data['column_keyword'] = $this->language->get('column_keyword');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_query'] = $this->language->get('entry_query');
		$data['entry_keyword'] = $this->language->get('entry_keyword');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');

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

		if (isset($this->request->get['filter_query'])) {
			$url .= '&filter_query=' . urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_keyword'])) {
			$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_query'] = $this->url->link('design/seo_url', 'token=' . $this->session->data['token'] . '&sort=query' . $url, true);
		$data['sort_keyword'] = $this->url->link('design/seo_url', 'token=' . $this->session->data['token'] . '&sort=keyword' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_query'])) {
			$url .= '&filter_query=' . urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_keyword'])) {
			$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $seo_url_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('design/seo_url', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($seo_url_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($seo_url_total - $this->config->get('config_limit_admin'))) ? $seo_url_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $seo_url_total, ceil($seo_url_total / $this->config->get('config_limit_admin')));

		$data['filter_query'] = $filter_query;
		$data['filter_keyword'] = $filter_keyword;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/seo_url_list', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['url_alias_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$data['entry_query'] = $this->language->get('entry_query');
		$data['entry_keyword'] = $this->language->get('entry_keyword');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['query'])) {
			$data['error_query'] = $this->error['query'];
		} else {
			$data['error_query'] = '';
		}

		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
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
			'href' => $this->url->link('design/seo_url', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['url_alias_id'])) {
			$data['action'] = $this->url->link('design/seo_url/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('design/seo_url/edit', 'token=' . $this->session->data['token'] . '&url_alias_id=' . $this->request->get['url_alias_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('design/seo_url', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['url_alias_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$seo_url_info = $this->model_design_seo_url->getSeoUrl($this->request->get['url_alias_id']);
		}

		if (isset($this->request->post['query'])) {
			$data['query'] = $this->request->post['query'];
		} elseif (!empty($seo_url_info)) {
			$data['query'] = $seo_url_info['query'];
		} else {
			$data['query'] = '';
		}

		if (isset($this->request->post['keyword'])) {
			$data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($seo_url_info)) {
			$data['keyword'] = $seo_url_info['keyword'];
		} else {
			$data['keyword'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/seo_url_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'design/seo_url')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (isset($this->request->get['url_alias_id']) && $this->request->get['url_alias_id']) {
			$seo_urls = $this->model_design_seo_url->getSeoUrlsByQueryId($this->request->get['url_alias_id'], $this->request->post['query']);
		} else {
			$seo_urls = $this->model_design_seo_url->getSeoUrlsByQuery($this->request->post['query']);
		}
		
		foreach ($seo_urls as $seo_url) {
			if ($seo_url['query'] == $this->request->post['query']) {
				$this->error['query'] = $this->language->get('error_query_exists');

				break;
			}
		}

		if (!$this->request->post['query']) {
			$this->error['query'] = $this->language->get('error_query');
		}

		$seo_urls = $this->model_design_seo_url->getSeoUrlsByKeyword($this->request->post['keyword']);

		foreach ($seo_urls as $seo_url) {
			if ($seo_url['query'] != $this->request->post['query']) {
				$this->error['keyword'] = $this->language->get('error_exists');

				break;
			}
		}

		if (!$this->request->post['keyword']) {
			$this->error['keyword'] = $this->language->get('error_keyword');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'design/seo_url')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}