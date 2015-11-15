<?php
/** 
 * Tệp tin function trong admin
 * Vị trí : admin/function.php 
 */
if ( ! defined('BASEPATH')) exit('403');

function hm_admin_head(){
	
	hook_action('hm_admin_head');
	
}



function admin_menu_con_tax(){
	
	global $hmtaxonomy;
	global $hmcontent;
	
	hook_action('admin_menu_con_tax_before');
	
	foreach($hmcontent->hmcontent as $con){
	
		$content_key = $con['content_key'];
		
		
		echo '<li><a href="?run=content.php&key='.$con['content_key'].'">'.$con['content_name'].'<span class="sub_icon fa fa-pencil"></span></a>'."\n";
			echo '<ul>'."\n";
				echo '<li><a href="?run=content.php&key='.$con['content_key'].'&status=public">'.$con['all_items'].'</a>'."\n";
				echo '<li><a href="?run=content.php&key='.$con['content_key'].'&action=add">'.$con['add_new_item'].'</a>'."\n";
				foreach($hmtaxonomy->hmtaxonomy as $tax){
					if($tax['content_key']==$content_key){
						echo '<li><a href="?run=taxonomy.php&key='.$tax['taxonomy_key'].'&status=public">'.$tax['all_items'].'</a>'."\n";
						echo '<li><a href="?run=taxonomy.php&key='.$tax['taxonomy_key'].'&action=add">'.$tax['add_new_item'].'</a>'."\n";
					}
				}
			echo '</ul>'."\n";
		echo '</li>'."\n";
	}	
	
	hook_action('admin_menu_con_tax_after');
	
}

function admin_menu_user(){
	
	hook_action('admin_menu_user_before');
	
	echo '<li><a href="?run=user.php">'._('Thành viên').'<span class="sub_icon fa fa-user"></span></a>'."\n";
		echo '<ul>'."\n";
			echo '<li><a href="?run=user.php">'._('Danh sách thành viên').'</a>'."\n";
			echo '<li><a href="?run=user.php&action=add">'._('Thêm thành viên').'</a>'."\n";
			echo '<li><a href="?run=user.php&action=edit">'._('Sửa thông tin cá nhân').'</a>'."\n";
		echo '</ul>'."\n";
	echo '</li>'."\n";
	
	hook_action('admin_menu_user_after');
}

function admin_menu_plugin(){
	
	hook_action('admin_menu_plugin_before');
	
	echo '<li><a href="?run=plugin.php">'._('Gói mở rộng').'<span class="sub_icon fa fa-puzzle-piece"></span></a>'."\n";
		echo '<ul>'."\n";
			echo '<li><a href="?run=plugin.php">'._('Gói mở rộng khả dụng').'</a>'."\n";
			echo '<li><a href="?run=plugin.php&action=add">'._('Thêm gói mở rộng').'</a>'."\n";
		echo '</ul>'."\n";
	echo '</li>'."\n";
	
	hook_action('admin_menu_plugin_after');
}

function admin_menu_theme(){
	
	hook_action('admin_menu_theme_before');
	
	echo '<li><a href="?run=theme.php">'._('Giao diện').'<span class="sub_icon fa fa-paint-brush"></span></a>'."\n";
		echo '<ul>'."\n";
			echo '<li><a href="?run=theme.php">'._('Giao diện khả dụng').'</a>'."\n";
			echo '<li><a href="?run=theme.php&action=add">'._('Thêm giao diện mới').'</a>'."\n";
			echo '<li><a href="?run=menu.php">'._('Quản lý trình đơn').'</a>'."\n";
		echo '</ul>'."\n";
	echo '</li>'."\n";
	
	hook_action('admin_menu_theme_after');
}

function admin_menu_command(){
	
	hook_action('admin_menu_command_before');
	
	echo '<li><a href="?run=command.php">'._('Sử dụng lệnh').'<span class="sub_icon fa fa-terminal"></span></a>'."\n";

	echo '</li>'."\n";
	
	hook_action('admin_menu_command_after');
}