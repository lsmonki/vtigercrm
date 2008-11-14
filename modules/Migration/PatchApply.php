<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
********************************************************************************/

ini_set("memory_limit","32M");
global $php_max_execution_time;
set_time_limit($php_max_execution_time);

global $current_user;
if($current_user->is_admin != 'on')
{
	die("<br><br><center>".$app_strings['LBL_PERMISSION']." <a href='javascript:window.history.back()'>".$app_strings['LBL_GO_BACK'].".</a></center>");
}

include("modules/Migration/versions.php");
$migrationlog =& LoggerManager::getLogger('MIGRATION');

//global $vtiger_current_version;
if($_REQUEST['source_version'] != '')
	$source_version = $_REQUEST['source_version'];

//echo '<br>Source Version = '.$source_version.'<br>Current Version = '.$current_version;

if(!isset($source_version) || empty($source_version))
{
	//If source version is not set then we cannot proceed
	echo "<br> Source Version is not set. Please provide the Source Version and contiune the Patch Process";
	exit;
}

$reach = 0;
foreach($versions as $version => $label)
{
	if($version == $source_version || $reach == 1)
	{
		$reach = 1;
		$temp[] = $version;
	}
}
$temp[] = $current_version;

if(!isset($continue_42P2))//This variable is used in MigrationInfo.php to avoid display the table tag
{
	echo "<br>Going to apply the Database Changes...<br>";
	//echo '<table width="98%" cellpadding="3" cellspacing="0" border="0" class="MigInfo">';
	echo '<table width="98%" border="1px" cellpadding="3" cellspacing="0" height="100%">';
}

//Here we have to decide the database object to which we have to run the migration queries
//For options 1 and 2 we need to execute the queries in current database ie., adb object
//But for option 3, we have to run the queries in given 4.2.3 database ie., conn object
//This session variable should be used in all patch files(which contains the queries) so that based on the option selected the queries will be executed in the corresponding database. ie., in all patch files we have to assign this session object to adb and conn objects
global $adb;
if($_REQUEST['migration_option'] == 'alter_db_details')
	$_SESSION['adodb_current_object'] = $conn;
else
	$_SESSION['adodb_current_object'] = $adb;


for($patch_count=0;$patch_count<count($temp);$patch_count++)
{
	//Here we have to include all the files (all db differences for each release will be included)
	$filename = "modules/Migration/DBChanges/".$temp[$patch_count]."_to_".$temp[$patch_count+1].".php";

	$empty_tag = "<tr><td colspan='3'>&nbsp;</td></tr>";
	$start_tag = "<tr><td colspan='3'><b><font color='red'>&nbsp;";
	$end_tag = "</font></b></td></tr>";
	
	if(is_file($filename))
	{
		echo $empty_tag.$start_tag.$temp[$patch_count]." ==> ".$temp[$patch_count+1]." Database changes -- Starts.".$end_tag;

		include($filename);//include the file which contains the corresponding db changes

		echo $start_tag.$temp[$patch_count]." ==> ".$temp[$patch_count+1]." Database changes -- Ends.".$end_tag;
	}
	elseif(isset($temp[$patch_count+1]))
	{
		echo $empty_tag.$start_tag."There is no Database Changes from ".$temp[$patch_count]." ==> ".$temp[$patch_count+1].$end_tag;
	}
	else
	{
		//No file available or Migration not provided for this release
		//echo '<br>No Migration / No File ==> '.$filename;
	}
}
	
	if(getMigrationCharsetFlag() == MIG_CHARSET_PHP_UTF8_DB_UTF8)
	{
		echo '</table><br><br>';
		include("modules/Migration/HTMLtoUTF8Conversion.php");
	}
	
if(!isset($continue_42P2))//This variable is used in MigrationInfo.php to avoid display the table tag
{
	echo '</table>';
	echo '<br><br><b style="color:#FF0000">Failed Queries Log</b>
		<div id="failedLog" style="border:1px solid #666666;width:90%;position:relative;height:200px;overflow:auto;left:5%;top:10px;">';

	if(is_array($failure_query_array))
		foreach($failure_query_array as $failed_query)
			echo '<br><font color="red">'.$failed_query.';</font>';
	else
		echo '<br> No queries failed during Patch Update.';

	echo '<br></div>';
	//echo "Failed Queries ==> <pre>";print_r($failure_query_array);echo '</pre>';
}


//HANDLE HERE - Mickie
//Here we have to update the version in table. so that when we do migration next time we will get the version
global $adb, $vtiger_current_version;
$res = $adb->pquery("select * from vtiger_version", array());
if($adb->num_rows($res))
{
	$res = $adb->pquery("update vtiger_version set old_version=?,current_version=?", array($versions[$source_version], $vtiger_current_version));
}
else
{
	$adb->pquery("insert into vtiger_version (id, old_version, current_version) values ('', ?, ?);", array($versions[$source_version], $vtiger_current_version));
}

