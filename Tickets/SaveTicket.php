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

global $client;
global $result;

$ticket = Array(
		'title'=>'title',
		'productid'=>'productid',
		'description'=>'description',
		'priority'=>'priority',
		'category'=>'category',
		'owner'=>'owner',
		'module'=>'module'
	       );

foreach($ticket as $key => $val)
	$ticket[$key] = $_REQUEST[$key];

$ticket['owner'] = $username;
$ticket['productid'] = $_SESSION['combolist'][0]['productid'][$ticket['productid']];


$title = $_REQUEST['title'];
$description = $_REQUEST['description'];
$priority = $_REQUEST['priority'];
$severity = $_REQUEST['severity'];
$category = $_REQUEST['category'];
$parent_id = $_SESSION['customer_id'];
$productid = $_SESSION['combolist'][0]['productid'][$_REQUEST['productid']];

$module = $_REQUEST['module'];

$params = array(
		'title'=>"$title",
		'description'=>"$description",
		'priority'=>"$priority",
		'severity'=>"$severity",
		'category'=>"$category",
		'user_name' => "$username",
		'parent_id'=>"$parent_id",
		'product_id'=>"$productid",
		'module'=>"$module"
	       );

$record_result = $client->call('create_ticket', $params);
if(isset($record_result[0]['new_ticket']) && $record_result[0]['new_ticket']['ticketid'] != '')
{
	$new_record = 1;
	$ticketid = $record_result[0]['new_ticket']['ticketid'];
}

$params_list = array('user_name' => "$username", 'id' => "$parent_id");
$result = $client->call('get_tickets_list', $params_list, $Server_Path, $Server_Path);

//header("Location: index.php?module=Tickets&action=index&fun=home");
if($new_record == 1)
{
	include("Tickets/TicketDetail.php");
}
else
{
	//$list = HomeTickets($result);
	//include("home.php");
	echo '<br> There may be problem in saving the Ticket. Please check whether the ticket has been created or not';
	include("NewTicket.php");
}





?>
