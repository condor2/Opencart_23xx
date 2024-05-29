<div class="row">
	<div class="col-lg-6">
		<table class="table table-bordered">
			<thead>
				<tr>
					<td colspan="2"><?php echo $text_payment_information; ?></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $text_transaction_id; ?></td>
					<td>
						<a href="<?php echo $transaction_url; ?>" target="_blank"><?php echo $transaction_id; ?></a>
						<input type="hidden" name="order_id" value="<?php echo $order_id; ?>" id="input_order_id" />
						<input type="hidden" name="country_code" value="<?php echo $country_code; ?>" id="input_country_code" />
					</td>
				</tr>
				<tr>
					<td><?php echo $text_transaction_description; ?></td>
					<td><?php echo ${'text_transaction_' . $transaction_status}; ?></td>
				</tr>
				<?php if (($transaction_status == 'created') || ($transaction_status == 'completed') || ($transaction_status == 'partially_captured') || ($transaction_status == 'partially_refunded')) { ?>
				<tr>
					<td><?php echo $text_transaction_action; ?></td>
					<td>
						<?php if (($transaction_status == 'created') || ($transaction_status == 'partially_captured')) { ?>
						<div class="row" style="margin: 0px -4px">
							<div class="col-lg-4" style="padding: 4px 4px;">
								<button type="button" class="btn btn-primary btn-block button-capture-payment"><?php echo $button_capture_payment; ?></button>
							</div>
							<div class="col-lg-4" style="padding: 4px 4px;">
								<input type="text" name="capture_amount" value="<?php echo $capture_amount; ?>" id="input_capture_amount" class="form-control" />
							</div>
							<div class="col-lg-4" style="padding: 4px 4px;">
								<div class="checkbox">
									<label><input type="checkbox" name="final_capture" value="1" checked="checked" id="input_final_capture">&nbsp;<?php echo $text_final_capture; ?></label>
								</div>
							</div>
						</div>
						<div class="row" style="margin: 0px -4px">
							<div class="col-lg-4" style="padding: 4px 4px;">
								<button type="button" class="btn btn-primary btn-block button-reauthorize-payment"><?php echo $button_reauthorize_payment; ?></button>
							</div>
							<div class="col-lg-4" style="padding: 4px 4px;">
								<input type="text" name="reauthorize_amount" value="<?php echo $reauthorize_amount; ?>" id="input_reauthorize_amount" class="form-control" />
							</div>
						</div>
						<div class="row" style="margin: 0px -4px">
							<div class="col-lg-4" style="padding: 4px 4px;">
								<button type="button" class="btn btn-primary btn-block button-void-payment"><?php echo $button_void_payment; ?></button>
							</div>
						</div>
						<?php } ?>
						<?php if (($transaction_status == 'completed') || ($transaction_status == 'partially_refunded')) { ?>
						<div class="row" style="margin: 0px -4px">
							<div class="col-lg-4" style="padding: 4px 4px;">
								<button type="button" class="btn btn-primary btn-block button-refund-payment"><?php echo $button_refund_payment; ?></button>
							</div>
							<div class="col-lg-4" style="padding: 4px 4px;">
								<input type="text" name="refund_amount" value="<?php echo $refund_amount; ?>" id="input_refund_amount" class="form-control" />
							</div>
						</div>
						<?php } ?>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<?php if ($transaction_status == 'completed') { ?>
	<div class="col-lg-6">
		<table class="table table-bordered">
			<thead>
				<tr>
					<td colspan="2"><?php echo $text_tracker_information; ?></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $text_tracking_number; ?></td>
					<td>
						<?php if ($tracking_number) { ?>
						<?php echo $tracking_number; ?>
						<input type="hidden" name="tracking_number" value="<?php echo $tracking_number; ?>" id="input_tracking_number" />
						<?php } else { ?>
						<input type="text" name="tracking_number" value="<?php echo $tracking_number; ?>" id="input_tracking_number" class="form-control" />
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td><?php echo $text_carrier_name; ?></td>
					<td>
						<?php if ($carrier_name) { ?>
						<?php echo $carrier_name; ?>
						<input type="hidden" name="carrier_name" value="<?php echo $carrier_name; ?>" id="input_carrier_name" />
						<?php } else { ?>
						<input type="text" name="carrier_name" value="<?php echo $carrier_name; ?>" id="input_carrier_name" class="form-control" />
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td><?php echo $text_tracker_action; ?></td>
					<td>
						<?php if ($tracking_number) { ?>
						<button class="btn btn-danger button-cancel-tracker"><i class="fa fa-minus-circle"></i> <?php echo $button_cancel_tracker; ?></button>
						<?php } else { ?>
						<button class="btn btn-primary button-create-tracker"><i class="fa fa-plus-circle"></i> <?php echo $button_create_tracker; ?></button>
						<?php } ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php } ?>
</div>
<script type="text/javascript">

window.addEventListener('load', function () {
	$('#tab-paypal').on('click', '.button-capture-payment', function() {
		$.ajax({
			type: 'post',
			url: '<?php echo $capture_payment_url; ?>',
			data: {'order_id': $('#tab-paypal #input_order_id').val(), 'capture_amount': $('#tab-paypal #input_capture_amount').val(), 'final_capture': $('#tab-paypal #input_final_capture:checked').val()},
			dataType: 'json',
			beforeSend: function() {
				$('#tab-paypal .btn').prop('disabled', true);
			},
			complete: function() {
				$('#tab-paypal .btn').prop('disabled', false);
			},
			success: function(json) {
				$('.alert-dismissible').remove();
			
				if (json['error'] && json['error']['warning']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
					$('html, body').animate({scrollTop: $('#content > .container-fluid .alert-danger').offset().top}, 'slow');
				}
			
				if (json['success']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
					$('html, body').animate({scrollTop: $('#content > .container-fluid .alert-success').offset().top}, 'slow');
				
					updateTabPayPal();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('#tab-paypal').on('click', '.button-reauthorize-payment', function() {
		$.ajax({
			type: 'post',
			url: '<?php echo $reauthorize_payment_url; ?>',
			data: {'order_id': $('#tab-paypal #input_order_id').val(), 'reauthorize_amount': $('#tab-paypal #input_reauthorize_amount').val()},
			dataType: 'json',
			beforeSend: function() {
				$('#tab-paypal .btn').prop('disabled', true);
			},
			complete: function() {
				$('#tab-paypal .btn').prop('disabled', false);
			},
			success: function(json) {
				$('.alert-dismissible').remove();
			
				if (json['error'] && json['error']['warning']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
					$('html, body').animate({scrollTop: $('#content > .container-fluid .alert-danger').offset().top}, 'slow');
				}
			
				if (json['success']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
					$('html, body').animate({scrollTop: $('#content > .container-fluid .alert-success').offset().top}, 'slow');
				
					updateTabPayPal();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('#tab-paypal').on('click', '.button-void-payment', function() {
		$.ajax({
			type: 'post',
			url: '<?php echo $void_payment_url; ?>',
			data: {'order_id': $('#tab-paypal #input_order_id').val()},
			dataType: 'json',
			beforeSend: function() {
				$('#tab-paypal .btn').prop('disabled', true);
			},
			complete: function() {
				$('#tab-paypal .btn').prop('disabled', false);
			},
			success: function(json) {
				$('.alert-dismissible').remove();
			
				if (json['error'] && json['error']['warning']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
					$('html, body').animate({scrollTop: $('#content > .container-fluid .alert-danger').offset().top}, 'slow');
				}
			
				if (json['success']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
					$('html, body').animate({scrollTop: $('#content > .container-fluid .alert-success').offset().top}, 'slow');
				
					updateTabPayPal();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('#tab-paypal').on('click', '.button-refund-payment', function() {
		$.ajax({
			type: 'post',
			url: '<?php echo $refund_payment_url; ?>',
			data: {'order_id': $('#tab-paypal #input_order_id').val(), 'refund_amount': $('#tab-paypal #input_refund_amount').val()},
			dataType: 'json',
			beforeSend: function() {
				$('#tab-paypal .btn').prop('disabled', true);
			},
			complete: function() {
				$('#tab-paypal .btn').prop('disabled', false);
			},
			success: function(json) {
				$('.alert-dismissible').remove();
			
				if (json['error'] && json['error']['warning']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
					$('html, body').animate({scrollTop: $('#content > .container-fluid .alert-danger').offset().top}, 'slow');
				}
			
				if (json['success']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
					$('html, body').animate({ scrollTop: $('#content > .container-fluid .alert-success').offset().top}, 'slow');
				
					updateTabPayPal();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('#tab-paypal').on('click', '.button-create-tracker', function() {
		$.ajax({
			type: 'post',
			url: '<?php echo $create_tracker_url; ?>',
			data: {'order_id': $('#tab-paypal #input_order_id').val(), 'country_code': $('#tab-paypal #input_country_code').val(), 'tracking_number': $('#tab-paypal #input_tracking_number').val(), 'carrier_name': $('#tab-paypal #input_carrier_name').val()},
			dataType: 'json',
			beforeSend: function() {
				$('#tab-paypal .btn').prop('disabled', true);
			},
			complete: function() {
				$('#tab-paypal .btn').prop('disabled', false);
			},
			success: function(json) {
				$('.alert-dismissible').remove();
			
				if (json['error'] && json['error']['warning']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
					$('html, body').animate({scrollTop: $('#content > .container-fluid .alert-danger').offset().top}, 'slow');
				}
			
				if (json['success']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
					$('html, body').animate({scrollTop: $('#content > .container-fluid .alert-success').offset().top}, 'slow');
				
					updateTabPayPal();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
	
	$('#tab-paypal').on('click', '.button-cancel-tracker', function() {
		$.ajax({
			type: 'post',
			url: '<?php echo $cancel_tracker_url; ?>',
			data: {'order_id': $('#tab-paypal #input_order_id').val(), 'tracking_number': $('#tab-paypal #input_tracking_number').val()},
			dataType: 'json',
			beforeSend: function() {
				$('#tab-paypal .btn').prop('disabled', true);
			},
			complete: function() {
				$('#tab-paypal .btn').prop('disabled', false);
			},
			success: function(json) {
				$('.alert-dismissible').remove();
			
				if (json['error'] && json['error']['warning']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
					$('html, body').animate({scrollTop: $('#content > .container-fluid .alert-danger').offset().top}, 'slow');
				}
			
				if (json['success']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
					$('html, body').animate({scrollTop: $('#content > .container-fluid .alert-success').offset().top}, 'slow');
				
					updateTabPayPal();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	updateTabPayPal();
});

function updateTabPayPal() {
	$('#tab-paypal').load('<?php echo $info_payment_url; ?>', function() {
		$('#tab-paypal #input_carrier_name').autocomplete({
			'source': function(request, response) {
				$.ajax({
					type: 'post',
					url: '<?php echo $autocomplete_carrier_url; ?>',
					data: {'filter_country_code': $('#tab-paypal #input_country_code').val(), 'filter_carrier_name': encodeURIComponent(request)},
					dataType: 'json',
					success: function(json) {
						response($.map(json, function(item) {
							return {
								label: item['name'],
								value: item['code']
							}
						}));
					}
				});
			},
			'select': function(item) {
				$('#input_carrier_name').val(item['label']);
			}
		});
	});
}

</script>