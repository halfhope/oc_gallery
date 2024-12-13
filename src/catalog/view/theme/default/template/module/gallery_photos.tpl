<style>
  .gallery{
    text-align: center;
  }
  .gallery .gallery_photo:hover{
    border: 1px solid #afafaf;
    box-shadow: 0 0 3px 0px rgba(0, 0, 0, 0.2),inset 0 1px 1px #fff;    
  }
  .gallery .gallery_photo{
    text-decoration: none;
    display: inline-block;
    border: 1px solid #ddd;
    padding: 5px;
    border-radius:4px;
    margin:3px;
  }
  .gallery .gallery_photo img, .gallery .gallery_photo span{
    display: block;
  }
  .gallery .gallery_photo span{
    text-align: center;
    padding-top:6px;
  }
  .gallery .album_description{
    padding: 10px;
    text-align: left;
  }
  .transition{
    -webkit-transition: all 100ms ease-in-out;
    -moz-transition: all 100ms ease-in-out;
    -ms-transition: all 100ms ease-in-out;
    -o-transition: all 100ms ease-in-out;
    transition: all 100ms ease-in-out;
  }
</style>
<div class="box">
  <?php if ($show_header): ?>
    <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-gallery">
  <?php endif ?>
  <div class="gallery">
    <?php if (count($albums) >= 2): ?>
      <div id="gallery_tabs<?php echo $no_conflict; ?>" class="htabs">
      <?php foreach ($albums as $key => $album): ?>
        <a href="#<?php echo $no_conflict; ?>tab_album<?php echo $album['album_id'] ?>"><?php echo $album['album_name'] ?></a>
      <?php endforeach ?>
      </div>
    <?php endif ?>
    <script>
      $(document).ready(function() {
        $('#gallery_tabs<?php echo $no_conflict; ?> a').tabs();
        <?php 
        foreach ($albums as $album) {
          switch ($album['album_data']['js_lib_type']) {
            case 0: 
            echo "$('a.".$no_conflict."colorbox".$album['album_id']."').colorbox({rel:'".$no_conflict."group".$album['album_id']."'});";
            break;
            case 2:
             echo "$('a.".$no_conflict."fancybox".$album['album_id']."').fancybox({openEffect:'none',closeEffect:'none'});";
            break;
          }
        } 
        ?>
      });
    </script>
    <?php foreach ($albums as $key => $album): ?>
    <?php if (count($albums) >= 2): ?>
      <div id="<?php echo $no_conflict; ?>tab_album<?php echo $album['album_id'] ?>" class="tab-content">
    <?php endif ?>
    <?php if (isset($album['album_description'])): ?>
      <div class="album_description"><?php echo $album['album_description'] ?></div>
    <?php endif ?>
      <?php foreach ($album['images'] as $img_key => $image): ?>
        <a class="transition gallery_photo <?php echo $no_conflict; ?><?php echo $album['js_lib_type_text'] ?><?php echo $album['album_id'] ?>"  rel="<?php echo $no_conflict; ?>group<?php echo $album['album_id'] ?>" href="<?php echo $image['popup'] ?>" <?php echo (($album['album_data']['js_lib_type'] == 1)? 'data-lightbox="'.$no_conflict.$album['album_id'].'" data-title="'.$image['title'].'" ' : ''); ?>title="<?php echo $image['title'] ?>">
          <img src="<?php echo $image['thumb'] ?>" width="<?php echo $gallery_thumb_image_width ?>" height="<?php echo $gallery_thumb_image_height ?>" alt="<?php echo $image['title'] ?>">
          <span><?php echo $image['title'] ?></span>
        </a>
      <?php endforeach ?>
    <?php if ($show_album_link): ?>
      <div style="margin:0px;margin-top:10px;text-align:right;width:100%;">
        <a href="<?php echo $album['album_link'] ?>" class="button"><span><?php echo $album_link_text ?></span></a>
      </div>
    <?php endif ?>
    <?php if (count($albums) >= 2): ?>
      </div>
    <?php endif ?>
    <?php endforeach ?>
    </div>
  <?php if ($show_header): ?>
    </div>
  </div>
  <?php endif ?>
</div>