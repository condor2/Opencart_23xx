<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset>
          <legend><?php echo $text_password; ?></legend>
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
        </fieldset>
        <div class="buttons clearfix">
          <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
          <div class="pull-right"><button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $button_continue; ?></button></div>
        </div>
      </form>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>