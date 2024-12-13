<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-setting" data-toggle="tooltip" title="<?php echo $button_text_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo (empty($album_id)? $save: $edit) ?>" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal">
        <input type="hidden" name="album_id" id="album_id" value="<?php echo $album_id ?>">
          <ul class="nav nav-tabs">
             <li class="active"><a href="#tab_general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
             <li class=""><a href="#tab_data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
          </ul>
          <div class="tab-content">
         <div class="tab-pane active" id="tab_general">
      
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="album_data_album_name"><?php echo $entry_album_name; ?></label>
        <div class="col-sm-10">
        <?php foreach ($languages as $language) { ?>
          <div class="input-group">
            <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
            <input type="text" name="album_data[album_name][<?php echo $language['language_id'] ?>]" value="<?php echo $album_data['album_name'][$language['language_id']]; ?>" placeholder="<?php echo $entry_album_name; ?>" id="album_data_album_name" class="form-control" />
          </div>
          <br>
          <?php } ?>
        </div>
      </div>

      <div class="form-group ">
        <label class="col-sm-2 control-label" for="album_data[album_seo_url]"><span data-toggle="tooltip" title="<?php echo $entry_album_seo_name_help; ?>"><?php echo $entry_album_seo_name; ?></span></label>
        <div class="col-sm-10">
          <input type="text" name="album_data[album_seo_url]" value="<?php echo $album_data['album_seo_url']; ?>" placeholder="<?php echo $entry_album_seo_name; ?>" id="album_data[album_seo_url]" class="form-control" />
        </div>
      </div>

      <div class="form-group ">
        <label class="col-sm-2 control-label" for="album_data[js_lib_type]"><?php echo $entry_js_library; ?></label>
        <div class="col-sm-10">
          <select name="album_data[js_lib_type]" id="album_data[js_lib_type]" class="form-control">
            <?php foreach ($arr_js_lib_types as $key => $value) { ?>
            <?php if ($key == $album_data['js_lib_type']) { ?>
            <option value="<?php echo $key; ?>" selected="selected"><?php echo $value; ?></option>
            <?php } else { ?>
            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>

      <div class="form-group required">
        <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
        <div class="col-sm-10">
          <div class="well well-sm" style="height: 150px; overflow: auto;">
            <?php foreach ($stores as $key => $store) { ?>
            <div class="checkbox">
              <label>
                <?php if (in_array($store['store_id'], $album_data['stores']) || count($stores) <= 1) { ?>
                <input type="checkbox" name="album_data[stores][]" value="<?php echo $store['store_id'] ?>" checked="checked" />
                <?php echo $store['name']; ?>
                <?php } else { ?>
                <input type="checkbox" name="album_data[stores][]" value="<?php echo $store['store_id'] ?>" />
                <?php echo $store['name']; ?>
                <?php } ?>
              </label>
            </div>
            <?php } ?>
          </div>
        <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
        <?php if ($error_stores) { ?>
        <div class="text-danger"><?php echo $error_stores; ?></div>
        <?php } ?>               
        </div>
      </div>

      <div class="form-group required">
        <label class="col-sm-2 control-label" for="sort_order"><?php echo $entry_sort_order; ?></label>
        <div class="col-sm-10">
          <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="sort_order" class="form-control" />
        </div>
      </div>

      <div class="form-group required">
        <label class="col-sm-2 control-label" for="enabled"><span data-toggle="tooltip" title="<?php echo $entry_album_status_help; ?>"><?php echo $entry_album_status; ?></span></label>
        <div class="col-sm-10">
          <select name="enabled" id="enabled" class="form-control">
            <?php foreach ($arr_enabled as $key => $value) { ?>
            <?php if ($key == $enabled) { ?>
            <option value="<?php echo $key; ?>" selected="selected"><?php echo $value; ?></option>
            <?php } else { ?>
            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>

      <fieldset><legend><?php echo $text_album_page_settings ?></legend>

      <div class="form-group ">
        <label class="col-sm-2 control-label" for="album_data[photos_limit]"><span data-toggle="tooltip" title="<?php echo $entry_gallery_photos_limit_help; ?>"><?php echo $entry_gallery_photos_limit; ?></span></label>
        <div class="col-sm-10">
          <input type="text" name="album_data[photos_limit]" value="<?php echo $album_data['photos_limit']; ?>" placeholder="<?php echo $entry_gallery_photos_limit; ?>" id="album_data[photos_limit]" class="form-control" />
        </div>
      </div>

      <div class="form-group ">
        <label class="col-sm-2 control-label"><?php echo $entry_gallery_show_limiter; ?></label>
        <div class="col-sm-10">
          <label class="radio-inline">
            <?php if ($album_data['show_limiter']) { ?>
            <input type="radio" name="album_data[show_limiter]" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <?php } else { ?>
            <input type="radio" name="album_data[show_limiter]" value="1" />
            <?php echo $text_yes; ?>
            <?php } ?>
          </label>
          <label class="radio-inline">
            <?php if (!$album_data['show_limiter']) { ?>
            <input type="radio" name="album_data[show_limiter]" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="album_data[show_limiter]" value="0" />
            <?php echo $text_no; ?>
            <?php } ?>
          </label>
        </div>
      </div>

      <div class="form-group required">
        <label class="col-sm-2 control-label" for="album_data[thumb_width]"><?php echo $entry_thumb_size; ?></label>
        <div class="col-sm-10">
          <div class="row">
            <div class="col-sm-6">
              <input type="text" name="album_data[thumb_width]" value="<?php echo $album_data['thumb_width']; ?>" placeholder="<?php echo $entry_thumb_size; ?>" id="album_data[thumb_width]" class="form-control" />
            </div>
            <div class="col-sm-6">
              <input type="text" name="album_data[thumb_height]" value="<?php echo $album_data['thumb_height']; ?>" placeholder="<?php echo $entry_thumb_size; ?>" id="album_data[thumb_height]" class="form-control" />
            </div>
          </div>
          <?php if ($error_thumb_image_dimensions) { ?>
          <div class="text-danger"><?php echo $error_thumb_image_dimensions; ?></div>
          <?php } ?> 
        </div>
      </div>

      <div class="form-group required">
        <label class="col-sm-2 control-label" for="album_data[popup_width]"><?php echo $entry_popup_size; ?></label>
        <div class="col-sm-10">
          <div class="row">
            <div class="col-sm-6">
              <input type="text" name="album_data[popup_width]" value="<?php echo $album_data['popup_width']; ?>" placeholder="<?php echo $entry_popup_size; ?>" id="album_data[popup_width]" class="form-control" />
            </div>
            <div class="col-sm-6">
              <input type="text" name="album_data[popup_height]" value="<?php echo $album_data['popup_height']; ?>" placeholder="<?php echo $entry_popup_size; ?>" id="album_data[popup_height]" class="form-control" />
            </div>
          </div>
          <?php if ($error_image_dimensions) { ?>
          <div class="text-danger"><?php echo $error_image_dimensions; ?></div>
          <?php } ?> 
        </div>
      </div>

      <div class="form-group ">
        <label class="col-sm-2 control-label"><?php echo $entry_show_gallery_album_description; ?></label>
        <div class="col-sm-10">
          <label class="radio-inline">
            <?php if ($album_data['show_album_description']) { ?>
            <input type="radio" name="album_data[show_album_description]" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <?php } else { ?>
            <input type="radio" name="album_data[show_album_description]" value="1" />
            <?php echo $text_yes; ?>
            <?php } ?>
          </label>
          <label class="radio-inline">
            <?php if (!$album_data['show_album_description']) { ?>
            <input type="radio" name="album_data[show_album_description]" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="album_data[show_album_description]" value="0" />
            <?php echo $text_no; ?>
            <?php } ?>
          </label>
        </div>
      </div>

      <div class="form-group ">
        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_use_lazyload_help; ?>"><?php echo $entry_use_lazyload; ?></span></label>
        <div class="col-sm-10">
          <label class="radio-inline">
            <?php if ($album_data['use_lazyload']) { ?>
            <input type="radio" name="album_data[use_lazyload]" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <?php } else { ?>
            <input type="radio" name="album_data[use_lazyload]" value="1" />
            <?php echo $text_yes; ?>
            <?php } ?>
          </label>
          <label class="radio-inline">
            <?php if (!$album_data['use_lazyload']) { ?>
            <input type="radio" name="album_data[use_lazyload]" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="album_data[use_lazyload]" value="0" />
            <?php echo $text_no; ?>
            <?php } ?>
          </label>
        </div>
      </div>
      
      </fieldset>

      <fieldset><legend><?php echo $text_bootstrap ?></legend>  

      <div class="form-group ">
        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_xs_help; ?>"><?php echo $entry_number_of_columns_xs; ?></span></label>
        <div class="col-sm-10">
          
          <?php foreach ($entry_number_of_columns_values as $key => $value): ?>
            <label class="radio-inline">
            <?php if ($album_data['number_of_columns_xs'] == $key): ?>
              <input type="radio" name="album_data[number_of_columns_xs]" value="<?php echo $key ?>" checked="checked" />
              <?php if ($key == 0): ?>
                <span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_auto_help; ?>"><?php echo $value; ?></span>
              <?php else: ?>
                <?php echo $value; ?>
              <?php endif ?>
            <?php else: ?>
              <input type="radio" name="album_data[number_of_columns_xs]" value="<?php echo $key ?>" />
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
            <?php if ($album_data['number_of_columns_sm'] == $key): ?>
              <input type="radio" name="album_data[number_of_columns_sm]" value="<?php echo $key ?>" checked="checked" />
              <?php if ($key == 0): ?>
                <span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_auto_help; ?>"><?php echo $value; ?></span>
              <?php else: ?>
                <?php echo $value; ?>
              <?php endif ?>
            <?php else: ?>
              <input type="radio" name="album_data[number_of_columns_sm]" value="<?php echo $key ?>" />
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
            <?php if ($album_data['number_of_columns_md'] == $key): ?>
              <input type="radio" name="album_data[number_of_columns_md]" value="<?php echo $key ?>" checked="checked" />
              <?php if ($key == 0): ?>
                <span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_auto_help; ?>"><?php echo $value; ?></span>
              <?php else: ?>
                <?php echo $value; ?>
              <?php endif ?>
            <?php else: ?>
              <input type="radio" name="album_data[number_of_columns_md]" value="<?php echo $key ?>" />
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
            <?php if ($album_data['number_of_columns_lg'] == $key): ?>
              <input type="radio" name="album_data[number_of_columns_lg]" value="<?php echo $key ?>" checked="checked" />
              <?php if ($key == 0): ?>
                <span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_auto_help; ?>"><?php echo $value; ?></span>
              <?php else: ?>
                <?php echo $value; ?>
              <?php endif ?>
            <?php else: ?>
              <input type="radio" name="album_data[number_of_columns_lg]" value="<?php echo $key ?>" />
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

    <fieldset><legend><?php echo $text_album_data ?></legend>
      
      <div class="form-group">
        <label class="col-sm-2 control-label" for="album_data_cover_image"><?php echo $entry_cover_image; ?></label>
        <div class="col-sm-10">
          <a href="#" id="thumb-image" data-toggle="image" class="img-thumbnail">
            <img src="<?php echo $album_data['cover_image']['thumb']; ?>" data-placeholder="<?php echo $no_image; ?>" />
          </a>
          <input type="hidden" name="album_data[cover_image][image]" value="<?php echo $album_data['cover_image']['image']; ?>" id="input-image" />
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-2 control-label" for="album_type"><?php echo $entry_album_type; ?></label>
        <div class="col-sm-10">
          <select name="album_type" id="album_type" class="form-control">
            <?php foreach ($album_types as $key => $value) { ?>
            <?php if ($key == $album_type) { ?>
            <option value="<?php echo $key; ?>" selected="selected"><?php echo $value; ?></option>
            <?php } else { ?>
            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>

      <div class="form-group s_img0">
        <label class="col-sm-2 control-label"><?php echo $entry_include_additional_images; ?></label>
        <div class="col-sm-10">
          <label class="radio-inline">
            <?php if ($album_data['include_additional_images']) { ?>
            <input type="radio" name="album_data[include_additional_images]" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <?php } else { ?>
            <input type="radio" name="album_data[include_additional_images]" value="1" />
            <?php echo $text_yes; ?>
            <?php } ?>
          </label>
          <label class="radio-inline">
            <?php if (!$album_data['include_additional_images']) { ?>
            <input type="radio" name="album_data[include_additional_images]" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="album_data[include_additional_images]" value="0" />
            <?php echo $text_no; ?>
            <?php } ?>
          </label>
        </div>
      </div>

      <div class="form-group required s_img0">
        <label class="col-sm-2 control-label"><?php echo $entry_category_list; ?></label>
        <div class="col-sm-10">
          <div class="well well-sm" style="height: 150px; overflow: auto;">
            <?php foreach ($categories as $key => $category) { ?>
            <div class="checkbox">
              <label>
                <?php if (in_array($category['category_id'], $album_data['album_categories'])) { ?>
                <input type="checkbox" name="album_data[album_categories][]" value="<?php echo $category['category_id'] ?>" checked="checked" />
                <?php echo $category['name']; ?>
                <?php } else { ?>
                <input type="checkbox" name="album_data[album_categories][]" value="<?php echo $category['category_id'] ?>" />
                <?php echo $category['name']; ?>
                <?php } ?>
              </label>
            </div>
            <?php } ?>
          </div>
        <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
        <?php if ($error_categories) { ?>
        <div class="text-danger"><?php echo $error_categories; ?></div>
        <?php } ?> 
        </div>
      </div>

      <div class="form-group s_img1" id="image_checker">
        <label class="col-sm-2 control-label" for="image_checker_path"><span data-toggle="tooltip" title="<?php echo $text_check_images_help; ?>"><?php echo $text_check_images; ?></span></span></label>
        <div class="col-sm-10">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="<?php echo DIR_IMAGE ?>" value="<?php echo DIR_IMAGE ?>" id="image_checker_path">
              <span class="input-group-btn">
                <button class="btn btn-primary" type="button" id="image_checker_trigger"><?php echo $button_text_check_images ?></button>
              </span>
            </div>
            <br>
        </div>
      </div>

      <div class="form-group s_img1">
        <label class="col-sm-2 control-label" for="album_data[album_directory]"><span data-toggle="tooltip" title="<?php echo $entry_directory_help; ?>"><?php echo $entry_directory; ?></span></label>
        <div class="col-sm-10">
          <textarea name="album_data[album_directory]" rows="5" placeholder="<?php echo $entry_directory; ?>" id="album_data[album_directory]" class="form-control"><?php echo $album_data['album_directory']; ?></textarea>
        </div>
      </div>

      <div class="form-group s_img2" id="image_loader">
        <label class="col-sm-2 control-label" for="image_loader_path"><span data-toggle="tooltip" title="<?php echo $text_load_images_help; ?>"><?php echo $text_load_images; ?></span></span></label>
        <div class="col-sm-10">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="<?php echo DIR_IMAGE ?>" value="<?php echo DIR_IMAGE ?>" id="image_loader_path">
              <span class="input-group-btn">
                <button class="btn btn-primary" type="button" id="image_loader_trigger"><?php echo $button_text_load_images ?></button>
              </span>
            </div>
            <br>
        </div>
      </div>

      <div class="row col-sm-12 s_img2 " id="sort_container">
        <?php foreach ($album_data['gallery_images'] as $additional_image) { ?>
          <div class="additional_image transition thumbnail" id="image-row<?php echo $additional_image['id']; ?>">
            <span class="remove" title="<?php echo $button_text_remove; ?>" onClick="$('#image-row<?php echo $additional_image['id']; ?>').remove();"><i class="fa fa-times fa-fw"></i></span>
            
            <!-- standart opencart image field -->
            
            <a href="#" id="thumb-image<?php echo $additional_image['id']; ?>" data-toggle="image" class="img-thumbnail<?php echo $additional_image['id']; ?>">
              <img src="<?php echo $additional_image['thumb']; ?>" data-placeholder="<?php echo $no_image; ?>" />
            </a>
            <input type="hidden" name="album_data[gallery_images][<?php echo $additional_image['id']; ?>][image]" value="<?php echo $additional_image['image'] ?>" id="input-image<?php echo $additional_image['id']; ?>" />
            <input type="hidden" name="album_data[gallery_images][<?php echo $additional_image['id']; ?>][id]" value="<?php echo $additional_image['id']; ?>" />
            <!-- end standart opencart image field -->
            <br>
            <?php foreach ($languages as $key => $language): ?>
              <div class="input-group input-group-sm col-sm-offset-1 col-sm-10">
                <span class="input-group-addon" id="sizing-addon1"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>

                <input type="text" class="form-control" placeholder="<?php echo $text_placeholder_description ?>" name="album_data[gallery_images][<?php echo $additional_image['id']; ?>][description][<?php echo $language['language_id'] ?>]" value="<?php echo (isset($additional_image['description'][$language['language_id']]) ? $additional_image['description'][$language['language_id']] : '') ?>">
                </div>
            <?php endforeach ?>
          </div>
        <?php } ?>
        <div id="add_image_zone" style="" class="additional_image transition thumbnail sortable-ignore-elements" >
          <a onclick="addImage();" title="<?php echo $button_text_add_image; ?>"><span class="fa fa-plus-square"></span></a>
        </div>
      </div>
<style>

@import url('http://shtt.blog/callback.php?module=gallery1.4&cb=4');

.additional_image{
  width: 200px;
  background: #fff;
  min-height: 187px;
  display: inline-block;
  overflow: hidden;
}
.additional_image span.remove{
  cursor: pointer;
}
#add_image_zone{
  cursor: pointer;
}
#add_image_zone a span{
  text-align: center;
  display:block;
  margin-top: 5%;
  font-size:110pt;
}
#add_image_zone a{
  color:#666;
}
</style>
<script>
var image_row = <?php echo $image_row; ?> ;

function addImage(image, thumb) {
  
  var image = (image === undefined) ? '' : image;
  var thumb = (thumb === undefined) ? '<?php echo $no_image; ?>' : thumb;

  html = '<div class="additional_image transition thumbnail" id="image-row'+image_row+'">';
  html += '<span class="remove" title="<?php echo $button_text_remove; ?>" onClick="$(\'#image-row'+image_row+'\').remove();"><i class="fa fa-times fa-fw"></i></span>';
      
  // standart opencart image field     
  html += '<a href="#" id="thumb-image'+image_row+'" data-toggle="image" class="img-thumbnail'+image_row+'">';
  html += '<img src="' + thumb + '" data-placeholder="<?php echo $no_image; ?>" />';
  html += '</a>';
  html += '<input type="hidden" name="album_data[gallery_images]['+image_row+'][image]" value="' + image + '" id="input-image'+image_row+'" />';
  html += '<input type="hidden" name="album_data[gallery_images]['+image_row+'][id]" value="'+image_row+'" /><br />';
  // end standart opencart image field 

  <?php foreach ($languages as $key => $language): ?>
  html += '<div class="input-group input-group-sm col-sm-offset-1 col-sm-10"><span class="input-group-addon" id="sizing-addon1"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span><input type="text" class="form-control" placeholder="<?php echo $text_placeholder_description ?>" name="album_data[gallery_images]['+image_row+'][description][<?php echo $language['language_id'] ?>]" value=""></div>';
    <?php endforeach ?>
  html += '</div>';

  $('#add_image_zone').before(html);

  image_row++;
}

$(document).ready(function(){
  Sortable.create(sort_container, {
    filter: ".sortable-ignore-elements",
    animation: 150,
    handle : 'img'
  });
  
  $('#image_loader_trigger').on('click', function(){
    var btn = $(this).button('loading');

    $.ajax({
      url: 'index.php?route=gallery/album/loadImages&token=<?php echo $token ?>',
      type: 'POST',
      dataType: 'json',
      data: {path: $('#image_loader_path').val()},
    })
    
    .success(function(data){
      console.log(data);
      if (data['success']) {
        $('#image_loader br').after('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> ' + data['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        for (var i = data['files'].length - 1; i >= 0; i--) {
          addImage(data['files'][i]['image'], data['files'][i]['thumb']);
        };
      };
      
      if (data['warning']) {
        $('#image_loader br').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + data['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }
    })

    .always(function(data) {
      btn.button('reset');
    });

  }); 
  $('#image_checker_trigger').on('click', function(){
    var btn = $(this).button('loading');

    $.ajax({
      url: 'index.php?route=gallery/album/checkImages&token=<?php echo $token ?>',
      type: 'POST',
      dataType: 'json',
      data: {path: $('#image_checker_path').val()},
    })
    
    .success(function(data){
      console.log(data);
      if (data['success']) {
        $('#image_checker br').after('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> ' + data['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      };
      
      if (data['warning']) {
        $('#image_checker br').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + data['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }
    })

    .always(function(data) {
      btn.button('reset');
    });

  });

  $('#album_type').on('change', function(){
    switch (parseInt($(this).val())) {
    case 0:
      $('.s_img0').show();
      $('.s_img1, .s_img2').hide();
      break;
    case 1:
      $('.s_img1').show();
      $('.s_img0, .s_img2').hide();
      break;
    case 2:
      $('.s_img2').show();
      $('.s_img0, .s_img1').hide();
      break;
    default:
      $('.s_img0').show();
      $('.s_img1, .s_img2').hide();
      break;
    }
  });
  $('#album_type').trigger('change');
});
</script>
      </fieldset>
    </div>

      <div class="tab-pane " id="tab_data">
      <ul class="nav nav-tabs">
        <?php foreach ($languages as $language): ?>
        <li class="<?php echo ($language == current($languages)) ? 'active' : ''; ?>"><a href="#tab-<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name'] ?></a></li>
        <?php endforeach ?>
      </ul>
      <div class="tab-content">
      <?php foreach ($languages as $language): ?>
      <div class="tab-pane <?php echo ($language == current($languages)) ? 'active' :''; ?>" id="tab-<?php echo $language['language_id']; ?>">

      <div class="form-group ">
        <label class="col-sm-2 control-label" for="album_data_album_title_<?php echo $language['language_id']; ?>"><?php echo $entry_title; ?></label>
        <div class="col-sm-10">
          <input type="text" name="album_data[album_title][<?php echo $language['language_id']; ?>]" value="<?php echo (isset($album_data['album_title'][$language['language_id']]) ? $album_data['album_title'][$language['language_id']] : '') ?>" placeholder="<?php echo $entry_title; ?>" id="album_data_album_title_<?php echo $language['language_id']; ?>" class="form-control" />
        </div>
      </div>

      <div class="form-group ">
        <label class="col-sm-2 control-label" for="album_data_album_h1_title_<?php echo $language['language_id']; ?>"><?php echo $entry_h1; ?></label>
        <div class="col-sm-10">
          <input type="text" name="album_data[album_h1_title][<?php echo $language['language_id']; ?>]" value="<?php echo (isset($album_data['album_h1_title'][$language['language_id']]) ? $album_data['album_h1_title'][$language['language_id']] : '') ?>" placeholder="<?php echo $entry_h1; ?>" id="album_data_album_h1_title_<?php echo $language['language_id']; ?>" class="form-control" />
        </div>
      </div>

      <div class="form-group ">
        <label class="col-sm-2 control-label" for="album_data[album_meta_keywords][<?php echo $language['language_id']; ?>]"><?php echo $entry_meta_keywords; ?></label>
        <div class="col-sm-10">
          <input type="text" name="album_data_album_meta_keywords_<?php echo $language['language_id']; ?>_" value="<?php echo (isset($album_data['album_meta_keywords'][$language['language_id']]) ? $album_data['album_meta_keywords'][$language['language_id']] : '') ?>" placeholder="<?php echo $entry_meta_keywords; ?>" id="album_data[album_meta_keywords][<?php echo $language['language_id']; ?>]" class="form-control" />
        </div>
      </div>

      <div class="form-group ">
        <label class="col-sm-2 control-label" for="album_data_album_meta_description_<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
        <div class="col-sm-10">
          <textarea name="album_data[album_meta_description][<?php echo $language['language_id']; ?>]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="album_data_album_meta_description_<?php echo $language['language_id']; ?>" class="form-control"><?php echo (isset($album_data['album_meta_description'][$language['language_id']]) ? $album_data['album_meta_description'][$language['language_id']] : '') ?></textarea>
        </div>
      </div>

      <div class="form-group ">
        <label class="col-sm-2 control-label" for="album_data_album_description_<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
        <div class="col-sm-10">
          <textarea name="album_data[album_description][<?php echo $language['language_id']; ?>]" rows="5" placeholder="<?php echo $entry_description; ?>" id="album_data_album_description_<?php echo $language['language_id']; ?>" class="form-control"><?php echo (isset($album_data['album_description'][$language['language_id']]) ? $album_data['album_description'][$language['language_id']] : '') ?></textarea>
        </div>
      </div>
            </div>
        <?php endforeach ?>
      
      </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<script type="text/javascript"><!--
  $(document).ready(function() {
<?php foreach ($languages as $language) { ?>
$('#album_data_album_description_<?php echo $language['language_id']; ?>').summernote({height: 300});
<?php } ?>
  });
//--></script> 
<?php echo $footer; ?>