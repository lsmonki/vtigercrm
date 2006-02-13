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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Potentials/EditView.php,v 1.16 2005/03/24 16:18:38 samk Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Potentials/Opportunity.php');
require_once('modules/Potentials/Forms.php');
require_once('include/CustomFieldUtil.php');
require_once('include/ComboUtil.php');
require_once('include/uifromdbutil.php');
require_once('include/FormValidationUtil.php');
global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;
// Unimplemented until jscalendar language files are fixed
// global $current_language;
// global $default_language;
// global $cal_codes;

$focus = new Potential();

if(isset($_REQUEST['record'])) {
    $focus->id = $_REQUEST['record'];
    $focus->mode = 'edit'; 	
    $focus->retrieve_entity_info($_REQUEST['record'],"Potentials");
    $focus->name=$focus->column_fields['potentialname'];	
}
if(isset($_REQUEST['account_id']))
{
        $focus->column_fields['account_id'] = $_REQUEST['account_id'];
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
    	$focus->mode = ''; 	
}
//get Block 1 Information

$block_1 = getBlockInformation("Potentials",1,$focus->mode,$focus->column_fields);



//get Description Information

$block_2 = getBlockInformation("Potentials",2,$focus->mode,$focus->column_fields);

//get Description Information

//$block_3 = getBlockInformation("Potentials",3,$focus->mode,$focus->column_fields);

$block_1_header = getBlockTableHeader("LBL_OPPORTUNITY_INFORMATION");
$block_2_header = getBlockTableHeader("LBL_ADDRESS_INFORMATION");

//get Custom Field Information
$block_5 = getBlockInformation("Potentials",5,$focus->mode,$focus->column_fields);
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



//needed when creating a new opportunity with a default account value passed in
if (isset($_REQUEST['accountname']) && is_null($focus->accountname)) {
	$focus->accountname = $_REQUEST['accountname'];
	
}
if (isset($_REQUEST['accountid']) && is_null($focus->accountid)) {
	$focus->accountid = $_REQUEST['accountid'];
}
if (isset($_REQUEST['contactid']) && is_null($focus->contactid)) {
	$focus->contactid = $_REQUEST['contactid'];
}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
//retreiving the combo values array
$comboFieldNames = Array('leadsource'=>'leadsource_dom'
                      ,'opportunity_type'=>'opportunity_type_dom'
                      ,'sales_stage'=>'sales_stage_dom');
$comboFieldArray = getComboArray($comboFieldNames);
require_once($theme_path.'layout_utils.php');

$log->info("Potential detail view");

$xtpl=new XTemplate ('modules/Potentials/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("BLOCK1", $block_1);
$xtpl->assign("BLOCK2", $block_2);
$xtpl->assign("BLOCK3", $block_3);
$xtpl->assign("BLOCK1_HEADER", $block_1_header);
$xtpl->assign("BLOCK2_HEADER", $block_2_header);

if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");

if(isset($cust_fld))
{
        $xtpl->assign("CUSTOMFIELD", $cust_fld);
}
if($focus->mode == 'edit')
{
	$xtpl->assign("MODE", $focus->mode);
}		



// Unimplemented until jscalendar language files are fixed
// $xtpl->assign("CALENDAR_LANG", ((empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language]));
$xtpl->assign("CALENDAR_LANG", "en");$xtpl->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$xtpl->assign("ID", $focus->id);
/*
$xtpl->assign("ACCOUNTNAME", $focus->accountname);
$xtpl->assign("ACCOUNTID", $focus->accountid);
$xtpl->assign("CONTACTID", $focus->contactid);
if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");
$xtpl->assign("AMOUNT", $focus->amount);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);
$xtpl->assign("DATE_CLOSED", $focus->date_closed);
$xtpl->assign("NEXT_STEP", $focus->next_step);
$xtpl->assign("PROBABILITY", $focus->probability);
$xtpl->assign("PRODUCTID", $focus->product_id);
$xtpl->assign("PRODUCTNAME", $focus->product_name);
$xtpl->assign("DESCRIPTION", $focus->description);
if ($focus->assigned_user_id == '' && (!isset($focus->id) || $focus->id=0)) $focus->assigned_user_id = $current_user->id;
$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active", $focus->assigned_user_id), $focus->assigned_user_id));

$xtpl->assign("LEAD_SOURCE_OPTIONS", get_select_options_with_id($comboFieldArray['leadsource_dom'], $focus->leadsource));
$xtpl->assign("TYPE_OPTIONS", get_select_options_with_id($comboFieldArray['opportunity_type_dom'], $focus->opportunity_type));
$xtpl->assign("SALES_STAGE_OPTIONS", get_select_options_with_id($comboFieldArray['sales_stage_dom'], $focus->sales_stage));

//CustomField
$custfld = CustomFieldEditView($focus->id, "Potentials", "opportunitycf", "opportunityid", $app_strings, $theme);
$xtpl->assign("CUSTOMFIELD", $custfld);

*/





 $potential_tables = Array('potential','crmentity','potentialscf'); 
 $tabid = getTabid("Potentials");
 $validationData = getDBValidationData($potential_tables,$tabid);
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






$xtpl->parse("main");

$xtpl->out("main");

?>
