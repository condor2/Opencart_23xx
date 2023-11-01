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
					<a href="<?php echo $configure_url[$environment]['ppcp']; ?>" target="_blank" class="btn btn-primary button-connect-ppcp" data-paypal-button="PPLtBlue" data-paypal-onboard-complete="onBoardedCallback"><?php echo $button_connect; ?></a>
					<div class="checkout-express"><?php echo $text_checkout_express; ?></div>
					<div class="support"><?php echo $text_support; ?></div>
					<div class="row">
						<div class="col col-sm-offset-4 col-sm-4 text-left">
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
				</div>
			</div>
		</div>
	</div>
</div>
<script src="https://www.paypal.com/webapps/merchantboarding/js/lib/lightbox/partner.js"></script>
<script type="text/javascript">

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