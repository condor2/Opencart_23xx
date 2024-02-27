<?php
class ModelExtensionPaymentPayPalGooglePay extends Model {
	
	public function getMethod($address, $total) {
		$method_data = array();
		
		$this->load->model('extension/payment/paypal');
		
		$agree_status = $this->model_extension_payment_paypal->getAgreeStatus();
		
		if ($this->config->get('paypal_status') && $this->config->get('paypal_client_id') && $this->config->get('paypal_secret') && $agree_status) {
			$this->load->language('extension/payment/paypal');

			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('paypal_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

			if (($this->config->get('paypal_total') > 0) && ($this->config->get('paypal_total') > $total)) {
				$status = false;
			} elseif (!$this->config->get('paypal_geo_zone_id')) {
				$status = true;
			} elseif ($query->num_rows) {
				$status = true;
			} else {
				$status = false;
			}

			if ($status) {
				$method_data = array(
					'code'       => 'paypal_googlepay',
					'title'      => $this->language->get('text_paypal_googlepay_title'),
					'terms'      => '',
					'sort_order' => $this->config->get('paypal_sort_order')
				);
			}
		}

		return $method_data;
	}
}