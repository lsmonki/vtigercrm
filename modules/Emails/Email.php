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

	var $table_name = "emails";
	var $tab_name = Array('crmentity','activity','emails','seactivityrel','cntactivityrel');
        var $tab_name_index = Array('crmentity'=>'crmid','activity'=>'activityid','emails'=>'emailid','seactivityrel'=>'activityid','cntactivityrel'=>'activityid');

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
	var $rel_cases_table = "emails_cases";
	var $rel_accounts_table = "emails_accounts";
	var $rel_opportunities_table = "emails_opportunities";
	var $rel_serel_table = "seactivityrel";

	var $object_name = "Email";

	var $column_fields = Array();

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
		// First, get the list of IDs.
		$query = 'select contactdetails.accountid, contactdetails.contactid, contactdetails.firstname,contactdetails.lastname, contactdetails.department, contactdetails.title, contactdetails.email, contactdetails.phone, contactdetails.emailoptout, crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime from contactdetails inner join seactivityrel on seactivityrel.crmid=contactdetails.contactid inner join crmentity on crmentity.crmid = contactdetails.contactid where seactivityrel.activityid='.$id.' and crmentity.deleted=0';
		renderRelatedContacts($query,$id);
	}
	
	/** Returns a list of the associated users
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_users($id)
	{
		// First, get the list of IDs.
		$query = 'SELECT users.id, users.first_name,users.last_name, users.user_name, users.email1, users.email2, users.yahoo_id, users.phone_home, users.phone_work, users.phone_mobile, users.phone_other, users.phone_fax from users inner join salesmanactivityrel on salesmanactivityrel.smid=users.id and salesmanactivityrel.activityid='.$id;
		renderRelatedUsers($query);
	}

	/**
	  * Returns a list of the associated attachments and notes of the Email
	  */
	function get_attachments($id)
	{
		// Armando Lüscher 18.10.2005 -> §visibleDescription
		// Desc: Inserted crm2.createdtime, notes.notecontent description, users.user_name
		// Inserted inner join users on crm2.smcreatorid= users.id
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
		where crmentity.crmid=".$id;
		$query .= ' union all ';
		// Armando Lüscher 18.10.2005 -> §visibleDescription
		// Desc: Inserted crm2.createdtime, attachments.description, users.user_name
		// Inserted inner join users on crm2.smcreatorid= users.id
		// Inserted order by createdtime desc
		$query .= "select attachments.description title ,'Attachments'  ActivityType,
			attachments.name filename, attachments.type FileType,crm2.modifiedtime lastmodified,
			attachments.attachmentsid  attachmentsid,	seattachmentsrel.attachmentsid crmid,
			crm2.createdtime, attachments.description, users.user_name
		from attachments
			inner join seattachmentsrel on seattachmentsrel.attachmentsid= attachments.attachmentsid
			inner join crmentity on crmentity.crmid= seattachmentsrel.crmid
			inner join crmentity crm2 on crm2.crmid=attachments.attachmentsid
			inner join users on crm2.smcreatorid= users.id
		where crmentity.crmid=".$id;

		renderRelatedAttachments($query,$id);
	}

        /**
          * Returns a list of the Emails to be export
          */
	function create_export_query(&$order_by, &$where)
        {
                $contact_required = ereg("contacts", $where);

                if($contact_required)
                {
			$query = 'SELECT emails.emailid,emails.filename,emails.description as email_content,activity.*,contactdetails.firstname, contactdetails.lastname FROM emails inner join crmentity on crmentity.crmid=emails.emailid inner join activity on activity.activityid=crmentity.crmid left join seactivityrel on seactivityrel.activityid = emails.emailid inner join contactdetails on contactdetails.contactid=seactivityrel.crmid where crmentity.deleted=0 ';
                }
                else
                {
			$query = 'SELECT emails.emailid,emails.filename,emails.description as email_content,activity.* FROM emails inner join crmentity on crmentity.crmid=emails.emailid inner join activity on activity.activityid=crmentity.crmid where crmentity.deleted=0 ';

                }

                return $query;
        }
}
?>
