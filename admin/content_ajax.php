<?php
/** 
 * Tệp tin xử lý content bằng ajax trong admin
 * Vị trí : admin/content_ajax.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

/** gọi model xử lý content */
require_once( dirname( __FILE__ ) . '/content/content_model.php' );
require_once( dirname( __FILE__ ) . '/taxonomy/taxonomy_model.php' );

$key=hm_get('key');
$id=hm_get('id');
$action=hm_get('action');

switch ($action) {
	
	case 'data':
		$status = hm_get('status','public');
		$perpage = hm_get('perpage','30');
		echo content_show_data($key,$status,$perpage);
	break;

    case 'add':
		/** Thực hiện thêm content */
		$args = array(
						'content_key'=>$key,
					 );
        echo content_ajax_add($args);
    break;
	
	case 'add_chapter':
		/** Thực hiện thêm chapter */
        echo content_ajax_add_chapter($id);
    break;
	
	case 'edit':
		/** Thực hiện sửa content */
       echo  content_ajax_edit($id);
    break;
	
	case 'draft':
		/** Thực hiện xóa content */
        echo content_update_val( array( 'id'=>hm_post('id'),'value'=>array('status'=>MySQL::SQLValue('draft') ) ) );
    break;
	
	case 'delete_permanently':
		/** Thực hiện xóa vĩnh viễn content */
        echo content_delete_permanently(hm_post('id'));
    break;
	
	case 'public':
		/** Thực hiện khôi phục content */
        echo content_update_val( array( 'id'=>hm_post('id'),'value'=>array('status'=>MySQL::SQLValue('public') ) ) );
    break;
	
	case 'ajax_slug':
		/** Thực hiện tạo slug từ chuỗi */
		echo content_ajax_slug();
	break;	

}


