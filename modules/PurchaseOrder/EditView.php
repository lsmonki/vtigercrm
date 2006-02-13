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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/PurchaseOrder/EditView.php,v 1.8 2006/01/27 18:20:22 jerrydgeorge Exp $
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once('data/Tracker.php');
require_once('modules/PurchaseOrder/PurchaseOrder.php');
require_once('modules/PurchaseOrder/Forms.php');
require_once('include/CustomFieldUtil.php');
require_once('include/ComboUtil.php');
require_once('include/utils/utils.php');
require_once('include/FormValidationUtil.php');

global $app_strings;
global $mod_strings;
global $current_user;
global $log;


$focus = new Order();
$smarty = new vtigerCRM_Smarty();

if(isset($_REQUEST['record']) && $_REQUEST['record'] != '') 
{
    $focus->id = $_REQUEST['record'];
    $focus->mode = 'edit'; 	
    $focus->retrieve_entity_info($_REQUEST['record'],"PurchaseOrder");		
    $focus->name=$focus->column_fields['subject']; 
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$num_of_products = getNoOfAssocProducts("PurchaseOrder",$focus);
	$associated_prod = getAssociatedProducts("PurchaseOrder",$focus);
	$focus->id = "";
    	$focus->mode = ''; 	
}
if(isset($_REQUEST['product_id']) && $_REQUEST['product_id'] !='')
{
        $focus->column_fields['product_id'] = $_REQUEST['product_id'];
	$log->debug("Purchase Order EditView: Product Id from the request is ".$_REQUEST['product_id']);
        $num_of_products = getNoOfAssocProducts("Products",$focus,$focus->column_fields['product_id']);
        $associated_prod = getAssociatedProducts("Products",$focus,$focus->column_fields['product_id']);
}

// Get vendor address if vendorid is given
if(isset($_REQUEST['vendor_id']) && $_REQUEST['vendor_id']!='' && $_REQUEST['record']==''){
	require_once('modules/Vendors/Vendor.php');
	$vend_focus = new Vendor();
	$vend_focus->retrieve_entity_info($_REQUEST['vendor_id'],"Vendor");
	$focus->column_fields['bill_city']=$vend_focus->column_fields['city'];
	$focus->column_fields['ship_city']=$vend_focus->column_fields['city'];
	$focus->column_fields['bill_street']=$vend_focus->column_fields['treet'];
	$focus->column_fields['ship_street']=$vend_focus->column_fields['treet'];
	$focus->column_fields['bill_state']=$vend_focus->column_fields['state'];
	$focus->column_fields['ship_state']=$vend_focus->column_fields['state'];
	$focus->column_fields['bill_code']=$vend_focus->column_fields['postalcode'];
	$focus->column_fields['ship_code']=$vend_focus->column_fields['postalcode'];
	$focus->column_fields['bill_country']=$vend_focus->column_fields['country'];
	$focus->column_fields['ship_country']=$vend_focus->column_fields['country'];

}
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
//retreiving the combo values array
$comboFieldNames = Array('accounttype'=>'account_type_dom'
                      ,'industry'=>'industry_dom');
$comboFieldArray = getComboArray($comboFieldNames);

$disp_view = getView($focus->mode);
$smarty->assign("BLOCKS",getBlocks("PurchaseOrder",$disp_view,$mode,$focus->column_fields));
//echo '<pre>';print_r(getBlocks("PurchaseOrder",$disp_view,$mode,$focus->column_fields));echo '</pre>';
$smarty->assign("OP_MODE",$disp_view);

$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD","PurchaseOrder");
$category = getParentTab();
$smarty->assign("CATEGORY",$category);


$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);


require_once($theme_path.'layout_utils.php');

$log->info("Order view");

if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
else $smarty->assign("NAME", "");

