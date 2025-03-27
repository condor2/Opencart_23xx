<form>
	<div class="row">
		<div class="col-sm-6">
			<div class="address"><?php echo $shipping_address['firstname']; ?> <?php echo $shipping_address['lastname']; ?><br /><?php echo $shipping_address['address_1']; ?><?php if ($shipping_address['address_2']) { ?>, <?php echo $shipping_address['address_2']; ?><?php } ?><br /><?php echo $shipping_address['city']; ?>, <?php echo $shipping_address['zone']; ?> <?php echo $shipping_address['postcode']; ?>, <?php echo $shipping_address['country']; ?><?php if ($guest['telephone']) { ?><br /><?php echo $guest['telephone']; ?><?php } ?></div>
			<input type="hidden" name="firstname" value="<?php echo $shipping_address['firstname']; ?>" id="input_shipping_firstname" />
			<input type="hidden" name="lastname" value="<?php echo $shipping_address['lastname']; ?>" id="input_shipping_lastname" />
			<input type="hidden" name="address_1" value="<?php echo $shipping_address['address_1']; ?>" id="input_shipping_address_1" />
			<input type="hidden" name="address_2" value="<?php echo $shipping_address['address_2']; ?>" id="input_shipping_address_2" />
			<input type="hidden" name="city" value="<?php echo $shipping_address['city']; ?>" id="input_shipping_city" />
			<input type="hidden" name="postcode" value="<?php echo $shipping_address['postcode']; ?>" id="input_shipping_postcode" />
			<input type="hidden" name="country_id" value="<?php echo $shipping_address['country_id']; ?>" id="input_shipping_country" />
			<input type="hidden" name="zone_id" value="<?php echo $shipping_address['zone_id']; ?>" id="input_shipping_zone" />
			<input type="hidden" name="telephone" value="<?php echo $guest['telephone']; ?>" id="input_telephone" />
		</div>
		<div class="col-sm-6">
			<div class="text-right">
				<button class="button-edit btn btn-default"><?php echo $button_edit; ?></button>
			</div>
		</div>
	</div>
	<br />
	<div class="text-center">
		<button class="button-confirm btn btn-primary"><?php echo $button_confirm; ?></button>
	</div>
</form>