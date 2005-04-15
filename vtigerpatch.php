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
$handle = @fopen($filename, "r");
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
	echo "<br><b>Following Modification made in vtigercrm4 database tables</b><br><br>";
	$db->query("alter table loginhistory change logout_time lo_time timestamp(14)");
	$db->query("alter table loginhistory change login_time logout_time timestamp(14);");
	$db->query("alter table loginhistory change lo_time login_time timestamp(14);");
        echo "a) Login History table fields modified.<br>";
	$db->query("update field set typeofdata='V~M',fieldname='ticket_title' where tabid = 13 and fieldname='title';");
	$db->query("update field set fieldname='subject' where tabid = 10 and fieldname='name';");
	echo "b) Email and Trouble ticket table fields modified.<br>";
	$fieldid = $db->getUniqueID("field");
	$db->query("insert into field values (16,".$fieldid.",'eventstatus','activity',1,'15','eventstatus','Status',1,0,0,100,9,1,1,'V~O');");
	$db->query("insert into profile2field values(1,16,".$fieldid.",0,1)");
	$db->query("insert into profile2field values(2,16,".$fieldid.",0,1)");
	$db->query("insert into profile2field values(3,16,".$fieldid.",0,1)");
	$db->query("insert into profile2field values(4,16,".$fieldid.",0,1)");
	$db->query("insert into field values (9,".$db->getUniqueID("field").",'eventstatus','activity',1,'15','eventstatus','Status',1,0,0,100,9,1,1,'V~O');");
	$db->query("alter table activity add column eventstatus varchar(50) after status");
	
	echo "c) Activity and Events table modified for adding event status field.<br>";
	echo "<br><b>Table modification completed.</b><br>";
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
	echo "File <b>$filename</b> does not exist";
}

?>