if($focus->mode == 'edit')
{
	$smarty->assign("UPDATEINFO",updateInfo($focus->id));
	$num_of_products = getNoOfAssocProducts("PurchaseOrder",$focus);
	$smarty->assign("ROWCOUNT", $num_of_products);
	$associated_prod = getAssociatedProducts("PurchaseOrder",$focus);
	$smarty->assign("ASSOCIATEDPRODUCTS", $associated_prod);
	$smarty->assign("MODE", $focus->mode);
	$smarty->assign("TAXVALUE", $focus->column_fields['txtTax']);
	$smarty->assign("ADJUSTMENTVALUE", $focus->column_fields['txtAdjustment']);
	$smarty->assign("SUBTOTAL", $focus->column_fields['hdnSubTotal']);
	$smarty->assign("GRANDTOTAL", $focus->column_fields['hdnGrandTotal']);
}
elseif(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true')
{
	$smarty->assign("ROWCOUNT", $num_of_products);
	$smarty->assign("ASSOCIATEDPRODUCTS", $associated_prod);
	$smarty->assign("MODE", $focus->mode);
	$smarty->assign("TAXVALUE", $focus->column_fields['txtTax']);
	$smarty->assign("ADJUSTMENTVALUE", $focus->column_fields['txtAdjustment']);
	$smarty->assign("SUBTOTAL", $focus->column_fields['hdnSubTotal']);
	$smarty->assign("GRANDTOTAL", $focus->column_fields['hdnGrandTotal']);

}
elseif((isset($_REQUEST['product_id']) && $_REQUEST['product_id'] != '')) {
        $smarty->assign("ROWCOUNT", $num_of_products);
        $smarty->assign("ASSOCIATEDPRODUCTS", $associated_prod);
	$InvTotal = getInventoryTotal($_REQUEST['return_module'],$_REQUEST['return_id']);
        $smarty->assign("MODE", $focus->mode);
        $smarty->assign("TAXVALUE", "0.000");
        $smarty->assign("ADJUSTMENTVALUE", "0.000");
        $smarty->assign("SUBTOTAL", $InvTotal.".00");
        $smarty->assign("GRANDTOTAL", $InvTotal.".00");

}
else
{
	$smarty->assign("ROWCOUNT", '1');
	$smarty->assign("TAXVALUE", '0');
	$smarty->assign("ADJUSTMENTVALUE", '0');
	$output ='';
	$output .= '<tr id="row1" class="oddListRow">';
        $output .= '<td height="25" style="padding:3px;" nowrap><input id="txtProduct1" name="txtProduct1" type="text" readonly> <img src="'.$image_path.'search.gif" onClick=\'productPickList(this)\' align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>';
        $output .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
        $output .= '<td style="padding:3px;"><input type=text id="txtQty1" name="txtQty1" size="7" onBlur=\'calcTotal(this)\'></td>';
        $output .='<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
        $output .= '<td style="padding:3px;"><div id="unitPrice1"></div>&nbsp;</td>';
        $output .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
        $output .= '<td style="padding:3px;"><input type=text id="txtListPrice1" name="txtListPrice1" value="0.00" size="12" onBlur="calcTotal(this)"> <img src="'.$image_path.'pricebook.gif" onClick=\'priceBookPickList(this)\' align="absmiddle" style="cursor:hand;cursor:pointer" title="Price Book"></td>';
        $output .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
        $output .= '<td style="padding:3px;"><div id="total1" align="right"></div></td>';
        $output .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
        $output .= '<td style="padding:0px 3px 0px 3px;" align="center" width="50">';
        $output .= '<input type="hidden" id="hdnProductId1" name="hdnProductId1">';
        $output .= '<input type="hidden" id="hdnRowStatus1" name="hdnRowStatus1">';
        $output .= '<input type="hidden" id="hdnTotal1" name="hdnTotal1">';
        $output .= '</td></tr>';
	$smarty->assign("ROW1", $output);	

}

if(isset($cust_fld))
{
        $smarty->assign("CUSTOMFIELD", $cust_fld);
}

		

if(isset($_REQUEST['return_module'])) $smarty->assign("RETURN_MODULE", $_REQUEST['return_module']);
else $smarty->assign("RETURN_MODULE","PurchaseOrder");
if(isset($_REQUEST['return_action'])) $smarty->assign("RETURN_ACTION", $_REQUEST['return_action']);
if(isset($_REQUEST['return_id'])) $smarty->assign("RETURN_ID", $_REQUEST['return_id']);
if (isset($_REQUEST['return_viewname'])) $smarty->assign("RETURN_VIEWNAME", $_REQUEST['return_viewname']);
$smarty->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("MODULE","PurchaseOrder");
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("ID", $focus->id);


$smarty->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);
$smarty->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));





$po_tables = Array('purchaseorder','pobillads','poshipads'); 
 $tabid = getTabid("PurchaseOrder");
 $validationData = getDBValidationData($po_tables,$tabid);
 $fieldName = '';
 $fieldLabel = '';
 $fldDataType = '';

 $rows = count($validationData);
 foreach($validationData as $fldName => $fldLabel_array)
 {
   if($fieldName == '')
   {
     $fieldName="'".$fldName."'";
   }
   else
   {
     $fieldName .= ",'".$fldName ."'";
   }
   foreach($fldLabel_array as $fldLabel => $datatype)
   {
	if($fieldLabel == '')
	{
			
     		$fieldLabel = "'".$fldLabel ."'";
	}		
      else
       {
      $fieldLabel .= ",'".$fldLabel ."'";
        }
 	if($fldDataType == '')
         {
      		$fldDataType = "'".$datatype ."'";
    	}
	 else
        {
       		$fldDataType .= ",'".$datatype ."'";
     	}
   }
 }

$smarty->assign("VALIDATION_DATA_FIELDNAME",$fieldName);
$smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$fldDataType);
$smarty->assign("VALIDATION_DATA_FIELDLABEL",$fieldLabel);

$smarty->display('salesEditView.tpl');
?>
