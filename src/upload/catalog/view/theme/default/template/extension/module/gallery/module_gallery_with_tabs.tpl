<div role="tabpanel">
	<?php if ($show_header): ?>
		<h3><?php echo $heading_title; ?></h3>
	<?php endif ?>
	<div class="row">
		<div class="gallery col-sm-12 center">
			<ul class="nav nav-tabs" role="tablist">
			<?php foreach ($galleries as $key => $gallery): ?>
				<li role="presentation" class="<?php echo ($gallery == current($galleries)) ? 'active' : ''; ?>"><a href="#<?php echo $no_conflict; ?>tab_gallery<?php echo $gallery['gallery_id'] ?>" aria-controls="#<?php echo $no_conflict; ?>tab_gallery<?php echo $gallery['gallery_id'] ?>" role="tab" data-toggle="tab"><?php echo $gallery['gallery_name'] ?></a></li>
			<?php endforeach ?>
			</ul>
			<script>
			$(document).ready(function(){
				<?php foreach ($galleries as $gallery) {
					switch ($gallery['gallery_data']['js_lib_type']) {
						case 'colorbox': 
							echo "$('a.".$no_conflict.$gallery['gallery_data']['js_lib_type'].$gallery['gallery_id']."').colorbox({rel:'".$no_conflict."group".$gallery['gallery_id']."'});\n";
						break;
						case 'fancybox':
							echo "$('a.".$no_conflict.$gallery['gallery_data']['js_lib_type'].$gallery['gallery_id']."').fancybox({openEffect:'none',closeEffect:'none'});\n";
						break;
						case 'magnific_popup':
							echo "$('#" . $no_conflict . "tab_gallery" . $gallery['gallery_id'] . "').magnificPopup({type:'image',delegate: 'a.thumbnail',gallery: {enabled:true}});\n";
						break;
					}
				} ?>
			});
			</script>
		<div class="tab-content">
		<?php foreach ($galleries as $key => $gallery): ?>
			<div role="tabpanel" class="tab-pane <?php echo ($gallery == current($galleries)) ? 'active' : ''; ?>" id="<?php echo $no_conflict; ?>tab_gallery<?php echo $gallery['gallery_id'] ?>">
				<?php if (isset($gallery['gallery_description'])): ?>
					<div class="gallery_description"><?php echo $gallery['gallery_description'] ?></div>
				<?php endif ?>
				<div class="row">
				<?php foreach ($gallery['images'] as $img_key => $image): ?>
					<div class="<?php echo $bootstrap_grid; ?> gallery_photo">
						<a class="transition thumbnail <?php echo $no_conflict; ?><?php echo $gallery['gallery_data']['js_lib_type'] ?><?php echo $gallery['gallery_id'] ?>" rel="<?php echo $no_conflict; ?>group<?php echo $gallery['gallery_id'] ?>" href="<?php echo $image['popup'] ?>" <?php echo (($gallery['gallery_data']['js_lib_type'] == 'lightbox')? 'data-lightbox="'.$no_conflict.$gallery['gallery_id'].'" data-title="'.$image['title'].'" ' : ''); ?>title="<?php echo $image['title'] ?>">
							<img src="<?php echo $image['thumb'] ?>" width="<?php echo $gallery_thumb_image_width ?>" height="<?php echo $gallery_thumb_image_height ?>" alt="<?php echo $image['title'] ?>" /><span><?php echo $image['title'] ?></span>
						</a>
					</div>
				<?php endforeach ?>
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
		<?php endforeach ?>
		</div>
	</div>
</div>