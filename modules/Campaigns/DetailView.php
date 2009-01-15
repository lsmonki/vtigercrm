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
require_once('modules/Campaigns/Campaigns.php');
require_once('include/utils/utils.php');
require_once('user_privileges/default_module_view.php');

$focus = new Campaigns();

if(isset($_REQUEST['record']) && $_REQUEST['record']!= null ) 
{
    $focus->retrieve_entity_info($_REQUEST['record'],"Campaigns");
    $focus->name=$focus->column_fields['campaignname'];
    $focus->id = $_REQUEST['record'];
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
        $focus->id = "";
}
global $app_strings,$mod_strings,$theme,$currentModule,$default_module_view,$adb;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("THEME", $theme);

if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
else $smarty->assign("NAME", "");
$smarty->assign("BLOCKS", getBlocks($currentModule,"detail_view",'',$focus->column_fields));

$smarty->assign("CUSTOMFIELD", $cust_fld);
$smarty->assign("SINGLE_MOD",'Campaign');
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

if(isPermitted("Campaigns","EditView",$_REQUEST['record']) == 'yes')
	$smarty->assign("EDIT_DUPLICATE","permitted");

if(isPermitted("Campaigns","Delete",$_REQUEST['record']) == 'yes')
	$smarty->assign("DELETE","permitted");

$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("UPDATEINFO",updateInfo($focus->id));
$smarty->assign("ID", $_REQUEST['record']);

// Module Sequence Numbering
$mod_seq_field = getModuleSequenceField($currentModule);
if ($mod_seq_field != null) {
	$mod_seq_id = $focus->column_fields[$mod_seq_field['name']];
} else {
	$mod_seq_id = $focus->id;
}
$smarty->assign('MOD_SEQ_ID', $mod_seq_id);
// END

$tabid = getTabid("Campaigns");
$validationData = getDBValidationData($focus->tab_name,$tabid);
$data = split_validationdataArray($validationData);

$smarty->assign("VALIDATION_DATA_FIELDNAME",$data['fieldname']);
$smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$data['datatype']);
$smarty->assign("VALIDATION_DATA_FIELDLABEL",$data['fieldlabel']);

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);
$smarty->assign("IS_REL_LIST",isPresentRelatedLists($currentModule));

if($singlepane_view == 'true')
{
	$related_array = getRelatedLists($currentModule,$focus);
	$smarty->assign("RELATEDLISTS", $related_array);

	require_once('modules/CustomView/CustomView.php');
	
	/* To get Contacts CustomView -START */
	$chtml = "<select id='cont_cv_list'><option value='None'>-- ".$mod_strings['Select One']." --</option>";
	$oCustomView = new CustomView('Contacts');
	$viewid = $oCustomView->getViewId('Contacts');
	$customviewcombo_html = $oCustomView->getCustomViewCombo($viewid, false);
	$chtml .= $customviewcombo_html;
	$chtml .= "</select>";
	$smarty->assign("CONTCVCOMBO",$chtml);
	/* To get Contacts CustomView -END */
	
	/* To get Leads CustomView -START */
	$lhtml = "<select id='lead_cv_list'><option value='None'>-- ".$mod_strings['Select One']." --</option>";
	$oCustomView = new CustomView('Leads');
	$viewid = $oCustomView->getViewId('Leads');
	$customviewcombo_html = $oCustomView->getCustomViewCombo($viewid, false);
	$lhtml .= $customviewcombo_html;
	$lhtml .= "</select>";
	$smarty->assign("LEADCVCOMBO",$lhtml);
	/* To get Leads CustomView -END */

}

$smarty->assign("SinglePane_View", $singlepane_view);
$smarty->assign("TODO_PERMISSION",CheckFieldPermission('parent_id','Calendar'));
$smarty->assign("EVENT_PERMISSION",CheckFieldPermission('parent_id','Events'));
$smarty->assign("MODULE",$currentModule);
$smarty->assign("EDIT_PERMISSION",isPermitted($currentModule,'EditView',$_REQUEST[record]));

if(isset($_SESSION['campaigns_listquery'])){
	$arrayTotlist = array();
	$aNamesToList = array(); 
	$forAllCRMIDlist_query=$_SESSION['campaigns_listquery'];
	$resultAllCRMIDlist_query=$adb->pquery($forAllCRMIDlist_query,array());
	while($forAllCRMID = $adb->fetch_array($resultAllCRMIDlist_query))
	{
		$arrayTotlist[]=$forAllCRMID['crmid'];
	}
	$_SESSION['listEntyKeymod_'.$focus->id] = $module.":".implode(",",$arrayTotlist);
	if(isset($_SESSION['listEntyKeymod_'.$focus->id]))
	{
		$split_temp=explode(":",$_SESSION['listEntyKeymod_'.$focus->id]);
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

// Record Change Notification
$focus->markAsViewed($current_user->id);
// END

$smarty->display("DetailView.tpl");

$focus->id = $_REQUEST['record'];

?>
