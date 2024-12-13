<div role="tabpanel">
  <?php if ($show_header): ?>
    <h3><?php echo $heading_title; ?></h3>
  <?php endif ?>
  <div class="row">
    <div class="gallery col-sm-12 center">
      <ul class="nav nav-tabs" role="tablist">
      <?php foreach ($albums as $key => $album): ?>
        <li role="presentation" class="<?php echo ($album == current($albums)) ? 'active' : ''; ?>"><a href="#<?php echo $no_conflict; ?>tab_album<?php echo $album['album_id'] ?>" aria-controls="#<?php echo $no_conflict; ?>tab_album<?php echo $album['album_id'] ?>" role="tab" data-toggle="tab"><?php echo $album['album_name'] ?></a></li>
      <?php endforeach ?>
      </ul>
      <script>
      $(document).ready(function(){
        <?php 
        foreach ($albums as $album) {
          switch ((int)$album['album_data']['js_lib_type']) {
            case 0: 
              echo "$('a.".$no_conflict."0".$album['album_id']."').colorbox({rel:'".$no_conflict."group".$album['album_id']."'});".PHP_EOL;
            break;
            case 2:
              echo "$('a.".$no_conflict."2".$album['album_id']."').fancybox({openEffect:'none',closeEffect:'none'});".PHP_EOL;
            break;
            case 3:
              echo "$('#" . $no_conflict . "tab_album" . $album['album_id'] . "').magnificPopup({type:'image',delegate: 'a',gallery: {enabled:true}});".PHP_EOL;
            break;
          }
        } 
        ?>
      });
      </script>
    <div class="tab-content">
    <?php foreach ($albums as $key => $album): ?>
      <div role="tabpanel" class="tab-pane <?php echo ($album == current($albums)) ? 'active' : ''; ?>" id="<?php echo $no_conflict; ?>tab_album<?php echo $album['album_id'] ?>">
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
          <div class="row">
            <div class="col-sm-12">
              <div class="pull-right">
                <a href="<?php echo $album['album_link'] ?>" class="btn btn-default"><?php echo $album_link_text ?></a>
              </div>
            </div>
          </div>
        <?php endif ?>
      </div>
    <?php endforeach ?>
    </div>
  </div>
</div>