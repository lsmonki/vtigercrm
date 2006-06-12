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
global $query_count, $success_query_count, $failure_query_count;
global $success_query_array, $failure_query_array;

$conn->println("Database Modifications for 4.2 Patch2 ==> 5.0(Alpha) Dev 3 Starts here.");


/****************** 5.0(Alpha) dev version 1 Database changes -- Starts*********************/


//Added the vtiger_announcement vtiger_table creation to avoid the error
$ann_query = "CREATE TABLE `announcement` (
	  `creatorid` int(19) NOT NULL,
	    `announcement` text,
	      `title` varchar(255) default NULL,
	        `time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
		  PRIMARY KEY  (`creatorid`),
		    KEY `announcement_UK01` (`creatorid`)
	    ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
Execute($ann_query);

//Added Primay Keys for the left out vtiger_tables
$alter_array1 = Array(
		"alter vtiger_table vtiger_activity_reminder ADD PRIMARY KEY (activity_id,recurringid)",
		"alter vtiger_table vtiger_activitygrouprelation ADD PRIMARY KEY (activityid)",
		"alter vtiger_table vtiger_cvadvfilter ADD PRIMARY KEY (cvid,columnindex)",
		"alter vtiger_table vtiger_cvcolumnlist ADD PRIMARY KEY (cvid,columnindex)",
		"alter vtiger_table vtiger_cvstdfilter ADD PRIMARY KEY (cvid)",
		"alter vtiger_table vtiger_def_org_field ADD PRIMARY KEY (fieldid)",
		"alter vtiger_table vtiger_leadgrouprelation ADD PRIMARY KEY (leadid)",
		"alter vtiger_table vtiger_leadgrouprelation drop key vtiger_leadgrouprelation_IDX0",
		"alter vtiger_table vtiger_organizationdetails ADD PRIMARY KEY (organizationame)",
		"alter vtiger_table vtiger_profile2field ADD PRIMARY KEY (profileid,fieldid)",
		"alter vtiger_table vtiger_profile2standardpermissions ADD PRIMARY KEY (profileid,tabid,Operation)",
		"alter vtiger_table vtiger_profile2standardpermissions drop index idx_prof2stad",
		"alter vtiger_table vtiger_profile2utility ADD PRIMARY KEY (profileid,tabid,activityid)",
		"alter vtiger_table vtiger_profile2utility drop index idx_prof2utility",
		"alter vtiger_table vtiger_relcriteria ADD PRIMARY KEY (queryid,columnindex)",
		"alter vtiger_table vtiger_reportdatefilter ADD PRIMARY KEY (datefilterid)",
		"alter vtiger_table vtiger_reportdatefilter DROP INDEX vtiger_reportdatefilter_IDX0",
		"alter vtiger_table vtiger_reportsortcol ADD PRIMARY KEY (sortcolid,reportid)",
		"alter vtiger_table vtiger_reportsummary ADD PRIMARY KEY (reportsummaryid,summarytype,columnname)",
		"drop vtiger_table vtiger_role2action",
		"drop vtiger_table vtiger_role2tab",
		"alter vtiger_table vtiger_selectcolumn ADD PRIMARY KEY (queryid,columnindex)",
		"alter vtiger_table vtiger_ticketgrouprelation ADD PRIMARY KEY (ticketid)",
		"alter vtiger_table vtiger_ticketstracktime ADD PRIMARY KEY (ticket_id)",
		"alter vtiger_table vtiger_users2group ADD PRIMARY KEY (groupname,userid)",
		"alter vtiger_table vtiger_users2group DROP INDEX idx_users2group",
		);
foreach($alter_array1 as $query)
{
	Execute($query);
}

//Tables vtiger_profile2globalpermissions, vtiger_actionmapping creation

$create_sql1 ="CREATE TABLE `profile2globalpermissions` (`profileid` int(19) NOT NULL, `globalactionid` int(19) NOT NULL, `globalactionpermission` int(19) default NULL, PRIMARY KEY  (`profileid`,`globalactionid`),  KEY `idx_profile2globalpermissions` (`profileid`,`globalactionid`)) ENGINE=InnoDB DEFAULT CHARSET=latin1";

Execute($create_sql1);

$create_sql2 = "CREATE TABLE `actionmapping` (
	`actionid` int(19) NOT NULL default '0',
	`actionname` varchar(200) NOT NULL default '',
	`securitycheck` int(19) default NULL,
PRIMARY KEY (`actionid`,`actionname`)
	) TYPE=InnoDB";
Execute($create_sql2);

//For all Profiles, insert the following entries into vtiger_profile2global permissions vtiger_table:
$sql = 'select * from vtiger_profile';
$res = $conn->query($sql);
$noofprofiles = $conn->num_rows($res);

for($i=0;$i<$noofprofiles;$i++)
{
	$profile_id = $conn->query_result($res,$i,'profileid');

	$sql1 = "insert into vtiger_profile2globalpermissions values ($profile_id,1,0)";
	$sql2 = "insert into vtiger_profile2globalpermissions values ($profile_id,2,0)";

	Execute($sql1);
	Execute($sql2);
}


//Removing entries for Dashboard and Home module from vtiger_profile2standardpermissions vtiger_table
$del_query1 = "delete from vtiger_profile2standardpermissions where vtiger_tabid in(1,3)";
Execute($del_query1);

//For all Profile do the following insert into vtiger_profile2utility vtiger_table:
$sql = 'select * from vtiger_profile';
$res = $conn->query($sql);
$noofprofiles = $conn->num_rows($res);

/* Commented by Don. Handled below
for($i=0;$i<$noofprofiles;$i++)
{
	$profile_id = $conn->query_result($res,$i,'profileid');

	$sql1 = "insert into vtiger_profile2utility values ($profile_id,4,7,0)";
	$sql2 = "insert into vtiger_profile2utility values ($profile_id,7,9,0)";

	Execute($sql1);
	Execute($sql2);
}
*/

//Insert Values into action mapping vtiger_table:
$actionmapping_array = Array(
		"insert into vtiger_actionmapping values(0,'Save',0)",
		"insert into vtiger_actionmapping values(1,'EditView',0)",
		"insert into vtiger_actionmapping values(2,'Delete',0)",
		"insert into vtiger_actionmapping values(3,'index',0)",
		"insert into vtiger_actionmapping values(4,'DetailView',0)",
		"insert into vtiger_actionmapping values(5,'Import',0)",
		"insert into vtiger_actionmapping values(6,'Export',0)",
		"insert into vtiger_actionmapping values(8,'Merge',0)",
		"insert into vtiger_actionmapping values(1,'VendorEditView',1)",
		"insert into vtiger_actionmapping values(4,'VendorDetailView',1)",
		"insert into vtiger_actionmapping values(0,'SaveVendor',1)",
		"insert into vtiger_actionmapping values(2,'DeleteVendor',1)",
		"insert into vtiger_actionmapping values(1,'PriceBookEditView',1)",
		"insert into vtiger_actionmapping values(4,'PriceBookDetailView',1)",
		"insert into vtiger_actionmapping values(0,'SavePriceBook',1)",
		"insert into vtiger_actionmapping values(2,'DeletePriceBook',1)",
		"insert into vtiger_actionmapping values(1,'SalesOrderEditView',1)",
		"insert into vtiger_actionmapping values(4,'SalesOrderDetailView',1)",
		"insert into vtiger_actionmapping values(0,'SaveSalesOrder',1)",
		"insert into vtiger_actionmapping values(2,'DeleteSalesOrder',1)",
		"insert into vtiger_actionmapping values(9,'ConvertLead',0)",
		"insert into vtiger_actionmapping values(1,'DetailViewAjax',1)",
		"insert into vtiger_actionmapping values(1,'QuickCreate',1)",
		"insert into vtiger_actionmapping values(4,'TagCloud',1)"
		);
foreach($actionmapping_array as $query)
{
	Execute($query);
}


//Added two columns in vtiger_field vtiger_table to construct the quickcreate form dynamically
$alter_array2 = Array(
		"ALTER TABLE vtiger_field ADD column quickcreate int(10) after typeofdata",
		"ALTER TABLE vtiger_field ADD column quickcreatesequence int(19) after quickcreate",
		);
foreach($alter_array2 as $query)
{
	Execute($query);
}

$update_array1 = Array(
		"UPDATE vtiger_field SET quickcreate = 1,quickcreatesequence = 0",

		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 1 WHERE vtiger_tabid = 2 and vtiger_fieldlabel = 'Potential Name'",
		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 2 WHERE vtiger_tabid = 2 and vtiger_fieldlabel = 'Account Name'",
		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 3 WHERE vtiger_tabid = 2 and vtiger_fieldlabel = 'Expected Close Date'",
		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 4 WHERE vtiger_tabid = 2 and vtiger_fieldlabel = 'Sales Stage'",
		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 5 WHERE vtiger_tabid = 2 and vtiger_fieldlabel = 'Amount'",

		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 1 WHERE vtiger_tabid = 4 and vtiger_fieldlabel = 'First Name'",
		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 2 WHERE vtiger_tabid = 4 and vtiger_fieldlabel = 'Last Name'",
		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 3 WHERE vtiger_tabid = 4 and vtiger_fieldlabel = 'Account Name'",
		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 4 WHERE vtiger_tabid = 4 and vtiger_fieldlabel = 'Office Phone'",
		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 5 WHERE vtiger_tabid = 4 and vtiger_fieldlabel = 'Email'",

		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 1 WHERE vtiger_tabid = 6 and vtiger_fieldlabel = 'Account Name'",
		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 2 WHERE vtiger_tabid = 6 and vtiger_fieldlabel = 'Phone'",
		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 3 WHERE vtiger_tabid = 6 and vtiger_fieldlabel = 'Website'",

		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 1 WHERE vtiger_tabid = 7 and vtiger_fieldlabel = 'First Name'",
		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 2 WHERE vtiger_tabid = 7 and vtiger_fieldlabel = 'Last Name'",
		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 3 WHERE vtiger_tabid = 7 and vtiger_fieldlabel = 'Company'",
		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 4 WHERE vtiger_tabid = 7 and vtiger_fieldlabel = 'Phone'",
		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 5 WHERE vtiger_tabid = 7 and vtiger_fieldlabel = 'Email'",

		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 1 WHERE vtiger_tabid = 8 and vtiger_fieldlabel = 'Subject'",

		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 1 WHERE vtiger_tabid = 9 and vtiger_fieldlabel = 'Subject'",
		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 2 WHERE vtiger_tabid = 9 and vtiger_fieldlabel = 'Start Date & Time'",

		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 1 WHERE vtiger_tabid = 10 and vtiger_fieldlabel = 'Subject'",
		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 2 WHERE vtiger_tabid = 10 and vtiger_fieldlabel = 'Date & Time Sent'",

		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 1 WHERE vtiger_tabid = 13 and vtiger_fieldlabel = 'Title'",
		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 2 WHERE vtiger_tabid = 13 and vtiger_fieldlabel = 'Description'",
		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 3 WHERE vtiger_tabid = 13 and vtiger_fieldlabel = 'Priority'",

		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 1 WHERE vtiger_tabid = 14 and vtiger_fieldlabel = 'Product Name'",
		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 2 WHERE vtiger_tabid = 14 and vtiger_fieldlabel = 'Product Code'",
		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 3 WHERE vtiger_tabid = 14 and vtiger_fieldlabel = 'Product Category'",

		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 1 WHERE vtiger_tabid = 16 and vtiger_fieldlabel = 'Subject'",
		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 2 WHERE vtiger_tabid = 16 and vtiger_fieldlabel = 'Start Date & Time'",
		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 3 WHERE vtiger_tabid = 16 and vtiger_fieldlabel = 'Activity Type'",
		"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 4 WHERE vtiger_tabid = 16 and vtiger_fieldlabel = 'Duration'",
		);
foreach($update_array1 as $query)
{
	Execute($query);
}

//Added for the "Color By User in Calendar " which has been contributed by Cesar
$alter_query1 = "ALTER TABLE `users` ADD `cal_color` VARCHAR(25) DEFAULT '#E6FAD8' AFTER `user_hash`";
Execute($alter_query1);

//code contributed by Fredy for color vtiger_priority
$newfieldid = $conn->getUniqueID("field");
$insert_query1 = "insert into vtiger_field values (16,".$newfieldid.",'priority','activity',1,15,'taskpriority','Priority',1,0,0,100,17,1,1,'V~O',1,'')";
Execute($insert_query1);

//Added on 23-12-2005 which is missed from Fredy's contribution for Color vtiger_priority
populateFieldForSecurity('16',$newfieldid);
$activity_alter_query = "alter vtiger_table vtiger_activity add column vtiger_priority varchar(150) default NULL";
Execute($activity_alter_query);

//Code contributed by Raju for better emailing 
/*
$insert_array1 = Array(
		"insert into vtiger_field values (10,".$conn->getUniqueID("field").",'crmid','seactivityrel',1,'357','parent_id','Related To',1,0,0,100,1,2,1,'I~O',1,'')",
		"insert into vtiger_field values (10,".$conn->getUniqueID("field").",'subject','activity',1,'2','subject','Subject',1,0,0,100,1,3,1,'V~M',0,1)",
		"insert into vtiger_field values (10,".$conn->getUniqueID("field").",'filename','emails',1,'61','filename','Attachment',1,0,0,100,1,4,1,'V~O',1,'')",
		"insert into vtiger_field values (10,".$conn->getUniqueID("field").",'description','emails',1,'19','description','Description',1,0,0,100,1,5,1,'V~O',1,'')",
		);
*/
//commented the above array as that queries are wrong queries -- changed on 23-12-2005
$insert_array1 = array(
			"update vtiger_field set uitype='357' where vtiger_tabid=10 and vtiger_fieldname='parent_id' and vtiger_tablename='seactivityrel'",
			"update vtiger_field set sequence=1 where vtiger_tabid=10 and vtiger_fieldname in ('parent_id','subject','filename','description')",
			"update vtiger_field set block=2 where vtiger_tabid=10 and vtiger_fieldname='parent_id'",
			"update vtiger_field set block=3 where vtiger_tabid=10 and vtiger_fieldname='subject'",
			"update vtiger_field set block=4 where vtiger_tabid=10 and vtiger_fieldname='filename'",
			"update vtiger_field set block=5 where vtiger_tabid=10 and vtiger_fieldname='description'",
		      );
foreach($insert_array1 as $query)
{
	Execute($query);
}

//code contributed by mike to rearrange the home page
$alter_query2 = "alter vtiger_table vtiger_users add column homeorder varchar(255) default 'ALVT,PLVT,QLTQ,CVLVT,HLT,OLV,GRT,OLTSO,ILTI' after date_format";
Execute($alter_query2);

//Added one column in vtiger_invoice vtiger_table to include 'Contact Name' vtiger_field in Invoice module
$alter_query3 = "ALTER TABLE vtiger_invoice ADD column contactid int(19) after customerno";
Execute($alter_query3);

$newfieldid = $conn->getUniqueID("field");
$insert_query2 = "insert into vtiger_field values (23,".$newfieldid.",'contactid','invoice',1,'57','contact_id','Contact Name',1,0,0,100,4,1,1,'I~O',1,'')";
Execute($insert_query2);
//Added on 23-12-2005 because we must populate vtiger_field entries in vtiger_profile2field and vtiger_def_org_field if we add a vtiger_field in vtiger_field vtiger_table
populateFieldForSecurity('23',$newfieldid);

//changes made to fix the bug in Address Information block of Accounts and Contacs module
$update_array2 = Array(
		"UPDATE vtiger_field SET vtiger_fieldlabel='Billing City' WHERE vtiger_tabid=6 and vtiger_tablename='accountbillads' and vtiger_fieldname='bill_city'",
		"UPDATE vtiger_field SET vtiger_fieldlabel='Billing State' WHERE vtiger_tabid=6 and vtiger_tablename='accountbillads' and vtiger_fieldname='bill_state'",
		"UPDATE vtiger_field SET vtiger_fieldlabel='Billing Code' WHERE vtiger_tabid=6 and vtiger_tablename='accountbillads' and vtiger_fieldname='bill_code'",
		"UPDATE vtiger_field SET vtiger_fieldlabel='Billing Country' WHERE vtiger_tabid=6 and vtiger_tablename='accountbillads' and vtiger_fieldname='bill_country'",

		"UPDATE vtiger_field SET vtiger_fieldlabel='Shipping City' WHERE vtiger_tabid=6 and vtiger_tablename='accountshipads' and vtiger_fieldname='ship_city'",
		"UPDATE vtiger_field SET vtiger_fieldlabel='Shipping Country' WHERE vtiger_tabid=6 and vtiger_tablename='accountshipads' and vtiger_fieldname='ship_country'",
		"UPDATE vtiger_field SET vtiger_fieldlabel='Shipping State' WHERE vtiger_tabid=6 and vtiger_tablename='accountshipads' and vtiger_fieldname='ship_state'",
		"UPDATE vtiger_field SET vtiger_fieldlabel='Shipping Code' WHERE vtiger_tabid=6 and vtiger_tablename='accountshipads' and vtiger_fieldname='ship_code'",

		"UPDATE vtiger_field SET vtiger_fieldlabel='Mailing City' WHERE vtiger_tabid=4 and vtiger_tablename='contactaddress' and vtiger_fieldname='mailingcity'",
		"UPDATE vtiger_field SET vtiger_fieldlabel='Mailing State' WHERE vtiger_tabid=4 and vtiger_tablename='contactaddress' and vtiger_fieldname='mailingstate'",
		"UPDATE vtiger_field SET vtiger_fieldlabel='Mailing Zip' WHERE vtiger_tabid=4 and vtiger_tablename='contactaddress' and vtiger_fieldname='mailingzip'",
		"UPDATE vtiger_field SET vtiger_fieldlabel='Mailing Country' WHERE vtiger_tabid=4 and vtiger_tablename='contactaddress' and vtiger_fieldname='mailingcountry'",

		"UPDATE vtiger_field SET vtiger_fieldlabel='Other City' WHERE vtiger_tabid=4 and vtiger_tablename='contactaddress' and vtiger_fieldname='othercity'",
		"UPDATE vtiger_field SET vtiger_fieldlabel='Other State' WHERE vtiger_tabid=4 and vtiger_tablename='contactaddress' and vtiger_fieldname='otherstate'",
		"UPDATE vtiger_field SET vtiger_fieldlabel='Other Zip' WHERE vtiger_tabid=4 and vtiger_tablename='contactaddress' and vtiger_fieldname='otherzip'",
		"UPDATE vtiger_field SET vtiger_fieldlabel='Other Country' WHERE vtiger_tabid=4 and vtiger_tablename='contactaddress' and vtiger_fieldname='othercountry'",
		);
foreach($update_array2 as $query)
{
	Execute($query);
}


//Added vtiger_field emailoptout in vtiger_account vtiger_table
$newfieldid = $conn->getUniqueID("field");
$insert_query3 = "insert into vtiger_field values (6,".$newfieldid.",'emailoptout','account',1,'56','emailoptout','Email Opt Out',1,0,0,100,17,1,1,'C~O',1,'')";
Execute($insert_query3);

//Added on 23-12-2005 because we must populate vtiger_field entries in vtiger_profile2field and vtiger_def_org_field if we add a vtiger_field in vtiger_field vtiger_table
populateFieldForSecurity('6',$newfieldid);

//Added on 22-12-2005
$alter_query4 = "alter vtiger_table vtiger_account add column emailoptout varchar(3) default 0";
Execute($alter_query4);

$update_array3 = Array(
		"update vtiger_field set sequence=18 where vtiger_tabid=6 and vtiger_fieldname ='assigned_user_id'",
		"update vtiger_field set sequence=19 where vtiger_tabid=6 and vtiger_fieldname ='createdtime'",
		"update vtiger_field set sequence=19 where vtiger_tabid=6 and vtiger_fieldname ='modifiedtime'",
		);
foreach($update_array3 as $query)
{
	Execute($query);
}


//create vtiger_table vtiger_moduleowners to assign the module and corresponding owners
$create_query2 = "CREATE TABLE `moduleowners` (
	  `tabid` int(19) NOT NULL default '0',
	    `user_id` varchar(11) NOT NULL,
	      PRIMARY KEY  (`tabid`),
	        KEY `moduleowners_tabid_user_id_idx` (`tabid`,`user_id`)
	) ENGINE=InnoDB";

/*
$create_query2 = "CREATE TABLE `moduleowners` 
(
 `tabid` int(19) NOT NULL default '0',
 `user_id` varchar(11) NOT NULL default '',
 PRIMARY KEY  (`tabid`),
 CONSTRAINT `fk_ModuleOwners` FOREIGN KEY (`tabid`) REFERENCES `tab` (`tabid`) ON DELETE CASCADE
) TYPE=InnoDB";
*/
Execute($create_query2);

//Populated the default entries for vtiger_moduleowners which is created newly
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
	$query = "insert into vtiger_moduleowners values(".$this->localGetTabID($mod).",1)";
	Execute($query);
}


//Changes made to include status vtiger_field in Activity Quickcreate Form
$update_array4 = Array(
		"UPDATE vtiger_field SET quickcreate=0,quickcreatesequence=3 WHERE vtiger_tabid=16 and vtiger_fieldname='eventstatus'",
		"UPDATE vtiger_field SET quickcreate=0,quickcreatesequence=4 WHERE vtiger_tabid=16 and vtiger_fieldname='activitytype'",
		"UPDATE vtiger_field SET quickcreate=0,quickcreatesequence=5 WHERE vtiger_tabid=16 and vtiger_fieldname='duration_hours'",

		"UPDATE vtiger_field SET quickcreate=0,quickcreatesequence=3 WHERE vtiger_tabid=9 and vtiger_fieldname='taskstatus'",
		);
foreach($update_array4 as $query)
{
	Execute($query);
}



//Table 'inventory_tandc' added newly to include Inventory Terms &Conditions
$create_query1 = "CREATE TABLE  vtiger_inventory_tandc(id INT(19),type VARCHAR(30) NOT NULL,tandc LONGTEXT default NULL,PRIMARY KEY(id))";
Execute($create_query1);

$insert_query4 = "insert into vtiger_inventory_tandc values('".$conn->getUniqueID('inventory_tandc')."','Inventory','  ')";
Execute($insert_query4);

/****************** 5.0(Alpha) dev version 1 Database changes -- Ends*********************/










/****************** 5.0(Alpha) dev version 2 Database changes -- Starts*********************/

$query1 = "ALTER TABLE vtiger_leadaddress change lane lane varchar(250)";
Execute($query1);

$rename_table_array1 = Array(
		"update vtiger_field set vtiger_tablename='customerdetails' where vtiger_tabid=4 and vtiger_fieldname in ('portal','support_start_date','support_end_date')",
		"alter vtiger_table PortalInfo drop foreign key fk_PortalInfo",
		"rename vtiger_table PortalInfo to vtiger_portalinfo",
		"alter vtiger_table vtiger_portalinfo add CONSTRAINT `fk_portalinfo` FOREIGN KEY (`id`) REFERENCES `contactdetails` (`contactid`) ON DELETE CASCADE",
		"alter vtiger_table CustomerDetails drop foreign key fk_CustomerDetails",
		"rename vtiger_table CustomerDetails to vtiger_customerdetails",
		"alter vtiger_table vtiger_customerdetails add CONSTRAINT `fk_customerdetails` FOREIGN KEY (`customerid`) REFERENCES `contactdetails` (`contactid`) ON DELETE CASCADE"
		);
foreach($rename_table_array1 as $query)
{
	Execute($query);
}


$query2 = "create vtiger_table vtiger_ownernotify(crmid int(19),smownerid int(19),flag int(3))";
Execute($query2);


//Form the vtiger_role_map_array as vtiger_roleid=>name mapping array
$sql = "select * from vtiger_role";
$res = $conn->query($sql);
$role_map_array = Array();
for($i=0;$i<$conn->num_rows($res);$i++)
{
	$roleid = $conn->query_result($res,$i,'roleid');
	$name = $conn->query_result($res,$i,'name');
	$role_map_array[$roleid] = $name;
}
//echo '<pre> List of vtiger_roles :';print_r($role_map_array);echo '</pre>';

//Before delete the vtiger_role take a backup array for the vtiger_table vtiger_user2role
$sql = "select * from vtiger_user2role";
$res = $conn->query($sql);
$user2role_array = array();
for($i=0;$i<$conn->num_rows($res);$i++)
{
	$userid = $conn->query_result($res,$i,'userid');
	$roleid = $conn->query_result($res,$i,'roleid');
	$user2role_array[$userid] = $roleid;
}
//echo '<pre> List of vtiger_user2role : (userid => vtiger_roleid)';print_r($user2role_array);echo '</pre>';

//Delete the vtiger_role entries
$sql = "truncate vtiger_role";
Execute($sql);


$query3 = "alter vtiger_table vtiger_user2role drop FOREIGN KEY fk_user2role2";
Execute($query3);

//4,5 th are the Extra added queries
$alter_query_array1 = Array(
		"alter vtiger_table vtiger_user2role change vtiger_roleid vtiger_roleid varchar(255)",
		"alter vtiger_table vtiger_role2profile change vtiger_roleid vtiger_roleid varchar(255)",
		"alter vtiger_table vtiger_role CHANGE vtiger_roleid vtiger_roleid varchar(255)",
		"alter vtiger_table vtiger_role2profile drop PRIMARY KEY",
		"alter vtiger_table vtiger_role2profile ADD PRIMARY KEY (roleid,profileid)"
		);
foreach($alter_query_array1 as $query)
{
	Execute($query);
}


$query4 = "ALTER TABLE vtiger_user2role ADD CONSTRAINT fk_user2role2 FOREIGN KEY (roleid) REFERENCES vtiger_role(roleid) ON DELETE CASCADE";
Execute($query4);

$alter_query_array2 = Array(
		"alter vtiger_table vtiger_role CHANGE name vtiger_rolename varchar(200)",
		"alter vtiger_table vtiger_role DROP description",
		"alter vtiger_table vtiger_role add parentrole varchar(255)",
		"alter vtiger_table vtiger_role add depth int(19)"
		);
foreach($alter_query_array2 as $query)
{
	Execute($query);
}



$query5 = "insert into vtiger_role values('H1','Organisation','H1',0)";
Execute($query5);

//include("include/utils/UserInfoUtil.php");
//Create vtiger_role based on vtiger_role_map_array values and form the new_role_map_array with old vtiger_roleid and new vtiger_roleid
foreach($role_map_array as $roleid => $rolename)
{
	$parentRole = 'H1';
	if($rolename == 'standard_user')
	{
		$rs = $conn->query("select * from vtiger_role where vtiger_rolename='administrator'");
		$parentRole = $conn->query_result($rs,0,'roleid');
	}
	$empty_array = array(""=>"");
	$new_role_id = createRole($rolename,$parentRole,$empty_array);
	$new_role_map_array[$roleid] = $new_role_id;
}

//Before insert the new entry we should remove the old entries -- added on 06-06-06
$user2role_del = "truncate vtiger_user2role";
Execute($user2role_del);

//First we will insert the old values from vtiger_user2role_array to vtiger_user2role vtiger_table and then update the new vtiger_role id
foreach($user2role_array as $userid => $roleid)
{
	$sql = "insert into vtiger_user2role (userid, vtiger_roleid) values(".$userid.",'".$new_role_map_array[$roleid]."')";
	Execute($sql);
}
//Commented the following loop as we have backup the vtiger_user2role and insert the entries with the new rold id using new_role_map_array above
//Update the vtiger_user2role vtiger_table with new vtiger_roleid
/*
   foreach($new_role_map_array as $old_roleid => $new_roleid)
   {
   $update_user2role = "update vtiger_user2role set vtiger_roleid='".$new_roleid."' where vtiger_roleid=".$old_roleid;
   Execute($update_user2role);
   }
 */
//Update the vtiger_role2profile vtiger_table with new vtiger_roleid
foreach($new_role_map_array as $old_roleid => $new_roleid)
{
	$update_role2profile = "update vtiger_role2profile set vtiger_roleid='".$new_roleid."' where vtiger_roleid=".$old_roleid;
	Execute($update_role2profile);
}



//Group Migration:
//Step 1 :  form and group_map_array as groupname => description from vtiger_groups vtiger_table
//Step 2 :  form an vtiger_users2group_map_array array as userid => groupname from vtiger_users2group vtiger_table
//Step 3 :  delete all entries from vtiger_groups vtiger_table and enter new values from group_map_array
//Step 4 :  drop the vtiger_table vtiger_users2group and create new vtiger_table
//Step 5 :  put entries to vtiger_users2group vtiger_table based on vtiger_users2group_map_array. Here get the groupid from vtiger_groups vtiger_table based on groupname

//Step 1 : Form the group_map_array as groupname => description
$sql = "select * from vtiger_groups";
$res = $conn->query($sql);
$group_map_array = Array();
for($i=0;$i<$conn->num_rows($res);$i++)
{
	$name = $conn->query_result($res,$i,'name');
	$desc = $conn->query_result($res,$i,'description');
	$group_map_array[$name] = $desc;
}
//echo '<pre>List of Groups : ';print_r($group_map_array);echo '</pre>';


//Step 2 : form an vtiger_users2group_map_array array as userid => groupname from vtiger_users2group vtiger_table
$sql = "select * from vtiger_users2group";
$res = $conn->query($sql);
$users2group_map_array = Array();
for($i=0;$i<$conn->num_rows($res);$i++)
{
	$groupname = $conn->query_result($res,$i,'groupname');
	$userid = $conn->query_result($res,$i,'userid');
	$users2group_map_array[$userid] = $groupname;
}
//echo '<pre>List of vtiger_users2group : ';print_r($users2group_map_array);echo '</pre>';

//Step 3 : delete all entries from vtiger_groups vtiger_table
$sql = "truncate vtiger_groups";
Execute($sql);

$alter_query_array3 = Array(
		"alter vtiger_table vtiger_users2group drop FOREIGN KEY fk_users2group",
		"alter vtiger_table vtiger_leadgrouprelation drop FOREIGN KEY fk_leadgrouprelation2",
		"alter vtiger_table vtiger_activitygrouprelation drop FOREIGN KEY fk_activitygrouprelation2",
		"alter vtiger_table vtiger_ticketgrouprelation drop FOREIGN KEY fk_ticketgrouprelation2",
		"alter vtiger_table vtiger_groups drop PRIMARY KEY"
		);
foreach($alter_query_array3 as $query)
{
	Execute($query);
}

//2 nd query is the Extra added query
//Adding columns in group vtiger_table:
$alter_query_array4 = Array(
		"alter vtiger_table vtiger_groups add column groupid int(19) FIRST",
		"alter vtiger_table vtiger_groups change name  groupname varchar(100)",
		"alter vtiger_table vtiger_groups ADD PRIMARY KEY (groupid)",
		"alter vtiger_table vtiger_groups add index (groupname)"
		);
foreach($alter_query_array4 as $query)
{
	Execute($query);
}


//Moved the create vtiger_table queries for vtiger_group2grouprel, vtiger_group2role, vtiger_group2rs from the end of this block
//Added on 06-06-06
$query8 = "CREATE TABLE `group2grouprel` (
	  `groupid` int(19) NOT NULL,
	    `containsgroupid` int(19) NOT NULL,
	      PRIMARY KEY  (`groupid`,`containsgroupid`)
      ) ENGINE=InnoDB";
      /*
$query8 = "CREATE TABLE `group2grouprel` 
(
 `groupid` int(19) NOT NULL default '0',
 `containsgroupid` int(19) NOT NULL default '0',
 PRIMARY KEY (`groupid`,`containsgroupid`),
 CONSTRAINT `fk_group2grouprel1` FOREIGN KEY (`groupid`) REFERENCES `groups` (`groupid`) ON DELETE CASCADE
) TYPE=InnoDB";
*/
Execute($query8);

//Added on 06-06-06
$query9 = "CREATE TABLE `group2role` (
	  `groupid` int(19) NOT NULL,
	    `roleid` varchar(255) NOT NULL,
	      PRIMARY KEY  (`groupid`,`roleid`)
      ) ENGINE=InnoDB";
/*
$query9 = "CREATE TABLE `group2role` 
(
 `groupid` int(19) NOT NULL default '0',
 `roleid` varchar(255) NOT NULL default '',
 PRIMARY KEY (`groupid`,`roleid`),
 CONSTRAINT `fk_group2role1` FOREIGN KEY (`groupid`) REFERENCES `groups` (`groupid`) ON DELETE CASCADE
) TYPE=InnoDB";
*/
Execute($query9);

//Added on 06-06-06
$query10 = "CREATE TABLE `group2rs` (
	  `groupid` int(19) NOT NULL,
	    `roleandsubid` varchar(255) NOT NULL,
	      PRIMARY KEY  (`groupid`,`roleandsubid`)
      ) ENGINE=InnoDB";
/*
$query10 = "CREATE TABLE `group2rs` 
(
 `groupid` int(19) NOT NULL default '0',
 `roleandsubid` varchar(255) NOT NULL default '',
 PRIMARY KEY (`groupid`,`roleandsubid`),
 CONSTRAINT `fk_group2rs1` FOREIGN KEY (`groupid`) REFERENCES `groups` (`groupid`) ON DELETE CASCADE
) TYPE=InnoDB";
*/
Execute($query10);

//Insert all the retrieved old values to the new vtiger_groups vtiger_table ie., create new vtiger_groups
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


//Copy all mappings in a user2grop vtiger_table in a array;

//Step 4 : Drop and again create vtiger_users2group
$query6 = "drop vtiger_table vtiger_users2group";
Execute($query6);

//Added on 06-06-06
$query7 = "CREATE TABLE `users2group` (
	  `groupid` int(19) NOT NULL,
	    `userid` int(19) NOT NULL,
	      PRIMARY KEY  (`groupid`,`userid`),
	        KEY `users2group_groupname_uerid_idx` (`groupid`,`userid`)
	) ENGINE=InnoDB";
/*
$query7 = "CREATE TABLE `users2group` 
(
 `groupid` int(19) NOT NULL default '0',
 `userid` int(19) NOT NULL default '0',
 PRIMARY KEY (`groupid`,`userid`),
 CONSTRAINT `fk_users2group1` FOREIGN KEY (`groupid`) REFERENCES `groups` (`groupid`) ON DELETE CASCADE
) TYPE=InnoDB";
*/
Execute($query7);

//Step 5 : put entries to vtiger_users2group vtiger_table based on vtiger_users2group_map_array. Here get the groupid from vtiger_groups vtiger_table based on groupname
foreach($users2group_map_array as $userid => $groupname)
{
	//$groupid = $conn->query_result($conn->query("select * from vtiger_groups where groupname='".$groupname."'"),0,'groupid');
	$sql = "insert into vtiger_users2group (groupid,userid) values(".$group_name_id_mapping[$groupname].",".$userid.")";
	Execute($sql);
}


$alter_query_array5 = Array(
		"alter vtiger_table vtiger_leadgrouprelation ADD CONSTRAINT fk_leadgrouprelation2 FOREIGN KEY (groupname) REFERENCES vtiger_groups(groupname) ON DELETE CASCADE",
		"ALTER TABLE vtiger_activitygrouprelation ADD CONSTRAINT fk_activitygrouprelation2 FOREIGN KEY (groupname) REFERENCES vtiger_groups(groupname) ON DELETE CASCADE",
		"ALTER TABLE vtiger_ticketgrouprelation ADD CONSTRAINT fk_ticketgrouprelation2 FOREIGN KEY (groupname) REFERENCES vtiger_groups(groupname) ON DELETE CASCADE"
		);
foreach($alter_query_array5 as $query)
{
	Execute($query);
}
//Moved the create vtiger_table queries for vtiger_group2grouprel, vtiger_group2role, vtiger_group2rs to before creatinf the Group ie., before call the createGroup


/***Added to include decimal places for amount vtiger_field in vtiger_potential vtiger_table  --by Mangai 15-Nov-2005***/

$query11 = "ALTER TABLE vtiger_potential change amount amount decimal(10,2)";
Execute($query11);

/****************** 5.0(Alpha) dev version 2 Database changes -- Ends*********************/












/****************** 5.0(Alpha) dev version 3 Database changes -- Starts*********************/

//Drop the column company_name from vtiger_vendor vtiger_table ---- modified by Mickie on 18-11-2005
$altersql1 = "alter vtiger_table vtiger_vendor drop column company_name";
Execute($altersql1);
//TODO (check): Remove this company_name entry from the vtiger_field vtiger_table if it already exists

//Migration for Default Organisation Share -- Added by Don on 20-11-2005

$query1 = "CREATE TABLE `org_share_action_mapping` (
`share_action_id` int(19) NOT NULL default '0',
	`share_action_name` varchar(200) NOT NULL default '',
PRIMARY KEY  (`share_action_id`,`share_action_name`)
	) TYPE=InnoDB ";
Execute($query1);

$query2 = "CREATE TABLE `org_share_action2tab` (
	`share_action_id` int(19) NOT NULL default '0',
	`tabid` int(19) NOT NULL default '0',
	PRIMARY KEY  (`share_action_id`,`tabid`),
	CONSTRAINT `fk_org_share_action2tab` FOREIGN KEY (`share_action_id`) REFERENCES `org_share_action_mapping` (`share_action_id`) ON DELETE CASCADE
	) TYPE=InnoDB";
Execute($query2);


$query3 = "alter vtiger_table vtiger_def_org_share add column editstatus int(19)";
Execute($query3);

$query4 = "delete from vtiger_def_org_share where vtiger_tabid in(8,14,15,18,19)";
Execute($query4);



//Inserting values into org share action mapping
$insert_query_array1 = Array(
			"insert into vtiger_org_share_action_mapping values(0,'Public: Read Only')",
			"insert into vtiger_org_share_action_mapping values(1,'Public:Read,Create/Edit')",
			"insert into vtiger_org_share_action_mapping values(2,'Public: Read, Create/Edit, Delete')",
			"insert into vtiger_org_share_action_mapping values(3,'Private')",
			"insert into vtiger_org_share_action_mapping values(4,'Hide Details')",
			"insert into vtiger_org_share_action_mapping values(5,'Hide Details and Add Events')",
			"insert into vtiger_org_share_action_mapping values(6,'Show Details')",
			"insert into vtiger_org_share_action_mapping values(7,'Show Details and Add Events')"
			);
foreach($insert_query_array1 as $query)
{
	Execute($query);
}


//Inserting for all vtiger_tabs
$def_org_tabid=Array(2,4,6,7,9,10,13,16,20,21,22,23,26);
foreach($def_org_tabid as $def_tabid)
{
	$insert_query_array2 = Array(
			"insert into vtiger_org_share_action2tab values(0,".$def_tabid.")",
			"insert into vtiger_org_share_action2tab values(1,".$def_tabid.")",
			"insert into vtiger_org_share_action2tab values(2,".$def_tabid.")",
			"insert into vtiger_org_share_action2tab values(3,".$def_tabid.")"
			);
	foreach($insert_query_array2 as $query)
	{
		Execute($query);
	}
}

$insert_query_array3 = Array(
		"insert into vtiger_org_share_action2tab values(4,17)",
		"insert into vtiger_org_share_action2tab values(5,17)",
		"insert into vtiger_org_share_action2tab values(6,17)",
		"insert into vtiger_org_share_action2tab values(7,17)"
		);
foreach($insert_query_array3 as $query)
{
	Execute($query);
}

$query_array1 = Array(
		"insert into vtiger_def_org_share values(9,17,7,0)",
		"update vtiger_def_org_share set editstatus=0",
		"update vtiger_def_org_share set editstatus=2 where vtiger_tabid=4",
		"update vtiger_def_org_share set editstatus=1 where vtiger_tabid=9",
		"update vtiger_def_org_share set editstatus=2 where vtiger_tabid=16"
		);
foreach($query_array1 as $query)
{
	Execute($query);
}

/****************** 5.0(Alpha) dev version 3 Database changes -- Ends*********************/



$conn->println("Database Modifications for 5.0(Alpha) Dev 3 ==> 5.0 Alpha starts here.");
//echo "<br><br><b>Database Modifications for 5.0(Alpha) Dev3 ==> 5.0 Alpha starts here.....</b><br>";
$alter_query_array6 = Array(
				"ALTER TABLE vtiger_users ADD column vtiger_activity_view VARCHAR(25) DEFAULT 'Today' AFTER homeorder",
				"ALTER TABLE vtiger_activity ADD column notime CHAR(3) DEFAULT '0' AFTER location"
			   );
foreach($alter_query_array6 as $query)
{
	Execute($query);
}

$insert_field_array1 = Array(
				"Insert into vtiger_field values (9,".$conn->getUniqueID("field").",'notime','activity',1,56,'notime','No Time',1,0,0,100,20,1,3,'C~O',1,'')",
				"Insert into vtiger_field values (16,".$conn->getUniqueID("field").",'notime','activity',1,56,'notime','No Time',1,0,0,100,18,1,1,'C~O',1,'')"
			    );
foreach($insert_field_array1 as $query)
{
	Execute($query);
}

$alter_query_array7 = Array(
				"alter vtiger_table vtiger_vendor add column pobox varchar(30) after state",
				"alter vtiger_table vtiger_leadaddress add column pobox varchar(30) after state",
				"alter vtiger_table vtiger_accountbillads add column pobox varchar(30) after state",
				"alter vtiger_table vtiger_accountshipads add column pobox varchar(30) after state",
				"alter vtiger_table vtiger_contactaddress add column mailingpobox varchar(30) after mailingstate",
				"alter vtiger_table vtiger_contactaddress add column otherpobox varchar(30) after otherstate",
				"alter vtiger_table vtiger_quotesbillads add column bill_pobox varchar(30) after bill_street",
				"alter vtiger_table vtiger_quotesshipads add column ship_pobox varchar(30) after ship_street",
				"alter vtiger_table vtiger_pobillads add column bill_pobox varchar(30) after bill_street",
				"alter vtiger_table vtiger_poshipads add column ship_pobox varchar(30) after ship_street",
				"alter vtiger_table vtiger_sobillads add column bill_pobox varchar(30) after bill_street",
				"alter vtiger_table vtiger_soshipads add column ship_pobox varchar(30) after ship_street",
				"alter vtiger_table vtiger_invoicebillads add column bill_pobox varchar(30) after bill_street",
				"alter vtiger_table vtiger_invoiceshipads add column ship_pobox varchar(30) after ship_street"
			   );
foreach($alter_query_array7 as $query)
{
	Execute($query);
}

$insert_field_array2 = Array(
				"insert into vtiger_field values (23,".$conn->getUniqueID("field").",'bill_pobox','invoicebillads',1,'1','bill_pobox','Billing Po Box',1,0,0,100,3,2,1,'V~O',1,'')",
				"insert into vtiger_field values (23,".$conn->getUniqueID("field").",'ship_pobox','invoiceshipads',1,'1','ship_pobox','Shipping Po Box',1,0,0,100,4,2,1,'V~O',1,'')",
				
				"insert into vtiger_field values (6,".$conn->getUniqueID("field").",'pobox','accountbillads',1,'1','bill_pobox','Billing Po Box',1,0,0,100,3,2,1,'V~O',1,'')",
				"insert into vtiger_field values (6,".$conn->getUniqueID("field").",'pobox','accountshipads',1,'1','ship_pobox','Shipping Po Box',1,0,0,100,4,2,1,'V~O',1,'')",
				
				"insert into vtiger_field values (7,".$conn->getUniqueID("field").",'pobox','leadaddress',1,'1','pobox','Po Box',1,0,0,100,2,2,1,'V~O',1,'')",

				"insert into vtiger_field values (4,".$conn->getUniqueID("field").",'mailingpobox','contactaddress',1,'1','mailingpobox','Mailing Po Box',1,0,0,100,3,2,1,'V~O',1,'')",
				"insert into vtiger_field values (4,".$conn->getUniqueID("field").",'otherpobox','contactaddress',1,'1','otherpobox','Other Po Box',1,0,0,100,4,2,1,'V~O',1,'')",

				"insert into vtiger_field values (18,".$conn->getUniqueID("field").",'pobox','vendor',1,'1','pobox','Po Box',1,0,0,100,2,2,1,'V~O',1,'')",

				"insert into vtiger_field values (20,".$conn->getUniqueID("field").",'bill_pobox','quotesbillads',1,'1','bill_pobox','Billing Po Box',1,0,0,100,3,2,1,'V~O',1,'')",
				"insert into vtiger_field values (20,".$conn->getUniqueID("field").",'ship_pobox','quotesshipads',1,'1','ship_pobox','Shipping Po Box',1,0,0,100,4,2,1,'V~O',1,'')",

				"insert into vtiger_field values (21,".$conn->getUniqueID("field").",'bill_pobox','pobillads',1,'1','bill_pobox','Billing Po Box',1,0,0,100,3,2,1,'V~O',1,'')",
				"insert into vtiger_field values (21,".$conn->getUniqueID("field").",'ship_pobox','poshipads',1,'1','ship_pobox','Shipping Po Box',1,0,0,100,4,2,1,'V~O',1,'')",

				"insert into vtiger_field values (22,".$conn->getUniqueID("field").",'bill_pobox','sobillads',1,'1','bill_pobox','Billing Po Box',1,0,0,100,3,2,1,'V~O',1,'')",
				"insert into vtiger_field values (22,".$conn->getUniqueID("field").",'ship_pobox','soshipads',1,'1','ship_pobox','Shipping Po Box',1,0,0,100,4,2,1,'V~O',1,'')"
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
		$query1 = "update vtiger_field set sequence=".$sequence[$j]." where vtiger_tablename='".$tablename[$n+$i]."' && vtiger_fieldname='".$fieldname[$j]."'";
		Execute($query1);
	}
}

$fieldname = array('code','city','country','state');
$tablename = 'leadaddress';
$sequence = array(3,4,5,6);
for($i = 0;$i < 4;$i++)
{
	$query2 = "update vtiger_field set sequence=".$sequence[$i]." where vtiger_tablename='".$tablename."' && vtiger_fieldname='".$fieldname[$i]."'";
	Execute($query2);
}

$fieldname = array('city','state','postalcode','country');
$tablename = 'vendor';
$sequence = array(3,4,5,6);

for($i = 0;$i < 4;$i++)
{
	$query3 = "update vtiger_field set sequence=".$sequence[$i]." where vtiger_tablename='".$tablename."' && vtiger_fieldname='".$fieldname[$i]."'";
	Execute($query3);
}

$fieldname = array('mailingcity','othercity','mailingstate','otherstate','mailingzip','otherzip','mailingcountry','othercountry');
$tablename = 'contactaddress';
$sequence = array(5,6,7,8,9,10,11,12);

for($i = 0;$i < 8;$i++)
{
	$query = "update vtiger_field set sequence=".$sequence[$i]." where vtiger_tablename='".$tablename."' && vtiger_fieldname='".$fieldname[$i]."'";
	Execute($query);
}

$query_array1 = Array(
			"update vtiger_field set vtiger_tablename='crmentity' where vtiger_tabid=10 and vtiger_fieldname='description'",
			"update vtiger_field set vtiger_tablename='attachments' where vtiger_tabid=10 and vtiger_fieldname='filename'",
			"drop vtiger_table emails",

			"alter vtiger_table vtiger_activity drop column description",
			"update vtiger_field set vtiger_tablename='crmentity' where vtiger_tabid in (9,16) and vtiger_fieldname='description'",

			"update vtiger_tab set name='PurchaseOrder',tablabel='PurchaseOrder' where vtiger_tabid=21",
			"update vtiger_tab set presence=0 where vtiger_tabid=22 and name='SalesOrder'",

			"delete from vtiger_actionmapping where actionname='SalesOrderDetailView'",
			"delete from vtiger_actionmapping where actionname='SalesOrderEditView'",
			"delete from vtiger_actionmapping where actionname='SaveSalesOrder'",
			"delete from vtiger_actionmapping where actionname='DeleteSalesOrder'",

			"insert into vtiger_field values (13,".$conn->getUniqueID("field").",'filename','attachments',1,'61','filename','Attachment',1,0,0,100,12,2,1,'V~O',0,1)",

			"alter vtiger_table vtiger_troubletickets add column filename varchar(50) default NULL after title"
		     );
foreach($query_array1 as $query)
{
	Execute($query);
}

$create_query3 = "create vtiger_table vtiger_parenttab(parenttabid int(19) not null, vtiger_parenttab_label varchar(100) not null, sequence int(10) not null, visible int(2) not null default '0', Primary Key(parenttabid))";
Execute($create_query3);
$create_query4 = "create vtiger_table vtiger_parenttabrel(parenttabid int(3) not null, vtiger_tabid int(3) not null,sequence int(3) not null)";
Execute($create_query4);

$insert_query_array4 = Array(
				"insert into vtiger_parenttab values(1,'My Home Page',1,0),(2,'Marketing',2,0),(3,'Sales',3,0),(4,'Support',4,0),(5,'Analytics',5,0),(6,'Inventory',6,0), (7,'Tools',7,0),(8,'Settings',8,0)",
				"insert into vtiger_parenttabrel values(1,9,2),(1,17,3),(1,10,4),(1,3,1),(3,7,1),(3,6,2),(3,4,3),(3,2,4),(3,20,5),(3,22,6),(3,23,7),(3,14,8),(3,19,9),(3,8,10),(4,13,1),(4,15,2),(4,6,3),(4,4,4),(4,14,5),(4,8,6),(5,1,1),(5,25,2),(6,14,1), (6,18,2), (6,19,3), (6,21,4), (6,22,5), (6,20,6), (6,23,7), (7,24,1), (7,27,2), (7,8,3), (2,26,1), (2,6,2), (2,4,3) "
			    );
foreach($insert_query_array4 as $query)
{
	Execute($query);
}


$create_query5 = "CREATE TABLE vtiger_blocks ( blockid int(19) NOT NULL, vtiger_tabid int(19) NOT NULL, blocklabel varchar(100) NOT NULL, sequence int(19) NOT NULL, show_title int(2) NOT NULL, visible int(2) NOT NULL DEFAULT 0, create_view int(2) NOT NULL DEFAULT 0, edit_view int(2) NOT NULL DEFAULT 0, detail_view int(2) NOT NULL DEFAULT 0, PRIMARY KEY (blockid))";
Execute($create_query5);

$update_query_array1 = Array(
				"update vtiger_field set block=2 where vtiger_tabid=2 and block=5",
				"update vtiger_field set block=3 where vtiger_tabid=2 and block=2",

				//"update vtiger_field set block=4 where vtiger_tabid=4 and block=1",
				"update vtiger_field set block=5 where vtiger_tabid=4 and block=5",
				"update vtiger_field set block=6 where vtiger_tabid=4 and block=4",//Modified on 24-04-06
				"update vtiger_field set block=4 where vtiger_tabid=4 and block=1",
				"update vtiger_field set block=7 where vtiger_tabid=4 and block=2",
				"update vtiger_field set block=8 where vtiger_tabid=4 and block=3",

				"update vtiger_field set block=9 where vtiger_tabid=6 and block=1",
				"update vtiger_field set block=10 where vtiger_tabid=6 and block=5",
				"update vtiger_field set block=11 where vtiger_tabid=6 and block=2",
				"update vtiger_field set block=12 where vtiger_tabid=6 and block=3",

				"update vtiger_field set block=13 where vtiger_tabid=7 and block=1",
				"update vtiger_field set block=14 where vtiger_tabid=7 and block=5",
				"update vtiger_field set block=15 where vtiger_tabid=7 and block=2",
				"update vtiger_field set block=16 where vtiger_tabid=7 and block=3",

				"update vtiger_field set block=17 where vtiger_tabid=8 and block=1",
				"update vtiger_field set block=17 where vtiger_tabid=8 and block=2",
				"update vtiger_field set block=18 where vtiger_tabid=8 and block=3",

				"update vtiger_field set block=19 where vtiger_tabid=9 and block=1",
				"update vtiger_field set block=19 where vtiger_tabid=9 and block=7",
				"update vtiger_field set block=20 where vtiger_tabid=9 and block=2",

				"update vtiger_field set block=21 where vtiger_tabid=10 and block=1",
				"update vtiger_field set block=22 where vtiger_tabid=10 and block=2",
				"update vtiger_field set block=23 where vtiger_tabid=10 and block=3",
				"update vtiger_field set block=23 where vtiger_tabid=10 and block=4",
				"update vtiger_field set block=24 where vtiger_tabid=10 and block=5",

				"update vtiger_field set block=25 where vtiger_tabid=13 and block=1",
				"update vtiger_field set block=26 where vtiger_tabid=13 and block=2",
				"update vtiger_field set block=27 where vtiger_tabid=13 and block=5",
				"update vtiger_field set block=28 where vtiger_tabid=13 and block=3",
				"update vtiger_field set block=29 where vtiger_tabid=13 and block=4",
				"update vtiger_field set block=30 where vtiger_tabid=13 and block=6",

				"update vtiger_field set block=31 where vtiger_tabid=14 and block=1",
				"update vtiger_field set block=32 where vtiger_tabid=14 and block=2",
				"update vtiger_field set block=33 where vtiger_tabid=14 and block=3",
				"update vtiger_field set block=34 where vtiger_tabid=14 and block=5",
				"update vtiger_field set block=35 where vtiger_tabid=14 and block=6",
				"update vtiger_field set block=36 where vtiger_tabid=14 and block=4",

				"update vtiger_field set block=37 where vtiger_tabid=15 and block=1",
				"update vtiger_field set block=38 where vtiger_tabid=15 and block=2",
				"update vtiger_field set block=39 where vtiger_tabid=15 and block=3",
				"update vtiger_field set block=40 where vtiger_tabid=15 and block=4",

				"update vtiger_field set block=41 where vtiger_tabid=16 and block=1",
				"update vtiger_field set block=42 where vtiger_tabid=16 and block=7",
				"update vtiger_field set block=43 where vtiger_tabid=16 and block=2",

				"update vtiger_field set block=44 where vtiger_tabid=18 and block=1",
				"update vtiger_field set block=45 where vtiger_tabid=18 and block=5",
				"update vtiger_field set block=36 where vtiger_tabid=18 and block=2",
				"update vtiger_field set block=47 where vtiger_tabid=18 and block=3",

				"update vtiger_field set block=48 where vtiger_tabid=19 and block=1",
				"update vtiger_field set block=49 where vtiger_tabid=19 and block=5",
				"update vtiger_field set block=50 where vtiger_tabid=19 and block=2",

				"update vtiger_field set block=51 where vtiger_tabid=20 and block=1",
				"update vtiger_field set block=52 where vtiger_tabid=20 and block=5",
				"update vtiger_field set block=53 where vtiger_tabid=20 and block=2",
				"update vtiger_field set block=55 where vtiger_tabid=20 and block=6",
				"update vtiger_field set block=56 where vtiger_tabid=20 and block=3",

				"update vtiger_field set block=57 where vtiger_tabid=21 and block=1",
				"update vtiger_field set block=58 where vtiger_tabid=21 and block=5",
				"update vtiger_field set block=59 where vtiger_tabid=21 and block=2",
				"update vtiger_field set block=61 where vtiger_tabid=21 and block=6",
				"update vtiger_field set block=62 where vtiger_tabid=21 and block=3",

				"update vtiger_field set block=63 where vtiger_tabid=22 and block=1",
				"update vtiger_field set block=64 where vtiger_tabid=22 and block=5",
				"update vtiger_field set block=65 where vtiger_tabid=22 and block=2",
				"update vtiger_field set block=67 where vtiger_tabid=22 and block=6",
				"update vtiger_field set block=68 where vtiger_tabid=22 and block=3",


				"update vtiger_field set block=69 where vtiger_tabid=23 and block=1",
				"update vtiger_field set block=70 where vtiger_tabid=23 and block=5",
				"update vtiger_field set block=71 where vtiger_tabid=23 and block=2",
				"update vtiger_field set block=73 where vtiger_tabid=23 and block=6",
				"update vtiger_field set block=74 where vtiger_tabid=23 and block=3",
			    );
foreach($update_query_array1 as $query)
{
	Execute($query);
}

$insert_query_array5 = Array(
				"insert into vtiger_blocks values (1,2,'LBL_OPPORTUNITY_INFORMATION',1,0,0,0,0,0)",
				"insert into vtiger_blocks values (2,2,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into vtiger_blocks values (3,2,'LBL_DESCRIPTION_INFORMATION',3,0,0,0,0,0)",
				"insert into vtiger_blocks values (4,4,'LBL_CONTACT_INFORMATION',1,0,0,0,0,0)",
				"insert into vtiger_blocks values (5,4,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into vtiger_blocks values (6,4,'LBL_CUSTOMER_PORTAL_INFORMATION',3,0,0,0,0,0)",
				"insert into vtiger_blocks values (7,4,'LBL_ADDRESS_INFORMATION',4,0,0,0,0,0)",
				"insert into vtiger_blocks values (8,4,'LBL_DESCRIPTION_INFORMATION',5,0,0,0,0,0)",
				"insert into vtiger_blocks values (9,6,'LBL_ACCOUNT_INFORMATION',1,0,0,0,0,0)",
				"insert into vtiger_blocks values (10,6,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into vtiger_blocks values (11,6,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)",
				"insert into vtiger_blocks values (12,6,'LBL_DESCRIPTION_INFORMATION',4,0,0,0,0,0)",
				"insert into vtiger_blocks values (13,7,'LBL_LEAD_INFORMATION',1,0,0,0,0,0)",
				"insert into vtiger_blocks values (14,7,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into vtiger_blocks values (15,7,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)",
				"insert into vtiger_blocks values (16,7,'LBL_DESCRIPTION_INFORMATION',4,0,0,0,0,0)",
				"insert into vtiger_blocks values (17,8,'LBL_NOTE_INFORMATION',1,0,0,0,0,0)",
				"insert into vtiger_blocks values (18,8,'',2,1,0,0,0,0)",
				"insert into vtiger_blocks values (19,9,'LBL_TASK_INFORMATION',1,0,0,0,0,0)",
				"insert into vtiger_blocks values (20,9,'',2,1,0,0,0,0)",
				"insert into vtiger_blocks values (21,10,'LBL_EMAIL_INFORMATION',1,0,0,0,0,0)",
				"insert into vtiger_blocks values (22,10,'',2,1,0,0,0,0)",
				"insert into vtiger_blocks values (23,10,'',3,1,0,0,0,0)",
				"insert into vtiger_blocks values (24,10,'',4,1,0,0,0,0)",
				"insert into vtiger_blocks values (25,13,'LBL_TICKET_INFORMATION',1,0,0,0,0,0)",
				"insert into vtiger_blocks values (26,13,'',2,1,0,0,0,0)",
				"insert into vtiger_blocks values (27,13,'LBL_CUSTOM_INFORMATION',3,0,0,0,0,0)",
				"insert into vtiger_blocks values (28,13,'LBL_DESCRIPTION_INFORMATION',4,0,0,0,0,0)",
				"insert into vtiger_blocks values (29,13,'LBL_TICKET_RESOLUTION',5,0,0,1,0,0)",
				"insert into vtiger_blocks values (30,13,'LBL_COMMENTS',6,0,0,1,0,0)",
				"insert into vtiger_blocks values (31,14,'LBL_PRODUCT_INFORMATION',1,0,0,0,0,0)",
				"insert into vtiger_blocks values (32,14,'LBL_PRICING_INFORMATION',2,0,0,0,0,0)",
				"insert into vtiger_blocks values (33,14,'LBL_STOCK_INFORMATION',3,0,0,0,0,0)",
				"insert into vtiger_blocks values (34,14,'LBL_CUSTOM_INFORMATION',4,0,0,0,0,0)",
				"insert into vtiger_blocks values (35,14,'LBL_IMAGE_INFORMATION',5,0,0,0,0,0)",
				"insert into vtiger_blocks values (36,14,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0)",
				"insert into vtiger_blocks values (37,15,'LBL_FAQ_INFORMATION',1,0,0,0,0,0)",
				"insert into vtiger_blocks values (38,15,'',2,1,0,0,0,0)",
				"insert into vtiger_blocks values (39,15,'',3,1,0,0,0,0)",
				"insert into vtiger_blocks values (40,15,'LBL_COMMENT_INFORMATION',4,0,0,1,0,0)",
				"insert into vtiger_blocks values (41,16,'LBL_EVENT_INFORMATION',1,0,0,0,0,0)",
				"insert into vtiger_blocks values (42,16,'',2,1,0,0,0,0)",
				"insert into vtiger_blocks values (43,16,'',3,1,0,0,0,0)",
				"insert into vtiger_blocks values (44,18,'LBL_VENDOR_INFORMATION',1,0,0,0,0,0)",
				"insert into vtiger_blocks values (45,18,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into vtiger_blocks values (46,18,'LBL_VENDOR_ADDRESS_INFORMATION',3,0,0,0,0,0)",
				"insert into vtiger_blocks values (47,18,'LBL_DESCRIPTION_INFORMATION',4,0,0,0,0,0)",
				"insert into vtiger_blocks values (48,19,'LBL_PRICEBOOK_INFORMATION',1,0,0,0,0,0)",
				"insert into vtiger_blocks values (49,19,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into vtiger_blocks values (50,19,'LBL_DESCRIPTION_INFORMATION',3,0,0,0,0,0)",
				"insert into vtiger_blocks values (51,20,'LBL_QUOTE_INFORMATION',1,0,0,0,0,0)",
				"insert into vtiger_blocks values (52,20,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into vtiger_blocks values (53,20,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)",
				"insert into vtiger_blocks values (54,20,'LBL_RELATED_PRODUCTS',4,0,0,0,0,0)",
				"insert into vtiger_blocks values (55,20,'LBL_TERMS_INFORMATION',5,0,0,0,0,0)",
				"insert into vtiger_blocks values (56,20,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0)",
				"insert into vtiger_blocks values (57,21,'LBL_PO_INFORMATION',1,0,0,0,0,0)",
				"insert into vtiger_blocks values (58,21,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into vtiger_blocks values (59,21,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)",
				"insert into vtiger_blocks values (60,21,'LBL_RELATED_PRODUCTS',4,0,0,0,0,0)",
				"insert into vtiger_blocks values (61,21,'LBL_TERMS_INFORMATION',5,0,0,0,0,0)",
				"insert into vtiger_blocks values (62,21,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0)",
				"insert into vtiger_blocks values (63,22,'LBL_SO_INFORMATION',1,0,0,0,0,0)",
				"insert into vtiger_blocks values (64,22,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into vtiger_blocks values (65,22,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)",
				"insert into vtiger_blocks values (66,22,'LBL_RELATED_PRODUCTS',4,0,0,0,0,0)",
				"insert into vtiger_blocks values (67,22,'LBL_TERMS_INFORMATION',5,0,0,0,0,0)",
				"insert into vtiger_blocks values (68,22,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0)",
				"insert into vtiger_blocks values (69,23,'LBL_INVOICE_INFORMATION',1,0,0,0,0,0)",
				"insert into vtiger_blocks values (70,23,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into vtiger_blocks values (71,23,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)",
				"insert into vtiger_blocks values (72,23,'LBL_RELATED_PRODUCTS',4,0,0,0,0,0)",
				"insert into vtiger_blocks values (73,23,'LBL_TERMS_INFORMATION',5,0,0,0,0,0)",
				"insert into vtiger_blocks values (74,23,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0)"
			    );
foreach($insert_query_array5 as $query)
{
	Execute($query);
}

$update_query_array2 = Array(
				"update vtiger_tab set name='Vendors', vtiger_tablabel='Vendors' where vtiger_tabid=18",
				"update vtiger_tab set name='PriceBooks', vtiger_tablabel='PriceBooks' where vtiger_tabid=19",
				"update vtiger_tab set presence=0 where vtiger_tabid in(18,19)",
				"update vtiger_relatedlists set label='PriceBooks' where vtiger_tabid=14 and related_tabid=19"
			    );
foreach($update_query_array2 as $query)
{
	Execute($query);
}

$delete_query1 = "delete from vtiger_actionmapping where actionname in ('SavePriceBook','SaveVendor','PriceBookEditView','VendorEditView','DeletePriceBook','DeleteVendor','PriceBookDetailView','VendorDetailView')";
Execute($query);

$insert_query_array6 = Array(
			"insert into vtiger_customview values(".$conn->getUniqueID('customview').",'All',1,0,'Leads')",
			"insert into vtiger_customview values(".$conn->getUniqueID('customview').",'All',1,0,'Accounts')",
			"insert into vtiger_customview values(".$conn->getUniqueID('customview').",'All',1,0,'Contacts')",
			"insert into vtiger_customview values(".$conn->getUniqueID('customview').",'All',1,0,'Potentials')",
			"insert into vtiger_customview values(".$conn->getUniqueID('customview').",'All',1,0,'HelpDesk')",
			"insert into vtiger_customview values(".$conn->getUniqueID('customview').",'All',1,0,'Quotes')",
			"insert into vtiger_customview values(".$conn->getUniqueID('customview').",'All',1,0,'Activities')",
			"insert into vtiger_customview values(".$conn->getUniqueID('customview').",'All',1,0,'Emails')",
			"insert into vtiger_customview values(".$conn->getUniqueID('customview').",'All',1,0,'Invoice')",
			"insert into vtiger_customview values(".$conn->getUniqueID('customview').",'All',1,0,'Notes')",
			"insert into vtiger_customview values(".$conn->getUniqueID('customview').",'All',1,0,'PriceBooks')",
			"insert into vtiger_customview values(".$conn->getUniqueID('customview').",'All',1,0,'Products')",
			"insert into vtiger_customview values(".$conn->getUniqueID('customview').",'All',1,0,'PurchaseOrder')",
				
			"insert into vtiger_customview values(".$conn->getUniqueID('customview').",'All',1,0,'SalesOrder')",
			"insert into vtiger_customview values(".$conn->getUniqueID('customview').",'All',1,0,'Vendors')",
			"insert into vtiger_customview values(".$conn->getUniqueID('customview').",'All',1,0,'Faq')"
			    );
foreach($insert_query_array6 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from vtiger_customview where viewname='All' and entitytype='Leads'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array7 = Array(
			"insert into vtiger_cvcolumnlist values ($cvid,0,'leaddetails:lastname:lastname:Leads_Last_Name:V')",
			"insert into vtiger_cvcolumnlist values ($cvid,1,'leaddetails:firstname:firstname:Leads_First_Name:V')",
			"insert into vtiger_cvcolumnlist values ($cvid,2,'leaddetails:company:company:Leads_Company:V')",
			"insert into vtiger_cvcolumnlist values ($cvid,3,'leadaddress:phone:phone:Leads_Phone:V')",
			"insert into vtiger_cvcolumnlist values ($cvid,4,'leadsubdetails:website:website:Leads_Website:V')",
			"insert into vtiger_cvcolumnlist values ($cvid,5,'leaddetails:email:email:Leads_Email:V')",
			"insert into vtiger_cvcolumnlist values ($cvid,6,'crmentity:smownerid:assigned_user_id:Leads_Assigned_To:V')"
			    );
foreach($insert_query_array7 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from vtiger_customview where viewname='All' and entitytype='Accounts'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array8 = Array(
		"insert into vtiger_cvcolumnlist values ($cvid,0,'account:accountname:accountname:Accounts_Account_Name:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,1,'accountbillads:city:bill_city:Accounts_City:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,2,'account:website:website:Accounts_Website:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,3,'account:phone:phone:Accounts_Phone:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,4,'crmentity:smownerid:assigned_user_id:Accounts_Assigned_To:V')"
			    );
foreach($insert_query_array8 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from vtiger_customview where viewname='All' and entitytype='Contacts'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array9 = Array(
		"insert into vtiger_cvcolumnlist values ($cvid,0,'contactdetails:firstname:firstname:Contacts_First_Name:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,1,'contactdetails:lastname:lastname:Contacts_Last_Name:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,2,'contactdetails:title:title:Contacts_Title:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,3,'account:accountname:accountname:Contacts_Account_Name:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,4,'contactdetails:email:email:Contacts_Email:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,5,'contactdetails:phone:phone:Contacts_Phone_Name:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,6,'crmentity:smownerid:assigned_user_id:Contacts_Assigned_To:V')"
			    );
foreach($insert_query_array9 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from vtiger_customview where viewname='All' and entitytype='Potentials'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array10 = Array(
	"insert into vtiger_cvcolumnlist values ($cvid,0,'potential:potentialname:potentialname:Potentials_Potential_Name:V')",
	"insert into vtiger_cvcolumnlist values ($cvid,1,'potential:accountid:account_id:Potentials_Account_Name:V')",
	"insert into vtiger_cvcolumnlist values ($cvid,2,'potential:amount:amount:Potentials_Amount:N')",
	"insert into vtiger_cvcolumnlist values ($cvid,3,'potential:closingdate:closingdate:Potentials_Expected_Close_Date:D')",
	"insert into vtiger_cvcolumnlist values ($cvid,4,'crmentity:smownerid:assigned_user_id:Potentials_Assigned_To:V')"
			     );
foreach($insert_query_array10 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from vtiger_customview where viewname='All' and entitytype='HelpDesk'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array11 = Array(
		"insert into vtiger_cvcolumnlist values ($cvid,0,'crmentity:crmid::HelpDesk_Ticket_ID:I')",
		"insert into vtiger_cvcolumnlist values ($cvid,1,'troubletickets:title:ticket_title:HelpDesk_Title:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,2,'troubletickets:parent_id:parent_id:HelpDesk_Related_to:I')",
		"insert into vtiger_cvcolumnlist values ($cvid,3,'troubletickets:status:ticketstatus:HelpDesk_Status:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,4,'troubletickets:priority:ticketpriorities:HelpDesk_Priority:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,5,'crmentity:smownerid:assigned_user_id:HelpDesk_Assigned_To:V')"
			     );
foreach($insert_query_array11 as $query)
{
	Execute($query);
}


$res=$conn->query("select cvid from vtiger_customview where viewname='All' and entitytype='Quotes'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array12 = Array(
		"insert into vtiger_cvcolumnlist values ($cvid,0,'crmentity:crmid::Quotes_Quote_ID:I')",
		"insert into vtiger_cvcolumnlist values ($cvid,1,'quotes:subject:subject:Quotes_Subject:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,2,'quotes:quotestage:quotestage:Quotes_Quote_Stage:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,3,'quotes:potentialid:potential_id:Quotes_Potential_Name:I')",
		"insert into vtiger_cvcolumnlist values ($cvid,4,'quotes:accountid:account_id:Quotes_Account_Name:I')",
		"insert into vtiger_cvcolumnlist values ($cvid,5,'quotes:total:hdnGrandTotal:Quotes_Total:I')",
		"insert into vtiger_cvcolumnlist values ($cvid,6,'crmentity:smownerid:assigned_user_id:Quotes_Assigned_To:V')"
			     );
foreach($insert_query_array12 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from vtiger_customview where viewname='All' and entitytype='Activities'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array13 = Array(
		"insert into vtiger_cvcolumnlist values ($cvid,0,'activity:status:taskstatus:Activities_Status:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,1,'activity:activitytype:activitytype:Activities_Type:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,2,'activity:subject:subject:Activities_Subject:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,3,'contactdetails:lastname:lastname:Activities_Contact_Name:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,4,'seactivityrel:crmid:parent_id:Activities_Related_To:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,5,'activity:date_start:date_start:Activities_Start_Date:D')",
		"insert into vtiger_cvcolumnlist values ($cvid,6,'activity:due_date:due_date:Activities_End_Date:D')",
		"insert into vtiger_cvcolumnlist values ($cvid,7,'crmentity:smownerid:assigned_user_id:Activities_Assigned_To:V')"
			     );
foreach($insert_query_array13 as $query)
{
	Execute($query);
}


$res=$conn->query("select cvid from vtiger_customview where viewname='All' and entitytype='Emails'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array14 = Array(
		"insert into vtiger_cvcolumnlist values ($cvid,0,'activity:subject:subject:Emails_Subject:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,1,'seactivityrel:crmid:parent_id:Emails_Related_To:I')",
		"insert into vtiger_cvcolumnlist values ($cvid,2,'activity:date_start:date_start:Emails_Date_Sent:D')",
		"insert into vtiger_cvcolumnlist values ($cvid,3,'crmentity:smownerid:assigned_user_id:Emails_Assigned_To:V')"
			     );
foreach($insert_query_array14 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from vtiger_customview where viewname='All' and entitytype='Invoice'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array15 = Array(
		"insert into vtiger_cvcolumnlist values ($cvid,0,'crmentity:crmid::Invoice_Invoice_Id:I')",
		"insert into vtiger_cvcolumnlist values ($cvid,1,'invoice:subject:subject:Invoice_Subject:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,2,'invoice:salesorderid:salesorder_id:Invoice_Sales_Order:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,3,'invoice:invoicestatus:invoicestatus:Invoice_Status:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,4,'invoice:total:hdnGrandTotal:Invoice_Total:I')",
		"insert into vtiger_cvcolumnlist values ($cvid,5,'crmentity:smownerid:assigned_user_id:Invoice_Assigned_To:V')"
			     );
foreach($insert_query_array15 as $query)
{
	Execute($query);
}

	     
$res=$conn->query("select cvid from vtiger_customview where viewname='All' and entitytype='Notes'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array16 = Array(
		"insert into vtiger_cvcolumnlist values ($cvid,0,'notes:title:title:Notes_Title:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,1,'notes:contact_id:contact_id:Notes_Contact_Name:I')",
		"insert into vtiger_cvcolumnlist values ($cvid,2,'senotesrel:crmid:crmid:Notes_Related_To:I')",
		"insert into vtiger_cvcolumnlist values ($cvid,3,'notes:filename:filename:Notes_File:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,4,'crmentity:modifiedtime:modifiedtime:Notes_Modified_Time:V')"
			     );
foreach($insert_query_array16 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from vtiger_customview where viewname='All' and entitytype='PriceBooks'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array17 = Array(
		"insert into vtiger_cvcolumnlist values ($cvid,1,'pricebook:bookname:bookname:PriceBooks_Price_Book_Name:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,2,'pricebook:active:active:PriceBooks_Active:V')"
			     );
foreach($insert_query_array17 as $query)
{
	Execute($query);
}


$res=$conn->query("select cvid from vtiger_customview where viewname='All' and entitytype='Products'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array18 = Array(
	"insert into vtiger_cvcolumnlist values ($cvid,0,'products:productname:productname:Products_Product_Name:V')",
	"insert into vtiger_cvcolumnlist values ($cvid,1,'products:productcode:productcode:Products_Product_Code:V')",
	"insert into vtiger_cvcolumnlist values ($cvid,2,'products:commissionrate:commissionrate:Products_Commission_Rate:V')",
	"insert into vtiger_cvcolumnlist values ($cvid,3,'products:qty_per_unit:qty_per_unit:Products_Qty/Unit:V')",
	"insert into vtiger_cvcolumnlist values ($cvid,4,'products:unit_price:unit_price:Products_Unit_Price:V')"
			     );
foreach($insert_query_array18 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from vtiger_customview where viewname='All' and entitytype='PurchaseOrder'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array19 = Array(
	"insert into vtiger_cvcolumnlist values($cvid,0,'crmentity:crmid::PurchaseOrder_Order_Id:I')",
	"insert into vtiger_cvcolumnlist values($cvid,1,'purchaseorder:subject:subject:PurchaseOrder_Subject:V')",
	"insert into vtiger_cvcolumnlist values($cvid,2,'purchaseorder:vendorid:vendor_id:PurchaseOrder_Vendor_Name:I')",
	"insert into vtiger_cvcolumnlist values($cvid,3,'purchaseorder:tracking_no:tracking_no:PurchaseOrder_Tracking_Number:V')",
	"insert into vtiger_cvcolumnlist values($cvid,4,'crmentity:smownerid:assigned_user_id:PurchaseOrder_Assigned_To:V')"
			     );
foreach($insert_query_array19 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from vtiger_customview where viewname='All' and entitytype='SalesOrder'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array20 = Array(
		"insert into vtiger_cvcolumnlist values ($cvid,0,'crmentity:crmid::SalesOrder_Order_Id:I')",
		"insert into vtiger_cvcolumnlist values ($cvid,1,'salesorder:subject:subject:SalesOrder_Subject:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,2,'account:accountid:account_id:SalesOrder_Account_Name:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,3,'quotes:quoteid:quote_id:SalesOrder_Quote_Name:I')",
		"insert into vtiger_cvcolumnlist values ($cvid,4,'salesorder:total:hdnGrandTotal:SalesOrder_Total:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,5,'crmentity:smownerid:assigned_user_id:SalesOrder_Assigned_To:V')"
			     );
foreach($insert_query_array20 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from vtiger_customview where viewname='All' and entitytype='Vendors'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array21 = Array(
			"insert into vtiger_cvcolumnlist values ($cvid,0,'vendor:vendorname:vendorname:Vendors_Vendor_Name:V')",
			"insert into vtiger_cvcolumnlist values ($cvid,1,'vendor:phone:phone:Vendors_Phone:V')",
			"insert into vtiger_cvcolumnlist values ($cvid,2,'vendor:email:email:Vendors_Email:V')",
			"insert into vtiger_cvcolumnlist values ($cvid,3,'vendor:category:category:Vendors_Category:V')"
			     );
foreach($insert_query_array21 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from vtiger_customview where viewname='All' and entitytype='Faq'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array22 = Array(
		"insert into vtiger_cvcolumnlist values ($cvid,0,'faq:id::Faq_FAQ_Id:I')",
		"insert into vtiger_cvcolumnlist values ($cvid,1,'faq:question:question:Faq_Question:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,2,'faq:category:faqcategories:Faq_Category:V')",
		"insert into vtiger_cvcolumnlist values ($cvid,3,'faq:product_id:product_id:Faq_Product_Name:I')",
		"insert into vtiger_cvcolumnlist values ($cvid,4,'crmentity:createdtime:createdtime:Faq_Created_Time:D')",
		"insert into vtiger_cvcolumnlist values ($cvid,5,'crmentity:modifiedtime:modifiedtime:Faq_Modified_Time:D')"
			     );
foreach($insert_query_array22 as $query)
{
	Execute($query);
}


$update_query_array3 = Array(
				"update vtiger_field set uitype=53 where vtiger_tabid=2 and columnname='smownerid'",
				"update vtiger_field set uitype=53 where vtiger_tabid=4 and columnname='smownerid'",
				"update vtiger_field set uitype=53 where vtiger_tabid=20 and columnname='smownerid'",
				"update vtiger_field set uitype=53 where vtiger_tabid=22 and columnname='smownerid'",
				"update vtiger_field set uitype=53 where vtiger_tabid=23 and columnname='smownerid'"
			    );
foreach($update_query_array3 as $query)
{
	Execute($query);
}

$create_query6 = "CREATE TABLE vtiger_accountgrouprelation ( vtiger_accountid int(19) NOT NULL default '0', groupname varchar(100) default NULL, PRIMARY KEY  (`accountid`))";
Execute($create_query6);

$alter_query_array8 = Array(
				"alter vtiger_table vtiger_accountgrouprelation ADD CONSTRAINT fk_accountgrouprelation FOREIGN KEY (accountid) REFERENCES vtiger_account(accountid) ON DELETE CASCADE",
				"alter vtiger_table vtiger_accountgrouprelation ADD CONSTRAINT fk_accountgrouprelation2 FOREIGN KEY (groupname) REFERENCES vtiger_groups(name) ON DELETE CASCADE"
			   );
foreach($alter_query_array8 as $query)
{
	Execute($query);
}

$create_query7 = "CREATE TABLE vtiger_contactgrouprelation ( contactid int(19) NOT NULL default '0', groupname varchar(100) default NULL, PRIMARY KEY  (`contactid`))";
Execute($create_query7);

$alter_query_array9 = Array(
				"alter vtiger_table vtiger_contactgrouprelation ADD CONSTRAINT fk_contactgrouprelation FOREIGN KEY (contactid) REFERENCES vtiger_contactdetails(contactid) ON DELETE CASCADE",
				"alter vtiger_table vtiger_contactgrouprelation ADD CONSTRAINT fk_contactgrouprelation2 FOREIGN KEY (groupname) REFERENCES vtiger_groups(name) ON DELETE CASCADE"
			   );
foreach($alter_query_array9 as $query)
{
	Execute($query);
}


$create_query10 = "CREATE TABLE vtiger_potentialgrouprelation ( vtiger_potentialid int(19) NOT NULL default '0', groupname varchar(100) default NULL, PRIMARY KEY  (`potentialid`))";
Execute($create_query10);

$alter_query_array10 = Array(
				"alter vtiger_table vtiger_potentialgrouprelation ADD CONSTRAINT fk_potentialgrouprelation FOREIGN KEY (potentialid) REFERENCES vtiger_potential(potentialid) ON DELETE CASCADE",
				"alter vtiger_table vtiger_potentialgrouprelation ADD CONSTRAINT fk_potentialgrouprelation2 FOREIGN KEY (groupname) REFERENCES vtiger_groups(name) ON DELETE CASCADE"
			    );
foreach($alter_query_array10 as $query)
{
	Execute($query);
}

$create_query11 = "CREATE TABLE vtiger_quotegrouprelation ( quoteid int(19) NOT NULL default '0', groupname varchar(100) default NULL, PRIMARY KEY  (`quoteid`) )";
Execute($create_query11);

$alter_query_array11 = Array(
				"alter vtiger_table vtiger_quotegrouprelation ADD CONSTRAINT fk_quotegrouprelation FOREIGN KEY (quoteid) REFERENCES vtiger_quotes(quoteid) ON DELETE CASCADE",
				"alter vtiger_table vtiger_quotegrouprelation ADD CONSTRAINT fk_quotegrouprelation2 FOREIGN KEY (groupname) REFERENCES vtiger_groups(name) ON DELETE CASCADE"
			    );
foreach($alter_query_array11 as $query)
{
	Execute($query);
}

$create_query12 = "CREATE TABLE vtiger_sogrouprelation ( vtiger_salesorderid int(19) NOT NULL default '0', groupname varchar(100) default NULL, PRIMARY KEY  (`salesorderid`) )";
Execute($create_query12);

$alter_query_array12 = Array(
				"alter vtiger_table vtiger_sogrouprelation ADD CONSTRAINT fk_sogrouprelation FOREIGN KEY (salesorderid) REFERENCES vtiger_salesorder(salesorderid) ON DELETE CASCADE",
				"alter vtiger_table vtiger_sogrouprelation ADD CONSTRAINT fk_sogrouprelation2 FOREIGN KEY (groupname) REFERENCES vtiger_groups(name) ON DELETE CASCADE"
			    );
foreach($alter_query_array12 as $query)
{
	Execute($query);
}

$create_query13 = "CREATE TABLE vtiger_invoicegrouprelation ( vtiger_invoiceid int(19) NOT NULL default '0',  groupname varchar(100) default NULL,  PRIMARY KEY  (`invoiceid`))";
Execute($create_query13);

$alter_query_array13 = Array(
				"alter vtiger_table vtiger_invoicegrouprelation ADD CONSTRAINT fk_invoicegrouprelation FOREIGN KEY (invoiceid) REFERENCES vtiger_invoice(invoiceid) ON DELETE CASCADE",
				"alter vtiger_table vtiger_invoicegrouprelation ADD CONSTRAINT fk_invoicegrouprelation2 FOREIGN KEY (groupname) REFERENCES vtiger_groups(name) ON DELETE CASCADE"
			    );
foreach($alter_query_array13 as $query)
{
	Execute($query);
}

$create_query14 = "CREATE TABLE vtiger_pogrouprelation ( vtiger_purchaseorderid int(19) NOT NULL default '0', groupname varchar(100) default NULL, PRIMARY KEY  (`purchaseorderid`))";
Execute($create_query14);

$alter_query_array14 = Array(
				"alter vtiger_table vtiger_pogrouprelation ADD CONSTRAINT fk_pogrouprelation FOREIGN KEY (purchaseorderid) REFERENCES vtiger_purchaseorder(purchaseorderid) ON DELETE CASCADE",
				"alter vtiger_table vtiger_pogrouprelation ADD CONSTRAINT fk_productgrouprelation2 FOREIGN KEY (groupname) REFERENCES vtiger_groups(name) ON DELETE CASCADE"
			    );
foreach($alter_query_array14 as $query)
{
	Execute($query);
}

$alter_query1 = "ALTER TABLE vtiger_users ADD column lead_view VARCHAR(25) DEFAULT 'Today' AFTER homeorder";
Execute($alter_query1);

$update_query1 = "update vtiger_users set homeorder = 'ALVT,PLVT,QLTQ,CVLVT,HLT,OLV,GRT,OLTSO,ILTI,MNL'";
Execute($update_query1);

$alter_query2 = "ALTER TABLE vtiger_products change column imagename imagename text";
Execute($alter_query2);

$alter_query3 = "alter vtiger_table vtiger_systems modify server varchar(50), modify server_username varchar(50), modify server_password varchar(50), add column smtp_auth char(5)";
Execute($alter_query3);

$alter_query_array15 = Array( 
				"alter vtiger_table vtiger_users add column imagename varchar(250)",
				"alter vtiger_table vtiger_users add column tagcloud varchar(250)"
			    );
foreach($alter_query_array15 as $query)
{
	Execute($query);
}

$alter_query_array16 = Array(
			"alter vtiger_table vtiger_systems change column server server varchar(80) default NULL",
			"alter vtiger_table vtiger_systems change column server_username server_username varchar(80) default NULL"
			    );
foreach($alter_query_array16 as $query)
{
	Execute($query);
}


$create_query15 = "create vtiger_table vtiger_portal(portalid int(19), vtiger_portalname varchar(255) NOT NULL, vtiger_portalurl varchar(255) NOT NULL,sequence int(3) NOT NULL, PRIMARY KEY (portalid))";
Execute($create_query15);

$alter_query_array = Array( 
				"alter vtiger_table vtiger_attachments drop column vtiger_attachmentsize",
				"alter vtiger_table vtiger_attachments drop column attachmentcontents"
			    );
foreach($alter_query_array as $query)
{
	Execute($query);
}

$alter_query = "ALTER TABLE vtiger_field ADD column info_type varchar(20) default NULL after quickcreatesequence";
Execute($alter_query);

//$update_query2 = "UPDATE vtiger_field SET vtiger_fieldlabel = 'Reference' WHERE vtiger_tabid = 4 and vtiger_tablename = 'contactdetails' and vtiger_fieldname='reference'";
//changed in 24-04-06 because the reference has not been entered into the vtiger_field vtiger_table. 
$update_query2 = "insert into vtiger_field values (4,".$conn->getUniqueID("field").",'reference','contactdetails',1,'56','reference','Reference',1,0,0,10,23,4,1,'C~O',1,null,'ADV')";
Execute($update_query2);

$update_query_array4 = Array(
				"UPDATE vtiger_field SET info_type = 'BAS'",

				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 7 and vtiger_fieldlabel = 'Website'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 7 and vtiger_fieldlabel = 'Industry'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 7 and vtiger_fieldlabel = 'Annual Revenue'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 7 and vtiger_fieldlabel = 'No Of Employees'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 7 and vtiger_fieldlabel = 'Yahoo Id'",

				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 6 and vtiger_fieldlabel = 'Ticker Symbol'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 6 and vtiger_fieldlabel = 'Other Phone'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 6 and vtiger_fieldlabel = 'Member Of'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 6 and vtiger_fieldlabel = 'Employees'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 6 and vtiger_fieldlabel = 'Other Email'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 6 and vtiger_fieldlabel = 'Ownership'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 6 and vtiger_fieldlabel = 'Rating'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 6 and vtiger_fieldlabel = 'industry'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 6 and vtiger_fieldlabel = 'SIC Code'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 6 and vtiger_fieldlabel = 'Type'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 6 and vtiger_fieldlabel = 'Annual Revenue'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 6 and vtiger_fieldlabel = 'Email Opt Out'",

				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 4 and vtiger_fieldlabel = 'Home Phone'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 4 and vtiger_fieldlabel = 'Department'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 4 and vtiger_fieldlabel = 'Birthdate'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 4 and vtiger_fieldlabel = 'Email'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 4 and vtiger_fieldlabel = 'Reports To'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 4 and vtiger_fieldlabel = 'Assistant'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 4 and vtiger_fieldlabel = 'Yahoo Id'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 4 and vtiger_fieldlabel = 'Assistant Phone'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 4 and vtiger_fieldlabel = 'Do Not Call'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 4 and vtiger_fieldlabel = 'Email Opt Out'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 4 and vtiger_fieldlabel = 'Reference'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 4 and vtiger_fieldlabel = 'Portal User'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 4 and vtiger_fieldlabel = 'Support Start Date'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 4 and vtiger_fieldlabel = 'Support End Date'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 4 and vtiger_fieldlabel = 'Contact Image'",

				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 14 and vtiger_fieldlabel = 'Usage Unit'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 14 and vtiger_fieldlabel = 'Qty/Unit'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 14 and vtiger_fieldlabel = 'Qty In Stock'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 14 and vtiger_fieldlabel = 'Reorder Level'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 14 and vtiger_fieldlabel = 'Handler'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 14 and vtiger_fieldlabel = 'Qty In Demand'",
				"UPDATE vtiger_field SET info_type = 'ADV' WHERE vtiger_tabid = 14 and vtiger_fieldlabel = 'Product Image'"
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

$create_query20 = "CREATE TABLE vtiger_freetags ( id int(19) NOT NULL, tag varchar(50) NOT NULL default '', raw_tag varchar(50) NOT NULL default '', PRIMARY KEY  (id)) TYPE=MyISAM";
Execute($create_query20);

$create_query21 = "CREATE TABLE vtiger_freetagged_objects ( tag_id int(19) NOT NULL default '0', tagger_id int(19) NOT NULL default '0', object_id int(19) NOT NULL default '0', tagged_on datetime NOT NULL default '0000-00-00 00:00:00', module varchar(50) NOT NULL default '', PRIMARY KEY  (`tag_id`,`tagger_id`,`object_id`), KEY `tag_id_index` (`tag_id`), KEY `tagger_id_index` (`tagger_id`),  KEY `object_id_index` (`object_id`)
) TYPE=MyISAM";
Execute($create_query21);
  
$alter_query4 = "alter vtiger_table vtiger_profile add column description text";
Execute($alter_query4);

$alter_query5 = "alter vtiger_table vtiger_contactdetails add column imagename varchar(250) after vtiger_currency";
Execute($alter_query5);

$alter_query = "ALTER TABLE vtiger_contactdetails ADD column reference varchar(3) default NULL after imagename";
Execute($alter_query);

$insert_query_array23 = Array(
				"insert into vtiger_blocks values(75,4,'LBL_IMAGE_INFORMATION',5,0,0,0,0,0)",
				"insert into vtiger_field values(4,".$conn->getUniqueID("field").",'imagename','contactdetails',1,'69','imagename','Contact Image',1,0,0,100,1,75,1,'V~O',1,null,'ADV')",

				"Insert into vtiger_field values(9,".$conn->getUniqueID("field").",'visibility','activity',1,15,'visibility','Visibility',1,0,0,100,17,19,3,'V~O',1,null,'BAS')",
				"Insert into vtiger_field values(16,".$conn->getUniqueID("field").",'visibility','activity',1,15,'visibility','Visibility',1,0,0,100,19,41,1,'V~O',1,null,'BAS')"
			     );
foreach($insert_query_array23 as $query)
{
	Execute($query);
}

$alter_query6 = "alter vtiger_table vtiger_activity add column vtiger_visibility varchar(50) NOT NULL after notime";
Execute($alter_query6);

$create_query22 = "CREATE TABLE `visibility` ( `visibilityid` int(19) NOT NULL auto_increment, `visibility` varchar(200) NOT NULL default '', `sortorderid` int(19) NOT NULL default '0', `presence` int(1) NOT NULL default '1', PRIMARY KEY  (`visibilityid`), UNIQUE KEY `Visibility_VLY` (`visibility`)) ENGINE=InnoDB";
Execute($create_query22);


$create_query23 = "CREATE TABLE `sharedcalendar` ( `userid` int(19) NOT NULL default '0',  `sharedid` int(19) NOT NULL default '0', PRIMARY KEY  (`userid`,`sharedid`)) ENGINE=MyISAM";
Execute($create_query23);

$insert_query6 = "INSERT INTO vtiger_tab VALUES(26,'Campaigns',0,23,'Campaigns',null,null,1)";
Execute($insert_query6);
$insert_query7 = "INSERT INTO vtiger_parenttabrel VALUES(2,26,1)";
Execute($insert_query7);

$insert_query8 = "insert into vtiger_blocks values(76,26,'LBL_CAMPAIGN_INFORMATION',1,0,0,0,0,0)";
Execute($insert_query8);
$insert_query8 = "insert into vtiger_blocks values (77,26,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)";
Execute($insert_query8);
$insert_query9 = "insert into vtiger_blocks values(78,26,'LBL_DESCRIPTION_INFORMATION',3,0,0,0,0,0)";
Execute($insert_query9);

$insert_query_array24 = Array(
	"insert into vtiger_field values (26,".$conn->getUniqueID("field").",'campaignname','campaign',1,'2','campaignname','Campaign Name',1,0,0,100,1,76,1,'V~M',0,1,'BAS')",
	"insert into vtiger_field values (26,".$conn->getUniqueID("field").",'campaigntype','campaign',1,15,'campaigntype','Campaign Type',1,0,0,100,2,76,1,'V~O',0,5,'BAS')",
	"insert into vtiger_field values (26,".$conn->getUniqueID("field").",'product_id','campaign',1,59,'product_id','Product',1,0,0,100,3,76,1,'I~O',0,5,'BAS')",
	"insert into vtiger_field values (26,".$conn->getUniqueID("field").",'campaignstatus','campaign',1,15,'campaignstatus','Campaign Status',1,0,0,100,4,76,1,'V~O',0,5,'BAS')",
	"insert into vtiger_field values (26,".$conn->getUniqueID("field").",'closingdate','campaign',1,'23','closingdate','Expected Close Date',1,0,0,100,5,76,1,'D~M',0,3,'BAS')",
	"insert into vtiger_field values (26,".$conn->getUniqueID("field").",'expectedrevenue','campaign',1,'1','expectedrevenue','Expected Revenue',1,0,0,100,6,76,1,'I~O',1,null,'BAS')",
	"insert into vtiger_field values (26,".$conn->getUniqueID("field").",'budgetcost','campaign',1,'1','budgetcost','Budget Cost',1,0,0,100,7,76,1,'I~O',1,null,'BAS')",
	"insert into vtiger_field values (26,".$conn->getUniqueID("field").",'actualcost','campaign',1,'1','actualcost','Actual Cost',1,0,0,100,8,76,1,'I~O',1,null,'BAS')",
	"insert into vtiger_field values (26,".$conn->getUniqueID("field").",'expectedresponse','campaign',1,'15','expectedresponse','Expected Response',1,0,0,100,9,76,1,'V~O',0,4,'BAS')",
	"insert into vtiger_field values (26,".$conn->getUniqueID("field").",'smownerid','crmentity',1,'53','assigned_user_id','Assigned To',1,0,0,100,10,76,1,'V~M',1,null,'BAS')",
	"insert into vtiger_field values (26,".$conn->getUniqueID("field").",'numsent','campaign',1,'9','numsent','Num Sent',1,0,0,100,11,76,1,'N~O',1,null,'BAS')",
	"insert into vtiger_field values (26,".$conn->getUniqueID("field").",'sponsor','campaign',1,'1','sponsor','Sponsor',1,0,0,100,12,76,1,'V~O',1,null,'BAS')",
	"insert into vtiger_field values (26,".$conn->getUniqueID("field").",'targetaudience','campaign',1,'1','targetaudience','Target Audience',1,0,0,100,13,76,1,'V~O',1,null,'BAS')",
	"insert into vtiger_field values (26,".$conn->getUniqueID("field").",'targetsize','campaign',1,'1','targetsize','TargetSize',1,0,0,100,14,76,1,'N~O',1,null,'BAS')",
	"insert into vtiger_field values (26,".$conn->getUniqueID("field").",'expectedresponsecount','campaign',1,'1','expectedresponsecount','Expected Response Count',1,0,0,100,17,76,1,'N~O',1,null,'BAS')",
	"insert into vtiger_field values (26,".$conn->getUniqueID("field").",'expectedsalescount','campaign',1,'1','expectedsalescount','Expected Sales Count',1,0,0,100,15,76,1,'N~O',1,null,'BAS')",
	"insert into vtiger_field values (26,".$conn->getUniqueID("field").",'expectedroi','campaign',1,'1','expectedroi','Expected ROI',1,0,0,100,19,76,1,'N~O',1,null,'BAS')",
	"insert into vtiger_field values (26,".$conn->getUniqueID("field").",'actualresponsecount','campaign',1,'1','actualresponsecount','Actual Response Count',1,0,0,100,18,76,1,'N~O',1,null,'BAS')",
	"insert into vtiger_field values (26,".$conn->getUniqueID("field").",'actualsalescount','campaign',1,'1','actualsalescount','Actual Sales Count',1,0,0,100,16,76,1,'N~O',1,null,'BAS')",
	"insert into vtiger_field values (26,".$conn->getUniqueID("field").",'actualroi','campaign',1,'1','actualroi','Actual ROI',1,0,0,100,20,76,1,'N~O',1,null,'BAS')",
	"insert into vtiger_field values (26,".$conn->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,15,76,2,'T~O',1,null,'BAS')",
	"insert into vtiger_field values (26,".$conn->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,16,76,2,'T~O',1,null,'BAS')",
	"insert into vtiger_field values (26,".$conn->getUniqueID("field").",'description','crmentity',1,'19','description','Description',1,0,0,100,1,78,1,'V~O',1,null,'BAS')"
			     );
foreach($insert_query_array24 as $query)
{
	Execute($query);
}

$insert_query_array25 = Array(
	"insert into vtiger_relatedlists values (".$conn->getUniqueID('relatedlists').",".getTabid("Campaigns").",".getTabid("Contacts").",'get_contacts',1,'Contacts',0)",
	"insert into vtiger_relatedlists values (".$conn->getUniqueID('relatedlists').",".getTabid("Campaigns").",".getTabid("Leads").",'get_leads',2,'Leads',0)"
			     );
foreach($insert_query_array25 as $query)
{
	Execute($query);
}


$insert_query_array26 = Array(
	"insert into vtiger_field values (7,".$conn->getUniqueID("field").",'campaignid','leaddetails',1,'51','campaignid','Campaign Name',1,0,0,100,6,13,3,'I~O',1,null,'BAS')",
	"insert into vtiger_field values (4,".$conn->getUniqueID("field").",'campaignid','contactdetails',1,'51','campaignid','Campaign Name',1,0,0,100,6,4,3,'I~O',1,null,'BAS')"
			     );
foreach($insert_query_array26 as $query)
{
	Execute($query);
}

$create_query24 = "
CREATE TABLE `campaign` (
   `campaignname` varchar(255) default NULL,
   `campaigntype` varchar(255) default NULL,
   `campaignstatus` varchar(255) default NULL,
   `expectedrevenue` int(19) default NULL,
   `budgetcost` int(19) default NULL,
   `actualcost` int(19) default NULL,
   `expectedresponse` varchar(255) default NULL,
   `numsent` decimal(11,0) default NULL,
   `product_id` int(19) default NULL,
   `sponsor` varchar(255) default NULL,
   `targetaudience` varchar(255) default NULL,
   `targetsize` int(19) default NULL,
   `expectedresponsecount` int(19) default NULL,
   `expectedsalescount` int(19) default NULL,
   `expectedroi` int(19) default NULL,
   `actualresponsecount` int(19) default NULL,
   `actualsalescount` int(19) default NULL,
   `actualroi` int(19) default NULL,
   `campaignid` int(19) NOT NULL,
   `closingdate` date default NULL,
    PRIMARY KEY  (`campaignid`),
    KEY `idx_campaignstatus` (`campaignstatus`),
    KEY `idx_campaignname` (`campaignname`),
    KEY `idx_campaignid` (`campaignid`)
) ENGINE=InnoDB
		  ";
Execute($create_query24);


//Added on 06-06-06
$create_query25 = "CREATE TABLE `campaigncontrel` (
	  `campaignid` int(19) NOT NULL default '0',
	    `contactid` int(19) NOT NULL default '0',
	      PRIMARY KEY  (`campaignid`),
	        KEY `campaigncontrel_contractid_idx` (`contactid`)
	) ENGINE=InnoDB";
/*
$create_query25 = "CREATE TABLE `campaigncontrel` (
  `campaignid` int(19) NOT NULL default '0',
  `contactid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`campaignid`),
  KEY `CampaignContRel_IDX1` (`contactid`),
  CONSTRAINT `fk_CampaignContRel2` FOREIGN KEY (`contactid`) REFERENCES `contactdetails` (`contactid`) ON DELETE CASCADE,
  CONSTRAINT `fk_CampaignContRel1` FOREIGN KEY (`campaignid`) REFERENCES `campaign` (`campaignid`) ON DELETE CASCADE
) ENGINE=InnoDB";
*/
Execute($create_query25);

