<?php
/** 
 * Tệp tin xử lý content bằng ajax trong admin
 * Vị trí : admin/content_ajax.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

/** gọi model xử lý content */
require_once( dirname( __FILE__ ) . '/content/content_model.php' );
require_once( dirname( __FILE__ ) . '/chapter/chapter_model.php' );
require_once( dirname( __FILE__ ) . '/taxonomy/taxonomy_model.php' );

$key=hm_get('key');
$id=hm_get('id');
$action=hm_get('action');

switch ($action) {
	
	case 'data':
		echo chapter_show_data($id);
	break;

}


