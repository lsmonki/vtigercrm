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

 require_once('include/database/PearDatabase.php');

// Delete the data starts
$sql_role2tab = "delete from role2tab where rolename='" . $_REQUEST["rolename"] ."'";
$result = $adb->query($sql_role2tab);

$sql_role2action = "delete from role2action where rolename='" . $_REQUEST["rolename"] ."'";
$result = $adb->query($sql_role2action);

//Leads starts

$flag=0;

if($_REQUEST['lead_create'] == 'on' )
     $flag=1;
     if($_REQUEST['lead_module_access'] == 'on')
     $lead_access_flag = 1;
     else
     $lead_access_flag = 0;

//entry for the Home Tab, Dashboard Tab , MessageBoard Tab

   $adb->query("insert into role2tab(rolename,tabid,module_permission) values('" .$_REQUEST["rolename"]."',1,1)");
   $adb->query("insert into role2tab(rolename,tabid,module_permission) values('" .$_REQUEST["rolename"]."',13,1)");
   $adb->query("insert into role2tab(rolename,tabid,module_permission) values('" .$_REQUEST["rolename"]."',2,1)");
//   $adb->query("insert into role2action(rolename,tabid,actionname,action_permission) values('" .$_REQUEST["rolename"]."',13,'index',1)");
   $adb->query("insert into role2action(rolename,tabid,actionname,action_permission) values('" .$_REQUEST["rolename"]."',1,'index',1)");
   $adb->query("insert into role2action(rolename,tabid,actionname,action_permission) values('" .$_REQUEST["rolename"]."',2,'index',1)");
//Added Entry for HelpDesk
   $adb->query("insert into role2action(rolename,tabid,actionname,action_permission) values('" .$_REQUEST["rolename"]."',14,'index',1)");
//Addedn Entry for Calendar
   $adb->query("insert into role2action(rolename,tabid,actionname,action_permission) values('" .$_REQUEST["rolename"]."',16,'index',1)");
    	

 $sql_leads_tab_permission = "insert into role2tab(rolename,tabid,module_permission) values ('" .$_REQUEST["rolename"]."',3," .$lead_access_flag.")";

$result_leads_tab_permission = $adb->query($sql_leads_tab_permission);

 $sql_leads_edit_create = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',3,'EditView'," .$flag .")";

$result_leads_edit_create = $adb->query($sql_leads_edit_create);

if('on' == $_REQUEST['lead_delete'])
     $flag=1;
     else
     $flag=0;

$sql_leads_delete = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',3,'Delete'," .$flag .")";

$sql_leads_index = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',3,'index'," .$lead_access_flag .")";

$result_leads_delete = $adb->query($sql_leads_delete);
$result_leads_index = $adb->query($sql_leads_index);

if($_REQUEST['import_leads'] == 'on' )
     $importleadflag=1;
     else
     $importleadflag=0;

 $adb->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('" .$_REQUEST["rolename"]."',3,'fetchfile'," .$importleadflag .",'')");

 $adb->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('" .$_REQUEST["rolename"]."',3,'Save',1,'')");

//Accounts starts

if($_REQUEST['account_create'] == 'on' )
     $flag=1;
     else
     $flag=0;
if($_REQUEST['account_module_access'] == 'on')
     $account_access_flag = 1;
     else
     $account_access_flag = 0;
     


$sql_accounts_tab_permission = "insert into role2tab(rolename,tabid,module_permission) values ('" .$_REQUEST["rolename"]."',5," .$account_access_flag.")";

$result_accounts_tab_permission = $adb->query($sql_accounts_tab_permission);

$sql_accounts_edit_create = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',5,'EditView'," .$flag .")";

$result_accounts_edit_create = $adb->query($sql_accounts_edit_create);

if('on' == $_REQUEST['account_delete'])
     $flag=1;
     else 
     $flag=0;

$sql_accounts_delete = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',5,'Delete'," .$flag .")";

$result_accounts_delete = $adb->query($sql_accounts_delete);

$sql_accounts_index = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',5,'index'," .$account_access_flag .")";

$result_accounts_index = $adb->query($sql_accounts_index);


if($_REQUEST['import_accounts'] == 'on' )
     $importaccountflag=1;
     else
     $importaccountflag=0;

 $adb->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('" .$_REQUEST["rolename"]."',5,'Import'," .$importaccountflag .",'')");



 $adb->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('" .$_REQUEST["rolename"]."',5,'Save',1,'')");

//Contacts starts


if($_REQUEST['contact_create'] == 'on' )
     $flag=1;
     else
     $flag=0;

if($_REQUEST['contact_module_access'] == 'on')
     $contact_access_flag = 1;
     else
     $contact_access_flag = 0;



$sql_contacts_tab_permission = "insert into role2tab(rolename,tabid,module_permission) values ('" .$_REQUEST["rolename"]."',4," .$contact_access_flag.")";

$result_contacts_tab_permission = $adb->query($sql_contacts_tab_permission);

$sql_contacts_edit_create = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',4,'EditView'," .$flag .")";

