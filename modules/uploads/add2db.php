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
require_once('include/utils.php');
$vtigerpath = $_SERVER['REQUEST_URI'];
$vtigerpath = str_replace("/index.php?module=uploads&action=add2db", "", $vtigerpath);
$uploaddir = $root_directory ."/test/upload/" ;// set this to wherever
// Arbitrary File Upload Vulnerability fix - Philip
$binFile = $_FILES['binFile']['name'];
    $ext_pos = strrpos($binFile, ".");

        $ext = substr($binFile, $ext_pos + 1);

        if (in_array($ext, $upload_badext))
        {
                $binFile .= ".txt";
        }
$_FILES["binFile"]["name"] = $binFile;
// Vulnerability fix ends

if(move_uploaded_file($_FILES["binFile"]["tmp_name"],$uploaddir.$_FILES["binFile"]["name"])) 
{
	$filename = basename($binFile);
	$filetype= $_FILES['binFile']['type'];
	$filesize = $_FILES['binFile']['size'];

	if($filesize != 0)	
	{
		$current_id = $adb->getUniqueID("crmentity");
		$desc = $_REQUEST['txtDescription'];
		$description = addslashes($desc);
		$date_var = date('YmdHis');

		//$data = addslashes(fread(fopen($uploaddir.$binFile, "r"), $filesize));
		$filenameBase64 = $filename.".base64";
		$rfh = fopen($uploaddir.$filename, "r");
		$wfh = fopen($uploaddir.$filenameBase64, "w");
		//FIXME: find a way to stream data to base64_encode() to reduce memory usage -mikefedyk
		fwrite($wfh,base64_encode(fread($rfh, $filesize)));
		deleteFile($uploaddir,$filename);
		
		$query = "insert into crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime) values('";
		$query .= $current_id."','".$current_user->id."','".$current_user->id."','".$_REQUEST['return_module'].' Attachment'."','".$description."','".$date_var."')";
		$result = $adb->query($query);

		$sql = "insert into attachments values(";
		$sql .= $current_id.",'".$filename."','".$description."','".$filetype."','".$filesize."','".$adb->getEmptyBlob()."')";
		$result = $adb->query($sql);
	
		//FIXME: adodb reads entire file into memory instead of streaming to DB -mikefedyk
		if($result!=false)	    
			$result = $adb->updateBlobFile('attachments','attachmentcontents',"attachmentsid='".$current_id."' and name='".$filename."'",$uploaddir.$filenameBase64);
		deleteFile($uploaddir,$filenameBase64);

		$crmid = $_REQUEST['return_id'];

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
		deleteFile($uploaddir,$filename);
		include "upload.php";
	}			
} 
else 
{
	$errorCode =  $_FILES['binFile']['error'];
	
	if($errorCode == 4)
	{
	    include('themes/'.$theme.'/header.php');
	    $errormessage = "<B><font color='red'>Please give a valid file for upload.</font></B> <br>" ;
	    echo $errormessage;
	    include "upload.php";
	}
	else if($errorCode == 2)
	{
	    $errormessage = "<B><font color='red'>Sorry, the uploaded file exceeds the maximum filesize limit. Please try a file smaller than $upload_maxsize bytes</font></B> <br>";
	    include('themes/'.$theme.'/header.php');
	    echo $errormessage;
	    include "upload.php";
	    //echo $errorCode;
	}
	else if($errorCode == 1)
	{
	    $errormessage = "<B><font color='red'>Sorry, the uploaded file exceeds the upload_max_filesize directive in php.ini</font></B> <br>";
	    include('themes/'.$theme.'/header.php');
	    echo $errormessage;
	    include "upload.php";
	    //echo $errorCode;
	}
	else if($errorCode == 3)
	{
	    include('themes/'.$theme.'/header.php');
	    echo "<b><font color='red'>The uploaded file was only partially received.  Please try again.</font></b><br>";
	    include "upload.php";
	}
	else if($errorcode == '')
	{
	    include('themes/'.$theme.'/header.php');
	    echo "<b><font color='red'>Error while receiving file.  Check post_max_size in php.ini</font></b><br>";
	    include "upload.php";
	}
	  
}
	
function deleteFile($dir,$filename)
{
   unlink($dir.$filename);	
}
?>

