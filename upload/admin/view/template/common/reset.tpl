<?php echo $header; ?>
<div id="content">
  <div class="container-fluid"><br />
    <br />
    <div class="row">
      <div class="col-sm-offset-4 col-sm-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h1 class="panel-title"><i class="fa fa-repeat"></i> <?php echo $heading_title; ?></h1>
          </div>
          <div class="panel-body">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
              <p><?php echo $text_password; ?></p>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
                <div class="col-sm-10">
                 <div class="input-group">
                  <input type="password" name="password" value="<?php echo $password; ?>" id="input-password" class="form-control" />
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="button" onclick="$('#input-password').attr('type') === 'password' ? $('#input-password').attr('type', 'text') : $('#input-password').attr('type', 'password'); $('#toggle-password').toggleClass('fa-eye fa-eye-slash');"><i id="toggle-password" class="fa fa-eye-slash"></i></button>
                  </span>
                 </div>
                  <?php if ($error_password) { ?>
                  <div class="text-danger"><?php echo $error_password; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-confirm"><?php echo $entry_confirm; ?></label>
                <div class="col-sm-10">
                 <div class="input-group">
                  <input type="password" name="confirm" value="<?php echo $confirm; ?>" id="input-confirm" class="form-control" />
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="button" onclick="$('#input-confirm').attr('type') === 'password' ? $('#input-confirm').attr('type', 'text') : $('#input-confirm').attr('type', 'password'); $('#toggle-confirm').toggleClass('fa-eye fa-eye-slash');"><i id="toggle-confirm" class="fa fa-eye-slash"></i></button>
                  </span>
                 </div>
                  <?php if ($error_confirm) { ?>
                  <div class="text-danger"><?php echo $error_confirm; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="text-right">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>