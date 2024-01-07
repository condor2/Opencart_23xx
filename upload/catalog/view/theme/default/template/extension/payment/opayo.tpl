<div id="opayo-form" class="form-horizontal">
	<fieldset class="opayo-payment">
		<legend><?php echo $text_credit_card; ?></legend>
		<div id="opayo-cards" style="display: <?php if ($cards) { ?>block<?php } else { ?>none<?php } ?>">
			<div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $entry_card; ?></label>
				<div class="col-sm-10">
					<label class="radio-inline">
						<input type="radio" name="opayo_card_existing" value="1" id="input-opayo-card-existing" class="input-opayo-card-existing" <?php if ($cards) { ?>checked<?php } ?> />	
						<?php echo $entry_card_existing; ?>				
					</label>
					<label class="radio-inline">
						<input type="radio" name="opayo_card_existing" value="0" id="input-opayo-card-new" class="input-opayo-card-existing" <?php if (!$cards) { ?>checked<?php } ?> />
						<?php echo $entry_card_new; ?>
					</label>
				</div>
			</div>
		</div>
		<div id="opayo-card-existing" style="display: <?php if ($cards) { ?>block<?php } else { ?>none<?php } ?>">                
			<div class="form-group required">
				<label class="col-sm-2 control-label" for="input-opayo-card"><?php echo $entry_card_choice; ?></label>
				<div class="col-sm-8">
					<select name="opayo_card_token" id="input-opayo-card" class="form-control">
						<?php foreach ($cards as $card) { ?>
						<option value="<?php echo $card['token']; ?>"><?php echo $text_card_type; ?> <?php echo $card['type']; ?>, <?php echo $text_card_digits; ?> <?php echo $card['digits']; ?>, <?php echo $text_card_expiry; ?> <?php echo $card['expiry']; ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-sm-2">
					<input type="button" value="<?php echo $button_delete_card; ?>" id="opayo-button-delete-card" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger" />
				</div>
			</div>
			<div class="form-group required">
				<label class="col-sm-2 control-label" for="input-card-cvv2"><?php echo $entry_card_cvv2; ?></label>
				<div class="col-sm-10">
					<input type="text" name="opayo_card_cvv2_1" value="" placeholder="<?php echo $entry_card_cvv2; ?>" id="input-card-cvv2" class="form-control" />
				</div>
			</div>
		</div>
		<div id="opayo-card-new" style="display: <?php if (!$cards) { ?>block<?php } else { ?>none<?php } ?>">
			<div class="form-group required">
				<label class="col-sm-2 control-label" for="input-card-owner"><?php echo $entry_card_owner; ?></label>
				<div class="col-sm-10">
					<input type="text" name="opayo_card_owner" value="" placeholder="<?php echo $entry_card_owner; ?>" id="input-card-owner" class="form-control" />
				</div>
			</div>
			<div class="form-group required">
				<label class="col-sm-2 control-label" for="input-card-type"><?php echo $entry_card_type; ?></label>
				<div class="col-sm-10">
					<select name="opayo_card_type" id="input-card-type" class="form-control">
						<?php foreach ($card_types as $card_type) { ?>
						<option value="<?php echo $card_type['code']; ?>"><?php echo $card_type['name']; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="form-group required">
				<label class="col-sm-2 control-label" for="input-card-number"><?php echo $entry_card_number; ?></label>
				<div class="col-sm-10">
					<input type="text" name="opayo_card_number" value="" placeholder="<?php echo $entry_card_number; ?>" id="input-card-number" class="form-control" />
				</div>
			</div>
			<div class="form-group required">
				<label class="col-sm-2 control-label" for="input-card-expire-date"><?php echo $entry_card_expire_date; ?></label>
				<div class="col-sm-5">
					<select name="opayo_card_expire_date_month" id="input-card-expire-date" class="form-control">
						<?php foreach ($months as $month) { ?>
						<option value="<?php echo $month['code']; ?>"><?php echo $month['name']; ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-sm-5">
					<select name="opayo_card_expire_date_year" class="form-control">
						<?php foreach ($years as $year) { ?>
						<option value="<?php echo $year['code']; ?>"><?php echo $year['name']; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="form-group required">
				<label class="col-sm-2 control-label" for="input-card-cvv2"><?php echo $entry_card_cvv2; ?></label>
				<div class="col-sm-10">
					<input type="text" name="opayo_card_cvv2_2" value="" placeholder="<?php echo $entry_card_cvv2; ?>" id="input-card-cvv2" class="form-control" />
				</div>
			</div>
			<?php if ($logged && $card_save) { ?>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-card-save"><?php echo $entry_card_save; ?></label>
				<div class="col-sm-2">
					<input type="checkbox" name="opayo_card_save" value="1" id="input-card-save" checked />
				</div>
			</div>
			<?php } ?>
		</div>
	</fieldset>
	<div class="buttons">
		<div class="pull-right">
			<button type="button" id="opayo-button-confirm" class="btn btn-primary" data-loading-text="<?php echo $text_loading; ?>"><?php echo $button_confirm; ?></button>
		</div>
	</div>
</div>

<script type="text/javascript">

updateOpayo();

function updateOpayo() {	
	$('#opayo-form .input-opayo-card-existing').on('change', function() {
		if ($(this).val() == 1) {
			$('#opayo-card-existing').show();
			$('#opayo-card-new').hide();
		} else {
			$('#opayo-card-existing').hide();
			$('#opayo-card-new').show();
		}
	});

	$('#opayo-form #opayo-button-delete-card').on('click', function () {
		if (confirm('<?php echo $text_confirm_delete; ?>')) {
			$.ajax({
				url: 'index.php?route=extension/payment/opayo/deleteCard',
				type: 'post',
				data: $('#opayo-card-existing :input[name=\'opayo_card_token\']'),
				dataType: 'json',
				beforeSend: function () {
					$('#opayo-button-delete-card').button('loading');
				},
				complete: function () {
					$('#opayo-button-delete-card').button('reset');
				},
				success: function (json) {
					if (json['error']) {
						alert(json['error']);
					}
			
					if (json['success']) {
						$('#opayo-form').load('index.php?route=extension/payment/opayo/getForm #opayo-form >', function() {
							updateOpayo();
						});
					}
				}
			});
		}
	});

	$('#opayo-form #opayo-button-confirm').bind('click', function() {
		$('#opayo-form #browser-info').remove();
	
		html  = '<div id="browser-info">';
		html += '<input type="hidden" name="BrowserColorDepth" value="' + window.screen.colorDepth + '" />';
		html += '<input type="hidden" name="BrowserScreenHeight" value="' + window.screen.height + '" />';
		html += '<input type="hidden" name="BrowserScreenWidth" value="' + window.screen.width + '" />';
		html += '<input type="hidden" name="BrowserTZ" value="' + new Date().getTimezoneOffset() + '" />';
		html += '</div>';
	
		$('#opayo-form').append(html);
	
		$.ajax({
			type: 'post',
			url: 'index.php?route=extension/payment/opayo/confirm',
			data: $('#opayo-form input[type="radio"]:checked, #opayo-form input[type="checkbox"]:checked, #opayo-form input[type="text"], #opayo-form input[type="hidden"], #opayo-form select'),
			dataType: 'json',
			cache: false,
			beforeSend: function() {
				$('#opayo-button-confirm').button('loading');
			},
			complete: function() {
				$('#opayo-button-confirm').button('reset');
			},
			success: function(json) {
				if (json['ACSURL']) {
					$('#3dauth-form').remove();

					html = '<form action="' + json['ACSURL'] + '" method="post" id="3dauth-form">';
				
					if (json['CReq']) {
						html += '<input type="hidden" name="creq" value="' + json['CReq'] + '" />';
					}
				
					if (json['ACSTransID']) {
						html += '<input type="hidden" name="acsTransID" value="' + json['ACSTransID'] + '" />';
					}
				
					if (json['DSTransID']) {
						html += '<input type="hidden" name="dsTransID" value="' + json['DSTransID'] + '" />';
					}
								
					if (json['MD']) {
						html += '<input type="hidden" name="MD" value="' + json['MD'] + '" />';
					}
				
					if (json['PaReq']) {
						html += '<input type="hidden" name="PaReq" value="' + json['PaReq'] + '" />';
					}
				
					html += '<input type="hidden" name="TermUrl" value="' + json['TermUrl'] + '" />';
					html += '</form>';
	
					$('#opayo-form').append(html);

					$('#3dauth-form').submit();
				}

				if (json['error']) {
					alert(json['error']);
				}

				if (json['redirect']) {
					location = json['redirect'];
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
}

</script>