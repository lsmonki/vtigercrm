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
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once('data/Tracker.php');
require_once('modules/Invoice/Invoice.php');
require_once('modules/Quotes/Quote.php');
require_once('modules/SalesOrder/SalesOrder.php');
require_once('modules/Invoice/Forms.php');
require_once('modules/Potentials/Opportunity.php');
require_once('include/CustomFieldUtil.php');
require_once('include/ComboUtil.php');
require_once('include/utils/utils.php');
require_once('include/FormValidationUtil.php');

global $app_strings,$mod_strings,$log;

$focus = new Invoice();
$smarty = new vtigerCRM_Smarty();

if(isset($_REQUEST['record']) && $_REQUEST['record'] != '') 
{
    if(isset($_REQUEST['convertmode']) &&  $_REQUEST['convertmode'] == 'quotetoinvoice')
    {
	$quoteid = $_REQUEST['record'];
	$quote_focus = new Quote();
	$quote_focus->id = $quoteid;
	$quote_focus->retrieve_entity_info($quoteid,"Quotes");
	$focus = getConvertQuoteToInvoice($focus,$quote_focus,$quoteid);
			
    }
    elseif(isset($_REQUEST['convertmode']) &&  $_REQUEST['convertmode'] == 'sotoinvoice')
    {
        $soid = $_REQUEST['record'];
        $so_focus = new SalesOrder();
        $so_focus->id = $soid;
        $so_focus->retrieve_entity_info($soid,"SalesOrder");
        $focus = getConvertSoToInvoice($focus,$so_focus,$soid);

    }
    elseif(isset($_REQUEST['convertmode']) &&  $_REQUEST['convertmode'] == 'potentoinvoice')
    {
	    $focus->mode = '';		
    }
    elseif(isset($_REQUEST['convertmode']) &&  $_REQUEST['convertmode'] == 'update_so_val')
    {
        //Updating the Selected SO Value in Edit Mode
        foreach($focus->column_fields as $fieldname => $val)
        {
                if(isset($_REQUEST[$fieldname]))
                {
                        $value = $_REQUEST[$fieldname];
                        $focus->column_fields[$fieldname] = $value;
                }

        }
	//Handling for dateformat in invoicedate field
        if($focus->column_fields['invoicedate'] != '')
        {
              $curr_due_date = $focus->column_fields['invoicedate'];
              $focus->column_fields['invoicedate'] = getDBInsertDateValue($curr_due_date);
        }

	$soid = $focus->column_fields['salesorder_id'];
        $so_focus = new SalesOrder();
        $so_focus->id = $soid;
        $so_focus->retrieve_entity_info($soid,"SalesOrder");
        $focus = getConvertSoToInvoice($focus,$so_focus,$soid);
        $focus->id = $_REQUEST['record'];
        $focus->mode = 'edit';
        $focus->name=$focus->column_fields['subject'];

    }		
    else
    {	
 	    $focus->id = $_REQUEST['record'];
	    $focus->mode = 'edit'; 	
	    $focus->retrieve_entity_info($_REQUEST['record'],"Invoice");		
	    $focus->name=$focus->column_fields['subject'];
    } 
}
else
{
	if(isset($_REQUEST['convertmode']) &&  $_REQUEST['convertmode'] == 'update_so_val')
	{
		//Updating the Selected SO Value in Create Mode
		foreach($focus->column_fields as $fieldname => $val)
		{
			if(isset($_REQUEST[$fieldname]))
			{
				$value = $_REQUEST[$fieldname];
				$focus->column_fields[$fieldname] = $value;
			}

		}
		//Handling for dateformat in invoicedate field
                if($focus->column_fields['invoicedate'] != '')
                {
                        $curr_due_date = $focus->column_fields['invoicedate'];
                        $focus->column_fields['invoicedate'] = getDBInsertDateValue($curr_due_date);
                }

		$soid = $focus->column_fields['salesorder_id'];
		$so_focus = new SalesOrder();
		$so_focus->id = $soid;
		$so_focus->retrieve_entity_info($soid,"SalesOrder");
		$focus = getConvertSoToInvoice($focus,$so_focus,$soid);

	}
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$num_of_products = getNoOfAssocProducts("Invoice",$focus);
	$associated_prod = getAssociatedProducts("Invoice",$focus);
	$focus->id = "";
    	$focus->mode = ''; 	
}
if(isset($_REQUEST['opportunity_id']) && $_REQUEST['opportunity_id'] !='')
{
	$potfocus = new Potential();
        $potfocus->column_fields['potential_id'] = $_REQUEST['opportunity_id'];
	$num_of_products = getNoOfAssocProducts("Potentials",$potfocus,$potfocus->column_fields['potential_id']);
        $associated_prod = getAssociatedProducts("Potentials",$potfocus,$potfocus->column_fields['potential_id']);
	
}
if(isset($_REQUEST['product_id']) && $_REQUEST['product_id'] != '') {
        $focus->column_fields['product_id'] = $_REQUEST['product_id'];
	  $log->debug("Invoice EditView: Product Id from the request is ".$_REQUEST['product_id']);
	$num_of_products = getNoOfAssocProducts("Products",$focus,$focus->column_fields['product_id']);
	$associated_prod = getAssociatedProducts("Products",$focus,$focus->column_fields['product_id']);
} 
 
 
if(isset($_REQUEST['account_id']) && $_REQUEST['account_id']!='' && ($_REQUEST['record']=='' || $_REQUEST['convertmode'] == "potentoinvoice")){
	require_once('modules/Accounts/Account.php');
	$acct_focus = new Account();
	$acct_focus->retrieve_entity_info($_REQUEST['account_id'],"Accounts");
	$focus->column_fields['bill_city']=$acct_focus->column_fields['bill_city'];
	$focus->column_fields['ship_city']=$acct_focus->column_fields['ship_city'];
	$focus->column_fields['bill_street']=$acct_focus->column_fields['bill_street'];
	$focus->column_fields['ship_street']=$acct_focus->column_fields['ship_street'];
	$focus->column_fields['bill_state']=$acct_focus->column_fields['bill_state'];
	$focus->column_fields['ship_state']=$acct_focus->column_fields['ship_state'];
	$focus->column_fields['bill_code']=$acct_focus->column_fields['bill_code'];
	$focus->column_fields['ship_code']=$acct_focus->column_fields['ship_code'];
	$focus->column_fields['bill_country']=$acct_focus->column_fields['bill_country'];
	$focus->column_fields['ship_country']=$acct_focus->column_fields['ship_country'];
	$focus->column_fields['bill_pobox']=$acct_focus->column_fields['bill_pobox'];
    $focus->column_fields['ship_pobox']=$acct_focus->column_fields['ship_pobox'];
	

}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
//retreiving the combo values array
$comboFieldNames = Array('accounttype'=>'account_type_dom'
                      ,'industry'=>'industry_dom');
