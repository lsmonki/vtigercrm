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

require_once('modules/Contacts/Contact.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');

$local_log =& LoggerManager::getLogger('index');

global $vtlog;
$focus = new Contact();
if(isset($_REQUEST['record']))
{
        $focus->id = $_REQUEST['record'];
}
if(isset($_REQUEST['mode']))
{
        $focus->mode = $_REQUEST['mode'];
}
if($_REQUEST['salutation'] == '--None--')	$_REQUEST['salutation'] = '';
/*
if (isset($_REQUEST['new_reports_to_id'])) {
	$focus->retrieve($_REQUEST['new_reports_to_id']);
	$focus->reports_to_id = $_REQUEST['record']; 
}
*/
//else {
//	$focus->retrieve($_REQUEST['record']);

foreach($focus->column_fields as $fieldname => $val)
{
	if(isset($_REQUEST[$fieldname]))
	{
		//$focus->$field = $_REQUEST[$field];
		$value = $_REQUEST[$fieldname];
		$focus->column_fields[$fieldname] = $value;
	}
}
/*	
	foreach($focus->additional_column_fields as $field)
	{
		if(isset($_REQUEST[$field]))
		{
			$value = $_REQUEST[$field];
			$focus->$field = $value;
			
		}
	}
*/
	if (!isset($_REQUEST['email_opt_out'])) $focus->email_opt_out = 'off';
	if (!isset($_REQUEST['do_not_call'])) $focus->do_not_call = 'off';
//}

//$focus->saveentity("Contacts");
$focus->save("Contacts");
$return_id = $focus->id;
//save_customfields($focus->id);

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Contacts";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

if(isset($_REQUEST['activity_mode']) && $_REQUEST['activity_mode'] != '') $activitymode = $_REQUEST['activity_mode'];

$local_log->debug("Saved record with id of ".$return_id);

//BEGIN -- Code for Create Customer Portal Users password and Send Mail 
if($_REQUEST['portal'] == '' && $_REQUEST['mode'] == 'edit')
{
	$sql = "update portalinfo set user_name='".$_REQUEST['email']."',isactive=0 where id=".$_REQUEST['record'];
	$adb->query($sql);
}
elseif($_REQUEST['portal'] != '' && $_REQUEST['email'] != '')// && $_REQUEST['mode'] != 'edit')
{
	$id = $_REQUEST['record'];
	$username = $_REQUEST['email'];

	if($_REQUEST['mode'] != 'edit')
		$insert = 'true';

	$sql = "select id,user_name,user_password,isactive from portalinfo";
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
				$sql = "update portalinfo set user_name='".$username."', isactive=1 where id=".$id;
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
		$sql = "insert into portalinfo (id,user_name,user_password,type,isactive) values(".$focus->id.",'".$username."','".$password."','C',1)";
                $adb->query($sql);
	}

	$subject = "Customer Portal Login Details";
	$contents = "Dear ".$_REQUEST['firstname'].' '.$_REQUEST['lastname'].',<br><br>';
	$contents .= 'Your Customer Portal Login details are given below:';
//	$contents .= '<br>Customer Portal URL:';
	$contents .= "<br><br>User Id : ".$_REQUEST['email'];
	$contents .= '<br>Password : '.$password;
	$contents .= "<br><br><a href='".$PORTAL_URL."/cp_index.php'>Please Login Here</a>";

	$contents .= '<br><br><b>Note : </b>We suggest you to change your password after logging in first time.';
	$contents .= '<br><br>Support Team';

	$vtlog->logthis("Customer Portal Information Updated in database and details are going to send => '".$_REQUEST['email']."'",'info');

	if($insert == 'true' || $update == 'true')
	{
		//Removed the function SendMailToCustomer and used the send_mail function to send mail to the customer
		require_once("modules/Emails/mail.php");
		$mail_status = send_mail('Contacts',$_REQUEST['email'],$current_user->user_name,'',$subject,$contents);
	}
	$vtlog->logthis("After return from the SendMailToCustomer function. Now control will go to the header.",'info');
}
function makeRandomPassword() 
{
        $salt = "abcdefghijklmnopqrstuvwxyz0123456789";
        srand((double)microtime()*1000000);
        $i = 0;
        while ($i <= 7)
	{
                $num = rand() % 33;
                $tmp = substr($salt, $num, 1);
                $pass = $pass . $tmp;
                $i++;
	}
      return $pass;
}
//END -- Code for Create Customer Portal Users password and Send Mail
$vtlog->logthis("This Page is redirected to : ".$return_module." / ".$return_action."& return id =".$return_id,'info');
//code added for returning back to the current view after edit from list view
if($_REQUEST['return_viewname'] == '') $return_viewname='0';
if($_REQUEST['return_viewname'] != '')$return_viewname=$_REQUEST['return_viewname'];
header("Location: index.php?action=$return_action&module=$return_module&record=$return_id&activity_mode=$activitymode&viewname=$return_viewname");
//Code to save the custom field info into database
function save_customfields($entity_id)
{
	global $adb;
	$dbquery="select * from customfields where module='Contacts'";
	$result = $adb->query($dbquery);
	$custquery = "select * from contactscf where contactid='".$entity_id."'";
        $cust_result = $adb->query($custquery);
	if($adb->num_rows($result) != 0)
	{
		
		$columns='';
		$values='';
		$update='';
		$noofrows = $adb->num_rows($result);
		for($i=0; $i<$noofrows; $i++)
		{
			$fldName=$adb->query_result($result,$i,"fieldlabel");
			$colName=$adb->query_result($result,$i,"column_name");
			if(isset($_REQUEST[$colName]))
			{
				$fldvalue=$_REQUEST[$colName];
				if(get_magic_quotes_gpc() == 1)
                		{
                        		$fldvalue = stripslashes($fldvalue);
                		}
			}
			else
			{
				$fldvalue = '';
			}
			if(isset($_REQUEST['record']) && $_REQUEST['record'] != '' && $adb->num_rows($cust_result) !=0)
			{
				//Update Block
				if($i == 0)
				{
					$update = $colName.'="'.$fldvalue.'"';
				}
				else
				{
					$update .= ', '.$colName.'="'.$fldvalue.'"';
				}
			}
			else
			{
				//Insert Block
				if($i == 0)
				{
					$columns='contactid, '.$colName;
					$values='"'.$entity_id.'", "'.$fldvalue.'"';
				}
				else
				{
					$columns .= ', '.$colName;
					$values .= ', "'.$fldvalue.'"';
				}
			}
			
				
		}
		if(isset($_REQUEST['record']) && $_REQUEST['record'] != '' && $adb->num_rows($cust_result) !=0)
		{
			//Update Block
			$query = 'update contactcf SET '.$update.' where contactid="'.$entity_id.'"'; 
			$adb->query($query);
		}
		else
		{
			//Insert Block
			$query = 'insert into contactcf ('.$columns.') values('.$values.')';
			$adb->query($query);
		}
		
	}
	/* srini patch
	else
	{
		if(isset($_REQUEST['record']) && $_REQUEST['record'] != '' && $adb->num_rows($cust_result) !=0)
		{
			//Update Block
		}
		else
		{
			//Insert Block
			$query = 'insert into contactcf ('.$columns.') values('.$values.')';
			$adb->query($query);
		}
	}*/
	
}
?>
