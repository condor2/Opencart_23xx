<div class="transaction-id"><?php echo $text_transaction_id; ?>: <a href="<?php echo $transaction_url; ?>" target="_blank"><?php echo $transaction_id; ?></a></div><br />
<div class="transaction-status"><?php echo ${'text_transaction_' . $transaction_status}; ?></div><br />
<?php if ($transaction_status == 'created') { ?>
<button type="button" class="btn btn-primary button-capture"><?php echo $button_capture; ?></button>
<button type="button" class="btn btn-primary button-reauthorize"><?php echo $button_reauthorize; ?></button>
<button type="button" class="btn btn-primary button-void"><?php echo $button_void; ?></button>
<?php } ?>
<?php if ($transaction_status == 'completed') { ?>
<button type="button" class="btn btn-primary button-refund"><?php echo $button_refund; ?></button>
<?php } ?>
<script type="text/javascript">

$('#tab-paypal').on('click', '.button-capture', function() {
	$.ajax({
		type: 'post',
		url: '<?php echo $capture_url; ?>',
		data: {'order_id' : '<?php echo $order_id; ?>', 'transaction_id' : '<?php echo $transaction_id; ?>'},
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
				
				$('html, body').animate({ scrollTop: $('#content > .container-fluid .alert-danger').offset().top}, 'slow');
			}
			
			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
				$('html, body').animate({ scrollTop: $('#content > .container-fluid .alert-success').offset().top}, 'slow');
				
				$('#tab-paypal').load('<?php echo $info_url; ?>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});


$('#tab-paypal').on('click', '.button-reauthorize', function() {
	$.ajax({
		type: 'post',
		url: '<?php echo $reauthorize_url; ?>',
		data: {'order_id' : '<?php echo $order_id; ?>', 'transaction_id' : '<?php echo $transaction_id; ?>'},
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
				
				$('html, body').animate({ scrollTop: $('#content > .container-fluid .alert-danger').offset().top}, 'slow');
			}
			
			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
				$('html, body').animate({ scrollTop: $('#content > .container-fluid .alert-success').offset().top}, 'slow');
				
				$('#tab-paypal').load('<?php echo $info_url; ?>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});


$('#tab-paypal').on('click', '.button-void', function() {
	$.ajax({
		type: 'post',
		url: '<?php echo $void_url; ?>',
		data: {'order_id' : '<?php echo $order_id; ?>', 'transaction_id' : '<?php echo $transaction_id; ?>'},
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
				
				$('html, body').animate({ scrollTop: $('#content > .container-fluid .alert-danger').offset().top}, 'slow');
			}
			
			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
				$('html, body').animate({ scrollTop: $('#content > .container-fluid .alert-success').offset().top}, 'slow');
				
				$('#tab-paypal').load('<?php echo $info_url; ?>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#tab-paypal').on('click', '.button-refund', function() {
	$.ajax({
		type: 'post',
		url: '<?php echo $refund_url; ?>',
		data: {'order_id' : '<?php echo $order_id; ?>', 'transaction_id' : '<?php echo $transaction_id; ?>'},
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
				
				$('html, body').animate({ scrollTop: $('#content > .container-fluid .alert-danger').offset().top}, 'slow');
			}
			
			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
				$('html, body').animate({ scrollTop: $('#content > .container-fluid .alert-success').offset().top}, 'slow');
				
				$('#tab-paypal').load('<?php echo $info_url; ?>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

</script>