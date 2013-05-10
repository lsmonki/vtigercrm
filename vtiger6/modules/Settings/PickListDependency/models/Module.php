<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
vimport('~~modules/PickList/DependentPickListUtils.php');

class Settings_PickListDependency_Module_Model extends Settings_Vtiger_Module_Model {

	var $baseTable = 'vtiger_picklist_dependency';
	var $baseIndex = 'id';
	var $name = 'PickListDependency';

	/**
	 * Function to get the url for default view of the module
	 * @return <string> - url
	 */
	public function getDefaultUrl() {
		return 'index.php?module=PickListDependency&parent=Settings&view=List';
	}

	/**
	 * Function to get the url for Adding Dependency
	 * @return <string> - url
	 */
	public function getCreateRecordUrl() {
		return "javascript:Settings_PickListDependency_Js.triggerAdd(event)";
	}
    
    public function isPagingSupported() {
        return false;
    }

	public static function getAvailablePicklists($module) {
		return Vtiger_DependencyPicklist::getAvailablePicklists($module);
	}
	
	public static function getPicklistSupportedModules() {
		$modules = Vtiger_DependencyPicklist::getDependentPickListModules();
        $modulesModelsList = array();
        foreach($modules as $moduleLabel => $moduleName) {
            $instance = new Vtiger_Module_Model();
            $instance->name = $moduleName;
            $instance->label = $moduleLabel;
            $modulesModelsList[] = $instance;
        }
        return $modulesModelsList;
    }
}
