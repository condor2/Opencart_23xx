<div id="fastlane_modal" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fal fa-close"></i></button>
				<h4 class="modal-title"><?php echo $text_fastlane; ?></h4>
			</div>
			<div class="modal-body">
				<div class="panel-group fastlane-accordion" id="fastlane_accordion">
					<?php if ($shipping_required) { ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title" data-original-title="<?php echo $text_fastlane_shipping; ?>"><?php echo $text_fastlane_shipping; ?></h4>
						</div>
						<div class="panel-collapse collapse" id="fastlane_collapse_shipping">
							<div class="panel-body"></div>
						</div>
					</div>
					<?php } ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title" data-original-title="<?php echo $text_fastlane_payment; ?>"><?php echo $text_fastlane_payment; ?></h4>
						</div>
						<div class="panel-collapse collapse" id="fastlane_collapse_payment">
							<div class="panel-body"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>