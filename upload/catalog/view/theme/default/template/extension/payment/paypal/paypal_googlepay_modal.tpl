<div id="paypal_modal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fal fa-close"></i></button>
				<h4 class="modal-title"><?php echo $text_paypal_googlepay_title; ?></h4>
			</div>
			<div class="modal-body">
				<div id="paypal_form">
					<?php if ($googlepay_button_status) { ?>
					<div id="googlepay_button" class="googlepay-button buttons clearfix">
						<div id="googlepay_button_container" class="googlepay-button-container paypal-spinner"></div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>