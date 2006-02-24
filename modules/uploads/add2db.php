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

require_once('include/database/PearDatabase.php');
require_once('include/utils/utils.php');
global $current_user;
$vtigerpath = $_SERVER['REQUEST_URI'];
$vtigerpath = str_replace("/index.php?module=uploads&action=add2db", "", $vtigerpath);
$directory = $root_directory."/storage/user_".getUserName($current_user->id)."/attachments/";

if(!is_dir($directory))
{
	if(!mkdirs($directory, 0777))	
	{
		echo "Access denined to create folder";
		die;
	}
}
$uploaddir = $directory;
$crmid = $_REQUEST['return_id'];

for ($filecount=0;$filecount<count($_FILES) && $_FILES['file_'.$filecount]!='';$filecount++)
{
	// Arbitrary File Upload Vulnerability fix - Philip
	$binFile = $_FILES['file_'.$filecount]['name'];

	$ext_pos = strrpos($binFile, ".");

	$ext = substr($binFile, $ext_pos + 1);

	if (in_array($ext, $upload_badext))
	{
		$binFile .= ".txt";
	}

	$_FILES["file_".$filecount]["name"] = $binFile;
	// Vulnerability fix ends

	if(move_uploaded_file($_FILES["file_".$filecount]["tmp_name"],$uploaddir.$crmid."_".$_FILES["file_".$filecount]["name"])) 
	{
		$filename = $crmid.'_'.basename($binFile);
		$filetype= $_FILES['file_'.$filecount]['type'];
		$filesize = $_FILES['file_'.$filecount]['size'];

		if($filesize != 0)	
		{
			$current_id = $adb->getUniqueID("crmentity");
			$desc = $_REQUEST['txtDescription'];
			$description = addslashes($desc);
			$date_var = date('YmdHis');

			$query = "insert into crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime) values('";
			$query .= $current_id."','".$current_user->id."','".$current_user->id."','".$_REQUEST['return_module'].' Attachment'."','".$description."','".$date_var."')";
			$result = $adb->query($query);

			$sql = "insert into attachments values(";
			$sql .= $current_id.",'".$filename."','".$description."','".$filetype."')";
			$result = $adb->query($sql);


			$sql1 = "insert into seattachmentsrel values('";
			$sql1 .= $crmid."','".$current_id."')";
			$result = $adb->query($sql1);

			header("Location: index.php?action=".$_REQUEST['return_action']."&module=".$_REQUEST['return_module']."&record=".$_REQUEST['return_id']."&filename=".$filename."");
		}
		else
		{
			include('themes/'.$theme.'/header.php');
			$errormessage = "<font color='red'><B>Error Message<ul>
				<li><font color='red'>Invalid file OR</font>
				<li><font color='red'>File has no data</font>
				</ul></B></font> <br>" ;
			echo $errormessage;
			include "upload.php";
		}			
	} 
	else 
	{
		$errorCode =  $_FILES['binFile']['error'];

		if($errorCode == 4)
		{
			include('themes/'.$theme.'/header.php');
			$errormessage = "<B><font color='red'>Kindly give a valid file for upload!</font></B> <br>" ;
			echo $errormessage;
			include "upload.php";
		}
		else if($errorCode == 2)
		{
			$errormessage = "<B><font color='red'>Sorry, the uploaded file exceeds the maximum filesize limit. Please try a file smaller than 1000000 bytes</font></B> <br>";
			include('themes/'.$theme.'/header.php');
			echo $errormessage;
			include "upload.php";
			//echo $errorCode;
		}
		else if($errorCode == 3 || $errorcode == '')
		{
			include('themes/'.$theme.'/header.php');
			echo "<b><font color='red'>Problems in file upload. Please try again!</font></b><br>";
			include "upload.php";
		}

	}

}
?>
