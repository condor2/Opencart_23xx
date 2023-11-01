<div id="paypal_modal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fal fa-close"></i></button>
				<h4 class="modal-title"><?php echo $text_paypal_applepay_title; ?></h4>
			</div>
			<div class="modal-body">
				<div id="paypal_form">
					<?php if ($applepay_button_status) { ?>
					<div id="applepay_button" class="applepay-button buttons clearfix">
						<div id="applepay_button_container" class="applepay-button-container paypal-spinner"></div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>