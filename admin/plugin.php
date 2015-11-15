<?php
/** 
 * Tệp tin xử lý plugin trong admin
 * Vị trí : admin/plugin.php 
 */
if ( ! defined('BASEPATH')) exit('403');



/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

/** gọi model xử lý plugin */
require_once( dirname( __FILE__ ) . '/plugin/plugin_model.php' );


$key=hm_get('key');
$id=hm_get('id');
$action=hm_get('action');

switch ($action) {
    case 'add':
			
		function admin_content_page(){
			require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'plugin_add.php');
		}
		
    break;
    default :
		
		/** Hiển thị giao diện tất cả plugin */
		function admin_content_page(){
			require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'plugin_all.php');
		}
}


/** fontend */
require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');