//If currency name in config.inc.php file and currency name in vtiger_currency_info table is differ then we have to change in config.inc.php file
$mig_currency = $adb->query_result($adb->pquery("select currency_name from vtiger_currency_info", array()),0,'currency_name');
if($currency_name != $mig_currency)
{
	echo "<br><br><b><font color='red'>NOTE:<br><br>Please change the base currency name as '$mig_currency' in config.inc.php ie., change the variable currency name as $"."currency_name = '$mig_currency' in config.inc.php file.</b><br><br>";
}

//Added to check database charset and $default_charset are set to UTF8.
//If both are not set to be UTF-8, Then we will show an alert message.
/*
function check_db_utf8_charset($conn) 
{ 
	$dbvarRS = &$conn->query("show variables like '%_database' "); 
	$db_character_set = null; 
	$db_collation_type = null; 
	while(!$dbvarRS->EOF) { 
		$arr = $dbvarRS->FetchRow(); 
		$arr = array_change_key_case($arr); 
		switch($arr['variable_name']) { 
		case 'character_set_database' : $db_character_set = $arr['value']; break; 
		case 'collation_database'     : $db_collation_type = $arr['value']; break; 
		}
		// If we have all the required information break the loop. 
		if($db_character_set != null && $db_collation_type != null) break; 
	} 
	return (stristr($db_character_set, 'utf8') && stristr($db_collation_type, 'utf8')); 
}

/*	global $adb,$default_charset;
	$db_status=check_db_utf8_charset($adb);
	if(strtolower($default_charset) == 'utf-8')	$config_status=1;
	else						$config_status=0;

	if(!$db_status && !$config_status)
	{
		$msg='<br><font color="red"><b>Your database charset and $default_charset variable in config.inc.php are not set to UTF-8. Due to that you may not use UTF-8 characters in vtigerCRM. Please set the above to UTF-8</b></font>';
	}
	else if($db_status && !$config_status)
	{
		$msg='<br><font color="red"><b>Your database charset is set as UTF-8. But $default_charset variable in config.inc.php is not set to UTF-8. Due to that you may not use UTF-8 characters in vtigerCRM. Please set the $default_charset variable to UTF-8</b></font>';

	}
	else if(!$db_status && $config_status)
	{
		$msg='<br><font color="red"><b>Your $default_charset variable in config.inc.php is set as UTF-8. But your database charset is not set as UTF-8. Due to that you may not use UTF-8 characters in vtigerCRM. Please set your database charset to UTF-8</b></font>';

	}
	echo $msg;
if(!$continue_42P2 && $db_change_conformation == true)
{
echo '<br><table border="1" cellpadding="3" cellspacing="0" height="100%" width="80%" align="center">
		<tr>
		<td colspan="2" align="center"><br>If you are migrating from 5.0.3 or earlier versions, which had partial unicode (UTF-8) support, the unicode characters got saved as HTML entities. <br>If you are going to use ISO charset in config file and in database, then you need not do conversion.<br> Click on "Convert Now" button to convert html entities into utf8 characters.<br><br> 
					<form name="html_to_utf" method="post" action="index.php">
					<input type="hidden" name="module" value="Migration">
					<input type="hidden" name="action" value="HTMLtoUTF8Conversion">

					<input type="submit" name="close" value=" &nbsp;Convert Now&nbsp; " class="crmbutton small save" />
				</form><br>
			</td>
		</tr>
	</table><br><br>';

}
//if($db_change_conformation == true)
//{ */
echo '<table width="95%"  border="0" align="center">
	<tr bgcolor="#FFFFFF"><td colspan="2">&nbsp;</td></tr>
		<tr>
			<td colspan="2" align="center">
				<form name="close_migration" method="post" action="index.php">
					<input type="hidden" name="module" value="Settings">
					<input type="hidden" name="action" value="index">
					<input type="submit" name="close" value=" &nbsp;Close&nbsp; " class="crmbutton small cancel" />
				</form>
			</td>
		</tr>
	</table><br><br>';
//}
perform_post_migration_activities();

//Function used to execute the query and display the success/failure of the query
function ExecuteQuery($query)
{
	global $adb;
	global $conn, $query_count, $success_query_count, $failure_query_count, $success_query_array, $failure_query_array;
        global $migrationlog;

	//For third option migration we have to use the $conn object because the queries should be executed in 4.2.3 db
	if($_REQUEST['migration_option'] == 'alter_db_details')
		$status = $conn->query($query);
	else
		$status = $adb->query($query);

	$query_count++;	
	if(is_object($status))
	{
		echo '
			<tr width="100%">
				<td width="10%">'.get_class($status).'</td>
				<td width="10%"><font color="green"> S </font></td>
				<td width="80%">'.$query.'</td>
			</tr>';
		$success_query_array[$success_query_count++] = $query;
		$migrationlog->debug("Query Success ==> $query");
	}
	else
	{
		echo '
			<tr width="100%">
				<td width="25%">'.$status.'</td>
				<td width="5%"><font color="red"> F </font></td>
				<td width="70%">'.$query.'</td>
			</tr>';
		$failure_query_array[$failure_query_count++] = $query;
		$migrationlog->debug("Query Failed ==> $query \n Error is ==> [".$adb->database->ErrorNo()."]".$adb->database->ErrorMsg());
	}
}


?>