//Added on 06-06-06
$create_table_query = "CREATE TABLE `campaigngrouprelation` (
	  `campaignid` int(19) NOT NULL,
	    `groupname` varchar(100) default NULL,
	      PRIMARY KEY  (`campaignid`),
	        KEY `campaigngrouprelation_IDX1` (`groupname`)
	) ENGINE=InnoDB";
/*
$create_table_query = "
CREATE TABLE `campaigngrouprelation` (
       `campaignid` int(19) NOT NULL,
       `groupname` varchar(100) default NULL,
	PRIMARY KEY  (`campaignid`),
	KEY `campaigngrouprelation_IDX1` (`groupname`),
	CONSTRAINT `fk_campaigngrouprelation2` FOREIGN KEY (`groupname`) REFERENCES `groups` (`groupname`) ON DELETE CASCADE,
	CONSTRAINT `fk_campaigngrouprelation1` FOREIGN KEY (`campaignid`) REFERENCES `campaign` (`campaignid`) ON DELETE CASCADE
) ENGINE=InnoDB";
*/
Execute($create_table_query);


//Added on 06-06-06
$create_query26 = "CREATE TABLE `campaignleadrel` (
			`campaignid` int(19) NOT NULL default '0',
			`leadid` int(19) NOT NULL default '0',
			PRIMARY KEY  (`campaignid`),
			KEY `campaignleadrel_leadid_campaignid_idx` (`leadid`,`campaignid`)
		   ) ENGINE=InnoDB";
