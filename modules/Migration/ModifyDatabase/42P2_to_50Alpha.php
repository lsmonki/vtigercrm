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

//Added the announcement table creation to avoid the error
$ann_query = "CREATE TABLE `announcement` (
	  `creatorid` int(19) NOT NULL,
	    `announcement` text,
	      `title` varchar(255) default NULL,
	        `time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
		  PRIMARY KEY  (`creatorid`),
		    KEY `announcement_UK01` (`creatorid`)
	    ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
Execute($ann_query);

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
	)";
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



$conn->println("Database Modifications for 5.0(Alpha) Dev 3 ==> 5.0 Alpha starts here.");
echo "<br><br><b>Database Modifications for 5.0(Alpha) Dev3 ==> 5.0 Alpha starts here.....</b><br>";
$alter_query_array6 = Array(
				"ALTER TABLE users ADD column activity_view VARCHAR(25) DEFAULT 'Today' AFTER homeorder",
				"ALTER TABLE activity ADD column notime CHAR(3) DEFAULT '0' AFTER location"
			   );
foreach($alter_query_array6 as $query)
{
	Execute($query);
}

$insert_field_array1 = Array(
				"Insert into field values (9,".$conn->getUniqueID("field").",'notime','activity',1,56,'notime','No Time',1,0,0,100,20,1,3,'C~O',1,'')",
				"Insert into field values (16,".$conn->getUniqueID("field").",'notime','activity',1,56,'notime','No Time',1,0,0,100,18,1,1,'C~O',1,'')"
			    );
foreach($insert_field_array1 as $query)
{
	Execute($query);
}

$alter_query_array7 = Array(
				"alter table vendor add column pobox varchar(30) after state",
				"alter table leadaddress add column pobox varchar(30) after state",
				"alter table accountbillads add column pobox varchar(30) after state",
				"alter table accountshipads add column pobox varchar(30) after state",
				"alter table contactaddress add column mailingpobox varchar(30) after mailingstate",
				"alter table contactaddress add column otherpobox varchar(30) after otherstate",
				"alter table quotesbillads add column bill_pobox varchar(30) after bill_street",
				"alter table quotesshipads add column ship_pobox varchar(30) after ship_street",
				"alter table pobillads add column bill_pobox varchar(30) after bill_street",
				"alter table poshipads add column ship_pobox varchar(30) after ship_street",
				"alter table sobillads add column bill_pobox varchar(30) after bill_street",
				"alter table soshipads add column ship_pobox varchar(30) after ship_street",
				"alter table invoicebillads add column bill_pobox varchar(30) after bill_street",
				"alter table invoiceshipads add column ship_pobox varchar(30) after ship_street"
			   );
foreach($alter_query_array7 as $query)
{
	Execute($query);
}

$insert_field_array2 = Array(
				"insert into field values (23,".$conn->getUniqueID("field").",'bill_pobox','invoicebillads',1,'1','bill_pobox','Billing Po Box',1,0,0,100,3,2,1,'V~O',1,'')",
				"insert into field values (23,".$conn->getUniqueID("field").",'ship_pobox','invoiceshipads',1,'1','ship_pobox','Shipping Po Box',1,0,0,100,4,2,1,'V~O',1,'')",
				
				"insert into field values (6,".$conn->getUniqueID("field").",'pobox','accountbillads',1,'1','bill_pobox','Billing Po Box',1,0,0,100,3,2,1,'V~O',1,'')",
				"insert into field values (6,".$conn->getUniqueID("field").",'pobox','accountshipads',1,'1','ship_pobox','Shipping Po Box',1,0,0,100,4,2,1,'V~O',1,'')",
				
				"insert into field values (7,".$conn->getUniqueID("field").",'pobox','leadaddress',1,'1','pobox','Po Box',1,0,0,100,2,2,1,'V~O',1,'')",

				"insert into field values (4,".$conn->getUniqueID("field").",'mailingpobox','contactaddress',1,'1','mailingpobox','Mailing Po Box',1,0,0,100,3,2,1,'V~O',1,'')",
				"insert into field values (4,".$conn->getUniqueID("field").",'otherpobox','contactaddress',1,'1','otherpobox','Other Po Box',1,0,0,100,4,2,1,'V~O',1,'')",

				"insert into field values (18,".$conn->getUniqueID("field").",'pobox','vendor',1,'1','pobox','Po Box',1,0,0,100,2,2,1,'V~O',1,'')",

				"insert into field values (20,".$conn->getUniqueID("field").",'bill_pobox','quotesbillads',1,'1','bill_pobox','Billing Po Box',1,0,0,100,3,2,1,'V~O',1,'')",
				"insert into field values (20,".$conn->getUniqueID("field").",'ship_pobox','quotesshipads',1,'1','ship_pobox','Shipping Po Box',1,0,0,100,4,2,1,'V~O',1,'')",

				"insert into field values (21,".$conn->getUniqueID("field").",'bill_pobox','pobillads',1,'1','bill_pobox','Billing Po Box',1,0,0,100,3,2,1,'V~O',1,'')",
				"insert into field values (21,".$conn->getUniqueID("field").",'ship_pobox','poshipads',1,'1','ship_pobox','Shipping Po Box',1,0,0,100,4,2,1,'V~O',1,'')",

				"insert into field values (22,".$conn->getUniqueID("field").",'bill_pobox','sobillads',1,'1','bill_pobox','Billing Po Box',1,0,0,100,3,2,1,'V~O',1,'')",
				"insert into field values (22,".$conn->getUniqueID("field").",'ship_pobox','soshipads',1,'1','ship_pobox','Shipping Po Box',1,0,0,100,4,2,1,'V~O',1,'')"
			    );
foreach($insert_field_array2 as $query)
{
	Execute($query);
}


$fieldname =array('bill_city','bill_state','bill_code','bill_country','ship_city','ship_state','ship_code','ship_country');
$tablename = array('accountbillads','quotesbillads','pobillads','sobillads','invoicebillads','accountshipads','quotesshipads','poshipads','soshipads','invoiceshipads');
$sequence = array(5,7,9,11,6,8,10,12);
$k = 0;
$n = 0;
for($j = 0;$j < 8;$j++)
{
	if($j == 4)
	$n = $n+5;
	for($i = 0;$i < 5;$i++)
	{
		$query1 = "update field set sequence=".$sequence[$j]." where tablename='".$tablename[$n+$i]."' && fieldname='".$fieldname[$j]."'";
		Execute($query1);
	}
}

$fieldname = array('code','city','country','state');
$tablename = 'leadaddress';
$sequence = array(3,4,5,6);
for($i = 0;$i < 4;$i++)
{
	$query2 = "update field set sequence=".$sequence[$i]." where tablename='".$tablename."' && fieldname='".$fieldname[$i]."'";
	Execute($query2);
}

$fieldname = array('city','state','postalcode','country');
$tablename = 'vendor';
$sequence = array(3,4,5,6);

for($i = 0;$i < 4;$i++)
{
	$query3 = "update field set sequence=".$sequence[$i]." where tablename='".$tablename."' && fieldname='".$fieldname[$i]."'";
	Execute($query3);
}

$fieldname = array('mailingcity','othercity','mailingstate','otherstate','mailingzip','otherzip','mailingcountry','othercountry');
$tablename = 'contactaddress';
$sequence = array(5,6,7,8,9,10,11,12);

for($i = 0;$i < 8;$i++)
{
	$query = "update field set sequence=".$sequence[$i]." where tablename='".$tablename."' && fieldname='".$fieldname[$i]."'";
	Execute($query);
}

$query_array1 = Array(
			"update field set tablename='crmentity' where tabid=10 and fieldname='description'",
			"update field set tablename='attachments' where tabid=10 and fieldname='filename'",
			"drop table emails",

			"alter table activity drop column description",
			"update field set tablename='crmentity' where tabid in (9,16) and fieldname='description'",

			"update tab set name='PurchaseOrder',tablabel='PurchaseOrder' where tabid=21",
			"update tab set presence=0 where tabid=22 and name='SalesOrder'",

			"delete from actionmapping where actionname='SalesOrderDetailView'",
			"delete from actionmapping where actionname='SalesOrderEditView'",
			"delete from actionmapping where actionname='SaveSalesOrder'",
			"delete from actionmapping where actionname='DeleteSalesOrder'",

			"insert into field values (13,".$conn->getUniqueID("field").",'filename','attachments',1,'61','filename','Attachment',1,0,0,100,12,2,1,'V~O',0,1)",

			"alter table troubletickets add column filename varchar(50) default NULL after title"
		     );
foreach($query_array1 as $query)
{
	Execute($query);
}

$create_query3 = "create table parenttab(parenttabid int(19) not null, parenttab_label varchar(100) not null, sequence int(10) not null, visible int(2) not null default '0', Primary Key(parenttabid))";
Execute($create_query3);
$create_query4 = "create table parenttabrel(parenttabid int(3) not null, tabid int(3) not null,sequence int(3) not null)";
Execute($create_query4);

$insert_query_array4 = Array(
				"insert into parenttab values(1,'My Home Page',1,0),(2,'Marketing',2,0),(3,'Sales',3,0),(4,'Support',4,0),(5,'Analytics',5,0),(6,'Inventory',6,0), (7,'Tools',7,0),(8,'Settings',8,0)",
				"insert into parenttabrel values(1,9,2),(1,17,3),(1,10,4),(1,3,1),(3,7,1),(3,6,2),(3,4,3),(3,2,4),(3,20,5),(3,22,6),(3,23,7),(3,14,8),(3,19,9),(3,8,10),(4,13,1),(4,15,2),(4,6,3),(4,4,4),(4,14,5),(4,8,6),(5,1,1),(5,25,2),(6,14,1), (6,18,2), (6,19,3), (6,21,4), (6,22,5), (6,20,6), (6,23,7), (7,24,1), (7,27,2), (7,8,3), (2,26,1) "
			    );
foreach($insert_query_array4 as $query)
{
	Execute($query);
}


$create_query5 = "CREATE TABLE blocks ( blockid int(19) NOT NULL, tabid int(19) NOT NULL, blocklabel varchar(100) NOT NULL, sequence int(19) NOT NULL, show_title int(2) NOT NULL, visible int(2) NOT NULL DEFAULT 0, create_view int(2) NOT NULL DEFAULT 0, edit_view int(2) NOT NULL DEFAULT 0, detail_view int(2) NOT NULL DEFAULT 0, PRIMARY KEY (blockid))";
Execute($create_query5);

$update_query_array1 = Array(
				"update field set block=2 where tabid=2 and block=5",
				"update field set block=3 where tabid=2 and block=2",

				"update field set block=4 where tabid=4 and block=1",
				"update field set block=5 where tabid=4 and block=5",
				"update field set block=6 where tabid=4 and block=4",
				"update field set block=7 where tabid=4 and block=2",
				"update field set block=8 where tabid=4 and block=3",

				"update field set block=9 where tabid=6 and block=1",
				"update field set block=10 where tabid=6 and block=5",
				"update field set block=11 where tabid=6 and block=2",
				"update field set block=12 where tabid=6 and block=3",

				"update field set block=13 where tabid=7 and block=1",
				"update field set block=14 where tabid=7 and block=5",
				"update field set block=15 where tabid=7 and block=2",
				"update field set block=16 where tabid=7 and block=3",

				"update field set block=17 where tabid=8 and block=1",
				"update field set block=17 where tabid=8 and block=2",
				"update field set block=18 where tabid=8 and block=3",

				"update field set block=19 where tabid=9 and block=1",
				"update field set block=19 where tabid=9 and block=7",
				"update field set block=20 where tabid=9 and block=2",

				"update field set block=21 where tabid=10 and block=1",
				"update field set block=22 where tabid=10 and block=2",
				"update field set block=23 where tabid=10 and block=3",
				"update field set block=23 where tabid=10 and block=4",
				"update field set block=24 where tabid=10 and block=5",

				"update field set block=25 where tabid=13 and block=1",
				"update field set block=26 where tabid=13 and block=2",
				"update field set block=27 where tabid=13 and block=5",
				"update field set block=28 where tabid=13 and block=3",
				"update field set block=29 where tabid=13 and block=4",
				"update field set block=30 where tabid=13 and block=6",

				"update field set block=31 where tabid=14 and block=1",
				"update field set block=32 where tabid=14 and block=2",
				"update field set block=33 where tabid=14 and block=3",
				"update field set block=34 where tabid=14 and block=5",
				"update field set block=35 where tabid=14 and block=6",
				"update field set block=36 where tabid=14 and block=4",

				"update field set block=37 where tabid=15 and block=1",
				"update field set block=38 where tabid=15 and block=2",
				"update field set block=39 where tabid=15 and block=3",
				"update field set block=40 where tabid=15 and block=4",

				"update field set block=41 where tabid=16 and block=1",
				"update field set block=42 where tabid=16 and block=7",
				"update field set block=43 where tabid=16 and block=2",

				"update field set block=44 where tabid=18 and block=1",
				"update field set block=45 where tabid=18 and block=5",
				"update field set block=36 where tabid=18 and block=2",
				"update field set block=47 where tabid=18 and block=3",

				"update field set block=48 where tabid=19 and block=1",
				"update field set block=49 where tabid=19 and block=5",
				"update field set block=50 where tabid=19 and block=2",

				"update field set block=51 where tabid=20 and block=1",
				"update field set block=52 where tabid=20 and block=5",
				"update field set block=53 where tabid=20 and block=2",
				"update field set block=55 where tabid=20 and block=6",
				"update field set block=56 where tabid=20 and block=3",

				"update field set block=57 where tabid=21 and block=1",
				"update field set block=58 where tabid=21 and block=5",
				"update field set block=59 where tabid=21 and block=2",
				"update field set block=61 where tabid=21 and block=6",
				"update field set block=62 where tabid=21 and block=3",

				"update field set block=63 where tabid=22 and block=1",
				"update field set block=64 where tabid=22 and block=5",
				"update field set block=65 where tabid=22 and block=2",
				"update field set block=67 where tabid=22 and block=6",
				"update field set block=68 where tabid=22 and block=3",


				"update field set block=69 where tabid=23 and block=1",
				"update field set block=70 where tabid=23 and block=5",
				"update field set block=71 where tabid=23 and block=2",
				"update field set block=73 where tabid=23 and block=6",
				"update field set block=74 where tabid=23 and block=3",
			    );
foreach($update_query_array1 as $query)
{
	Execute($query);
}

$insert_query_array5 = Array(
				"insert into blocks values (1,2,'LBL_OPPORTUNITY_INFORMATION',1,0,0,0,0,0)",
				"insert into blocks values (2,2,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into blocks values (3,2,'LBL_DESCRIPTION_INFORMATION',3,0,0,0,0,0)",
				"insert into blocks values (4,4,'LBL_CONTACT_INFORMATION',1,0,0,0,0,0)",
				"insert into blocks values (5,4,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into blocks values (6,4,'LBL_CUSTOMER_PORTAL_INFORMATION',3,0,0,0,0,0)",
				"insert into blocks values (7,4,'LBL_ADDRESS_INFORMATION',4,0,0,0,0,0)",
				"insert into blocks values (8,4,'LBL_DESCRIPTION_INFORMATION',5,0,0,0,0,0)",
				"insert into blocks values (9,6,'LBL_ACCOUNT_INFORMATION',1,0,0,0,0,0)",
				"insert into blocks values (10,6,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into blocks values (11,6,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)",
				"insert into blocks values (12,6,'LBL_DESCRIPTION_INFORMATION',4,0,0,0,0,0)",
				"insert into blocks values (13,7,'LBL_LEAD_INFORMATION',1,0,0,0,0,0)",
				"insert into blocks values (14,7,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into blocks values (15,7,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)",
				"insert into blocks values (16,7,'LBL_DESCRIPTION_INFORMATION',4,0,0,0,0,0)",
				"insert into blocks values (17,8,'LBL_NOTE_INFORMATION',1,0,0,0,0,0)",
				"insert into blocks values (18,8,'',2,1,0,0,0,0)",
				"insert into blocks values (19,9,'LBL_TASK_INFORMATION',1,0,0,0,0,0)",
				"insert into blocks values (20,9,'',2,1,0,0,0,0)",
				"insert into blocks values (21,10,'LBL_EMAIL_INFORMATION',1,0,0,0,0,0)",
				"insert into blocks values (22,10,'',2,1,0,0,0,0)",
				"insert into blocks values (23,10,'',3,1,0,0,0,0)",
				"insert into blocks values (24,10,'',4,1,0,0,0,0)",
				"insert into blocks values (25,13,'LBL_TICKET_INFORMATION',1,0,0,0,0,0)",
				"insert into blocks values (26,13,'',2,1,0,0,0,0)",
				"insert into blocks values (27,13,'LBL_CUSTOM_INFORMATION',3,0,0,0,0,0)",
				"insert into blocks values (28,13,'LBL_DESCRIPTION_INFORMATION',4,0,0,0,0,0)",
				"insert into blocks values (29,13,'LBL_TICKET_RESOLUTION',5,0,0,1,0,0)",
				"insert into blocks values (30,13,'LBL_COMMENTS',6,0,0,1,0,0)",
				"insert into blocks values (31,14,'LBL_PRODUCT_INFORMATION',1,0,0,0,0,0)",
				"insert into blocks values (32,14,'LBL_PRICING_INFORMATION',2,0,0,0,0,0)",
				"insert into blocks values (33,14,'LBL_STOCK_INFORMATION',3,0,0,0,0,0)",
				"insert into blocks values (34,14,'LBL_CUSTOM_INFORMATION',4,0,0,0,0,0)",
				"insert into blocks values (35,14,'LBL_IMAGE_INFORMATION',5,0,0,0,0,0)",
				"insert into blocks values (36,14,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0)",
				"insert into blocks values (37,15,'LBL_FAQ_INFORMATION',1,0,0,0,0,0)",
				"insert into blocks values (38,15,'',2,1,0,0,0,0)",
				"insert into blocks values (39,15,'',3,1,0,0,0,0)",
				"insert into blocks values (40,15,'LBL_COMMENT_INFORMATION',4,0,0,1,0,0)",
				"insert into blocks values (41,16,'LBL_EVENT_INFORMATION',1,0,0,0,0,0)",
				"insert into blocks values (42,16,'',2,1,0,0,0,0)",
				"insert into blocks values (43,16,'',3,1,0,0,0,0)",
				"insert into blocks values (44,18,'LBL_VENDOR_INFORMATION',1,0,0,0,0,0)",
				"insert into blocks values (45,18,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into blocks values (46,18,'LBL_VENDOR_ADDRESS_INFORMATION',3,0,0,0,0,0)",
				"insert into blocks values (47,18,'LBL_DESCRIPTION_INFORMATION',4,0,0,0,0,0)",
				"insert into blocks values (48,19,'LBL_PRICEBOOK_INFORMATION',1,0,0,0,0,0)",
				"insert into blocks values (49,19,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into blocks values (50,19,'LBL_DESCRIPTION_INFORMATION',3,0,0,0,0,0)",
				"insert into blocks values (51,20,'LBL_QUOTE_INFORMATION',1,0,0,0,0,0)",
				"insert into blocks values (52,20,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into blocks values (53,20,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)",
				"insert into blocks values (54,20,'LBL_RELATED_PRODUCTS',4,0,0,0,0,0)",
				"insert into blocks values (55,20,'LBL_TERMS_INFORMATION',5,0,0,0,0,0)",
				"insert into blocks values (56,20,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0)",
				"insert into blocks values (57,21,'LBL_PO_INFORMATION',1,0,0,0,0,0)",
				"insert into blocks values (58,21,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into blocks values (59,21,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)",
				"insert into blocks values (60,21,'LBL_RELATED_PRODUCTS',4,0,0,0,0,0)",
				"insert into blocks values (61,21,'LBL_TERMS_INFORMATION',5,0,0,0,0,0)",
				"insert into blocks values (62,21,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0)",
				"insert into blocks values (63,22,'LBL_SO_INFORMATION',1,0,0,0,0,0)",
				"insert into blocks values (64,22,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into blocks values (65,22,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)",
				"insert into blocks values (66,22,'LBL_RELATED_PRODUCTS',4,0,0,0,0,0)",
				"insert into blocks values (67,22,'LBL_TERMS_INFORMATION',5,0,0,0,0,0)",
				"insert into blocks values (68,22,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0)",
				"insert into blocks values (69,23,'LBL_INVOICE_INFORMATION',1,0,0,0,0,0)",
				"insert into blocks values (70,23,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into blocks values (71,23,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)",
				"insert into blocks values (72,23,'LBL_RELATED_PRODUCTS',4,0,0,0,0,0)",
				"insert into blocks values (73,23,'LBL_TERMS_INFORMATION',5,0,0,0,0,0)",
				"insert into blocks values (74,23,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0)"
			    );
foreach($insert_query_array5 as $query)
{
	Execute($query);
}

$update_query_array2 = Array(
				"update tab set name='Vendors', tablabel='Vendors' where tabid=18",
				"update tab set name='PriceBooks', tablabel='PriceBooks' where tabid=19",
				"update tab set presence=0 where tabid in(18,19)",
				"update relatedlists set label='PriceBooks' where tabid=14 and related_tabid=19"
			    );
foreach($update_query_array2 as $query)
{
	Execute($query);
}

$delete_query1 = "delete from actionmapping where actionname in ('SavePriceBook','SaveVendor','PriceBookEditView','VendorEditView','DeletePriceBook','DeleteVendor','PriceBookDetailView','VendorDetailView')";
Execute($query);

$insert_query_array6 = Array(
			"insert into customview values(".$conn->getUniqueID('customview').",'All',1,0,'Leads')",
			"insert into customview values(".$conn->getUniqueID('customview').",'All',1,0,'Accounts')",
			"insert into customview values(".$conn->getUniqueID('customview').",'All',1,0,'Contacts')",
			"insert into customview values(".$conn->getUniqueID('customview').",'All',1,0,'Potentials')",
			"insert into customview values(".$conn->getUniqueID('customview').",'All',1,0,'HelpDesk')",
			"insert into customview values(".$conn->getUniqueID('customview').",'All',1,0,'Quotes')",
			"insert into customview values(".$conn->getUniqueID('customview').",'All',1,0,'Activities')",
			"insert into customview values(".$conn->getUniqueID('customview').",'All',1,0,'Emails')",
			"insert into customview values(".$conn->getUniqueID('customview').",'All',1,0,'Invoice')",
			"insert into customview values(".$conn->getUniqueID('customview').",'All',1,0,'Notes')",
			"insert into customview values(".$conn->getUniqueID('customview').",'All',1,0,'PriceBooks')",
			"insert into customview values(".$conn->getUniqueID('customview').",'All',1,0,'Products')",
			"insert into customview values(".$conn->getUniqueID('customview').",'All',1,0,'PurchaseOrder')",
				
			"insert into customview values(".$conn->getUniqueID('customview').",'All',1,0,'SalesOrder')",
			"insert into customview values(".$conn->getUniqueID('customview').",'All',1,0,'Vendors')",
			"insert into customview values(".$conn->getUniqueID('customview').",'All',1,0,'Faq')"
			    );
foreach($insert_query_array6 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from customview where viewname='All' and entitytype='Leads'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array7 = Array(
			"insert into cvcolumnlist values ($cvid,0,'leaddetails:lastname:lastname:Leads_Last_Name:V')",
			"insert into cvcolumnlist values ($cvid,1,'leaddetails:firstname:firstname:Leads_First_Name:V')",
			"insert into cvcolumnlist values ($cvid,2,'leaddetails:company:company:Leads_Company:V')",
			"insert into cvcolumnlist values ($cvid,3,'leadaddress:phone:phone:Leads_Phone:V')",
			"insert into cvcolumnlist values ($cvid,4,'leadsubdetails:website:website:Leads_Website:V')",
			"insert into cvcolumnlist values ($cvid,5,'leaddetails:email:email:Leads_Email:V')",
			"insert into cvcolumnlist values ($cvid,6,'crmentity:smownerid:assigned_user_id:Leads_Assigned_To:V')"
			    );
foreach($insert_query_array7 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from customview where viewname='All' and entitytype='Accounts'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array8 = Array(
		"insert into cvcolumnlist values ($cvid,0,'account:accountname:accountname:Accounts_Account_Name:V')",
		"insert into cvcolumnlist values ($cvid,1,'accountbillads:city:city:Accounts_City:V')",
		"insert into cvcolumnlist values ($cvid,2,'account:website:website:Accounts_Website:V')",
		"insert into cvcolumnlist values ($cvid,3,'account:phone:phone:Accounts_Phone:V')",
		"insert into cvcolumnlist values ($cvid,4,'crmentity:smownerid:assigned_user_id:Accounts_Assigned_To:V')"
			    );
foreach($insert_query_array8 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from customview where viewname='All' and entitytype='Contacts'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array9 = Array(
		"insert into cvcolumnlist values ($cvid,0,'contactdetails:firstname:firstname:Contacts_First_Name:V')",
		"insert into cvcolumnlist values ($cvid,1,'contactdetails:lastname:lastname:Contacts_Last_Name:V')",
		"insert into cvcolumnlist values ($cvid,2,'contactdetails:title:title:Contacts_Title:V')",
		"insert into cvcolumnlist values ($cvid,3,'account:accountname:accountname:Contacts_Account_Name:V')",
		"insert into cvcolumnlist values ($cvid,4,'contactdetails:email:email:Contacts_Email:V')",
		"insert into cvcolumnlist values ($cvid,5,'contactdetails:phone:phone:Contacts_Phone_Name:V')",
		"insert into cvcolumnlist values ($cvid,6,'crmentity:smownerid:assigned_user_id:Contacts_Assigned_To:V')"
			    );
foreach($insert_query_array9 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from customview where viewname='All' and entitytype='Potentials'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array10 = Array(
	"insert into cvcolumnlist values ($cvid,0,'potential:potentialname:potentialname:Potentials_Potential_Name:V')",
	"insert into cvcolumnlist values ($cvid,1,'potential:accountid:account_id:Potentials_Account_Name:V')",
	"insert into cvcolumnlist values ($cvid,2,'potential:amount:amount:Potentials_Amount:N')",
	"insert into cvcolumnlist values ($cvid,3,'potential:closingdate:closingdate:Potentials_Expected_Close_Date:D')",
	"insert into cvcolumnlist values ($cvid,4,'crmentity:smownerid:assigned_user_id:Potentials_Assigned_To:V')"
			     );
foreach($insert_query_array10 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from customview where viewname='All' and entitytype='HelpDesk'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array11 = Array(
		"insert into cvcolumnlist values ($cvid,0,'crmentity:crmid::HelpDesk_Ticket_ID:I')",
		"insert into cvcolumnlist values ($cvid,1,'troubletickets:title:ticket_title:HelpDesk_Title:V')",
		"insert into cvcolumnlist values ($cvid,2,'troubletickets:parent_id:parent_id:HelpDesk_Related_to:I')",
		"insert into cvcolumnlist values ($cvid,3,'troubletickets:status:ticketstatus:HelpDesk_Status:V')",
		"insert into cvcolumnlist values ($cvid,4,'troubletickets:priority:ticketpriorities:HelpDesk_Priority:V')",
		"insert into cvcolumnlist values ($cvid,5,'crmentity:smownerid:assigned_user_id:HelpDesk_Assigned_To:V')"
			     );
foreach($insert_query_array11 as $query)
{
	Execute($query);
}


$res=$conn->query("select cvid from customview where viewname='All' and entitytype='Quotes'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array12 = Array(
		"insert into cvcolumnlist values ($cvid,0,'crmentity:crmid::Quotes_Quote_ID:I')",
		"insert into cvcolumnlist values ($cvid,1,'quotes:subject:subject:Quotes_Subject:V')",
		"insert into cvcolumnlist values ($cvid,2,'quotes:quotestage:quotestage:Quotes_Quote_Stage:V')",
		"insert into cvcolumnlist values ($cvid,3,'quotes:potentialid:potential_id:Quotes_Potential_Name:I')",
		"insert into cvcolumnlist values ($cvid,4,'quotes:accountid:account_id:Quotes_Account_Name:I')",
		"insert into cvcolumnlist values ($cvid,5,'quotes:total:hdnGrandTotal:Quotes_Total:I')",
		"insert into cvcolumnlist values ($cvid,6,'crmentity:smownerid:assigned_user_id:Quotes_Assigned_To:V')"
			     );
foreach($insert_query_array12 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from customview where viewname='All' and entitytype='Activities'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array13 = Array(
		"insert into cvcolumnlist values ($cvid,0,'activity:status:status:Activities_Status:V')",
		"insert into cvcolumnlist values ($cvid,1,'activity:activitytype:activitytype:Activities_Type:V')",
		"insert into cvcolumnlist values ($cvid,2,'activity:subject:subject:Activities_Subject:V')",
		"insert into cvcolumnlist values ($cvid,3,'contactdetails:lastname:lastname:Activities_Contact_Name:V')",
		"insert into cvcolumnlist values ($cvid,4,'seactivityrel:activityid:activityid:Activities_Related_To:V')",
		"insert into cvcolumnlist values ($cvid,5,'activity:date_start:date_start:Activities_Start_Date:D')",
		"insert into cvcolumnlist values ($cvid,6,'activity:due_date:due_date:Activities_End_Date:D')",
		"insert into cvcolumnlist values ($cvid,7,'crmentity:smownerid:assigned_user_id:Activities_Assigned_To:V')"
			     );
foreach($insert_query_array13 as $query)
{
	Execute($query);
}


$res=$conn->query("select cvid from customview where viewname='All' and entitytype='Emails'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array14 = Array(
		"insert into cvcolumnlist values ($cvid,0,'activity:subject:subject:Emails_Subject:V')",
		"insert into cvcolumnlist values ($cvid,1,'seactivityrel:activityid:activityid:Emails_Related_To:I')",
		"insert into cvcolumnlist values ($cvid,2,'activity:date_start:date_start:Emails_Date_Sent:D')",
		"insert into cvcolumnlist values ($cvid,3,'crmentity:smownerid:assigned_user_id:Emails_Assigned_To:V')"
			     );
foreach($insert_query_array14 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from customview where viewname='All' and entitytype='Invoice'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array15 = Array(
		"insert into cvcolumnlist values ($cvid,0,'crmentity:crmid::Invoice_Invoice_Id:I')",
		"insert into cvcolumnlist values ($cvid,1,'invoice:subject:subject:Invoice_Subject:V')",
		"insert into cvcolumnlist values ($cvid,2,'invoice:salesorderid:salesorder_id:Invoice_Sales_Order:V')",
		"insert into cvcolumnlist values ($cvid,3,'invoice:invoicestatus:invoicestatus:Invoice_Status:V')",
		"insert into cvcolumnlist values ($cvid,4,'invoice:total:hdnGrandTotal:Invoice_Total:I')",
		"insert into cvcolumnlist values ($cvid,5,'crmentity:smownerid:assigned_user_id:Invoice_Assigned_To:V')"
			     );
foreach($insert_query_array15 as $query)
{
	Execute($query);
}

	     
$res=$conn->query("select cvid from customview where viewname='All' and entitytype='Notes'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array16 = Array(
		"insert into cvcolumnlist values ($cvid,0,'notes:title:title:Notes_Title:V')",
		"insert into cvcolumnlist values ($cvid,1,'notes:contact_id:contact_id:Notes_Contact_Name:I')",
		"insert into cvcolumnlist values ($cvid,2,'senotesrel:crmid:crmid:Notes_Related_To:I')",
		"insert into cvcolumnlist values ($cvid,3,'notes:filename:filename:Notes_File:V')",
		"insert into cvcolumnlist values ($cvid,4,'crmentity:modifiedtime:modifiedtime:Notes_Modified_Time:V')"
			     );
foreach($insert_query_array16 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from customview where viewname='All' and entitytype='PriceBooks'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array17 = Array(
		"insert into cvcolumnlist values ($cvid,1,'pricebook:bookname:bookname:PriceBooks_Price_Book_Name:V')",
		"insert into cvcolumnlist values ($cvid,2,'pricebook:active:active:PriceBooks_Active:V')"
			     );
foreach($insert_query_array17 as $query)
{
	Execute($query);
}


$res=$conn->query("select cvid from customview where viewname='All' and entitytype='Products'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array18 = Array(
	"insert into cvcolumnlist values ($cvid,0,'products:productname:productname:Products_Product_Name:V')",
	"insert into cvcolumnlist values ($cvid,1,'products:productcode:productcode:Products_Product_Code:V')",
	"insert into cvcolumnlist values ($cvid,2,'products:commissionrate:commissionrate:Products_Commission_Rate:V')",
	"insert into cvcolumnlist values ($cvid,3,'products:qty_per_unit:qty_per_unit:Products_Qty/Unit:V')",
	"insert into cvcolumnlist values ($cvid,4,'products:unit_price:unit_price:Products_Unit_Price:V')"
			     );
foreach($insert_query_array18 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from customview where viewname='All' and entitytype='PurchaseOrder'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array19 = Array(
	"insert into cvcolumnlist values($cvid,0,'crmentity:crmid::PurchaseOrder_Order_Id:I')",
	"insert into cvcolumnlist values($cvid,1,'purchaseorder:subject:subject:PurchaseOrder_Subject:V')",
	"insert into cvcolumnlist values($cvid,2,'purchaseorder:vendorid:vendor_id:PurchaseOrder_Vendor_Name:I')",
	"insert into cvcolumnlist values($cvid,3,'purchaseorder:tracking_no:tracking_no:PurchaseOrder_Tracking_Number:V')",
	"insert into cvcolumnlist values($cvid,4,'crmentity:smownerid:assigned_user_id:PurchaseOrder_Assigned_To:V')"
			     );
foreach($insert_query_array19 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from customview where viewname='All' and entitytype='SalesOrder'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array20 = Array(
		"insert into cvcolumnlist values ($cvid,0,'crmentity:crmid::SalesOrder_Order_Id:I')",
		"insert into cvcolumnlist values ($cvid,1,'salesorder:subject:subject:SalesOrder_Subject:V')",
		"insert into cvcolumnlist values ($cvid,2,'account:accountid:account_id:SalesOrder_Account_Name:V')",
		"insert into cvcolumnlist values ($cvid,3,'quotes:quoteid:quote_id:SalesOrder_Quote_Name:I')",
		"insert into cvcolumnlist values ($cvid,4,'salesorder:total:hdnGrandTotal:SalesOrder_Total:V')",
		"insert into cvcolumnlist values ($cvid,5,'crmentity:smownerid:assigned_user_id:SalesOrder_Assigned_To:V')"
			     );
foreach($insert_query_array20 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from customview where viewname='All' and entitytype='Vendors'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array21 = Array(
			"insert into cvcolumnlist values ($cvid,0,'vendor:vendorname:vendorname:Vendors_Vendor_Name:V')",
			"insert into cvcolumnlist values ($cvid,1,'vendor:phone:phone:Vendors_Phone:V')",
			"insert into cvcolumnlist values ($cvid,2,'vendor:email:email:Vendors_Email:V')",
			"insert into cvcolumnlist values ($cvid,3,'vendor:category:category:Vendors_Category:V')"
			     );
foreach($insert_query_array21 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from customview where viewname='All' and entitytype='Faq'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array22 = Array(
		"insert into cvcolumnlist values ($cvid,0,'faq:id::Faq_FAQ_Id:I')",
		"insert into cvcolumnlist values ($cvid,1,'faq:question:question:Faq_Question:V')",
		"insert into cvcolumnlist values ($cvid,2,'faq:category:faqcategories:Faq_Category:V')",
		"insert into cvcolumnlist values ($cvid,3,'faq:product_id:product_id:Faq_Product_Name:I')",
		"insert into cvcolumnlist values ($cvid,4,'crmentity:createdtime:createdtime:Faq_Created_Time:D')",
		"insert into cvcolumnlist values ($cvid,5,'crmentity:modifiedtime:modifiedtime:Faq_Modified_Time:D')"
			     );
foreach($insert_query_array22 as $query)
{
	Execute($query);
}


$update_query_array3 = Array(
				"update field set uitype=53 where tabid=2 and columnname='smownerid'",
				"update field set uitype=53 where tabid=4 and columnname='smownerid'",
				"update field set uitype=53 where tabid=20 and columnname='smownerid'",
				"update field set uitype=53 where tabid=22 and columnname='smownerid'",
				"update field set uitype=53 where tabid=23 and columnname='smownerid'"
			    );
foreach($update_query_array3 as $query)
{
	Execute($query);
}

$create_query6 = "CREATE TABLE accountgrouprelation ( accountid int(19) NOT NULL default '0', groupname varchar(100) default NULL, PRIMARY KEY  (`accountid`))";
Execute($create_query6);

$alter_query_array8 = Array(
				"alter table accountgrouprelation ADD CONSTRAINT fk_accountgrouprelation FOREIGN KEY (accountid) REFERENCES account(accountid) ON DELETE CASCADE",
				"alter table accountgrouprelation ADD CONSTRAINT fk_accountgrouprelation2 FOREIGN KEY (groupname) REFERENCES groups(name) ON DELETE CASCADE"
			   );
foreach($alter_query_array8 as $query)
{
	Execute($query);
}

$create_query7 = "CREATE TABLE contactgrouprelation ( contactid int(19) NOT NULL default '0', groupname varchar(100) default NULL, PRIMARY KEY  (`contactid`))";
Execute($create_query7);

$alter_query_array9 = Array(
				"alter table contactgrouprelation ADD CONSTRAINT fk_contactgrouprelation FOREIGN KEY (contactid) REFERENCES contactdetails(contactid) ON DELETE CASCADE",
				"alter table contactgrouprelation ADD CONSTRAINT fk_contactgrouprelation2 FOREIGN KEY (groupname) REFERENCES groups(name) ON DELETE CASCADE"
			   );
foreach($alter_query_array9 as $query)
{
	Execute($query);
}


$create_query10 = "CREATE TABLE potentialgrouprelation ( potentialid int(19) NOT NULL default '0', groupname varchar(100) default NULL, PRIMARY KEY  (`potentialid`))";
Execute($create_query10);

$alter_query_array10 = Array(
				"alter table potentialgrouprelation ADD CONSTRAINT fk_potentialgrouprelation FOREIGN KEY (potentialid) REFERENCES potential(potentialid) ON DELETE CASCADE",
				"alter table potentialgrouprelation ADD CONSTRAINT fk_potentialgrouprelation2 FOREIGN KEY (groupname) REFERENCES groups(name) ON DELETE CASCADE"
			    );
foreach($alter_query_array10 as $query)
{
	Execute($query);
}

$create_query11 = "CREATE TABLE quotegrouprelation ( quoteid int(19) NOT NULL default '0', groupname varchar(100) default NULL, PRIMARY KEY  (`quoteid`) )";
Execute($create_query11);

$alter_query_array11 = Array(
				"alter table quotegrouprelation ADD CONSTRAINT fk_quotegrouprelation FOREIGN KEY (quoteid) REFERENCES quotes(quoteid) ON DELETE CASCADE",
				"alter table quotegrouprelation ADD CONSTRAINT fk_quotegrouprelation2 FOREIGN KEY (groupname) REFERENCES groups(name) ON DELETE CASCADE"
			    );
foreach($alter_query_array11 as $query)
{
	Execute($query);
}

$create_query12 = "CREATE TABLE sogrouprelation ( salesorderid int(19) NOT NULL default '0', groupname varchar(100) default NULL, PRIMARY KEY  (`salesorderid`) )";
Execute($create_query12);

$alter_query_array12 = Array(
				"alter table sogrouprelation ADD CONSTRAINT fk_sogrouprelation FOREIGN KEY (salesorderid) REFERENCES salesorder(salesorderid) ON DELETE CASCADE",
				"alter table sogrouprelation ADD CONSTRAINT fk_sogrouprelation2 FOREIGN KEY (groupname) REFERENCES groups(name) ON DELETE CASCADE"
			    );
foreach($alter_query_array12 as $query)
{
	Execute($query);
}

$create_query13 = "CREATE TABLE invoicegrouprelation ( invoiceid int(19) NOT NULL default '0',  groupname varchar(100) default NULL,  PRIMARY KEY  (`invoiceid`))";
Execute($create_query13);

$alter_query_array13 = Array(
				"alter table invoicegrouprelation ADD CONSTRAINT fk_invoicegrouprelation FOREIGN KEY (invoiceid) REFERENCES invoice(invoiceid) ON DELETE CASCADE",
				"alter table invoicegrouprelation ADD CONSTRAINT fk_invoicegrouprelation2 FOREIGN KEY (groupname) REFERENCES groups(name) ON DELETE CASCADE"
			    );
foreach($alter_query_array13 as $query)
{
	Execute($query);
}

$create_query14 = "CREATE TABLE pogrouprelation ( purchaseorderid int(19) NOT NULL default '0', groupname varchar(100) default NULL, PRIMARY KEY  (`purchaseorderid`))";
Execute($create_query14);

$alter_query_array14 = Array(
				"alter table pogrouprelation ADD CONSTRAINT fk_pogrouprelation FOREIGN KEY (purchaseorderid) REFERENCES purchaseorder(purchaseorderid) ON DELETE CASCADE",
				"alter table pogrouprelation ADD CONSTRAINT fk_productgrouprelation2 FOREIGN KEY (groupname) REFERENCES groups(name) ON DELETE CASCADE"
			    );
foreach($alter_query_array14 as $query)
{
	Execute($query);
}

$alter_query1 = "ALTER TABLE users ADD column lead_view VARCHAR(25) DEFAULT 'Today' AFTER homeorder";
Execute($alter_query1);

$update_query1 = "update users set homeorder = 'ALVT,PLVT,QLTQ,CVLVT,HLT,OLV,GRT,OLTSO,ILTI,MNL'";
Execute($update_query1);

$alter_query2 = "ALTER TABLE products change column imagename imagename text";
Execute($alter_query2);

$alter_query3 = "alter table systems modify server varchar(50), modify server_username varchar(50), modify server_password varchar(50), add column smtp_auth char(5)";
Execute($alter_query3);

$alter_query_array15 = Array( 
				"alter table users add column imagename varchar(250)",
				"alter table users add column tagcloud varchar(250)"
			    );
foreach($alter_query_array15 as $query)
{
	Execute($query);
}

$alter_query_array16 = Array(
			"alter table systems change column server server varchar(80) default NULL",
			"alter table systems change column server_username server_username varchar(80) default NULL"
			    );
foreach($alter_query_array16 as $query)
{
	Execute($query);
}


$create_query15 = "create table portal(portalid int(19), portalname varchar(255) NOT NULL, portalurl varchar(255) NOT NULL,sequence int(3) NOT NULL, PRIMARY KEY (portalid))";
Execute($create_query15);

$alter_query_array = Array( 
				"alter table attachments drop column attachmentsize",
				"alter table attachments drop column attachmentcontents"
			    );
foreach($alter_query_array as $query)
{
	Execute($query);
}

$update_query2 = "UPDATE field SET fieldlabel = 'Reference' WHERE tabid = 4 and tablename = 'contactdetails'";
Execute($update_query2);

$alter_query = "ALTER TABLE field ADD column info_type varchar(20) default NULL after quickcreatesequence";
Execute($alter_query);

$update_query_array4 = Array(
				"UPDATE field SET info_type = 'BAS'",

				"UPDATE field SET info_type = 'ADV' WHERE tabid = 7 and fieldlabel = 'Website'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 7 and fieldlabel = 'Industry'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 7 and fieldlabel = 'Annual Revenue'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 7 and fieldlabel = 'No Of Employees'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 7 and fieldlabel = 'Yahoo Id'",

				"UPDATE field SET info_type = 'ADV' WHERE tabid = 6 and fieldlabel = 'Ticker Symbol'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 6 and fieldlabel = 'Other Phone'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 6 and fieldlabel = 'Member Of'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 6 and fieldlabel = 'Employees'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 6 and fieldlabel = 'Other Email'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 6 and fieldlabel = 'Ownership'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 6 and fieldlabel = 'Rating'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 6 and fieldlabel = 'industry'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 6 and fieldlabel = 'SIC Code'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 6 and fieldlabel = 'Type'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 6 and fieldlabel = 'Annual Revenue'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 6 and fieldlabel = 'Email Opt Out'",

				"UPDATE field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Home Phone'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Department'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Birthdate'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Email'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Reports To'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Assistant'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Yahoo Id'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Assistant Phone'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Do Not Call'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Email Opt Out'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Reference'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Portal User'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Support Start Date'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Support End Date'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Contact Image'",

				"UPDATE field SET info_type = 'ADV' WHERE tabid = 14 and fieldlabel = 'Usage Unit'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 14 and fieldlabel = 'Qty/Unit'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 14 and fieldlabel = 'Qty In Stock'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 14 and fieldlabel = 'Reorder Level'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 14 and fieldlabel = 'Handler'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 14 and fieldlabel = 'Qty In Demand'",
				"UPDATE field SET info_type = 'ADV' WHERE tabid = 14 and fieldlabel = 'Product Image'"
			    );
foreach($update_query_array4 as $query)
{
	Execute($query);
}


$create_query16 = "CREATE TABLE `chat_msg` ( `id` bigint(20) NOT NULL auto_increment, `chat_from` bigint(20) NOT NULL default '0', `chat_to` bigint(20) NOT NULL default '0', `born` timestamp NULL default '0000-00-00 00:00:00', `msg` varchar(255) NOT NULL, PRIMARY KEY  (`id`), KEY `chat_to` (`chat_to`), KEY `chat_from` (`chat_from`), KEY `born` (`born`)) ENGINE=InnoDB";
Execute($create_query16);
$create_query17 = "CREATE TABLE `chat_pchat` ( `id` bigint(20) NOT NULL auto_increment, `msg` bigint(20) NOT NULL, PRIMARY KEY  (`id`), UNIQUE KEY `msg` (`msg`)) ENGINE=InnoDB";
Execute($create_query17);

$create_query18 = "CREATE TABLE `chat_pvchat` ( `id` bigint(20) NOT NULL auto_increment, `msg` bigint(20) NOT NULL, PRIMARY KEY  (`id`), UNIQUE KEY `msg` (`msg`)) ENGINE=InnoDB";
Execute($create_query18);

$create_query19 = "CREATE TABLE `chat_users` ( `id` bigint(20) NOT NULL auto_increment, `nick` varchar(50) NOT NULL, `session` varchar(50) NOT NULL, `ip` varchar(20) NOT NULL default '000.000.000.000', `ping` timestamp NULL default '0000-00-00 00:00:00', PRIMARY KEY  (`id`), UNIQUE KEY `session` (`session`), UNIQUE KEY `nick` (`nick`), KEY `ping` (`ping`)) ENGINE=InnoDB";
Execute($create_query19);

$alter_query_array17 = Array(
				"ALTER TABLE `chat_msg`  ADD CONSTRAINT `chat_msg_ibfk_1` FOREIGN KEY (`chat_from`) REFERENCES `chat_users` (`id`) ON DELETE CASCADE",

				"ALTER TABLE `chat_pchat`  ADD CONSTRAINT `chat_pchat_ibfk_1` FOREIGN KEY (`msg`) REFERENCES `chat_msg` (`id`) ON DELETE CASCADE",

				"ALTER TABLE `chat_pvchat`  ADD CONSTRAINT `chat_pvchat_ibfk_1` FOREIGN KEY (`msg`) REFERENCES `chat_msg` (`id`) ON DELETE CASCADE"
			    );
foreach($alter_query_array17 as $query)
{
	Execute($query);
}

$create_query20 = "CREATE TABLE freetags ( id int(19) NOT NULL, tag varchar(50) NOT NULL default '', raw_tag varchar(50) NOT NULL default '', PRIMARY KEY  (id)) TYPE=MyISAM";
Execute($create_query20);

$create_query21 = "CREATE TABLE freetagged_objects ( tag_id int(19) NOT NULL default '0', tagger_id int(19) NOT NULL default '0', object_id int(19) NOT NULL default '0', tagged_on datetime NOT NULL default '0000-00-00 00:00:00', module varchar(50) NOT NULL default '', PRIMARY KEY  (`tag_id`,`tagger_id`,`object_id`), KEY `tag_id_index` (`tag_id`), KEY `tagger_id_index` (`tagger_id`),  KEY `object_id_index` (`object_id`)
) TYPE=MyISAM";
Execute($create_query21);
  
$alter_query4 = "alter table profile add column description text";
Execute($alter_query4);

$alter_query5 = "alter table contactdetails add column imagename varchar(250) after currency";
Execute($alter_query5);

$alter_query = "ALTER TABLE contactdetails ADD column reference varchar(3) default NULL after imagename";
Execute($alter_query);

$insert_query_array23 = Array(
				"insert into blocks values(75,4,'LBL_IMAGE_INFORMATION',5,0,0,0,0,0)",
				"insert into field values(4,".$conn->getUniqueID("field").",'imagename','contactdetails',1,'69','imagename','Contact Image',1,0,0,100,1,75,1,'V~O',1,null,'ADV')",

				"Insert into field values(9,".$conn->getUniqueID("field").",'visibility','activity',1,15,'visibility','Visibility',1,0,0,100,17,19,3,'V~O',1,null,'BAS')",
				"Insert into field values(16,".$conn->getUniqueID("field").",'visibility','activity',1,15,'visibility','Visibility',1,0,0,100,19,41,1,'V~O',1,null,'BAS')"
			     );
foreach($insert_query_array23 as $query)
{
	Execute($query);
}

$alter_query6 = "alter table activity add column visibility varchar(50) NOT NULL after notime";
Execute($alter_query6);

$create_query22 = "CREATE TABLE `visibility` ( `visibilityid` int(19) NOT NULL auto_increment, `visibility` varchar(200) NOT NULL default '', `sortorderid` int(19) NOT NULL default '0', `presence` int(1) NOT NULL default '1', PRIMARY KEY  (`visibilityid`), UNIQUE KEY `Visibility_VLY` (`visibility`)) ENGINE=InnoDB";
Execute($create_query22);


$create_query23 = "CREATE TABLE `sharedcalendar` ( `userid` int(19) NOT NULL default '0',  `sharedid` int(19) NOT NULL default '0', PRIMARY KEY  (`userid`,`sharedid`)) ENGINE=MyISAM";
Execute($create_query23);

$insert_query6 = "INSERT INTO tab VALUES(26,'Campaigns',0,23,'Campaigns',null,null,1)";
Execute($insert_query6);
$insert_query7 = "INSERT INTO parenttabrel VALUES(2,26,1)";
Execute($insert_query7);

$insert_query8 = "insert into blocks values(76,26,'LBL_CAMPAIGN_INFORMATION',1,0,0,0,0,0)";
Execute($insert_query8);
$insert_query9 = "insert into blocks values(77,26,'LBL_DESCRIPTION_INFORMATION',2,0,0,0,0,0)";
Execute($insert_query9);

$insert_query_array24 = Array(
	"insert into field values(26,".$conn->getUniqueID("field").",'campaignname','campaign',1,'2','campaignname','Campaign Name',1,0,0,100,1,76,1,'V~M',0,1,'BAS')",
	"insert into field values(26,".$conn->getUniqueID("field").",'campaigntype','campaign',1,15,'campaigntype','Campaign Type',1,0,0,100,2,76,1,'N~O',0,5,'BAS')",
	"insert into field values(26,".$conn->getUniqueID("field").",'campaignstatus','campaign',1,15,'campaignstatus','Campaign Status',1,0,0,100,3,76,1,'N~O',0,5,'BAS')",
	"insert into field values(26,".$conn->getUniqueID("field").",'closingdate','campaign',1,'23','closingdate','Expected Close Date',1,0,0,100,5,76,1,'D~M',0,3,'BAS')",
	"insert into field values(26,".$conn->getUniqueID("field").",'expectedrevenue','campaign',1,'15','expectedrevenue','Expected Revenue',1,0,0,100,6,76,1,'V~O',1,null,'BAS')",
	"insert into field values(26,".$conn->getUniqueID("field").",'budgetcost','campaign',1,'1','budgetcost','Budget Cost',1,0,0,100,7,76,1,'V~O',1,null,'BAS')",
	"insert into field values(26,".$conn->getUniqueID("field").",'actualcost','campaign',1,'15','actualcost','Actual Cost',1,0,0,100,8,76,1,'V~O',1,null,'BAS')",
	"insert into field values(26,".$conn->getUniqueID("field").",'expectedresponse','campaign',1,'16','expectedresponse','Expected Response',1,0,0,100,9,76,1,'V~O',0,4,'BAS')",
	"insert into field values(26,".$conn->getUniqueID("field").",'smownerid','crmentity',1,'53','assigned_user_id','Assigned To',1,0,0,100,10,76,1,'V~M',1,null,'BAS')",
	"insert into field values(26,".$conn->getUniqueID("field").",'numsent','campaign',1,'9','numsent','Num Sent',1,0,0,100,11,76,1,'N~O',1,null,'BAS')",
	"insert into field values(26,".$conn->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,13,76,2,'T~O',1,null,'BAS')",
	"insert into field values(26,".$conn->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,14,76,2,'T~O',1,null,'BAS')",
	"insert into field values(26,".$conn->getUniqueID("field").",'description','crmentity',1,'19','description','Description',1,0,0,100,1,77,1,'V~O',1,null,'BAS')"
			     );
foreach($insert_query_array24 as $query)
{
	Execute($query);
}


$insert_query_array25 = Array(
	"insert into relatedlists values (".$conn->getUniqueID('relatedlists').",".getTabid("Campaigns").",".getTabid("Contacts").",'get_contacts',1,'Contacts',0)",
	"insert into relatedlists values (".$conn->getUniqueID('relatedlists').",".getTabid("Campaigns").",".getTabid("Leads").",'get_leads',2,'Leads',0)"
			     );
foreach($insert_query_array25 as $query)
{
	Execute($query);
}


$insert_query_array26 = Array(
	"insert into field values (7,".$conn->getUniqueID("field").",'campaignid','leaddetails',1,'51','campaignid','Campaign Name',1,0,0,100,6,4,3,'I~O',1,null,'BAS')",
	"insert into field values (4,".$conn->getUniqueID("field").",'campaignid','contactdetails',1,'51','campaignid','Campaign Name',1,0,0,100,6,4,3,'I~O',1,null,'BAS')"
			     );
foreach($insert_query_array26 as $query)
{
	Execute($query);
}


$create_query24 = "CREATE TABLE `campaign` (
  `campaignname` varchar(255) NOT NULL default '',
  `campaigntype` varchar(255) default NULL,
  `campaignstatus` varchar(255) default NULL,
  `expectedrevenue` decimal(11,3) default NULL,
  `budgetcost` decimal(11,3) default NULL,
  `actualcost` decimal(11,3) default NULL,
  `expectedresponse` decimal(10,0) default NULL,
  `numsent` decimal(11,0) default NULL,
  `campaignid` int(7) default NULL,
  `closingdate` date default NULL,
  PRIMARY KEY  (`campaignname`),
  KEY `idx_campaignid` (`campaignid`),
  KEY `idx_campaignname` (`campaignname`)
) ENGINE=InnoDB";
Execute($create_query24);

$create_query25 = "CREATE TABLE `campaigncontrel` (
  `campaignid` int(19) NOT NULL default '0',
  `contactid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`campaignid`),
  KEY `CampaignContRel_IDX1` (`contactid`),
  CONSTRAINT `fk_CampaignContRel2` FOREIGN KEY (`contactid`) REFERENCES `contactdetails` (`contactid`) ON DELETE CASCADE
) ENGINE=InnoDB";
Execute($create_query25);

