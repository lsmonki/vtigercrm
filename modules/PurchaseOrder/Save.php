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

require_once('modules/PurchaseOrder/PurchaseOrder.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');

$local_log =& LoggerManager::getLogger('index');

$focus = new Order();

setObjectValuesFromRequest(&$focus);

//Added code for auto product stock updation on receiving goods
$update_prod_stock='';
if($focus->column_fields['postatus'] == 'Received Shipment' && $focus->mode == 'edit')
{
        $prev_postatus=getPoStatus($focus->id);
        if($focus->column_fields['postatus'] != $prev_postatus)
        {
                $update_prod_stock='true';
        }

}

$focus->save("PurchaseOrder");
if($focus->mode == 'edit')
{
        $query1 = "delete from poproductrel where purchaseorderid=".$focus->id;
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

                $query ="insert into poproductrel values(".$focus->id.",".$prod_id.",".$qty.",".$listprice.")";
                $adb->query($query);
		if($update_prod_stock == 'true')
                {
                        addToProductStock($prod_id,$qty);
                }
		
        }
}


$return_id = $focus->id;

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "PurchaseOrder";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

$local_log->debug("Saved record with id of ".$return_id);

//code added for returning back to the current view after edit from list view
if($_REQUEST['return_viewname'] == '') $return_viewname='0';
if($_REQUEST['return_viewname'] != '')$return_viewname=$_REQUEST['return_viewname'];

header("Location: index.php?action=$return_action&module=$return_module&record=$return_id&viewname=$return_viewname");
?>
