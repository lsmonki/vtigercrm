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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Emails/EditView.php,v 1.25 2005/04/18 10:37:49 samk Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Emails/Email.php');
require_once('modules/Emails/Forms.php');
require_once('include/uifromdbutil.php');
require_once('include/FormValidationUtil.php');

global $vtlog;
global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;
// Unimplemented until jscalendar language files are fixed
// global $current_language;
// global $default_language;
// global $cal_codes;


echo get_module_title("Emails", $mod_strings['LBL_MODULE_TITLE'], true); 
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

				echo '<td class="tabOn" nowrap><a href="index.php?module=Emails&action=ListView&smodule='.$_REQUEST['smodule'].'" class="tabLink">'.$mod_strings[$label].'</a></td>';

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




$focus = new Email();

if($_REQUEST['upload_error'] == true)
{
        echo '<br><b><font color="red"> The selected file has no data or a invalid file.</font></b><br>';
}

$message = substr($_REQUEST['message'],0,49);
if(isset($_REQUEST['message']) && $_REQUEST['message']== 'Language string failed to load: connect_host')
	echo '<h3><b><font color=red>'.$mod_strings['MESSAGE_CHECK_MAIL_SERVER_NAME'].'</font></b></h3>';
if(isset($_REQUEST['message']) && (($_REQUEST['message']=='Language string failed to load: recipients_failed') || ($message == 'Language string failed to load: recipients_failed')) )
	echo '<h3><b><font color=red>'.$mod_strings['MESSAGE_CHECK_MAIL_ID'].'</font></b></h3>';
if(@strstr($_REQUEST['message'],'Language string failed to load: from_failed'))
	echo '<h3><b><font color=red>Please check the "From" mail id.</font></b></h3>';

if(isset($_REQUEST['record'])) {
	$focus->id = $_REQUEST['record'];
	$focus->mode = 'edit';
	$focus->retrieve_entity_info($_REQUEST['record'],"Emails");
		$vtlog->logthis("Entity info successfully retrieved for EditView.",'info');
        $focus->name=$focus->column_fields['name'];		
}
//$old_id = '';
if(isset($_REQUEST['parent_id']) && $_REQUEST['parent_id'] != '')
{
        $focus->column_fields['parent_id'] = $_REQUEST['parent_id'];
	$focus->mode = '';
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true')
{
	$old_id = $_REQUEST['record'];
        if (! empty($focus->filename) )
        {
         $old_id = $focus->id;
        }
        $focus->id = "";
	$focus->mode = "";
}

//get Email Information
$block_1 = getBlockInformation("Emails",1,$focus->mode,$focus->column_fields);
$block_2 = getBlockInformation("Emails",2,$focus->mode,$focus->column_fields);
$block_3 = getBlockInformation("Emails",3,$focus->mode,$focus->column_fields);
$block_4 = getBlockInformation("Emails",4,$focus->mode,$focus->column_fields);

//needed when creating a new email with default values passed in
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
if (isset($_REQUEST['filename']) && $_REQUEST['isDuplicate'] != 'true') {
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

$xtpl=new XTemplate ('modules/Emails/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");

$xtpl->assign("BLOCK1", $block_1);
$xtpl->assign("BLOCK2", $block_2);
$xtpl->assign("BLOCK3", $block_3);
$xtpl->assign("BLOCK4", $block_4);
$block_1_header = getBlockTableHeader("LBL_EMAIL_INFORMATION");
$xtpl->assign("BLOCK1_HEADER", $block_1_header);

//Added to set the cc when click reply all
if(isset($_REQUEST['msg_cc']) && $_REQUEST['msg_cc'] != '')
{
	$xtpl->assign("MAIL_MSG_CC", $_REQUEST['msg_cc']);
}

if($focus->mode == 'edit')
{
        $xtpl->assign("MODE", $focus->mode);
}

// Unimplemented until jscalendar language files are fixed
// $xtpl->assign("CALENDAR_LANG", ((empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language]));

$xtpl->assign("CALENDAR_LANG", "en");$xtpl->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));

if(isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
else $xtpl->assign("RETURN_MODULE",'Emails');
if(isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
else $xtpl->assign("RETURN_ACTION",'index');
if(isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
//if(isset($_REQUEST['parent_id']) && $_REQUEST['parent_id'] != '')
//{
//	$xtpl->assign("PARENTID", $_REQUEST['parent_id']);
//}

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$xtpl->assign("ID", $focus->id);
$xtpl->assign("ENTITY_ID", $_REQUEST["record"]);
$xtpl->assign("ENTITY_TYPE",$_REQUEST["email_directing_module"]);
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
	$change_parent_button = "<input title='".$app_strings['LBL_CHANGE_BUTTON_TITLE']."' tabindex='2' accessKey='".$app_strings['LBL_CHANGE_BUTTON_KEY']."' type='button' class='button' value='".$app_strings['LBL_CHANGE_BUTTON_LABEL']."' name='button' LANGUAGE=javascript onclick='return window.open(\"index.php?module=\"+ document.EditView.parent_type.value + \"&action=Popup&html=Popup_picker&form=TasksEditView\",\"test\",\"width=600,height=400,resizable=1,scrollbars=1\");'>";
	$xtpl->assign("CHANGE_PARENT_BUTTON", $change_parent_button);
}

if ($focus->parent_type == "Account") $xtpl->assign("DEFAULT_SEARCH", "&query=true&account_id=$focus->parent_id&account_name=".urlencode($focus->parent_name));


 $email_tables = Array('emails','crmentity','activity'); 
 $tabid = getTabid("Emails");
 $validationData = getDBValidationData($email_tables,$tabid);
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
