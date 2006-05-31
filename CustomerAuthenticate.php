<?
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

include("include.php");

$err = $client->getError();
if ($err)
{
        echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
}

$username = $_REQUEST['username'];
$password = $_REQUEST['pw'];

//$encrypted_password = encrypt_password($username,$password);

$params = array('user_name' => "$username",
		'user_password'=>"$password");

global $result;
$result = $client->call('authenticate_user', $params, $Server_Path, $Server_Path);

if($result[1] == $username && $result[2] == $password)
{
	session_start();
	$_SESSION['customer_id'] = $result[0];
	$_SESSION['customer_name'] = $result[1];
	$_SESSION['last_login'] = $result[3];
	$_SESSION['support_start_date'] = $result[4];
	$_SESSION['support_end_date'] = $result[5];

        $params1 = Array('id' => "$result[0]",'flag'=>"login");
        $result1 = $client->call('update_login_details', $params1, $Server_Path, $Server_Path);

	//include("general.php");
	header("Location: index.php?action=index&module=Tickets");
}
else
{
	$login_error_msg = base64_encode('<font color=red size=1px;> Please enter a valid Username and Password</font>');
	header("Location: login.php?login_error=$login_error_msg");
}
/*
function encrypt_password($user_name,$user_password)
{
	// encrypt the password.
        $salt = substr($user_name, 0, 2);
        $encrypted_password = crypt($user_password, $salt);

        return $encrypted_password;
}
*/
?>
