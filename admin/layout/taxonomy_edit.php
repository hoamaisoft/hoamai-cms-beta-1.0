<!-- Tree -->
<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/css/tree.css">
<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/tree.js"></script>
<!-- custom js page -->
<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/taxonomy.js"></script>
<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/css/taxonomy.css">

<div class="row" >

	<?php if(hm_get('mes')=='edit_success'){ ?>
		<div class="alert alert-success" role="alert"><?php echo _('Đã lưu lại chỉnh sửa'); ?></div>
	<?php } ?>
	
	<div class="col-md-12">
		<h1 class="page_title"><?php echo $args['taxonomy_name']; ?></h1>
	</div>
	<form action="?run=taxonomy_ajax.php&id=<?php echo hm_get('id'); ?>&action=edit" method="post" class="ajaxForm ajaxFormtaxonomyEdit">
		
		<div class="col-md-9 admin_mainbar">

			<p class="page_action"><?php echo $args['edit_item']; ?></p>
			
			<div class="row admin_mainbar_box">

				<div class="list-form-input">
					<?php
						$fields=$args['taxonomy_field'];
						$fields_val=$args_tax['field'];
						
						foreach($fields as $field){
							
							if(isset($fields_val[$field['name']])){
								$field['default_value'] = $fields_val[$field['name']];
							}else{
								$field['default_value'] = NULL;
							}
								
							$field['object_id'] = hm_get('id');
							$field['object_type'] = 'taxonomy';
							build_input_form($field);
							
						}
					?>
				</div>
			
			</div>
			<?php
			taxonomy_box(array('taxonomy_key'=>$args['taxonomy_key'],'position'=>'left'));
			?>
		</div>
		
		<div class="col-md-3 admin_sidebar">
		
			<div class="row admin_sidebar_box">
				<p class="admin_sidebar_box_title"><?php echo _('Ảnh đại diện'); ?></p>
				<?php
					$field_array['name']='content_thumbnail';	
					$field_array['label']='Chọn ảnh đại diện';
					if(isset($fields_val['content_thumbnail'])){$field_array['default_value']=$fields_val['content_thumbnail'];}
					media_file_input($field_array);
					unset($field_array);
				?>
			</div>
			
			<?php
			taxonomy_box(array('taxonomy_key'=>$args['taxonomy_key'],'position'=>'right'));
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