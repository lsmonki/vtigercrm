<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the 
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/vtigercrm/modules/HelpDesk/Save.php,v 1.8 2005/04/25 05:21:46 Mickie Exp $
 * Description:  Saves an Account record and then redirects the browser to the 
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/HelpDesk/HelpDesk.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');

$focus = new HelpDesk();

setObjectValuesFromRequest(&$focus);

$focus->save("HelpDesk");
$return_id = $focus->id;

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "HelpDesk";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

if($_REQUEST['mode'] == 'edit')
	$reply = 'Re : ';
else
	$reply = '';

$subject = '[ Ticket ID : '.$focus->id.' ] '.$reply.$_REQUEST['ticket_title'];
$bodysubject = ' Ticket ID : '.$focus->id.'<br> Subject : '.$_REQUEST['ticket_title'];

$emailoptout = 0;

//To get the emailoptout field value and then decide whether send mail about the tickets or not
if($focus->column_fields['parent_id'] != '')
{
	$parent_module = getSalesEntityType($focus->column_fields['parent_id']);
	if($parent_module == 'Contacts')
	{
		$result = $adb->query("select * from contactdetails where contactid=".$focus->column_fields['parent_id']);
		$emailoptout = $adb->query_result($result,0,'emailoptout');
		$contactname = $adb->query_result($result,0,'firstname').' '.$adb->query_result($result,0,'lastname');
		$parentname = $contactname;
		$contact_mailid = $adb->query_result($result,0,'email');
	}
	if($parent_module == 'Accounts')
	{
		$result = $adb->query("select * from account where accountid=".$focus->column_fields['parent_id']);
		$emailoptout = $adb->query_result($result,0,'emailoptout');
		$parentname = $adb->query_result($result,0,'accountname');
	}
}

//Get the status of the portal user. if the customer is active then send the portal link in the mail
if($contact_mailid != '')
{
	$sql = "select * from portalinfo where user_name='".$contact_mailid."'";
	$isactive = $adb->query_result($adb->query($sql),0,'isactive');
}
if($isactive == 1)
{
	$bodydetails = "Dear ".$contactname.",<br><br>";
	$bodydetails .= 'There is a reply to <b>'.$_REQUEST['ticket_title'].'</b> in the "Customer Portal" at VTiger.';
	$bodydetails .= "You can use the following link to view the replies made:<br>";

	$bodydetails .= "<a href='".$PORTAL_URL."/general.php?action=UserTickets&ticketid=".$focus->id."&fun=detail'>Ticket Details</a>";
	$bodydetails .= "<br><br>Thanks,<br><br> Vtiger Support Team ";

	$email_body = $bodysubject.'<br><br>'.$bodydetails;
}
else
{
	$desc = 'Ticket ID : '.$focus->id.'<br> Ticket Title : '.$reply.$_REQUEST['ticket_title'];
	$desc .= "<br><br>Dear ".$parentname.",<br><br>The Ticket is replied and the details are : <br>";
	$desc .= "<br> Status : ".$focus->column_fields['ticketstatus'];
	$desc .= "<br> Category : ".$focus->column_fields['ticketcategories'];
	$desc .= "<br> Severity : ".$focus->column_fields['ticketseverities'];
	$desc .= "<br> Priority : ".$focus->column_fields['ticketpriorities'];
	$desc .= '<br><br>Description : <br>'.$focus->column_fields['description'];
	$desc .= '<br><br>Solution : <br>'.$focus->column_fields['solution'];
	$desc .= getTicketComments($focus->id);

	$email_body = $desc;
}
$_REQUEST['return_id'] = $return_id;

if($_REQUEST['product_id'] != '' && $focus->id != '' && $_REQUEST['mode'] != 'edit')
{
        $sql = 'insert into seticketsrel values('.$_REQUEST['product_id'].' , '.$focus->id.')';
        $adb->query($sql);

	if($_REQUEST['return_module'] == 'Products')
	        $return_id = $_REQUEST['product_id'];
}

//send mail to the assigned to user and the parent to whom this ticket is assigned
require_once('modules/Emails/mail.php');
$user_emailid = getUserEmailId('id',$focus->column_fields['assigned_user_id']);
if($user_emailid != '')
{
	$mail_status = send_mail('HelpDesk',$user_emailid,$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID,$subject,$email_body);
	$mail_status_str = $user_emailid."=".$mail_status."&&&";
}
else
{
	$mail_status_str = "'".$to_email."'=0&&&";
}
//added condition to check the emailoptout(this is for contacts and accounts.)
if($emailoptout == 0)
{
	//send mail to parent
	if($_REQUEST['parent_id'] != '' && $_REQUEST['parent_type'] != '')
        {
                $parentmodule = $_REQUEST['parent_type'];
                $parentid = $_REQUEST['parent_id'];

		$parent_email = getParentMailId($parentmodule,$parentid);	
		$mail_status = send_mail('HelpDesk',$parent_email,$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID,$subject,$email_body);
		$mail_status_str .= $parent_email."=".$mail_status."&&&";
        }
}
else
{
	$adb->println("'".$parentname."' is not want to get the email about the ticket details as emailoptout is selected");
}

$mail_error_status = getMailErrorString($mail_status_str);

//code added for returning back to the current view after edit from list view
if($_REQUEST['return_viewname'] == '') $return_viewname='0';
if($_REQUEST['return_viewname'] != '')$return_viewname=$_REQUEST['return_viewname'];
header("Location: index.php?action=$return_action&module=$return_module&record=$return_id&$mail_error_status&viewname=$return_viewname");

function getTicketComments($ticketid)
{
	global $adb;

	$commentlist = '';
	$sql = "select * from ticketcomments where ticketid=".$ticketid;
	$result = $adb->query($sql);
	for($i=0;$i<$adb->num_rows($result);$i++)
	{
		$comment = $adb->query_result($result,$i,'comments');
		if($comment != '')
		{
			$commentlist .= '<br><br>'.$comment;
		}
	}
	if($commentlist != '')
		$commentlist = '<br><br> The comments are : '.$commentlist;

	return $commentlist;
}
?>
