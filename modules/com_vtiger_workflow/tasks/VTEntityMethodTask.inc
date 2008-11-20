<?php
require_once('modules/com_vtiger_workflow/VTEntityMethodManager.inc');
class VTEntityMethodTask extends VTTask{
	
	public $executeImmediately = true;
	
	public function getFieldNames(){return array('methodName');}
	public function doTask($module, $data){
		global $adb;
		$emm = new VTEntityMethodTask($adb);
		$emm->executeMethod($module, $this->methodName);
	}
}
?>