<?php if ($show_header): ?>
<h3><?php echo $heading_title; ?></h3>
<?php endif ?>
<div class="list-group">
  <?php foreach ($albums as $key => $album): ?>
    <?php if (isset($current_album_id) && $album['album_id'] == $current_album_id): ?>
      <a href="<?php echo $album['album_link'] ?>" class="list-group-item active"><?php echo $album['album_name'] ?></a>
    <?php else: ?>
      <a href="<?php echo $album['album_link'] ?>" class="list-group-item"><?php echo $album['album_name'] ?></a>
    <?php endif ?>
  <?php endforeach ?>
</div>
