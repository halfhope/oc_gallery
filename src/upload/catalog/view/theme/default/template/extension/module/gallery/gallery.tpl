<?php echo $header; ?>
<div class="container">
	<ul class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li> <a href="<?php echo $breadcrumb['href']; ?>"> <?php echo $breadcrumb['text']; ?> </a> </li>
		<?php } ?>
	</ul>
	<div class="row"><?php echo $column_left; ?>
		<?php if ($column_left && $column_right) { ?>
		<?php $class = 'col-sm-6'; ?>
		<?php } elseif ($column_left || $column_right) { ?>
		<?php $class = 'col-sm-9'; ?>
		<?php } else { ?>
		<?php $class = 'col-sm-12'; ?>
		<?php } ?>
		<div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
			<h1><?php echo $heading_title; ?></h1>
			<?php if ($gallery['gallery_data']['show_limiter']): ?>
				<div class="row control-group">
					<div class="col-md-3 col-md-offset-7 text-right">
						<label class="control-label" for="input-limit"><?php echo $text_limit; ?></label>
					</div>
					<div class="col-md-2">
						<select id="input-limit" class="form-control" onchange="location = this.value;">
							<?php foreach ($limits as $limits) { ?>
							<?php if ($limits['value'] == $limit) { ?>
							<option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
							<?php } else { ?>
							<option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
							<?php } ?>
							<?php } ?>
						</select>
					</div>
				</div>
			<?php endif ?>
			<div class="gallery">
				<script>
					$(document).ready(function() {
						<?php if ($gallery['gallery_data']['use_lazyload']): ?>
							isMobDevice = (/iphone|ipad|Android|webOS|iPod|BlackBerry|Windows Phone|ZuneWP7/gi).test(navigator.appVersion);
							if(!isMobDevice) {
								$("img.lazy").lazyload({
									effect: "fadeIn",
								});
							} else {
								$('img.lazy').each(function() {
									$(this).attr('src',$(this).data('original'));
								});
							}
						<?php endif ?>
						<?php switch ($gallery['gallery_data']['js_lib_type']) {
							case 'colorbox': 
								echo "$('a." . $no_conflict . $gallery['gallery_data']['js_lib_type'] . $gallery['gallery_id'] . "').colorbox({rel:'" . $no_conflict . "group" . $gallery['gallery_id'] . "'});\n";
							break;
							case 'fancybox': 
								echo "$('a." . $no_conflict . $gallery['gallery_data']['js_lib_type'] . $gallery['gallery_id'] . "').fancybox({openEffect:'none', closeEffect:'none'});\n";
							break;
							case 'magnific_popup': 
								echo "$('.container" . $no_conflict . $gallery['gallery_data']['js_lib_type'] . $gallery['gallery_id'] . "').magnificPopup({type:'image', delegate: 'a', gallery: {enabled:true}});\n";
							break;
						} ?>
					});
				</script>
		
				<?php if (isset($gallery['gallery_description'])): ?>
				<div class="gallery_description"><?php echo $gallery['gallery_description'] ?></div>
				<?php endif ?>
				
				<div class="center container<?php echo $no_conflict; ?><?php echo $gallery['gallery_data']['js_lib_type'] ?><?php echo $gallery['gallery_id'] ?>">
					<div class="row">
					<?php foreach ($gallery['images'] as $img_key => $image): ?>
						<div class="<?php echo $gallery['bootstrap_grid']; ?> gallery_photo">
							<a class="transition <?php echo $no_conflict; ?><?php echo $gallery['gallery_data']['js_lib_type'] ?><?php echo $gallery['gallery_id'] ?> thumbnail"  rel="<?php echo $no_conflict; ?>group<?php echo $gallery['gallery_id'] ?>" href="<?php echo $image['popup'] ?>" <?php echo (($gallery['gallery_data']['js_lib_type'] == 'lightbox')? 'data-lightbox="'.$no_conflict.$gallery['gallery_id'].'" data-title="'.$image['title'].'" ' : ''); ?>title="<?php echo $image['title'] ?>">
								<img <?php if ($gallery['gallery_data']['use_lazyload']){echo 'class="lazy" src="'.$gallery['gallery_data']['lazyload_image'].'" data-original';}else{echo 'src';} ?>="<?php echo $image['thumb'] ?>" width="<?php echo $gallery['gallery_data']['thumb_width'] ?>" height="<?php echo $gallery['gallery_data']['thumb_height'] ?>" alt="<?php echo $image['title'] ?>" />
								<span><?php echo $image['title'] ?></span>
							</a>
						</div>
					<?php endforeach ?>
					</div>
				</div>
			</div>
			<?php if ($show_pagination): ?>
				<div class="row col-sm-12">
					<div class="pagination"><?php echo $pagination; ?></div>
				</div>
			<?php endif ?>
			<?php echo $content_bottom; ?>
		</div>
		<?php echo $column_right; ?>
	</div>
</div>
<?php echo $footer; ?>