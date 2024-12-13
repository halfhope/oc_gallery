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
      <h1><?php echo $h1_title; ?></h1>
  <div class="galleries">
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
<?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>