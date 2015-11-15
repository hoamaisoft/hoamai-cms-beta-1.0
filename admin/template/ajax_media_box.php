<?php
/**
 * File template hiển thị media box
 */
if ( ! defined('BASEPATH')) exit('403');
$media_group_id = media_group_id();
?>
<!-- custom js page -->
<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/perfect-scrollbar/perfect-scrollbar.min.css">

<!-- tree_media -->
<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/css/tree_media.css">

<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/media_box.js"></script>
<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/css/media_box.css">


<div class="row media_box_ajax_load">

	<form action="?run=media_ajax.php&action=add_media" method="post" enctype="multipart/form-data" class="ajaxFormMedia">
	
			
		<div class="col-md-12 media_error" style="display:none">
			<div class="alert alert-danger" role="alert">
			  
			</div>
		</div>
		
		<div class="col-md-12">
			<div class="progress media-progress" style="display:none">
				<div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
					0%
				</div>
			</div>
			<div id="status"></div>
		</div>
		<div class="col-md-3">
			<div class="row">
				<input class="new_media_group col-md-9" name="name" placeholder="<?php echo _('Tạo nhóm thư viện mới'); ?>" />
				<button name="submit" type="button" class="btn btn-default col-md-3 submit_new_media_group"><?php echo _('Thêm'); ?></button>
			</div>
			<div class="row">
				<ul class="media_tree media_file_scroll tree_media">
					<li data-id="0" data-folder="anh-noi-dung" class="media_tree_item media_tree_item_0" >
						<label class="radio-inline"><input type="radio" name="media_group" value="0" checked="checked" > <?php echo _('Tất cả'); ?></label>
						<ul class="media_tree_sub_group media_tree_sub_group_of_0 tree">
							<?php media_group_tree(); ?>
						</ul>
					</li>
				</ul>
			</div>
		</div>
		<div class="col-md-9">
		
			<div class="top_media_box">
				<div class="input-group media-preview">
					<input type="text" class="form-control media-preview-filename" disabled="disabled"> 
					<span class="input-group-btn">
						<button type="submit" class="btn btn-default media-submit" style="display:none;">
							<span class="glyphicon glyphicon-cloud-upload"></span> <?php echo _('Tải lên'); ?>
						</button>
						<button type="button" class="btn btn-default media-preview-clear" style="display:none;">
							<span class="glyphicon glyphicon-remove"></span> <?php echo _('Hủy'); ?>
						</button>
						<div class="btn btn-default media-preview-input">
							<span class="glyphicon glyphicon-folder-open"></span>
							<span class="media-preview-input-title"><?php echo _('Chọn file'); ?></span>
							<input type="file" name="file[]" multiple />
						</div>
					</span>
				</div>
			</div>
			
			<div class="content_media_box">
				
				<div class="row">
					<ol class="breadcrumb">
					<?php 
					echo '<li data-id="0">'._('Tất cả').'</li>';
					$bre = breadcrumb_folder($media_group_id); 
					foreach($bre as $item){
						echo '<li data-id="'.$item['id'].'">'.$item['name'].'</li>';
					}
					?>
					</ol>
				</div>
			
				<div class="row">
					<div class="col-md-12 media_file_show media_file_scroll" >
						<ul>
							<?php show_media_file(); ?>
						</ul>
					</div>
					<div class="col-md-3 media_file_info" style="display:none" >
						<div class="form-group">
							<label for="file_dst_name"><?php echo _('ID file'); ?></label>
							<input type="text" class="form-control" id="file_id" value="">
						</div>
						<div class="form-group">
							<label for="file_dst_name"><?php echo _('Tên file'); ?></label>
							<input type="text" class="form-control" id="file_dst_name" value="">
						</div>
						<div class="form-group">
							<label for="file_src_name_ext"><?php echo _('Phần mở rộng'); ?></label>
							<input type="text" class="form-control" id="file_src_name_ext" value="">
						</div>
						<div class="form-group">
							<label for="file_src_mime"><?php echo _('Loại file'); ?></label>
							<input type="text" class="form-control" id="file_src_mime" value="">
						</div>
						<div class="form-group">
							<label for="file_src_size"><?php echo _('Dung lượng'); ?></label>
							<input type="text" class="form-control" id="file_src_size" value="">
						</div>
						<div class="form-group">
							<label for="file_src"><?php echo _('Đường dẫn'); ?></label>
							<input type="text" class="form-control" id="file_src" value="">
						</div>
						<div class="row">
							<input type="checkbox" name="checkbox_delete_media_file" class="checkbox_delete_media_file" />
							<span data-id="" class="delete_media_file"><?php echo _('Xóa vĩnh viễn'); ?></span>
						</div>
						
						<div class="row">
							<input type="hidden" id="media_query" value="<?php echo $_SERVER['QUERY_STRING']; ?>" />
							<?php
							if(hm_get('use_media_file') != NULL){
							?>
							<input type="hidden" id="group_id" value="<?php echo $media_group_id; ?>" />
							<input type="hidden" id="group_parent" value="<?php echo hm_get('group_parent','0'); ?>" />
							<input type="hidden" id="use_media_file" value="<?php echo hm_get('use_media_file'); ?>" />
							<input type="hidden" id="multi" value="<?php echo hm_get('multi'); ?>" />
							<input type="hidden" id="imageonly" value="<?php echo hm_get('imageonly'); ?>" />
							<button type="button" class="btn btn-default use_media_file_insert" use_media_file="<?php echo hm_get('use_media_file'); ?>" multi="<?php echo hm_get('multi'); ?>" imageonly="<?php echo hm_get('imageonly'); ?>">
								<span class="glyphicon glyphicon-pencil"></span><?php echo _('Sử dụng'); ?>
							</button>
							<?php
							}
							?>
							<button type="button" class="btn btn-default hide_media_file_info">
								<span class="glyphicon glyphicon-remove"></span> <?php echo _('Ẩn'); ?>
							</button>
						</div>
						
					</div>
				</div>
			</div>

		</div>
	</form>
	
	<div class="new_dir_input_box">
		<span class="close-icon"></span>
		<input type="text" class="new_dir_input_name col-md-9" placeholder="Tên thư mục">
		<button class="new_dir_input_button btn btn-default col-md-3 "><?php echo _('Thêm'); ?></button>
	</div>
	
	<div class="rename_dir_input_box">
		<span class="close-icon"></span>
		<input type="text" class="rename_dir_input_name col-md-9" placeholder="Nhập tên mới">
		<button class="rename_dir_input_button btn btn-default col-md-3 "><?php echo _('Đổi tên'); ?></button>
	</div>
	
</div>

