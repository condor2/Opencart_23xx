<?php echo $header; ?><?php echo $column_left; ?>
<div id="content" class="payment-paypal">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
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
		<div class="panel panel-default panel-auth">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			</div>
			<div class="panel-body">
				<div class="section-connect">
					<div class="icon icon-logo"></div>
					<div class="welcome"><?php echo $text_welcome; ?></div>
					<div class="row">
						<div class="col col-sm-6 text-left">
							<div class="form-group">
								<label class="control-label" for="input_authorization_type"><?php echo $entry_authorization_type; ?></label>
								<select name="paypal_authorization_type" id="input_authorization_type" class="form-control">
									<?php if ($authorization_type == 'automatic') { ?>
									<option value="automatic" selected="selected"><?php echo $text_automatic; ?></option>
									<option value="manual"><?php echo $text_manual; ?></option>
									<?php } else { ?>
									<option value="automatic"><?php echo $text_automatic; ?></option>
									<option value="manual" selected="selected"><?php echo $text_manual; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col col-sm-6 text-left">
							<div class="form-group">
								<label class="control-label" for="input_environment"><?php echo $entry_environment; ?></label>
								<select name="paypal_environment" id="input_environment" class="form-control">
									<?php if ($environment == 'production') { ?>
									<option value="production" selected="selected"><?php echo $text_production; ?></option>
									<option value="sandbox"><?php echo $text_sandbox; ?></option>
									<?php } else { ?>
									<option value="production"><?php echo $text_production; ?></option>
									<option value="sandbox" selected="selected"><?php echo $text_sandbox; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<div class="automatic-authorization <?php if ($authorization_type == 'manual') { ?>hidden<?php } ?>">
						<a href="<?php echo $configure_url[$environment]['ppcp']; ?>" target="_blank" class="btn btn-primary button-connect-ppcp" data-paypal-button="PPLtBlue" data-paypal-onboard-complete="onBoardedCallback"><?php echo $button_connect; ?></a>
						<div class="checkout-express"><?php echo $text_checkout_express; ?></div>
					</div>
					<div class="manual-authorization <?php if ($authorization_type == 'automatic') { ?>hidden<?php } ?>">
						<div class="form-group text-left">
							<label class="control-label" for="input_merchant_id"><?php echo $entry_merchant_id; ?></label>
							<input type="text" name="payment_paypal_merchant_id" id="input_merchant_id" class="form-control" />
						</div>
						<div class="form-group text-left">
							<label class="control-label" for="input_client_id"><?php echo $entry_client_id; ?></label>
							<input type="text" name="payment_paypal_client_id" id="input_client_id" class="form-control" />
						</div>
						<div class="form-group text-left">
							<label class="control-label" for="input_client_secret"><?php echo $entry_client_secret; ?></label>
							<div class="input-group">
								<input type="password" name="payment_paypal_client_secret" id="input_client_secret" class="form-control" />
								<span class="input-group-btn">
									<button type="button" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-default view-password" field_id="input_client_secret"><i class="fa fa-eye"></i></button>
								</span>
							</div>
						</div>
						<div class="form-group">
							<div class="button-group">
								<button type="button" class="btn btn-primary button-connect" data-loading-text="<?php echo $text_loading; ?>"><?php echo $button_connect; ?></button>
							</div>
						</div>
					</div>
					<div class="support"><?php echo $text_support; ?></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="https://www.paypal.com/webapps/merchantboarding/js/lib/lightbox/partner.js"></script>
<script type="text/javascript">

$('#input_authorization_type').on('change', function() {
	var authorization_type = $(this).val();
	
	if (authorization_type == 'automatic') {
		$('.automatic-authorization').removeClass('hidden');
		$('.manual-authorization').addClass('hidden');
	} else {
		$('.automatic-authorization').addClass('hidden');
		$('.manual-authorization').removeClass('hidden');
	}	
});

$('#input_environment').on('change', function() {
	var environment = $(this).val();
	
	if (environment == 'production') {
		$('.button-connect-ppcp').attr('href', '<?php echo $configure_url['production']['ppcp']; ?>');
		$('.button-connect-express-checkout').attr('href', '<?php echo $configure_url['production']['express_checkout']; ?>');
	} else {
		$('.button-connect-ppcp').attr('href', '<?php echo $configure_url['sandbox']['ppcp']; ?>');
		$('.button-connect-express_checkout').attr('href', '<?php echo $configure_url['sandbox']['express_checkout']; ?>');
	}	
});

$('.payment-paypal').on('click', '.view-password', function(event) {
	event.preventDefault();
	
	if ($('#' + $(this).attr('field_id')).attr('type') == 'password') {
		$('#' + $(this).attr('field_id')).attr('type', 'text');
	} else {
		$('#' + $(this).attr('field_id')).attr('type', 'password');
	}
});

$('.payment-paypal').on('click', '.button-connect', function() {	
	var environment = $('#input_environment').val();
	var client_id = $('#input_client_id').val();
	var client_secret = $('#input_client_secret').val();
	var merchant_id = $('#input_merchant_id').val();
	
	$('.payment-paypal .button-connect').prop('disabled', true).button('loading');
	
	$.ajax({
		type: 'post',
		url: '<?php echo $connect_url; ?>',
		data: 'environment=' + environment + '&merchant_id=' + merchant_id + '&client_id=' + client_id + '&client_secret=' + client_secret,
		dataType: 'json',
		success: function(json) {
			$('.payment-paypal .button-connect').prop('disabled', false).button('reset');	
			
			$('.payment-paypal .alert').remove();
						
			if (json['error'] && json['error']['warning']) {
				$('.payment-paypal > .container-fluid').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
				$('html, body').animate({scrollTop: $('#content > .container-fluid .alert-danger').offset().top}, 'slow');
			} else {
				location.reload();
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

function onBoardedCallback(authorization_code, shared_id) {
	var environment = $('#input_environment').val();
	
	$.ajax({
		url: '<?php echo $callback_url; ?>',
		type: 'post',
		data: 'environment=' + environment + '&authorization_code=' + authorization_code + '&shared_id=' + shared_id + '&seller_nonce=<?php echo $seller_nonce; ?>',
		dataType: 'json',
		success: function(json) {
			
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

</script>
<?php echo $footer; ?>