<?php
/** 
 * Tệp tin chứa những hàm cơ bản
 * Vị trí : hm_include/functions.php 
 */
 
if ( ! defined('BASEPATH')) exit('403');
 
/** load class */
$hmdiff = new Diff();
$hmsecurity = new Security();


if (!function_exists('_')) {
    function _($str) {
        return $str;
    }
}

function hm_get($parameter,$default = NULL){
	
	if(isset($_GET[$parameter])){
		return $_GET[$parameter];
	}else{
		return $default;
	}
	
}

function hm_post($parameter,$default = NULL){

	if(isset($_POST[$parameter])){
		if(is_array($_POST[$parameter])){
			return $_POST[$parameter];
		}else{
			return $_POST[$parameter];
		}
	}else{
		return $default;
	}
	
}

function hm_exit($str=NULL){
	
	@header('Content-Type: text/html; charset=utf-8');
	
	?>
		<head>
			<title>Lỗi xử lý</title>
			<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
		</head>
		<body>
			<style>
				body {
				  margin: 0px;
				  padding: 0px;
				  font-family: Arial;
				}
				.error_content {
				  width: 100%;
				  height: 100%;
				  position: relative;
				}
				p.error {
				  width: 800px;
				  position: absolute;
				  top: 0px;
				  left: 0px;
				  right: 0px;
				  bottom: 0px;
				  margin: auto;
				  height: 36px;
				  line-height: 36px;
				  text-align: center;
				  background: #e14d43;
				  font-family: Arial;
				  color: #ffffff;
				  font-size: 14px;
				  padding: 10px 20px 10px 20px;
				  text-decoration: none;
				  border-radius: 2px;
				}
			</style>
			<div class="error_content">
				<p class="error"><?php echo $str; ?></p>
			</div>
		</body>
	<?php
	
	exit();
	
}

function hm_ip(){
	
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
	
}

function hm_include($file){
	
	if(file_exists($file)){
		include ($file);
	}
	
}

function hm_encode($str=NULL,$key=ENCRYPTION_KEY){
	
	global $hmsecurity;
	
	$encoded = $hmsecurity->encrypt($str, $key);
	
	return $encoded;
	
}

function hm_decode($str=NULL,$key=ENCRYPTION_KEY){
	
	global $hmsecurity;
	
	$decoded = $hmsecurity->decrypt($str, $key);
	
	return $decoded;
	
}

function hm_encode_str($str=NULL,$key=ENCRYPTION_KEY){
	
	if(is_array($str)){$str = json_encode($str);}
	$encoded = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $str, MCRYPT_MODE_CBC, md5(md5($key))));
	return $encoded;
	
}

function hm_decode_str($str=NULL,$key=ENCRYPTION_KEY){
	
	$decoded = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($str), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
	return $decoded;
	
}


function build_input_form($field_array){
	
	hook_action('build_input_form'); 
	
	if($field_array['name']){ 
		
		if(!isset($field_array['object_id']) OR !is_numeric($field_array['object_id']) ) $field_array['object_id']='0';
		if(!isset($field_array['nice_name'])) $field_array['nice_name']='';
		if(!isset($field_array['object_type'])) $field_array['object_type']='';
		if(!isset($field_array['description'])) $field_array['description']='';
		if(!isset($field_array['placeholder'])) $field_array['placeholder']='';
		if(!isset($field_array['default_value'])) $field_array['default_value']='';
		if(!isset($field_array['addClass'])) $field_array['addClass']=NULL;
		if(!isset($field_array['input_type'])) $field_array['input_type']='text';
		if(!isset($field_array['create_slug'])) $field_array['create_slug']=FALSE;
		if(!isset($field_array['required'])) $field_array['required']=FALSE;
		if(!isset($field_array['handle'])) $field_array['handle']=TRUE;
		if($field_array['required']==TRUE) $field_array['required']='required';
		if(!isset($field_array['input_option'])) $field_array['input_option']=array();
		if(!is_array($field_array['input_option'])) $field_array['input_option']=array();
		
		switch ($field_array['input_type']) {
			
			case 'text':
				
				input_text($field_array);
				
			break;
			
			case 'hidden':
				
				input_hidden($field_array);
				
			break;
			
			case 'request_uri':
				
				input_request_uri($field_array);
				
			break;
			
			case 'number':
				
				input_number($field_array);
				
			break;
			
			case 'password':
			
				input_password($field_array);
				
			break;
			
			case 'textarea':
			
				input_textarea($field_array);
				
			break;
			
			case 'wysiwyg':
			
				echo input_editor($field_array);
			
			break;
			
			case 'select':
			
				input_select($field_array);
			
			break;
			
			case 'multiimage':
			
				input_multiimage($field_array);
			
			break;
	
		}
	}
	
}

