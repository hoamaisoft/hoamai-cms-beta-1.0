<?php
/** 
 * Định nghĩa các hook
 * Vị trí : hm_include/hook.php 
 */
if ( ! defined('BASEPATH')) exit('403');


/**
 * Action Hooks
 */
 

$action_events = array();


/**
 * Cho phép chạy các function theo sự kiện
 */
function hook_action($event)
{
    global $action_events;
 
    if(isset($action_events[$event]))
    {
        foreach($action_events[$event] as $funcs)
        {
            $func=$funcs['function'];
            $args=$funcs['input_array'];
            if(!function_exists($func)) {
                die('Unknown function: '.$func);
            }else{
                call_user_func($func, $args);
            }
        }
    }
 
}


/**
 * Đăng ký function chạy theo sự kiện
 * register_action([sự kiện][function][mảng input cho function])
 */
function register_action($event, $func, $args=array())
{
    global $action_events;
    $action_events[$event][] = array('function'=>$func,'input_array'=>$args);
}


/**
 * Filter Hooks
 */
 
 
$filter_events = array();


/**
 * Cho phép chạy các function filter
 * hook_filter([sự kiện][nội dung sự kiện])
 */
function hook_filter($event,$content) {
	
    global $filter_events;
	
    if(isset($filter_events[$event]))
    {
        foreach($filter_events[$event] as $func) {
            if(!function_exists($func)) {
				
                die('Unknown function: '.$func);
				
            }else{
				
				if( is_array($content) ){
					
					$content = call_user_func($func,json_encode($content));
					
				}else{
					
					$content = call_user_func($func,$content);
					
				}
				
            }
        }
    }
	
    return $content;
}


/**
 * Đăng ký function filter
 * register_filter([sự kiện][function])
 */
function register_filter($event, $func)
{
    global $filter_events;
    $filter_events[$event][] = $func;
}


/**
 * Request Hooks
 */

$hmrequest = array();

/** Đăng ký 1 request cố định */
function register_request($request,$func){
	global $hmrequest;
    $hmrequest[$request] = $func;
}


?>