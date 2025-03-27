<?php if ($checkout_mode == 'multi_button') { ?>
<div id="paypal_form">
	<?php if ($fastlane_status) { ?>
	<div id="fastlane_card" class="fastlane-card">
		<div id="fastlane_card_container" class="fastlane-card-container paypal-spinner">
			<div id="fastlane_card_form" class="fastlane-card-form">
				<div id="fastlane_card_form_container" class="fastlane-card-form-container"></div>
				<div class="card-button">
					<button type="button" id="fastlane_card_button" class="btn fastlane-card-button" data-loading-text="<?php echo $text_loading; ?>"><?php echo $button_pay; ?></button>
				</div>
			</div>
		</div>
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
		<button type="button" id="button-confirm" class="btn btn-primary paypal-fastlane-button-confirm" data-loading-text="<?php echo $text_loading; ?>" onClick="loadPayPalModal()"><?php echo $button_confirm; ?></button>
	</div>
</div>
<script type="text/javascript">

function loadPayPalModal() {
	$('.paypal-fastlane-button-confirm').button('loading');
	
	$('#paypal_modal').remove();
	
	$('body').append('<div id="paypal_modal" class="modal fade"></div>');
	
	$('#paypal_modal').load('index.php?route=extension/payment/paypal_fastlane/modal #paypal_modal >', function() {		
		$('.paypal-fastlane-button-confirm').button('reset');
		
		$('#paypal_modal').modal('show');
		
		if (typeof PayPalAPI !== 'undefined') {
			PayPalAPI.init();
		}
	});
}

</script>
<?php } ?>