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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Emails/DetailView.php,v 1.22 2005/03/24 19:09:21 rank Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Emails/Email.php');
require_once('modules/Emails/Forms.php');
require_once('include/upload_file.php');
require_once('include/database/PearDatabase.php');
require_once('include/uifromdbutil.php');

global $vtlog;
global $app_strings;
global $mod_strings;

$focus = new Email();

if(isset($_REQUEST['record'])) {
	$focus->retrieve_entity_info($_REQUEST['record'],"Emails");
		$vtlog->logthis("Entity info successfully retrieved for DetailView.",'info');
	$focus->id = $_REQUEST['record'];
        $focus->name=$focus->column_fields['name'];		
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
} 

//needed when creating a new email with default values passed in 
if (isset($_REQUEST['contact_name']) && is_null($focus->contact_name)) {
	$focus->contact_name = $_REQUEST['contact_name'];
}
if (isset($_REQUEST['contact_id']) && is_null($focus->contact_id)) {
	$focus->contact_id = $_REQUEST['contact_id'];
}
if (isset($_REQUEST['opportunity_name']) && is_null($focus->parent_name)) {
	$focus->parent_name = $_REQUEST['opportunity_name'];
}
if (isset($_REQUEST['opportunity_id']) && is_null($focus->parent_id)) {
	$focus->parent_id = $_REQUEST['opportunity_id'];
}
if (isset($_REQUEST['account_name']) && is_null($focus->parent_name)) {
	$focus->parent_name = $_REQUEST['account_name'];
}
if (isset($_REQUEST['account_id']) && is_null($focus->parent_id)) {
	$focus->parent_id = $_REQUEST['account_id'];
}
if (isset($_REQUEST['parent_name'])) {
        $focus->parent_name = $_REQUEST['parent_name'];
}
if (isset($_REQUEST['parent_id'])) {
        $focus->parent_id = $_REQUEST['parent_id'];
}
if (isset($_REQUEST['parent_type'])) {
        $focus->parent_type = $_REQUEST['parent_type'];
}
if (isset($_REQUEST['filename']) && is_null($focus->filename)) {
        $focus->filename = $_REQUEST['filename'];
}
elseif (is_null($focus->parent_type)) {
        $focus->parent_type = $app_list_strings['record_type_default_key'];
}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Email detail view");

$submenu = array('LBL_EMAILS_TITLE'=>'index.php?module=Emails&action=ListView.php','LBL_WEBMAILS_TITLE'=>'index.php?module=squirrelmail-1.4.4&action=redirect');
$sec_arr = array('index.php?module=Emails&action=ListView.php'=>'Emails','index.php?module=squirrelmail-1.4.4&action=redirect'=>'Emails'); 
echo '<br>';
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
   <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
     <td class="tabStart">&nbsp;&nbsp;</td>
<?
	if(isset($_REQUEST['smodule']) && $_REQUEST['smodule'] != '')
	{
		$classname = "tabOff";
	}
	else
	{
		$classname = "tabOn";
	}
	$listView = "ListView.php";
	foreach($submenu as $label=>$filename)
	{
		$cur_mod = $sec_arr[$filename];
		$cur_tabid = getTabid($cur_mod);

		if($tab_per_Data[$cur_tabid] == 0)
		{

			list($lbl,$sname,$title)=split("_",$label);
			if(stristr($label,"EMAILS"))
			{

				echo '<td class="tabOn" nowrap><a href="index.php?module=Emails&action=ListView" class="tabLink">'.$mod_strings[$label].'</a></td>';

				$listView = $filename;
				$classname = "tabOff";
			}
			elseif(stristr($label,$_REQUEST['smodule']))
			{
				echo '<td class="tabOn" nowrap><a href="index.php?module=squirrelmail-1.4.4&action=redirect&smodule='.$_REQUEST['smodule'].'" class="tabLink">'.$mod_strings[$label].'</a></td>';	
				$listView = $filename;
				$classname = "tabOff";
			}
			else
			{
				echo '<td class="'.$classname.'" nowrap><a href="index.php?module=squirrelmail-1.4.4&action=redirect&smodule='.$sname.'" class="tabLink">'.$mod_strings[$label].'</a></td>';	
			}
			$classname = "tabOff";
		}

	}
?>
     <td width="100%" class="tabEnd">&nbsp;</td>
   </tr>
 </table></td>
 </tr>
 </table>
 <br>
<?


$xtpl=new XTemplate ('modules/Emails/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);

if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");

$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
//$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());

//get Email Information
$block_1 = getDetailBlockInformation("Emails",1,$focus->column_fields);
$xtpl->assign("BLOCK1", $block_1);
$block_2 = getDetailBlockInformation("Emails",2,$focus->column_fields);
$xtpl->assign("BLOCK2", $block_2);
$block_3 = getDetailBlockInformation("Emails",4,$focus->column_fields);
$xtpl->assign("BLOCK4", $block_3);

$vtlog->logthis("Detail Block Informations successfully retrieved.",'info');

$block_1_header = getBlockTableHeader("LBL_EMAIL_INFORMATION");
$xtpl->assign("BLOCK1_HEADER", $block_1_header);

$xtpl->assign("ID", $focus->id);

/*
$sql = "select * from email_attachments where parent_id ='".$_REQUEST['record'] ."'";
$value = $adb->query($sql);
$valueArray = $adb->fetch_array($value);
$filename= $valueArray["filename"];
$xtpl->assign("FILENAME",$filename);
*/
 
$permissionData = $_SESSION['action_permission_set'];
if(isPermitted("Emails",1,$_REQUEST['record']) == 'yes')
{
	$xtpl->assign("EDITBUTTON","<td><input title=\"$app_strings[LBL_EDIT_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_EDIT_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Emails'; this.form.return_action.value='DetailView'; this.form.return_id.value='".$_REQUEST['record']."'; this.form.action.value='EditView'\" type=\"submit\" name=\"Edit\" value=\"$app_strings[LBL_EDIT_BUTTON_LABEL]\"></td>");


	$xtpl->assign("DUPLICATEBUTTON","<td><input title=\"$app_strings[LBL_DUPLICATE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_DUPLICATE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Emails'; this.form.return_action.value='DetailView'; this.form.isDuplicate.value='true'; this.form.action.value='EditView'\" type=\"submit\" name=\"Duplicate\" value=\"$app_strings[LBL_DUPLICATE_BUTTON_LABEL]\"></td>");
}


if(isPermitted("Emails",2,$_REQUEST['record']) == 'yes')
{
	$xtpl->assign("DELETEBUTTON","<td><input title=\"$app_strings[LBL_DELETE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_DELETE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Emails'; this.form.return_action.value='ListView'; this.form.action.value='Delete'; return confirm('$app_strings[NTC_DELETE_CONFIRMATION]')\" type=\"submit\" name=\"Delete\" value=\"$app_strings[LBL_DELETE_BUTTON_LABEL]\"></td>");
}
 
$xtpl->parse("main");

$xtpl->out("main");

// Now get the list of invitees that match this one.

//Security check for related list
global $profile_id;
$tab_per_Data = getAllTabsPermission($profile_id);
$permissionData = $_SESSION['action_permission_set'];


//Constructing the Related Lists from here
getRelatedLists("Emails",$focus);

/*
if($tab_per_Data[4] == 0)
{
        if($permissionData[4][3] == 0)
        {
		$focus->get_contacts($focus->id);
	}
}

$focus->get_users($focus->id);
if($tab_per_Data[8] == 0)
{
        if($permissionData[8][3] == 0)
        {
		$focus->get_attachments($focus->id);
	}
}
*/
?>
