<?php
class ControllerExtensionPaymentPayPal extends Controller {
	private $error = array();
	
	public function __construct($registry) {
		parent::__construct($registry);
		
		if (empty($this->config->get('paypal_version')) || (!empty($this->config->get('paypal_version')) && ($this->config->get('paypal_version') < '3.0.0'))) {
			$this->uninstall();
			$this->install();
		}
	}
			
	public function index() {				
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = HTTPS_SERVER;
			$catalog = HTTPS_CATALOG;
		} else {
			$server = HTTP_SERVER;
			$catalog = HTTP_CATALOG;
		}
			
		$_config = new Config();
		$_config->load('paypal');
		
		$config_setting = $_config->get('paypal_setting');
		
		$cache_data = $this->cache->get('paypal');
		
		$this->cache->delete('paypal');
		
		if (!empty($cache_data['environment']) && !empty($cache_data['authorization_code']) && !empty($cache_data['shared_id']) && !empty($cache_data['seller_nonce']) && !empty($this->request->get['merchantIdInPayPal'])) {							
			$this->load->language('extension/payment/paypal');
			
			$this->load->model('extension/payment/paypal');
			
			$environment = $cache_data['environment'];
			
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
			
			$paypal_info = array(
				'client_id' => $cache_data['shared_id'],
				'environment' => $environment,
				'partner_attribution_id' => $config_setting['partner'][$environment]['partner_attribution_id']
			);
					
			$paypal = new PayPal($paypal_info);
			
			$token_info = array(
				'grant_type' => 'authorization_code',
				'code' => $cache_data['authorization_code'],
				'code_verifier' => $cache_data['seller_nonce']
			);
			
			$paypal->setAccessToken($token_info);
							
			$result = $paypal->getSellerCredentials($config_setting['partner'][$environment]['partner_id']);
									
			$client_id = '';
			$secret = '';
			
			if (isset($result['client_id']) && isset($result['client_secret'])) {
				$client_id = $result['client_id'];
				$secret = $result['client_secret'];
			}
			
			$paypal_info = array(
				'partner_id' => $config_setting['partner'][$environment]['partner_id'],
				'client_id' => $client_id,
				'secret' => $secret,
				'environment' => $environment,
				'partner_attribution_id' => $config_setting['partner'][$environment]['partner_attribution_id']
			);
		
			$paypal = new PayPal($paypal_info);
			
			$token_info = array(
				'grant_type' => 'client_credentials'
			);	
		
			$paypal->setAccessToken($token_info);
			
			$callback_token = sha1(uniqid(mt_rand(), 1));
			$webhook_token = sha1(uniqid(mt_rand(), 1));
			$cron_token = sha1(uniqid(mt_rand(), 1));
							
			$webhook_info = array(
				'url' => $catalog . 'index.php?route=extension/payment/paypal&webhook_token=' . $webhook_token,
				'event_types' => array(
					array('name' => 'PAYMENT.AUTHORIZATION.CREATED'),
					array('name' => 'PAYMENT.AUTHORIZATION.VOIDED'),
					array('name' => 'PAYMENT.CAPTURE.COMPLETED'),
					array('name' => 'PAYMENT.CAPTURE.DENIED'),
					array('name' => 'PAYMENT.CAPTURE.PENDING'),
					array('name' => 'PAYMENT.CAPTURE.REFUNDED'),
					array('name' => 'PAYMENT.CAPTURE.REVERSED'),
					array('name' => 'CHECKOUT.ORDER.COMPLETED'),
					array('name' => 'VAULT.PAYMENT-TOKEN.CREATED')
				)
			);
			
			$result = $paypal->createWebhook($webhook_info);
						
			$webhook_id = '';
		
			if (isset($result['id'])) {
				$webhook_id = $result['id'];
			}
			
			if ($paypal->hasErrors()) {
				$error_messages = array();
				
				$errors = $paypal->getErrors();
						
				foreach ($errors as $error) {
					if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
						$error['message'] = $this->language->get('error_timeout');
					}
					
					if (isset($error['details'][0]['description'])) {
						$error_messages[] = $error['details'][0]['description'];
					} elseif (isset($error['message'])) {
						$error_messages[] = $error['message'];
					}
					
					$this->model_extension_payment_paypal->log($error, $error['message']);
				}
				
				$this->error['warning'] = implode(' ', $error_messages);
			}
   			
			$merchant_id = $this->request->get['merchantIdInPayPal'];
			
			$this->load->model('setting/setting');
			
			$setting = $this->model_setting_setting->getSetting('paypal');
						
			$setting['paypal_environment'] = $environment;
			$setting['paypal_client_id'] = $client_id;
			$setting['paypal_secret'] = $secret;
			$setting['paypal_merchant_id'] = $merchant_id;
			$setting['paypal_webhook_id'] = $webhook_id;
			$setting['paypal_status'] = 1;
			$setting['paypal_total'] = 0;
			$setting['paypal_geo_zone_id'] = 0;
			$setting['paypal_sort_order'] = 0;
			$setting['paypal_setting']['general']['callback_token'] = $callback_token;
			$setting['paypal_setting']['general']['webhook_token'] = $webhook_token;
			$setting['paypal_setting']['general']['cron_token'] = $cron_token;
			
			$this->load->model('localisation/country');
		
