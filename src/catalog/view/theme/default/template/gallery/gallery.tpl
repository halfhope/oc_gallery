<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $h1_title; ?></h1>
  <div class="galleries content">
  <?php if (isset($galleries_description) && !empty($galleries_description)): ?>
    <div class="album_description"><?php echo $galleries_description ?></div>
  <?php endif ?>
    <div class="center">
    <?php foreach ($albums as $key => $album): ?>
      <div class="<?php echo $bootstrap_grid; ?> gallery_cover">
        <a class="transition thumbnail" href="<?php echo $album['album_link'] ?>">
          <img src="<?php echo $album['album_data']['cover_image']['thumb'] ?>" width="<?php echo $config_gallery_cover_image_width ?>" height="<?php echo $config_gallery_cover_image_height ?>" alt="<?php echo $album['album_name'] ?>" />
          <span><?php echo $album['album_name'] ?></span>
        </a>
      </div>
    <?php endforeach ?>
    </div>
  </div>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>