<?php
/** 
 * Tệp tin xử lý login trong admin
 * Vị trí : admin/login.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

/** gọi model xử lý login */
require_once( dirname( __FILE__ ) . '/login/login_model.php' );

$key=hm_get('key');
$id=hm_get('id');
$action=hm_get('action');

switch ($action) {
	case 'lostpw':
		require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'lostpw.php');
	break;
	case 'newpw':
		newpw_checkkey();
		require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'newpw.php');
	break;
	case 'logout':
		admin_cp_logout();
	break;
	default :
		require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'login.php');
}



