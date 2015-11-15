<?php
/** 
 * Tệp tin xử lý các chapter của content trong admin
 * Vị trí : admin/chapter.php 
 */
if ( ! defined('BASEPATH')) exit('403');


/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

/** gọi model xử lý content */
require_once( dirname( __FILE__ ) . '/content/content_model.php' );
require_once( dirname( __FILE__ ) . '/chapter/chapter_model.php' );
require_once( dirname( __FILE__ ) . '/taxonomy/taxonomy_model.php' );

if(hm_get('id') != NULL){
	$id=hm_get('id');
}else{
	$id=NULL;
}

if(hm_get('action') != NULL){
	$action=hm_get('action');
}else{
	$action='';
}

switch ($action) {
    case 'view':
		if(isset_content_id($id)){
			
			$args=content_data_by_id($id);
			
			/** Hiển thị nội dung bản chapter */		
			function admin_content_page(){
				global $args;
				require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'chapter_view.php');
			}
			
		}else{
			/** Không tồn tại content có id như request, hiển thị giao diện 404 */		
			function admin_content_page(){
				require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'admincp_404.php');
			}
		}	
    break;
	case 'restore':
	
    break;
    default :
		if(isset_content_id($id)){
			
			$args=content_data_by_id($id);

			/** Hiển thị các bản thay đổi của content có id như request */		
			function admin_content_page(){
				global $args;
				require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'chapter_all.php');
			}
			
		}else{
			/** Không tồn tại content có id như request, hiển thị giao diện 404 */		
			function admin_content_page(){
				require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'admincp_404.php');
			}
		}		
}


/** fontend */
require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');