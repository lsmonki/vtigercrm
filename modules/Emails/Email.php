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
      // 'Contact Name'=>Array('contactdetails'=>'lastname'),
       'Related to'=>Array('seactivityrel'=>'activityid'),
       'Date Sent'=>Array('activity'=>'date_start'),
       'Assigned To'=>Array('crmentity','smownerid')
       );

       var $list_fields_name = Array(
       'Subject'=>'subject',
     //  'Contact Name'=>'lastname',
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

	function Email() {
		$this->log = LoggerManager::getLogger('email');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Emails');
	}

	var $new_schema = true;

	function create_tables () {

	}

	function drop_tables () {
	}


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
    //include_once('modules/Emails/RenderRelatedListUI.php');
    renderRelatedUsers($query);
  }
  
  function get_leads($id)
  {
    // First, get the list of IDs.
	$query = 'SELECT leaddetails.leadid, leaddetails.firstname,leaddetails.lastname, leaddetails.leadstatus, crmentity.modifiedtime from leaddetails inner join seactivityrel on seactivityrel.crmid=leaddetails.leadid inner join crmentity on crmentity.crmid = leaddetails.leadid where seactivityrel.activityid='.$id;
    //include_once('modules/Emails/RenderRelatedListUI.php');
    renderRelatedLeads($query);
  }

  function get_potentials($id)
  {
          // First, get the list of IDs.
	$query = 'SELECT potential.potentialid, potential.potentialname, potential.potentialtype, potential.amount, potential.closingdate, crmentity.modifiedtime from potential inner join seactivityrel on seactivityrel.crmid=potential.potentialid inner join crmentity on crmentity.crmid = potential.potentialid where seactivityrel.activityid='.$id;
          renderRelatedPotentials($query);
  }
  function get_attachments($id)
  {
		$query = "select notes.title,'Notes      '  ActivityType, notes.filename, attachments.type  FileType,crm2.modifiedtime  lastmodified, seattachmentsrel.attachmentsid attachmentsid, notes.notesid crmid from notes inner join senotesrel on senotesrel.notesid= notes.notesid inner join crmentity on crmentity.crmid= senotesrel.crmid inner join crmentity crm2 on crm2.crmid=notes.notesid and crm2.deleted=0 left join seattachmentsrel  on seattachmentsrel.crmid =notes.notesid left join attachments on seattachmentsrel.attachmentsid = attachments.attachmentsid where crmentity.crmid=".$id;
                $query .= ' union all ';
                $query .= "select attachments.description title ,'Attachments'  ActivityType, attachments.name  filename, attachments.type  FileType,crm2.modifiedtime  lastmodified, attachments.attachmentsid  attachmentsid, seattachmentsrel.attachmentsid crmid from attachments inner join seattachmentsrel on seattachmentsrel.attachmentsid= attachments.attachmentsid inner join crmentity on crmentity.crmid= seattachmentsrel.crmid inner join crmentity crm2 on crm2.crmid=attachments.attachmentsid where crmentity.crmid=".$id;

    renderRelatedAttachments($query,$id);
  }









	
	
	function save_relationship_changes($is_update)
    {
		if($this->account_id != "")
    	{
    		$this->set_emails_account_relationship($this->id, $this->account_id);    	
    	}
		if($this->opportunity_id != "")
    	{
    		$this->set_emails_opportunity_relationship($this->id, $this->opportunity_id);    	
    	}
		if($this->case_id != "")
    	{
    		$this->set_emails_case_relationship($this->id, $this->case_id);    	
    	}
		if($this->contact_id != "")
    	{
    		$this->set_emails_contact_invitee_relationship($this->id, $this->contact_id);
			$this->set_emails_se_invitee_relationship($this->id, $this->contact_id);    				
    	}
		if($this->user_id != "")
    	{
    		$this->set_emails_user_invitee_relationship($this->id, $this->user_id);    	
    	}
    }
	
	function set_emails_account_relationship($email_id, $account_id)
	{
		$query = "insert into $this->rel_accounts_table (id,account_id,email_id) values ('".create_guid()."', '$account_id', '$email_id')";
		$this->db->query($query,true,"Error setting email to account relationship: "."<BR>$query");
	}

	function set_emails_opportunity_relationship($email_id, $opportunity_id)
	{
		$query = "insert into $this->rel_opportunities_table (id, opportunity_id, email_id) values ('".create_guid()."','$opportunity_id','$email_id')";
		$this->db->query($query,true,"Error setting email to opportunity relationship: "."<BR>$query");
	}

	function set_emails_case_relationship($email_id, $case_id)
	{
		$query = "insert into $this->rel_cases_table (id,case_id,email_id) values ('".create_guid()."','$case_id','$email_id')";
		$this->db->query($query,true,"Error setting email to case relationship: "."<BR>$query");
	}

        function set_emails_contact_invitee_relationship($email_id, $contact_id)
        {
        //      $query = "insert into $this->rel_contacts_table (id,contact_id,email_id) values('".create_guid()."','$contact_id','$email_id')";
                $query = "insert into $this->rel_contacts_table (contactid,activityid) values('$contact_id','$email_id')";
                $this->db->query($query,true,"Error setting email to contact relationship: "."<BR>$query");
        }
		  
		  function set_emails_se_invitee_relationship($email_id, $contact_id)
        {
        //      $query = "insert into $this->rel_contacts_table (id,contact_id,email_id) values('".create_guid()."','$contact_id','$email_id')";
                $query = "insert into $this->rel_serel_table (crmid,activityid) values('$contact_id','$email_id')";
                $this->db->query($query,true,"Error setting email to contact relationship: "."<BR>$query");
        }

        function set_emails_user_invitee_relationship($email_id, $user_id)
        {
                //$query = "insert into $this->rel_users_table (id,user_id,email_id) values ('".create_guid()."', '$user_id', '$email_id')";
                $query = "insert into $this->rel_users_table (smid,activityid) values ('$user_id', '$email_id')";
                $this->db->query($query,true,"Error setting email to user relationship: "."<BR>$query");
        }

	function get_summary_text()
	{
		return "$this->name";
	}

	function create_list_query(&$order_by, &$where)
	{
		$contact_required = ereg("contacts", $where);

		if($contact_required)
		{
			$query = "SELECT emails.emailid,  emails.date_start, emails.time_start, contacts.first_name, contacts.last_name FROM contacts, emails, emails_contacts ";
			$where_auto = "emails_contacts.contact_id = contacts.id AND emails_contacts.email_id = emails.emailid AND emails.deleted=0 AND contacts.deleted=0";
		}
		else 
		{
                  $query="SELECT emails.emailid, emails.name, crmentity.smcreatorid FROM emails inner join crmentity on crmentity.crmid=emails.emailid ";
                  $where_auto = " emails.deleted=0 ";
                  
		}
		
		if($where != "")
			$query .= "where $where AND ".$where_auto;
		else 
			$query .= "where ".$where_auto;		

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else 
			$query .= " ORDER BY emails.name";			
		return $query;
	}

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



	function fill_in_additional_list_fields()
	{
		$this->fill_in_additional_detail_fields();	
	}
	
	function fill_in_additional_detail_fields()
	{
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);

		$query  = "SELECT c.firstname, c.lastname, c.phone, c.email, c.contactid FROM contactdetails c, seactivityrel s ";
		$query .= "WHERE s.crmid=c.contactid AND s.activityid='$this->id' AND c.deleted=0";
		$result =$this->db->query($query,true," Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);
		
		$this->log->info($row);
		
		if($row != null)
		{
			$this->contact_name = return_name($row, 'first_name', 'last_name');				
			$this->contact_phone = $row['phone_work'];
			$this->contact_id = $row['id'];
			$this->contact_email = $row['email1'];
			$this->log->debug("Call($this->id): contact_name = $this->contact_name");
			$this->log->debug("Call($this->id): contact_phone = $this->contact_phone");
			$this->log->debug("Call($this->id): contact_id = $this->contact_id");
			$this->log->debug("Call($this->id): contact_email1 = $this->contact_email");
		}
		else {
			$this->contact_name = '';
			$this->contact_phone = '';
			$this->contact_id = '';
			$this->contact_email = '';
			$this->log->debug("Call($this->id): contact_name = $this->contact_name");
			$this->log->debug("Call($this->id): contact_phone = $this->contact_phone");
			$this->log->debug("Call($this->id): contact_id = $this->contact_id");
			$this->log->debug("Call($this->id): contact_email1 = $this->contact_email");
		}

		if ($this->parent_type == "Opportunities") {
			require_once("modules/Opportunities/Opportunity.php");
			$parent = new Opportunity();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");
	
			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);
			
			if($row != null)
			{
				$this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Cases") {
			require_once("modules/Cases/Case.php");
			$parent = new aCase();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");
	
			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);
			
			if($row != null)
			{
				$this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Accounts") {
			require_once("modules/Accounts/Account.php");
			$parent = new Account();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");
	
			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);
			
			if($row != null)
			{
				$this->parent_name = stripslashes($row['name']);
			}
		}	
		else {
			$this->parent_name = '';
		}
	}
	
	function mark_relationships_deleted($id)
	{
		$query = "UPDATE $this->rel_users_table set deleted=1 where email_id='$id'";
		$this->db->query($query,true,"Error marking record deleted: ");

		$query = "UPDATE $this->rel_contacts_table set deleted=1 where email_id='$id'";
		$this->db->query($query,true,"Error marking record deleted: ");
		/*
		$query = "UPDATE $this->rel_cases_table set deleted=1 where email_id='$id'";
		$this->db->query($query,true,"Error marking record deleted: ");
		$query = "UPDATE $this->rel_accounts_table set deleted=1 where email_id='$id'";
		$this->db->query($query,true,"Error marking record deleted: ");
		$query = "UPDATE $this->rel_opportunities_table set deleted=1 where email_id='$id'";
		$this->db->query($query,true,"Error marking record deleted: ");
		*/
		
	}
	
	function mark_email_contact_relationship_deleted($contact_id, $email_id)
	{
		$query = "UPDATE $this->rel_contacts_table set deleted=1 where contact_id='$contact_id' and email_id='$email_id' and deleted=0";
		$this->db->query($query,true,"Error clearing email to contact relationship: ");
	}

	function mark_email_user_relationship_deleted($user_id, $email_id)
	{
		$query = "UPDATE $this->rel_users_table set deleted=1 where user_id='$user_id' and email_id='$email_id' and deleted=0";
		$this->db->query($query,true,"Error clearing email to user relationship: ");
	}
	function mark_email_case_relationship_deleted($id, $email_id)
	{
	/*
		$query = "UPDATE $this->rel_cases_table set deleted=1 where case_id='$id' and email_id='$email_id' and deleted=0";
		$this->db->query($query,true,"Error clearing email to user relationship: ");
	*/
	}
	function mark_email_account_relationship_deleted($id, $email_id)
	{
	/*
		$query = "UPDATE $this->rel_accounts_table set deleted=1 where account_id='$id' and email_id='$email_id' and deleted=0";
		$this->db->query($query,true,"Error clearing email to user relationship: ");
	*/
	}
	function mark_email_opportunity_relationship_deleted($id, $email_id)
	{
	/*
		$query = "UPDATE $this->rel_opportunities_table set deleted=1 where opportunity_id='$id' and email_id='$email_id' and deleted=0";
		$this->db->query($query,true,"Error clearing email to user relationship: ");
	*/
	}
	function get_list_view_data(){
		$email_fields = $this->get_list_view_array();
		global $app_list_strings;
		if (isset($this->parent_type) && $this->parent_type != "") 
			$email_fields['PARENT_MODULE'] = $this->parent_type;
		return $email_fields;
	}
	
	/** Returns a list of the associated opportunities
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	
	/** Returns a list of the associated accounts
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/


  function get_accounts($id)
  {
	$query = 'SELECT account.accountid, account.accountname, account.account_type, crmentity.modifiedtime from account inner join seactivityrel on seactivityrel.crmid=account.accountid inner join crmentity on crmentity.crmid=account.accountid and seactivityrel.activityid='.$id;
    renderRelatedAccounts($query);
  }
	
  
}
?>
