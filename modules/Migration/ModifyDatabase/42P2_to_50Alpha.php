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

//This file is used to modify the database from 4.2Patch2 to 5.0 Alpha release


global $conn;

$conn->println("Database Modifications for 4.2 Patch2 ==> 5.0(Alpha) Dev 3 Starts here.");
echo "<br><br><b>Database Modifications for 4.2 Patch2 ==> 5.0(Alpha) Dev 3 Starts here.....</b><br>";

/****************** 5.0(Alpha) dev version 1 Database changes -- Starts*********************/

//Added Primay Keys for the left out tables
$alter_array1 = Array(
		"alter table activity_reminder ADD PRIMARY KEY (activity_id,recurringid)",
		"alter table activitygrouprelation ADD PRIMARY KEY (activityid)",
		"alter table cvadvfilter ADD PRIMARY KEY (cvid,columnindex)",
		"alter table cvcolumnlist ADD PRIMARY KEY (cvid,columnindex)",
		"alter table cvstdfilter ADD PRIMARY KEY (cvid)",
		"alter table def_org_field ADD PRIMARY KEY (fieldid)",
		"alter table leadgrouprelation ADD PRIMARY KEY (leadid)",
		"alter table leadgrouprelation drop key leadgrouprelation_IDX0",
		"alter table organizationdetails ADD PRIMARY KEY (organizationame)",
		"alter table profile2field ADD PRIMARY KEY (profileid,fieldid)",
		"alter table profile2standardpermissions ADD PRIMARY KEY (profileid,tabid,Operation)",
		"alter table profile2standardpermissions drop index idx_prof2stad",
		"alter table profile2utility ADD PRIMARY KEY (profileid,tabid,activityid)",
		"alter table profile2utility drop index idx_prof2utility",
		"alter table relcriteria ADD PRIMARY KEY (queryid,columnindex)",
		"alter table reportdatefilter ADD PRIMARY KEY (datefilterid)",
		"alter table reportdatefilter DROP INDEX reportdatefilter_IDX0",
		"alter table reportsortcol ADD PRIMARY KEY (sortcolid,reportid)",
		"alter table reportsummary ADD PRIMARY KEY (reportsummaryid,summarytype,columnname)",
		"drop table role2action",
		"drop table role2tab",
		"alter table selectcolumn ADD PRIMARY KEY (queryid,columnindex)",
		"alter table ticketgrouprelation ADD PRIMARY KEY (ticketid)",
		"alter table ticketstracktime ADD PRIMARY KEY (ticket_id)",
		"alter table users2group ADD PRIMARY KEY (groupname,userid)",
		"alter table users2group DROP INDEX idx_users2group",
		);
foreach($alter_array1 as $query)
{
	$status = $conn->query($query);
	echo '<br>'.$status.' ==> '.$query;
}

//Tables profile2globalpermissions, actionmapping creation
$create_sql1 = "CREATE TABLE `profile2globalpermissions` (
`profileid` int(19) NOT NULL default '0',
	`globalactionid` int(19) NOT NULL default '0',
	`globalactionpermission` int(19) default NULL,
	PRIMARY KEY (`profileid`,`globalactionid`),
	CONSTRAINT `fk_profile2globalpermissions` FOREIGN KEY (`profileid`) REFERENCES `profile` (`profileid`) ON DELETE CASCADE
	) TYPE=InnoDB";
$status = $conn->query($create_sql1);
echo '<br>'.$status.' ==> '.$create_sql1;

$create_sql2 = "CREATE TABLE `actionmapping` (
	`actionid` int(19) NOT NULL default '0',
	`actionname` varchar(200) NOT NULL default '',
	`securitycheck` int(19) default NULL,
PRIMARY KEY (`actionid`,`actionname`)
	) TYPE=InnoDB";
$status = $conn->query($create_sql2);
echo '<br>'.$status.' ==> '.$create_sql2;


//For all Profiles, insert the following entries into profile2global permissions table:
$sql = 'select * from profile';
$res = $conn->query($sql);
$noofprofiles = $conn->num_rows($res);

for($i=0;$i<$noofprofiles;$i++)
{
	$profile_id = $conn->query_result($res,$i,'profileid');

	$sql1 = "insert into profile2globalpermissions values ($profile_id,1,0)";
	$sql2 = "insert into profile2globalpermissions values ($profile_id,2,0)";

	$status1 = $conn->query($sql1);
	$status2 = $conn->query($sql2);

	echo '<br>'.$status1.' ==> '.$sql1;
	echo '<br>'.$status2.' ==> '.$sql2;
}


//Removing entries for Dashboard and Home module from profile2standardpermissions table
$del_query1 = "delete from profile2standardpermissions where tabid in(1,3)";
$status = $conn->query($del_query1);
echo '<br>'.$status.' ==> '.$del_query1;

//For all Profile do the following insert into profile2utility table:
$sql = 'select * from profile';
$res = $conn->query($sql);
$noofprofiles = $conn->num_rows($res);

