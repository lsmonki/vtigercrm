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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Contacts/Save.php,v 1.9 2005/03/15 09:58:21 shaw Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Contacts/Contacts.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once("modules/Emails/mail.php");

$local_log =& LoggerManager::getLogger('index');

global $log,$adb;
$focus = new Contacts();

setObjectValuesFromRequest($focus);

if($_REQUEST['salutation'] == '--None--')	$_REQUEST['salutation'] = '';
if (!isset($_REQUEST['email_opt_out'])) $focus->email_opt_out = 'off';
if (!isset($_REQUEST['do_not_call'])) $focus->do_not_call = 'off';

//Checking If image is given or not
//$image_upload_array=SaveImage($_FILES,'contact',$focus->id,$focus->mode);
$image_name_val=$image_upload_array['imagename'];
$image_error="false";
$errormessage=$image_upload_array['errormessage'];
$saveimage=$image_upload_array['saveimage'];

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

        $log->debug("There is an error during the upload of contact image.");
        $field_values_passed.="";
        foreach($focus->column_fields as $fieldname => $val)
        {
                if(isset($_REQUEST[$fieldname]))
                {
			$log->debug("Assigning the previous values given for the contact to respective vtiger_fields ");
                        $field_values_passed.="&";
                        $value = $_REQUEST[$fieldname];
                        $focus->column_fields[$fieldname] = $value;
                        $field_values_passed.=$fieldname."=".$value;

                }
        }
        $values_pass=$field_values_passed;
        $encode_field_values=base64_encode($values_pass);

        $error_module = "Contacts";
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
        $focus->column_fields['imagename']=$image_name_val;
        $log->debug("Assign the Image name to the vtiger_field name ");
}

//if image added then we have to set that $_FILES['name'] in imagename field then only the image will be displayed
if($_FILES['imagename']['name'] != '')
{
	$focus->column_fields['imagename'] = $_FILES['imagename']['name'];
}
elseif($focus->id != '')
{
	$result = $adb->query("select imagename from vtiger_contactdetails where contactid = ".$focus->id);
	$focus->column_fields['imagename'] = $adb->query_result($result,0,'imagename');
}
	
//Saving the contact
if($image_error=="false")
{
	$focus->save("Contacts");
	$return_id = $focus->id;

	if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
	else $return_module = "Contacts";
	if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
	else $return_action = "DetailView";
	if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

	if(isset($_REQUEST['activity_mode']) && $_REQUEST['activity_mode'] != '') $activitymode = $_REQUEST['activity_mode'];

	$local_log->debug("Saved record with id of ".$return_id);

	if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] == "Campaigns")
	{
		if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "")
		{
			$sql = "insert into vtiger_campaigncontrel values (".$_REQUEST['return_id'].",".$focus->id.")";
			$adb->query($sql);
		}
	}

	//BEGIN -- Code for Create Customer Portal Users password and Send Mail 
	if($_REQUEST['portal'] == '' && $_REQUEST['mode'] == 'edit')
	{
		$sql = "update vtiger_portalinfo set user_name='".$_REQUEST['email']."',isactive=0 where id=".$_REQUEST['record'];
		$adb->query($sql);
	}
	elseif($_REQUEST['portal'] != '' && $_REQUEST['email'] != '')// && $_REQUEST['mode'] != 'edit')
	{
		$id = $_REQUEST['record'];
		$username = $_REQUEST['email'];

		if($_REQUEST['mode'] != 'edit')
			$insert = 'true';

		$sql = "select id,user_name,user_password,isactive from vtiger_portalinfo";
		$result = $adb->query($sql);

		for($i=0;$i<$adb->num_rows($result);$i++)
		{
			if($id == $adb->query_result($result,$i,'id'))
			{
				$dbusername = $adb->query_result($result,$i,'user_name');
				$isactive = $adb->query_result($result,$i,'isactive');

				if($username == $dbusername && $isactive == 1)
					$flag = 'true';
				else
				{
					$sql = "update vtiger_portalinfo set user_name='".$username."', isactive=1 where id=".$id;
					$adb->query($sql);
					$update = 'true';
					$flag = 'true';
					$password = $adb->query_result($result,$i,'user_password');
				}
			}
		}
		if($flag != 'true')
			$insert = 'true';
		else
			$insert = 'false';

		if($insert == 'true')
		{
			$password = makeRandomPassword();
			$sql = "insert into vtiger_portalinfo (id,user_name,user_password,type,isactive) values(".$focus->id.",'".$username."','".$password."','C',1)";
			$adb->query($sql);
		}

		//changes made to send mail to portal user when we use ajax edit
		$data_array = Array();
		$data_array['first_name'] = $_REQUEST['firstname'];
		$data_array['last_name'] = $_REQUEST['lastname'];
		$data_array['email'] = $_REQUEST['email'];
		$data_array['portal_url'] = "<a href=".$PORTAL_URL."/login.php>".$mod_strings['Please Login Here']."</a>";
		$contents = getmail_contents_portalUser($data_array,$password);

		$log->info("Customer Portal Information Updated in database and details are going to send => '".$_REQUEST['email']."'");
		if($insert == 'true' || $update == 'true')
		{
			$mail_status = send_mail('Contacts',$_REQUEST['email'],$current_user->user_name,'',$mod_strings['Customer Portal Login Details'],$contents);
		}
		$log->info("After return from the SendMailToCustomer function. Now control will go to the header.");
	}
	//END -- Code for Create Customer Portal Users password and Send Mail

	$log->info("This Page is redirected to : ".$return_module." / ".$return_action."& return id =".$return_id);

	//code added for returning back to the current view after edit from list view
	if($_REQUEST['return_viewname'] == '') $return_viewname='0';
	if($_REQUEST['return_viewname'] != '')$return_viewname=$_REQUEST['return_viewname'];

	if(isset($_REQUEST['parenttab']) && $_REQUEST['parenttab'] != "") $parenttab = $_REQUEST['parenttab'];

	//Send notification mail to the assigned to owner about the contact creation
	if($focus->column_fields['notify_owner'] == 1 || $focus->column_fields['notify_owner'] == 'on')
		$status = sendNotificationToOwner('Contacts',$focus);

	header("Location: index.php?action=$return_action&module=$return_module&parenttab=$parenttab&record=$return_id&activity_mode=$activitymode&viewname=$return_viewname");

}


?>
