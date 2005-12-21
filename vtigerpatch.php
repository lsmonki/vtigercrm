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

require_once('config.php');
require_once('include/utils.php');
$filename = $root_directory.'vtigerversion.php';
$patch_applied=false;
$handle = @fopen($filename, "r+");
$newbuf = '';
if($handle)
{
	$pv = '';
	while (!feof($handle)) {

	    $buffer = fgets($handle, 5200);

	    list($starter, $tmp) = explode(" = ", $buffer);
	    if($starter == '$patch_version' && stristr($tmp,'2'))
    	    {
		$newbuf .= "\$patch_version = '2';\n";
		$newbuf .= "\$patch_release_date = '16 September 2005';\n";
		$pv = 2;
    	    } 
	    elseif($starter == '$patch_version' && stristr($tmp,'1'))
    	    {
		$newbuf .= "\$patch_version = '2';\n";
		$newbuf .= "\$patch_release_date = '16 September 2005';\n";
		$pv = 1;
    	    } 
	    elseif($starter == '$patch_version')
    	    {
		$newbuf .= "\$patch_version = '2';\n";
		$newbuf .= "\$patch_release_date = '16 September 2005';\n";
    	    }
	    elseif($starter == '$vtiger_current_version' && !stristr($tmp,'4.2'))
    	    {
		die("<font color=red><center> *** This Patch cannot be applied for vtiger CRM versions other than 4.2! *** </center></font>");   
    	    }
	    elseif($starter == '$modified_database' && stristr($tmp,'true'))
    	    {
        	$newbuf .= "\$modified_database = 'true';\n";
		$patch_applied = true;
    	    }
	    elseif($starter == '$modified_database')
    	    {
        	$newbuf .= "\$modified_database = 'true';\n";
    	    }
    	    else
	    {	
		$newbuf .= $buffer;
	    }
	}
fclose($handle);

// Check whether patch is applied and then execute Alter table commands
if(!$patch_applied && $pv == '')
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

	//Added for patch2
	$query = "update field set sequence=9 where tabid=14 and fieldname='manufacturer' and tablename='products' and columnname='manufacturer'";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);

	$query = "update field set fieldlabel='Billing City' where tabid=6 and tablename='accountbillads' and fieldlabel='City'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Billing State' where tabid=6 and tablename='accountbillads' and fieldlabel='State'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Billing Code' where tabid=6 and tablename='accountbillads' and fieldlabel='Code'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Billing Country' where tabid=6 and tablename='accountbillads' and fieldlabel='Country'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Shipping City' where tabid=6 and tablename='accountshipads' and fieldlabel='City'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Shipping Country' where tabid=6 and tablename='accountshipads' and fieldlabel='Country'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Shipping State' where tabid=6 and tablename='accountshipads' and fieldlabel='State'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Shipping Code' where tabid=6 and tablename='accountshipads' and fieldlabel='Code'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);

	$query = "update field set fieldlabel='Mailing City' where tabid=4 and tablename='contactaddress' and fieldname='mailingcity'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Other City' where tabid=4 and tablename='contactaddress' and fieldname='othercity'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Mailing State' where tabid=4 and tablename='contactaddress' and fieldname='mailingstate'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Mailing Zip' where tabid=4 and tablename='contactaddress' and fieldname='mailingzip'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Mailing Country' where tabid=4 and tablename='contactaddress' and fieldname='mailingcountry'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Other State' where tabid=4 and tablename='contactaddress' and fieldname='otherstate'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Other Zip' where tabid=4 and tablename='contactaddress' and fieldname='otherzip'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Other Country' where tabid=4 and tablename='contactaddress' and fieldname='othercountry'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);

	$query = "alter table users change signature signature varchar(250)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);

	$query = "insert into relatedlists values (".$adb->getUniqueID('relatedlists').",9,0,'get_users',1,'Users',0)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);

	$query = "insert into relatedlists values (".$adb->getUniqueID('relatedlists').",9,4,'get_contacts',2,'Contacts',0)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	
	$query = "insert into relatedlists values(".$adb->getUniqueID('relatedlists').",".getTabid("Potentials").",".getTabid("Activities").",'get_history',8,'History',0)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	
	$query = "insert into relatedlists values(".$adb->getUniqueID('relatedlists').",".getTabid("Orders").",".getTabid("Activities").",'get_history',3,'History',0)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	
	$query = "insert into relatedlists values(".$adb->getUniqueID('relatedlists').",".getTabid("SalesOrder").",".getTabid("Activities").",'get_history',4,'History',0)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	
	$query = "insert into relatedlists values(".$adb->getUniqueID('relatedlists').",".getTabid("Invoice").",".getTabid("Activities").",'get_history',3,'History',0)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	
	$query = "insert into profile2utility values (1,14,5,0)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);

	$query = "insert into profile2utility values (1,14,6,0)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	
	$query = "insert into profile2utility values (2,14,5,1)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);

	$query = "insert into profile2utility values (2,14,6,1)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
		
	$query = "insert into profile2utility values (3,14,5,1)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);

	$query = "insert into profile2utility values (3,14,6,1)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	
	$query = "insert into profile2utility values (4,14,5,1)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);

	$query = "insert into profile2utility values (4,14,6,1)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);

	$db->query("alter table wordtemplates drop PRIMARY KEY");

	$db->query("alter table wordtemplates add column templateid int(19) FIRST");

	$temp_result = $db->query("select * from wordtemplates");
	$rows= $db->num_rows($temp_result);
	if($rows >=1)
	{
		for($j=0; $j<$rows;$j++)
		{
			$fname = $db->query_result($temp_result,$j,"filename");
			$templateid = $db->getUniqueID("wordtemplates");
			$db->query("update wordtemplates set templateid=$templateid where filename ='$fname'");	
		}	
	}
	$db->query("alter table wordtemplates add PRIMARY KEY (templateid)");
	echo "Table 'wordtemplates' modified";

	echo '<BR> <BR>';
	echo "<font color=blue><center> *** DataBase modified Successfully for vtiger CRM 4.2 Patch !!!  *** </center></font>";
}
elseif($patch_applied && $pv == 1)
{
	// Applying Patch 2 Db changes	

	require_once('include/database/PearDatabase.php');
	$db = new PearDatabase();

	$query = "update field set sequence=9 where tabid=14 and fieldname='manufacturer' and tablename='products' and columnname='manufacturer'";
	echo '<BR> '.$query.'<BR>';
	$db->query($query);

	$query = "update field set fieldlabel='Billing City' where tabid=6 and tablename='accountbillads' and fieldlabel='City'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Billing State' where tabid=6 and tablename='accountbillads' and fieldlabel='State'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Billing Code' where tabid=6 and tablename='accountbillads' and fieldlabel='Code'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Billing Country' where tabid=6 and tablename='accountbillads' and fieldlabel='Country'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Shipping City' where tabid=6 and tablename='accountshipads' and fieldlabel='City'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Shipping Country' where tabid=6 and tablename='accountshipads' and fieldlabel='Country'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Shipping State' where tabid=6 and tablename='accountshipads' and fieldlabel='State'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Shipping Code' where tabid=6 and tablename='accountshipads' and fieldlabel='Code'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);

	$query = "update field set fieldlabel='Mailing City' where tabid=4 and tablename='contactaddress' and fieldname='mailingcity'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Other City' where tabid=4 and tablename='contactaddress' and fieldname='othercity'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Mailing State' where tabid=4 and tablename='contactaddress' and fieldname='mailingstate'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Mailing Zip' where tabid=4 and tablename='contactaddress' and fieldname='mailingzip'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Mailing Country' where tabid=4 and tablename='contactaddress' and fieldname='mailingcountry'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Other State' where tabid=4 and tablename='contactaddress' and fieldname='otherstate'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Other Zip' where tabid=4 and tablename='contactaddress' and fieldname='otherzip'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	$query = "update field set fieldlabel='Other Country' where tabid=4 and tablename='contactaddress' and fieldname='othercountry'";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);

	$query = "alter table users change signature signature varchar(250)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);

	$query = "insert into relatedlists values (".$adb->getUniqueID('relatedlists').",9,0,'get_users',1,'Users',0)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);

	$query = "insert into relatedlists values (".$adb->getUniqueID('relatedlists').",9,4,'get_contacts',2,'Contacts',0)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	
	$query = "insert into relatedlists values(".$adb->getUniqueID('relatedlists').",".getTabid("Potentials").",".getTabid("Activities").",'get_history',8,'History',0)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	
	$query = "insert into relatedlists values(".$adb->getUniqueID('relatedlists').",".getTabid("Orders").",".getTabid("Activities").",'get_history',3,'History',0)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	
	$query = "insert into relatedlists values(".$adb->getUniqueID('relatedlists').",".getTabid("SalesOrder").",".getTabid("Activities").",'get_history',4,'History',0)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	
	$query = "insert into relatedlists values(".$adb->getUniqueID('relatedlists').",".getTabid("Invoice").",".getTabid("Activities").",'get_history',3,'History',0)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	
	$query = "insert into profile2utility values (1,14,5,0)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);

	$query = "insert into profile2utility values (1,14,6,0)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	
	$query = "insert into profile2utility values (2,14,5,1)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);

	$query = "insert into profile2utility values (2,14,6,1)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
		
	$query = "insert into profile2utility values (3,14,5,1)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);

	$query = "insert into profile2utility values (3,14,6,1)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);
	
	$query = "insert into profile2utility values (4,14,5,1)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);

	$query = "insert into profile2utility values (4,14,6,1)";
	echo '<BR> '.$query.'<BR>';
        $db->query($query);

	$db->query("alter table wordtemplates drop PRIMARY KEY");

	$db->query("alter table wordtemplates add column templateid int(19) FIRST");

	$temp_result = $db->query("select * from wordtemplates");
	$rows= $db->num_rows($temp_result);
	if($rows >=1)
	{
		for($j=0; $j<$rows;$j++)
		{
			$fname = $db->query_result($temp_result,$j,"filename");
			$templateid = $db->getUniqueID("wordtemplates");
			$db->query("update wordtemplates set templateid=$templateid where filename ='$fname'");	
		}	
	}
	$db->query("alter table wordtemplates add PRIMARY KEY (templateid)");
	echo "Table 'wordtemplates' modified";

	echo '<BR> <BR>';
	echo "<font color=blue><center> *** Database modified Successfully for vtiger CRM 4.2 Patch 2 !!!  *** </center></font>";
}
else
{
	echo "<font color=green><center> *** Database changes for vtiger CRM 4.2 patches have been applied already! *** </center></font>";
}

