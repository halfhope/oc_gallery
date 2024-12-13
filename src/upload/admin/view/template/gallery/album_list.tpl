<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
      
      <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_text_insert; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a> 
      
      <a href="<?php echo $copy; ?>" onClick="$('#form-albums').attr('action','<?php echo $copy ?>');$('#form-albums').submit();return false;" data-toggle="tooltip" title="<?php echo $button_text_copy; ?>" class="btn btn-default"><i class="fa fa-files-o"></i></a>
      
      <a href="<?php echo $delete; ?>" onClick="confirm('<?php echo $text_confirm; ?>') ? $('#form-albums').submit() : false; return false;" data-toggle="tooltip" title="<?php echo $button_text_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>

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

    <div class="panel">
      <ul class="nav nav-pills">
        <li role="presentation" class="active"><a href="<?php echo $link_section_album_list ?>"><i class="fa fa-list fa-fw"></i> <?php echo $text_section_album_list ?></a></li>
        <li role="presentation"><a href="<?php echo $link_section_modules ?>"><i class="fa fa-puzzle-piece fa-fw"></i> <?php echo $text_section_modules ?></a></li>
        <li role="presentation"><a href="<?php echo $link_section_settings ?>"><i class="fa fa-cog fa-fw"></i> <?php echo $text_section_settings ?></a></li>
      </ul>
    </div>

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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
      <?php if (empty($albums)){ ?>
        <center><?php echo $text_list_empty ?></center>
      <?php }else{ ?>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-albums">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="text-align: center;" class="text-left" width="1">
                    <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);">
                  </td>
                  <td class="text-left"><?php echo $column_name ?></td>
                  <td class="text-left"><?php echo $column_album_type ?></td>
                  <td class="text-right"><?php echo $column_store ?></td>
                  <td class="text-right" width="100"><?php echo $column_enabled ?></td>
                  <td class="text-right" width="150"><?php echo $column_sort_order ?></td>
                  <td class="text-right" width="70"><?php echo $column_action ?></td>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($albums as $key => $album): ?>
                <tr style="cursor:pointer;">
                  <td style="text-align: center;" class="text-left">
                  <input type="checkbox" class="chk<?php echo $album['album_id'] ?>" name="selected[]" value="<?php echo $album['album_id'] ?>" />
                  </td>
                  <td class="text-left" onClick="window.location.href='<?php echo $album['edit_link'] ?>';"><?php echo $album['album_data']['album_name'][$config_admin_language_id] ?></td>
                  <td class="text-left" onClick="window.location.href='<?php echo $album['edit_link'] ?>';"><?php echo $album_types[$album['album_type']] ?></td>
                  <td class="text-right">
                    <?php foreach ($stores as $key => $store): ?>  
                      <?php if (isset($album['view_link'][(int)$store['store_id']])): ?>
                      <a href="<?php echo $album['view_link'][(int)$store['store_id']] ?>" target="_blank" data-toggle="tooltip" data-original-title="<?php echo $button_text_view ?>"><span class="label label-success"><?php echo $stores[(int)$store['store_id']]['name'] ?></span></a>                                             
                      <?php endif ?>
                    <?php endforeach ?>
                  </td>
                  <td class="text-right"><?php echo $arr_enabled[$album['enabled']]; ?></td>
                  <td class="text-right"><?php echo $album['sort_order'] ?></td>
                  <td class="text-right">

                  <a href="<?php echo $album['edit_link'] ?>" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="<?php echo $button_text_edit ?>"><i class="fa fa-pencil"></i></a>
                  </td>
                </tr>
                <?php endforeach ?>
              </tbody>
            </table>
          </div>
        </form>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>