/*
$create_query26 = "CREATE TABLE `campaignleadrel` (
  `campaignid` int(19) NOT NULL default '0',
  `leadid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`campaignid`),
  KEY `CampaignLeadRel_IDX1` (`leadid`,`campaignid`),
  CONSTRAINT `fk_CampaignLeadRel1234` FOREIGN KEY (`campaignid`) REFERENCES `campaign` (`campaignid`) ON DELETE CASCADE,
  CONSTRAINT `fk_CampaignLeadRel2423` FOREIGN KEY (`leadid`) REFERENCES `leaddetails` (`leadid`) ON DELETE CASCADE
) ENGINE=InnoDB";
*/
Execute($create_query26);

$create_table_query1 = "CREATE TABLE `campaignscf` (
  `campaignid` int(19) NOT NULL default '0',
   PRIMARY KEY  (`campaignid`),
   CONSTRAINT `fk_CampaignsCF` FOREIGN KEY (`campaignid`) REFERENCES `campaign` (`campaignid`) ON DELETE CASCADE
) ENGINE=InnoDB";
Execute($create_table_query1);

$alter_query_array18 = Array(
				"alter vtiger_table vtiger_leaddetails add column vtiger_campaignid int(19) default NULL after leadid",
				"alter vtiger_table vtiger_contactdetails add column  vtiger_campaignid int(19) default NULL after vtiger_accountid",
				//"alter vtiger_table vtiger_notes drop PRIMARY KEY contact_id",
				"alter vtiger_table vtiger_notes drop PRIMARY KEY , add primary key(notesid)",
				"update vtiger_field set uitype=99 where vtiger_fieldname='update_log' and vtiger_tabid=13"
			    );
