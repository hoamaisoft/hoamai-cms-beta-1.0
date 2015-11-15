<?php
/** 
 * Load giao diện
 * Vị trí : hm_include/theme.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/**
 * Gọi thư viện theme
 */
require_once(BASEPATH . HM_INC . '/theme/hm_theme.php');

/**
 * Khởi tạo class
 */
$hmtheme = new theme;
	
function activated_theme(){
	
	global $hmtheme;
	
	hook_action('activated_theme');
	
	$return = $hmtheme->activated_theme();
	
	return $return;
	
}

function load_theme($args){
	
	hook_filter('before_load_theme',$args);
	
	global $hmtheme;
	
	hook_action('load_theme');
	
	$hmtheme->load_theme($args);
	
}


function get_template_part($template_file){
	
	hook_filter('before_get_template_part',$template_file);
	
	global $hmtheme;
	
	hook_action('get_template_part');
	
	$hmtheme->get_template_part($template_file);
	
}

function css($file=NULL){
	
	$is_url = filter_var($file, FILTER_VALIDATE_URL);
	if($is_url == FALSE){
		
		$theme = activated_theme();
		if(file_exists('./'.HM_THEME_DIR.'/'.$theme.'/'.$file)){
			return '<link rel="stylesheet" type="text/css" href="'.SITE_URL.FOLDER_PATH.HM_THEME_DIR.'/'.$theme.'/'.$file.'">'."\n";
		}else{
			return FALSE;
		}
	}else{
		
		return '<link rel="stylesheet" type="text/css" href="'.$is_url.'">'."\n";
		
	}
	
}

function js($file=NULL){
	
	$is_url = filter_var($file, FILTER_VALIDATE_URL);
	if($is_url == FALSE){
		
		$theme = activated_theme();
		if(file_exists('./'.HM_THEME_DIR.'/'.$theme.'/'.$file)){
			return '<script type="text/javascript" src="'.SITE_URL.FOLDER_PATH.HM_THEME_DIR.'/'.$theme.'/'.$file.'"></script>'."\n";
		}else{
			return FALSE;
		}
		
	}else{
		
		return '<script type="text/javascript" src="'.$file.'"></script>'."\n";
		
	}

}


function img($file=NULL){
	
	$is_url = filter_var($file, FILTER_VALIDATE_URL);
	if($is_url == FALSE){
		
		$theme = activated_theme();
		if(file_exists('./'.HM_THEME_DIR.'/'.$theme.'/'.$file)){
			return '<img src="'.SITE_URL.FOLDER_PATH.HM_THEME_DIR.'/'.$theme.'/'.$file.'" />'."\n";
		}else{
			return FALSE;
		}
		
	}else{
		
		return '<img src="'.$file.'" />'."\n";
		
	}

}

function theme_uri(){
	
	$theme = activated_theme();
	return SITE_URL.FOLDER_PATH.HM_THEME_DIR.'/'.$theme;
	
}

function get_id(){
	
	$request = get_current_uri();
	$request_data = get_uri_data( array('uri'=>$request) );
	
	if( $request_data != FALSE ){
		
		$object_type = $request_data->object_type;
		$object_id = $request_data->object_id;

		return  $object_id;
		 
	}else{
		return FALSE;
	}
	
}	


function have_content(){
	
	$request = get_current_uri();
	$request_data = get_uri_data( array('uri'=>$request) );
	
	if( $request_data != FALSE ){
		
		$object_type = $request_data->object_type;
		$object_id = $request_data->object_id;
		
		switch ($object_type) {
			case 'taxonomy':
				$return = taxonomy_have_content($object_id);
			break;
		}	
		 
		return  $return;
		 
	}else{
		return FALSE;
	}
	
}	

function is_taxonomy(){
	
	$request = get_current_uri();
	$request_data = get_uri_data( array('uri'=>$request) );
	
	if( $request_data != FALSE ){
		
		$object_type = $request_data->object_type;
		if($object_type == 'taxonomy'){
			return TRUE;
		}
		 
	}else{
		return FALSE;
	}
	
}

function is_content(){
	
	$request = get_current_uri();
	$request_data = get_uri_data( array('uri'=>$request) );
	
	if( $request_data != FALSE ){
		
		$object_type = $request_data->object_type;
		if($object_type == 'content'){
			return TRUE;
		}
		 
	}else{
		return FALSE;
	}
	
}

function hm_head(){
	hook_action('before_hm_head');
	
	echo hm_title();
	
	hook_action('after_hm_head');
}

function hm_title(){
	
	hook_action('before_hm_title');
	
	global $hmcontent;
	global $hmtaxonomy;
	
	$request = get_current_uri();
	$request_data = get_uri_data( array('uri'=>$request) );
	if($request == ''){
		$home_title = get_option( array('section'=>'system_setting','key'=>'website_name','default_value'=>'Một trang web sử dụng HoaMaiCMS') );
		if($home_title!=''){
			$title = '<title>'.$home_title.'</title>'."\n\r";
		}else{
			$title = FALSE;
		}
		return hook_filter('hm_title',$title);
	}else{
		if( $request_data != FALSE ){
			
			$object_type = $request_data->object_type;
			$object_id = $request_data->object_id;
			
			switch ($object_type) {
				case 'content':
					$content_data = content_data_by_id($object_id);
					$title = $content_data['content']->name;
					$title = '<title>'.$title.'</title>'."\n\r";
					return hook_filter('hm_title',$title);
				break;
				case 'taxonomy':
					$taxonomy_data = taxonomy_data_by_id($object_id);
					$title = $taxonomy_data['taxonomy']->name;
					$title = '<title>'.$title.'</title>'."\n\r";
					return hook_filter('hm_title',$title);
				break;
			}
			 
		}else{
			return FALSE;
		}
	}
	hook_action('after_hm_title');
}

function hm_footer(){
	hook_action('before_hm_footer');
	
	hook_action('after_hm_footer');
}

?>