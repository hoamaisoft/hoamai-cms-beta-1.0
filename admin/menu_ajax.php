<?php/**  * Tệp tin xử lý menu trong admin * Vị trí : admin/menu.php  */if ( ! defined('BASEPATH')) exit('403');/** gọi tệp tin admin base */require_once( dirname( __FILE__ ) . '/admin.php' );/** gọi model xử lý menu */require_once( dirname( __FILE__ ) . '/menu/menu_model.php' );$key=hm_get('key');$id=hm_get('id');$action=hm_get('action');switch ($action) {		case 'add':		echo add_menu();	break;	case 'edit':		echo edit_menu($id);	break;	case 'delete':		$menu_id = hm_post('id');		echo delete_menu($menu_id);	break;	case 'delete_item':		$item_id = hm_post('id');		echo delete_menu_item($item_id);	break;	case 'edit_item':		echo edit_menu_item($id);	break;	case 'save_order':		echo save_menu_item_order($id);	break;	case 'location':		$args = hm_post('menu');		echo save_menu_location($args);	break;}