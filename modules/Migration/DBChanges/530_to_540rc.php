<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *********************************************************************************/

require_once 'include/utils/utils.php';
require_once 'modules/com_vtiger_workflow/include.inc';
require_once 'modules/com_vtiger_workflow/tasks/VTEntityMethodTask.inc';
require_once 'modules/com_vtiger_workflow/VTEntityMethodManager.inc';
require_once 'include/events/include.inc';
include_once 'vtlib/Vtiger/Cron.php';

//5.2.1 to 5.3.0RC database changes

$adb = $_SESSION['adodb_current_object'];
$conn = $_SESSION['adodb_current_object'];

global $migrationlog;

$migrationlog->debug("\n\nDB Changes from 5.3.0 to 5.4.0RC -------- Starts \n\n");

$moduleInstance = Vtiger_Module::getInstance('Home');
$moduleInstance->addLink(
		'HEADERSCRIPT',
		'Help Me',
		'modules/Home/js/HelpMeNow.js'
);

$documentsTabId = getTabid('Documents');
$adb->pquery("UPDATE vtiger_blocks SET sequence = ? WHERE blocklabel = ? AND tabid = ? ", array(2, 'LBL_FILE_INFORMATION', $documentsTabId));
$adb->pquery("UPDATE vtiger_blocks SET sequence = ? WHERE blocklabel = ? AND tabid = ?", array(3, 'LBL_DESCRIPTION', $documentsTabId));

// Adding 'from_portal' field to Trouble tickets module, to track the tickets created from customer portal
$moduleInstance = Vtiger_Module::getInstance('HelpDesk');
$block = Vtiger_Block::getInstance('LBL_TICKET_INFORMATION', $moduleInstance);

$field = new Vtiger_Field();
$field->name = 'from_portal';
$field->label = 'From Portal';
$field->table ='vtiger_troubletickets';
$field->column = 'from_portal';
$field->columntype = 'varchar(3)';
$field->typeofdata = 'C~O';
$field->uitype = 56;
$field->displaytype = 3;
$field->presence = 0;
$block->addField($field);

// Register Entity Methods
$emm = new VTEntityMethodManager($adb);

// Register Entity Method for Customer Portal Login details email notification task
$emm->addEntityMethod("Contacts","SendPortalLoginDetails","modules/Contacts/ContactsHandler.php","Contacts_sendCustomerPortalLoginDetails");

// Register Entity Method for Email notification on ticket creation from Customer portal
$emm->addEntityMethod("HelpDesk","NotifyOnPortalTicketCreation","modules/HelpDesk/HelpDeskHandler.php","HelpDesk_nofifyOnPortalTicketCreation");

// Register Entity Method for Email notification on ticket comment from Customer portal
$emm->addEntityMethod("HelpDesk","NotifyOnPortalTicketComment","modules/HelpDesk/HelpDeskHandler.php","HelpDesk_notifyOnPortalTicketComment");

// Register Entity Method for Email notification to Record Owner on ticket change, which is not from Customer portal
$emm->addEntityMethod("HelpDesk","NotifyOwnerOnTicketChange","modules/HelpDesk/HelpDeskHandler.php","HelpDesk_notifyOwnerOnTicketChange");

// Register Entity Method for Email notification to Related Customer on ticket change, which is not from Customer portal
$emm->addEntityMethod("HelpDesk","NotifyParentOnTicketChange","modules/HelpDesk/HelpDeskHandler.php","HelpDesk_notifyParentOnTicketChange");

// Creating Default workflows
$workflowManager = new VTWorkflowManager($adb);
$taskManager = new VTTaskManager($adb);

// Contact workflow on creation/modification
$contactWorkFlow = $workflowManager->newWorkFlow("Contacts");
$contactWorkFlow->test = '';
$contactWorkFlow->description = "Workflow for Contact Creation or Modification";
$contactWorkFlow->executionCondition = VTWorkflowManager::$ON_EVERY_SAVE;
$contactWorkFlow->defaultworkflow = 1;
$workflowManager->save($contactWorkFlow);