for($i=0;$i<$noofprofiles;$i++)
{
	$profile_id = $conn->query_result($res,$i,'profileid');

	$sql1 = "insert into profile2utility values ($profile_id,4,7,0)";
	$sql2 = "insert into profile2utility values ($profile_id,7,9,0)";

	$status1 = $conn->query($sql1);
	$status2 = $conn->query($sql2);

	echo '<br>'.$status1.' ==> '.$sql1;
	echo '<br>'.$status2.' ==> '.$sql2;
}


//Insert Values into action mapping table:
$actionmapping_array = Array(
		"insert into actionmapping values(0,'Save',0)",
		"insert into actionmapping values(1,'EditView',0)",
		"insert into actionmapping values(2,'Delete',0)",
		"insert into actionmapping values(3,'index',0)",
		"insert into actionmapping values(4,'DetailView',0)",
		"insert into actionmapping values(5,'Import',0)",
		"insert into actionmapping values(6,'Export',0)",
		"insert into actionmapping values(7,'AddBusinessCard',0)",
		"insert into actionmapping values(8,'Merge',0)",
		"insert into actionmapping values(1,'VendorEditView',0)",
		"insert into actionmapping values(4,'VendorDetailView',0)",
		"insert into actionmapping values(0,'SaveVendor',0)",
		"insert into actionmapping values(2,'DeleteVendor',0)",
		"insert into actionmapping values(1,'PriceBookEditView',0)",
		"insert into actionmapping values(4,'PriceBookDetailView',0)",
		"insert into actionmapping values(0,'SavePriceBook',0)",
		"insert into actionmapping values(2,'DeletePriceBook',0)",
		"insert into actionmapping values(1,'SalesOrderEditView',0)",
		"insert into actionmapping values(4,'SalesOrderDetailView',0)",
		"insert into actionmapping values(0,'SaveSalesOrder',0)",
		"insert into actionmapping values(2,'DeleteSalesOrder',0)",
		"insert into actionmapping values(9,'ConvertLead',0)",
		);
foreach($actionmapping_array as $query)
{
	$status = $conn->query($query);
	echo '<br>'.$status.' ==> '.$query;
}


//Added two columns in field table to construct the quickcreate form dynamically
$alter_array2 = Array(
		"ALTER TABLE field ADD column quickcreate int(10) after typeofdata",
		"ALTER TABLE field ADD column quickcreatesequence int(19) after quickcreate",
		);
foreach($alter_array2 as $query)
{
	$status = $conn->query($query);
	echo '<br>'.$status.' ==> '.$query;
}

$update_array1 = Array(
		"UPDATE field SET quickcreate = 1,quickcreatesequence = 0",

		"UPDATE field SET quickcreate = 0,quickcreatesequence = 1 WHERE tabid = 2 and fieldlabel = 'Potential Name'",
		"UPDATE field SET quickcreate = 0,quickcreatesequence = 2 WHERE tabid = 2 and fieldlabel = 'Account Name'",
		"UPDATE field SET quickcreate = 0,quickcreatesequence = 3 WHERE tabid = 2 and fieldlabel = 'Expected Close Date'",
		"UPDATE field SET quickcreate = 0,quickcreatesequence = 4 WHERE tabid = 2 and fieldlabel = 'Sales Stage'",
		"UPDATE field SET quickcreate = 0,quickcreatesequence = 5 WHERE tabid = 2 and fieldlabel = 'Amount'",

		"UPDATE field SET quickcreate = 0,quickcreatesequence = 1 WHERE tabid = 4 and fieldlabel = 'First Name'",
		"UPDATE field SET quickcreate = 0,quickcreatesequence = 2 WHERE tabid = 4 and fieldlabel = 'Last Name'",
		"UPDATE field SET quickcreate = 0,quickcreatesequence = 3 WHERE tabid = 4 and fieldlabel = 'Account Name'",
		"UPDATE field SET quickcreate = 0,quickcreatesequence = 4 WHERE tabid = 4 and fieldlabel = 'Office Phone'",
		"UPDATE field SET quickcreate = 0,quickcreatesequence = 5 WHERE tabid = 4 and fieldlabel = 'Email'",

		"UPDATE field SET quickcreate = 0,quickcreatesequence = 1 WHERE tabid = 6 and fieldlabel = 'Account Name'",
		"UPDATE field SET quickcreate = 0,quickcreatesequence = 2 WHERE tabid = 6 and fieldlabel = 'Phone'",
		"UPDATE field SET quickcreate = 0,quickcreatesequence = 3 WHERE tabid = 6 and fieldlabel = 'Website'",

		"UPDATE field SET quickcreate = 0,quickcreatesequence = 1 WHERE tabid = 7 and fieldlabel = 'First Name'",
		"UPDATE field SET quickcreate = 0,quickcreatesequence = 2 WHERE tabid = 7 and fieldlabel = 'Last Name'",
		"UPDATE field SET quickcreate = 0,quickcreatesequence = 3 WHERE tabid = 7 and fieldlabel = 'Company'",
		"UPDATE field SET quickcreate = 0,quickcreatesequence = 4 WHERE tabid = 7 and fieldlabel = 'Phone'",
		"UPDATE field SET quickcreate = 0,quickcreatesequence = 5 WHERE tabid = 7 and fieldlabel = 'Email'",

		"UPDATE field SET quickcreate = 0,quickcreatesequence = 1 WHERE tabid = 8 and fieldlabel = 'Subject'",

		"UPDATE field SET quickcreate = 0,quickcreatesequence = 1 WHERE tabid = 9 and fieldlabel = 'Subject'",
		"UPDATE field SET quickcreate = 0,quickcreatesequence = 2 WHERE tabid = 9 and fieldlabel = 'Start Date & Time'",

		"UPDATE field SET quickcreate = 0,quickcreatesequence = 1 WHERE tabid = 10 and fieldlabel = 'Subject'",
		"UPDATE field SET quickcreate = 0,quickcreatesequence = 2 WHERE tabid = 10 and fieldlabel = 'Date & Time Sent'",

		"UPDATE field SET quickcreate = 0,quickcreatesequence = 1 WHERE tabid = 13 and fieldlabel = 'Title'",
		"UPDATE field SET quickcreate = 0,quickcreatesequence = 2 WHERE tabid = 13 and fieldlabel = 'Description'",
		"UPDATE field SET quickcreate = 0,quickcreatesequence = 3 WHERE tabid = 13 and fieldlabel = 'Priority'",

		"UPDATE field SET quickcreate = 0,quickcreatesequence = 1 WHERE tabid = 14 and fieldlabel = 'Product Name'",
		"UPDATE field SET quickcreate = 0,quickcreatesequence = 2 WHERE tabid = 14 and fieldlabel = 'Product Code'",
		"UPDATE field SET quickcreate = 0,quickcreatesequence = 3 WHERE tabid = 14 and fieldlabel = 'Product Category'",

		"UPDATE field SET quickcreate = 0,quickcreatesequence = 1 WHERE tabid = 16 and fieldlabel = 'Subject'",
		"UPDATE field SET quickcreate = 0,quickcreatesequence = 2 WHERE tabid = 16 and fieldlabel = 'Start Date & Time'",
		"UPDATE field SET quickcreate = 0,quickcreatesequence = 3 WHERE tabid = 16 and fieldlabel = 'Activity Type'",
		"UPDATE field SET quickcreate = 0,quickcreatesequence = 4 WHERE tabid = 16 and fieldlabel = 'Duration'",
		);
