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
				<a href="<?php echo $href_dashboard; ?>" class="back-dashboard"><i class="icon icon-back-dashboard"></i><?php echo $text_tab_dashboard; ?></a>
				<ul class="nav nav-tabs">
					<li class="nav-tab"><a href="<?php echo $href_general; ?>" class="tab"><i class="tab-icon tab-icon-general"></i><span class="tab-title"><?php echo $text_tab_general; ?></span></a></li>
					<li class="nav-tab"><a href="<?php echo $href_button; ?>" class="tab"><i class="tab-icon tab-icon-button"></i><span class="tab-title"><?php echo $text_tab_button; ?></span></a></li>
					<li class="nav-tab"><a href="<?php echo $href_googlepay_button; ?>" class="tab"><i class="tab-icon tab-icon-googlepay-button"></i><span class="tab-title"><?php echo $text_tab_googlepay_button; ?></span></a></li>
					<li class="nav-tab"><a href="<?php echo $href_applepay_button; ?>" class="tab"><i class="tab-icon tab-icon-applepay-button"></i><span class="tab-title"><?php echo $text_tab_applepay_button; ?></span></a></li>
					<li class="nav-tab"><a href="<?php echo $href_card; ?>" class="tab"><i class="tab-icon tab-icon-card"></i><span class="tab-title"><?php echo $text_tab_card; ?></span></a></li>
					<li class="nav-tab"><a href="<?php echo $href_fastlane; ?>" class="tab"><i class="tab-icon tab-icon-fastlane"></i><span class="tab-title"><?php echo $text_tab_fastlane; ?></span></a></li>
					<li class="nav-tab active"><a href="<?php echo $href_message_configurator; ?>" class="tab"><i class="tab-icon tab-icon-message-configurator"></i><span class="tab-title"><?php echo $text_tab_message_configurator; ?></span></a></li>
					<li class="nav-tab"><a href="<?php echo $href_message_setting; ?>" class="tab"><i class="tab-icon tab-icon-message-setting"></i><span class="tab-title"><?php echo $text_tab_message_setting; ?></span></a></li>
					<li class="nav-tab"><a href="<?php echo $href_order_status; ?>" class="tab"><i class="tab-icon tab-icon-order-status"></i><span class="tab-title"><?php echo $text_tab_order_status; ?></span></a></li>
					<li class="nav-tab"><a href="<?php echo $href_contact; ?>" class="tab"><i class="tab-icon tab-icon-contact"></i><span class="tab-title"><?php echo $text_tab_contact; ?></span></a></li>
				</ul>
					
				<div class="section-content">
					<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form_payment">
						<?php foreach ($setting['message'] as $message) { ?>
						<input type="hidden" name="paypal_setting[message][<?php echo $message['page_code']; ?>][status]" value="<?php echo $message['status']; ?>" id="input_message_<?php echo $message['page_code']; ?>_status" />
						<input type="hidden" name="paypal_setting[message][<?php echo $message['page_code']; ?>][layout]" value="<?php echo $message['layout']; ?>" id="input_message_<?php echo $message['page_code']; ?>_layout" />
						<input type="hidden" name="paypal_setting[message][<?php echo $message['page_code']; ?>][logo_type]" value="<?php echo $message['logo_type']; ?>" id="input_message_<?php echo $message['page_code']; ?>_logo_type" />
						<input type="hidden" name="paypal_setting[message][<?php echo $message['page_code']; ?>][logo_position]" value="<?php echo $message['logo_position']; ?>" id="input_message_<?php echo $message['page_code']; ?>_logo_position" />
						<input type="hidden" name="paypal_setting[message][<?php echo $message['page_code']; ?>][text_color]" value="<?php echo $message['text_color']; ?>" id="input_message_<?php echo $message['page_code']; ?>_text_color" />
						<input type="hidden" name="paypal_setting[message][<?php echo $message['page_code']; ?>][text_size]" value="<?php echo $message['text_size']; ?>" id="input_message_<?php echo $message['page_code']; ?>_text_size" />
						<input type="hidden" name="paypal_setting[message][<?php echo $message['page_code']; ?>][flex_color]" value="<?php echo $message['flex_color']; ?>" id="input_message_<?php echo $message['page_code']; ?>_flex_color" />
						<input type="hidden" name="paypal_setting[message][<?php echo $message['page_code']; ?>][flex_ratio]" value="<?php echo $message['flex_ratio']; ?>" id="input_message_<?php echo $message['page_code']; ?>_flex_ratio" />
						<?php } ?>
					</form>
					<div id="messaging-configurator"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

readyMerchantConfigurators();

