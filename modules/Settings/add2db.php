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
$vtigerpath = str_replace("/index.php?module=Settings&action=add2db", "", $vtigerpath);
$uploaddir = $root_directory ."/test/logo/" ;// set this to wherever
$saveflag="true";

if(move_uploaded_file($_FILES["binFile"]["tmp_name"],$uploaddir.$_FILES["binFile"]["name"])) 
{
	$binFile = $_FILES['binFile']['name'];
	$filename = basename($binFile);
	$filetype= $_FILES['binFile']['type'];
	$filesize = $_FILES['binFile']['size'];


	if($filesize != 0)	
	{
		//$data = base64_encode(fread(fopen($uploaddir.$binFile, "r"), $filesize));
		if($result!=false)
		{
			$savelogo="true";
		}
	}
	else
	{
		include('themes/'.$theme.'/header.php');
		$errormessage = "<font color='red'><B>Error Message<ul>
			<li><font color='red'>Invalid file OR</font>
			<li><font color='red'>File has no data</font>
			</ul></B></font> <br>" ;
		//deleteFile($uploaddir,$filename);
	}			
} 
else 
{
	$errorCode =  $_FILES['binFile']['error'];
	if($errorCode == 4)
	{
	    $errormessage = "<B><font color='red'>Kindly give a valid file for upload!</font></B> <br>" ;
	}
	else if($errorCode == 2)
	{
	    $errormessage = "<B><font color='red'>Sorry, the uploaded file exceeds the maximum filesize limit. Please try a file smaller than 1000000 bytes</font></B> <br>";
	    $savelogo="false";	    	
	}
	else if($errorCode == 3)
	{
	    $errormessage = "<b>Problems in file upload. Please try again! </b><br>";
		$savelogo="false";
	}
	  
}
	

function deleteFile($dir,$filename)
{
   unlink($dir.$filename);	
}
if($saveflag=="true")
{
	$organization_name=$_REQUEST['organization_name'];
	$org_name=$_REQUEST['org_name'];
	$organization_address=$_REQUEST['organization_address'];
	$organization_city=$_REQUEST['organization_city'];
	$organization_state=$_REQUEST['organization_state'];
	$organization_code=$_REQUEST['organization_code'];
	$organization_country=$_REQUEST['organization_country'];
	$organization_phone=$_REQUEST['organization_phone'];
	$organization_fax=$_REQUEST['organization_fax'];
	$organization_website=$_REQUEST['organization_website'];
	$organization_logo=$_REQUEST['organization_logo'];
	$organization_logoname=$filename;
	if(!isset($organization_logoname))
		$organization_logoname="";

	$sql="select * from organizationdetails where organizationame = '".$org_name."'";
	$result = $adb->query($sql);
	$org_name = $adb->query_result($result,0,'organizationame');

	if($org_name=='')
	{
		$sql="insert into organizationdetails(organizationame,address,city,state,code,country,phone,fax,website,logoname) values( '".$organization_name ."','".$organization_address."','". $organization_city."','".$organization_state."','".$organization_code."','".$organization_country."','".$organization_phone."','".$organization_fax."','".$organization_website."','".$organization_logoname."')";
	}
	else
	{
		$sql="update organizationdetails set organizationame = '".$organization_name."', address = '".$organization_address."', city = '".$organization_city."', state = '".$organization_state."',  code = '".$organization_code."', country = '".$organization_country."' ,  phone = '".$organization_phone."' ,  fax = '".$organization_fax."',  website = '".$organization_website."', logoname = '". $organization_logoname ."' where organizationame = '".$org_name."'";
	}
	$adb->query($sql);
	if($savelogo=="true")
	{
	//	$result = $adb->updateBlob('organizationdetails','logo',"organizationame='".$organization_name."' and logoname='".$filename."'",$data);
		header("Location: index.php?module=Settings&action=OrganizationConfig");
	}
	elseif($savelogo=="false")
	{
	    include('themes/'.$theme.'/header.php');
	    echo $errormessage;	
		return;
	}
	header("Location: index.php?module=Settings&action=OrganizationConfig");
	

}
?>

