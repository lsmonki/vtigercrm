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


require_once('nusoap/lib/nusoap.php');

//Configuration settings -- Start.

$Server_Path = 'http://rajeshkannan'; //This is the path where have you run ther vtiger server.
$proxyhost = '';       //This is the proxy host 
$proxyport = '';       //This is proxy port 
$proxyusername = '';   //This is the proxy setting user name 
$proxypassword = '';   //This is the proxy setting password

//Configuration settings -- End.


$client = new soapclient($Server_Path."/contactserialize.php", false,
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

	$params = array('lastname' => "$lastname",
	                'email'=>"$email",
	                'phone'=>"$phone",
	                'company'=>"$company",
	                'country'=>"$country",
	                'description'=>"$description"
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
		$_REQUEST['error_message'] = "<h3>Last Name and Company must be entered to create a Lead.</h3>";
		include("index.php");
	}
}
else
{
	include("index.php");
}

?>