foreach($alter_query_array18 as $query)
{
	Execute($query);
}



//echo "<br><br><b>Database Modifications for Indexing and some missded vtiger_tables starts here.....</b><br>";
//Added queries which are for indexing and the missing vtiger_tables - Mickie - on 06-04-2006

$query_array = Array(

"ALTER TABLE `accountgrouprelation` DROP INDEX `fk_accountgrouprelation2`",
//"ALTER TABLE `accountscf` DROP COLUMN `cf_356`",
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
//"ALTER TABLE `leadscf` DROP COLUMN `cf_354`",
//"ALTER TABLE `leadscf` DROP COLUMN `cf_358`",
//"ALTER TABLE `leadscf` DROP COLUMN `cf_360`",
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
) ENGINE=InnoDB",

"CREATE TABLE `campaigntype` (
  `campaigntypeid` int(19) NOT NULL auto_increment,
  `campaigntype` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`campaigntypeid`),
  UNIQUE KEY `Campaigntype_UK01` (`campaigntype`)
) ENGINE=InnoDB",

"CREATE TABLE `currency_info_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1",

"CREATE TABLE `datashare_module_rel` (
  `shareid` int(19) NOT NULL,
  `tabid` int(19) NOT NULL,
  `relationtype` varchar(200) default NULL,
  PRIMARY KEY  (`shareid`),
  KEY `idx_datashare_module_rel_tabid` (`tabid`),
) ENGINE=InnoDB",

