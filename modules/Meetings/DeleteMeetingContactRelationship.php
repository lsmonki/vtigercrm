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
 * $Header:  vtiger_crm/sugarcrm/modules/Meetings/DeleteMeetingContactRelationship.php,v 1.2 2005/01/08 07:44:16 jack Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('modules/Meetings/Meeting.php');

require_once('include/logging.php');
$log = LoggerManager::getLogger('meeting contact relationship delete');

$focus = new Meeting();

if(!isset($_REQUEST['contact_id']) || !isset($_REQUEST['meeting_id']))
	die("A record number must be specified to delete the contact to meeting relationship.");

$focus->mark_meeting_contact_relationship_deleted($_REQUEST['contact_id'],$_REQUEST['return_id']);

header("Location: index.php?module=".$_REQUEST['return_module']."&action=".$_REQUEST['return_action']."&record=".$_REQUEST['return_id']);
?>
