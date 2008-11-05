<?php

require_once('vtlib/Vtiger/Common.inc.php');

class Vtiger_CustomView {

	static function getUniqueId() {
		global $adb;
		return $adb->getUniqueId('vtiger_customview');
	}

	static function create($module, $viewname, $setdefault=false, $setmetrics=false) {
		global $adb;

		$setdefault_val = ($setdefault===true || $setdefault == 'true')? 1 : 0;
		$setmetrics_val = ($setmetrics===true || $setmetrics == 'true')? 1 : 0;

		// Avoid Duplicate Creation
		if(self::check($module, $viewname)) 
			return;

		$cvid = self::getUniqueId();
		$sql = "INSERT INTO vtiger_customview(
			cvid,viewname,setdefault,setmetrics,entitytype)
			VALUES($cvid, '$viewname', $setdefault_val, $setmetrics_val, '$module')";

		$adb->query($sql);

		Vtiger_Utils::Log("Creating CustomView($viewname) for $module ... DONE");
	}

	static function check($module, $viewname) {
		return (self::getId($module,$viewname) != null);
	}

	static function getId($module, $viewname) {
		global $adb;
		$sqlresult = $adb->query("SELECT cvid from vtiger_customview where
			entitytype = '$module' and viewname = '$viewname'");
		return $adb->query_result($sqlresult, 0, 'cvid');
	}

	var $_id;
	function Vtiger_CustomView($module, $viewname) {
		$this->_id = self::getId($module, $viewname);
	}

	function addColumn($vtiger_field, $columnindex=0) {
		global $adb;

		$adb->query(
			"UPDATE vtiger_cvcolumnlist set columnindex=columnindex+1 WHERE cvid=$this->_id AND columnindex>=$columnindex ORDER BY columnindex DESC");
		$split_typeofdata = split('~', $vtiger_field->get('typeofdata'));
		$displayinfo = $vtiger_field->get('module'). '_' . str_replace(' ', '_', $vtiger_field->get('fieldlabel')) . ':' . $split_typeofdata[0];
		$cvcolumnname_value = $vtiger_field->get('tablename').':'.$vtiger_field->get('columnname').':'.$vtiger_field->get('fieldname').':'.$displayinfo;

		$adb->query("INSERT INTO vtiger_cvcolumnlist(cvid, columnindex, columnname) VALUES ($this->_id, $columnindex, '$cvcolumnname_value')");

		Vtiger_Utils::Log("Adding " . $vtiger_field->get('fieldlabel') . " to " . $vtiger_field->get('module') . " CustomView($this->_id) ... DONE");
		
		return $this;
	}
}

?>
