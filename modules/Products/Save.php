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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Products/Save.php,v 1.12 2006/02/07 07:27:23 jerrydgeorge Exp $
 * Description:  Saves an Account record and then redirects the browser to the 
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Products/Product.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
global $log;

$focus = new Product();
if(isset($_REQUEST['record']))
{
	$focus->id = $_REQUEST['record'];
	$record_id=$focus->id;
	$log->info("Record Id is present during Saving the product :->".$record_id);
}
if(isset($_REQUEST['mode']))
{
	$focus->mode = $_REQUEST['mode'];
  	$mode=$focus->mode;
	  $log->info("Type of 'mode' during Product Save is ".$mode);
}
foreach($focus->column_fields as $fieldname => $val)
{
	if(isset($_REQUEST[$fieldname]))
	{
		$value = $_REQUEST[$fieldname];
		$focus->column_fields[$fieldname] = $value;
	}
}

if($_REQUEST['imagelist'] != '')
{
	$del_images = array();
	$del_images = explode('###',$_REQUEST['imagelist']);
	$del_image_array = array_slice($del_images,0,count($del_images)-1);
}

//Checking If image is given or not 
$uploaddir = $root_directory."test/product/" ;//set this to which location you need to give the product image
$log->info("The Location to Save the Product Image is ".$uploaddir);
$image_lists=array();
$count=0;
foreach($_FILES as $files)
{
	$file_path_name = $files['name'];
	$image_error="false";
	$saveimage="true";
	$file_name = basename($file_path_name);
	//if the image is given
	if($file_name!="")
	{
		$log->debug("Product Image is given for uploading");
		$image_name_val=file_exist_fn($file_name,0);
		$image_lists[]=$image_name_val;
		$encode_field_values="";
		$errormessage="";

		$move_upload_status=move_uploaded_file($files["tmp_name"],$uploaddir.$image_name_val);
		$image_error="false";

		//if there is an error in the uploading of image

		$filetype= $files['type'];
		$filesize = $files['size'];

		$filetype_array=explode("/",$filetype);

		$file_type_val_image=strtolower($filetype_array[0]);
		$file_type_val=strtolower($filetype_array[1]);
		$log->info("The File type of the Product Image is :: ".$file_type_val);
		//checking the uploaded image is if an image type or not
		if(!$move_upload_status) //if any error during file uploading  
		{
			$log->debug("Error is present in uploading product Image.");	
			$errorCode =  $files['error'];
			if($errorCode == 4)
			{
				$errorcode="no-image";
				$saveimage="false";
				$image_error="true";
			}
			else if($errorCode == 2)
			{
				$errormessage = 2;
				$saveimage="false";
				$image_error="true";
			}
			else if($errorCode == 3 )
			{
				$errormessage = 3;
				$saveimage="false";
				$image_error="true";
			}
		}
		else 
		{
			$log->debug("Successfully uploaded the product Image.");
			if($filesize != 0)
			{
				if (($file_type_val == "jpeg" ) || ($file_type_val == "png") || ($file_type_val == "jpg" ) || ($file_type_val == "pjpeg" ) || ($file_type_val == "x-png") || ($file_type_val == "gif") ) //Checking whether the file is an image or not
				{
					$saveimage="true";
					$image_error="false";
				}
				else
				{
					$savelogo="false";
					$image_error="true";
					$errormessage = "image";
				}

			}
			else
			{	$savelogo="false";
				$image_error="true";
				$errormessage = "invalid";
			}

		}
	}
}

//added to retain the pictures from db
if($mode=="edit" && $image_error=="false" )
{
		$image_lists[]= getProductImageName($record_id,$del_image_array);
		$saveimage="true";
}

//end of code to retain the pictures from db
 //code added for returning back to the current view after edit from list view
if($_REQUEST['return_viewname'] == '') $return_viewname='0';
if($_REQUEST['return_viewname'] != '')$return_viewname=$_REQUEST['return_viewname'];

if($image_error=="true") //If there is any error in the file upload then moving all the data to EditView.
{
	//re diverting the page and reassigning the same values as image error occurs	
	if($_REQUEST['activity_mode'] != '')$activity_mode=$_REQUEST['activity_mode'];
	if($_REQUEST['return_module'] != '')$return_module=$_REQUEST['return_module'];
	if($_REQUEST['return_action'] != '')$return_action=$_REQUEST['return_action'];
	if($_REQUEST['return_id'] != '')$return_id=$_REQUEST['return_id'];

	 $log->debug("There is an error during the upload of product image.");
	$field_values_passed.="";
	foreach($focus->column_fields as $fieldname => $val)
	{
		if(isset($_REQUEST[$fieldname]))
		{
			 $log->debug("Assigning the previous values given for the product to respective fields ");
			$field_values_passed.="&";
			$value = $_REQUEST[$fieldname];
			$focus->column_fields[$fieldname] = $value;
			$field_values_passed.=$fieldname."=".$value;

		}
	}
	$values_pass=$field_values_passed;
	$encode_field_values=base64_encode($values_pass);

	$error_module = "Products";
	$error_action = "EditView";

	if(isset($_request['return_id']) && $_request['return_id'] != "")
		$return_id = $_request['return_id'];
	if(isset($_request['activity_mode']))
		$return_action .= '&activity_mode='.$_request['activity_mode'];

	if($mode=="edit")
	{
		$return_id=$_REQUEST['record'];
	}
	header("location: index.php?action=$error_action&module=$error_module&record=$return_id&return_id=$return_id&return_action=$return_action&return_module=$return_module&activity_mode=$activity_mode&return_viewname=$return_viewname&saveimage=$saveimage&error_msg=$errormessage&image_error=$image_error&encode_val=$encode_field_values");

}
if($saveimage=="true")
{
	$image_lists_db=implode("###",$image_lists);
	$focus->column_fields['imagename']=$image_lists_db;
	$log->debug("Assign the Image name to the field name ");
}
//Saving the product
if($image_error=="false")
{
	$focus->save("Products");
	$return_id = $focus->id;

	//Checking and Sending Mail from reorder level
	global $current_user;
	$productname = $focus->column_fields['productname'];
	$qty_stk = $focus->column_fields['qtyinstock'];
	$reord = $focus->column_fields['reorderlevel'];
	$handler = $focus->column_fields['assigned_user_id'];
	if($qty_stk != '' && $reord != '')
	{
		if($qty_stk < $reord)
		{
			$handler_name = getUserName($handler);
			$sender_name = getUserName($current_user->id);
			$to_address= getUserEmail($handler);
			$subject =  $productname.' Stock Level is Low';
			$body = 'Dear '.$handler_name.',

					The current stock of '.$productname.' in our warehouse is '.$qty_stk.'. Kindly procure required number of units as the stock level is below reorder level '.$reord.'.

					Severity: Major 
				Thanks,
				'.$sender_name; 

			include("modules/Emails/mail.php");
			$mail_status = send_mail("Products",$to_address,$current_user->user_name,$current_user->email1,$subject,$body);
		}
	}

	if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
	else $return_module = "Products";
	if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
	else $return_action = "DetailView";
	if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];
	if(isset($_REQUEST['activity_mode'])) $return_action .= '&activity_mode='.$_REQUEST['activity_mode'];

	if(isset($_REQUEST['parenttab']) && $_REQUEST['parenttab'] != "") $parenttab = $_REQUEST['parenttab'];
	header("Location: index.php?action=$return_action&module=$return_module&parenttab=$parenttab&record=$return_id&viewname=$return_viewname");
}



?>
