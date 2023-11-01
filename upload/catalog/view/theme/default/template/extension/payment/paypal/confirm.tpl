<?php echo $header; ?>
<div class="container">
	<ul class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
		<?php } ?>
	</ul>
	<?php if ($attention) { ?>
	<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $attention; ?>
		<button type="button" class="close" data-dismiss="alert">&times;</button>
	</div>
	<?php } ?>
	<?php if ($success) { ?>
    <div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
		<button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
	<?php } ?>
	<?php if ($error_warning) { ?>
    <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
		<button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
	<?php } ?>
	<div class="row"><?php echo $column_left; ?>
		<?php if ($column_left && $column_right) { ?>
		<?php $class = 'col-sm-6'; ?>
		<?php } elseif ($column_left || $column_right) { ?>
		<?php $class = 'col-sm-9'; ?>
		<?php } else { ?>
		<?php $class = 'col-sm-12'; ?>
		<?php } ?>
		<div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
			<h1><?php echo $heading_title; ?></h1>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title"><?php echo $text_checkout_payment_address; ?><div class="button-payment-address pull-right" role="button"><i class="fa fa-pencil"></i></div></h4>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-6">
							<fieldset id="account">
								<legend><?php echo $text_your_details; ?></legend>
								<table class="table table-bordered">
									<?php if ($guest['firstname']) { ?><tr><td><b><?php echo $entry_firstname; ?></b></td><td><?php echo $guest['firstname']; ?></td></tr><?php } ?>
									<?php if ($guest['lastname']) { ?><tr><td><b><?php echo $entry_lastname; ?></b></td><td><?php echo $guest['lastname']; ?></td></tr><?php } ?>
									<?php if ($guest['email']) { ?><tr><td><b><?php echo $entry_email; ?></b></td><td><?php echo $guest['email']; ?></td></tr><?php } ?>
									<?php if ($guest['telephone']) { ?><tr><td><b><?php echo $entry_telephone; ?></b></td><td><?php echo $guest['telephone']; ?></td></tr><?php } ?>
									<?php foreach ($custom_fields as $custom_field) { ?>
									<?php if ($custom_field['location'] == 'account') { ?>
									<tr><td><b><?php echo $custom_field['name']; ?></b></td><td><?php echo $custom_field['value']; ?></td></tr>
									<?php } ?>
									<?php } ?>
								</table>
							</fieldset>
						</div>
						<div class="col-sm-6">
							<fieldset id="address">
								<legend><?php echo $text_your_address; ?></legend>
								<table class="table table-bordered">
									<?php if ($payment_address['company']) { ?><tr><td><b><?php echo $entry_company; ?></b></td><td><?php echo $payment_address['company']; ?></td></tr><?php } ?>
									<?php if ($payment_address['address_1']) { ?><tr><td><b><?php echo $entry_address_1; ?></b></td><td><?php echo $payment_address['address_1']; ?></td></tr><?php } ?>
									<?php if ($payment_address['address_2']) { ?><tr><td><b><?php echo $entry_address_2; ?></b></td><td><?php echo $payment_address['address_2']; ?></td></tr><?php } ?>
									<?php if ($payment_address['city']) { ?><tr><td><b><?php echo $entry_city; ?></b></td><td><?php echo $payment_address['city']; ?></td></tr><?php } ?>
									<?php if ($payment_address['postcode']) { ?><tr><td><b><?php echo $entry_postcode; ?></b></td><td><?php echo $payment_address['postcode']; ?></td></tr><?php } ?>
									<?php if ($payment_address['country']) { ?><tr><td><b><?php echo $entry_country; ?></b></td><td><?php echo $payment_address['country']; ?></td></tr><?php } ?>
									<?php if ($payment_address['zone']) { ?><tr><td><b><?php echo $entry_zone; ?></b></td><td><?php echo $payment_address['zone']; ?></td></tr><?php } ?>
									<?php foreach ($custom_fields as $custom_field) { ?>
									<?php if ($custom_field['location'] == 'address') { ?>
									<tr><td><b><?php echo $custom_field['name']; ?></b></td><td><?php echo $custom_field['value']; ?></td></tr>
									<?php } ?>
									<?php } ?>
								</table>
							</fieldset>
						</div>
					</div>
				</div>
			</div>
			<?php if ($has_shipping) { ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title"><?php echo $text_checkout_shipping_address; ?><div class="button-shipping-address pull-right" role="button"><i class="fa fa-pencil"></i></div></h4>
				</div>
				<div class="panel-body">
					<table class="table table-bordered">
						<?php if ($shipping_address['firstname']) { ?><tr><td><b><?php echo $entry_firstname; ?></b></td><td><?php echo $shipping_address['firstname']; ?></td></tr><?php } ?>
						<?php if ($shipping_address['lastname']) { ?><tr><td><b><?php echo $entry_lastname; ?></b></td><td><?php echo $shipping_address['lastname']; ?></td></tr><?php } ?>
						<?php if ($shipping_address['company']) { ?><tr><td><b><?php echo $entry_company; ?></b></td><td><?php echo $shipping_address['company']; ?></td></tr><?php } ?>
						<?php if ($shipping_address['address_1']) { ?><tr><td><b><?php echo $entry_address_1; ?></b></td><td><?php echo $shipping_address['address_1']; ?></td></tr><?php } ?>
						<?php if ($shipping_address['address_2']) { ?><tr><td><b><?php echo $entry_address_2; ?></b></td><td><?php echo $shipping_address['address_2']; ?></td></tr><?php } ?>
						<?php if ($shipping_address['city']) { ?><tr><td><b><?php echo $entry_city; ?></b></td><td><?php echo $shipping_address['city']; ?></td></tr><?php } ?>
						<?php if ($shipping_address['postcode']) { ?><tr><td><b><?php echo $entry_postcode; ?></b></td><td><?php echo $shipping_address['postcode']; ?></td></tr><?php } ?>
						<?php if ($shipping_address['country']) { ?><tr><td><b><?php echo $entry_country; ?></b></td><td><?php echo $shipping_address['country']; ?></td></tr><?php } ?>
						<?php if ($shipping_address['zone']) { ?><tr><td><b><?php echo $entry_zone; ?></b></td><td><?php echo $shipping_address['zone']; ?></td></tr><?php } ?>	
						<?php foreach ($custom_fields as $custom_field) { ?>
						<?php if ($custom_field['location'] == 'address') { ?>
						<tr><td><b><?php echo $custom_field['name']; ?></b></td><td><?php echo $custom_field['value']; ?></td></tr>
						<?php } ?>
						<?php } ?>
					</table>
				</div>
			</div>
			<?php } ?>
			<?php if ($has_shipping) { ?>
			<?php if (!$shipping_methods) { ?>
			<div class="alert alert-warning alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error_no_shipping; ?></div>
			<?php } else { ?>
			<form action="<?php echo $action_shipping; ?>" method="post" id="shipping_form">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title"><?php echo $text_checkout_shipping_method; ?></h4>
					</div>
					<div class="panel-body">
						<?php foreach ($shipping_methods as $shipping_method) { ?>
						<p><strong><?php echo $shipping_method['title']; ?></strong></p>
						<?php if (!$shipping_method['error']) { ?>
						<?php foreach ($shipping_method['quote'] as $quote) { ?>
						<div class="radio">
							<label>
								<?php if (($quote['code'] == $code) || !$code) { ?>
								<?php $code = $quote['code']; ?>
								<input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" checked="checked" />
								<?php } else { ?>
								<input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" />
								<?php } ?>
								<?php echo $quote['title']; ?> - <?php echo $quote['text']; ?>
							</label>
						</div>
						<?php } ?>
						<?php } else { ?>
						<div class="alert alert-danger alert-dismissible"><?php echo $shipping_method['error']; ?></div>
						<?php } ?>
						<?php } ?>
					</div>
				</div>
			</form>
			<?php } ?>
			<?php } ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title"><?php echo $text_checkout_payment_method; ?></h4>
				</div>
				<div class="panel-body">
					<?php foreach ($payment_methods as $payment_method) { ?>
					<?php if ($payment_method['code'] == 'paypal') { ?>
					<div class="radio">
						<label>
							<input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" checked="checked" />
							<?php echo $payment_method['title']; ?>
						</label>
					</div>
					<?php } ?>
					<?php } ?>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<td class="text-left"><?php echo $column_name; ?></td>
							<td class="text-left"><?php echo $column_model; ?></td>
							<td class="text-center"><?php echo $column_quantity; ?></td>
							<td class="text-right"><?php echo $column_price; ?></td>
							<td class="text-right"><?php echo $column_total; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($products as $product) { ?>
						<tr>
							<td class="text-left">
								<a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
								<?php foreach ($product['option'] as $option) { ?> <br />
								<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
								<?php } ?>
								<?php if ($product['recurring']) { ?>
								<br /><span class="label label-info"><?php echo $text_recurring_item; ?></span> <small><?php echo $product['recurring_description']; ?></small>
								<?php } ?>
							</td>
							<td class="text-left"><?php echo $product['model']; ?></td>
							<td class="text-center"><?php echo $product['quantity']; ?></td>
							<td class="text-right"><?php echo $product['price']; ?></td>
							<td class="text-right"><?php echo $product['total']; ?></td>
						</tr>
						<?php } ?>
						<?php foreach ($vouchers as $voucher) { ?>
						<tr>
							<td class="text-left"><?php echo $voucher['description']; ?></td>
							<td class="text-left"></td>
							<td class="text-center">1</td>
							<td class="text-right"><?php echo $voucher['amount']; ?></td>
							<td class="text-right"><?php echo $voucher['amount']; ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<br />
			<?php if ($coupon || $voucher || $reward) { ?>
			<div class="panel-group" id="accordion"><?php echo $coupon; ?><?php echo $voucher; ?><?php echo $reward; ?></div><br />
			<?php } ?>
			<div class="row">
				<div class="col-sm-4 col-sm-offset-8">
					<table class="table table-bordered">
						<?php foreach ($totals as $total) { ?>
						<tr>
							<td class="text-right"><strong><?php echo $total['title']; ?>:</strong></td>
							<td class="text-right"><?php echo $total['text']; ?></td>
						</tr>
						<?php } ?>
					</table>
				</div>
			</div>
			<div class="buttons">
				<div class="pull-right"><a href="<?php echo $action_confirm; ?>" class="btn btn-primary" id="paypal_confirm"><?php echo $button_confirm; ?></a></div>
			</div>
			<?php echo $content_bottom; ?>
		</div>
		<?php echo $column_right; ?>
	</div>
</div>
<script type="text/javascript">

$('input[name=\'shipping_method\']').change(function() {
	$('#shipping_form').submit();
});

$(document).on('click', '.button-payment-address', function(event) {
	$('#payment_address').remove();
	$('body').append('<div id="payment_address" class="modal fade"></div>');
	$('#payment_address').load('index.php?route=extension/payment/paypal/paymentAddress #payment_address >', onLoadPaymentAddress);
	$('#payment_address').modal('show');
});

$(document).on('click', '.button-shipping-address', function(event) {
	$('#shipping_address').remove();
	$('body').append('<div id="shipping_address" class="modal fade"></div>');
	$('#shipping_address').load('index.php?route=extension/payment/paypal/shippingAddress #shipping_address >', onLoadShippingAddress);
	$('#shipping_address').modal('show');
});

$('input[name=\'next\']').bind('change', function() {
	$('.cart-discounts > div').hide();

	$('#' + this.value).show();
});

$('#paypal_confirm').bind('click', function() {
    $('#paypal_confirm').prop('disabled', true).button('loading');
});

function onLoadPaymentAddress() {
	// Sort the custom fields
	$('#payment_address #account .form-group[data-sort]').detach().each(function() {
		if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#payment_address #account .form-group').length) {
			$('#payment_address #account .form-group').eq($(this).attr('data-sort')).before(this);
		}

		if ($(this).attr('data-sort') > $('#payment_address #account .form-group').length) {
			$('#payment_address #account .form-group:last').after(this);
		}

		if ($(this).attr('data-sort') == $('#payment_address #account .form-group').length) {
			$('#payment_address #account .form-group:last').after(this);
		}

		if ($(this).attr('data-sort') < -$('#payment_address #account .form-group').length) {
			$('#payment_address #account .form-group:first').before(this);
		}
	});

	$('#payment_address #address .form-group[data-sort]').detach().each(function() {
		if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#payment_address #address .form-group').length) {
			$('#payment_address #address .form-group').eq($(this).attr('data-sort')).before(this);
		}

		if ($(this).attr('data-sort') > $('#payment_address #address .form-group').length) {
			$('#payment_address #address .form-group:last').after(this);
		}

		if ($(this).attr('data-sort') == $('#payment_address #address .form-group').length) {
			$('#payment_address #address .form-group:last').after(this);
		}

		if ($(this).attr('data-sort') < -$('#payment_address #address .form-group').length) {
			$('#payment_address #address .form-group:first').before(this);
		}
	});

	$('#payment_address select[name=\'country_id\']').on('change', function() {
		$.ajax({
			url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				$('#payment_address select[name=\'country_id\']').prop('disabled', true);
			},
			complete: function() {
				$('#payment_address select[name=\'country_id\']').prop('disabled', false);
			},
			success: function(json) {
				if (json['postcode_required'] == '1') {
					$('#payment_address input[name=\'postcode\']').parent().parent().addClass('required');
				} else {
					$('#payment_address input[name=\'postcode\']').parent().parent().removeClass('required');
				}

				html = '<option value=""><?php echo $text_select; ?></option>';

				if (json['zone'] && json['zone'] != '') {
					for (i = 0; i < json['zone'].length; i++) {
						html += '<option value="' + json['zone'][i]['zone_id'] + '"';

						if (json['zone'][i]['zone_id'] == '<?php echo $payment_address['zone_id']; ?>') {
							html += ' selected="selected"';
						}

						html += '>' + json['zone'][i]['name'] + '</option>';
					}
				} else {
					html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
				}

				$('#payment_address select[name=\'zone_id\']').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('#payment_address select[name=\'country_id\']').trigger('change');
	
	$('.date').datetimepicker({
		language: '<?php echo $datepicker; ?>',
		pickTime: false
	});

	$('.time').datetimepicker({
		language: '<?php echo $datepicker; ?>',
		pickDate: false
	});

	$('.datetime').datetimepicker({
		language: '<?php echo $datepicker; ?>',
		pickDate: true,
		pickTime: true
	});
	
	$('#payment_address button[id^=\'button_payment_custom_field\']').on('click', function() {
		var node = this;

		$('#form_upload').remove();

		$('body').prepend('<form enctype="multipart/form-data" id="form_upload" style="display: none;"><input type="file" name="file" /></form>');

		$('#form_upload input[name=\'file\']').trigger('click');

		if (typeof timer != 'undefined') {
			clearInterval(timer);
		}

		timer = setInterval(function() {
			if ($('#form_upload input[name=\'file\']').val() != '') {
				clearInterval(timer);

				$.ajax({
					url: 'index.php?route=tool/upload',
					type: 'post',
					dataType: 'json',
					data: new FormData($('#form_upload')[0]),
					cache: false,
					contentType: false,
					processData: false,
					beforeSend: function() {
						$(node).button('loading');
					},
					complete: function() {
						$(node).button('reset');
					},
					success: function(json) {
						$(node).parent().find('.text-danger').remove();

						if (json['error']) {
							$(node).parent().find('input[name^=\'custom_field\']').after('<div class="text-danger">' + json['error'] + '</div>');
						}

						if (json['success']) {
							alert(json['success']);

							$(node).parent().find('input[name^=\'custom_field\']').val(json['code']);
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		}, 500);
	});
	
	$('#payment_address .button-confirm').on('click', function(event) {
		$.ajax({
			type: 'post',
			url: 'index.php?route=extension/payment/paypal/confirmPaymentAddress',
			data: $('#payment_address [name]'),
			dataType: 'json',
			success: function(json) {
				$('#payment_address .alert, #payment_address .text-danger').remove();
				$('#payment_address .form-group').removeClass('has-error');
			
				if (json['error']) {
					for (i in json['error']) {
						var element = $('#payment_address #input_payment_' + i);
					
						if (element.parent().hasClass('input-group')) {
							$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
						} else {
							$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
						}
			
						$(element).parents('.form-group').addClass('has-error');
					}
				}
				
				if (json['url']) {
					location = json['url'];
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
}

function onLoadShippingAddress() {
	// Sort the custom fields
	$('#shipping_address .form-group[data-sort]').detach().each(function() {
		if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#shipping_address .form-group').length-2) {
			$('#shipping_address .form-group').eq(parseInt($(this).attr('data-sort'))+2).before(this);
		}

		if ($(this).attr('data-sort') > $('#shipping_address .form-group').length-2) {
			$('#shipping_address .form-group:last').after(this);
		}

		if ($(this).attr('data-sort') == $('#collapse-shipping-address .form-group').length-2) {
			$('#collapse-shipping-address .form-group:last').after(this);
		}

		if ($(this).attr('data-sort') < -$('#shipping_address .form-group').length-2) {
			$('#shipping_address .form-group:first').before(this);
		}
	});
	
	$('#shipping_address select[name=\'country_id\']').on('change', function() {
		$.ajax({
			url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				$('#shipping_address select[name=\'country_id\']').prop('disabled', true);
			},
			complete: function() {
				$('#shipping_address select[name=\'country_id\']').prop('disabled', false);
			},
			success: function(json) {
				if (json['postcode_required'] == '1') {
					$('#shipping_address input[name=\'postcode\']').parent().parent().addClass('required');
				} else {
					$('#shipping_address input[name=\'postcode\']').parent().parent().removeClass('required');
				}

				html = '<option value=""><?php echo $text_select; ?></option>';

				if (json['zone'] && json['zone'] != '') {
					for (i = 0; i < json['zone'].length; i++) {
						html += '<option value="' + json['zone'][i]['zone_id'] + '"';

						if (json['zone'][i]['zone_id'] == '<?php echo $shipping_address['zone_id']; ?>') {
							html += ' selected="selected"';
						}

						html += '>' + json['zone'][i]['name'] + '</option>';
					}
				} else {
					html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
				}

				$('#shipping_address select[name=\'zone_id\']').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('#shipping_address select[name=\'country_id\']').trigger('change');
	
	$('.date').datetimepicker({
		language: '<?php echo $datepicker; ?>',
		pickTime: false
	});

	$('.time').datetimepicker({
		language: '<?php echo $datepicker; ?>',
		pickDate: false
	});

	$('.datetime').datetimepicker({
		language: '<?php echo $datepicker; ?>',
		pickDate: true,
		pickTime: true
	});
	
	$('#shipping_address button[id^=\'button_shipping_custom_field\']').on('click', function() {
		var node = this;

		$('#form_upload').remove();

		$('body').prepend('<form enctype="multipart/form-data" id="form_upload" style="display: none;"><input type="file" name="file" /></form>');

		$('#form_upload input[name=\'file\']').trigger('click');

		if (typeof timer != 'undefined') {
			clearInterval(timer);
		}

		timer = setInterval(function() {
			if ($('#form_upload input[name=\'file\']').val() != '') {
				clearInterval(timer);

				$.ajax({
					url: 'index.php?route=tool/upload',
					type: 'post',
					dataType: 'json',
					data: new FormData($('#form_upload')[0]),
					cache: false,
					contentType: false,
					processData: false,
					beforeSend: function() {
						$(node).button('loading');
					},
					complete: function() {
						$(node).button('reset');
					},
					success: function(json) {
						$(node).parent().find('.text-danger').remove();

						if (json['error']) {
							$(node).parent().find('input[name^=\'custom_field\']').after('<div class="text-danger">' + json['error'] + '</div>');
						}

						if (json['success']) {
							alert(json['success']);

							$(node).parent().find('input[name^=\'custom_field\']').attr('value', json['file']);
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		}, 500);
	});
	
	$('#shipping_address .button-confirm').on('click', function(event) {
		$.ajax({
			type: 'post',
			url: 'index.php?route=extension/payment/paypal/confirmShippingAddress',
			data: $('#shipping_address [name]'),
			dataType: 'json',
			success: function(json) {
				$('#shipping_address .alert, #shipping_address .text-danger').remove();
				$('#shipping_address .form-group').removeClass('has-error');
			
				if (json['error']) {
					for (i in json['error']) {
						var element = $('#shipping_address #input_shipping_' + i);
					
						if (element.parent().hasClass('input-group')) {
							$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
						} else {
							$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
						}
			
						$(element).parents('.form-group').addClass('has-error');
					}
				}
				
				if (json['url']) {
					location = json['url'];
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
}

</script>
<?php echo $footer; ?>