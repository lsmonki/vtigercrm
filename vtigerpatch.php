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

$filename = './vtigerversion.php';
$patch_applied=false;
$handle = @fopen($filename, "r+");
$newbuf = '';
if($handle)
{
	
	while (!feof($handle)) {

	    $buffer = fgets($handle, 5200);

	    list($starter, $tmp) = explode(" = ", $buffer);
	    if($starter == '$patch_version')
    	    {
		$newbuf .= "\$patch_version = '1';\n";
    	    }
	    elseif($starter == '$modified_database')
    	    {
        	$newbuf .= "\$modified_database = 'true';\n";
    	    }
    	    else
    	    {
    		$newbuf .= $buffer;
    	    }

		if($starter == '$modified_database' && stristr($tmp,'true'))
		$patch_applied = true;

	}
fclose($handle);

// Check whether patch is applied and then execute Alter table commands
if(!$patch_applied)
{
  
	require_once('include/database/PearDatabase.php');
	$db = new PearDatabase();
	$query1 = "update field set typeofdata='I~O' where fieldname='noofemployees' and tablename='leaddetails' and columnname='noofemployees'";
	echo '<BR> '.$query1.'<BR>';
	$db->query($query1);

	//Increasing the website field length in leadsubdetails table
	$query2 = "alter table leadsubdetails change website website VARCHAR(255)";
	echo '<BR> '.$query2.'<BR>';
	$db->query($query2);

	//Integrating the performance fix by microwe 
	$query = "ALTER TABLE `account` ADD INDEX ( `account_type` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);

	$query = "ALTER TABLE `activity` ADD INDEX ( `activitytype`,`date_start` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `activity` ADD INDEX ( `date_start`,`due_date` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `activity` ADD INDEX ( `date_start`,`time_start` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `activity` ADD INDEX ( `eventstatus` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `activity` ADD INDEX ( `status`,`eventstatus` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `attachments` ADD INDEX ( `attachmentsid` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `attachments` ADD INDEX ( `description`,`name`,`type`,`attachmentsid` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `crmentity` ADD INDEX ( `deleted`,`smownerid` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `crmentity` ADD INDEX ( `smownerid`,`deleted` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `customaction` ADD INDEX ( `cvid` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `customview` ADD INDEX ( `cvid` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `cvadvfilter` ADD INDEX ( `cvid` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `cvcolumnlist` ADD INDEX ( `columnindex` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `cvstdfilter` ADD INDEX ( `cvid` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `def_org_field` ADD INDEX ( `tabid` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `def_org_field` ADD INDEX ( `visible`,`fieldid` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `field` ADD INDEX ( `fieldname` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `field` ADD INDEX ( `tabid`,`block`,`displaytype` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `leaddetails` ADD INDEX ( `converted`,`leadstatus` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `potential` ADD INDEX ( `potentialid` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `potential` ADD INDEX ( `sales_stage` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `profile2field` ADD INDEX ( `tabid`,`profileid` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `profile2field` ADD INDEX ( `visible`,`profileid` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `quotes` ADD INDEX ( `quotestage` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `seactivityrel` ADD INDEX ( `crmid` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `seattachmentsrel` ADD INDEX ( `attachmentsid`,`crmid` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `tab` ADD INDEX ( `tabid` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE `troubletickets` ADD INDEX ( `status` )";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);
	$query = "ALTER TABLE potential MODIFY probability decimal(7,3)";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);

	$query = "UPDATE field SET typeofdata='N~O~3,3~LE~100' WHERE tablename='potential' and columnname = 'probability' and fieldname='probability'";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);

	echo '<BR> <BR>';
	echo "<font color=red><center> *** DataBase modified Successfully!!!  *** </center></font>";
	
}
else
{
	echo "Database changes for patch 1 has been applied already";
}

$handle = fopen($filename, "w");
fputs($handle, $newbuf);

}
else
{
	echo "File <b>$filename</b> does not exist or it may not have write permission.";
}

?>

