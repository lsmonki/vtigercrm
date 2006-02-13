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


//check if the accountname field is set
//if so, create an account first and then if the contactname is set create that also setting the accountid as the foreign key in the same

global $adb;

$idholderaccount=0;
$idholdercontact=0;

//account info

$acctname = $_POST['account_name'];
$acctphone = $_POST['account_phone'];
$acctwebsite = $_POST['account_website'];


//account notes info

$acctnotesname = $_POST['AccountNotesname'];
$acctnotesdescription = $_POST['AccountNotesdescription'];


//if acctrelated notes data are given, add to the db
//populate crmentity,senotesrel



//contact info

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$title = $_POST['title'];
$department = $_POST['department'];
$mailingcity = $_POST['mailingcity'];
$mailingstate = $_POST['mailingstate'];
$mailingcode = $_POST['mailingzip'];
$mailingcountry = $_POST['mailingcountry'];
$phone = $_POST['phone'];
$mobile = $_POST['mobile'];
$fax = $_POST['fax'];
$email = $_POST['email'];
$otheremail = $_POST['otheremail'];


//contact notes info

$contactnotesname = $_POST['ContactNotesname'];
$contactnotesdescription = $_POST['ContactNotesdescription'];


$date_var = date('YmdHis');

if(($acctname != '') && ($lastname != ''))
{
  //echo 'both acct and contact are not empty';

  $account_id = $adb->getUniqueID("crmentity");
  $idholderaccount = $account_id;
  $sql = "insert into crmentity (crmid,smcreatorid,smownerid,modifiedby,setype,description,createdtime,modifiedtime,viewedtime,presence,deleted) values(".$account_id.",".$current_user->id.",".$current_user->id.",".$current_user->id.",'Accounts','created from business card','".$date_var."','".$date_var."','".$date_var."',0,0".")";
  $adb->query($sql);
 
  $sql_insertacct = "insert into account(accountid,accountname,phone,website) values(".$account_id.",'".$acctname."','".$acctphone."','".$acctwebsite ."')";
 
  $adb->query($sql_insertacct);

  $adb->query("insert into accountbillads (accountaddressid, street, city, state, code, country) values(".$account_id.", '', '', '', '', '')");

  $adb->query("insert into accountshipads (accountaddressid, street, city, state, code, country) values(".$account_id.", '', '', '', '', '')");
  $adb->query("insert into accountscf (accountid) values(".$account_id.")");
 
 
  if($acctnotesname != '')
  {
   
    $accountnote_id = $adb->getUniqueID("crmentity");
    $sql = "insert into crmentity (crmid,smcreatorid,smownerid,modifiedby,setype,description,createdtime,modifiedtime,viewedtime,presence,deleted) values(".$accountnote_id.",".$current_user->id.",".$current_user->id.",".$current_user->id.",'notes','created from business card','".$date_var."','".$date_var."','".$date_var."',0,0".")";
    $adb->query($sql);
 
    $sql_insertnote = "insert into notes(notesid,title,notecontent) values(".$accountnote_id.",'".$acctnotesname."','".$acctnotesdescription ."')";
 
    $adb->query($sql_insertnote);
  
    $sql_insertsenotesrel = "insert into senotesrel(crmid,notesid) values(".$account_id.",".$accountnote_id.")";
 
    $adb->query($sql_insertsenotesrel);
   
  }

 
  //insert into contactdetails now

  $contact_id = $adb->getUniqueID("crmentity");
  $idholdercontact = $contact_id;
  $sql = "insert into crmentity (crmid,smcreatorid,smownerid,modifiedby,setype,description,createdtime,modifiedtime,viewedtime,presence,deleted) values(".$contact_id.",".$current_user->id.",".$current_user->id.",".$current_user->id.",'Contacts','created from business card','".$date_var."','".$date_var."','".$date_var."',0,0".")";
  $adb->query($sql);
 
 
  $sql_contactdetails = "insert into contactdetails(contactid,accountid,firstname,lastname,title,department,email,phone,mobile,fax,otheremail) values(".$contact_id.",".$account_id.",'".$firstname."','".$lastname."','".$title."','".$department."','".$email."','".$phone."','".$mobile."','".$fax."','".$otheremail."')";
  //echo $sql_contactdetails;

  $adb->query($sql_contactdetails);

  $adb->query("insert into contactaddress (contactaddressid, mailingstreet, otherstreet, mailingcity, othercity, mailingstate, otherstate, mailingzip, otherzip, mailingcountry, othercountry) values(".$contact_id.", '', '', '".$mailingcity."', '', '".$mailingstate."', '', '".$mailingcode."', '', '".$mailingcountry."', '')");

  $adb->query("insert into contactsubdetails (contactsubscriptionid, homephone, leadsource, otherphone, birthday, assistant, assistantphone) values(".$contact_id.", '', '--None--', '', '', '', '')");

  $adb->query("insert into contactscf (contactid) values(".$contact_id.")");

  if($contactnotesname != '')
  {
   
    $contactnote_id = $adb->getUniqueID("crmentity");
    $sql = "insert into crmentity (crmid,smcreatorid,smownerid,modifiedby,setype,description,createdtime,modifiedtime,viewedtime,presence,deleted) values(".$contactnote_id.",".$current_user->id.",".$current_user->id.",".$current_user->id.",'notes','created from business card','".$date_var."','".$date_var."','".$date_var."',0,0".")";
    $adb->query($sql);
 
    $sql_insertnote = "insert into notes(notesid,contact_id,title,notecontent) values(".$contactnote_id.",".$contact_id.",'".$contactnotesname."','".$contactnotesdescription ."')";
 
    $adb->query($sql_insertnote);
  
    $sql_insertsenotesrel = "insert into senotesrel(crmid,notesid) values(".$contact_id.",".$contactnote_id.")";
 
    $adb->query($sql_insertsenotesrel);
   
  }


}
/* 
else if(($acctname != '') && ($lastname == ''))
{
  echo 'acct name given but contactname empty !!!';
  $id = $adb->getUniqueID("crmentity");
  $idholder = $id;
  $sql = "insert into crmentity (crmid,smcreatorid,smownerid,modifiedby,setype,description,createdtime,modifiedtime,viewedtime,presence,deleted) values(".$id.",".$current_user->id.",".$current_user->id.",".$current_user->id.",'Accounts','created from business card','','','',0,0".")";


  $adb->query($sql);


  //insert into account table now

  $sql_insertacct = "insert into account(accountid,accountname,phone,website) values(".$id.",'".$acctname."','".$acctphone."','".$acctwebsite ."')";



  $adb->query($sql_insertacct);



  if($acctnotesname != '')
  {
   
    $accountnote_id = $adb->getUniqueID("crmentity");
    $sql = "insert into crmentity (crmid,smcreatorid,smownerid,modifiedby,setype,description,createdtime,modifiedtime,viewedtime,presence,deleted) values(".$accountnote_id.",".$current_user->id.",".$current_user->id.",".$current_user->id.",'notes','created from business card','','','',0,0".")";
    $adb->query($sql);
 
    $sql_insertnote = "insert into notes(notesid,title,notecontent) values(".$accountnote_id.",'".$acctnotesname."','".$acctnotesdescription ."')";
 
    $adb->query($sql_insertnote);
  
    $sql_insertsenotesrel = "insert into senotesrel(crmid,notesid) values(".$id.",".$accountnote_id.")";
 
    $adb->query($sql_insertsenotesrel);
   
  }


}*/

