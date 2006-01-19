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


session_start();

require_once('PortalConfig.php');
require_once('nusoap/lib/nusoap.php');
$proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : $Proxy_Host;
$proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : $Proxy_Port;
$proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : $Proxy_Username;
$proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : $Proxy_Password;
global $Server_Path;
global $client;

$client = new soapclient($Server_Path."/vtigerservice.php?service=customerportal", false,
                                                $proxyhost, $proxyport, $proxyusername, $proxypassword);

if($_REQUEST['param'] == 'forgot_password')
{
	global $client;

	$email = $_REQUEST['email_id'];
	$params = array('email' => "$email");
	$result = $client->call('send_mail_for_password', $params);
	$_REQUEST['mail_send_message'] = $result;
	require_once("supportpage.php");
}
elseif($_REQUEST['logout'] == 'true')
{
	$id = $_SESSION['customer_id'];
        $params = array('id' => "$id",'flag'=>"logout");
        $result = $client->call('update_login_details', $params);

	session_unregister('customer_id');
	session_unregister('customer_name');
	session_unregister('last_login');
	session_unregister('support_start_date');
	session_unregister('support_end_date');
	session_destroy();
	include("index.php");
}
else
{
	if(isset($_REQUEST['action']) && $_REQUEST['action'] != '' && $_SESSION['customer_id'] != '')
		$action = $_REQUEST['action'].".php";
	else
		$action = "UserTickets.php";

	include($action);
}

?>
