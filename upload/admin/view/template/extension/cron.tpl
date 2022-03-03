<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-cron').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
    <?php if ($success) { ?>
      <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    <?php } ?>
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-question-circle"></i> <?php echo $text_instruction; ?></h3>
      </div>
      <div class="panel-body">
        <p><?php echo $text_cron_1; ?></p>
        <p><?php echo $text_cron_2; ?></p>
        <div class="form-group">
          <div class="col-sm-10">
            <div class="input-group">
              <span class="input-group-addon"><?php echo $entry_cron; ?></span>
              <input type="text" value="wget &quot;<?php echo $cron; ?>&quot; --read-timeout=5400" id="input-cron" class="form-control"/>
              <div class="input-group-btn">
                <button type="button" id="button-copy" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn btn-default"><i class="fa fa-copy"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-cron">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').trigger('click');"/></td>
                  <td class="text-left"><?php if ($sort == 'code') { ?><a href="<?php echo $sort_code; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_code; ?></a><?php } else { ?><a href="<?php echo $sort_code; ?>"><?php echo $column_code; ?></a><?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'cycle') { ?><a href="<?php echo $sort_cycle; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_cycle; ?></a><?php } else { ?><a href="<?php echo $sort_cycle; ?>"><?php echo $column_cycle; ?></a><?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'action') { ?><a href="<?php echo $sort_action; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_action; ?></a><?php } else { ?><a href="<?php echo $sort_action; ?>"><?php echo $column_action; ?></a><?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'status') { ?><a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a><?php } else { ?><a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a><?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'date_added') { ?><a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a><?php } else { ?><a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a><?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'date_modified') { ?><a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_modified; ?></a><?php } else { ?><a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a><?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($crons) { ?>
                  <?php foreach ($crons as $cron) { ?>
                    <tr>
                      <td class="text-center"><?php if (in_array($cron['cron_id'], $selected)) { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $cron['cron_id']; ?>" checked="checked"/>
                        <?php } else { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $cron['cron_id']; ?>"/>
                        <?php } ?></td>
                      <td class="text-left"><?php echo $cron['code']; ?></td>
                      <td class="text-left"><?php echo $cron['cycle']; ?></td>
                      <td class="text-left"><?php echo $cron['action']; ?></td>
                      <td class="text-left"><?php echo $cron['status']; ?></td>
                      <td class="text-left"><?php echo $cron['date_added']; ?></td>
                      <td class="text-left"><?php echo $cron['date_modified']; ?></td>
                      <td class="text-right">
                        <button type="button" value="<?php echo $cron['cron_id']; ?>" data-toggle="tooltip" data-title="<?php echo $button_run; ?>" class="btn btn-warning"><i class="fa fa-play"></i></button>
                        <?php if (!$cron['enabled']) { ?>
                          <button type="button" value="<?php echo $cron['cron_id']; ?>" data-toggle="tooltip" data-title="<?php echo $button_enable; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></button>
                        <?php } else { ?>
                          <button type="button" value="<?php echo $cron['cron_id']; ?>" data-toggle="tooltip" data-title="<?php echo $button_disable; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
                        <?php } ?></td>
                    </tr>
                  <?php } ?>
                <?php } else { ?>
                  <tr>
                    <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#button-copy').on('click', function() {
	$('#input-cron').select();
	document.execCommand('copy');
});
$('#form-cron .btn-warning').on('click', function() {
	var element = this;
	$.ajax({
		url: 'index.php?route=extension/cron/run&token=<?php echo $token; ?>&cron_id=' + $(element).val(),
		dataType: 'json',
		beforeSend: function() {
			$(element).button('loading');
		},
		complete: function() {
			$(element).button('reset');
		},
		success: function(json) {
			$('.alert').remove();
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
$('#form-cron').on('click', '.btn-success', function() {
	var element = this;
	$.ajax({
		url: 'index.php?route=extension/cron/enable&token=<?php echo $token; ?>&cron_id=' + $(element).val(),
		dataType: 'json',
		beforeSend: function() {
			$(element).button('loading');
		},
		complete: function() {
			$(element).button('reset');
		},
		success: function(json) {
			$('.alert').remove();
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				$(element).replaceWith('<button type="button" value="' + $(element).val() + '" data-toggle="tooltip" data-title="<?php echo $button_disable; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
$('#form-cron').on('click', '.btn-danger', function() {
	var element = this;
	$.ajax({
		url: 'index.php?route=extension/cron/disable&token=<?php echo $token; ?>&cron_id=' + $(element).val(),
		dataType: 'json',
		beforeSend: function() {
			$(element).button('loading');
		},
		complete: function() {
			$(element).button('reset');
		},
		success: function(json) {
			$('.alert').remove();
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				$(element).replaceWith('<button type="button" value="' + $(element).val() + '" data-toggle="tooltip" data-title="<?php echo $button_enable; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></button>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
//--></script>
<?php echo $footer; ?>