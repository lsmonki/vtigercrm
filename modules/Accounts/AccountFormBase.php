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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Accounts/AccountFormBase.php,v 1.14 2005/05/03 13:18:42 saraj Exp $
 * Description:  base form for account
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 
class AccountFormBase{
	
	
function checkForDuplicates($prefix){
	require_once('include/formbase.php');
	require_once('modules/Accounts/Account.php');
	$focus = new Account();
	if(!checkRequired($prefix, array_keys($focus->required_fields))){
		return null;
	}
	$query = '';
	$baseQuery = 'select id, name, website, billing_address_city  from accounts where deleted!=1 and (';
	if(isset($_POST[$prefix.'name']) && !empty($_POST[$prefix.'name'])){
	$query = $baseQuery ."  name like ".PearDatabase::quote('%'.$_POST[$prefix.'name'].'%');	
        //$query = $baseQuery ."  name like '%".PearDatabase::quote($_POST[$prefix.'name'])."%'";
	}
	if(isset($_POST[$prefix.'website']) && !empty($_POST[$prefix.'website'])){	
		if(empty($query)){
                  //			$query = $baseQuery ."  website like '".PearDatabase::quote($_POST[$prefix.'website'])."%'";
	$query = $baseQuery ."  website like ".PearDatabase::quote($_POST[$prefix.'website'].'%');
		}else {
			$query .= "or website like '".$_POST[$prefix.'website']."%'";
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


function buildTableForm($rows, $mod='Accounts'){
	if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
	}else global $mod_strings;
	global $app_strings;
	$cols = sizeof($rows[0]) * 2 + 1;
	$form = '<table width="100%"><tr><td>'.$mod_strings['MSG_DUPLICATE']. '</td></tr><tr><td height="20"></td></tr></table>';
	
	$form .= "<form action='index.php' method='post' name='dupAccounts'><input type='hidden' name='selectedAccount' value=''>";
	$form .=  get_form_header($mod_strings['LBL_DUPLICATE'], "<input type='submit' class='button' name='ContinueAccount' value='${app_strings['LBL_CREATE_BUTTON_LABEL']} ${mod_strings['LNK_NEW_ACCOUNT']}'>", '');
	$form .= "<table width='100%' cellpadding='0' cellspacing='0'>	<tr class='blackline'><td colspan='$cols' class='blackline'></td></tr><tr class='moduleListTitle'><td WIDTH='1' class='blackLine'></td>	<td class='moduleListTitle'>";
	require_once('include/formbase.php');
	$form .= getPostToForm();
	if(isset($rows[0])){
		foreach ($rows[0] as $key=>$value){
			if($key != 'id'){
									$form .= "<td WIDTH='1' class='blackLine'></td>";	
					$form .= "<td class='moduleListTitle'>". $mod_strings[$mod_strings['db_'.$key]]. "</td>";
		}}
		$form .= "<td WIDTH='1' class='blackLine'></td>";
		$form .= "</tr>";
	}
		$form .= "<tr class='blackline'><td colspan='$cols' class='blackline'></td></tr>";
	$rowColor = 'oddListRow';
	foreach($rows as $row){
		
		$form .= "<tr class='$rowColor'>";
		$form .= "<td WIDTH='1' class='blackLine'></td>";	
		$form .= "<td width='1%' nowrap><a href='#' onclick='document.dupAccounts.selectedAccount.value=\"${row['id']}\";document.dupAccounts.submit();'>[${app_strings['LBL_SELECT_BUTTON_LABEL']}]</a>&nbsp;&nbsp;</td>";	
		foreach ($row as $key=>$value){
				if($key != 'id'){
								$form .= "<td WIDTH='1' class='blackLine'></td>";	
					$form .= "<td><a target='_blank' href='index.php?module=Accounts&action=DetailView&record=${row['id']}'>$value</a></td>";
		
				}}
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
		<form name="${prefix}AccountSave" onSubmit="return verify_data(${prefix}AccountSave)" method="POST" action="index.php">
			<input type="hidden" name="${prefix}module" value="Accounts">
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

function getFormBody($prefix, $mod=''){

if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
}else global $mod_strings;
global $app_strings;
global $current_user;

$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
$lbl_account_name = $mod_strings['LBL_ACCOUNT_NAME'];
$lbl_phone = $mod_strings['LBL_PHONE'];
$lbl_website = $mod_strings['LBL_WEBSITE'];
$user_id = $current_user->id;
	$form = <<<EOQ
		<input type="hidden" name="${prefix}record" value="">
		<input type="hidden" name="${prefix}email1" value="">
		<input type="hidden" name="${prefix}email2" value="">
		<input type="hidden" name="${prefix}assigned_user_id" value='${user_id}'>	
		<FONT class="required">$lbl_required_symbol</FONT>$lbl_account_name<br>
		<input name='${prefix}name' type="text" value=""><br>
		$lbl_phone<br>
		<input name='${prefix}phone_office' type="text" value=""><br>
		$lbl_website<br>
		http://<input name='${prefix}website' type="text" value=""><br>
EOQ;
	return $form;
}


function handleSave($prefix,$redirect=true, $useRequired=false){
	require_once('modules/Accounts/Account.php');
	require_once('include/logging.php');
	require_once('include/formbase.php');
	$local_log =& LoggerManager::getLogger('AccountSaveForm');
	$focus = new Account();
	if($useRequired &&  !checkRequired($prefix, array_keys($focus->required_fields))){
		return null;
	}
	$focus = populateFromPost($prefix, $focus);
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
	else $return_module = "Accounts";
	if(isset($_POST['return_action']) && $_POST['return_action'] != "") $return_action = $_POST['return_action'];
	else $return_action = "DetailView";
	if(isset($_POST['return_id']) && $_POST['return_id'] != "") $return_id = $_POST['return_id'];
	header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
	
}
}
?>