foreach($update_array1 as $query)
{
	$status = $conn->query($query);
	echo '<br>'.$status.' ==> '.$query;
}

//Added for the "Color By User in Calendar " which has been contributed by Cesar
$alter_query1 = "ALTER TABLE `users` ADD `cal_color` VARCHAR(25) DEFAULT '#E6FAD8' AFTER `user_hash`";
$status = $conn->query($alter_query1);
echo '<br>'.$status.' ==> '.$alter_query1;

//code contributed by Fredy for color priority
$newfieldid = $conn->getUniqueID("field");
$insert_query1 = "insert into field values (16,".$newfieldid.",'priority','activity',1,15,'taskpriority','Priority',1,0,0,100,17,1,1,'V~O',1,'')";
$status = $conn->query($insert_query1);
echo '<br>'.$status.' ==> '.$insert_query1;

//Added on 23-12-2005 which is missed from Fredy's contribution for Color priority
populateFieldForSecurity('16',$newfieldid);
$activity_alter_query = "alter table activity add column priority varchar(150) default NULL";
$status = $conn->query($activity_alter_query);
echo '<br>'.$status.' ==> '.$activity_alter_query;

//Code contributed by Raju for better emailing 
/*
$insert_array1 = Array(
		"insert into field values (10,".$conn->getUniqueID("field").",'crmid','seactivityrel',1,'357','parent_id','Related To',1,0,0,100,1,2,1,'I~O',1,'')",
		"insert into field values (10,".$conn->getUniqueID("field").",'subject','activity',1,'2','subject','Subject',1,0,0,100,1,3,1,'V~M',0,1)",
		"insert into field values (10,".$conn->getUniqueID("field").",'filename','emails',1,'61','filename','Attachment',1,0,0,100,1,4,1,'V~O',1,'')",
		"insert into field values (10,".$conn->getUniqueID("field").",'description','emails',1,'19','description','Description',1,0,0,100,1,5,1,'V~O',1,'')",
		);
*/
//commented the above array as that queries are wrong queries -- changed on 23-12-2005
$insert_array1 = array(
			"update field set uitype='357' where tabid=10 and fieldname='parent_id' and tablename='seactivityrel'",
			"update field set sequence=1 where tabid=10 and fieldname in ('parent_id','subject','filename','description')",
			"update field set block=2 where tabid=10 and fieldname='parent_id'",
			"update field set block=3 where tabid=10 and fieldname='subject'",
			"update field set block=4 where tabid=10 and fieldname='filename'",
			"update field set block=5 where tabid=10 and fieldname='description'",
		      );
foreach($insert_array1 as $query)
{
	$status = $conn->query($query);
	echo '<br>'.$status.' ==> '.$query;
}

