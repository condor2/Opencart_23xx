<?php
class ControllerCommonColumnLeft extends Controller {
	public function index(): string {
		if (isset($this->request->get['token']) && isset($this->session->data['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
			$this->load->language('common/column_left');

			// Create a 3 level menu array
			// Level 2 can not have children

			// Menu
			$data['menus'][] = [
				'id'       => 'menu-dashboard',
				'icon'	   => 'fa-dashboard',
				'name'	   => $this->language->get('text_dashboard'),
				'href'     => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
				'children' => []
			];

			// Catalog
			$catalog = [];

			if ($this->user->hasPermission('access', 'catalog/category')) {
				$catalog[] = [
					'name'	   => $this->language->get('text_category'),
					'href'     => $this->url->link('catalog/category', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'catalog/product')) {
				$catalog[] = [
					'name'	   => $this->language->get('text_product'),
					'href'     => $this->url->link('catalog/product', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'catalog/recurring')) {
				$catalog[] = [
					'name'	   => $this->language->get('text_recurring'),
					'href'     => $this->url->link('catalog/recurring', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'catalog/filter')) {
				$catalog[] = [
					'name'	   => $this->language->get('text_filter'),
					'href'     => $this->url->link('catalog/filter', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			// Attributes
			$attribute = [];

			if ($this->user->hasPermission('access', 'catalog/attribute')) {
				$attribute[] = [
					'name'     => $this->language->get('text_attribute'),
					'href'     => $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'catalog/attribute_group')) {
				$attribute[] = [
					'name'	   => $this->language->get('text_attribute_group'),
					'href'     => $this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($attribute) {
				$catalog[] = [
					'name'	   => $this->language->get('text_attribute'),
					'href'     => '',
					'children' => $attribute
				];
			}

			if ($this->user->hasPermission('access', 'catalog/option')) {
				$catalog[] = [
					'name'	   => $this->language->get('text_option'),
					'href'     => $this->url->link('catalog/option', 'token=' . $this->session->data['token'], true),
					'children' => []		
				];
			}

			if ($this->user->hasPermission('access', 'catalog/manufacturer')) {
				$catalog[] = [
					'name'	   => $this->language->get('text_manufacturer'),
					'href'     => $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'], true),
					'children' => []		
				];
			}

			if ($this->user->hasPermission('access', 'catalog/download')) {
				$catalog[] = [
					'name'	   => $this->language->get('text_download'),
					'href'     => $this->url->link('catalog/download', 'token=' . $this->session->data['token'], true),
					'children' => []		
				];
			}

			if ($this->user->hasPermission('access', 'catalog/review')) {		
				$catalog[] = [
					'name'	   => $this->language->get('text_review'),
					'href'     => $this->url->link('catalog/review', 'token=' . $this->session->data['token'], true),
					'children' => []		
				];		
			}

			if ($this->user->hasPermission('access', 'catalog/information')) {		
				$catalog[] = [
					'name'	   => $this->language->get('text_information'),
					'href'     => $this->url->link('catalog/information', 'token=' . $this->session->data['token'], true),
					'children' => []		
				];					
			}

			if ($catalog) {
				$data['menus'][] = [
					'id'       => 'menu-catalog',
					'icon'	   => 'fa-tags', 
					'name'	   => $this->language->get('text_catalog'),
					'href'     => '',
					'children' => $catalog
				];		
			}

			// Extension
			$extension = [];

			if ($this->user->hasPermission('access', 'extension/installer')) {
				$extension[] = [
					'name'	   => $this->language->get('text_installer'),
					'href'     => $this->url->link('extension/installer', 'token=' . $this->session->data['token'], true),
					'children' => []		
				];					
			}	

			if ($this->user->hasPermission('access', 'extension/extension')) {
				$extension[] = [
					'name'	   => $this->language->get('text_extension'),
					'href'     => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'extension/modification')) {
				$extension[] = [
					'name'	   => $this->language->get('text_modification'),
					'href'     => $this->url->link('extension/modification', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'extension/event')) {
				$extension[] = [
					'name'	   => $this->language->get('text_event'),
					'href'     => $this->url->link('extension/event', 'token=' . $this->session->data['token'], true),
					'children' => []		
				];
			}

			if ($extension) {
				$data['menus'][] = [
					'id'       => 'menu-extension',
					'icon'	   => 'fa-puzzle-piece',
					'name'	   => $this->language->get('text_extension'),
					'href'     => '',
					'children' => $extension
				];		
			}

			// Design
			$design = [];

			if ($this->user->hasPermission('access', 'design/layout')) {
				$design[] = [
					'name'	   => $this->language->get('text_layout'),
					'href'     => $this->url->link('design/layout', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'design/banner')) {
				$design[] = [
					'name'	   => $this->language->get('text_banner'),
					'href'     => $this->url->link('design/banner', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'design/seo_url')) {
				$design[] = [
					'name'	   => $this->language->get('text_seo_url'),
					'href'     => $this->url->link('design/seo_url', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($design) {
				$data['menus'][] = [
					'id'       => 'menu-design',
					'icon'	   => 'fa-television', 
					'name'	   => $this->language->get('text_design'),
					'href'     => '',
					'children' => $design
				];
			}

			// Sales
			$sale = [];

			if ($this->user->hasPermission('access', 'sale/order')) {
				$sale[] = [
					'name'	   => $this->language->get('text_order'),
					'href'     => $this->url->link('sale/order', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'sale/recurring')) {
				$sale[] = [
					'name'	   => $this->language->get('text_order_recurring'),
					'href'     => $this->url->link('sale/recurring', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'sale/return')) {
				$sale[] = [
					'name'	   => $this->language->get('text_return'),
					'href'     => $this->url->link('sale/return', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			// Voucher
			$voucher = [];

			if ($this->user->hasPermission('access', 'sale/voucher')) {
				$voucher[] = [
					'name'	   => $this->language->get('text_voucher'),
					'href'     => $this->url->link('sale/voucher', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'sale/voucher_theme')) {
				$voucher[] = [
					'name'	   => $this->language->get('text_voucher_theme'),
					'href'     => $this->url->link('sale/voucher_theme', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($voucher) {
				$sale[] = [
					'name'	   => $this->language->get('text_voucher'),
					'href'     => '',
					'children' => $voucher
				];
			}

			if ($sale) {
				$data['menus'][] = [
					'id'       => 'menu-sale',
					'icon'	   => 'fa-shopping-cart',
					'name'	   => $this->language->get('text_sale'),
					'href'     => '',
					'children' => $sale
				];
			}

			// Customer
			$customer = [];

			if ($this->user->hasPermission('access', 'customer/customer')) {
				$customer[] = [
					'name'	   => $this->language->get('text_customer'),
					'href'     => $this->url->link('customer/customer', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'customer/customer_group')) {
				$customer[] = [
					'name'	   => $this->language->get('text_customer_group'),
					'href'     => $this->url->link('customer/customer_group', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'customer/custom_field')) {
				$customer[] = [
					'name'	   => $this->language->get('text_custom_field'),
					'href'     => $this->url->link('customer/custom_field', 'token=' . $this->session->data['token'], true),
					'children' => []
				];	
			}

			if ($customer) {
				$data['menus'][] = [
					'id'       => 'menu-customer',
					'icon'	   => 'fa-user',
					'name'	   => $this->language->get('text_customer'),
					'href'     => '',
					'children' => $customer
				];
			}

			// Marketing
			$marketing = [];

			if ($this->user->hasPermission('access', 'marketing/marketing')) {
				$marketing[] = [
					'name'	   => $this->language->get('text_marketing'),
					'href'     => $this->url->link('marketing/marketing', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'marketing/affiliate')) {
				$marketing[] = [
					'name'	   => $this->language->get('text_affiliate'),
					'href'     => $this->url->link('marketing/affiliate', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'marketing/coupon')) {
				$marketing[] = [
					'name'	   => $this->language->get('text_coupon'),
					'href'     => $this->url->link('marketing/coupon', 'token=' . $this->session->data['token'], true),
					'children' => []
				];	
			}

			if ($this->user->hasPermission('access', 'marketing/contact')) {
				$marketing[] = [
					'name'	   => $this->language->get('text_contact'),
					'href'     => $this->url->link('marketing/contact', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($marketing) {
				$data['menus'][] = [
					'id'       => 'menu-marketing',
					'icon'	   => 'fa-share-alt',
					'name'	   => $this->language->get('text_marketing'),
					'href'     => '',
					'children' => $marketing
				];
			}

			// System
			$system = [];

			if ($this->user->hasPermission('access', 'setting/setting')) {
				$system[] = [
					'name'	   => $this->language->get('text_setting'),
					'href'     => $this->url->link('setting/store', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			// Users
			$user = [];

			if ($this->user->hasPermission('access', 'user/user')) {
				$user[] = [
					'name'	   => $this->language->get('text_users'),
					'href'     => $this->url->link('user/user', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'user/user_permission')) {
				$user[] = [
					'name'	   => $this->language->get('text_user_group'),
					'href'     => $this->url->link('user/user_permission', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'user/api')) {
				$user[] = [
					'name'	   => $this->language->get('text_api'),
					'href'     => $this->url->link('user/api', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($user) {
				$system[] = [
					'name'	   => $this->language->get('text_users'),
					'href'     => '',
					'children' => $user
				];
			}

			// Localisation
			$localisation = [];

			if ($this->user->hasPermission('access', 'localisation/location')) {
				$localisation[] = [
					'name'	   => $this->language->get('text_location'),
					'href'     => $this->url->link('localisation/location', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/language')) {
				$localisation[] = [
					'name'	   => $this->language->get('text_language'),
					'href'     => $this->url->link('localisation/language', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/currency')) {
				$localisation[] = [
					'name'	   => $this->language->get('text_currency'),
					'href'     => $this->url->link('localisation/currency', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/stock_status')) {
				$localisation[] = [
					'name'	   => $this->language->get('text_stock_status'),
					'href'     => $this->url->link('localisation/stock_status', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/order_status')) {
				$localisation[] = [
					'name'	   => $this->language->get('text_order_status'),
					'href'     => $this->url->link('localisation/order_status', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			// Returns
			$return = [];

			if ($this->user->hasPermission('access', 'localisation/return_status')) {
				$return[] = [
					'name'	   => $this->language->get('text_return_status'),
					'href'     => $this->url->link('localisation/return_status', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/return_action')) {
				$return[] = [
					'name'	   => $this->language->get('text_return_action'),
					'href'     => $this->url->link('localisation/return_action', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/return_reason')) {
				$return[] = [
					'name'	   => $this->language->get('text_return_reason'),
					'href'     => $this->url->link('localisation/return_reason', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($return) {
				$localisation[] = [
					'name'	   => $this->language->get('text_return'),
					'href'     => '',
					'children' => $return
				];
			}

			if ($this->user->hasPermission('access', 'localisation/country')) {
				$localisation[] = [
					'name'	   => $this->language->get('text_country'),
					'href'     => $this->url->link('localisation/country', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/zone')) {
				$localisation[] = [
					'name'	   => $this->language->get('text_zone'),
					'href'     => $this->url->link('localisation/zone', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/geo_zone')) {
				$localisation[] = [
					'name'	   => $this->language->get('text_geo_zone'),
					'href'     => $this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			// Tax
			$tax = [];

			if ($this->user->hasPermission('access', 'localisation/tax_class')) {
				$tax[] = [
					'name'	   => $this->language->get('text_tax_class'),
					'href'     => $this->url->link('localisation/tax_class', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/tax_rate')) {
				$tax[] = [
					'name'	   => $this->language->get('text_tax_rate'),
					'href'     => $this->url->link('localisation/tax_rate', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($tax) {
				$localisation[] = [
					'name'	   => $this->language->get('text_tax'),
					'href'     => '',
					'children' => $tax
				];
			}

			if ($this->user->hasPermission('access', 'localisation/length_class')) {
				$localisation[] = [
					'name'	   => $this->language->get('text_length_class'),
					'href'     => $this->url->link('localisation/length_class', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'localisation/weight_class')) {
				$localisation[] = [
					'name'	   => $this->language->get('text_weight_class'),
					'href'     => $this->url->link('localisation/weight_class', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($localisation) {
				$system[] = [
					'name'	   => $this->language->get('text_localisation'),
					'href'     => '',
					'children' => $localisation	
				];
			}

			// Tools
			$tool = [];

			if ($this->user->hasPermission('access', 'tool/upload')) {
				$tool[] = [
					'name'	   => $this->language->get('text_upload'),
					'href'     => $this->url->link('tool/upload', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'tool/backup')) {
				$tool[] = [
					'name'	   => $this->language->get('text_backup'),
					'href'     => $this->url->link('tool/backup', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'tool/log')) {
				$tool[] = [
					'name'	   => $this->language->get('text_log'),
					'href'     => $this->url->link('tool/log', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($tool) {
				$system[] = [
					'name'	   => $this->language->get('text_tools'),
					'href'     => '',
					'children' => $tool
				];
			}

			if ($system) {
				$data['menus'][] = [
					'id'       => 'menu-system',
					'icon'	   => 'fa-cog',
					'name'	   => $this->language->get('text_system'),
					'href'     => '',
					'children' => $system
				];
			}

			// Report
			$report = [];

			// Report Sales
			$report_sale = [];	

			if ($this->user->hasPermission('access', 'report/sale_order')) {
				$report_sale[] = [
					'name'	   => $this->language->get('text_report_sale_order'),
					'href'     => $this->url->link('report/sale_order', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'report/sale_tax')) {
				$report_sale[] = [
					'name'	   => $this->language->get('text_report_sale_tax'),
					'href'     => $this->url->link('report/sale_tax', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'report/sale_shipping')) {
				$report_sale[] = [
					'name'	   => $this->language->get('text_report_sale_shipping'),
					'href'     => $this->url->link('report/sale_shipping', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'report/sale_return')) {
				$report_sale[] = [
					'name'	   => $this->language->get('text_report_sale_return'),
					'href'     => $this->url->link('report/sale_return', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'report/sale_coupon')) {
				$report_sale[] = [
					'name'	   => $this->language->get('text_report_sale_coupon'),
					'href'     => $this->url->link('report/sale_coupon', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($report_sale) {
				$report[] = [
					'name'	   => $this->language->get('text_report_sale'),
					'href'     => '',
					'children' => $report_sale
				];
			}

			// Report Products
			$report_product = [];

			if ($this->user->hasPermission('access', 'report/product_viewed')) {
				$report_product[] = [
					'name'	   => $this->language->get('text_report_product_viewed'),
					'href'     => $this->url->link('report/product_viewed', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'report/product_purchased')) {
				$report_product[] = [
					'name'	   => $this->language->get('text_report_product_purchased'),
					'href'     => $this->url->link('report/product_purchased', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($report_product) {
				$report[] = [
					'name'	   => $this->language->get('text_report_product'),
					'href'     => '',
					'children' => $report_product
				];
			}

			// Report Customers
			$report_customer = [];

			if ($this->user->hasPermission('access', 'report/customer_online')) {
				$report_customer[] = [
					'name'	   => $this->language->get('text_report_customer_online'),
					'href'     => $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'report/customer_activity')) {
				$report_customer[] = [
					'name'	   => $this->language->get('text_report_customer_activity'),
					'href'     => $this->url->link('report/customer_activity', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'report/customer_search')) {
				$report_customer[] = [
					'name'	   => $this->language->get('text_report_customer_search'),
					'href'     => $this->url->link('report/customer_search', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'report/customer_order')) {
				$report_customer[] = [
					'name'	   => $this->language->get('text_report_customer_order'),
					'href'     => $this->url->link('report/customer_order', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'report/customer_reward')) {
				$report_customer[] = [
					'name'	   => $this->language->get('text_report_customer_reward'),
					'href'     => $this->url->link('report/customer_reward', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'report/customer_credit')) {
				$report_customer[] = [
					'name'	   => $this->language->get('text_report_customer_credit'),
					'href'     => $this->url->link('report/customer_credit', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($report_customer) {
				$report[] = [
					'name'	   => $this->language->get('text_report_customer'),
					'href'     => '',
					'children' => $report_customer
				];
			}

			// Report Marketing
			$report_marketing = [];

			if ($this->user->hasPermission('access', 'report/marketing')) {
				$report_marketing[] = [
					'name'	   => $this->language->get('text_report_marketing'),
					'href'     => $this->url->link('report/marketing', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'report/affiliate')) {
				$report_marketing[] = [
					'name'	   => $this->language->get('text_report_affiliate'),
					'href'     => $this->url->link('report/affiliate', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'report/affiliate_activity')) {
				$report_marketing[] = [
					'name'	   => $this->language->get('text_report_affiliate_activity'),
					'href'     => $this->url->link('report/affiliate_activity', 'token=' . $this->session->data['token'], true),
					'children' => []
				];
			}

			if ($report_marketing) {
				$report[] = [
					'name'	   => $this->language->get('text_report_marketing'),
					'href'     => '',
					'children' => $report_marketing
				];
			}

			if ($report) {
				$data['menus'][] = [
					'id'       => 'menu-report',
					'icon'	   => 'fa-bar-chart-o',
					'name'	   => $this->language->get('text_reports'),
					'href'     => '',
					'children' => $report
				];
			}

			// Stats
			$data['text_complete_status'] = $this->language->get('text_complete_status');
			$data['text_processing_status'] = $this->language->get('text_processing_status');
			$data['text_other_status'] = $this->language->get('text_other_status');

			$this->load->model('sale/order');

			$order_total = $this->model_sale_order->getTotalOrders();

			$complete_total = $this->model_sale_order->getTotalOrders(['filter_order_status' => implode(',', $this->config->get('config_complete_status'))]);

			if ($complete_total) {
				$data['complete_status'] = round(($complete_total / $order_total) * 100);
			} else {
				$data['complete_status'] = 0;
			}

			$processing_total = $this->model_sale_order->getTotalOrders(['filter_order_status' => implode(',', $this->config->get('config_processing_status'))]);

			if ($processing_total) {
				$data['processing_status'] = round(($processing_total / $order_total) * 100);
			} else {
				$data['processing_status'] = 0;
			}

			$this->load->model('localisation/order_status');

			$order_status_data = [];

			$results = $this->model_localisation_order_status->getOrderStatuses();

			foreach ($results as $result) {
				if (!in_array($result['order_status_id'], array_merge($this->config->get('config_complete_status'), $this->config->get('config_processing_status')))) {
					$order_status_data[] = $result['order_status_id'];
				}
			}

			$other_total = $this->model_sale_order->getTotalOrders(['filter_order_status' => implode(',', $order_status_data)]);

			if ($other_total) {
				$data['other_status'] = round(($other_total / $order_total) * 100);
			} else {
				$data['other_status'] = 0;
			}

			return $this->load->view('common/column_left', $data);
		} else {
			return '';
		}
	}
}
