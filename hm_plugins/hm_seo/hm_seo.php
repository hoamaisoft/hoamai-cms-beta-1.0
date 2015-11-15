<?php
/*
Plugin Name: Hoa Mai SEO;
Description: Thêm các thông tin SEO cho content	và taxonomy;
Version: 1.0;
Version Number: 1;
*/
/* 
Tạo content box
*/
$args=array(
	'label'=>'SEO',
	'position'=>'right',
	'function'=>'seo_content_box',
);
register_content_box($args);
function seo_content_box(){
?>
	<div role="tabpanel">
	  <!-- Nav tabs -->
	  <ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#meta" aria-controls="meta" role="tab" data-toggle="tab"><?php echo _('Các thẻ meta'); ?></a></li>
		<li role="presentation"><a href="#sitemap" aria-controls="sitemap" role="tab" data-toggle="tab"><?php echo _('Sitemap'); ?></a></li>
	  </ul>
	  <!-- Tab panes -->
	  <div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="meta">
			<?php seo_box_meta(); ?>
		</div>
		<div role="tabpanel" class="tab-pane" id="sitemap">
			<?php seo_box_sitemap(); ?>
		</div>
	  </div>
	</div>
<?php
}
/* 
Tạo ô nhập các thẻ meta
*/
function seo_box_meta(){
	$args = array(
		'nice_name'=>'Title',
		'name'=>'title',
		'input_type'=>'text',
		'required'=>FALSE,
	);
	if(hm_get('run') == 'taxonomy.php'){
		$args['default_value'] = get_tax_val(array('name'=>'title','id'=>hm_get('id')));
	}elseif(hm_get('run') == 'content.php'){
		$args['default_value'] = get_con_val(array('name'=>'title','id'=>hm_get('id')));
	}
	
	build_input_form($args);
	
	$args = array(
		'nice_name'=>'Meta description',
		'name'=>'meta_description',
		'input_type'=>'textarea',
		'required'=>FALSE,
	);
	if(hm_get('run') == 'taxonomy.php'){
		$args['default_value'] = get_tax_val(array('name'=>'meta_description','id'=>hm_get('id')));
	}elseif(hm_get('run') == 'content.php'){
		$args['default_value'] = get_con_val(array('name'=>'meta_description','id'=>hm_get('id')));
	}
	build_input_form($args);
	
	$args = array(
		'nice_name'=>'Meta keywords',
		'name'=>'meta_keywords',
		'input_type'=>'text',
		'required'=>FALSE,
	);
	if(hm_get('run') == 'taxonomy.php'){
		$args['default_value'] = get_tax_val(array('name'=>'meta_keywords','id'=>hm_get('id')));
	}elseif(hm_get('run') == 'content.php'){
		$args['default_value'] = get_con_val(array('name'=>'meta_keywords','id'=>hm_get('id')));
	}
	build_input_form($args);
}
/* 
Xuất hiện trong sitemap ?
*/
function seo_box_sitemap(){
	/* include */
	$args = array(
		'nice_name'=>'Có trong sitemap',
		'name'=>'include_to_sitemap',
		'input_type'=>'select',
		'input_option'=>array(
								array('value'=>'yes','label'=>'Có'),
								array('value'=>'no','label'=>'Không'),
							),
	);
	if(hm_get('run') == 'taxonomy.php'){
		$args['default_value'] = get_tax_val(array('name'=>'include_to_sitemap','id'=>hm_get('id')));
	}elseif(hm_get('run') == 'content.php'){
		$args['default_value'] = get_con_val(array('name'=>'include_to_sitemap','id'=>hm_get('id')));
	}
	build_input_form($args);
	
	/* Change Frequency */
	$args = array(
		'nice_name'=>'Change Frequency',
		'name'=>'sitemap_change_frequency',
		'input_type'=>'select',
		'input_option'=>array(
								array('value'=>'auto','label'=>'Mặc định'),
								array('value'=>'always','label'=>'always'),
								array('value'=>'hourly','label'=>'hourly'),
								array('value'=>'daily','label'=>'daily'),
								array('value'=>'weekly','label'=>'weekly'),
								array('value'=>'monthly','label'=>'monthly'),
								array('value'=>'yearly','label'=>'yearly'),
								array('value'=>'never','label'=>'never'),
							),
	);
	if(hm_get('run') == 'taxonomy.php'){
		$args['default_value'] = get_tax_val(array('name'=>'sitemap_change_frequency','id'=>hm_get('id')));
	}elseif(hm_get('run') == 'content.php'){
		$args['default_value'] = get_con_val(array('name'=>'sitemap_change_frequency','id'=>hm_get('id')));
	}
	build_input_form($args);
	
	/* Priority */
	$args = array(
		'nice_name'=>'Priority',
		'name'=>'sitemap_priority',
		'input_type'=>'select',
		'input_option'=>array(
								array('value'=>'auto','label'=>'Mặc định'),
								array('value'=>'0.0','label'=>'0.0'),
								array('value'=>'0.1','label'=>'0.1'),
								array('value'=>'0.2','label'=>'0.2'),
								array('value'=>'0.3','label'=>'0.3'),
								array('value'=>'0.4','label'=>'0.4'),
								array('value'=>'0.5','label'=>'0.5'),
								array('value'=>'0.6','label'=>'0.6'),
								array('value'=>'0.7','label'=>'0.7'),
								array('value'=>'0.8','label'=>'0.8'),
								array('value'=>'0.9','label'=>'0.9'),
								array('value'=>'1.0','label'=>'1.0'),
							),
	);
	if(hm_get('run') == 'taxonomy.php'){
		$args['default_value'] = get_tax_val(array('name'=>'sitemap_priority','id'=>hm_get('id')));
	}elseif(hm_get('run') == 'content.php'){
		$args['default_value'] = get_con_val(array('name'=>'sitemap_priority','id'=>hm_get('id')));
	}
	build_input_form($args);
}
/* 
Tạo taxonomy box
*/
$args=array(
	'label'=>'SEO',
	'position'=>'left',
	'function'=>'seo_taxonomy_box',
);
register_taxonomy_box($args);
function seo_taxonomy_box(){
?>
	<div role="tabpanel">
	  <!-- Nav tabs -->
	  <ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#meta" aria-controls="meta" role="tab" data-toggle="tab"><?php echo _('Các thẻ meta'); ?></a></li>
		<li role="presentation"><a href="#sitemap" aria-controls="sitemap" role="tab" data-toggle="tab"><?php echo _('Sitemap'); ?></a></li>
	  </ul>
	  <!-- Tab panes -->
	  <div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="meta">
			<?php seo_box_meta(); ?>
		</div>
		<div role="tabpanel" class="tab-pane" id="sitemap">
			<?php seo_box_sitemap(); ?>
		</div>
	  </div>
	</div>
<?php
}
/* 
Đăng ký trang plugin setting
*/
$args=array(
	'label'=>'SEO',
	'key'=>'hm_seo_main_setting',
	'function'=>'hm_seo_main_setting',
	'function_input'=>array(),
	'child_of'=>FALSE,
);
register_admin_setting_page($args);
function hm_seo_main_setting(){
	
	if(isset($_POST['save_seo_setting'])){
		
		foreach($_POST as $key => $value){
			
			$args = array(
							'section'=>'hm_seo',
							'key'=>$key,
							'value'=>$value,
						);
			
			set_option($args);
			
		}
	
	}
	
	hm_include(BASEPATH.'/'.HM_PLUGIN_DIR.'/hm_seo/layout/main_setting.php');
}
/* 
Hook action để hiển thị title, meta_description & meta_keywords
*/
register_filter('hm_title','hm_seo_display_title');
function hm_seo_display_title($title){
	global $hmcontent;
	global $hmtaxonomy;

	$request = get_current_uri();
	$request_data = get_uri_data( array('uri'=>$request) );
	if($request == ''){
		$home_title = get_option( array('section'=>'hm_seo','key'=>'home_title') );
		if($home_title!=''){
			return '<title>'.$home_title.'</title>'."\n\r";
		}else{
			return $title;
		}
	}else{
		if( $request_data != FALSE ){
			
			$object_type = $request_data->object_type;
			$object_id = $request_data->object_id;
			
			switch ($object_type) {
				case 'content':
					$seo_title = get_con_val("name=title&id=".$object_id);
					if($seo_title!=''){
						return '<title>'.$seo_title.'</title>'."\n\r";
					}else{
						return $title;
					}
				break;
				case 'taxonomy':
					$seo_title = get_tax_val("name=title&id=".$object_id);
					if($seo_title!=''){
						return '<title>'.$seo_title.'</title>'."\n\r";
					}else{
						return $title;
					}
				break;
			}
			 
		}else{
			return $title;
		}
	}
}


