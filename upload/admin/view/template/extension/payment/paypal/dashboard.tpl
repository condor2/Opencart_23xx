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
		<div class="panel panel-default panel-dashboard">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form_payment">
					<div class="row">
						<div class="col col-sm-6">
							<div class="paypal-sale">
								<span class="paypal-sale-title"><?php echo $text_paypal_sales; ?>:</span> <span class="paypal-sale-total"><?php echo $paypal_sale_total; ?></span>
							</div>
						</div>
						<div class="col col-sm-6">
							<div class="form-group-status">
								<label class="control-label" for="input_status"><?php echo $entry_status; ?></label>
								<input type="hidden" name="paypal_status" value="0" />
								<input type="checkbox" name="paypal_status" value="1" class="switch" <?php if ($status) { ?>checked="checked"<?php } ?> />
							</div>
						</div>
					</div>
					<div class="row row-tab">
						<div class="col col-sm-6 col-md-4 col-lg-3 col-tab">
							<a href="<?php echo $href_general; ?>" class="tab">
								<i class="tab-icon-status tab-icon-status-<?php if ($status) { ?>on<?php } else { ?>off<?php } ?>"></i>
								<i class="tab-icon tab-icon-general"></i>
								<span class="tab-title"><?php echo $text_tab_general; ?></span>
							</a>
						</div>
						<div class="col col-sm-6 col-md-4 col-lg-3 col-tab">
							<a href="<?php echo $href_button; ?>" class="tab">
								<i class="tab-icon-status tab-icon-status-<?php if ($button_status) { ?>on<?php } else { ?>off<?php } ?>"></i>
								<i class="tab-icon tab-icon-button"></i>
								<span class="tab-title"><?php echo $text_tab_button; ?></span>
							</a>
						</div>
						<div class="col col-sm-6 col-md-4 col-lg-3 col-tab">
							<a href="<?php echo $href_googlepay_button; ?>" class="tab">
								<i class="tab-icon-status tab-icon-status-<?php if ($googlepay_button_status) { ?>on<?php } else { ?>off<?php } ?>"></i>
								<i class="tab-icon tab-icon-googlepay-button"></i>
								<span class="tab-title"><?php echo $text_tab_googlepay_button; ?></span>
							</a>
						</div>
						<div class="col col-sm-6 col-md-4 col-lg-3 col-tab">
							<a href="<?php echo $href_applepay_button; ?>" class="tab">
								<i class="tab-icon-status tab-icon-status-<?php if ($applepay_button_status) { ?>on<?php } else { ?>off<?php } ?>"></i>
								<i class="tab-icon tab-icon-applepay-button"></i>
								<span class="tab-title"><?php echo $text_tab_applepay_button; ?></span>
							</a>
						</div>
						<div class="col col-sm-6 col-md-4 col-lg-3 col-tab">
							<a href="<?php echo $href_card; ?>" class="tab">
								<i class="tab-icon-status tab-icon-status-<?php if ($card_status) { ?>on<?php } else { ?>off<?php } ?>"></i>
								<i class="tab-icon tab-icon-card"></i>
								<span class="tab-title"><?php echo $text_tab_card; ?></span>
							</a>
						</div>
						<div class="col col-sm-6 col-md-4 col-lg-3 col-tab">
							<a href="<?php echo $href_message; ?>" class="tab">
								<i class="tab-icon-status tab-icon-status-<?php if ($message_status) { ?>on<?php } else { ?>off<?php } ?>"></i>
								<i class="tab-icon tab-icon-message"></i>
								<span class="tab-title"><?php echo $text_tab_message; ?></span>
							</a>
						</div>
						<div class="col col-sm-6 col-md-4 col-lg-3 col-tab">
							<a href="<?php echo $href_order_status ; ?>" class="tab">
								<i class="tab-icon tab-icon-order-status"></i>
								<span class="tab-title"><?php echo $text_tab_order_status; ?></span>
							</a>
						</div>
						<div class="col col-sm-6 col-md-4 col-lg-3 col-tab">
							<a href="<?php echo $href_contact; ?>" class="tab">
								<i class="tab-icon tab-icon-contact"></i>
								<span class="tab-title"><?php echo $text_tab_contact; ?></span>
							</a>
						</div>
					</div>
					<div class="row flex-row">
						<div class="col col-lg-3">
							<div class="panel panel-default panel-statistic">
								<div class="panel-heading">
									<h3 class="panel-title"><i class="icon icon-panel-statistic"></i> <?php echo $text_panel_statistic; ?></h3>
								</div>
								<div class="panel-body">
									<div class="statistic">
										<i class="icon icon-statistic"></i>
										<div class="statistic-title"><?php echo $text_statistic_title; ?></div>
										<div class="statistic-description"><?php echo $text_statistic_description; ?></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col col-lg-9">
							<div class="panel panel-default panel-sale-analytics">
								<div class="panel-heading">
									<div class="pull-right">
										<a href="#sale_analytics_range" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-calendar"></i> <i class="caret"></i></a>
										<div id="#sale_analytics_range" class="dropdown-menu dropdown-menu-right">
											<?php foreach ($setting['sale_analytics_range'] as $sale_analytics_range) { ?>
											<?php if ($sale_analytics_range['code'] == $setting['general']['sale_analytics_range']) { ?>
											<a href="<?php echo $sale_analytics_range['code']; ?>" class="dropdown-item active"><?php echo ${$sale_analytics_range['name']}; ?></a>
											<?php } else { ?>
											<a href="<?php echo $sale_analytics_range['code']; ?>" class="dropdown-item"><?php echo ${$sale_analytics_range['name']}; ?></a>
											<?php } ?>
											<?php } ?>
										</div>
									</div>
									<h3 class="panel-title"><i class="icon icon-panel-sale-analytics"></i> <?php echo $text_panel_sale_analytics; ?></h3>
								</div>
								<div class="panel-body">
									<div class="sale-analytics"></div>
								</div>	
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.js"></script> 
<script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.resize.min.js"></script>
<script type="text/javascript">

