<div id="shipping_address" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fal fa-close"></i></button>
				<h4 class="modal-title"><?php echo $text_checkout_shipping_address; ?></h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input_shipping_firstname"><?php echo $entry_firstname; ?></label>
						<div class="col-sm-10">
							<input type="text" name="firstname" value="<?php echo $shipping_address['firstname']; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input_shipping_firstname" class="form-control" />
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input_shipping_lastname"><?php echo $entry_lastname; ?></label>
						<div class="col-sm-10">
							<input type="text" name="lastname" value="<?php echo $shipping_address['lastname']; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input_shipping_lastname" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input_shipping_company"><?php echo $entry_company; ?></label>
						<div class="col-sm-10">
							<input type="text" name="company" value="<?php echo $shipping_address['company']; ?>" placeholder="<?php echo $entry_company; ?>" id="input_shipping_company" class="form-control" />
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input_shipping_address-1"><?php echo $entry_address_1; ?></label>
						<div class="col-sm-10">
							<input type="text" name="address_1" value="<?php echo $shipping_address['address_1']; ?>" placeholder="<?php echo $entry_address_1; ?>" id="input_shipping_address-1" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input_shipping_address-2"><?php echo $entry_address_2; ?></label>
						<div class="col-sm-10">
							<input type="text" name="address_2" value="<?php echo $shipping_address['address_2']; ?>" placeholder="<?php echo $entry_address_2; ?>" id="input_shipping_address-2" class="form-control" />
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input_shipping_city"><?php echo $entry_city; ?></label>
						<div class="col-sm-10">
							<input type="text" name="city" value="<?php echo $shipping_address['city']; ?>" placeholder="<?php echo $entry_city; ?>" id="input_shipping_city" class="form-control" />
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input_shipping_postcode"><?php echo $entry_postcode; ?></label>
						<div class="col-sm-10">
							<input type="text" name="postcode" value="<?php echo $shipping_address['postcode']; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input_shipping_postcode" class="form-control" />
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input_shipping_country"><?php echo $entry_country; ?></label>
						<div class="col-sm-10">
							<select name="country_id" id="input_shipping_country" class="form-control">
								<option value=""><?php echo $text_select; ?></option>
								<?php foreach ($countries as $country) { ?>
								<?php if ($country['country_id'] == $shipping_address['country_id']) { ?>
								<option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
								<?php } else { ?>
								<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input_shipping_zone"><?php echo $entry_zone; ?></label>
						<div class="col-sm-10">
							<select name="zone_id" id="input_shipping_zone" class="form-control"></select>
						</div>
					</div>
					<?php foreach ($custom_fields as $custom_field) { ?>
					<?php if ($custom_field['location'] == 'address') { ?>
					<?php if ($custom_field['type'] == 'select') { ?>
					<div id="shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label" for="input_shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
						<select name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" id="input_shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>" class="form-control">
							<option value=""><?php echo $text_select; ?></option>
							<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
							<?php if ($shipping_address['custom_field'][$custom_field['custom_field_id']] && ($custom_field_value['custom_field_value_id'] == $shipping_address['custom_field'][$custom_field['custom_field_id']])) { ?>
							<?php } else { ?>
							<option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
							<?php } ?>
							<?php } ?>
						</select>
					</div>
					<?php } ?>
					<?php if ($custom_field['type'] == 'radio') { ?>
					<div id="shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom_field" data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label"><?php echo $custom_field['name']; ?></label>
						<div id="input_shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>">
							<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
							<div class="radio"> 
								<?php if ($shipping_address['custom_field'][$custom_field['custom_field_id']] && ($custom_field_value['custom_field_value_id'] == $shipping_address['custom_field'][$custom_field['custom_field_id']])) { ?>
								<label>
									<input type="radio" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
									<?php echo $custom_field_value['name']; ?>
								</label>
								<?php } else { ?>
								<label>
									<input type="radio" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
									<?php echo $custom_field_value['name']; ?>
								</label>
								<?php } ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<?php } ?>
					<?php if ($custom_field['type'] == 'checkbox') { ?>
					<div id="shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label"><?php echo $custom_field['name']; ?></label>
						<div id="input_shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>"> 
							<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
							<div class="checkbox"> 
								<?php if ($shipping_address['custom_field'][$custom_field['custom_field_id']] && in_array($custom_field_value['custom_field_value_id'], $shipping_address['custom_field'][$custom_field['custom_field_id']])) { ?>
								<label>
									<input type="checkbox" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
									<?php echo $custom_field_value['name']; ?>
								</label>
								<?php } else { ?>
								<label>
									<input type="checkbox" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
									<?php echo $custom_field_value['name']; ?>
								</label>
								<?php } ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<?php } ?>
					<?php if ($custom_field['type'] == 'text') { ?>
					<div id="shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label" for="input_shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
						<input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php if ($shipping_address['custom_field'][$custom_field['custom_field_id']]) { ?> <?php echo $guest['custom_field'][$custom_field['custom_field_id']]; ?> <?php } else { ?> <?php echo $custom_field['value']; ?> <?php } ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input_shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
					</div>
					<?php } ?>
					<?php if ($custom_field['type'] == 'textarea') { ?>
					<div id="shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label" for="input_shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
						<textarea name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="input_shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"><?php if ($shipping_address['custom_field'][$custom_field['custom_field_id']]) { ?> <?php echo $guest['custom_field'][$custom_field['custom_field_id']]; ?> <?php } else { ?> <?php echo $custom_field['value']; ?> <?php } ?></textarea>
					</div>
					<?php } ?>
					<?php if ($custom_field['type'] == 'file') { ?>
					<div id="shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label"><?php echo $custom_field['name']; ?></label>
						<br />
						<button type="button" id="button_shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
						<input type="hidden" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php if ($shipping_address['custom_field'][$custom_field['custom_field_id']]) { ?> <?php echo $guest['custom_field'][$custom_field['custom_field_id']]; ?> <?php } ?>" id="input_shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>" />
					</div>
					<?php } ?>
					<?php if ($custom_field['type'] == 'date') { ?>
					<div id="shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label" for="input_shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
						<div class="input-group date">
							<input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php if ($shipping_address['custom_field'][$custom_field['custom_field_id']]) { ?> <?php echo $guest['custom_field'][$custom_field['custom_field_id']]; ?> <?php } else { ?> <?php echo $custom_field['value']; ?> <?php } ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD" id="input_shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
							<span class="input-group-btn">
								<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
							</span>
						</div>
					</div>
					<?php } ?>
					<?php if ($custom_field['type'] == 'time') { ?>
					<div id="shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label" for="input_shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
						<div class="input-group time">
							<input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php if ($shipping_address['custom_field'][$custom_field['custom_field_id']]) { ?> <?php echo $guest['custom_field'][$custom_field['custom_field_id']]; ?> <?php } else { ?> <?php echo $custom_field['value']; ?> <?php } ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="HH:mm" id="input_shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
							<span class="input-group-btn">
								<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
							</span>
						</div>
					</div>
					<?php } ?>
					<?php if ($custom_field['type'] == 'datetime') { ?>
					<div id="shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
						<label class="control-label" for="input_shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
						<div class="input-group datetime">
							<input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php if ($shipping_address['custom_field'][$custom_field['custom_field_id']]) { ?> <?php echo $guest['custom_field'][$custom_field['custom_field_id']]; ?> <?php } else { ?> <?php echo $custom_field['value']; ?> <?php } ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input_shipping_custom_field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
							<span class="input-group-btn">
								<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
							</span>
						</div>
					</div>
					<?php } ?>
					<?php } ?>
					<?php } ?>
				</form>
			</div>
			<div class="modal-footer">
				<button class="button-confirm btn btn-primary"><?php echo $button_confirm; ?></button>
			</div>
		</div>
	</div>
</div>