			$country = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));
			
			$setting['paypal_setting']['general']['country_code'] = $country['iso_code_2'];
									
			$currency_code = $this->config->get('config_currency');
			$currency_value = $this->currency->getValue($this->config->get('config_currency'));
		
			if (!empty($config_setting['currency'][$currency_code]['status'])) {
				$setting['paypal_setting']['general']['currency_code'] = $currency_code;
				$setting['paypal_setting']['general']['currency_value'] = $currency_value;
			}
			
			if (!empty($config_setting['currency'][$currency_code]['card_status'])) {
				$setting['paypal_setting']['general']['card_currency_code'] = $currency_code;
				$setting['paypal_setting']['general']['card_currency_value'] = $currency_value;
			}
			
			$this->model_setting_setting->editSetting('paypal', $setting);
		}
		
		if (!empty($this->request->get['merchantIdInPayPal']) && !$this->error) {
			sleep(3);
			
			$this->response->redirect($this->url->link('extension/payment/paypal', 'token=' . $this->session->data['token'], true));
		}
		
		if (!$this->config->get('paypal_client_id')) {
			$this->auth();
		} else {
			$this->dashboard();
		}
	}
	
	public function auth() {
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		$this->document->addStyle('view/stylesheet/paypal/paypal.css');
		
		$this->document->setTitle($this->language->get('heading_title_main'));
		
		$data['heading_title_main'] = $this->language->get('heading_title_main');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_welcome'] = $this->language->get('text_welcome');
		$data['text_production'] = $this->language->get('text_production');
		$data['text_sandbox'] = $this->language->get('text_sandbox');
				
		$data['entry_environment'] = $this->language->get('entry_environment');
							
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_connect'] = $this->language->get('button_connect');
						
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
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link('extension/payment/paypal', 'token=' . $this->session->data['token'], true)
		);
								
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);
		$data['partner_url'] = str_replace('&amp;', '%26', $this->url->link('extension/payment/paypal', 'token=' . $this->session->data['token'], true));
		$data['callback_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/callback', 'token=' . $this->session->data['token'], true));
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/agree', 'token=' . $this->session->data['token'], true));
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['server'] = HTTPS_SERVER;
			$data['catalog'] = HTTPS_CATALOG;
		} else {
			$data['server'] = HTTP_SERVER;
			$data['catalog'] = HTTP_CATALOG;
		}
		
		$_config = new Config();
		$_config->load('paypal');
		
		$data['setting'] = $_config->get('paypal_setting');
				
		if (isset($this->session->data['environment'])) {
			$data['environment'] = $this->session->data['environment'];
		} else {
			$data['environment'] = 'production';
		}
								
		$data['seller_nonce'] = $this->token(50);
		
		$data['configure_url'] = array(
			'production' => array(
				'ppcp' => 'https://www.paypal.com/bizsignup/partner/entry?partnerId=' . $data['setting']['partner']['production']['partner_id'] . '&partnerClientId=' . $data['setting']['partner']['production']['client_id'] . '&features=PAYMENT,REFUND,ACCESS_MERCHANT_INFORMATION,VAULT,BILLING_AGREEMENT&product=PPCP,ADVANCED_VAULTING&capabilities=PAYPAL_WALLET_VAULTING_ADVANCED&integrationType=FO&returnToPartnerUrl=' . $data['partner_url'] . '&displayMode=minibrowser&sellerNonce=' . $data['seller_nonce'],
				'express_checkout' => 'https://www.paypal.com/bizsignup/partner/entry?partnerId=' . $data['setting']['partner']['production']['partner_id'] . '&partnerClientId=' . $data['setting']['partner']['production']['client_id'] . '&features=PAYMENT,REFUND,ACCESS_MERCHANT_INFORMATION,VAULT,BILLING_AGREEMENT&product=EXPRESS_CHECKOUT,ADVANCED_VAULTING&capabilities=PAYPAL_WALLET_VAULTING_ADVANCED&integrationType=FO&returnToPartnerUrl=' . $data['partner_url'] . '&displayMode=minibrowser&sellerNonce=' . $data['seller_nonce']
			),
			'sandbox' => array(
				'ppcp' => 'https://www.sandbox.paypal.com/bizsignup/partner/entry?partnerId=' . $data['setting']['partner']['sandbox']['partner_id'] . '&partnerClientId=' . $data['setting']['partner']['sandbox']['client_id'] . '&features=PAYMENT,REFUND,ACCESS_MERCHANT_INFORMATION,VAULT,BILLING_AGREEMENT&product=PPCP,ADVANCED_VAULTING&capabilities=PAYPAL_WALLET_VAULTING_ADVANCED&integrationType=FO&returnToPartnerUrl=' . $data['partner_url'] . '&displayMode=minibrowser&sellerNonce=' . $data['seller_nonce'],
				'express_checkout' => 'https://www.sandbox.paypal.com/bizsignup/partner/entry?partnerId=' . $data['setting']['partner']['sandbox']['partner_id'] . '&partnerClientId=' . $data['setting']['partner']['sandbox']['client_id'] . '&features=PAYMENT,REFUND,ACCESS_MERCHANT_INFORMATION,VAULT,BILLING_AGREEMENT&product=EXPRESS_CHECKOUT,ADVANCED_VAULTING&capabilities=PAYPAL_WALLET_VAULTING_ADVANCED&integrationType=FO&returnToPartnerUrl=' . $data['partner_url'] . '&displayMode=minibrowser&sellerNonce=' . $data['seller_nonce']
			)
		);
		
		$data['text_checkout_express'] = sprintf($this->language->get('text_checkout_express'), $data['configure_url'][$data['environment']]['express_checkout']);
		$data['text_support'] = sprintf($this->language->get('text_support'), $this->request->server['HTTP_HOST']);
		
		$result = $this->model_extension_payment_paypal->checkVersion(VERSION, $data['setting']['version']);
		
		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}
		
		$agree_status = $this->model_extension_payment_paypal->getAgreeStatus();
		
		if (!$agree_status) {
			$this->error['warning'] = $this->language->get('error_agree');
		}
																
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
					
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/paypal/auth', $data));
	}
		
	public function dashboard() {
		if (!$this->config->get('paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/payment/paypal', 'token=' . $this->session->data['token'], true));
		}
		
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		$this->load->model('setting/setting');
		
		$this->document->addStyle('view/stylesheet/paypal/paypal.css');
		$this->document->addStyle('view/stylesheet/paypal/bootstrap-switch.css');
		
		$this->document->addScript('view/javascript/paypal/bootstrap-switch.js');

		$this->document->setTitle($this->language->get('heading_title_main'));
		
		$data['heading_title_main'] = $this->language->get('heading_title_main');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_tab_general'] = $this->language->get('text_tab_general');
		$data['text_tab_button'] = $this->language->get('text_tab_button');
		$data['text_tab_googlepay_button'] = $this->language->get('text_tab_googlepay_button');
		$data['text_tab_applepay_button'] = $this->language->get('text_tab_applepay_button');
		$data['text_tab_card'] = $this->language->get('text_tab_card');
		$data['text_tab_message_configurator'] = $this->language->get('text_tab_message_configurator');
		$data['text_tab_message_setting'] = $this->language->get('text_tab_message_setting');
		$data['text_tab_order_status'] = $this->language->get('text_tab_order_status');
		$data['text_tab_contact'] = $this->language->get('text_tab_contact');
		$data['text_all_sales']	= $this->language->get('text_all_sales');
		$data['text_paypal_sales'] = $this->language->get('text_paypal_sales');
		$data['text_panel_statistic']	= $this->language->get('text_panel_statistic');
		$data['text_panel_sale_analytics']	= $this->language->get('text_panel_sale_analytics');
		$data['text_statistic_title']	= $this->language->get('text_statistic_title');
		$data['text_statistic_description']	= $this->language->get('text_statistic_description');
		$data['text_on'] = $this->language->get('text_on');
		$data['text_off'] = $this->language->get('text_off');
		$data['text_day']	= $this->language->get('text_day');
		$data['text_week']	= $this->language->get('text_week');
		$data['text_month']	= $this->language->get('text_month');
		$data['text_year']	= $this->language->get('text_year');
				
		$data['entry_status'] = $this->language->get('entry_status');
		
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
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link('extension/payment/paypal', 'token=' . $this->session->data['token'], true)
		);
		
		$data['href_dashboard'] = $this->url->link('extension/payment/paypal/dashboard', 'token=' . $this->session->data['token'], true);
		$data['href_general'] = $this->url->link('extension/payment/paypal/general', 'token=' . $this->session->data['token'], true);
		$data['href_button'] = $this->url->link('extension/payment/paypal/button', 'token=' . $this->session->data['token'], true);
		$data['href_googlepay_button'] = $this->url->link('extension/payment/paypal/googlepay_button', 'token=' . $this->session->data['token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'token=' . $this->session->data['token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'token=' . $this->session->data['token'], true);
		$data['href_message_configurator'] = $this->url->link('extension/payment/paypal/message_configurator', 'token=' . $this->session->data['token'], true);
		$data['href_message_setting'] = $this->url->link('extension/payment/paypal/message_setting', 'token=' . $this->session->data['token'], true);
		$data['href_order_status'] = $this->url->link('extension/payment/paypal/order_status', 'token=' . $this->session->data['token'], true);
		$data['href_contact'] = $this->url->link('extension/payment/paypal/contact', 'token=' . $this->session->data['token'], true);
						
		$data['action'] = $this->url->link('extension/payment/paypal/save', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);
		$data['sale_analytics_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/getSaleAnalytics', 'token=' . $this->session->data['token'], true));
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/agree', 'token=' . $this->session->data['token'], true));
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['server'] = HTTPS_SERVER;
			$data['catalog'] = HTTPS_CATALOG;
		} else {
			$data['server'] = HTTP_SERVER;
			$data['catalog'] = HTTP_CATALOG;
		}
			
		$_config = new Config();
		$_config->load('paypal');
		
		$data['setting'] = $_config->get('paypal_setting');
		
		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('paypal_setting'));
		
		if ($this->config->get('paypal_status') != null) {
			$data['status'] = $this->config->get('paypal_status');
		} else {
			$data['status'] = 1;
		}
									
		if ($data['setting']['button']['product']['status'] || $data['setting']['button']['cart']['status'] || $data['setting']['button']['checkout']['status']) {
			$data['button_status'] = 1;
		} else {
			$data['button_status'] = 0;
		}
		
		if ($data['setting']['googlepay_button']['status']) {
			$data['googlepay_button_status'] = 1;
		} else {
			$data['googlepay_button_status'] = 0;
		}
		
		if ($data['setting']['applepay_button']['status']) {
			$data['applepay_button_status'] = 1;
		} else {
			$data['applepay_button_status'] = 0;
		}
		
		if ($data['setting']['card']['status']) {
			$data['card_status'] = 1;
		} else {
			$data['card_status'] = 0;
		}
		
		if ($data['setting']['message']['home']['status'] || $data['setting']['message']['product']['status'] || $data['setting']['message']['cart']['status'] || $data['setting']['message']['checkout']['status']) {
			$data['message_status'] = 1;
		} else {
			$data['message_status'] = 0;
		}
		
		$paypal_sale_total = $this->model_extension_payment_paypal->getTotalSales();
		
		$data['paypal_sale_total'] = $this->currency->format($paypal_sale_total, $this->config->get('config_currency'));
		
		$result = $this->model_extension_payment_paypal->checkVersion(VERSION, $data['setting']['version']);
		
		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}
		
		$agree_status = $this->model_extension_payment_paypal->getAgreeStatus();
		
		if (!$agree_status) {
			$this->error['warning'] = $this->language->get('error_agree');
		}		
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
					
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/paypal/dashboard', $data));
	}
	
	public function general() {
		if (!$this->config->get('paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/payment/paypal', 'token=' . $this->session->data['token'], true));
		}
		
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		$this->document->addStyle('view/stylesheet/paypal/paypal.css');
		$this->document->addStyle('view/stylesheet/paypal/bootstrap-switch.css');
		
		$this->document->addScript('view/javascript/paypal/bootstrap-switch.js');

		$this->document->setTitle($this->language->get('heading_title_main'));
		
		$data['heading_title_main'] = $this->language->get('heading_title_main');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_tab_dashboard'] = $this->language->get('text_tab_dashboard');
		$data['text_tab_general'] = $this->language->get('text_tab_general');
		$data['text_tab_button'] = $this->language->get('text_tab_button');
		$data['text_tab_googlepay_button'] = $this->language->get('text_tab_googlepay_button');
		$data['text_tab_applepay_button'] = $this->language->get('text_tab_applepay_button');
		$data['text_tab_card'] = $this->language->get('text_tab_card');
		$data['text_tab_message_configurator'] = $this->language->get('text_tab_message_configurator');
		$data['text_tab_message_setting'] = $this->language->get('text_tab_message_setting');
		$data['text_tab_order_status'] = $this->language->get('text_tab_order_status');
		$data['text_tab_contact'] = $this->language->get('text_tab_contact');
		$data['text_on'] = $this->language->get('text_on');
		$data['text_off'] = $this->language->get('text_off');
		$data['text_day']	= $this->language->get('text_day');
		$data['text_week']	= $this->language->get('text_week');
		$data['text_month']	= $this->language->get('text_month');
		$data['text_year']	= $this->language->get('text_year');
		$data['text_multi_button'] = $this->language->get('text_multi_button');
		$data['text_one_button'] = $this->language->get('text_one_button');
		$data['text_authorization'] = $this->language->get('text_authorization');
		$data['text_sale'] = $this->language->get('text_sale');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_currency_aud'] = $this->language->get('text_currency_aud');
		$data['text_currency_brl'] = $this->language->get('text_currency_brl');
		$data['text_currency_cad'] = $this->language->get('text_currency_cad');
		$data['text_currency_czk'] = $this->language->get('text_currency_czk');
		$data['text_currency_dkk'] = $this->language->get('text_currency_dkk');
		$data['text_currency_eur'] = $this->language->get('text_currency_eur');
		$data['text_currency_hkd'] = $this->language->get('text_currency_hkd');
		$data['text_currency_huf'] = $this->language->get('text_currency_huf');
		$data['text_currency_inr'] = $this->language->get('text_currency_inr');
		$data['text_currency_ils'] = $this->language->get('text_currency_ils');
		$data['text_currency_jpy'] = $this->language->get('text_currency_jpy');
		$data['text_currency_myr'] = $this->language->get('text_currency_myr');
		$data['text_currency_mxn'] = $this->language->get('text_currency_mxn');
		$data['text_currency_twd'] = $this->language->get('text_currency_twd');
		$data['text_currency_nzd'] = $this->language->get('text_currency_nzd');
		$data['text_currency_nok'] = $this->language->get('text_currency_nok');
		$data['text_currency_php'] = $this->language->get('text_currency_php');
		$data['text_currency_pln'] = $this->language->get('text_currency_pln');
		$data['text_currency_gbp'] = $this->language->get('text_currency_gbp');
		$data['text_currency_rub'] = $this->language->get('text_currency_rub');
		$data['text_currency_sgd'] = $this->language->get('text_currency_sgd');
		$data['text_currency_sek'] = $this->language->get('text_currency_sek');
		$data['text_currency_chf'] = $this->language->get('text_currency_chf');
		$data['text_currency_thb'] = $this->language->get('text_currency_thb');
		$data['text_currency_usd'] = $this->language->get('text_currency_usd');
		$data['text_confirm'] = $this->language->get('text_confirm');
		
		$data['entry_connect'] = $this->language->get('entry_connect');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_debug'] = $this->language->get('entry_debug');
		$data['entry_sale_analytics_range'] = $this->language->get('entry_sale_analytics_range');
		$data['entry_checkout_mode'] = $this->language->get('entry_checkout_mode');
		$data['entry_transaction_method'] = $this->language->get('entry_transaction_method');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_country_code'] = $this->language->get('entry_country_code');
		$data['entry_currency_code'] = $this->language->get('entry_currency_code');
		$data['entry_currency_value'] = $this->language->get('entry_currency_value');
		$data['entry_card_currency_code'] = $this->language->get('entry_card_currency_code');
		$data['entry_card_currency_value'] = $this->language->get('entry_card_currency_value');
		$data['entry_cron_url'] = $this->language->get('entry_cron_url');
		
		$data['help_checkout_mode'] = $this->language->get('help_checkout_mode');
		$data['help_total'] = $this->language->get('help_total');
		$data['help_country_code'] = $this->language->get('help_country_code');
		$data['help_currency_code'] = $this->language->get('help_currency_code');
		$data['help_currency_value'] = $this->language->get('help_currency_value');
		$data['help_card_currency_code'] = $this->language->get('help_card_currency_code');
		$data['help_card_currency_value'] = $this->language->get('help_card_currency_value');
		$data['help_cron_url'] = $this->language->get('help_cron_url');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_disconnect'] = $this->language->get('button_disconnect');
		$data['button_all_settings'] = $this->language->get('button_all_settings');
		$data['button_copy_url'] = $this->language->get('button_copy_url');
		
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
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link('extension/payment/paypal', 'token=' . $this->session->data['token'], true)
		);
		
		// Action
		$data['href_dashboard'] = $this->url->link('extension/payment/paypal/dashboard', 'token=' . $this->session->data['token'], true);
		$data['href_general'] = $this->url->link('extension/payment/paypal/general', 'token=' . $this->session->data['token'], true);
		$data['href_button'] = $this->url->link('extension/payment/paypal/button', 'token=' . $this->session->data['token'], true);
		$data['href_googlepay_button'] = $this->url->link('extension/payment/paypal/googlepay_button', 'token=' . $this->session->data['token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'token=' . $this->session->data['token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'token=' . $this->session->data['token'], true);
		$data['href_message_configurator'] = $this->url->link('extension/payment/paypal/message_configurator', 'token=' . $this->session->data['token'], true);
		$data['href_message_setting'] = $this->url->link('extension/payment/paypal/message_setting', 'token=' . $this->session->data['token'], true);
		$data['href_order_status'] = $this->url->link('extension/payment/paypal/order_status', 'token=' . $this->session->data['token'], true);
		$data['href_contact'] = $this->url->link('extension/payment/paypal/contact', 'token=' . $this->session->data['token'], true);
		
		$data['action'] = $this->url->link('extension/payment/paypal/save', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);
		$data['disconnect_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/disconnect', 'token=' . $this->session->data['token'], true));
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/agree', 'token=' . $this->session->data['token'], true));
				
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['server'] = HTTPS_SERVER;
			$data['catalog'] = HTTPS_CATALOG;
		} else {
			$data['server'] = HTTP_SERVER;
			$data['catalog'] = HTTP_CATALOG;
		}
		
		$_config = new Config();
		$_config->load('paypal');
		
		$data['setting'] = $_config->get('paypal_setting');
		
		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('paypal_setting'));
		
		if ($this->config->get('paypal_status') != null) {
			$data['status'] = $this->config->get('paypal_status');
		} else {
			$data['status'] = 1;
		}
					
		$data['client_id'] = $this->config->get('paypal_client_id');
		$data['secret'] = $this->config->get('paypal_secret');
		$data['merchant_id'] = $this->config->get('paypal_merchant_id');
		$data['webhook_id'] = $this->config->get('paypal_webhook_id');
		$data['environment'] = $this->config->get('paypal_environment');
				
		$data['text_connect'] = sprintf($this->language->get('text_connect'), $data['client_id'], $data['secret'], $data['merchant_id'], $data['webhook_id'], $data['environment']);

		$data['total'] = $this->config->get('paypal_total');
		$data['geo_zone_id'] = $this->config->get('paypal_geo_zone_id');
		$data['sort_order'] = $this->config->get('paypal_sort_order');
						
		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
										
		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();
		
		$data['cron_url'] = $data['catalog'] . 'index.php?route=extension/payment/paypal&cron_token=' . $data['setting']['general']['cron_token'];
		
		$result = $this->model_extension_payment_paypal->checkVersion(VERSION, $data['setting']['version']);
		
		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}
		
		$agree_status = $this->model_extension_payment_paypal->getAgreeStatus();
		
		if (!$agree_status) {
			$this->error['warning'] = $this->language->get('error_agree');
		}		
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
							
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/payment/paypal/general', $data));
	}
	
	public function button() {
		if (!$this->config->get('paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/payment/paypal', 'token=' . $this->session->data['token'], true));
		}
		
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		$this->document->addStyle('view/stylesheet/paypal/paypal.css');
		$this->document->addStyle('view/stylesheet/paypal/bootstrap-switch.css');
		
		$this->document->addScript('view/javascript/paypal/paypal.js');
		$this->document->addScript('view/javascript/paypal/bootstrap-switch.js');

		$this->document->setTitle($this->language->get('heading_title_main'));
		
		$data['heading_title_main'] = $this->language->get('heading_title_main');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_tab_dashboard'] = $this->language->get('text_tab_dashboard');
		$data['text_tab_general'] = $this->language->get('text_tab_general');
		$data['text_tab_button'] = $this->language->get('text_tab_button');
		$data['text_tab_googlepay_button'] = $this->language->get('text_tab_googlepay_button');
		$data['text_tab_applepay_button'] = $this->language->get('text_tab_applepay_button');
		$data['text_tab_card'] = $this->language->get('text_tab_card');
		$data['text_tab_message_configurator'] = $this->language->get('text_tab_message_configurator');
		$data['text_tab_message_setting'] = $this->language->get('text_tab_message_setting');
		$data['text_tab_order_status'] = $this->language->get('text_tab_order_status');
		$data['text_tab_contact'] = $this->language->get('text_tab_contact');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_cart'] = $this->language->get('text_cart');
		$data['text_step_coupon'] = $this->language->get('text_step_coupon');
		$data['text_step_shipping'] = $this->language->get('text_step_shipping');
		$data['text_step_payment_method'] = $this->language->get('text_step_payment_method');
		$data['text_step_confirm_order'] = $this->language->get('text_step_confirm_order');
		$data['text_product_name'] = $this->language->get('text_product_name');
		$data['text_product_price'] = $this->language->get('text_product_price');
		$data['text_product_manufacturer'] = $this->language->get('text_product_manufacturer');
		$data['text_product_model'] = $this->language->get('text_product_model');
		$data['text_product_stock'] = $this->language->get('text_product_stock');
		$data['text_cart_product_image'] = $this->language->get('text_cart_product_image');
		$data['text_cart_product_name'] = $this->language->get('text_cart_product_name');
		$data['text_cart_product_model'] = $this->language->get('text_cart_product_model');
		$data['text_cart_product_quantity'] = $this->language->get('text_cart_product_quantity');
		$data['text_cart_product_price'] = $this->language->get('text_cart_product_price');
		$data['text_cart_product_total'] = $this->language->get('text_cart_product_total');
		$data['text_cart_product_name_value'] = $this->language->get('text_cart_product_name_value');
		$data['text_cart_product_model_value'] = $this->language->get('text_cart_product_model_value');
		$data['text_cart_product_quantity_value'] = $this->language->get('text_cart_product_quantity_value');
		$data['text_cart_product_price_value'] = $this->language->get('text_cart_product_price_value');
		$data['text_cart_product_total_value'] = $this->language->get('text_cart_product_total_value');
		$data['text_cart_sub_total'] = $this->language->get('text_cart_sub_total');
		$data['text_cart_total'] = $this->language->get('text_cart_total');
		$data['text_button_settings'] = $this->language->get('text_button_settings');
		$data['text_on'] = $this->language->get('text_on');
		$data['text_off'] = $this->language->get('text_off');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_auto'] = $this->language->get('text_auto');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_insert_prepend'] = $this->language->get('text_insert_prepend');
		$data['text_insert_append'] = $this->language->get('text_insert_append');
		$data['text_insert_before'] = $this->language->get('text_insert_before');
		$data['text_insert_after'] = $this->language->get('text_insert_after');	
		$data['text_align_left'] = $this->language->get('text_align_left');
		$data['text_align_center'] = $this->language->get('text_align_center');
		$data['text_align_right'] = $this->language->get('text_align_right');
		$data['text_small'] = $this->language->get('text_small');
		$data['text_medium'] = $this->language->get('text_medium');
		$data['text_large'] = $this->language->get('text_large');
		$data['text_responsive'] = $this->language->get('text_responsive');
		$data['text_gold'] = $this->language->get('text_gold');
		$data['text_blue'] = $this->language->get('text_blue');
		$data['text_silver'] = $this->language->get('text_silver');
		$data['text_white'] = $this->language->get('text_white');
		$data['text_black'] = $this->language->get('text_black');
		$data['text_pill'] = $this->language->get('text_pill');
		$data['text_rect'] = $this->language->get('text_rect');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_pay'] = $this->language->get('text_pay');
		$data['text_buy_now'] = $this->language->get('text_buy_now');
		$data['text_pay_pal'] = $this->language->get('text_pay_pal');
		$data['text_installment'] = $this->language->get('text_installment');
		$data['text_card'] = $this->language->get('text_card');
		$data['text_credit'] = $this->language->get('text_credit');
		$data['text_bancontact'] = $this->language->get('text_bancontact');
		$data['text_blik'] = $this->language->get('text_blik');
		$data['text_eps'] = $this->language->get('text_eps');
		$data['text_giropay'] = $this->language->get('text_giropay');
		$data['text_ideal'] = $this->language->get('text_ideal');
		$data['text_mercadopago'] = $this->language->get('text_mercadopago');
		$data['text_mybank'] = $this->language->get('text_mybank');
		$data['text_p24'] = $this->language->get('text_p24');
		$data['text_sepa'] = $this->language->get('text_sepa');
		$data['text_sofort'] = $this->language->get('text_sofort');
		$data['text_venmo'] = $this->language->get('text_venmo');
		$data['text_paylater'] = $this->language->get('text_paylater');
		$data['text_text'] = $this->language->get('text_text');
		$data['text_flex'] = $this->language->get('text_flex');
		
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_button_insert_tag'] = $this->language->get('entry_button_insert_tag');
		$data['entry_button_insert_type'] = $this->language->get('entry_button_insert_type');
		$data['entry_button_align'] = $this->language->get('entry_button_align');
		$data['entry_button_size'] = $this->language->get('entry_button_size');
		$data['entry_button_color'] = $this->language->get('entry_button_color');
		$data['entry_button_shape'] = $this->language->get('entry_button_shape');
		$data['entry_button_label'] = $this->language->get('entry_button_label');
		$data['entry_button_tagline'] = $this->language->get('entry_button_tagline');
		
		$data['help_button_status'] = $this->language->get('help_button_status');
						
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_all_settings'] = $this->language->get('button_all_settings');
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_checkout'] = $this->language->get('button_checkout');
			
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
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link('extension/payment/paypal', 'token=' . $this->session->data['token'], true)
		);
		
		// Action
		$data['href_dashboard'] = $this->url->link('extension/payment/paypal/dashboard', 'token=' . $this->session->data['token'], true);
		$data['href_general'] = $this->url->link('extension/payment/paypal/general', 'token=' . $this->session->data['token'], true);
		$data['href_button'] = $this->url->link('extension/payment/paypal/button', 'token=' . $this->session->data['token'], true);
		$data['href_googlepay_button'] = $this->url->link('extension/payment/paypal/googlepay_button', 'token=' . $this->session->data['token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'token=' . $this->session->data['token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'token=' . $this->session->data['token'], true);
		$data['href_message_configurator'] = $this->url->link('extension/payment/paypal/message_configurator', 'token=' . $this->session->data['token'], true);
		$data['href_message_setting'] = $this->url->link('extension/payment/paypal/message_setting', 'token=' . $this->session->data['token'], true);
		$data['href_order_status'] = $this->url->link('extension/payment/paypal/order_status', 'token=' . $this->session->data['token'], true);
		$data['href_contact'] = $this->url->link('extension/payment/paypal/contact', 'token=' . $this->session->data['token'], true);
		
		$data['action'] = $this->url->link('extension/payment/paypal/save', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/agree', 'token=' . $this->session->data['token'], true));
						
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['server'] = HTTPS_SERVER;
			$data['catalog'] = HTTPS_CATALOG;
		} else {
			$data['server'] = HTTP_SERVER;
			$data['catalog'] = HTTP_CATALOG;
		}
		
		$_config = new Config();
		$_config->load('paypal');
		
		$data['setting'] = $_config->get('paypal_setting');
		
		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('paypal_setting'));
		
		$data['client_id'] = $this->config->get('paypal_client_id');
		$data['secret'] = $this->config->get('paypal_secret');
		$data['merchant_id'] = $this->config->get('paypal_merchant_id');
		$data['webhook_id'] = $this->config->get('paypal_webhook_id');
		$data['environment'] = $this->config->get('paypal_environment');
		$data['partner_attribution_id'] = $data['setting']['partner'][$data['environment']]['partner_attribution_id'];

		$country = $this->model_extension_payment_paypal->getCountryByCode($data['setting']['general']['country_code']);
		
		$data['locale'] = preg_replace('/-(.+?)+/', '', $this->config->get('config_language')) . '_' . $country['iso_code_2'];
			
		$data['currency_code'] = $data['setting']['general']['currency_code'];
		$data['currency_value'] = $data['setting']['general']['currency_value'];
						
		$data['decimal_place'] = $data['setting']['currency'][$data['currency_code']]['decimal_place'];
				
		if ($data['client_id'] && $data['secret']) {										
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
			
			$paypal_info = array(
				'client_id' => $data['client_id'],
				'secret' => $data['secret'],
				'environment' => $data['environment'],
				'partner_attribution_id' => $data['setting']['partner'][$data['environment']]['partner_attribution_id']
			);
		
			$paypal = new PayPal($paypal_info);
			
			$token_info = array(
				'grant_type' => 'client_credentials'
			);	
				
			$paypal->setAccessToken($token_info);
					
			$data['client_token'] = $paypal->getClientToken();
																	
			if ($paypal->hasErrors()) {
				$error_messages = array();
				
				$errors = $paypal->getErrors();
								
				foreach ($errors as $error) {
					if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
						$error['message'] = $this->language->get('error_timeout');
					}
					
					if (isset($error['details'][0]['description'])) {
						$error_messages[] = $error['details'][0]['description'];
					} elseif (isset($error['message'])) {
						$error_messages[] = $error['message'];
					}
					
					$this->model_extension_payment_paypal->log($error, $error['message']);
				}
				
				$this->error['warning'] = implode(' ', $error_messages);
			}
		}
		
		$result = $this->model_extension_payment_paypal->checkVersion(VERSION, $data['setting']['version']);
		
		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}
		
		$agree_status = $this->model_extension_payment_paypal->getAgreeStatus();
		
		if (!$agree_status) {
			$this->error['warning'] = $this->language->get('error_agree');
		}		
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
											
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/payment/paypal/button', $data));
	}
	
	public function googlepay_button() {
		if (!$this->config->get('paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/payment/paypal', 'token=' . $this->session->data['token'], true));
		}
		
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		$this->document->addStyle('view/stylesheet/paypal/paypal.css');
		$this->document->addStyle('view/stylesheet/paypal/bootstrap-switch.css');
		
		$this->document->addScript('view/javascript/paypal/paypal.js');
		$this->document->addScript('view/javascript/paypal/bootstrap-switch.js');
		$this->document->addScript('https://pay.google.com/gp/p/js/pay.js');

		$this->document->setTitle($this->language->get('heading_title_main'));
		
		$data['heading_title_main'] = $this->language->get('heading_title_main');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_tab_dashboard'] = $this->language->get('text_tab_dashboard');
		$data['text_tab_general'] = $this->language->get('text_tab_general');
		$data['text_tab_button'] = $this->language->get('text_tab_button');
		$data['text_tab_googlepay_button'] = $this->language->get('text_tab_googlepay_button');
		$data['text_tab_applepay_button'] = $this->language->get('text_tab_applepay_button');
		$data['text_tab_card'] = $this->language->get('text_tab_card');
		$data['text_tab_message_configurator'] = $this->language->get('text_tab_message_configurator');
		$data['text_tab_message_setting'] = $this->language->get('text_tab_message_setting');
		$data['text_tab_order_status'] = $this->language->get('text_tab_order_status');
		$data['text_tab_contact'] = $this->language->get('text_tab_contact');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_cart'] = $this->language->get('text_cart');
		$data['text_step_coupon'] = $this->language->get('text_step_coupon');
		$data['text_step_shipping'] = $this->language->get('text_step_shipping');
		$data['text_step_payment_method'] = $this->language->get('text_step_payment_method');
		$data['text_step_confirm_order'] = $this->language->get('text_step_confirm_order');
		$data['text_cart_product_price_value'] = $this->language->get('text_cart_product_price_value');
		$data['text_cart_product_total_value'] = $this->language->get('text_cart_product_total_value');
		$data['text_cart_sub_total'] = $this->language->get('text_cart_sub_total');
		$data['text_cart_total'] = $this->language->get('text_cart_total');
		$data['text_googlepay_button_settings'] = $this->language->get('text_googlepay_button_settings');
		$data['text_on'] = $this->language->get('text_on');
		$data['text_off'] = $this->language->get('text_off');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_auto'] = $this->language->get('text_auto');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_insert_prepend'] = $this->language->get('text_insert_prepend');
		$data['text_insert_append'] = $this->language->get('text_insert_append');
		$data['text_insert_before'] = $this->language->get('text_insert_before');
		$data['text_insert_after'] = $this->language->get('text_insert_after');	
		$data['text_align_left'] = $this->language->get('text_align_left');
		$data['text_align_center'] = $this->language->get('text_align_center');
		$data['text_align_right'] = $this->language->get('text_align_right');
		$data['text_small'] = $this->language->get('text_small');
		$data['text_medium'] = $this->language->get('text_medium');
		$data['text_large'] = $this->language->get('text_large');
		$data['text_responsive'] = $this->language->get('text_responsive');
		$data['text_black'] = $this->language->get('text_black');
		$data['text_white'] = $this->language->get('text_white');
		$data['text_pill'] = $this->language->get('text_pill');
		$data['text_rect'] = $this->language->get('text_rect');
		$data['text_buy'] = $this->language->get('text_buy');
		$data['text_donate'] = $this->language->get('text_donate');
		$data['text_plain'] = $this->language->get('text_plain');
		$data['text_pay'] = $this->language->get('text_pay');
		$data['text_check_out'] = $this->language->get('text_check_out');
						
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_googlepay_button_align'] = $this->language->get('entry_googlepay_button_align');
		$data['entry_googlepay_button_size'] = $this->language->get('entry_googlepay_button_size');
		$data['entry_googlepay_button_color'] = $this->language->get('entry_googlepay_button_color');
		$data['entry_googlepay_button_shape'] = $this->language->get('entry_googlepay_button_shape');
		$data['entry_googlepay_button_type'] = $this->language->get('entry_googlepay_button_type');
				
		$data['help_googlepay_button_status'] = $this->language->get('help_googlepay_button_status');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_checkout'] = $this->language->get('button_checkout');
				
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
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link('extension/payment/paypal', 'token=' . $this->session->data['token'], true)
		);
		
		// Action
		$data['href_dashboard'] = $this->url->link('extension/payment/paypal/dashboard', 'token=' . $this->session->data['token'], true);
		$data['href_general'] = $this->url->link('extension/payment/paypal/general', 'token=' . $this->session->data['token'], true);
		$data['href_button'] = $this->url->link('extension/payment/paypal/button', 'token=' . $this->session->data['token'], true);
		$data['href_googlepay_button'] = $this->url->link('extension/payment/paypal/googlepay_button', 'token=' . $this->session->data['token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'token=' . $this->session->data['token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'token=' . $this->session->data['token'], true);
		$data['href_message_configurator'] = $this->url->link('extension/payment/paypal/message_configurator', 'token=' . $this->session->data['token'], true);
		$data['href_message_setting'] = $this->url->link('extension/payment/paypal/message_setting', 'token=' . $this->session->data['token'], true);
		$data['href_order_status'] = $this->url->link('extension/payment/paypal/order_status', 'token=' . $this->session->data['token'], true);
		$data['href_contact'] = $this->url->link('extension/payment/paypal/contact', 'token=' . $this->session->data['token'], true);
		
		$data['action'] = $this->url->link('extension/payment/paypal/save', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/agree', 'token=' . $this->session->data['token'], true));
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['server'] = HTTPS_SERVER;
			$data['catalog'] = HTTPS_CATALOG;
		} else {
			$data['server'] = HTTP_SERVER;
			$data['catalog'] = HTTP_CATALOG;
		}
		
		$_config = new Config();
		$_config->load('paypal');
		
		$data['setting'] = $_config->get('paypal_setting');
		
		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('paypal_setting'));
		
		$data['client_id'] = $this->config->get('paypal_client_id');
		$data['secret'] = $this->config->get('paypal_secret');
		$data['merchant_id'] = $this->config->get('paypal_merchant_id');
		$data['webhook_id'] = $this->config->get('paypal_webhook_id');
		$data['environment'] = $this->config->get('paypal_environment');
		$data['partner_attribution_id'] = $data['setting']['partner'][$data['environment']]['partner_attribution_id'];

		$country = $this->model_extension_payment_paypal->getCountryByCode($data['setting']['general']['country_code']);
		
		$data['locale'] = preg_replace('/-(.+?)+/', '', $this->config->get('config_language')) . '_' . $country['iso_code_2'];
			
		$data['currency_code'] = $data['setting']['general']['currency_code'];
		$data['currency_value'] = $data['setting']['general']['currency_value'];
						
		$data['decimal_place'] = $data['setting']['currency'][$data['currency_code']]['decimal_place'];
				
		if ($data['client_id'] && $data['secret']) {										
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
			
			$paypal_info = array(
				'client_id' => $data['client_id'],
				'secret' => $data['secret'],
				'environment' => $data['environment'],
				'partner_attribution_id' => $data['setting']['partner'][$data['environment']]['partner_attribution_id']
			);
		
			$paypal = new PayPal($paypal_info);
			
			$token_info = array(
				'grant_type' => 'client_credentials'
			);	
				
			$paypal->setAccessToken($token_info);
		
			$data['client_token'] = $paypal->getClientToken();
														
			if ($paypal->hasErrors()) {
				$error_messages = array();
				
				$errors = $paypal->getErrors();
								
				foreach ($errors as $error) {
					if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
						$error['message'] = $this->language->get('error_timeout');
					}
					
					if (isset($error['details'][0]['description'])) {
						$error_messages[] = $error['details'][0]['description'];
					} elseif (isset($error['message'])) {
						$error_messages[] = $error['message'];
					}
					
					$this->model_extension_payment_paypal->log($error, $error['message']);
				}
				
				$this->error['warning'] = implode(' ', $error_messages);
			}
		}
		
		$result = $this->model_extension_payment_paypal->checkVersion(VERSION, $data['setting']['version']);
		
		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}
		
		$agree_status = $this->model_extension_payment_paypal->getAgreeStatus();
		
		if (!$agree_status) {
			$this->error['warning'] = $this->language->get('error_agree');
		}		
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
											
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/payment/paypal/googlepay_button', $data));
	}
	
	public function applepay_button() {
		if (!$this->config->get('paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/payment/paypal', 'token=' . $this->session->data['token'], true));
		}
		
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		$this->document->addStyle('view/stylesheet/paypal/paypal.css');
		$this->document->addStyle('view/stylesheet/paypal/bootstrap-switch.css');
		
		$this->document->addScript('view/javascript/paypal/paypal.js');
		$this->document->addScript('view/javascript/paypal/bootstrap-switch.js');
		$this->document->addScript('https://applepay.cdn-apple.com/jsapi/v1/apple-pay-sdk.js');

		$this->document->setTitle($this->language->get('heading_title_main'));
		
		$data['heading_title_main'] = $this->language->get('heading_title_main');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_tab_dashboard'] = $this->language->get('text_tab_dashboard');
		$data['text_tab_general'] = $this->language->get('text_tab_general');
		$data['text_tab_button'] = $this->language->get('text_tab_button');
		$data['text_tab_googlepay_button'] = $this->language->get('text_tab_googlepay_button');
		$data['text_tab_applepay_button'] = $this->language->get('text_tab_applepay_button');
		$data['text_tab_card'] = $this->language->get('text_tab_card');
		$data['text_tab_message_configurator'] = $this->language->get('text_tab_message_configurator');
		$data['text_tab_message_setting'] = $this->language->get('text_tab_message_setting');
		$data['text_tab_order_status'] = $this->language->get('text_tab_order_status');
		$data['text_tab_contact'] = $this->language->get('text_tab_contact');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_cart'] = $this->language->get('text_cart');
		$data['text_step_coupon'] = $this->language->get('text_step_coupon');
		$data['text_step_shipping'] = $this->language->get('text_step_shipping');
		$data['text_step_payment_method'] = $this->language->get('text_step_payment_method');
		$data['text_step_confirm_order'] = $this->language->get('text_step_confirm_order');
		$data['text_cart_product_price_value'] = $this->language->get('text_cart_product_price_value');
		$data['text_cart_product_total_value'] = $this->language->get('text_cart_product_total_value');
		$data['text_cart_sub_total'] = $this->language->get('text_cart_sub_total');
		$data['text_cart_total'] = $this->language->get('text_cart_total');
		$data['text_applepay_button_settings'] = $this->language->get('text_applepay_button_settings');
		$data['text_on'] = $this->language->get('text_on');
		$data['text_off'] = $this->language->get('text_off');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_auto'] = $this->language->get('text_auto');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_insert_prepend'] = $this->language->get('text_insert_prepend');
		$data['text_insert_append'] = $this->language->get('text_insert_append');
		$data['text_insert_before'] = $this->language->get('text_insert_before');
		$data['text_insert_after'] = $this->language->get('text_insert_after');	
		$data['text_align_left'] = $this->language->get('text_align_left');
		$data['text_align_center'] = $this->language->get('text_align_center');
		$data['text_align_right'] = $this->language->get('text_align_right');
		$data['text_small'] = $this->language->get('text_small');
		$data['text_medium'] = $this->language->get('text_medium');
		$data['text_large'] = $this->language->get('text_large');
		$data['text_responsive'] = $this->language->get('text_responsive');
		$data['text_black'] = $this->language->get('text_black');
		$data['text_white'] = $this->language->get('text_white');
		$data['text_white_outline'] = $this->language->get('text_white_outline');
		$data['text_pill'] = $this->language->get('text_pill');
		$data['text_rect'] = $this->language->get('text_rect');
		$data['text_buy'] = $this->language->get('text_buy');
		$data['text_donate'] = $this->language->get('text_donate');
		$data['text_plain'] = $this->language->get('text_plain');
		$data['text_check_out'] = $this->language->get('text_check_out');
		$data['text_applepay_alert'] = $this->language->get('text_applepay_alert');
		$data['text_applepay_step_1'] = $this->language->get('text_applepay_step_1');
		$data['text_applepay_step_2'] = $this->language->get('text_applepay_step_2');
				
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_applepay_button_align'] = $this->language->get('entry_applepay_button_align');
		$data['entry_applepay_button_size'] = $this->language->get('entry_applepay_button_size');
		$data['entry_applepay_button_color'] = $this->language->get('entry_applepay_button_color');
		$data['entry_applepay_button_shape'] = $this->language->get('entry_applepay_button_shape');
		$data['entry_applepay_button_type'] = $this->language->get('entry_applepay_button_type');
				
		$data['help_applepay_button_status'] = $this->language->get('help_applepay_button_status');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_download'] = $this->language->get('button_download');
		$data['button_download_host'] = $this->language->get('button_download_host');
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_checkout'] = $this->language->get('button_checkout');
				
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
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link('extension/payment/paypal', 'token=' . $this->session->data['token'], true)
		);
		
		// Action
		$data['href_dashboard'] = $this->url->link('extension/payment/paypal/dashboard', 'token=' . $this->session->data['token'], true);
		$data['href_general'] = $this->url->link('extension/payment/paypal/general', 'token=' . $this->session->data['token'], true);
		$data['href_button'] = $this->url->link('extension/payment/paypal/button', 'token=' . $this->session->data['token'], true);
		$data['href_googlepay_button'] = $this->url->link('extension/payment/paypal/googlepay_button', 'token=' . $this->session->data['token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'token=' . $this->session->data['token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'token=' . $this->session->data['token'], true);
		$data['href_message_configurator'] = $this->url->link('extension/payment/paypal/message_configurator', 'token=' . $this->session->data['token'], true);
		$data['href_message_setting'] = $this->url->link('extension/payment/paypal/message_setting', 'token=' . $this->session->data['token'], true);
		$data['href_order_status'] = $this->url->link('extension/payment/paypal/order_status', 'token=' . $this->session->data['token'], true);
		$data['href_contact'] = $this->url->link('extension/payment/paypal/contact', 'token=' . $this->session->data['token'], true);
		
		$data['action'] = $this->url->link('extension/payment/paypal/save', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);
		$data['applepay_download_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/downloadAssociationFile', 'token=' . $this->session->data['token'], true));
		$data['applepay_download_host_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/downloadHostAssociationFile', 'token=' . $this->session->data['token'], true));
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/agree', 'token=' . $this->session->data['token'], true));
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['server'] = HTTPS_SERVER;
			$data['catalog'] = HTTPS_CATALOG;
		} else {
			$data['server'] = HTTP_SERVER;
			$data['catalog'] = HTTP_CATALOG;
		}
			
		$_config = new Config();
		$_config->load('paypal');
		
		$data['setting'] = $_config->get('paypal_setting');
		
		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('paypal_setting'));
		
		$data['client_id'] = $this->config->get('paypal_client_id');
		$data['secret'] = $this->config->get('paypal_secret');
		$data['merchant_id'] = $this->config->get('paypal_merchant_id');
		$data['webhook_id'] = $this->config->get('paypal_webhook_id');
		$data['environment'] = $this->config->get('paypal_environment');
		$data['partner_attribution_id'] = $data['setting']['partner'][$data['environment']]['partner_attribution_id'];

		$country = $this->model_extension_payment_paypal->getCountryByCode($data['setting']['general']['country_code']);
		
		$data['locale'] = preg_replace('/-(.+?)+/', '', $this->config->get('config_language')) . '_' . $country['iso_code_2'];
			
		$data['currency_code'] = $data['setting']['general']['currency_code'];
		$data['currency_value'] = $data['setting']['general']['currency_value'];
						
		$data['decimal_place'] = $data['setting']['currency'][$data['currency_code']]['decimal_place'];
				
		if ($data['client_id'] && $data['secret']) {										
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
			
			$paypal_info = array(
				'client_id' => $data['client_id'],
				'secret' => $data['secret'],
				'environment' => $data['environment'],
				'partner_attribution_id' => $data['setting']['partner'][$data['environment']]['partner_attribution_id']
			);
		
			$paypal = new PayPal($paypal_info);
			
			$token_info = array(
				'grant_type' => 'client_credentials'
			);	
				
			$paypal->setAccessToken($token_info);
		
			$data['client_token'] = $paypal->getClientToken();
														
			if ($paypal->hasErrors()) {
				$error_messages = array();
				
				$errors = $paypal->getErrors();
								
				foreach ($errors as $error) {
					if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
						$error['message'] = $this->language->get('error_timeout');
					}
					
					if (isset($error['details'][0]['description'])) {
						$error_messages[] = $error['details'][0]['description'];
					} elseif (isset($error['message'])) {
						$error_messages[] = $error['message'];
					}
					
					$this->model_extension_payment_paypal->log($error, $error['message']);
				}
				
				$this->error['warning'] = implode(' ', $error_messages);
			}
		}
		
		$result = $this->model_extension_payment_paypal->checkVersion(VERSION, $data['setting']['version']);
		
		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}
		
		$agree_status = $this->model_extension_payment_paypal->getAgreeStatus();
		
		if (!$agree_status) {
			$this->error['warning'] = $this->language->get('error_agree');
		}		
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
											
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/payment/paypal/applepay_button', $data));
	}
	
	public function card() {
		if (!$this->config->get('paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/payment/paypal', 'token=' . $this->session->data['token'], true));
		}
		
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		$this->document->addStyle('view/stylesheet/paypal/paypal.css');
		$this->document->addStyle('view/stylesheet/paypal/card.css');
		$this->document->addStyle('view/stylesheet/paypal/bootstrap-switch.css');
		
		$this->document->addScript('view/javascript/paypal/paypal.js');
		$this->document->addScript('view/javascript/paypal/bootstrap-switch.js');

		$this->document->setTitle($this->language->get('heading_title_main'));
		
		$data['heading_title_main'] = $this->language->get('heading_title_main');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_tab_dashboard'] = $this->language->get('text_tab_dashboard');
		$data['text_tab_general'] = $this->language->get('text_tab_general');
		$data['text_tab_button'] = $this->language->get('text_tab_button');
		$data['text_tab_googlepay_button'] = $this->language->get('text_tab_googlepay_button');
		$data['text_tab_applepay_button'] = $this->language->get('text_tab_applepay_button');
		$data['text_tab_card'] = $this->language->get('text_tab_card');
		$data['text_tab_message_configurator'] = $this->language->get('text_tab_message_configurator');
		$data['text_tab_message_setting'] = $this->language->get('text_tab_message_setting');
		$data['text_tab_order_status'] = $this->language->get('text_tab_order_status');
		$data['text_tab_contact'] = $this->language->get('text_tab_contact');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_cart'] = $this->language->get('text_cart');
		$data['text_step_coupon'] = $this->language->get('text_step_coupon');
		$data['text_step_shipping'] = $this->language->get('text_step_shipping');
		$data['text_step_payment_method'] = $this->language->get('text_step_payment_method');		
		$data['text_step_confirm_order'] = $this->language->get('text_step_confirm_order');
		$data['text_cart_product_price_value'] = $this->language->get('text_cart_product_price_value');
		$data['text_cart_product_total_value'] = $this->language->get('text_cart_product_total_value');
		$data['text_cart_sub_total'] = $this->language->get('text_cart_sub_total');
		$data['text_cart_total'] = $this->language->get('text_cart_total');
		$data['text_card_settings'] = $this->language->get('text_card_settings');
		$data['text_on'] = $this->language->get('text_on');
		$data['text_off'] = $this->language->get('text_off');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_auto'] = $this->language->get('text_auto');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_align_left'] = $this->language->get('text_align_left');
		$data['text_align_center'] = $this->language->get('text_align_center');
		$data['text_align_right'] = $this->language->get('text_align_right');
		$data['text_small'] = $this->language->get('text_small');
		$data['text_medium'] = $this->language->get('text_medium');
		$data['text_large'] = $this->language->get('text_large');
		$data['text_responsive'] = $this->language->get('text_responsive');
		$data['text_accept'] = $this->language->get('text_accept');
		$data['text_decline'] = $this->language->get('text_decline');
		$data['text_sca_when_required'] = $this->language->get('text_sca_when_required');
		$data['text_sca_always'] = $this->language->get('text_sca_always');
		$data['text_recommended'] = $this->language->get('text_recommended');
		$data['text_3ds_failed_authentication'] = $this->language->get('text_3ds_failed_authentication');
		$data['text_3ds_rejected_authentication'] = $this->language->get('text_3ds_rejected_authentication');
		$data['text_3ds_attempted_authentication'] = $this->language->get('text_3ds_attempted_authentication');
		$data['text_3ds_unable_authentication'] = $this->language->get('text_3ds_unable_authentication');
		$data['text_3ds_challenge_authentication'] = $this->language->get('text_3ds_challenge_authentication');
		$data['text_3ds_card_ineligible'] = $this->language->get('text_3ds_card_ineligible');
		$data['text_3ds_system_unavailable'] = $this->language->get('text_3ds_system_unavailable');
		$data['text_3ds_system_bypassed'] = $this->language->get('text_3ds_system_bypassed');
				
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_card_align'] = $this->language->get('entry_card_align');
		$data['entry_card_size'] = $this->language->get('entry_card_size');
		$data['entry_card_secure_method'] = $this->language->get('entry_card_secure_method');
		$data['entry_card_secure_scenario'] = $this->language->get('entry_card_secure_scenario');
		$data['entry_card_number'] = $this->language->get('entry_card_number');
		$data['entry_expiration_date'] = $this->language->get('entry_expiration_date');
		$data['entry_cvv'] = $this->language->get('entry_cvv');
				
		$data['help_card_status'] = $this->language->get('help_card_status');
		$data['help_card_secure_method'] = $this->language->get('help_card_secure_method');
		$data['help_card_secure_scenario'] = $this->language->get('help_card_secure_scenario');
						
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_all_settings'] = $this->language->get('button_all_settings');
		$data['button_checkout'] = $this->language->get('button_checkout');
		$data['button_pay'] = $this->language->get('button_pay');
				
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
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link('extension/payment/paypal', 'token=' . $this->session->data['token'], true)
		);
		
		// Action
		$data['href_dashboard'] = $this->url->link('extension/payment/paypal/dashboard', 'token=' . $this->session->data['token'], true);
		$data['href_general'] = $this->url->link('extension/payment/paypal/general', 'token=' . $this->session->data['token'], true);
		$data['href_button'] = $this->url->link('extension/payment/paypal/button', 'token=' . $this->session->data['token'], true);
		$data['href_googlepay_button'] = $this->url->link('extension/payment/paypal/googlepay_button', 'token=' . $this->session->data['token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'token=' . $this->session->data['token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'token=' . $this->session->data['token'], true);
		$data['href_message_configurator'] = $this->url->link('extension/payment/paypal/message_configurator', 'token=' . $this->session->data['token'], true);
		$data['href_message_setting'] = $this->url->link('extension/payment/paypal/message_setting', 'token=' . $this->session->data['token'], true);
		$data['href_order_status'] = $this->url->link('extension/payment/paypal/order_status', 'token=' . $this->session->data['token'], true);
		$data['href_contact'] = $this->url->link('extension/payment/paypal/contact', 'token=' . $this->session->data['token'], true);
		
		$data['action'] = $this->url->link('extension/payment/paypal/save', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/agree', 'token=' . $this->session->data['token'], true));
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['server'] = HTTPS_SERVER;
			$data['catalog'] = HTTPS_CATALOG;
		} else {
			$data['server'] = HTTP_SERVER;
			$data['catalog'] = HTTP_CATALOG;
		}
		
		$_config = new Config();
		$_config->load('paypal');
		
		$data['setting'] = $_config->get('paypal_setting');
		
		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('paypal_setting'));
		
		$data['client_id'] = $this->config->get('paypal_client_id');
		$data['secret'] = $this->config->get('paypal_secret');
		$data['merchant_id'] = $this->config->get('paypal_merchant_id');
		$data['webhook_id'] = $this->config->get('paypal_webhook_id');
		$data['environment'] = $this->config->get('paypal_environment');
		$data['partner_attribution_id'] = $data['setting']['partner'][$data['environment']]['partner_attribution_id'];

		$country = $this->model_extension_payment_paypal->getCountryByCode($data['setting']['general']['country_code']);
		
		$data['locale'] = preg_replace('/-(.+?)+/', '', $this->config->get('config_language')) . '_' . $country['iso_code_2'];
			
		$data['currency_code'] = $data['setting']['general']['currency_code'];
		$data['currency_value'] = $data['setting']['general']['currency_value'];
						
		$data['decimal_place'] = $data['setting']['currency'][$data['currency_code']]['decimal_place'];
				
		if ($data['client_id'] && $data['secret']) {										
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
			
			$paypal_info = array(
				'client_id' => $data['client_id'],
				'secret' => $data['secret'],
				'environment' => $data['environment'],
				'partner_attribution_id' => $data['setting']['partner'][$data['environment']]['partner_attribution_id']
			);
		
			$paypal = new PayPal($paypal_info);
			
			$token_info = array(
				'grant_type' => 'client_credentials'
			);	
				
			$paypal->setAccessToken($token_info);
		
			$data['client_token'] = $paypal->getClientToken();
														
			if ($paypal->hasErrors()) {
				$error_messages = array();
				
				$errors = $paypal->getErrors();
								
				foreach ($errors as $error) {
					if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
						$error['message'] = $this->language->get('error_timeout');
					}
					
					if (isset($error['details'][0]['description'])) {
						$error_messages[] = $error['details'][0]['description'];
					} elseif (isset($error['message'])) {
						$error_messages[] = $error['message'];
					}
					
					$this->model_extension_payment_paypal->log($error, $error['message']);
				}
				
				$this->error['warning'] = implode(' ', $error_messages);
			}
		}
		
		$result = $this->model_extension_payment_paypal->checkVersion(VERSION, $data['setting']['version']);
		
		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}
		
		$agree_status = $this->model_extension_payment_paypal->getAgreeStatus();
		
		if (!$agree_status) {
			$this->error['warning'] = $this->language->get('error_agree');
		}		
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
											
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/payment/paypal/card', $data));
	}
	
	public function message_configurator() {
		if (!$this->config->get('paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/payment/paypal', 'token=' . $this->session->data['token'], true));
		}
		
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		$this->document->addStyle('view/stylesheet/paypal/paypal.css');
		$this->document->addStyle('view/stylesheet/paypal/bootstrap-switch.css');
		
		$this->document->addScript('view/javascript/paypal/paypal.js');
		$this->document->addScript('view/javascript/paypal/bootstrap-switch.js');
		//$this->document->addScript('https://www.paypalobjects.com/merchant-library/merchant-configurator.js');
		$this->document->addScript('https://www.paypalobjects.com/merchant-library/preview/merchant-configurator.js');

		$this->document->setTitle($this->language->get('heading_title_main'));
		
		$data['heading_title_main'] = $this->language->get('heading_title_main');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_tab_dashboard'] = $this->language->get('text_tab_dashboard');
		$data['text_tab_general'] = $this->language->get('text_tab_general');
		$data['text_tab_button'] = $this->language->get('text_tab_button');
		$data['text_tab_googlepay_button'] = $this->language->get('text_tab_googlepay_button');
		$data['text_tab_applepay_button'] = $this->language->get('text_tab_applepay_button');
		$data['text_tab_card'] = $this->language->get('text_tab_card');
		$data['text_tab_message_configurator'] = $this->language->get('text_tab_message_configurator');
		$data['text_tab_message_setting'] = $this->language->get('text_tab_message_setting');
		$data['text_tab_order_status'] = $this->language->get('text_tab_order_status');
		$data['text_tab_contact'] = $this->language->get('text_tab_contact');
												
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_all_settings'] = $this->language->get('button_all_settings');
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_checkout'] = $this->language->get('button_checkout');
						
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
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link('extension/payment/paypal', 'token=' . $this->session->data['token'], true)
		);
		
		// Action
		$data['href_dashboard'] = $this->url->link('extension/payment/paypal/dashboard', 'token=' . $this->session->data['token'], true);
		$data['href_general'] = $this->url->link('extension/payment/paypal/general', 'token=' . $this->session->data['token'], true);
		$data['href_button'] = $this->url->link('extension/payment/paypal/button', 'token=' . $this->session->data['token'], true);
		$data['href_googlepay_button'] = $this->url->link('extension/payment/paypal/googlepay_button', 'token=' . $this->session->data['token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'token=' . $this->session->data['token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'token=' . $this->session->data['token'], true);
		$data['href_message_configurator'] = $this->url->link('extension/payment/paypal/message_configurator', 'token=' . $this->session->data['token'], true);
		$data['href_message_setting'] = $this->url->link('extension/payment/paypal/message_setting', 'token=' . $this->session->data['token'], true);
		$data['href_order_status'] = $this->url->link('extension/payment/paypal/order_status', 'token=' . $this->session->data['token'], true);
		$data['href_contact'] = $this->url->link('extension/payment/paypal/contact', 'token=' . $this->session->data['token'], true);
		
		$data['action'] = $this->url->link('extension/payment/paypal/save', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/agree', 'token=' . $this->session->data['token'], true));
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['server'] = HTTPS_SERVER;
			$data['catalog'] = HTTPS_CATALOG;
		} else {
			$data['server'] = HTTP_SERVER;
			$data['catalog'] = HTTP_CATALOG;
		}
		
		$_config = new Config();
		$_config->load('paypal');
		
		$data['setting'] = $_config->get('paypal_setting');
		
		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('paypal_setting'));
		
		$data['client_id'] = $this->config->get('paypal_client_id');
		$data['secret'] = $this->config->get('paypal_secret');
		$data['merchant_id'] = $this->config->get('paypal_merchant_id');
		$data['webhook_id'] = $this->config->get('paypal_webhook_id');
		$data['environment'] = $this->config->get('paypal_environment');
		$data['partner_client_id'] = $data['setting']['partner'][$data['environment']]['client_id'];
		$data['partner_attribution_id'] = $data['setting']['partner'][$data['environment']]['partner_attribution_id'];
		
		$country = $this->model_extension_payment_paypal->getCountryByCode($data['setting']['general']['country_code']);
		
		$data['locale'] = preg_replace('/-(.+?)+/', '', $this->config->get('config_language')) . '_' . $country['iso_code_2'];
			
		$data['currency_code'] = $data['setting']['general']['currency_code'];
		$data['currency_value'] = $data['setting']['general']['currency_value'];
						
		$data['decimal_place'] = $data['setting']['currency'][$data['currency_code']]['decimal_place'];
								
		if ($data['client_id'] && $data['secret']) {										
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
			
			$paypal_info = array(
				'client_id' => $data['client_id'],
				'secret' => $data['secret'],
				'environment' => $data['environment'],
				'partner_attribution_id' => $data['setting']['partner'][$data['environment']]['partner_attribution_id']
			);
		
			$paypal = new PayPal($paypal_info);
			
			$token_info = array(
				'grant_type' => 'client_credentials'
			);	
				
			$paypal->setAccessToken($token_info);
		
			$data['client_token'] = $paypal->getClientToken();
														
			if ($paypal->hasErrors()) {
				$error_messages = array();
				
				$errors = $paypal->getErrors();
								
				foreach ($errors as $error) {
					if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
						$error['message'] = $this->language->get('error_timeout');
					}
					
					if (isset($error['details'][0]['description'])) {
						$error_messages[] = $error['details'][0]['description'];
					} elseif (isset($error['message'])) {
						$error_messages[] = $error['message'];
					}
					
					$this->model_extension_payment_paypal->log($error, $error['message']);
				}
				
				$this->error['warning'] = implode(' ', $error_messages);
			}
		}
		
		$result = $this->model_extension_payment_paypal->checkVersion(VERSION, $data['setting']['version']);
		
		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}
		
		$agree_status = $this->model_extension_payment_paypal->getAgreeStatus();
		
		if (!$agree_status) {
			$this->error['warning'] = $this->language->get('error_agree');
		}		
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
											
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/payment/paypal/message_configurator', $data));
	}
	
	public function message_setting() {
		if (!$this->config->get('paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/payment/paypal', 'token=' . $this->session->data['token'], true));
		}
		
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		$this->document->addStyle('view/stylesheet/paypal/paypal.css');
		$this->document->addStyle('view/stylesheet/paypal/bootstrap-switch.css');
		
		$this->document->addScript('view/javascript/paypal/paypal.js');
		$this->document->addScript('view/javascript/paypal/bootstrap-switch.js');
		
		$this->document->setTitle($this->language->get('heading_title_main'));
		
		$data['heading_title_main'] = $this->language->get('heading_title_main');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_tab_dashboard'] = $this->language->get('text_tab_dashboard');
		$data['text_tab_general'] = $this->language->get('text_tab_general');
		$data['text_tab_button'] = $this->language->get('text_tab_button');
		$data['text_tab_googlepay_button'] = $this->language->get('text_tab_googlepay_button');
		$data['text_tab_applepay_button'] = $this->language->get('text_tab_applepay_button');
		$data['text_tab_card'] = $this->language->get('text_tab_card');
		$data['text_tab_message_configurator'] = $this->language->get('text_tab_message_configurator');
		$data['text_tab_message_setting'] = $this->language->get('text_tab_message_setting');
		$data['text_tab_order_status'] = $this->language->get('text_tab_order_status');
		$data['text_tab_contact'] = $this->language->get('text_tab_contact');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_home'] = $this->language->get('text_home');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_cart'] = $this->language->get('text_cart');
		$data['text_message_settings'] = $this->language->get('text_message_settings');
		$data['text_on'] = $this->language->get('text_on');
		$data['text_off'] = $this->language->get('text_off');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_auto'] = $this->language->get('text_auto');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_insert_prepend'] = $this->language->get('text_insert_prepend');
		$data['text_insert_append'] = $this->language->get('text_insert_append');
		$data['text_insert_before'] = $this->language->get('text_insert_before');
		$data['text_insert_after'] = $this->language->get('text_insert_after');	
				
		$data['entry_message_insert_tag'] = $this->language->get('entry_message_insert_tag');
		$data['entry_message_insert_type'] = $this->language->get('entry_message_insert_type');
				
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_all_settings'] = $this->language->get('button_all_settings');
								
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
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link('extension/payment/paypal', 'token=' . $this->session->data['token'], true)
		);
		
		// Action
		$data['href_dashboard'] = $this->url->link('extension/payment/paypal/dashboard', 'token=' . $this->session->data['token'], true);
		$data['href_general'] = $this->url->link('extension/payment/paypal/general', 'token=' . $this->session->data['token'], true);
		$data['href_button'] = $this->url->link('extension/payment/paypal/button', 'token=' . $this->session->data['token'], true);
		$data['href_googlepay_button'] = $this->url->link('extension/payment/paypal/googlepay_button', 'token=' . $this->session->data['token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'token=' . $this->session->data['token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'token=' . $this->session->data['token'], true);
		$data['href_message_configurator'] = $this->url->link('extension/payment/paypal/message_configurator', 'token=' . $this->session->data['token'], true);
		$data['href_message_setting'] = $this->url->link('extension/payment/paypal/message_setting', 'token=' . $this->session->data['token'], true);
		$data['href_order_status'] = $this->url->link('extension/payment/paypal/order_status', 'token=' . $this->session->data['token'], true);
		$data['href_contact'] = $this->url->link('extension/payment/paypal/contact', 'token=' . $this->session->data['token'], true);
		
		$data['action'] = $this->url->link('extension/payment/paypal/save', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/agree', 'token=' . $this->session->data['token'], true));
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['server'] = HTTPS_SERVER;
			$data['catalog'] = HTTPS_CATALOG;
		} else {
			$data['server'] = HTTP_SERVER;
			$data['catalog'] = HTTP_CATALOG;
		}
		
		$_config = new Config();
		$_config->load('paypal');
		
		$data['setting'] = $_config->get('paypal_setting');
		
		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('paypal_setting'));
		
		$data['client_id'] = $this->config->get('paypal_client_id');
		$data['secret'] = $this->config->get('paypal_secret');
		$data['merchant_id'] = $this->config->get('paypal_merchant_id');
		$data['webhook_id'] = $this->config->get('paypal_webhook_id');
		$data['environment'] = $this->config->get('paypal_environment');
		$data['partner_client_id'] = $data['setting']['partner'][$data['environment']]['client_id'];
		$data['partner_attribution_id'] = $data['setting']['partner'][$data['environment']]['partner_attribution_id'];
		
		$country = $this->model_extension_payment_paypal->getCountryByCode($data['setting']['general']['country_code']);
		
		$data['locale'] = preg_replace('/-(.+?)+/', '', $this->config->get('config_language')) . '_' . $country['iso_code_2'];
			
		$data['currency_code'] = $data['setting']['general']['currency_code'];
		$data['currency_value'] = $data['setting']['general']['currency_value'];
						
		$data['decimal_place'] = $data['setting']['currency'][$data['currency_code']]['decimal_place'];
		
		if ($country['iso_code_2'] == 'GB') {
			$data['text_message_alert'] = $this->language->get('text_message_alert_uk');
			$data['text_message_footnote'] = $this->language->get('text_message_footnote_uk');
		} elseif ($country['iso_code_2'] == 'US') {
			$data['text_message_alert'] = $this->language->get('text_message_alert_us');
			$data['text_message_footnote'] = $this->language->get('text_message_footnote_us');
		}
						
		if ($data['client_id'] && $data['secret']) {										
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
			
			$paypal_info = array(
				'client_id' => $data['client_id'],
				'secret' => $data['secret'],
				'environment' => $data['environment'],
				'partner_attribution_id' => $data['setting']['partner'][$data['environment']]['partner_attribution_id']
			);
		
			$paypal = new PayPal($paypal_info);
			
			$token_info = array(
				'grant_type' => 'client_credentials'
			);	
				
			$paypal->setAccessToken($token_info);
		
			$data['client_token'] = $paypal->getClientToken();
														
			if ($paypal->hasErrors()) {
				$error_messages = array();
				
				$errors = $paypal->getErrors();
								
				foreach ($errors as $error) {
					if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
						$error['message'] = $this->language->get('error_timeout');
					}
					
					if (isset($error['details'][0]['description'])) {
						$error_messages[] = $error['details'][0]['description'];
					} elseif (isset($error['message'])) {
						$error_messages[] = $error['message'];
					}
					
					$this->model_extension_payment_paypal->log($error, $error['message']);
				}
				
				$this->error['warning'] = implode(' ', $error_messages);
			}
		}
		
		$result = $this->model_extension_payment_paypal->checkVersion(VERSION, $data['setting']['version']);
		
		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}
		
		$agree_status = $this->model_extension_payment_paypal->getAgreeStatus();
		
		if (!$agree_status) {
			$this->error['warning'] = $this->language->get('error_agree');
		}		
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
											
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/payment/paypal/message_setting', $data));
	}
	
	public function order_status() {
		if (!$this->config->get('paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/payment/paypal', 'token=' . $this->session->data['token'], true));
		}
		
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		$this->document->addStyle('view/stylesheet/paypal/paypal.css');

		$this->document->setTitle($this->language->get('heading_title_main'));
		
		$data['heading_title_main'] = $this->language->get('heading_title_main');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_tab_dashboard'] = $this->language->get('text_tab_dashboard');
		$data['text_tab_general'] = $this->language->get('text_tab_general');
		$data['text_tab_button'] = $this->language->get('text_tab_button');
		$data['text_tab_googlepay_button'] = $this->language->get('text_tab_googlepay_button');
		$data['text_tab_applepay_button'] = $this->language->get('text_tab_applepay_button');
		$data['text_tab_card'] = $this->language->get('text_tab_card');
		$data['text_tab_message_configurator'] = $this->language->get('text_tab_message_configurator');
		$data['text_tab_message_setting'] = $this->language->get('text_tab_message_setting');
		$data['text_tab_order_status'] = $this->language->get('text_tab_order_status');
		$data['text_tab_contact'] = $this->language->get('text_tab_contact');
		$data['text_completed_status'] = $this->language->get('text_completed_status');
		$data['text_denied_status'] = $this->language->get('text_denied_status');
		$data['text_failed_status'] = $this->language->get('text_failed_status');
		$data['text_pending_status'] = $this->language->get('text_pending_status');
		$data['text_refunded_status'] = $this->language->get('text_refunded_status');
		$data['text_reversed_status'] = $this->language->get('text_reversed_status');
		$data['text_voided_status'] = $this->language->get('text_voided_status');
		
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
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link('extension/payment/paypal', 'token=' . $this->session->data['token'], true)
		);
		
		// Action
		$data['href_dashboard'] = $this->url->link('extension/payment/paypal/dashboard', 'token=' . $this->session->data['token'], true);
		$data['href_general'] = $this->url->link('extension/payment/paypal/general', 'token=' . $this->session->data['token'], true);
		$data['href_button'] = $this->url->link('extension/payment/paypal/button', 'token=' . $this->session->data['token'], true);
		$data['href_googlepay_button'] = $this->url->link('extension/payment/paypal/googlepay_button', 'token=' . $this->session->data['token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'token=' . $this->session->data['token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'token=' . $this->session->data['token'], true);
		$data['href_message_configurator'] = $this->url->link('extension/payment/paypal/message_configurator', 'token=' . $this->session->data['token'], true);
		$data['href_message_setting'] = $this->url->link('extension/payment/paypal/message_setting', 'token=' . $this->session->data['token'], true);
		$data['href_order_status'] = $this->url->link('extension/payment/paypal/order_status', 'token=' . $this->session->data['token'], true);
		$data['href_contact'] = $this->url->link('extension/payment/paypal/contact', 'token=' . $this->session->data['token'], true);
		
		$data['action'] = $this->url->link('extension/payment/paypal/save', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/agree', 'token=' . $this->session->data['token'], true));
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['server'] = HTTPS_SERVER;
			$data['catalog'] = HTTPS_CATALOG;
		} else {
			$data['server'] = HTTP_SERVER;
			$data['catalog'] = HTTP_CATALOG;
		}
		
		$_config = new Config();
		$_config->load('paypal');
		
		$data['setting'] = $_config->get('paypal_setting');
		
		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('paypal_setting'));
		
		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$result = $this->model_extension_payment_paypal->checkVersion(VERSION, $data['setting']['version']);
		
		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}

		$agree_status = $this->model_extension_payment_paypal->getAgreeStatus();
		
		if (!$agree_status) {
			$this->error['warning'] = $this->language->get('error_agree');
		}		
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}		
									
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/payment/paypal/order_status', $data));
	}
	
	public function contact() {
		if (!$this->config->get('paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/payment/paypal', 'token=' . $this->session->data['token'], true));
		}
		
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		$this->document->addStyle('view/stylesheet/paypal/paypal.css');

		$this->document->setTitle($this->language->get('heading_title_main'));
		
		$data['heading_title_main'] = $this->language->get('heading_title_main');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_tab_dashboard'] = $this->language->get('text_tab_dashboard');
		$data['text_tab_general'] = $this->language->get('text_tab_general');
		$data['text_tab_button'] = $this->language->get('text_tab_button');
		$data['text_tab_googlepay_button'] = $this->language->get('text_tab_googlepay_button');
		$data['text_tab_applepay_button'] = $this->language->get('text_tab_applepay_button');
		$data['text_tab_card'] = $this->language->get('text_tab_card');
		$data['text_tab_message_configurator'] = $this->language->get('text_tab_message_configurator');
		$data['text_tab_message_setting'] = $this->language->get('text_tab_message_setting');
		$data['text_tab_order_status'] = $this->language->get('text_tab_order_status');
		$data['text_tab_contact'] = $this->language->get('text_tab_contact');
		$data['text_contact_business'] = $this->language->get('text_contact_business');
		$data['text_contact_product'] = $this->language->get('text_contact_product');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_bt_dcc'] = $this->language->get('text_bt_dcc');
		$data['text_express_checkout'] = $this->language->get('text_express_checkout');
		$data['text_credit_installments'] = $this->language->get('text_credit_installments');
		$data['text_point_of_sale'] = $this->language->get('text_point_of_sale');
		$data['text_invoicing_api'] = $this->language->get('text_invoicing_api');
		$data['text_paypal_working_capital'] = $this->language->get('text_paypal_working_capital');
		$data['text_risk_servicing'] = $this->language->get('text_risk_servicing');
		$data['text_paypal_here'] = $this->language->get('text_paypal_here');
		$data['text_payouts'] = $this->language->get('text_payouts');
		$data['text_marketing_solutions'] = $this->language->get('text_marketing_solutions');
		
		$data['entry_contact_company'] = $this->language->get('entry_contact_company');
		$data['entry_contact_first_name'] = $this->language->get('entry_contact_first_name');
		$data['entry_contact_last_name'] = $this->language->get('entry_contact_last_name');
		$data['entry_contact_email'] = $this->language->get('entry_contact_email');
		$data['entry_contact_url'] = $this->language->get('entry_contact_url');
		$data['entry_contact_sales'] = $this->language->get('entry_contact_sales');
		$data['entry_contact_phone'] = $this->language->get('entry_contact_phone');
		$data['entry_contact_country'] = $this->language->get('entry_contact_country');
		$data['entry_contact_notes'] = $this->language->get('entry_contact_notes');
		$data['entry_contact_merchant'] = $this->language->get('entry_contact_merchant');
		$data['entry_contact_merchant_name'] = $this->language->get('entry_contact_merchant_name');
		$data['entry_contact_product'] = $this->language->get('entry_contact_product');
		$data['entry_contact_send'] = $this->language->get('entry_contact_send');
				
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_send'] = $this->language->get('button_send');
			
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
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link('extension/payment/paypal', 'token=' . $this->session->data['token'], true)
		);
		
		// Action
		$data['href_dashboard'] = $this->url->link('extension/payment/paypal/dashboard', 'token=' . $this->session->data['token'], true);
		$data['href_general'] = $this->url->link('extension/payment/paypal/general', 'token=' . $this->session->data['token'], true);
		$data['href_button'] = $this->url->link('extension/payment/paypal/button', 'token=' . $this->session->data['token'], true);
		$data['href_googlepay_button'] = $this->url->link('extension/payment/paypal/googlepay_button', 'token=' . $this->session->data['token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'token=' . $this->session->data['token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'token=' . $this->session->data['token'], true);
		$data['href_message_configurator'] = $this->url->link('extension/payment/paypal/message_configurator', 'token=' . $this->session->data['token'], true);
		$data['href_message_setting'] = $this->url->link('extension/payment/paypal/message_setting', 'token=' . $this->session->data['token'], true);
		$data['href_order_status'] = $this->url->link('extension/payment/paypal/order_status', 'token=' . $this->session->data['token'], true);
		$data['href_contact'] = $this->url->link('extension/payment/paypal/contact', 'token=' . $this->session->data['token'], true);
		
		$data['action'] = $this->url->link('extension/payment/paypal/save', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);
		$data['contact_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/sendContact', 'token=' . $this->session->data['token'], true));
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/agree', 'token=' . $this->session->data['token'], true));
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['server'] = HTTPS_SERVER;
			$data['catalog'] = HTTPS_CATALOG;
		} else {
			$data['server'] = HTTP_SERVER;
			$data['catalog'] = HTTP_CATALOG;
		}
		
		$_config = new Config();
		$_config->load('paypal');
		
		$data['setting'] = $_config->get('paypal_setting');
		
		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('paypal_setting'));
		
		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();
		
		$result = $this->model_extension_payment_paypal->checkVersion(VERSION, $data['setting']['version']);
		
		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}
		
		$agree_status = $this->model_extension_payment_paypal->getAgreeStatus();
		
		if (!$agree_status) {
			$this->error['warning'] = $this->language->get('error_agree');
		}		
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
											
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/payment/paypal/contact', $data));
	}
	
	public function save() {
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('setting/setting');
						
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$setting = $this->model_setting_setting->getSetting('paypal');
			
			$setting = array_replace_recursive($setting, $this->request->post);
						
			$this->model_setting_setting->editSetting('paypal', $setting);
														
			$data['success'] = $this->language->get('success_save');
		}
		
		$data['error'] = $this->error;
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));	
	}
	
	public function disconnect() {
		$this->load->model('setting/setting');
		
		$setting = $this->model_setting_setting->getSetting('paypal');
						
		$setting['paypal_client_id'] = '';
		$setting['paypal_secret'] = '';
		$setting['paypal_merchant_id'] = '';
		$setting['paypal_webhook_id'] = '';
		
		$this->model_setting_setting->editSetting('paypal', $setting);
		
		$data['error'] = $this->error;
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
		
	public function callback() {
		if (isset($this->request->post['environment']) && isset($this->request->post['authorization_code']) && isset($this->request->post['shared_id']) && isset($this->request->post['seller_nonce'])) {
			$cache_data['environment'] = $this->request->post['environment'];
			$cache_data['authorization_code'] = $this->request->post['authorization_code'];
			$cache_data['shared_id'] = $this->request->post['shared_id'];
			$cache_data['seller_nonce'] = $this->request->post['seller_nonce'];
			
			$this->cache->set('paypal', $cache_data, 30);
		}
		
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
    }
	
	public function getSaleAnalytics() {
		$this->load->language('extension/payment/paypal');

		$data = array();

		$this->load->model('extension/payment/paypal');

		$data['all_sale'] = array();
		$data['paypal_sale'] = array();
		$data['xaxis'] = array();
		
		$data['all_sale']['label'] = $this->language->get('text_all_sales');
		$data['paypal_sale']['label'] = $this->language->get('text_paypal_sales');
		$data['all_sale']['data'] = array();
		$data['paypal_sale']['data'] = array();

		if (isset($this->request->get['range'])) {
			$range = $this->request->get['range'];
		} else {
			$range = 'day';
		}

		switch ($range) {
			default:
			case 'day':
				$results = $this->model_extension_payment_paypal->getTotalSalesByDay();

				foreach ($results as $key => $value) {
					$data['all_sale']['data'][] = array($key, $value['total']);
					$data['paypal_sale']['data'][] = array($key, $value['paypal_total']);
				}

				for ($i = 0; $i < 24; $i++) {
					$data['xaxis'][] = array($i, $i);
				}
				
				break;
			case 'week':
				$results = $this->model_extension_payment_paypal->getTotalSalesByWeek();

				foreach ($results as $key => $value) {
					$data['all_sale']['data'][] = array($key, $value['total']);
					$data['paypal_sale']['data'][] = array($key, $value['paypal_total']);
				}

				$date_start = strtotime('-' . date('w') . ' days');

				for ($i = 0; $i < 7; $i++) {
					$date = date('Y-m-d', $date_start + ($i * 86400));

					$data['xaxis'][] = array(date('w', strtotime($date)), date('D', strtotime($date)));
				}
				
				break;
			case 'month':
				$results = $this->model_extension_payment_paypal->getTotalSalesByMonth();

				foreach ($results as $key => $value) {
					$data['all_sale']['data'][] = array($key, $value['total']);
					$data['paypal_sale']['data'][] = array($key, $value['paypal_total']);
				}

				for ($i = 1; $i <= date('t'); $i++) {
					$date = date('Y') . '-' . date('m') . '-' . $i;

					$data['xaxis'][] = array(date('j', strtotime($date)), date('d', strtotime($date)));
				}
				
				break;
			case 'year':
				$results = $this->model_extension_payment_paypal->getTotalSalesByYear();

				foreach ($results as $key => $value) {
					$data['all_sale']['data'][] = array($key, $value['total']);
					$data['paypal_sale']['data'][] = array($key, $value['paypal_total']);
				}

				for ($i = 1; $i <= 12; $i++) {
					$data['xaxis'][] = array($i, date('M', mktime(0, 0, 0, $i)));
				}
				
				break;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function downloadAssociationFile() {
		$environment = $this->config->get('paypal_environment');
		
		if ($environment == 'production') {
			$file = 'https://www.paypalobjects.com/.well-known/apple-developer-merchantid-domain-association';
		
			$file_headers = @get_headers($file);
				
			if (strpos($file_headers[0], '404') !== false) {
				$file = 'https://www.paypalobjects.com/.well-known/apple-developer-merchantid-domain-association.txt';
			}
		} else {
			$file = 'https://www.paypalobjects.com/sandbox/apple-developer-merchantid-domain-association';
		}
		
		header('Content-Description: File Transfer');
		header('Content-Type: text/plain');
		header('Content-Disposition: attachment; filename="' . basename($file, '.txt') . '"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
				
		readfile($file);
	}
	
	public function downloadHostAssociationFile() {
		$this->load->language('extension/payment/paypal');
		
		$environment = $this->config->get('paypal_environment');
		
		if ($environment == 'production') {
			$file = 'https://www.paypalobjects.com/.well-known/apple-developer-merchantid-domain-association';
		
			$file_headers = @get_headers($file);
				
			if (strpos($file_headers[0], '404') !== false) {
				$file = 'https://www.paypalobjects.com/.well-known/apple-developer-merchantid-domain-association.txt';
			}
		} else {
			$file = 'https://www.paypalobjects.com/sandbox/apple-developer-merchantid-domain-association';
		}
		
		$content = file_get_contents($file);
				
		if ($content) {
			$dir = str_replace('admin/', '.well-known/', DIR_APPLICATION);
			
			if (!file_exists($dir)) {
				mkdir($dir, 0777, true);
			}
			
			if (file_exists($dir)) {
				$fh = fopen($dir . basename($file, '.txt'), 'w');
				fwrite($fh, $content);
				fclose($fh);
			}
			
			$data['success'] = $this->language->get('success_download_host');
		}
		
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
			
	public function sendContact() {
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		if (isset($this->request->post['paypal_setting']['contact'])) {
			$this->model_extension_payment_paypal->sendContact($this->request->post['paypal_setting']['contact']);
			
			$data['success'] = $this->language->get('success_send');
		}
		
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function agree() {		
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		$this->model_extension_payment_paypal->setAgreeStatus();
			
		$data['success'] = $this->language->get('success_agree');
				
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
					
	public function install() {		
		$this->load->model('extension/payment/paypal');
		
		$this->model_extension_payment_paypal->install();
		
		$this->load->model('extension/event');
		
		$this->model_extension_event->deleteEvent('paypal_order_info');
		$this->model_extension_event->deleteEvent('paypal_header');
		$this->model_extension_event->deleteEvent('paypal_extension_get_extensions');
		$this->model_extension_event->deleteEvent('paypal_order_delete_order');
		$this->model_extension_event->deleteEvent('paypal_customer_delete_customer');
		
		$this->model_extension_event->addEvent('paypal_order_info', 'admin/view/sale/order_info/before', 'extension/payment/paypal/order_info_before');
		$this->model_extension_event->addEvent('paypal_header', 'catalog/controller/common/header/before', 'extension/payment/paypal/header_before');
		$this->model_extension_event->addEvent('paypal_extension_get_extensions', 'catalog/model/extension/extension/getExtensions/after', 'extension/payment/paypal/extension_get_extensions_after');
		$this->model_extension_event->addEvent('paypal_order_delete_order', 'catalog/model/checkout/order/deleteOrder/before', 'extension/payment/paypal/order_delete_order_before');
		$this->model_extension_event->addEvent('paypal_customer_delete_customer', 'admin/model/customer/customer/deleteCustomer/before', 'extension/payment/paypal/customer_delete_customer_before');
		
		$_config = new Config();
		$_config->load('paypal');
			
		$config_setting = $_config->get('paypal_setting');
				
		$setting['paypal_version'] = $config_setting['version'];
		
		$this->load->model('setting/setting');
		
		$this->model_setting_setting->editSetting('paypal_version', $setting);
	}
	
	public function uninstall() {
		$this->load->model('extension/payment/paypal');
		
		$this->model_extension_payment_paypal->uninstall();
		
		$this->load->model('extension/event');
		
		$this->model_extension_event->deleteEvent('paypal_order_info');
		$this->model_extension_event->deleteEvent('paypal_header');
		$this->model_extension_event->deleteEvent('paypal_extension_get_extensions');
		$this->model_extension_event->deleteEvent('paypal_order_delete_order');
		$this->model_extension_event->deleteEvent('paypal_customer_delete_customer');
		
		$this->load->model('setting/setting');
		
		$this->model_setting_setting->deleteSetting('paypal_version');
	}
	
	public function customer_delete_customer_before($route, &$data) {
		$this->load->model('extension/payment/paypal');

		$customer_id = $data[0];

		$this->model_extension_payment_paypal->deletePayPalCustomerTokens($customer_id);
	}
	
	public function order_info_before($route, &$data) {
		if ($this->config->get('paypal_status') && !empty($this->request->get['order_id'])) {
			$this->load->language('extension/payment/paypal');
			
			$this->load->model('extension/payment/paypal');
			
			$data['order_id'] = $this->request->get['order_id'];
			
			$paypal_order_info = $this->model_extension_payment_paypal->getPayPalOrder($data['order_id']);
				
			if ($paypal_order_info) {
				$data['text_transaction_id'] = $this->language->get('text_transaction_id');
				$data['text_transaction_created'] = $this->language->get('text_transaction_created');
				$data['text_transaction_voided'] = $this->language->get('text_transaction_voided');
				$data['text_transaction_completed'] = $this->language->get('text_transaction_completed');
				$data['text_transaction_declined'] = $this->language->get('text_transaction_declined');
				$data['text_transaction_pending'] = $this->language->get('text_transaction_pending');
				$data['text_transaction_refunded'] = $this->language->get('text_transaction_refunded');
				$data['text_transaction_reversed'] = $this->language->get('text_transaction_reversed');			
				
				$data['button_capture'] = $this->language->get('button_capture');
				$data['button_reauthorize'] = $this->language->get('button_reauthorize');
				$data['button_void'] = $this->language->get('button_void');
				$data['button_refund'] = $this->language->get('button_refund');
				
				$data['transaction_id'] = $paypal_order_info['transaction_id'];
				$data['transaction_status'] = $paypal_order_info['transaction_status'];
				
				if ($paypal_order_info['environment'] == 'production') {
					$data['transaction_url'] = 'https://www.paypal.com/activity/payment/' . $data['transaction_id'];
				} else {
					$data['transaction_url'] = 'https://www.sandbox.paypal.com/activity/payment/' . $data['transaction_id'];
				}
				
				$data['info_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/getPaymentInfo', 'token=' . $this->session->data['token'] . '&order_id=' . $data['order_id'], true));
				$data['capture_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/capturePayment', 'token=' . $this->session->data['token'], true));
				$data['reauthorize_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/reauthorizePayment', 'token=' . $this->session->data['token'], true));
				$data['void_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/voidPayment', 'token=' . $this->session->data['token'], true));
				$data['refund_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/refundPayment', 'token=' . $this->session->data['token'], true));
								
				$data['tabs'][] = array(
					'code'    => 'paypal',
					'title'   => $this->language->get('heading_title_main'),
					'content' => $this->load->view('extension/payment/paypal/order', $data)
				);
			}
		}
	}
	
	public function getPaymentInfo() {
		$content = '';
		
		if ($this->config->get('paypal_status') && !empty($this->request->get['order_id'])) {
			$this->load->language('extension/payment/paypal');
			
			$this->load->model('extension/payment/paypal');
			
			$data['order_id'] = $this->request->get['order_id'];
			
			$paypal_order_info = $this->model_extension_payment_paypal->getPayPalOrder($data['order_id']);
				
			if ($paypal_order_info) {
				$data['text_transaction_id'] = $this->language->get('text_transaction_id');
				$data['text_transaction_created'] = $this->language->get('text_transaction_created');
				$data['text_transaction_voided'] = $this->language->get('text_transaction_voided');
				$data['text_transaction_completed'] = $this->language->get('text_transaction_completed');
				$data['text_transaction_declined'] = $this->language->get('text_transaction_declined');
				$data['text_transaction_pending'] = $this->language->get('text_transaction_pending');
				$data['text_transaction_refunded'] = $this->language->get('text_transaction_refunded');
				$data['text_transaction_reversed'] = $this->language->get('text_transaction_reversed');			
				
				$data['button_capture_payment'] = $this->language->get('button_capture_payment');
				$data['button_reauthorize_payment'] = $this->language->get('button_reauthorize_payment');
				$data['button_void_payment'] = $this->language->get('button_void_payment');
				$data['button_refund_payment'] = $this->language->get('button_refund_payment');
				
				$data['transaction_id'] = $paypal_order_info['transaction_id'];
				$data['transaction_status'] = $paypal_order_info['transaction_status'];
				
				if ($paypal_order_info['environment'] == 'production') {
					$data['transaction_url'] = 'https://www.paypal.com/activity/payment/' . $data['transaction_id'];
				} else {
					$data['transaction_url'] = 'https://www.sandbox.paypal.com/activity/payment/' . $data['transaction_id'];
				}
				
				$data['info_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/getPaymentInfo', 'token=' . $this->session->data['token'] . '&order_id=' . $data['order_id'], true));
				$data['capture_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/capturePayment', 'token=' . $this->session->data['token'], true));
				$data['reauthorize_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/reauthorizePayment', 'token=' . $this->session->data['token'], true));
				$data['void_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/voidPayment', 'token=' . $this->session->data['token'], true));
				$data['refund_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/refundPayment', 'token=' . $this->session->data['token'], true));
								
				$content = $this->load->view('extension/payment/paypal/order', $data);
			}
		}
		
		$this->response->setOutput($content);
	}
	
	public function capturePayment() {						
		if ($this->config->get('paypal_status') && !empty($this->request->post['order_id']) && !empty($this->request->post['transaction_id'])) {
			$this->load->language('extension/payment/paypal');
			
			$this->load->model('extension/payment/paypal');
			
			$order_id = $this->request->post['order_id'];
			$transaction_id = $this->request->post['transaction_id'];
			
			$_config = new Config();
			$_config->load('paypal');
			
			$config_setting = $_config->get('paypal_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('paypal_setting'));
				
			$client_id = $this->config->get('paypal_client_id');
			$secret = $this->config->get('paypal_secret');
			$environment = $this->config->get('paypal_environment');
			$partner_id = $setting['partner'][$environment]['partner_id'];
			$partner_attribution_id = $setting['partner'][$environment]['partner_attribution_id'];
			$transaction_method = $setting['general']['transaction_method'];
			
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
		
			$paypal_info = array(
				'partner_id' => $partner_id,
				'client_id' => $client_id,
				'secret' => $secret,
				'environment' => $environment,
				'partner_attribution_id' => $partner_attribution_id
			);
		
			$paypal = new PayPal($paypal_info);
		
			$token_info = array(
				'grant_type' => 'client_credentials'
			);	
						
			$paypal->setAccessToken($token_info);

			$result = $paypal->setPaymentCapture($transaction_id);
							
			if ($paypal->hasErrors()) {
				$error_messages = array();
				
				$errors = $paypal->getErrors();
								
				foreach ($errors as $error) {
					if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
						$error['message'] = $this->language->get('error_timeout');
					}
				
					if (isset($error['details'][0]['description'])) {
						$error_messages[] = $error['details'][0]['description'];
					} elseif (isset($error['message'])) {
						$error_messages[] = $error['message'];
					}
					
					$this->model_extension_payment_paypal->log($error, $error['message']);
				}
				
				$this->error['warning'] = implode(' ', $error_messages);
			}
						
			if (isset($result['id']) && isset($result['status']) && !$this->error) {
				$transaction_id = $result['id'];
				$transaction_status = 'completed';
				
				$paypal_order_data = array(
					'order_id' => $order_id,
					'transaction_id' => $transaction_id,
					'transaction_status' => $transaction_status
				);

				$this->model_extension_payment_paypal->editPayPalOrder($paypal_order_data);
								
				$data['success'] = $this->language->get('success_capture_payment');
			}
		}
				
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function reauthorizePayment() {
		if ($this->config->get('paypal_status') && !empty($this->request->post['order_id']) && !empty($this->request->post['transaction_id'])) {
			$this->load->language('extension/payment/paypal');
			
			$this->load->model('extension/payment/paypal');
			
			$order_id = $this->request->post['order_id'];
			$transaction_id = $this->request->post['transaction_id'];
			
			$_config = new Config();
			$_config->load('paypal');
			
			$config_setting = $_config->get('paypal_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('paypal_setting'));
				
			$client_id = $this->config->get('paypal_client_id');
			$secret = $this->config->get('paypal_secret');
			$environment = $this->config->get('paypal_environment');
			$partner_id = $setting['partner'][$environment]['partner_id'];
			$partner_attribution_id = $setting['partner'][$environment]['partner_attribution_id'];
			$transaction_method = $setting['general']['transaction_method'];
			
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
		
			$paypal_info = array(
				'partner_id' => $partner_id,
				'client_id' => $client_id,
				'secret' => $secret,
				'environment' => $environment,
				'partner_attribution_id' => $partner_attribution_id
			);
		
			$paypal = new PayPal($paypal_info);
		
			$token_info = array(
				'grant_type' => 'client_credentials'
			);	
						
			$paypal->setAccessToken($token_info);
		
			$result = $paypal->setPaymentReauthorize($transaction_id);
								
			if ($paypal->hasErrors()) {
				$error_messages = array();
				
				$errors = $paypal->getErrors();
					
				foreach ($errors as $error) {
					if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
						$error['message'] = $this->language->get('error_timeout');
					}
				
					if (isset($error['details'][0]['description'])) {
						$error_messages[] = $error['details'][0]['description'];
					} elseif (isset($error['message'])) {
						$error_messages[] = $error['message'];
					}
					
					$this->model_extension_payment_paypal->log($error, $error['message']);
				}
			
				$this->error['warning'] = implode(' ', $error_messages);
			}
							
			if (isset($result['id']) && isset($result['status']) && !$this->error) {
				$transaction_id = $result['id'];
				$transaction_status = 'created';
				
				$this->model_extension_payment_paypal->deletePayPalOrder($order_id);
										
				$paypal_order_data = array(
					'order_id' => $order_id,
					'transaction_id' => $transaction_id,
					'transaction_status' => $transaction_status
				);

				$this->model_extension_payment_paypal->editPayPalOrder($paypal_order_data);
								
				$data['success'] = $this->language->get('success_reauthorize_payment');
			}
		}
						
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function voidPayment() {
		if ($this->config->get('paypal_status') && !empty($this->request->post['order_id']) && !empty($this->request->post['transaction_id'])) {
			$this->load->language('extension/payment/paypal');
			
			$this->load->model('extension/payment/paypal');
			
			$order_id = $this->request->post['order_id'];
			$transaction_id = $this->request->post['transaction_id'];
			
			$_config = new Config();
			$_config->load('paypal');
			
			$config_setting = $_config->get('paypal_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('paypal_setting'));
				
			$client_id = $this->config->get('paypal_client_id');
			$secret = $this->config->get('paypal_secret');
			$environment = $this->config->get('paypal_environment');
			$partner_id = $setting['partner'][$environment]['partner_id'];
			$partner_attribution_id = $setting['partner'][$environment]['partner_attribution_id'];
			$transaction_method = $setting['general']['transaction_method'];
			
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
		
			$paypal_info = array(
				'partner_id' => $partner_id,
				'client_id' => $client_id,
				'secret' => $secret,
				'environment' => $environment,
				'partner_attribution_id' => $partner_attribution_id
			);
		
			$paypal = new PayPal($paypal_info);
		
			$token_info = array(
				'grant_type' => 'client_credentials'
			);	
						
			$paypal->setAccessToken($token_info);
		
			$result = $paypal->setPaymentVoid($transaction_id);
				
			if ($paypal->hasErrors()) {
				$error_messages = array();
				
				$errors = $paypal->getErrors();
								
				foreach ($errors as $error) {
					if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
						$error['message'] = $this->language->get('error_timeout');
					}
				
					if (isset($error['details'][0]['description'])) {
						$error_messages[] = $error['details'][0]['description'];
					} elseif (isset($error['message'])) {
						$error_messages[] = $error['message'];
					}
					
					$this->model_extension_payment_paypal->log($error, $error['message']);
				}
				
				$this->error['warning'] = implode(' ', $error_messages);
			}
			
			if (!$this->error) {
				$transaction_status = 'voided';
				
				$this->model_extension_payment_paypal->deletePayPalOrder($order_id);
										
				$paypal_order_data = array(
					'order_id' => $order_id,
					'transaction_status' => $transaction_status
				);

				$this->model_extension_payment_paypal->editPayPalOrder($paypal_order_data);
								
				$data['success'] = $this->language->get('success_void_payment');
			}
		}
						
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function refundPayment() {
		if ($this->config->get('paypal_status') && !empty($this->request->post['order_id']) && !empty($this->request->post['transaction_id'])) {
			$this->load->language('extension/payment/paypal');
			
			$this->load->model('extension/payment/paypal');
			
			$order_id = $this->request->post['order_id'];
			$transaction_id = $this->request->post['transaction_id'];
			
			$_config = new Config();
			$_config->load('paypal');
			
			$config_setting = $_config->get('paypal_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('paypal_setting'));
				
			$client_id = $this->config->get('paypal_client_id');
			$secret = $this->config->get('paypal_secret');
			$environment = $this->config->get('paypal_environment');
			$partner_id = $setting['partner'][$environment]['partner_id'];
			$partner_attribution_id = $setting['partner'][$environment]['partner_attribution_id'];
			$transaction_method = $setting['general']['transaction_method'];
			
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
		
			$paypal_info = array(
				'partner_id' => $partner_id,
				'client_id' => $client_id,
				'secret' => $secret,
				'environment' => $environment,
				'partner_attribution_id' => $partner_attribution_id
			);
		
			$paypal = new PayPal($paypal_info);
		
			$token_info = array(
				'grant_type' => 'client_credentials'
			);	
						
			$paypal->setAccessToken($token_info);

			$result = $paypal->setPaymentRefund($transaction_id);
							
			if ($paypal->hasErrors()) {
				$error_messages = array();
				
				$errors = $paypal->getErrors();
								
				foreach ($errors as $error) {
					if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
						$error['message'] = $this->language->get('error_timeout');
					}
				
					if (isset($error['details'][0]['description'])) {
						$error_messages[] = $error['details'][0]['description'];
					} elseif (isset($error['message'])) {
						$error_messages[] = $error['message'];
					}
					
					$this->model_extension_payment_paypal->log($error, $error['message']);
				}
				
				$this->error['warning'] = implode(' ', $error_messages);
			}
		
			if (isset($result['id']) && isset($result['status']) && !$this->error) {
				$transaction_status = 'refunded';
				
				$this->model_extension_payment_paypal->deletePayPalOrder($order_id);
										
				$paypal_order_data = array(
					'order_id' => $order_id,
					'transaction_status' => $transaction_status
				);

				$this->model_extension_payment_paypal->editPayPalOrder($paypal_order_data);
								
				$data['success'] = $this->language->get('success_refund_payment');
			}
		}
						
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function recurringButtons() {
		$content = '';
		
		if ($this->config->get('paypal_status') && !empty($this->request->get['order_recurring_id'])) {
			$this->load->language('extension/payment/paypal');
		
			$this->load->model('sale/recurring');
			
			$data['order_recurring_id'] = $this->request->get['order_recurring_id'];

			$order_recurring_info = $this->model_sale_recurring->getRecurring($data['order_recurring_id']);
			
			if ($order_recurring_info) {
				$data['button_enable_recurring'] = $this->language->get('button_enable_recurring');
				$data['button_disable_recurring'] = $this->language->get('button_disable_recurring');
								
				$data['recurring_status'] = $order_recurring_info['status'];

				$data['info_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/getRecurringInfo', 'token=' . $this->session->data['token'] . '&order_recurring_id=' . $data['order_recurring_id'], true));
				$data['enable_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/enableRecurring', 'token=' . $this->session->data['token'], true));
				$data['disable_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/disableRecurring', 'token=' . $this->session->data['token'], true));
								
				$content = $this->load->view('extension/payment/paypal/recurring', $data);
			}
		}
		
		return $content;
	}
	
	public function getRecurringInfo() {
		$this->response->setOutput($this->recurringButtons());
	}
	
	public function enableRecurring() {
		if ($this->config->get('paypal_status') && !empty($this->request->post['order_recurring_id'])) {
			$this->load->language('extension/payment/paypal');
			
			$this->load->model('extension/payment/paypal');
			
			$order_recurring_id = $this->request->post['order_recurring_id'];
			
			$this->model_extension_payment_paypal->editOrderRecurringStatus($order_recurring_id, 1);
			
			$data['success'] = $this->language->get('success_enable_recurring');	
		}
						
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function disableRecurring() {
		if ($this->config->get('paypal_status') && !empty($this->request->post['order_recurring_id'])) {
			$this->load->language('extension/payment/paypal');
			
			$this->load->model('extension/payment/paypal');
			
			$order_recurring_id = $this->request->post['order_recurring_id'];
			
			$this->model_extension_payment_paypal->editOrderRecurringStatus($order_recurring_id, 2);
			
			$data['success'] = $this->language->get('success_disable_recurring');	
		}
						
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/paypal')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
				
		return !$this->error;
	}
	
	private function token($length = 32) {
		// Create random token
		$string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	
		$max = strlen($string) - 1;
	
		$token = '';
	
		for ($i = 0; $i < $length; $i++) {
			$token .= $string[mt_rand(0, $max)];
		}	
	
		return $token;
	}
}