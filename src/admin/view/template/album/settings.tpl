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
    <form action="<?php echo $save ?>" id="form" method="post" enctype="multipart/form-data">
      <table class="form">
        <tr>
          <td><?php echo $entry_enable_full_cache ?></td>
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
        <tr>
          <td><span class="required">* &nbsp;</span><?php echo $entry_gallery_cover_image_dimension ?></td>
          <td>
            <input type="text" name="config_gallery_cover_image_width" id="thumb_width" size="3" value="<?php echo $config_gallery_cover_image_width ?>">
            <input type="text" name="config_gallery_cover_image_height" id="thumb_height" size="3" value="<?php echo $config_gallery_cover_image_height ?>">
          </td>
        </tr>
        <tr>
          <td><?php echo $entry_gallery_show_counter; ?></td>
          <td><?php if ($config_gallery_show_counter) { ?>
          <input type="radio" name="config_gallery_show_counter" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_show_counter" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="config_gallery_show_counter" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_show_counter" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_gallery_include_colorbox; ?></td>
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
        <tr>
          <td><?php echo $entry_gallery_include_lightbox; ?></td>
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
        <tr>
          <td><?php echo $entry_gallery_include_fancybox; ?></td>
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
        <tr>
          <td><?php echo $entry_gallery_include_jstabs; ?></td>
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
      </table>
    </form>
    </div>
  </div>
  <script>
  $(document).ready(function() {
    setTimeout('$(".warning, .success").hide("fast")', 3000);
  });
  </script>
<?php echo $footer ?>


