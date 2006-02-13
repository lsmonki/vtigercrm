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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Contacts/SubPanelViewContactsAndUsers.php,v 1.16 2005/05/03 13:18:53 saraj Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('include/ListView/ListView.php');

class SubPanelViewContactsAndUsers{
	
var $hideUsers = false;
var $hideContacts = false;
var $contacts_list = null;
var $users_list = null;
var $hideNewButton = false;
var $focus;

function setFocus(&$value){
	$this->focus =(object) $value;		
}


function setContactsList(&$value){
	$this->contacts_list =$value;		
}

function setUsersList(&$value){
	$this->users_list =$value;		
}

function setHideUsers($value){
	$this->hideUsers = $value;	
}
function setHideContacts($value){
	$this->hideContacts = $value;	
}
function setHideNewButton($value){
	$this->hideNewButton = $value;	
}

function SubPanelViewContactsAndUsers(){
	global $theme;
	$theme_path="themes/".$theme."/";
	require_once($theme_path.'layout_utils.php');
}

function getHeaderText($action, $currentModule){
	global $app_strings;
	global $mod_strings;
	$button  = "<table cellspacing='0' cellpadding='1' border='0'><form border='0' action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<input type='hidden' name='module' value='Contacts'>\n";
if ($currentModule == 'Contacts') {
	if (isset($this->focus->primary_address_street)) $button .= "<input type='hidden' name='primary_address_street' value='".$this->focus->primary_address_street."'>\n";
	if (isset($this->focus->primary_address_city)) $button .= "<input type='hidden' name='primary_address_city' value='".$this->focus->primary_address_city."'>\n";
	if (isset($this->focus->primary_address_state)) $button .= "<input type='hidden' name='primary_address_state' value='".$this->focus->primary_address_state."'>\n";
	if (isset($this->focus->primary_address_country)) $button .= "<input type='hidden' name='primary_address_country' value='".$this->focus->primary_address_country."'>\n";
	if (isset($this->focus->primary_address_postalcode)) $button .= "<input type='hidden' name='primary_address_postalcode' value='".$this->focus->primary_address_postalcode."'>\n";
	$button .= "<input type='hidden' name='reports_to_id'>\n";
	$button .= "<input type='hidden' name='reports_to_name'>\n";
	$button .= "<input type='hidden' name='account_id'>\n";
	$button .= "<input type='hidden' name='account_name'>\n";

	
}
elseif ($currentModule == 'Accounts') {
	$button .= "<input type='hidden' name='account_id' value='".$this->focus->id."'>\n";
        $button .= "<input type='hidden' name='account_name' value='".$this->focus->name."'>\n";
	if (isset($this->focus->billing_address_street)) $button .= "<input type='hidden' name='primary_address_street' value='".$this->focus->billing_address_street."'>\n";
	if (isset($this->focus->billing_address_city)) $button .= "<input type='hidden' name='primary_address_city' value='".$this->focus->billing_address_city."'>\n";
	if (isset($this->focus->billing_address_state)) $button .= "<input type='hidden' name='primary_address_state' value='".$this->focus->billing_address_state."'>\n";
	if (isset($this->focus->billing_address_country)) $button .= "<input type='hidden' name='primary_address_country' value='".$this->focus->billing_address_country."'>\n";
	if (isset($this->focus->billing_address_postalcode)) $button .= "<input type='hidden' name='primary_address_postalcode' value='".$this->focus->billing_address_postalcode."'>\n";
	
}
elseif ($currentModule == 'Opportunities') $button .= "<input type='hidden' name='opportunity_id' value='".$this->focus->id."'>\n<input type='hidden' name='account_id' value='".$this->focus->account_id."'>\n<input type='hidden' name='account_name' value='".$this->focus->account_name."'>\n";

	//if ($currentModule == 'Accounts') $button .= "<input type='hidden' name='account_id' value='".$this->focus->id."'>\n<input type='hidden' name='account_name' value='".$this->focus->name."'>\n";
	//if ($currentModule == 'Cases') $button .= "<input type='hidden' name='case_id' value='".$this->focus->id."'>\n<input type='hidden' name='account_id' value='".$this->focus->account_id."'>\n<input type='hidden' name='account_name' value='".$this->focus->account_name."'>\n";
//elseif ($currentModule == 'Opportunities') $button .= "<input type='hidden' name='opportunity_id' value='".$this->focus->id."'>\n<input type='hidden' name='account_id' value='".$this->focus->account_id."'>\n<input type='hidden' name='account_name' value='".$this->focus->account_name."'>\n";
	$button .= "<input type='hidden' name='return_module' value='".$currentModule."'>\n";
	$button .= "<input type='hidden' name='return_action' value='".$action."'>\n";
	$button .= "<input type='hidden' name='return_id' value='".$this->focus->id."'>\n";
	$button .= "<input type='hidden' name='action'>\n";
	$button .= "<tr><td>&nbsp;</td>";
	if(!$this->hideNewButton){
		if($currentModule == 'Contacts')
			$button .= "<td><input title='".$app_strings['LBL_NEW_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_NEW_BUTTON_KEY']."' class='button' onclick=\"this.form.action.value='EditView';this.form.account_id.value='".$this->focus->account_id."';this.form.account_name.value='".$this->focus->account_name."'; this.form.reports_to_id.value='".$this->focus->id."';this.form.reports_to_name.value='".$this->focus->first_name." ".$this->focus->last_name."'\" type='submit' name='button' value='  ".$app_strings['LBL_NEW_BUTTON_LABEL']."  '></td>\n";
		elseif($currentModule == 'Accounts')
			$button .= "<td><input title='".$app_strings['LBL_NEW_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_NEW_BUTTON_KEY']."' class='button' onclick=\"this.form.action.value='EditView'\" type='submit' name='button' value='  ".$app_strings['LBL_NEW_BUTTON_LABEL']."  '></td>\n";
		else$button .= "<td><input title='".$app_strings['LBL_NEW_BUTTON_TITLE']."' accessyKey='".$app_strings['LBL_NEW_BUTTON_KEY']."' class='button' onclick=\"this.form.action.value='EditView'\" type='submit' name='button' value='  ".$app_strings['LBL_NEW_BUTTON_LABEL']."  '></td>\n";
	}
	if(!$this->hideUsers){
		
		if($currentModule=='Emails')
		{
                      $button .= "<td><input title='".$mod_strings['LBL_BULK_MAILS']."' class='button' value='".$mod_strings['LBL_BULK_MAILS']."' name='button' onclick=\"this.form.module.value='Emails';this.form.action.value='sendmail';this.form.return_module.value='Emails';this.form.return_action.value='ListView';this.form.record.value='".$this->focus->id."'\" type='submit''></td>\n";
		}

		$button .= "<td><input title='".$app_strings['LBL_SELECT_USER_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_SELECT_USER_BUTTON_KEY']."' type='button' class='button' value='".$app_strings['LBL_SELECT_USER_BUTTON_LABEL']."' name='button' LANGUAGE=javascript onclick='window.open(\"index.php?module=Users&action=Popup&html=Popup_picker&form=DetailView&form_submit=true\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\");'></td>\n";
	}
	if(!$this->hideContacts)
	{
		$selectName = 'LBL_SELECT_BUTTON';
		if(!$this->hideUsers)
		{
			$selectName = 'LBL_SELECT_CONTACT_BUTTON';
		}
		
		if (isset($this->focus->parent_type) && $this->focus->parent_type == "Accounts") 
			$button .= "<td><input title='".$app_strings[$selectName.'_TITLE']."' accessyKey='".$app_strings[$selectName.'_KEY']."' type='button' class='button' value='".$app_strings[$selectName.'_LABEL']."' name='button' LANGUAGE=javascript onclick='window.open(\"index.php?module=Contacts&action=Popup&html=Popup_picker&form=DetailView&form_submit=true&query=true&account_id=".$this->focus->parent_id."&account_name=".urlencode($this->focus->parent_name)."\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\");'></td>\n";

		elseif($currentModule == 'Contacts') 
			$button.= "<td><input title='".$app_strings[$selectName.'_TITLE']."' accessKey='".$app_strings[$selectName.'_KEY']."' type='button' class='button' value=' ".$app_strings[$selectName.'_LABEL']." ' name='button' LANGUAGE=javascript onclick='window.open(\"index.php?module=Contacts&action=Popup&html=Popup_picker&form=ContactDetailView&form_submit=true&query=true&account_id=".$this->focus->account_id."&account_name=".urlencode($this->focus->account_name)."\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\");'></td>\n";

		else if(isset($this->focus->account_id) && isset($this->focus->account_name))
			 $button .= "<td><input title='".$app_strings[$selectName.'_TITLE']."' accessyKey='".$app_strings[$selectName.'_KEY']."' type='button' class='button' value='  ".$app_strings[$selectName.'_LABEL']."  ' name='button' LANGUAGE=javascript onclick='window.open(\"index.php?module=Contacts&action=Popup&html=Popup_picker&form=DetailView&form_submit=true&query=true&account_id=".$this->focus->account_id."&account_name=".urlencode($this->focus->account_name)."\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\");'></td>\n";

		else
			$button .= "<td><input title='".$app_strings[$selectName.'_TITLE']."' accessyKey='".$app_strings[$selectName.'_KEY']."' type='button' class='button' value='  ".$app_strings[$selectName.'_LABEL']."  ' name='button' LANGUAGE=javascript onclick='window.open(\"index.php?module=Contacts&action=Popup&html=Popup_picker&form=DetailView&form_submit=true&query=true\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\");'></td>\n";
	}

	$button .= "</tr></form></table>\n";
	return $button;
}

function ProcessSubPanelListView($xTemplatePath, &$mod_strings,$action, $curModule=''){
	global $currentModule;
	if(empty($curModule))
		$curModule = $currentModule;
	$ListView = new ListView();
	$ListView->initNewXTemplate($xTemplatePath,$mod_strings);
	$ListView->xTemplateAssign("RETURN_URL", "&return_module=".$curModule."&return_action=DetailView&return_id=".$this->focus->id);
	$ListView->xTemplateAssign("RECORD_ID",  $this->focus->id);
	$ListView->setHeaderTitle($mod_strings['LBL_INVITEE']);
	$ListView->setHeaderText($this->getHeaderText($action, $curModule));
	if(!$this->hideUsers){
		if(!isset($this->users_list))
			$this->setUsersList($this->focus->get_users());
		$ListView->processListView($this->users_list, "users", "USER");
	}
	if(!$this->hideContacts){

		if(!$this->hideUsers){
			$ListView->setDisplayHeaderAndFooter(false);	
		}
		if(!isset($this->contacts_list)){
			$this->setContactsList($this->focus->get_contacts());
		}
		
		
		$ListView->processListView($this->contacts_list, "contacts", "CONTACT");
	}
}
	



}
?>
