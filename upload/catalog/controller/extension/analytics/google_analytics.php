<?php
class ControllerExtensionAnalyticsGoogleAnalytics extends Controller {
    public function index(): void {
		return html_entity_decode($this->config->get('google_analytics_code'), ENT_QUOTES, 'UTF-8');
	}
}