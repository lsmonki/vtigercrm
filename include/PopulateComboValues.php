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
require_once('database/DatabaseConnection.php');

class PopulateComboValues 
{
		
	//var $table_name="lead_source";
	var $app_list_strings;	

	function insertComboValues($values, $tableName)
	{
		foreach ($values as $val => $cal)
		{
			if($val != '')
			{	
				mysql_query("insert into ".$tableName. " values('".$val."')");
			}
			else
			{
				mysql_query("insert into ".$tableName. " values('--None--')");
			}
		}
	}

	function create_tables () {
		global $app_list_strings;
		$comboTables = Array('lead_source','account_type','industry','lead_status','rating','license_key','opportunity_type','salutation','sales_stage');

		foreach ($comboTables as $comTab)
		{
			$result = mysql_query("show tables like '%".$comTab."%'");
			if(mysql_num_rows($result) == 0)
			{
				$query = 'CREATE TABLE '.$comTab.' (';
						$query .=$comTab.' varchar(200) NOT NULL';
						$query .=', PRIMARY KEY ('.$comTab.'))';

				mysql_query($query) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());
				echo("Created table ".$comTab);
				echo("<BR>");
				$this->insertComboValues($app_list_strings[$comTab."_dom"],$comTab);
			}
			else
			{
				echo("Table ".$comTab." already exists");
				echo("<BR>");
				$tableRows = mysql_query("select * from ".$comTab);
				if(mysql_num_rows($tableRows) == 0)
				{
					
					$this->insertComboValues($app_list_strings[$comTab."_dom"],$comTab);
				}
			}


		}
				
			
	}

	

}
?>
