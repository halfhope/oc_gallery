<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<style>
.form-horizontal .control-label {
	padding-top: 0;
}
</style>
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-setting" data-toggle="tooltip" title="<?php echo $button_text_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_text_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
			<h1><?php echo $heading_title; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">

		<div class="panel">
			<ul class="nav nav-pills">
				<li role="presentation"><a href="<?php echo $link_section_galleries ?>"><i class="fa fa-list fa-fw"></i> <?php echo $text_section_galleries ?></a></li>
				<li role="presentation"><a href="<?php echo $link_section_modules ?>"><i class="fa fa-puzzle-piece fa-fw"></i> <?php echo $text_section_modules ?></a></li>
				<li role="presentation" class="active"><a href="<?php echo $link_section_settings ?>"><i class="fa fa-cog fa-fw"></i> <?php echo $text_section_settings ?></a></li>
			</ul>
		</div>

		<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<?php if ($success) { ?>
		<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-cog"></i> <?php echo $text_edit; ?></h3><h3 class="panel-title pull-right">v<?php echo $version; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal">
					<div class="tab-content">
						<fieldset><legend><?php echo $text_general_settings ?></legend>
			<div class="form-group ">
				<label class="col-sm-2 control-label"><?php echo $text_feed; ?></label>
				<div class="col-sm-10">
					<a href="<?php echo HTTP_CATALOG.'index.php?route=extension/module/gallery/feed' ?>" target="_blank"><?php echo HTTP_CATALOG.'index.php?route=extension/module/gallery/feed' ?></a>
				</div>
			</div>

			</fieldset>

			<fieldset><legend><?php echo $text_js_library_settings ?></legend>

			<div class="form-group ">
				<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_gallery_include_colorbox_help; ?>"><?php echo $entry_gallery_include_colorbox; ?></span></label>
				<div class="col-sm-10">
					<input type="checkbox" class="switcher ui-switcher form-control" data-target="config_gallery_include_colorbox" <?php echo ($config_gallery_include_colorbox == 1) ? 'checked' : ''; ?>>
					<input type="hidden" id="config_gallery_include_colorbox" name="config_gallery_include_colorbox" value="<?php echo $config_gallery_include_colorbox ?>">
				</div>
			</div>

			<div class="form-group ">
				<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_gallery_include_lightbox_help; ?>"><?php echo $entry_gallery_include_lightbox; ?></span></label>
				<div class="col-sm-10">
					<input type="checkbox" class="switcher ui-switcher form-control" data-target="config_gallery_include_lightbox" <?php echo ($config_gallery_include_lightbox == 1) ? 'checked' : ''; ?>>
					<input type="hidden" id="config_gallery_include_lightbox" name="config_gallery_include_lightbox" value="<?php echo $config_gallery_include_lightbox ?>">
				</div>
			</div>

			<div class="form-group ">
				<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_gallery_include_fancybox_help; ?>"><?php echo $entry_gallery_include_fancybox; ?></span></label>
				<div class="col-sm-10">
					<input type="checkbox" class="switcher ui-switcher form-control" data-target="config_gallery_include_fancybox" <?php echo ($config_gallery_include_fancybox == 1) ? 'checked' : ''; ?>>
					<input type="hidden" id="config_gallery_include_fancybox" name="config_gallery_include_fancybox" value="<?php echo $config_gallery_include_fancybox ?>">
				</div>
			</div>

			<div class="form-group ">
				<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_gallery_include_magnific_popup_help; ?>"><?php echo $entry_gallery_include_magnific_popup; ?></span></label>
				<div class="col-sm-10">
					<input type="checkbox" class="switcher ui-switcher form-control" data-target="config_gallery_include_magnific_popup" <?php echo ($config_gallery_include_magnific_popup == 1) ? 'checked' : ''; ?>>
					<input type="hidden" id="config_gallery_include_magnific_popup" name="config_gallery_include_magnific_popup" value="<?php echo $config_gallery_include_magnific_popup ?>">
				</div>
			</div>

			<div class="form-group hidden">
				<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_gallery_include_jstabs_help; ?>"><?php echo $entry_gallery_include_jstabs; ?></span></label>
				<div class="col-sm-10">
					<input type="checkbox" class="switcher ui-switcher form-control" data-target="config_gallery_include_jstabs" <?php echo ($config_gallery_include_jstabs == 1) ? 'checked' : ''; ?>>
					<input type="hidden" id="config_gallery_include_jstabs" name="config_gallery_include_jstabs" value="<?php echo $config_gallery_include_jstabs ?>">
				</div>
			</div>

			<div class="form-group ">
				<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_gallery_include_lazyload_help; ?>"><?php echo $entry_gallery_include_lazyload; ?></span></label>
				<div class="col-sm-10">
					<input type="checkbox" class="switcher ui-switcher form-control" data-target="config_gallery_include_lazyload" <?php echo ($config_gallery_include_lazyload == 1) ? 'checked' : ''; ?>>
					<input type="hidden" id="config_gallery_include_lazyload" name="config_gallery_include_lazyload" value="<?php echo $config_gallery_include_lazyload ?>">
				</div>
			</div>

			</fieldset>
			<fieldset><legend><?php echo $text_galleries_settings ?></legend>

			<ul class="nav nav-tabs">
				<?php foreach ($stores as $store): ?>
				<li role="presentation" class="<?php echo ($store['store_id'] == 0) ? 'active' :''; ?>"><a href="#tab-store-<?php echo $store['store_id']; ?>" data-toggle="tab"><?php echo $store['name'] ?></a></li>
				<?php endforeach ?>
			</ul>
			<div class="tab-content">
			<?php foreach ($stores as $store): ?>
			<div class="tab-pane <?php echo ($store['store_id'] == 0) ? 'active' :''; ?>" id="tab-store-<?php echo $store['store_id']; ?>">

			<div class="form-group ">
				<label class="col-sm-2 control-label" for="config_gallery_galleries_seo_name<?php echo $store['store_id']; ?>"><span data-toggle="tooltip" title="<?php echo $entry_galleries_seo_name_help; ?>"><?php echo $entry_galleries_seo_name; ?></span></label>
				<div class="col-sm-10">
					<input type="text" name="config_gallery_galleries_seo_name[<?php echo $store['store_id']; ?>]" value="<?php echo $config_gallery_galleries_seo_name[$store['store_id']]; ?>" placeholder="<?php echo $entry_galleries_seo_name; ?>" id="config_gallery_galleries_seo_name<?php echo $store['store_id']; ?>" class="form-control" />
				</div>
			</div>

			<div class="form-group ">
				<label class="col-sm-2 control-label"><?php echo $entry_galleries_include_seo_path; ?></label>
				<div class="col-sm-10">
					<input type="checkbox" class="switcher ui-switcher form-control" data-target="config_gallery_galleries_include_seo_path_<?php echo $store['store_id']; ?>" <?php echo ($config_gallery_galleries_include_seo_path[$store['store_id']] == 1) ? 'checked' : ''; ?>>
					<input type="hidden" id="config_gallery_galleries_include_seo_path_<?php echo $store['store_id']; ?>" name="config_gallery_galleries_include_seo_path[<?php echo $store['store_id']; ?>]" value="<?php echo $config_gallery_galleries_include_seo_path[$store['store_id']] ?>">
				</div>
			</div>

			<div class="form-group ">
				<label class="col-sm-2 control-label" for="config_gallery_cover_image_width<?php echo $store['store_id']; ?>"><?php echo $entry_gallery_cover_image_dimension; ?></label>
				<div class="col-sm-10">
					<div class="row">
						<div class="col-sm-6">
							<input type="text" name="config_gallery_cover_image_width[<?php echo $store['store_id']; ?>]" value="<?php echo $config_gallery_cover_image_width[$store['store_id']]; ?>" placeholder="<?php echo $text_width; ?>" id="config_gallery_cover_image_width<?php echo $store['store_id']; ?>" class="form-control" />
						</div>
						<div class="col-sm-6">
							<input type="text" name="config_gallery_cover_image_height[<?php echo $store['store_id']; ?>]" value="<?php echo $config_gallery_cover_image_height[$store['store_id']]; ?>" placeholder="<?php echo $text_height; ?>" id="config_gallery_cover_image_height<?php echo $store['store_id']; ?>" class="form-control" />
						</div>
					</div>
				</div>
			</div>

			<div class="form-group ">
				<label class="col-sm-2 control-label"><?php echo $entry_gallery_show_counter; ?></label>
				<div class="col-sm-10">
					<input type="checkbox" class="switcher ui-switcher form-control" data-target="config_gallery_show_counter_<?php echo $store['store_id']; ?>" <?php echo ($config_gallery_show_counter[$store['store_id']] == 1) ? 'checked' : ''; ?>>
					<input type="hidden" id="config_gallery_show_counter_<?php echo $store['store_id']; ?>" name="config_gallery_show_counter[<?php echo $store['store_id']; ?>]" value="<?php echo $config_gallery_show_counter[$store['store_id']] ?>">
				</div>
			</div>

			<div class="form-group ">
				<label class="col-sm-2 control-label"><?php echo $entry_gallery_show_description; ?></label>
				<div class="col-sm-10">
					<input type="checkbox" class="switcher ui-switcher form-control" data-target="config_gallery_show_description_<?php echo $store['store_id']; ?>" <?php echo ($config_gallery_show_description[$store['store_id']] == 1) ? 'checked' : ''; ?>>
					<input type="hidden" id="config_gallery_show_description_<?php echo $store['store_id']; ?>" name="config_gallery_show_description[<?php echo $store['store_id']; ?>]" value="<?php echo $config_gallery_show_description[$store['store_id']] ?>">
				</div>
			</div>

			<ul class="nav nav-tabs">
				<?php foreach ($languages as $language): ?>
				<li class="<?php echo ($language == current($languages)) ? 'active' :''; ?>"><a href="#tab-store-<?php echo $store['store_id']; ?>-<?php echo $language['language_id']; ?>" data-toggle="tab"><?php echo $language['html_image'] ?> <?php echo $language['name'] ?></a></li>
				<?php endforeach ?>
			</ul>
			<div class="tab-content">
			<?php foreach ($languages as $language): ?>
			<div class="tab-pane <?php echo ($language == current($languages)) ? 'active' :''; ?>" id="tab-store-<?php echo $store['store_id']; ?>-<?php echo $language['language_id']; ?>">
			<div class="form-group ">
				<label class="col-sm-2 control-label" for="config_gallery_galleries_title[<?php echo $store['store_id']; ?>][<?php echo $language['language_id']; ?>]"><?php echo $entry_title; ?></label>
				<div class="col-sm-10">
					<input type="text" name="config_gallery_galleries_title[<?php echo $store['store_id']; ?>][<?php echo $language['language_id']; ?>]" value="<?php echo (isset($config_gallery_galleries_title[$store['store_id']][$language['language_id']]) ? $config_gallery_galleries_title[$store['store_id']][$language['language_id']] : '') ?>" placeholder="<?php echo $entry_title; ?>" id="config_gallery_galleries_title[<?php echo $store['store_id']; ?>][<?php echo $language['language_id']; ?>]" class="form-control" />
				</div>
			</div>
			<div class="form-group ">
				<label class="col-sm-2 control-label" for="config_gallery_galleries_breadcrumb_<?php echo $store['store_id']; ?>_<?php echo $language['language_id']; ?>"><?php echo $entry_breadcrumb; ?></label>
				<div class="col-sm-10">
					<input type="text" name="config_gallery_galleries_breadcrumb[<?php echo $store['store_id']; ?>][<?php echo $language['language_id']; ?>]" value="<?php echo (isset($config_gallery_galleries_breadcrumb[$store['store_id']][$language['language_id']]) ? $config_gallery_galleries_breadcrumb[$store['store_id']][$language['language_id']] : '') ?>" placeholder="<?php echo $entry_breadcrumb; ?>" id="config_gallery_galleries_breadcrumb_<?php echo $store['store_id']; ?>_<?php echo $language['language_id']; ?>" class="form-control" />
				</div>
			</div>

			<div class="form-group ">
				<label class="col-sm-2 control-label" for="config_gallery_galleries_h1_title_<?php echo $store['store_id']; ?>_<?php echo $language['language_id']; ?>"><?php echo $entry_h1; ?></label>
				<div class="col-sm-10">
					<input type="text" name="config_gallery_galleries_h1_title[<?php echo $store['store_id']; ?>][<?php echo $language['language_id']; ?>]" value="<?php echo (isset($config_gallery_galleries_h1_title[$store['store_id']][$language['language_id']]) ? $config_gallery_galleries_h1_title[$store['store_id']][$language['language_id']] : '') ?>" placeholder="<?php echo $entry_h1; ?>" id="config_gallery_galleries_h1_title_<?php echo $store['store_id']; ?>_<?php echo $language['language_id']; ?>" class="form-control" />
				</div>
			</div>

			<div class="form-group ">
				<label class="col-sm-2 control-label" for="config_gallery_galleries_meta_keywords_<?php echo $store['store_id']; ?>_<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keywords; ?></label>
				<div class="col-sm-10">
					<input type="text" name="config_gallery_galleries_meta_keywords[<?php echo $store['store_id']; ?>][<?php echo $language['language_id']; ?>]" value="<?php echo (isset($config_gallery_galleries_meta_keywords[$store['store_id']][$language['language_id']]) ? $config_gallery_galleries_meta_keywords[$store['store_id']][$language['language_id']] : '') ?>" placeholder="<?php echo $entry_meta_keywords; ?>" id="config_gallery_galleries_meta_keywords_<?php echo $store['store_id']; ?>_<?php echo $language['language_id']; ?>" class="form-control" />
				</div>
			</div>

			<div class="form-group ">
				<label class="col-sm-2 control-label" for="config_gallery_galleries_meta_description_<?php echo $store['store_id']; ?>_<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
				<div class="col-sm-10">
					<textarea name="config_gallery_galleries_meta_description[<?php echo $store['store_id']; ?>][<?php echo $language['language_id']; ?>]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="config_gallery_galleries_meta_description_<?php echo $store['store_id']; ?>_<?php echo $language['language_id']; ?>" class="form-control"><?php echo (isset($config_gallery_galleries_meta_description[$store['store_id']][$language['language_id']]) ? $config_gallery_galleries_meta_description[$store['store_id']][$language['language_id']] : '') ?></textarea>
				</div>
			</div>

			<div class="form-group ">
				<label class="col-sm-2 control-label" for="config_gallery_galleries_description_<?php echo $store['store_id']; ?>_<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
				<div class="col-sm-10">
					<textarea name="config_gallery_galleries_description[<?php echo $store['store_id']; ?>][<?php echo $language['language_id']; ?>]" id="config_gallery_galleries_description_<?php echo $store['store_id']; ?>_<?php echo $language['language_id']; ?>" rows="5" placeholder="<?php echo $entry_description; ?>" id="config_gallery_galleries_description[<?php echo $language['language_id']; ?>]" class="form-control"><?php echo (isset($config_gallery_galleries_description[$store['store_id']][$language['language_id']]) ? $config_gallery_galleries_description[$store['store_id']][$language['language_id']] : '') ?></textarea>
				</div>
			</div>

			</div>
			<?php endforeach; ?>
			</div>
			<fieldset><legend><?php echo $text_grid_settings ?></legend>
	
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-grid_columns_count"><?php echo $entry_bootstrap_grid ?></label>
					<div class="col-sm-10">
						<table class="table table-bordered table-striped bootstrap_grid_table">
							<tbody>
								<tr>
									<td class="text-center"><img src="view/image/bootstrap_grid/extra_small.png" class="img-responsive" alt=""><br>
										Extra small [None(auto)]</td>
									<td class="text-center"><img src="view/image/bootstrap_grid/small.png" class="img-responsive" alt=""><br>
										Small [750px]</td>
									<td class="text-center"><img src="view/image/bootstrap_grid/medium.png" class="img-responsive" alt=""><br>
										Medium [970px]</td>
									<td class="text-center"><img src="view/image/bootstrap_grid/large.png" class="img-responsive" alt=""><br>
										Large [1170px]</td>
								</tr>
								<tr class="values" style="background:#efefef;">
									<?php foreach ($config_gallery_bootstrap_grid[$store['store_id']] as $prefix => $value): ?>
										<td class="text-center">
											<div class="btn-group" role="group" data-toggle="buttons">
												<?php foreach ($bootstrap_grid_to_cols as $bcol => $col): ?>
												<label class="btn btn-sm<?php echo ($bcol == 0) ? ' btn-success' : ' btn-primary' ?> <? echo ($value == $bcol) ? ' active' : '' ?>">
													<input type="radio" name="config_gallery_bootstrap_grid[<?php echo $store['store_id'] ?>][<?php echo $prefix ?>]" value="<?php echo $bcol ?>" autocomplete="off" <?php echo ($value == $bcol) ? 'checked' : '' ?>><?php echo ($bcol == 0) ? 'auto' : $col ?>
												</label>
												<?php endforeach ?>
											</div>
										</td>
									<?php endforeach ?>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

			</fieldset>
			</div>

		<?php endforeach ?>
			</div>

						</fieldset>

					</div>
				</form>
			</div>
		</div>
	</div>
<script type="text/javascript"><!--
// $(document).ready(function() {

$('.switcher').on('change', function(e) {
	$('#' + $(e.target).data('target')).val(Number($(e.target).prop('checked')));
});
$.switcher('input[type=checkbox].switcher');

<?php foreach ($languages as $language) { ?>
<?php foreach ($stores as $store) { ?>
$('#config_gallery_galleries_description_<?php echo $store['store_id']; ?>_<?php echo $language['language_id']; ?>').summernote({height: 300});
<?php } ?>
<?php } ?>
// });
//--></script>
<?php echo $footer; ?>