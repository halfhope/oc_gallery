<?php if ($show_header): ?>
  <h3><?php echo $heading_title; ?></h3>
<?php endif ?>
<div class="gallery">
  <div class="row">
    <div class="galleries col-sm-12 ">
      <script>
        $(document).ready(function() {
          <?php 
            switch ($album['album_data']['js_lib_type']) {
              case 0: 
                echo "$('a.".$no_conflict."0".$album['album_id']."').colorbox({rel:'".$no_conflict."group".$album['album_id']."'});";
              break;
              case 2:
                echo "$('a.".$no_conflict."2".$album['album_id']."').fancybox({openEffect:'none',closeEffect:'none'});";
              break;
              case 3:
                echo "$('.container_". $no_conflict.$album['album_data']['js_lib_type'].$album['album_id']."').magnificPopup({type:'image',delegate: 'a',gallery: {enabled:true}});";
              break;
            }
          ?>
        });
      </script>
      <?php if (isset($album['album_description'])): ?>
        <div class="album_description"><?php echo $album['album_description'] ?></div>
      <?php endif ?>
      <div class="center container_<?php echo $no_conflict; ?><?php echo $album['album_data']['js_lib_type'] ?><?php echo $album['album_id'] ?>">
        <?php foreach ($album['images'] as $img_key => $image): ?>
        <div class="<?php echo $bootstrap_grid; ?> gallery_photo">
          <a class="transition thumbnail <?php echo $no_conflict; ?><?php echo $album['album_data']['js_lib_type'] ?><?php echo $album['album_id'] ?>"  rel="<?php echo $no_conflict; ?>group<?php echo $album['album_id'] ?>" href="<?php echo $image['popup'] ?>" <?php echo (($album['album_data']['js_lib_type'] == 1)? 'data-lightbox="'.$no_conflict.$album['album_id'].'" data-title="'.$image['title'].'" ' : ''); ?>title="<?php echo $image['title'] ?>">
            <img src="<?php echo $image['thumb'] ?>" width="<?php echo $gallery_thumb_image_width ?>" height="<?php echo $gallery_thumb_image_height ?>" alt="<?php echo $image['title'] ?>" />
            <span><?php echo $image['title'] ?></span>
          </a>
        </div>
      <?php endforeach ?>
      </div>
      <?php if ($show_album_link): ?>
        <div class="row">
          <div class="col-sm-12">
            <div class="pull-right">
              <a href="<?php echo $album['album_link'] ?>" class="btn btn-default"><?php echo $album_link_text ?></a>
            </div>
          </div>
        </div>
      <?php endif ?>
    </div>
  </div>
</div>