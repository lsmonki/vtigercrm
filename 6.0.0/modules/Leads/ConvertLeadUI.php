<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ******************************************************************************* */

/*
 * class to manage all the UI related info
 */
require_once 'modules/PickList/PickListUtils.php';

class ConvertLeadUI {

	var $current_user;
	var $leadid;
	var $row;
	var $leadowner = '';
	var $userselected = '';
	var $userdisplay = 'none';
	var $groupselected = '';
	var $groupdisplay = 'none';
	var $account_fields;
	var $contact_fields;
	var $potential_fields;

	static $cachedIndustryPicklistValues = false;
	static $cachedSalesStagePicklistValues = false;
	static $cachedDescribeInfos = array();
	static $cachedMappedFieldValue = array();
	static $cachedOwnerList = array();

	function __construct($leadid, $current_user) {
		global $adb;
		$this->leadid = $leadid;
		$this->current_user = $current_user;
		$sql = "SELECT * FROM vtiger_leaddetails,vtiger_leadscf,vtiger_crmentity
			WHERE vtiger_leaddetails.leadid=vtiger_leadscf.leadid
			AND vtiger_leaddetails.leadid=vtiger_crmentity.crmid
			AND vtiger_leaddetails.leadid =?";
		$result = $adb->pquery($sql, array($this->leadid));
		$this->row = $adb->fetch_array($result);
		if (getFieldVisibilityPermission('Leads', $current_user->id, 'company') == '1') {
			$this->row["company"] = '';
		}
		$this->setAssignedToInfo();
	}

	function isModuleActive($module) {
		include_once 'include/utils/VtlibUtils.php';
		if (vtlib_isModuleActive($module) && ((isPermitted($module, 'EditView') == 'yes'))) {
			return true;
		}
		return false;
	}

	function isActive($field, $mod) {
		global $adb;
		$tabid = getTabid($mod);
		$query = 'select * from vtiger_field where fieldname = ?  and tabid = ? and presence in (0,2)';
		$res = $adb->pquery($query, array($field, $tabid));
		$rows = $adb->num_rows($res);
		if ($rows > 0) {
			return true;
		}else
			return false;
	}

	function isMandatory($module, $fieldname) {
		$fieldInfo = $this->getFieldInfo($module, $fieldname);
		if ($fieldInfo['mandatory']) {
			return true;
		}
		return false;
	}

	function getFieldInfo($module, $fieldname) {
		global $current_user;

		$describe = null;
		if (!isset(self::$cachedDescribeInfos[$module])) {
			$describe = vtws_describe($module, $current_user);
			self::$cachedDescribeInfos[$module] = $describe;
		} else {
			$describe = self::$cachedDescribeInfos[$module];
		}

		foreach ($describe['fields'] as $index => $fieldInfo) {
			if ($fieldInfo['name'] == $fieldname) {
				return $fieldInfo;
			}
		}
		return false;
	}

	function setAssignedToInfo() {
		$userid = $this->row["smownerid"];
		//Retreiving the current user id
		if ($userid != '') {
			global $adb;
			$query = "SELECT * from vtiger_users WHERE id = ?";
			$res = $adb->pquery($query, array($userid));
			$rows = $adb->num_rows($res);
			$this->leadowner = $userid;
			if ($rows > 0) {
				$this->userselected = 'checked';
				$this->userdisplay = 'block';
			} else {
				$this->groupselected = 'checked';
				$this->groupdisplay = 'block';
			}
		} else {
			$this->leadowner = $this->getUserId();
			$this->userselected = 'checked';
			$this->userdisplay = 'block';
		}
	}

	function getUserSelected() {
		return $this->userselected;
	}

	function getUserDisplay() {
		return $this->userdisplay;
	}

	function getGroupSelected() {
		return $this->groupselected;
	}

	function getGroupDisplay() {
		return $this->groupdisplay;
	}

	function getLeadInfo() {
		//Retreive lead details from database
		return $this->row;
	}

	function getDateFormat() {
		return $this->current_user->date_format;
	}

	function getleadId() {
		return $this->leadid;
	}

	function getCompany() {
		global $default_charset;
		$value = html_entity_decode($this->row['company'], ENT_QUOTES, $default_charset);
		return htmlentities($value, ENT_QUOTES, $default_charset);
	}

	function getIndustryList() {

		if (!self::$cachedIndustryPicklistValues) {
			global $adb;
			$industry_list = array();
			if (is_admin($this->current_user)) {
				$pick_list_values = getAllPickListValues('industry');
			} else {
				$pick_list_values = getAssignedPicklistValues('industry', $this->current_user->roleid, $adb);
			}
			foreach ($pick_list_values as $value) {
				$industry_list[$value]["value"] = $value;
			}
			self::$cachedIndustryPicklistValues = $industry_list;
		}

		return self::$cachedIndustryPicklistValues;

	}

