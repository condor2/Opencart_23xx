<?php if ($checkout_mode == 'multi_button') { ?>
<div id="paypal_form">
	<?php if ($button_status) { ?>
	<div id="paypal_button" class="paypal-button clearfix">
		<div id="paypal_button_container" class="paypal-button-container paypal-spinner"></div>
	</div>
	<?php } ?>
	<?php if ($googlepay_button_status) { ?>
	<div id="googlepay_button" class="googlepay-button clearfix">
		<div id="googlepay_button_container" class="googlepay-button-container paypal-spinner"></div>
	</div>
	<?php } ?>
	<?php if ($applepay_button_status) { ?>
	<div id="applepay_button" class="applepay-button clearfix">
		<div id="applepay_button_container" class="applepay-button-container paypal-spinner"></div>
	</div>
	<?php } ?>
	<?php if ($card_status) { ?>
	<div id="paypal_card_tokens" class="paypal-card-tokens">
		<div id="paypal_card_tokens_container" class="paypal-card-tokens-container"></div>
	</div>
	<div id="paypal_card" class="paypal-card">
		<div id="paypal_card_container" class="paypal-card-container paypal-spinner">
			<div id="paypal_card_form" class="paypal-card-form">
				<div class="card-info-holder-name clearfix">
					<div id="card_holder_name" class="card-input-container"></div>
				</div>
				<div class="card-info-number clearfix">
					<div id="card_number" class="card-input-container"></div>
				</div>
				<div class="card-info-date-cvv clearfix">
					<div class="card-info-date">
						<div id="expiration_date" class="card-input-container"></div>
					</div>
					<div class="card-info-cvv">
						<div id="cvv" class="card-input-container"></div>
					</div>
				</div>
				<div class="card-button">
					<?php if ($vault_status && $logged) { ?>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="paypal_card_save" id="paypal_card_save" value="1" checked /> <?php echo $entry_card_save; ?>
						</label>
					</div>
					<?php } ?>
					<button type="button" id="paypal_card_button" class="btn paypal-card-button" data-loading-text="<?php echo $text_loading; ?>"><?php echo $button_pay; ?></button>
				</div>
			</div>
			<div id="payments-sdk__contingency-lightbox"></div>
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
		<button type="button" id="button-confirm" class="btn btn-primary paypal-button-confirm" data-loading-text="<?php echo $text_loading; ?>" onClick="loadPayPalModal()"><?php echo $button_confirm; ?></button>
	</div>
</div>
<script type="text/javascript">

function loadPayPalModal() {
	$('.paypal-button-confirm').prop('disabled', true).button('loading');
	
	$('#paypal_modal').remove();
	
	$('body').append('<div id="paypal_modal" class="modal fade"></div>');
	
	$('#paypal_modal').load('index.php?route=extension/payment/paypal/modal #paypal_modal >', function() {		
		$('.paypal-button-confirm').prop('disabled', false).button('reset');
		
		$('#paypal_modal').modal('show');
		
		if (typeof PayPalAPI !== 'undefined') {
			PayPalAPI.init();
		}
	});
}

</script>
<?php } ?>