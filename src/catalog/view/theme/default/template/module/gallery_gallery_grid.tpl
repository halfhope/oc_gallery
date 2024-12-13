<div class="box">
  <?php if ($show_header): ?>
    <div class="box-heading"><?php echo $heading_title; ?></div>
  <?php endif ?>
  <div class="box-content">
    <div class="box-gallery">
    <?php foreach ($albums as $key => $album): ?>
      <a class="transition gallery_cover" href="<?php echo $album['album_link'] ?>">
        <img src="<?php echo $album['album_data']['cover_image']['thumb'] ?>" width="<?php echo $cover_image_width ?>" height="<?php echo $cover_image_height ?>" alt="<?php echo $album['album_name'] ?>" />
        <span class="asd">
          <?php echo $album['album_name'] ?>
        </span>
      </a>
    <?php endforeach ?>
    <?php if ($show_album_galleries_link): ?>
      <div style="margin:0px;margin-top:10px;text-align:right;width:100%;">
        <a href="<?php echo $galleries_link ?>" class="button"><span><?php echo $album_galleries_link_text ?></span></a>
      </div>
    <?php endif ?>
    </div>
  </div>
</div>