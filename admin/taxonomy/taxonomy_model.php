<?php
/** 
 * Tệp tin model của taxonomy trong admin
 * Vị trí : admin/taxonomy/taxonomy_model.php 
 */
if ( ! defined('BASEPATH')) exit('403');


function taxonomy_show_all($key){
	
	global $hmtaxonomy;
	
	$tax=$hmtaxonomy->hmtaxonomy;
	
	if(isset($tax[$key])){
		$args=$tax[$key];
		return $args;
	}else{
		hm_exit( _('Không có kiểu phân loại này') );
	}
	
}

function taxonomy_data($key){
	
	global $hmtaxonomy;
	
	$tax=$hmtaxonomy->hmtaxonomy;

	if(isset($tax[$key])){
		$args=$tax[$key];
		return $args;
	}else{
		hm_exit( _('Không có kiểu phân loại này') );
	}
}

function taxonomy_ajax_add($key,$id_update=NULL){
	
	global $hmtaxonomy;
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	hook_action('taxonomy_ajax_add');
	
	$tax=$hmtaxonomy->hmtaxonomy;
	
	if(isset($tax[$key])){
		
		$this_tax=$tax[$key];

		foreach ( $_POST as $post_key => $post_val ){
			
			$primary = ($this_tax['taxonomy_field']['primary_name_field']['name']);
			
			/** input này được dùng làm khóa tên chính */
			if($post_key==$primary){
				
				$tax_name = $post_val;
				$parent = hm_post('parent');
				if( !is_numeric($parent)) $parent=0;
				if( $parent ==$id_update ) $parent=0;
				
				if(isset($_POST['slug_of_'.$post_key])){
					
					if(is_numeric($id_update)){
						$tax_slug = $_POST['slug_of_'.$post_key];
					}else{
						$tax_slug = add_request_uri_custom($_POST['slug_of_'.$post_key]);
					}
					
				}else{
					
					switch ($_POST['accented_of_'.$post_key]) {
						
						case 'true':
							if(is_numeric($id_update)){
								$tax_slug = sanitize_title_with_accented($post_val);
							}else{
								$tax_slug = add_request_uri_with_accented($post_val);
							}
						break;
						case 'false':
							if(is_numeric($id_update)){
								$tax_slug = sanitize_title($post_val);
							}else{
								$tax_slug = add_request_uri($post_val);
							}
						break;
						default:
							if(is_numeric($id_update)){
								$tax_slug = sanitize_title($post_val);
							}else{
								$tax_slug = add_request_uri($post_val);
							}
					}
					
				}
				
				/** lưu taxonomy vào data base */
				$tableName=DB_PREFIX.'taxonomy';
				
				$values["name"] = MySQL::SQLValue($tax_name);
				$values["slug"] = MySQL::SQLValue($tax_slug);
				$values["key"] = MySQL::SQLValue($key);
				$values["parent"] = MySQL::SQLValue($parent);
				$values["status"] = MySQL::SQLValue('public');

				if(is_numeric($id_update)){
					
					unset($values["status"]);
					$whereArray = array (
											'id'=>$id_update,
										);
					$hmdb->AutoInsertUpdate($tableName, $values, $whereArray);
					$insert_id=$id_update;
					up_date_request_uri_object($tax_slug,$insert_id,'taxonomy','has_object');

				}else{
					$insert_id=$hmdb->InsertRow($tableName, $values);
					up_date_request_uri_object($tax_slug,$insert_id,'taxonomy','null_object');
				}
				
				$latest_array = array(
											'id'=>$insert_id,
											'name'=>$tax_name,
											'slug'=>$tax_slug,
											'key'=>$key,
											'parent'=>$parent,
										);
				
				unset($values);
			}
		}
		
		foreach ( $_POST as $post_key => $post_val ){
				
			/** lưu taxonomy field vào data */
			if(is_numeric($insert_id)){
				
				$tableName=DB_PREFIX.'field';
				
				$values["name"] = MySQL::SQLValue($post_key);
				$values["val"] = MySQL::SQLValue($post_val);
				$values["object_id"] = MySQL::SQLValue($insert_id, MySQL::SQLVALUE_NUMBER);
				$values["object_type"] = MySQL::SQLValue('taxonomy');
				
				if(is_numeric($id_update)){
					
					$whereArray = array (
										'object_id'=>MySQL::SQLValue($id_update, MySQL::SQLVALUE_NUMBER),
										'object_type'=>MySQL::SQLValue('taxonomy'),
										'name'=>MySQL::SQLValue($post_key),
										);
					$hmdb->AutoInsertUpdate($tableName, $values, $whereArray);
					
				}else{
					$hmdb->InsertRow($tableName, $values);
				}
				
				unset($values);
			
			}
		}
		/** show table */
		$status = hm_get('status','public');
		$perpage = hm_get('perpage','30');
		$return_array = taxonomy_show_data($key,$status,$perpage,false);
		$return_array['latest'] = $latest_array;
		return json_encode($return_array,true);
	}
}

