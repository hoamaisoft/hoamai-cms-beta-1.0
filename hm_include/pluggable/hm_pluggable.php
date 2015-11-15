<?php
/**
 * Class này xử lý các plugin cài vào website
 */
if ( ! defined('BASEPATH')) exit('403');

$hmpluggable=array();


Class pluggable extends MySQL{
	
	public $hmpluggable = array();
	
	/** Khởi chạy tất cả plugin đã được active */
	public function run_plugin(){
		
		$tableName=DB_PREFIX."plugin";
		$whereArray=array('active'=>MySQL::SQLValue(1, MySQL::SQLVALUE_NUMBER));

		$this->SelectRows($tableName, $whereArray);
		if( $this->HasRecords() ){
			
			while($row = $this->Row()){
				
				$plugin = $row->key;
				$plugin_location = BASEPATH.HM_PLUGIN_DIR.'/'.$plugin.'/'.$plugin.'.php';
				if(file_exists($plugin_location)){
					
					require_once($plugin_location);
					
				}
				
			}
			
		}
		
	}
	
}