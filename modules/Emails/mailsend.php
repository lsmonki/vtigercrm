<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

include("modules/Emails/mail.php");
require_once("include/utils/GetGroupUsers.php");
require_once("include/utils/UserInfoUtil.php");

global $adb;
global $current_user;

//set the return module and return action and set the return id based on return module and record
$returnmodule = $_REQUEST['return_module'];
$returnaction = $_REQUEST['return_action'];
if((($returnmodule != 'Emails') || ($returnmodule == 'Emails' && $_REQUEST['record'] == '')) && $_REQUEST['return_id'] != '')
{
	$returnid = $_REQUEST['return_id'];
}
else
{
	$returnid = $focus->id;//$_REQUEST['record'];
}


$adb->println("\n\nMail Sending Process has been started.");
//This function call is used to send mail to the assigned to user. In this mail CC and BCC addresses will be added.
if($focus->column_fields["assigned_user_id"]==0 && $_REQUEST['assigned_group_name']!='')
{
	$grp_obj = new GetGroupUsers();
	$grp_obj->getAllUsersInGroup(getGrpId($_REQUEST['assigned_group_name']));
	$users_list = constructList($grp_obj->group_users,'INTEGER');
	$sql = "select first_name, last_name, email1, email2, yahoo_id from users where id in ".$users_list;
	$res = $adb->query($sql);
	$user_email = '';
	while ($user_info = $adb->fetch_array($res))
	{
		$email = $user_info['email1'];
		if($email == '' || $email == 'NULL')
		{
			$email = $user_info['email2'];
			if($email == '' || $email == 'NULL')
			{
				$email = $user_info['yahoo_id'];
			}
		}	
		if($user_email=='')
		$user_email .= $user_info['first_name']." ".$user_info['last_name']."<".$email.">";
		else
		$user_email .= ",".$user_info['first_name']." ".$user_info['last_name']."<".$email.">";
		$email='';
	}
	$to_email = $user_email;
}
else
{
	$to_email = getUserEmailId('id',$focus->column_fields["assigned_user_id"]);
}
$cc = $_REQUEST['ccmail'];
$bcc = $_REQUEST['bccmail'];
if($to_email == '' && $cc == '' && $bcc == '')
{
	$adb->println("Mail Error : send_mail function not called because To email id of assigned to user, CC and BCC are empty");
	$mail_status_str = "'".$to_email."'=0&&&";
	$errorheader1 = 1;
}
else
{
	$mail_status = send_mail('Emails',$to_email,$current_user->user_name,'',$_REQUEST['subject'],$_REQUEST['description'],$cc,$bcc,'all',$focus->id);
	//set the errorheader1 to 1 if the mail has not been sent to the assigned to user
	if($mail_status != 1)//when mail send fails
	{
		$errorheader1 = 1;
		$mail_status_str = $to_email."=".$mail_status."&&&";
	}
	elseif($mail_status == 1 && $to_email == '')//Mail send success only for CC and BCC but the 'to' email is empty 
	{
		$errorheader1 = 1;
		$mail_status_str = "cc_success=0&&&";
	}
	else
	{
		$mail_status_str = $to_email."=".$mail_status."&&&";
	}
}


//Added code from mysendmail.php which is contributed by Raju(rdhital)
$parentid= $_REQUEST['parent_id'];
$myids=explode("|",$parentid);
for ($i=0;$i<(count($myids)-1);$i++)
{
	$realid=explode("@",$myids[$i]);
	$nemail=count($realid);
	$mycrmid=$realid[0];
	$pmodule=getSalesEntityType($mycrmid);
	for ($j=1;$j<$nemail;$j++)
	{
		$temp=$realid[$j];
		//$myquery='Select columnname from field where fieldid='.$temp;
		$myquery='Select columnname from field where fieldid='.PearDatabase::quote($temp);
		$fresult=$adb->query($myquery);			
		if ($pmodule=='Contacts')
		{
			require_once('modules/Contacts/Contact.php');
			$myfocus = new Contact();
			$myfocus->retrieve_entity_info($mycrmid,"Contacts");
		}
		elseif ($pmodule=='Accounts')
		{
			require_once('modules/Accounts/Account.php');
			$myfocus = new Account();
			$myfocus->retrieve_entity_info($mycrmid,"Accounts");
		} 
		elseif ($pmodule=='Leads')
		{
			require_once('modules/Leads/Lead.php');
			$myfocus = new Lead();
			$myfocus->retrieve_entity_info($mycrmid,"Leads");
		}
		$fldname=$adb->query_result($fresult,0,"columnname");
		$emailadd=br2nl($myfocus->column_fields[$fldname]);

		if($emailadd != '')
		{
			$mail_status = send_mail('Emails',$emailadd,$current_user->user_name,'',$focus->column_fields['subject'],$focus->column_fields['description'],'','','all',$focus->id);
			$mail_status_str .= $emailadd."=".$mail_status."&&&";
			//added to get remain the EditView page if an error occurs in mail sending
			if($mail_status != 1)
			{
				$errorheader2 = 1;
			}
		}
	}	
}

//Added to redirect the page to Emails/EditView if there is an error in mail sending
if($errorheader1 == 1 || $errorheader2 == 1)
{
	$returnset = 'return_module='.$returnmodule.'&return_action='.$returnaction.'&return_id='.$_REQUEST['return_id'];
	$returnmodule = 'Emails';
	$returnaction = 'EditView';
	//This condition is added to set the record(email) id when we click on send mail button after returning mail error
	if($_REQUEST['mode'] == 'edit')
	{
		$returnid = $_REQUEST['record'];
	}
	else
	{
		$returnid = $_REQUEST['currentid'];
	}
}

//The following function call is used to parse and form a encoded error message and then pass to result page
$mail_error_str = getMailErrorString($mail_status_str);
$adb->println("Mail Sending Process has been finished.\n\n");

header("Location:index.php?module=$returnmodule&action=$returnaction&record=$returnid&$returnset&$mail_error_str");


?>