function input_text($field_array=array()){
	
	hook_action('input_text');
	
	if($field_array['create_slug']==TRUE){

		$data_slug='slug-input="slug_of_'.$field_array['name'].'" slug-accented="accented_of_'.$field_array['name'].'" object-id="'.$field_array['object_id'].'"';
		$addClass=' input_have_slug field_'.$field_array['object_id'].' '.$field_array['addClass'];
	
	}else{
		
		$hav_slug='';
		$addClass=' '.$field_array['addClass'];
		$data_slug='';
		
	}
	
	echo '<div class="form-group">'."\n";
	if($field_array['handle']){
	echo '	<div class="form-group-handle"></div>';
	}
	if($field_array['nice_name']!=''){
		echo '	<label for="'.$field_array['name'].'">'.$field_array['nice_name'].'</label>'."\n";
	}
	if($field_array['description']!=''){
		echo '	<p class="input_description">'.$field_array['description'].'</p>'."\n";
	}
	echo '	<input '.$data_slug.' '.$field_array['required'].' name="'.$field_array['name'].'" type="text" class="form-control'.$addClass.'" id="'.$field_array['name'].'" placeholder="'.$field_array['placeholder'].'" value="'.str_replace('"','&quot;',$field_array['default_value']).'">'."\n";
	
	
	if($field_array['create_slug']==TRUE){
	
		build_input_slug($field_array['name'],$field_array['nice_name'],$field_array['object_id'],$field_array['object_type']);
		
	}
	
	echo '</div>'."\n";
	
}


function input_request_uri($field_array=array()){
	
	hook_action('input_request_uri');
	
	$addClass=' '.$field_array['addClass'];
	
	echo '<div class="form-group">'."\n";
	if($field_array['handle']){
	echo '	<div class="form-group-handle"></div>';
	}
	if($field_array['nice_name']!=''){
		echo '	<label for="'.$field_array['name'].'">'.$field_array['nice_name'].'</label>'."\n";
	}
	if($field_array['description']!=''){
		echo '	<p class="input_description">'.$field_array['description'].'</p>'."\n";
	}
	echo '	<input '.$data_slug.' '.$field_array['required'].' type="text" class="form-control request_uri'.$addClass.'" data-input="'.$field_array['name'].'" placeholder="'.$field_array['placeholder'].'" >'."\n";
	echo '	<input '.$data_slug.' '.$field_array['required'].' name="'.$field_array['name'].'" type="hidden" class="form-control request_uri" value="'.str_replace('"','&quot;',$field_array['default_value']).'">'."\n";
	echo '  <ul class="auto_suggest_result auto_suggest_of_'.$field_array['name'].'"></ul>';
	
	echo '</div>'."\n";
	
}

function input_hidden($field_array=array()){
	
	hook_action('input_hidden');
	
	echo '<div class="form-group">'."\n";
	if($field_array['handle']){
	echo '	<div class="form-group-handle"></div>';
	}
	if($field_array['nice_name']!=''){
		echo '	<label for="'.$field_array['name'].'">'.$field_array['nice_name'].'</label>'."\n";
	}
	if($field_array['description']!=''){
		echo '	<p class="input_description">'.$field_array['description'].'</p>'."\n";
	}
	echo '	<input '.$field_array['required'].' name="'.$field_array['name'].'" type="hidden" class="form-control '.$field_array['addClass'].'" id="'.$field_array['name'].'" placeholder="'.$field_array['placeholder'].'" value="'.$field_array['default_value'].'">'."\n";
	echo '</div>'."\n";
	
}