$create_query26 = "CREATE TABLE `campaignleadrel` (
  `campaignid` int(19) NOT NULL default '0',
  `leadid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`campaignid`),
  KEY `CampaignLeadRel_IDX1` (`leadid`),
  CONSTRAINT `fk_CampaignLeadRel2` FOREIGN KEY (`leadid`) REFERENCES `leaddetails` (`leadid`) ON DELETE CASCADE
) ENGINE=InnoDB";
Execute($create_query26);

$alter_query_array18 = Array(
				"alter table leaddetails add column campaignid int(19) default NULL after leadid",
				"alter table contactdetails add column  campaignid int(19) default NULL after accountid",
				//"alter table notes drop PRIMARY KEY contact_id",
				"alter table notes drop PRIMARY KEY , add primary key(notesid)",
				"update field set uitype=99 where fieldname='update_log' and tabid=13"
			    );
foreach($alter_query_array18 as $query)
{
	Execute($query);
}



echo "<br><br><b>Database Modifications for Indexing and some missded tables starts here.....</b><br>";
//Added queries which are for indexing and the missing tables - Mickie - on 06-04-2006

$query_array = Array(

"ALTER TABLE `accountgrouprelation` DROP INDEX `fk_accountgrouprelation2`",
"ALTER TABLE `accountscf` DROP COLUMN `cf_356`",
"ALTER TABLE `activity` DROP INDEX `status`",
"ALTER TABLE `attachments` DROP INDEX `attachmentsid`",
"ALTER TABLE `carrier` DROP INDEX `carrier_UK0`",
"ALTER TABLE `chat_msg` DROP INDEX `chat_to`",
"ALTER TABLE `chat_msg` DROP INDEX `chat_from`",
"ALTER TABLE `chat_msg` DROP INDEX `born`",
"ALTER TABLE `chat_pchat` DROP INDEX `msg`",
"ALTER TABLE `chat_pvchat` DROP INDEX `msg`",
"ALTER TABLE `chat_users` DROP INDEX `session`",
"ALTER TABLE `chat_users` DROP INDEX `nick`",
"ALTER TABLE `chat_users` DROP INDEX `ping`",
"ALTER TABLE `contactgrouprelation` DROP INDEX `fk_contactgrouprelation2`",
"ALTER TABLE `customview` DROP INDEX `customview`",
"ALTER TABLE `def_org_field` DROP INDEX `tabid`",
"ALTER TABLE `field` DROP INDEX `tabid`",
"ALTER TABLE `freetagged_objects` DROP INDEX `tagger_id_index`",
"ALTER TABLE `freetagged_objects` DROP INDEX `object_id_index`",
"ALTER TABLE `groups` DROP INDEX `groupname`",
"ALTER TABLE `invoicegrouprelation` DROP INDEX `fk_invoicegrouprelation2`",
"ALTER TABLE `leadscf` DROP COLUMN `cf_354`",
"ALTER TABLE `leadscf` DROP COLUMN `cf_358`",
"ALTER TABLE `leadscf` DROP COLUMN `cf_360`",
"ALTER TABLE `pogrouprelation` DROP INDEX `fk_productgrouprelation2`",
"ALTER TABLE `potential` DROP INDEX `potentialid`",
"ALTER TABLE `potentialgrouprelation` DROP INDEX `fk_potentialgrouprelation2`",
"ALTER TABLE `profile2field` DROP INDEX `tabid`",
"ALTER TABLE `profile2tab` DROP INDEX `idx_profile2tab`",
"ALTER TABLE `quotegrouprelation` DROP INDEX `fk_quotegrouprelation2`",
"ALTER TABLE `reportmodules` DROP INDEX `reportmodules_IDX0`",
"ALTER TABLE `reportsortcol` DROP INDEX `reportsortcol_IDX0`",
"ALTER TABLE `reportsummary` DROP INDEX `reportsummary_IDX0`",
"ALTER TABLE `seattachmentsrel` DROP INDEX `attachmentsid`",
"ALTER TABLE `sogrouprelation` DROP INDEX `fk_sogrouprelation2`",
"ALTER TABLE `soproductrel` DROP COLUMN `shortdescription`",
"ALTER TABLE `tab` DROP INDEX `tabid`",
"ALTER TABLE `troubletickets` DROP INDEX `status`",
"ALTER TABLE `accountgrouprelation` TYPE=InnoDB, COMMENT='', ROW_FORMAT=COMPACT",
"ALTER TABLE `activity_reminder` TYPE=MyISAM, COMMENT='', ROW_FORMAT=FIXED",
"ALTER TABLE `activsubtype` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `contactgrouprelation` TYPE=InnoDB, COMMENT='', ROW_FORMAT=COMPACT",
//"DROP TABLE `crmentity_seq`",
"ALTER TABLE `currency_info` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `customerdetails` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
//"DROP TABLE `customfield_sequence_seq`",
"ALTER TABLE `customview_seq` TYPE=MyISAM, COMMENT='', ROW_FORMAT=FIXED",
"ALTER TABLE `def_org_field` TYPE=MyISAM, COMMENT='', ROW_FORMAT=FIXED",
"ALTER TABLE `def_org_share` TYPE=MyISAM, COMMENT='', ROW_FORMAT=FIXED",
"ALTER TABLE `def_org_share_seq` TYPE=MyISAM, COMMENT='', ROW_FORMAT=FIXED",
"ALTER TABLE `defaultcv` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `durationhrs` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `durationmins` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `emailtemplates` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `emailtemplates_seq` TYPE=MyISAM, COMMENT='', ROW_FORMAT=FIXED",
"ALTER TABLE `faqcategories` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `faqstatus` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `field_seq` TYPE=MyISAM, COMMENT='', ROW_FORMAT=FIXED",
"ALTER TABLE `files` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `freetagged_objects` TYPE=InnoDB, COMMENT='', ROW_FORMAT=COMPACT",
"ALTER TABLE `group2grouprel` TYPE=MyISAM, COMMENT='', ROW_FORMAT=FIXED",
"ALTER TABLE `group2role` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `group2rs` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
//"DROP TABLE `groups_seq`",
"ALTER TABLE `headers` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `import_maps` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `inventorynotification_seq` TYPE=MyISAM, COMMENT='', ROW_FORMAT=FIXED",
"ALTER TABLE `invoicegrouprelation` TYPE=InnoDB, COMMENT='', ROW_FORMAT=COMPACT",
"ALTER TABLE `loginhistory` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `mail_accounts` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `notificationscheduler_seq` TYPE=MyISAM, COMMENT='', ROW_FORMAT=FIXED",
"ALTER TABLE `ownernotify` TYPE=InnoDB, COMMENT='', ROW_FORMAT=COMPACT",
"ALTER TABLE `parenttabrel` TYPE=InnoDB, COMMENT='', ROW_FORMAT=COMPACT",
"ALTER TABLE `pogrouprelation` TYPE=InnoDB, COMMENT='', ROW_FORMAT=COMPACT",
"ALTER TABLE `portal` TYPE=InnoDB, COMMENT='', ROW_FORMAT=COMPACT",
"ALTER TABLE `portalinfo` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `potentialgrouprelation` TYPE=InnoDB, COMMENT='', ROW_FORMAT=COMPACT",
"ALTER TABLE `profile2field` TYPE=MyISAM, COMMENT='', ROW_FORMAT=FIXED",
"ALTER TABLE `profile2globalpermissions` TYPE=InnoDB, COMMENT='', ROW_FORMAT=COMPACT",
"ALTER TABLE `profile2standardpermissions` TYPE=MyISAM, COMMENT='', ROW_FORMAT=FIXED",
"ALTER TABLE `profile2tab` TYPE=MyISAM, COMMENT='', ROW_FORMAT=FIXED",
"ALTER TABLE `profile2utility` TYPE=MyISAM, COMMENT='', ROW_FORMAT=FIXED",
"ALTER TABLE `profile_seq` TYPE=MyISAM, COMMENT='', ROW_FORMAT=FIXED",
"ALTER TABLE `quotegrouprelation` TYPE=InnoDB, COMMENT='', ROW_FORMAT=COMPACT",
"ALTER TABLE `rating` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `relatedlists` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `relatedlists_seq` TYPE=MyISAM, COMMENT='', ROW_FORMAT=FIXED",
"ALTER TABLE `role2profile` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `role_seq` TYPE=MyISAM, COMMENT='', ROW_FORMAT=FIXED",
"ALTER TABLE `rss` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `sales_stage` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `salutationtype` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `selectquery_seq` TYPE=MyISAM, COMMENT='', ROW_FORMAT=FIXED",
"ALTER TABLE `sogrouprelation` TYPE=InnoDB, COMMENT='', ROW_FORMAT=COMPACT",
"ALTER TABLE `systems` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `taskpriority` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `taskstatus` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `ticketcategories` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `ticketpriorities` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `ticketseverities` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `ticketstatus` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `ticketstracktime` TYPE=MyISAM, COMMENT='', ROW_FORMAT=FIXED",
"ALTER TABLE `tracker` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `users2group` TYPE=MyISAM, COMMENT='', ROW_FORMAT=FIXED",
"ALTER TABLE `users_last_import` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",
"ALTER TABLE `users_seq` TYPE=MyISAM, COMMENT='', ROW_FORMAT=FIXED",
"ALTER TABLE `wordtemplates` TYPE=MyISAM, COMMENT='', ROW_FORMAT=DYNAMIC",

"CREATE TABLE `actualcost` (
  `actualcostid` int(19) NOT NULL auto_increment,
  `actualcost` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`actualcostid`),
  UNIQUE KEY `CampaignActCst_UK01` (`actualcost`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `campaignstatus` (
  `campaignstatusid` int(19) NOT NULL auto_increment,
  `campaignstatus` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`campaignstatusid`),
  KEY `Campaignstatus_UK01` (`campaignstatus`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `campaigntype` (
  `campaigntypeid` int(19) NOT NULL auto_increment,
  `campaigntype` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`campaigntypeid`),
  UNIQUE KEY `Campaigntype_UK01` (`campaigntype`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `currency_info_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1",

