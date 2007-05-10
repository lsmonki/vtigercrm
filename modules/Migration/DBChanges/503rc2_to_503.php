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


//5.0.3 RC2 to 5.0.3 database changes - added on 29-03-07
global $adb;

$migrationlog->debug("\n\nDB Changes from 5.0.3RC2 to 5.0.3 -------- Starts \n\n");

$query_array = Array(
			//description field added in vtiger_inventoryproductrel
			"alter table vtiger_inventoryproductrel add column description TEXT default NULL after comment",

			//size increased for comment field
			"alter table vtiger_inventoryproductrel change comment comment varchar(250)",

			//size increased for tax and discount related fields in Inventory modules
			"alter table vtiger_purchaseorder change salestax salestax decimal(25,3)",
			"alter table vtiger_purchaseorder change adjustment adjustment decimal(25,3)",
			"alter table vtiger_purchaseorder change salescommission salescommission decimal(25,3)",
			"alter table vtiger_purchaseorder change exciseduty exciseduty decimal(25,3)",
			"alter table vtiger_purchaseorder change total total decimal(25,3)",
			"alter table vtiger_purchaseorder change subtotal subtotal decimal(25,3)",
			"alter table vtiger_purchaseorder change discount_percent discount_percent decimal(25,3)",
			"alter table vtiger_purchaseorder change discount_amount discount_amount decimal(25,3)",
			"alter table vtiger_purchaseorder change s_h_amount s_h_amount decimal(25,3)",

			"alter table vtiger_salesorder change salestax salestax decimal(25,3)",
			"alter table vtiger_salesorder change adjustment adjustment decimal(25,3)",
			"alter table vtiger_salesorder change salescommission salescommission decimal(25,3)",
			"alter table vtiger_salesorder change exciseduty exciseduty decimal(25,3)",
			"alter table vtiger_salesorder change total total decimal(25,3)",
			"alter table vtiger_salesorder change subtotal subtotal decimal(25,3)",
			"alter table vtiger_salesorder change discount_percent discount_percent decimal(25,3)",
			"alter table vtiger_salesorder change discount_amount discount_amount decimal(25,3)",
			"alter table vtiger_salesorder change s_h_amount s_h_amount decimal(25,3)",

			"alter table vtiger_invoice change salestax salestax decimal(25,3)",
			"alter table vtiger_invoice change adjustment adjustment decimal(25,3)",
			"alter table vtiger_invoice change salescommission salescommission decimal(25,3)",
			"alter table vtiger_invoice change exciseduty exciseduty decimal(25,3)",
			"alter table vtiger_invoice change total total decimal(25,3)",
			"alter table vtiger_invoice change subtotal subtotal decimal(25,3)",
			"alter table vtiger_invoice change discount_percent discount_percent decimal(25,3)",
			"alter table vtiger_invoice change discount_amount discount_amount decimal(25,3)",
			"alter table vtiger_invoice change s_h_amount s_h_amount decimal(25,3)",

			"alter table vtiger_quotes change subtotal subtotal decimal(25,3)",
			"alter table vtiger_quotes change tax tax decimal(25,3)",
			"alter table vtiger_quotes change adjustment adjustment decimal(25,3)",
			"alter table vtiger_quotes change total total decimal(25,3)",
			"alter table vtiger_quotes change discount_percent discount_percent decimal(25,3)",
			"alter table vtiger_quotes change discount_amount discount_amount decimal(25,3)",
			"alter table vtiger_quotes change s_h_amount s_h_amount decimal(25,3)",

			"alter table vtiger_pricebookproductrel change listprice listprice decimal(25,3)",

			"alter table vtiger_inventoryproductrel change listprice listprice decimal(25,3)",

			"alter table vtiger_products change unit_price unit_price decimal(25,2)",

			"alter table vtiger_campaign change expectedrevenue expectedrevenue decimal(25,3)",
			"alter table vtiger_campaign change budgetcost budgetcost decimal(25,3)",
			"alter table vtiger_campaign change actualcost actualcost decimal(25,3)",
			"alter table vtiger_campaign change expectedroi expectedroi decimal(25,3)",
			"alter table vtiger_campaign change actualroi actualroi decimal(25,3)",

			//unwanted currency column removed from lead and contact
			"alter table vtiger_leadsubdetails drop column currency",

			"alter table vtiger_contactdetails drop column currency",

			//Amount and probability field datatype modified.(http://forums.vtiger.com/viewtopic.php?t=14006)

			"alter table vtiger_potential change probability probability decimal(5,2)",
			"alter table vtiger_potential change amount amount decimal(12,2)",
			"alter table vtiger_opportunitystage change probability probability decimal(5,2)",
			"alter table vtiger_dealintimation change dealprobability dealprobability decimal(5,2)",
			"alter table vtiger_potstagehistory change probability probability decimal(5,2)",
			"alter table vtiger_potstagehistory change amount amount decimal(12,2)",

			//Homepage order has been changed 
			"update vtiger_users set homeorder = 'HDB,ALVT,PLVT,QLTQ,CVLVT,HLT,OLV,GRT,OLTSO,ILTI,MNL,OLTPO,LTFAQ'",

		    );

