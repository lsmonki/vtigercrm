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

include("config.php");
require_once('nusoap/lib/nusoap.php');

$client = new soapclient($Server_Path."/vtigerservice.php?service=webforms", false,
                                                $proxyhost, $proxyport, $proxyusername, $proxypassword);
$err = $client->getError();

if($_REQUEST['create'] == 'contact')
{
	$contact_lastname = $_POST['contact_lastname'];
	$contact_firstname = $_POST['contact_firstname'];
	$contact_email = $_POST['contact_email'];
	$contact_phone = $_POST['contact_phone'];
	$contact_department = $_POST['contact_department'];
	$contact_description = "WebForm : ".$_POST['contact_description'];

	$params = array(
				'first_name' => "$contact_firstname",
				'last_name' => "$contact_lastname",
		                'email_address'=>"$contact_email",
	        	        'home_phone'=>"$contact_phone",
				'department'=>$contact_department,
		                'description'=>"$contact_description",
		                'assigned_user_id'=>"$assigned_user_id"
			);

	if($contact_lastname != '')
	{
		$result = $client->call('create_contact_from_webform', $params, $Server_Path, $Server_Path);
	
		if($result['faultstring'] != '' && is_array($result))
		{
			echo '<br>'.$result['faultstring'];
		}
		else
		{
			echo '<br><br>'.$result.'<br><br><a href="index.php">Home</a>';
		}
	}
	else
	{
		$error_message = "<h3>Last Name must be entered to create a Contact.</h3>";
		include("index.php");
	}

}
else
{
	include("index.php");
}

?>