$task = $taskManager->createTask('VTEntityMethodTask', $contactWorkFlow->id);
$task->active = true;
$task->summary = 'Email Customer Portal Login Details';
$task->methodName = "SendPortalLoginDetails";
$taskManager->saveTask($task);

// Trouble Tickets workflow on creation from Customer Portal
$helpDeskWorkflow = $workflowManager->newWorkFlow("HelpDesk");
$helpDeskWorkflow->test = '[{"fieldname":"from_portal","operation":"is","value":"true:boolean"}]';
$helpDeskWorkflow->description = "Workflow for Ticket Created from Portal";
$helpDeskWorkflow->executionCondition = VTWorkflowManager::$ON_FIRST_SAVE;
$helpDeskWorkflow->defaultworkflow = 1;
$workflowManager->save($helpDeskWorkflow);

$task = $taskManager->createTask('VTEntityMethodTask', $helpDeskWorkflow->id);
$task->active = true;
$task->summary = 'Notify Record Owner and the Related Contact when Ticket is created from Portal';
$task->methodName = "NotifyOnPortalTicketCreation";
$taskManager->saveTask($task);

// Trouble Tickets workflow on ticket update from Customer Portal
$helpDeskWorkflow = $workflowManager->newWorkFlow("HelpDesk");
$helpDeskWorkflow->test = '[{"fieldname":"from_portal","operation":"is","value":"true:boolean"}]';
$helpDeskWorkflow->description = "Workflow for Ticket Updated from Portal";
$helpDeskWorkflow->executionCondition = VTWorkflowManager::$ON_MODIFY;
$helpDeskWorkflow->defaultworkflow = 1;
$workflowManager->save($helpDeskWorkflow);

$task = $taskManager->createTask('VTEntityMethodTask', $helpDeskWorkflow->id);
$task->active = true;
$task->summary = 'Notify Record Owner when Comment is added to a Ticket from Customer Portal';
$task->methodName = "NotifyOnPortalTicketComment";
$taskManager->saveTask($task);

// Trouble Tickets workflow on ticket change, which is not from Customer Portal - Both Record Owner and Related Customer
$helpDeskWorkflow = $workflowManager->newWorkFlow("HelpDesk");
$helpDeskWorkflow->test = '[{"fieldname":"from_portal","operation":"is","value":"false:boolean"}]';
$helpDeskWorkflow->description = "Workflow for Ticket Change, not from the Portal";
$helpDeskWorkflow->executionCondition = VTWorkflowManager::$ON_EVERY_SAVE;
$helpDeskWorkflow->defaultworkflow = 1;
$workflowManager->save($helpDeskWorkflow);

$task = $taskManager->createTask('VTEntityMethodTask', $helpDeskWorkflow->id);
$task->active = true;
$task->summary = 'Notify Record Owner on Ticket Change, which is not done from Portal';
$task->methodName = "NotifyOwnerOnTicketChange";
$taskManager->saveTask($task);

$task = $taskManager->createTask('VTEntityMethodTask', $helpDeskWorkflow->id);
$task->active = true;
$task->summary = 'Notify Related Customer on Ticket Change, which is not done from Portal';
$task->methodName = "NotifyParentOnTicketChange";
$taskManager->saveTask($task);

// Events workflow when Send Notification is checked
$eventsWorkflow = $workflowManager->newWorkFlow("Events");
$eventsWorkflow->test = '[{"fieldname":"sendnotification","operation":"is","value":"true:boolean"}]';
$eventsWorkflow->description = "Workflow for Events when Send Notification is True";
$eventsWorkflow->executionCondition = VTWorkflowManager::$ON_EVERY_SAVE;
$eventsWorkflow->defaultworkflow = 1;
$workflowManager->save($eventsWorkflow);

