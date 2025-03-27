<div id="paypal_modal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fal fa-close"></i></button>
				<h4 class="modal-title"><?php echo $text_paypal_fastlane_title; ?></h4>
			</div>
			<div class="modal-body">
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
			</div>
		</div>
	</div>
</div>