//Added on 06-06-06
"CREATE TABLE `datashare_grp2grp` (
	  `shareid` int(19) NOT NULL,
	    `share_groupid` int(19) default NULL,
	      `to_groupid` int(19) default NULL,
	        `permission` int(19) default NULL,
		  PRIMARY KEY  (`shareid`),
		    KEY `datashare_grp2grp_share_groupid_idx` (`share_groupid`),
		      KEY `datashare_grp2grp_to_groupid_idx` (`to_groupid`)
	      ) ENGINE=InnoDB",
/*
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
*/

"CREATE TABLE `datashare_grp2role` (
	  `shareid` int(19) NOT NULL,
	    `share_groupid` int(19) default NULL,
	      `to_roleid` varchar(255) default NULL,
	        `permission` int(19) default NULL,
		  PRIMARY KEY  (`shareid`),
		    KEY `idx_datashare_grp2role_share_groupid` (`share_groupid`),
		      KEY `idx_datashare_grp2role_to_roleid` (`to_roleid`)
	      ) ENGINE=InnoDB",
/*
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
*/

//Added on 06-06-06
"CREATE TABLE `datashare_grp2rs` (
	  `shareid` int(19) NOT NULL,
	    `share_groupid` int(19) default NULL,
	      `to_roleandsubid` varchar(255) default NULL,
	        `permission` int(19) default NULL,
		  PRIMARY KEY  (`shareid`),
		    KEY `datashare_grp2rs_share_groupid_idx` (`share_groupid`),
		      KEY `datashare_grp2rs_to_roleandsubid_idx` (`to_roleandsubid`)
	      ) ENGINE=InnoDB",
