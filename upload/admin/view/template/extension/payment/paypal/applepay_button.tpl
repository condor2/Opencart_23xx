<?php echo $header; ?><?php echo $column_left; ?>
<div id="content" class="payment-paypal">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary button-save"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
			<h1><?php echo $heading_title_main; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
		<?php } ?>
		<?php if ($text_version) { ?>
		<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_version; ?></div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form_payment">
					<a href="<?php echo $href_dashboard; ?>" class="back-dashboard"><i class="icon icon-back-dashboard"></i><?php echo $text_tab_dashboard; ?></a>
					<ul class="nav nav-tabs">
						<li class="nav-tab"><a href="<?php echo $href_general; ?>" class="tab"><i class="tab-icon tab-icon-general"></i><span class="tab-title"><?php echo $text_tab_general; ?></span></a></li>
						<li class="nav-tab"><a href="<?php echo $href_button; ?>" class="tab"><i class="tab-icon tab-icon-button"></i><span class="tab-title"><?php echo $text_tab_button; ?></span></a></li>
						<li class="nav-tab"><a href="<?php echo $href_googlepay_button; ?>" class="tab"><i class="tab-icon tab-icon-googlepay-button"></i><span class="tab-title"><?php echo $text_tab_googlepay_button; ?></span></a></li>
						<li class="nav-tab active"><a href="<?php echo $href_applepay_button; ?>" class="tab"><i class="tab-icon tab-icon-applepay-button"></i><span class="tab-title"><?php echo $text_tab_applepay_button; ?></span></a></li>
						<li class="nav-tab"><a href="<?php echo $href_card; ?>" class="tab"><i class="tab-icon tab-icon-card"></i><span class="tab-title"><?php echo $text_tab_card; ?></span></a></li>
						<li class="nav-tab"><a href="<?php echo $href_message_configurator; ?>" class="tab"><i class="tab-icon tab-icon-message-configurator"></i><span class="tab-title"><?php echo $text_tab_message_configurator; ?></span></a></li>
						<li class="nav-tab"><a href="<?php echo $href_message_setting; ?>" class="tab"><i class="tab-icon tab-icon-message-setting"></i><span class="tab-title"><?php echo $text_tab_message_setting; ?></span></a></li>
						<li class="nav-tab"><a href="<?php echo $href_order_status; ?>" class="tab"><i class="tab-icon tab-icon-order-status"></i><span class="tab-title"><?php echo $text_tab_order_status; ?></span></a></li>
						<li class="nav-tab"><a href="<?php echo $href_contact; ?>" class="tab"><i class="tab-icon tab-icon-contact"></i><span class="tab-title"><?php echo $text_tab_contact; ?></span></a></li>
					</ul>
					<div class="section-content">
						<div class="row">
							<div class="col col-lg-6">
								<div class="section-checkout">
									<div class="section-title"><?php echo $text_checkout; ?></div>
									<div class="section-panel">
										<div class="section-panel-title"><?php echo $text_step_payment_method; ?><i class="icon icon-section-panel"></i></div>
									</div>
									<div class="section-panel">
										<div class="section-panel-title"><?php echo $text_step_confirm_order; ?><i class="icon icon-section-panel"></i></div>
									</div>
									<div class="table-totals">
										<div class="row">
											<div class="col col-md-offset-6 col-md-6">
												<div class="row row-total">
													<div class="col col-xs-6 col-title"><?php echo $text_cart_sub_total; ?></div>
													<div class="col col-xs-6 col-price"><?php echo $text_cart_product_total_value; ?></div>
												</div>
												<div class="row row-total">
													<div class="col col-xs-6 col-title"><?php echo $text_cart_total; ?></div>
													<div class="col col-xs-6 col-price"><?php echo $text_cart_product_total_value; ?></div>
												</div>
											</div>
										</div>
									</div>
									<div id="applepay_button" class="applepay-button">
										<div id="applepay_button_container" class="applepay-button-container paypal-spinner"></div>
									</div>
									<br />
								</div>	
							</div>
							<div class="col col-lg-6">
								<div class="section-applepay-button-setting">
									<div class="row">
										<div class="col col-md-6">
											<legend class="legend"><?php echo $text_applepay_button_settings; ?></legend>
										</div>
										<div class="col col-md-6">
											<div class="form-group-status">
												<label class="control-label" for="input_applepay_button_status"><span data-toggle="tooltip" title="<?php echo $help_applepay_button_status; ?>"><?php echo $entry_status; ?></span></label>
												<input type="hidden" name="paypal_setting[applepay_button][status]" value="0" />
												<input type="checkbox" name="paypal_setting[applepay_button][status]" value="1" class="switch" <?php if ($setting['applepay_button']['status']) { ?>checked="checked"<?php } ?> />
											</div>
										</div>
									</div>
									<?php if ($text_applepay_alert) { ?>
									<div class="form-group">
										<p class="alert alert-info"><?php echo $text_applepay_alert; ?></p>
									</div>
									<div class="form-group">
										<p class="footnote step-1"><?php echo $text_applepay_step_1; ?></p>
										<div class="row">
											<div class="col col-md-6">
												<a href="<?php echo $applepay_download_url; ?>" target="_blank" class="btn btn-primary btn-block button-download"><?php echo $button_download; ?></a>
											</div>
											<div class="col col-md-6">
												<button type="button" class="btn btn-primary btn-block button-download-host"><?php echo $button_download_host; ?></button>
											</div>
										</div>
									</div>
									<div class="form-group">
										<p class="footnote step-2"><?php echo $text_applepay_step_2; ?></p>
									</div>
									<?php } ?>
									<div class="row">
										<div class="col col-md-6">
											<div class="form-group">
												<label class="control-label" for="input_applepay_button_align"><?php echo $entry_applepay_button_align; ?></label>
												<select name="paypal_setting[applepay_button][align]" id="input_applepay_button_align" class="form-control control-applepay-button">
													<?php foreach ($setting['applepay_button_align'] as $applepay_button_align) { ?>
													<?php if ($applepay_button_align['code'] == $setting['applepay_button']['align']) { ?>
													<option value="<?php echo $applepay_button_align['code']; ?>" selected="selected"><?php echo ${$applepay_button_align['name']}; ?></option>
													<?php } else { ?>
													<option value="<?php echo $applepay_button_align['code']; ?>"><?php echo ${$applepay_button_align['name']}; ?></option>
													<?php } ?>
													<?php } ?>
												</select>
											</div>
											<div class="form-group">
												<label class="control-label" for="input_applepay_button_size"><?php echo $entry_applepay_button_size; ?></label>
												<select name="paypal_setting[applepay_button][size]" id="input_applepay_button_size" class="form-control control-applepay-button">
													<?php foreach ($setting['applepay_button_size'] as $applepay_button_size) { ?>
													<?php if ($applepay_button_size['code'] == $setting['applepay_button']['size']) { ?>
													<option value="<?php echo $applepay_button_size['code']; ?>" selected="selected"><?php echo ${$applepay_button_size['name']}; ?></option>
													<?php } else { ?>
													<option value="<?php echo $applepay_button_size['code']; ?>"><?php echo ${$applepay_button_size['name']}; ?></option>
													<?php } ?>
													<?php } ?>
												</select>
											</div>
											<div class="form-group">
												<label class="control-label" for="input_applepay_button_color"><?php echo $entry_applepay_button_color; ?></label>
												<select name="paypal_setting[applepay_button][color]" id="input_applepay_button_color" class="form-control control-applepay-button">
													<?php foreach ($setting['applepay_button_color'] as $applepay_button_color) { ?>
													<?php if ($applepay_button_color['code'] == $setting['applepay_button']['color']) { ?>
													<option value="<?php echo $applepay_button_color['code']; ?>" selected="selected"><?php echo ${$applepay_button_color['name']}; ?></option>
													<?php } else { ?>
													<option value="<?php echo $applepay_button_color['code']; ?>"><?php echo ${$applepay_button_color['name']}; ?></option>
													<?php } ?>
													<?php } ?>
												</select>
											</div>
										</div>
										<div class="col col-md-6">
											<div class="form-group">
												<label class="control-label" for="input_applepay_button_shape"><?php echo $entry_applepay_button_shape; ?></label>
												<select name="paypal_setting[applepay_button][shape]" id="input_applepay_button_shape" class="form-control control-applepay-button">
													<?php foreach ($setting['applepay_button_shape'] as $applepay_button_shape) { ?>
													<?php if ($applepay_button_shape['code'] == $setting['applepay_button']['shape']) { ?>
													<option value="<?php echo $applepay_button_shape['code']; ?>" selected="selected"><?php echo ${$applepay_button_shape['name']}; ?></option>
													<?php } else { ?>
													<option value="<?php echo $applepay_button_shape['code']; ?>"><?php echo ${$applepay_button_shape['name']}; ?></option>
													<?php } ?>
													<?php } ?>
												</select>
											</div>
											<div class="form-group">
												<label class="control-label" for="input_applepay_button_type"><?php echo $entry_applepay_button_type; ?></label>
												<select name="paypal_setting[applepay_button][type]" id="input_applepay_button_type" class="form-control control-applepay-button">
													<?php foreach ($setting['applepay_button_type'] as $applepay_button_type) { ?>
													<?php if ($applepay_button_type['code'] == $setting['applepay_button']['type']) { ?>
													<option value="<?php echo $applepay_button_type['code']; ?>" selected="selected"><?php echo ${$applepay_button_type['name']}; ?></option>
													<?php } else { ?>
													<option value="<?php echo $applepay_button_type['code']; ?>"><?php echo ${$applepay_button_type['name']}; ?></option>
													<?php } ?>
													<?php } ?>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

var applepay_button_width = JSON.parse('<?php echo json_encode($setting['applepay_button_width']); ?>');

updateApplePayButton();

$('.payment-paypal .switch').bootstrapSwitch({
    'onColor': 'success',
    'onText': '<?php echo $text_on; ?>',
    'offText': '<?php echo $text_off; ?>'
});

$('.payment-paypal').on('change', '.control-applepay-button', function() {
	updateApplePayButton();
});

$('.payment-paypal').on('click', '.button-download-host', function() {
	$.ajax({
		type: 'post',
		url: '<?php echo $applepay_download_host_url; ?>',
		data: '',
		dataType: 'json',
		success: function(json) {
			if (json['success']) {
				$('.payment-paypal > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> ' + json['success'] + '</div>');
				
				$('html, body').animate({scrollTop: $('#content > .container-fluid .alert-success').offset().top}, 'slow');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	})
});

$('.payment-paypal').on('click', '.button-save', function() {
    $.ajax({
		type: 'post',
		url: $('#form_payment').attr('action'),
		data: $('#form_payment').serialize(),
		dataType: 'json',
		success: function(json) {
			$('.payment-paypal .alert-success').remove();
			
			if (json['success']) {
				$('.payment-paypal > .container-fluid').prepend('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> ' + json['success'] + '</div>');
				
				$('html, body').animate({scrollTop: $('.payment-paypal > .container-fluid .alert-success').offset().top}, 'slow');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
    });  
});

$('.payment-paypal').on('click', '.button-agree', function() {
	$.ajax({
		type: 'post',
		url: '<?php echo $agree_url; ?>',
		data: '',
		dataType: 'json',
		success: function(json) {
			$('.payment-paypal .alert').remove();
			
			if (json['success']) {
				$('.payment-paypal > .container-fluid').prepend('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> ' + json['success'] + '</div>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

function updateApplePayButton() {								
	var paypal_data = {};

	paypal_data['client_id'] = '<?php echo $client_id; ?>';
	paypal_data['secret'] = '<?php echo $secret; ?>';
	paypal_data['merchant_id'] = '<?php echo $merchant_id; ?>';
	paypal_data['environment'] = '<?php echo $environment; ?>';
	paypal_data['partner_attribution_id'] = '<?php echo $partner_attribution_id; ?>';
	paypal_data['locale'] = '<?php echo $locale; ?>';
	paypal_data['currency_code'] = '<?php echo $currency_code; ?>';
	paypal_data['currency_value'] = '<?php echo $currency_value; ?>';
	paypal_data['decimal_place'] = '<?php echo $decimal_place; ?>';
	paypal_data['client_token'] = '<?php echo $client_token; ?>';
	paypal_data['transaction_method'] = '<?php echo $setting['general']['transaction_method']; ?>';
	paypal_data['components'] = ['applepay'];
	paypal_data['applepay_button_align'] = $('.payment-paypal #input_applepay_button_align').val();
	paypal_data['applepay_button_size'] = $('.payment-paypal #input_applepay_button_size').val();
	paypal_data['applepay_button_color'] = $('.payment-paypal #input_applepay_button_color').val();
	paypal_data['applepay_button_shape'] = $('.payment-paypal #input_applepay_button_shape').val();
	paypal_data['applepay_button_type'] = $('.payment-paypal #input_applepay_button_type').val();
	paypal_data['applepay_button_width'] = applepay_button_width[paypal_data['applepay_button_size']];
		
	PayPalAPI.init(paypal_data);
}

</script>
<?php echo $footer; ?>