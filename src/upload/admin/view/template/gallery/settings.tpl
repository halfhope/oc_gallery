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
        <li role="presentation"><a href="<?php echo $link_section_album_list ?>"><i class="fa fa-list fa-fw"></i> <?php echo $text_section_album_list ?></a></li>
        <li role="presentation"><a href="<?php echo $link_section_modules ?>"><i class="fa fa-puzzle-piece fa-fw"></i> <?php echo $text_section_modules ?></a></li>
        <li role="presentation" class="active"><a href="<?php echo $link_section_settings ?>"><i class="fa fa-cog fa-fw"></i> <?php echo $text_section_settings ?></a></li>
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
        <h3 class="panel-title"><i class="fa fa-cog"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal">
          <div class="tab-content">
            <fieldset><legend><?php echo $text_general_settings ?></legend>
      <div class="form-group ">
        <label class="col-sm-2 control-label"><?php echo $text_feed; ?></label>
        <div class="col-sm-10">
          <a href="<?php echo HTTP_CATALOG.'index.php?route=feed/gallery' ?>" target="_blank"><?php echo HTTP_CATALOG.'index.php?route=feed/gallery' ?></a>
        </div>
      </div>

      <div class="form-group ">
        <label class="col-sm-2 control-label" for="config_gallery_modules_cache_enabled"><span data-toggle="tooltip" title="<?php echo $entry_enable_full_cache_help; ?>"><?php echo $entry_enable_full_cache; ?></span></label>
        <div class="col-sm-10">
          <select name="config_gallery_modules_cache_enabled" id="config_gallery_modules_cache_enabled" class="form-control">
            <?php foreach ($arr_enabled as $key => $value) { ?>
            <?php if ($key == $config_gallery_modules_cache_enabled) { ?>
            <option value="<?php echo $key; ?>" selected="selected"><?php echo $value; ?></option>
            <?php } else { ?>
            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>
      </fieldset>
      
      <fieldset><legend><?php echo $text_js_library_settings ?></legend>

      <div class="form-group ">
        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_gallery_include_colorbox_help; ?>"><?php echo $entry_gallery_include_colorbox; ?></span></label>
        <div class="col-sm-10">
          <label class="radio-inline">
            <?php if ($config_gallery_include_colorbox) { ?>
            <input type="radio" name="config_gallery_include_colorbox" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <?php } else { ?>
            <input type="radio" name="config_gallery_include_colorbox" value="1" />
            <?php echo $text_yes; ?>
            <?php } ?>
          </label>
          <label class="radio-inline">
            <?php if (!$config_gallery_include_colorbox) { ?>
            <input type="radio" name="config_gallery_include_colorbox" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="config_gallery_include_colorbox" value="0" />
            <?php echo $text_no; ?>
            <?php } ?>
          </label>
        </div>
      </div>

      <div class="form-group ">
        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_gallery_include_lightbox_help; ?>"><?php echo $entry_gallery_include_lightbox; ?></span></label>
        <div class="col-sm-10">
          <label class="radio-inline">
            <?php if ($config_gallery_include_lightbox) { ?>
            <input type="radio" name="config_gallery_include_lightbox" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <?php } else { ?>
            <input type="radio" name="config_gallery_include_lightbox" value="1" />
            <?php echo $text_yes; ?>
            <?php } ?>
          </label>
          <label class="radio-inline">
            <?php if (!$config_gallery_include_lightbox) { ?>
            <input type="radio" name="config_gallery_include_lightbox" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="config_gallery_include_lightbox" value="0" />
            <?php echo $text_no; ?>
            <?php } ?>
          </label>
        </div>
      </div>

      <div class="form-group ">
        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_gallery_include_fancybox_help; ?>"><?php echo $entry_gallery_include_fancybox; ?></span></label>
        <div class="col-sm-10">
          <label class="radio-inline">
            <?php if ($config_gallery_include_fancybox) { ?>
            <input type="radio" name="config_gallery_include_fancybox" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <?php } else { ?>
            <input type="radio" name="config_gallery_include_fancybox" value="1" />
            <?php echo $text_yes; ?>
            <?php } ?>
          </label>
          <label class="radio-inline">
            <?php if (!$config_gallery_include_fancybox) { ?>
            <input type="radio" name="config_gallery_include_fancybox" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="config_gallery_include_fancybox" value="0" />
            <?php echo $text_no; ?>
            <?php } ?>
          </label>
        </div>
      </div>
      
      <div class="form-group ">
        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_gallery_include_magnific_popup_help; ?>"><?php echo $entry_gallery_include_magnific_popup; ?></span></label>
        <div class="col-sm-10">
          <label class="radio-inline">
            <?php if ($config_gallery_include_magnific_popup) { ?>
            <input type="radio" name="config_gallery_include_magnific_popup" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <?php } else { ?>
            <input type="radio" name="config_gallery_include_magnific_popup" value="1" />
            <?php echo $text_yes; ?>
            <?php } ?>
          </label>
          <label class="radio-inline">
            <?php if (!$config_gallery_include_magnific_popup) { ?>
            <input type="radio" name="config_gallery_include_magnific_popup" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="config_gallery_include_magnific_popup" value="0" />
            <?php echo $text_no; ?>
            <?php } ?>
          </label>
        </div>
      </div>
      
      <div class="form-group hidden">
        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_gallery_include_jstabs_help; ?>"><?php echo $entry_gallery_include_jstabs; ?></span></label>
        <div class="col-sm-10">
          <label class="radio-inline">
            <?php if ($config_gallery_include_jstabs) { ?>
            <input type="radio" name="config_gallery_include_jstabs" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <?php } else { ?>
            <input type="radio" name="config_gallery_include_jstabs" value="1" />
            <?php echo $text_yes; ?>
            <?php } ?>
          </label>
          <label class="radio-inline">
            <?php if (!$config_gallery_include_jstabs) { ?>
            <input type="radio" name="config_gallery_include_jstabs" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="config_gallery_include_jstabs" value="0" />
            <?php echo $text_no; ?>
            <?php } ?>
          </label>
        </div>
      </div>
      
      <div class="form-group ">
        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_gallery_include_lazyload_help; ?>"><?php echo $entry_gallery_include_lazyload; ?></span></label>
        <div class="col-sm-10">
          <label class="radio-inline">
            <?php if ($config_gallery_include_lazyload) { ?>
            <input type="radio" name="config_gallery_include_lazyload" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <?php } else { ?>
            <input type="radio" name="config_gallery_include_lazyload" value="1" />
            <?php echo $text_yes; ?>
            <?php } ?>
          </label>
          <label class="radio-inline">
            <?php if (!$config_gallery_include_lazyload) { ?>
            <input type="radio" name="config_gallery_include_lazyload" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="config_gallery_include_lazyload" value="0" />
            <?php echo $text_no; ?>
            <?php } ?>
          </label>
        </div>
      </div>

      </fieldset>
      <fieldset><legend><?php echo $text_albums_settings ?></legend>

      <ul class="nav nav-tabs">
        <?php foreach ($stores as $store): ?>
        <li role="presentation" class="<?php echo ($store['store_id'] == 0) ? 'active' :''; ?>"><a href="#tab-store-<?php echo $store['store_id']; ?>" data-toggle="tab"><?php echo $store['name'] ?></a></li>
        <?php endforeach ?>
      </ul>
      <div class="tab-content">
      <?php foreach ($stores as $store): ?>
      <div class="tab-pane <?php echo ($store['store_id'] == 0) ? 'active' :''; ?>" id="tab-store-<?php echo $store['store_id']; ?>">

      <div class="form-group ">
        <label class="col-sm-2 control-label" for="config_gallery_galleries_seo_name<?php echo $store['store_id']; ?>"><span data-toggle="tooltip" title="<?php echo $entry_galleries_seo_name_help; ?>"><?php echo $entry_galleries_seo_name; ?></span></label>
        <div class="col-sm-10">
          <input type="text" name="config_gallery_galleries_seo_name[<?php echo $store['store_id']; ?>]" value="<?php echo $config_gallery_galleries_seo_name[$store['store_id']]; ?>" placeholder="<?php echo $entry_galleries_seo_name; ?>" id="config_gallery_galleries_seo_name<?php echo $store['store_id']; ?>" class="form-control" />
        </div>
      </div>

      <div class="form-group ">
        <label class="col-sm-2 control-label"><?php echo $entry_galleries_include_seo_path; ?></label>
        <div class="col-sm-10">
          <label class="radio-inline">
            <?php if ($config_gallery_galleries_include_seo_path[$store['store_id']]) { ?>
            <input type="radio" name="config_gallery_galleries_include_seo_path[<?php echo $store['store_id']; ?>]" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <?php } else { ?>
            <input type="radio" name="config_gallery_galleries_include_seo_path[<?php echo $store['store_id']; ?>]" value="1" />
            <?php echo $text_yes; ?>
            <?php } ?>
          </label>
          <label class="radio-inline">
            <?php if (!$config_gallery_galleries_include_seo_path[$store['store_id']]) { ?>
            <input type="radio" name="config_gallery_galleries_include_seo_path[<?php echo $store['store_id']; ?>]" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="config_gallery_galleries_include_seo_path[<?php echo $store['store_id']; ?>]" value="0" />
            <?php echo $text_no; ?>
            <?php } ?>
          </label>
        </div>
      </div>

      <div class="form-group ">
        <label class="col-sm-2 control-label" for="config_gallery_cover_image_width<?php echo $store['store_id']; ?>"><?php echo $entry_gallery_cover_image_dimension; ?></label>
        <div class="col-sm-10">
          <div class="row">
            <div class="col-sm-6">
              <input type="text" name="config_gallery_cover_image_width[<?php echo $store['store_id']; ?>]" value="<?php echo $config_gallery_cover_image_width[$store['store_id']]; ?>" placeholder="<?php echo $text_width; ?>" id="config_gallery_cover_image_width<?php echo $store['store_id']; ?>" class="form-control" />
            </div>
            <div class="col-sm-6">
              <input type="text" name="config_gallery_cover_image_height[<?php echo $store['store_id']; ?>]" value="<?php echo $config_gallery_cover_image_height[$store['store_id']]; ?>" placeholder="<?php echo $text_height; ?>" id="config_gallery_cover_image_height<?php echo $store['store_id']; ?>" class="form-control" />
            </div>
          </div>
        </div>
      </div>

      <div class="form-group ">
        <label class="col-sm-2 control-label"><?php echo $entry_gallery_show_counter; ?></label>
        <div class="col-sm-10">
          <label class="radio-inline">
            <?php if ($config_gallery_show_counter[$store['store_id']]) { ?>
            <input type="radio" name="config_gallery_show_counter[<?php echo $store['store_id']; ?>]" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <?php } else { ?>
            <input type="radio" name="config_gallery_show_counter[<?php echo $store['store_id']; ?>]" value="1" />
            <?php echo $text_yes; ?>
            <?php } ?>
          </label>
          <label class="radio-inline">
            <?php if (!$config_gallery_show_counter[$store['store_id']]) { ?>
            <input type="radio" name="config_gallery_show_counter[<?php echo $store['store_id']; ?>]" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="config_gallery_show_counter[<?php echo $store['store_id']; ?>]" value="0" />
            <?php echo $text_no; ?>
            <?php } ?>
          </label>
        </div>
      </div>

      <div class="form-group ">
        <label class="col-sm-2 control-label"><?php echo $entry_gallery_show_description; ?></label>
        <div class="col-sm-10">
          <label class="radio-inline">
            <?php if ($config_gallery_show_description[$store['store_id']]) { ?>
            <input type="radio" name="config_gallery_show_description[<?php echo $store['store_id']; ?>]" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <?php } else { ?>
            <input type="radio" name="config_gallery_show_description[<?php echo $store['store_id']; ?>]" value="1" />
            <?php echo $text_yes; ?>
            <?php } ?>
          </label>
          <label class="radio-inline">
            <?php if (!$config_gallery_show_description[$store['store_id']]) { ?>
            <input type="radio" name="config_gallery_show_description[<?php echo $store['store_id']; ?>]" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="config_gallery_show_description[<?php echo $store['store_id']; ?>]" value="0" />
            <?php echo $text_no; ?>
            <?php } ?>
          </label>
        </div>
      </div>
      
      <ul class="nav nav-tabs">
        <?php foreach ($languages as $language): ?>
        <li class="<?php echo ($language == current($languages)) ? 'active' :''; ?>"><a href="#tab-store-<?php echo $store['store_id']; ?>-<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name'] ?></a></li>
        <?php endforeach ?>
      </ul>
      <div class="tab-content">
      <?php foreach ($languages as $language): ?>
      <div class="tab-pane <?php echo ($language == current($languages)) ? 'active' :''; ?>" id="tab-store-<?php echo $store['store_id']; ?>-<?php echo $language['language_id']; ?>">
      <div class="form-group ">
        <label class="col-sm-2 control-label" for="config_gallery_galleries_title[<?php echo $store['store_id']; ?>][<?php echo $language['language_id']; ?>]"><?php echo $entry_title; ?></label>
        <div class="col-sm-10">
          <input type="text" name="config_gallery_galleries_title[<?php echo $store['store_id']; ?>][<?php echo $language['language_id']; ?>]" value="<?php echo (isset($config_gallery_galleries_title[$store['store_id']][$language['language_id']]) ? $config_gallery_galleries_title[$store['store_id']][$language['language_id']] : '') ?>" placeholder="<?php echo $entry_title; ?>" id="config_gallery_galleries_title[<?php echo $store['store_id']; ?>][<?php echo $language['language_id']; ?>]" class="form-control" />
        </div>
      </div>
      <div class="form-group ">
        <label class="col-sm-2 control-label" for="config_gallery_galleries_breadcrumb_<?php echo $store['store_id']; ?>_<?php echo $language['language_id']; ?>"><?php echo $entry_breadcrumb; ?></label>
        <div class="col-sm-10">
          <input type="text" name="config_gallery_galleries_breadcrumb[<?php echo $store['store_id']; ?>][<?php echo $language['language_id']; ?>]" value="<?php echo (isset($config_gallery_galleries_breadcrumb[$store['store_id']][$language['language_id']]) ? $config_gallery_galleries_breadcrumb[$store['store_id']][$language['language_id']] : '') ?>" placeholder="<?php echo $entry_breadcrumb; ?>" id="config_gallery_galleries_breadcrumb_<?php echo $store['store_id']; ?>_<?php echo $language['language_id']; ?>" class="form-control" />
        </div>
      </div>

      <div class="form-group ">
        <label class="col-sm-2 control-label" for="config_gallery_galleries_h1_title_<?php echo $store['store_id']; ?>_<?php echo $language['language_id']; ?>"><?php echo $entry_h1; ?></label>
        <div class="col-sm-10">
          <input type="text" name="config_gallery_galleries_h1_title[<?php echo $store['store_id']; ?>][<?php echo $language['language_id']; ?>]" value="<?php echo (isset($config_gallery_galleries_h1_title[$store['store_id']][$language['language_id']]) ? $config_gallery_galleries_h1_title[$store['store_id']][$language['language_id']] : '') ?>" placeholder="<?php echo $entry_h1; ?>" id="config_gallery_galleries_h1_title_<?php echo $store['store_id']; ?>_<?php echo $language['language_id']; ?>" class="form-control" />
        </div>
      </div>

      <div class="form-group ">
        <label class="col-sm-2 control-label" for="config_gallery_galleries_meta_keywords_<?php echo $store['store_id']; ?>_<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keywords; ?></label>
        <div class="col-sm-10">
          <input type="text" name="config_gallery_galleries_meta_keywords[<?php echo $store['store_id']; ?>][<?php echo $language['language_id']; ?>]" value="<?php echo (isset($config_gallery_galleries_meta_keywords[$store['store_id']][$language['language_id']]) ? $config_gallery_galleries_meta_keywords[$store['store_id']][$language['language_id']] : '') ?>" placeholder="<?php echo $entry_meta_keywords; ?>" id="config_gallery_galleries_meta_keywords_<?php echo $store['store_id']; ?>_<?php echo $language['language_id']; ?>" class="form-control" />
        </div>
      </div>

      <div class="form-group ">
        <label class="col-sm-2 control-label" for="config_gallery_galleries_meta_description_<?php echo $store['store_id']; ?>_<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
        <div class="col-sm-10">
          <textarea name="config_gallery_galleries_meta_description[<?php echo $store['store_id']; ?>][<?php echo $language['language_id']; ?>]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="config_gallery_galleries_meta_description_<?php echo $store['store_id']; ?>_<?php echo $language['language_id']; ?>" class="form-control"><?php echo (isset($config_gallery_galleries_meta_description[$store['store_id']][$language['language_id']]) ? $config_gallery_galleries_meta_description[$store['store_id']][$language['language_id']] : '') ?></textarea>
        </div>
      </div>

      <div class="form-group ">
        <label class="col-sm-2 control-label" for="config_gallery_galleries_description_<?php echo $store['store_id']; ?>_<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
        <div class="col-sm-10">
          <textarea name="config_gallery_galleries_description[<?php echo $store['store_id']; ?>][<?php echo $language['language_id']; ?>]" id="config_gallery_galleries_description_<?php echo $store['store_id']; ?>_<?php echo $language['language_id']; ?>" rows="5" placeholder="<?php echo $entry_description; ?>" id="config_gallery_galleries_description[<?php echo $language['language_id']; ?>]" class="form-control"><?php echo (isset($config_gallery_galleries_description[$store['store_id']][$language['language_id']]) ? $config_gallery_galleries_description[$store['store_id']][$language['language_id']] : '') ?></textarea>
        </div>
      </div>
      
      </div>
      <?php endforeach; ?>
      </div>
      <fieldset><legend><?php echo $text_bootstrap ?></legend>
      <div class="form-group ">
        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_xs_help; ?>"><?php echo $entry_number_of_columns_xs; ?></span></label>
        <div class="col-sm-10">
          
          <?php foreach ($entry_number_of_columns_values as $key => $value): ?>
            <label class="radio-inline">
            <?php if ($config_gallery_number_of_columns_xs[$store['store_id']] == $key): ?>
              <input type="radio" name="config_gallery_number_of_columns_xs[<?php echo $store['store_id']; ?>]" value="<?php echo $key ?>" checked="checked" />
              <?php if ($key == 0): ?>
                <span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_auto_help; ?>"><?php echo $value; ?></span>
              <?php else: ?>
                <?php echo $value; ?>
              <?php endif ?>
            <?php else: ?>
              <input type="radio" name="config_gallery_number_of_columns_xs[<?php echo $store['store_id']; ?>]" value="<?php echo $key ?>" />
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
            <?php if ($config_gallery_number_of_columns_sm[$store['store_id']] == $key): ?>
              <input type="radio" name="config_gallery_number_of_columns_sm[<?php echo $store['store_id']; ?>]" value="<?php echo $key ?>" checked="checked" />
              <?php if ($key == 0): ?>
                <span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_auto_help; ?>"><?php echo $value; ?></span>
              <?php else: ?>
                <?php echo $value; ?>
              <?php endif ?>
            <?php else: ?>
              <input type="radio" name="config_gallery_number_of_columns_sm[<?php echo $store['store_id']; ?>]" value="<?php echo $key ?>" />
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
            <?php if ($config_gallery_number_of_columns_md[$store['store_id']] == $key): ?>
              <input type="radio" name="config_gallery_number_of_columns_md[<?php echo $store['store_id']; ?>]" value="<?php echo $key ?>" checked="checked" />
              <?php if ($key == 0): ?>
                <span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_auto_help; ?>"><?php echo $value; ?></span>
              <?php else: ?>
                <?php echo $value; ?>
              <?php endif ?>
            <?php else: ?>
              <input type="radio" name="config_gallery_number_of_columns_md[<?php echo $store['store_id']; ?>]" value="<?php echo $key ?>" />
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
            <?php if ($config_gallery_number_of_columns_lg[$store['store_id']] == $key): ?>
              <input type="radio" name="config_gallery_number_of_columns_lg[<?php echo $store['store_id']; ?>]" value="<?php echo $key ?>" checked="checked" />
              <?php if ($key == 0): ?>
                <span data-toggle="tooltip" title="<?php echo $entry_number_of_columns_auto_help; ?>"><?php echo $value; ?></span>
              <?php else: ?>
                <?php echo $value; ?>
              <?php endif ?>
            <?php else: ?>
              <input type="radio" name="config_gallery_number_of_columns_lg[<?php echo $store['store_id']; ?>]" value="<?php echo $key ?>" />
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
      </div>
      
    <?php endforeach ?>
      </div>
      
            </fieldset>
      
          </div>
        </form>
      </div>
    </div>
  </div>
<script type="text/javascript"><!--
  $(document).ready(function() {
<?php foreach ($languages as $language) { ?>
<?php foreach ($stores as $store) { ?>
$('#config_gallery_galleries_description_<?php echo $store['store_id']; ?>_<?php echo $language['language_id']; ?>').summernote({height: 300});
<?php } ?>
<?php } ?>
  });
//--></script> 
<?php echo $footer; ?>