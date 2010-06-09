<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
class Mobile_WS_Utils {
	/*
	static function initAppGlobals() {
		global $current_language, $app_strings, $app_list_strings, $app_currency_strings;
		$current_language = 'en_us';
		
		$app_currency_strings = return_app_currency_strings_language($current_language);
		$app_strings = return_application_language($current_language);
		$app_list_strings = return_app_list_strings_language($current_language);
	}
	
	static function initModuleGlobals($module) {
		global $mod_strings, $current_language;
		if(isset($current_language)) {
			$mod_strings = return_module_language($current_language, $module);
		}
	}*/
	
	static function array_replace($search, $replace, $array) {
		$index = array_search($search, $array);
		if($index !== false) {
			$array[$index] = $replace;
		}
		return $array;
	}
	
	static function getModuleListQuery($moduleName, $where = '1=1') {
		$module = CRMEntity::getInstance($moduleName);
		return $module->create_list_query('', $where);
	}
	
	static function getEntityModuleWSId($moduleName) {
		global $adb;
		$result = $adb->pquery("SELECT id FROM vtiger_ws_entity WHERE name=?", array($moduleName));
		if ($result && $adb->num_rows($result)) {
			return $adb->query_result($result, 0, 'id');
		}
		return false;
	}
	
	static function getEntityModuleWSIds($ignoreNonModule = true) {
		global $adb;
		
		$modulewsids = array();
		$result = false;
		if($ignoreNonModule) {
			$result = $adb->pquery("SELECT id, name FROM vtiger_ws_entity WHERE ismodule=1", array());
		} else {
			$result = $adb->pquery("SELECT id, name FROM vtiger_ws_entity", array());
		}
		
		while($resultrow = $adb->fetch_array($result)) {
			$modulewsids[$resultrow['name']] = $resultrow['id'];
		}
		return $modulewsids;
	}
	
	static function getEntityFieldnames($module) {
		global $adb;
		$result = $adb->pquery("SELECT fieldname FROM vtiger_entityname WHERE modulename=?", array($module));
		$fieldnames = array();
		if($result && $adb->num_rows($result)) {
			$fieldnames = explode(',', $adb->query_result($result, 0, 'fieldname'));
		}
		switch($module) {
			case 'HelpDesk': $fieldnames = self::array_replace('title', 'ticket_title', $fieldnames); break;
			case 'Document': $fieldnames = self::array_replace('title', 'notes_title', $fieldnames); break;
		}
		return $fieldnames;
	}
	
	static function getModuleColumnTableByFieldNames($module, $fieldnames) {
		global $adb;
		$result = $adb->pquery("SELECT fieldname,columnname,tablename FROM vtiger_field WHERE tabid=? AND fieldname IN (".
			generateQuestionMarks($fieldnames) . ")", array(getTabid($module), $fieldnames)
		);
		$columnnames = array();
		if ($result && $adb->num_rows($result)) {
			while($resultrow = $adb->fetch_array($result)) {
				$columnnames[$resultrow['fieldname']] = array('column' => $resultrow['columnname'], 'table' => $resultrow['tablename']);
			}
		}
		return $columnnames;
	}
	
	static function detectModulenameFromRecordId($wsrecordid) {
		global $adb;
		$idComponents = vtws_getIdComponents($wsrecordid);
		$result = $adb->pquery("SELECT name FROM vtiger_ws_entity WHERE id=?", array($idComponents[0]));
		if($result && $adb->num_rows($result)) {
			return $adb->query_result($result, 0, 'name');
		}
		return false;
	}
	
	static $detectFieldnamesToResolveCache = array();
	
	static function detectFieldnamesToResolve($module) {
		global $adb;
		
		// Cache hit?
		if(isset(self::$detectFieldnamesToResolveCache[$module])) {
			return self::$detectFieldnamesToResolveCache[$module];
		}
		
		$resolveUITypes = array(10, 101, 116, 117, 26, 357, 50, 51, 52, 53, 57, 58, 59, 66, 68, 73, 75, 76, 77, 78, 80, 81);
		
		$result = $adb->pquery(
			"SELECT fieldname FROM vtiger_field WHERE uitype IN(". 
			generateQuestionMarks($resolveUITypes) .") AND tabid=?", array($resolveUITypes, getTabid($module)) 
		);
		$fieldnames = array();
		while($resultrow = $adb->fetch_array($result)) {
			$fieldnames[] = $resultrow['fieldname'];
		}
		
		// Cache information		
		self::$detectFieldnamesToResolveCache[$module] = $fieldnames;
		
		return $fieldnames;
	}

	static $gatherModuleFieldGroupInfoCache = array();
	
	static function gatherModuleFieldGroupInfo($module) {
		global $adb;
		
		if($module == 'Events') $module = 'Calendar';
		
		// Cache hit?
		if(isset(self::$gatherModuleFieldGroupInfoCache[$module])) {
			return self::$gatherModuleFieldGroupInfoCache[$module];
		}
		
		$result = $adb->pquery(
			"SELECT fieldname, fieldlabel, blocklabel, uitype FROM vtiger_field INNER JOIN
			vtiger_blocks ON vtiger_blocks.tabid=vtiger_field.tabid AND vtiger_blocks.blockid=vtiger_field.block 
			WHERE vtiger_field.tabid=? ORDER BY vtiger_blocks.sequence, vtiger_field.sequence", array(getTabid($module))
		);

		$fieldgroups = array();
		while($resultrow = $adb->fetch_array($result)) {
			$blocklabel = getTranslatedString($resultrow['blocklabel'], $module);
			if(!isset($fieldgroups[$blocklabel])) {
				$fieldgroups[$blocklabel] = array();
			}
			$fieldgroups[$blocklabel][$resultrow['fieldname']] = 
				array(
					'label' => getTranslatedString($resultrow['fieldlabel'], $module),
					'uitype'=> $resultrow['uitype']
				);
		}
		
		// Cache information
		self::$gatherModuleFieldGroupInfoCache[$module] = $fieldgroups;
		
		return $fieldgroups;
	}
}