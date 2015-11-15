<?php 
/** Định nghĩa BASEPATH là thư mục gốc chứa hm_header.php */
define( 'BASEPATH', dirname(__FILE__) . '/' );

if( !file_exists(BASEPATH . 'hm_config.php') ){
	
	/** Gọi file install */
	require_once(BASEPATH . '/hm_include/install.php');	
	
}else{
	/** Định nghĩa phiên bản */
	define('HM_VERSION_NAME', 'Beta 1');
	define('HM_VERSION_NUMBER', 1);

	/** Load file cấu hình */
	require_once('hm_config.php');

	/** Gọi file load các thư viện */
	require_once('hm_loader.php');

	/** Xây dưng website */
	require_once('hm_setup.php');
	
	/** Chạy website dựa trên truy vấn URL */
	require_once('hm_routing.php');
}
?>

