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
require_once('include/logging.php');
global $log;
global $current_user;
$vtigerpath = $_SERVER['REQUEST_URI'];
$vtigerpath = str_replace("/index.php?module=uploads&action=add2db", "", $vtigerpath);

$crmid = $_REQUEST['return_id'];
$log->debug("DEBUG In add2db.php");

	//fix for space in file name.
	$_FILES['filename']['name'] = preg_replace('/\s+/', '_', $_FILES['filename']['name']);
	// Arbitrary File Upload Vulnerability fix - Philip
	$binFile = $_FILES['filename']['name'];

	$ext_pos = strrpos($binFile, ".");

	$ext = substr($binFile, $ext_pos + 1);

	if (in_array($ext, $upload_badext))
	{
		$binFile .= ".txt";
	}

	$_FILES["filename"]["name"] = $binFile;
	// Vulnerability fix ends

	//decide the file path where we should upload the file in the server
	$upload_filepath = decideFilePath();

	$current_id = $adb->getUniqueID("vtiger_crmentity");
	
	if(move_uploaded_file($_FILES["filename"]["tmp_name"],$upload_filepath.$current_id."_".$_FILES["filename"]["name"])) 
	{
		$filename = basename($binFile);
		$filetype= $_FILES['filename']['type'];
		$filesize = $_FILES['filename']['size'];

		if($filesize != 0)	
		{
			$desc = $_REQUEST['txtDescription'];
			$description = addslashes($desc);
			$date_var = $adb->formatDate(date('YmdHis'));	
			$current_date = getdate();
			$current_date = $adb->formatDate(date('YmdHis'));	
			$query = "insert into vtiger_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) values('";
			$query .= $current_id."','".$current_user->id."','".$current_user->id."','".$_REQUEST['return_module'].' Attachment'."','".$description."',".$date_var.",".$current_date.")";	
			$result = $adb->query($query);

			# Added by DG 26 Oct 2005
			# Attachments added to contacts are also added to their accounts
			$log->debug("DEBUG return_module: ".$_REQUEST['return_module']);
			if ($_REQUEST['return_module'] == 'Contacts')
			{
				$crmid = $_REQUEST['return_id'];
				$query = 'select accountid from vtiger_contactdetails where contactid='.$crmid;
				$result = $adb->query($query);
				if($adb->num_rows($result) != 0)
				{
					$log->debug("DEBUG Returned a row");
					$associated_account = $adb->query_result($result,0,"accountid");
					# Now make sure that we haven't already got this attachment associated to this account
					# Hmmm... if this works, should we NOT upload the attachment again, and just set the relation for the contact too?
					$log->debug("DEBUG Associated Account: ".$associated_account);
					$query = "select name,attachmentsize from vtiger_attachments where name= '".$filename."'";
					$result = $adb->query($query);
					if($adb->num_rows($result) != 0)
					{
						$log->debug("DEBUG Matched a row");
						# Whoops! We matched the name. Is it the same size?
						$dg_size = $adb->query_result($result,0,"attachmentsize");
						$log->debug("DEBUG: These should be the same size: ".$dg_size." ".$filesize);
						if ($dg_size == $filesize)
						{
							# Yup, it is probably the same file
							$associated_account = '';
						}
					}
				}
				else
				{
					$associated_account = '';
				}
			}
			# DG 19 June 2006
			# Strip out single quotes from filenames
			$filename = preg_replace('/\'/', '', $filename);
			$sql = "insert into vtiger_attachments values(";
			$sql .= $current_id.",'".$filename."','".$description."','".$filetype."','".$upload_filepath."')";
			$result = $adb->query($sql);


			$sql1 = "insert into vtiger_seattachmentsrel values('";
			$sql1 .= $crmid."','".$current_id."')";
			$result = $adb->query($sql1);

			# Added by DG 26 Oct 2005
			# Attachments added to contacts are also added to their accounts
			if ($associated_account)
			{
				$log->debug("DEBUG: inserting into vtiger_seattachmentsrel from add2db 2");
				$sql1 = "insert into vtiger_seattachmentsrel values('";
				$sql1 .= $associated_account."','".$current_id."')";
				$result = $adb->query($sql1);
			}

			echo '<script>window.opener.location.href = window.opener.location.href;self.close();</script>';
		}
		else
		{
			$errormessage = "<font color='red'><B>Error Message<ul>
				<li><font color='red'>Invalid file OR</font>
				<li><font color='red'>File has no data</font>
				</ul></B></font> <br>" ;
			header("Location: index.php?module=uploads&action=uploadsAjax&msg=true&file=upload&errormessage=".$errormessage);
		}			
	} 
	else 
	{
		$errorCode =  $_FILES['binFile']['error'];
		$errormessage = "";

		if($errorCode == 4)
		{
			$errormessage = "<B><font color='red'>Kindly give a valid file for upload!</font></B> <br>" ;
		}
		else if($errorCode == 2)
		{
			$errormessage = "<B><font color='red'>Sorry, the uploaded file exceeds the maximum filesize limit. Please try a file smaller than $upload_maxsize bytes</font></B> <br>";
		}
		else if($errorCode == 6)
		{
			$errormessage = "<B>Please configure <font color='red'>upload_tmp_dir</font> variable in php.ini file.</B> <br>" ;
		}
		else if($errorCode == 3 || $errorcode == '')
		{
			$errormessage = "<b><font color='red'>Problems in file upload. Please try again!</font></b><br>";
		}

		if($errormessage != '')
		{
			echo $errormessage;
			include("upload.php");
		}
	}

?>
