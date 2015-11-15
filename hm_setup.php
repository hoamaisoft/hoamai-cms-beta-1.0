<?php
/** 
 * Đây là tệp tin khởi tạo cấu trúc website
 * Tất cả các hàm ở đây đều có thể dùng trong plugin hay theme
 * Điều khác biệt là các hàm ở /hm_setup.php luôn chạy không phụ thuộc vào
 * plugin hay theme bạn đang dùng, còn nếu khai báo ở plugin hay theme thì
 * chỉ chạy khi plugin hoặc theme đó đã kích hoạt.
 * Trong mã nguồn này HoaMai được build dưới dạng một blog
 * Cấu trúc cho blog gồm 1 taxonomy "Danh mục bài viết" và 1 content type "Bài viết"
 * Để thực hiện việc này chúng ta sử dụng hàm register_taxonomy(); và register_content();
 * Vị trí : /hm_setup.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/**
 * Khởi tạo 1 taxonomy mặc định có tên là "Danh mục bài viết" và key là "category" 
 * cho dạng nội dung "post" và định nghĩa các trường nhập vào
 * Lưu ý : Luôn phải có 1 trường có key là primary_name_field, trường này sẽ được dùng là tên của
 * content, taxonomy và trường này có 'create_slug'=>TRUE để tạo slug - đường dẫn tĩnh cho content, taxonomy này
 */
 
hook_action('before_web_setup');
 
$field_array=array(
	'primary_name_field'=>array(
		'nice_name'=>'Tên danh mục',
		'description'=>'Tên là cách nó xuất hiện trên trang web của bạn',
		'name'=>'name',
		'create_slug'=>TRUE,
		'input_type'=>'text',
		'default_value'=>'',
		'placeholder'=>'Nhập tiêu đề tại đây',
		'required'=>TRUE,
	),
	array(
		'nice_name'=>'Mô tả danh mục',
		'description'=>'Một đoạn văn bản ngắn mô tả về danh mục này, tất nhiên nó chỉ hiện thị nếu giao diện bạn dùng có hỗ trợ',
		'name'=>'description',
		'input_type'=>'textarea',
		'default_value'=>'',
		'placeholder'=>'',
		'required'=>FALSE,
	),
);

$args=array(
	'taxonomy_name'=>'Danh mục bài viết',
	'taxonomy_key'=>'category',
	'content_key'=>'post',
	'all_items'=>'Tất cả danh mục',
	'edit_item'=>'Sửa danh mục',
	'view_item'=>'Xem danh mục',
	'update_item'=>'Cập nhật danh mục',
	'add_new_item'=>'Thêm danh mục mới',
	'new_item_name'=>'Tên danh mục mới',
	'parent_item'=>'Danh mục cha',
	'no_parent'=>'Không có danh mục cha',
	'search_items'=>'Tìm kiếm danh mục',
	'popular_items'=>'Danh mục dùng nhiều',
	'taxonomy_field'=>$field_array,
	'primary_name_field'=>$field_array['primary_name_field'],
);
register_taxonomy($args);


/**
 * Khởi tạo 1 content mặc định là "Bài viết" sử dụng kiểu taxonomy là "Danh mục bài viết" 
 * đã khởi tạo ở trên, vì ở trên taxonomy "Danh mục bài viết" đã đăng ký content_key là "post"
 * nên dạng nội dung này sẽ có content_key là "post" để dùng được trong "Danh mục bài viết"
 */
 
$field_array=array(
	'primary_name_field'=>array(
		'nice_name'=>'Tên bài viết',
		'name'=>'name',
		'create_slug'=>TRUE,
		'input_type'=>'text',
		'default_value'=>'',
		'placeholder'=>'Tiêu đề bài viết',
		'required'=>TRUE,
	),
	array(
		'nice_name'=>'Mô tả bài viết',
		'description'=>'Mô tả ngắn gọn về nội dung của bài viết này',
		'name'=>'description',
		'input_type'=>'textarea',
		'default_value'=>'',
		'placeholder'=>'',
		'required'=>FALSE,
	),
	array(
		'nice_name'=>'Nội dung bài viết',
		'name'=>'content',
		'input_type'=>'wysiwyg',
		'default_value'=>'',
		'placeholder'=>'',
		'required'=>FALSE,
	),
);
$args=array(
	'content_name'=>'Bài viết',
	'taxonomy_key'=>'category',
	'content_key'=>'post',
	'all_items'=>'Tất cả bài viết',
	'edit_item'=>'Sửa bài viết',
	'view_item'=>'Xem bài viết',
	'update_item'=>'Cập nhật bài viết',
	'add_new_item'=>'Thêm bài viết mới',
	'new_item_name'=>'Tên bài viết mới',
	'chapter'=>FALSE,
	'search_items'=>'Tìm kiếm bài viết',
	'content_field'=>$field_array,
	'primary_name_field'=>$field_array['primary_name_field'],
);
register_content($args);



/**
 * Trong quản trị ngoài việc khai báo các trường bắt buộc cho thành viên như
 * tên đăng nhập, mật khẩu ... Bạn có thể bổ sung thêm các trường cần cho website của bạn
 * như skype, email, số điện thoại ... như dưới đây
 */

$args=array(
	array(
		'nice_name'=>'Email',
		'name'=>'name',
		'input_type'=>'email',
		'default_value'=>'',
		'placeholder'=>'Email của người dùng',
		'required'=>FALSE,
	),
	array(
		'nice_name'=>'Skype',
		'name'=>'skype',
		'input_type'=>'text',
		'default_value'=>'',
		'placeholder'=>'Skype của người dùng',
		'required'=>FALSE,
	),
	array(
		'nice_name'=>'Yahoo',
		'name'=>'yahoo',
		'input_type'=>'text',
		'default_value'=>'',
		'placeholder'=>'Yahoo của người dùng',
		'required'=>FALSE,
	),
	array(
		'nice_name'=>'Số điện thoại',
		'name'=>'phone',
		'input_type'=>'text',
		'default_value'=>'',
		'placeholder'=>'Nhập số điện thoại',
		'required'=>FALSE,
	),
	array(
		'nice_name'=>'Thông tin cá nhân',
		'name'=>'bio',
		'input_type'=>'textarea',
		'default_value'=>'',
		'placeholder'=>'Giới thiệu về bản thân',
		'required'=>FALSE,
	),
);

register_user_field($args);

hook_action('after_web_setup');
?>