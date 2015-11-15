<?php
/** 
 * Tệp tin model của plugin trong admin
 * Vị trí : admin/plugin/plugin_model.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/** Load danh sách plugin */
function available_plugin(){ 
	
	$plugins = array();
	$available_plugin = scandir(BASEPATH.HM_PLUGIN_DIR);
	foreach($available_plugin as $plugin){
		
		if(
			( is_dir(BASEPATH.HM_PLUGIN_DIR.'/'.$plugin) )
			AND 
			( is_file(BASEPATH.HM_PLUGIN_DIR.'/'.$plugin.'/'.$plugin.'.php') )
		){
			/** lấy nội dung comment trong file */
			$source = file_get_contents( BASEPATH.HM_PLUGIN_DIR.'/'.$plugin.'/'.$plugin.'.php' );
			$tokens = token_get_all( $source );
			$comment = array(
				T_COMMENT,
				T_DOC_COMMENT     
			);
			foreach( $tokens as $token ) {
				
				if( in_array($token[0], $comment) ){
					
					$string = $token[1];
					/** get thông tin plugin */
					
					preg_match("'Plugin Name:(.*?);'si", $string, $results);
					if(isset($results[1])){
						$plugin_name= $results[1];
					}
					
					preg_match("'Description:(.*?);'si", $string, $results);
					if(isset($results[1])){
						$plugin_description= $results[1];
					}
					
					preg_match("'Version:(.*?);'si", $string, $results);
					if(isset($results[1])){
						$plugin_version= $results[1];
					}
					
					if(is_plugin_active($plugin)==TRUE){$plugin_active='1';}else{$plugin_active='0';}
					
					$plugins[$plugin]=array(
											'plugin_name'=>$plugin_name,
											'plugin_description'=>$plugin_description,
											'plugin_version'=>$plugin_version,
											'plugin_active'=>$plugin_active,
											'plugin_key'=>$plugin,
											);
					unset($string);
				}

			}

		}
		
	}
	ksort($plugins);
	$args['plugins']=$plugins;
	return json_encode($args);
}

function is_plugin_active($key){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	if(
		( is_dir(BASEPATH.HM_PLUGIN_DIR.'/'.$key) )
		AND 
		( is_file(BASEPATH.HM_PLUGIN_DIR.'/'.$key.'/'.$key.'.php') )
	){
		
		$tableName=DB_PREFIX."plugin";
		$whereArray=array('key'=>MySQL::SQLValue($key));
		$hmdb->SelectRows($tableName, $whereArray);
		if( $hmdb->HasRecords() ){
			
			$row = $hmdb->Row();
			$active = $row->active;
			if($active==1){
				
				return TRUE;
				
			}else{
				
				return FALSE;
				
			}
			
		}else{
			return FALSE;
		}
		
	}else{
		return FALSE;
	}

}

function list_plugin($active = 1){

	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	$tableName=DB_PREFIX."plugin";
	$whereArray=array(
					'active'=>MySQL::SQLValue($active, MySQL::SQLVALUE_NUMBER),
					);
	$hmdb->SelectRows($tableName, $whereArray);
	if( $hmdb->HasRecords() ){
		
		while ($row = $hmdb->Row()) {
			$return[] = $row->key;
		}
		return json_encode($return);

	}else{
	
		return array();
		
	}
	
}

/** Active / Deactive plugin */
function disable_plugin($status,$plugin_key=FALSE){ 

	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	if( $plugin_key == FALSE ){
		
		$plugin_key = hm_post('plugin');
		
	}
	
	
	if(
		( is_dir(BASEPATH.HM_PLUGIN_DIR.'/'.$plugin_key) )
		AND 
		( is_file(BASEPATH.HM_PLUGIN_DIR.'/'.$plugin_key.'/'.$plugin_key.'.php') )
	){
	
		$tableName=DB_PREFIX.'plugin';
		$values["active"] = MySQL::SQLValue($status);
		$values["key"] = MySQL::SQLValue($plugin_key);
			
		$whereArray = array (
								'key'=>MySQL::SQLValue($plugin_key),
							);
		
		$hmdb->AutoInsertUpdate($tableName, $values, $whereArray);
		
		$mes = NULL;
		if($status == '1'){$mes=$plugin_key.' active';}elseif($status == '0'){$mes=$plugin_key.' deactive';}
		
		$args=array(
						'status' => 'success',
						'mes' => $mes,
				   );
		return json_encode($args);
						
	}

}

/** Xóa plugin */
function drop_plugin($plugin_key=FALSE){ 
	
	disable_plugin('1',$plugin_key);
	$path = BASEPATH.'/'.HM_PLUGIN_DIR.'/'.$plugin_key;
	DeleteDir($path);
	
	$mes = $plugin_key.' deleted';
	$args=array(
					'status' => 'success',
					'mes' => $mes,
			   );
	return json_encode($args);

}

?>