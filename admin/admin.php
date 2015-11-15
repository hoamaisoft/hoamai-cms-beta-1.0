<?php
/** 
 * Đây là tệp tin xử lý quản trị admin
 * Vị trí : /admin/admin.php 
 */
 
if ( ! defined('BASEPATH')) exit('403');

$disallow_check = array('login.php','login_ajax.php');

if( !in_array( hm_get('run'),$disallow_check ) ){
	
	$cookie_admin_login = $_COOKIE['admin_login'];
	$session_admin_login = $_SESSION['admin_login'];
	
	if($cookie_admin_login == NULL OR $session_admin_login == NULL){
		
		$login_page = SITE_URL.FOLDER_PATH.HM_ADMINCP_DIR.'?run=login.php&back='.urlencode(SITE_URL.$_SERVER['REQUEST_URI']);
		echo '<meta http-equiv="refresh" content="0;'.$login_page.'">';
		hm_exit(_('Đang chuyển hướng đến trang đăng nhập'));
	}
	
	if ( $cookie_admin_login == $session_admin_login ) {
		
		/** Làm mới cookie admin */
		setcookie('admin_login', $cookie_admin_login ,time() + COOKIE_EXPIRES, '/');
		define('ADMIN_LOGIN',$session_admin_login);
		
	}else{
		
		$login_page = SITE_URL.FOLDER_PATH.HM_ADMINCP_DIR.'?run=login.php&back='.SITE_URL.$_SERVER['REQUEST_URI'];
		echo '<meta http-equiv="refresh" content="0;'.$login_page.'">';
		hm_exit(_('Đang chuyển hướng đến trang đăng nhập'));
		
	}

}



