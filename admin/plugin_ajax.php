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

if( $_SERVER['HTTP_HOST'] != parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) ){
	mh_die('403 - Truy cập bị từ chối');
}

$key=hm_get('key');
$id=hm_get('id');
$action=hm_get('action');

switch ($action) {
	
	case 'available':
		echo available_plugin();
	break;
	
	case 'disable_plugin':
		echo disable_plugin(0);
	break;
	
	case 'active_plugin':
		echo disable_plugin(1);
	break;

}
