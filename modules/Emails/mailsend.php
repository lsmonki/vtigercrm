<?php
include("modules/Emails/mail.php");

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
//This function call is used to send mail to the assigned to user
$to_email = getUserEmailId('id',$focus->column_fields["assigned_user_id"]);
if($to_email != '')
{
	$mail_status = send_mail('Emails',$to_email,$current_user->user_name,'',$_REQUEST['name'],$_REQUEST['description'],$_REQUEST['ccmail'],$_REQUEST['bccmail']);
	//set the errorheader1 to 1 if the mail has not been sent to the assigned to user
	if($mail_status != 1)//== 'connect_host')
	{
		//$adb->println("Email server configuration is not correct. Page will be redirected to Emails/EditView");
		$errorheader1 = 1;
	}
	$mail_status_str = $to_email."=".$mail_status."&&&";
}
else
{
	$adb->println("Mail Error : send_mail function not called because email id for assigned to user is empty");
	$mail_status_str = "'".$to_email."'=0&&&";
	$errorheader1 = 1;
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
	for ($j=1;$j<$nemail;$j++){
		$temp=$realid[$j];
		$myquery='Select columnname from field where fieldid='.$temp;
		$fresult=$adb->query($myquery);			
		if ($pmodule=='Contacts'){
			require_once('modules/Contacts/Contact.php');
			$myfocus = new Contact();
			$myfocus->retrieve_entity_info($mycrmid,"Contacts");
		}
		elseif ($pmodule=='Accounts'){
			require_once('modules/Accounts/Account.php');
			$myfocus = new Account();
			$myfocus->retrieve_entity_info($mycrmid,"Accounts");
		} 
		elseif ($pmodule=='Leads'){
			require_once('modules/Leads/Lead.php');
			$myfocus = new Lead();
			$myfocus->retrieve_entity_info($mycrmid,"Leads");
		}
		$fldname=$adb->query_result($fresult,0,"columnname");
		$emailadd=br2nl($myfocus->column_fields[$fldname]);
		//send_mail($emailadd,$from,$subject,$contents,$mail_server,$mail_server_username,$mail_server_password,$attachpath,$ccmail);
		if($emailadd != '')
		{
			$mail_status = send_mail('Emails',$emailadd,$current_user->user_name,'',$focus->column_fields['subject'],$focus->column_fields['description']);
			$mail_status_str .= $emailadd."=".$mail_status."&&&";
			//added to get remail the EditView if an error occurs in mail sending
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
