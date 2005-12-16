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
  $binFile = $_FILES['binFile']['name'];
  $filename = basename($binFile);
  $filetype= $_FILES['binFile']['type'];

    $filesize = $_FILES['binFile']['size'];
    if($filesize != 0)	
    {
		$data = base64_encode(fread(fopen($uploaddir.$binFile, "r"), $filesize));
		//$data = addslashes(fread(fopen($uploaddir.$binFile, "r"), $filesize));
	   $textDesc = $_REQUEST['txtDescription'];	
		$strDescription = addslashes($textDesc);
	//    $fileid = create_guid();
		$date_entered = date('YmdHis');
		//Retreiving the return module and setting the parent type
		$ret_module = $_REQUEST['return_module'];
		$parent_type;		
		if($_REQUEST['return_module'] == 'Leads')
		{
			$parent_type = 'Lead';
		}
		elseif($_REQUEST['return_module'] == 'Accounts')
		{
			$parent_type = 'Account';
		}
		elseif($_REQUEST['return_module'] == 'Contacts')
		{
			$parent_type = 'Contact';
		}
		elseif($_REQUEST['return_module'] == 'Potentials')
		{
			$parent_type = 'Potential';
		}
	 
		$genQueryId = $adb->getUniqueID("wordtemplates");
		if($genQueryId != '')
		{
			$module = $_REQUEST['target_module'];
			$sql = "INSERT INTO wordtemplates ";
			$sql .= "(templateid,module,date_entered,parent_type,data,description,filename,filesize,filetype) ";
			$sql .= "VALUES (".$genQueryId.",'".$module."',".$adb->formatString('wordtemplates','date_entered',$date_entered).",'$parent_type',".$adb->getEmptyBlob().",'$strDescription',";
			$sql .= "'$filename', '$filesize', '$filetype')";

			$result = $adb->query($sql);
			if($result!=false)
			{
			   $result = $adb->updateBlob('wordtemplates','data'," filename='".$filename."'",$data);
			   deleteFile($uploaddir,$filename);
			   header("Location: index.php?action=listwordtemplates&module=Users");	
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
    $errormessage = "<B><font color='red'>Sorry, the uploaded file exceeds the maximum filesize limit. Please try a smaller file</font></B> <br>";
    include('themes/'.$theme.'/header.php');
    echo $errormessage;
    include "upload.php";
    //echo $errorCode;
  }
  else if($errorCode == 3)
  {
   include('themes/'.$theme.'/header.php');
    echo "Problems in file upload. Please try again! <br>";
    include "upload.php";
  }
  
}

function deleteFile($dir,$filename)
{
   unlink($dir.$filename);	
}
?>
