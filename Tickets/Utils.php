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


function getComboList($name, $value, $defaultval='', $selectval='')
{
	$list = '<select name="'.$name.'" size="1" class="detailedViewTextBox">';

	//Add the default value as a first option
	if($defaultval != '')
		$list .= '<OPTION value="'.$defaultval.'">'.$defaultval.'</OPTION>';

	foreach($value as $index => $val)
	{
		$selected = '';
		if($selectval == $val)
			$selected = ' selected ';
		$list .= '<OPTION value="'.$val.'" '.$selected.'>'.$val.'</OPTION>';
	}
	$list .= '</select>';

	return $list;
}

function UpdateComment()
{
	global $client;
	$ticketid = $_REQUEST['ticketid'];
	$ownerid = $_SESSION['customer_id'];
	$comments = $_REQUEST['comments'];

        $params = Array('id'=>"$ticketid",'ownerid'=>"$ownerid",'comments'=>"$comments");
        $commentresult = $client->call('update_ticket_comment', $params, $Server_Path, $Server_Path);
}

function Close_Ticket($ticketid)
{
        global $client;
        $params = Array('id'=>"$ticketid");
        $result = $client->call('close_current_ticket', $params, $Server_Path, $Server_Path);
        return $result;
}

function getPicklist($picklist_name)
{
	global $client;

	$params = Array('id'=>"$picklist_name");
	$ticket_picklist_array = $client->call('get_picklists', $params, $Server_Path, $Server_Path);

	return $ticket_picklist_array;
}

function getStatusComboList($selectedvalue='')
{
	$temp_array = getPicklist('ticketstatus');

	$status_combo = "<option value=''>All</option>";
	foreach($temp_array as $index => $val)
	{
		$select = '';
		if($val == $selectedvalue)
			$select = ' selected';

		$status_combo .= '<option value="'.$val.'"'.$select.'>'.$val.'</option>';
	}

	return $status_combo;
}

//Added for My Settings - Save Password
function SavePassword()
{
	global $client;
	global $mod_strings;

	$customer_id = $_SESSION['customer_id'];
	$customer_name = $_SESSION['customer_name'];
	$oldpw = $_REQUEST['old_password'];
	$newpw = $_REQUEST['new_password'];
	$confirmpw = $_REQUEST['confirm_password'];

	$params = Array('user_name'=>"$customer_name",'user_password'=>"$oldpw");
	$result = $client->call('authenticate_user',$params);

	if($oldpw == $result[2])
	{
		if($newpw == $confirmpw)
		{
			$id = $result[0];
			$params = Array('id'=>"$id",'user_name'=>"$customer_name",'user_password'=>"$newpw");
			$result = $client->call('change_password',$params);
			$errormsg .= $mod_strings['MSG_PASSWORD_CHANGED'];
		}
		else
		{
			$errormsg = $mod_strings['MSG_ENTER_NEW_PASSWORDS_SAME'];
		}
	}
	else
	{
		$errormsg = $mod_strings['MSG_YOUR_PASSWORD_WRONG'];
	}

	return $errormsg;
}

function getTicketAttachmentsList($ticketid)
{
	global $client;
	global $mod_strings;

	$customer_id = $_SESSION['customer_id'];
	$customer_name = $_SESSION['customer_name'];

	$params = Array('user_id'=>"$customer_id",'ticketid'=>"$ticketid");
	$result = $client->call('get_ticket_attachments',$params);

	return $result;
}

function AddAttachment()
{
	global $client;
	$ticketid = $_REQUEST['ticketid'];
	$ownerid = $_SESSION['customer_id'];

	$filename = $_FILES['customerfile']['name'];
	$filetype = $_FILES['customerfile']['type'];
	$filesize = $_FILES['customerfile']['size'];
	$fileerror = $_FILES['customerfile']['error'];

	$upload_error = '';
	if($fileerror == 4)
	{
		$upload_error = 'Kindly give a valid file for upload!';
	}
	elseif($fileerror == 2)
	{
		$upload_error = 'Sorry, the uploaded file exceeds the maximum filesize limit. Please try a small file';
	}
	elseif($fileerror == 3)
	{
		$upload_error = 'Problems in file upload. Please try again!';
	}

	//Copy the file in temp and then read and pass the contents of the file as a string to db
	global	$upload_dir;

	if($filesize > 0)
	{
		if(move_uploaded_file($_FILES["customerfile"]["tmp_name"],$upload_dir.'/'.$_FILES["customerfile"]["name"]))
		{
			$filecontents = base64_encode(fread(fopen($upload_dir.'/'.$filename, "r"), $filesize));
		}

		$params = Array(
				'id'=>"$ticketid",
				'filename'=>"$filename",
				'filetype'=>"$filetype",
				'filesize'=>"$filesize",
				'filecontents'=>"$filecontents"
			       );
		$commentresult = $client->call('add_ticket_attachment', $params, $Server_Path, $Server_Path);
	}
	else
	{
		$upload_error = 'Please enter a valid file.';
	}

	return $upload_error;
}

?>
