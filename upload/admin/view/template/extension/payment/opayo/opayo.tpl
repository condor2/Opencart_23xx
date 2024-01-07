<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-payment" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
			<h1><?php echo $heading_title; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-payment" class="form-horizontal">
					<ul class="nav nav-tabs">
						<li class="nav-tab active"><a href="#tab-general" data-toggle="tab"><?php echo $text_tab_general; ?></a></li>
						<li class="nav-tab"><a href="#tab-cron" data-toggle="tab"><?php echo $text_tab_cron; ?></a></li>
					</ul>
					
					<div class="tab-content">
						<div class="tab-pane active" id="tab-general">
							<div class="form-group required">
								<label class="col-sm-2 control-label" for="input-vendor"><?php echo $entry_vendor; ?></label>
								<div class="col-sm-10">
									<input type="text" name="opayo_vendor" value="<?php echo $vendor; ?>" placeholder="<?php echo $entry_vendor; ?>" id="input-vendor" class="form-control" />
									<?php if ($error_vendor) { ?>
									<div class="text-danger"><?php echo $error_vendor; ?></div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
								<div class="col-sm-10">
									<select name="opayo_status" id="input-status" class="form-control">
										<?php if ($status) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-general-environment"><?php echo $entry_environment; ?></label>
								<div class="col-sm-10">
									<select name="opayo_setting[general][environment]" id="input-general-environment" class="form-control">
										<?php foreach ($setting['environment'] as $environment) { ?>
										<?php if ($environment['code'] == $setting['general']['environment']) { ?>
										<option value="<?php echo $environment['code']; ?>" selected="selected"><?php echo ${$environment['name']}; ?></option>
										<?php } else { ?>
										<option value="<?php echo $environment['code']; ?>"><?php echo ${$environment['name']}; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-general-transaction-method"><span data-toggle="tooltip" title="<?php echo $help_transaction_method; ?>"><?php echo $entry_transaction_method; ?></span></label>
								<div class="col-sm-10">
									<select name="opayo_setting[general][transaction_method]" id="input-general-transaction-method" class="form-control">
										<?php foreach ($setting['transaction_method'] as $transaction_method) { ?>
										<?php if ($transaction_method['code'] == $setting['general']['transaction_method']) { ?>
										<option value="<?php echo $transaction_method['code']; ?>" selected="selected"><?php echo ${$transaction_method['name']}; ?></option>
										<?php } else { ?>
										<option value="<?php echo $transaction_method['code']; ?>"><?php echo ${$transaction_method['name']}; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-general-card-save"><?php echo $entry_card_save; ?></label>
								<div class="col-sm-10">
									<div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <?php echo $help_card_save; ?></div>
									<select name="opayo_setting[general][card_save]" id="input-general-card-save" class="form-control">
										<?php if ($setting['general']['card_save']) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-general-debug"><span data-toggle="tooltip" title="<?php echo $help_debug; ?>"><?php echo $entry_debug; ?></span></label>
								<div class="col-sm-10">
									<select name="opayo_setting[general][debug]" id="input-general-debug" class="form-control">
										<?php if ($setting['general']['debug']) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-general-order-status"><?php echo $entry_order_status; ?></label>
								<div class="col-sm-10">
									<select name="opayo_setting['general']['order_status_id']" id="input-general-order-status" class="form-control">
										<?php foreach ($order_statuses as $order_status) { ?>
										<?php if ($order_status['order_status_id'] == $setting['general']['order_status_id']) { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="opayo_total" value="<?php echo $total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
								<div class="col-sm-10">
									<select name="opayo_geo_zone_id" id="input-geo-zone" class="form-control">
										<option value="0"><?php echo $text_all_zones; ?></option>
										<?php foreach ($geo_zones as $geo_zone) { ?>
										<?php if ($geo_zone['geo_zone_id'] == $geo_zone_id) { ?>
										<option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>							
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
								<div class="col-sm-10">
									<input type="text" name="opayo_sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
								</div>
							</div>
						</div>	
						<div class="tab-pane" id="tab-cron">
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-cron-token"><span data-toggle="tooltip" title="<?php echo $help_cron_token; ?>"><?php echo $entry_cron_token; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="opayo_setting[cron][token]" value="<?php echo $setting['cron']['token']; ?>" placeholder="<?php echo $entry_cron_token; ?>" id="input-cron-token" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-cron-url"><span data-toggle="tooltip" title="<?php echo $help_cron_url; ?>"><?php echo $entry_cron_url; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="opayo_setting[cron][url]" value="<?php echo $setting['cron']['url']; ?>" placeholder="<?php echo $entry_cron_url; ?>" id="input-cron-url" class="form-control" />
								</div>
							</div>
							<?php if ($setting['cron']['last_run']) { ?>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_cron_last_run; ?></label>
								<div class="col-sm-10"><?php echo $setting['cron']['last_run']; ?></div>
							</div>
							<?php } ?>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?>
