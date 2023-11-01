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
						<li class="nav-tab"><a href="<?php echo $href_card; ?>" class="tab"><i class="tab-icon tab-icon-card"></i><span class="tab-title"><?php echo $text_tab_card; ?></span></a></li>
						<li class="nav-tab active"><a href="<?php echo $href_message; ?>" class="tab"><i class="tab-icon tab-icon-message"></i><span class="tab-title"><?php echo $text_tab_message; ?></span></a></li>
						<li class="nav-tab"><a href="<?php echo $href_order_status; ?>" class="tab"><i class="tab-icon tab-icon-order-status"></i><span class="tab-title"><?php echo $text_tab_order_status; ?></span></a></li>
						<li class="nav-tab"><a href="<?php echo $href_contact; ?>" class="tab"><i class="tab-icon tab-icon-contact"></i><span class="tab-title"><?php echo $text_tab_contact; ?></span></a></li>
					</ul>
					
					<div class="section-content">
						<ul class="nav nav-pills">
							<?php $i = 0; ?>
							<?php foreach ($setting['message'] as $message) { ?>
							<li class="nav-pill <?php if ($i == 0) { ?>active<?php } ?>"><a href="#pill_<?php echo $message['page_code']; ?>" class="pill" data-toggle="tab"><?php echo ${$message['page_name']}; ?></a></li>
							<?php $i++; ?>
							<?php } ?>
						</ul>
						<hr class="hr" />
						<div class="tab-content">
							<?php $i = 0; ?>
							<?php foreach ($setting['message'] as $message) { ?>
							<div id="pill_<?php echo $message['page_code']; ?>" class="tab-pane <?php if ($i == 0) { ?>active<?php } ?>">
								<div class="row">
									<div class="col col-lg-6">
										<?php if ($message['page_code'] == 'checkout') { ?>
										<div class="section-checkout">
											<div class="section-title"><?php echo $text_checkout; ?></div>
											<div class="section-panel">
												<div class="section-panel-title"><?php echo $text_step_payment_method; ?><i class="icon icon-section-panel"></i></div>
											</div>
											<div class="radio-payments">
												<div class="radio-payment">
													<div class="form-check-input checked"></div>
													<label class="payment-label"><?php echo $text_payment_method_paypal; ?></label>												
												</div>
												<div class="radio-payment">
													<div class="form-check-input"></div>
													<label class="payment-label"><?php echo $text_payment_method_paypal_paylater; ?>
														<br />
														<div id="paypal_message_checkout" class="paypal-message">
															<div id="paypal_message_checkout_container" class="paypal-message-container paypal-spinner"></div>
														</div>
													</label>												
												</div>
												<div class="radio-payment">	
													<div class="form-check-input"></div>
													<label class="payment-label"><?php echo $text_payment_method_cod; ?></label>
												</div>	
											</div>
										</div>
										<?php } ?>
										<?php if ($message['page_code'] == 'cart') { ?>
										<div class="section-cart">
											<div class="section-title"><?php echo $text_cart; ?></div>
											<div class="table-cart">
												<div class="table-row table-row-header">
													<div class="table-col table-col-product-image"><?php echo $text_cart_product_image; ?></div>
													<div class="table-col table-col-product-name"><?php echo $text_cart_product_name; ?></div>
													<div class="table-col table-col-product-model"><?php echo $text_cart_product_model; ?></div>
													<div class="table-col table-col-product-quantity"><?php echo $text_cart_product_quantity; ?></div>
													<div class="table-col table-col-product-price"><?php echo $text_cart_product_price; ?></div>
													<div class="table-col table-col-product-total"><?php echo $text_cart_product_total; ?></div>
												</div>
												<div class="table-row">
													<div class="table-col table-col-product-image"><div class="product-image"></div></div>
													<div class="table-col table-col-product-name"><?php echo $text_cart_product_name_value; ?></div>
													<div class="table-col table-col-product-model"><?php echo $text_cart_product_model_value; ?></div>
													<div class="table-col table-col-product-quantity"><?php echo $text_cart_product_quantity_value; ?></div>
													<div class="table-col table-col-product-price"><?php echo $text_cart_product_price_value; ?></div>
													<div class="table-col table-col-product-total"><?php echo $text_cart_product_total_value; ?></div>
												</div>
											</div>	
											<div class="section-panel">
												<div class="section-panel-title"><?php echo $text_step_coupon; ?><i class="icon icon-section-panel"></i></div>
											</div>
											<div class="section-panel">
												<div class="section-panel-title"><?php echo $text_step_shipping; ?><i class="icon icon-section-panel"></i></div>
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
											<div class="row">
												<div class="col col-md-offset-7 col-md-5">
													<button type="button" class="btn button-cart"><?php echo $button_checkout; ?></button>
												</div>
											</div>
											<div id="paypal_message_cart" class="paypal-message">
												<div id="paypal_message_cart_container" class="paypal-message-container paypal-spinner"></div>
											</div>												
										</div>
										<?php } ?>
										<?php if ($message['page_code'] == 'product') { ?>
										<div class="section-product">
											<div class="row">
												<div class="col col-sm-6">
													<div class="product-image"></div>
												</div>
												<div class="col col-sm-6">
													<div class="product-name"><?php echo $text_product_name; ?></div>
													<div class="product-price"><?php echo $text_product_price; ?></div>
													<div id="paypal_message_product" class="paypal-message">
														<div id="paypal_message_product_container" class="paypal-message-container paypal-spinner"></div>
													</div>
													<div class="product-manufacturer"><?php echo $text_product_manufacturer; ?></div>
													<div class="product-model"><?php echo $text_product_model; ?></div>
													<div class="product-stock"><?php echo $text_product_stock; ?></div>
													<button type="button" class="btn button-cart"><?php echo $button_cart; ?></button>												
												</div>
											</div>
										</div>
										<?php } ?>
										<?php if ($message['page_code'] == 'home') { ?>
										<div class="section-home">
											<div class="section-title"><?php echo $text_home; ?></div>
											<div class="table-menu">
												<div class="table-row">
													<div class="table-col"><?php echo $text_menu_desktops; ?></div>
													<div class="table-col"><?php echo $text_menu_laptops; ?></div>
													<div class="table-col"><?php echo $text_menu_components; ?></div>
													<div class="table-col"><?php echo $text_menu_tablets; ?></div>
													<div class="table-col"><?php echo $text_menu_software; ?></div>
													<div class="table-col"><?php echo $text_menu_cameras; ?></div>
												</div>
											</div>
											<div id="paypal_message_home" class="paypal-message">
												<div id="paypal_message_home_container" class="paypal-message-container paypal-spinner"></div>
											</div>	
										</div>
										<?php } ?>
									</div>
									<div class="col col-lg-6">
										<div class="section-message-setting">
											<div class="row">
												<div class="col col-md-6">
													<legend class="legend"><?php echo $text_message_settings; ?></legend>
												</div>
												<div class="col col-md-6">
													<div class="form-group-status">
														<label class="control-label" for="input_message_<?php echo $message['page_code']; ?>_status"><span data-toggle="tooltip" title="<?php echo $help_message_status; ?>"><?php echo $entry_status; ?></span></label>
														<input type="hidden" name="paypal_setting[message][<?php echo $message['page_code']; ?>][status]" value="0" />
														<input type="checkbox" name="paypal_setting[message][<?php echo $message['page_code']; ?>][status]" value="1" class="switch" <?php if ($message['status']) { ?>checked="checked"<?php } ?> />
													</div>
												</div>
											</div>
											<?php if ($text_message_alert) { ?>
											<div class="form-group">
												<p class="alert alert-info"><?php echo $text_message_alert; ?></p>
											</div>
											<div class="form-group">
												<p class="footnote"><?php echo $text_message_footnote; ?></p>
											</div>
											<?php } ?>
											<?php if ($message['page_code'] != 'checkout') { ?>
											<div class="row">
												<div class="col col-md-6">
													<div class="form-group">
														<label class="control-label" for="input_message_<?php echo $message['page_code']; ?>_insert_tag"><?php echo $entry_message_insert_tag; ?></label>
														<input type="text" name="paypal_setting[message][<?php echo $message['page_code']; ?>][insert_tag]" value="<?php echo $message['insert_tag']; ?>" id="input_button_<?php echo $message['page_code']; ?>_insert_tag" class="form-control" />
													</div>
												</div>
												<div class="col col-md-6">
													<div class="form-group">
														<label class="control-label" for="input_message_<?php echo $message['page_code']; ?>_insert_type"><?php echo $entry_message_insert_type; ?></label>
														<select name="paypal_setting[message][<?php echo $message['page_code']; ?>][insert_type]" id="input_message_<?php echo $message['page_code']; ?>_insert_type" class="form-control">
															<?php foreach ($setting['message_insert_type'] as $message_insert_type) { ?>
															<?php if ($message_insert_type['code'] == $message['insert_type']) { ?>
															<option value="<?php echo $message_insert_type['code']; ?>" selected="selected"><?php echo ${$message_insert_type['name']}; ?></option>
															<?php } else { ?>
															<option value="<?php echo $message_insert_type['code']; ?>"><?php echo ${$message_insert_type['name']}; ?></option>
															<?php } ?>
															<?php } ?>
														</select>
													</div>
												</div>
											</div>
											<?php } ?>
											<div class="row">
												<div class="col col-md-6">
													<div class="form-group">
														<label class="control-label" for="input_message_<?php echo $message['page_code']; ?>_align"><?php echo $entry_message_align; ?></label>
														<select name="paypal_setting[message][<?php echo $message['page_code']; ?>][align]" id="input_message_<?php echo $message['page_code']; ?>_align" class="form-control control-paypal-message">
															<?php foreach ($setting['message_align'] as $message_align) { ?>
															<?php if ($message_align['code'] == $message['align']) { ?>
															<option value="<?php echo $message_align['code']; ?>" selected="selected"><?php echo ${$message_align['name']}; ?></option>
															<?php } else { ?>
															<option value="<?php echo $message_align['code']; ?>"><?php echo ${$message_align['name']}; ?></option>
															<?php } ?>
															<?php } ?>
														</select>
													</div>
													<div class="form-group">
														<label class="control-label" for="input_message_<?php echo $message['page_code']; ?>_size"><?php echo $entry_message_size; ?></label>
														<select name="paypal_setting[message][<?php echo $message['page_code']; ?>][size]" id="input_message_<?php echo $message['page_code']; ?>_size" class="form-control control-paypal-message">
															<?php foreach ($setting['message_size'] as $message_size) { ?>
															<?php if ($message_size['code'] == $message['size']) { ?>
															<option value="<?php echo $message_size['code']; ?>" selected="selected"><?php echo ${$message_size['name']}; ?></option>
															<?php } else { ?>
															<option value="<?php echo $message_size['code']; ?>"><?php echo ${$message_size['name']}; ?></option>
															<?php } ?>
															<?php } ?>
														</select>
													</div>
													<div class="form-group">
														<label class="control-label" for="input_message_<?php echo $message['page_code']; ?>_layout"><?php echo $entry_message_layout; ?></label>
														<select name="paypal_setting[message][<?php echo $message['page_code']; ?>][layout]" id="input_message_<?php echo $message['page_code']; ?>_layout" class="form-control input-message-layout control-paypal-message">
															<?php foreach ($setting['message_layout'] as $message_layout) { ?>
															<?php if ($message_layout['code'] == $message['layout']) { ?>
															<option value="<?php echo $message_layout['code']; ?>" selected="selected"><?php echo ${$message_layout['name']}; ?></option>
															<?php } else { ?>
															<option value="<?php echo $message_layout['code']; ?>"><?php echo ${$message_layout['name']}; ?></option>
															<?php } ?>
															<?php } ?>
														</select>
													</div>
												</div>
												<div class="col col-md-6">
													<div class="form-group <?php if ($setting['message'][$message['page_code']]['layout'] == 'flex') { ?>hidden<?php } ?>">
														<label class="control-label" for="input_message_<?php echo $message['page_code']; ?>_text_color"><?php echo $entry_message_text_color; ?></label>
														<select name="paypal_setting[message][<?php echo $message['page_code']; ?>][text_color]" id="input_message_<?php echo $message['page_code']; ?>_text_color" class="form-control control-paypal-message">
															<?php foreach ($setting['message_text_color'] as $message_text_color) { ?>
															<?php if ($message_text_color['code'] == $message['text_color']) { ?>
															<option value="<?php echo $message_text_color['code']; ?>" selected="selected"><?php echo ${$message_text_color['name']}; ?></option>
															<?php } else { ?>
															<option value="<?php echo $message_text_color['code']; ?>"><?php echo ${$message_text_color['name']}; ?></option>
															<?php } ?>
															<?php } ?>
														</select>
													</div>
													<div class="form-group <?php if ($setting['message'][$message['page_code']]['layout'] == 'flex') { ?>hidden<?php } ?>">
														<label class="control-label" for="input_message_<?php echo $message['page_code']; ?>_text_size"><?php echo $entry_message_text_size; ?></label>
														<select name="paypal_setting[message][<?php echo $message['page_code']; ?>][text_size]" id="input_message_<?php echo $message['page_code']; ?>_text_size" class="form-control control-paypal-message">
															<?php foreach ($setting['message_text_size'] as $message_text_size) { ?>
															<?php if ($message_text_size == $message['text_size']) { ?>
															<option value="<?php echo $message_text_size; ?>" selected="selected"><?php echo $message_text_size; ?></option>
															<?php } else { ?>
															<option value="<?php echo $message_text_size; ?>"><?php echo $message_text_size; ?></option>
															<?php } ?>
															<?php } ?>
														</select>
													</div>
													<div class="form-group <?php if ($setting['message'][$message['page_code']]['layout'] == 'text') { ?>hidden<?php } ?>">
														<label class="control-label" for="input_message_<?php echo $message['page_code']; ?>_flex_color"><?php echo $entry_message_flex_color; ?></label>
														<select name="paypal_setting[message][<?php echo $message['page_code']; ?>][flex_color]" id="input_message_<?php echo $message['page_code']; ?>_flex_color" class="form-control control-paypal-message">
															<?php foreach ($setting['message_flex_color'] as $message_flex_color) { ?>
															<?php if ($message_flex_color['code'] == $message['flex_color']) { ?>
															<option value="<?php echo $message_flex_color['code']; ?>" selected="selected"><?php echo ${$message_flex_color['name']}; ?></option>
															<?php } else { ?>
															<option value="<?php echo $message_flex_color['code']; ?>"><?php echo ${$message_flex_color['name']}; ?></option>
															<?php } ?>
															<?php } ?>
														</select>
													</div>
													<div class="form-group <?php if ($setting['message'][$message['page_code']]['layout'] == 'text') { ?>hidden<?php } ?>">
														<label class="control-label" for="input_message_<?php echo $message['page_code']; ?>_flex_ratio"><?php echo $entry_message_flex_ratio; ?></label>
														<select name="paypal_setting[message][<?php echo $message['page_code']; ?>][flex_ratio]" id="input_message_<?php echo $message['page_code']; ?>_flex_ratio" class="form-control control-paypal-message">
															<?php foreach ($setting['message_flex_ratio'] as $message_flex_ratio) { ?>
															<?php if ($message_flex_ratio == $message['flex_ratio']) { ?>
															<option value="<?php echo $message_flex_ratio; ?>" selected="selected"><?php echo $message_flex_ratio; ?></option>
															<?php } else { ?>
															<option value="<?php echo $message_flex_ratio; ?>"><?php echo $message_flex_ratio; ?></option>
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
							<?php $i++; ?>
							<?php } ?>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

var message_width = JSON.parse('<?php echo json_encode($setting['message_width']); ?>');

updatePayPalMessage('checkout', function() {
	updatePayPalMessage('home', function() {
		updatePayPalMessage('product', function() {
			updatePayPalMessage('cart');
		});
	});
});

$('.payment-paypal .switch').bootstrapSwitch({
    'onColor': 'success',
    'onText': '<?php echo $text_on; ?>',
    'offText': '<?php echo $text_off; ?>'
});

$('.payment-paypal').on('change', '.control-paypal-message', function() {
	var page_code = $(this).parents('.tab-pane').attr('id').replace('pill_', '');
	
	updatePayPalMessage(page_code);
});

$('.input-message-layout').on('change', function() {
	var page_code = $(this).parents('.tab-pane').attr('id').replace('pill_', '');
	var layout = $(this).val();
	
	if (layout == 'text') {
		$('#input_message_' + page_code + '_flex_color').parents('.form-group').addClass('hidden');
		$('#input_message_' + page_code + '_flex_ratio').parents('.form-group').addClass('hidden');
		$('#input_message_' + page_code + '_text_color').parents('.form-group').removeClass('hidden');
		$('#input_message_' + page_code + '_text_size').parents('.form-group').removeClass('hidden');
	} else {
		$('#input_message_' + page_code + '_text_color').parents('.form-group').addClass('hidden');
		$('#input_message_' + page_code + '_text_size').parents('.form-group').addClass('hidden');
		$('#input_message_' + page_code + '_flex_color').parents('.form-group').removeClass('hidden');
		$('#input_message_' + page_code + '_flex_ratio').parents('.form-group').removeClass('hidden');
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
				$('.payment-paypal > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> ' + json['success'] + '</div>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

function updatePayPalMessage(message_page_code, paypal_callback = '') {								
	var paypal_data = {};

	paypal_data['page_code'] = message_page_code;
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
	paypal_data['components'] = ['messages'];
	paypal_data['message_align'] = $('.payment-paypal #input_message_' + message_page_code + '_align').val();
	paypal_data['message_size'] = $('.payment-paypal #input_message_' + message_page_code + '_size').val();
	paypal_data['message_layout'] = $('.payment-paypal #input_message_' + message_page_code + '_layout').val();
	paypal_data['message_text_color'] = $('.payment-paypal #input_message_' + message_page_code + '_text_color').val();
	paypal_data['message_text_size'] = $('.payment-paypal #input_message_' + message_page_code + '_text_size').val();
	paypal_data['message_flex_color'] = $('.payment-paypal #input_message_' + message_page_code + '_flex_color').val();
	paypal_data['message_flex_ratio'] = $('.payment-paypal #input_message_' + message_page_code + '_flex_ratio').val();
	
	paypal_data['message_width'] = message_width[paypal_data['message_size']];
		
	PayPalAPI.init(paypal_data, paypal_callback);
}

</script>
<?php echo $footer; ?>