$task = $taskManager->createTask('VTEmailTask', $eventsWorkflow->id);
$task->active = true;
$task->summary = 'Send Notification Email to Record Owner';
$task->recepient = "\$(assigned_user_id : (Users) email1)";
$task->subject = "Event :  \$subject";
$task->content = '$(assigned_user_id : (Users) last_name) $(assigned_user_id : (Users) first_name) ,<br/>'
				.'<b>Activity Notification Details:</b><br/>'
				.'Subject             : $subject<br/>'
				.'Start date and time : $date_start  $time_start ( $(general : (__VtigerMeta__) dbtimezone) ) <br/>'
				.'End date and time   : $due_date  $time_end ( $(general : (__VtigerMeta__) dbtimezone) ) <br/>'
				.'Status              : $eventstatus <br/>'
				.'Priority            : $taskpriority <br/>'
				.'Related To          : $(parent_id : (Leads) lastname) $(parent_id : (Leads) firstname) $(parent_id : (Accounts) accountname) '
				.'$(parent_id         : (Potentials) potentialname) $(parent_id : (HelpDesk) ticket_title) <br/>'
				.'Contacts List       : $(contact_id : (Contacts) lastname) $(contact_id : (Contacts) firstname) <br/>'
				.'Location            : $location <br/>'
				.'Description         : $description';
$taskManager->saveTask($task);

// Calendar workflow when Send Notification is checked
$calendarWorkflow = $workflowManager->newWorkFlow("Calendar");
$calendarWorkflow->test = '[{"fieldname":"sendnotification","operation":"is","value":"true:boolean"}]';
$calendarWorkflow->description = "Workflow for Calendar Todos when Send Notification is True";
$calendarWorkflow->executionCondition = VTWorkflowManager::$ON_EVERY_SAVE;
$calendarWorkflow->defaultworkflow = 1;
$workflowManager->save($calendarWorkflow);

$task = $taskManager->createTask('VTEmailTask', $calendarWorkflow->id);
$task->active = true;
$task->summary = 'Send Notification Email to Record Owner';
$task->recepient = "\$(assigned_user_id : (Users) email1)";
$task->subject = "Task :  \$subject";
$task->content = '$(assigned_user_id : (Users) last_name) $(assigned_user_id : (Users) first_name) ,<br/>'
				.'<b>Task Notification Details:</b><br/>'
				.'Subject : $subject<br/>'
				.'Start date and time : $date_start  $time_start ( $(general : (__VtigerMeta__) dbtimezone) ) <br/>'
				.'End date and time   : $due_date ( $(general : (__VtigerMeta__) dbtimezone) ) <br/>'
				.'Status              : $taskstatus <br/>'
				.'Priority            : $taskpriority <br/>'
				.'Related To          : $(parent_id : (Leads) lastname) $(parent_id : (Leads) firstname) $(parent_id : (Accounts) accountname) '
				.'$(parent_id         : (Potentials) potentialname) $(parent_id : (HelpDesk) ticket_title) <br/>'
				.'Contacts List       : $(contact_id : (Contacts) lastname) $(contact_id : (Contacts) firstname) <br/>'
				.'Location            : $location <br/>'
				.'Description         : $description';
$taskManager->saveTask($task);

