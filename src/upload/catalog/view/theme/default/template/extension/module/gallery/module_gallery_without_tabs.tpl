<?php if ($show_header): ?>
<h3><?php echo $heading_title; ?></h3>
<?php endif ?>
<div class="gallery">
	<div class="row">
		<div class="galleries col-sm-12 ">
			<script>
				$(document).ready(function() {
					<?php 
						switch ($gallery['gallery_data']['js_lib_type']) {
							case 'colorbox': 
								echo "$('a.".$no_conflict."0".$gallery['gallery_id']."').colorbox({rel:'".$no_conflict."group".$gallery['gallery_id']."'});";
							break;
							case 'fancybox':
								echo "$('a.".$no_conflict."2".$gallery['gallery_id']."').fancybox({openEffect:'none',closeEffect:'none'});";
							break;
							case 'magnific_popup':
								echo "$('.container_". $no_conflict.$gallery['gallery_data']['js_lib_type'].$gallery['gallery_id']."').magnificPopup({type:'image',delegate: 'a',gallery: {enabled:true}});";
							break;
						}
					?>
				});
			</script>
			<?php if (isset($gallery['gallery_description'])): ?>
				<div class="gallery_description"><?php echo $gallery['gallery_description'] ?></div>
			<?php endif ?>
			<div class="center container_<?php echo $no_conflict; ?><?php echo $gallery['gallery_data']['js_lib_type'] ?><?php echo $gallery['gallery_id'] ?>">
				<div class="row">
				<?php foreach ($gallery['images'] as $img_key => $image): ?>
				<div class="<?php echo $bootstrap_grid; ?> gallery_photo">
					<a class="transition thumbnail <?php echo $no_conflict; ?><?php echo $gallery['gallery_data']['js_lib_type'] ?><?php echo $gallery['gallery_id'] ?>"  rel="<?php echo $no_conflict; ?>group<?php echo $gallery['gallery_id'] ?>" href="<?php echo $image['popup'] ?>" <?php echo (($gallery['gallery_data']['js_lib_type'] == 'lightbox')? 'data-lightbox="'.$no_conflict.$gallery['gallery_id'].'" data-title="'.$image['title'].'" ' : ''); ?>title="<?php echo $image['title'] ?>">
						<img src="<?php echo $image['thumb'] ?>" width="<?php echo $gallery_thumb_image_width ?>" height="<?php echo $gallery_thumb_image_height ?>" alt="<?php echo $image['title'] ?>" />
						<span><?php echo $image['title'] ?></span>
					</a>
				</div>
			<?php endforeach ?>
				</div>
			</div>
			<?php if ($show_gallery_link): ?>
				<div class="row">
					<div class="col-sm-12">
						<div class="pull-right">
							<a href="<?php echo $gallery['gallery_link'] ?>" class="btn btn-default"><?php echo $gallery_link_text ?></a>
						</div>
					</div>
				</div>
			<?php endif ?>
		</div>
	</div>
</div>