//code contributed by mike to rearrange the home page
$alter_query2 = "alter table users add column homeorder varchar(255) default 'ALVT,PLVT,QLTQ,CVLVT,HLT,OLV,GRT,OLTSO,ILTI' after date_format";
$status = $conn->query($alter_query2);
echo '<br>'.$status.' ==> '.$alter_query2;


//Added one column in invoice table to include 'Contact Name' field in Invoice module
$alter_query3 = "ALTER TABLE invoice ADD column contactid int(19) after customerno";
$status = $conn->query($alter_query3);
echo '<br>'.$status.' ==> '.$alter_query3;

$newfieldid = $conn->getUniqueID("field");
$insert_query2 = "insert into field values (23,".$newfieldid.",'contactid','invoice',1,'57','contact_id','Contact Name',1,0,0,100,4,1,1,'I~O',1,'')";
$status = $conn->query($insert_query2);
echo '<br>'.$status.' ==> '.$insert_query2;
//Added on 23-12-2005 because we must populate field entries in profile2field and def_org_field if we add a field in field table
populateFieldForSecurity('23',$newfieldid);

//changes made to fix the bug in Address Information block of Accounts and Contacs module
$update_array2 = Array(
		"UPDATE field SET fieldlabel='Billing City' WHERE tabid=6 and tablename='accountbillads' and fieldname='bill_city'",
		"UPDATE field SET fieldlabel='Billing State' WHERE tabid=6 and tablename='accountbillads' and fieldname='bill_state'",
		"UPDATE field SET fieldlabel='Billing Code' WHERE tabid=6 and tablename='accountbillads' and fieldname='bill_code'",
		"UPDATE field SET fieldlabel='Billing Country' WHERE tabid=6 and tablename='accountbillads' and fieldname='bill_country'",

		"UPDATE field SET fieldlabel='Shipping City' WHERE tabid=6 and tablename='accountshipads' and fieldname='ship_city'",
		"UPDATE field SET fieldlabel='Shipping Country' WHERE tabid=6 and tablename='accountshipads' and fieldname='ship_country'",
		"UPDATE field SET fieldlabel='Shipping State' WHERE tabid=6 and tablename='accountshipads' and fieldname='ship_state'",
		"UPDATE field SET fieldlabel='Shipping Code' WHERE tabid=6 and tablename='accountshipads' and fieldname='ship_code'",

		"UPDATE field SET fieldlabel='Mailing City' WHERE tabid=4 and tablename='contactaddress' and fieldname='mailingcity'",
		"UPDATE field SET fieldlabel='Mailing State' WHERE tabid=4 and tablename='contactaddress' and fieldname='mailingstate'",
		"UPDATE field SET fieldlabel='Mailing Zip' WHERE tabid=4 and tablename='contactaddress' and fieldname='mailingzip'",
		"UPDATE field SET fieldlabel='Mailing Country' WHERE tabid=4 and tablename='contactaddress' and fieldname='mailingcountry'",

		"UPDATE field SET fieldlabel='Other City' WHERE tabid=4 and tablename='contactaddress' and fieldname='othercity'",
		"UPDATE field SET fieldlabel='Other State' WHERE tabid=4 and tablename='contactaddress' and fieldname='otherstate'",
		"UPDATE field SET fieldlabel='Other Zip' WHERE tabid=4 and tablename='contactaddress' and fieldname='otherzip'",
		"UPDATE field SET fieldlabel='Other Country' WHERE tabid=4 and tablename='contactaddress' and fieldname='othercountry'",
		);
foreach($update_array2 as $query)
{
	$status = $conn->query($query);
	echo '<br>'.$status.' ==> '.$query;
}


//Added field emailoptout in account table
$newfieldid = $conn->getUniqueID("field");
$insert_query3 = "insert into field values (6,".$newfieldid.",'emailoptout','account',1,'56','emailoptout','Email Opt Out',1,0,0,100,17,1,1,'C~O',1,'')";
$status = $conn->query($insert_query3);
echo '<br>'.$status.' ==> '.$insert_query3;

//Added on 23-12-2005 because we must populate field entries in profile2field and def_org_field if we add a field in field table
populateFieldForSecurity('6',$newfieldid);

//Added on 22-12-2005
$alter_query4 = "alter table account add column emailoptout varchar(3) default 0";
$status = $conn->query($alter_query4);
echo '<br>'.$status.' ==> '.$alter_query4;

$update_array3 = Array(
		"update field set sequence=18 where tabid=6 and fieldname ='assigned_user_id'",
		"update field set sequence=19 where tabid=6 and fieldname ='createdtime'",
		"update field set sequence=19 where tabid=6 and fieldname ='modifiedtime'",
		);
foreach($update_array3 as $query)
{
	$status = $conn->query($query);
	echo '<br>'.$status.' ==> '.$query;
}


//create table moduleowners to assign the module and corresponding owners
$create_query2 = "CREATE TABLE `moduleowners` 
(
 `tabid` int(19) NOT NULL default '0',
 `user_id` varchar(11) NOT NULL default '',
 PRIMARY KEY  (`tabid`),
 CONSTRAINT `fk_ModuleOwners` FOREIGN KEY (`tabid`) REFERENCES `tab` (`tabid`) ON DELETE CASCADE
) TYPE=InnoDB";
$status = $conn->query($create_query2);
echo '<br>'.$status.' ==> '.$create_query2;

