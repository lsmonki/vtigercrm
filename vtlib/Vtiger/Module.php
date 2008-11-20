<?php

include_once('vtlib/Vtiger/ModuleBasic.php');

class Vtiger_Module extends Vtiger_ModuleBasic {

	function __getRelatedListUniqueId() {
		global $adb;
		return $adb->getUniqueId('vtiger_relatedlists');
	}

	function __getNextRelatedListSequence() {
		global $adb;
		$max_sequence = 0;
		$result = $adb->pquery("SELECT max(sequence) as maxsequence FROM vtiger_relatedlists WHERE tabid=?", Array($this->id));
		if($adb->num_rows($result)) $max_sequence = $adb->query_result($result, 0, 'maxsequence');
		return ++$max_sequence;
	}

	function setRelatedList($moduleInstance, $label='', $function_name='get_related_list') {
		global $adb;

		if(empty($moduleInstance)) return;

		Vtiger_Utils::CreateTable(
			'vtiger_crmentityrel',
			'(crmid INT NOT NULL, module VARCHAR(100) NOT NULL, relcrmid INT NOT NULL, relmodule VARCHAR(100) NOT NULL)'
		);

		$relation_id = $this->__getRelatedListUniqueId();
		$sequence = $this->__getNextRelatedListSequence();
		$presence = 0; // 0 - Enabled, 1 - Disabled

		if(empty($label)) $label = $moduleInstance->name;

		$adb->pquery("INSERT INTO vtiger_relatedlists(relation_id,tabid,related_tabid,name,sequence,label,presence) VALUES(?,?,?,?,?,?,?)",
			Array($relation_id,$this->id,$moduleInstance->id,$function_name,$sequence,$label,$presence));

		self::log("Setting relation with $moduleInstance->name ... DONE");
	}

	function unsetRelatedList($moduleInstance, $label, $function_name='get_related_list') {
		global $adb;

		if(empty($moduleInstance)) return;

		if(empty($label)) $label = $moduleInstance->name;

		$adb->pquery("DELETE FROM vtiger_relatedlists WHERE tabid=? AND related_tabid=? AND name=? AND label=?", 
			Array($this->id, $moduleInstance->id, $function_name, $label));

		self::log("Unsetting relation with $moduleInstance->name ... DONE");		
	}

}

?>
