<?php
/** 
 * Save và get các option
 * Vị trí : hm_include/option.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/** Lưu hoặc update dữ liệu */

function set_option($args = array() ){
	
	if(!is_array($args)){
		parse_str($args, $args);
	}
	
	global $hmdb; 
	
	if( isset($args['section']) ){ $section = $args['section']; }else{ $section = ''; }
	if( isset($args['key']) ){ $key = $args['key']; }else{ $key = FALSE; }
	if( isset($args['value']) ){ $value = $args['value']; }else{ $value = FALSE; }
	if( is_array($value) ){ $value = json_encode($value); }
	
	if($section != '' ){
		
		$tableName=DB_PREFIX.'option';
		
		$values["key"] = MySQL::SQLValue($key);
		$values["value"] = MySQL::SQLValue($value);
		$values["section"] = MySQL::SQLValue($section);
			
		$whereArray = array (
								'key'=>MySQL::SQLValue($key),
								'section'=>MySQL::SQLValue($section),
							);
		return $hmdb->AutoInsertUpdate($tableName, $values, $whereArray);
		
	
	}else{
	
		return FALSE;
		
	}
	
}

/** Lấy dữ liệu */
function get_option($args = array() ){
	
	if(!is_array($args)){
		parse_str($args, $args);
	}
	
	global $hmdb; 

	if( isset($args['section']) ){ $section = $args['section']; }else{ $section = ''; }
	if( isset($args['key']) ){ $key = $args['key']; }else{ $key = ''; }
	if( isset($args['default_value']) ){ $default_value = $args['default_value']; }else{ $default_value = ''; }

	if($section != '' AND $key != '' ){
		
		$tableName=DB_PREFIX.'option';
		
		$whereArray = array (
								'section'=>MySQL::SQLValue($section),
								'key'=>MySQL::SQLValue($key),
							);
						
		$hmdb->SelectRows($tableName, $whereArray);
		
		if( $hmdb->HasRecords() ){
			
			$row=$hmdb->Row();
			return $row->value;
			
		}else{
			
			return $default_value;
			
		}
	
	}else{
	
		return FALSE;
		
	}

}