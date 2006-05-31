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

@include("../PortalConfig.php");
if(!isset($_SESSION['customer_id']) || $_SESSION['customer_id'] == '')
{
	@header("Location: $Authenticate_Path/login.php");
	exit;
}

//include("Tickets/Utils.php");
include("Tickets/index.html");

global $result;
$username = $_SESSION['customer_name'];
$id = $_SESSION['customer_id'];

if($_REQUEST['fun'] == '' || $_REQUEST['fun'] == 'home')
{
	// This is an archaic parameter list
	$params = array('user_name' => "$username", 'id' => "$id");
	$result = $client->call('get_tickets_list', $params, $Server_Path, $Server_Path);

	include("TicketsList.php");
}
elseif($_REQUEST['fun'] == 'newticket')
{
	include("NewTicket.php");
}
elseif($_REQUEST['fun'] == 'detail' || $_REQUEST['fun'] == 'updatecomment' || $_REQUEST['fun'] == 'close_ticket' || $_REQUEST['fun'] == 'uploadfile')
{
	if($_REQUEST['fun'] == 'updatecomment')
	{
		UpdateComment();
	}
	if($_REQUEST['fun'] == 'close_ticket')
	{
		$ticketid = $_REQUEST['ticketid'];
		$res = Close_Ticket($ticketid);
	}
	if($_REQUEST['fun'] == 'uploadfile')
	{
		$upload_status = AddAttachment();
	}

	// This is an archaic parameter list
	$params = array('user_name' => "$username", 'id' => "$id");
	$result = $client->call('get_tickets_list', $params, $Server_Path, $Server_Path);

	include("TicketDetail.php");
}
elseif($_REQUEST['fun'] == 'saveticket')
{
	include("SaveTicket.php");
}
elseif($_REQUEST['fun'] == 'search')
{
	$params = array('user_name' => "$username", 'id' => "$id");
	$result = $client->call('get_tickets_list', $params, $Server_Path, $Server_Path);

	include("TicketSearch.php");
}

if($_REQUEST['fun'] == 'changepassword')
{
	echo '<br> Change Password Option in Tickets/index.php file';
	//$list = ChangePasswordUI();
	//echo $list;
}

echo '</table></td></tr></table></td></tr></table><br><br>';

//This is to see the soap request and Response for debugging
//echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
//echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
//echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';

?>
