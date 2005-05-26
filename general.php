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
$proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
$proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
$proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
$proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
global $Server_Path;
global $client;

$client = new soapclient($Server_Path."/contactserialize.php", false,
                                                $proxyhost, $proxyport, $proxyusername, $proxypassword);

if($_REQUEST['logout'] == 'true')
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

elseif(isset($_REQUEST['action']) && $_REQUEST['action'] != '' && $_SESSION['customer_id'] != '')
{
	$action = $_REQUEST['action'].".php";
	include($action);
}
else
{
	include("UserTickets.php");
}


?>
