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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Accounts/EditView.php,v 1.19 2005/03/24 16:18:38 samk Exp $
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once('data/Tracker.php');
require_once('modules/Accounts/Accounts.php');
require_once('include/CustomFieldUtil.php');
require_once('include/ComboUtil.php');
require_once('include/utils/utils.php');
require_once('include/FormValidationUtil.php');

global $app_strings,$mod_strings,$currentModule,$theme;
$smarty=new vtigerCRM_Smarty;

$focus = new Accounts();

//added to fix the issue4600
$searchurl = getBasic_Advance_SearchURL();
$smarty->assign("SEARCH", $searchurl);
//ends 4600
if(isset($_REQUEST['record'])) 
{
    $focus->id = $_REQUEST['record'];
    $focus->mode = 'edit'; 	
    $focus->retrieve_entity_info($_REQUEST['record'],"Accounts");		
    $focus->name=$focus->column_fields['accountname']; 
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
    	$focus->mode = ''; 	
}
if(empty($_REQUEST['record']) && $focus->mode != 'edit'){
	setObjectValuesFromRequest($focus);
}
$disp_view = getView($focus->mode);
if($disp_view == 'edit_view')
	$smarty->assign("BLOCKS",getBlocks("Accounts",$disp_view,$mode,$focus->column_fields));
else	
{
	$smarty->assign("BASBLOCKS",getBlocks("Accounts",$disp_view,$mode,$focus->column_fields,'BAS'));
	$smarty->assign("ADVBLOCKS",getBlocks("Accounts",$disp_view,$mode,$focus->column_fields,'ADV'));
}

$smarty->assign("OP_MODE",$disp_view);
 

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
//retreiving the combo values array
$comboFieldNames = Array('accounttype'=>'account_type_dom'
                      ,'industry'=>'industry_dom');
$comboFieldArray = getComboArray($comboFieldNames);


$log->info("Account detail view");


$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);

if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
else $smarty->assign("NAME", "");
if(isset($cust_fld))
{
        $smarty->assign("CUSTOMFIELD", $cust_fld);
}
if($focus->mode == 'edit')
{
	$smarty->assign("UPDATEINFO",updateInfo($focus->id));
        $smarty->assign("MODE", $focus->mode);
}

if(isset($_REQUEST['return_module'])) $smarty->assign("RETURN_MODULE", $_REQUEST['return_module']);
else $smarty->assign("RETURN_MODULE","Accounts");
if(isset($_REQUEST['return_action'])) $smarty->assign("RETURN_ACTION", $_REQUEST['return_action']);
if(isset($_REQUEST['return_id'])) $smarty->assign("RETURN_ID", $_REQUEST['return_id']);
if(isset($_REQUEST['return_viewname'])) $smarty->assign("RETURN_VIEWNAME", $_REQUEST['return_viewname']);
$category = getParentTab();
$smarty->assign("CATEGORY",$category);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("ID", $focus->id);
$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD",'Account');

$smarty->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);
$smarty->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));

 $tabid = getTabid("Accounts");
 $validationData = getDBValidationData($focus->tab_name,$tabid);
 $data = split_validationdataArray($validationData);

 $smarty->assign("VALIDATION_DATA_FIELDNAME",$data['fieldname']);
 $smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$data['datatype']);
 $smarty->assign("VALIDATION_DATA_FIELDLABEL",$data['fieldlabel']);

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);
$smarty->assign("DUPLICATE",$_REQUEST['isDuplicate'] );
 
// Module Sequence Numbering
if($focus->mode != 'edit') {
		$autostr = getTranslatedString('MSG_AUTO_GEN_ON_SAVE');
		$inv_no = $adb->pquery("SELECT prefix, cur_id from vtiger_modentity_num where semodule = ? and active=1",array($module));
        $invstr = $adb->query_result($inv_no,0,'prefix');
        $invno = $adb->query_result($inv_no,0,'cur_id');
        if($focus->checkModuleSeqNumber('vtiger_account', 'account_no', $invstr.$invno))
                echo '<br><font color="#FF0000"><b>Duplicate Account Number - Click <a href="index.php?module=Settings&action=CustomModEntityNo&parenttab=Settings">here</a> to Configure the Account Number</b></font>'.$num_rows;
        else
                $smarty->assign("inv_no",$autostr);
}
// END

if ($focus->mode == 'edit')
$smarty->display('salesEditView.tpl');
else
$smarty->display('CreateView.tpl');

?>

