<?php
/*********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*
********************************************************************************/
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('modules/Documents/Documents.php');

global $adb;
global $current_user;


if(isset($_REQUEST['filemode']) && $_REQUEST['filemode'] == 'AddFile')
{
	global $current_user;
	global $log;
	global $adb;
	$log->debug("Entering into AddFile mode...");
	$file_saved = false;
	$errMsg = '';
	$file_size=ini_get('upload_max_filesize');
	$folderid=$_REQUEST['folderid'];
	if($_REQUEST['filelocationtype'] == 'I')
	{
		$log->debug("Reaches here when dldtype = 'I' ...");
		if($_FILES['filelocation']['name'] != '')
		{
			$log->debug("Entering when file name is not empty...");
			$errCode=$_FILES['filelocation']['error'];
			if($errCode == 4)
			{
				
				$log->debug("Reaches here when errCode($errCode) == 4...");
				$errMsg = "No file specified or an invalid file. Please try again.";
				$file_saved = false;
			}
			else if($errCode == 2 || $errCode == 1)
			{
				$log->debug("Reaches here when errCode($errCode) == 2 or 1...");
				$errMsg = "File size exceeds the maximum limit.Please try uploading file less than".$file_size.".";
				$file_saved = false;
				}
			else if($errCode == 0)
			{
				$log->debug("Reaches here when errCode($errCode)=0...");
				foreach($_FILES as $fileindex => $files)
				{
					if($files['name'] != '' && $files['size'] > 0)
					{
						$return_value = uploadAndSaveDownloadFiles($files,$notesid);
						if($return_value == 'IS_NOT_UPLOAD')
						{
							$errMsg = "Error while getting the file from the tmp directory.";
							$file_saved = false;
						}
						elseif($return_value == 'DB_FAIL')
						{
							$errMsg = "Error while inserting values into DB.";
							$file_saved = false;
						}
						elseif($return_value == 'NOT_SAVED')
						{
							$errMsg = "Error while saving the file to local directory.";
							$file_saved = false;
						}
						elseif($return_value == 'OK')
						{
							$file_saved = true;
						}
					}
				}
			}
			else
			{
				$log->debug("Reaches here for unknown errCode...");
				$errMsg = "Unable to upload file! Please try it again.";
				$file_saved = false;
			}
		}
		else
		{
			$log->debug("Reaches here when file name is empty...");
		?>
			<script language="javascript">
				alert('Unable to upload file. Please add a valid file');
                parent.hide('fileLay');
                parent.reloadFrame();
	        </script>
		<?php }
	}
	elseif($_REQUEST['filelocationtype'] == 'E')
	{
		if(!isset($_REQUEST['crm_id']) || $_REQUEST['crm_id'] == 0)
		{
			$focus = new Documents();
			if(!isset($_REQUEST['assigned_user_id'])){
				$focus->column_fields['assigned_user_id'] = 1;
			}
			$focus->insertIntoCrmEntity('Documents');
			$notesid = $adb->getUniqueID("vtiger_crmentity") - 1;
		}
		else
		{
			$notesid = $_REQUEST['crm_id'];
		}
		$log->debug("Reaches here when download type = 'E'...");
		$filename=$_REQUEST['external_filename'];
		$dldtype=$_REQUEST['filelocationtype'];
		$filetype='';
		$filesize='';
		$dldcnt=0;
		$upload_file_path= $_REQUEST['filelocation'];
		$filetime=date('Y-m-d H:i:s');
		$createdby=$current_user->id;
		$lastmodifiedby=$current_user->id;
		$status=$_REQUEST['status'];
		$arc=$_REQUEST['arc'];
		if($arc == 'PD')
			$os = $_REQUEST['os'];
		else
			$os='';
		$version=utf8RawUrlDecode($_REQUEST['version']);
		if(!isset($_REQUEST['crm_id']) || $_REQUEST['crm_id'] == 0)
		{
			$focus->mode='edit';
			$sql="insert into vtiger_notes (notesid,folderid,filename,filelocationtype,filetype,filesize,filedownloadcount,filepath,filestatus,fileversion) values(?,?,?,?,?,?,?,?,?,?) ";
			$res=$adb->pquery($sql,array($notesid,$folderid,$filename,$dldtype,$filetype,$filesize,$dldcnt,$upload_file_path,$status,$version));
		}		
		/*
		$sql="insert into vtiger_notes(folderid,filename,dldtype,description,filetype,filesize,dldcnt,path,createdtime,modifiedtime,createdby,lastmodifiedby,status,architecture,version,os) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$res=$adb->pquery($sql,array($folderid,$filename,$dldtype,$filedesc,$filetype,$filesize,$dldcnt,$upload_file_path,$filetime,$filetime,$createdby,$lastmodifiedby,$status,$arc,$version,$os));
		*/
		else
		{
			$sql="update vtiger_notes set folderid = ?,filename= ?,filelocationtype= ?,filetype= ?,filesize= ?,filedownloadcount= ?,filepath= ?,filestatus= ?,fileversion= ? where notesid= ?";
			$res=$adb->pquery($sql,array($folderid,$filename,$dldtype,$filetype,$filesize,$dldcnt,$upload_file_path,$status,$version,$notesid));
		}
		
		if(!$res)
		{
			$errMsg = "Error while inserting values into DB.";
			$file_saved = false;
		}
		if($res)
		{
			?>
				<script language="javascript">
					parent.$('fileid').value="<?php echo $notesid; ?>";
					parent.$('fileaddbutton').innerHTML="";
				</script>
		<?php
			$external_file_saved = true;
		}
	}
	else	
	{
		$log->debug("Reaches here when dldtype is neither I or E...");
		$errMsg = "Unable to upload file! File size exceeds ".$file_size." size.";
		$file_saved = false;
	}
	$log->debug("Reaches here after saving file to DB and file_saved=$file_saved...");
	if($file_saved)
	{
		$log->debug("Reaches here when return_action == 'ListView'...");
		if($_REQUEST['return_action'] == 'ListView')
		{ ?>
		<script language="javascript">
			parent.loadFolderContents(<?php echo $folderid;?>);
		</script>
		<?php }
		else
		{
			$log->debug("Reaches here when return_action not equal to 'ListView'...");
		?>
			<script language="javascript">
				parent.$('filename').value="<?php echo $files['name']; ?>";
			</script>
		<?php }
	}
	elseif($external_file_saved)
	{
		?>
		<script language="javascript">
			parent.$('filename').value="<?php echo $filename; ?>";
		</script> <?php
	}
		else 
		{
			if($errMsg == '')
				$errMsg = "Unable to upload file! Please try it again.";
		?>	
			<script language="javascript">
				alert('<?php echo $errMsg;?>');
				parent.hide('fileLay');
				parent.reloadFrame();
			</script>
		<?php
		}
	}

