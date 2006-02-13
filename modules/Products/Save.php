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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Products/Save.php,v 1.7 2005/07/16 07:36:58 crouchingtiger Exp $
 * Description:  Saves an Account record and then redirects the browser to the 
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Products/Product.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');

$focus = new Product();
if(isset($_REQUEST['record']))
{
	$focus->id = $_REQUEST['record'];
}
if(isset($_REQUEST['mode']))
{
	$focus->mode = $_REQUEST['mode'];
}

foreach($focus->column_fields as $fieldname => $val)
{
	if(isset($_REQUEST[$fieldname]))
	{
		$value = $_REQUEST[$fieldname];
		$focus->column_fields[$fieldname] = $value;
	}
		
}

//$focus->saveentity("Products");
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
		SendMailToCustomer($to_address,$current_user->id,$subject,$body);	
			
	}	
	
}
function SendMailToCustomer($to,$current_user_id,$subject,$contents)
{
        global $vtlog;
        $vtlog->logthis("Inside SendMailToCustomer function.",'debug');
        require_once("modules/Emails/class.phpmailer.php");

        $mail = new PHPMailer();

        $mail->Subject = $subject;
        $mail->Body    = nl2br($contents);
        $mail->IsSMTP();

        if($current_user_id != '')
        {
                global $adb;
                $sql = "select * from users where id= ".$current_user_id;
                $result = $adb->query($sql);
                $from = $adb->query_result($result,0,'email1');
                $initialfrom = $adb->query_result($result,0,'user_name');
        }
        if($mail_server=='')
        {
                global $adb;
                $mailserverresult=$adb->query("select * from systems where server_type='email'");
                $mail_server=$adb->query_result($mailserverresult,0,'server');
                $_REQUEST['server']=$mail_server;
        }
        $mail->Host = $mail_server;
        $mail->SMTPAuth = true;
        $mail->Username = $mail_server_username;
        $mail->Password = $mail_server_password;
	$mail->From = $from;
        $mail->FromName = $initialfrom;

        $mail->AddAddress($to);
        $mail->AddReplyTo($from);
        $mail->WordWrap = 50;

        $mail->IsHTML(true);

        $mail->AltBody = "This is the body in plain text for non-HTML mail clients";

        if(!$mail->Send())
        {
                $errormsg = "Mail Could not be sent...";
        }
}


if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Products";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];
if(isset($_REQUEST['activity_mode'])) $return_action .= '&activity_mode='.$_REQUEST['activity_mode'];

header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");

?>
