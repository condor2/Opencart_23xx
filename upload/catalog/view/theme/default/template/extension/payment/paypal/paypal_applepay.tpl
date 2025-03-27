<?php if ($checkout_mode == 'multi_button') { ?>
<div id="paypal_form">
	<?php if ($applepay_button_status) { ?>
	<div id="applepay_button" class="applepay-button clearfix">
		<div id="applepay_button_container" class="applepay-button-container paypal-spinner"></div>
	</div>
	<?php } ?>
</div>
<script type="text/javascript">

if (typeof PayPalAPI !== 'undefined') {
	PayPalAPI.init();
}
	
</script>
<?php } else { ?>
<div class="buttons">
	<div class="pull-right">
		<button type="button" id="button-confirm" class="btn btn-primary paypal-applepay-button-confirm" data-loading-text="<?php echo $text_loading; ?>" onClick="loadPayPalModal()"><?php echo $button_confirm; ?></button>
	</div>
</div>
<script type="text/javascript">

function loadPayPalModal() {
	$('.paypal-applepay-button-confirm').button('loading');
	
	$('#paypal_modal').remove();
	
	$('body').append('<div id="paypal_modal" class="modal fade"></div>');
	
	$('#paypal_modal').load('index.php?route=extension/payment/paypal_applepay/modal #paypal_modal >', function() {		
		$('.paypal-applepay-button-confirm').button('reset');
		
		$('#paypal_modal').modal('show');
		
		if (typeof PayPalAPI !== 'undefined') {
			PayPalAPI.init();
		}
	});
}

</script>
<?php } ?>