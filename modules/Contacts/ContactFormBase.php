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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Contacts/ContactFormBase.php,v 1.14 2005/05/03 13:18:53 saraj Exp $
 * Description:  Base form for contact
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 
class ContactFormBase  {
	
function checkForDuplicates($prefix){
	require_once('include/formbase.php');
	require_once('modules/Contacts/Contact.php');
	$focus = new Contact();
	if(!checkRequired($prefix, array_keys($focus->required_fields))){
		return null;
	}
	$query = '';
	$baseQuery = 'select id,first_name, last_name, title, email1, email2  from contacts where deleted!=1 and (';
	if(isset($_POST[$prefix.'first_name']) && !empty($_POST[$prefix.'first_name']) && isset($_POST[$prefix.'last_name']) && !empty($_POST[$prefix.'last_name'])){	
		$query = $baseQuery ."  (first_name='". PearDatabase::quote($_POST[$prefix.'first_name']) . "' and last_name = '". PearDatabase::quote($_POST[$prefix.'last_name']) ."')";
	}else{
			$query = $baseQuery ."  last_name = '". PearDatabase::quote($_POST[$prefix.'last_name']) ."'";
	}
	if(isset($_POST[$prefix.'email1']) && !empty($_POST[$prefix.'email1'])){	
		if(empty($query)){
		$query = $baseQuery. "  email1='". $_POST[$prefix.'email1'] . "' or email2 = '". $_POST[$prefix.'email1'] ."'";
		}else {
			$query .= "or email1='". PearDatabase::quote($_POST[$prefix.'email1']) . "' or email2 = '". PearDatabase::quote($_POST[$prefix.'email1']) ."'";		
		}
	}
	if(isset($_POST[$prefix.'email2']) && !empty($_POST[$prefix.'email2'])){
		if(empty($query))	{
			$query = $baseQuery. "  email1='".PearDatabase::quote( $_POST[$prefix.'email2']) . "' or email2 = '". PearDatabase::quote($_POST[$prefix.'email2']) ."'";
		}else{
			$query .= "or email1='". PearDatabase::quote($_POST[$prefix.'email2']) . "' or email2 = '". PearDatabase::quote($_POST[$prefix.'email2']) ."'";	
		}
	
	}
	
	if(!empty($query)){
		$rows = array();
		require_once('include/database/PearDatabase.php');
		$db = new PearDatabase();
		$result =& $db->query($query.');');
		if($db->getRowCount($result) == 0){
			return null;
		}
		for($i = 0; $i < $db->getRowCount($result); $i++){
			$rows[$i] = $db->fetchByAssoc($result, $i);	
		}
		return $rows;		
	}
	return null;
}


function buildTableForm($rows){
	if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
	}else global $mod_strings;
	global $app_strings;
	$cols = sizeof($rows[0]) * 2 + 1;
	$form = '<table width="100%"><tr><td>'.$mod_strings['MSG_DUPLICATE']. '</td></tr><tr><td height="20"></td></tr></table>';
	$form .= "<form action='index.php' method='post' name='dupContacts'><input type='hidden' name='selectedContact' value=''>";
	 $form .= get_form_header($mod_strings['LBL_DUPLICATE']," <input type='submit' class='button' name='ContinueContact' value='${app_strings['LBL_CREATE_BUTTON_LABEL']} ${mod_strings['LNK_NEW_CONTACT']}'>", '');
	$form .= "<table width='100%' cellpadding='0' cellspacing='0'>	<tr class='blackline'><td colspan='$cols' class='blackline'></td></tr><tr class='moduleListTitle'><td WIDTH='1' class='blackLine'></td>	<td class='moduleListTitle'>";
		
	
	require_once('include/formbase.php');
	$form .= getPostToForm();
	
