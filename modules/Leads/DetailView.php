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
require_once('Smarty_setup.php');
require_once('data/Tracker.php');
require_once('modules/Leads/Leads.php');
require_once('include/database/PearDatabase.php');
require_once('include/CustomFieldUtil.php');
require_once('include/utils/utils.php');
require_once('include/utils/UserInfoUtil.php');
require_once('user_privileges/default_module_view.php');

global $mod_strings;
global $app_strings;
global $currentModule, $singlepane_view;
    global $log;
$focus = new Leads();

if(isset($_REQUEST['record']))
{
    $focus->id = $_REQUEST['record'];	

    $focus->retrieve_entity_info($_REQUEST['record'],"Leads");
    $focus->id = $_REQUEST['record'];
     $log->debug("id is ".$focus->id);
    $focus->firstname=$focus->column_fields['firstname'];
    $focus->lastname=$focus->column_fields['lastname'];
	
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
} 

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Lead detail view");

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);

$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("ID", $focus->id);
$smarty->assign("SINGLE_MOD", 'Lead');

$smarty->assign("NAME",$focus->lastname.' '.$focus->firstname);

$smarty->assign("UPDATEINFO",updateInfo($focus->id));
$smarty->assign("BLOCKS", getBlocks($currentModule,"detail_view",'',$focus->column_fields));
$smarty->assign("CUSTOMFIELD", $cust_fld);

if(useInternalMailer() == 1)
        $smarty->assign("INT_MAILER","true");


$val = isPermitted("Leads","EditView",$_REQUEST['record']);

if(isPermitted("Leads","EditView",$_REQUEST['record']) == 'yes')
	$smarty->assign("EDIT_DUPLICATE","permitted");


if(isPermitted("Leads","ConvertLead") =='yes' && isPermitted("Accounts","EditView") =='yes' && isPermitted("Contacts","EditView"))
{
	$smarty->assign("CONVERTLEAD","permitted");
}

$category = getParentTab();
$smarty->assign("CATEGORY",$category);


if(isPermitted("Leads","Delete",$_REQUEST['record']) == 'yes')
	$smarty->assign("DELETE","permitted");

if(isPermitted("Emails","EditView",'') == 'yes')
{
	//Added to pass the parents list as hidden for Emails -- 09-11-2005
	$parent_email = getEmailParentsList('Leads',$_REQUEST['record']);
        $smarty->assign("HIDDEN_PARENTS_LIST",$parent_email);
	$smarty->assign("SENDMAILBUTTON","permitted");
	$smarty->assign("EMAIL1",$focus->column_fields['email']);
	$smarty->assign("EMAIL2",$focus->column_fields['yahooid']);
      
}

if(isPermitted("Leads","Merge",'') == 'yes') 
{
	$smarty->assign("MERGEBUTTON","permitted");
	$wordTemplateResult = fetchWordTemplateList("Leads");
	$tempCount = $adb->num_rows($wordTemplateResult);
	$tempVal = $adb->fetch_array($wordTemplateResult);
	for($templateCount=0;$templateCount<$tempCount;$templateCount++)
	{
		$optionString[$tempVal["templateid"]] =$tempVal["filename"];
		$tempVal = $adb->fetch_array($wordTemplateResult);
	}
	 $smarty->assign("TEMPLATECOUNT",$tempCount);
	$smarty->assign("WORDTEMPLATEOPTIONS",$app_strings['LBL_SELECT_TEMPLATE_TO_MAIL_MERGE']);
        $smarty->assign("TOPTIONS",$optionString);
}

$tabid = getTabid("Leads");
$validationData = getDBValidationData($focus->tab_name,$tabid);
$data = split_validationdataArray($validationData);

$smarty->assign("VALIDATION_DATA_FIELDNAME",$data['fieldname']);
$smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$data['datatype']);
$smarty->assign("VALIDATION_DATA_FIELDLABEL",$data['fieldlabel']);

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);

$smarty->assign("MODULE", $currentModule);
$smarty->assign("EDIT_PERMISSION",isPermitted($currentModule,'EditView',$_REQUEST[record]));

$smarty->assign("IS_REL_LIST",isPresentRelatedLists($currentModule));

if($singlepane_view == 'true')
{
	$related_array = getRelatedLists($currentModule,$focus);
	$smarty->assign("RELATEDLISTS", $related_array);
}
//added for email link in detailv view
$querystr="SELECT fieldid FROM vtiger_field WHERE tabid=".getTabid($currentModule)." and uitype=13;";
$queryres = $adb->query($querystr);
$fieldid = $adb->query_result($queryres,0,'fieldid');
$smarty->assign("FIELD_ID",$fieldid);

$smarty->assign("SinglePane_View", $singlepane_view);

$smarty->display("DetailView.tpl");

?>
