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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Notes/EditView.php,v 1.13 2005/04/18 10:37:49 samk Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Notes/Note.php');
require_once('modules/Notes/Forms.php');
require_once('include/uifromdbutil.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;

$focus = new Note();

if($_REQUEST['upload_error'] == true)
{
	echo '<br><b><font color="red"> The selected file has no data or a invalid file.</font></b><br>';
}

if(isset($_REQUEST['record'])) 
{
	$focus->id = $_REQUEST['record'];
	$focus->mode = 'edit';
	$focus->retrieve_entity_info($_REQUEST['record'],"Notes");
        $focus->name=$focus->column_fields['title'];
}
if(isset($_REQUEST['parent_id']))
{
        $focus->column_fields['parent_id'] = $_REQUEST['parent_id'];
}
//$old_id = '';
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
	$old_id = $_REQUEST['record'];
	if (! empty($focus->filename) )
	{	
	 $old_id = $focus->id;
	}
	$focus->id = "";
	$focus->mode = '';
}



//setting default flag value so due date and time not required
if (!isset($focus->id)) $focus->date_due_flag = 'on';

//needed when creating a new case with default values passed in
if (isset($_REQUEST['contact_name']) && is_null($focus->contact_name)) {
	$focus->contact_name = $_REQUEST['contact_name'];
}
if (isset($_REQUEST['contact_id']) && is_null($focus->contact_id)) {
	$focus->contact_id = $_REQUEST['contact_id'];
}
if (isset($_REQUEST['parent_name']) && is_null($focus->parent_name)) {
	$focus->parent_name = $_REQUEST['parent_name'];
}
if (isset($_REQUEST['parent_id']) && is_null($focus->parent_id)) {
	$focus->parent_id = $_REQUEST['parent_id'];
}
if (isset($_REQUEST['parent_type'])) {
	$focus->parent_type = $_REQUEST['parent_type'];
}
elseif (!isset($focus->parent_type)) {
	$focus->parent_type = $app_list_strings['record_type_default_key'];
}

if (isset($_REQUEST['filename']) && $_REQUEST['isDuplicate'] != 'true') {
        $focus->filename = $_REQUEST['filename'];
}

//get Block 1 Information
$block_1 = getBlockInformation("Notes",1,$focus->mode,$focus->column_fields);

//get Address Information
$block_2 = getBlockInformation("Notes",2,$focus->mode,$focus->column_fields);

//get Description Information
$block_3 = getBlockInformation("Notes",3,$focus->mode,$focus->column_fields);

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Note detail view");

$xtpl=new XTemplate ('modules/Notes/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("BLOCK1", $block_1);
$xtpl->assign("BLOCK2", $block_2);
$xtpl->assign("BLOCK3", $block_3);
$block_1_header = getBlockTableHeader("LBL_NOTE_INFORMATION");
$xtpl->assign("BLOCK1_HEADER", $block_1_header);

if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");

if($focus->mode == 'edit')
{
        $xtpl->assign("MODE", $focus->mode);
}

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
else $xtpl->assign("RETURN_MODULE","Notes");
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
else $xtpl->assign("RETURN_ACTION","index");
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
if (isset($_REQUEST['email_id'])) $xtpl->assign("EMAILID", $_REQUEST['email_id']);
if (isset($_REQUEST['ticket_id'])) $xtpl->assign("TICKETID", $_REQUEST['ticket_id']);
if (isset($_REQUEST['fileid'])) $xtpl->assign("FILEID", $_REQUEST['fileid']);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$xtpl->assign("ID", $focus->id);
$xtpl->assign("OLD_ID", $old_id );

if ( empty($focus->filename))
{
	$xtpl->assign("FILENAME_TEXT", "");
	$xtpl->assign("FILENAME", "");
}
else
{
	$xtpl->assign("FILENAME_TEXT", "(".$focus->filename.")");
	$xtpl->assign("FILENAME", $focus->filename);
}

if (isset($focus->parent_type) && $focus->parent_type != "") {
	$change_parent_button = "<input title='".$app_strings['LBL_CHANGE_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CHANGE_BUTTON_KEY']."' tabindex='3' type='button' class='button' value='".$app_strings['LBL_CHANGE_BUTTON_LABEL']."' name='button' LANGUAGE=javascript onclick='return window.open(\"index.php?module=\"+ document.EditView.parent_type.value + \"&action=Popup&html=Popup_picker&form=TasksEditView\",\"test\",\"width=600,height=400,resizable=1,scrollbars=1\");'>";
	$xtpl->assign("CHANGE_PARENT_BUTTON", $change_parent_button);
}
if ($focus->parent_type == "Account") $xtpl->assign("DEFAULT_SEARCH", "&query=true&account_id=$focus->parent_id&account_name=".urlencode($focus->parent_name));

$xtpl->parse("main");

$xtpl->out("main");

?>