//Populated the default entries for moduleowners which is created newly
$module_array = Array(
		'Potentials',
		'Contacts',
		'Accounts',
		'Leads',
		'Notes',
		'Activities',
		'Emails',
		'HelpDesk',
		'Products',
		'Faq',
		'Vendor',
		'PriceBook',
		'Quotes',
		'Orders',
		'SalesOrder',
		'Invoice',
		'Reports'
		);
foreach($module_array as $mod)
{
	$query = "insert into moduleowners values(".$this->localGetTabID($mod).",1)";
	$status = $conn->query($query);
	echo '<br>'.$status.' ==> '.$query;
}


//Changes made to include status field in Activity Quickcreate Form
$update_array4 = Array(
		"UPDATE field SET quickcreate=0,quickcreatesequence=3 WHERE tabid=16 and fieldname='eventstatus'",
		"UPDATE field SET quickcreate=0,quickcreatesequence=4 WHERE tabid=16 and fieldname='activitytype'",
		"UPDATE field SET quickcreate=0,quickcreatesequence=5 WHERE tabid=16 and fieldname='duration_hours'",

		"UPDATE field SET quickcreate=0,quickcreatesequence=3 WHERE tabid=9 and fieldname='taskstatus'",
		);
foreach($update_array4 as $query)
{
	$status = $conn->query($query);
	echo '<br>'.$status.' ==> '.$query;
}



//Table 'inventory_tandc' added newly to include Inventory Terms &Conditions
$create_query1 = "CREATE TABLE  inventory_tandc(id INT(19),type VARCHAR(30) NOT NULL,tandc LONGTEXT default NULL,PRIMARY KEY(id))";
$status = $conn->query($create_query1);
echo '<br>'.$status.' ==> '.$create_query1;

$insert_query4 = "insert into inventory_tandc values('".$conn->getUniqueID('inventory_tandc')."','Inventory','  ')";
$status = $conn->query($insert_query4);
echo '<br>'.$status.' ==> '.$insert_query4;

/****************** 5.0(Alpha) dev version 1 Database changes -- Ends*********************/










/****************** 5.0(Alpha) dev version 2 Database changes -- Starts*********************/

$query1 = "ALTER TABLE leadaddress change lane lane varchar(250)";
$status1 = $conn->query($query1);
echo '<br>'.$status1.' ==> '.$query1;

$rename_table_array1 = Array(
		"update field set tablename='customerdetails' where tabid=4 and fieldname in ('portal','support_start_date','support_end_date')",
		"alter table PortalInfo drop foreign key fk_PortalInfo",
		"rename table PortalInfo to portalinfo",
		"alter table portalinfo add CONSTRAINT `fk_portalinfo` FOREIGN KEY (`id`) REFERENCES `contactdetails` (`contactid`) ON DELETE CASCADE",
		"alter table CustomerDetails drop foreign key fk_CustomerDetails",
		"rename table CustomerDetails to customerdetails",
		"alter table customerdetails add CONSTRAINT `fk_customerdetails` FOREIGN KEY (`customerid`) REFERENCES `contactdetails` (`contactid`) ON DELETE CASCADE"
		);
foreach($rename_table_array1 as $query)
{
	$status = $conn->query($query);
	echo '<br>'.$status.' ==> '.$query;
}


$query2 = "create table ownernotify(crmid int(19),smownerid int(19),flag int(3))";
$status2 = $conn->query($query2);
echo '<br>'.$status2.' ==> '.$query2;


//Form the role_map_array as roleid=>name mapping array
$sql = "select * from role";
$res = $conn->query($sql);
$role_map_array = Array();
for($i=0;$i<$conn->num_rows($res);$i++)
{
	$roleid = $conn->query_result($res,$i,'roleid');
	$name = $conn->query_result($res,$i,'name');
	$role_map_array[$roleid] = $name;
}
echo '<pre> List of role :';print_r($role_map_array);echo '</pre>';

//Before delete the role take a backup array for the table user2role
$sql = "select * from user2role";
$res = $conn->query($sql);
$user2role_array = array();
for($i=0;$i<$conn->num_rows($res);$i++)
{
	$userid = $conn->query_result($res,$i,'userid');
	$roleid = $conn->query_result($res,$i,'roleid');
	$user2role_array[$userid] = $roleid;
}
echo '<pre> List of user2role : (userid => roleid)';print_r($user2role_array);echo '</pre>';

//Delete the role entries
$sql = "truncate role";
$result = $conn->query($sql);
echo '<br>'.$result.' ==> '.$sql;


$query3 = "alter table user2role drop FOREIGN KEY fk_user2role2";
$status3 = $conn->query($query3);
echo '<br>'.$status3.' ==> '.$query3;

//4,5 th are the Extra added queries
$alter_query_array1 = Array(
		"alter table user2role change roleid roleid varchar(255)",
		"alter table role2profile change roleid roleid varchar(255)",
		"alter table role CHANGE roleid roleid varchar(255)",
		"alter table role2profile drop PRIMARY KEY",
		"alter table role2profile ADD PRIMARY KEY (roleid,profileid)"
		);
