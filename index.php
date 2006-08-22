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

include("include.php");

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
	//include("index.php");
	include("login.php");
}
else
{
	$module = '';
	$action = 'login.php';
	if($_SESSION['customer_id'] != '')
	{
		$is_logged = 1;

		//Added to download attachments
		if($_REQUEST['downloadfile'] == 'true')
		{
			$filename = $_REQUEST['filename'];
			$fileType = $_REQUEST['filetype'];
			$fileid = $_REQUEST['fileid'];
			$filesize = $_REQUEST['filesize'];

			$contentname = $fileid.'_filecontents';
			$fileContent = $_SESSION[$contentname];

			header("Content-type: $fileType");
			header("Content-length: $filesize");
			header("Cache-Control: private");
			header("Content-Disposition: attachment; filename=$filename");
			header("Content-Description: PHP Generated Data");
			echo base64_decode($fileContent);

			exit;
		}
		if($_REQUEST['module'] != '' && $_REQUEST['action'] != '')
		{
			$module = $_REQUEST['module']."/";
			$action = $_REQUEST['action'].".php";
		}
		elseif($_REQUEST['action'] != '' && $_REQUEST['module'] == '')
		{
			$action = $_REQUEST['action'].".php";
		}
		elseif($_SESSION['customer_id'] != '')
		{
			$module = 'Tickets';
			//$action = "TicketsList.php";
			$action = "index.php";
		}
	}
	$filename = $module.$action;

	if($is_logged == 1)
	{
		include("Tickets/Utils.php");
		include("language/en_us.lang.php");
		include("header.html");

	?>

	<script>
		<?php
		if(strstr($module,'Tickets'))
		{
		?>
			document.getElementById("pi").className = "dvtSelectedCell";
			document.getElementById("mi").className = "dvtUnSelectedCell";
		<?php
		}
		elseif(strstr($module,'Faq'))
		{
		?>
			document.getElementById("mi").className = "dvtSelectedCell";
			document.getElementById("pi").className = "dvtUnSelectedCell";
		<?php
		}
		?>
	</script>

	<?

		if(is_file($filename))
			include($filename);
		elseif($_SESSION['customer_id'] != '')
			include("Tickets/index.php");

		include("footer.html");
	}
	else
		header("Location: login.php");

}

?>
