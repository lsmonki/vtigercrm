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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Orders/Delete.php,v 1.9 2005/07/13 15:57:46 saraj Exp $
 * Description:  Deletes an Account record and then redirects the browser to the 
 * defined return URL.
 ********************************************************************************/

require_once('modules/Orders/Order.php');
require_once('modules/Orders/SalesOrder.php');
global $mod_strings;

require_once('include/logging.php');
$log = LoggerManager::getLogger('order_delete');

if($_REQUEST['module'] == 'Orders')
{
	$focus = new Order();

	if(!isset($_REQUEST['record']))
		die($mod_strings['ERR_DELETE_RECORD']);

	$sql_recentviewed ='delete from tracker where user_id = '.$current_user->id.' and item_id = '.$_REQUEST['record'];
	$adb->query($sql_recentviewed);
	if($_REQUEST['return_module'] == $_REQUEST['module'] || $_REQUEST['return_module'] == "Accounts")
	{
		$focus->mark_deleted($_REQUEST['record']);
	}
	elseif($_REQUEST['return_module'] == "Products")
	{
		if($_REQUEST['return_action'] == "VendorDetailView")
		{	
			$sql_req ='DELETE from purchaseorder where purchaseorderid= '.$_REQUEST['record'];
			$adb->query($sql_req);
		}
		else
		{	
			//$sql_req ='DELETE from poproductrel where purchaseorderid= '.$_REQUEST['record'].' and productid = '.$_REQUEST['return_id'];
			//Removing the relation from the po product rel
			$po_query = "select * from poproductrel where productid=".$_REQUEST['return_id'];
			$result = $adb->query($po_query);
			$num_rows = $adb->num_rows($result);
			for($i=0; $i< $num_rows; $i++)
			{
			        $po_id = $adb->query_result($result,$i,"purchaseorderid");
			        $qty = $adb->query_result($result,$i,"quantity");
			        $listprice = $adb->query_result($result,$i,"listprice");
			        $prod_total = $qty * $listprice;

			        //Get the current sub total from Quotes and update it with the new subtotal
			        updateSubTotal("Orders","purchaseorder","subtotal","total","purchaseorderid",$po_id,$prod_total);
			}
			//delete the relation from po product rel
			$del_query = "delete from poproductrel where productid=".$_REQUEST['return_id']." and purchaseorderid=".$_REQUEST['record'];
			$adb->query($del_query);

		}
	}
	elseif($_REQUEST['return_module'] == "Contacts")
	{	
		$sql_req ='UPDATE purchaseorder set contactid="" where purchaseorderid = '.$_REQUEST['record'];
		$adb->query($sql_req);
	}

}

header("Location: index.php?module=".$_REQUEST['return_module']."&action=".$_REQUEST['return_action']."&record=".$_REQUEST['return_id']);
?>
