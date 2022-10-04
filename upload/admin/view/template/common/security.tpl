<div id="modal-security" class="modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title text-danger"><i class="fa fa-exclamation-triangle"></i> <?php echo $heading_title; ?></h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <?php echo $text_security; ?></div>
        <form class="form-horizontal">
          <fieldset>
            <legend><?php echo $text_choose; ?></legend>
            <div class="form-group">
              <select name="type" id="input-type" class="form-control">
                <option value="automatic"><?php echo $text_automatic; ?></option>
                <option value="manual"><?php echo $text_manual; ?></option>
              </select>
            </div>
          </fieldset>
          <fieldset id="collapse-automatic" class="collapse">
            <legend><?php echo $text_automatic; ?></legend>
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-btn dropdown">
                  <button type="button" id="button-path" data-toggle="dropdown" class="btn btn-default"><?php echo $document_root; ?> <span class="caret"></span></button>
                  <ul class="dropdown-menu">
                    <?php foreach ($paths as $path) { ?>
                    <?php if ($path > $document_root) { ?>
                    <li><a href="<?php echo $path; ?>"><i class="fa fa-exclamation-triangle fa-fw text-danger"></i> <?php echo $path; ?></a></li>
                    <?php } else { ?>
                    <li><a href="<?php echo $path; ?>"><i class="fa fa-check-circle fa-fw text-success"></i> <?php echo $path; ?></a></li>
                    <?php } ?>
                    <?php } ?>
                  </ul>
                </div>
                <input type="text" name="directory" value="storage" placeholder="<?php echo $entry_directory; ?>" class="form-control" />
                <div class="input-group-btn">
                  <button type="button" id="button-move" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger"><i class="fa fa-exclamation-triangle"></i> <?php echo $button_move; ?></button>
                </div>
              </div>
              <input type="hidden" name="path" value="<?php echo $document_root; ?>" />
            </div>
          </fieldset>
          <fieldset id="collapse-manual" class="collapse">
            <legend><?php echo $text_manual; ?></legend>
            <div class="form-group">
              <div style="height: 300px; overflow-y: scroll;" class="form-control" disabled="disabled"></div>
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#modal-security').modal('show');
});

$('#modal-security select[name=\'type\']').on('change', function() {
	$('#modal-security fieldset.collapse').removeClass('in');
	
	$('#modal-security #collapse-' + $(this).val()).addClass('in');
});

$('#modal-security select[name=\'type\']').trigger('change');

$('#modal-security .dropdown-menu a').on('click', function(e) {
	e.preventDefault();
	
	$('#modal-security #button-path').html($(this).html() + ' <span class="caret"></span>');
	
	$('#modal-security input[name=\'path\']').val($(this).attr('href'));
});


$('#modal-security .dropdown-menu a').on('click', function(e) {
	e.preventDefault();
	
	$('#button-path').html($(this).text() + ' <span class="caret"></span>');
	
	$('input[name=\'path\']').val($(this).attr('href'));
	
	$('input[name=\'path\']').trigger('change');
});

$('#button-move').on('click', function() {
	var element = this;
	
	$.ajax({
		url: 'index.php?route=common/security/move&token=<?php echo $token; ?>',
		type: 'post',
		data: $('input[name=\'path\'], input[name=\'directory\']'),
		dataType: 'json',
		crossDomain: true,
		beforeSend: function() {
			$(element).button('loading');
		},
		complete: function() {
			$(element).button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#modal-security .modal-body').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['success']) {
				$('#modal-security .modal-body').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#modal-security select[name=\'type\']').on('change', function () {
	html  = '<ol>';
	html += '<li><p><?php echo $text_move; ?></p>';
	html += '<p><strong><?php echo $storage; ?></strong></p>';
	html += '<p><?php echo $text_to; ?></p>';
	html += '<p><strong>' + $('#modal-security input[name=\'path\']').val() + $('#modal-security input[name=\'directory\']').val() + '/</strong></p></li>';
	html += '<li><p><?php echo $text_config; ?></p>';
	html += '<p><strong>define(\'DIR_STORAGE\', DIR_SYSTEM . \'storage/\');</strong></p>';
	html += '<p>to</p>';
	html += '<p><strong>define(\'DIR_STORAGE\', \'' + $('#modal-security input[name=\'path\']').val() + $('#modal-security input[name=\'directory\']').val() + '/\');</strong></p></li>';
	html += '<li><p><?php echo $text_admin; ?></p>';
	html += '<p><strong>define(\'DIR_STORAGE\', DIR_SYSTEM . \'storage/\');</strong></p>';
	html += '<p>to</p>';
	html += '<p><strong>define(\'DIR_STORAGE\', \'' + $('#modal-security input[name=\'path\']').val() + $('#modal-security input[name=\'directory\']').val() + '/\');</strong></p></li>';
    html += '</ol>';
	
	$('#collapse-manual .form-control').html(html);
});

$('input[name=\'path\']').trigger('change');
//--></script>
