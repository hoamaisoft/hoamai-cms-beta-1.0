<?php
/** 
 * Tệp tin xử lý content trong admin
 * Vị trí : admin/content.php 
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
    case 'add':
		
		/** Lấy thông tin content type trả về array */
		
        $args=content_data($key);
		$args_tax=taxonomy_data($args['taxonomy_key']);
		
		/** Hiển thị giao diện thêm content bằng array ở trên */		
		function admin_content_page(){
			global $args;
			global $args_tax;
			require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'content_add.php');
		}
		
    break;
	case 'add_chapter':
		if(isset_content_id($id) == TRUE){
			
			/** Lấy thông tin content type trả về array */
			
			$args_con=content_data_by_id($id);
			$key=$args_con['content']->key;
			$args=content_data($key);
			$args_tax=taxonomy_data($args['taxonomy_key']);
			
			/** Hiển thị giao diện thêm content bằng array ở trên */		
			function admin_content_page(){
				global $args;
				global $args_tax;
				global $args_con;
				require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'content_add_chapter.php');
			}
		}
    break;
	case 'edit':

		if(isset_content_id($id) == TRUE){
			
			/** Thực hiện sửa content */
			$args_con=content_data_by_id($id);
			$key=$args_con['content']->key;
			$args=content_data($key);
			$args_tax=taxonomy_data($args['taxonomy_key']);

			/** Hiển thị giao diện sửa content bằng array ở trên */		
			function admin_content_page(){
				global $args;
				global $args_tax;
				global $args_con;
				require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'content_edit.php');
			}
			
		}else{
			/** Không tồn tại content có id như request, hiển thị giao diện 404 */		
			function admin_content_page(){
				require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'admincp_404.php');
			}
		}
		
    break;
	case 'view':
		
		if(isset_content_id($id) == TRUE){
			
			/** Thực hiện sửa content */
			$args=content_data_by_id($id);

			/** Hiển thị giao diện sửa content bằng array ở trên */		
			function admin_content_page(){
				global $args;
				require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'content_view.php');
			}
			
		}else{
			/** Không tồn tại content có id như request, hiển thị giao diện 404 */		
			function admin_content_page(){
				require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'admincp_404.php');
			}
		}
		
    break;
    default :
		$args=content_data($key);
		/** Hiển thị giao diện thêm tất cả content */		
		function admin_content_page(){
			global $args;
			require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'content_all.php');
		}
}


/** fontend */
require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');