foreach($query_array as $query)
{
	ExecuteQuery($query);
}


//Added for Custom Invoice Number, No need for security population
ExecuteQuery("insert into vtiger_field values(23,".$adb->getUniqueID("vtiger_field").",'invoice_no','vtiger_invoice',1,'1','invoice_no','invoice_no',1,0,0,100,3,69,1,'V~M',1,NULL,'BAS')");

ExecuteQuery("alter table vtiger_invoice add column (invoice_no varchar(50) UNIQUE default NULL)");

$res = $adb->query("select cvid from vtiger_customview where entitytype='Invoice' and viewname='All'");
$cvid = $adb->query_result($res,0,'cvid');

ExecuteQuery("update vtiger_cvcolumnlist set columnindex=6 where columnindex=5 and cvid=$cvid");
ExecuteQuery("update vtiger_cvcolumnlist set columnindex=5 where columnindex=4 and cvid=$cvid");
ExecuteQuery("update vtiger_cvcolumnlist set columnindex=4 where columnindex=3 and cvid=$cvid");
ExecuteQuery("update vtiger_cvcolumnlist set columnindex=3 where columnindex=2 and cvid=$cvid");
ExecuteQuery("update vtiger_cvcolumnlist set columnindex=2 where columnindex=1 and cvid=$cvid");
ExecuteQuery("insert into vtiger_cvcolumnlist values($cvid,1,'vtiger_invoice:invoice_no:invoice_no:Invoice_invoice_no:V')");

//Added for product custom view taxclass issue Ticket #3364 
ExecuteQuery("update vtiger_field set tablename='vtiger_products' where tablename='vtiger_producttaxrel' and columnname='taxclass'");
ExecuteQuery("update vtiger_cvcolumnlist set columnname='vtiger_products:taxclass:taxclass:Products_Tax_Class:V' where columnname='vtiger_producttaxrel:taxclass:taxclass:Products_Tax_Class:V'");




//Display type 3 added in profile & default org tables

$profileresult = $adb->query("select * from vtiger_profile");
$countprofiles = $adb->num_rows($profileresult);

$res = $adb->query("select * from vtiger_field where fieldid not in (select fieldid from vtiger_profile2field) and generatedtype=1 and displaytype=3 and tabid!=29");
//$res = $adb->query("select * from vtiger_field where generatedtype=1 and displaytype=3 and tabid!=29");
$num_fields = $adb->num_rows($res);
for($i=0;$i<$num_fields;$i++)
{
	$tabid = $adb->query_result($res,$i,'tabid');
	$fieldid = $adb->query_result($res,$i,'fieldid');

	//For each profile, we have to enter the current fields
	for ($j=0;$j<$countprofiles;$j++)
	{
        	$profileid = $adb->query_result($profileresult,$j,'profileid');
	        ExecuteQuery('insert into vtiger_profile2field values ('.$profileid.','.$tabid.','.$fieldid.',0,1)');
	}
	
	$def_query = "insert into vtiger_def_org_field values (".$tabid.",".$fieldid.",0,1)";
	ExecuteQuery($def_query);
}


$query_array2 = Array(
	
			//Added To fix Duplicate items in Report's Select Column(ticket #3665)

			"update vtiger_field set fieldlabel='Adjustment' where tabid=22 and columnname='adjustment'",

			"update vtiger_field set fieldlabel='Subtotal' where tabid=22 and columnname='subtotal'",

			"update vtiger_field set fieldlabel='Adjustment' where tabid=23 and columnname='adjustment'",

			"update vtiger_field set fieldlabel='Salestax' where tabid=20 and columnname='tax'",

			// Changes made to make discontinued column in vtiger_products '0' during deactivation.

			"alter table vtiger_products modify discontinued int(1) NOT NULL default 0",


			//Ref : ticket#3278, 3309, 3461
			"update vtiger_field set typeofdata='E~O' where fieldname in ('yahooid','yahoo_id')",
			"alter table vtiger_leaddetails modify noofemployees int(50)",
			"update vtiger_field set typeofdata='I~O' where fieldname ='noofemployees' && tabid='7'",

			//Ref : ticket#3521
			"update vtiger_field set typeofdata ='D~O' where tabid=21 && fieldname='duedate'",


			//Changes made to add an email Id for standarduser since a user must have an Email Id.Changes for 5.0.3.
			"update vtiger_users set email1='standarduser@vtigeruser.com' where id = '2' and email1 = ''",


			//#3668, this query is already available in the file modules/Migration/DBChanges/42P2_to_50.php
			"update vtiger_crmentity set setype='Calendar' where setype='Activities'",

			//we don't have field security for Emails module, so we can delete the existing entries
			"delete from vtiger_profile2field where tabid=10",
			"delete from vtiger_def_org_field where tabid=10",
		     );

