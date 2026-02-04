<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-usps-oauth" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-usps-oauth" class="form-horizontal">
          
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-client-id"><?php echo $entry_client_id; ?></label>
            <div class="col-sm-10">
              <input type="text" name="usps_oauth_client_id" value="<?php echo $usps_oauth_client_id; ?>" placeholder="<?php echo $entry_client_id; ?>" id="input-client-id" class="form-control" />
              <?php if ($error_client_id) { ?>
              <div class="text-danger"><?php echo $error_client_id; ?></div>
              <?php } ?>
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-client-secret"><?php echo $entry_client_secret; ?></label>
            <div class="col-sm-10">
              <input type="text" name="usps_oauth_client_secret" value="<?php echo $usps_oauth_client_secret; ?>" placeholder="<?php echo $entry_client_secret; ?>" id="input-client-secret" class="form-control" />
              <?php if ($error_client_secret) { ?>
              <div class="text-danger"><?php echo $error_client_secret; ?></div>
              <?php } ?>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-postcode"><?php echo $entry_postcode; ?></label>
            <div class="col-sm-10">
              <input type="text" name="usps_oauth_postcode" value="<?php echo $usps_oauth_postcode; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-postcode" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_services; ?></label>
            <div class="col-sm-10">
              <div class="well well-sm" style="height: 150px; overflow: auto;">
                <div class="checkbox">
                  <label><input type="checkbox" name="usps_oauth_usps_ground_advantage" value="1" <?php echo $usps_oauth_usps_ground_advantage ? 'checked="checked"' : ''; ?> /> <?php echo $text_usps_ground_advantage; ?></label>
                </div>
                <div class="checkbox">
                  <label><input type="checkbox" name="usps_oauth_priority_mail" value="1" <?php echo $usps_oauth_priority_mail ? 'checked="checked"' : ''; ?> /> <?php echo $text_priority_mail; ?></label>
                </div>
                <div class="checkbox">
                  <label><input type="checkbox" name="usps_oauth_priority_mail_express" value="1" <?php echo $usps_oauth_priority_mail_express ? 'checked="checked"' : ''; ?> /> <?php echo $text_priority_mail_express; ?></label>
                </div>
                <div class="checkbox">
                  <label><input type="checkbox" name="usps_oauth_media_mail" value="1" <?php echo $usps_oauth_media_mail ? 'checked="checked"' : ''; ?> /> <?php echo $text_media_mail; ?></label>
                </div>
              </div>
            </div>
          </div>

<div class="form-group">
  <label class="col-sm-2 control-label" for="input-weight-class"><?php echo $entry_weight_class; ?></label>
  <div class="col-sm-10">
    <select name="usps_oauth_weight_class_id" id="input-weight-class" class="form-control">
      <?php foreach ($weight_classes as $weight_class) { ?>
      <option value="<?php echo $weight_class['weight_class_id']; ?>" <?php echo ($weight_class['weight_class_id'] == $usps_oauth_weight_class_id) ? 'selected="selected"' : ''; ?>><?php echo $weight_class['title']; ?></option>
      <?php } ?>
    </select>
  </div>
</div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-price-type"><?php echo $entry_price_type; ?></label>
            <div class="col-sm-10">
              <select name="usps_oauth_price_type" id="input-price-type" class="form-control">
                <option value="RETAIL" <?php echo ($usps_oauth_price_type == 'RETAIL') ? 'selected="selected"' : ''; ?>><?php echo $text_retail; ?></option>
                <option value="COMMERCIAL" <?php echo ($usps_oauth_price_type == 'COMMERCIAL') ? 'selected="selected"' : ''; ?>><?php echo $text_commercial; ?></option>
              </select>
            </div>
          </div>

<div class="form-group">
  <label class="col-sm-2 control-label" for="input-handling-fee">
    <span data-toggle="tooltip" title="Flat amount to add to the shipping total (e.g., 2.50)">Handling Fee</span>
  </label>
  <div class="col-sm-10">
    <input type="text" name="usps_oauth_handling_fee" value="<?php echo $usps_oauth_handling_fee; ?>" placeholder="0.00" id="input-handling-fee" class="form-control" />
  </div>
</div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-test"><?php echo $entry_test; ?></label>
            <div class="col-sm-10">
              <select name="usps_oauth_test" id="input-test" class="form-control">
                <option value="1" <?php echo $usps_oauth_test ? 'selected="selected"' : ''; ?>><?php echo $text_yes; ?></option>
                <option value="0" <?php echo !$usps_oauth_test ? 'selected="selected"' : ''; ?>><?php echo $text_no; ?></option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
              <select name="usps_oauth_geo_zone_id" id="input-geo-zone" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" <?php echo ($geo_zone['geo_zone_id'] == $usps_oauth_geo_zone_id) ? 'selected="selected"' : ''; ?>><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="usps_oauth_status" id="input-status" class="form-control">
                <option value="1" <?php echo $usps_oauth_status ? 'selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
                <option value="0" <?php echo !$usps_oauth_status ? 'selected="selected"' : ''; ?>><?php echo $text_disabled; ?></option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="usps_oauth_sort_order" value="<?php echo $usps_oauth_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-debug"><?php echo $entry_debug_mode; ?></label>
            <div class="col-sm-10">
              <select name="usps_oauth_debug_mode" id="input-debug" class="form-control">
                <option value="1" <?php echo $usps_oauth_debug_mode ? 'selected="selected"' : ''; ?>><?php echo $text_yes; ?></option>
                <option value="0" <?php echo !$usps_oauth_debug_mode ? 'selected="selected"' : ''; ?>><?php echo $text_no; ?></option>
              </select>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
