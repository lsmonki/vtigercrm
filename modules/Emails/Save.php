<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the 
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header:  vtiger_crm/sugarcrm/modules/Emails/Save.php,v 1.10 2004/12/23 13:59:37 jack Exp $
 * Description:  Saves an Account record and then redirects the browser to the 
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Emails/Email.php');
require_once('include/logging.php');
require_once('send_mail.php');
$local_log =& LoggerManager::getLogger('index');

$focus = new Email();
$focus->retrieve($_REQUEST['record']);
foreach($focus->column_fields as $field)
{
	if(isset($_REQUEST[$field]))
	{
		$value = $_REQUEST[$field];
		$focus->$field = $value;
		
	}
}

foreach($focus->additional_column_fields as $field)
{
	if(isset($_REQUEST[$field]))
	{
		$value = $_REQUEST[$field];
		$focus->$field = $value;
		
	}
}

$focus->save();

//this is to receive the data from the Select Users button
$_REQUEST['assigned_user_id']=$_REQUEST['user_id'];

//this will be the case if the Select Contact button is chosen
if($_REQUEST['assigned_user_id'] == null)
{
   $_REQUEST['assigned_user_id']=$_REQUEST['entity_id'];
}

//this is to receive the data from the Select Users button
if($_REQUEST['source_module'] == null)
{
	$module = 'users';
}
//this will be the case if the Select Contact button is chosen
else
{
	$module = $_REQUEST['source_module'];
}

//subject, contents

$_REQUEST['name'] = $focus->name;
$_REQUEST['description'] = $focus->description;

$return_id = $focus->id;


function deleteFile($dir,$filename)
{
   unlink($dir.$filename);	
}



if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Emails";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

$local_log->debug("Saved record with id of ".$return_id);

$uploaddir = $_SERVER['DOCUMENT_ROOT'] ."/test/upload/" ;// set this to wherever
if($_FILES["uploadfile"])
{
if(move_uploaded_file($_FILES["uploadfile"]["tmp_name"],$uploaddir.$_FILES["uploadfile"]["name"])) 
{
  $binFile = $_FILES['uploadfile']['name'];
  $filename = basename($binFile);
  $filetype= $_FILES['uploadfile']['type'];
    $filesize = $_FILES['uploadfile']['size'];
    if($filesize != 0)	
    {
    $data = base64_encode(fread(fopen($uploaddir.$binFile, "r"), $filesize));
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

    $sql = "INSERT INTO email_attachments ";
    $sql .= "(date_entered,parent_type,parent_id,data, filename, filesize, filetype) ";
    $sql .= "VALUES ('$date_entered','$parent_type','$parent_id','$data',";
    $sql .= "'$filename', '$filesize', '$filetype')";
    $result = mysql_query($sql);
     mysql_close();
     deleteFile($uploaddir,$filename);
     header("Location: index.php?action=DetailView&module=$ret_module&record=$parent_id");	
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
   //include "upload.php";
     }			
   
} 
else 
{
  $errorCode =  $_FILES['uploadfile']['error'];
  echo "Problems in file upload. Please try again! <br>" .$errorCode;
}
}

header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
?>