foreach($alter_query_array1 as $query)
{
	$status = $conn->query($query);
	echo '<br>'.$status.' ==> '.$query;
}


$query4 = "ALTER TABLE user2role ADD CONSTRAINT fk_user2role2 FOREIGN KEY (roleid) REFERENCES role(roleid) ON DELETE CASCADE";
$status4 = $conn->query($query4);
echo '<br>'.$status4.' ==> '.$query4;

$alter_query_array2 = Array(
		"alter table role CHANGE name rolename varchar(200)",
		"alter table role DROP description",
		"alter table role add parentrole varchar(255)",
		"alter table role add depth int(19)"
		);
foreach($alter_query_array2 as $query)
{
	$status = $conn->query($query);
	echo '<br>'.$status.' ==> '.$query;
}



$query5 = "insert into role values('H1','Organisation','H1',0)";
$status5 = $conn->query($query5);
echo '<br>'.$status5.' ==> '.$query5;

//include("include/utils/UserInfoUtil.php");
//Create role based on role_map_array values and form the new_role_map_array with old roleid and new roleid
foreach($role_map_array as $roleid => $rolename)
{
	$parentRole = 'H1';
	if($rolename == 'standard_user')
	{
		$rs = $conn->query("select * from role where rolename='administrator'");
		$parentRole = $conn->query_result($rs,0,'roleid');
	}
	$empty_array = array(""=>"");
	$new_role_id = createRole($rolename,$parentRole,$empty_array);
	$new_role_map_array[$roleid] = $new_role_id;
}
//First we will insert the old values from user2role_array to user2role table and then update the new role id
foreach($user2role_array as $userid => $roleid)
{
	$sql = "insert into user2role (userid, roleid) values(".$userid.",'".$new_role_map_array[$roleid]."')";
	$status = $conn->query($sql);
	echo '<br>'.$status.' ==> '.$sql;
}
//Commented the following loop as we have backup the user2role and insert the entries with the new rold id using new_role_map_array above
//Update the user2role table with new roleid
/*
   foreach($new_role_map_array as $old_roleid => $new_roleid)
   {
   $update_user2role = "update user2role set roleid='".$new_roleid."' where roleid=".$old_roleid;
   $status = $conn->query($update_user2role);
   echo '<br>'.$status.' ==> '.$update_user2role;
   }
 */
//Update the role2profile table with new roleid
foreach($new_role_map_array as $old_roleid => $new_roleid)
{
	$update_role2profile = "update role2profile set roleid='".$new_roleid."' where roleid=".$old_roleid;
	$status = $conn->query($update_role2profile);
	echo '<br>'.$status.' ==> '.$update_role2profile;
}



//Group Migration:
//Step 1 :  form and group_map_array as groupname => description from groups table
//Step 2 :  form an users2group_map_array array as userid => groupname from users2group table
//Step 3 :  delete all entries from groups table and enter new values from group_map_array
//Step 4 :  drop the table users2group and create new table
//Step 5 :  put entries to users2group table based on users2group_map_array. Here get the groupid from groups table based on groupname

//Step 1 : Form the group_map_array as groupname => description
$sql = "select * from groups";
$res = $conn->query($sql);
$group_map_array = Array();
for($i=0;$i<$conn->num_rows($res);$i++)
{
	$name = $conn->query_result($res,$i,'name');
	$desc = $conn->query_result($res,$i,'description');
	$group_map_array[$name] = $desc;
}
echo '<pre>List of Groups : ';print_r($group_map_array);echo '</pre>';


//Step 2 : form an users2group_map_array array as userid => groupname from users2group table
$sql = "select * from users2group";
$res = $conn->query($sql);
$users2group_map_array = Array();
for($i=0;$i<$conn->num_rows($res);$i++)
{
	$groupname = $conn->query_result($res,$i,'groupname');
	$userid = $conn->query_result($res,$i,'userid');
	$users2group_map_array[$userid] = $groupname;
}
echo '<pre>List of users2group : ';print_r($users2group_map_array);echo '</pre>';

//Step 3 : delete all entries from groups table
$sql = "truncate groups";
$result = $conn->query($sql);
echo '<br>'.$result.' ==> '.$sql;

$alter_query_array3 = Array(
		"alter table users2group drop FOREIGN KEY fk_users2group",
		"alter table leadgrouprelation drop FOREIGN KEY fk_leadgrouprelation2",
		"alter table activitygrouprelation drop FOREIGN KEY fk_activitygrouprelation2",
		"alter table ticketgrouprelation drop FOREIGN KEY fk_ticketgrouprelation2",
		"alter table groups drop PRIMARY KEY"
		);
foreach($alter_query_array3 as $query)
{
	$status = $conn->query($query);
	echo '<br>'.$status.' ==> '.$query;
}

//2 nd query is the Extra added query
//Adding columns in group table:
$alter_query_array4 = Array(
		"alter table groups add column groupid int(19) FIRST",
		"alter table groups change name  groupname varchar(100)",
		"alter table groups ADD PRIMARY KEY (groupid)",
		"alter table groups add index (groupname)"
		);