if(isset($_REQUEST['act']) && $_REQUEST['act'] == 'updateDldCnt')
{
	global $adb;
	$file_id=$_REQUEST['file_id'];
	$sql = "select filedownloadcount from vtiger_notes where notesid= ?";
	$download_count = $adb->query_result($adb->pquery($sql,array($file_id)),0,'filedownloadcount') + 1;
	$sql="update vtiger_notes set filedownloadcount= ? where notesid= ?";
	$res=$adb->pquery($sql,array($download_count,$file_id));	
	
	/*$query="select max(downloadid) from vtiger_dldhistory";
	$downloadid=$adb->query_result($adb->query($query),0,'max(downloadid)')+1;
	$usip=$_SERVER['REMOTE_ADDR'];
	$date1=date('Y-m-d H:i:s');
	$sqldldhis="insert into vtiger_dldhistory (downloadid,dldfileid,userid,ipaddress,dateTime) values(".$downloadid.",".$fileid.",".$current_user->id.",'".$usip."','".date('Y-m-d H:i:s')."')";
	$res=$adb->pquery($sqldldhis,array($downloadid,$fileid,$current_user->id,$usip,$date1));*/
}

if(isset($_REQUEST['act']) && $_REQUEST['act'] == 'checkFileIntegrityDetailView')
{	
		$dbQuery = "SELECT * FROM vtiger_notes where notesid= ?";
		$result = $adb->pquery($dbQuery,array($_REQUEST['file_id']));	
		$activeToinactive_count = 0;
		$file_status = @$adb->query_result($result,0,"filestatus");
		$download_type = @$adb->query_result($result,0,"filelocationtype");
		$fileid = @$adb->query_result($result,0,"notesid");
		$folderid = @$adb->query_result($result,0,"folderid");
		$name = @$adb->query_result($result,0,"filename");
		$filepath = @$adb->query_result($result,0,"filepath");
		if($download_type == 'I')
			$saved_filename = $fileid."_".$folderid."_".$name;
		else
			$saved_filename = '';
		if(!fopen($filepath.$saved_filename, "r"))
		{
			$activeToinactive_count = 1;			
			if($file_status == 1)
			{
				$dbQuery1 = "update vtiger_notes set filestatus=0 where notesid= ?";
				$result1 = $adb->pquery($dbQuery1,array($fileid));
				echo "lost_integrity";
			}
			else 
				echo "file_not_available";	
		}
		if($activeToinactive_count == 0)
			echo 'not_this_file';
}


