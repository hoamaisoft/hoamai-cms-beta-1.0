<?php
/** 
 * Tệp tin xử lý thư viện trong admin
 * Vị trí : admin/media.php 
 */
if ( ! defined('BASEPATH')) exit('403');


/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

/** gọi model xử lý media */
require_once( dirname( __FILE__ ) . '/media/media_model.php' );

$key=hm_get('key');
$id=hm_get('id');
$action=hm_get('action');

switch ($action) {
	default :
		/** Hiển thị giao diện thư viện */
		function admin_content_page(){
			require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'media.php');
		}
}

/** fontend */
require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');

