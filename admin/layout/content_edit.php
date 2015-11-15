<!-- Tree -->
<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/css/tree.css">
<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/tree.js"></script>
<!-- custom js page -->
<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/content.js"></script>
<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/css/content.css">

<div class="row" >

	<?php if(hm_get('mes')=='edit_success'){ ?>
		<div class="alert alert-success" role="alert"><?php echo _('Đã lưu lại chỉnh sửa'); ?></div>
	<?php } ?>
	
	<?php if(hm_get('mes')=='add_success'){ ?>
		<div class="alert alert-success" role="alert"><?php echo _('Đã đăng bài mới'); ?></div>
	<?php } ?>
	
	<div class="col-md-12">
		<h1 class="page_title"><?php echo $args['content_name']; ?></h1>
	</div>
	<form action="?run=content_ajax.php&id=<?php echo hm_get('id'); ?>&action=edit" method="post" class="ajaxForm ajaxFormcontentEdit">
		
		<div class="col-md-9 admin_mainbar">

			<p class="page_action"><?php echo $args['edit_item']; ?></p>
			
			<div class="row admin_mainbar_box">
			
				<div class="media_btn_wap form-group">
					<button type="button" class="btn btn-default media_btn" data-toggle="modal" data-target="#media_box_modal">
						<span class="glyphicon glyphicon-picture"></span> <?php echo _('Thư viện'); ?>
					</button>
					<a href="<?php echo request_uri('type=content&id='.hm_get('id')); ?>" class="btn btn-default media_btn" target="_blank" >
						<span class="glyphicon glyphicon-eye-open"></span> <?php echo _('Xem'); ?>
					</a>
				</div>
				
				<div class="list-form-input">
					<?php
						$fields=$args['content_field'];
						$fields_val=$args_con['field'];
						foreach($fields as $field){
							
							if(isset($fields_val[$field['name']])){
								$field['default_value'] = $fields_val[$field['name']];
							}else{
								$field['default_value'] = NULL;
							}
								
							$field['object_id'] = hm_get('id');
							$field['object_type'] = 'content';
							build_input_form($field);
							
						}
					?>
				</div>
			
			</div>
			
			<?php
			content_box(array('content_key'=>$args['content_key'],'position'=>'left'));
			?>

		</div>
		
		<div class="col-md-3 admin_sidebar">
			
			<div class="row admin_sidebar_box">
				<p class="admin_sidebar_box_title"><?php echo _('Thông tin'); ?></p>
				<?php
					$field_array['default_value']=$fields_val['status'];
					$field_array['input_type']='select';
					$field_array['name']='status';
					$field_array['nice_name']='<span class="glyphicon glyphicon-pushpin"></span> '._('Tình trạng');
					$field_array['input_option']=array  (
														array('value'=>'public','label'=>_('Công khai')),
														array('value'=>'draft','label'=>_('Bản nháp')),
														array('value'=>'password','label'=>_('Bảo vệ bằng mật khẩu')),
														);
					build_input_form($field_array);
					unset($field_array);
				?>
				<div class="form-group input_password_content" style="display:none">
					<input name="password" type="password" class="form-control" placeholder="<?php echo _('Nhập mật khẩu để xem bài này'); ?>" value="">
				</div>
				<div class="form-group">
					<label for="status">
						<span class="glyphicon glyphicon-calendar"></span> <?php echo _('Thời gian'); ?>
					</label>
					<div class="change_content_time">
						<?php 
							if(isset($fields_val['day'])) $field_array['default_value']['day'] = $fields_val['day'];
							if(isset($fields_val['month'])) $field_array['default_value']['month'] = $fields_val['month'];
							if(isset($fields_val['year'])) $field_array['default_value']['year'] = $fields_val['year'];
							if(isset($fields_val['hour'])) $field_array['default_value']['hour'] = $fields_val['hour'];
							if(isset($fields_val['minute'])) $field_array['default_value']['minute'] = $fields_val['minute'];
							input_time($field_array); 
							unset($field_array);
						?>
					</div>
					<p class="input_description"><?php echo _('Bạn có thể hẹn giờ đăng bài bằng cách chọn thời gian đăng bài trong tương lai'); ?></p>
				</div>
				<div class="form-group">
					<label for="revision">
						<a href="?run=revision.php&id=<?php echo hm_get('id'); ?>">
							<span class="glyphicon glyphicon-repeat"></span> <?php echo _('Lịch sử thay đổi').' ('.content_number_revision(hm_get('id')).') '; ?>
						</a>
					</label>
				</div>
				<?php
				if($args['chapter']){
				?>
				<div class="form-group">
					<label for="chapter">
						<a href="?run=chapter.php&id=<?php echo hm_get('id'); ?>">
							<span class="glyphicon glyphicon-repeat"></span> <?php echo _('Danh sách chapter').' ('.content_number_chapter(hm_get('id')).') '; ?>
						</a>
					</label>
				</div>
				<?php
				}
				?>
			</div>
			
			<div class="row admin_sidebar_box">
				<p class="admin_sidebar_box_title"><?php echo $args_tax['taxonomy_name']; ?></p>
				<ul class="taxnomy_list tree">
					<?php 
						$field_array['key']=$args['taxonomy_key'];
						$field_array['object_id'] = hm_get('id');
						taxonomy_checkbox_list($field_array); 
						unset($field_array);
					?>
				</ul>
			</div>
			
			<div class="row admin_sidebar_box">
				<p class="admin_sidebar_box_title"><?php echo _('Ảnh đại diện'); ?></p>
				<?php
					$field_array['name']='content_thumbnail';	
					$field_array['label']='Chọn ảnh đại diện';
					$field_array['imageonly']=TRUE;
					if(isset($fields_val['content_thumbnail'])){$field_array['default_value']=$fields_val['content_thumbnail'];}
					media_file_input($field_array);
					unset($field_array);
				?>
			</div>
			
			<div class="row admin_sidebar_box">
				<p class="admin_sidebar_box_title"><?php echo _('Từ khóa (cách nhau bằng dấu phẩy)'); ?></p>
				<div class="form-group">
					<input name="tags" type="text" class="form-control" placeholder="" value="<?php if(isset($fields_val['tags'])) echo $fields_val['tags']; ?>">
				</div>
			</div>
			
			<?php
			content_box(array('content_key'=>$args['content_key'],'position'=>'right'));
			?>
			
			<div class="row admin_sidebar_box">
				<p class="admin_sidebar_box_title"><?php echo _('Tác vụ'); ?></p>
				<div class="form-group">
					<button name="submit" type="submit" class="btn btn-primary"><?php echo $args['edit_item']; ?></button>
				</div>
			</div>
				
		</div>
		
	
	</form>
	
</div>

<!-- Modal -->
<div class="modal fade" id="media_box_modal" tabindex="-1" role="dialog" aria-labelledby="media_box_modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="media_box_modalLabel"><?php echo _('Thư viện'); ?></h4>
            </div>
            <div class="modal-body media_box">
				<?php win8_loading(); ?>
            </div>
        </div>
    </div>
</div>