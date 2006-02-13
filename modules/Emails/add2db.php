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
//$vtigerpath = $_SERVER['REQUEST_URI'];
//$vtigerpath = str_replace("/index.php?module=uploads&action=add2db", "", $vtigerpath);
//$uploaddir = $root_directory .$vtigerpath ."/test/upload/" ;// set this to wherever
$uploaddir = $root_directory."/test/upload/" ;// set this to wherever
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
    $fileid = create_guid();
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
    elseif($_REQUEST['return_module'] == 'Opportunities')
    {
	    $parent_type = 'Opportunity';
    }
   elseif($_REQUEST['return_module'] == 'Cases')
    {
	    $parent_type = 'Case';
    }		
   elseif($_REQUEST['return_module'] == 'Emails')
    {
	    $parent_type = 'Emails';
    }		

    $parent_id = $_REQUEST['return_id'];	 			
//echo '<br>parent id is .............. '.$parent_id;

    $sql = "INSERT INTO email_attachments ";
    $sql .= "(date_entered,parent_type,parent_id,data, filename, filesize, filetype) ";
    $sql .= "VALUES (".$adb->formatString('email_attachments','date_entered',$date_entered).",'$parent_type','$parent_id','$data',";
    $sql .= "'$filename', '$filesize', '$filetype')";
//echo '<br>sql is ....................................          '.$sql;
    $result = $adb->query($sql);
//       mysql_close();
	
    $sql2 = "select max(id) from email_attachments";
	$result2 = $adb->query($sql2);
	$tempRow = $adb->fetch_array($result2);
	$attachmentid=$tempRow[0];
//echo '<br>attachment id is ' .$attachmentid;
//echo 'file name ..'.$filename;
       deleteFile($uploaddir,$filename);
       header("Location: index.php?action=EditView&module=$ret_module&record=$parent_id&filename=$filename&attachmentid=$attachmentid");	
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
/*
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
*/

function deleteFile($dir,$filename)
{
   unlink($dir.$filename);	
}
?>

