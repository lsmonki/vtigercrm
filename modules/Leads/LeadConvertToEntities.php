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
$close_date = getDBInsertDateValue($_REQUEST["closedate"]);
$current_user_id = $_REQUEST["current_user_id"];
$assigned_user_id = $_REQUEST["assigned_user_id"];
$accountname = $_REQUEST['account_name'];
$potential_amount = $_REQUEST['potential_amount'];
$potential_sales_stage = $_REQUEST['potential_sales_stage'];

global $vtlog;
$vtlog->logthis("id is ".$id,'debug'); 
$vtlog->logthis("assigned_user_id is ".$assigned_user_id,'debug');
$vtlog->logthis("createpotential is ".$createpotential,'debug');
$vtlog->logthis("close date is ".$close_date,'debug');
$vtlog->logthis("current user id is ".$current_user_id,'debug');
$vtlog->logthis("assigned user id is ".$assigned_user_id,'debug');
$vtlog->logthis("accountname is ".$accountname,'debug');
$vtlog->logthis("module is ".$module,'debug');

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

//function for getting the custom values from leads and saving to account/contact/potential custom fields -Jag
function getInsertValues($type,$type_id)
{
	global $id,$adb,$vtlog;

	$sql_convert_lead="select * from convertleadmapping ";
	$convert_result = $adb->query($sql_convert_lead);
	$noofrows = $adb->num_rows($convert_result);

	for($i=0;$i<$noofrows;$i++)
	{
		$flag="false";
		$vtlog->logthis("In convertleadmapping function",'info');
		$lead_id=$adb->query_result($convert_result,$i,"leadfid");  
		//Getting the relatd customfields for Accounts/Contact/potential from convertleadmapping table	
		$account_id_val=$adb->query_result($convert_result,$i,"accountfid");
		$contact_id_val=$adb->query_result($convert_result,$i,"contactfid");
		$potential_id_val=$adb->query_result($convert_result,$i,"potentialfid");
		
		$sql_leads_column="select field.fieldid,field.columnname from field,tab where field.tabid=tab.tabid and generatedtype=2 and tab.name='Leads' and fieldid=".$lead_id; //getting the columnname for the customfield of the lead

		$vtlog->logthis("Lead's custom field coumn name is ".$sql_leads_column,'debug');

		$lead_column_result = $adb->query($sql_leads_column);
		$leads_no_rows = $adb->num_rows($lead_column_result);
		if($leads_no_rows>0)
		{
			$lead_column_name=$adb->query_result($lead_column_result,0,"columnname");
			$sql_leads_val="select ".$lead_column_name." from leadscf where leadid=".$id; //custom field value for lead
			$lead_val_result = $adb->query($sql_leads_val);
			$lead_value=$adb->query_result($lead_val_result,0,$lead_column_name);
			$vtlog->logthis("Lead's custom field value is ".$lead_value,'debug');
		}	
		//Query for getting the column name for Accounts/Contacts/Potentials if custom field for lead is mappped
		$sql_type="select field.fieldid,field.columnname from field,tab where field.tabid=tab.tabid and generatedtype=2 and tab.name="; 
		if($type=="Accounts")
		{
			if($account_id_val!="" && $account_id_val!=0)	
			{
				$flag="true";
				$vtlog->logthis("Getting the  Accounts custom field column name  ",'info');
				$sql_type.="'Accounts' and fieldid=".$account_id_val;
			}
		}
		else if($type == "Contacts")
		{	
			if($contact_id_val!="" && $contact_id_val!=0)	
			{
				$flag="true";
				$vtlog->logthis("Getting the  Contacts custom field column name  ",'info');
				$sql_type.="'Contacts' and fieldid=".$contact_id_val;
			}
		}
		else if($type == "Potentials")
		{
			if($potential_id_val!="" && $potential_id_val!=0)
                        {
				$flag="true";
				$vtlog->logthis("Getting the  Potentials custom field column name  ",'info');
                                $sql_type.="'Potentials' and fieldid=".$potential_id_val;
                        }

		}
		if($flag=="true")
		{ 
			$type_result=$adb->query($sql_type);
		
			if(isset($type_insert_column))
				$type_insert_column.=",";
                	$type_insert_column.=$adb->query_result($type_result,0,"columnname") ;

			if(isset($insert_value))
				$insert_value.=",";
			
			$insert_value.="'".$adb->query_result($lead_val_result,0,$lead_column_name)."'";
		}

	}
	$vtlog->logthis("columns to be inserted are ".$type_insert_column,'debug');	
	$vtlog->logthis("columns to be inserted are ".$insert_value,'debug');	
	$values = array ($type_insert_column,$insert_value);
	return $values;	
}
//function Ends

