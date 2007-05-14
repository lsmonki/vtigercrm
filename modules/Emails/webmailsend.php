<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
require_once("modules/Emails/mail.php");

$mail_status = send_mail('Emails',$_REQUEST["parent_name"],$_REQUEST['from_add'],'',$_REQUEST['subject'],$_REQUEST['description'],$_REQUEST["ccmail"],$_REQUEST["bccmail"],'all',$focus->id);
	
$query = 'update vtiger_emaildetails set email_flag ="SENT" where emailid='.$focus->id;
$adb->query($query);

//set the errorheader1 to 1 if the mail has not been sent to the assigned to user
if($mail_status != 1) { //when mail send fails 
		$errorheader1 = 1;
		$mail_status_str = $to_email."=".$mail_status."&&&";

} elseif($mail_status == 1 && $to_email == '') { //Mail send success only for CC and BCC but the 'to' email is empty 
		$adb->query($query);
		$errorheader1 = 1;
		$mail_status_str = "cc_success=0&&&";
} else
	$mail_status_str = $to_email."=".$mail_status."&&&";



//Added to redirect the page to Emails/EditView if there is an error in mail sending
if($errorheader1 == 1 || $errorheader2 == 1)
{
	$returnset = 'return_module='.$returnmodule.'&return_action='.$returnaction.'&return_id='.$_REQUEST['return_id'];
	$returnmodule = 'Emails';
	$returnaction = 'EditView';
	if($_REQUEST['mode'] == 'edit')
		$returnid = $_REQUEST['record'];
	else
		$returnid = $_REQUEST['currentid'];
}

//The following function call is used to parse and form a encoded error message and then pass to result page
$mail_error_str = getMailErrorString($mail_status_str);
$adb->println("Mail Sending Process has been finished.\n\n");

if(isset($_REQUEST['popupaction']) && $_REQUEST['popupaction'] != '')
{
	$inputs="<script>window.self.close();</script>";
	echo $inputs;
}
?>