	if(isset($rows[0])){
		foreach ($rows[0] as $key=>$value){
			if($key != 'id'){
					$form .= "<td WIDTH='1' class='blackLine'></td>";	
					
					$form .= "<td class='moduleListTitle'>". $mod_strings[$mod_strings['db_'.$key]]. "</td>";
			}
		}
		$form .= "<td WIDTH='1' class='blackLine'></td>";	
		$form .= "</tr>";
	}
	$form .= "<tr class='blackline'><td colspan='$cols' class='blackline'></td></tr>";
	$rowColor = 'oddListRow';
	foreach($rows as $row){
		
		
		$form .= "<tr class='$rowColor'>";
		$form .= "<td WIDTH='1' class='blackLine'></td>";	
		$form .= "<td width='1%' nowrap><a href='#' onClick=\"document.dupContacts.selectedContact.value='${row['id']}';document.dupContacts.submit() \">[${app_strings['LBL_SELECT_BUTTON_LABEL']}]</a>&nbsp;&nbsp;</td>";	
		$wasSet = false;
		
		foreach ($row as $key=>$value){
				if($key != 'id'){
					
					$form .= "<td WIDTH='1' class='blackLine'></td>";	
				
					$form .= "<td><a target='_blank' href='index.php?module=Contacts&action=DetailView&record=${row['id']}'>$value</a></td>";
		}
		}
		$form .= "<td WIDTH='1' class='blackLine'></td>";	
		if($rowColor == 'evenListRow'){
			$rowColor = 'oddListRow';	
		}else $rowColor = 'evenListRow';
		$form .= "</tr>";
	}
		$form .= "<tr class='blackline'><td colspan='$cols' class='blackline'></td></tr>";
	$form .= "</table></form>";
	return $form;
	
	
	
	
		
}
function getWideFormBody($prefix, $mod='', $contact = ''){
	require_once('modules/Contacts/Contact.php');
	if(empty($contact)){
		$contact = new Contact();	
	}
	if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
}else global $mod_strings;
		global $app_strings;
		global $current_user;
		$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
		$lbl_first_name = $mod_strings['LBL_FIRST_NAME'];
		$lbl_last_name = $mod_strings['LBL_LAST_NAME'];
		$lbl_phone = $mod_strings['LBL_OFFICE_PHONE'];
		$lbl_address =  $mod_strings['LBL_PRIMARY_ADDRESS'];
		$user_id = $current_user->id;
		$lbl_email_address = $mod_strings['LBL_EMAIL_ADDRESS'];
		$form = <<<EOQ
		<input type="hidden" name="${prefix}record" value="">
		<input type="hidden" name="${prefix}assigned_user_id" value='${user_id}'>
		<table class='evenListRow' border='0' width='100%'><tr><td nowrap cospan='1'>$lbl_first_name<br><input name="${prefix}first_name" type="text" value="{$contact->first_name}"></td><td colspan='1'><FONT class="required">$lbl_required_symbol</FONT>&nbsp;$lbl_last_name<br><input name='${prefix}last_name' type="text" value="{$contact->last_name}"></td></tr>
		<tr><td colspan='4'><hr></td></tr>
		<tr><td nowrap colspan='1'>${mod_strings['LBL_TITLE']}<br><input name='${prefix}title' type="text" value="{$contact->title}"></td><td nowrap colspan='1'>${mod_strings['LBL_DEPARTMENT']}<br><input name='${prefix}department' type="text" value="{$contact->department}"></td></tr>
		<tr><td colspan='4'><hr></td></tr>
		<tr><td nowrap colspan='4'>$lbl_address<br><input type='text' name='${prefix}primary_address_street' size='80' value='{$contact->primary_address_street}'</td></tr>
		<tr><td> ${mod_strings['LBL_CITY']}<BR><input name='${prefix}primary_address_city'  maxlength='100' value='{$contact->primary_address_city}'></td><td>${mod_strings['LBL_STATE']}<BR><input name='${prefix}primary_address_state'  maxlength='100' value='{$contact->primary_address_state}'></td><td>${mod_strings['LBL_POSTAL_CODE']}<BR><input name='${prefix}primary_address_postalcode'  maxlength='100' value='{$contact->primary_address_postalcode}'></td><td>${mod_strings['LBL_COUNTRY']}<BR><input name='${prefix}primary_address_country'  maxlength='100' value=''></td></tr>
		<tr><td colspan='4'><hr></td></tr>
		<tr><td nowrap >$lbl_phone<br><input name='${prefix}phone_work' type="text" value="{$contact->phone_work}"></td><td nowrap >${mod_strings['LBL_MOBILE_PHONE']}<br><input name='${prefix}phone_mobile' type="text" value="{$contact->phone_mobile}"></td><td nowrap >${mod_strings['LBL_FAX_PHONE']}<br><input name='${prefix}phone_fax' type="text" value="{$contact->phone_fax}"></td><td nowrap >${mod_strings['LBL_HOME_PHONE']}<br><input name='${prefix}phone_home' type="text" value="{$contact->phone_home}"></td></tr>
		<tr><td colspan='4'><hr></td></tr>
		<tr><td nowrap colspan='1'>$lbl_email_address<br><input name='${prefix}email1' type="text" value="{$contact->email1}"></td><td nowrap colspan='1'>${mod_strings['LBL_OTHER_EMAIL_ADDRESS']}<br><input name='${prefix}email2' type="text" value="{$contact->email2}"></td></tr>
		<tr><td nowrap colspan='4'>${mod_strings['LBL_DESCRIPTION']}<br><textarea cols='80' rows='4' name='${prefix}description' >{$contact->description}</textarea></td></tr></table>
		
