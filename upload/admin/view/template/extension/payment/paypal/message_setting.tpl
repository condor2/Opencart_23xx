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
						<li class="nav-tab"><a href="<?php echo $href_fastlane; ?>" class="tab"><i class="tab-icon tab-icon-fastlane"></i><span class="tab-title"><?php echo $text_tab_fastlane; ?></span></a></li>
						<li class="nav-tab"><a href="<?php echo $href_message_configurator; ?>" class="tab"><i class="tab-icon tab-icon-message-configurator"></i><span class="tab-title"><?php echo $text_tab_message_configurator; ?></span></a></li>
						<li class="nav-tab active"><a href="<?php echo $href_message_setting; ?>" class="tab"><i class="tab-icon tab-icon-message-setting"></i><span class="tab-title"><?php echo $text_tab_message_setting; ?></span></a></li>
						<li class="nav-tab"><a href="<?php echo $href_order_status; ?>" class="tab"><i class="tab-icon tab-icon-order-status"></i><span class="tab-title"><?php echo $text_tab_order_status; ?></span></a></li>
						<li class="nav-tab"><a href="<?php echo $href_contact; ?>" class="tab"><i class="tab-icon tab-icon-contact"></i><span class="tab-title"><?php echo $text_tab_contact; ?></span></a></li>
					</ul>
					<div class="section-content">
						<ul class="nav nav-pills">
							<?php foreach ($setting['message'] as $message) { ?>
							<?php if ($message['page_code'] != 'checkout') { ?>
							<li class="nav-pill <?php if ($message['page_code'] == 'cart') { ?>active<?php } ?>"><a href="#pill_<?php echo $message['page_code']; ?>" class="pill" data-toggle="tab"><?php echo ${$message['page_name']}; ?></a></li>
							<?php } ?>
							<?php } ?>
						</ul>
						<hr class="hr" />
						<div class="tab-content">
							<?php foreach ($setting['message'] as $message) { ?>
							<?php if ($message['page_code'] != 'checkout') { ?>
							<div id="pill_<?php echo $message['page_code']; ?>" class="tab-pane <?php if ($message['page_code'] == 'cart') { ?>active<?php } ?>">
								<div class="section-message-setting">
									<div class="row">
										<div class="col col-md-6">
											<legend class="legend"><?php echo $text_message_settings; ?></legend>
										</div>
									</div>
									<?php if ($text_message_alert) { ?>
									<?php /*<div class="form-group">
										<p class="alert alert-info"><?php echo $text_message_alert; ?></p>
									</div>
									<div class="form-group">
										<p class="footnote"><?php echo $text_message_footnote; ?></p>
									</div>
									*/ ?>
									<?php } ?>
									<div class="row">
										<div class="col col-md-6">
											<div class="form-group">
												<label class="control-label" for="input_message_<?php echo $message['page_code']; ?>_insert_tag"><?php echo $entry_message_insert_tag; ?></label>
												<input type="text" name="paypal_setting[message][<?php echo $message['page_code']; ?>][insert_tag]" value="<?php echo $message['insert_tag']; ?>" id="input_message_<?php echo $message['page_code']; ?>_insert_tag" class="form-control" />
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
								</div>
							</div>
							<?php } ?>
							<?php } ?>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

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
				$('.payment-paypal > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> ' + json['success'] + '</div>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

</script>
<?php echo $footer; ?>