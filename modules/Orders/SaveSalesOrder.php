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
 * $Header$
 * Description:  Saves an Account record and then redirects the browser to the 
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Orders/SalesOrder.php');
require_once('include/logging.php');
//require_once('database/DatabaseConnection.php');
require_once('include/database/PearDatabase.php');

$local_log =& LoggerManager::getLogger('index');

$focus = new SalesOrder();

if(isset($_REQUEST['record']))
{
	$focus->id = $_REQUEST['record'];
}
if(isset($_REQUEST['mode']))
{
	$focus->mode = $_REQUEST['mode'];
}

//$focus->retrieve($_REQUEST['record']);

foreach($focus->column_fields as $fieldname => $val)
{
	if(isset($_REQUEST[$fieldname]))
	{
		$value = $_REQUEST[$fieldname];
		//echo '<BR>';
		//echo $fieldname."         ".$value;
		//echo '<BR>';
		$focus->column_fields[$fieldname] = $value;
	}
		
}


$focus->save("SalesOrder");
//Checking if quote_id is present and updating the quote status
if($focus->column_fields["quote_id"] != '')
{
        $qt_id = $focus->column_fields["quote_id"];
        $query1 = "update quotes set quotestage='Accepted' where quoteid=".$qt_id;
        $adb->query($query1);
}


$ext_prod_arr = Array();
if($focus->mode == 'edit')
{
	$query2  = "select * from soproductrel where salesorderid=".$focus->id;
        $result2 = $adb->query($query2);
        $num_rows = $adb->num_rows($result2);
        for($i=0; $i<$num_rows;$i++)
        {
                $pro_id = $adb->query_result($result2,$i,"productid");
                $pro_qty = $adb->query_result($result2,$i,"quantity");
                $ext_prod_arr[$pro_id] = $pro_qty;
        }

	
        $query1 = "delete from soproductrel where salesorderid=".$focus->id;
        //echo $query1;
        $adb->query($query1);

}
//Printing the total Number of rows
$tot_no_prod = $_REQUEST['totalProductCount'];
for($i=1; $i<=$tot_no_prod; $i++)
{
        $product_id_var = 'hdnProductId'.$i;
        $status_var = 'hdnRowStatus'.$i;
        $qty_var = 'txtQty'.$i;
        $list_price_var = 'txtListPrice'.$i;

        $prod_id = $_REQUEST[$product_id_var];
        $prod_status = $_REQUEST[$status_var];
        $qty = $_REQUEST[$qty_var];
        $listprice = $_REQUEST[$list_price_var];
        if($prod_status != 'D')
        {

                $query ="insert into soproductrel values(".$focus->id.",".$prod_id.",".$qty.",".$listprice.")";
                $adb->query($query);
		updateStk($prod_id,$qty,$focus->mode,$ext_prod_arr);
        }
}
$return_id = $focus->id;

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Orders";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "SalesOrderDetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

$local_log->debug("Saved record with id of ".$return_id);

function updateStk($product_id,$qty,$mode,$ext_prod_arr)
{
	global $adb;
	global $current_user;
	$prod_name = getProductName($product_id);
	$qtyinstk= getPrdQtyInStck($product_id);
	
	if($mode == 'edit')
        {
                if(array_key_exists($product_id,$ext_prod_arr))
                {
                        $old_qty = $ext_prod_arr[$product_id];
                        if($old_qty > $qty)
                        {

                                $diff_qty = $old_qty - $qty;
                                $upd_qty = $qtyinstk+$diff_qty;
                                //Updating the Product Quantity
                                 //updateProductQty($product_id, $upd_qty);
                                 sendPrdStckMail($product_id,$upd_qty,$prod_name,$qtyinstk,$qty);

                        }
                        elseif($old_qty < $qty)
                        {
                                $diff_qty = $qty - $old_qty;
                                $upd_qty = $qtyinstk-$diff_qty;
                                //updateProductQty($product_id, $upd_qty);
                                sendPrdStckMail($product_id,$upd_qty,$prod_name,$qtyinstk,$qty);


                        }
                }
                else
                {
                        $upd_qty = $qtyinstk-$qty;
                        //updateProductQty($product_id, $upd_qty);
                        sendPrdStckMail($product_id,$upd_qty,$prod_name,$qtyinstk,$qty);

                }
        }
	else
        {

                        $upd_qty = $qtyinstk-$qty;
                        //updateProductQty($product_id, $upd_qty);
                        sendPrdStckMail($product_id,$upd_qty,$prod_name,$qtyinstk,$qty);
        }
	
}


function sendPrdStckMail($product_id,$upd_qty,$prod_name,$qtyinstk,$qty)
{
        global $current_user;
        global $adb;
	$reorderlevel = getPrdReOrderLevel($product_id);
	if($upd_qty < $reorderlevel)
	{
		
		//send mail to the handler
		$handler=getPrdHandler($product_id);
		$handler_name = getUserName($handler);
		$to_address= getUserEmail($handler);
		//Get the email details from database;
		$query = "select * from inventorynotification where notificationname='SalesOrderNotification'";
		$result = $adb->query($query);

		$subject = $adb->query_result($result,0,'notificationsubject');
		$body = $adb->query_result($result,0,'notificationbody');

		$subject = str_replace('{PRODUCTNAME}',$prod_name,$subject);
		$body = str_replace('{HANDLER}',$handler_name,$body);	
		$body = str_replace('{PRODUCTNAME}',$prod_name,$body);	
		$body = str_replace('{CURRENTSTOCK}',$qtyinstk,$body);	
		$body = str_replace('{SOQUANTITY}',$qty,$body);	
		$body = str_replace('{CURRENTUSER}',$current_user->user_name,$body);	

		SendMailToCustomer($to_address,$current_user->id,$subject,$body);
		
	}
}

function getPrdQtyInStck($product_id)
{
	global $adb;
	$query1 = "select qtyinstock from products where productid=".$product_id;
	$result=$adb->query($query1);
	$qtyinstck= $adb->query_result($result,0,"qtyinstock");
	return $qtyinstck;
	
	
}
function getPrdReOrderLevel($product_id)
{
	global $adb;
	$query1 = "select reorderlevel from products where productid=".$product_id;
	$result=$adb->query($query1);
	$reorderlevel= $adb->query_result($result,0,"reorderlevel");
	return $reorderlevel;
	
}
function getPrdHandler($product_id)
{
	global $adb;
	$query1 = "select handler from products where productid=".$product_id;
	$result=$adb->query($query1);
	$handler= $adb->query_result($result,0,"handler");
	return $handler;
	
}

function SendMailToCustomer($to,$current_user_id,$subject,$contents)
{
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
 //code added for returning back to the current view after edit from list view
 if($_REQUEST['return_viewname'] == '') $return_viewname='0';
 if($_REQUEST['return_viewname'] != '')$return_viewname=$_REQUEST['return_viewname'];
header("Location: index.php?action=$return_action&module=$return_module&record=$return_id&viewname=$return_viewname&smodule=SO");
?>
