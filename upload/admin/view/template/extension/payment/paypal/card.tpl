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
						<li class="nav-tab"><a href="<?php echo $href_applepay_button; ?>" class="tab"><i class="tab-icon tab-icon-applepay-button"></i><span class="tab-title"><?php echo $text_tab_applepay_button; ?></span></a></li>
						<li class="nav-tab active"><a href="<?php echo $href_card; ?>" class="tab"><i class="tab-icon tab-icon-card"></i><span class="tab-title"><?php echo $text_tab_card; ?></span></a></li>
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
									<div id="paypal_card" class="paypal-card">
										<div id="paypal_card_container" class="paypal-card-container paypal-spinner">
											<div id="paypal_card_form" class="paypal-card-form well">
												<div class="card-info-number clearfix">
													<label for="card_number" class="card-label"><?php echo $entry_card_number; ?></label>
													<div id="card_number" class="card-input-container"><div id="card_image"></div></div>
												</div>
												<div class="card-info-date-cvv clearfix">
													<div class="card-info-date">
														<label for="expiration_date" class="card-label"><?php echo $entry_expiration_date; ?></label>
														<div id="expiration_date" class="card-input-container"></div>
													</div>
													<div class="card-info-cvv">
														<label for="cvv" class="card-label"><?php echo $entry_cvv; ?></label>
														<div id="cvv" class="card-input-container"></div>
													</div>
												</div>
												<button id="paypal_button_submit" class="btn" value="submit"><?php echo $button_pay; ?></button>
											</div>
										</div>
									</div>
								</div>	
							</div>
							<div class="col col-lg-6">
								<div class="section-card-setting">
									<div class="row">
										<div class="col col-md-6">
											<legend class="legend"><?php echo $text_card_settings; ?></legend>
										</div>
										<div class="col col-md-6">
											<div class="form-group-status">
												<label class="control-label" for="input_card_status"><span data-toggle="tooltip" title="<?php echo $help_card_status; ?>"><?php echo $entry_status; ?></span></label>
												<input type="hidden" name="paypal_setting[card][status]" value="0" />
												<input type="checkbox" name="paypal_setting[card][status]" value="1" class="switch" <?php if ($setting['card']['status']) { ?>checked="checked"<?php } ?> />
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label" for="input_card_align"><?php echo $entry_card_align; ?></label>
										<select name="paypal_setting[card][align]" id="input_card_align" class="form-control control-paypal-card">
											<?php foreach ($setting['card_align'] as $card_align) { ?>
											<?php if ($card_align['code'] == $setting['card']['align']) { ?>
											<option value="<?php echo $card_align['code']; ?>" selected="selected"><?php echo ${$card_align['name']}; ?></option>
											<?php } else { ?>
											<option value="<?php echo $card_align['code']; ?>"><?php echo ${$card_align['name']}; ?></option>
											<?php } ?>
											<?php } ?>
										</select>
									</div>
									<div class="form-group">
										<label class="control-label" for="input_card_size"><?php echo $entry_card_size; ?></label>
										<select name="paypal_setting[card][size]" id="input_card_size" class="form-control control-paypal-card">
											<?php foreach ($setting['card_size'] as $card_size) { ?>
											<?php if ($card_size['code'] == $setting['card']['size']) { ?>
											<option value="<?php echo $card_size['code']; ?>" selected="selected"><?php echo ${$card_size['name']}; ?></option>
											<?php } else { ?>
											<option value="<?php echo $card_size['code']; ?>"><?php echo ${$card_size['name']}; ?></option>
											<?php } ?>
											<?php } ?>
										</select>
									</div>
									<div class="form-group">
										<label class="control-label" for="input_card_secure_method"><span data-toggle="tooltip" title="<?php echo $help_card_secure_method; ?>"><?php echo $entry_card_secure_method; ?></span></label>
										<select name="paypal_setting[card][secure_method]" id="input_card_secure_method" class="form-control">
											<?php foreach ($setting['card_secure_method'] as $card_secure_method) { ?>
											<?php if ($card_secure_method['code'] == $setting['card']['secure_method']) { ?>
											<option value="<?php echo $card_secure_method['code']; ?>" selected="selected"><?php echo ${$card_secure_method['name']}; ?></option>
											<?php } else { ?>
											<option value="<?php echo $card_secure_method['code']; ?>"><?php echo ${$card_secure_method['name']}; ?></option>
											<?php } ?>
											<?php } ?>
										</select>
									</div>
									<hr class="hr" />
									<button type="button" href="#all_settings" class="btn btn-default button-all-settings collapsed" data-toggle="collapse" role="button"><?php echo $button_all_settings; ?><i class="icon icon-all-settings"></i></button>	
									<div id="all_settings" class="all-settings collapse">
										<div class="form-group">
											<label class="control-label" for="input_card_secure_scenario"><?php echo $entry_card_secure_scenario; ?></label>
											<p class="alert alert-info"><?php echo $help_card_secure_scenario; ?></p>
										</div>
										<?php foreach ($setting['card_secure_scenario'] as $card_secure_scenario) { ?>
										<div class="form-group">
											<label class="control-label" for="input_card_secure_scenario_<?php echo $card_secure_scenario['code']; ?>"><?php echo ${$card_secure_scenario['name']}; ?></label>	
											<select name="paypal_setting[card][secure_scenario][<?php echo $card_secure_scenario['code']; ?>]" id="input_card_secure_scenario_<?php echo $card_secure_scenario['code']; ?>" class="form-control">
												<option value="1" <?php if ($setting['card']['secure_scenario'][$card_secure_scenario['code']]) { ?>selected="selected"<?php } ?>><?php echo $text_accept; ?><?php if ($card_secure_scenario['recommended']) { ?> <?php echo $text_recommended; ?><?php } ?></option>
												<option value="0" <?php if (!$setting['card']['secure_scenario'][$card_secure_scenario['code']]) { ?>selected="selected"<?php } ?>><?php echo $text_decline; ?><?php if (!$card_secure_scenario['recommended']) { ?> <?php echo $text_recommended; ?><?php } ?></option>
											</select>
										</div>
										<?php } ?>
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

var card_width = JSON.parse('<?php echo json_encode($setting['card_width']); ?>');

updatePayPalCard();

$('.payment-paypal .switch').bootstrapSwitch({
    'onColor': 'success',
    'onText': '<?php echo $text_on; ?>',
    'offText': '<?php echo $text_off; ?>'
});

$('.payment-paypal').on('change', '.control-paypal-card', function() {
	updatePayPalCard();
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

function updatePayPalCard() {								
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
	paypal_data['components'] = ['hosted-fields'];
	paypal_data['card_align'] = $('.payment-paypal #input_card_align').val();
	paypal_data['card_size'] = $('.payment-paypal #input_card_size').val();
		
	paypal_data['card_width'] = card_width[paypal_data['card_size']];
			
	PayPalAPI.init(paypal_data);
}

</script>
<?php echo $footer; ?>