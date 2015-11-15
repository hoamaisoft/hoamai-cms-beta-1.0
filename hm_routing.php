<?php
/** 
 * Đây là tệp tin xử lý đường dẫn
 * Vị trí : /hm_routing.php 
 */

if ( ! defined('BASEPATH')) exit('403');

/** Lấy uri trang đang xem */
$request_slug = get_current_uri();

$segments = explode('/', $request_slug);

/** Kiểm tra segments 1 có phải là 1 module */
$modules = $hmmodule->hmmodule;

if($segments[0]==HM_ADMINCP_DIR AND $segments[1]==''){
	
	/** Admin cp */
	require_once(BASEPATH . HM_ADMINCP_DIR . '/index.php');
	
}elseif( isset($modules[$module_key]) ){
	/** Module */
	
	$module = $modules[$module_key];
	if(is_array($module)){
		
		$module_name = $module['module_name'];
		$module_key = $module['module_key'];
		$module_dir = $module['module_dir'];
		$module_index = $module['module_index'];
		
		if( file_exists( BASEPATH . HM_MODULE_DIR .'/'. $module_dir .'/'. $module_index ) ){
			require_once(BASEPATH . HM_MODULE_DIR .'/'. $module_dir .'/'. $module_index);
		}else{
			hm_exit('Không tìm thấy file "'.$module_index.'" của module "'.$module_key.'"');
		}
		
	}else{
		hm_exit('Lỗi xử lý module'.' '.$module);
	}
	
}else{
	
	if( isset($hmrequest[$request_slug]) ){
		if(!function_exists($hmrequest[$request_slug])) {
			die('Unknown function: '.$hmrequest[$request_slug]);
		}else{
			call_user_func($hmrequest[$request_slug]);
		}
	}else{
		/** Fontend */
		$theme = activated_theme();
		$args = array(
			'theme' => $theme,
			'request' => $request_slug,
		);
		load_theme($args);
	}
	
}

?>