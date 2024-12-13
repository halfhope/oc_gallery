<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<style>

.vtabs {
	width: 190px;
	padding: 10px 0;
	float: left;
	display: block;
	border-right: 1px solid #DDD
}
.vtabs a {
	display: none
}
.vtabs a,
.vtabs span {
	display: block;
	float: left;
	border-radius: 3px 0px 0px 3px;
	width: 190px;
	margin-bottom: 5px;
	clear: both;
	border-top: 1px solid #DDD;
	border-left: 1px solid #DDD;
	border-bottom: 1px solid #DDD;
	background: #F7F7F7;
	padding: 6px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
	font-weight: 700;
	text-align: right;
	text-decoration: none;
	border-right: 1px solid #ddd;
}
.vtabs a.selected {
	background: #FFF;
	border-right: 1px solid #fff;
}
.vtabs a img,
.vtabs span img {
	position: relative;
	top: 3px;
	cursor: pointer
}
.vtabs .tab_btn {
	word-break: break-all;
}
.vtabs-content {
	display: none;
}
.s_gallery {
	background-color: #f4ffed;
	border-left: 5px solid #b1db95;
	display: none;
}
.s_galleries {
	background-color: #f7f2ff;
	border-left: 5px solid #c4a0ff;
	display: none;
}
.col-sm-2 .ui-switcher {
	margin-top: 7px;
}
.form-horizontal .control-label {
	padding-top: 0;
}
</style>
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<a href="#" onclick="$('#form-modules').submit();return false;" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></a>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
				<li role="presentation" class="active"><a href="<?php echo $link_section_modules ?>"><i class="fa fa-puzzle-piece fa-fw"></i> <?php echo $text_section_modules ?></a></li>
				<li role="presentation"><a href="<?php echo $link_section_settings ?>"><i class="fa fa-cog fa-fw"></i> <?php echo $text_section_settings ?></a></li>
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
		<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_layout; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3><h3 class="panel-title pull-right">v<?php echo $version; ?></h3>
			</div>
			<div class="panel-body">
			 <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-modules" class="form-horizontal">
				<div class="row">
					<div class="col-sm-2">
				<div id="tabs" class="vtabs">
					<?php $module_row = 1; ?>
					<?php foreach ($modules as $key => $module): ?>
						<a href="#tab_module_<?php echo $module_row; ?>" id="a_tab_module_<?php echo $module_row; ?>" class="tab_btn tab_module_<?php echo $key; ?> <?php echo (isset($selected_module_id) && $key == $selected_module_id) ? 'selected' : ''; ?>">
							<?php echo $module['name'] ?>
							<i class="fa fa-minus-square fa-fw" onclick="$('#tab_module_<?php echo $module_row; ?>, #a_tab_module_<?php echo $module_row; ?>').remove(); $('#tabs a.tab_btn:last').click(); return false;"></i>
						</a>
						<?php $module_row++; ?>
					<?php endforeach ?>
					<a href="#" id="add_module_btn"><?php echo $button_text_add_module ?> <i class="fa fa-plus-square fa-fw"></i></a>
				</div>
				</div>
				<div class="col-sm-10">
				<div id="module_wrapper">
				<?php $module_row = 1; ?>
				<?php foreach ($modules as $module) { ?>
				<div id="tab_module_<?php echo $module_row; ?>" class="vtabs-content">
				<div class="tab-content">

				<input type="hidden" name="gallery_module[<?php echo $module_row; ?>][module_id]" value="<?php echo $module['module_id'] ?>">

			<div class="form-group required">
				<label class="col-sm-2 control-label" for="gallery_module_<?php echo $module_row; ?>_name"><span data-toggle="tooltip" title="<?php echo $entry_module_name_help; ?>"><?php echo $entry_module_name; ?></span></label>
				<div class="col-sm-10">
					<input type="text" data-id="<?php echo $module_row; ?>" name="gallery_module[<?php echo $module_row; ?>][name]" value="<?php echo $module['name']; ?>" placeholder="<?php echo $entry_module_name; ?>" id="gallery_module_<?php echo $module_row; ?>_name" class="form-control name-changer" />
				</div>
			</div>

			<div class="form-group ">
				<label class="col-sm-2 control-label" for="gallery_module_<?php echo $module_row; ?>_header"><?php echo $entry_module_header; ?></label>
				<div class="col-sm-10">
				<?php foreach ($languages as $language) { ?>
					<div class="row">
						<div class="col-sm-10">
							<div class="input-group">
								<span class="input-group-addon"><?php echo $language['html_image'] ?></span>
								<input type="text" name="gallery_module[<?php echo $module_row; ?>][header][<?php echo $language['language_id']; ?>]" value="<?php echo isset($module['header'][$language['language_id']]) ? $module['header'][$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_module_header; ?>" id="gallery_module[<?php echo $module_row; ?>][header][<?php echo $language['language_id']; ?>]" class="form-control" />
							</div>
						</div>
						<div class="col-sm-2">
							<input type="checkbox" class="switcher ui-switcher form-control" data-target="gallery_module_<?php echo $module_row; ?>_show_header_<?php echo $language['language_id']; ?>" <?php echo ($module['show_header'][$language['language_id']] == 1) ? 'checked' : '' ?>>
							<input type="hidden" id="gallery_module_<?php echo $module_row; ?>_show_header_<?php echo $language['language_id']; ?>" name="gallery_module[<?php echo $module_row; ?>][show_header][<?php echo $language['language_id']; ?>]" value="<?php echo $module['show_header'][$language['language_id']] ?>">
						</div>
					</div>
					<br>
				<?php } ?>
				</div>
			</div>

			<div class="form-group ">
				<label class="col-sm-2 control-label" for="gallery_module_<?php echo $module_row; ?>_status"><?php echo $entry_status; ?></label>
				<div class="col-sm-10">
					<input type="checkbox" class="switcher ui-switcher form-control" data-target="gallery_module_<?php echo $module_row; ?>_status" <?php echo ($module['status'] == 1) ? 'checked' : '' ?>>
					<input type="hidden" id="gallery_module_<?php echo $module_row; ?>_status" name="gallery_module[<?php echo $module_row; ?>][status]" value="<?php echo $module['status'] ?>">
				</div>
			</div>

			<div class="form-group ">
				<label class="col-sm-2 control-label" for="gallery_module_<?php echo $module_row; ?>_module_type"><?php echo $entry_module_type; ?></label>
				<div class="col-sm-10">
					<select name="gallery_module[<?php echo $module_row; ?>][module_type]" data-id="<?php echo $module_row; ?>" id="gallery_module_<?php echo $module_row; ?>_module_type" class="form-control type-changer">
						<?php foreach ($module_types as $key => $module_type) { ?>
							<?php if ($key == $module['module_type']) { ?>
								<option value="<?php echo $key; ?>" selected="selected"><?php echo $module_type; ?></option>
							<?php } else { ?>
								<option value="<?php echo $key; ?>"><?php echo $module_type; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="form-group s_galleries s_galleries_<?php echo $module_row ?>">
				<label class="col-sm-2 control-label" for="input-process-status"><span data-toggle="tooltip" title="<?php echo $entry_photo_gallery_list_help; ?>"><?php echo $entry_photo_gallery_list; ?></span></label>
				<div class="col-sm-10">
					<div class="well well-sm" style="height: 150px; overflow: auto;">
						<?php foreach ($galleries as $gallery) { ?>
						<div class="checkbox">
							<label>
								<?php if (in_array($gallery['gallery_id'], $module['gallery_list'])) { ?>
								<input type="checkbox" name="gallery_module[<?php echo $module_row ?>][gallery_list][]" value="<?php echo $gallery['gallery_id']; ?>" checked="checked" />
								<?php echo $gallery['gallery_data']['gallery_name'][$config_admin_language_id]; ?>
								<?php } else { ?>
								<input type="checkbox" name="gallery_module[<?php echo $module_row ?>][gallery_list][]" value="<?php echo $gallery['gallery_id']; ?>" />
								<?php echo $gallery['gallery_data']['gallery_name'][$config_admin_language_id]; ?>
								<?php } ?>
							</label>
						</div>
						<?php } ?>
					</div>
				<a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a></div>
			</div>

			<div class="form-group s_galleries s_galleries_<?php echo $module_row ?>">
				<label class="col-sm-2 control-label"><?php echo $entry_show_covers; ?></label>
				<div class="col-sm-10">
					<input type="checkbox" class="switcher ui-switcher form-control" data-target="gallery_module_<?php echo $module_row ?>_show_covers" <?php echo ($module['show_covers'] == 1) ? 'checked' : '' ?>>
					<input type="hidden" id="gallery_module_<?php echo $module_row ?>_show_covers" name="gallery_module[<?php echo $module_row ?>][show_covers]" value="<?php echo $module['show_covers'] ?>">
				</div>
			</div>

			<div class="form-group s_galleries s_galleries_<?php echo $module_row ?>">
				<label class="col-sm-2 control-label" for="gallery_module[<?php echo $module_row; ?>][cover_image_width]"><?php echo $entry_cover_size; ?></label>
				<div class="col-sm-10">
					<div class="row">
						<div class="col-sm-6">
							<input type="text" name="gallery_module[<?php echo $module_row; ?>][cover_image_width]" value="<?php echo $module['cover_image_width']; ?>" placeholder="<?php echo $entry_cover_size; ?>" id="gallery_module[<?php echo $module_row; ?>][cover_image_width]" class="form-control" />
						</div>
						<div class="col-sm-6">
							<input type="text" name="gallery_module[<?php echo $module_row; ?>][cover_image_height]" value="<?php echo $module['cover_image_height']; ?>" placeholder="<?php echo $entry_cover_size; ?>" id="gallery_module[<?php echo $module_row; ?>][cover_image_height]" class="form-control" />
						</div>
					</div>
				</div>
			</div>

			<div class="form-group s_galleries s_galleries_<?php echo $module_row ?>">
				<label class="col-sm-2 control-label"><?php echo $entry_show_counter; ?></label>
				<div class="col-sm-10">
					<input type="checkbox" class="switcher ui-switcher form-control" data-target="gallery_module_<?php echo $module_row ?>_show_counter" <?php echo ($module['show_counter'] == 1) ? 'checked' : '' ?>>
					<input type="hidden" id="gallery_module_<?php echo $module_row ?>_show_counter" name="gallery_module[<?php echo $module_row ?>][show_counter]" value="<?php echo $module['show_counter'] ?>">
				</div>
			</div>

			<div class="form-group s_galleries s_galleries_<?php echo $module_row ?>">
				<label class="col-sm-2 control-label"><?php echo $entry_show_gallery_galleries_link; ?></label>
				<div class="col-sm-10">
					<input type="checkbox" class="switcher ui-switcher form-control" data-target="gallery_module_<?php echo $module_row ?>_show_gallery_galleries_link" <?php echo ($module['show_gallery_galleries_link'] == 1) ? 'checked' : '' ?>>
					<input type="hidden" id="gallery_module_<?php echo $module_row ?>_show_gallery_galleries_link" name="gallery_module[<?php echo $module_row ?>][show_gallery_galleries_link]" value="<?php echo $module['show_gallery_galleries_link'] ?>">
				</div>
			</div>

			<div class="form-group s_galleries s_galleries_<?php echo $module_row ?>">
				<label class="col-sm-2 control-label" for="gallery_module[<?php echo $module_row ?>][gallery_galleries_link_text]"><?php echo $entry_show_gallery_text; ?></label>
				<div class="col-sm-10">
					<div class="row">
				<?php foreach ($languages as $language): ?>
					<div class="col-sm-6">
					<div class="input-group">
						<span class="input-group-addon"><?php echo $language['html_image'] ?></span>
						<input type="text" name="gallery_module[<?php echo $module_row ?>][gallery_galleries_link_text][<?php echo $language['language_id']; ?>]" value="<?php echo $module['gallery_galleries_link_text'][$language['language_id']]; ?>" placeholder="<?php echo $entry_show_gallery_text; ?>" id="gallery_module[<?php echo $module_row ?>][gallery_galleries_link_text][<?php echo $language['language_id']; ?>]" class="form-control" />
					</div>
					</div>
				<?php endforeach ?>
					</div>
				</div>
			</div>
			<div class="form-group s_gallery s_gallery_<?php echo $module_row ?>">
				<label class="col-sm-2 control-label" for="input-process-status"><span data-toggle="tooltip" title="<?php echo $entry_photo_gallery_list_help; ?>"><?php echo $entry_photo_gallery_list; ?></span></label>
				<div class="col-sm-10">
					<div class="well well-sm" style="height: 150px; overflow: auto;">
						<?php foreach ($galleries as $gallery) { ?>
						<div class="checkbox">
							<label>
								<?php if (in_array($gallery['gallery_id'], $module['photo_gallery_list'])) { ?>
								<input type="checkbox" name="gallery_module[<?php echo $module_row ?>][photo_gallery_list][]" value="<?php echo $gallery['gallery_id']; ?>" checked="checked" />
								<?php echo $gallery['gallery_data']['gallery_name'][$config_admin_language_id]; ?>
								<?php } else { ?>
								<input type="checkbox" name="gallery_module[<?php echo $module_row ?>][photo_gallery_list][]" value="<?php echo $gallery['gallery_id']; ?>" />
								<?php echo $gallery['gallery_data']['gallery_name'][$config_admin_language_id]; ?>
								<?php } ?>
							</label>
						</div>
						<?php } ?>
					</div>
				<a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a></div>
			</div>

			<div class="form-group s_gallery s_gallery_<?php echo $module_row ?>">
				<label class="col-sm-2 control-label"><?php echo $entry_show_gallery_description; ?></label>
				<div class="col-sm-10">
					<input type="checkbox" class="switcher ui-switcher form-control" data-target="gallery_module_<?php echo $module_row ?>_show_gallery_description" <?php echo ($module['show_gallery_description'] == 1) ? 'checked' : '' ?>>
					<input type="hidden" id="gallery_module_<?php echo $module_row ?>_show_gallery_description" name="gallery_module[<?php echo $module_row ?>][show_gallery_description]" value="<?php echo $module['show_gallery_description'] ?>">
				</div>
			</div>

			<div class="form-group s_gallery s_gallery_<?php echo $module_row ?>">
				<label class="col-sm-2 control-label" for="gallery_module[<?php echo $module_row; ?>][gallery_thumb_image_width]"><?php echo $entry_thumb_size_mod; ?></label>
				<div class="col-sm-10">
					<div class="row">
						<div class="col-sm-6">
							<input type="text" name="gallery_module[<?php echo $module_row; ?>][gallery_thumb_image_width]" value="<?php echo $module['gallery_thumb_image_width']; ?>" placeholder="<?php echo $entry_thumb_size_mod; ?>" id="gallery_module[<?php echo $module_row; ?>][gallery_thumb_image_width]" class="form-control" />
						</div>
						<div class="col-sm-6">
							<input type="text" name="gallery_module[<?php echo $module_row; ?>][gallery_thumb_image_height]" value="<?php echo $module['gallery_thumb_image_height']; ?>" placeholder="<?php echo $entry_thumb_size_mod; ?>" id="gallery_module[<?php echo $module_row; ?>][gallery_thumb_image_height]" class="form-control" />
						</div>
					</div>
				</div>
			</div>

			<div class="form-group s_gallery s_gallery_<?php echo $module_row ?>">
				<label class="col-sm-2 control-label" for="gallery_module[<?php echo $module_row; ?>][gallery_popup_image_width]"><?php echo $entry_popup_size_mod; ?></label>
				<div class="col-sm-10">
					<div class="row">
						<div class="col-sm-6">
							<input type="text" name="gallery_module[<?php echo $module_row; ?>][gallery_popup_image_width]" value="<?php echo $module['gallery_popup_image_width']; ?>" placeholder="<?php echo $entry_popup_size_mod; ?>" id="gallery_module[<?php echo $module_row; ?>][gallery_popup_image_width]" class="form-control" />
						</div>
						<div class="col-sm-6">
							<input type="text" name="gallery_module[<?php echo $module_row; ?>][gallery_popup_image_height]" value="<?php echo $module['gallery_popup_image_height']; ?>" placeholder="<?php echo $entry_popup_size_mod; ?>" id="gallery_module[<?php echo $module_row; ?>][gallery_popup_image_height]" class="form-control" />
						</div>
					</div>
				</div>
			</div>

			<div class="form-group s_gallery s_gallery_<?php echo $module_row ?>">
				<label class="col-sm-2 control-label" for="gallery_module[<?php echo $module_row; ?>][photos_limit]"><span data-toggle="tooltip" title="<?php echo $entry_photos_limit_help; ?>"><?php echo $entry_photos_limit; ?></span></label>
				<div class="col-sm-10">
					<input type="text" name="gallery_module[<?php echo $module_row; ?>][photos_limit]" value="<?php echo $module['photos_limit']; ?>" placeholder="<?php echo $entry_photos_limit; ?>" id="gallery_module[<?php echo $module_row; ?>][photos_limit]" class="form-control" />
				</div>
			</div>

			<div class="form-group s_gallery s_gallery_<?php echo $module_row ?>">
				<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_show_gallery_link_help; ?>"><?php echo $entry_show_gallery_link; ?></span></label>
				<div class="col-sm-10">
					<input type="checkbox" class="switcher ui-switcher form-control" data-target="gallery_module_<?php echo $module_row ?>_show_gallery_link" <?php echo ($module['show_gallery_link'] == 1) ? 'checked' : '' ?>>
					<input type="hidden" id="gallery_module_<?php echo $module_row ?>_show_gallery_link" name="gallery_module[<?php echo $module_row ?>][show_gallery_link]" value="<?php echo $module['show_gallery_link'] ?>">
				</div>
			</div>

			<div class="form-group s_gallery s_gallery_<?php echo $module_row ?>">
				<label class="col-sm-2 control-label" for="gallery_module[<?php echo $module_row ?>][gallery_link_text]"><?php echo $entry_show_gallery_text; ?></label>
				<div class="col-sm-10">
					<div class="row">
				<?php foreach ($languages as $language): ?>
					<div class="col-sm-6">
					<div class="input-group">
					<span class="input-group-addon"><?php echo $language['html_image'] ?></span>
					<input type="text" name="gallery_module[<?php echo $module_row ?>][gallery_link_text][<?php echo $language['language_id']; ?>]" value="<?php echo $module['gallery_link_text'][$language['language_id']]; ?>" placeholder="<?php echo $entry_show_gallery_text; ?>" id="gallery_module[<?php echo $module_row ?>][gallery_link_text][<?php echo $language['language_id']; ?>]" class="form-control" />
					</div>
					</div>
				<?php endforeach ?>
					</div>
				</div>
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
									<?php foreach ($module['bootstrap_grid'] as $prefix => $value): ?>
										<td class="text-center">
											<div class="btn-group" role="group" data-toggle="buttons">
												<?php foreach ($bootstrap_grid_to_cols as $bcol => $col): ?>
												<label class="btn btn-sm<?php echo ($bcol == 0) ? ' btn-success' : ' btn-primary' ?> <? echo ($value == $bcol) ? ' active' : '' ?>">
													<input type="radio" name="gallery_module[<?php echo $module_row ?>][bootstrap_grid][<?php echo $prefix ?>]" value="<?php echo $bcol ?>" autocomplete="off" <?php echo ($value == $bcol) ? 'checked' : '' ?>><?php echo ($bcol == 0) ? 'auto' : $col ?>
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
				</div><!-- <div id="tabs" class="vtabs">  -->
				<?php $module_row++; ?>
				<?php } ?>
				</div>
				</div>
				</div>
				</form>
			</div>
		</div>
	</div>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

// $(document).ready(function() {

	$('#form-modules #tabs').on('click', 'a', function(event) {
		event.preventDefault();
		event.stopPropagation();

		if ($(this).attr('id') === 'add_module_btn') {
			addModule();
			return;
		}

		let targetId = $(this).attr('href');
		let $selected = $(this).parent('#tabs').find('a.selected');

		if ($selected.length > 0) {
			$($selected.attr('href')).hide();
			$selected.removeClass('selected');
		}

		if (targetId !== '#') {
			$(this).addClass('selected');
			$(targetId).show();
		}
	});

	<?php if (isset($selected_module_id)): ?>
		$('a.tab_module_<?php echo $selected_module_id ?>').click();
	<?php else: ?>
		$('#a_tab_module_1').click();
	<?php endif ?>

	$.switcher('input[type=checkbox].switcher');

	$('#form-modules').delegate('.switcher', 'change', function(e) {
		$('#' + $(e.target).data('target')).val(Number($(e.target).prop('checked')));
	});

	$('#form-modules').delegate('.name-changer', 'change, paste, keyup', function(event) {
		event.preventDefault();
		var row_id = $(this).attr('data-id');
		var remove_btn = "<i class=\"fa fa-minus-square fa-fw\" onclick=\"$('#tab_module_"+ row_id +", #a_tab_module_"+ row_id +"').remove(); $('#tabs a.tab_btn:last').click();return false;\"></i>";
		$('#a_tab_module_' + row_id).html($(this).val() + ' ' + remove_btn);
	});

	$('#form-modules').delegate('.type-changer', 'change', function(){
		var m_row = $(this).attr('data-id');
		var m_val = parseInt($(this).val());
		switch (m_val){
			case 0:
				$('.s_galleries.s_galleries_'+m_row).show();
				$('.s_gallery.s_gallery_'+m_row).hide();
			break;
			case 1:
				$('.s_galleries.s_galleries_'+m_row).hide();
				$('.s_gallery.s_gallery_'+m_row).show();
			break;
		}
	});
	$('.type-changer').trigger('change');

// });
function addModule() {
	html ='<div id="tab_module_' + module_row + '" class="vtabs-content">';

	html +='<div class="tab-content">';
	html +='<div class="form-group required">';
	html +='<label class="col-sm-2 control-label" for="gallery_module_' + module_row + '_name"><span data-toggle="tooltip" title="<?php echo $entry_module_name_help; ?>"><?php echo $entry_module_name; ?></span></label>';
	html +='<div class="col-sm-10">';
	html +='<input type="text"  data-id="' + module_row + '" name="gallery_module[' + module_row + '][name]" value="<?php echo $text_new_module_name ?> ' + module_row + '" placeholder="<?php echo $entry_module_name; ?>" id="gallery_module_' + module_row + '_name" class="form-control name-changer" />';
	html +='</div>';
	html +='</div>';

	html +='<div class="form-group ">';
	html +='<label class="col-sm-2 control-label" for="gallery_module_' + module_row + '_header_<?php echo $language['language_id']; ?>"><?php echo $entry_module_header; ?></label>';
	html +='<div class="col-sm-10">';
	<?php foreach ($languages as $language) { ?>
	html +='<div class="row">';
	html +='<div class="col-sm-10">';
	html +='<div class="input-group">';
	html +='<span class="input-group-addon"><?php echo $language['html_image'] ?></span>';
	html +='<input type="text" data-id="' + module_row + '" name="gallery_module[' + module_row + '][header][<?php echo $language['language_id']; ?>]" value="<?php echo $text_new_module_name; ?> ' + module_row + '" placeholder="<?php echo $entry_module_header; ?>" id="gallery_module[' + module_row + '][header][<?php echo $language['language_id']; ?>]" class="form-control" />';
	html +='</div>';
	html +='</div>';
	html +='<div class="col-sm-2">';
	html +='<input type="checkbox" class="switcher ui-switcher form-control" data-target="gallery_module_' + module_row + '_show_header_<?php echo $language['language_id']; ?>" checked>';
	html +='<input type="hidden" id="gallery_module_' + module_row + '_show_header_<?php echo $language['language_id']; ?>" name="gallery_module[' + module_row + '][show_header][<?php echo $language['language_id']; ?>]" value="1">';
	html +='</div>';
	html +='</div>';
	html +='</br>';
	<?php } ?>
	html +='</div>';
	html +='</div>';

	html +='<div class="form-group ">';
	html +='<label class="col-sm-2 control-label" for="gallery_module_' + module_row + '_status"><?php echo $entry_status; ?></label>';
	html +='<div class="col-sm-10">';
	html +='<input type="checkbox" class="switcher ui-switcher form-control" data-target="gallery_module_' + module_row + '_status" checked>';
	html +='<input type="hidden" id="gallery_module_' + module_row + '_status" name="gallery_module[' + module_row + '][status]" value="1">';
	html +='</div>';
	html +='</div>';

	html +='<div class="form-group ">';
	html +='<label class="col-sm-2 control-label" for="gallery_module_' + module_row + '_module_type"><?php echo $entry_module_type; ?></label>';
	html +='<div class="col-sm-10">';
	html +='<select name="gallery_module[' + module_row + '][module_type]" data-id="' + module_row + '" id="gallery_module_' + module_row + '_module_type" class="form-control type-changer">';
	<?php foreach ($module_types as $key => $module_type) { ?>
	html +='<option value="<?php echo $key; ?>"><?php echo $module_type; ?></option>';
	<?php } ?>
	html +='</select>';
	html +='</div>';
	html +='</div>';

	html +='<div class="form-group s_galleries s_galleries_' + module_row + '">';
	html +='<label class="col-sm-2 control-label" for="input-process-status"><span data-toggle="tooltip" title="<?php echo $entry_photo_gallery_list_help; ?>"><?php echo $entry_photo_gallery_list; ?></span></label>';
	html +='<div class="col-sm-10">';
	html +='<div class="well well-sm" style="height: 150px; overflow: auto;">';
	<?php foreach ($galleries as $gallery) { ?>
	html +='<div class="checkbox">';
	html +='<label>';
	html +='<input type="checkbox" name="gallery_module[' + module_row + '][gallery_list][]" value="<?php echo $gallery['gallery_id']; ?>" />';
	html +='&nbsp;<?php echo $gallery['gallery_data']['gallery_name'][$config_admin_language_id]; ?>';
	html +='</label>';
	html +='</div>';
	<?php } ?>
	html +='</div>';
	html +='<a onclick="$(this).parent().find(\':checkbox\').prop(\'checked\', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(\':checkbox\').prop(\'checked\', false);"><?php echo $text_unselect_all; ?></a></div>';
	html +='</div>';

	html +='<div class="form-group s_galleries s_galleries_' + module_row + '">';
	html +='<label class="col-sm-2 control-label"><?php echo $entry_show_covers; ?></label>';
	html +='<div class="col-sm-10">';
	html +='<input type="checkbox" class="switcher ui-switcher form-control" data-target="gallery_module_' + module_row + '_show_covers" checked>';
	html +='<input type="hidden" id="gallery_module_' + module_row + '_show_covers" name="gallery_module[' + module_row + '][show_covers]" value="1">';
	html +='</div>';
	html +='</div>';

	html +='<div class="form-group s_galleries s_galleries_' + module_row + '">';
	html +='<label class="col-sm-2 control-label" for="gallery_module[' + module_row + '][cover_image_width]"><?php echo $entry_cover_size; ?></label>';
	html +='<div class="col-sm-10">';
	html +='<div class="row">';
	html +='<div class="col-sm-6">';
	html +='<input type="text" name="gallery_module[' + module_row + '][cover_image_width]" value="228" placeholder="<?php echo $entry_cover_size; ?>" id="gallery_module[' + module_row + '][cover_image_width]" class="form-control" />';
	html +='</div>';
	html +='<div class="col-sm-6">';
	html +='<input type="text" name="gallery_module[' + module_row + '][cover_image_height]" value="228" placeholder="<?php echo $entry_cover_size; ?>" id="gallery_module[' + module_row + '][cover_image_height]" class="form-control" />';
	html +='</div>';
	html +='</div>';
	html +='</div>';
	html +='</div>';

	html +='<div class="form-group s_galleries s_galleries_' + module_row + '">';
	html +='<label class="col-sm-2 control-label"><?php echo $entry_show_counter; ?></label>';
	html +='<div class="col-sm-10">';
	html +='<input type="checkbox" class="switcher ui-switcher form-control" data-target="gallery_module_' + module_row + '_show_counter" checked>';
	html +='<input type="hidden" id="gallery_module_' + module_row + '_show_counter" name="gallery_module[' + module_row + '][show_counter]" value="1">';
	html +='</div>';
	html +='</div>';

	html +='<div class="form-group s_galleries s_galleries_' + module_row + '">';
	html +='<label class="col-sm-2 control-label"><?php echo $entry_show_gallery_galleries_link; ?></label>';
	html +='<div class="col-sm-10">';
	html +='<input type="checkbox" class="switcher ui-switcher form-control" data-target="gallery_module_' + module_row + '_show_gallery_galleries_link" checked>';
	html +='<input type="hidden" id="gallery_module_' + module_row + '_show_gallery_galleries_link" name="gallery_module[' + module_row + '][show_gallery_galleries_link]" value="1">';
	html +='</div>';
	html +='</div>';

	html +='<div class="form-group s_galleries s_galleries_' + module_row + '">';
	html +='<label class="col-sm-2 control-label" for="gallery_module[' + module_row + '][gallery_galleries_link_text]"><?php echo $entry_show_gallery_text; ?></label>';
	html +='<div class="col-sm-10"><div class="row">';
	<?php foreach ($languages as $language): ?>
	html +='<div class="col-sm-6">';
	html +='<div class="input-group">';
	html +='<span class="input-group-addon"><?php echo $language['html_image'] ?></span>';
	html +='<input type="text" name="gallery_module[' + module_row + '][gallery_galleries_link_text][<?php echo $language['language_id']; ?>]" value="<?php echo $text_show_galleries_text; ?>" placeholder="<?php echo $entry_show_gallery_text; ?>" id="gallery_module[' + module_row + '][gallery_galleries_link_text][<?php echo $language['language_id']; ?>]" class="form-control" />';
	html +='</div>';
	html +='</div>';
	<?php endforeach ?>
	html +='</div></div>';
	html +='</div>';

	html +='<div class="form-group s_gallery s_gallery_' + module_row + '">';
	html +='<label class="col-sm-2 control-label" for="input-process-status"><span data-toggle="tooltip" title="<?php echo $entry_photo_gallery_list_help; ?>"><?php echo $entry_photo_gallery_list; ?></span></label>';
	html +='<div class="col-sm-10">';
	html +='<div class="well well-sm" style="height: 150px; overflow: auto;">';
	<?php foreach ($galleries as $gallery) { ?>
	html +='<div class="checkbox">';
	html +='<label>';
	html +='<input type="checkbox" name="gallery_module[' + module_row + '][photo_gallery_list][]" value="<?php echo $gallery['gallery_id']; ?>" />';
	html +='&nbsp;<?php echo $gallery['gallery_data']['gallery_name'][$config_admin_language_id]; ?>';
	html +='</label>';
	html +='</div>';
	<?php } ?>
	html +='</div>';
	html +='<a onclick="$(this).parent().find(\':checkbox\').prop(\'checked\', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(\':checkbox\').prop(\'checked\', false);"><?php echo $text_unselect_all; ?></a></div>';
	html +='</div>';

	html +='<div class="form-group s_gallery s_gallery_' + module_row + '">';
	html +='<label class="col-sm-2 control-label"><?php echo $entry_show_gallery_description; ?></label>';
	html +='<div class="col-sm-10">';
	html +='<input type="checkbox" class="switcher ui-switcher form-control" data-target="gallery_module_' + module_row + '_show_gallery_description" checked>';
	html +='<input type="hidden" id="gallery_module_' + module_row + '_show_gallery_description" name="gallery_module[' + module_row + '][show_gallery_description]" value="1">';
	html +='</div>';
	html +='</div>';

	html +='<div class="form-group s_gallery s_gallery_' + module_row + '">';
	html +='<label class="col-sm-2 control-label" for="gallery_module[' + module_row + '][gallery_thumb_image_width]"><?php echo $entry_thumb_size_mod; ?></label>';
	html +='<div class="col-sm-10">';
	html +='<div class="row">';
	html +='<div class="col-sm-6">';
	html +='<input type="text" name="gallery_module[' + module_row + '][gallery_thumb_image_width]" value="228" placeholder="<?php echo $entry_thumb_size_mod; ?>" id="gallery_module[' + module_row + '][gallery_thumb_image_width]" class="form-control" />';
	html +='</div>';
	html +='<div class="col-sm-6">';
	html +='<input type="text" name="gallery_module[' + module_row + '][gallery_thumb_image_height]" value="228" placeholder="<?php echo $entry_thumb_size_mod; ?>" id="gallery_module[' + module_row + '][gallery_thumb_image_height]" class="form-control" />';
	html +='</div>';
	html +='</div>';
	html +='</div>';
	html +='</div>';

	html +='<div class="form-group s_gallery s_gallery_' + module_row + '">';
	html +='<label class="col-sm-2 control-label" for="gallery_module[' + module_row + '][gallery_popup_image_width]"><?php echo $entry_popup_size_mod; ?></label>';
	html +='<div class="col-sm-10">';
	html +='<div class="row">';
	html +='<div class="col-sm-6">';
	html +='<input type="text" name="gallery_module[' + module_row + '][gallery_popup_image_width]" value="500" placeholder="<?php echo $entry_popup_size_mod; ?>" id="gallery_module[' + module_row + '][gallery_popup_image_width]" class="form-control" />';
	html +='</div>';
	html +='<div class="col-sm-6">';
	html +='<input type="text" name="gallery_module[' + module_row + '][gallery_popup_image_height]" value="500" placeholder="<?php echo $entry_popup_size_mod; ?>" id="gallery_module[' + module_row + '][gallery_popup_image_height]" class="form-control" />';
	html +='</div>';
	html +='</div>';
	html +='</div>';
	html +='</div>';

	html +='<div class="form-group s_gallery s_gallery_' + module_row + '">';
	html +='<label class="col-sm-2 control-label" for="gallery_module[' + module_row + '][photos_limit]"><span data-toggle="tooltip" title="<?php echo $entry_photos_limit_help; ?>"><?php echo $entry_photos_limit; ?></span></label>';
	html +='<div class="col-sm-10">';
	html +='<input type="text" name="gallery_module[' + module_row + '][photos_limit]" value="8" placeholder="<?php echo $entry_photos_limit; ?>" id="gallery_module[' + module_row + '][photos_limit]" class="form-control" />';
	html +='</div>';
	html +='</div>';

	html +='<div class="form-group s_gallery s_gallery_' + module_row + '">';
	html +='<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_show_gallery_link_help; ?>"><?php echo $entry_show_gallery_link; ?></span></label>';
	html +='<div class="col-sm-10">';
	html +='<input type="checkbox" class="switcher ui-switcher form-control" data-target="gallery_module_' + module_row + '_show_gallery_link" checked>';
	html +='<input type="hidden" id="gallery_module_' + module_row + '_show_gallery_link" name="gallery_module[' + module_row + '][show_gallery_link]" value="1">';
	html +='</div>';
	html +='</div>';

	html +='<div class="form-group s_gallery s_gallery_' + module_row + '">';
		html +='<label class="col-sm-2 control-label" for="gallery_module[' + module_row + '][gallery_link_text]"><?php echo $entry_show_gallery_text; ?></label>';
		html +='<div class="col-sm-10">';
			html +='<div class="row">';
			<?php foreach ($languages as $language): ?>
			html +='<div class="col-sm-6">';
				html +='<div class="input-group">';
					html +='<span class="input-group-addon"><?php echo $language['html_image'] ?></span>';
					html +='<input type="text" name="gallery_module[' + module_row + '][gallery_link_text][<?php echo $language['language_id']; ?>]" value="<?php echo $text_show_gallery_text; ?>" placeholder="<?php echo $entry_show_gallery_text; ?>" id="gallery_module[' + module_row + '][gallery_link_text][<?php echo $language['language_id']; ?>]" class="form-control" />';
				html +='</div>';
			html +='</div>';
			<?php endforeach ?>
			html +='</div>';
		html +='</div>';
	html +='</div>';

	html +='<fieldset><legend><?php echo $text_grid_settings ?></legend>';

		html +='<div class="form-group">';
		html +='<label class="col-sm-2 control-label" for="input-grid_columns_count"><?php echo $entry_bootstrap_grid ?></label>';
		html +='<div class="col-sm-10">';
			html +='<table class="table table-bordered table-striped bootstrap_grid_table">';
				html +='<tbody>';
					html +='<tr>';
						html +='<td class="text-center"><img src="view/image/bootstrap_grid/extra_small.png" class="img-responsive" alt=""><br>';
							html +='Extra small [None(auto)]</td>';
						html +='<td class="text-center"><img src="view/image/bootstrap_grid/small.png" class="img-responsive" alt=""><br>';
							html +='Small [750px]</td>';
						html +='<td class="text-center"><img src="view/image/bootstrap_grid/medium.png" class="img-responsive" alt=""><br>';
							html +='Medium [970px]</td>';
						html +='<td class="text-center"><img src="view/image/bootstrap_grid/large.png" class="img-responsive" alt=""><br>';
							html +='Large [1170px]</td>';
					html +='</tr>';
					html +='<tr class="values" style="background:#efefef;">';
						<?php foreach ($config_gallery_bootstrap_grid as $prefix => $value): ?>
							html +='<td class="text-center">';
								html +='<div class="btn-group" role="group" data-toggle="buttons">';
									<?php foreach ($bootstrap_grid_to_cols as $bcol => $col): ?>
									html +='<label class="btn btn-sm<?php echo ($bcol == 0) ? ' btn-success' : ' btn-primary' ?> <? echo ($value == $bcol) ? ' active' : '' ?>">';
										html +='<input type="radio" name="gallery_module[' + module_row + '][bootstrap_grid][<?php echo $prefix ?>]" value="<?php echo $bcol ?>" autocomplete="off" <?php echo ($value == $bcol) ? 'checked' : '' ?>><?php echo ($bcol == 0) ? 'auto' : $col ?>';
									html +='</label>';
									<?php endforeach ?>
								html +='</div>';
							html +='</td>';
						<?php endforeach ?>
					html +='</tr>';
				html +='</tbody>';
			html +='</table>';
		html +='</div>';
		html +='</div>';

	html +='</fieldset>';

	html +='</div>';

	html +='</div>';

	$('#module_wrapper').append(html);
	tab_data = '<a href="#tab_module_' + module_row + '" id="a_tab_module_' + module_row + '" class="tab_btn tab_module_' + module_row + '">';
	tab_data += '<?php echo $text_new_module_name ?> ' + module_row + ' ';
	tab_data += '<i class="fa fa-minus-square fa-fw" onclick="$(\'#tab_module_' + module_row + ', #a_tab_module_' + module_row + '\').remove();$(\'#tabs a.tab_btn:last\').trigger(\'click\'); return false;"></i>';
	tab_data += '</a>';
	$('#add_module_btn').before(tab_data);
	$('#tabs').find('a.tab_module_' + module_row).trigger('click');
	
	$.switcher($('#tab_module_' + module_row).find('input[type=checkbox].switcher'));
	$('#tab_module_' + module_row).find('.type-changer').trigger('change');

	module_row++;
}
//--></script>
<?php echo $footer; ?>