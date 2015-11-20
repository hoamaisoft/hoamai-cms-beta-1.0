<?php
/** 
 * Xử lý taxonomy
 * Vị trí : hm_include/taxonomy.php 
 */
if ( ! defined('BASEPATH')) exit('403');
/**
 * Gọi thư viện taxonomy
 */
require_once(BASEPATH . HM_INC . '/taxonomy/hm_taxonomy.php');
/**
 * Khởi tạo class
 */
$hmtaxonomy = new taxonomy;
/**
 * Định nghĩa các hàm để thực hiện phương thức trong class kèm theo hook
 */
function isset_taxonomy( $args=array() ){
	hook_filter('before_isset_taxonomy',$args);
	
	global $hmtaxonomy;
	
	hook_action('isset_taxonomy');
	
	$return = $hmtaxonomy->isset_taxonomy($args);
	
	$return = hook_filter('isset_taxonomy',$return);
	
	return $return;
} 
function isset_taxonomy_id( $id ){
	hook_filter('before_isset_taxonomy_id',$id);
	
	global $hmtaxonomy;
	
	hook_action('isset_taxonomy_id');
	
	$return = $hmtaxonomy->isset_taxonomy_id($id);
	
	$return = hook_filter('isset_taxonomy_id',$return);
	
	return $return;
} 
function register_taxonomy( $args=array() ){
	hook_filter('before_register_taxonomy',$args);
	
	global $hmtaxonomy;
	
	hook_action('register_taxonomy');
	
	$return = $hmtaxonomy->register_taxonomy($args);
	
	$return = hook_filter('register_taxonomy',$return);
	
	return $return;
}
function destroy_taxonomy( $args=array() ){
	hook_filter('before_destroy_taxonomy',$args);
	
	global $hmtaxonomy;
	
	hook_action('destroy_taxonomy');
	
	$return = $hmtaxonomy->destroy_taxonomy($args);
	$return = hook_filter('destroy_taxonomy',$return);
	
	return $return;
}
function total_taxonomy( $args=array() ){
	hook_filter('before_total_taxonomy',$args);
	
	global $hmtaxonomy;
	
	hook_action('total_taxonomy');
	
	$return = $hmtaxonomy->total_taxonomy($args);
	
	$return = hook_filter('total_taxonomy',$return);
	
	return $return; 
}
function get_tax_val( $args=array() ){
	
	if(!is_array($args)){
		parse_str($args, $args);
	}
	hook_filter('before_get_tax_val',$args);
	
	global $hmtaxonomy;
	
	hook_action('get_tax_val');
	
	$return = $hmtaxonomy->get_tax_val($args);
	
	$return = hook_filter('get_tax_val',$return);
	
	return $return;  
}
function taxonomy_update_val( $args=array() ){
	hook_filter('before_taxonomy_update_val',$args);
	
	global $hmtaxonomy;
	hook_action('taxonomy_update_val');
	
	$return = $hmtaxonomy->taxonomy_update_val($args);
	
	return $return;
}
function taxonomy_data_by_id( $id=0 ){
	hook_filter('before_taxonomy_data_by_id',$id);
	
	global $hmtaxonomy;
	hook_action('taxonomy_data_by_id');
	
	$return = $hmtaxonomy->taxonomy_data_by_id($id);
	
	return $return;
}
function register_taxonomy_box( $args=array() ){
	hook_filter('before_register_taxonomy_box',$args);
	
	hook_action('register_taxonomy_box');
	
	global $taxonomy_box;
	
	if(is_array($args)){
		$taxonomy_box[] = $args;
	}
}
function taxonomy_have_content( $tax_id ){
	
	hook_filter('taxonomy_have_content',$tax_id);
	
	global $hmtaxonomy;
	hook_action('taxonomy_have_content');
	
	$return = $hmtaxonomy->taxonomy_have_content($tax_id);
	
	return $return;
	
}
function taxonomy_get_content_key( $tax_id ){
	
	hook_filter('taxonomy_get_content_key',$tax_id);
	
	global $hmtaxonomy;
	hook_action('taxonomy_get_content_key');
	
	$return = $hmtaxonomy->taxonomy_get_content_key($tax_id);
	
	return $return;
	
}	

	
?>