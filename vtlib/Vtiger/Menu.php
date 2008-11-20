<?php

include_once('vtlib/Vtiger/Utils.php');

class Vtiger_Menu {
	var $id = false;
	var $label = false;
	var $sequence = false;
	var $visible = 0;

	function __construct() {
	}

	function initialize($valuemap) {
		$this->id       = $valuemap[parenttabid];
		$this->label    = $valuemap[parenttab_label];
		$this->sequence = $valuemap[sequence];
		$this->visible  = $valuemap[visible];
	}

	function __getNextRelSequence() {
		global $adb;
		$result = $adb->pquery("SELECT MAX(sequence) AS max_seq FROM vtiger_parenttab WHERE parenttabid=?", Array($this->id));
		$maxseq = $adb->query_result($result, 0, 'max_seq');
		return ++$maxseq;
	}

	function addModule($moduleInstance) {
		if($this->id) {
			global $adb;
			$relsequence = $this->__getNextRelSequence();
			$adb->pquery("INSERT INTO vtiger_parenttabrel (parenttabid,tabid,sequence) VALUES(?,?,?)",
					Array($this->id, $moduleInstance->id, $relsequence));
			self::log("Added to menu $this->label ... DONE");
		} else {
			self::log("Menu could not be found!");
		}

		self::syncfile();
	}

	static function getInstance($value) {
		global $adb;
		$query = false;
		$instance = false;
		if(Vtiger_Utils::isNumber($value)) {
			$query = "SELECT * FROM vtiger_parenttab WHERE parenttabid=?";
		} else {
			$query = "SELECT * FROM vtiger_parenttab WHERE parenttab_label=?";
		}
		$result = $adb->pquery($query, Array($value));
		if($adb->num_rows($result)) {
			$instance = new self();
			$instance->initialize($adb->fetch_array($result));
		}
		return $instance;
	}

	static function log($message) {
		Vtiger_Utils::Log($message);
	}

	static function syncfile() {
		self::log("Updating parent_tabdata file ... STARTED");
		create_parenttab_data_file();
		self::log("Updating parent_tabdata file ... DONE");
	}
}

?>
