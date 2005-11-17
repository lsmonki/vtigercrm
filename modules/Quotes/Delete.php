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
 * Description:  Deletes an Account record and then redirects the browser to the 
 * defined return URL.
 ********************************************************************************/

require_once('modules/Quotes/Quote.php');
global $mod_strings;

require_once('include/logging.php');
$log = LoggerManager::getLogger('quote_delete');

$focus = new Quote();

if(!isset($_REQUEST['record']))
	die($mod_strings['ERR_DELETE_RECORD']);

$sql_recentviewed ='delete from tracker where user_id = '.$current_user->id.' and item_id = '.$_REQUEST['record'];
$adb->query($sql_recentviewed);
if($_REQUEST['return_module'] == $_REQUEST['module'] || $_REQUEST['return_module'] == "Accounts" )
{
	$focus->mark_deleted($_REQUEST['record']);
}
elseif($_REQUEST['return_module'] == "Potentials")
{
	$relation_query = "UPDATE quotes set potentialid='' where quoteid=".$_REQUEST['record'];
	$adb->query($relation_query);
}
elseif($_REQUEST['return_module'] == "Contacts")
{
	$relation_query = "UPDATE quotes set contactid='' where quoteid=".$_REQUEST['record'];
	$adb->query($relation_query);
}
elseif($_REQUEST['return_module'] == "Products")
{
	//$relation_query = "DELETE FROM quotesproductrel where productid=".$_REQUEST['return_id']." and quoteid=".$_REQUEST['record'];
	//$adb->query($relation_query);
	//Removing the relation from the quotes product rel
	$qt_query = "select * from quotesproductrel where productid=".$_REQUEST['return_id'];
	//echo $qt_query;
	$result = $adb->query($qt_query);
	$num_rows = $adb->num_rows($result);
	for($i=0; $i< $num_rows; $i++)
	{
	        $quote_id = $adb->query_result($result,$i,"quoteid");
        	$qty = $adb->query_result($result,$i,"quantity");
	        $listprice = $adb->query_result($result,$i,"listprice");
        	$prod_total = $qty * $listprice;

	        //Get the current sub total from Quotes and update it with the new subtotal
        	updateSubTotal("Quotes","quotes","subtotal","total","quoteid",$quote_id,$prod_total);
	}
	//delete the relation from quotes product rel
	$del_query = "delete from quotesproductrel where productid=".$_REQUEST['return_id']." and quoteid=".$_REQUEST['record'];
	$adb->query($del_query);

}
//code added for returning back to the current view after delete from list view
if($_REQUEST['return_viewname'] == '') $return_viewname='0';
if($_REQUEST['return_viewname'] != '')$return_viewname=$_REQUEST['return_viewname'];
header("Location: index.php?module=".$_REQUEST['return_module']."&action=".$_REQUEST['return_action']."&record=".$_REQUEST['return_id']."&viewname=".$return_viewname);
?>
