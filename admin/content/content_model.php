<?php
/** 
 * Tệp tin model của content trong admin
 * Vị trí : admin/content/content_model.php 
 */
if ( ! defined('BASEPATH')) exit('403');


function content_show_all($key){
	
	global $hmcontent;
	
	$con=$hmcontent->hmcontent;
	
	if(isset($con[$key])){
		$args=$con[$key];
		return $args;
	}else{
		hm_exit( _('Không có kiểu nội dung này') );
	}
	
}

function content_data($key){
	
	global $hmcontent;
	
	$con=$hmcontent->hmcontent;

	if(isset($con[$key])){
		$args=$con[$key];
		return $args;
	}else{
		hm_exit( _('Không có kiểu nội dung này') );
	}
}


function content_ajax_add($args=array()){
	
	global $hmcontent;
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	hook_action('content_ajax_add');
	
	if( isset($args['content_key']) ){$key = $args['content_key'];}else{$key = NULL;}
	if( isset($args['id_update']) ){$id_update = $args['id_update'];}else{$id_update = NULL;}
	if( isset($args['parent']) ){$parent = $args['parent'];}else{$parent = 0;}
	if( isset($args['status']) ){$status = $args['status'];}else{$status = 'public';}
	
	$con=$hmcontent->hmcontent;
	if(isset($_POST['taxonomy'])){
		$method_post_taxonomy = $_POST['taxonomy'];
	}else{
		$method_post_taxonomy = array();
	}
	
	if( ($key!=NULL) AND isset($con[$key]) ){
		
		$this_con=$con[$key];

		foreach ( $_POST as $post_key => $post_val ){
			
			$primary = ($this_con['content_field']['primary_name_field']['name']);
			
			/** input này được dùng làm khóa tên chính */
			if($post_key==$primary){
				
				$con_name = $post_val;
				
				if(isset($_POST['slug_of_'.$post_key])){
					
					if(is_numeric($id_update)){
						$con_slug = $_POST['slug_of_'.$post_key];
						$con_slug = str_replace('#','-',$con_slug);
					}else{
						$con_slug = add_request_uri_custom($_POST['slug_of_'.$post_key]);
					}
					
				}else{
					
					switch ($_POST['accented_of_'.$post_key]) {
						
						case 'true':
							if(is_numeric($id_update)){
								$con_slug = sanitize_title_with_accented($post_val);
							}else{
								$con_slug = add_request_uri_with_accented($post_val);
							}
						break;
						case 'false':
							if(is_numeric($id_update)){
								$con_slug = sanitize_title($post_val);
							}else{
								$con_slug = add_request_uri($post_val);
							}
						break;
						default:
							if(is_numeric($id_update)){
								$con_slug = sanitize_title($post_val);
							}else{
								$con_slug = add_request_uri($post_val);
							}
					}
					
				}
				
				/** lưu content vào database */
				$tableName=DB_PREFIX.'content';

				$values["name"] = MySQL::SQLValue($con_name);
				$values["slug"] = MySQL::SQLValue($con_slug);
				$values["key"] = MySQL::SQLValue($key);
				$values["status"] = MySQL::SQLValue($status);
				$values["parent"] = MySQL::SQLValue($parent);
				
				if(is_numeric($id_update)){
					
					$args_con=content_data_by_id($id_update);
					
					$key=$args_con['content']->key;
					$status=$args_con['content']->status;
					$parent=$args_con['content']->parent;
					
					$values["key"] = MySQL::SQLValue($key);
					$values["status"] = MySQL::SQLValue($status);
					$values["parent"] = MySQL::SQLValue($parent);
						
					$whereArray = array (
											'id'=>$id_update,
										);
					$hmdb->AutoInsertUpdate($tableName, $values, $whereArray);
					$insert_id=$id_update;
					up_date_request_uri_object($con_slug,$insert_id,'content','has_object');
					
				}else{
					$insert_id=$hmdb->InsertRow($tableName, $values);
					up_date_request_uri_object($con_slug,$insert_id,'content','null_object');
				}
				
				unset($values);
			}
		}
		
		foreach ( $_POST as $post_key => $post_val ){
			
			/** lưu content field vào data */
			if(is_numeric($insert_id)){
				if(is_array($post_val)){ $post_val = json_encode($post_val); }
					$tableName=DB_PREFIX.'field';
					
					$values["name"] = MySQL::SQLValue($post_key);
					$values["val"] = MySQL::SQLValue($post_val);
					$values["object_id"] = MySQL::SQLValue($insert_id, MySQL::SQLVALUE_NUMBER);
					$values["object_type"] = MySQL::SQLValue('content');
					
					if(is_numeric($id_update)){
						
						$whereArray = array (
											'object_id'=>MySQL::SQLValue($id_update, MySQL::SQLVALUE_NUMBER),
											'object_type'=>MySQL::SQLValue('content'),
											'name'=>MySQL::SQLValue($post_key),
											);
						$hmdb->AutoInsertUpdate($tableName, $values, $whereArray);
						
						
					}else{
						$hmdb->InsertRow($tableName, $values);
					}
					
					unset($values);
				
			}
		}
		
		/** Lưu content time */
		if(is_numeric($insert_id)){
				
			$day = $_POST['day']; if(!is_numeric($day)) $day = 0;
			$month = $_POST['month']; if(!is_numeric($month)) $month = 0;
			$year = $_POST['year']; if(!is_numeric($year)) $year = 0;
			$hour = $_POST['hour']; if(!is_numeric($hour)) $hour = 0;
			$minute = $_POST['minute']; if(!is_numeric($minute)) $minute = 0;
			
			$public_time = strtotime($day.'-'.$month.'-'.$year.' '.$hour.':'.$minute);
			
			$values["name"] = MySQL::SQLValue('public_time');
			$values["val"] = MySQL::SQLValue($public_time);
			$values["object_id"] = MySQL::SQLValue($insert_id, MySQL::SQLVALUE_NUMBER);
			$values["object_type"] = MySQL::SQLValue('content');
			
			if(is_numeric($id_update)){
				
				$whereArray = array (
									'object_id'=>MySQL::SQLValue($id_update, MySQL::SQLVALUE_NUMBER),
									'object_type'=>MySQL::SQLValue('content'),
									'name'=>MySQL::SQLValue('public_time'),
									);
				$hmdb->AutoInsertUpdate($tableName, $values, $whereArray);	
				
			}else{
				$hmdb->InsertRow($tableName, $values);
			}
			
			unset($values);
		
		}
		
		/** lưu relationship content - taxonomy */
		$tableName=DB_PREFIX.'relationship';
		
		if( isset($method_post_taxonomy) AND is_array($method_post_taxonomy) ){
				
			foreach ( $method_post_taxonomy as $taxonomy_id ){
				
				$values["object_id"] = MySQL::SQLValue($insert_id, MySQL::SQLVALUE_NUMBER);
				$values["target_id"] = MySQL::SQLValue($taxonomy_id, MySQL::SQLVALUE_NUMBER);
				$values["relationship"] = MySQL::SQLValue('contax');
				
				if(is_numeric($id_update)){
					
					$hmdb->AutoInsertUpdate($tableName, $values, $values);	
					
				}else{
					$hmdb->InsertRow($tableName, $values);
				}
				
				unset($values);
			}
			
		}
		
		/** Gỡ bỏ contax đã bỏ chọn */
		if(is_numeric($id_update)){
			
			$id_deletes = array();
			
			$whereArray = array (
									'object_id'=>MySQL::SQLValue($insert_id),
									'relationship'=>MySQL::SQLValue('contax'),
								);
			$hmdb->SelectRows($tableName, $whereArray);
			
			if( $hmdb->HasRecords() ){
				
				while( $row=$hmdb->Row() ){
				
					$id_relationship = $row->id;
					$target_id = $row->target_id;
					
					if(!in_array ($target_id,$method_post_taxonomy) ){
						
						$id_deletes[] = $id_relationship;
						
					}
					
				}
				
			}else{
				$id_deletes = array();
			}
			
			foreach($id_deletes as $id_delete){
				
				$whereArray = array (
									'id'=>MySQL::SQLValue($id_delete, MySQL::SQLVALUE_NUMBER),
								);
				$hmdb->DeleteRows($tableName, $whereArray);
				
			}
		}
		
		/** Gỡ bỏ contag cũ */
		if(is_numeric($id_update)){
			
				$whereArray = array (
									'object_id'=>MySQL::SQLValue($id_update, MySQL::SQLVALUE_NUMBER),
									'relationship'=>MySQL::SQLValue('contag'),
								);
				$hmdb->DeleteRows($tableName, $whereArray);
			
		}
		
		/** lưu tags vào data */
		if( isset($_POST['tags']) ){
			
			$tags=explode(',',$_POST['tags']);
			
			$tags=array_map("trim", $tags);
			
			foreach($tags as $tag){
				/** Lưu tag vào bảng taxonomy */
				
				$tableName = DB_PREFIX.'taxonomy';
				$tag_slug = sanitize_title($tag);
				
				$values["name"] = MySQL::SQLValue($tag);
				$values["slug"] = MySQL::SQLValue($tag_slug);
				$values["key"] = MySQL::SQLValue('tag');
				
				$whereArray=array('key'=>MySQL::SQLValue('tag'),'slug'=>MySQL::SQLValue($tag_slug));
				
				$hmdb->AutoInsertUpdate($tableName, $values, $whereArray);
				
				unset($values);
				
				/** lưu relationship content - tag */
				$tableName=DB_PREFIX."taxonomy";
				$whereArray=array('key'=>MySQL::SQLValue('tag'),'slug'=>MySQL::SQLValue($tag_slug));
	
				$hmdb->SelectRows($tableName, $whereArray);
				if( $hmdb->HasRecords() ){
					
					$row = $hmdb->Row();
					$tableName=DB_PREFIX.'relationship';
				
					$values["object_id"] = MySQL::SQLValue($insert_id, MySQL::SQLVALUE_NUMBER);
					$values["target_id"] = MySQL::SQLValue($row->id, MySQL::SQLVALUE_NUMBER);
					$values["relationship"] = MySQL::SQLValue('contag');
					
					if(is_numeric($id_update)){
					
						$hmdb->AutoInsertUpdate($tableName, $values, $values);	
						
					}else{
						$hmdb->InsertRow($tableName, $values);
					}
					
					unset($values);
					
				} 
				
			}
			
		}
		
		/** show latest */
		$latest=array(	
						'id'=>$insert_id,
						'name'=>$con_name,
						'slug'=>$con_slug,
						'key'=>$key,
					);
		return json_encode(array('latest'=>$latest));
	}
}

