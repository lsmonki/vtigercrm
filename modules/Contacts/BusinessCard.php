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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Contacts/BusinessCard.php,v 1.13 2005/06/28 14:30:22 mickie Exp $
 * Description:  Business Card Wizard
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 
global $vtlog;
global $app_strings;
global $app_list_strings;
require_once('XTemplate/xtpl.php');
global $theme;
$error_msg = '';
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
global $current_language;
$mod_strings = return_module_language($current_language, 'Contacts');

$xtpl=new XTemplate ('modules/Contacts/BusinessCard.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);

$xtpl->assign("HEADER", $mod_strings['LBL_ADD_BUSINESSCARD']);

$xtpl->assign("MODULE", $_REQUEST['module']);
if ($error_msg != '')
{
	$xtpl->assign("ERROR", $error_msg);
	$xtpl->parse("main.error");
}

if(isset($_POST['handle']) && $_POST['handle'] == 'Save'){
	require_once('modules/Contacts/Contact.php');
	require_once('modules/Contacts/ContactFormBase.php');
	$contactForm = new ContactFormBase();
	require_once('modules/Accounts/AccountFormBase.php');
	$accountForm = new AccountFormBase();
	if(!isset($_POST['selectedContact']) && !isset($_POST['ContinueContact'])){
		$duplicateContacts = $contactForm->checkForDuplicates('Contacts');
		$vtlog->logthis("Duplicate Contact Checking Finished.",'info');
		if(isset($duplicateContacts)){
			$xtpl->assign('FORMBODY', $contactForm->buildTableForm($duplicateContacts));
			$xtpl->parse('main.formnoborder');
			$xtpl->parse('main');
			$xtpl->out('main');
			return;
		}
	}
	if(!isset($_POST['selectedAccount']) && !isset($_POST['ContinueAccount'])){
		$duplicateAccounts = $accountForm->checkForDuplicates('Accounts');
		$vtlog->logthis("Duplicate Account Checking Finished.",'info');
		if(isset($duplicateAccounts)){
			$xtpl->assign('FORMBODY', $accountForm->buildTableForm($duplicateAccounts));
			$xtpl->parse('main.formnoborder');
			$xtpl->parse('main');
			$xtpl->out('main');
			return;
		}
	}
	if(isset($_POST['selectedContact']) && !empty($_POST['selectedContact'])){
		$contact = new Contact();
		$contact->retrieve($_POST['selectedContact']);
		$vtlog->logthis("Selected Contact Successfully Retrieved.",'info');	
	}else{
		$contact= $contactForm->handleSave('Contacts',false, true);
	}
	if(isset($_POST['selectedAccount']) && !empty($_POST['selectedAccount'])){
		$account = new Account();
		$account->retrieve($_POST['selectedAccount']);	
		$vtlog->logthis("Selected Account Successfully Retrieved.",'info');
	}else{
		$account= $accountForm->handleSave('Accounts',false, true);
	}
	require_once('modules/Notes/NoteFormBase.php');

	$noteForm = new NoteFormBase();
	if(isset($account))
		$accountnote= $noteForm->handleSave('AccountNotes',false, true);
	if(isset($contact))
		$contactnote= $noteForm->handleSave('ContactNotes',false, true);
	if(isset($_POST['appointment']) && $_POST['appointment'] == 'Meeting'){
		require_once('modules/Meetings/MeetingFormBase.php');
		$meetingForm = new MeetingFormBase();
		$meeting= $meetingForm->handleSave('Appointments',false, true);
	}else{
		require_once('modules/Calls/CallFormBase.php');
		$callForm = new CallFormBase();
		$call= $callForm->handleSave('Appointments',false, true);	
	}
	
	if(isset($call)){
		if(isset($contact))
			$call->set_calls_contact_invitee_relationship($call->id, $contact->id);
		if(isset($account)){
			$call->set_calls_account_relationship($call->id, $account->id);	
		}else if(isset($opportunity)){
			$call->set_calls_opportunity_relationship($call->id, $opportunity->id);	
		}
		
	}
	if(isset($meeting)){
		if(isset($contact))
			$meeting->set_meetings_contact_invitee_relationship($meeting->id, $contact->id);
		if(isset($account)){
			$meeting->set_meetings_account_relationship($meeting->id, $account->id);	
		}else if(isset($opportunity)){
			$meeting->set_meetings_opportunity_relationship($meeting->id, $opportunity->id);	
		}
	}
	if(isset($account)){
		if(isset($contact)){
			$account->set_account_contact_relationship($account->id, $contact->id);	
		}
		if(isset($opportunity)){
			$account->set_account_opportunity_relationship($account->id, $opportunity->id);	
		}
		if(isset($accountnote)){
			$account->set_account_note_relationship($account->id, $accountnote->id);
		}	
	}
	if(isset($opportunity)){
		if(isset($contact)){
			$opportunity->set_opportunity_contact_relationship($opportunity->id, $contact->id);	
		}
		if(isset($accountnote)){
			$opportunity->set_opportunity_note_relationship($opportunity->id, $accountnote->id);
		}	
	}
	if(isset($contact)){
		if(isset($contactnote)){
			$contact->set_note_contact_relationship($contact->id, $contactnote->id);	
		}
	}
	
	if(isset($contact)){
		if(isset($_POST['selectedContact']) && $_POST['selectedContact'] == $contact->id){
			$xtpl->assign('ROWVALUE', "<LI>".$mod_strings['LBL_EXISTING_CONTACT']." - <a href='index.php?action=DetailView&module=Contacts&record=".$contact->id."'>".$contact->first_name ." ".$contact->last_name."</a>" );
			$xtpl->parse('main.row');
		}else{
			
			$xtpl->assign('ROWVALUE', "<LI>".$mod_strings['LBL_CREATED_CONTACT']." - <a href='index.php?action=DetailView&module=Contacts&record=".$contact->id."'>".$contact->first_name ." ".$contact->last_name."</a>" );
			$xtpl->parse('main.row');
		}
	}
	if(isset($account)){
		if(isset($_POST['selectedAccount']) && $_POST['selectedAccount'] == $account->id){
			$xtpl->assign('ROWVALUE', "<LI>".$mod_strings['LBL_EXISTING_ACCOUNT']. " - <a href='index.php?action=DetailView&module=Accounts&record=".$account->id."'>".$account->name."</a>");
			$xtpl->parse('main.row');
		}else{
			$xtpl->assign('ROWVALUE', "<LI>".$mod_strings['LBL_CREATED_ACCOUNT']. " - <a href='index.php?action=DetailView&module=Accounts&record=".$account->id."'>".$account->name."</a>");		
			$xtpl->parse('main.row');
		}
		
	}
	if(isset($call)){
		$xtpl->assign('ROWVALUE', "<LI>".$mod_strings['LBL_CREATED_CALL']. " - <a href='index.php?action=DetailView&module=Calls&record=".$call->id."'>".$call->name."</a>");	
		$xtpl->parse('main.row');
		}
	if(isset($meeting)){
		$xtpl->assign('ROWVALUE', "<LI>".$mod_strings['LBL_CREATED_MEETING']. " - <a href='index.php?action=DetailView&module=Calls&record=".$meeting->id."'>".$meeting->name."</a>");	
		$xtpl->parse('main.row');
		}
		$xtpl->assign('ROWVALUE',"&nbsp;");	
		$xtpl->parse('main.row');
		$xtpl->assign('ROWVALUE',"<a href='index.php?module=Contacts&action=BusinessCard'>{$mod_strings['LBL_ADDMORE_BUSINESSCARD']}</a>");	
	$xtpl->parse('main.row');
	$xtpl->parse('main');
	$xtpl->out('main');	
}
	
else{


//CONTACT
$xtpl->assign('FORMHEADER',get_form_header($mod_strings['LNK_NEW_CONTACT'], '', ''));
$xtpl->parse("main.startform");
require_once('modules/Contacts/ContactFormBase.php');
$contactForm = new ContactFormBase();
$xtpl->assign('FORMBODY',$contactForm->getWideFormBody('Contacts', 'Contacts'));
$xtpl->assign('FORMFOOTER',get_form_footer());
$xtpl->assign('CLASS', 'evenListRow');
require_once('modules/Notes/NoteFormBase.php');
$noteForm = new NoteFormBase();
$postform = "<div id='contactnotelink'><a href='javascript:toggleDisplay(\"contactnote\");'>[${mod_strings['LNK_NEW_NOTE']}]</a></div>";
$postform .= '<div id="contactnote" style="display:none">'.$noteForm->getFormBody('ContactNotes', 'Notes', 85).'</div>';
$xtpl->assign('POSTFORM',$postform);
$xtpl->parse("main.form");



//Account
$xtpl->assign('FORMHEADER',get_form_header($mod_strings['LNK_NEW_ACCOUNT'], '', ''));
require_once('modules/Accounts/AccountFormBase.php');
$accountForm = new AccountFormBase();
$xtpl->assign('CLASS', 'oddListRow');
$xtpl->assign('FORMBODY',"<table width='100%'><tr><td valing='top'>".$accountForm->getFormBody('Accounts', 'Accounts')."</td><td>${mod_strings['LBL_DESCRIPTION']}<br><textarea name='Accountsdescription' cols='50' rows='5'></textarea></td></tr></table>");
$xtpl->assign('FORMFOOTER',get_form_footer());
require_once('modules/Notes/NoteFormBase.php');
$noteForm = new NoteFormBase();
$postform = "<div id='accountnotelink'><a href='javascript:toggleDisplay(\"accountnote\");'>[${mod_strings['LNK_NEW_NOTE']}]</a></div>";
$postform .= '<div id="accountnote" style="display:none">'.$noteForm->getFormBody('AccountNotes', 'Notes', 85).'</div>';
$xtpl->assign('POSTFORM',$postform);
$xtpl->parse("main.form");


//Appointment
$xtpl->assign('FORMHEADER', get_form_header($mod_strings['LNK_NEW_APPOINTMENT'], '', ''));
require_once('modules/Calls/CallFormBase.php');
$callForm = new CallFormBase();
$xtpl->assign('FORMBODY', "<table width='100%'><tr><td valign='top'><input type='radio' name='appointment' value='Call' checked> ${mod_strings['LNK_NEW_CALL']}<input type='radio' name='appointment' value='Meeting'> ${mod_strings['LNK_NEW_MEETING']}<br>".$callForm->getFormBody('Appointments', 'Calls')."</td><td>${mod_strings['LBL_DESCRIPTION']}<br><textarea name='Appointmentsdescription' cols='50' rows='5'></textarea></td></tr></table>");
$xtpl->assign('FORMFOOTER', get_form_footer());
$xtpl->assign('POSTFORM','');
$xtpl->parse("main.form");
$xtpl->parse("main.save");
$xtpl->parse("main.endform");
$xtpl->parse("main");

$xtpl->out("main");
}
?>
