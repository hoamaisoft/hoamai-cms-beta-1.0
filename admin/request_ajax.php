<?php/**  * Tệp tin xử lý request uri bằng ajax trong admin * Vị trí : admin/request_ajax.php  */if ( ! defined('BASEPATH')) exit('403');/** gọi tệp tin admin base */require_once( dirname( __FILE__ ) . '/admin.php' );/** gọi model xử lý taxonomy */require_once( dirname( __FILE__ ) . '/request/request_model.php' );$key=hm_get('key');$id=hm_get('id');$action=hm_get('action');switch ($action) {		case 'suggest':		$key = hm_post('val');		echo request_suggest($key);	break;}