function input_number($field_array=array()){
	
	hook_action('input_number');
	
	echo '<div class="form-group">'."\n";
	if($field_array['handle']){
	echo '	<div class="form-group-handle"></div>';
	}
	if($field_array['nice_name']!=''){
		echo '	<label for="'.$field_array['name'].'">'.$field_array['nice_name'].'</label>'."\n";
	}
	if($field_array['description']!=''){
		echo '	<p class="input_description">'.$field_array['description'].'</p>'."\n";
	}
	echo '	<input '.$field_array['required'].' name="'.$field_array['name'].'" type="number" class="form-control '.$field_array['addClass'].'" id="'.$field_array['name'].'" placeholder="'.$field_array['placeholder'].'" value="'.$field_array['default_value'].'">'."\n";
	echo '</div>'."\n";
	
}

function input_password($field_array=array()){
	
	hook_action('input_password');
	
	echo '<div class="form-group">'."\n";
	if($field_array['handle']){
	echo '	<div class="form-group-handle"></div>';
	}
	if($field_array['nice_name']!=''){
		echo '	<label for="'.$field_array['name'].'">'.$field_array['nice_name'].'</label>'."\n";
	}
	if($field_array['description']!=''){
		echo '	<p class="input_description">'.$field_array['description'].'</p>'."\n";
	}
	echo '	<input '.$field_array['required'].' name="'.$field_array['name'].'" type="password" class="form-control'.$field_array['addClass'].'" id="'.$field_array['name'].'" placeholder="'.$field_array['placeholder'].'" value="'.$field_array['default_value'].'">'."\n";
	echo '</div>'."\n";
	
}

function input_textarea($field_array=array()){
	
	hook_action('input_textarea');
	
	echo '<div class="form-group">'."\n";
	if($field_array['handle']){
	echo '	<div class="form-group-handle"></div>';
	}
	if($field_array['nice_name']!=''){
		echo '	<label for="'.$field_array['name'].'">'.$field_array['nice_name'].'</label>'."\n";
	}
	if($field_array['description']!=''){
		echo '	<p class="input_description">'.$field_array['description'].'</p>'."\n";
	}
	echo '	<textarea '.$field_array['required'].' name="'.$field_array['name'].'" class="form-control '.$field_array['addClass'].'" id="'.$field_array['name'].'">'.$field_array['default_value'].'</textarea>'."\n";
	echo '</div>'."\n";
	
}

function input_editor($field_array=array()){
	
	hook_action('input_editor');
	
	$field_array = hook_filter('input_editor_input',$field_array);
	
	$return='';
	
	if( $field_array['addClass'] != NULL ){
		
		$addClass=$field_array['addClass'];
	
	}else{
		
		$addClass='wysiwyg';
		
	}
	
	$default_value = $field_array['default_value'];
	$default_value = str_replace('&lt;','&amp;lt;',$default_value);
	$default_value = str_replace('&gt;','&amp;gt;',$default_value);
	$default_value = str_replace('<pre>','&lt;pre&gt;',$default_value);
	$default_value = str_replace('</pre>','&lt;/pre&gt;',$default_value);

	
	$return = $return.'<div class="form-group">'."\n";
	if($field_array['handle']){
	$return = $return.'	<div class="form-group-handle"></div>';
	}
	$return = $return.'	<label for="'.$field_array['name'].'">'.$field_array['nice_name'].'</label>'."\n";
	$return = $return.'	<button id="'.$field_array['name'].'" multi="false" imageonly="true" type="button" class="btn btn-default media_btn btn-xs" data-toggle="modal" data-target="#media_box_modal">'."\n";
	$return = $return.'		<span class="glyphicon glyphicon-picture"></span> '._('Thư viện')."\n";
	$return = $return.'	</button>'."\n";
	$return = $return.'	<p class="input_description">'.$field_array['description'].'</p>'."\n";
	$return = $return.'	<textarea '.$field_array['required'].' name="'.$field_array['name'].'" class="'.$addClass.'" id="'.$field_array['name'].'">'.$default_value.'</textarea>'."\n";
	$return = $return.'</div>'."\n";
	
	$return = hook_filter('input_editor_output',$return);
	
	return $return;

}

