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


//5.0.4 RC to 5.0.4 database changes

//we have to use the current object (stored in PatchApply.php) to execute the queries
$adb = $_SESSION['adodb_current_object'];
$conn = $_SESSION['adodb_current_object'];

$migrationlog->debug("\n\nDB Changes from 5.0.4rc to 5.0.4 -------- Starts \n\n");

//Increased the size of salution field for Leads module
ExecuteQuery("alter table vtiger_leaddetails modify column salutation varchar(50)");

//Added to handle the crypt_type in users table. From 5.0.4 onwards the default crypt type will be MD5. But for existing users crypt type will be empty untill they change their password. Once the existing users change the password then their crypt type will be set as MD5
ExecuteQuery("alter table vtiger_users add column crypt_type varchar(20) not null default 'MD5'");
ExecuteQuery("update vtiger_users set crypt_type=''");

//In 503 to 504rc release we have included the role based picklist migration but the sequence tables for corresponding picklists are not handled. Now we are handling the sequence tables
//Popullating arry with picklist field names
$picklist_arr = array('leadsource'=>'leadsourceid','accounttype'=>'accounttypeid','industry'=>'industryid','leadstatus'=>'leadstatusid','rating'=>'rating_id','opportunity_type'=>'opptypeid','salutationtype'=>'salutationid','sales_stage'=>'sales_stage_id','ticketstatus'=>'ticketstatus_id','ticketpriorities'=>'ticketpriorities_id','ticketseverities'=>'ticketseverities_id','ticketcategories'=>'ticketcategories_id','eventstatus'=>'eventstatusid','taskstatus'=>'taskstatusid','taskpriority'=>'taskpriorityid','manufacturer'=>'manufacturerid','productcategory'=>'productcategoryid','faqcategories'=>'faqcategories_id','usageunit'=>'usageunitid','glacct'=>'glacctid','quotestage'=>'quotestageid','carrier'=>'carrierid','faqstatus'=>'faqstatus_id','invoicestatus'=>'inovicestatusid','postatus'=>'postatusid','sostatus'=>'sostatusid','campaigntype'=>'campaigntypeid','campaignstatus'=>'campaignstatusid','expectedresponse'=>'expectedresponseid');

$custom_result = $adb->query("select fieldname from vtiger_field where (uitype=15 or uitype=33) and fieldname like '%cf_%'");
$numrow = $adb->num_rows($custom_result);
for($i=0; $i < $numrow; $i++)
{
	$fieldname=$adb->query_result($custom_result,$i,'fieldname');
	$picklist_arr[$fieldname] = $adb->query_result($custom_result,$i,'fieldname')."id";
}

foreach($picklist_arr as $picklistname => $picklistidname)
{
	$result = $adb->query("select max(".$picklistidname.") as id from vtiger_".$picklistname);
	$max_count = $adb->query_result($result,0,'id');
	ExecuteQuery("update vtiger_".$picklistname."_seq set id=".$max_count);
}

$migrationlog->debug("\n\nDB Changes from 5.0.4rc to 5.0.4 -------- Ends \n\n");


?>
