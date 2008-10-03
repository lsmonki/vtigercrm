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

global $theme,$current_user;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$log->info("Lead detail view");

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);

$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("ID", $focus->id);
$smarty->assign("SINGLE_MOD", 'Lead');

$lead_name = $focus->lastname;
if (getFieldVisibilityPermission($currentModule, $current_user->id,'firstname') == '0') {
	$lead_name .= ' '.$focus->firstname;
}
$smarty->assign("NAME",$lead_name );

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
	global $current_user;
        require("user_privileges/user_privileges_".$current_user->id.".php");

	$wordTemplateResult = fetchWordTemplateList("Leads");
	$tempCount = $adb->num_rows($wordTemplateResult);
	$tempVal = $adb->fetch_array($wordTemplateResult);
	for($templateCount=0;$templateCount<$tempCount;$templateCount++)
	{
		$optionString[$tempVal["templateid"]] =$tempVal["filename"];
		$tempVal = $adb->fetch_array($wordTemplateResult);
	}
        if($is_admin)
                $smarty->assign("MERGEBUTTON","permitted");
	elseif($tempCount >0)
		$smarty->assign("MERGEBUTTON","permitted");

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
$smarty->assign("TODO_PERMISSION",CheckFieldPermission('parent_id','Calendar'));
$smarty->assign("EVENT_PERMISSION",CheckFieldPermission('parent_id','Events'));

$smarty->assign("IS_REL_LIST",isPresentRelatedLists($currentModule));

if($singlepane_view == 'true')
{
	$related_array = getRelatedLists($currentModule,$focus);
	$smarty->assign("RELATEDLISTS", $related_array);
}
$smarty->assign("SinglePane_View", $singlepane_view);

if(isset($_SESSION['leads_listquery'])){
	$arrayTotlist = array();
	$aNamesToList = array(); 
	$forAllCRMIDlist_query=$_SESSION['leads_listquery'];
	$resultAllCRMIDlist_query=$adb->pquery($forAllCRMIDlist_query,array());
	while($forAllCRMID = $adb->fetch_array($resultAllCRMIDlist_query))
	{
		$arrayTotlist[]=$forAllCRMID['crmid'];
	}
	$_SESSION['listEntyKeymod'] = $module.":".implode(",",$arrayTotlist);
	if(isset($_SESSION['listEntyKeymod']))
	{
	$split_temp=explode(":",$_SESSION['listEntyKeymod']);
		if($split_temp[0] == $module)
		{	
			$smarty->assign("SESMODULE",$split_temp[0]);
			$ar_allist=explode(",",$split_temp[1]);
			
			for($listi=0;$listi<count($ar_allist);$listi++)
			{
				if($ar_allist[$listi]==$_REQUEST[record])
				{
					if($listi-1>=0)
					{
						$privrecord=$ar_allist[$listi-1];
						$smarty->assign("privrecord",$privrecord);
					}else {unset($privrecord);}
					if($listi+1<count($ar_allist))
					{
						$nextrecord=$ar_allist[$listi+1];
						$smarty->assign("nextrecord",$nextrecord);
					}else {unset($nextrecord);}
					break;
				}
				
			}
		}
	}
}
$smarty->display("DetailView.tpl");

?>
