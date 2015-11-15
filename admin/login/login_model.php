<?php
/** 
 * Tệp tin model của login trong admin
 * Vị trí : admin/login/login_model.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/** Đăng nhập admin cp */
function admin_cp_login(){
	
	global $hmuser;
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	hook_action('admin_cp_login');
	
	$user_login = hm_post('login');
	$password = hm_post('password');
	$logmein = hm_post('log-me-in');

	if( is_numeric($logmein) ){
	
		$tableName=DB_PREFIX."users";
		$whereArray=array('user_login'=>MySQL::SQLValue($user_login));
		$hmdb->SelectRows($tableName, $whereArray);
		if( $hmdb->HasRecords() ){
			
			$row=$hmdb->Row();
			$salt = $row->salt;
			$user_pass = $row->user_pass;
			
			$password_encode = hm_encode_str(md5($password.$salt));
			
			if($password_encode == $user_pass){
				
				$time = time();
				$ip = hm_ip();
				
				$cookie_array = array(
										'time'=>$time,
										'ip'=>$ip,
										'user_login'=>$user_login,
										'admincp'=>'yes',
									);
				$cookie_user = hm_encode_str($cookie_array);
				
				setcookie('admin_login', $cookie_user ,time() + COOKIE_EXPIRES, '/');
				$_SESSION['admin_login'] = $cookie_user;
				
				return json_encode(array( 'status'=>'success','mes'=>_('Đăng nhập thành công') ));
				
			}else{
				
				return json_encode(array( 'status'=>'error','mes'=>_('Sai mật khẩu') ));
			
			}
			
		}else{
			return json_encode(array( 'status'=>'error','mes'=>_('Không có tài khoản này') ));
		}
	
	}	
		
}

/** Đăng xuất admin cp */
function admin_cp_logout(){
	$back = hm_get('back',SITE_URL);
	setcookie('admin_login', $_SESSION['admin_login'], 1);
	unset($_SESSION['admin_login']);
	header('Location: '.$back);
	hm_edit('Đang thoát tài khoản');
}