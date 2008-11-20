<?php

include_once('vtlib/Vtiger/Utils.php');
include_once('vtlib/Vtiger/FieldBasic.php');

class Vtiger_Field extends Vtiger_FieldBasic {

	function __getPicklistUniqueId() {
		global $adb;
		return $adb->getUniqueId('vtiger_picklist');
	}

	function setupPicklistValues($values) {
		global $adb;

		$picklist_table = 'vtiger_'.$this->name;
		$picklist_idcol = $this->name.'id';

		if(!Vtiger_Utils::CheckTable($picklist_table)) {
			Vtiger_Utils::CreateTable(
				$picklist_table,
				"($picklist_idcol INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				$this->name VARCHAR(200) NOT NULL,
				presence INT (1) NOT NULL DEFAULT 1,
				picklist_valueid INT NOT NULL DEFAULT 0)");
			$new_picklistid = $this->__getPicklistUniqueId();
			$adb->pquery("INSERT INTO vtiger_picklist (picklistid,name) VALUES(?,?)",Array($new_picklistid, $this->name));
			self::log("Creating table $picklist_table ... DONE");
		} else {
			$new_picklistid = $adb->query_result(
				$adb->pquery("SELECT picklistid FROM vtiger_picklist WHERE name=?", Array($this->name)), 0, 'picklistid');
		}

		// Add value to picklist now
		$sortid = 0; // TODO To be set per role
		foreach($values as $value) {
			$new_picklistvalueid = getUniquePicklistID();
			$presence = 1; // 0 - readonly, Refer function in include/ComboUtil.php
			$new_id = $adb->getUniqueId($picklist_table);
			$adb->pquery("INSERT INTO $picklist_table($picklist_idcol, $this->name, presence, picklist_valueid) VALUES(?,?,?,?)",
				Array($new_id, $value, $presence, $new_picklistvalueid));
			++$sortid;

			// Associate picklist values to all the role
			$adb->query("INSERT INTO vtiger_role2picklist(roleid, picklistvalueid, picklistid, sortid) SELECT roleid, 
				$new_picklistvalueid, $new_picklistid, $sortid FROM vtiger_role");
		}
	}

	function setRelatedModules($moduleNames) {

		// We need to create core table to capture the relation between the field and modules.
		Vtiger_Utils::CreateTable(
			'vtiger_fieldmodulerel',
			'(fieldid INT NOT NULL, module VARCHAR(100) NOT NULL, relmodule VARCHAR(100) NOT NULL, status VARCHAR(10), sequence INT)'
		);
		// END

		global $adb;
		foreach($moduleNames as $relmodule) {
			$checkres = $adb->pquery('SELECT * FROM vtiger_fieldmodulerel WHERE fieldid=? AND module=? AND relmodule=?',
				Array($this->id, $this->getModuleName(), $relmodule));

			// If relation already exist continue
			if($adb->num_rows($checkres)) continue;

			$adb->pquery('INSERT INTO vtiger_fieldmodulerel(fieldid, module, relmodule) VALUES(?,?,?)', 
				Array($this->id, $this->getModuleName(), $relmodule));
			
			self::log("Setting $this->name relation with $relmodule ... DONE");
		}
		return true;
	}

	function unsetRelatedModules($moduleNames) {
		global $adb;
		foreach($moduleNames as $relmodule) {
			$adb->pquery('DELETE FROM vtiger_fieldmodulerel WHERE fieldid=? AND module=? AND relmodule = ?', 
				Array($this->id, $this->module->name, $relmodule));
			
			Vtiger_Utils::Log("Unsetting $this->name relation with $relmodule ... DONE");
		}
		return true;
	}
}

?>
