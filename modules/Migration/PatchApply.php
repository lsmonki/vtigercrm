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

include("modules/Migration/versions.php");

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

for($i=0;$i<count($temp);$i++)
{
	//Here we have to include all the files (all db differences for each release will be included)
	$filename = "modules/Migration/DBChanges/".$temp[$i]."_to_".$temp[$i+1].".php";

	$empty_tag = "<tr><td colspan='3'>&nbsp;</td></tr>";
	$start_tag = "<tr><td colspan='3'><b><font color='red'>&nbsp;";
	$end_tag = "</font></b></td></tr>";
	
	if(is_file($filename))
	{
		echo $empty_tag.$start_tag.$temp[$i]." ==> ".$temp[$i+1]." Database changes -- Starts.".$end_tag;
		
		include($filename);//include the file which contains the corresponding db changes

		echo $start_tag.$temp[$i]." ==> ".$temp[$i+1]." Database changes -- Ends.".$end_tag;
	}
	elseif(isset($temp[$i+1]))
	{
		echo $empty_tag.$start_tag."There is no Database Changes from ".$temp[$i]." ==> ".$temp[$i+1].$end_tag;
	}
	else
	{
		//No file available or Migration not provided for this release
		//echo '<br>No Migration / No File ==> '.$filename;
	}
}

if(!isset($continue_42P2))//This variable is used in MigrationInfo.php to avoid display the table tag
{
	echo '</table>';
	echo '<br><br><b style="color:#FF0000">Failed Queries Log</b>
		<div id="failedLog" style="border:1px solid #666666;width:90%;position:relative;height:200px;overflow:auto;left:5%;top:10px;">';

	foreach($failure_query_array as $failed_query)
		echo '<br><font color="red">'.$failed_query.';</font>';

	echo '<br></div>';
	//echo "Failed Queries ==> <pre>";print_r($failure_query_array);echo '</pre>';
}
	
//HANDLE HERE - Mickie
//Here we have to update the version in table. so that when we do migration next time we will get the version
global $adb, $vtiger_current_version;
$adb->query("update vtiger_version set old_version='".$versions[$source_version]."',current_version='".$vtiger_current_version."'");



//Function used to execute the query and display the success/failure of the query
function ExecuteQuery($query)
{
	global $adb;
	global $conn, $query_count, $success_query_count, $failure_query_count, $success_query_array, $failure_query_array;
        global $migrationlog;

	$status = $adb->query($query);

	$query_count++;	
	if(is_object($status))
	{
		echo '
			<tr width="100%">
				<td width="10%">'.$status.'</td>
				<td width="10%"><font color="green"> S </font></td>
				<td width="80%">'.$query.'</td>
			</tr>';
		$success_query_array[$success_query_count++] = $query;
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
	}
}










?>
