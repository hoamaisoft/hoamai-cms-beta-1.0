<?php
/** 
 * Tệp tin model của dashboard trong admin
 * Vị trí : admin/dashboard/dashboard_model.php 
 */
if ( ! defined('BASEPATH')) exit('403');


/** Đăng ký dashboard box */
function register_dashboard_box( $args=array() ){
	hook_filter('before_register_dashboard_box',$args);
	
	hook_action('register_dashboard_box');
	
	global $dashboard_box;
	
	if(is_array($args)){
		$dashboard_box[] = $args;
	}
}

/** Hiển thị dashboard box */
function dashboard_box($args=array()){

	global $dashboard_box;
	
	foreach ($dashboard_box as $box){
			
		$width = $box['width'];
		$func = $box['function'];
		
		echo '<div class="col-md-'.$width.'">';
			echo '<p class="admin_sidebar_box_title ui-sortable-handle" ?>'.$label.'</p>';
			echo '<div class="admin_mainbar_boxdashboard">';
			if(function_exists($func)) {
				call_user_func($func, $args);
			}
			echo '</div>';
		echo '</div>';
			
	}

}

?>