function content_ajax_add_chapter($id){
	
	global $hmcontent;
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	hook_action('content_ajax_add_chapter');
	
	if(isset_content_id($id) == TRUE){
		
		$args_con=content_data_by_id($id);
		$key=$args_con['content']->key;
		$args = array(
						'content_key'=>$key,
						'parent'=>$id,
						'status'=>'chapter',
					 );
        return content_ajax_add($args);
		
	}
	
}

function content_ajax_edit($id){
	
	global $hmcontent;
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	hook_action('content_ajax_edit');
	
	$tableName=DB_PREFIX."content";
	$whereArray=array('id'=>MySQL::SQLValue($id));
	
	$hmdb->SelectRows($tableName, $whereArray);
	if( $hmdb->HasRecords() ){
		
		$row=$hmdb->Row();
		$content_key = $row->key;
		
		/** lưu bản revision content vào database */
		content_create_revision($id);
	
		/** Hoàn thành lưu bản chỉnh sửa - Lưu bản cập nhật mới*/
		$args = array(
						'content_key'=>$content_key,
						'id_update'=>$id,
					 );
		return content_ajax_add($args);
	
	}
}

function content_create_revision($id){
	
	global $hmcontent;
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	hook_action('content_create_revision');
	$id = hook_filter('content_create_revision',$id);
	
	$tableName=DB_PREFIX."content";
	$whereArray=array('id'=>MySQL::SQLValue($id));

	$hmdb->SelectRows($tableName, $whereArray);
	if( $hmdb->HasRecords() ){
		
		$row=$hmdb->Row();
		$content_key = $row->key;
		
		$values["name"] = MySQL::SQLValue($row->name);
		$values["slug"] = MySQL::SQLValue($row->slug);
		$values["key"] = MySQL::SQLValue($row->key);
		$values["status"] = MySQL::SQLValue('revision');
		$values["parent"] = MySQL::SQLValue($row->id);

		$insert_revision_id=$hmdb->InsertRow($tableName, $values);
		
		unset($values);
		
		/** lưu field của bản revision content vào database */
		
		$tableName=DB_PREFIX."field";
		$whereArray=array(
							'object_id'=>MySQL::SQLValue($id),
							'object_type'=>MySQL::SQLValue('content')
						 );

		$hmdb->SelectRows($tableName, $whereArray);
		
		if( $hmdb->HasRecords() ){
			$count = $hmdb->RowCount();
			$i=1;
			while( $row = $hmdb->Row() ){
				
				$values[$i]["name"] = MySQL::SQLValue($row->name);
				$values[$i]["val"] = MySQL::SQLValue($row->val);
				$values[$i]["object_id"] = MySQL::SQLValue($insert_revision_id);
				$values[$i]["object_type"] = MySQL::SQLValue('content');

				$i++;
			}
			foreach($values as $value){
				$hmdb->InsertRow($tableName, $value);
			}
		}
		
		/** lưu relationship của bản revision content vào database */
		
		$tableName=DB_PREFIX."relationship";
		$whereArray=array(
							'object_id'=>MySQL::SQLValue($id),
						 );

		$hmdb->SelectRows($tableName, $whereArray);

		if( $hmdb->HasRecords() ){
			$count = $hmdb->RowCount();
			$i=1;
			while( $row = $hmdb->Row() ){
				
				$values[$i]["relationship"] = MySQL::SQLValue($row->relationship);
				$values[$i]["target_id"] = MySQL::SQLValue($row->target_id);
				$values[$i]["object_id"] = MySQL::SQLValue($insert_revision_id);

				$i++;
			}
			foreach($values as $value){
				$hmdb->InsertRow($tableName, $value);
			}
		}
		
		return $insert_revision_id;
	}else{
		return FALSE;
	}
	
}

