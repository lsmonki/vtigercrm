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
 * Contributor(s): ______________________________________..
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Notes/DetailView.php,v 1.11 2005/03/17 11:21:40 ray Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('data/Tracker.php');
require_once('Smarty_setup.php');
require_once('modules/Notes/Note.php');
require_once('modules/Notes/Forms.php');
require_once('include/upload_file.php');
require_once('include/utils/utils.php');
global $app_strings;
global $mod_strings;

$focus = new Note();

if(isset($_REQUEST['record'])) {
   $focus->retrieve_entity_info($_REQUEST['record'],"Notes");
   $focus->id = $_REQUEST['record'];
   $focus->name=$focus->column_fields['title'];
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

//needed when creating a new note with default values passed in
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

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Note detail view");

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("BLOCKS", getBlocks("Notes","detail_view",'',$focus->column_fields));
$smarty->assign("UPDATEINFO",updateInfo($focus->id));


if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
else $smarty->assign("NAME", "");

if (isset($_REQUEST['return_module'])) $smarty->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $smarty->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $smarty->assign("RETURN_ID", $_REQUEST['return_id']);

$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$smarty->assign("ID", $focus->id);
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

if ( isset($focus->filename) && $focus->filename != '')
{
        $fileurl = "<a href=\"".UploadFile::get_url($focus->filename,$focus->id)."\" target=\"_blank\">". $focus->filename ."</a>";
	$smarty->assign("FILELINK", $fileurl);
}

$smarty->assign("SINGLE_MOD","Note");

$permissionData = $_SESSION['action_permission_set'];
if(isPermitted("Notes",1,$_REQUEST['record']) == 'yes')
	$smarty->assign("EDIT_DUPLICATE","permitted");

if(isPermitted("Notes",2,$_REQUEST['record']) == 'yes')
	$smarty->assign("DELETE","permitted");


$smarty->assign("MODULE", $module);
$smarty->display("DetailView.tpl");

?>