$('.payment-paypal').on('click', '.button-save', function() {
	$('.payment-paypal .buttonOverride').trigger('click');
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

function readyMerchantConfigurators() {
	if (typeof merchantConfigurators === 'undefined') {
		setTimeout(readyMerchantConfigurators, 100);
	} else {
		initMerchantConfigurators();
	}
}

function initMerchantConfigurators() {		
	window.merchantConfigurators.Messaging({
		locale: '<?php echo $locale; ?>',
		merchantIdentifier: '<?php echo $client_id; ?>',
		partnerClientId:'<?php echo $partner_client_id; ?>',
		partnerName: 'Opencart',
		bnCode: '<?php echo $partner_attribution_id; ?>',
		placements: ['checkout', 'cart', 'product', 'homepage'],
		styleOverrides: {
			button: 'buttonOverride',
			header: 'headerOverride'
		},
		config: {
			'checkout': {
				'placement': 'checkout',
				'status': (($('.payment-paypal #input_message_checkout_status').val() == 1) ? 'enabled' : 'disabled'),
				'layout': $('.payment-paypal #input_message_checkout_layout').val(),
				'logo-type': $('.payment-paypal #input_message_checkout_logo_type').val(),
				'logo-position': $('.payment-paypal #input_message_checkout_logo_position').val(),
				'text-color': $('.payment-paypal #input_message_checkout_text_color').val(),
				'text-size': $('.payment-paypal #input_message_checkout_text_size').val()
			},
			'cart': {
				'placement': 'cart',
				'status': (($('.payment-paypal #input_message_cart_status').val() == 1) ? 'enabled' : 'disabled'),
				'layout': $('.payment-paypal #input_message_cart_layout').val(),
				'logo-type': $('.payment-paypal #input_message_cart_logo_type').val(),
				'logo-position': $('.payment-paypal #input_message_cart_logo_position').val(),
				'text-color': $('.payment-paypal #input_message_cart_text_color').val(),
				'text-size': $('.payment-paypal #input_message_cart_text_size').val()
			},
			'product': {
				'placement': 'product',
				'status': (($('.payment-paypal #input_message_product_status').val() == 1) ? 'enabled' : 'disabled'),
				'layout': $('.payment-paypal #input_message_product_layout').val(),
				'logo-type': $('.payment-paypal #input_message_product_logo_type').val(),
				'logo-position': $('.payment-paypal #input_message_product_logo_position').val(),
				'text-color': $('.payment-paypal #input_message_product_text_color').val(),
				'text-size': $('.payment-paypal #input_message_product_text_size').val()
			},
			'homepage': {
				'placement': 'homepage',
				'status': (($('.payment-paypal #input_message_home_status').val() == 1) ? 'enabled' : 'disabled'),
				'layout': $('.payment-paypal #input_message_home_layout').val(),
				'color': $('.payment-paypal #input_message_home_flex_color').val(),
				'ratio': $('.payment-paypal #input_message_home_flex_ratio').val()
			}
		},
		onSave: function(data) {
			$('.payment-paypal #input_message_checkout_status').val((data['config']['checkout']['status'] == 'enabled') ? 1 : 0);
			$('.payment-paypal #input_message_checkout_layout').val(data['config']['checkout']['layout']);
			$('.payment-paypal #input_message_checkout_logo_type').val(data['config']['checkout']['logo-type']);
			$('.payment-paypal #input_message_checkout_logo_position').val(data['config']['checkout']['logo-position']);
			$('.payment-paypal #input_message_checkout_text_color').val(data['config']['checkout']['text-color']);
			$('.payment-paypal #input_message_checkout_text_size').val(data['config']['checkout']['text-size']);
			
			$('.payment-paypal #input_message_cart_status').val((data['config']['cart']['status'] == 'enabled') ? 1 : 0);
			$('.payment-paypal #input_message_cart_layout').val(data['config']['cart']['layout']);
			$('.payment-paypal #input_message_cart_logo_type').val(data['config']['cart']['logo-type']);
			$('.payment-paypal #input_message_cart_logo_position').val(data['config']['cart']['logo-position']);
			$('.payment-paypal #input_message_cart_text_color').val(data['config']['cart']['text-color']);
			$('.payment-paypal #input_message_cart_text_size').val(data['config']['cart']['text-size']);
			
			$('.payment-paypal #input_message_product_status').val((data['config']['product']['status'] == 'enabled') ? 1 : 0);
			$('.payment-paypal #input_message_product_layout').val(data['config']['product']['layout']);
			$('.payment-paypal #input_message_product_logo_type').val(data['config']['product']['logo-type']);
			$('.payment-paypal #input_message_product_logo_position').val(data['config']['product']['logo-position']);
			$('.payment-paypal #input_message_product_text_color').val(data['config']['product']['text-color']);
			$('.payment-paypal #input_message_product_text_size').val(data['config']['product']['text-size']);
			
			$('.payment-paypal #input_message_home_status').val((data['config']['homepage']['status'] == 'enabled') ? 1 : 0);
			$('.payment-paypal #input_message_home_layout').val(data['config']['homepage']['layout']);
			$('.payment-paypal #input_message_home_flex_color').val(data['config']['homepage']['color']);
			$('.payment-paypal #input_message_home_flex_ratio').val(data['config']['homepage']['ratio']);
			
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
		}
	});
}

</script>
<?php echo $footer; ?>