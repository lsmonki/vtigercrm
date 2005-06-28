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
require_once('modules/Leads/Lead.php');
//Getting the Parameters from the ConvertLead Form
$id = $_REQUEST["record"];
$module = $_REQUEST["module"];
$assigned_user_id = $_REQUEST["smowerid"];
$createpotential = $_REQUEST["createpotential"];
$potential_name = $_REQUEST["potential_name"];
$close_date = $_REQUEST["closedate"];
$current_user_id = $_REQUEST["current_user_id"];
$assigned_user_id = $_REQUEST["assigned_user_id"];
$accountname = $_REQUEST['account_name'];

global $vtlog;
$vtlog->logthis("id is ".$id,'debug'); 
$vtlog->logthis("assigned_user_id is ".$assigned_user_id,'debug');
$vtlog->logthis("createpotential is ".$createpotential,'debug');
$vtlog->logthis("close date is ".$close_date,'debug');
$vtlog->logthis("current user id is ."$current_user_id,'debug');
$vtlog->logthis("assigned user id is ."$assigned_user_id,'debug');
$vtlog->logthis("accountname is ."$accountname,'debug');
$vtlog->logthis("module is ."$module,'debug');

//Retrieve info from all the tables related to leads
  $focus = new Lead();
 $focus->retrieve_entity_info($id,"Leads");

//get all the lead related columns 
$row = $focus->column_fields;

$date_entered;
$date_modified;

$date_entered = date('YmdHis');
$date_modified = date('YmdHis');

$crmid = $adb->getUniqueID("crmentity");
//$sql_crmentity = "insert into crmentity(crmid,smcreatorid,smownerid,setype,presence,createdtime,modifiedtime,deleted) values(".$crmid.",".$current_user_id.",".$current_user_id.",'Accounts',1,".$date_entered.",".$date_modified.",0)";
$sql_crmentity = "insert into crmentity(crmid,smcreatorid,smownerid,setype,presence,createdtime,modifiedtime,deleted,description) values(".$crmid.",".$current_user_id.",".$assigned_user_id.",'Accounts',1,".$date_entered.",".$date_modified.",0,'".$row['description']."')";

$adb->query($sql_crmentity);


$sql_insert_account = "INSERT INTO account (accountid,accountname,industry,annualrevenue,phone,fax,rating,email1,website,employees) VALUES (".$crmid.",'".$accountname ."','".$row["industry"] ."','" .$row["annualrevenue"] ."','" .$row["phone"] ."','".$row["fax"] ."','" .$row["rating"] ."','" .$row["email"] ."','" .$row["website"] ."','" .$row["noofemployees"] ."')";


$adb->query($sql_insert_account);

$sql_insert_accountbillads = "INSERT INTO accountbillads (accountaddressid,city,code,country,state,street) VALUES (".$crmid.",'".$row["city"] ."','" .$row["code"] ."','" .$row["country"] ."','".$row["state"] ."','" .$row["lane"]."')";

 $adb->query($sql_insert_accountbillads);


$sql_insert_accountshipads = "INSERT INTO accountshipads (accountaddressid,city,code,country,state,street) VALUES (".$crmid.",'".$row["city"] ."','" .$row["code"] ."','" .$row["country"] ."','".$row["state"] ."','" .$row["lane"]."')";


 $adb->query($sql_insert_accountshipads);

$sql_insert_accountcustomfield = "INSERT INTO accountscf (accountid) VALUES (".$crmid.")";

 $adb->query($sql_insert_accountcustomfield);

 $date_entered = date('YmdHis');
 $date_modified = date('YmdHis');

$crmcontactid = $adb->getUniqueID("crmentity");
$sql_crmentity1 = "insert into crmentity(crmid,smcreatorid,smownerid,setype,presence,deleted,description,createdtime) values(".$crmcontactid.",".$current_user_id.",".$assigned_user_id.",'Contacts',0,0,'".$row['description']."','".$date_entered."')";

$adb->query($sql_crmentity1);


$contact_id = $crmcontactid;
$vtlog->logthis("contact id is ."$contact_id,'debug');

 $sql_insert_contact = "INSERT INTO contactdetails (contactid,accountid,salutation,firstname,lastname,email,phone,mobile,title,fax,yahooid) VALUES (".$contact_id.",".$crmid.",'".$row["salutation"] ."','" .$row["firstname"] ."','" .$row["lastname"] ."','" .$row["email"] ."','" .$row["phone"]. "','" .$row["mobile"] ."','" .$row["title"] ."','".$row["fax"] ."','".$row['yahooid']."')";

$adb->query($sql_insert_contact);


 $sql_insert_contactsubdetails = "INSERT INTO contactsubdetails (contactsubscriptionid,homephone,otherphone,leadsource) VALUES (".$contact_id.",'".$row["phone"] ."','" .$row["phone"] ."','".$row['leadsource']."')";

$adb->query($sql_insert_contactsubdetails);

 $sql_insert_contactaddress = "INSERT INTO contactaddress (contactaddressid,mailingcity,mailingstreet,mailingstate,mailingcountry,mailingzip) VALUES (".$contact_id.",'".$row["city"] ."','" .$row["lane"] ."','".$row['state']."','" .$row["country"] ."','".$row['code']."')";

$adb->query($sql_insert_contactaddress);


 $sql_insert_contactcustomfield = "INSERT INTO contactscf (contactid) VALUES (".$contact_id.")";

$adb->query($sql_insert_contactcustomfield);



if(! isset($createpotential) || ! $createpotential == "on")
{
  $vtlog->logthis("createpotential is not set",'info');
  $date_entered = date('YmdHis');
  $date_modified = date('YmdHis');
  

  $oppid = $adb->getUniqueID("crmentity");
  $sql_crmentity = "insert into crmentity(crmid,smcreatorid,smownerid,setype,presence,deleted,createdtime,description) values(".$oppid.",".$current_user_id.",".$assigned_user_id.",'Potentials',0,0,'".$date_entered."','".$row['description']."')";
  
  $adb->query($sql_crmentity);


	$sql_insert_opp = "INSERT INTO potential (potentialid,accountid,potentialname,leadsource,closingdate) VALUES (".$oppid.",".$crmid .",'".$potential_name."','".$row['leadsource']."','".$close_date."')";

	$adb->query($sql_insert_opp);


       	$sql_insert_potentialcustomfield = "INSERT INTO potentialscf (potentialid) VALUES (".$oppid.")";

	$adb->query($sql_insert_potentialcustomfield);

        $sql_insert2contpotentialrel ="insert into contpotentialrel values(".$contact_id.",".$oppid .")";
        
        $adb->query($sql_insert2contpotentialrel);

	
}

//Deleting from the tracker
$sql_delete_tracker= "DELETE from tracker where item_id='" .$id ."'";
$adb->query($sql_delete_tracker);

//Updating the deleted status
$sql_update_converted = "UPDATE leaddetails SET converted = 1 where leadid='" .$id ."'";
$adb->query($sql_update_converted); 

header("Location: index.php?action=DetailView&module=Accounts&record=$crmid");

?>
