<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <?php if ($album['album_data']['show_limiter']): ?>
  <div class="limiter">
    <div class="limit"><b><?php echo $text_limit; ?></b>
      <select onchange="location = this.value;">
        <?php foreach ($limits as $limits) { ?>
        <?php if ($limits['value'] == $limit) { ?>
        <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
  </div>
  <?php endif ?>
  <div class="content">
  <div class="gallery">
    <script>
      $(document).ready(function() {
        <?php if ($album['album_data']['use_lazyload']): ?>
          $('img.lazy').lazyload({
            effect:"fadeIn"
          });
        <?php endif ?>
        <?php 
          switch ($album['album_data']['js_lib_type']) {
            case 0: 
            echo "$('a.".$no_conflict."colorbox".$album['album_id']."').colorbox({rel:'".$no_conflict."group".$album['album_id']."'});";
            break;
            case 2:
             echo "$('a.".$no_conflict."fancybox".$album['album_id']."').fancybox({openEffect:'none',closeEffect:'none'});";
            break;
          }
        ?>
      });
    </script>
    <?php if (isset($album['album_description'])): ?>
      <div class="album_description"><?php echo $album['album_description'] ?></div>
    <?php endif ?>
      <?php foreach ($album['images'] as $img_key => $image): ?>
        <a class="transition gallery_photo <?php echo $no_conflict; ?><?php echo $album['js_lib_type_text'] ?><?php echo $album['album_id'] ?>"  rel="<?php echo $no_conflict; ?>group<?php echo $album['album_id'] ?>" href="<?php echo $image['popup'] ?>" <?php echo (($album['album_data']['js_lib_type'] == 1)? 'data-lightbox="'.$no_conflict.$album['album_id'].'" data-title="'.$image['title'].'" ' : ''); ?>title="<?php echo $image['title'] ?>">
          <img <?php if ($album['album_data']['use_lazyload']){echo 'class="lazy" src="'.$album['album_data']['lazyload_image'].'" data-original';}else{echo 'src';} ?>="<?php echo $image['thumb'] ?>" width="<?php echo $album['album_data']['thumb_width'] ?>" height="<?php echo $album['album_data']['thumb_height'] ?>" alt="<?php echo $image['title'] ?>" />
          <span><?php echo $image['title'] ?></span>
        </a>
      <?php endforeach ?>
    </div>
  </div>
<?php if ($show_pagination): ?>
  <div class="pagination"><?php echo $pagination; ?></div>
<?php endif ?>
<?php echo $content_bottom; ?></div>
<?php echo $footer; ?>