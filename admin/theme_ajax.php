<?php/**  * Tệp tin xử lý theme trong admin * Vị trí : admin/theme.php  */if ( ! defined('BASEPATH')) exit('403');/** gọi tệp tin admin base */require_once( dirname( __FILE__ ) . '/admin.php' );/** gọi model xử lý theme */require_once( dirname( __FILE__ ) . '/theme/theme_model.php' );$key=hm_get('key');$id=hm_get('id');$action=hm_get('action');switch ($action) {	case 'available':		echo available_theme();	break;	case 'active_theme':		echo active_theme();	break;}