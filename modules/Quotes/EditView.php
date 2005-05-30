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

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Quotes/Quote.php');
require_once('modules/Accounts/Forms.php');
require_once('include/CustomFieldUtil.php');
require_once('include/ComboUtil.php');
require_once('include/uifromdbutil.php');
require_once('include/FormValidationUtil.php');

global $app_strings;
global $mod_strings;
global $current_user;

$focus = new Quote();

if(isset($_REQUEST['record'])) 
{
    $focus->id = $_REQUEST['record'];
    $focus->mode = 'edit'; 	
    $focus->retrieve_entity_info($_REQUEST['record'],"Quotes");		
    $focus->name=$focus->column_fields['subject']; 
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
    	$focus->mode = ''; 	
}
//get Block 1 Information
$block_1_header = getBlockTableHeader("LBL_QUOTE_INFORMATION");
$block_1 = getBlockInformation("Quotes",1,$focus->mode,$focus->column_fields);



//get Address Information

$block_2_header = getBlockTableHeader("LBL_ADDRESS_INFORMATION");
$block_2 = getBlockInformation("Quotes",2,$focus->mode,$focus->column_fields);

//get Description Information

$block_3_header = getBlockTableHeader("LBL_DESCRIPTION_INFORMATION");
$block_3 = getBlockInformation("Quotes",3,$focus->mode,$focus->column_fields);


$block_4_header = getBlockTableHeader("LBL_RELATED_PRODUCTS");
/*
//get Custom Field Information
$block_5 = getBlockInformation("Accounts",5,$focus->mode,$focus->column_fields);
if(trim($block_5) != '')
{
        $cust_fld = '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formOuterBorder">';
        $cust_fld .=  '<tr><td>';
	$block_5_header = getBlockTableHeader("LBL_CUSTOM_INFORMATION");
        $cust_fld .= $block_5_header;
        $cust_fld .= '<table width="100%" border="0" cellspacing="1" cellpadding="0">';
        $cust_fld .= $block_5;
        $cust_fld .= '</table>';
        $cust_fld .= '</td></tr></table>';
	$cust_fld .='<BR>';
}
*/

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
//retreiving the combo values array
$comboFieldNames = Array('accounttype'=>'account_type_dom'
                      ,'industry'=>'industry_dom');
$comboFieldArray = getComboArray($comboFieldNames);


require_once($theme_path.'layout_utils.php');

$log->info("Quote view");

$xtpl=new XTemplate ('modules/Quotes/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("BLOCK1", $block_1);
$xtpl->assign("BLOCK1_HEADER", $block_1_header);
$xtpl->assign("BLOCK2", $block_2);
$xtpl->assign("BLOCK2_HEADER", $block_2_header);
$xtpl->assign("BLOCK3", $block_3);
$xtpl->assign("BLOCK3_HEADER", $block_3_header);
$xtpl->assign("BLOCK4_HEADER", $block_4_header);


if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");
/*
if(isset($cust_fld))
{
        $xtpl->assign("CUSTOMFIELD", $cust_fld);
}
*/


if($focus->mode == 'edit')
{
	$num_of_products = getNoOfAssocProducts($module,$focus);
	$xtpl->assign("ROWCOUNT", $num_of_products);
	$associated_prod = getAssociatedProducts("Quotes",$focus);
	$xtpl->assign("ASSOCIATEDPRODUCTS", $associated_prod);
	$xtpl->assign("MODE", $focus->mode);
	$xtpl->assign("TAXVALUE", $focus->column_fields['txtTax']);
	$xtpl->assign("SUBTOTAL", $focus->column_fields['hdnSubTotal']);
	$xtpl->assign("GRANDTOTAL", $focus->column_fields['hdnGrandTotal']);
}
else
{
	$xtpl->assign("ROWCOUNT", '0');
	$xtpl->assign("TAXVALUE", '0');
}
	

if(isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
else $xtpl->assign("RETURN_MODULE","Quotes");
if(isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
else $xtpl->assign("RETURN_ACTION","index");
if(isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);


 $xtpl->assign("CALENDAR_LANG", "en");$xtpl->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));





$quote_tables = Array('quotes','crmentity','quotesbillads','quotesshipads'); 
 $tabid = getTabid("Quotes");
 $validationData = getDBValidationData($quote_tables,$tabid);
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

$xtpl->assign("VALIDATION_DATA_FIELDNAME",$fieldName);
$xtpl->assign("VALIDATION_DATA_FIELDDATATYPE",$fldDataType);
$xtpl->assign("VALIDATION_DATA_FIELDLABEL",$fieldLabel);










//CustomField
//$date_format = parse_calendardate($app_strings['NTC_DATE_FORMAT']);
//$custfld = CustomFieldEditView($focus->id, "Accounts", "accountcf", "accountid", $app_strings, $theme);
//$xtpl->assign("CUSTOMFIELD", $custfld);

$xtpl->parse("main");

$xtpl->out("main");

?>
