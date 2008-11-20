<?php

include_once('include/utils/UserInfoUtil.php');
include_once('vtlib/Vtiger/Utils.php');
include_once('vtlib/Vtiger/Profile.php');

/**
 * Access Controller class.
 */
class Vtiger_Access {

	/**
	 * Helper function to log messages.
	 * @param $message Message to log
	 * @param $delim true (default) end with a linebreak (\n or <BR>), false to avoid it.
	 */
	static function log($message, $delim=true) {
		Vtiger_Utils::Log($message, $delim);
	}

	/**
	 * Get unique id for sharing access record.
	 * @access private
	 */
	static function __getDefaultSharingAccessId() {
		global $adb;
		return $adb->getUniqueId('vtiger_def_org_share');
	}

	/**
	 * Synchronize sharing access with recalculation
	 */
	static function syncSharingAccess() {
		self::log("Recalculating sharing rules ... ", false);
		RecalculateSharingRules();
		self::log("DONE");
	}

	/**
	 * Enable or Disable sharing access control to module.
	 * @param $moduleInstance Vtiger_Module instance
	 * @param $enable true (default) enable sharing access or false disable sharing access
	 */
	static function allowSharing($moduleInstance, $enable=true) {
		global $adb;
		$ownedby = $enable? 0 : 1;
		$adb->pquery("UPDATE vtiger_tab set ownedby=? WHERE tabid=?", Array($ownedby, $moduleInstance->id));
		self::log(($enable? "Enabled" : "Disabled") . " sharing access control ... DONE");
	}

	/**
	 * Initialize sharing access.
	 * @param $moduleInstance Vtiger_Module instance
	 * @access private
	 * NOTE: This method is called from Vtiger_Module during creation.
	 */
	static function initSharing($moduleInstance) {
		global $adb;

		$result = $adb->query("SELECT share_action_id from vtiger_org_share_action_mapping WHERE share_action_name in
			('Public: Read Only', 'Public: Read, Create/Edit', 'Public: Read, Create/Edit, Delete', 'Private')");

		for($index = 0; $index < $adb->num_rows($result); ++$index) {
			$actionid = $adb->query_result($result, $index, 'share_action_id');
			$adb->pquery("INSERT INTO vtiger_org_share_action2tab(share_action_id,tabid) VALUES(?,?)", Array($actionid, $moduleInstance->id));
		}
		self::log("Setting up sharing access options ... DONE");
	}

	/**
	 * Set default sharing for a module
	 * @param $moduleInstance Vtiger_Module instance
	 * @param $permission_text 'Public_ReadWriteDelete' (default), 'Public_ReadOnly', 'Public_ReadWrite', 'Private'
	 */
	static function setDefaultSharing($moduleInstance, $permission_text='Public_ReadWriteDelete') {
		global $adb;

		$permission_text = strtolower($permission_text);
		
		if($permission_text == 'public_readonly')             $permission = 0;
		else if($permission_text == 'public_readwrite')       $permission = 1;
		else if($permission_text == 'public_readwritedelete') $permission = 2;
		else if($permission_text == 'private')                $permission = 3;
		else $permission = 2; // public_readwritedelete is default

		$editstatus = 0; // 0 or 1

		$result = $adb->pquery("SELECT * FROM vtiger_def_org_share WHERE tabid=?", Array($moduleInstance->id));
		if($adb->num_rows($result)) {
			$ruleid = $adb->query_result($result, 0, 'ruleid');
			$adb->pquery("UPDATE vtiger_def_org_share SET permission=? WHERE ruleid=?", Array($permission, $ruleid));
		} else {
			$ruleid = self::__getDefaultSharingAccessId();
			$adb->pquery("INSERT INTO vtiger_def_org_share (ruleid,tabid,permission,editstatus) VALUES(?,?,?,?)", 
				Array($ruleid,$moduleInstance->id,$permission,$editstatus));
		}

		self::syncSharingAccess();
	}

	/**
	 * Enable tool for module.
	 * @param $moduleInstance Vtiger_Module instance
	 * @param $toolAction Tool actions like Import, Export, Merge
	 * @param $flag true to enable toolAction, false to disable toolAction
	 * @param $profileid false (default) applies update on all profile
	 */
	static function updateTool($moduleInstance, $toolAction, $flag, $profileid=false) {
		global $adb;

		$result = $adb->pquery("SELECT actionid FROM vtiger_actionmapping WHERE actionname=?", Array($toolAction));
		if($adb->num_rows($result)) {
			$actionid = $adb->query_result($result, 0, 'actionid');
			$permission = ($flag == true)? '0' : '1';

			$profileids = Array();
			if($profileid) {
				$profileids[] = $profileid;
			} else {
				$profileids = Vtiger_Profile::getAllIds();
			}

			self::log( ($flag? 'Enabling':'Disabling') . " $toolAction for Profile [", false);

			foreach($profileids as $useprofileid) {
				$result = $adb->pquery("SELECT permission FROM vtiger_profile2utility WHERE profileid=? AND tabid=? AND activityid=?", Array($useprofileid, $moduleInstance->id, $actionid));
				if($adb->num_rows($result)) {
					$curpermission = $adb->query_result($result, 0, 'permission');
					if($curpermission != $permission) {
						$adb->pquery("UPDATE vtiger_profile2utility set permission=? WHERE profileid=? AND tabid=? AND activityid=?", Array($permission, $useprofileid, $moduleInstance->id, $actionid));
					}
				} else {
						$adb->pquery("INSERT INTO vtiger_profile2utility (profileid, tabid, activityid, permission) VALUES(?,?,?,?)", Array($useprofileid, $moduleInstance->id, $actionid, $permission));
				}

				self::log("$useprofileid,", false);
			}
			self::log("] ... DONE");
		}
	}
}

?>
