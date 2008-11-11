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

	function addRule($vtiger_field, $comparator, $comparevalue, $columnindex=0) {
		global $adb;

		if(empty($comparator)) return $this;

		$comparator = $this->translateComparator($comparator);

		$adb->query(
			"UPDATE vtiger_cvadvfilter set columnindex=columnindex+1 WHERE cvid=$this->_id AND columnindex>=$columnindex ORDER BY columnindex DESC");
		$split_typeofdata = split('~', $vtiger_field->get('typeofdata'));
		$displayinfo = $vtiger_field->get('module'). '_' . str_replace(' ', '_', $vtiger_field->get('fieldlabel')) . ':' . $split_typeofdata[0];
		$cvcolumnname_value = $vtiger_field->get('tablename').':'.$vtiger_field->get('columnname').':'.$vtiger_field->get('fieldname').':'.$displayinfo;

		$adb->pquery("INSERT INTO vtiger_cvadvfilter(cvid, columnindex, columnname, comparator, value) VALUES(?,?,?,?,?)",
			Array($this->_id, $columnindex, $cvcolumnname_value, $comparator, $comparevalue));

		Vtiger_Utils::Log("Adding Condition $comparator for " . $vtiger_field->get('fieldlabel') . " of " . $vtiger_field->get('module') . " CustomView($this->_id) ... DONE");
		
		return $this;
	}

	/**
	 * Translate comparator (condition) to long or short form
	 * Used in PackageExport also.
	 */
	static function translateComparator($value, $tolongform=false) {
		$comparator = false;
		if($tolongform) {
			$comparator = strtolower($value);
			if($comparator == 'e') $comparator = 'EQUALS';
			else if($comparator == 'n') $comparator = 'NOT_EQUALS';
			else if($comparator == 's') $comparator = 'STARTS_WITH';
			else if($comparator == 'ew') $comparator = 'ENDS_WITH';
			else if($comparator == 'c') $comparator = 'CONTAINS';
			else if($comparator == 'k') $comparator = 'DOES_NOT_CONTAINS';
			else if($comparator == 'l') $comparator = 'LESS_THAN';
			else if($comparator == 'g') $comparator = 'GREATER_THAN';
			else if($comparator == 'm') $comparator = 'LESS_OR_EQUAL';
			else if($comparator == 'h') $comparator = 'GREATER_OR_EQUAL';
		} else {
			$comparator = strtoupper($value);
			if($comparator == 'EQUALS') $comparator = 'e';
			else if($comparator == 'NOT_EQUALS') $comparator = 'n';
			else if($comparator == 'STARTS_WITH') $comparator = 's';
			else if($comparator == 'ENDS_WITH') $comparator = 'ew';
			else if($comparator == 'CONTAINS') $comparator = 'c';
			else if($comparator == 'DOES_NOT_CONTAINS') $comparator = 'k';
			else if($comparator == 'LESS_THAN') $comparator = 'l';
			else if($comparator == 'GREATER_THAN') $comparator = 'g';
			else if($comparator == 'LESS_OR_EQUAL') $comparator = 'm';
			else if($comparator == 'GREATER_OR_EQUAL') $comparator = 'h';
		}
		return $comparator;
	}
}

?>
