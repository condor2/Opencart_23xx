<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-exchange"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-backup" data-toggle="tab"><?php echo $tab_backup; ?></a></li>
          <li><a href="#tab-restore" data-toggle="tab"><?php echo $tab_restore; ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-backup">
            <form action="<?php echo $export; ?>" method="post" enctype="multipart/form-data" id="form-export" class="form-horizontal">
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_export; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($tables as $table) { ?>
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" name="backup[]" value="<?php echo $table; ?>" checked="checked" />
                        <?php echo $table; ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                  <button type="button" onclick="$(this).parent().find(':checkbox').prop('checked', true);" class="btn btn-link"><?php echo $text_select_all; ?></button>
                  /
                  <button type="button" onclick="$(this).parent().find(':checkbox').prop('checked', false);" class="btn btn-link"><?php echo $text_unselect_all; ?></button>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2">
                  <button type="submit" form="form-export" class="btn btn-default"><i class="fa fa-download"></i> <?php echo $button_export; ?></button>
                </div>
              </div>
            </form>
          </div>
          <div class="tab-pane" id="tab-restore">
            <form class="form-horizontal">
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_progress; ?></label>
                <div class="col-sm-10">
                  <div id="progress-import" class="progress">
                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2">
                  <button type="button" id="button-import" class="btn btn-primary"><i class="fa fa-upload"></i> <?php echo $button_import; ?></button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-import').on('click', function() {
	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="import" /></form>');

	$('#form-upload input[name=\'import\']').trigger('click');

	if (typeof timer != 'undefined') {
		clearInterval(timer);
	}
	
	timer = setInterval(function() {
		if ($('#form-upload input[name=\'import\']').val() != '') {
			clearInterval(timer);
	
			$('#progress-import .progress-bar').attr('aria-valuenow', 0);
			$('#progress-import .progress-bar').css('width', '0%');
	
			$.ajax({
				url: 'index.php?route=tool/backup/import&token=<?php echo $token; ?>',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$('#button-import').button('loading');
				},
				complete: function() {
					$('#button-import').button('reset');
				},
				success: function(json) {
					$('.alert').remove();
					
					if (json['error']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
					
					if (json['success']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
					
					if (json['total']) {
						$('#progress-import .progress-bar').attr('aria-valuenow', json['total']);
						$('#progress-import .progress-bar').css('width', json['total'] + '%');
					}
					
					if (json['next']) {
						next(json['next']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});

function next(url) {
	$.ajax({
		url: url,
		dataType: 'json',
		success: function(json) {
			$('.alert').remove();
			
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
			
			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
			
			if (json['total']) {
				$('#progress-import .progress-bar').attr('aria-valuenow', json['total']);
				$('#progress-import .progress-bar').css('width', json['total'] + '%');
			}
			
			if (json['next']) {
				next(json['next']);
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}
  //--></script> 
</div>
<?php echo $footer; ?>