$handle = fopen($filename, "w");
fputs($handle, $newbuf);

}
else
{
	echo "<font color=red><center> *** File <b>$filename</b> does not exist or it may not have write permission. *** </center></font>";
}

$filename = $root_directory.'config.php';
$new_filename = $root_directory.'new_config.php';
$handle = @fopen($filename, "r+");
$newbuf = '';
if($handle)
{
	$filecontents = file_get_contents($filename);
	//To check whether the file is already rewrited with the variables
	$exist = strstr($filecontents,'$PORTAL_URL');

	if(!$exist)
	{
		while (!feof($handle)) 
		{
			$buffer = fgets($handle, 4096);
			list($starter, $tmp) = explode(" = ", $buffer);
			if($starter == '$CALCULATOR_DISPLAY')
			{
				$newbuf .= "\$CALCULATOR_DISPLAY = 'true';\n\n";
				$newbuf .= "//This is the URL for customer portal. (Ex. http://vtiger.com/portal)\n";
				$newbuf .= "\$PORTAL_URL = 'http://your-domain.com/customerportal';\n\n";
				$newbuf .= "//These two are the HelpDesk support email id and the support name. ";
				$newbuf .= "(Ex. 'support@vtiger.com' and 'vtiger Support')\n";
				$newbuf .= "\$HELPDESK_SUPPORT_EMAIL_ID = 'support@your-domain.com';\n";
				$newbuf .= "\$HELPDESK_SUPPORT_NAME = 'your-domain Name';\n";
			}
			else
			{
				$newbuf .= $buffer;
			}
		}
		fclose($handle);

		//Open the file new_conf.php and write the new contents of conf.php file
		$new_handle = fopen($new_filename, "w");
		fputs($new_handle, $newbuf);
		fclose($new_handle);

		//Rename the original config.php to bkp_config.php & rename the new_config.php file to config.php
		rename($root_directory."config.php",$root_directory."bkp_config.php");
		rename($root_directory."new_config.php",$root_directory."config.php");
		echo '<br><b> config.php file has been updated.</b>';
	}
	else
	{
		echo '<br><b> config.php file has been updated already.</b>';
	}
}
else
{
	echo "<br><b> Could not read the file config.php. Please give write permission to this file.</b>";
}

?>

