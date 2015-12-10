<!-- custom js page -->
<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/user.js"></script>
<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/css/user.css">

<div class="row" >
	<div class="col-md-12">
		<h1 class="page_title"><?php echo _('Thành viên'); ?></h1>
	</div>
	<form action="?run=user_ajax.php&action=add" method="post" class="ajaxForm ajaxFormuserAdd">
		
		<div class="col-md-6 admin_mainbar">

			<p class="page_action"><?php echo _('Thông tin tài khoản'); ?></p>
			
			<div class="row add_user_noti">
			
			</div>
			<div class="row admin_mainbar_box">
			
				<div class="list-form-input">
					<?php
					$args=array(
								'input_type'=>'text',
								'name'=>'user_login',
								'nice_name'=>_('Tên đăng nhập'),
								'description'=>_('Tên tài khoản dùng để đăng nhập'),
								'required'=>TRUE,
								);
					build_input_form($args);
					
					
					$args=array(
								'input_type'=>'password',
								'name'=>'password',
								'nice_name'=>_('Mật khẩu'),
								'description'=>_('Đặt mật khẩu phức tạp để bảo vệ tài khoản của bạn'),
								'required'=>TRUE,
								);
					build_input_form($args);
					
					$args=array(
								'input_type'=>'password',
								'name'=>'password2',
								'nice_name'=>_('Nhập lại mật khẩu'),
								'description'=>_('Nhập lại mật khẩu lần nữa trùng với mật khẩu đã nhập ở trên'),
								'required'=>TRUE,
								);
					build_input_form($args);
					
					$args=array(
								'input_type'=>'text',
								'name'=>'nicename',
								'nice_name'=>_('Tên hiển thị'),
								'description'=>_('Tên đại diện cho bạn khi hiển trị trên website'),
								'required'=>TRUE,
								);
					build_input_form($args);
					
					$args=array(
								'input_type'=>'email',
								'name'=>'user_email',
								'nice_name'=>_('Email'),
								'description'=>_('Email sẽ dùng để lấy lại mật khẩu'),
								'required'=>TRUE,
								);
					build_input_form($args);
					
					$args=array(
								'input_type'=>'select',
								'name'=>'userrole',
								'nice_name'=>_('Quyền hạn'),
								'description'=>_('Quyền hạn của thành viên'),
								'required'=>TRUE,
								'input_option'=>array(
													array('value'=>1,'label'=>_('Administrator')),
													array('value'=>2,'label'=>_('Quản trị viên')),
													array('value'=>3,'label'=>_('Biên tập viên')),
													array('value'=>4,'label'=>_('Thành viên')),
													),
								);
					build_input_form($args);
					?>
					
					<div class="form-group">
						<button name="submit" type="submit" class="btn btn-primary"><?php echo _('Thêm thành viên'); ?></button>
					</div>
					
				</div>
			
			</div>

		</div>
		
		
		<div class="col-md-6 admin_mainbar">

			<p class="page_action"><?php echo _('Thông tin cá nhân'); ?></p>

			<div class="row admin_mainbar_box">
			
				<?php user_field(); ?>
			
			</div>

		</div>
		
	</form>
	
</div>