$result_contacts_edit_create = $adb->query($sql_contacts_edit_create);

if('on' == $_REQUEST['contact_delete']) 
     $flag=1;
     else
     $flag=0;

 $sql_contacts_delete = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',4,'Delete'," .$flag .")";

 $result_contacts_delete = $adb->query($sql_contacts_delete);

 $sql_contacts_index = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',4,'index'," .$contact_access_flag .")";

   $result_contacts_index = $adb->query($sql_contacts_index);


if($_REQUEST['import_contacts'] == 'on' )
     $importcontactflag=1;
     else
     $importcontactflag=0;




 $adb->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('" .$_REQUEST["rolename"]."',4,'Import'," .$importcontactflag .",'')");


 $adb->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('" .$_REQUEST["rolename"]."',4,'Save',1,'')");
 $adb->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('" .$_REQUEST["rolename"]."',4,'BusinessCard',1,'')");


//Opportunities starts




if($_REQUEST['opportunities_create'] == 'on' )
     $flag=1;
     else
     $flag=0;
if($_REQUEST['opportunities_module_access'] == 'on')
     $opportunities_access_flag = 1;
     else
     $opportunities_access_flag = 0;


$sql_opportunities_tab_permission = "insert into role2tab(rolename,tabid,module_permission) values ('" .$_REQUEST["rolename"]."',6," .$opportunities_access_flag.")";

$result_opportunities_tab_permission = $adb->query($sql_opportunities_tab_permission);

$sql_opportunities_edit_create = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',6,'EditView'," .$flag .")";

$result_opportunities_edit_create = $adb->query($sql_opportunities_edit_create);

if('on' == $_REQUEST['opportunities_delete'])
     $flag=1;
     else
     $flag=0;

$sql_opportunities_delete = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',6,'Delete'," .$flag .")";

$result_opportunities_delete = $adb->query($sql_opportunities_delete);


$sql_opportunities_index = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',6,'index'," .$opportunities_access_flag .")";

$result_opportunities_index = $adb->query($sql_opportunities_index);


if($_REQUEST['import_opportunities'] == 'on' )                                                                          $importopportunityflag=1;                                                                                         else                                                                                                          $importopportunityflag=0;

 $adb->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('" .$_REQUEST["rolename"]."',6,'Import'," .$importopportunityflag .",'')");

 $adb->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('" .$_REQUEST["rolename"]."',6,'Save',1,'')");
//Activities starts



if($_REQUEST['activities_create'] == 'on' )
     $flag=1;
     else
     $flag=0;
if($_REQUEST['activities_module_access'] == 'on')
     $activities_access_flag = 1;
     else
     $activities_access_flag = 0;



$sql_activities_tab_permission = "insert into role2tab(rolename,tabid,module_permission) values ('" .$_REQUEST["rolename"]."',12," .$activities_access_flag.")";

$result_activities_tab_permission = $adb->query($sql_activities_tab_permission);

$sql_activities_edit_create = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',12,'EditView'," .$flag .")";

$result_activities_edit_create = $adb->query($sql_activities_edit_create);

if('on' == $_REQUEST['activities_delete'])
     $flag=1;
     else
     $flag=0;

$sql_activities_delete = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',12,'Delete'," .$flag .")";
$result_activities_delete = $adb->query($sql_activities_delete);



$sql_activities_index = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',12,'index'," .$activities_access_flag .")";
$result_activities_index = $adb->query($sql_activities_index);




 $adb->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('" .$_REQUEST["rolename"]."',12,'Import'," .$importopportunityflag .",'')");

 $adb->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('" .$_REQUEST["rolename"]."',12,'Save',1,'')");
//Cases starts






if($_REQUEST['cases_create'] == 'on' )
     $flag=1;
     else
     $flag=0;
if($_REQUEST['cases_module_access'] == 'on')
     $cases_access_flag = 1;
     else
     $cases_access_flag = 0;


$sql_cases_tab_permission = "insert into role2tab(rolename,tabid,module_permission) values ('" .$_REQUEST["rolename"]."',7," .$cases_access_flag.")";

$result_cases_tab_permission = $adb->query($sql_cases_tab_permission);

$sql_cases_edit_create = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',7,'EditView'," .$flag .")";

$result_cases_edit_create = $adb->query($sql_cases_edit_create);

if('on' == $_REQUEST['cases_delete'])
     $flag=1;
     else
     $flag=0;

$sql_cases_delete = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',7,'Delete'," .$flag .")";
$result_cases_delete = $adb->query($sql_cases_delete);


$sql_cases_index = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',7,'index'," .$cases_access_flag .")";
$result_cases_index = $adb->query($sql_cases_index);




 $adb->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('" .$_REQUEST["rolename"]."',7,'Import'," .$importopportunityflag .",'')");


 $adb->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('" .$_REQUEST["rolename"]."',7,'Save',1,'')");
//Emails starts


if($_REQUEST['emails_create'] == 'on' )
     $flag=1;
     else
     $flag=0;
