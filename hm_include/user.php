<?php
/** 
 * Xử lý user
 * Vị trí : hm_include/user.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/**
 * Gọi thư viện user
 */
require_once(BASEPATH . HM_INC . '/user/hm_user.php');

/**
 * Khởi tạo class
 */
$hmuser = new user;

/**
 * Định nghĩa các hàm để thực hiện phương thức trong class kèm theo hook
 */
 
function register_user_field( $args=array() ){
	
	global $hmuser;

	hook_action('register_user_field');
	
	$return = $hmuser->register_user_field($args);
	
	$return = hook_filter('register_user_field',$return);
	
	return $return;

}

function get_user_field(){
	
	global $hmuser;

	hook_action('get_user_field');
	
	$return = $hmuser->get_user_field();
	
	$return = hook_filter('get_user_field',$return);
	
	return $return;

}

function user_role_id_to_nicename($user_role){
	
	global $hmuser;

	hook_action('user_role_id_to_nicename');
	
	$return = $hmuser->user_role_id_to_nicename($user_role);
	
	$return = hook_filter('user_role_id_to_nicename',$return);
	
	return $return;

}

function user_data( $id ){
	
	global $hmuser;
	
	hook_action('user_data');
	
	$return = $hmuser->user_data($id);

	$return = hook_filter('user_data',$return);
	
	return $return;

}

function user_field( $id=NULL ){
	
	global $hmuser;
	
	hook_action('user_field');
	
	$hmuser->user_field($id);

}

function user_field_data( $args = array() ){
	
	global $hmuser;
	
	hook_action('user_field_data');
	
	$return = $hmuser->user_field_data($id);

	$return = hook_filter('user_field_data',$return);
	
	return $return;

}

function isset_user( $id ){
	
	global $hmuser;
	
	hook_action('isset_user');
	
	$return = $hmuser->isset_user($id);

	$return = hook_filter('isset_user',$return);
	
	return $return;

}


?>