function content_show_data($key,$status,$perpage){
	
	global $hmcontent;
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	hook_action('content_show_data');
	
	$request_paged = hm_get('paged',1);
	$paged = $request_paged - 1;
	$offset = $paged * $perpage;
	$limit  = "LIMIT $perpage OFFSET $offset";
	
	if (! $hmdb->Query("SELECT * FROM ".DB_PREFIX."content WHERE `key` = '$key' AND status = '$status' ORDER BY id DESC $limit")) $hmdb->Kill();
	
	if( $hmdb->HasRecords() ){
		
		/* Trả về các content */
		while ($row = $hmdb->Row()) {
			$array_con[]=array('id'=>$row->id,'name'=>$row->name,'slug'=>$row->slug);
		}
		$array['content']=$array_con;
		
		/* Tạo pagination */
		$hmdb->Query(" SELECT * FROM ".DB_PREFIX."content WHERE `key` = '$key' AND status = '$status' ");
		$total_item  = $hmdb->RowCount();
		$total_page = ceil($total_item / $perpage);
		$first = '1';
		if($request_paged > 1){
			$previous = $request_paged - 1;
		}else{
			$previous = $first;
		}
		if($request_paged < $total_page){
			$next = $request_paged + 1;
		}else{
			$next = $total_page;
		}

		
		$array['pagination']=array(
								'first'=>$first,
								'previous'=>$previous,
								'next'=>$next,
								'last'=>$total_page,
								'total'=>$total_item,
								'paged'=>$request_paged,
		);
		
		$all_content = $hmcontent->hmcontent;
		if( isset($all_content[$key]['chapter']) AND $all_content[$key]['chapter'] == TRUE ){
			$array['chapter']=TRUE;
		}else{
			$array['chapter']=FALSE;
		}
		
	}else{
		$array['content']=array();
		$array['pagination']=array();
		$array['chapter']=FALSE;
	}
	
	return hook_filter('content_show_data',json_encode($array,TRUE));
	
}