function taxonomy_ajax_edit($id){
	
	global $hmcontent;
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	hook_action('taxonomy_ajax_edit');
	
	$tableName=DB_PREFIX."taxonomy";
	$whereArray=array('id'=>MySQL::SQLValue($id));

	$hmdb->SelectRows($tableName, $whereArray);
	if( $hmdb->HasRecords() ){
		
		$row=$hmdb->Row();
		$content_key = $row->key;
		
	}
	
	return taxonomy_ajax_add($content_key,$id);
	
}

function taxonomy_show_data($key,$status,$perpage,$return_json=TRUE){
	
	global $hmtaxonomy;
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	hook_action('taxonomy_show_data');
	
	$request_paged = hm_get('paged',1);
	$paged = $request_paged - 1;
	$offset = $paged * $perpage;
	$limit  = "LIMIT $perpage OFFSET $offset";
	
	if (! $hmdb->Query("SELECT * FROM ".DB_PREFIX."taxonomy WHERE `key` = '$key' AND status = '$status' ORDER BY id DESC $limit")) $hmdb->Kill();

	if( $hmdb->HasRecords() ){
		
		/* Trả về các taxonomy */
		while ($row = $hmdb->Row()) {
			$array_tax[]=array('id'=>$row->id,'name'=>$row->name,'slug'=>$row->slug);
		}
		$array['taxonomy']=$array_tax;
		
		/* Tạo pagination */
		$hmdb->Query(" SELECT * FROM ".DB_PREFIX."taxonomy WHERE `key` = '$key' AND status = '$status' ");
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
	}else{
		$array['taxonomy']=array();
		$array['pagination']=array();
	}
	
	if($return_json==TRUE){
		$return = json_encode($array,TRUE);
		return hook_filter('taxonomy_show_data',$return);
	}else{
		$return = $array;
		return hook_filter('taxonomy_show_data',$return);
	}
	
	
	
}

function taxonomy_select_parent($key,$checked_id=0){
	
	hook_action('taxonomy_select_parent');
	hook_filter('taxonomy_select_parent',$key);
	
	global $hmtaxonomy;
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	$tax=$hmtaxonomy->hmtaxonomy;
	
	$tableName=DB_PREFIX."taxonomy";
	
	/* lấy parent hiện tại */
	$whereArray=array('id'=>MySQL::SQLValue($checked_id));
	$hmdb->SelectRows($tableName, $whereArray);
	if($hmdb->HasRecords()){
		$row = $hmdb->Row();
		$checked = $row->parent;
	}else{
		$checked = 0;
	}
	
	$whereArray=array('key'=>MySQL::SQLValue($key),'status'=>MySQL::SQLValue('public'));
	
	$hmdb->SelectRows($tableName, $whereArray);
	
	$this_tax=$tax[$key];
		
	$options[]=array('value'=>'0','label'=>$this_tax['no_parent']);
	
	while ($row = $hmdb->Row()) {
		$options[]=array('value'=>$row->id,'label'=>$row->name);
	}
	
	$field_array['input_type']='select';
	$field_array['nice_name']=$this_tax['parent_item'];
	$field_array['name']='parent';
	$field_array['input_option']=$options;
	$field_array['default_value']=$checked;
	$field_array['description']=_('Đây là danh mục cấp cha của danh mục bạn sắp tạo, ví dụ như "áo phông" có thể có danh mục cha là "áo" ');
	
	build_input_form($field_array);
}


function taxonomy_ajax_slug(){
	
	if(isset($_POST['val'])){
		
		if( isset($_POST['accented']) AND $_POST['accented']=='true' ){
			return create_request_uri_with_accented($_POST['val'],'',hm_post('object'));
		}elseif( isset($_POST['accented']) AND $_POST['accented']=='false' ){
			return create_request_uri($_POST['val'],'',hm_post('object'));
		}
		
	}
	
}

function quick_edit_tax_form(){
	
	hook_action('quick_edit_tax_form');

	if($_SERVER['HTTP_HOST'] == parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST)){
		
		$args=taxonomy_data(hm_get('key'));
		$args['object_id']=hm_get('id');
		$args['object_type']='taxonomy'; 
	
		require_once(BASEPATH . HM_ADMINCP_DIR . '/' . TEMPLATE_DIR . '/' . 'quick_edit_tax_form.php');
		
	}

}