function input_select($field_array=array()){
	
	hook_action('input_select');
	
	echo '<div class="form-group">'."\n";
	if($field_array['handle']){
	echo '	<div class="form-group-handle"></div>';
	}
	if($field_array['nice_name']!=''){
		echo '	<label for="'.$field_array['name'].'">'.$field_array['nice_name'].'</label>'."\n";
	}
	if($field_array['description']!=''){
		echo '	<p class="input_description">'.$field_array['description'].'</p>'."\n";
	}
	echo '	<select name="'.$field_array['name'].'" class="form-control '.$field_array['addClass'].'" >'."\n";
			foreach($field_array['input_option'] as $option){
				if( $option['value'] == $field_array['default_value'] ){
					$selected='selected="selected"';
				}else{
					$selected='';
				}
	echo '		<option '.$selected.' value="'.$option['value'].'">'.$option['label'].'</option>';			
			}
	echo '	</select>'."\n";
	echo '</div>'."\n";

}

function media_file_input($args=NULL){
	
	if(is_array($args)){
					
		@$id=$args['name'];	
		@$label=$args['label'];
		@$default_value=$args['default_value'];
		@$multi=$args['multi'];
		@$imageonly=$args['imageonly'];
		
		global $hmdb;
		
		$tableName=DB_PREFIX.'media';
		$hmdb->Query("SELECT * FROM `$tableName` WHERE `id` IN($default_value) ");
		
		if ($hmdb->HasRecords()) {
			
			$thumbnail_src = array();
			
			while($row = $hmdb->Row()){
				
				$file_id = $row->id;
				$file_info = $row->file_info;
				$file_name = $row->file_name;
				$file_folder = $row->file_folder;
				if($file_folder!='/'){
					$file_folder = '/'.$file_folder.'/';
				}
				$file_info = json_decode($file_info,TRUE);
				if($file_info['file_is_image']==TRUE){
					$thumbnail_src[$file_id] = SITE_URL.FOLDER_PATH.HM_CONTENT_DIR.'/uploads'.$file_folder.$file_info['thumbnail'];
				}else{
					$file_src_name_ext = strtolower ($file_info['file_src_name_ext']);
					$file_ext_icon = './'.HM_CONTENT_DIR.'/icon/fileext/'.$file_src_name_ext.'.png';
					if( file_exists($file_ext_icon) ){
						$thumbnail_src[$file_id] = SITE_URL.FOLDER_PATH.HM_CONTENT_DIR.'/icon/fileext/'.$file_src_name_ext.'.png';
					}else{
						$thumbnail_src[$file_id] = SITE_URL.FOLDER_PATH.HM_CONTENT_DIR.'/icon/fileext/blank.png';
					}
				}
				
			}

		}else{
			$thumbnail_src = NULL;
		}
		
		if($multi==TRUE){
			$multi_class=' preview_media_file_multi';
			$multi_data='multi="true"';
		}else{
			$multi_data='multi="false"';
		}
		
		if($imageonly==TRUE){
			$imageonly_class=' preview_media_file_imageonly';
			$imageonly_data='imageonly="true"';
		}else{
			$imageonly_data='imageonly="false"';
		}
		
		echo '<div class="form-group">'."\n";
		echo '<div class="preview_media_file'.$multi_class.' '.$imageonly_class.'" use_media_file="'.$id.'">'."\n";
			if(is_array($thumbnail_src)){
				foreach($thumbnail_src as $file_id => $thumb){
					echo '<div class="preview_media_file_wapper" file-id="'.$file_id.'" use_media_file="'.$id.'">'."\n";
					echo '<div class="preview_media_file_remove" file-id="'.$file_id.'" use_media_file="'.$id.'"><i class="fa fa-remove"></i></div>'."\n";
					echo '<img file-id="'.$file_id.'" src="'.$thumb.'" />'."\n";
					echo '</div>'."\n";
				}
			}
		echo '</div>'."\n";
		echo '<span id="'.$id.'" '.$multi_data.' '.$imageonly_data.' class="use_media_file btn btn-default btn-xs" data-toggle="modal" data-target="#media_box_modal">'."\n";
		echo 	_($label);
		echo '</span>'."\n";
		echo '<input use_media_file="'.$id.'" type="hidden" name="'.$id.'" value="'.$default_value.'" />'."\n";		
		echo '</div>'."\n";
				
	}
	
}

