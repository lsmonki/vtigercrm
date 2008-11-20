<?php

class Vtiger_FieldBasic {
	var $id;
	var $name;
	var $label = false;	
	var $table = false;
	var $column = false;
	var $columntype = false;

	var $uitype = 1;
	var $typeofdata = 'V~O';
	var	$displaytype   = 1;

	var $generatedtype = 1;
	var	$readonly      = 1;
	var	$presence      = 0;
	var	$selected      = 0;
	var	$maximumlength = 100;
	var	$sequence      = false;
	var	$quickcreate   = 1;
	var	$quicksequence = false;
	var	$info_type     = 'BAS';
	
	var $block;

	function __construct() {
	}

	function initialize($valuemap, $blockInstance=false, $moduleInstance=false) {
		$this->id = $valuemap[fieldid];
		$this->name = $valuemap[fieldname];
		$this->label= $valuemap[fieldlabel];
		$this->column = $valuemap[columnname];
		$this->table  = $valuemap[tablename];
		$this->uitype = $valuemap[uitype];
		$this->typeofdata = $valuemap[typeofdata];
		$this->block= $blockInstance? $blockInstance : Vtiger_Block::getInstance($valuemap[block], $moduleInstance);
	}

	function __getUniqueId() {
		global $adb;
		return $adb->getUniqueId('vtiger_field');
	}

	function __getNextSequence() {
		global $adb;
		$result = $adb->pquery("SELECT MAX(sequence) AS max_seq FROM vtiger_field WHERE tabid=? AND block=?",
			Array($this->getModuleId(), $this->getBlockId()));
		$maxseq = 0;
		if($result && $adb->num_rows($result)) {
			$maxseq = $adb->query_result($result, 0, 'max_seq');
			$maxseq += 1;
		}
		return $maxseq;
	}
	function __getNextQuickCreateSequence() {
		global $adb;
		$result = $adb->pquery("SELECT MAX(quickcreatesequence) AS max_quickcreateseq FROM vtiger_field WHERE tabid=?",
			Array($this->getModuleId()));
		$max_quickcreateseq = 0;
		if($result && $adb->num_rows($result)) {
			$max_quickcreateseq = $adb->query_result($result, 0, 'max_quickcreateseq');
			$max_quickcreateseq += 1;
		}
		return $max_quickcreateseq;
	}

	function __create($blockInstance) {
		global $adb;

		$this->block = $blockInstance;

		$moduleInstance = $this->getModuleInstance();

		$this->id = $this->__getUniqueId();
		$this->sequence = $this->__getNextSequence();
		if($this->quickcreate) {
			if(!$this->quicksequence) {
				$this->quicksequence = $this->__getNextQuickCreateSequence();
			}
		} else {
			$this->quicksequence = null;
		}

		// Initialize other variables which are not done
		if(!$this->table) $this->table = $moduleInstance->basetable;
		if(!$this->column) {
			$this->column = strtolower($this->name);
			if(!$this->columntype) $this->columntype = 'VARCHAR(100)';
		}
		if(!$this->label) $this->label = $this->name;

		$adb->pquery("INSERT INTO vtiger_field (tabid, fieldid, columnname, tablename, generatedtype,
			uitype, fieldname, fieldlabel, readonly, presence, selected, maximumlength, sequence,
			block, displaytype, typeofdata, quickcreate, quickcreatesequence, info_type) 
			VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
				Array($this->getModuleId(), $this->id, $this->column, $this->table, $this->generatedtype,
				$this->uitype, $this->name, $this->label, $this->readonly, $this->presence, $this->selected,
				$this->maximumlength, $this->sequence, $this->getBlockId(), $this->displaytype, $this->typeofdata,
				$this->quickcreate, $this->quicksequence, $this->info_type));

		Vtiger_Profile::initForField($this);

		echo "$this->columntype ... create? $this->column ... $this->table";
		print_r($adb->getColumnNames($this->table));

		if(!empty($this->columntype) && !in_array($this->column, $adb->getColumnNames($this->table))) {
			echo "ALTER TABLE ADD COLUMN $this->column $this->columntype";
			Vtiger_Utils::AlterTable($this->table, " ADD COLUMN $this->column $this->columntype");
		}	

		self::log("Creating Field $this->name ... DONE");
		self::log("Module language mapping for $this->label ... CHECK");
	}

	function __update() {
		self::log("Updating Field $this->name ... DONE");
	}

	function getBlockId() {
		return $this->block->id;
	}

	function getModuleId() {
		return $this->block->module->id;
	}

	function getModuleName() {
		return $this->block->module->name;
	}

	function getModuleInstance(){
		return $this->block->module;
	}

	function save($blockInstance) {
		if($this->id) $this->__update();
		else $this->__create($blockInstance);
		return $this->id;
	}

	static function log($message) {
		Vtiger_Utils::Log($message);
	}

	static function getInstance($value, $blockInstance=false, $moduleInstance=false) {
		global $adb;
		$instance = false;

		$query = false;
		$queryParams = false;
		if(Vtiger_Utils::isNumber($value)) {
			$query = "SELECT * FROM vtiger_field WHERE fieldid=?";
			$queryParams = Array($value);
		} else {
			$query = "SELECT * FROM vtiger_field WHERE fieldname=? AND block=? AND tabid=?";
			$queryParams = Array($value, $blockInstance->id, $moduleInstance->id);
		}
		$result = $adb->pquery($query, $queryParams);
		if($adb->num_rows($result)) {
			$instance = new self();
			$instance->initialize($adb->fetch_array($result), $blockInstance, $moduleInstance);
		}
		return $instance;
	}

	static function getAllForBlock($blockInstance, $moduleInstance=false) {
		global $adb;
		$instances = false;

		$query = false;
		$queryParams = false;
		if($moduleInstance) {
			$query = "SELECT * FROM vtiger_field WHERE block=? AND tabid=?";
			$queryParams = Array($blockInstance->id, $moduleInstance->id);
		} else {
			$query = "SELECT * FROM vtiger_field WHERE block=?";
			$queryParams = Array($blockInstance->id);
		}
		$result = $adb->pquery($query, $queryParams);
		for($index = 0; $index < $adb->num_rows($result); ++$index) {
			$instance = new self();
			$instance->initialize($adb->fetch_array($result), $blockInstance, $moduleInstance);
			$instances[] = $instance;
		}
		return $instances;
	}

	static function getAllForModule($moduleInstance) {
		global $adb;
		$instances = false;

		$query = "SELECT * FROM vtiger_field WHERE tabid=?";
		$queryParams = Array($moduleInstance->id);
		
		$result = $adb->pquery($query, $queryParams);
		for($index = 0; $index < $adb->num_rows($result); ++$index) {
			$instance = new self();
			$instance->initialize($adb->fetch_array($result), false, $moduleInstance);
			$instances[] = $instance;
		}
		return $instances;
	}
}
?>
