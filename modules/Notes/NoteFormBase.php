<?PHP
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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Notes/NoteFormBase.php,v 1.14 2005/05/03 13:18:56 saraj Exp $
 * Description:  Base Form For Notes
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/


class NoteFormBase{

function getFormBody($prefix, $mod='', $size='30'){
			if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
}else global $mod_strings;
			global $app_strings;
			global $app_list_strings;

		$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
		$lbl_note_subject = $mod_strings['LBL_NOTE_SUBJECT'];
		$lbl_note_description = $mod_strings['LBL_NOTE'];
		$default_parent_type= $app_list_strings['record_type_default_key'];

			$form = <<<EOF
				<input type="hidden" name="${prefix}record" value="">
				<input type="hidden" name="${prefix}parent_type" value="${default_parent_type}">

				<FONT class="required">$lbl_required_symbol</FONT>$lbl_note_subject<br>
				<input name='${prefix}name' size='${size}' maxlength='255' type="text" value=""><br>
				$lbl_note_description<br><textarea name='${prefix}description' cols='${size}' rows='4' ></textarea><br>

EOF;
return $form;
}

function getForm($prefix, $mod=''){
	if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
}else global $mod_strings;
	global $app_strings;
	global $app_list_strings;

	$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
	$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
	$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];


	$the_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);
	$the_form .= <<<EOQ

			<form name="${prefix}NoteSave" onSubmit="return verify_data(${prefix}NoteSave)" method="POST" action="index.php">
				<input type="hidden" name="${prefix}module" value="Notes">
				<input type="hidden" name="${prefix}action" value="Save">
EOQ;
	$the_form .= $this->getFormBody($prefix);
	$the_form .= <<<EOQ
			<input title="$lbl_save_button_title" accessKey="$lbl_save_button_key" class="button" type="submit" name="button" value="  $lbl_save_button_label  " >
			</form>

EOQ;

	$the_form .= get_left_form_footer();
	$the_form .= get_validate_record_js();


	return $the_form;
}


function handleSave($prefix,$redirect=true, $useRequired=false){
	require_once('modules/Notes/Note.php');
	require_once('include/logging.php');
	require_once('include/formbase.php');
	global $upload_maxsize, $upload_dir;

	$local_log =& LoggerManager::getLogger('NoteSaveForm');
	$focus = new Note();
	if($useRequired &&  !checkRequired($prefix, array_keys($focus->required_fields))){
		return null;
	}
	$focus = populateFromPost($prefix, $focus);

	if (!isset($_REQUEST['date_due_flag'])) $focus->date_due_flag = 'off';

	$upload_file = new UploadFile('uploadfile');

	if ($upload_file->confirm_upload())
	{

       		 if (!empty($focus->id) && !empty($_REQUEST['old_filename']) )
       		 { 
			$upload_file->unlink_file($focus->id,$_REQUEST['old_filename']); 
		}

	       	 $focus->filename = $upload_file->get_stored_file_name();

	}
	else
	{
		$focus->filename = $_REQUEST['old_filename'];
	}


	if (!isset($_POST['date_due_flag'])) $focus->date_due_flag = 'off';

	$focus->save();

	$upload_file->final_move($focus->id);

	$return_id = $focus->id;

	$local_log->debug("Saved record with id of ".$return_id);
	if($redirect){
		handleRedirect($return_id, "Notes");
	}else{
		return $focus;
	}
}








}
?>
