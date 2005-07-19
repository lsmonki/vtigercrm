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

if($_REQUEST['create'] == 'contact')
{
	$contact_lastname = $_POST['contact_lastname'];
	$contact_firstname = $_POST['contact_firstname'];
	$contact_email = $_POST['contact_email'];
	$contact_phone = $_POST['contact_phone'];
	$contact_title = $_POST['contact_title'];
	$contact_department = $_POST['contact_department'];
	$contact_description = "WebForm : ".$_POST['contact_description'];

	$params = array('user_name'=>'',
			'first_name' => "$contact_firstname",
			'last_name' => "$contact_lastname",
	                'email_address'=>"$contact_email",
			'account_name'=>'',
			'salutation'=>'',
			'title'=>"$contact_title",
			'phone_mobile'=>'',
			'reports_to'=>'',
			'primary_address_street'=>'',
			'primary_address_city'=>'',
		        'primary_address_state'=>'' ,
        		'primary_address_postalcode'=>'',
        		'primary_address_country'=>'',
		        'alt_address_city'=>'',
		        'alt_address_street'=>'',
		        'alt_address_state'=>'',
		        'alt_address_postalcode'=>'',
		        'alt_address_country'=>'',
		        'office_phone'=>'',
	                'home_phone'=>"$contact_phone",
	                'fax'=>'',
			'department'=>$contact_department,
	                'description'=>"$contact_description"
			);
	if($contact_lastname != '')
	{
		$result = $client->call('create_contact', $params, $Server_Path, $Server_Path);
	
		if($result['faultstring'] != '' && is_array($result))
		{
			echo '<br>'.$result['faultstring'];
		}
		elseif($result != '')
		{
			echo '<br><br>The Contact has been Successfully created in vtiger CRM.<br><br><a href="index.php">Home</a>';
		}
	}
	else
	{
		$_REQUEST['error_message'] = "<h3>Last Name must be entered to create a Contact.</h3>";
		include("index.php");
	}

}
else
{
	include("index.php");
}

?>