foreach($query_array2 as $query)
{
	ExecuteQuery($query);
}


//change the picklist - presence value ie., if presence = 0 then you cannot edit, if presence = 1 then you can edit
$noneditable_tables = Array("ticketstatus","taskstatus","eventstatus","eventstatus","faqstatus","quotestage","postatus","sostatus","invoicestatus");
$noneditable_values = Array(
				"sales_stage"=>"Closed Won",
			   );
foreach($noneditable_tables as $picklistname)
{
	//we have to interchange 0 and 1, so change 0->2, 1->0, 2->1
	ExecuteQuery("UPDATE vtiger_".$picklistname." SET PRESENCE=2 WHERE PRESENCE=0");
	ExecuteQuery("UPDATE vtiger_".$picklistname." SET PRESENCE=0 WHERE PRESENCE=1");
	ExecuteQuery("UPDATE vtiger_".$picklistname." SET PRESENCE=1 WHERE PRESENCE=2");
}
foreach($noneditable_values as $picklistname => $value)
{
	ExecuteQuery("UPDATE vtiger_".$picklistname." SET PRESENCE=0 WHERE $picklistname='".$value."'");
}

//Assigned To value is shown as empty in Accounts, Emails and PO listviews because of uitype 52
ExecuteQuery("update vtiger_field set uitype=53 where fieldname='assigned_user_id' and tabid in (6,10,21)");

//AccountName is shown as empty in SO/Quotes/Invoice listview because of account details in vtiger_cvcolumnlist.columnname
$modules_array = Array("SalesOrder","Quotes","Invoice","Contacts","Potentials");
foreach($modules_array as $module)
{
	ExecuteQuery("update vtiger_cvcolumnlist inner join vtiger_customview on vtiger_customview.cvid=vtiger_cvcolumnlist.cvid set columnname='vtiger_account:accountname:accountname:".$module."_Account_Name:V' where columnname like '%:accountid:account_id:%' and vtiger_customview.entitytype='".$module."'");
}

/*
$res = $adb->query("select vtiger_cvcolumnlist.*, vtiger_customview.viewname from vtiger_cvcolumnlist inner join vtiger_customview on vtiger_customview.cvid=vtiger_cvcolumnlist.cvid where columnname like '%:accountid:account_id:%' and vtiger_customview.entitytype='SalesOrder'");
for($i=0;$i<$adb->num_rows($res);$i++)
{
	$cvid = $adb->query_result($res,$i,'cvid');
	$columnindex = $adb->query_result($res,$i,'columnindex');
	ExecuteQuery("update vtiger_cvcolumnlist set columnname='vtiger_account:accountname:accountname:SalesOrder_Account_Name:V' where cvid=$cvid and columnindex=$columnindex");
}
*/

//ContactName in Calendar listview is a link but record id is empty in link so when we click the link fatal error comes
ExecuteQuery("update vtiger_cvcolumnlist inner join vtiger_customview on vtiger_customview.cvid=vtiger_cvcolumnlist.cvid set columnname = 'vtiger_cntactivityrel:contactid:contact_id:Calendar_Contact_Name:V' where columnname = 'vtiger_contactdetails:lastname:lastname:Calendar_Contact_Name:V' and vtiger_customview.entitytype='Calendar'");

//Related To is not displayed in Calendar Listview
ExecuteQuery("update vtiger_cvcolumnlist inner join vtiger_customview on vtiger_customview.cvid=vtiger_cvcolumnlist.cvid set columnname = 'vtiger_seactivityrel:crmid:parent_id:Calendar_Related_to:V' where columnname = 'vtiger_seactivityrel:crmid:parent_id:Calendar_Related_To:V' and vtiger_customview.entitytype='Calendar'");

//In 4.2.3 we have assigned to group option only for Leads, HelpDesk and Activies and default None can be assigned. Now we will assign the unassigned entities to current user
ExecuteQuery("update vtiger_crmentity set smownerid=1 where smownerid=0 and setype not in ('Leads','HelpDesk','Calendar')");




ExecuteQuery("CREATE TABLE vtiger_version (id int(11) NOT NULL auto_increment, old_version varchar(30) default NULL, current_version varchar(30) default NULL, PRIMARY KEY  (id) ) ENGINE=InnoDB DEFAULT CHARSET=latin1");



$migrationlog->debug("\n\nDB Changes from 5.0.3RC2 to 5.0.3 -------- Ends \n\n");



?>
