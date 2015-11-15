<?php
/** 
 * Tệp tin xử lý command trong admin
 * Vị trí : admin/command.php 
 */
if ( ! defined('BASEPATH')) exit('403');


/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

/** gọi model xử lý command */
//require_once( dirname( __FILE__ ) . '/plugin/plugin_model.php' );


$key=hm_get('key');
$id=hm_get('id');
$action=hm_get('action');

/** Hiển thị giao diện command */
function admin_content_page(){
	require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'command.php');
}


/** fontend */
require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');