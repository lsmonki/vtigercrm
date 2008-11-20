<?php

include_once('vtlib/Vtiger/Utils.php');

class Vtiger_Block {
	var $id;
	var $label;

	var $sequence;

	var $showtitle = 0;
	var $visible = 0;
	var $increateview = 0;
	var $ineditview = 0;
	var $indetailview = 0;

	var $module;

	function __construct() {
	}

	function __getUniqueId() {
		global $adb;
		$result = $adb->query("SELECT MAX(blockid) as max_blockid from vtiger_blocks");
		$maxblockid = 0;
		if($adb->num_rows($result)) {
			$maxblockid  = $adb->query_result($result, 0, 'max_blockid');
		}
		return ++$maxblockid;
	}

	function __getNextSequence() {
		global $adb;
		$result = $adb->pquery("SELECT MAX(sequence) as max_sequence from vtiger_blocks where tabid = ?", Array($this->module->id));
		$maxseq = 0;
		if($adb->num_rows($result)) {
			$maxseq = $adb->query_result($result, 0, 'max_sequence');
		}
		return ++$blockseq;
	}

	function initialize($valuemap, $moduleInstance=false) {
		$this->id = $valuemap[blockid];
		$this->label= $valuemap[blocklabel];
		$this->module=$moduleInstance? $moduleInstance: Vtiger_Module::getInstance($valuemap[tabid]);
	}

	function __create($moduleInstance) {
		global $adb;

		$this->module = $moduleInstance;

		$this->id = $this->__getUniqueId();
		if(!$this->sequence) $this->sequence = $this->__getNextSequence();

		$adb->pquery("INSERT INTO vtiger_blocks(blockid,tabid,blocklabel,sequence,show_title,visible,create_view,edit_view,detail_view) VALUES(?,?,?,?,?,?,?,?,?)", Array($this->id, $this->module->id, $this->label,$this->sequence, $this->showtitle, $this->visible,$this->increateview, $this->ineditview, $this->indetailview));
		self::log("Creating Block $this->label ... DONE");
		self::log("Module language entry for $this->label ... CHECK");
	}

	function __update() {
		self::log("Updating Block $this->label ... DONE");
	}

	function save($moduleInstance=false) {
		if($this->id) $this->__update();
		else $this->__create($moduleInstance);
		return $this->id;
	}

	function addField($fieldInstance) {
		$fieldInstance->save($this);
		return $this;
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
