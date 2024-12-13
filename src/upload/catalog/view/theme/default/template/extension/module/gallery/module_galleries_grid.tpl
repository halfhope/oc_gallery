<div class="gallery_gallery_grid">
	<?php if ($show_header): ?>
	<h3><?php echo $heading_title; ?></h3>
	<?php endif ?>
	<div class="row">
		<div class="galleries col-sm-12 ">
			<div class="center">
				<div class="row">
				<?php foreach ($galleries as $key => $gallery): ?>
					<div class="<?php echo $bootstrap_grid; ?> gallery_cover">
						<a class="transition thumbnail" href="<?php echo $gallery['gallery_link'] ?>">
							<img src="<?php echo $gallery['gallery_data']['cover_image']['thumb'] ?>" width="<?php echo $cover_image_width ?>" height="<?php echo $cover_image_height ?>" alt="<?php echo $gallery['gallery_name'] ?>" />
							<span><?php echo $gallery['gallery_name'] ?></span>
						</a>
					</div>
				<?php endforeach ?>
				</div>
			</div>
			<?php if ($show_gallery_galleries_link): ?>
				<div class="pull-right">
					<a href="<?php echo $galleries_link ?>" class="btn btn-default"><?php echo $gallery_galleries_link_text ?></a>
				</div>
			<?php endif ?>
		</div>
	</div>
</div>