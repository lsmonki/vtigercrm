<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

/**
 * ServicesSetup Class is used handle the pre and post installation setup for the module
 */

class ServicesSetup {
	
	function postInstall() {
		require_once('include/utils/utils.php');
		require_once('vtlib/Vtiger/Module.php');
			
		$moduleInstance = Vtiger_Module::getInstance('Services');
		$moduleInstance->disallowSharing();
		
		$ttModuleInstance = Vtiger_Module::getInstance('HelpDesk');
		$ttModuleInstance->setRelatedList($moduleInstance,'Services',array('select'));
		
		$leadModuleInstance = Vtiger_Module::getInstance('Leads');
		$leadModuleInstance->setRelatedList($moduleInstance,'Services',array('select'));
		
		$accModuleInstance = Vtiger_Module::getInstance('Accounts');
		$accModuleInstance->setRelatedList($moduleInstance,'Services',array('select'));
		
		$conModuleInstance = Vtiger_Module::getInstance('Contacts');
		$conModuleInstance->setRelatedList($moduleInstance,'Services',array('select'));
		
		$potModuleInstance = Vtiger_Module::getInstance('Potentials');
		$potModuleInstance->setRelatedList($moduleInstance,'Services',array('select'));
		
		$pbModuleInstance = Vtiger_Module::getInstance('PriceBooks');
		$pbModuleInstance->setRelatedList($moduleInstance,'Services',array('select'),'get_pricebook_services');
	}
}
?>