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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Emails/Email.php,v 1.41 2005/04/28 08:11:21 rank Exp $
 * Description:  TODO: To be written.
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
require_once('modules/Potentials/Opportunity.php');
require_once('modules/Users/User.php');

// Email is used to store customer information.
class Email extends CRMEntity {
	var $log;
	var $db;

	// Stored fields
	var $id;
	var $mode;

	var $emailid;
	var $description;
	var $name;
	var $date_start;
	var $time_start;
  	var $module_id="emailid";
	var $default_email_name_values = array('Assemble catalogs', 'Make travel arrangements', 'Send a letter', 'Send contract', 'Send fax', 'Send a follow-up letter', 'Send literature', 'Send proposal', 'Send quote');

	var $table_name = "activity";
	var $tab_name = Array('crmentity','activity','seactivityrel','cntactivityrel');
        var $tab_name_index = Array('crmentity'=>'crmid','activity'=>'activityid','seactivityrel'=>'activityid','cntactivityrel'=>'activityid');

	// This is the list of fields that are in the lists.
        var $list_fields = Array(
       'Subject'=>Array('activity'=>'subject'),
       'Related to'=>Array('seactivityrel'=>'activityid'),
       'Date Sent'=>Array('activity'=>'date_start'),
       'Assigned To'=>Array('crmentity','smownerid')
       );

       var $list_fields_name = Array(
       'Subject'=>'subject',
       'Related to'=>'activityid',
       'Date Sent'=>'date_start',
       'Assigned To'=>'assigned_user_id');

       var $list_link_field= 'subject';


	var $rel_users_table = "salesmanactivityrel";
	var $rel_contacts_table = "cntactivityrel";
	var $rel_serel_table = "seactivityrel";

	var $object_name = "Email";

	var $column_fields = Array();

	var $sortby_fields = Array('subject');

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'subject';
	var $default_sort_order = 'ASC';

	function create_tables () {

        }
	function Email() {
		$this->log = LoggerManager::getLogger('email');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Emails');
	}

	var $new_schema = true;

	/** Returns a list of the associated contacts
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_contacts($id)
	{
		//$query = 'select contactdetails.accountid, contactdetails.contactid, contactdetails.firstname,contactdetails.lastname, contactdetails.department, contactdetails.title, contactdetails.email, contactdetails.phone, contactdetails.emailoptout, crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime from contactdetails inner join seactivityrel on seactivityrel.crmid=contactdetails.contactid inner join crmentity on crmentity.crmid = contactdetails.contactid where seactivityrel.activityid='.$id.' and crmentity.deleted=0';
		//SQL injectiong given by Chris is added
		$query = 'select contactdetails.accountid, contactdetails.contactid, contactdetails.firstname,contactdetails.lastname, contactdetails.department, contactdetails.title, contactdetails.email, contactdetails.phone, contactdetails.emailoptout, crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime from contactdetails inner join seactivityrel on seactivityrel.crmid=contactdetails.contactid inner join crmentity on crmentity.crmid = contactdetails.contactid where seactivityrel.activityid='.PearDatabase::quote($id).' and crmentity.deleted=0';
	return renderRelatedContacts($query,$id);
	}
	
	/** Returns a list of the associated users
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_users($id)
	{
		//$query = 'SELECT users.id, users.first_name,users.last_name, users.user_name, users.email1, users.email2, users.yahoo_id, users.phone_home, users.phone_work, users.phone_mobile, users.phone_other, users.phone_fax from users inner join salesmanactivityrel on salesmanactivityrel.smid=users.id and salesmanactivityrel.activityid='.$id;
		$query = 'SELECT users.id, users.first_name,users.last_name, users.user_name, users.email1, users.email2, users.yahoo_id, users.phone_home, users.phone_work, users.phone_mobile, users.phone_other, users.phone_fax from users inner join salesmanactivityrel on salesmanactivityrel.smid=users.id and salesmanactivityrel.activityid='.PearDatabase::quote($id);
	return renderRelatedUsers($query);
	}

	/**
	  * Returns a list of the associated attachments and notes of the Email
	  */
	function get_attachments($id)
	{
		$query = "select notes.title,'Notes      '  ActivityType, notes.filename,
			attachments.type  FileType,crm2.modifiedtime lastmodified,
			seattachmentsrel.attachmentsid attachmentsid, notes.notesid crmid,
			crm2.createdtime, notes.notecontent description, users.user_name
		from notes
			inner join senotesrel on senotesrel.notesid= notes.notesid
			inner join crmentity on crmentity.crmid= senotesrel.crmid
			inner join crmentity crm2 on crm2.crmid=notes.notesid and crm2.deleted=0
			left join seattachmentsrel  on seattachmentsrel.crmid =notes.notesid
			left join attachments on seattachmentsrel.attachmentsid = attachments.attachmentsid
			inner join users on crm2.smcreatorid= users.id
		where crmentity.crmid=".PearDatabase::quote($id);
		//where crmentity.crmid=".$id;
		$query .= ' union all ';
		$query .= "select attachments.description title ,'Attachments'  ActivityType,
			attachments.name filename, attachments.type FileType,crm2.modifiedtime lastmodified,
			attachments.attachmentsid  attachmentsid,	seattachmentsrel.attachmentsid crmid,
			crm2.createdtime, attachments.description, users.user_name
		from attachments
			inner join seattachmentsrel on seattachmentsrel.attachmentsid= attachments.attachmentsid
			inner join crmentity on crmentity.crmid= seattachmentsrel.crmid
			inner join crmentity crm2 on crm2.crmid=attachments.attachmentsid
			inner join users on crm2.smcreatorid= users.id
		where crmentity.crmid=".PearDatabase::quote($id);
		//where crmentity.crmid=".$id;

	return	renderRelatedAttachments($query,$id);
	}

        /**
          * Returns a list of the Emails to be exported
          */
	function create_export_query(&$order_by, &$where)
        {
		$query = 'SELECT activity.activityid, activity.subject, activity.activitytype, attachments.name as filename, crmentity.description as email_content FROM activity inner join crmentity on crmentity.crmid=activity.activityid left join seattachmentsrel on activity.activityid=seattachmentsrel.crmid left join attachments on seattachmentsrel.attachmentsid = attachments.attachmentsid where activity.activitytype="Emails" and crmentity.deleted=0';

                return $query;
        }
}
?>
