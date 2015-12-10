<?php
/** 
 * Tệp tin xử lý login bằng ajax trong admin
 * Vị trí : admin/login_ajax.php 
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
	case 'log-me-in':
		echo admin_cp_login();
	break;
	case 'lostpw':
		echo admin_cp_lostpw();
	break;	
	case 'newpw':
		echo admin_cp_newpw();
	break;	
}