$adb->pquery("UPDATE com_vtiger_workflows SET defaultworkflow=1 WHERE
			module_name='Invoice' and summary='UpdateInventoryProducts On Every Save'", array());

$em = new VTEventsManager($adb);
// Registering event for HelpDesk - To reset from_portal value
$em->registerHandler('vtiger.entity.aftersave.final', 'modules/HelpDesk/HelpDeskHandler.php', 'HelpDeskHandler');

Vtiger_Cron::register( 'Workflow', 'cron/modules/com_vtiger_workflow/com_vtiger_workflow.service', 900, 'com_vtiger_workflow', '', '', 'Recommended frequency for Workflow is 15 mins');
Vtiger_Cron::register( 'RecurringInvoice', 'cron/modules/SalesOrder/RecurringInvoice.service', 43200, 'SalesOrder', '', '', 'Recommended frequency for RecurringInvoice is 12 hours');
Vtiger_Cron::register( 'SendReminder', 'cron/SendReminder.service', 900, 'Calendar', '', '', 'Recommended frequency for SendReminder is 15 mins');
Vtiger_Cron::register( 'ScheduleReports', 'cron/modules/Reports/ScheduleReports.service', 900, 'Reports', '', '', 'Recommended frequency for ScheduleReports is 15 mins');
Vtiger_Cron::register( 'MailScanner', 'cron/MailScanner.service', 900, 'Settings', '', '', 'Recommended frequency for MailScanner is 15 mins');

$adb->pquery("DELETE FROM vtiger_settings_field WHERE name='LBL_ASSIGN_MODULE_OWNERS'", array());

$adb->query("alter table vtiger_tab add parent varchar(30)");
$adb->query("update vtiger_tab set parent = 'Sales' where name = 'Accounts'");
$adb->query("update vtiger_tab set parent = 'Tools' where name = 'Calendar'");
$adb->query("update vtiger_tab set parent = 'Sales' where name = 'Contacts'");
$adb->query("update vtiger_tab set parent = 'Analytics' where name = 'Dashboard'");
$adb->query("update vtiger_tab set parent = 'Sales' where name = 'Leads'");
$adb->query("update vtiger_tab set parent = 'Sales' where name = 'Potentials'");
$adb->query("update vtiger_tab set parent = 'Inventory' where name = 'Vendors'");
$adb->query("update vtiger_tab set parent = 'Inventory' where name = 'Products'");
$adb->query("update vtiger_tab set parent = 'Tools' where name = 'Documents'");
$adb->query("update vtiger_tab set parent = 'Tools' where name = 'Emails'");
$adb->query("update vtiger_tab set parent = 'Support' where name = 'HelpDesk'");
$adb->query("update vtiger_tab set parent = 'Support' where name = 'Faq'");
$adb->query("update vtiger_tab set tabsequence = -1 where name = 'Faq'");
$adb->query("update vtiger_tab set parent = 'Inventory' where name = 'PriceBooks'");
$adb->query("update vtiger_tab set tabsequence = -1 where name = 'PriceBooks'");
$adb->query("update vtiger_tab set parent = 'Sales' where name = 'SalesOrder'");
$adb->query("update vtiger_tab set tabsequence = -1 where name = 'SalesOrder'");
$adb->query("update vtiger_tab set parent = 'Sales' where name = 'Quotes'");
$adb->query("update vtiger_tab set tabsequence = -1 where name = 'Quotes'");
$adb->query("update vtiger_tab set parent = 'Inventory' where name = 'PurchaseOrder'");
$adb->query("update vtiger_tab set tabsequence = -1 where name = 'PurchaseOrder'");
$adb->query("update vtiger_tab set parent = 'Sales' where name = 'Invoice'");
$adb->query("update vtiger_tab set tabsequence = -1 where name = 'Invoice'");
$adb->query("update vtiger_tab set parent = 'Tools' where name = 'RSS'");
$adb->query("update vtiger_tab set tabsequence = -1 where name = 'RSS'");
$adb->query("update vtiger_tab set parent = 'Analytics' where name = 'Reports'");
$adb->query("update vtiger_tab set tabsequence = -1 where name = 'Reports'");
$adb->query("update vtiger_tab set parent = 'Marketing' where name = 'Campaigns'");
$adb->query("update vtiger_tab set tabsequence = -1 where name = 'Campaigns'");
$adb->query("update vtiger_tab set parent = 'Tools' where name = 'Portal'");
$adb->query("update vtiger_tab set tabsequence = -1 where name = 'Portal'");
$adb->query("update vtiger_tab set parent = 'Support' where name = 'ServiceContracts'");
$adb->query("update vtiger_tab set tabsequence = -1 where name = 'ServiceContracts'");
$adb->query("update vtiger_tab set parent = 'Tools' where name = 'PBX Manager'");
$adb->query("update vtiger_tab set tabsequence = -1 where name = 'PBX Manager'");
$adb->query("update vtiger_tab set parent = 'Inventory' where name = 'Services'");
$adb->query("update vtiger_tab set tabsequence = -1 where name = 'Services'");
$adb->query("update vtiger_tab set parent = 'Tools' where name = 'RecycleBin'");
$adb->query("update vtiger_tab set tabsequence = -1 where name = 'RecycleBin'");
$adb->query("update vtiger_tab set parent = 'Support' where name = 'Assets'");
$adb->query("update vtiger_tab set tabsequence = -1 where name = 'Assets'");
$adb->query("update vtiger_tab set parent = 'Tools' where name = 'ModComments'");
$adb->query("update vtiger_tab set tabsequence = -1 where name = 'ModComments'");
$adb->query("update vtiger_tab set parent = 'Support' where name = 'ProjectMilestone'");
$adb->query("update vtiger_tab set tabsequence = -1 where name = 'ProjectMilestone'");
$adb->query("update vtiger_tab set parent = 'Support' where name = 'ProjectTask'");
$adb->query("update vtiger_tab set tabsequence = -1 where name = 'ProjectTask'");
$adb->query("update vtiger_tab set parent = 'Support' where name = 'Project'");
$adb->query("update vtiger_tab set tabsequence = -1 where name = 'Project'");
$adb->query("update vtiger_tab set parent = 'Tools' where name = 'SMSNotifier'");
$adb->query("update vtiger_tab set tabsequence = -1 where name = 'SMSNotifier'");
$adb->query("update vtiger_tab set parent = 'Tools' where name = 'MailManager'");
$adb->query("update vtiger_tab set tabsequence = -1 where name = 'MailManager'");

$fieldId = $adb->getUniqueId("vtiger_settings_field");
$adb->query("insert into vtiger_settings_field (fieldid,blockid,name,iconpath,description,linkto,sequence,active)
					values ($fieldId,".	getSettingsBlockId('LBL_STUDIO') .",'LBL_MENU_EDITOR','menueditor.png','LBL_MENU_DESC',
					'index.php?module=Settings&action=MenuEditor&parenttab=Settings',4,0)");

$present_module = array();
$result = $adb->query('select tabid,name,tablabel,tabsequence,parent from vtiger_tab where parent is not null and parent!=" "');
for ($i = 0; $i < $adb->num_rows($result); $i++) {
	$modulename = $adb->query_result($result, $i, 'name');
	$modulelabel = $adb->query_result($result, $i, 'tablabel');
	array_push($present_module, $modulelabel);
}
$result = $adb->query("select name,tablabel,parenttab_label,vtiger_tab.tabid
							from vtiger_parenttabrel
							inner join vtiger_tab on vtiger_parenttabrel.tabid = vtiger_tab.tabid
							inner join vtiger_parenttab on vtiger_parenttabrel.parenttabid = vtiger_parenttab.parenttabid
									and vtiger_parenttab.parenttab_label is not null
									and vtiger_parenttab.parenttab_label != ' '");

$skipModules = array("Webmails", "Home");
for ($i = 0; $i < $adb->num_rows($result); $i++) {
	$modulename = $adb->query_result($result, $i, 'name');
	$modulelabel = $adb->query_result($result, $i, 'tablabel');
	$parent = $adb->query_result($result, $i, 'parenttab_label');
	if ((!(in_array($modulelabel, $present_module))) && (!(in_array($modulelabel, $skipModules)))) {
		if ($modulelabel == "MailManager") {
			$adb->pquery("update vtiger_tab set parent = ? where tablabel = ?", array("Tools", $modulelabel));
			$adb->pquery("update vtiger_tab set tabsequence = -1 where tablabel = ?", array($modulelabel));
		} else {
			$adb->pquery("update vtiger_tab set parent = ? where tablabel = ?", array($parent, $modulelabel));
		}
	}
}

$query = "INSERT INTO vtiger_customerportal_prefs (
			SELECT tabid, 'defaultassignee', prefvalue FROM vtiger_customerportal_prefs WHERE prefkey='userid'
		)";
$adb->pquery($query, array());

$migrationlog->debug("\n\nDB Changes from 5.3.0 to 5.4.0RC -------- Ends \n\n");

?>