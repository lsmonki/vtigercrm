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

if($_REQUEST['create'] == 'lead')
{
	$lastname = $_POST['lastname'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$company = $_POST['company'];
	$country = $_POST['country'];
	$description = "WebForm : ".$_POST['description'];

	$params = array(
				'lastname' => "$lastname",
		                'email'=>"$email",
		                'phone'=>"$phone",
	        	        'company'=>"$company",
	                	'country'=>"$country",
		                'description'=>"$description",
		                'assigned_user_id'=>"$assigned_user_id"
			);

	if($lastname != '' && $company != '')
	{
		$result = $client->call('create_lead_from_webform', $params, $Server_Path, $Server_Path);
	
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
		$error_message = "<h3>Last Name and Company must be entered to create a Lead.</h3>";
		include("index.php");
	}
}
else
{
	include("index.php");
}

?>
