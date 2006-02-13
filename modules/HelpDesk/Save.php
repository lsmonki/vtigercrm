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
 * $Header: /advent/projects/wesat/vtiger_crm/vtigercrm/modules/HelpDesk/Save.php,v 1.8 2005/04/25 05:21:46 rajeshkannan Exp $
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
if(isset($_REQUEST['record']))
{
	$focus->id = $_REQUEST['record'];
}
if(isset($_REQUEST['mode']))
{
	$focus->mode = $_REQUEST['mode'];
}

foreach($focus->column_fields as $fieldname => $val)
{
	if(isset($_REQUEST[$fieldname]))
	{
		$value = $_REQUEST[$fieldname];
		$focus->column_fields[$fieldname] = $value;
	}
		
}


//$focus->saveentity("HelpDesk");
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

$_REQUEST['name'] = '[ Ticket ID : '.$focus->id.' ] '.$reply.$_REQUEST['ticket_title'];
$bodysubject = ' Subject : '.$focus->id.' : '.$_REQUEST['ticket_title'].'<br><br>';

if($focus->column_fields['parent_id'] != '')
{
	$query = "select * from crmentity where crmid=".$focus->column_fields['parent_id'];
	$parent_module = $adb->query_result($adb->query($query),0,'setype');
	if($parent_module == 'Contacts')
	{
		$sql = "select * from contactdetails where contactid=".$focus->column_fields['parent_id'];
		$result = $adb->query($sql);
		$emailoptout = $adb->query_result($result,0,'emailoptout');
		$contactname = $adb->query_result($result,0,'firstname').' '.$adb->query_result($result,0,'lastname');
		$contact_mailid = $adb->query_result($result,0,'email');
	}
}
if($contact_mailid != '')
{
	$sql = "select * from PortalInfo where user_name='".$contact_mailid."'";
	$isactive = $adb->query_result($adb->query($sql),0,'isactive');
}
if($isactive == 1)
{
	$bodydetails = "Dear ".$contactname.",<br><br>";
	$bodydetails .= 'There is a reply to <b>'.$_REQUEST['ticket_title'].'</b> in the "Customer Portal" at VTiger.';
	$bodydetails .= "You can use the following link to view the replies made:<br>";

	//Provide your customer portal url
	$PORTAL_URL = "<customerportal-url:port>";//e.g : vtigercrm:90/customerportal

	$bodydetails .= "<a href='http://".$PORTAL_URL."/general.php?action=UserTickets&ticketid=".$focus->id."&fun=detail'>Ticket Details</a>";
	$bodydetails .= "<br><br>Thanks,<br><br> Vtiger Support Team ";

	$_REQUEST['description'] = $bodysubject.$bodydetails;
}
else
{
	$desc = 'Ticket ID : '.$focus->id.'<br> Ticket Title : '.$reply.$_REQUEST['ticket_title'];
	$desc .= "<br><br>Dear ".$contactname.",<br><br>The Ticket is replied and the details are : <br>";
	$desc .= "<br> Status : ".$focus->column_fields['ticketstatus'];
	$desc .= "<br> Category : ".$focus->column_fields['ticketcategories'];
	$desc .= "<br> Severity : ".$focus->column_fields['ticketseverities'];
	$desc .= "<br> Priority : ".$focus->column_fields['ticketpriorities'];
	$desc .= '<br><br>Description : <br>'.$focus->column_fields['description'];
	$desc .= '<br><br>Solution : <br>'.$focus->column_fields['solution'];
	$desc .= getTicketComments($focus->id);

	$_REQUEST['description'] = $desc;
}
//$_REQUEST['parent_id'] = $_REQUEST['contact_id'];
$_REQUEST['return_id'] = $return_id;

if($_REQUEST['product_id'] != '' && $focus->id != '' && $_REQUEST['mode'] != 'edit')
{
        $sql = 'insert into seticketsrel values('.$_REQUEST['product_id'].' , '.$focus->id.')';
        $adb->query($sql);
        $return_id = $_REQUEST['product_id'];
}

if($emailoptout == 0)
{
	require_once('modules/Emails/send_mail.php');
}
else
{
	header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
}

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
