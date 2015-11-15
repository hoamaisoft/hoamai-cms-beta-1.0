<?php
/** 
 * Tệp tin model của theme trong admin
 * Vị trí : admin/theme/theme_model.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/** Load danh sách theme */
function available_theme(){ 
	
	$themes = array();
	$available_theme = scandir(BASEPATH.HM_THEME_DIR);
	
	foreach($available_theme as $theme){
		
		if(
			( is_dir(BASEPATH.HM_THEME_DIR.'/'.$theme) )
			AND 
			( is_file(BASEPATH.HM_THEME_DIR.'/'.$theme.'/init.php') )
		){
			/** lấy nội dung comment trong file */
			$source = file_get_contents( BASEPATH.HM_THEME_DIR.'/'.$theme.'/init.php' );
			$tokens = token_get_all( $source );
			$comment = array(
				T_COMMENT,
				T_DOC_COMMENT     
			);
			
			foreach( $tokens as $token ) {
				
				if( in_array($token[0], $comment) ){
					
					$string = $token[1];
					/** get thông tin theme */

					preg_match("'Theme Name:(.*?);'si", $string, $results);
					if(isset($results[1])){
						$theme_name= $results[1];
					}
					
					preg_match("'Description:(.*?);'si", $string, $results);		
					if(isset($results[1])){
						$theme_description= $results[1];
					}
					
					preg_match("'Version:(.*?);'si", $string, $results);
					if(isset($results[1])){
						$theme_version= $results[1];
					}
					
					if(is_theme_active($theme)==TRUE){$theme_active='1';}else{$theme_active='0';}
					
					if( file_exists(BASEPATH.HM_THEME_DIR.'/'.$theme.'/thumbnail.jpg') ){
						$theme_thumbnail = SITE_URL.FOLDER_PATH.HM_THEME_DIR.'/'.$theme.'/thumbnail.jpg';
					}else{
						$theme_thumbnail = SITE_URL.FOLDER_PATH.HM_CONTENT_DIR.'/images/theme_no_thumbnail.jpg';
					}
			
					$themes[$theme]=array(
											'theme_name'=>$theme_name,
											'theme_description'=>$theme_description,
											'theme_version'=>$theme_version,
											'theme_key'=>$theme,
											'theme_active'=>$theme_active,
											'theme_thumbnail'=>$theme_thumbnail,
											);											
					unset($string);
				}

			}

		}
		
	}
	ksort($themes);
	$args['themes']=$themes;
	return json_encode($args);
	
}


function is_theme_active($theme){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	if(
		( is_dir(BASEPATH.HM_THEME_DIR.'/'.$theme) )
		AND 
		( is_file(BASEPATH.HM_THEME_DIR.'/'.$theme.'/init.php') )
	){

		$tableName=DB_PREFIX."option";
		$whereArray=array(
				'section'=>MySQL::SQLValue('system_setting'),
				'key'=>MySQL::SQLValue('theme'),
				'value'=>MySQL::SQLValue($theme),
		);
		$hmdb->SelectRows($tableName, $whereArray);
		if( $hmdb->HasRecords() ){
			
			return TRUE;
			
		}else{
			return FALSE;
		}
		
	}else{
		return FALSE;
	}

}

function active_theme(){
	
	$theme = hm_post('theme');
	
	if(
		( is_dir(BASEPATH.HM_THEME_DIR.'/'.$theme) )
		AND 
		( is_file(BASEPATH.HM_THEME_DIR.'/'.$theme.'/init.php') )
	){
	
		$args = array(
						'section'=>'system_setting',
						'key'=>'theme',
						'value'=>$theme,
					);
		
		set_option($args);
	
	}
	
}