	function getSalesStageList() {

		if (!self::$cachedSalesStagePicklistValues) {
			global$adb;
			$sales_stage_list = array();
			if (is_admin($this->current_user)) {
				$pick_list_values = getAllPickListValues('sales_stage');
			} else {
				$pick_list_values = getAssignedPicklistValues('sales_stage', $this->current_user->roleid, $adb);
			}
			foreach ($pick_list_values as $value) {
				$sales_stage_list[$value]["value"] = $value;
			}

			self::$cachedSalesStagePicklistValues = $sales_stage_list;
		}

		return self::$cachedSalesStagePicklistValues;
	}

	function getUserId() {
		return $this->current_user->id;
	}

	function getOwnerList($type) {

		if (!isset(self::$cachedOwnerList[$type])) {
			$private = self::checkOwnership($this->current_user);
			if ($type === 'user')
				$owner = get_user_array(false, "Active", $this->row["smownerid"], $private);
			else
				$owner = get_group_array(false, "Active", $this->row["smownerid"], $private);
			$owner_list = array();
			foreach ($owner as $id => $name) {
				if ($id == $this->row['smownerid'])
					$owner_list[] = array($type . 'id' => $id, $type . 'name' => $name, 'selected' => true);
				else
					$owner_list[] = array($type . 'id' => $id, $type . 'name' => $name, 'selected' => false);
			}
			self::$cachedOwnerList[$type] = $owner_list;
		}

		return self::$cachedOwnerList[$type];
	}

	static function checkOwnership($user) {
		$private = '';
		if ($user->id != 1) {
			include 'user_privileges/sharing_privileges_' . $user->id . '.php';
			$Acc_tabid = getTabid('Accounts');
			$con_tabid = getTabid('Contacts');
			if ($defaultOrgSharingPermission[$Acc_tabid] === 0 || $defaultOrgSharingPermission[$Acc_tabid] == 3) {
				$private = 'private';
			} elseif ($defaultOrgSharingPermission[$con_tabid] === 0 || $defaultOrgSharingPermission[$con_tabid] == 3) {
				$private = 'private';
			}
		}
		return $private;
	}

	function getMappedFieldValue($module, $fieldName, $editable) {
		global $adb,$default_charset;

		if (!isset(self::$cachedMappedFieldValue[$module][$fieldName])) {

			$fieldid = getFieldid(getTabid($module), $fieldName);

			$sql = "SELECT leadfid FROM vtiger_convertleadmapping
				WHERE (accountfid=?
				OR contactfid=?
				OR potentialfid=?)
				AND editable=?";
			$result = $adb->pquery($sql, array($fieldid, $fieldid, $fieldid, $editable));
			$leadfid = $adb->query_result($result, 0, 'leadfid');

			$sql = "SELECT fieldname FROM vtiger_field WHERE fieldid=? AND tabid=?";
			$result = $adb->pquery($sql, array($leadfid, getTabid('Leads')));
			$leadfname = $adb->query_result($result, 0, 'fieldname');

			$fieldinfo = $this->getFieldInfo($module, $fieldName);
            
			//currency field value converted to userformat
            if($fieldinfo['type']['name'] == 'currency'){
                $currencyField = new CurrencyField($this->row[$leadfname]);
                $value = $currencyField->getDisplayValue();
                self::$cachedMappedFieldValue[$module][$fieldName] = $value;
                return $value;
            }

			if ($fieldinfo['type']['name'] == 'picklist' || $fieldinfo['type']['name'] == 'multipicklist') {
				$valuelist = null;
				switch ($fieldName) {
					case 'industry':$valuelist = $this->getIndustryList();
						break;
					case 'sales_stage':$valuelist = $this->getSalesStageList();
						break;
				}
				foreach ($fieldinfo['type']['picklistValues'] as $key => $values) {
					if ($values['value'] == $this->row[$leadfname]) {
						$value = $this->row[$leadfname];
						self::$cachedMappedFieldValue[$module][$fieldName] = $value;
						return $value;
					}
				}
				$value = $fieldinfo['default'];
				self::$cachedMappedFieldValue[$module][$fieldName] = $value;
				return $value;
			}
            
            if($fieldinfo['type']['name'] === 'date'){
                if($this->row[$leadfname] != null) {
                    $value = DateTimeField::convertToUserFormat($this->row[$leadfname]);
                    self::$cachedMappedFieldValue[$module][$fieldName] = $value;
                    return $value;
                }
            }
           
			$value = html_entity_decode($this->row[$leadfname], ENT_QUOTES, $default_charset);
			$value = htmlentities($value, ENT_QUOTES, $default_charset);
			self::$cachedMappedFieldValue[$module][$fieldName] = $value;
		}

		return self::$cachedMappedFieldValue[$module][$fieldName];
	}

}

?>
