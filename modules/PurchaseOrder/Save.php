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

$focus = new PurchaseOrder();
//added to fix 4600
$search=$_REQUEST['search_url'];

global $current_user;
setObjectValuesFromRequest($focus);

//Added code for auto product stock updation on receiving goods
$focus->update_prod_stock='';
if($focus->column_fields['postatus'] == 'Received Shipment')
{
	if($focus->mode != 'edit')
		$focus->update_prod_stock='true';
	else
	{
        	$prev_postatus=getPoStatus($focus->id);
        	if($focus->column_fields['postatus'] != $prev_postatus)
        	{
        	        $focus->update_prod_stock='true';
        	}
	}

}
if($focus->mode != 'edit')
{
	$focus->column_fields['purchaseorder_no'] = setInventorySeqNumber("increment_invno",$module);
}

$focus->save("PurchaseOrder");

$return_id = $focus->id;

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "PurchaseOrder";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];
if(isset($_REQUEST['parenttab']) && $_REQUEST['parenttab'] != "") $parenttab = $_REQUEST['parenttab'];

$local_log->debug("Saved record with id of ".$return_id);

//code added for returning back to the current view after edit from list view
if($_REQUEST['return_viewname'] == '') $return_viewname='0';
if($_REQUEST['return_viewname'] != '')$return_viewname=$_REQUEST['return_viewname'];

header("Location: index.php?action=$return_action&module=$return_module&record=$return_id&parenttab=$parenttab&viewname=$return_viewname&start=".$_REQUEST['pagenumber'].$search);
?>