register_action('after_hm_head','hm_seo_display_meta_description');
function hm_seo_display_meta_description(){
	global $hmcontent;
	global $hmtaxonomy;
	
	$request = get_current_uri();
	$request_data = get_uri_data( array('uri'=>$request) );
	if( $request_data != FALSE ){
		
		$object_type = $request_data->object_type;
		$object_id = $request_data->object_id;
		
		switch ($object_type) {
			case 'content':
				$description = get_con_val("name=meta_description&id=".$object_id);
				echo '<meta name="description" content="'.$description.'" />'."\n\r";
			break;
			case 'taxonomy':
				$description = get_tax_val("name=meta_description&id=".$object_id);
				echo '<meta name="description" content="'.$description.'" />'."\n\r";
			break;
		}
		 
	}else{
		return FALSE;
	}
}

register_action('after_hm_head','hm_seo_display_meta_keywords');
function hm_seo_display_meta_keywords(){
	global $hmcontent;
	global $hmtaxonomy;
	
	$request = get_current_uri();
	$request_data = get_uri_data( array('uri'=>$request) );
	if( $request_data != FALSE ){
		
		$object_type = $request_data->object_type;
		$object_id = $request_data->object_id;
		
		switch ($object_type) {
			case 'content':
				$keywords = get_con_val("name=meta_keywords&id=".$object_id);
				echo '<meta name="keywords" content="'.$keywords.'" />'."\n\r";
			break;
			case 'taxonomy':
				$keywords = get_tax_val("name=meta_keywords&id=".$object_id);
				echo '<meta name="keywords" content="'.$keywords.'" />'."\n\r";
			break;
		}
		 
	}else{
		return FALSE;
	}
}


/**
 * Đăng ký request cho sitemap
*/

register_request('sitemap.xml','hm_seo_sitemap');
function hm_seo_sitemap(){
	hm_include(BASEPATH.'/'.HM_PLUGIN_DIR.'/hm_seo/layout/sitemap.php');
}

/**
 * Đăng ký request cho robots.txt
*/

register_request('robots.txt','hm_seo_robotstxt');
function hm_seo_robotstxt(){
	hm_include(BASEPATH.'/'.HM_PLUGIN_DIR.'/hm_seo/layout/robots.php');
}

?>