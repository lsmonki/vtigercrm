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
require_once('nusoap.php');

$client = new soapclient($Server_Path."/vtigerservice.php?service=webforms", false,
                                                $proxyhost, $proxyport, $proxyusername, $proxypassword);
$err = $client->getError();

if($_REQUEST['unsubscribe'] == 'true')
{
	$email_address = $_POST['email_address'];
	
	$params = array('email_address'=>"$email_address");

	if(trim($email_address) != '')
	{
		$result = $client->call('unsubscribe_email', $params, $Server_Path, $Server_Path);

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
		$error_message = "<h3>Email address must be entered to Unsubscribe.</h3>";
		include("index.php");
	}

}
else
{
	include("index.php");
}

?>