function content_ajax_slug(){

	if(isset($_POST['val'])){
		if( isset($_POST['accented']) AND $_POST['accented']=='true' ){
			return create_request_uri_with_accented(hm_post('val'),'',hm_post('object'));
		}elseif( isset($_POST['accented']) AND $_POST['accented']=='false' ){
			return create_request_uri(hm_post('val'),'',hm_post('object'));
		}
	}
	
}

function content_delete_permanently($id){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	hook_action('content_delete_permanently');
	
	if(is_numeric($id)){
		
		/* xóa bảng content */
		$tableName=DB_PREFIX."content";
		$whereArray=array(
							'id'=>MySQL::SQLValue($id),
						 );
		$hmdb->DeleteRows($tableName, $whereArray);
		
		/** Tìm các sub content */
		$whereArray=array(
							'parent'=>MySQL::SQLValue($id),
						 );
		$hmdb->SelectRows($tableName, $whereArray);
		
		$ids = array();
		while( $row = $hmdb->Row() ){
			$ids[] = $row->id;
		}
		
		foreach($ids as $delete_id){
			/** xóa các sub content */
			$tableName=DB_PREFIX."content";
			$whereArray=array(
							'id'=>MySQL::SQLValue($delete_id),
						 );
			$hmdb->DeleteRows($tableName, $whereArray);
			/** xóa các sub content field */
			$tableName=DB_PREFIX."field";
			$whereArray=array(
							'object_id'=>MySQL::SQLValue($delete_id),
							'object_type'=>MySQL::SQLValue('content'),
						 );
			$hmdb->DeleteRows($tableName, $whereArray);
			/* xóa các relationship */
			$tableName=DB_PREFIX."relationship";
			$whereArray=array(
								'object_id'=>MySQL::SQLValue($delete_id),
							 );
			$hmdb->DeleteRows($tableName, $whereArray);
			/** xóa các sub request */
			$tableName=DB_PREFIX."request_uri";
			$whereArray=array(
							'object_id'=>MySQL::SQLValue($delete_id),
						 );
			$hmdb->DeleteRows($tableName, $whereArray);
		}
		
		/* xóa bảng field */
		$tableName=DB_PREFIX."field";
		$whereArray=array(
							'object_id'=>MySQL::SQLValue($id),
							'object_type'=>MySQL::SQLValue('content'),
						 );
		$hmdb->DeleteRows($tableName, $whereArray);
		
		/* xóa bảng relationship */
		$tableName=DB_PREFIX."relationship";
		$whereArray=array(
							'object_id'=>MySQL::SQLValue($id),
						 );
		$hmdb->DeleteRows($tableName, $whereArray);
		
		/* xóa bảng request uri */
		$tableName=DB_PREFIX."request_uri";
		$whereArray=array(
							'object_id'=>MySQL::SQLValue($id),
							'object_type'=>MySQL::SQLValue('content'),
						 );
		$hmdb->DeleteRows($tableName, $whereArray);
		
	}
	
}


function content_box($args=array()){

	global $content_box;
	
	$position = $args['position'];
	$content_key = $args['content_key'];
	
		
	foreach ($content_box as $box){
		if($box['content_key'] == $content_key OR ( !isset($box['content_key']) )){
			
			$label = $box['label'];
			$func = $box['function'];
			
			if($box['position'] == $position ){
			
				echo '<div class="row admin_mainbar_box">';
					echo '<p class="admin_sidebar_box_title ui-sortable-handle" ?>'.$label.'</p>';
					echo '<div class="admin_mainbar_boxcontent">';
					if(function_exists($func)) {
						call_user_func($func, $args);
					}
					echo '</div>';
				echo '</div>';
				
			}
			
		}
	}

}

?>