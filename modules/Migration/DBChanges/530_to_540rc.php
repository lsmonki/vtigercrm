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

$migrationlog->debug("\n\nDB Changes from 5.3.0 to 5.4.0RC -------- Ends \n\n");

?>