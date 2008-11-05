<?php

include_once('vtlib/Vtiger/Common.inc.php');

class Vtiger_Module {

	static function getId($module_name) {
		global $adb;

		$sqlresult = $adb->query("select tabid from vtiger_tab where name='".Vtiger_Utils::SQLEscape($module_name)."'");
		return $adb->query_result($sqlresult, 0, 'tabid');
	}

	/**
	 * Enable tab access control for each profile.
	 */
	static function addToProfile($tabid) {
		global $adb;

		$sqlresult = $adb->query("SELECT profileid FROM vtiger_profile");
		$profilecnt = $adb->num_rows($sqlresult);
		for($pridx = 0; $pridx < $profilecnt; ++$pridx) {
			$profileid = $adb->query_result($sqlresult, $pridx, "profileid");
			$adb->query("INSERT INTO vtiger_profile2tab (profileid, tabid, permissions) VALUES($profileid, $tabid,0)");
		}

		$sqlresult2 = $adb->query("SELECT actionid from vtiger_actionmapping 
			WHERE actionname IN ('Save','EditView','Delete','index','DetailView')");
		// NOTE: Other actionname (actionid >= 5) is considered as utility (tools) for a profile.
		// Gather all the actionid for associating to profile.
		$actionids = Array();
		$actioncnt = $adb->num_rows($sqlresult2);
		for($actidx = 0; $actidx < $actioncnt; ++$actidx) {
			$actionids[] = $adb->query_result($sqlresult2, $actidx, 'actionid');
		}
		
		for($pridx = 0; $pridx < $profilecnt; ++$pridx) {
			$profileid = $adb->query_result($sqlresult, $pridx, "profileid");
			for($actidx = 0; $actidx < count($actionids); ++$actidx) {
				$actionid = $actionids[$actidx];
				$adb->query("INSERT INTO vtiger_profile2standardpermissions (profileid, tabid, Operation, permissions) 
					VALUES($profileid, $tabid, $actionid, 0)");
			}
		}
	}

	/**
	 * Synchronize sharing access data.
	 */
	static function syncSharingAccess() {
		Vtiger_Utils::Log("Recalculating sharing rules ... STARTED");
		RecalculateSharingRules();
		Vtiger_Utils::Log("Recalculating sharing rules ... DONE");
	}

	/**
	 * Set up the available access mappings to the module.
	 */
	static function setupSharingAccessOptions($tabid) {
		global $adb;

		$sqlresult = $adb->query("SELECT share_action_id from vtiger_org_share_action_mapping WHERE share_action_name in
			('Public: Read Only', 'Public: Read, Create/Edit', 'Public: Read, Create/Edit, Delete', 'Private')");

		$num_rows = $adb->num_rows($sqlresult);
		for($index = 0; $index < $num_rows; ++$index) {
			$share_action_id = $adb->query_result($sqlresult, $index, 'share_action_id');
			$adb->query("INSERT INTO vtiger_org_share_action2tab (share_action_id, tabid) VALUES ($share_action_id, $tabid)");
		}

		Vtiger_Utils::Log("Setting up sharing access options ... DONE");
	}

	/**
	 * Set (create/update) default sharing access to the module.
	 */
	static function setDefaultSharingAccess($module, $permission_text='Public_ReadWriteDelete') {
		global $adb;

		$tabid = self::getId($module);

		$permission_text = strtolower($permission_text);
		$permission = 2; // Default: Public_ReadWriteDelete

		if($permission_text == 'public_readonly')             $permission = 0;
		else if($permission_text == 'public_readwrite')       $permission = 1;
		else if($permission_text == 'public_readwritedelete') $permission = 2;
		else if($permission_text == 'private')                $permission = 3;

		$editstatus = 0; // 0 or 1

		$sqlresult = $adb->query("SELECT * FROM vtiger_def_org_share WHERE tabid=$tabid");
		if($adb->num_rows($sqlresult) > 0) {
			$ruleid = $adb->query_result($sqlresult, 0, 'ruleid');
			$sqlresult = $adb->query("UPDATE vtiger_def_org_share SET permission=$permission WHERE ruleid=$ruleid");
		} else {
			$ruleid = self::getDefaultSharingAccessId();
			$sqlresult = $adb->query("INSERT INTO vtiger_def_org_share (ruleid, tabid, permission, editstatus) 
			VALUES ($ruleid, $tabid, $permission, $editstatus)");
		}
		// We have added new permission, sharing access files needs update
		self::syncSharingAccess();
	}

	/**
	 * Disable Sharing Access for the module.
	 */
	static function disableSharingAccess($module) {
		global $adb;

		$tabid = self::getId($module);
		$adb->query("UPDATE vtiger_tab set ownedby=1 where tabid=$tabid");
	}

	/**
	 * Enable Sharing Access for the module.
	 */
	static function enableSharingAccess($module) {
		global $adb;

		$tabid = self::getId($module);
		$adb->query("UPDATE vtiger_tab set ownedby=0 where tabid=$tabid");
	}

	/**
	 * Enable module action like, Import/Export/Merge etc..
	 */
	static function enableAction($module, $action, $profileid=null) {
		self::updateAction($module, $action, true, $profileid);
	}
	/**
	 * Disable module action like, Import/Export/Merge etc...
	 */
	static function disableAction($module, $action, $profileid=null) {
		self::updateAction($module, $action, false, $profileid);
	}

	/**
	 * Common function to enable or disable action (tools) for module
	 * Actions are defined in the table vtiger_actionmapping like Import, Export etc...
	 */
	static function updateAction($module, $action, $enable_disable, $profile_id=null) {
		global $adb;

		$tabid = self::getId($module);

		$sqlresult = $adb->query("select actionid from vtiger_actionmapping where actionname = '$action'");
		if($adb->num_rows($sqlresult) < 1) return;

		$actionid = $adb->query_result($sqlresult, 0, 'actionid');

		$enable_disable = ($enable_disable == true)? '0' : '1';

		if($profile_id == null) {
			$sqlresult = $adb->query("select profileid from vtiger_profile");
			$profilecnt = $adb->num_rows($sqlresult);
			for($pridx = 0; $pridx < $profilecnt; ++$pridx) {
				$profileid = $adb->query_result($sqlresult, $pridx, "profileid");
				$adb->query("INSERT INTO vtiger_profile2utility (profileid, tabid, activityid, permission)
					VALUES($profileid, $tabid, $actionid, $enable_disable)");

				Vtiger_Utils::Log( (($enable_disable == '0')? 'Enabling':'Disabling') . 
					" Action $action for module $module for Profile $profileid ... DONE");
			}
		} else {
			$adb->query("INSERT INTO vtiger_profile2utility (profileid, tabid, activityid, permission)
				VALUES($profile_id, $tabid, $actionid, $enable_disable)");

			Vtiger_Utils::Log( (($enable_disable == '0')? 'Enabling':'Disabling') .
			   	" Action $action for module $module for Profile $profileid ... DONE");			
		}
	}

	/**
	 * Generate next id for default sharing access.
	 */
	static function getDefaultSharingAccessId() {
		global $adb;
		return $adb->getUniqueId('vtiger_def_org_share');
	}

	/**
	 * Related list configuration API.
	 */
	static function setRelatedList($for_module, $with_module, $label_text='', $function_name='get_related_list') {

		// We need to create core table to capture the relation between the modules.
		// TODO: If this is going to be a success, then this table can become part of the vtiger CRM core.

		Vtiger_Utils::CreateTable(
			'vtiger_crmentityrel',
			'(crmid INT NOT NULL, module VARCHAR(100) NOT NULL, relcrmid INT NOT NULL, relmodule VARCHAR(100) NOT NULL)'
		);

		// END

		$relation_id = self::getUniqueRelatedListId();
		$for_tabid   = self::getId($for_module);
		$with_tabid  = self::getId($with_module);
		$sequence    = self::getNextRelatedListSequence($for_tabid);
		$presence    = 0; // 0 - Enabled, 1 - Disabled
		
		if($label_text == '') $label_text = $with_module;

		global $adb;
		$adb->query("INSERT INTO vtiger_relatedlists(relation_id,tabid,related_tabid,name,sequence,label,presence)
			VALUES ($relation_id, $for_tabid, $with_tabid, '$function_name', $sequence, '$label_text', $presence)");

		Vtiger_Utils::Log("Setting up relation between $for_module and $with_module ... DONE");
	}

	/**
	 * Delete related list entry.
	 */
	static function unsetRelatedList($for_module, $with_module, $label_text='', $function_name='get_related_list') {
		global $adb;

		$for_tabid = self::getId($for_module);
		$with_tabid= self::getId($with_module);
		if($label_text == '') $label_text = $with_module;

		$adb->query("DELETE FROM vtiger_relatedlists 
			WHERE tabid=$for_tabid AND related_tabid=$with_tabid AND name='$function_name' AND label='$label_text'");
	}

	/**
	 * Get unique id for creating related list entry.
	 */
	static function getUniqueRelatedListId() {
		global $adb;
		return $adb->getUniqueId('vtiger_relatedlists');
	}
	/**
	 * Get the next sequence which can be associated to related list.
	 */
	static function getNextRelatedListSequence($tabid) {
		global $adb;
		$max_sequence = 0;
		$sqlresult = $adb->query("SELECT max(sequence) as maxsequence FROM vtiger_relatedlists WHERE tabid=$tabid");
		if($adb->num_rows($sqlresult)) $max_sequence = $adb->query_result($sqlresult, 0, 'maxsequence');
		return ++$max_sequence;
	}
}

?>
