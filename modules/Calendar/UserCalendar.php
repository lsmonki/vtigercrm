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
 * $Header$
 * Description:  Defines the Account SugarBean Account entity with the necessary
 * methods and variables.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('data/CRMEntity.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Accounts/Account.php');
//require_once('modules/Tasks/Task.php');

// Account is used to store account information.
class UserCalendar extends CRMEntity {
	var $log;
	var $db;


	// Stored fields
	var $a_start;
	var $a_end;
	var $t_ignore;
	var $descr;
	var $outside;
	var $subject;
	var $date_entered;
	var $modified_user_id;
	var $creator;
	var $id;

	// These are for related fields
	var $account_name;
	var $contact_name;
	var $contact_id;
	var $task_id;
	var $assigned_user_name;
	
	var $table_name = "calendar";

	var $object_name = "Calendar";

	var $new_schema = true;

	var $column_fields = Array(
		"a_start",
		"a_end",
		"t_ignore",
		"descr",
		"outside",
		"subject",
		"date_entered",
		"contact_id",
		"creator",
		"id");

	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'descr', 'subject', 'user_name');

	// This is the list of fields that are required.
	var $required_fields =  array("name"=>1);

	function UserCalendar() {
		$this->log =LoggerManager::getLogger('calendar');
		$this->db = new PearDatabase();
	}

}

?>
