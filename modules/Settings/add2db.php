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


$uploaddir = $root_directory ."/test/logo/" ;// set this to wherever
$saveflag="true";
$nologo_specified="true";
if(move_uploaded_file($_FILES["binFile"]["tmp_name"],$uploaddir.$_FILES["binFile"]["name"])) 
{
	$nologo_specified="false";
	$binFile = $_FILES['binFile']['name'];
	$filename = basename($binFile);
	$filetype= $_FILES['binFile']['type'];
	$filesize = $_FILES['binFile']['size'];
	
	$filetype_array=explode("/",$filetype); 

	$file_type_val=strtolower($filetype_array[1]);

	if($filesize != 0)
	{
		if (($file_type_val == "jpeg" ) || ($file_type_val == "png") || ($file_type_val == "jpg" ) ||  ($file_type_val == "pjpeg" ) || ($file_type_val == "x-png") ) //Checking whether the file is an image or not
		{
			if(stristr($binFile, '.gif') != FALSE)
			{
				$savelogo="false";
				$errormessage = "<font color='red'><B> Logo has to be an Image of type jpeg/png</B></font>";
			}		
			else if($result!=false)
			{
				$savelogo="true";
			}
		}
		else
		{
			$savelogo="false";
			$errormessage = "<font color='red'><B> Logo has to be an Image of type jpeg/png</B></font>";
		}
		
	}
	else
	{
		$savelogo="false";
		$errormessage = "<font color='red'><B>Error Message<ul>
		<li><font color='red'>Invalid file OR</font>
		<li><font color='red'>File has no data</font>
		</ul></B></font> <br>" ;
		deleteFile($uploaddir,$filename);
	}

} 
else 
{

	$errorCode =  $_FILES['binFile']['error'];
	if($errorCode == 4)
	{
	    	$savelogo="false";	    	
		$errorcode="";
		$saveflag="true";
		$nologo_specified="true";
	}
	else if($errorCode == 2)
	{
	    	$errormessage = "<B><font color='red'>Sorry, the uploaded file exceeds the maximum filesize limit. Please try a file smaller than 800000 bytes</font></B> <br>";
	    	$savelogo="false";	    	
	$nologo_specified="false";
	}
	else if($errorCode == 3 )
	{
		$errormessage = "<b>Problems in file upload. Please try again! </b><br>";
	  	$savelogo="false";
	$nologo_specified="false";
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
	$org_logo = $adb->query_result($result,0,'logoname'); 


	if($org_name=='')
	{
		$sql="insert into organizationdetails(organizationame,address,city,state,code,country,phone,fax,website,logoname) values( '".$organization_name ."','".$organization_address."','". $organization_city."','".$organization_state."','".$organization_code."','".$organization_country."','".$organization_phone."','".$organization_fax."','".$organization_website."','".$organization_logoname."')";
	}
	else
	{
		if($savelogo=="false")
		{
			$organization_logoname="";
		}
		if($nologo_specified=="true")
		{
			$savelogo="true";
			$organization_logoname=$org_logo;
		}

		$sql="update organizationdetails set organizationame = '".$organization_name."', address = '".$organization_address."', city = '".$organization_city."', state = '".$organization_state."',  code = '".$organization_code."', country = '".$organization_country."' ,  phone = '".$organization_phone."' ,  fax = '".$organization_fax."',  website = '".$organization_website."', logoname = '". $organization_logoname ."' where organizationame = '".$org_name."'";
	}
	$adb->query($sql);

	if($savelogo=="true")
	{
		header("Location: index.php?module=Settings&action=OrganizationConfig");
	}
	elseif($savelogo=="false")
	{

	    include('themes/'.$theme.'/header.php');
	    echo $errormessage;	
		return;
	}
	

}
?>

