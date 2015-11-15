<?php
/** 
 * Tệp tin xử lý command bằng ajax trong admin
 * Vị trí : admin/command_ajax.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

if( $_SERVER['HTTP_HOST'] != parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) ){
	hm_exit('403 - Truy cập bị từ chối');
}

/** Gọi các model */
require_once( dirname( __FILE__ ) . '/plugin/plugin_model.php' );
 
/** Bắt đầu */ 
require(BASEPATH . HM_INC . '/json-rpc.php');

if (function_exists('xdebug_disable')) {
    xdebug_disable();
}

class Hmcommand {
		
	public function plugin($parameter1=NULL,$parameter2=NULL) {
		
		switch ($parameter1) {
			case 'showall':
				
				$return = '';
				
				$available_plugin = json_decode( available_plugin(),TRUE );
				$plugins = $available_plugin['plugins'];
				
				foreach ($plugins as $key => $val){
					
					if( is_plugin_active($key) ){ $key = '[active] '.$key; }else{ $key = '[deactive] '.$key; }
					
					$return[] = "	".$key."\n";
					
				}
				
				return (implode('',$return));
				
			break;
			case 'active':
				
				if( isset($parameter2) ){
				
					$return = disable_plugin('1',$parameter2);
					$args = json_decode($return,TRUE);
					return $args['mes'];
					
				}else{
					
					$return = '';
				
					$active_plugin = json_decode( list_plugin(1),TRUE );
					
					foreach ($active_plugin as $key => $val){
						
						$return[] = "	".$val."\n";
						
					}
					
					return (implode('',$return));
					
				}
				
			break;
			case 'deactive':
				
				if( isset($parameter2) ){
				
					return disable_plugin('0',$parameter2);
					$args = json_decode($return,TRUE);
					return $args['mes'];
					
				}else{
					
					$return = '';
				
					$active_plugin = json_decode( list_plugin(0),TRUE );
					
					foreach ($active_plugin as $key => $val){
						
						$return[] = "	".$val."\n";
						
					}
					
					return (implode('',$return));
					
				}
				
			break;
			case 'drop':
				
				if( isset($parameter2) ){
				
					return drop_plugin($parameter2);
					$args = json_decode($return,TRUE);
					return $args['mes'];
					
				}else{
					
					$mes = _('Vui lòng nhập plugin key muốn xóa');
					return $mes;
					
				}
				
			break;
			default :
				$help = "	plugin showall : "._('Hiển thị tất cả plugin hiện có')."\n".
						"	plugin active : "._('Hiển thị tất cả plugin đang chạy')."\n".
						"	plugin active [pluginkey] : "._('Kích hoạt plugin [pluginkey]')."\n".
						"	plugin deactive : "._('Hiển thị tất cả plugin đang tắt')."\n".
						"	plugin deactive [pluginkey] : "._('Tắt plugin [pluginkey]')."\n".
						"	plugin drop [pluginkey]: "._('Xóa plugin [pluginkey]')."\n".
						""."\n";
				return $help;
		}
		
	}
	
	public function content($parameter1=NULL,$parameter2=NULL) {
		
		switch ($parameter1) {
			
			case 'type':
			
				global $hmcontent;
				
				if( isset($parameter2) ){
				
					$con=$hmcontent->hmcontent;

					if(isset($con[$parameter2])){
					
						$args=$con[$parameter2];
						foreach ($args as $key => $val){
							
							if( is_array($val) ){
							
								$return[] = "	".hm_array_to_list($key,$val);
								
							}else{
							
								$return[] = "	".$key." : ".$val."\n";
							
							}
							
						}
						
						return (implode('',$return));
						
					}
					
				}else{
				
					$content_type = $hmcontent->hmcontent;
					
					foreach ($content_type as $key => $val){
							
						$return[] = "	".$key."\n";
						
					}
					
					return (implode('',$return));
				
				}
				
				
			break;
			default :
				$help = "	content type : "._('Hiển thị tất cả content type')."\n".
						"	content type ['content key'] : "._('Hiển thị chi tiết content type')."\n".
						""."\n";
				return $help;
		}
		
	}
}

handle_json_rpc(new Hmcommand());