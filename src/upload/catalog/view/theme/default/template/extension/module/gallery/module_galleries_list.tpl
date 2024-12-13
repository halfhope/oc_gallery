<?php if ($show_header): ?>
<h3><?php echo $heading_title; ?></h3>
<?php endif ?>
<div class="list-group">
	<?php foreach ($galleries as $key => $gallery): ?>
		<?php if (isset($current_gallery_id) && $gallery['gallery_id'] == $current_gallery_id): ?>
			<a href="<?php echo $gallery['gallery_link'] ?>" class="list-group-item active"><?php echo $gallery['gallery_name'] ?></a>
		<?php else: ?>
			<a href="<?php echo $gallery['gallery_link'] ?>" class="list-group-item"><?php echo $gallery['gallery_name'] ?></a>
		<?php endif ?>
	<?php endforeach ?>
</div>
