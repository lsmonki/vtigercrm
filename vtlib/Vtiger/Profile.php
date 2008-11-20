<?php

include_once('vtlib/Vtiger/Utils.php');

class Vtiger_Profile {

	static function log($message, $delimit=true) {
		Vtiger_Utils::Log($message, $delimit);
	}

	static function initForField($fieldInstance) {
		global $adb;

		// Allow field access to all
		$adb->pquery("INSERT INTO vtiger_def_org_field (tabid, fieldid, visible, readonly) VALUES(?,?,?,?)",
			Array($fieldInstance->getModuleId(), $fieldInstance->id, '0', '1'));

		$profileids = self::getAllIds();
		foreach($profileids as $profileid) {
			$adb->pquery("INSERT INTO vtiger_profile2field (profileid, tabid, fieldid, visible, readonly) VALUES(?,?,?,?,?)",
				Array($profileid, $fieldInstance->getModuleId(), $fieldInstance->id, '0', '1'));
		}
	}

	static function getAllIds() {
		global $adb;
		$profileids = Array();
		$result = $adb->query('SELECT profileid FROM vtiger_profile');
		for($index = 0; $index < $adb->num_rows($result); ++$index) {
			$profileids[] = $adb->query_result($result, $index, 'profileid');
		}
		return $profileids;
	}


	static function initForModule($moduleInstance) {
		global $adb;

		$actionids = Array();
		$result = $adb->query("SELECT actionid from vtiger_actionmapping WHERE actionname IN 
			('Save','EditView','Delete','index','DetailView')");
		/* 
		 * NOTE: Other actionname (actionid >= 5) is considered as utility (tools) for a profile.
		 * Gather all the actionid for associating to profile.
		 */
		for($index = 0; $index < $adb->num_rows($result); ++$index) {
			$actionids[] = $adb->query_result($result, $index, 'actionid');
		}

		$profileids = self::getAllIds();		

		foreach($profileids as $profileid) {			
			$adb->pquery("INSERT INTO vtiger_profile2tab (profileid, tabid, permissions) VALUES (?,?,?)",
				Array($profileid, $moduleInstance->id, 0));
			foreach($actionids as $actionid) {
				$adb->pquery(
					"INSERT INTO vtiger_profile2standardpermissions (profileid, tabid, Operation, permissions) VALUES(?,?,?,?)",
					Array($profileid, $moduleInstance->id, $actionid, 0));
			}
		}
		self::log("Initializing module permissions ... DONE");
	}
}
?>
