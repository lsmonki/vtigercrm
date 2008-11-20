<?php

include_once('vtlib/Vtiger/Utils.php');

class Vtiger_Filter {
	var $id;
	var $name;
	var $isdefault;

	var $inmetrics = false;
	var $entitytype= false;

	var $module;

	function __construct() {
	}

	function __getUniqueId() {
		global $adb;
		return $adb->getUniqueId('vtiger_customview');
	}

	function initialize($valuemap, $moduleInstance=false) {
		$this->id = $valuemap[cvid];
		$this->name= $valuemap[viewname];
		$this->module=$moduleInstance? $moduleInstance: Vtiger_Module::getInstance($valuemap[tabid]);
	}

	function __create($moduleInstance) {
		global $adb;
		$this->module = $moduleInstance;

		$this->id = $this->__getUniqueId();
		$this->isdefault = ($this->isdefault===true||$this->isdefault=='true')?1:0;
		$this->inmetrics = ($this->inmetrics===true||$this->inmetrics=='true')?1:0;

		$adb->pquery("INSERT INTO vtiger_customview(cvid,viewname,setdefault,setmetrics,entitytype) VALUES(?,?,?,?,?)", Array($this->id, $this->name, $this->isdefault, $this->inmetrics, $this->module->name));
		
		self::log("Creating Filter $this->name ... DONE");
	}

	function __update() {
		self::log("Updating Filter $this->name ... DONE");
	}

	function save($moduleInstance=false) {
		if($this->id) $this->__update();
		else $this->__create($moduleInstance);
		return $this->id;
	}

	function __getColumnValue($fieldInstance) {
		$tod = split('~', $fieldInstance->typeofdata);
		$displayinfo = $fieldInstance->getModuleName().'_'.str_replace(' ','_',$fieldInstance->label).':'.$tod[0];
		$cvcolvalue = "$fieldInstance->table:$fieldInstance->column:$fieldInstance->name:$displayinfo";
		return $cvcolvalue;
	}

	function addField($fieldInstance, $index=0) {
		global $adb;

		$cvcolvalue = $this->__getColumnValue($fieldInstance);

		$adb->pquery("UPDATE vtiger_cvcolumnlist SET columnindex=columnindex+1 WHERE cvid=? AND columnindex>=? ORDER BY columnindex DESC", 
			Array($this->id, $index));
		$adb->pquery("INSERT INTO vtiger_cvcolumnlist(cvid,columnindex,columnname) VALUES(?,?,?)", Array($this->id, $index, $cvcolvalue));

		$this->log("Adding $fieldInstance->name to $this->name filter ... DONE");
		return $this;
	}

	function addRule($fieldInstance, $comparator, $comparevalue, $index=0) {
		global $adb;

		if(empty($comparator)) return $this;

		$comparator = self::translateComparator($comparator);
		$cvcolvalue = $this->__getColumnValue($fieldInstance);

		$adb->pquery("UPDATE vtiger_cvadvfilter set columnindex=columnindex+1 WHERE cvid=? AND columnindex>=? ORDER BY columnindex DESC",
			Array($this->id, $index));		
		$adb->pquery("INSERT INTO vtiger_cvadvfilter(cvid, columnindex, columnname, comparator, value) VALUES(?,?,?,?,?)",
			Array($this->id, $index, $cvcolvalue, $comparator, $comparevalue));

		Vtiger_Utils::Log("Adding Condition " . self::translateComparator($comparator,true) ." on $fieldInstance->name of $this->name filter ... DONE");
		
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

	static function log($message) {
		Vtiger_Utils::Log($message);
	}

	static function getInstance($value, $moduleInstance=false) {
		global $adb;
		$instance = false;

		$query = false;
		$queryParams = false;
		if(Vtiger_Utils::isNumber($value)) {
			$query = "SELECT * FROM vtiger_blocks WHERE blockid=?";
			$queryParams = Array($value);
		} else {
			$query = "SELECT * FROM vtiger_blocks WHERE blocklabel=? AND tabid=?";
			$queryParams = Array($value, $moduleInstance->id);
		}
		$result = $adb->pquery($query, $queryParams);
		if($adb->num_rows($result)) {
			$instance = new self();
			$instance->initialize($adb->fetch_array($result), $moduleInstance);
		}
		return $instance;
	}

	static function getAllForModule($moduleInstance) {
		global $adb;
		$instances = false;

		$query = "SELECT * FROM vtiger_blocks WHERE tabid=?";
		$queryParams = Array($moduleInstance->id);
		
		$result = $adb->pquery($query, $queryParams);
		for($index = 0; $index < $adb->num_rows($result); ++$index) {
			$instance = new self();
			$instance->initialize($adb->fetch_array($result), $moduleInstance);
			$instances[] = $instance;
		}
		return $instances;
	}
}
?>
