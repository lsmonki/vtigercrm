<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the 
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/database/DatabaseConnection.php,v 1.3 2005/01/20 09:35:16 jack Exp $
 * Description:  Creates the runtime database connection.
 ********************************************************************************/

class DatabaseConnection {
/*
	var $connection;

	function connect() {
		global $dbconfig;
		global $disable_persistent_connections;

		$this->connection = false;
		
		if(!isset($disable_persistent_connections) || $disable_persistent_connections == false)
		{
			$this->connection = @mysql_pconnect($dbconfig['db_host_name'], $dbconfig['db_user_name'], 
				$dbconfig['db_password']);
				
		}
		
		if($this->connection == false)
		{
			// We were unable to connect with pconnect.  Try connect	
			$this->connection = mysql_connect($dbconfig['db_host_name'], 
				$dbconfig['db_user_name'], 
				$dbconfig['db_password']) 
				or die("Could not connect to server ".$dbconfig['db_host_name']." as ".$dbconfig['db_user_name'].".".mysql_error());

			if($this->connection == false && !isset($disable_persistent_connections) || $disable_persistent_connections == false)
			{
				
				$_SESSION['administrator_error'] = "<B>Severe Performance Degradation: Persistent Database Connections not working.  Please set \$disable_persistent_connections to true in your config.php file</B>";  			
			}

		}
			
		mysql_select_db($dbconfig['db_name'],$this->connection)
			or die("Could not select database. Reason: ".mysql_error());
	}
	function disconnect() {
		mysql_close($this->connection);
	}*/

	function println($msg)
	{
	require_once('include/logging.php');
	$log1 =& LoggerManager::getLogger('GS');
	if(is_array($msg))
	{
		$log1->fatal("PearDatabse ->".print_r($msg,true));
/*		$log1->fatal("PearDatabse ->".$this->getString($msg));
		foreach ($msg as $str)
		{
			if(is_array($str)) 
				$this->println($str);
			else
				$log1->fatal("PearDatabase ->".$str);
		}*/		
	}
	else
	{
		$log1->fatal("PearDatabase ->".$msg);
	}
	return $msg;
	}

}

	$database = new DatabaseConnection;
	$database->println("DatabaseConnection - Illegal Access");

?>