$('.payment-paypal .switch').bootstrapSwitch({
    'onColor': 'success',
    'onText': '<?php echo $text_on; ?>',
    'offText': '<?php echo $text_off; ?>'
});

$('.payment-paypal .panel-sale-analytics').on('click', '.dropdown-item', function(event) {
	event.preventDefault();
	
	$(this).parent().find('.dropdown-item').removeClass('active');

	$(this).addClass('active');
	
	$.ajax({
		type: 'get',
		url: '<?php echo $sale_analytics_url; ?>&range=' + $(this).attr('href'),
		dataType: 'json',
		success: function(json) {			
			if (typeof json['all_sale'] == 'undefined') {
				return false;
			}
			
			var option = {	
				shadowSize: 0,
				colors: ['#9FD5F1', '#306EB9'],
				bars: { 
					show: true,
					fill: true,
					lineWidth: 1
				},
				grid: {
					backgroundColor: '#FFFFFF',
					hoverable: true
				},
				points: {
					show: false
				},
				xaxis: {
					show: true,
            		ticks: json['xaxis']
				}
			}
			
			$.plot('.payment-paypal .sale-analytics', [json['all_sale'], json['paypal_sale']], option);	
					
			$('.payment-paypal .sale-analytics').bind('plothover', function(event, pos, item) {
				$('.tooltip').remove();
			  
				if (item) {
					$('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');
					
					$('#tooltip').css({
						position: 'absolute',
						left: item.pageX - ($('#tooltip').outerWidth() / 2),
						top: item.pageY - $('#tooltip').outerHeight(),
						pointer: 'cusror'
					}).fadeIn('slow');	
					
					$('.payment-paypal .sale-analytics').css('cursor', 'pointer');		
			  	} else {
					$('.payment-paypal .sale-analytics').css('cursor', 'auto');
				}
			});
		},
        error: function(xhr, ajaxOptions, thrownError) {
           console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
	});
});

$('.payment-paypal .panel-sale-analytics .dropdown-item.active').trigger('click');

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

</script>
<?php echo $footer; ?>