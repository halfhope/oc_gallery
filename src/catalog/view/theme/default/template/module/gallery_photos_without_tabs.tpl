<div class="box">
  <?php if ($show_header): ?>
    <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-gallery">
  <?php endif ?>
  <div class="gallery">
    <script>
      $(document).ready(function() {
        $('#gallery_tabs<?php echo $no_conflict; ?> a').tabs();
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
          <img src="<?php echo $image['thumb'] ?>" width="<?php echo $gallery_thumb_image_width ?>" height="<?php echo $gallery_thumb_image_height ?>" alt="<?php echo $image['title'] ?>" />
          <span><?php echo $image['title'] ?></span>
        </a>
      <?php endforeach ?>
    <?php if ($show_album_link): ?>
      <div style="margin:0px;margin-top:10px;text-align:right;width:100%;">
        <a href="<?php echo $album['album_link'] ?>" class="button"><span><?php echo $album_link_text ?></span></a>
      </div>
    <?php endif ?>
    </div>
  <?php if ($show_header): ?>
    </div>
  </div>
  <?php endif ?>
</div>