foreach($alter_query_array4 as $query)
{
	$status = $conn->query($query);
	echo '<br>'.$status.' ==> '.$query;
}


//Moved the create table queries for group2grouprel, group2role, group2rs from the end of this block
$query8 = "CREATE TABLE `group2grouprel` 
(
 `groupid` int(19) NOT NULL default '0',
 `containsgroupid` int(19) NOT NULL default '0',
 PRIMARY KEY (`groupid`,`containsgroupid`),
 CONSTRAINT `fk_group2grouprel1` FOREIGN KEY (`groupid`) REFERENCES `groups` (`groupid`) ON DELETE CASCADE
) TYPE=InnoDB";
$status8 = $conn->query($query8);
echo '<br>'.$status8.' ==> '.$query8;

$query9 = "CREATE TABLE `group2role` 
(
 `groupid` int(19) NOT NULL default '0',
 `roleid` varchar(255) NOT NULL default '',
 PRIMARY KEY (`groupid`,`roleid`),
 CONSTRAINT `fk_group2role1` FOREIGN KEY (`groupid`) REFERENCES `groups` (`groupid`) ON DELETE CASCADE
) TYPE=InnoDB";
$status9 = $conn->query($query9);
echo '<br>'.$status9.' ==> '.$query9;

$query10 = "CREATE TABLE `group2rs` 
(
 `groupid` int(19) NOT NULL default '0',
 `roleandsubid` varchar(255) NOT NULL default '',
 PRIMARY KEY (`groupid`,`roleandsubid`),
 CONSTRAINT `fk_group2rs1` FOREIGN KEY (`groupid`) REFERENCES `groups` (`groupid`) ON DELETE CASCADE
) TYPE=InnoDB";
$status10 = $conn->query($query10);
echo '<br>'.$status10.' ==> '.$query10;

//Insert all the retrieved old values to the new groups table ie., create new groups
foreach($group_map_array as $groupname => $description)
{
	$empty_array = array(
			"groups" => array(""=>""),
			"roles" => array(""=>""),
			"rs" => array(""=>""),
			"users" => array(""=>"")
			);
	$groupid = createGroup($groupname,$empty_array,$description);
	$group_name_id_mapping[$groupname] = $groupid;
}


//Copy all mappings in a user2grop table in a array;

//Step 4 : Drop and again create users2group
$query6 = "drop table users2group";
$status6 = $conn->query($query6);
echo '<br>'.$status6.' ==> '.$query6;


$query7 = "CREATE TABLE `users2group` 
(
 `groupid` int(19) NOT NULL default '0',
 `userid` int(19) NOT NULL default '0',
 PRIMARY KEY (`groupid`,`userid`),
 CONSTRAINT `fk_users2group1` FOREIGN KEY (`groupid`) REFERENCES `groups` (`groupid`) ON DELETE CASCADE
) TYPE=InnoDB";
$status7 = $conn->query($query7);
echo '<br>'.$status7.' ==> '.$query7;

//Step 5 : put entries to users2group table based on users2group_map_array. Here get the groupid from groups table based on groupname
foreach($users2group_map_array as $userid => $groupname)
{
	//$groupid = $conn->query_result($conn->query("select * from groups where groupname='".$groupname."'"),0,'groupid');
	$sql = "insert into users2group (groupid,userid) values(".$group_name_id_mapping[$groupname].",".$userid.")";
	$status = $conn->query($sql);
	echo '<br>'.$status.' ==> '.$sql;
}


$alter_query_array5 = Array(
		"alter table leadgrouprelation ADD CONSTRAINT fk_leadgrouprelation2 FOREIGN KEY (groupname) REFERENCES groups(groupname) ON DELETE CASCADE",
		"ALTER TABLE activitygrouprelation ADD CONSTRAINT fk_activitygrouprelation2 FOREIGN KEY (groupname) REFERENCES groups(groupname) ON DELETE CASCADE",
		"ALTER TABLE ticketgrouprelation ADD CONSTRAINT fk_ticketgrouprelation2 FOREIGN KEY (groupname) REFERENCES groups(groupname) ON DELETE CASCADE"
		);
foreach($alter_query_array5 as $query)
{
	$status = $conn->query($query);
	echo '<br>'.$status.' ==> '.$query;
}
//Moved the create table queries for group2grouprel, group2role, group2rs to before creatinf the Group ie., before call the createGroup


/***Added to include decimal places for amount field in potential table  --by Mangai 15-Nov-2005***/

$query11 = "ALTER TABLE potential change amount amount decimal(10,2)";
$status11 = $conn->query($query11);
echo '<br>'.$status11.' ==> '.$query11;

/****************** 5.0(Alpha) dev version 2 Database changes -- Ends*********************/












/****************** 5.0(Alpha) dev version 3 Database changes -- Starts*********************/

//Drop the column company_name from vendor table ---- modified by Mickie on 18-11-2005
$altersql1 = "alter table vendor drop column company_name";
$status1 = $conn->query($altersql1);
echo '<br>'.$status1.' ==> '.$altersql1;
//TODO (check): Remove this company_name entry from the field table if it already exists