/*
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
*/

"CREATE TABLE `datashare_relatedmodule_permission` (
  `shareid` int(19) NOT NULL,
  `datashare_relatedmodule_id` int(19) NOT NULL,
  `permission` int(19) default NULL,
  PRIMARY KEY  (`shareid`,`datashare_relatedmodule_id`),
  KEY `datashare_relatedmodule_permission_UK1` (`shareid`,`permission`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

//Added on 06-06-06
"CREATE TABLE `datashare_relatedmodules` (
	  `datashare_relatedmodule_id` int(19) NOT NULL,
	    `tabid` int(19) default NULL,
	      `relatedto_tabid` int(19) default NULL,
	        PRIMARY KEY  (`datashare_relatedmodule_id`),
		  KEY `datashare_relatedmodules_tabid_idx` (`tabid`),
		    KEY `datashare_relatedmodules_relatedto_tabid_idx` (`relatedto_tabid`)
	    ) ENGINE=InnoDB",
/*
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
*/

"CREATE TABLE `datashare_relatedmodules_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1",

//Added on 06-06-06
"CREATE TABLE `datashare_role2group` (
	  `shareid` int(19) NOT NULL,
	    `share_roleid` varchar(255) default NULL,
	      `to_groupid` int(19) default NULL,
	        `permission` int(19) default NULL,
		  PRIMARY KEY  (`shareid`),
		    KEY `idx_datashare_role2group_share_roleid` (`share_roleid`),
		      KEY `idx_datashare_role2group_to_groupid` (`to_groupid`)
	      ) ENGINE=InnoDB",