function uploadAndSaveDownloadFiles($file_details,$notesid)
{
	
	global $adb, $current_user;
	global $upload_badext;
	global $log;
	if(!isset($_REQUEST['crm_id']) || $_REQUEST['crm_id'] == 0)
	{
		$focus = new Documents();
		if(!isset($_REQUEST['assigned_user_id'])){
		$focus->column_fields['assigned_user_id'] = 1;
		}
		$focus->insertIntoCrmEntity('Documents');
		$notesid = $adb->getUniqueID("vtiger_crmentity") - 1;
	}
	else
	{
		$notesid = $_REQUEST['crm_id'];
	}
	global $log;
	$log->debug("Entering into uploadAndSaveDownloadFiles($file_details,$fileid) method.");
	global $adb, $current_user;
	global $upload_badext;
	$date_var = date('YmdHis');
	$createdby = $current_user->id;
	$lastmodifiedby = $current_user->id;
	$file = $file_details['name'];
	$binFile = preg_replace('/\s+/', '_', $file);
	$ext_pos = strrpos($binFile, ".");
	$ext = substr($binFile, $ext_pos + 1);
	if (in_array($ext, $upload_badext))
	{
		$binFile .= ".txt";
	}
	$folderid=$_REQUEST['folderid'];
	$filename = ltrim(basename(" ".$binFile)); //allowed filename like UTF-8 characters
	$filetype= $file_details['type'];
	$filesize = $file_details['size'];
	$filetmp_name = $file_details['tmp_name'];
	$dldtype=$_REQUEST['filelocationtype'];
	$dldcnt=0;
	$filetime=date('Y-m-d H:i:s');
	$status=$_REQUEST['status'];
	$arc=$_REQUEST['arc'];
	if($arc == 'PD')
		$os = $_REQUEST['os'];
	else
		$os='';
	$version=utf8RawUrlDecode($_REQUEST['version']);
	$upload_filepath = decidePath();
	$upload_file_path = $upload_filepath.$notesid."_".$folderid."_".$binFile;
	if(!is_uploaded_file($filetmp_name))
	{
		return 'IS_NOT_UPLOAD';
	}
	else
		$upload_status = move_uploaded_file($filetmp_name,$upload_file_path);
	$save_file = 'true';
	$log->debug("Reaches here after uploading file to tmp directory and upload_status = $upload_status...");
	if($save_file == "true" && $upload_status == "true")
	{
		/*
		$sql="insert into vtiger_dldfiles(dldfileid,folderid,packagename,filename,dldtype,description,filetype,filesize,dldcnt,path,createdtime,modifiedtime,createdby,lastmodifiedby,status,architecture,version,os) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$res = $adb->pquery($sql,array($fileid,$folderid,$packagename,$filename,$dldtype,$filedesc,$filetype,$filesize,$dldcnt,$upload_filepath,$filetime,$filetime,$createdby,$lastmodifiedby,$status,$arc,$version,$os));
		*/
		$log->debug("File saved and uploaded");
		if(!isset($_REQUEST['crm_id']) || $_REQUEST['crm_id'] == 0)
		{
			$focus->mode = 'edit';
			$sql="insert into vtiger_notes (notesid,folderid,filename,filelocationtype,filetype,filesize,filedownloadcount,filepath,filestatus,fileversion) values(?,?,?,?,?,?,?,?,?,?) ";
			$res=$adb->pquery($sql,array($notesid,$folderid,$filename,$dldtype,$filetype,$filesize,$dldcnt,$upload_filepath,$status,$version));
		}
		else
		{		
			delete_old_file($notesid);
			$sql="update vtiger_notes set folderid = ?,filename= ?,filelocationtype= ?,filetype= ?,filesize= ?,filedownloadcount= ?,filepath= ?,filestatus= ?,fileversion= ? where notesid= ?";
			$res=$adb->pquery($sql,array($folderid,$filename,$dldtype,$filetype,$filesize,$dldcnt,$upload_filepath,$status,$version,$notesid));
		}	
		if(!$res)
		{
			return 'DB_FAIL';
		}
		else
		{ ?>
				<script language="javascript">
					parent.$('fileid').value="<?php echo $notesid; ?>";
					parent.$('fileaddbutton').innerHTML="";
				</script>
		<?php
			return 'OK';
		}
			
	}
	else
	{
		$log->debug("Skip the save file_downloads process.");
		return 'NOT_SAVED';

	}
}
function decidePath()
{
	global $log, $adb;
	$log->debug("Entering into decidePath() method ...");

	$filepath = 'storage/attachments';

	if(!is_dir($filepath))
	{
		mkdir($filepath);
	}
	$fullFilepath = $filepath."/";
	$log->debug("filepath=\"$filepath\"");
	$log->debug("Exiting from decidePath() method ...");
	return $fullFilepath;
}

function delete_old_file($fileid)
{
			global $log,$adb,$root_directory;
			$filelocationqry = "select concat(filepath,notesid,'_',folderid,'_',filename) as filepath from vtiger_notes where notesid = ?";
			$result = $adb->pquery($filelocationqry,array($fileid));
			$noofrows = $adb->num_rows($result);

			$filelocation = $adb->query_result($result,0,'filepath');
			$filelocation = $root_directory.$filelocation;
			$log->debug('In delete_old_file function. The file to be deleted is '.$filelocation);
			@unlink($filelocation);
}
?>