function taxonomy_checkbox_list($args=array()){
	
	hook_action('taxonomy_checkbox_list');
	hook_filter('taxonomy_checkbox_list_before',$args);
	
	global $hmtaxonomy;
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	@$key = $args['key'];
	@$parent = $args['parent']?$args['parent']:0;
	@$default_value = $args['default_value'];
	@$object_id = $args['object_id'];
	
	if(is_numeric($object_id)){
		$tableName=DB_PREFIX."relationship";
		$whereArray=array(
						'object_id'=>MySQL::SQLValue($object_id),
						'relationship'=>MySQL::SQLValue('contax'),
						);
		$hmdb->SelectRows($tableName, $whereArray);
		while($row = $hmdb->Row()){
			$default_value[] = $row->target_id;
		}
	}
	
	if(!is_array($default_value)) $default_value = array();

	$tax=$hmtaxonomy->hmtaxonomy;
	
	if( isset($tax[$key]) ){
		
		$tableName=DB_PREFIX."taxonomy";
		$whereArray=array(
						'key'=>MySQL::SQLValue($key),
						'parent'=>MySQL::SQLValue($parent),
						'status'=>MySQL::SQLValue('public'),
						);
		$hmdb->SelectRows($tableName, $whereArray);

		if($hmdb->HasRecords()){
		
			if( $parent!=0 ){
				echo '<ul class="taxonomy_tree_sub_group taxonomy_tree_sub_group_of_'.$parent.'">';
			}
			
			while( $row=$hmdb->Row() ){
			
				$taxs[]=$row;
				
			}
			
			foreach ($taxs as $tax){
				
				if( in_array($tax->id,$default_value) ){
					$checked = 'checked';
				}else{
					$checked = '';
				}
			
				echo '<li data-id="'.$tax->id.'" data-slug="'.$tax->slug.'" class="tax_tree_item tax_tree_item_'.$tax->id.'">';
					
					echo '<input type="checkbox" name="taxonomy[]" value="'.$tax->id.'" '.$checked.' /> ';
					echo '<label>'.$tax->name.'</label>';
										
					taxonomy_checkbox_list(array('key'=>$key,'parent'=>$tax->id,'default_value'=>$default_value));
					
				echo '</li>';
				
			}
			
			if( $parent!=0 ){
				echo '</ul>';
			}
		
		}
		
	}
	
}

function taxonomy_delete_permanently($id){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	hook_action('taxonomy_delete_permanently');
	
	if(isset_taxonomy_id($id) == TRUE){
		
		
		$tableName=DB_PREFIX."taxonomy";
		
		/* update lại parent các sub taxonomy */
		$valuesArray=array('parent'=>MySQL::SQLValue(0));
		$whereArray=array('parent'=>MySQL::SQLValue($id));
		$hmdb->UpdateRows($tableName, $valuesArray, $whereArray);
		
		/* xóa bảng taxonomy */
		$whereArray=array('id'=>MySQL::SQLValue($id));
		$hmdb->DeleteRows($tableName, $whereArray);
		
		/* xóa bảng field */
		$tableName=DB_PREFIX."field";
		$whereArray=array(
							'object_id'=>MySQL::SQLValue($id),
							'object_type'=>MySQL::SQLValue('taxonomy'),
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
							'object_type'=>MySQL::SQLValue('taxonomy'),
						 );
		$hmdb->DeleteRows($tableName, $whereArray);
		
	}
	
}


function taxonomy_box($args=array()){

	global $taxonomy_box;
	if(!is_array($taxonomy_box)){$taxonomy_box = array();}
	
	$position = $args['position'];
	$taxonomy_key = $args['taxonomy_key'];
	
		
	foreach ($taxonomy_box as $box){
		if($box['taxonomy_key'] == $taxonomy_key OR ( !isset($box['taxonomy_key']) )){
			
			$label = $box['label'];
			$func = $box['function'];
			
			if($box['position'] == $position ){
			
				echo '<div class="row admin_mainbar_box">';
					echo '<p class="admin_sidebar_box_title ui-sortable-handle" ?>'.$label.'</p>';
					echo '<div class="admin_mainbar_boxtaxonomy">';
					if(function_exists($func)) {
						call_user_func($func, $args);
					}
					echo '</div>';
				echo '</div>';
				
			}
			
		}
	}

}

function taxonomy_ajax_multi($key){
	$action = hm_post('action');
	switch ($action) {
		case 'draft':
			$taxonomy_ids = hm_post('taxonomy_ids');
			foreach($taxonomy_ids as $taxonomy_id){
				
				taxonomy_update_val( array( 'id'=>$taxonomy_id,'value'=>array('status'=>MySQL::SQLValue('draft') ) ) );
				
			}
		break;
		
	}
}


?>