function getRelatedNotesAttachments($id,$accountid)
{
	global $adb,$vtlog,$id;

	
	$sql_lead_notes	="select * from senotesrel where crmid=".$id;
	$lead_notes_result = $adb->query($sql_lead_notes);
	$noofrows = $adb->num_rows($lead_notes_result);

	for($i=0; $i<$noofrows;$i++ )
	{

		$lead_related_note_id=$adb->query_result($lead_notes_result,$i,"notesid");
		$vtlog->logthis("Lead related note id ".$lead_related_note_id,'debug');	

		$sql_delete_lead_notes="delete from senotesrel where crmid=".$id;
		$adb->query($sql_delete_lead_notes);

		$sql_insert_account_notes="insert into senotesrel(crmid,notesid) values (".$accountid.",".$lead_related_note_id.")";
		$adb->query($sql_insert_account_notes);
	}

	$sql_lead_attachment="select * from seattachmentsrel where crmid=".$id;
        $lead_attachment_result = $adb->query($sql_lead_attachment);
        $noofrows = $adb->num_rows($lead_attachment_result);

        for($i=0;$i<$noofrows;$i++)
        {
						
                $lead_related_attachment_id=$adb->query_result($lead_attachment_result,$i,"attachmentsid");
		$vtlog->logthis("Lead related attachment id ".$lead_related_attachment_id,'debug');	

                $sql_delete_lead_attachment="delete from seattachmentsrel where crmid=".$id;
                $adb->query($sql_delete_lead_attachment);

                $sql_insert_account_attachment="insert into seattachmentsrel(crmid,attachmentsid) values (".$accountid.",".$lead_related_attachment_id.")";                        
                $adb->query($sql_insert_account_attachment);
        }
	
}

function getRelatedActivities($accountid,$contact_id)
{
	global $adb,$vtlog,$id;	
	
	$sql_lead_activity="select * from seactivityrel where crmid=".$id;
	$lead_activity_result = $adb->query($sql_lead_activity);
        $noofrows = $adb->num_rows($lead_activity_result);
        for($i=0;$i<$noofrows;$i++)
        {

                $lead_related_activity_id=$adb->query_result($lead_activity_result,$i,"activityid");
		$vtlog->logthis("Lead related activity id ".$lead_related_activity_id,'debug');	

		$sql_type_email="select setype from crmentity where crmid=".$lead_related_activity_id;
		$type_email_result = $adb->query($sql_type_email);
                $type=$adb->query_result($type_email_result,0,"setype");
		$vtlog->logthis("type of activity id ".$type,'debug');	

                $sql_delete_lead_activity="delete from seactivityrel where crmid=".$id;
                $adb->query($sql_delete_lead_activity);

		if($type != "Emails")
		{
                	$sql_insert_account_activity="insert into seactivityrel(crmid,activityid) values (".$accountid.",".$lead_related_activity_id.")";
	                $adb->query($sql_insert_account_activity);

			$sql_insert_account_activity="insert into cntactivityrel(contactid,activityid) values (".$contact_id.",".$lead_related_activity_id.")";
                	$adb->query($sql_insert_account_activity);
		}
		else
		{
			 $sql_insert_account_activity="insert into seactivityrel(crmid,activityid) values (".$contact_id.",".$lead_related_activity_id.")";                                                                                     $adb->query($sql_insert_account_activity);
		}
        }

	
}


//$sql_crmentity = "insert into crmentity(crmid,smcreatorid,smownerid,setype,presence,createdtime,modifiedtime,deleted) values(".$crmid.",".$current_user_id.",".$current_user_id.",'Accounts',1,".$date_entered.",".$date_modified.",0)";
$sql_crmentity = "insert into crmentity(crmid,smcreatorid,smownerid,setype,presence,createdtime,modifiedtime,deleted,description) values(".$crmid.",".$current_user_id.",".$assigned_user_id.",'Accounts',1,".$date_entered.",".$date_modified.",0,'".$row['description']."')";

$adb->query($sql_crmentity);


$sql_insert_account = "INSERT INTO account (accountid,accountname,industry,annualrevenue,phone,fax,rating,email1,website,employees) VALUES (".$crmid.",'".$accountname ."','".$row["industry"] ."','" .$row["annualrevenue"] ."','" .$row["phone"] ."','".$row["fax"] ."','" .$row["rating"] ."','" .$row["email"] ."','" .$row["website"] ."','" .$row["noofemployees"] ."')";


$adb->query($sql_insert_account);

$sql_insert_accountbillads = "INSERT INTO accountbillads (accountaddressid,city,code,country,state,street) VALUES (".$crmid.",'".$row["city"] ."','" .$row["code"] ."','" .$row["country"] ."','".$row["state"] ."','" .$row["lane"]."')";

 $adb->query($sql_insert_accountbillads);


$sql_insert_accountshipads = "INSERT INTO accountshipads (accountaddressid,city,code,country,state,street) VALUES (".$crmid.",'".$row["city"] ."','" .$row["code"] ."','" .$row["country"] ."','".$row["state"] ."','" .$row["lane"]."')";


 $adb->query($sql_insert_accountshipads);

