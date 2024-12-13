<div class="box">
  <?php if ($show_header): ?>
    <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-gallery">
  <?php endif ?>
  <div class="gallery">
      <div id="gallery_tabs<?php echo $no_conflict; ?>" class="htabs">
      <?php foreach ($albums as $key => $album): ?>
        <a href="#<?php echo $no_conflict; ?>tab_album<?php echo $album['album_id'] ?>"><?php echo $album['album_name'] ?></a>
      <?php endforeach ?>
      </div>
    <script>
      $(document).ready(function() {
        $('#gallery_tabs<?php echo $no_conflict; ?> a').tabs();
        <?php 
        foreach ($albums as $album) {
          switch ($album['album_data']['js_lib_type']) {
            case 0: 
            echo "$('a.".$no_conflict.$album['album_data']['js_lib_type'].$album['album_id']."').colorbox({rel:'".$no_conflict."group".$album['album_id']."'});";
            break;
            case 2:
             echo "$('a.".$no_conflict.$album['album_data']['js_lib_type'].$album['album_id']."').fancybox({openEffect:'none',closeEffect:'none'});";
            break;
            case 3:
              echo "$('#".$no_conflict."tab_album".$album['album_id']."').magnificPopup({type:'image',delegate: 'a',gallery: {enabled:true}});".PHP_EOL;
            break;
          }
        } 
        ?>
      });
    </script>
    <?php foreach ($albums as $key => $album): ?>
      <div id="<?php echo $no_conflict; ?>tab_album<?php echo $album['album_id'] ?>" class="tab-content">
    <?php if (isset($album['album_description'])): ?>
      <div class="album_description"><?php echo $album['album_description'] ?></div>
    <?php endif ?>
    <?php foreach ($album['images'] as $img_key => $image): ?>
      <div class="<?php echo $bootstrap_grid; ?> gallery_photo">
        <a class="transition thumbnail <?php echo $no_conflict; ?><?php echo $album['album_data']['js_lib_type'] ?><?php echo $album['album_id'] ?>"  rel="<?php echo $no_conflict; ?>group<?php echo $album['album_id'] ?>" href="<?php echo $image['popup'] ?>" <?php echo (($album['album_data']['js_lib_type'] == 1)? 'data-lightbox="'.$no_conflict.$album['album_id'].'" data-title="'.$image['title'].'" ' : ''); ?>title="<?php echo $image['title'] ?>">
          <img src="<?php echo $image['thumb'] ?>" width="<?php echo $gallery_thumb_image_width ?>" height="<?php echo $gallery_thumb_image_height ?>" alt="<?php echo $image['title'] ?>" /><span><?php echo $image['title'] ?></span>
        </a>
      </div>
    <?php endforeach ?>
    <?php if ($show_album_link): ?>
      <div style="margin:0px;margin-top:10px;text-align:right;width:100%;" class="col-sm-12">
        <a href="<?php echo $album['album_link'] ?>" class="button"><span><?php echo $album_link_text ?></span></a>
      </div>
    <?php endif ?>
      </div>
    <?php endforeach ?>
    </div>
  <?php if ($show_header): ?>
    </div>
  </div>
  <?php endif ?>
</div>