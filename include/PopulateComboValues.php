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
require_once('include/ComboUtil.php');
/**
 *  Class which handles the population of the combo values
 * 
 *
 */
class PopulateComboValues
{
	var $app_list_strings;


	/** 
	 * To populate the default combo values for the combo vtiger_tables
	 * @param $values -- values:: Type string array
	 * @param $tableName -- tablename:: Type string 
	 */
	function insertComboValues($values, $tableName,$picklistid)
	{
		global $log;
		$log->debug("Entering insertComboValues(".$values.", ".$tableName.") method ...");
		global $adb;
		//inserting the value in the vtiger_picklistvalues_seq for the getting uniqueID for each picklist values...
		$i=0;
		foreach ($values as $val => $cal)
		{
			$picklist_valueid = getUniquePicklistID();
			$id = $adb->getUniqueID('vtiger_'.$tableName);
			if($val != '')
			{
				$adb->query("insert into vtiger_".$tableName. " values(".$id.",'".$val."',1,".$picklist_valueid.")");
			}
			else
			{
				$adb->query("insert into vtiger_".$tableName. " values(".$id.",'--None--',1,".$picklist_valueid.")");
			}

			//Default entries for role2picklist relation has been inserted..

			$sql="select roleid from vtiger_role";
			$role_result = $adb->query($sql);
			$numrow = $adb->num_rows($role_result);
			for($k=0; $k < $numrow; $k ++)
			{
				$roleid = $adb->query_result($role_result,$k,'roleid');
				$adb->query("insert into vtiger_role2picklist values('".$roleid."',".$picklist_valueid.",$picklistid,".$i.")");



			}

			$i++;
		}
	

		$log->debug("Exiting insertComboValues method ...");
	}


	/** 
	 * To populate the combo vtiger_tables at startup time
	 */

	function create_tables () 
	{
		global $log;
		$log->debug("Entering create_tables () method ...");
				
		global $app_list_strings,$adb;
		global $combo_strings;
		$comboTables = Array('leadsource','accounttype','industry','leadstatus','rating','opportunity_type','salutationtype','sales_stage','ticketstatus','ticketpriorities','ticketseverities','ticketcategories','eventstatus','taskstatus','taskpriority','manufacturer','productcategory','faqcategories','usageunit','glacct','quotestage','carrier','faqstatus','invoicestatus','postatus','sostatus','campaigntype','campaignstatus','expectedresponse');

		foreach ($comboTables as $comTab)
		{
			$picklistid = $adb->getUniqueID("vtiger_picklist");
			$picklist_qry = "insert into vtiger_picklist values(".$picklistid.",'".$comTab."')";
			$adb->query($picklist_qry);

			$this->insertComboValues($combo_strings[$comTab."_dom"],$comTab,$picklistid);
		}



		//we have to decide what are all the picklist and picklist values are non editable
		//presence = 0 means you cannot edit the picklist value
		//presence = 1 means you can edit the picklist value
		$noneditable_tables = Array("ticketstatus","taskstatus","eventstatus","faqstatus","quotestage","postatus","sostatus","invoicestatus");
		$noneditable_values = Array(
						"Closed Won"=>"sales_stage",
						"Closed Lost"=>"sales_stage",
					   );
		foreach($noneditable_tables as $picklistname)
		{
			$adb->query("update vtiger_".$picklistname." set PRESENCE=0");
		}
		foreach($noneditable_values as $picklistname => $value)
		{
			$adb->query("update vtiger_".$value." set PRESENCE=0 where $value='".$picklistname."'");
		}

		$log->debug("Exiting create_tables () method ...");

	}


	function create_nonpicklist_tables ()
	{
	
		global $log;
		$log->debug("Entering create_nonpicklist_tables () method ...");
				
		global $app_list_strings,$adb;
		global $combo_strings;
		$comboTables = Array('duration_minutes','activitytype','visibility','status','activity_view','lead_view','date_format','recurringtype','currency','licencekeystatus','taxclass');

		foreach ($comboTables as $comTab)
		{
			$this->insertNonPicklistValues($combo_strings[$comTab."_dom"],$comTab);
		}
		$log->debug("Exiting create_tables () method ...");
	}
	function insertNonPicklistValues($values, $tableName)
	{
		global $log;
		$log->debug("Entering insertNonPicklistValues(".$values.", ".$tableName.") method ...");
		global $adb;
		$i=0;
		foreach ($values as $val => $cal)
		{
				$id = $adb->getUniqueID('vtiger_'.$tableName);
				if($val != '')
				{
					$adb->query("insert into vtiger_".$tableName. " values(".$id.",'".$val."',".$i.",1)");
				}
				else
				{
					$adb->query("insert into vtiger_".$tableName. " values(".$id.",'--None--',".$i.",1)");
				}
				$i++;
		}
		$log->debug("Exiting insertNonPicklistValues method ...");
	}

}
?>