EOQ;

return $form;
}
	
function getFormBody($prefix, $mod=''){
		if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
}else global $mod_strings;
		global $app_strings;
		global $current_user;
		$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
		$lbl_first_name = $mod_strings['LBL_FIRST_NAME'];
		$lbl_last_name = $mod_strings['LBL_LAST_NAME'];
		$lbl_phone = $mod_strings['LBL_PHONE'];
		$user_id = $current_user->id;
		$lbl_email_address = $mod_strings['LBL_EMAIL_ADDRESS'];
		$form = <<<EOQ
		<input type="hidden" name="${prefix}record" value="">
		<input type="hidden" name="${prefix}email2" value="">
		<input type="hidden" name="${prefix}assigned_user_id" value='${user_id}'>
		$lbl_first_name<br>
		<input name="${prefix}first_name" type="text" value=""><br>
		<FONT class="required">$lbl_required_symbol</FONT>$lbl_last_name<br>
		<input name='${prefix}last_name' type="text" value=""><br>
		$lbl_phone<br>
		<input name='${prefix}phone_work' type="text" value=""><br>
		$lbl_email_address<br>
		<input name='${prefix}email1' type="text" value=""><br><br>
		
EOQ;
return $form;
		
}
function getForm($prefix, $mod=''){
if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
}else global $mod_strings;
global $app_strings;

$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];


$the_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);
$the_form .= <<<EOQ

		<form name="${prefix}ContactSave" onSubmit="return verify_data(${prefix}ContactSave)" method="POST" action="index.php">
			<input type="hidden" name="${prefix}module" value="Contacts">
			<input type="hidden" name="${prefix}action" value="Save">
EOQ;
$the_form .= $this->getFormBody($prefix);
$the_form .= <<<EOQ
		<input title="$lbl_save_button_title" accessKey="$lbl_save_button_key" class="button" type="submit" name="${prefix}button" value="  $lbl_save_button_label  " >
		</form>

EOQ;
$the_form .= get_left_form_footer();
$the_form .= get_validate_record_js();

return $the_form;
	
	
}


function handleSave($prefix,$redirect=true, $useRequired=false){
	require_once('modules/Contacts/Contact.php');
	require_once('include/logging.php');
	require_once('include/formbase.php');

	$local_log =& LoggerManager::getLogger('index');

	$focus = new Contact();
	if($useRequired &&  !checkRequired($prefix, array_keys($focus->required_fields))){
		return null;
	}
	if (isset($_POST[$prefix.'new_reports_to_id'])) {
		$focus->retrieve($_POST[$prefix.'new_reports_to_id']);
		$focus->reports_to_id = $_POST[$prefix.'record']; 
	}
	else {
		$focus = populateFromPost($prefix, $focus);
		if (!isset($_POST[$prefix.'email_opt_out'])) $focus->email_opt_out = 'off';
		if (!isset($_POST[$prefix.'do_not_call'])) $focus->do_not_call = 'off';
	}
	$focus->save();
	$return_id = $focus->id;
	$local_log->debug("Saved record with id of ".$return_id);
	if($redirect){
		$this->handleRedirect($return_id);
	}else{
		return $focus;	
	}
}

function handleRedirect($return_id){
	if(isset($_POST['return_module']) && $_POST['return_module'] != "") $return_module = $_POST['return_module'];
	else $return_module = "Contacts";
	if(isset($_POST['return_action']) && $_POST['return_action'] != "") $return_action = $_POST['return_action'];
	else $return_action = "DetailView";
	if(isset($_POST['return_id']) && $_POST['return_id'] != "") $return_id = $_POST['return_id'];
	header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");	
		
}

}


?>