if($_REQUEST['emails_module_access'] == 'on')
     $emails_access_flag = 1;
     else
     $emails_access_flag = 0;





$sql_emails_tab_permission = "insert into role2tab(rolename,tabid,module_permission) values ('" .$_REQUEST["rolename"]."',10," .$emails_access_flag.")";

$result_emails_tab_permission = $adb->query($sql_emails_tab_permission);


$sql_email_edit_create = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',10,'EditView'," .$flag .")";

$result_emails_edit_create = $adb->query($sql_email_edit_create);

if('on' == $_REQUEST['emails_delete'])
     $flag=1;
     else
     $flag=0;

$sql_email_delete = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',10,'Delete'," .$flag .")";

$result_email_delete = $adb->query($sql_email_delete);

$sql_email_index = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',10,'index'," .$emails_access_flag .")";

$result_email_index = $adb->query($sql_email_index);



 $adb->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('" .$_REQUEST["rolename"]."',10,'Import'," .$importopportunityflag .",'')");


 $adb->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('" .$_REQUEST["rolename"]."',10,'Save',1,'')");
//Notes starts



if($_REQUEST['notes_create'] == 'on' )
     $flag=1;
     else
     $flag=0;
if($_REQUEST['notes_module_access'] == 'on')
     $notes_access_flag = 1;
     else
     $notes_access_flag = 0;




$sql_notes_tab_permission = "insert into role2tab(rolename,tabid,module_permission) values ('" .$_REQUEST["rolename"]."',8," .$notes_access_flag.")";

$result_notes_tab_permission = $adb->query($sql_notes_tab_permission);



$sql_notes_edit_create = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',8,'EditView'," .$flag .")";

$result_notes_edit_create = $adb->query($sql_notes_edit_create);

if('on' == $_REQUEST['notes_delete'])
     $flag=1;
     else
     $flag=0;

$sql_notes_delete = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',8,'Delete'," .$flag .")";

$result_notes_delete = $adb->query($sql_notes_delete);


$sql_notes_index = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',8,'index'," .$notes_access_flag .")";

$result_notes_index = $adb->query($sql_notes_index);


 $adb->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('" .$_REQUEST["rolename"]."',8,'Import'," .$importopportunityflag .",'')");

 $adb->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('" .$_REQUEST["rolename"]."',8,'Save',1,'')");
//Meetings starts






if($_REQUEST['meetings_create'] == 'on' )
     $flag=1;
     else
     $flag=0;

if($_REQUEST['meetings_module_access'] == 'on')
     $meetings_access_flag = 1;
     else
     $meetings_access_flag = 0;





$sql_meetings_tab_permission = "insert into role2tab(rolename,tabid,module_permission) values ('" .$_REQUEST["rolename"]."',11," .$meetings_access_flag.")";

$result_meetings_tab_permission = $adb->query($sql_meetings_tab_permission);





$sql_meetings_edit_create = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',11,'EditView'," .$flag .")";

$result_meetings_edit_create = $adb->query($sql_meetings_edit_create);

if('on' == $_REQUEST['meetings_delete'])
     $flag=1;
     else
     $flag=0;

$sql_meetings_delete = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',11,'Delete'," .$flag .")";

$result_meetings_delete = $adb->query($sql_meetings_delete);


$sql_meetings_index = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',11,'index'," .$meetings_access_flag .")";

$result_meetings_index = $adb->query($sql_meetings_index);



 $adb->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('" .$_REQUEST["rolename"]."',11,'Import'," .$importopportunityflag .",'')");

 $adb->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('" .$_REQUEST["rolename"]."',11,'Save',1,'')");
//Calls starts

if($_REQUEST['calls_create'] == 'on' )
     $flag=1;
     else
     $flag=0;
if($_REQUEST['calls_module_access'] == 'on')
     $calls_access_flag = 1;




$sql_calls_tab_permission = "insert into role2tab(rolename,tabid,module_permission) values ('" .$_REQUEST["rolename"]."',9," .$calls_access_flag.")";

$result_calls_tab_permission = $adb->query($sql_calls_tab_permission);


$sql_calls_edit_create = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',9,'EditView'," .$flag .")";


$result_calls_edit_create = $adb->query($sql_calls_edit_create);

if('on' == $_REQUEST['calls_delete'])
     $flag=1;
     else
     $flag=0;

$sql_calls_delete = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',9,'Delete'," .$flag .")";


$result_calls_delete = $adb->query($sql_calls_delete);



$sql_calls_index = "insert into role2action(rolename,tabid,actionname,action_permission) values ('" .$_REQUEST["rolename"]."',9,'index'," .$calls_access_flag .")";

$result_calls_index = $adb->query($sql_calls_index);

 $adb->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('" .$_REQUEST["rolename"]."',9,'Import'," .$importopportunityflag .",'')");

 $adb->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('" .$_REQUEST["rolename"]."',9,'Save',1,'')");
$rolename = $_REQUEST["rolename"];
header("Location: index.php?module=Users&action=ListPermissions&rolename=$rolename");


?>