//Getting the custom field values from leads and inserting into Accounts if the field is mapped - Jaguar
$insert_value=$crmid;
$insert_column="accountid";	
$val= getInsertValues("Accounts",$insert_value);
if($val[0]!="")
	$insert_column.=",";
if($val[1]!="")
	$insert_value.=",";

$insert_column.=$val[0];
$insert_value.=$val[1];
$sql_insert_accountcustomfield = "INSERT INTO accountscf (".$insert_column.") VALUES (".$insert_value.")";

$adb->query($sql_insert_accountcustomfield);

//


$acccount_id=$crmid;
getRelatedNotesAttachments($id,$crmid); //To Convert Related Notes & Attachments -Jaguar

 $date_entered = date('YmdHis');
 $date_modified = date('YmdHis');

$crmcontactid = $adb->getUniqueID("crmentity");
$sql_crmentity1 = "insert into crmentity(crmid,smcreatorid,smownerid,setype,presence,deleted,description,createdtime) values(".$crmcontactid.",".$current_user_id.",".$assigned_user_id.",'Contacts',0,0,'".$row['description']."','".$date_entered."')";

$adb->query($sql_crmentity1);


$contact_id = $crmcontactid;
$vtlog->logthis("contact id is ".$contact_id,'debug');

 $sql_insert_contact = "INSERT INTO contactdetails (contactid,accountid,salutation,firstname,lastname,email,phone,mobile,title,fax,yahooid) VALUES (".$contact_id.",".$crmid.",'".$row["salutation"] ."','" .$row["firstname"] ."','" .$row["lastname"] ."','" .$row["email"] ."','" .$row["phone"]. "','" .$row["mobile"] ."','" .$row["title"] ."','".$row["fax"] ."','".$row['yahooid']."')";

$adb->query($sql_insert_contact);


 $sql_insert_contactsubdetails = "INSERT INTO contactsubdetails (contactsubscriptionid,homephone,otherphone,leadsource) VALUES (".$contact_id.",'".$row["phone"] ."','" .$row["phone"] ."','".$row['leadsource']."')";

$adb->query($sql_insert_contactsubdetails);

 $sql_insert_contactaddress = "INSERT INTO contactaddress (contactaddressid,mailingcity,mailingstreet,mailingstate,mailingcountry,mailingzip) VALUES (".$contact_id.",'".$row["city"] ."','" .$row["lane"] ."','".$row['state']."','" .$row["country"] ."','".$row['code']."')";

$adb->query($sql_insert_contactaddress);


//Getting the customfield values from leads and inserting into the respected ContactCustomfield to which it is mapped - Jaguar
$insert_column="contactid";
$insert_value=$contact_id;
$val= getInsertValues("Contacts",$contact_id);

if($val[0]!="")
	$insert_column.=",";	
if($val[1]!="")
	$insert_value.=",";	

$insert_column.=$val[0];
$insert_value.=$val[1];
$sql_insert_contactcustomfield = "INSERT INTO contactscf (".$insert_column.") VALUES (".$insert_value.")";

$adb->query($sql_insert_contactcustomfield);
//

getRelatedActivities($acccount_id,$contact_id); //To convert relates Activites  and Email -Jaguar

if(! isset($createpotential) || ! $createpotential == "on")
{
  $vtlog->logthis("createpotential is not set",'info');
  $date_entered = date('YmdHis');
  $date_modified = date('YmdHis');
  

  $oppid = $adb->getUniqueID("crmentity");
  $sql_crmentity = "insert into crmentity(crmid,smcreatorid,smownerid,setype,presence,deleted,createdtime,description) values(".$oppid.",".$current_user_id.",".$assigned_user_id.",'Potentials',0,0,'".$date_entered."','".$row['description']."')";
  
  $adb->query($sql_crmentity);


	if(!isset($potential_amount) || $potential_amount == null)
	{
		$potential_amount=0;
        }

	$sql_insert_opp = "INSERT INTO potential (potentialid,accountid,potentialname,leadsource,closingdate,sales_stage,amount) VALUES (".$oppid.",".$crmid .",'".$potential_name."','".$row['leadsource']."','".$close_date."','".$potential_sales_stage."',".$potential_amount.")";

	$adb->query($sql_insert_opp);

//Getting the customfield values from leads and inserting into the respected PotentialCustomfield to which it is mapped - Jaguar
	$insert_column="potentialid";
	$insert_value=$oppid;
	$val= getInsertValues("Potentials",$oppid);
	if($val[0]!="")
		$insert_column.=",";		
	if($val[1]!="")
		$insert_value.=",";		
	
	$insert_column.=$val[0];
	$insert_value.=$val[1];

	$sql_insert_potentialcustomfield = "INSERT INTO potentialscf (".$insert_column.") VALUES (".$insert_value.")";
//

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
