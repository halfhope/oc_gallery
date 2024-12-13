<?php echo $header ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator'] ?><a href="<?php echo $breadcrumb['href'] ?>"><?php echo $breadcrumb['text'] ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><a href="<?php echo $link_section_album_list ?>"><img alt="<?php echo $text_section_album_list ?>" title="<?php echo $text_section_album_list ?>" src="view/image/image.png" /></a>
        <a href="<?php echo $link_section_modules ?>"><img alt="<?php echo $text_section_modules ?>" title="<?php echo $text_section_modules ?>" src="view/image/module.png" /></a>
        <a href="<?php echo $link_section_settings ?>"><img alt="<?php echo $text_section_settings ?>" title="<?php echo $text_section_settings ?>" src="view/image/setting.png" /></a>
        <?php echo $heading_title ?></h1> 
      <div class="buttons">
        <a href="#" onClick="$('#form').submit();return false;" class="button"><?php echo $button_text_save ?></a>
        <a href="<?php echo $cancel ?>" class="button"><?php echo $button_text_cancel ?></a>
      </div>
    </div>
    <div class="content">
    <form action="<?php echo $action ?>" id="form" method="post" enctype="multipart/form-data">
    <h2><?php echo $text_general_settings ?></h2>
      <table class="form">
        <tr class="s_img1">
          <td><?php echo $text_feed ?></td>
          <td>
            <a href="<?php echo HTTP_CATALOG.'index.php?route=feed/gallery' ?>" target="_blank"><?php echo HTTP_CATALOG.'index.php?route=feed/gallery' ?></a>
          </td>
        </tr>
        <tr class="s_img1">
          <td><?php echo $entry_enable_full_cache ?><?php echo sprintf('<span class="help">%s</span>', $entry_enable_full_cache_help); ?></td>
          <td>
            <select name="config_gallery_modules_cache_enabled" id="config_gallery_modules_cache_enabled">
              <?php if ($config_gallery_modules_cache_enabled) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select>
          </td>
        </tr>
      </table>    
      
      <h2><?php echo $text_modules_settings ?></h2>
      <table class="form">
        <tr class="s_img2">
          <td><?php echo $entry_category_layout; ?></td>
          <td><select name="config_gallery_module_category_layout_id">
            <?php foreach ($layouts as $layout) { ?>
            <?php if ($layout['layout_id'] == $config_gallery_module_category_layout_id) { ?>
            <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
        </tr>
        <tr class="s_img2">
          <td><?php echo $entry_product_layout; ?></td>
          <td><select name="config_gallery_module_product_layout_id">
            <?php foreach ($layouts as $layout) { ?>
            <?php if ($layout['layout_id'] == $config_gallery_module_product_layout_id) { ?>
            <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
        </tr>
        <tr class="s_img2">
          <td><?php echo $entry_hook_layout; ?></td>
          <td><select name="config_gallery_module_hook_layout_id">
            <?php foreach ($layouts as $layout) { ?>
            <?php if ($layout['layout_id'] == $config_gallery_module_hook_layout_id) { ?>
            <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
        </tr>
      </table>

      <h2><?php echo $text_js_library_settings ?></h2>
      <table class="form">
        <tr class="s_img4">
          <td><?php echo $entry_gallery_include_colorbox; ?><?php echo sprintf('<span class="help">%s</span>', $entry_gallery_include_colorbox_help); ?></td>
          <td><?php if ($config_gallery_include_colorbox) { ?>
          <input type="radio" name="config_gallery_include_colorbox" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_include_colorbox" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="config_gallery_include_colorbox" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_include_colorbox" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
        </tr>
        <tr class="s_img4">
          <td><?php echo $entry_gallery_include_lightbox; ?><?php echo sprintf('<span class="help">%s</span>', $entry_gallery_include_lightbox_help); ?></td>
          <td><?php if ($config_gallery_include_lightbox) { ?>
          <input type="radio" name="config_gallery_include_lightbox" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_include_lightbox" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="config_gallery_include_lightbox" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_include_lightbox" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
        </tr>
        <tr class="s_img4">
          <td><?php echo $entry_gallery_include_fancybox; ?><?php echo sprintf('<span class="help">%s</span>', $entry_gallery_include_fancybox_help); ?></td>
          <td><?php if ($config_gallery_include_fancybox) { ?>
          <input type="radio" name="config_gallery_include_fancybox" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_include_fancybox" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="config_gallery_include_fancybox" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_include_fancybox" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
        </tr>
        <tr class="s_img4">
          <td><?php echo $entry_gallery_include_magnific_popup; ?><?php echo sprintf('<span class="help">%s</span>', $entry_gallery_include_magnific_popup_help); ?></td>
          <td><?php if ($config_gallery_include_magnific_popup) { ?>
          <input type="radio" name="config_gallery_include_magnific_popup" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_include_magnific_popup" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="config_gallery_include_magnific_popup" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_include_magnific_popup" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
        </tr>
        <tr class="s_img4">
          <td><?php echo $entry_gallery_include_jstabs; ?><?php echo sprintf('<span class="help">%s</span>', $entry_gallery_include_jstabs_help); ?></td>
          <td><?php if ($config_gallery_include_jstabs) { ?>
          <input type="radio" name="config_gallery_include_jstabs" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_include_jstabs" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="config_gallery_include_jstabs" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_include_jstabs" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
        </tr>
        <tr class="s_img4">
          <td><?php echo $entry_gallery_include_lazyload; ?><?php echo sprintf('<span class="help">%s</span>', $entry_gallery_include_lazyload_help); ?></td>
          <td><?php if ($config_gallery_include_lazyload) { ?>
          <input type="radio" name="config_gallery_include_lazyload" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_include_lazyload" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="config_gallery_include_lazyload" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_include_lazyload" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
        </tr>
        <tr class="s_img4">
          <td><?php echo $entry_gallery_include_bootstrap; ?><?php echo sprintf('<span class="help">%s</span>', $entry_gallery_include_bootstrap_help); ?></td>
          <td><?php if ($config_gallery_include_bootstrap) { ?>
          <input type="radio" name="config_gallery_include_bootstrap" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_include_bootstrap" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="config_gallery_include_bootstrap" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_include_bootstrap" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
        </tr>
      </table>

      <h2><?php echo $text_albums_settings ?></h2>
      <div class="htabs" id="store_tabs">
        <?php foreach ($stores as $store): ?>
          <a class="<?php echo ($store['store_id'] == 0) ? 'active' :''; ?>" href="#tab_store<?php echo $store['store_id']; ?>"><?php echo $store['name'] ?></a>
        <?php endforeach ?>
        </div>
        <?php foreach ($stores as $store): ?>

        <div id="tab_store<?php echo $store['store_id'] ?>">
          <table class="form">
            <tr  class="s_img3">
              <td><?php echo $entry_galleries_seo_name ?><?php echo sprintf('<span class="help">%s</span>', $entry_galleries_seo_name_help); ?></td>
              <td>
                <input type="text" name="config_gallery_galleries_seo_name[<?php echo $store['store_id']; ?>]" size="28" value="<?php echo $config_gallery_galleries_seo_name[$store['store_id']] ?>">
              </td>
            </tr>
            <tr class="s_img3">
              <td><?php echo $entry_galleries_include_seo_path; ?></td>
              <td><?php if ($config_gallery_galleries_include_seo_path[$store['store_id']]) { ?>
              <input type="radio" name="config_gallery_galleries_include_seo_path[<?php echo $store['store_id']; ?>]" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_gallery_galleries_include_seo_path[<?php echo $store['store_id']; ?>]" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="config_gallery_galleries_include_seo_path[<?php echo $store['store_id']; ?>]" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_gallery_galleries_include_seo_path[<?php echo $store['store_id']; ?>]" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
            </tr>
            <tr  class="s_img3">
              <td><span class="required">* &nbsp;</span><?php echo $entry_gallery_cover_image_dimension ?></td>
              <td>
                <input type="text" name="config_gallery_cover_image_width[<?php echo $store['store_id']; ?>]" id="thumb_width<?php echo $store['store_id']; ?>" size="3" value="<?php echo $config_gallery_cover_image_width[$store['store_id']] ?>">
                <input type="text" name="config_gallery_cover_image_height[<?php echo $store['store_id']; ?>]" id="thumb_height<?php echo $store['store_id']; ?>" size="3" value="<?php echo $config_gallery_cover_image_height[$store['store_id']] ?>">
              </td>
            </tr>
            <tr class="s_img3">
              <td><?php echo $entry_gallery_show_counter; ?></td>
              <td><?php if ($config_gallery_show_counter[$store['store_id']]) { ?>
              <input type="radio" name="config_gallery_show_counter[<?php echo $store['store_id']; ?>]" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_gallery_show_counter[<?php echo $store['store_id']; ?>]" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="config_gallery_show_counter[<?php echo $store['store_id']; ?>]" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_gallery_show_counter[<?php echo $store['store_id']; ?>]" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
            </tr>
            <tr class="s_img3">
              <td><?php echo $entry_gallery_show_description; ?></td>
              <td><?php if ($config_gallery_show_description[$store['store_id']]) { ?>
              <input type="radio" name="config_gallery_show_description[<?php echo $store['store_id']; ?>]" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_gallery_show_description[<?php echo $store['store_id']; ?>]" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="config_gallery_show_description[<?php echo $store['store_id']; ?>]" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_gallery_show_description[<?php echo $store['store_id']; ?>]" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
            </tr>
          </table>    
          <div class="htabs" id="lang_tabs<?php echo $store['store_id']; ?>">
          <?php foreach ($languages as $key => $language): ?>
            <a href="#tab_lang<?php echo $language['language_id'] ?>-<?php echo $store['store_id']; ?>" class="<?php echo ($language == current($languages)) ? 'active' :''; ?>"><?php echo $language['name'] ?>&nbsp;<img src="view/image/flags/<?php echo $language['image'] ?>" alt=""></a>
          <?php endforeach ?>
          </div>
          <?php foreach ($languages as $language): ?>
          <div id="tab_lang<?php echo $language['language_id'] ?>-<?php echo $store['store_id']; ?>">
            <table class="form">
              <tr class="s_img3">
                <td><?php echo $entry_title ?></td>
                <td><input type="text" style="width:500px;" name="config_gallery_galleries_title[<?php echo $store['store_id']; ?>][<?php echo $language['language_id'] ?>]" id="album_title" value="<?php echo (isset($config_gallery_galleries_title[$store['store_id']][$language['language_id']]) ? $config_gallery_galleries_title[$store['store_id']][$language['language_id']] : '') ?>"></td>
              </tr>
              <tr class="s_img3">
                <td><?php echo $entry_title ?></td>
                <td><input type="text" style="width:500px;" name="config_gallery_galleries_breadcrumb[<?php echo $store['store_id']; ?>][<?php echo $language['language_id'] ?>]" id="album_title" value="<?php echo (isset($config_gallery_galleries_breadcrumb[$store['store_id']][$language['language_id']]) ? $config_gallery_galleries_breadcrumb[$store['store_id']][$language['language_id']] : '') ?>"></td>
              </tr>
              <tr class="s_img3">
                <td><?php echo $entry_h1 ?></td>
                <td><input type="text" style="width:500px;" name="config_gallery_galleries_h1_title[<?php echo $store['store_id']; ?>][<?php echo $language['language_id'] ?>]" id="album_h1_title" value="<?php echo (isset($config_gallery_galleries_h1_title[$store['store_id']][$language['language_id']]) ? $config_gallery_galleries_h1_title[$store['store_id']][$language['language_id']] : '') ?>"></td>
              </tr>
              <tr class="s_img3">
                <td><?php echo $entry_meta_keywords ?></td>
                <td><input type="text" style="width:500px;" name="config_gallery_galleries_meta_keywords[<?php echo $store['store_id']; ?>][<?php echo $language['language_id'] ?>]" id="album_meta_keywords" value="<?php echo (isset($config_gallery_galleries_meta_keywords[$store['store_id']][$language['language_id']]) ? $config_gallery_galleries_meta_keywords[$store['store_id']][$language['language_id']] : '') ?>"></td>
              </tr>
              <tr class="s_img3">
                <td><?php echo $entry_meta_description ?></td>
                <td><textarea style="width:500px;min-height:50px;" name="config_gallery_galleries_meta_description[<?php echo $store['store_id']; ?>][<?php echo $language['language_id'] ?>]"><?php echo (isset($config_gallery_galleries_meta_description[$store['store_id']][$language['language_id']]) ? $config_gallery_galleries_meta_description[$store['store_id']][$language['language_id']] : '') ?></textarea></td>
              </tr>
              <tr class="s_img3">
                <td><?php echo $entry_description ?></td>
                <td><textarea name="config_gallery_galleries_description[<?php echo $store['store_id']; ?>][<?php echo $language['language_id'] ?>]" id="galleries_description-<?php echo $store['store_id']; ?>-<?php echo $language['language_id'] ?>"><?php echo (isset($config_gallery_galleries_description[$store['store_id']][$language['language_id']]) ? $config_gallery_galleries_description[$store['store_id']][$language['language_id']] : '') ?></textarea></td>
              </tr>
            </table>
          </div>
          <?php endforeach ?>
          <h2><?php echo $text_bootstrap ?></h2>
              <table class="form">
                <tr>
                  <td><?php echo $entry_number_of_columns_xs ?><?php echo sprintf('<span class="help">%s</span>', $entry_number_of_columns_xs_help); ?></td>
                  <td>
                    <?php foreach ($entry_number_of_columns_values as $key => $value): ?>
                      <label class="radio-inline">
                      <?php if ($config_gallery_number_of_columns_xs[$store['store_id']] == $key): ?>
                        <input type="radio" name="config_gallery_number_of_columns_xs[<?php echo $store['store_id'] ?>]" value="<?php echo $key ?>" checked="checked" />
                          <?php echo $value; ?>
                      <?php else: ?>
                        <input type="radio" name="config_gallery_number_of_columns_xs[<?php echo $store['store_id'] ?>]" value="<?php echo $key ?>" />
                          <?php echo $value; ?>
                      <?php endif ?>
                        </label>
                    <?php endforeach ?>
                  </td>
                </tr>
                <tr>
                  <td><?php echo $entry_number_of_columns_sm ?><?php echo sprintf('<span class="help">%s</span>', $entry_number_of_columns_sm_help); ?></td>
                  <td>
                    <?php foreach ($entry_number_of_columns_values as $key => $value): ?>
                      <label class="radio-inline">
                      <?php if ($config_gallery_number_of_columns_sm[$store['store_id']] == $key): ?>
                        <input type="radio" name="config_gallery_number_of_columns_sm[<?php echo $store['store_id'] ?>]" value="<?php echo $key ?>" checked="checked" />
                          <?php echo $value; ?>
                      <?php else: ?>
                        <input type="radio" name="config_gallery_number_of_columns_sm[<?php echo $store['store_id'] ?>]" value="<?php echo $key ?>" />
                          <?php echo $value; ?>
                      <?php endif ?>
                        </label>
                    <?php endforeach ?>
                  </td>
                </tr>
                <tr>
                  <td><?php echo $entry_number_of_columns_md ?><?php echo sprintf('<span class="help">%s</span>', $entry_number_of_columns_md_help); ?></td>
                  <td>
                    <?php foreach ($entry_number_of_columns_values as $key => $value): ?>
                      <label class="radio-inline">
                      <?php if ($config_gallery_number_of_columns_md[$store['store_id']] == $key): ?>
                        <input type="radio" name="config_gallery_number_of_columns_md[<?php echo $store['store_id'] ?>]" value="<?php echo $key ?>" checked="checked" />
                          <?php echo $value; ?>
                      <?php else: ?>
                        <input type="radio" name="config_gallery_number_of_columns_md[<?php echo $store['store_id'] ?>]" value="<?php echo $key ?>" />
                          <?php echo $value; ?>
                      <?php endif ?>
                        </label>
                    <?php endforeach ?>
                  </td>
                </tr>
                <tr>
                  <td><?php echo $entry_number_of_columns_lg ?><?php echo sprintf('<span class="help">%s</span>', $entry_number_of_columns_lg_help); ?></td>
                  <td>
                    <?php foreach ($entry_number_of_columns_values as $key => $value): ?>
                      <label class="radio-inline">
                      <?php if ($config_gallery_number_of_columns_lg[$store['store_id']] == $key): ?>
                        <input type="radio" name="config_gallery_number_of_columns_lg[<?php echo $store['store_id'] ?>]" value="<?php echo $key ?>" checked="checked" />
                          <?php echo $value; ?>
                      <?php else: ?>
                        <input type="radio" name="config_gallery_number_of_columns_lg[<?php echo $store['store_id'] ?>]" value="<?php echo $key ?>" />
                          <?php echo $value; ?>
                      <?php endif ?>
                        </label>
                    <?php endforeach ?>
                  </td>
                </tr>

              </table>
        </div>
        <?php endforeach ?>
      </form>
    </div>
  </div>
  <script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
  <script>
  $(document).ready(function() {
    setTimeout('$(".warning, .success").hide("fast")', 3000);
    $('#store_tabs a').tabs();

    <?php foreach ($stores as $store): ?>
    $('#lang_tabs<?php echo $store['store_id']; ?> a').tabs();
    <?php endforeach ?>
  });
  <?php foreach ($stores as $key => $store): ?>
    <?php foreach ($languages as $key => $language): ?>
      CKEDITOR.replace('galleries_description-<?php echo $store['store_id']; ?>-<?php echo $language['language_id'] ?>', {
        filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
        filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
        filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
        filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
        filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
        filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
      });
    <?php endforeach ?>
  <?php endforeach ?>
  </script>
<?php echo $footer ?>


