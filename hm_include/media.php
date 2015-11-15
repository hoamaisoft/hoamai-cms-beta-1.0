<?php
/** 
 * Xử lý media
 * Vị trí : hm_include/media.php 
 */
if ( ! defined('BASEPATH')) exit('403');


/** Check file tồn tại */
function isset_file($id){
	if( is_numeric($id) ){
		
		$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
		
		$tableName=DB_PREFIX."media";
		$whereArray=array('id'=>MySQL::SQLValue($id));
		$hmdb->SelectRows($tableName,$whereArray);
		$rowCount = $hmdb->RowCount();
		if($rowCount!=0){
			return TRUE;
		}else{
			return FALSE;
		}
	}else{
		return FALSE;
	}
}

/** Lấy thông tin file */
function get_file_data($id){
	if(isset_file($id)){
		$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
		$tableName=DB_PREFIX."media";
		$whereArray=array('id'=>MySQL::SQLValue($id));
		$hmdb->SelectRows($tableName,$whereArray);
		return $hmdb->Row();
	}else{
		return FALSE;
	}
}

/** Check file tồn tại và là ảnh*/
function isset_image($id){
	if( is_numeric($id) ){
		$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
		$tableName=DB_PREFIX."media";
		$whereArray = array(
						'id' => MySQL::SQLValue($id),
						'file_is_image' => MySQL::SQLValue('true'),
					);
		$hmdb->SelectRows($tableName,$whereArray);
		$rowCount = $hmdb->RowCount();
		if($rowCount!=0){
			return TRUE;
		}else{
			return FALSE;
		}
	}else{
		return FALSE;
	}
}

/** Lấy đường dẫn nhóm media */
function get_media_group_part($id=0,$i=1,$deepest=FALSE){
		
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	if(!is_numeric($id)){
		$tableName=DB_PREFIX.'media_groups';
		$whereArray=array('folder'=>MySQL::SQLValue($id));
		$hmdb->SelectRows($tableName, $whereArray);
		$row=$hmdb->Row();
		$id = $row->id;
	}
	
	
	$bre = array();
	$sub_bre = FALSE;
	
	if($deepest == FALSE){
		$deepest=$id;
	}
	
	$tableName=DB_PREFIX.'media_groups';
	$whereArray=array('id'=>MySQL::SQLValue($id));
	$hmdb->SelectRows($tableName, $whereArray);
	$row=$hmdb->Row();
	$num_rows = $hmdb->RowCount();
	
	if($num_rows!=0){
		
		$this_id = $row->id;
		$folder = $row->folder;
		$parent = $row->parent;
		
		$bre['level_'.$i] = $folder;
		if($parent != '0'){
			$inew = $i + 1;
			$sub_bre = get_media_group_part($parent,$inew,$deepest);
		}
	}
	
	if(is_array($sub_bre)){
		$bre = array_merge($bre,$sub_bre);
	}
	
	krsort($bre);
	$part = implode("/",$bre);
	
	if($deepest == $id){
		return $part;
	}else{
		return $bre;
	}
}

/** Đường dẫn tĩnh của file */
function get_file_url($id,$include_file_name=TRUE){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	if(isset_file($id)){
		$row=get_file_data($id);
		$file_info = $row->file_info;
		$file_name = $row->file_name;
		$file_folder = $row->file_folder;
		if($file_folder!='/'){
			$file_folder_part = '/'.get_media_group_part($file_folder).'/';
		}else{
			$file_folder_part = '/';
		}
		
		$file_info = json_decode($file_info,TRUE);
		if($include_file_name){
			$file_url = SITE_URL.'/'.HM_CONTENT_DIR.'/uploads'.$file_folder_part.$file_info['file_dst_name'];
		}else{
			$file_url = SITE_URL.'/'.HM_CONTENT_DIR.'/uploads'.$file_folder_part;
		}
		return $file_url;
		
	}
	
}

/** location file */
function get_file_location($id,$include_file_name=TRUE){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	if(isset_file($id)){
		$row=get_file_data($id);
		$file_info = $row->file_info;
		$file_name = $row->file_name;
		$file_folder = $row->file_folder;
		if($file_folder!='/'){
			$file_folder_part = '/'.get_media_group_part($file_folder).'/';
		}else{
			$file_folder_part = '/';
		}
		
		$file_info = json_decode($file_info,TRUE);
		if($include_file_name){
			$file_location = BASEPATH.HM_CONTENT_DIR.'/uploads'.$file_folder_part.$file_info['file_dst_name'];
		}else{
			$file_location = BASEPATH.HM_CONTENT_DIR.'/uploads'.$file_folder_part;
		}
		return $file_location;
		
	}
	
}

/** Cắt ảnh theo cỡ tùy chọn */
function create_image($args){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	hook_filter('before_create_image',$args);
	hook_action('create_image');
	
	if(!is_array($args)){
		parse_str($args, $args);
	}
	
	$file_id = $args['file'];
	if(isset_image($file_id)){
		
		$row = get_file_data($file_id);
		$file_info = json_decode($row->file_info);
		$file_dst_name_body = $file_info->file_dst_name_body;
		$file_dst_name_ext = $file_info->file_dst_name_ext;
		$source_file = get_file_location($file_id);
		
		$crop_name = $file_dst_name_body.'-'.$args['w'].'x'.$args['h'].'.'.$file_dst_name_ext;
		if( file_exists(get_file_location($file_id,FALSE).$crop_name) ){
			return get_file_url($file_id,FALSE).$crop_name;
		}else{
			
			/** resize file */
					
			/* fix func exif_imagetype not avaiable */
			$type = getimagesize($source_file);
			
			$type = $type['mime'];
			switch($type){
				case 'image/png': $type = IMAGETYPE_PNG; break;
				case 'image/jpeg': $type = IMAGETYPE_JPEG; break;
				case 'image/gif': $type = IMAGETYPE_GIF; break;
				case 'image/bmp': $type = IMAGETYPE_BMP; break;
				case 'image/x-ms-bmp': $type = IMAGETYPE_BMP; break;
			};
			/* fix func exif_imagetype not avaiable */
			
			switch ($type) { 
				case 1 : 
					$source = imageCreateFromGif($source_file); 
				break; 
				case 2 : 
					$source = imageCreateFromJpeg($source_file); 
				break; 
				case 3 : 
					$source = imageCreateFromPng($source_file); 
				break; 
				case 6 : 
					$source = imageCreateFromBmp($source_file); 
				break; 
			} 
			
			/** resize file gốc về cùng 1 cỡ */
			$size = getimagesize($source_file);
			$source_width = $size[0];
			$source_height = $size[1];
			
			$fix_width = $args['w'];
			$fix_height = $args['h'];
		
			$thumb = imagecreatetruecolor($fix_width, $fix_height);
			
			/* Fix black background */
			$white = imagecolorallocate($thumb, 255, 255, 255); 
			imagefill($thumb,0,0,$white); 
			/* Fix black background */
			
			/* fix quality with imagecopyresampled , repalce imagecopyresized */
			imagecopyresampled($thumb, $source, 0, 0, 0, 0, $fix_width, $fix_height, $source_width, $source_height);
		
			$saveto = get_file_location($file_id,FALSE).$crop_name;
			imagejpeg($thumb,$saveto, 100); 
			return get_file_url($file_id,FALSE).$crop_name;
		}
	}else{
		return FALSE;
	}
	
}

?>