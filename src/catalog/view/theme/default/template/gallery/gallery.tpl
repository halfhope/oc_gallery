<style>
  .box-gallery{
    text-align: center;
  }
  .box-gallery .gallery_cover{
    text-decoration: none;
    display: inline-block;
    border: 1px solid #ddd;
    padding: 5px;
    border-radius:4px;
    margin:3px;
  }
   .box-gallery .gallery_cover:hover{
    border: 1px solid #afafaf;
    box-shadow: 0 0 3px 0px rgba(0, 0, 0, 0.2),inset 0 1px 1px #fff;    
  }
  .box-gallery .gallery_cover img, .box-gallery .gallery_cover span{
    display: block;
  }
  .box-gallery .gallery_cover span{
    text-align: center;
    padding-top:6px;
  }
  .transition{
    -webkit-transition: all 100ms ease-in-out;
    -moz-transition: all 100ms ease-in-out;
    -ms-transition: all 100ms ease-in-out;
    -o-transition: all 100ms ease-in-out;
    transition: all 100ms ease-in-out;
  }
</style>
<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <div class="content">
	<div class="box-gallery">
	<?php foreach ($albums as $key => $album): ?>
      <a class="transition gallery_cover" href="<?php echo $album['album_link'] ?>">
        <img src="<?php echo $album['album_data']['cover_image']['thumb'] ?>" alt="<?php echo $album['album_name'] ?>">
        <span class="asd">
          <?php echo $album['album_name'] ?>
        </span>
      </a>
    <?php endforeach ?>
	</div>
  </div>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>