/*
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
*/

//Added on 06-06-06
"CREATE TABLE `datashare_role2role` (
	  `shareid` int(19) NOT NULL,
	    `share_roleid` varchar(255) default NULL,
	      `to_roleid` varchar(255) default NULL,
	        `permission` int(19) default NULL,
		  PRIMARY KEY  (`shareid`),
		    KEY `datashare_role2role_share_roleid_idx` (`share_roleid`),
		      KEY `datashare_role2role_to_roleid_idx` (`to_roleid`)
	      ) ENGINE=InnoDB",
/*
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
*/

//Added on 06-06-06
"CREATE TABLE `datashare_role2rs` (
	  `shareid` int(19) NOT NULL,
	    `share_roleid` varchar(255) default NULL,
	      `to_roleandsubid` varchar(255) default NULL,
	        `permission` int(19) default NULL,
		  PRIMARY KEY  (`shareid`),
		    KEY `datashare_role2s_share_roleid_idx` (`share_roleid`),
		      KEY `datashare_role2s_to_roleandsubid_idx` (`to_roleandsubid`)
	      ) ENGINE=InnoDB",
/*
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
*/

"CREATE TABLE `datashare_rs2grp` (
  `shareid` int(19) NOT NULL,
  `share_roleandsubid` varchar(255) default NULL,
  `to_groupid` int(19) default NULL,
  `permission` int(19) default NULL,
  PRIMARY KEY  (`shareid`),
  KEY `datashare_rs2grp_share_roleandsubid_idx` (`share_roleandsubid`),
  KEY `datashare_rs2grp_to_groupid_idx` (`to_groupid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `datashare_rs2role` (
  `shareid` int(19) NOT NULL,
  `share_roleandsubid` varchar(255) default NULL,
  `to_roleid` varchar(255) default NULL,
  `permission` int(19) default NULL,
  PRIMARY KEY  (`shareid`),
  KEY `datashare_rs2role_share_roleandsubid_idx` (`share_roleandsubid`),
  KEY `datashare_rs2role_to_roleid_idx` (`to_roleid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `datashare_rs2rs` (
  `shareid` int(19) NOT NULL,
  `share_roleandsubid` varchar(255) default NULL,
  `to_roleandsubid` varchar(255) default NULL,
  `permission` int(19) default NULL,
  PRIMARY KEY  (`shareid`),
  KEY `datashare_rs2rs_share_roleandsubid_idx` (`share_roleandsubid`),
  KEY `idx_datashare_rs2rs_to_roleandsubid_idx` (`to_roleandsubid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `expectedresponse` (
  `expectedresponseid` int(19) NOT NULL auto_increment,
  `expectedresponse` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`expectedresponseid`),
  UNIQUE KEY `CampaignExpRes_UK01` (`expectedresponse`)
) ENGINE=InnoDB",

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
  KEY `tmp_read_group_rel_sharing_per_userid_sharedgroupid_tabid` (`userid`,`sharedgroupid`,`tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `tmp_read_group_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `sharedgroupid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`tabid`,`sharedgroupid`),
  KEY `tmp_read_group_sharing_per_userid_sharedgroupid_idx` (`userid`,`sharedgroupid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `tmp_read_user_rel_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `relatedtabid` int(11) NOT NULL,
  `shareduserid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`tabid`,`relatedtabid`,`shareduserid`),
  KEY `tmp_read_user_rel_sharing_per_userid_shared_reltabid_idx` (`userid`,`shareduserid`,`relatedtabid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `tmp_read_user_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `shareduserid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`tabid`,`shareduserid`),
  KEY `tmp_read_user_sharing_per_userid_shareduserid_idx` (`userid`,`shareduserid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `tmp_write_group_rel_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `relatedtabid` int(11) NOT NULL,
  `sharedgroupid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`tabid`,`relatedtabid`,`sharedgroupid`),
  KEY `tmp_write_group_rel_sharing_per_userid_sharedgroupid_tabid_idx` (`userid`,`sharedgroupid`,`tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `tmp_write_group_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `sharedgroupid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`tabid`,`sharedgroupid`),
  KEY `tmp_write_group_sharing_per_UK1` (`userid`,`sharedgroupid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `tmp_write_user_rel_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `relatedtabid` int(11) NOT NULL,
  `shareduserid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`tabid`,`relatedtabid`,`shareduserid`),
  KEY `tmp_write_user_rel_sharing_per_userid_sharduserid_tabid_idx` (`userid`,`shareduserid`,`tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1",

"CREATE TABLE `tmp_write_user_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `shareduserid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`tabid`,`shareduserid`),
  KEY `tmp_write_user_sharing_per_userid_shareduserid_idx` (`userid`,`shareduserid`)
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
"ALTER TABLE `mail_accounts` ADD COLUMN `ssltype` VARCHAR(50) DEFAULT NULL",
"ALTER TABLE `mail_accounts` ADD COLUMN `sslmeth` VARCHAR(50) DEFAULT NULL",
"ALTER TABLE `mail_accounts` ADD COLUMN `showbody` VARCHAR(10) DEFAULT NULL",
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
//"ALTER TABLE `purchaseorder` MODIFY COLUMN `quoteid` INTEGER(19) DEFAULT NULL UNIQUE",
//"ALTER TABLE `purchaseorder` MODIFY COLUMN `vendorid` INTEGER(19) DEFAULT NULL UNIQUE",
//"ALTER TABLE `purchaseorder` MODIFY COLUMN `contactid` INTEGER(19) DEFAULT NULL UNIQUE",
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
"ALTER TABLE `users` ADD COLUMN `defhomeview` VARCHAR(100) COLLATE latin1_swedish_ci DEFAULT 'home_metrics'",
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
"ALTER TABLE `currency_info` MODIFY COLUMN `currency_name` VARCHAR(100) COLLATE latin1_swedish_ci DEFAULT NULL",
"ALTER TABLE `currency_info` ADD COLUMN `id` INTEGER(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST",
"ALTER TABLE `currency_info` ADD COLUMN `conversion_rate` DECIMAL(10,3) DEFAULT NULL",
"ALTER TABLE `currency_info` ADD COLUMN `currency_status` VARCHAR(25) COLLATE latin1_swedish_ci DEFAULT NULL",
"ALTER TABLE `currency_info` ADD COLUMN `defaultid` VARCHAR(10) COLLATE latin1_swedish_ci NOT NULL DEFAULT '0'",
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
"ALTER TABLE `quotes` DROP INDEX vtiger_quotestage",
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


$conn->println("Database Modifications for 5.0(Alpha) Dev 3 ==> 5.0 Alpha (5) ends here.");


/************************* The following changes have been made after 5.0 Alpha 5 *************************/
$conn->println("Database Modifications after 5.0(Alpha 5) starts here.");


//Added on 22-04-06 - to add the Notify Owner vtiger_field in Contacts and Accounts
$notify_owner_array = Array(
	"update vtiger_field set sequence=26 where vtiger_tabid=4 and vtiger_fieldname='modifiedtime'",
	"update vtiger_field set sequence=25 where vtiger_tabid=4 and vtiger_fieldname='createdtime'",
	
	"insert into vtiger_field values(4,".$conn->getUniqueID("field").",'notify_owner','contactdetails',1,56,'notify_owner','Notify Owner',1,0,0,10,24,4,1,'C~O',1,NULL,'ADV')",
	"alter vtiger_table vtiger_contactdetails add column notify_owner varchar(3) default 0 after reference",

	"update vtiger_field set sequence=21 where vtiger_tabid=6 and vtiger_fieldname='modifiedtime'",
	"update vtiger_field set sequence=20 where vtiger_tabid=6 and vtiger_fieldname='createdtime'",
	"update vtiger_field set sequence=19 where vtiger_tabid=6 and vtiger_fieldname='assigned_user_id'",
	
	"insert into vtiger_field values(6,".$conn->getUniqueID("field").",'notify_owner','account',1,56,'notify_owner','Notify Owner',1,0,0,10,18,9,1,'C~O',1,NULL,'ADV')",
	"alter vtiger_table vtiger_account add column notify_owner varchar(3) default 0 after emailoptout"
			   );
foreach($notify_owner_array as $query)
{
	Execute($query);
}

//Added for RSS entries
$rss_insert_query = "insert into vtiger_field values (24,".$conn->getUniqueID("field").",'rsscategory','rss',1,'15','rsscategory','rsscategory',1,0,0,255,13,null,1,'V~O',1,null,'BAS')";
Execute($rss_insert_query);

//Quick Create Feature added for Vendor & PriceBook
$quickcreate_query = Array(
	"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 1 WHERE vtiger_tabid = 18 and vtiger_fieldname = 'vendorname'",
	"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 2 WHERE vtiger_tabid = 18 and vtiger_fieldname = 'phone'",
	"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 3 WHERE vtiger_tabid = 18 and vtiger_fieldname = 'email'",

	"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 1 WHERE vtiger_tabid = 19 and vtiger_fieldname = 'bookname'",
	"UPDATE vtiger_field SET quickcreate = 0,quickcreatesequence = 2 WHERE vtiger_tabid = 19 and vtiger_fieldname = 'active'"
			  );
foreach($quickcreate_query as $query)
{
	Execute($query);
}


//Added on 24-04-06 to populate vtiger_customview All for Campaign and webmails modules
$cvid1 = $conn->getUniqueID("customview");
$cvid2 = $conn->getUniqueID("customview");
$customview_query_array = Array(
	"insert into vtiger_customview(cvid,viewname,setdefault,setmetrics,entitytype) values(".$cvid1.",'All',1,0,'Campaigns')",
	"insert into vtiger_cvcolumnlist (cvid,columnindex,columnname) values (".$cvid1.",0,'campaign:campaignname:campaignname:Campaigns_Campaign_Name:V')",
	"insert into vtiger_cvcolumnlist (cvid,columnindex,columnname) values (".$cvid1.",1,'campaign:campaigntype:campaigntype:Campaigns_Campaign_Type:N')",
	"insert into vtiger_cvcolumnlist (cvid,columnindex,columnname) values (".$cvid1.",2,'campaign:campaignstatus:campaignstatus:Campaigns_Campaign_Status:N')",
	"insert into vtiger_cvcolumnlist (cvid,columnindex,columnname) values (".$cvid1.",3,'campaign:expectedrevenue:expectedrevenue:Campaigns_Expected_Revenue:V')",
	"insert into vtiger_cvcolumnlist (cvid,columnindex,columnname) values (".$cvid1.",4,'campaign:closingdate:closingdate:Campaigns_Expected_Close_Date:D')",
	"insert into vtiger_cvcolumnlist (cvid,columnindex,columnname) values (".$cvid1.",5,'crmentity:smownerid:assigned_user_id:Campaigns_Assigned_To:V')",


	"insert into vtiger_customview(cvid,viewname,setdefault,setmetrics,entitytype) values(".$cvid2.",'All',1,0,'Webmails')",
	"insert into vtiger_cvcolumnlist (cvid,columnindex,columnname) values (".$cvid2.",0,'subject:subject:subject:Subject:V')",
	"insert into vtiger_cvcolumnlist (cvid,columnindex,columnname) values (".$cvid2.",1,'from:fromname:fromname:From:N')",
	"insert into vtiger_cvcolumnlist (cvid,columnindex,columnname) values (".$cvid2.",2,'to:tpname:toname:To:N')",
	"insert into vtiger_cvcolumnlist (cvid,columnindex,columnname) values (".$cvid2.",3,'body:body:body:Body:V')"

			       );
foreach($customview_query_array as $query)
{
	Execute($query);
}


$query_array2 = Array(
				"INSERT INTO vtiger_parenttabrel VALUES(2,4,2)",
				"INSERT INTO vtiger_parenttabrel VALUES(2,6,3)",
				"update vtiger_cvcolumnlist set columnname ='crmentity:smownerid:assigned_user_id:Emails_Sender:V' where cvid=20 and columnindex=3",
				"update vtiger_field set sequence = 2 where columnname='filename' and vtiger_tablename = 'attachments'",
				"delete from vtiger_cvcolumnlist where columnname = 'seactivityrel:crmid:parent_id:Emails_Related_To:I'",
				"update vtiger_cvcolumnlist set columnindex = 1 where cvid=3 and columnindex=3",
				"update vtiger_field set info_type='ADV' where vtiger_tabid=18 and columnname in ('street','pobox','city','state','postalcode','country','description')",
				"update vtiger_field set info_type='ADV' where vtiger_tabid in (20,21,22,23) and columnname in ('description','terms_conditions')",

				"create vtiger_table vtiger_inventorytaxinfo (taxid int(3) NOT NULL auto_increment, taxname varchar(50) default NULL, percentage decimal(7,3) default NULL,  PRIMARY KEY  (taxid), KEY vtiger_inventorytaxinfo_taxname_idx (taxname))",
				"create vtiger_table vtiger_producttaxrel ( productid int(11) NOT NULL, taxid int(3) NOT NULL, taxpercentage decimal(7,3) default NULL, KEY vtiger_producttaxrel_productid_idx (productid), KEY vtiger_producttaxrel_taxid_idx (taxid))",

				"insert into vtiger_inventorytaxinfo values(".$conn->getUniqueID("inventorytaxinfo").",'VAT','4.5')",
				"insert into vtiger_inventorytaxinfo values(".$conn->getUniqueID("inventorytaxinfo").",'Sales','10')",
				"insert into vtiger_inventorytaxinfo values(".$conn->getUniqueID("inventorytaxinfo").",'Service','12.5')",
				"update vtiger_field set uitype=83, vtiger_tablename='producttaxrel' where vtiger_tabid=14 and vtiger_fieldname='taxclass'",
				"insert into vtiger_moduleowners values(".$this->localGetTabID('Campaigns').",1)",

				"alter vtiger_table vtiger_attachments add column path varchar(255) default NULL"
			     );

foreach($query_array2 as $query)
{
	Execute($query);
}

			     
//To populate the comboStrings for Campaigns module which are added newly
require_once('include/ComboStrings.php');
global $combo_strings;

$comboTables = Array('campaigntype','campaignstatus','expectedresponse');
foreach ($comboTables as $tablename)
{
	$values = $combo_strings[$tablename."_dom"];
	$i=0;
	foreach ($values as $val => $cal)
	{
		if($val != '')
		{
			$conn->query("insert into ".$tablename. " values(null,'".$val."',".$i.",1)");
		}
		else
		{
			$conn->query("insert into ".$tablename. " values(null,'--None--',".$i.",1)");
		}
		$i++;
	}

}

$update_query3 = "update vtiger_currency_info set conversion_rate=1, vtiger_currency_status='Active', defaultid='-11' where id=1";
Execute($update_query3);

$update_query4 = "update vtiger_relatedlists set label='Purchase Order' where vtiger_tabid=18 and name='get_purchase_orders'";
Execute($update_query4);



//Added on 27-05-06

$create_query27 = "CREATE TABLE vtiger_invitees (activityid int(19) NOT NULL, inviteeid int(19) NOT NULL, PRIMARY KEY (activityid,inviteeid))";
Execute($create_query27);

$alter_query_array17 = Array(
				"ALTER TABLE vtiger_users ADD column hour_format varchar(30) default 'am/pm' AFTER date_format",
				"ALTER TABLE vtiger_users ADD column start_hour varchar(30) default '10:00' AFTER hour_format",
				"ALTER TABLE vtiger_users ADD column end_hour varchar(30) default '23:00' AFTER start_hour"
			    );
foreach($alter_query_array17 as $query)
{
	Execute($query);
}

$create_query28 = "CREATE TABLE vtiger_emaildetails (
			emailid int(19) NOT NULL,
			from_email varchar(50) NOT NULL default '',
			to_email text,
			cc_email text,
			bcc_email text,
			assigned_user_email varchar(50) NOT NULL default '',
			idlists varchar(50) NOT NULL default '',
			email_flag varchar(50) NOT NULL default '',
			PRIMARY KEY  (`emailid`)
		  )";
Execute($create_query28);


$obj_array = Array('Leads'=>'leaddetails','Contacts'=>'contactdetails');
$leadfieldid = $conn->query_result($conn->query("select vtiger_fieldid from vtiger_field where vtiger_tabid=7 and vtiger_fieldname='email'"),0,'fieldid');
$contactfieldid = $conn->query_result($conn->query("select vtiger_fieldid from vtiger_field where vtiger_tabid=4 and vtiger_fieldname='email'"),0,'fieldid');
$fieldid_array = Array("Leads"=>"$leadfieldid","Contacts"=>"$contactfieldid");
$idname_array = Array("Leads"=>"leadid","Contacts"=>"contactid");

$query = 'select * from vtiger_seactivityrel where vtiger_activityid in (select vtiger_activityid from vtiger_activity where vtiger_activitytype="Emails")';
$result = $conn->query($query);
$numofrows = $conn->num_rows($result);

$islists = '';
$toemail = '';
for($i=0;$i<$numofrows;$i++)
{
	$parentid = $conn->query_result($result,$i,'crmid');
	$emailid = $conn->query_result($result,$i,'activityid');

	$query = 'select setype from vtiger_crmentity where crmid='.$parentid;
	$result1 = $conn->query($query);
	$module = $conn->query_result($result1,0,'setype');

	$idlists = "$parentid@$fieldid_array[$module]|";

	if($module == 'Leads' || $module == 'Contacts')
	{
		$result2 = $conn->query("select lastname, firstname, email from $obj_array[$module] where $idname_array[$module] = $parentid");
		$toemail = $conn->query_result($result2,0,'lastname').' '.$conn->query_result($result2,0,'firstname').'<'.$conn->query_result($result2,0,'email').'>###';

		//insert this idlists and toemail values in vtiger_emaildetails vtiger_table
		$sql = "insert into vtiger_emaildetails values ($emailid,'',\"$toemail\",'','','',\"$idlists\",'SAVE')";
		Execute($sql);
	}
	else
	{
		//the parent is not a Lead or Contact. so we have avoided the insert query
	}
}

$update_query5 = "update vtiger_field set quickcreate=1, quickcreatesequence=NULL where vtiger_tabid in (10,14)";
Execute($update_query5);

$alter_query_array18 = Array(
				"alter vtiger_table vtiger_soproductrel add column vattax decimal(7,3) default NULL",
				"alter vtiger_table vtiger_soproductrel add column salestax decimal(7,3) default NULL",
				"alter vtiger_table vtiger_soproductrel add column servicetax decimal(7,3) default NULL",
				
				"alter vtiger_table vtiger_poproductrel add column vattax decimal(7,3) default NULL",
				"alter vtiger_table vtiger_poproductrel add column salestax decimal(7,3) default NULL",
				"alter vtiger_table vtiger_poproductrel add column servicetax decimal(7,3) default NULL",
				
				"alter vtiger_table vtiger_quotesproductrel add column vattax decimal(7,3) default NULL",
				"alter vtiger_table vtiger_quotesproductrel add column salestax decimal(7,3) default NULL",
				"alter vtiger_table vtiger_quotesproductrel add column servicetax decimal(7,3) default NULL",
				
				"alter vtiger_table vtiger_invoiceproductrel add column vattax decimal(7,3) default NULL",
				"alter vtiger_table vtiger_invoiceproductrel add column salestax decimal(7,3) default NULL",
				"alter vtiger_table vtiger_invoiceproductrel add column servicetax decimal(7,3) default NULL",
			    );
foreach($alter_query_array18 as $query)
{
	Execute($query);
}



//Security vtiger_profile and vtiger_tab vtiger_table handling by DON starts
$sql_sec="select vtiger_profileid from  vtiger_profile";
$result_sec=$conn->query($sql_sec);
$num_rows=$conn->num_rows($result_sec);
for($i=0;$i<$num_row;$i++)
{
	$prof_id=$conn->query_result($result_sec,$i,'profileid');
	$sql1_sec="insert into vtiger_profile2utility values(".$prof_id.",13,8,0)";
	Execute($sql1_sec);

	$sql2_sec="insert into vtiger_profile2utility values(".$prof_id.",7,9,0)";
	Execute($sql2_sec);

	$sql3_sec="insert into vtiger_profile2tab values(".$prof_id.",26,0)";
	Execute($sql3_sec);

	$sql4_sec="insert into vtiger_profile2tab values(".$prof_id.",27,0)";
	Execute($sql4_sec);

	$sql7_sec="insert into vtiger_profile2standardpermissions values(".$prof_id.",26,0,0)";
	Execute($sql7_sec);

	$sql8_sec="insert into vtiger_profile2standardpermissions values(".$prof_id.",26,1,0)";
	Execute($sql8_sec);

	$sql9_sec="insert into vtiger_profile2standardpermissions values(".$prof_id.",26,2,0)";
	Execute($sql9_sec);

	$sql10_sec="insert into vtiger_profile2standardpermissions values(".$prof_id.",26,3,0)";
	Execute($sql10_sec);

	$sql11_sec="insert into vtiger_profile2standardpermissions values(".$prof_id.",26,4,0)";
	Execute($sql11_sec);	

}

//Inserting into vtiger_tab vtiger_tables
$sec2="INSERT INTO vtiger_tab VALUES (27,'Portal',0,24,'Portal',null,null,1)";
$sec3="INSERT INTO vtiger_tab VALUES (28,'Webmails',0,25,'Webmails',null,null,1)";

//Insert into vtiger_def_org_share vtiger_tables
$sec4="insert into vtiger_def_org_share values (".$conn->getUniqueID('def_org_share').",26,2,0)";	

Execute($sec2);
Execute($sec3);
Execute($sec4);

//Inserting into datashare related modules vtiger_table

Execute("insert into vtiger_datashare_relatedmodules_seq values(1)");
	
//Lead Related Module
Execute("insert into vtiger_datashare_relatedmodules values (".$conn->getUniqueID('datashare_relatedmodules').",7,10)");

//Account Related Module
Execute("insert into vtiger_datashare_relatedmodules values (".$conn->getUniqueID('datashare_relatedmodules').",6,2)");
Execute("insert into vtiger_datashare_relatedmodules values (".$conn->getUniqueID('datashare_relatedmodules').",6,13)");
Execute("insert into vtiger_datashare_relatedmodules values (".$conn->getUniqueID('datashare_relatedmodules').",6,20)");
Execute("insert into vtiger_datashare_relatedmodules values (".$conn->getUniqueID('datashare_relatedmodules').",6,22)");
Execute("insert into vtiger_datashare_relatedmodules values (".$conn->getUniqueID('datashare_relatedmodules').",6,23)");
Execute("insert into vtiger_datashare_relatedmodules values (".$conn->getUniqueID('datashare_relatedmodules').",6,10)");


//Potential Related Module
Execute("insert into vtiger_datashare_relatedmodules values (".$conn->getUniqueID('datashare_relatedmodules').",2,20)");
Execute("insert into vtiger_datashare_relatedmodules values (".$conn->getUniqueID('datashare_relatedmodules').",2,22)");

//Quote Related Module
Execute("insert into vtiger_datashare_relatedmodules values (".$conn->getUniqueID('datashare_relatedmodules').",20,22)");

//SO Related Module
Execute("insert into vtiger_datashare_relatedmodules values (".$conn->getUniqueID('datashare_relatedmodules').",22,23)");
	

//By Don Ends

//Added the vtiger_tabel vtiger_mail_accounts which has been added by mmbrich
$alter_query18 = "alter vtiger_table vtiger_mail_accounts add column int_mailer int(1) default '0'";
Execute($alter_query18);

$update_query_array5 = Array(
	"update vtiger_field set info_type='BAS' where vtiger_tabid=6 and vtiger_fieldname in ('tickersymbol','account_id')",
	"update vtiger_relatedlists set label = 'Activity History' where vtiger_tabid in (4,6,7,20,21,22,23) and label = 'History'",
	"update vtiger_relatedlists set label = 'Products' where vtiger_tabid=2 and name='get_products' and label='History'",
	"update vtiger_relatedlists set label = 'Activity History' where vtiger_tabid=2 and name='get_history' and label='History'"
			    );
foreach($update_query_array5 as $query)
{
	Execute($query);
}

$insert_query_array27 = Array(
	"insert into vtiger_relatedlists values(".$conn->getUniqueID('relatedlists').",13,0,'get_ticket_history',3,'Ticket History',0)",
	"insert into vtiger_parenttabrel values (2,10,4)",
	"insert into vtiger_parenttabrel values (4,10,7)"
			     );
foreach($insert_query_array27 as $query)
{
	Execute($query);
}



			     

//Added to get the conversion rate and update for all records
//include("modules/Migration/ModifyDatabase/updateCurrency.php");
?>
<script>
	function ajaxSaveResponse(response)
	{
		//alert(response.responseText);
		alert("Currency Changes has been made Successfully");
	}

	if(!confirm("Are you using Dollar $ as Currency? \n Click OK to remain as $, Cancel to change the vtiger_currency conversion rate."))
	{
		getConversionRate('');
	}

	function getConversionRate(err)
	{
		var crate = prompt(err+"\nPlease enter the conversion rate of your vtiger_currency");

		if(crate != 0 && crate > 0)
		{
			var ajaxObj = new VtigerAjax(ajaxSaveResponse);
			url = 'module=Migration&action=updateCurrency&ajax=1&crate='+crate;
			ajaxObj.process("index.php?",url);
		}
		else
		{
			getConversionRate("Please give valid conversion rate ( > 0)");
		}
	}
</script>
<?php




//Function which is used to execute the query and display the result within tr tag. Also it stores the success and failure queries in a array where we can get this array to find the list of success and failure queries at the end of migraion.
function Execute($query)
{
	global $conn, $query_count, $success_query_count, $failure_query_count, $success_query_array, $failure_query_array;

	$status = $conn->query($query);
	$query_count++;
	if(is_object($status))
	{
		echo '
			<tr width="100%">
				<td width="25%" nowrap>'.$status.'</td>
				<td width="5%"><font color="green"> S </font></td>
				<td width="70%">'.$query.'</td>
			</tr>';
		$success_query_array[$success_query_count++] = $query;
	}
	else
	{
		echo '
			<tr width="100%">
				<td width="25%">'.$status.'</td>
				<td width="5%"><font color="red"><b> F </b></font></td>
				<td width="70%">'.$query.'</td>
			</tr>';
		$failure_query_array[$failure_query_count++] = $query;
	}
}

//Added on 23-12-2005 which is used to populate the vtiger_profile2field and vtiger_def_org_field vtiger_table entries for the vtiger_field per vtiger_tab
//if we enter a vtiger_field in vtiger_field vtiger_table then we must populate that vtiger_field in these vtiger_table for security access
function populateFieldForSecurity($tabid,$fieldid)
{
	global $conn;

	$profileresult = $conn->query("select * from vtiger_profile");
	$countprofiles = $conn->num_rows($profileresult);
	for ($i=0;$i<$countprofiles;$i++)
	{
        	$profileid = $conn->query_result($profileresult,$i,'profileid');
	        $sqlProf2FieldInsert[$i] = 'insert into vtiger_profile2field values ('.$profileid.','.$tabid.','.$fieldid.',0,1)';
        	Execute($sqlProf2FieldInsert[$i]);
	}
	$def_query = "insert into vtiger_def_org_field values (".$tabid.",".$fieldid.",0,1)";
	Execute($def_query);
}


?>
