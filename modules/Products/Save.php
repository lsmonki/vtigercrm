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

require_once('modules/Products/Products.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
global $log,$current_user,$mod_strings;
$currencyid=fetchCurrency($current_user->id);
$rate_symbol = getCurrencySymbolandCRate($currencyid);
$rate = $rate_symbol['rate'];
$focus = new Products();
//added to fix 4600
$search=$_REQUEST['search_url'];

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
		if(is_array($_REQUEST[$fieldname]))
			$value = $_REQUEST[$fieldname];
		else
			$value = trim($_REQUEST[$fieldname]);
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
$image_lists=array();
$count=0;

$saveimage = "true";
$image_error = "false";
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
			 $log->debug("Assigning the previous values given for the product to respective vtiger_fields ");
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
	header("location: index.php?action=$error_action&module=$error_module&record=$return_id&return_id=$return_id&return_action=$return_action&return_module=$return_module&activity_mode=$activity_mode&return_viewname=$return_viewname&saveimage=$saveimage&error_msg=$errormessage&image_error=$image_error&encode_val=$encode_field_values.$search");
}
if($saveimage=="true")
{
	$image_lists_db=implode("###",$image_lists);
	$focus->column_fields['imagename']=$image_lists_db;
	$log->debug("Assign the Image name to the vtiger_field name ");
}

if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != '')
	$focus->parentid = $_REQUEST['return_id'];
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module']!='')
	$focus->return_module = $_REQUEST['return_module'];
if($_REQUEST['assigntype'] == 'U') {
	$focus->column_fields['assigned_user_id'] = $_REQUEST['assigned_user_id'];
} elseif($_REQUEST['assigntype'] == 'T') {
	$focus->column_fields['assigned_user_id'] = $_REQUEST['assigned_group_id'];
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
			$subject =  $productname.' '.$mod_strings['MSG_STOCK_LEVEL'];
			$body = $mod_strings['MSG_DEAR'].' '.$handler_name.',<br><br>'.

					$mod_strings['MSG_CURRENT_STOCK'].' '.$productname.' '.$mod_strings['MSG_IN_OUR_WAREHOUSE'].' '.$qty_stk.'. '.$mod_strings['MSG_PROCURE_REQUIRED_NUMBER'].' '.$reord.'.<br> '.

					$mod_strings['MSG_SEVERITY'].'<br><br> '.
				$mod_strings['MSG_THANKS'].'<br> '.
				$sender_name;

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
	header("Location: index.php?action=$return_action&module=$return_module&parenttab=$parenttab&record=$return_id&viewname=$return_viewname&start=".$_REQUEST['pagenumber'].$search);
}



?>
