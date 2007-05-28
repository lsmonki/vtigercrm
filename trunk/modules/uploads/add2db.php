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
$log->debug("DGDEBUG In add2db.php");

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

			$query = "insert into vtiger_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime) values('";
			$query .= $current_id."','".$current_user->id."','".$current_user->id."','".$_REQUEST['return_module'].' Attachment'."','".$description."',".$date_var.")";	
			$result = $adb->query($query);

			# Added by DG 26 Oct 2005
			# Attachments added to contacts are also added to their accounts
			$log->debug("DGDEBUG Here's the test:");
			$log->debug("DGDEBUG return_module: ".$_REQUEST['return_module']);
			if ($_REQUEST['return_module'] == 'Contacts')
			{
				$log->debug("DGDEBUG Passed the test.");
				$crmid = $_REQUEST['return_id'];
				$query = 'select accountid from vtiger_contactdetails where contactid='.$crmid;
				$log->debug("DGDEBUG Running query: ".$query);
				$result = $adb->query($query);
				if($adb->num_rows($result) != 0)
				{
					$log->debug("DGDEBUG Returned a row");
					$associated_account = $adb->query_result($result,0,"accountid");
					# Now make sure that we haven't already got this attachment associated to this account
					# Hmmm... if this works, should we NOT upload the attachment again, and just set the relation for the contact too?
					$log->debug("DGDEBUG Associated Account: ".$associated_account);
					$query = "select name,attachmentsize from vtiger_attachments where name= '".$filename."'";
					$result = $adb->query($query);
					if($adb->num_rows($result) != 0)
					{
						$log->debug("DGDEBUG Matched a row");
						# Whoops! We matched the name. Is it the same size?
						$dg_size = $adb->query_result($result,0,"attachmentsize");
						$log->debug("DGDEBUG: These should be the same size: ".$dg_size." ".$filesize);
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
				$log->debug("DGDEBUG: inserting into vtiger_seattachmentsrel from add2db 2");
				$sql1 = "insert into vtiger_seattachmentsrel values('";
				$sql1 .= $associated_account."','".$current_id."')";
				$log->debug("DGDEBUG: Here's the query: ".$sql1);
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
			echo $errormessage;
			include "upload.php";
		}			
	} 
	else 
	{
		$errorCode =  $_FILES['binFile']['error'];

		if($errorCode == 4)
		{
			$errormessage = "<B><font color='red'>Kindly give a valid file for upload!</font></B> <br>" ;
			echo $errormessage;
			include "upload.php";
		}
		else if($errorCode == 2)
		{
			$errormessage = "<B><font color='red'>Sorry, the uploaded file exceeds the maximum filesize limit. Please try a file smaller than 1000000 bytes</font></B> <br>";
			echo $errormessage;
			include "upload.php";
			//echo $errorCode;
		}
		else if($errorCode == 3 || $errorcode == '')
		{
			echo "<b><font color='red'>Problems in file upload. Please try again!</font></b><br>";
			include "upload.php";
		}

	}

?>