//Migration for Default Organisation Share -- Added by Don on 20-11-2005

$query1 = "CREATE TABLE `org_share_action_mapping` (
`share_action_id` int(19) NOT NULL default '0',
	`share_action_name` varchar(200) NOT NULL default '',
PRIMARY KEY  (`share_action_id`,`share_action_name`)
	) TYPE=InnoDB ";
$status1 = $conn->query($query1);
echo '<br>'.$status1.' ==> '.$query1;

$query2 = "CREATE TABLE `org_share_action2tab` (
	`share_action_id` int(19) NOT NULL default '0',
	`tabid` int(19) NOT NULL default '0',
	PRIMARY KEY  (`share_action_id`,`tabid`),
	CONSTRAINT `fk_org_share_action2tab` FOREIGN KEY (`share_action_id`) REFERENCES `org_share_action_mapping` (`share_action_id`) ON DELETE CASCADE
	) TYPE=InnoDB";
$status2 = $conn->query($query2);
echo '<br>'.$status2.' ==> '.$query2;


$query3 = "alter table def_org_share add column editstatus int(19)";
$status3 = $conn->query($query3);
echo '<br>'.$status3.' ==> '.$query3;

$query4 = "delete from def_org_share where tabid in(8,14,15,18,19)";
$status4 = $conn->query($query4);
echo '<br>'.$status7.' ==> '.$query4;



//Inserting values into org share action mapping
$insert_query_array1 = Array(
			"insert into org_share_action_mapping values(0,'Public: Read Only')",
			"insert into org_share_action_mapping values(1,'Public:Read,Create/Edit')",
			"insert into org_share_action_mapping values(2,'Public: Read, Create/Edit, Delete')",
			"insert into org_share_action_mapping values(3,'Private')",
			"insert into org_share_action_mapping values(4,'Hide Details')",
			"insert into org_share_action_mapping values(5,'Hide Details and Add Events')",
			"insert into org_share_action_mapping values(6,'Show Details')",
			"insert into org_share_action_mapping values(7,'Show Details and Add Events')"
			);
foreach($insert_query_array1 as $query)
{
	$status = $conn->query($query);
	echo '<br>'.$status.' ==> '.$query;
}


//Inserting for all tabs
$def_org_tabid=Array(2,4,6,7,9,10,13,16,20,21,22,23);
foreach($def_org_tabid as $def_tabid)
{
	$insert_query_array2 = Array(
			"insert into org_share_action2tab values(0,".$def_tabid.")",
			"insert into org_share_action2tab values(1,".$def_tabid.")",
			"insert into org_share_action2tab values(2,".$def_tabid.")",
			"insert into org_share_action2tab values(3,".$def_tabid.")"
			);
	foreach($insert_query_array2 as $query)
	{
		$status = $conn->query($query);
		echo '<br>'.$status.' ==> '.$query;
	}
}

$insert_query_array3 = Array(
		"insert into org_share_action2tab values(4,17)",
		"insert into org_share_action2tab values(5,17)",
		"insert into org_share_action2tab values(6,17)",
		"insert into org_share_action2tab values(7,17)"
		);
foreach($insert_query_array3 as $query)
{
	$status = $conn->query($query);
	echo '<br>'.$status.' ==> '.$query;
}

$query_array1 = Array(
		"insert into def_org_share values(9,17,7,0)",
		"update def_org_share set editstatus=0",
		"update def_org_share set editstatus=2 where tabid=4",
		"update def_org_share set editstatus=1 where tabid=9",
		"update def_org_share set editstatus=2 where tabid=16"
		);
foreach($query_array1 as $query)
{
	$status = $conn->query($query);
	echo '<br>'.$status.' ==> '.$query;
}

/****************** 5.0(Alpha) dev version 3 Database changes -- Ends*********************/






$conn->println("Database Modifications for 4.2 Patch2 ==> 5.0(Alpha) Dev 3 ends here.");
echo "<br><br><b>Database Modifications for 4.2 Patch2 ==> 5.0(Alpha) Ends here.....</b><br>";


//Added on 23-12-2005 which is used to populate the profile2field and def_org_field table entries for the field per tab
//if we enter a field in field table then we must populate that field in these table for security access
function populateFieldForSecurity($tabid,$fieldid)
{
	global $conn;

	$profileresult = $conn->query("select * from profile");
	$countprofiles = $conn->num_rows($profileresult);
	for ($i=0;$i<$countprofiles;$i++)
	{
        	$profileid = $conn->query_result($profileresult,$i,'profileid');
	        $sqlProf2FieldInsert[$i] = 'insert into profile2field values ('.$profileid.','.$tabid.','.$fieldid.',0,1)';
        	$status = $conn->query($sqlProf2FieldInsert[$i]);
	        echo '<br>'.$status.' ==> '.$sqlProf2FieldInsert[$i];
	}
	$def_query = "insert into def_org_field values (".$tabid.",".$fieldid.",0,1)";
	$status = $conn->query($def_query);
	echo '<br>'.$status.' ==> '.$def_query;
}




?>
