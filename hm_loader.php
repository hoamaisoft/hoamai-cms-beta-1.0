<?php
/** 
 * Load các thư viện và file cần thiết
 * Vị trí : /hm_loader.php 
 */
if ( ! defined('BASEPATH')) exit('403');
if ( HM_DEBUG ){
	error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );
}else{
	error_reporting(0);
}

session_start(); 

/** Database */
require_once(BASEPATH . HM_INC . '/database.php');

/** Hook */
require_once(BASEPATH . HM_INC . '/hook.php');

/** PHP Class */
require_once(BASEPATH . HM_INC . '/class.Diff.php');
require_once(BASEPATH . HM_INC . '/class.Security.php');

/** Functions */
require_once(BASEPATH . HM_INC . '/functions.php');

/** Input_sanitization */
require_once(BASEPATH . HM_INC . '/input_sanitization.php');

/** String */
require_once(BASEPATH . HM_INC . '/string.php');

/** Upload */
require_once(BASEPATH . HM_INC . '/upload.php');

/** Media */
require_once(BASEPATH . HM_INC . '/media.php');

/** Taxonomy */
require_once(BASEPATH . HM_INC . '/taxonomy.php');

/** Content */
require_once(BASEPATH . HM_INC . '/content.php');

/** Menu */
require_once(BASEPATH . HM_INC . '/menu.php');

/** User */
require_once(BASEPATH . HM_INC . '/user.php');

/** Option */
require_once(BASEPATH . HM_INC . '/option.php');

/** Pluggable */
require_once(BASEPATH . HM_INC . '/pluggable.php');

/** Module */
require_once(BASEPATH . HM_INC . '/module.php');

/** Theme */
require_once(BASEPATH . HM_INC . '/theme.php');

/** Routing */
require_once(BASEPATH . HM_INC . '/routing.php');

/** Init */
require_once(BASEPATH . HM_INC . '/init.php');



?>