$comboFieldArray = getComboArray($comboFieldNames);

$disp_view = getView($focus->mode);
if($disp_view == 'edit_view')
	$smarty->assign("BLOCKS",getBlocks("Invoice",$disp_view,$mode,$focus->column_fields));
else	
{
	$smarty->assign("BASBLOCKS",getBlocks("Invoice",$disp_view,$mode,$focus->column_fields,'BAS'));
}

$smarty->assign("OP_MODE",$disp_view);

$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD","Invoice");

$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);

require_once($theme_path.'layout_utils.php');

$log->info("Invoice view");

if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
else $smarty->assign("NAME", "");


if(isset($_REQUEST['convertmode']) &&  $_REQUEST['convertmode'] == 'quotetoinvoice')
{
	$smarty->assign("MODE", $quote_focus->mode);
	$se_array=getProductDetailsBlockInfo($quote_focus->mode,"Quote",$quote_focus);
}
elseif(isset($_REQUEST['convertmode']) &&  ($_REQUEST['convertmode'] == 'sotoinvoice' || $_REQUEST['convertmode'] == 'update_so_val'))
{
	$smarty->assign("MODE", $focus->mode);
	$se_array=getProductDetailsBlockInfo($focus->mode,"SalesOrder",$so_focus);
}
elseif($focus->mode == 'edit')
{
	$smarty->assign("UPDATEINFO",updateInfo($focus->id));
	$smarty->assign("MODE", $focus->mode);
	$se_array=getProductDetailsBlockInfo($focus->mode,"Invoice",$focus);
}
elseif(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true')
{
	$smarty->assign("MODE", $focus->mode);
	$se_array=getProductDetailsBlockInfo($focus->mode,"",$focus,$num_of_products,$associated_prod);
}
elseif((isset($_REQUEST['product_id']) && $_REQUEST['product_id'] != '') || (isset($_REQUEST['opportunity_id']) && $_REQUEST['opportunity_id'] != '')) {
        $smarty->assign("MODE", $focus->mode);
	$focus->column_fields['txtTax'] = "0.000";
        $focus->column_fields['txtAdjustment'] = "0.000";
        $focus->column_fields['hdnSubTotal'] = ".00";
        $focus->column_fields['hdnGrandTotal'] =".00";
        $se_array=getProductDetailsBlockInfo($focus->mode,"",$focus,$num_of_products,$associated_prod);

}
else
{
	$smarty->assign("ROWCOUNT", '1');
	$smarty->assign("TAXVALUE", '0');
	$smarty->assign("ADJUSTMENTVALUE", '0');
}

if(isset($cust_fld))
{
        $smarty->assign("CUSTOMFIELD", $cust_fld);
}

		

if(isset($_REQUEST['return_module'])) $smarty->assign("RETURN_MODULE", $_REQUEST['return_module']);
else $smarty->assign("RETURN_MODULE","Invoice");
if(isset($_REQUEST['return_action'])) $smarty->assign("RETURN_ACTION", $_REQUEST['return_action']);
else $smarty->assign("RETURN_ACTION","index");
if(isset($_REQUEST['return_id'])) $smarty->assign("RETURN_ID", $_REQUEST['return_id']);
if (isset($_REQUEST['return_viewname'])) $smarty->assign("RETURN_VIEWNAME", $_REQUEST['return_viewname']);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("ID", $focus->id);


$smarty->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);
$smarty->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));





$invoice_tables = Array('invoice','invoicebillads','invoiceshipads'); 
 $tabid = getTabid("Invoice");
 $validationData = getDBValidationData($invoice_tables,$tabid);
 $data = split_validationdataArray($validationData);
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

$smarty->assign("VALIDATION_DATA_FIELDNAME",$data['fieldname']);
$smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$data['datatype']);
$smarty->assign("VALIDATION_DATA_FIELDLABEL",$data['fieldlabel']);

if($focus->mode == 'edit')
$smarty->display("salesEditView.tpl");
else
$smarty->display('CreateView.tpl');

?>
