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
						<li class="nav-tab active"><a href="<?php echo $href_general; ?>" class="tab"><i class="tab-icon tab-icon-general"></i><span class="tab-title"><?php echo $text_tab_general; ?></span></a></li>
						<li class="nav-tab"><a href="<?php echo $href_button; ?>" class="tab"><i class="tab-icon tab-icon-button"></i><span class="tab-title"><?php echo $text_tab_button; ?></span></a></li>
						<li class="nav-tab"><a href="<?php echo $href_googlepay_button; ?>" class="tab"><i class="tab-icon tab-icon-googlepay-button"></i><span class="tab-title"><?php echo $text_tab_googlepay_button; ?></span></a></li>
						<li class="nav-tab"><a href="<?php echo $href_applepay_button; ?>" class="tab"><i class="tab-icon tab-icon-applepay-button"></i><span class="tab-title"><?php echo $text_tab_applepay_button; ?></span></a></li>
						<li class="nav-tab"><a href="<?php echo $href_card; ?>" class="tab"><i class="tab-icon tab-icon-card"></i><span class="tab-title"><?php echo $text_tab_card; ?></span></a></li>
						<li class="nav-tab"><a href="<?php echo $href_message; ?>" class="tab"><i class="tab-icon tab-icon-message"></i><span class="tab-title"><?php echo $text_tab_message; ?></span></a></li>
						<li class="nav-tab"><a href="<?php echo $href_order_status; ?>" class="tab"><i class="tab-icon tab-icon-order-status"></i><span class="tab-title"><?php echo $text_tab_order_status; ?></span></a></li>
						<li class="nav-tab"><a href="<?php echo $href_contact; ?>" class="tab"><i class="tab-icon tab-icon-contact"></i><span class="tab-title"><?php echo $text_tab_contact; ?></span></a></li>
					</ul>
					<div class="section-content">
						<button type="button" class="btn btn-danger button-disconnect"><?php echo $button_disconnect; ?></button>
						<hr class="hr" />
						<button type="button" href="#all_settings" class="btn btn-default button-all-settings collapsed" data-toggle="collapse" role="button"><?php echo $button_all_settings; ?><i class="icon icon-all-settings"></i></button>	
						<div id="all_settings" class="all-settings collapse">
							<div class="form-group">
								<label class="control-label" for="input_connect"><?php echo $entry_connect; ?></label>
								<p class="alert alert-info"><?php echo $text_connect; ?></p>
							</div>
							<div class="row">
								<div class="col col-md-6">
									<div class="form-group">
										<label class="control-label" for="input_status"><?php echo $entry_status; ?></label>
										<div id="input_status">
											<input type="hidden" name="paypal_status" value="0" />
											<input type="checkbox" name="paypal_status" value="1" class="switch" <?php if ($status) { ?>checked="checked"<?php } ?> />
										</div>
									</div>
								</div>
								<div class="col col-md-6">
									<div class="form-group">
										<label class="control-label" for="input_general_debug"><?php echo $entry_debug; ?></label>
										<div id="input_general_debug">
											<input type="hidden" name="paypal_setting[general][debug]" value="0" />
											<input type="checkbox" name="paypal_setting[general][debug]" value="1" class="switch" <?php if ($setting['general']['debug']) { ?>checked="checked"<?php } ?> />
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col col-md-6">
									<div class="form-group">
										<label class="control-label" for="input_general_sale_analytics_range"><?php echo $entry_sale_analytics_range; ?></label>
										<select name="paypal_setting[general][sale_analytics_range]" id="input_general_sale_analytics_range" class="form-control">
											<?php foreach ($setting['sale_analytics_range'] as $sale_analytics_range) { ?>
											<?php if ($sale_analytics_range['code'] == $setting['general']['sale_analytics_range']) { ?>
											<option value="<?php echo $sale_analytics_range['code']; ?>" selected="selected"><?php echo ${$sale_analytics_range['name']}; ?></option>
											<?php } else { ?>
											<option value="<?php echo $sale_analytics_range['code']; ?>"><?php echo ${$sale_analytics_range['name']}; ?></option>
											<?php } ?>
											<?php } ?>
										</select>
									</div>
									<div class="form-group">
										<label class="control-label" for="input_general_checkout_mode"><span data-toggle="tooltip" title="<?php echo $help_checkout_mode; ?>"><?php echo $entry_checkout_mode; ?></span></label>
										<select name="paypal_setting[general][checkout_mode]" id="input_general_checkout_mode" class="form-control">
											<?php foreach ($setting['checkout_mode'] as $checkout_mode) { ?>
											<?php if ($checkout_mode['code'] == $setting['general']['checkout_mode']) { ?>
											<option value="<?php echo $checkout_mode['code']; ?>" selected="selected"><?php echo ${$checkout_mode['name']}; ?></option>
											<?php } else { ?>
											<option value="<?php echo $checkout_mode['code']; ?>"><?php echo ${$checkout_mode['name']}; ?></option>
											<?php } ?>
											<?php } ?>
										</select>
									</div>
									<div class="form-group">
										<label class="control-label" for="input_general_transaction_method"><?php echo $entry_transaction_method; ?></label>
										<select name="paypal_setting[general][transaction_method]" id="input_general_transaction_method" class="form-control">
											<?php foreach ($setting['transaction_method'] as $transaction_method) { ?>
											<?php if ($transaction_method['code'] == $setting['general']['transaction_method']) { ?>
											<option value="<?php echo $transaction_method['code']; ?>" selected="selected"><?php echo ${$transaction_method['name']}; ?></option>
											<?php } else { ?>
											<option value="<?php echo $transaction_method['code']; ?>"><?php echo ${$transaction_method['name']}; ?></option>
											<?php } ?>
											<?php } ?>
										</select>
									</div>
									<div class="form-group">
										<label class="control-label" for="input_total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
										<input type="text" name="paypal_total" value="<?php echo $total; ?>" placeholder="<?php echo $entry_total; ?>" id="input_total" class="form-control" />
									</div>
									<div class="form-group">
										<label class="control-label" for="input_geo_zone"><?php echo $entry_geo_zone; ?></label>
										<select name="paypal_geo_zone_id" id="input_geo_zone" class="form-control">
											<option value="0"><?php echo $text_all_zones; ?></option>
											<?php foreach ($geo_zones as $geo_zone) { ?>
											<?php if ($geo_zone['geo_zone_id'] == $geo_zone_id) { ?>
											<option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
											<?php } ?>
											<?php } ?>
										</select>
									</div>
									<div class="form-group">
										<label class="control-label" for="input_sort_order"><?php echo $entry_sort_order; ?></label>
										<input type="text" name="paypal_sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input_sort_order" class="form-control" />
									</div>
								</div>
								<div class="col col-md-6">
									<div class="form-group">
										<label class="control-label" for="input_general_country_code"><span data-toggle="tooltip" title="<?php echo $help_country_code; ?>"><?php echo $entry_country_code; ?></span></label>
										<select name="paypal_setting[general][country_code]" id="input_general_country_code" class="form-control">
											<?php foreach ($countries as $country) { ?>
											<?php if ($country['status']) { ?>
											<?php if ($country['iso_code_2'] == $setting['general']['country_code']) { ?>
											<option value="<?php echo $country['iso_code_2']; ?>" selected="selected"><?php echo $country['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $country['iso_code_2']; ?>"><?php echo $country['name']; ?></option>
											<?php } ?>
											<?php } ?>
											<?php } ?>
										</select>
									</div>
									<div class="form-group">
										<label class="control-label" for="input_general_currency_code"><span data-toggle="tooltip" title="<?php echo $help_currency_code; ?>"><?php echo $entry_currency_code; ?></span></label>
										<select name="paypal_setting[general][currency_code]" id="input_general_currency_code" class="form-control">
											<?php foreach ($setting['currency'] as $currency) { ?>
											<?php if ($currency['status']) { ?>
											<?php if ($currency['code'] == $setting['general']['currency_code']) { ?>
											<option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo ${$currency['name']}; ?></option>
											<?php } else { ?>
											<option value="<?php echo $currency['code']; ?>"><?php echo ${$currency['name']}; ?></option>
											<?php } ?>
											<?php } ?>
											<?php } ?>
										</select>
									</div>
									<div class="form-group">
										<label class="control-label" for="input_general_currency_value"><span data-toggle="tooltip" title="<?php echo $help_currency_value; ?>"><?php echo $entry_currency_value; ?></span></label>
										<input type="text" name="paypal_setting[general][currency_value]" value="<?php echo $setting['general']['currency_value']; ?>" placeholder="<?php echo $entry_currency_value; ?>" id="input_general_currency_value" class="form-control" />
									</div>
									<div class="form-group">
										<label class="control-label" for="input_general_card_currency_code"><span data-toggle="tooltip" title="<?php echo $help_card_currency_code; ?>"><?php echo $entry_card_currency_code; ?></span></label>
										<select name="paypal_setting[general][card_currency_code]" id="input_general_card_currency_code" class="form-control">
											<?php foreach ($setting['currency'] as $currency) { ?>
											<?php if ($currency['card_status']) { ?>
											<?php if ($currency['code'] == $setting['general']['card_currency_code']) { ?>
											<option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo ${$currency['name']}; ?></option>
											<?php } else { ?>
											<option value="<?php echo $currency['code']; ?>"><?php echo ${$currency['name']}; ?></option>
											<?php } ?>
											<?php } ?>
											<?php } ?>
										</select>
									</div>
									<div class="form-group">
										<label class="control-label" for="input_general_card_currency_value"><span data-toggle="tooltip" title="<?php echo $help_card_currency_value; ?>"><?php echo $entry_card_currency_value; ?></span></label>
										<input type="text" name="paypal_setting[general][card_currency_value]" value="<?php echo $setting['general']['card_currency_value']; ?>" placeholder="<?php echo $entry_card_currency_value; ?>" id="input_general_card_currency_value" class="form-control" />										
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

$('.payment-paypal .switch').bootstrapSwitch({
    'onColor': 'success',
    'onText': '<?php echo $text_on; ?>',
    'offText': '<?php echo $text_off; ?>'
});

$('.payment-paypal').on('click', '.button-disconnect', function() {
	if (confirm('<?php echo $text_confirm; ?>')) {		
		$.ajax({
			type: 'post',
			url: '<?php echo $disconnect_url; ?>',
			data: '',
			dataType: 'json',
			success: function(json) {
				location.reload();
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
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
				
				$('html, body').animate({ scrollTop: $('.payment-paypal > .container-fluid .alert-success').offset().top}, 'slow');
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

</script>
<?php echo $footer; ?>