"CREATE TABLE `datashare_grp2grp` (
  `shareid` int(19) NOT NULL,
  `share_groupid` int(19) default NULL,
  `to_groupid` int(19) default NULL,
  `permission` int(19) default NULL,
  PRIMARY KEY  (`shareid`),
  KEY `idx_datashare_grp2grp_share_groupid` (`share_groupid`),
  KEY `idx_datashare_grp2grp_to_groupid` (`to_groupid`),
  CONSTRAINT `fk_datashare_grp2grp2` FOREIGN KEY (`to_groupid`) REFERENCES `groups` (`groupid`) ON DELETE CASCADE,
  CONSTRAINT `fk_datashare_grp2grp1` FOREIGN KEY (`share_groupid`) REFERENCES `groups` (`groupid`) ON DELETE CASCADE,
  CONSTRAINT `fk_datashare_grp2grp789` FOREIGN KEY (`shareid`) REFERENCES `datashare_module_rel` (`shareid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `datashare_grp2role` (
  `shareid` int(19) NOT NULL,
  `share_groupid` int(19) default NULL,
  `to_roleid` varchar(255) default NULL,
  `permission` int(19) default NULL,
  PRIMARY KEY  (`shareid`),
  KEY `idx_datashare_grp2role_share_groupid` (`share_groupid`),
  KEY `idx_datashare_grp2role_to_roleid` (`to_roleid`),
  CONSTRAINT `fk_datashare_grp2role2` FOREIGN KEY (`to_roleid`) REFERENCES `role` (`roleid`) ON DELETE CASCADE,
  CONSTRAINT `fk_datashare_grp2role1` FOREIGN KEY (`share_groupid`) REFERENCES `groups` (`groupid`) ON DELETE CASCADE,
  CONSTRAINT `fk_datashare_grp2role345` FOREIGN KEY (`shareid`) REFERENCES `datashare_module_rel` (`shareid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `datashare_grp2rs` (
  `shareid` int(19) NOT NULL,
  `share_groupid` int(19) default NULL,
  `to_roleandsubid` varchar(255) default NULL,
  `permission` int(19) default NULL,
  PRIMARY KEY  (`shareid`),
  KEY `idx_datashare_grp2rs_share_groupid` (`share_groupid`),
  KEY `idx_datashare_grp2rs_to_roleandsubid` (`to_roleandsubid`),
  CONSTRAINT `fk_datashare_grp2rs3` FOREIGN KEY (`to_roleandsubid`) REFERENCES `role` (`roleid`) ON DELETE CASCADE,
  CONSTRAINT `fk_datashare_grp2rs1` FOREIGN KEY (`share_groupid`) REFERENCES `groups` (`groupid`) ON DELETE CASCADE,
  CONSTRAINT `fk_datashare_grp2rs36` FOREIGN KEY (`shareid`) REFERENCES `datashare_module_rel` (`shareid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `datashare_module_rel` (
  `shareid` int(19) NOT NULL,
  `tabid` int(19) NOT NULL,
  `relationtype` varchar(200) default NULL,
  PRIMARY KEY  (`shareid`),
  KEY `idx_datashare_module_rel_tabid` (`tabid`),
  CONSTRAINT `fk_datashare_module_rel456` FOREIGN KEY (`tabid`) REFERENCES `tab` (`tabid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `datashare_relatedmodule_permission` (
  `shareid` int(19) NOT NULL,
  `datashare_relatedmodule_id` int(19) NOT NULL,
  `permission` int(19) default NULL,
  PRIMARY KEY  (`shareid`,`datashare_relatedmodule_id`),
  KEY `datashare_relatedmodule_permission_UK1` (`shareid`,`permission`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `datashare_relatedmodules` (
  `datashare_relatedmodule_id` int(19) NOT NULL,
  `tabid` int(19) default NULL,
  `relatedto_tabid` int(19) default NULL,
  PRIMARY KEY  (`datashare_relatedmodule_id`),
  KEY `idx_datashare_relatedmodules_tabid` (`tabid`),
  KEY `idx_datashare_relatedmodules_relatedto_tabid` (`relatedto_tabid`),
  CONSTRAINT `fk_datashare_relatedmodules1` FOREIGN KEY (`relatedto_tabid`) REFERENCES `tab` (`tabid`) ON DELETE CASCADE,
  CONSTRAINT `fk_datashare_relatedmodules123` FOREIGN KEY (`tabid`) REFERENCES `tab` (`tabid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `datashare_relatedmodules_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1",

"CREATE TABLE `datashare_role2group` (
  `shareid` int(19) NOT NULL,
  `share_roleid` varchar(255) default NULL,
  `to_groupid` int(19) default NULL,
  `permission` int(19) default NULL,
  PRIMARY KEY  (`shareid`),
  KEY `idx_datashare_role2group_share_roleid` (`share_roleid`),
  KEY `idx_datashare_role2group_to_groupid` (`to_groupid`),
  CONSTRAINT `fk_datashare_role2group3` FOREIGN KEY (`to_groupid`) REFERENCES `groups` (`groupid`) ON DELETE CASCADE,
  CONSTRAINT `fk_datashare_role2group1` FOREIGN KEY (`share_roleid`) REFERENCES `role` (`roleid`) ON DELETE CASCADE,
  CONSTRAINT `fk_datashare_role2group568` FOREIGN KEY (`shareid`) REFERENCES `datashare_module_rel` (`shareid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `datashare_role2role` (
  `shareid` int(19) NOT NULL,
  `share_roleid` varchar(255) default NULL,
  `to_roleid` varchar(255) default NULL,
  `permission` int(19) default NULL,
  PRIMARY KEY  (`shareid`),
  KEY `idx_datashare_role2role_share_roleid` (`share_roleid`),
  KEY `idx_datashare_role2role_to_roleid` (`to_roleid`),
  CONSTRAINT `fk_datashare_role2role3` FOREIGN KEY (`to_roleid`) REFERENCES `role` (`roleid`) ON DELETE CASCADE,
  CONSTRAINT `fk_datashare_role2role1` FOREIGN KEY (`share_roleid`) REFERENCES `role` (`roleid`) ON DELETE CASCADE,
  CONSTRAINT `fk_datashare_role2role345` FOREIGN KEY (`shareid`) REFERENCES `datashare_module_rel` (`shareid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `datashare_role2rs` (
  `shareid` int(19) NOT NULL,
  `share_roleid` varchar(255) default NULL,
  `to_roleandsubid` varchar(255) default NULL,
  `permission` int(19) default NULL,
  PRIMARY KEY  (`shareid`),
  KEY `idx_datashare_role2s_share_roleid` (`share_roleid`),
  KEY `idx_datashare_role2s_to_roleandsubid` (`to_roleandsubid`),
  CONSTRAINT `fk_datashare_role2rs3` FOREIGN KEY (`to_roleandsubid`) REFERENCES `role` (`roleid`) ON DELETE CASCADE,
  CONSTRAINT `fk_datashare_role2rs1` FOREIGN KEY (`share_roleid`) REFERENCES `role` (`roleid`) ON DELETE CASCADE,
  CONSTRAINT `fk_datashare_role2rs987` FOREIGN KEY (`shareid`) REFERENCES `datashare_module_rel` (`shareid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `datashare_rs2grp` (
  `shareid` int(19) NOT NULL,
  `share_roleandsubid` varchar(255) default NULL,
  `to_groupid` int(19) default NULL,
  `permission` int(19) default NULL,
  PRIMARY KEY  (`shareid`),
  KEY `idx_datashare_rs2grp_share_roleandsubid` (`share_roleandsubid`),
  KEY `idx_datashare_rs2grp_to_groupid` (`to_groupid`),
  CONSTRAINT `fk_datashare_rs2grp2` FOREIGN KEY (`to_groupid`) REFERENCES `groups` (`groupid`) ON DELETE CASCADE,
  CONSTRAINT `fk_datashare_rs2grp1` FOREIGN KEY (`share_roleandsubid`) REFERENCES `role` (`roleid`) ON DELETE CASCADE,
  CONSTRAINT `fk_datashare_rs2grpQ2` FOREIGN KEY (`shareid`) REFERENCES `datashare_module_rel` (`shareid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `datashare_rs2role` (
  `shareid` int(19) NOT NULL,
  `share_roleandsubid` varchar(255) default NULL,
  `to_roleid` varchar(255) default NULL,
  `permission` int(19) default NULL,
  PRIMARY KEY  (`shareid`),
  KEY `idx_datashare_rs2role_share_roleandsubid` (`share_roleandsubid`),
  KEY `idx_datashare_rs2role_to_roleid` (`to_roleid`),
  CONSTRAINT `fk_datashare_rs2role2` FOREIGN KEY (`to_roleid`) REFERENCES `role` (`roleid`) ON DELETE CASCADE,
  CONSTRAINT `fk_datashare_rs2role1` FOREIGN KEY (`share_roleandsubid`) REFERENCES `role` (`roleid`) ON DELETE CASCADE,
  CONSTRAINT `fk_datashare_rs2role122` FOREIGN KEY (`shareid`) REFERENCES `datashare_module_rel` (`shareid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `datashare_rs2rs` (
  `shareid` int(19) NOT NULL,
  `share_roleandsubid` varchar(255) default NULL,
  `to_roleandsubid` varchar(255) default NULL,
  `permission` int(19) default NULL,
  PRIMARY KEY  (`shareid`),
  KEY `idx_datashare_rs2rs_share_roleandsubid` (`share_roleandsubid`),
  KEY `idx_datashare_rs2rs_to_roleandsubid` (`to_roleandsubid`),
  CONSTRAINT `fk_datashare_rs2rs2` FOREIGN KEY (`to_roleandsubid`) REFERENCES `role` (`roleid`) ON DELETE CASCADE,
  CONSTRAINT `fk_datashare_rs2rs1` FOREIGN KEY (`share_roleandsubid`) REFERENCES `role` (`roleid`) ON DELETE CASCADE,
  CONSTRAINT `fk_datashare_rs2rs353` FOREIGN KEY (`shareid`) REFERENCES `datashare_module_rel` (`shareid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `expectedresponse` (
  `expectedresponseid` int(19) NOT NULL auto_increment,
  `expectedresponse` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`expectedresponseid`),
  UNIQUE KEY `CampaignExpRes_UK01` (`expectedresponse`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `expectedrevenue` (
  `expectedrevenueid` int(19) NOT NULL auto_increment,
  `expectedrevenue` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`expectedrevenueid`),
  UNIQUE KEY `CampaignExpRev_UK01` (`expectedrevenue`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `tmp_read_group_rel_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `relatedtabid` int(11) NOT NULL,
  `sharedgroupid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`tabid`,`relatedtabid`,`sharedgroupid`),
  KEY `tmp_read_group_rel_sharing_per_UK1` (`userid`,`sharedgroupid`,`tabid`),
  KEY `fk_tmp_read_group_rel_sharing_per2` (`tabid`),
  KEY `fk_tmp_read_group_rel_sharing_per4` (`relatedtabid`),
  KEY `fk_tmp_read_group_rel_sharing_per3` (`sharedgroupid`),
  CONSTRAINT `fk_tmp_read_group_rel_sharing_per3` FOREIGN KEY (`sharedgroupid`) REFERENCES `groups` (`groupid`) ON DELETE CASCADE,
  CONSTRAINT `fk_tmp_read_group_rel_sharing_per1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tmp_read_group_rel_sharing_per2` FOREIGN KEY (`tabid`) REFERENCES `tab` (`tabid`) ON DELETE CASCADE,
  CONSTRAINT `fk_tmp_read_group_rel_sharing_per4` FOREIGN KEY (`relatedtabid`) REFERENCES `tab` (`tabid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `tmp_read_group_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `sharedgroupid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`tabid`,`sharedgroupid`),
  KEY `tmp_read_group_sharing_per_UK1` (`userid`,`sharedgroupid`),
  KEY `fk_tmp_read_group_sharing_per2` (`tabid`),
  KEY `fk_tmp_read_group_sharing_per3` (`sharedgroupid`),
  CONSTRAINT `fk_tmp_read_group_sharing_per3` FOREIGN KEY (`sharedgroupid`) REFERENCES `groups` (`groupid`) ON DELETE CASCADE,
  CONSTRAINT `fk_tmp_read_group_sharing_per1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tmp_read_group_sharing_per2` FOREIGN KEY (`tabid`) REFERENCES `tab` (`tabid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `tmp_read_user_rel_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `relatedtabid` int(11) NOT NULL,
  `shareduserid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`tabid`,`relatedtabid`,`shareduserid`),
  KEY `tmp_read_user_rel_sharing_per_UK1` (`userid`,`shareduserid`,`relatedtabid`),
  KEY `fk_tmp_read_user_rel_sharing_per2` (`tabid`),
  KEY `fk_tmp_read_user_rel_sharing_per4` (`relatedtabid`),
  KEY `fk_tmp_read_user_rel_sharing_per3` (`shareduserid`),
  CONSTRAINT `fk_tmp_read_user_rel_sharing_per3` FOREIGN KEY (`shareduserid`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tmp_read_user_rel_sharing_per1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tmp_read_user_rel_sharing_per2` FOREIGN KEY (`tabid`) REFERENCES `tab` (`tabid`) ON DELETE CASCADE,
  CONSTRAINT `fk_tmp_read_user_rel_sharing_per4` FOREIGN KEY (`relatedtabid`) REFERENCES `tab` (`tabid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `tmp_read_user_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `shareduserid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`tabid`,`shareduserid`),
  KEY `tmp_read_user_sharing_per_UK1` (`userid`,`shareduserid`),
  KEY `fk_tmp_read_user_sharing_per2` (`tabid`),
  KEY `fk_tmp_read_user_sharing_per3` (`shareduserid`),
  CONSTRAINT `fk_tmp_read_user_sharing_per3` FOREIGN KEY (`shareduserid`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tmp_read_user_sharing_per1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tmp_read_user_sharing_per2` FOREIGN KEY (`tabid`) REFERENCES `tab` (`tabid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `tmp_write_group_rel_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `relatedtabid` int(11) NOT NULL,
  `sharedgroupid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`tabid`,`relatedtabid`,`sharedgroupid`),
  KEY `tmp_write_group_rel_sharing_per_UK1` (`userid`,`sharedgroupid`,`tabid`),
  KEY `fk_tmp_write_group_rel_sharing_per2` (`tabid`),
  KEY `fk_tmp_write_group_rel_sharing_per4` (`relatedtabid`),
  KEY `fk_tmp_write_group_rel_sharing_per3` (`sharedgroupid`),
  CONSTRAINT `fk_tmp_write_group_rel_sharing_per3` FOREIGN KEY (`sharedgroupid`) REFERENCES `groups` (`groupid`) ON DELETE CASCADE,
  CONSTRAINT `fk_tmp_write_group_rel_sharing_per1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tmp_write_group_rel_sharing_per2` FOREIGN KEY (`tabid`) REFERENCES `tab` (`tabid`) ON DELETE CASCADE,
  CONSTRAINT `fk_tmp_write_group_rel_sharing_per4` FOREIGN KEY (`relatedtabid`) REFERENCES `tab` (`tabid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `tmp_write_group_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `sharedgroupid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`tabid`,`sharedgroupid`),
  KEY `tmp_write_group_sharing_per_UK1` (`userid`,`sharedgroupid`),
  KEY `fk_tmp_write_group_sharing_per2` (`tabid`),
  KEY `fk_tmp_write_group_sharing_per3` (`sharedgroupid`),
  CONSTRAINT `fk_tmp_write_group_sharing_per3` FOREIGN KEY (`sharedgroupid`) REFERENCES `groups` (`groupid`) ON DELETE CASCADE,
  CONSTRAINT `fk_tmp_write_group_sharing_per1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tmp_write_group_sharing_per2` FOREIGN KEY (`tabid`) REFERENCES `tab` (`tabid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `tmp_write_user_rel_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `relatedtabid` int(11) NOT NULL,
  `shareduserid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`tabid`,`relatedtabid`,`shareduserid`),
  KEY `tmp_write_user_rel_sharing_per_UK1` (`userid`,`shareduserid`,`tabid`),
  KEY `fk_tmp_write_user_rel_sharing_per2` (`tabid`),
  KEY `fk_tmp_write_user_rel_sharing_per4` (`relatedtabid`),
  KEY `fk_tmp_write_user_rel_sharing_per3` (`shareduserid`),
  CONSTRAINT `fk_tmp_write_user_rel_sharing_per3` FOREIGN KEY (`shareduserid`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tmp_write_user_rel_sharing_per1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tmp_write_user_rel_sharing_per2` FOREIGN KEY (`tabid`) REFERENCES `tab` (`tabid`) ON DELETE CASCADE,
  CONSTRAINT `fk_tmp_write_user_rel_sharing_per4` FOREIGN KEY (`relatedtabid`) REFERENCES `tab` (`tabid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `tmp_write_user_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `shareduserid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`tabid`,`shareduserid`),
  KEY `tmp_write_user_sharing_per_UK1` (`userid`,`shareduserid`),
  KEY `fk_tmp_write_user_sharing_per2` (`tabid`),
  KEY `fk_tmp_write_user_sharing_per3` (`shareduserid`),
  CONSTRAINT `fk_tmp_write_user_sharing_per3` FOREIGN KEY (`shareduserid`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tmp_write_user_sharing_per1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tmp_write_user_sharing_per2` FOREIGN KEY (`tabid`) REFERENCES `tab` (`tabid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"ALTER TABLE `account` MODIFY COLUMN `website` VARCHAR(100) COLLATE latin1_swedish_ci DEFAULT NULL",
"ALTER TABLE `account` MODIFY COLUMN `emailoptout` VARCHAR(3) COLLATE latin1_swedish_ci DEFAULT '0'",
"ALTER TABLE `accountgrouprelation` MODIFY COLUMN `accountid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `actionmapping` MODIFY COLUMN `actionid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `activity` MODIFY COLUMN `date_start` DATE NOT NULL UNIQUE",
"ALTER TABLE `activity` MODIFY COLUMN `sendnotification` VARCHAR(3) COLLATE latin1_swedish_ci NOT NULL DEFAULT '0'",
"ALTER TABLE `activity` MODIFY COLUMN `duration_hours` VARCHAR(2) COLLATE latin1_swedish_ci DEFAULT NULL",
"ALTER TABLE `activity` MODIFY COLUMN `duration_minutes` VARCHAR(2) COLLATE latin1_swedish_ci DEFAULT NULL",
"ALTER TABLE `activity` MODIFY COLUMN `notime` VARCHAR(3) COLLATE latin1_swedish_ci NOT NULL DEFAULT '0'",
"ALTER TABLE `activity_reminder` MODIFY COLUMN `activity_id` INTEGER(11) NOT NULL PRIMARY KEY",
"ALTER TABLE `activity_reminder` MODIFY COLUMN `reminder_time` INTEGER(11) NOT NULL",
"ALTER TABLE `activity_reminder` MODIFY COLUMN `reminder_sent` INTEGER(2) NOT NULL",
"ALTER TABLE `activity_reminder` MODIFY COLUMN `recurringid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `activitygrouprelation` MODIFY COLUMN `activityid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `announcement` MODIFY COLUMN `creatorid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `attachments` MODIFY COLUMN `attachmentsid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `blocks` MODIFY COLUMN `blockid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `blocks` MODIFY COLUMN `tabid` INTEGER(19) NOT NULL UNIQUE",
"ALTER TABLE `blocks` MODIFY COLUMN `sequence` INTEGER(10) DEFAULT NULL",
"ALTER TABLE `blocks` MODIFY COLUMN `show_title` INTEGER(2) DEFAULT NULL",
"ALTER TABLE `campaign` MODIFY COLUMN `campaignname` VARCHAR(255) COLLATE latin1_swedish_ci DEFAULT NULL UNIQUE",
"ALTER TABLE `campaign` MODIFY COLUMN `campaignstatus` VARCHAR(255) COLLATE latin1_swedish_ci DEFAULT NULL UNIQUE",
"ALTER TABLE `campaign` MODIFY COLUMN `expectedrevenue` VARCHAR(255) DEFAULT NULL",
"ALTER TABLE `campaign` MODIFY COLUMN `budgetcost` VARCHAR(255) DEFAULT NULL",
"ALTER TABLE `campaign` MODIFY COLUMN `actualcost` VARCHAR(255) DEFAULT NULL",
"ALTER TABLE `campaign` MODIFY COLUMN `expectedresponse` VARCHAR(255) DEFAULT NULL",
"ALTER TABLE `campaign` MODIFY COLUMN `campaignid` INTEGER(11) NOT NULL PRIMARY KEY",
"ALTER TABLE `chat_msg` MODIFY COLUMN `id` INTEGER(20) NOT NULL AUTO_INCREMENT PRIMARY KEY",
"ALTER TABLE `chat_msg` MODIFY COLUMN `chat_from` INTEGER(20) NOT NULL DEFAULT '0' UNIQUE",
"ALTER TABLE `chat_msg` MODIFY COLUMN `chat_to` INTEGER(20) NOT NULL DEFAULT '0' UNIQUE",
"ALTER TABLE `chat_msg` MODIFY COLUMN `born` DATETIME DEFAULT '0000-00-00 00:00:00' UNIQUE",
"ALTER TABLE `chat_pchat` MODIFY COLUMN `id` INTEGER(20) NOT NULL AUTO_INCREMENT PRIMARY KEY",
"ALTER TABLE `chat_pchat` MODIFY COLUMN `msg` INTEGER(20) DEFAULT '0'",
"ALTER TABLE `chat_pvchat` MODIFY COLUMN `id` INTEGER(20) NOT NULL AUTO_INCREMENT PRIMARY KEY",
"ALTER TABLE `chat_pvchat` MODIFY COLUMN `msg` INTEGER(20) DEFAULT '0'",
"ALTER TABLE `chat_users` MODIFY COLUMN `id` INTEGER(20) NOT NULL AUTO_INCREMENT PRIMARY KEY",
"ALTER TABLE `chat_users` MODIFY COLUMN `nick` VARCHAR(50) COLLATE latin1_swedish_ci NOT NULL UNIQUE",
"ALTER TABLE `chat_users` MODIFY COLUMN `session` VARCHAR(50) COLLATE latin1_swedish_ci NOT NULL UNIQUE",
"ALTER TABLE `chat_users` MODIFY COLUMN `ping` DATETIME DEFAULT '0000-00-00 00:00:00' UNIQUE",
"ALTER TABLE `competitor` MODIFY COLUMN `competitorid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `contactdetails` MODIFY COLUMN `donotcall` VARCHAR(3) COLLATE latin1_swedish_ci DEFAULT NULL",
"ALTER TABLE `contactdetails` MODIFY COLUMN `emailoptout` VARCHAR(3) COLLATE latin1_swedish_ci DEFAULT '0'",
"ALTER TABLE `contactdetails` MODIFY COLUMN `imagename` VARCHAR(150) COLLATE latin1_swedish_ci DEFAULT NULL",
"ALTER TABLE `contactdetails` MODIFY COLUMN `reference` VARCHAR(3) COLLATE latin1_swedish_ci DEFAULT NULL",
"ALTER TABLE `contactgrouprelation` MODIFY COLUMN `contactid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `convertleadmapping` MODIFY COLUMN `leadfid` INTEGER(19) NOT NULL",
"ALTER TABLE `crmentity` MODIFY COLUMN `crmid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `crmentity` MODIFY COLUMN `description` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `crmentity` MODIFY COLUMN `createdtime` DATETIME NOT NULL",
"ALTER TABLE `crmentity` MODIFY COLUMN `modifiedtime` DATETIME NOT NULL",
"ALTER TABLE `currency_info` MODIFY COLUMN `currency_name` VARCHAR(100) COLLATE latin1_swedish_ci DEFAULT NULL",
"ALTER TABLE `currency_info` ADD COLUMN `id` INTEGER(11) NOT NULL AUTO_INCREMENT PRIMARY KEY",
"ALTER TABLE `currency_info` ADD COLUMN `conversion_rate` DECIMAL(5,3) DEFAULT NULL",
"ALTER TABLE `currency_info` ADD COLUMN `currency_status` VARCHAR(25) COLLATE latin1_swedish_ci DEFAULT NULL",
"ALTER TABLE `currency_info` ADD COLUMN `defaultid` VARCHAR(10) COLLATE latin1_swedish_ci NOT NULL DEFAULT '0'",
"ALTER TABLE `customaction` MODIFY COLUMN `cvid` INTEGER(19) NOT NULL UNIQUE",
"ALTER TABLE `customaction` MODIFY COLUMN `content` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `customerdetails` MODIFY COLUMN `customerid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `customerdetails` MODIFY COLUMN `portal` VARCHAR(3) COLLATE latin1_swedish_ci DEFAULT NULL",
"ALTER TABLE `customview` MODIFY COLUMN `cvid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `customview_seq` MODIFY COLUMN `id` INTEGER(11) NOT NULL",
"ALTER TABLE `cvadvfilter` MODIFY COLUMN `cvid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `cvadvfilter` MODIFY COLUMN `columnindex` INTEGER(11) NOT NULL PRIMARY KEY",
"ALTER TABLE `cvcolumnlist` MODIFY COLUMN `cvid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `cvcolumnlist` MODIFY COLUMN `columnindex` INTEGER(11) NOT NULL PRIMARY KEY",
"ALTER TABLE `cvstdfilter` MODIFY COLUMN `cvid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `dealintimation` MODIFY COLUMN `dealprobability` DECIMAL(3,2) NOT NULL DEFAULT '0.00'",
"ALTER TABLE `def_org_field` MODIFY COLUMN `fieldid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `def_org_share` MODIFY COLUMN `tabid` INTEGER(11) NOT NULL",
"ALTER TABLE `def_org_share` MODIFY COLUMN `permission` INTEGER(19) DEFAULT NULL UNIQUE",
"ALTER TABLE `def_org_share_seq` MODIFY COLUMN `id` INTEGER(11) NOT NULL",
"ALTER TABLE `defaultcv` MODIFY COLUMN `tabid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `defaultcv` MODIFY COLUMN `query` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `emailtemplates` MODIFY COLUMN `description` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `emailtemplates` MODIFY COLUMN `body` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `emailtemplates_seq` MODIFY COLUMN `id` INTEGER(11) NOT NULL",
"ALTER TABLE `faq` MODIFY COLUMN `question` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `faq` MODIFY COLUMN `answer` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `faqcomments` MODIFY COLUMN `comments` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `faqcomments` MODIFY COLUMN `createdtime` DATETIME NOT NULL",
"ALTER TABLE `field` MODIFY COLUMN `tabid` INTEGER(19) NOT NULL UNIQUE",
"ALTER TABLE `field` MODIFY COLUMN `readonly` INTEGER(1) NOT NULL",
"ALTER TABLE `field` MODIFY COLUMN `selected` INTEGER(1) NOT NULL",
"ALTER TABLE `field` MODIFY COLUMN `block` INTEGER(19) DEFAULT NULL UNIQUE",
"ALTER TABLE `field` MODIFY COLUMN `displaytype` INTEGER(19) DEFAULT NULL UNIQUE",
"ALTER TABLE `field` MODIFY COLUMN `quickcreate` INTEGER(10) NOT NULL DEFAULT '1'",
"ALTER TABLE `field_seq` MODIFY COLUMN `id` INTEGER(11) NOT NULL",
"ALTER TABLE `freetagged_objects` MODIFY COLUMN `tag_id` INTEGER(20) NOT NULL DEFAULT '0' PRIMARY KEY",
"ALTER TABLE `freetagged_objects` MODIFY COLUMN `tagger_id` INTEGER(20) NOT NULL DEFAULT '0' PRIMARY KEY",
"ALTER TABLE `freetagged_objects` MODIFY COLUMN `object_id` INTEGER(20) NOT NULL DEFAULT '0' PRIMARY KEY",
"ALTER TABLE `freetags` MODIFY COLUMN `id` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `group2grouprel` MODIFY COLUMN `groupid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `group2grouprel` MODIFY COLUMN `containsgroupid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `group2role` MODIFY COLUMN `groupid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `group2rs` MODIFY COLUMN `groupid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `groups` MODIFY COLUMN `groupid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `groups` MODIFY COLUMN `description` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `import_maps` MODIFY COLUMN `is_published` VARCHAR(3) COLLATE latin1_swedish_ci NOT NULL DEFAULT 'no'",
"ALTER TABLE `inventory_tandc` MODIFY COLUMN `id` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `inventory_tandc` MODIFY COLUMN `tandc` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `inventory_tandc_seq` MODIFY COLUMN `id` INTEGER(11) NOT NULL",
"ALTER TABLE `inventorynotification` MODIFY COLUMN `notificationbody` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `inventorynotification_seq` MODIFY COLUMN `id` INTEGER(11) NOT NULL",
"ALTER TABLE `invoice` MODIFY COLUMN `salesorderid` INTEGER(19) DEFAULT NULL UNIQUE",
"ALTER TABLE `invoice` MODIFY COLUMN `terms_conditions` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `invoicegrouprelation` MODIFY COLUMN `invoiceid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `invoiceproductrel` MODIFY COLUMN `invoiceid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `invoiceproductrel` MODIFY COLUMN `productid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `lar` MODIFY COLUMN `createdon` DATE NOT NULL",
"ALTER TABLE `leaddetails` MODIFY COLUMN `leadid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `leaddetails` MODIFY COLUMN `comments` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `leadgrouprelation` MODIFY COLUMN `leadid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `mail_accounts` MODIFY COLUMN `account_id` INTEGER(11) NOT NULL PRIMARY KEY",
"ALTER TABLE `mail_accounts` MODIFY COLUMN `user_id` INTEGER(11) NOT NULL",
"ALTER TABLE `mail_accounts` ADD COLUMN `box_refresh` INTEGER(10) DEFAULT NULL",
"ALTER TABLE `mail_accounts` ADD COLUMN `mails_per_page` INTEGER(10) DEFAULT NULL",
"ALTER TABLE `mail_accounts` ADD COLUMN `ssltype` VARCHAR(50) COLLATE latin1_swedish_ci DEFAULT NULL",
"ALTER TABLE `mail_accounts` ADD COLUMN `sslmeth` VARCHAR(50) COLLATE latin1_swedish_ci DEFAULT NULL",
"ALTER TABLE `mail_accounts` ADD COLUMN `showbody` VARCHAR(10) COLLATE latin1_swedish_ci DEFAULT NULL",
"ALTER TABLE `notes` MODIFY COLUMN `contact_id` INTEGER(19) DEFAULT '0'",
"ALTER TABLE `notes` MODIFY COLUMN `notecontent` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `notificationscheduler` MODIFY COLUMN `notificationbody` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `notificationscheduler_seq` MODIFY COLUMN `id` INTEGER(11) NOT NULL",
"ALTER TABLE `opportunitystage` MODIFY COLUMN `probability` DECIMAL(3,2) DEFAULT '0.00'",
"ALTER TABLE `org_share_action2tab` MODIFY COLUMN `share_action_id` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `org_share_action2tab` MODIFY COLUMN `tabid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `org_share_action_mapping` MODIFY COLUMN `share_action_id` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `organizationdetails` MODIFY COLUMN `website` VARCHAR(100) COLLATE latin1_swedish_ci DEFAULT NULL",
"ALTER TABLE `organizationdetails` MODIFY COLUMN `logo` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `ownernotify` MODIFY COLUMN `crmid` INTEGER(19) DEFAULT NULL UNIQUE",
"ALTER TABLE `parenttab` MODIFY COLUMN `parenttabid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `parenttab` MODIFY COLUMN `sequence` INTEGER(10) NOT NULL",
"ALTER TABLE `parenttabrel` MODIFY COLUMN `parenttabid` INTEGER(3) NOT NULL",
"ALTER TABLE `parenttabrel` MODIFY COLUMN `tabid` INTEGER(3) NOT NULL UNIQUE",
"ALTER TABLE `parenttabrel` MODIFY COLUMN `sequence` INTEGER(3) NOT NULL",
"ALTER TABLE `pogrouprelation` MODIFY COLUMN `purchaseorderid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `poproductrel` MODIFY COLUMN `purchaseorderid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `poproductrel` MODIFY COLUMN `productid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `portal` MODIFY COLUMN `portalid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `portal` MODIFY COLUMN `portalname` VARCHAR(200) COLLATE latin1_swedish_ci NOT NULL UNIQUE",
"ALTER TABLE `portal` MODIFY COLUMN `sequence` INTEGER(3) NOT NULL",
"ALTER TABLE `portalinfo` MODIFY COLUMN `id` INTEGER(11) NOT NULL PRIMARY KEY",
"ALTER TABLE `portalinfo` MODIFY COLUMN `last_login_time` DATETIME NOT NULL",
"ALTER TABLE `portalinfo` MODIFY COLUMN `login_time` DATETIME NOT NULL",
"ALTER TABLE `portalinfo` MODIFY COLUMN `logout_time` DATETIME NOT NULL",
"ALTER TABLE `potcompetitorrel` MODIFY COLUMN `potentialid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `potcompetitorrel` MODIFY COLUMN `competitorid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `potential` MODIFY COLUMN `amount` DECIMAL(10,2) DEFAULT '0.00'",
"ALTER TABLE `potential` MODIFY COLUMN `description` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `potentialgrouprelation` MODIFY COLUMN `potentialid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `potstagehistory` MODIFY COLUMN `potentialid` INTEGER(19) NOT NULL UNIQUE",
"ALTER TABLE `potstagehistory` MODIFY COLUMN `probability` DECIMAL(3,2) DEFAULT NULL",
"ALTER TABLE `potstagehistory` MODIFY COLUMN `lastmodified` DATETIME NOT NULL",
"ALTER TABLE `pricebook` MODIFY COLUMN `description` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `pricebookproductrel` MODIFY COLUMN `pricebookid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `pricebookproductrel` MODIFY COLUMN `productid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `productcollaterals` MODIFY COLUMN `productid` INTEGER(11) NOT NULL PRIMARY KEY",
"ALTER TABLE `productcollaterals` MODIFY COLUMN `description` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `products` MODIFY COLUMN `productid` INTEGER(11) NOT NULL PRIMARY KEY",
"ALTER TABLE `products` MODIFY COLUMN `product_description` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `products` MODIFY COLUMN `commissionrate` DECIMAL(3,3) DEFAULT NULL",
"ALTER TABLE `profile2field` MODIFY COLUMN `profileid` INTEGER(11) NOT NULL PRIMARY KEY",
"ALTER TABLE `profile2field` MODIFY COLUMN `fieldid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `profile2globalpermissions` MODIFY COLUMN `profileid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `profile2globalpermissions` MODIFY COLUMN `globalactionid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `profile2standardpermissions` MODIFY COLUMN `profileid` INTEGER(11) NOT NULL PRIMARY KEY",
"ALTER TABLE `profile2standardpermissions` MODIFY COLUMN `tabid` INTEGER(10) NOT NULL PRIMARY KEY",
"ALTER TABLE `profile2standardpermissions` MODIFY COLUMN `Operation` INTEGER(10) NOT NULL PRIMARY KEY",
"ALTER TABLE `profile2utility` MODIFY COLUMN `profileid` INTEGER(11) NOT NULL PRIMARY KEY",
"ALTER TABLE `profile2utility` MODIFY COLUMN `tabid` INTEGER(11) NOT NULL PRIMARY KEY",
"ALTER TABLE `profile2utility` MODIFY COLUMN `activityid` INTEGER(11) NOT NULL PRIMARY KEY",
"ALTER TABLE `profile_seq` MODIFY COLUMN `id` INTEGER(11) NOT NULL",
"ALTER TABLE `purchaseorder` MODIFY COLUMN `quoteid` INTEGER(19) DEFAULT NULL UNIQUE",
"ALTER TABLE `purchaseorder` MODIFY COLUMN `vendorid` INTEGER(19) DEFAULT NULL UNIQUE",
"ALTER TABLE `purchaseorder` MODIFY COLUMN `contactid` INTEGER(19) DEFAULT NULL UNIQUE",
"ALTER TABLE `purchaseorder` MODIFY COLUMN `terms_conditions` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `quotegrouprelation` MODIFY COLUMN `quoteid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `quotes` MODIFY COLUMN `potentialid` INTEGER(19) DEFAULT NULL UNIQUE",
"ALTER TABLE `quotes` MODIFY COLUMN `quotestage` VARCHAR(200) COLLATE latin1_swedish_ci DEFAULT NULL",
"ALTER TABLE `quotes` MODIFY COLUMN `contactid` INTEGER(19) DEFAULT NULL UNIQUE",
"ALTER TABLE `quotes` MODIFY COLUMN `terms_conditions` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `quotesproductrel` MODIFY COLUMN `quoteid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `quotesproductrel` MODIFY COLUMN `productid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `recurringevents` MODIFY COLUMN `activityid` INTEGER(19) NOT NULL",
"ALTER TABLE `relatedlists` MODIFY COLUMN `relation_id` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `relatedlists_seq` MODIFY COLUMN `id` INTEGER(11) NOT NULL",
"ALTER TABLE `relcriteria` MODIFY COLUMN `queryid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `relcriteria` MODIFY COLUMN `columnindex` INTEGER(11) NOT NULL PRIMARY KEY",
"ALTER TABLE `report` MODIFY COLUMN `reportid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `report` MODIFY COLUMN `folderid` INTEGER(19) NOT NULL UNIQUE",
"ALTER TABLE `reportdatefilter` MODIFY COLUMN `datefilterid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `reportmodules` MODIFY COLUMN `reportmodulesid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `reportsortcol` MODIFY COLUMN `sortcolid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `reportsortcol` MODIFY COLUMN `reportid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `reportsummary` MODIFY COLUMN `reportsummaryid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `reportsummary` MODIFY COLUMN `summarytype` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `role2profile` MODIFY COLUMN `profileid` INTEGER(11) NOT NULL PRIMARY KEY",
"ALTER TABLE `role_seq` MODIFY COLUMN `id` INTEGER(11) NOT NULL",
"ALTER TABLE `rss` MODIFY COLUMN `rssid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `salesorder` MODIFY COLUMN `contactid` INTEGER(19) DEFAULT NULL UNIQUE",
"ALTER TABLE `salesorder` MODIFY COLUMN `vendorid` INTEGER(19) DEFAULT NULL UNIQUE",
"ALTER TABLE `salesorder` MODIFY COLUMN `terms_conditions` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `seactivityrel` MODIFY COLUMN `crmid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `seactivityrel` MODIFY COLUMN `activityid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `selectcolumn` MODIFY COLUMN `queryid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `selectquery` MODIFY COLUMN `queryid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `selectquery_seq` MODIFY COLUMN `id` INTEGER(11) NOT NULL",
"ALTER TABLE `sharedcalendar` MODIFY COLUMN `userid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `sharedcalendar` MODIFY COLUMN `sharedid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `sogrouprelation` MODIFY COLUMN `salesorderid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `soproductrel` MODIFY COLUMN `salesorderid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `soproductrel` MODIFY COLUMN `productid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `systems` MODIFY COLUMN `id` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `systems` MODIFY COLUMN `server` VARCHAR(30) COLLATE latin1_swedish_ci DEFAULT NULL",
"ALTER TABLE `systems` MODIFY COLUMN `server_username` VARCHAR(30) COLLATE latin1_swedish_ci DEFAULT NULL",
"ALTER TABLE `systems` MODIFY COLUMN `server_password` VARCHAR(30) COLLATE latin1_swedish_ci DEFAULT NULL",
"ALTER TABLE `ticketcomments` MODIFY COLUMN `comments` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `ticketcomments` MODIFY COLUMN `createdtime` DATETIME NOT NULL",
"ALTER TABLE `ticketgrouprelation` MODIFY COLUMN `ticketid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `troubletickets` MODIFY COLUMN `ticketid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `troubletickets` MODIFY COLUMN `description` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `troubletickets` MODIFY COLUMN `solution` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `troubletickets` MODIFY COLUMN `update_log` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `user2role` MODIFY COLUMN `userid` INTEGER(11) NOT NULL PRIMARY KEY",
"ALTER TABLE `user2role` MODIFY COLUMN `roleid` VARCHAR(255) COLLATE latin1_swedish_ci NOT NULL UNIQUE",
"ALTER TABLE `users` MODIFY COLUMN `is_admin` VARCHAR(3) COLLATE latin1_swedish_ci DEFAULT '0'",
"ALTER TABLE `users` MODIFY COLUMN `description` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `users` MODIFY COLUMN `user_preferences` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `users` MODIFY COLUMN `homeorder` VARCHAR(255) COLLATE latin1_swedish_ci DEFAULT 'ALVT,PLVT,QLTQ,CVLVT,HLT,OLV,GRT,OLTSO,ILTI,MNL'",
"ALTER TABLE `users` ADD COLUMN `currency_id` INTEGER(19) NOT NULL DEFAULT '1'",
"ALTER TABLE `users` ADD COLUMN `defhomeview` VARCHAR(100) COLLATE latin1_swedish_ci DEFAULT NULL",
"ALTER TABLE `users2group` MODIFY COLUMN `groupid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `users2group` MODIFY COLUMN `userid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `users_seq` MODIFY COLUMN `id` INTEGER(11) NOT NULL",
"ALTER TABLE `vendor` MODIFY COLUMN `street` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `vendor` MODIFY COLUMN `description` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `wordtemplates` MODIFY COLUMN `templateid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `wordtemplates` MODIFY COLUMN `description` TEXT COLLATE latin1_swedish_ci",
"ALTER TABLE `accountgrouprelation` ADD KEY `accountgrouprelation_IDX1` (`groupname`)",
"ALTER TABLE `activity` ADD KEY `status1` (`status`, `eventstatus`)",
"ALTER TABLE `attachments` ADD KEY `attachmentsid1` (`attachmentsid`)",
"ALTER TABLE `blocks` ADD KEY `block_tabid` (`tabid`)",
"ALTER TABLE `campaign` DROP PRIMARY KEY",
"ALTER TABLE `campaign` ADD PRIMARY KEY (`campaignid`)",
"ALTER TABLE `campaign` ADD KEY `idx_campaignstatus` (`campaignstatus`)",
"ALTER TABLE `campaignleadrel` DROP INDEX CampaignLeadRel_IDX1",
"ALTER TABLE `campaignleadrel` ADD INDEX `CampaignLeadRel_IDX1` (`leadid`, `campaignid`)",
"ALTER TABLE `carrier` ADD UNIQUE KEY `carrier_UK01` (`carrier`)",
"ALTER TABLE `chat_msg` ADD KEY `chat_msg_IDX0` (`chat_from`)",
"ALTER TABLE `chat_msg` ADD KEY `chat_msg_IDX1` (`chat_to`)",
"ALTER TABLE `chat_msg` ADD KEY `chat_msg_IDX2` (`born`)",
"ALTER TABLE `chat_pchat` ADD UNIQUE KEY `chat_pchat_UK0` (`msg`)",
"ALTER TABLE `chat_pvchat` ADD UNIQUE KEY `chat_pvchat_UK0` (`msg`)",
"ALTER TABLE `chat_users` ADD KEY `chat_users_IDX0` (`nick`)",
"ALTER TABLE `chat_users` ADD KEY `chat_users_IDX1` (`session`)",
"ALTER TABLE `chat_users` ADD KEY `chat_users_IDX2` (`ping`)",
"ALTER TABLE `contactgrouprelation` ADD KEY `contactgrouprelation_IDX1` (`groupname`)",
"ALTER TABLE `currency_info` DROP PRIMARY KEY",
"ALTER TABLE `currency_info` ADD PRIMARY KEY ()",
"ALTER TABLE `def_org_field` ADD KEY `tabid4` (`tabid`)",
"ALTER TABLE `def_org_share` ADD KEY `fk_def_org_share23` (`permission`)",
"ALTER TABLE `field` ADD KEY `tabid2` (`tabid`)",
"ALTER TABLE `field` ADD KEY `blockid` (`block`)",
"ALTER TABLE `field` ADD KEY `displaytypeid` (`displaytype`)",
"ALTER TABLE `freetagged_objects` DROP INDEX tag_id_index",
"ALTER TABLE `freetagged_objects` ADD INDEX `tag_id_index` (`tag_id`, `tagger_id`, `object_id`)",
"ALTER TABLE `group2grouprel` ADD KEY `fk_group2grouprel2` (`containsgroupid`)",
"ALTER TABLE `group2role` ADD KEY `fk_group2role2` (`roleid`)",
"ALTER TABLE `group2rs` ADD KEY `fk_group2rs2` (`roleandsubid`)",
"ALTER TABLE `groups` ADD KEY `idx_groups_123group` (`groupname`)",
"ALTER TABLE `invoice` ADD KEY `SoPo_IDX` (`invoiceid`)",
"ALTER TABLE `invoice` ADD KEY `fk_Invoice2` (`salesorderid`)",
"ALTER TABLE `invoicegrouprelation` ADD KEY `invoicegrouprelation_IDX1` (`groupname`, `invoiceid`)",
"ALTER TABLE `leadgrouprelation` ADD KEY `leadgrouprelation_IDX0` (`leadid`)",
"ALTER TABLE `moduleowners` ADD KEY `moduleowners_UK11` (`tabid`, `user_id`)",
"ALTER TABLE `org_share_action2tab` ADD KEY `fk_org_share_action2tab12345` (`tabid`)",
"ALTER TABLE `ownernotify` ADD KEY `ownernotify_UK1` (`crmid`, `flag`)",
"ALTER TABLE `parenttab` ADD KEY `parenttab_UK1` (`parenttabid`, `parenttab_label`, `visible`)",
"ALTER TABLE `parenttabrel` ADD KEY `parenttabrelUK01` (`tabid`, `parenttabid`)",
"ALTER TABLE `pogrouprelation` ADD KEY `pogrouprelation_IDX1` (`groupname`, `purchaseorderid`)",
"ALTER TABLE `portal` ADD KEY `portal_UK01` (`portalname`)",
"ALTER TABLE `potential` ADD KEY `potentialid1` (`potentialid`)",
"ALTER TABLE `potentialgrouprelation` ADD KEY `potentialgrouprelation_IDX1` (`groupname`)",
"ALTER TABLE `potstagehistory` DROP INDEX PotStageHistory_IDX1",
"ALTER TABLE `potstagehistory` ADD INDEX `PotStageHistory_IDX1` (`historyid`)",
"ALTER TABLE `potstagehistory` ADD KEY `fk_PotStageHistory` (`potentialid`)",
"ALTER TABLE `profile2field` ADD KEY `tabid3` (`tabid`, `profileid`)",
"ALTER TABLE `profile2globalpermissions` ADD KEY `idx_profile2globalpermissions` (`profileid`, `globalactionid`)",
"ALTER TABLE `profile2standardpermissions` ADD KEY `idx_prof2stad` (`profileid`, `tabid`, `Operation`)",
"ALTER TABLE `profile2tab` ADD KEY `idx_profile2tab1` (`profileid`, `tabid`)",
"ALTER TABLE `profile2utility` ADD KEY `idx_prof2utility` (`profileid`, `tabid`, `activityid`)",
"ALTER TABLE `purchaseorder` ADD KEY `PO_Vend_IDX` (`vendorid`)",
"ALTER TABLE `purchaseorder` ADD KEY `PO_Quote_IDX` (`quoteid`)",
"ALTER TABLE `purchaseorder` ADD KEY `PO_Contact_IDX` (`contactid`)",
"ALTER TABLE `quotegrouprelation` ADD KEY `quotegrouprelation_IDX1` (`groupname`)",
"ALTER TABLE `quotes` DROP INDEX quotestage",
"ALTER TABLE `quotes` ADD INDEX `quotestage` (`quoteid`)",
"ALTER TABLE `quotes` ADD KEY `potentialid2` (`potentialid`)",
"ALTER TABLE `quotes` ADD KEY `contactid` (`contactid`)",
"ALTER TABLE `recurringtype` ADD UNIQUE KEY `RecurringEvent_UK0` (`recurringtype`)",
"ALTER TABLE `reportsortcol` ADD KEY `FK1_reportsortcol` (`reportid`)",
"ALTER TABLE `role2profile` ADD KEY `idx_role2profileid1` (`roleid`, `profileid`)",
"ALTER TABLE `salesorder` ADD KEY `SoVend_IDX` (`vendorid`)",
"ALTER TABLE `salesorder` ADD KEY `SoContact_IDX` (`contactid`)",
"ALTER TABLE `seattachmentsrel` ADD KEY `attachmentsid2` (`attachmentsid`, `crmid`)",
"ALTER TABLE `selectquery` ADD KEY `selectquery_IDX0` (`queryid`)",
"ALTER TABLE `sogrouprelation` ADD KEY `sogrouprelation_IDX1` (`groupname`)",
"ALTER TABLE `tab` ADD KEY `tabid1` (`tabid`)",
"ALTER TABLE `taxclass` ADD UNIQUE KEY `carrier_UK02` (`taxclass`)",
"ALTER TABLE `troubletickets` ADD KEY `status2` (`status`)",
"ALTER TABLE `users2group` ADD KEY `idx_users2group` (`groupid`, `userid`)",
"ALTER TABLE `users2group` ADD KEY `fk_users2group2` (`userid`)",
"ALTER TABLE `accountgrouprelation` ADD CONSTRAINT `fk_accountgrouprelation2` FOREIGN KEY (`groupname`) REFERENCES `groups` (`groupname`) ON DELETE CASCADE",
"ALTER TABLE `accountgrouprelation` ADD CONSTRAINT `fk_accountgrouprelation123` FOREIGN KEY (`accountid`) REFERENCES `account` (`accountid`) ON DELETE CASCADE",
"ALTER TABLE `campaigncontrel` ADD CONSTRAINT `fk_CampaignContRel1` FOREIGN KEY (`campaignid`) REFERENCES `campaign` (`campaignid`) ON DELETE CASCADE",
"ALTER TABLE `campaignleadrel` ADD CONSTRAINT `fk_CampaignLeadRel1234` FOREIGN KEY (`campaignid`) REFERENCES `campaign` (`campaignid`) ON DELETE CASCADE",
"ALTER TABLE `contactgrouprelation` ADD CONSTRAINT `fk_contactgrouprelation2` FOREIGN KEY (`groupname`) REFERENCES `groups` (`groupname`) ON DELETE CASCADE",
"ALTER TABLE `contactgrouprelation` ADD CONSTRAINT `fk_contactgrouprelation123` FOREIGN KEY (`contactid`) REFERENCES `contactdetails` (`contactid`) ON DELETE CASCADE",
"ALTER TABLE `customaction` ADD CONSTRAINT `customaction_FK1` FOREIGN KEY (`cvid`) REFERENCES `customview` (`cvid`) ON DELETE CASCADE",
"ALTER TABLE `invoice` ADD CONSTRAINT `fk_Invoice2` FOREIGN KEY (`salesorderid`) REFERENCES `salesorder` (`salesorderid`) ON DELETE CASCADE",
"ALTER TABLE `invoicegrouprelation` ADD CONSTRAINT `fk_invoicegrouprelation234` FOREIGN KEY (`invoiceid`) REFERENCES `invoice` (`invoiceid`) ON DELETE CASCADE",
"ALTER TABLE `invoicegrouprelation` ADD CONSTRAINT `fk_invoicegrouprelation2` FOREIGN KEY (`groupname`) REFERENCES `groups` (`groupname`) ON DELETE CASCADE",
"ALTER TABLE `org_share_action2tab` ADD CONSTRAINT `fk_org_share_action2tab12345` FOREIGN KEY (`tabid`) REFERENCES `tab` (`tabid`) ON DELETE CASCADE",
"ALTER TABLE `pogrouprelation` ADD CONSTRAINT `fk_pogrouprelation2` FOREIGN KEY (`groupname`) REFERENCES `groups` (`groupname`) ON DELETE CASCADE",
"ALTER TABLE `pogrouprelation` ADD CONSTRAINT `fk_pogrouprelation123` FOREIGN KEY (`purchaseorderid`) REFERENCES `purchaseorder` (`purchaseorderid`) ON DELETE CASCADE",
"ALTER TABLE `potentialgrouprelation` ADD CONSTRAINT `fk_potentialgrouprelation2` FOREIGN KEY (`groupname`) REFERENCES `groups` (`groupname`) ON DELETE CASCADE",
"ALTER TABLE `potentialgrouprelation` ADD CONSTRAINT `fk_potentialgrouprelation67` FOREIGN KEY (`potentialid`) REFERENCES `potential` (`potentialid`) ON DELETE CASCADE",
"ALTER TABLE `profile2globalpermissions` ADD CONSTRAINT `fk_profile2globalpermissions57` FOREIGN KEY (`profileid`) REFERENCES `profile` (`profileid`) ON DELETE CASCADE",
"ALTER TABLE `purchaseorder` ADD CONSTRAINT `fk_PO3` FOREIGN KEY (`contactid`) REFERENCES `contactdetails` (`contactid`) ON DELETE CASCADE",
"ALTER TABLE `purchaseorder` ADD CONSTRAINT `fk_PO2` FOREIGN KEY (`vendorid`) REFERENCES `vendor` (`vendorid`) ON DELETE CASCADE",
"ALTER TABLE `purchaseorder` ADD CONSTRAINT `fk_PO2345` FOREIGN KEY (`quoteid`) REFERENCES `quotes` (`quoteid`) ON DELETE CASCADE",
"ALTER TABLE `quotegrouprelation` ADD CONSTRAINT `fk_quotegrouprelation2` FOREIGN KEY (`groupname`) REFERENCES `groups` (`groupname`) ON DELETE CASCADE",
"ALTER TABLE `quotegrouprelation` ADD CONSTRAINT `fk_quotegrouprelation132` FOREIGN KEY (`quoteid`) REFERENCES `quotes` (`quoteid`) ON DELETE CASCADE",
"ALTER TABLE `quotes` ADD CONSTRAINT `fk_Quotes3` FOREIGN KEY (`contactid`) REFERENCES `contactdetails` (`contactid`) ON DELETE CASCADE",
"ALTER TABLE `quotes` ADD CONSTRAINT `fk_Quotes2` FOREIGN KEY (`potentialid`) REFERENCES `potential` (`potentialid`) ON DELETE CASCADE",
"ALTER TABLE `salesorder` ADD CONSTRAINT `fk_SO4` FOREIGN KEY (`contactid`) REFERENCES `contactdetails` (`contactid`) ON DELETE CASCADE",
"ALTER TABLE `salesorder` ADD CONSTRAINT `fk_SO2` FOREIGN KEY (`vendorid`) REFERENCES `vendor` (`vendorid`) ON DELETE CASCADE",
"ALTER TABLE `sogrouprelation` ADD CONSTRAINT `fk_sogrouprelation2` FOREIGN KEY (`groupname`) REFERENCES `groups` (`groupname`) ON DELETE CASCADE",
"ALTER TABLE `sogrouprelation` ADD CONSTRAINT `fk_sogrouprelation78` FOREIGN KEY (`salesorderid`) REFERENCES `salesorder` (`salesorderid`) ON DELETE CASCADE",
"ALTER TABLE `vendorcontactrel` ADD CONSTRAINT `fk_VendorContactRel45` FOREIGN KEY (`vendorid`) REFERENCES `vendor` (`vendorid`) ON DELETE CASCADE"
		    );
foreach($query_array as $query)
{
	Execute($query);
}





$conn->println("Database Modifications for 5.0(Alpha) Dev 3 ==> 5.0 Alpha ends here.");
echo "<br><br><b>Database Modifications for 5.0(Alpha) Dev3 ==> 5.0 Alpha ends here.....</b><br>";

$conn->println("Database Modifications for 4.2 Patch2 ==> 5.0(Alpha) Dev 3 ends here.");
echo "<br><br><b>Database Modifications for 4.2 Patch2 ==> 5.0(Alpha) Ends here.....</b><br>";



function Execute($query)
{
	global $conn;
	$status = $conn->query($query);
	if(is_object($status))
	{
		echo '<br>'.$status.' ==> '.$query;
	}
	else
	{
		echo '<br><br>'.$status.' ======> '.$query;
	}
}
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
