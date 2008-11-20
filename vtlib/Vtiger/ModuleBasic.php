<?php

include_once('vtlib/Vtiger/Block.php');
include_once('vtlib/Vtiger/Field.php');
include_once('vtlib/Vtiger/Filter.php');
include_once('vtlib/Vtiger/Profile.php');
include_once('vtlib/Vtiger/Access.php');

/**
 * Wrapper class over vtiger CRM Module
 */
class Vtiger_ModuleBasic {
	var $id = false;
	var $name = false;
	var $label = false;

	var $presence = 0;
	var $ownedby = 0; // 0 - Sharing Access Enabled, 1 - Sharing Access Disabled
	var $tabsequence = false;

	var $entityidcolumn = false;
	var $entityidfield = false;

	var $basetable = false;
	var $basetableid=false;
	var $customtable=false;
	var $grouptable = false;

	function __construct() {
	}

	function initialize($valuemap) {
		$this->id = $valuemap[tabid];
		$this->name=$valuemap[name];
		$this->label=$valuemap[tablabel];

		$this->presence = $valuemap[presence];
		$this->ownedby = $valuemap[ownedby];
		$this->tabsequence = $valuemap[tabsequence];
	}

	function __getUniqueId() {
		global $adb;
		$result = $adb->query("SELECT MAX(tabid) AS max_seq FROM vtiger_tab");
		$maxseq = $adb->query_result($result, 0, 'max_seq');
		return ++$maxseq;
	}

	function __getNextSequence() {
		global $adb;
		$result = $adb->query("SELECT MAX(tabsequence) AS max_tabseq FROM vtiger_tab");
		$maxtabseq = $adb->query_result($result, 0, 'max_tabseq');
		return ++$maxtabseq;
	}

	function __create() {
		global $adb;

		self::log("Creating Module $this->name ... STARTED");

		$this->id = $this->__getUniqueId();
		if(!$this->tabsequence) $this->tabsequence = $this->__getNextSequence();

		$adb->pquery("INSERT INTO vtiger_tab (tabid,name,presence,tabsequence,tablabel,modifiedby,
			modifiedtime,customized,ownedby) VALUES (?,?,?,?,?,?,?,?,?)", 
			Array($this->id, $this->name, $this->presence, $this->tabsequence, $this->label, NULL, NULL, 0, $this->ownedby));

		Vtiger_Profile::initForModule($this);
		
		self::syncfile();

		Vtiger_Access::initSharing($this);

		self::log("Creating Module $this->name ... DONE");
	}

	function __update() {
		self::log("Updating Module $this->name ... DONE");
	}

	function save() {
		if($this->id) $this->__update();
		else $this->__create();
		return $this->id;
	}

	function initTables($basetable=false, $basetableid=false) {
		$this->basetable = $basetable;
		$this->basetableid=$basetableid;

		// Initialize tablename and index column names
		$lcasemodname = strtolower($this->name);
		if(!$this->basetable) $this->basetable = "vtiger_$lcasemodname";
		if(!$this->basetableid)$this->basetableid=$lcasemodname . "id";

		if(!$this->customtable)$this->customtable = $this->basetable . "cf";
		if(!$this->grouptable)$this->grouptable = $this->basetable."grouprel";

		Vtiger_Utils::CreateTable($this->basetable,"($this->basetableid INT)");
		Vtiger_Utils::CreateTable($this->customtable,
			"($this->basetableid INT PRIMARY KEY)");
		Vtiger_Utils::CreateTable($this->grouptable,
			"($this->basetableid INT PRIMARY KEY, groupname varchar(100))");
	}

	function setEntityIdentifier($fieldInstance) {
		global $adb;

		if($this->basetableid) {
			if(!$this->entityidfield) $this->entityidfield = $this->basetableid;
			if(!$this->entityidcolumn)$this->entityidcolumn= $this->basetableid;
		}
		if($this->entityidfield && $this->entityidcolumn) {
			$adb->pquery("INSERT INTO vtiger_entityname(tabid, modulename, tablename, fieldname, entityidfield, entityidcolumn) VALUES(?,?,?,?,?,?)",
				Array($this->id, $this->name, $fieldInstance->table, $fieldInstance->name, $this->entityidfield, $this->entityidcolumn));
			self::log("Setting entity identifier information ... DONE");
		}
	}

	function setDefaultSharing($permission_text='Public_ReadWriteDelete') {
		Vtiger_Access::setDefaultSharing($this, $permission_text);
	}

	function allowSharing() {
		Vtiger_Access::allowSharing($this, true);
	}
	function disallowSharing() {
		Vtiger_Access::allowSharing($this, false);
	}

	function enableTools($tools) {
		if(is_string($tools)) {
			$tools = Array(0 => $tools);
		}

		foreach($tools as $tool) {
			Vtiger_Access::updateTool($this, $tool, true);
		}
	}
	function disableTools($tools) {
		if(is_string($tools)) {
			$tools = Array(0 => $tools);
		}
		foreach($tools as $tool) {
			Vtiger_Access::updateTool($this, $tool, false);
		}
	}

	function addBlock($blockInstance) {
		$blockInstance->save($this);
		return $this;
	}

	function addFilter($filterInstance) {
		$filterInstance->save($this);
		return $this;
	}

	function getFields($blockInstance=false) {
		$fields = false;
		if($blockInstance) $fields = Vtiger_Field::getAllForBlock($blockInstance, $this);
		else $fields = Vtiger_Field::getAllForModule($this);
		return $fields;
	}

	static function log($message, $delimit=true) {
		Vtiger_Utils::Log($message, $delimit);
	}
	
	static function syncfile() {
		self::log("Updating tabdata file ... ", false);
		create_tab_data_file();
		self::log("DONE");
	}

	static function getInstance($value) {
		global $adb;
		$instance = false;

		$query = false;
		if(Vtiger_Utils::isNumber($value)) {
			$query = "SELECT * FROM vtiger_tab WHERE tabid=?";
		} else {
			$query = "SELECT * FROM vtiger_tab WHERE name=?";
		}
		$result = $adb->pquery($query, Array($value));
		if($adb->num_rows($result)) {
			$instance = new self();
			$instance->initialize($adb->fetch_array($result));
		}
		return $instance;
	}
}

?>
