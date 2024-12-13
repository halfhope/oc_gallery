<div class="box">
  <?php if ($show_header): ?>
    <div class="box-heading"><?php echo $heading_title; ?></div>
  <?php endif ?>
  <div class="box-content">
    <div class="box-category">
    <ul class="box-category">
      <?php foreach ($albums as $key => $album): ?>
    <li><a href="<?php echo $album['album_link'] ?>"><?php echo $album['album_name'] ?></a></li>        
      <?php endforeach ?>
    </ul>
    </div>
  </div>
</div>