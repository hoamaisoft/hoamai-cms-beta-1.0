<?php
/** 
 * Tệp tin xử lý plugin trong admin
 * Vị trí : admin/plugin.php 
 */
if ( ! defined('BASEPATH')) exit('403');


/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

/** gọi model xử lý setting */
require_once( dirname( __FILE__ ) . '/setting/setting_model.php' );


function admin_content_page(){
	
	$key=hm_get('key');
	$id=hm_get('id');
	$action=hm_get('action');
		
	$setting_page = get_admin_setting_page();
	
	if( isset($setting_page[$key]) ){
	
		$func = $setting_page[$key]['function'];
		$func_input = $setting_page[$key]['function_input'];
		if(!function_exists($func)) {
			echo(_('Không tìm thấy hàm : ').$func);
		}else{
			call_user_func($func,$func_input);
		}
	
	}else{
		
		require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'admincp_404.php');
		
	}

}

/** fontend */
require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');