<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

require_once('include/database/PearDatabase.php');
require_once('Smarty_setup.php');
require_once('modules/HelpDesk/HelpDesk.php');
require_once('include/utils/utils.php');
require_once('user_privileges/default_module_view.php');

$focus = new HelpDesk();

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) 
{
    $focus->retrieve_entity_info($_REQUEST['record'],"HelpDesk");
    $focus->name=$focus->column_fields['ticket_title'];
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
        $focus->id = "";
}

//Added code for Error display in sending mail to assigned to user when ticket is created or updated.
if($_REQUEST['mail_error'] != '')
{
        require_once("modules/Emails/mail.php");
        echo parseEmailErrorString($_REQUEST['mail_error']);
}

global $app_strings;
global $mod_strings;
global $currentModule, $singlepane_view;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);

$focus->id = $_REQUEST['record'];
if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
else $smarty->assign("NAME", "");
$smarty->assign("BLOCKS", getBlocks($currentModule,"detail_view",'',$focus->column_fields));
$smarty->assign("TICKETID", $_REQUEST['record']);

$smarty->assign("CUSTOMFIELD", $cust_fld);
$smarty->assign("SINGLE_MOD", 'HelpDesk');
$category = getParentTab();
$smarty->assign("CATEGORY",$category);
$smarty->assign("UPDATEINFO",updateInfo($_REQUEST['record']));

if(isPermitted("HelpDesk","EditView",$_REQUEST['record']) == 'yes')
	$smarty->assign("EDIT_DUPLICATE","permitted");

if(isPermitted("HelpDesk","Delete",$_REQUEST['record']) == 'yes')
	$smarty->assign("DELETE","permitted");

//Added button for Convert the ticket to FAQ
if(isPermitted("Faq","EditView",'') == 'yes')
	$smarty->assign("CONVERTASFAQ","permitted");

$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("ID", $_REQUEST['record']);
if(isPermitted("HelpDesk","Merge",'') == 'yes')
{
	$smarty->assign("MERGEBUTTON","permitted");
        require_once('include/utils/UserInfoUtil.php');
        $wordTemplateResult = fetchWordTemplateList("HelpDesk");
        $tempCount = $adb->num_rows($wordTemplateResult);
        $tempVal = $adb->fetch_array($wordTemplateResult);
        for($templateCount=0;$templateCount<$tempCount;$templateCount++)
        {
                $optionString[$tempVal["templateid"]]=$tempVal["filename"];
                $tempVal = $adb->fetch_array($wordTemplateResult);
        }
	 $smarty->assign("TEMPLATECOUNT",$tempCount);
	$smarty->assign("WORDTEMPLATEOPTIONS",$app_strings['LBL_SELECT_TEMPLATE_TO_MAIL_MERGE']);
        $smarty->assign("TOPTIONS",$optionString);
}

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);

$tabid = getTabid("HelpDesk");
$validationData = getDBValidationData($focus->tab_name,$tabid);
$data = split_validationdataArray($validationData);
$smarty->assign("VALIDATION_DATA_FIELDNAME",$data['fieldname']);
$smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$data['datatype']);
$smarty->assign("VALIDATION_DATA_FIELDLABEL",$data['fieldlabel']);

//Added to display the ticket comments information
$smarty->assign("COMMENT_BLOCK",$focus->getCommentInformation($_REQUEST['record']));

$smarty->assign("MODULE",$currentModule);
$smarty->assign("EDIT_PERMISSION",isPermitted($currentModule,'EditView',$_REQUEST[record]));
$smarty->assign("IS_REL_LIST",isPresentRelatedLists($currentModule));

if($singlepane_view == 'true')
{
	$related_array = getRelatedLists($currentModule,$focus);
	$smarty->assign("RELATEDLISTS", $related_array);
}

$smarty->assign("SinglePane_View", $singlepane_view);

$smarty->display("DetailView.tpl");


?>
