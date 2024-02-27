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
						<li class="nav-tab"><a href="<?php echo $href_message_configurator; ?>" class="tab"><i class="tab-icon tab-icon-message-configurator"></i><span class="tab-title"><?php echo $text_tab_message_configurator; ?></span></a></li>
						<li class="nav-tab"><a href="<?php echo $href_message_setting; ?>" class="tab"><i class="tab-icon tab-icon-message-setting"></i><span class="tab-title"><?php echo $text_tab_message_setting; ?></span></a></li>
						<li class="nav-tab"><a href="<?php echo $href_order_status; ?>" class="tab"><i class="tab-icon tab-icon-order-status"></i><span class="tab-title"><?php echo $text_tab_order_status; ?></span></a></li>
						<li class="nav-tab active"><a href="<?php echo $href_contact; ?>" class="tab"><i class="tab-icon tab-icon-contact"></i><span class="tab-title"><?php echo $text_tab_contact; ?></span></a></li>
					</ul>
					<div class="section-content">
						<div class="row">
							<div class="col col-md-12">
								<legend class="legend"><?php echo $text_contact_business; ?></legend>
							</div>
							<div class="col col-md-6">
								<input type="hidden" name="paypal_setting[contact][oid]" value="<?php echo $setting['contact']['oid']; ?>" />
								<input type="hidden" name="paypal_setting[contact][retURL]" value="<?php echo $setting['contact']['retURL']; ?>">
								<input type="hidden" name="paypal_setting[contact][Vendor_Partner_ID_VPID_MAM__c]" value="<?php echo $setting['contact']['Vendor_Partner_ID_VPID_MAM__c']; ?>" />
								<input type="hidden" name="paypal_setting[contact][Campaign_ID__c]" value="<?php echo $setting['contact']['Campaign_ID__c']; ?>" />
								<input type="hidden" name="paypal_setting[contact][lead_source]" value="<?php echo $setting['contact']['lead_source']; ?>" />
								<input type="hidden" name="paypal_setting[contact][recordType]" value="<?php echo $setting['contact']['recordType']; ?>" />
								<div class="form-group">
									<label class="control-label" for="input_contact_company"><?php echo $entry_contact_company; ?></label>
									<input type="text" name="paypal_setting[contact][company]" value="<?php echo $setting['contact']['company']; ?>" id="input_contact_company" class="form-control" maxlength="40" />
								</div>
								<div class="form-group">
									<label class="control-label" for="input_contact_first_name"><?php echo $entry_contact_first_name; ?></label>
									<input type="text" name="paypal_setting[contact][first_name]" value="<?php echo $setting['contact']['first_name']; ?>" id="input_contact_first_name" class="form-control" maxlength="40" />
								</div>
								<div class="form-group">
									<label class="control-label" for="input_contact_last_name"><?php echo $entry_contact_last_name; ?></label>
									<input type="text" name="paypal_setting[contact][last_name]" value="<?php echo $setting['contact']['last_name']; ?>" id="input_contact_last_name" class="form-control" maxlength="80" />
								</div>
								<div class="form-group">
									<label class="control-label" for="input_contact_email"><?php echo $entry_contact_email; ?></label>
									<input type="text" name="paypal_setting[contact][email]" value="<?php echo $setting['contact']['email']; ?>" id="input_contact_email" class="form-control" maxlength="80" />
								</div>
								<div class="form-group">
									<label class="control-label" for="input_contact_url"><?php echo $entry_contact_url; ?></label>
									<input type="text" name="paypal_setting[contact][url]" value="<?php echo $setting['contact']['url']; ?>" id="input_contact_url" class="form-control" maxlength="80" />
								</div>
								<div class="form-group">
									<label class="control-label" for="input_contact_sales"><?php echo $entry_contact_sales; ?></label>
									<select name="paypal_setting[contact][00N30000000gJEZ]" id="input_contact_sales" class="form-control">
										<?php if ($setting['contact']['00N30000000gJEZ'] == '') { ?>
										<option value="" selected="selected"><?php echo $text_none; ?></option>
										<?php } else { ?>
										<option value=""><?php echo $text_none; ?></option>
										<?php } ?>
										<?php foreach ($setting['contact_sales'] as $contact_sales) { ?>
										<?php if ($contact_sales == $setting['contact']['00N30000000gJEZ']) { ?>
										<option value="<?php echo $contact_sales; ?>" selected="selected"><?php echo $contact_sales; ?></option>
										<?php } else { ?>
										<option value="<?php echo $contact_sales; ?>"><?php echo $contact_sales; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
								<div class="form-group">
									<label class="control-label" for="input_contact_phone"><?php echo $entry_contact_phone; ?></label>
									<input type="text" name="paypal_setting[contact][phone]" value="<?php echo $setting['contact']['phone']; ?>" id="input_contact_phone" class="form-control" maxlength="40" />
								</div>
							</div>
							<div class="col col-md-6">
								<div class="form-group">
									<label class="control-label" for="input_contact_country"><?php echo $entry_contact_country; ?></label>
									<select name="paypal_setting[contact][country]" id="input_contact_country" class="form-control">
										<?php foreach ($countries as $country) { ?>
										<?php if ($country['iso_code_2'] == $setting['contact']['country']) { ?>
										<option value="<?php echo $country['iso_code_2']; ?>" selected="selected"><?php echo $country['name']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $country['iso_code_2']; ?>"><?php echo $country['name']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
								<div class="form-group">
									<label class="control-label" for="input_contact_notes"><?php echo $entry_contact_notes; ?></label>
									<textarea name="paypal_setting[contact][00N2E00000II4xQ]" id="input_contact_notes" class="form-control"><?php echo $setting['contact']['00N2E00000II4xQ']; ?></textarea>
								</div>
								<div class="form-group">
									<div id="input_contact_merchant">
										<input type="hidden" name="paypal_setting[contact][00N2E00000II4xP]" value="0" />
										<input type="checkbox" name="paypal_setting[contact][00N2E00000II4xP]" value="1" class="form-check-input" <?php if ($setting['contact']['00N2E00000II4xP']) { ?>checked="checked"<?php } ?> /><label class="control-label"><?php echo $entry_contact_merchant; ?></label>
									</div>									
								</div>
								<div class="form-group">
									<label class="control-label" for="input_contact_merchant_name"><?php echo $entry_contact_merchant_name; ?></label>
									<input type="text" name="paypal_setting[contact][00N2E00000II4xO]" value="<?php echo $setting['contact']['00N2E00000II4xO']; ?>" id="input_contact_merchant_name" class="form-control" maxlength="55" />
								</div>
								<div class="form-group"></div>
								<legend class="legend"><?php echo $text_contact_product; ?></legend>
								<div class="form-group">
									<label class="control-label" for="input_contact_product"><?php echo $entry_contact_product; ?></label>
									<select name="paypal_setting[contact][00N80000004IGsC]" id="input_contact_product" class="form-control" multiple="multiple">
										<?php foreach ($setting['contact_product'] as $contact_product) { ?>
										<?php if ($contact_product['code'] == $setting['contact']['00N80000004IGsC']) { ?>
										<option value="<?php echo $contact_product['code']; ?>" selected="selected"><?php echo ${$contact_product['name']}; ?></option>
										<?php } else { ?>
										<option value="<?php echo $contact_product['code']; ?>"><?php echo ${$contact_product['name']}; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
								<div class="form-group text-right">
									<button type="button" class="btn btn-primary button-send"><?php echo $button_send; ?></button>
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

$('.payment-paypal').on('click', '.button-send', function() {
	$.ajax({
		type: 'post',
		url: '<?php echo $contact_url; ?>',
		data: $('#form_payment').serialize(),
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
	});
});

$('.payment-paypal').on('click', '.button-save', function() {
    $.ajax({
		type: 'post',
		url: $('#form_payment').attr('action'),
		data: $('#form_payment').serialize(),
		dataType: 'json',
		success: function(json) {
			$('.payment-paypal .alert').remove();
			
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
	$('.alert').remove();
	
	$.ajax({
		type: 'post',
		url: '<?php echo $agree_url; ?>',
		data: '',
		dataType: 'json',
		success: function(json) {
			$('.payment-paypal .alert-success').remove();
			
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