function build_input_slug($val_name,$val_nicename,$object_id,$object_type){
	
	hook_action('build_input_slug');
	
	$checked_true=NULL;
	$checked_false=NULL;
	$slug=NULL;
	
	if(is_numeric($object_id) AND $object_id!=0 ){
		
		if($object_type=='taxonomy'){
			$slug = get_tax_val(array('name'=>'slug_of_'.$val_name,'id'=>$object_id));
			$checked = get_tax_val(array('name'=>'accented_of_'.$val_name,'id'=>$object_id));
		}
		if($object_type=='content'){
			$slug = get_con_val(array('name'=>'slug_of_'.$val_name,'id'=>$object_id));
			$checked = get_con_val(array('name'=>'accented_of_'.$val_name,'id'=>$object_id));
		}
		
		if($checked=='true'){$checked_true='checked="checked"';}
		if($checked=='false'){$checked_false='checked="checked"';}
		if($checked=='html'){$checked_html='checked="checked"';}
		
	}
	
	echo '<div class="form-group-sub">'."\n";
	echo '	<label for="slug_of_'.$val_name.'">'._('Slug').' ('._('tự động tạo từ').' '.$val_nicename.')</label>'."\n";
	echo '	<p class="input_description">'._('Slug là đường dẫn thân thiện với các bộ máy tìm kiếm, nó sẽ tự động tạo nếu bạn bỏ trống').'</p>'."\n";
	echo '	<input autocomplete="off" required value="'.$slug.'" name="slug_of_'.$val_name.'" type="text" class="form-control ajax_slug slug_of_'.$val_name.' slug_of_'.$val_name.'_'.$object_id.'" id="slug_of_'.$val_name.'" >'."\n";
	echo '</div>'."\n";
	
	echo '<div class="form-group-sub">'."\n";
	echo '	<label class="radio-inline">'."\n";
	echo '		<input '.$checked_true.' type="radio" slug-input="slug_of_'.$val_name.'" data-field-name="'.$val_name.'" data-field-object="'.$object_id.'" class="accented accented_of_'.$val_name.'" name="accented_of_'.$val_name.'" value="true" >Giữ lại dấu</input>'."\n";
	echo '	</label>'."\n";
	echo '	<label class="radio-inline">'."\n";
	echo '		<input '.$checked_false.' type="radio" slug-input="slug_of_'.$val_name.'" data-field-name="'.$val_name.'" data-field-object="'.$object_id.'" class="accented accented_of_'.$val_name.'" name="accented_of_'.$val_name.'" value="false" >Xóa dấu</input>'."\n";
	echo '	</label>'."\n";
	echo '</div>'."\n";
	
}

function win8_loading($ball=8){
	
	echo '<div class="windows8_loading">';
		for($i=1;$i<=$ball;$i++){
			
			echo '<div class="wBall" id="wBall_'.$i.'">';
				echo '<div class="wInnerBall"></div>';
			echo '</div>';
			
		}
	echo '</div>';
	
}

function wave_loading(){
	
	echo '<div class="bubblingG">';
		for($i=1;$i<=3;$i++){
			
			echo '<span id="bubblingG_'.$i.'"></span>';
			
		}
	echo '</div>';
	
}