/*
elseif($lastname != '' && $acctname == '')
{
 
  echo 'contact given but acct empty !!!!!!!';
  $id = $adb->getUniqueID("crmentity");
  $sql = "insert into crmentity (crmid,smcreatorid,smownerid,modifiedby,setype,description,createdtime,modifiedtime,viewedtime,presence,deleted) values(".$id.",".$current_user->id.",".$current_user->id.",".$current_user->id.",'contact','created from business card','','','',0,0".")";

  $adb->query($sql);

  //insert into contactdetails now


  $sql_contactdetails = "insert into contactdetails(contactid,firstname,lastname,title,department,email,mobile,fax,otheremail) values(".$id.",'".$firstname."','".$lastname."','".$title."','".$department."','".$email."','".$mobile."','".$fax."','".$otheremail."')";

  $adb->query($sql_contactdetails);

  if($contactnotesname != '')
  {
   
    $contactnote_id = $adb->getUniqueID("crmentity");
    $sql = "insert into crmentity (crmid,smcreatorid,smownerid,modifiedby,setype,description,createdtime,modifiedtime,viewedtime,presence,deleted) values(".$contactnote_id.",".$current_user->id.",".$current_user->id.",".$current_user->id.",'notes','created from business card','','','',0,0".")";
    $adb->query($sql);
 
    $sql_insertnote = "insert into notes(notesid,title,notecontent) values(".$contactnote_id.",'".$contactnotesname."','".$contactnotesdescription ."')";
 
    $adb->query($sql_insertnote);
  
    $sql_insertsenotesrel = "insert into senotesrel(crmid,notesid) values(".$contact_id.",".$contactnote_id.")";
 
    $adb->query($sql_insertsenotesrel);
   
  }
  
}
*/

  if($_POST['appointment'] != '')
  {
    $appointmentname = $_POST['Appointmentsname'];
    $startdate = $_POST['Appointmentsdate_start'];
    $starttime = $_POST['Appointmentstime_start'];
    
    //insert into crmentity
    //insert into activity
    //insert into event
    //insert into specific table
    //insert into seactivityrel table
    
    $id = $adb->getUniqueID("crmentity");
    $type = $_POST['appointment'];
    
    $sql= "insert into crmentity (crmid,smcreatorid,smownerid,modifiedby,setype,description,createdtime,modifiedtime,viewedtime,presence,deleted) values(".$id.",".$current_user->id.",".$current_user->id.",".$current_user->id.",'Activities','created from business card','".$date_var."','".$date_var."','".$date_var."',0,0".")";
    
    $adb->query($sql);

    
    $sql_activity= "insert into activity (activityid,subject,date_start,time_start,activitytype) values(".$id.",'".$appointmentname."','".$startdate ."','".$starttime ."','".$type."')";
    $adb->query($sql_activity);
    //get what type of appointment is it

    /*if($_POST['appointment'] == 'Call')
    {
    
      $sql_call = "insert into calls(callid) values (".$id .")";
      $adb->query($sql_call);
    }
    else
    {
      $sql_meeting = "insert into meetings(meetingid) values (".$id .")";
      $adb->query($sql_meeting);
      
    }*/
    
    //Save the activity relationship with the account
    if($idholderaccount != 0)
    {
      $sql_seactivityrel = "insert into seactivityrel values(".$idholderaccount.",".$id.")";
      $adb->query($sql_seactivityrel);
    }
    //Save the activity relationship with the contact
    if($idholdercontact != 0)
    {
	$sql_seactivityrel = "insert into cntactivityrel values(".$idholdercontact.",".$id.")";
	$adb->query($sql_seactivityrel);
    }
    
    
  }

  header("Location: index.php?action=DetailView&module=Contacts&record=".$idholdercontact);
 
?>
