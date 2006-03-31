<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/language/en_us.lang.php');
require_once('include/database/PearDatabase.php');
require_once('include/ComboStrings.php');

class PopulateComboValues
{
	var $app_list_strings;

	function insertComboValues($values, $tableName)
	{
		global $adb;
		$i=0;
		foreach ($values as $val => $cal)
		{
			if($val != '')
			{
				$adb->query("insert into ".$tableName. " values(null,'".$val."',".$i.",1)");
			}
			else
			{
				$adb->query("insert into ".$tableName. " values(null,'--None--',".$i.",1)");
			}
			$i++;
		}
	}

	function create_tables () 
	{
		global $app_list_strings,$adb;
		global $combo_strings;
		$comboTables = Array('leadsource','accounttype','industry','leadstatus','rating','licencekeystatus','opportunity_type','salutationtype','sales_stage','ticketstatus','ticketpriorities','ticketseverities','ticketcategories','duration_minutes','eventstatus','taskstatus','taskpriority','manufacturer','productcategory','activitytype','currency','faqcategories','rsscategory','usageunit','glacct','quotestage','carrier','taxclass','recurringtype','faqstatus','invoicestatus','postatus','sostatus','visibility','campaigntype','campaignstatus','expectedrevenue','actualcost','expectedresponse');

		foreach ($comboTables as $comTab)
		{
			$this->insertComboValues($combo_strings[$comTab."_dom"],$comTab);
		}
	}
}
?>