function input_time($field_array=array()){
	
	if(!isset($field_array['default_value'])) $field_array['default_value']='';
	
	
	$time = time();
	
	if(isset($field_array['default_value']['day'])){
		$day = $field_array['default_value']['day'];
	}else{
		$day = date('d',$time);
	} 
	
	if(isset($field_array['default_value']['month'])){
		$month = $field_array['default_value']['month'];
	}else{
		$month = date('m',$time);
	} 
	
	if(isset($field_array['default_value']['year'])){
		$year = $field_array['default_value']['year'];
	}else{
		$year = date('Y',$time);
	} 
	
	if(isset($field_array['default_value']['hour'])){
		$hour = $field_array['default_value']['hour'];
	}else{
		$hour = date('H',$time);
	} 
	
	if(isset($field_array['default_value']['minute'])){
		$minute = $field_array['default_value']['minute'];
	}else{
		$minute = date('i',$time);
	} 

	$selected = '';
	
	echo '		<input type="text" name="day" id="day" value="'.$day.'" />';
	echo '		<select name="month" id="month" class="col-md-6">';
					if($month == '01') {$selected = 'selected';}else{$selected = '';};
	echo '			<option value="01" '.$selected.'>'._('01-Tháng 1').'</option>';
					if($month == '02') {$selected = 'selected';}else{$selected = '';};
	echo '			<option value="02" '.$selected.'>'._('02-Tháng 2').'</option>';
					if($month == '03') {$selected = 'selected';}else{$selected = '';};
	echo '			<option value="03" '.$selected.'>'._('03-Tháng 3').'</option>';
					if($month == '04') {$selected = 'selected';}else{$selected = '';};
	echo '			<option value="04" '.$selected.'>'._('04-Tháng 4').'</option>';
					if($month == '05') {$selected = 'selected';}else{$selected = '';};
	echo '			<option value="05" '.$selected.'>'._('05-Tháng 5').'</option>';
					if($month == '06') {$selected = 'selected';}else{$selected = '';};
	echo '			<option value="06" '.$selected.'>'._('06-Tháng 6').'</option>';
					if($month == '07') {$selected = 'selected';}else{$selected = '';};
	echo '			<option value="07" '.$selected.'>'._('07-Tháng 7').'</option>';
					if($month == '08') {$selected = 'selected';}else{$selected = '';};
	echo '			<option value="08" '.$selected.'>'._('08-Tháng 8').'</option>';
					if($month == '09') {$selected = 'selected';}else{$selected = '';};
	echo '			<option value="09" '.$selected.'>'._('09-Tháng 9').'</option>';
					if($month == '10') {$selected = 'selected';}else{$selected = '';};
	echo '			<option value="10" '.$selected.'>'._('10-Tháng 10').'</option>';
					if($month == '11') {$selected = 'selected';}else{$selected = '';};
	echo '			<option value="11" '.$selected.'>'._('11-Tháng 11').'</option>';
					if($month == '12') {$selected = 'selected';}else{$selected = '';};
	echo '			<option value="12" '.$selected.'>'._('12-Tháng 12').'</option>';
	echo '		</select>';
	echo '		<input type="text" name="year" id="year" value="'.$year.'" />';
	echo '		<span>@</span>';
	echo '		<input type="text" name="hour" id="hour" value="'.$hour.'" />';
	echo '		<span> : </span>';
	echo '		<input type="text" name="minute" id="minute" value="'.$minute.'" />';

					
}

function input_multiimage($field_array=array()){
	
	$field_array['multi'] = TRUE;
	
	media_file_input($field_array);
	
}

function register_admin_setting_page($args=array()){
	
	hook_action('register_admin_setting_page');
	
	global $setting_page;
	
	if(is_array($args)){
		$setting_page[$args['key']] = $args;
	}
	
}

function get_admin_setting_page(){
	
	hook_action('get_admin_setting_page');
	
	global $setting_page;
	
	return $setting_page;
	
}

function hm_array_to_list( $key = '', $val = array(), $prefix = '' ){

	$return[] = $prefix.$key." : \n";
	
	if(is_array($val)){
		$prefix = $prefix.'-';
		foreach ($val as $sub_key => $sub_val){
			
			if( is_array($sub_val) ){
				
				$new_prefix =  $prefix.'-';
				$return[] = "	".hm_array_to_list($sub_key,$sub_val,$new_prefix);
				
			}else{

				$return[] = "	".$prefix.$sub_key." : ".$sub_val."\n";
			
			}
		
		}
	}
	
	return (implode('',$return));
	
}

/** Lấy user đang login */
function user_name(){
	
	$session_admin_login = $_SESSION['admin_login'];
	$json = hm_decode_str($session_admin_login);
	$array = json_decode($json,TRUE);
	return $array['user_login'];
	
}

/** Xóa thư mục và files bên trong */
function DeleteDir($path){
    if (is_dir($path) === true){
        $files = array_diff(scandir($path), array('.', '..'));
        foreach ($files as $file){
            DeleteDir(realpath($path) . '/' . $file);
        }
        return rmdir($path);
    }
    else if (is_file($path) === true){
        return unlink($path);
    }
    return false;
}
?>