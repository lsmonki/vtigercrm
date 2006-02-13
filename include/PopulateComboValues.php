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

	//var $table_name="lead_source";
	var $app_list_strings;

  function insertComboValues($values, $tableName)
	{
          global $adb;
//	  global $vtlog;
//	  $vtlog->logthis("in  insertComboValues ".$tableName ,'info');  
          $i=0;
          foreach ($values as $val => $cal)
          {
            if($val != '')
            {
              $adb->query("insert into ".$tableName. " values('','".$val."',".$i.",1)");
            }
            else
            {
              $adb->query("insert into ".$tableName. " values('','--None--',".$i.",1)");
            }
            $i++;
          }
	}

	function create_tables () {
		global $app_list_strings,$adb;
                global $combo_strings;
		$comboTables = Array('leadsource','accounttype','industry','leadstatus','rating','licencekeystatus','opportunity_type','salutationtype','sales_stage','ticketstatus','ticketpriorities','ticketseverities','ticketcategories','duration_minutes','eventstatus','taskstatus','taskpriority','manufacturer','productcategory','activitytype','currency','faqcategories','rsscategory','usageunit','glacct','quotestage','carrier','taxclass','recurringtype','faqstatus','invoicestatus','postatus','sostatus');

		foreach ($comboTables as $comTab)
		{
			/*$result = mysql_query("show tables like '%".$comTab."%'");
			if(mysql_num_rows($result) == 0)
			{
				$query = 'CREATE TABLE '.$comTab.' (';
						$query .=$comTab.' varchar(200) NOT NULL';
						$query .=', PRIMARY KEY ('.$comTab.'))';

				mysql_query($query) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());
				echo("Created table ".$comTab);
				echo("<BR>");
				$this->insertComboValues($combo_strings[$comTab."_dom"],$comTab);
			}
			else
			{
				echo("Table ".$comTab." already exists");
				echo("<BR>");
				$tableRows = mysql_query("select * from ".$comTab);
				if(mysql_num_rows($tableRows) == 0)
				{

					$this->insertComboValues($combo_strings[$comTab."_dom"],$comTab);
				}
			}*/

                  $this->insertComboValues($combo_strings[$comTab."_dom"],$comTab);
		}

	}



}
?>
