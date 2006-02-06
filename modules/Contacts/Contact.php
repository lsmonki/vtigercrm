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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Contacts/Contact.php,v 1.70 2005/04/27 11:21:49 rank Exp $
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
require_once('include/utils/utils.php');
require_once('modules/Potentials/Opportunity.php');
require_once('modules/Activities/Activity.php');
require_once('modules/Notes/Note.php');
require_once('modules/Emails/Email.php');
require_once('modules/HelpDesk/HelpDesk.php');


// Contact is used to store customer information.
class Contact extends CRMEntity {
	var $log;
	var $db;

	// Stored fields
	var $id;
	var $mode;

	var $contactid;
	var $leadsource;
	var $description;
	var $salutation;	
	var $firstname;
	var $lastname;
	var $title;
	var $department;
	var $birthdate;
	var $reportsto;
	var $do_not_call;
	var $phone_home;
	var $phone_mobile;
	var $phone_work;
	var $phone_other;
	var $phone_fax;
	var $email1;
	var $email2;
	var $yahoo_id;
	var $assistant;
	var $assistant_phone;
	var $email_opt_out;

	// These are for related fields
	var $accountname;
	var $accountid;
	var $campaignid;
	var $reports_to_name;
	var $opportunity_role;
	var $opportunity_rel_id;
	var $opportunity_id;
	var $case_role;
	var $case_rel_id;
	var $case_id;
	var $task_id;
	var $note_id;
	var $meeting_id;
	var $call_id;
	var $email_id;
	var $assigned_user_name;
		
	var $table_name = "contactdetails";
	var $tab_name = Array('crmentity','contactdetails','contactaddress','contactsubdetails','contactscf','customerdetails');
	var $tab_name_index = Array('crmentity'=>'crmid','contactdetails'=>'contactid','contactaddress'=>'contactaddressid','contactsubdetails'=>'contactsubscriptionid','contactscf'=>'contactid','customerdetails'=>'customerid');


	var $rel_account_table = "accounts_contacts";
	//This is needed for upgrade.  This table definition moved to Opportunity module.
	var $rel_opportunity_table = "opportunities_contacts";

	var $module_id = "contactid";
	var $object_name = "Contact";
	
	var $new_schema = true;

	var $column_fields = Array();
	
	var $sortby_fields = Array('lastname','firstname','title','email','phone','smownerid');

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'account_name', 'account_id', 'opportunity_id', 'case_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id');		
	
	// This is the list of fields that are in the lists.
	var $list_fields = Array(
	'Last Name' => Array('contactdetails'=>'lastname'),
	'First Name' => Array('contactdetails'=>'firstname'),
	'Title' => Array('contactdetails'=>'title'),
	'Account Name' => Array('account'=>'accountname'),
	'Email' => Array('contactdetails'=>'email'),
	'Phone' => Array('contactdetails'=>'phone'),
	'Assigned To' => Array('crmentity'=>'smownerid')
	);
	
	var $range_fields = Array(
	'first_name',
	'last_name',
	'primary_address_city',
	'account_name',
	'account_id',
	'id',
	'email1',
	'salutation',
	'title',
	'phone_mobile',
	'reports_to_name',
	'primary_address_street',
	'primary_address_city',
	'primary_address_state',
	'primary_address_postalcode',
	'primary_address_country',
	'alt_address_city',
	'alt_address_street',
	'alt_address_city',
	'alt_address_state',
	'alt_address_postalcode',
	'alt_address_country',

    'office_phone',
    'home_phone',
    'other_phone',
    'fax',
    'department',
    'birthdate',
    'assistant_name',
    'assistant_phone'

	
	);

	var $list_fields_name = Array(
	'Last Name' => 'lastname',
	'First Name' => 'firstname',
	'Title' => 'title',
	'Account Name' => 'accountid',
	'Email' => 'email',
	'Phone' => 'phone',
	'Assigned To' => 'assigned_user_id'
	);

	
	var $list_link_field= 'lastname';

	var $record_id;
	var $list_mode;
        var $popup_type;

	var $search_fields = Array(
	'Name' => Array('contactdetails'=>'lastname'),
	'Title' => Array('contactdetails'=>'title')
		);
	
	var $search_fields_name = Array(
	'Name' => 'lastname',
	'Title' => 'title'
	);

	// This is the list of fields that are required
	var $required_fields =  array("lastname"=>1);

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'lastname';
	var $default_sort_order = 'ASC';

	function Contact() {
		$this->log = LoggerManager::getLogger('contact');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Contacts');
	}

	function create_tables () {
	
	}

	function drop_tables () {
	}
	
	function delete($id)
        {
          $this->db->query('update crmentity set deleted=1 where crmid = \'' .$contactid . '\'');
        }
    
    function getCount($user_name) 
    {
        $query = "select count(*) from contactdetails  inner join crmentity on crmentity.crmid=contactdetails.contactid inner join users on users.id=crmentity.smownerid where user_name='" .$user_name ."' and crmentity.deleted=0";

        $result = $this->db->query($query,true,"Error retrieving contacts count");
        $rows_found =  $this->db->getRowCount($result);
        $row = $this->db->fetchByAssoc($result, 0);

    
            return $row["count(*)"];
    }       

        function get_contacts1($user_name,$email_address)
    {   
      $query = "select contactdetails.lastname last_name,contactdetails.firstname first_name,contactdetails.contactid as id, contactdetails.salutation as salutation, contactdetails.email as email1,contactdetails.title as title,contactdetails.mobile as phone_mobile,account.accountname as account_name,account.accountid as account_id   from contactdetails inner join crmentity on crmentity.crmid=contactdetails.contactid inner join users on users.id=crmentity.smownerid  left join account on account.accountid=contactdetails.accountid left join contactaddress on contactaddress.contactaddressid=contactdetails.contactid where user_name='" .$user_name ."' and crmentity.deleted=0  and contactdetails.email like '%" .$email_address ."%' limit 50";
      return $this->process_list_query1($query);
    }

    function get_contacts($user_name,$from_index,$offset)
    {   
      $query = "select contactdetails.department department, contactdetails.phone office_phone, contactdetails.fax fax, contactsubdetails.assistant assistant_name, contactsubdetails.otherphone other_phone, contactsubdetails.homephone home_phone,contactsubdetails.birthday birthdate, contactdetails.lastname last_name,contactdetails.firstname first_name,contactdetails.contactid as id, contactdetails.salutation as salutation, contactdetails.email as email1,contactdetails.title as title,contactdetails.mobile as phone_mobile,account.accountname as account_name,account.accountid as account_id, contactaddress.mailingcity as primary_address_city,contactaddress.mailingstreet as primary_address_street, contactaddress.mailingcountry as primary_address_country,contactaddress.mailingstate as primary_address_state, contactaddress.mailingzip as primary_address_postalcode,   contactaddress.othercity as alt_address_city,contactaddress.otherstreet as alt_address_street, contactaddress.othercountry as alt_address_country,contactaddress.otherstate as alt_address_state, contactaddress.otherzip as alt_address_postalcode  from contactdetails inner join crmentity on crmentity.crmid=contactdetails.contactid inner join users on users.id=crmentity.smownerid left join account on account.accountid=contactdetails.accountid left join contactaddress on contactaddress.contactaddressid=contactdetails.contactid left join contactsubdetails on contactsubdetails.contactsubscriptionid = contactdetails.contactid where user_name='" .$user_name ."' and crmentity.deleted=0 limit " .$from_index ."," .$offset;
      return $this->process_list_query1($query);
    }



    function process_list_query1($query)
    {
	  
        $result =& $this->db->query($query,true,"Error retrieving $this->object_name list: ");
        $list = Array();
        $rows_found =  $this->db->getRowCount($result);
        if($rows_found != 0)
        {
		   $contact = Array();
               for($index = 0 , $row = $this->db->fetchByAssoc($result, $index); $row && $index <$rows_found;$index++, $row = $this->db->fetchByAssoc($result, $index))
            
             {
                foreach($this->range_fields as $columnName)
                {
                    if (isset($row[$columnName])) {
			    
                        $contact[$columnName] = $row[$columnName];
                    }   
                    else     
                    {   
                            $contact[$columnName] = "";
                    }   
	     }	
    
// TODO OPTIMIZE THE QUERY ACCOUNT NAME AND ID are set separetly for every contactdetails and hence 
// account query goes for ecery single account row

                    $list[] = $contact;
                }
        }   

        $response = Array();
        $response['list'] = $list;
        $response['row_count'] = $rows_found;
        $response['next_offset'] = $next_offset;
        $response['previous_offset'] = $previous_offset;


        return $response;
    }


	function get_summary_text()
	{
		return "$this->first_name $this->last_name";
	}
	
	/** Returns a list of the associated contactdetails who are direct reports
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_direct_reports()
	{
		// First, get the list of IDs.
		$query = "SELECT c1.contactid from contactdetails c1, contactdetails c2 where c2.contactid=c1.reports_to_id AND c2.contactid='$this->contactid' AND c1.deleted=0 order by c1.last_name";
		
		return $this->build_related_list($query, new Contact());
	}
	
	/** Returns a list of the associated opportunities
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_opportunities($id)
	{
		global $log;
		global $mod_strings;

		$focus = new Potential();
		$button = '';

		if(isPermitted("Potentials",1,"") == 'yes')
		{

			$button .= '<input title="New Potential" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Potentials\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_POTENTIAL'].'">&nbsp;';
		}
		$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;

		$log->info("Potential Related List for Contact Displayed");

		// First, get the list of IDs.
		$query = 'select contactdetails.accountid, contactdetails.contactid , potential.potentialid, potential.potentialname, potential.potentialtype, potential.sales_stage, potential.amount, potential.closingdate, crmentity.crmid, crmentity.smownerid from contactdetails inner join potential on contactdetails.accountid = potential.accountid inner join crmentity on crmentity.crmid = potential.potentialid where contactdetails.contactid = '.$id.' and crmentity.deleted=0';
		if($this->column_fields['account_id'] != 0)
		return GetRelatedList('Contacts','Potentials',$focus,$query,$button,$returnset);
	}
	
  
	/** Returns a list of the associated tasks
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_activities($id)
	{
     	global $log;
		global $mod_strings;

    	$focus = new Activity();

		$button = '';

        if(isPermitted("Activities",1,"") == 'yes')
        {
		$button .= '<input title="New Task" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.activity_mode.value=\'Task\';this.form.return_module.value=\'Contacts\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_TASK'].'">&nbsp;';
		$button .= '<input title="New Event" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.return_module.value=\'Contacts\';this.form.activity_mode.value=\'Events\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_EVENT'].'">&nbsp;';
		}
		$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;

		$log->info("Activity Related List for Contact Displayed");

		$query = "SELECT contactdetails.lastname, contactdetails.firstname,  activity.activityid , activity.subject, activity.activitytype, activity.date_start, activity.due_date, cntactivityrel.contactid, crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime, recurringevents.recurringtype  from contactdetails inner join cntactivityrel on cntactivityrel.contactid = contactdetails.contactid inner join activity on cntactivityrel.activityid=activity.activityid inner join crmentity on crmentity.crmid = cntactivityrel.activityid left outer join recurringevents on recurringevents.activityid=activity.activityid left join activitygrouprelation on activitygrouprelation.activityid=crmentity.crmid left join groups on groups.groupname=activitygrouprelation.groupname  where contactdetails.contactid=".$id." and crmentity.deleted = 0 and (activity.activitytype = 'Meeting' or activity.activitytype='Call' or activity.activitytype='Task') AND ( activity.status is NULL || activity.status != 'Completed' ) and ( activity.eventstatus is NULL || activity.eventstatus != 'Held') and ( activity.eventstatus is NULL || activity.eventstatus != 'Not Held' )";  //recurring type is added in Query -Jaguar

		return GetRelatedList('Contacts','Activities',$focus,$query,$button,$returnset);

	}

	function get_history($id)
	{
		$query = "SELECT activity.activityid, activity.subject, activity.status, activity.eventstatus,
			activity.activitytype, contactdetails.contactid, contactdetails.firstname,
			contactdetails.lastname, crmentity.modifiedtime,
			crmentity.createdtime, crmentity.description, users.user_name
				from activity
				inner join cntactivityrel on cntactivityrel.activityid= activity.activityid
				inner join contactdetails on contactdetails.contactid= cntactivityrel.contactid
				inner join crmentity on crmentity.crmid=activity.activityid
				left join seactivityrel on seactivityrel.activityid=activity.activityid
				inner join users on crmentity.smcreatorid= users.id
				where (activity.activitytype = 'Meeting' or activity.activitytype='Call' or activity.activitytype='Task')
				and (activity.status = 'Completed' or activity.status = 'Deferred' or (activity.eventstatus != 'Planned' and activity.eventstatus != ''))
				and cntactivityrel.contactid=".$id;
		//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php

		return getHistory('Contacts',$query,$id);
	}
	
	function get_tickets($id)
	{
		global $log;
		global $app_strings;

		$focus = new HelpDesk();

		$button = '<td valign="bottom" align="right"><input title="New Ticket" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'HelpDesk\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_TICKET'].'">&nbsp;</td>';
		$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;

		$query = "select crmentity.crmid, troubletickets.title, contactdetails.contactid, troubletickets.parent_id, contactdetails.firstname, contactdetails.lastname, troubletickets.status, troubletickets.priority, crmentity.smownerid from troubletickets inner join crmentity on crmentity.crmid=troubletickets.ticketid left join contactdetails on contactdetails.contactid=troubletickets.parent_id left join users on users.id=crmentity.smownerid left join ticketgrouprelation on troubletickets.ticketid=ticketgrouprelation.ticketid left join groups on groups.groupname=ticketgrouprelation.groupname where crmentity.deleted=0 and contactdetails.contactid=".$id;
		$log->info("Ticket Related List for Contact Displayed");
		return GetRelatedList('Contacts','HelpDesk',$focus,$query,$button,$returnset);
	}

	function get_attachments($id)
	{
		global $log;
		$query = "select notes.title,'Notes      '  ActivityType,
			notes.filename, attachments.type  FileType,crm2.modifiedtime  lastmodified,
			seattachmentsrel.attachmentsid  attachmentsid, notes.notesid crmid,
			crm2.createdtime, notes.notecontent description, users.user_name
		from notes
			inner join crmentity on crmentity.crmid= notes.contact_id
			inner join crmentity crm2 on crm2.crmid=notes.notesid and crm2.deleted=0
			left join seattachmentsrel on seattachmentsrel.crmid =notes.notesid
			left join attachments on seattachmentsrel.attachmentsid = attachments.attachmentsid
			inner join users on crm2.smcreatorid= users.id
		where crmentity.crmid=".$id;
		$query .= " union all ";
		$query .= "select attachments.description title,'Attachments'  ActivityType,
			attachments.name  filename, attachments.type  FileType,crm2.modifiedtime  lastmodified,
			attachments.attachmentsid attachmentsid, seattachmentsrel.attachmentsid crmid,
			crm2.createdtime, attachments.description, users.user_name
		from attachments
			inner join seattachmentsrel on seattachmentsrel.attachmentsid= attachments.attachmentsid
			inner join crmentity on crmentity.crmid= seattachmentsrel.crmid
			inner join crmentity crm2 on crm2.crmid=attachments.attachmentsid
			inner join users on crm2.smcreatorid= users.id
		where crmentity.crmid=".$id."
		order by createdtime desc";
	  	$log->info("Notes&Attachmenmts for Contact Displayed");
		return getAttachmentsAndNotes('Contacts',$query,$id);
	  }
	 
	 function get_quotes($id)
	 {	
		global $app_strings;
		require_once('modules/Quotes/Quote.php');		
		$focus = new Quote();
	
		$button = '';
		if(isPermitted("Quotes",1,"") == 'yes')
        {
		$button .= '<input title="'.$app_strings['LBL_NEW_QUOTE_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_QUOTE_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Quotes\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_QUOTE_BUTTON'].'">&nbsp;</td>';
		}
		$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;
		$query = "select crmentity.*, quotes.*,potential.potentialname,contactdetails.lastname from quotes inner join crmentity on crmentity.crmid=quotes.quoteid left outer join contactdetails on contactdetails.contactid=quotes.contactid left outer join potential on potential.potentialid=quotes.potentialid where crmentity.deleted=0 and contactdetails.contactid=".$id;
		return GetRelatedList('Contacts','Quotes',$focus,$query,$button,$returnset);
	  }
	 
	 function get_salesorder($id)
	 {	
		 require_once('modules/SalesOrder/SalesOrder.php');
		 global $app_strings;
		 $focus = new SalesOrder();
		 $button = '';

		 if(isPermitted("SalesOrder",1,"") == 'yes')
		 {

			 $button .= '<input title="'.$app_strings['LBL_NEW_SORDER_BUTTON_TITLE'].'" accessyKey="O" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'SalesOrder\';this.form.return_module.value=\'Contacts\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_SORDER_BUTTON'].'">&nbsp;';
		 }
		 $returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;

		 $query = "select crmentity.*, salesorder.*, quotes.subject as quotename, account.accountname, contactdetails.lastname from salesorder inner join crmentity on crmentity.crmid=salesorder.salesorderid left outer join quotes on quotes.quoteid=salesorder.quoteid left outer join account on account.accountid=salesorder.accountid left outer join contactdetails on contactdetails.contactid=salesorder.contactid where crmentity.deleted=0 and salesorder.contactid = ".$id;
		 return GetRelatedList('Contacts','SalesOrder',$focus,$query,$button,$returnset);
	 }
	 
	 function get_products($id)
	 {
		 
		 global $app_strings;
		 require_once('modules/Products/Product.php');
		 $focus = new Product();
		 $button = '';

		 if(isPermitted("Products",1,"") == 'yes')
		 {

			 $button .= '<input title="'.$app_strings['LBL_NEW_PRODUCT'].'" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Products\';this.form.return_module.value=\'Contacts\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_PRODUCT'].'">&nbsp;';
		 }
		 $returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;

		 $query = 'select products.productid, products.productname, products.productcode, products.commissionrate, products.qty_per_unit, products.unit_price, crmentity.crmid, crmentity.smownerid,contactdetails.lastname from products inner join crmentity on crmentity.crmid = products.productid left outer join contactdetails on contactdetails.contactid = products.contactid where contactdetails.contactid = '.$id.' and crmentity.deleted = 0';
		 return GetRelatedList('Contacts','Products',$focus,$query,$button,$returnset);
	 }
	 
	 function get_purchase_orders($id)
	 {
		 global $app_strings;
		 require_once('modules/PurchaseOrder/PurchaseOrder.php');
		 $focus = new Order();

		 $button = '';

		 if(isPermitted("PurchaseOrder",1,"") == 'yes')
		 {

			 $button .= '<input title="'.$app_strings['LBL_PORDER_BUTTON_TITLE'].'" accessyKey="O" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'PurchaseOrder\';this.form.return_module.value=\'Contacts\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_PORDER_BUTTON'].'">&nbsp;';
		 }
		 $returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;

		 $query = "select crmentity.*, purchaseorder.*,vendor.vendorname,contactdetails.lastname from purchaseorder inner join crmentity on crmentity.crmid=purchaseorder.purchaseorderid left outer join vendor on purchaseorder.vendorid=vendor.vendorid left outer join contactdetails on contactdetails.contactid=purchaseorder.contactid where crmentity.deleted=0 and purchaseorder.contactid=".$id;
		 return GetRelatedList('Contacts','PurchaseOrder',$focus,$query,$button,$returnset);
	 }

	/** Returns a list of the associated emails
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_emails($id)
	{
		global $log;
		global $mod_strings;

		$focus = new Email();

		$button = '';

		if(isPermitted("Emails",1,"") == 'yes')
		{	
			$button .= '<input title="New Email" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Emails\';this.form.email_directing_module.value=\'contacts\';this.form.record.value='.$id.';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_EMAIL'].'">';
		}
		$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;

		$log->info("Email Related List for Contact Displayed");

		$query = 'select activity.activityid, activity.activityid, activity.subject, activity.activitytype, users.user_name, crmentity.modifiedtime, crmentity.crmid, crmentity.smownerid, activity.date_start from activity, seactivityrel, contactdetails, users, crmentity where seactivityrel.activityid = activity.activityid and contactdetails.contactid = seactivityrel.crmid and users.id=crmentity.smownerid and crmentity.crmid = activity.activityid  and contactdetails.contactid = '.$id.'  and activity.activitytype="Emails" and crmentity.deleted = 0';
		return GetRelatedList('Contacts','Emails',$focus,$query,$button,$returnset);
	}

	function create_list_query(&$order_by, &$where)
	{
		// Determine if the account name is present in the where clause.
		$account_required = ereg("accounts\.name", $where);
		
		if($account_required)
		{
			$query = "SELECT * FROM accounts, accounts_contacts a_c, contactdetails ";
			$where_auto = "a_c.contact_id = contactdetails.contactid AND a_c.account_id = accounts.id AND a_c.deleted=0 AND accounts.deleted=0 AND contactdetails.deleted=0";
		}
		else 
		{
			$query = "select * from $this->table_name left join contactscf on contactdetails.contactid=contactscf.contactid ";
			//$query = "SELECT * FROM contactdetails ";
			//$query = "SELECT id, yahoo_id, contactdetails.assigned_user_id, first_name, last_name, phone_work, title, email1 FROM contactdetails ";
			$where_auto = "deleted=0";
		}
		
		if($where != "")
			$query .= "where ($where) AND ".$where_auto;
		else 
			$query .= "where ".$where_auto;		

		if(!empty($order_by))
			$query .= " ORDER BY $order_by";

		return $query;
	}


//method added to construct the query to fetch the custom fields 
	function constructCustomQueryAddendum()
	{
        
	 global $log;
         global $adb;
        	//get all the custom fields created 
		$sql1 = "select columnname,fieldlabel from field where generatedtype=2 and tabid=4";
        	$result = $adb->query($sql1);
		$numRows = $adb->num_rows($result);
		$sql3 = "select ";
		for($i=0; $i < $numRows;$i++)
		{
			$columnName = $adb->query_result($result,$i,"columnname");
			$fieldlable = $adb->query_result($result,$i,"fieldlabel");
			//construct query as below
		       if($i == 0)
		      	{
				$sql3 .= "contactscf.".$columnName. " '" .$fieldlable."'";
			}
			else
			{	
				$sql3 .= ", contactscf.".$columnName. " '" .$fieldlable."'";
			}
        
	         }
		 $log->info("Custom Query successfully Constructed in constructCustomQueryAddendum()");
		return $sql3;
        	}

//check if the custom table exists or not in the first place
function checkIfCustomTableExists()
{
 $result = $this->db->query("select * from contactscf");
 $testrow = $this->db->num_fields($result);
	if($testrow > 1)
	{
		$exists=true;
	}
	else
	{
		$exists=false;
	}
return $exists;
}

        function create_export_query(&$order_by, &$where)
        {
		global $log;
		if($this->checkIfCustomTableExists())
		{
	   $query =  $this->constructCustomQueryAddendum() .",
                                contactdetails.*, contactaddress.*,
                                account.accountname account_name,
                                users.user_name assigned_user_name
                                FROM contactdetails
				inner join crmentity on crmentity.crmid=contactdetails.contactid
                                LEFT JOIN users ON crmentity.smcreatorid=users.id
                                LEFT JOIN account on contactdetails.accountid=account.accountid
				left join contactaddress on contactaddress.contactaddressid=contactdetails.contactid
			        left join contactscf on contactscf.contactid=contactdetails.contactid
				where crmentity.deleted=0 and users.status='Active' ";
		}
		else
		{
                  	 $query = "SELECT
                                contactdetails.*, contactaddress.*,
                                account.accountname account_name,
                                users.user_name assigned_user_name
                                FROM contactdetails
                                inner join crmentity on crmentity.crmid=contactdetails.contactid
                                LEFT JOIN users ON crmentity.smcreatorid=users.id
                                LEFT JOIN account on contactdetails.accountid=account.accountid
				left join contactaddress on contactaddress.contactaddressid=contactdetails.contactid
			        left join contactscf on contactscf.contactid=contactdetails.contactid
				where crmentity.deleted=0 and users.status='Active' ";
		}
                 $log->info("Export Query Constructed Successfully");
		return $query;
        }



	function save_relationship_changes($is_update)
    {
    	$this->clear_account_contact_relationship($this->id);
    	
    	if($this->account_id != "")
    	{
    		$this->set_account_contact_relationship($this->id, $this->account_id);    	
    	}
        if($this->reports_to_id == "")
    	{
              $this->clear_contact_direct_report_relationship($this->id);
    	}
	
    	if($this->opportunity_id != "")
    	{
    		$this->set_opportunity_contact_relationship($this->id, $this->opportunity_id);    	
    	}
    	if($this->case_id != "")
    	{
    		$this->set_case_contact_relationship($this->id, $this->case_id);    	
    	}
    	if($this->task_id != "")
    	{
    		$this->set_task_contact_relationship($this->id, $this->task_id);    	
    	}
    	if($this->note_id != "")
    	{
    		$this->set_note_contact_relationship($this->id, $this->note_id);    	
    	}
    	if($this->meeting_id != "")
    	{
    		$this->set_meeting_contact_relationship($this->id, $this->meeting_id);    	
    	}
    	if($this->call_id != "")
    	{
    		$this->set_call_contact_relationship($this->id, $this->call_id);    	
    	}
    	if($this->email_id != "")
    	{
    		$this->set_email_contact_relationship($this->id, $this->email_id);    	
    	}
    }

	function clear_account_contact_relationship($contact_id)
	{
		$query = "UPDATE accounts_contacts set deleted=1 where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing account to contact relationship: ");
	}
    
	function set_account_contact_relationship($contact_id, $account_id)
	{
		$query = "insert into accounts_contacts (id,contact_id,account_id) values ('".create_guid()."','$contact_id','$account_id')";
		$this->db->query($query,true,"Error setting account to contact relationship: "."<BR>$query");
	}

	function set_opportunity_contact_relationship($contact_id, $opportunity_id)
	{
		global $app_list_strings;
		$default = $app_list_strings['opportunity_relationship_type_default_key'];
		$query = "insert into opportunities_contacts (id,opportunity_id,contact_id,contact_role) values('".create_guid()."','$opportunity_id','$contact_id','$default')";
		$this->db->query($query,true,"Error setting account to contact relationship: "."<BR>$query");
	}

	function clear_opportunity_contact_relationship($contact_id)
	{
		$query = "UPDATE opportunities_contacts set deleted=1 where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing opportunity to contact relationship: ");
	}
    
	function set_case_contact_relationship($contact_id, $case_id)
	{
		global $app_list_strings;
		$default = $app_list_strings['case_relationship_type_default_key'];
		$query = "insert into contacts_cases (id,case_id,contact_id,contact_role) values ('".create_guid()."','$case_id','$contact_id','$default')";
		$this->db->query($query,true,"Error setting account to contact relationship: "."<BR>$query");
	}

	function clear_case_contact_relationship($contact_id)
	{
		$query = "UPDATE contacts_cases set deleted=1 where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing case to contact relationship: ");
	}
    
	function set_task_contact_relationship($contact_id, $task_id)
	{
		$query = "UPDATE tasks set contact_id='$contact_id' where id='$task_id'";
		$this->db->query($query,true,"Error setting contact to task relationship: ");
	}
	
	function clear_task_contact_relationship($contact_id)
	{
		$query = "UPDATE tasks set deleted=1 where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing task to contact relationship: ");
	}

	function set_note_contact_relationship($contact_id, $note_id)
	{
		$query = "UPDATE notes set contact_id='$contact_id' where id='$note_id'";
		$this->db->query($query,true,"Error setting contact to note relationship: ");
	}
	
	function clear_note_contact_relationship($contact_id)
	{
		$query = "UPDATE notes set deleted=1 where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing note to contact relationship: ");
	}

	function set_meeting_contact_relationship($contact_id, $meeting_id)
	{
		$query = "insert into meetings_contacts (id,meeting_id,contact_id) values ('".create_guid()."','$meeting_id','$contact_id')";
		$this->db->query($query,true,"Error setting meeting to contact relationship: "."<BR>$query");
	}

	function clear_meeting_contact_relationship($contact_id)
	{
		$query = "UPDATE meetings_contacts set deleted=1 where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing meeting to contact relationship: ");
	}

	function set_call_contact_relationship($contact_id, $call_id)
	{
		$query = "insert into calls_contacts (id,call_id,contact_id) values ('".create_guid()."','$call_id','$contact_id')";
		$this->db->query($query,true,"Error setting meeting to contact relationship: "."<BR>$query");
	}

	function clear_call_contact_relationship($contact_id)
	{
		$query = "UPDATE calls_contacts set deleted=1 where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing call to contact relationship: ");
	}

	function set_email_contact_relationship($contact_id, $email_id)
	{
		$query = "insert into emails_contacts (id,email_id,contact_id) values ('".create_guid()."','$email_id','$contact_id')";
		$this->db->query($query,true,"Error setting email to contact relationship: "."<BR>$query");
	}

	function clear_email_contact_relationship($contact_id)
	{
		$query = "UPDATE emails_contacts set deleted=1 where contact_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing email to contact relationship: ");
	}

	function clear_contact_all_direct_report_relationship($contact_id)
	{
		$query = "UPDATE contactdetails set reports_to_id='' where reports_to_id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing contact to direct report relationship: ");
	}

	function clear_contact_direct_report_relationship($contact_id)
	{
		$query = "UPDATE contactdetails set reports_to_id='' where id='$contact_id' and deleted=0";
		$this->db->query($query,true,"Error clearing contact to direct report relationship: ");
	}

	function mark_relationships_deleted($id)
	{
		$this->clear_contact_all_direct_report_relationship($id);
		$this->clear_account_contact_relationship($id);
		$this->clear_opportunity_contact_relationship($id);
		$this->clear_case_contact_relationship($id);
		$this->clear_task_contact_relationship($id);
		$this->clear_note_contact_relationship($id);
		$this->clear_call_contact_relationship($id);
		$this->clear_meeting_contact_relationship($id);
		$this->clear_email_contact_relationship($id);
	}
		
	function fill_in_additional_list_fields()
	{
		$this->fill_in_additional_detail_fields();	
	}
	
	function fill_in_additional_detail_fields()
	{
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);
		
		//$query = "SELECT acc.id, acc.name from accounts acc, accounts_contacts  a_c where acc.id = a_c.account_id and a_c.contact_id = '$this->id' and a_c.deleted=0";
		$query = "SELECT acc.accountid, acc.accountname from account acc, contactdetails  a_c where acc.accountid = a_c.contactid and a_c.contactid = '$this->id' and a_c.deleted=0";
		$result = $this->db->query($query,true," Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);
		
		if($row != null)
		{
			$this->account_name = $row['name'];
			$this->account_id = $row['id'];
		}
		else 
		{
			$this->account_name = '';
			$this->account_id = '';
		}		
		$query = "SELECT c1.firstname, c1.lastname from contactdetails c1, contactdetails c2 where c1.contactid = c2.reportsto and c2.contactid = '$this->id' and c1.deleted=0";
		$result = $this->db->query($query,true," Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);
		
		if($row != null)
		{
			$this->reports_to_name = $row['first_name'].' '.$row['last_name'];
		}
		else 
		{
			$this->reports_to_name = '';
		}		
	}
	function get_list_view_data(){
		$temp_array = $this->get_list_view_array();
    	$temp_array["ENCODED_NAME"]=htmlspecialchars($this->first_name.' '.$this->last_name, ENT_QUOTES);
    	return $temp_array;
		
	}
	function list_view_parse_additional_sections(&$list_form, $section){
		
		if($list_form->exists($section.".row.yahoo_id") && isset($this->yahoo_id) && $this->yahoo_id != '')
			$list_form->parse($section.".row.yahoo_id");
		elseif ($list_form->exists($section.".row.no_yahoo_id"))
				$list_form->parse($section.".row.no_yahoo_id");
		return $list_form;
		
		
	}
	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string) {
	$where_clauses = Array();
	$the_query_string = addslashes($the_query_string);
	array_push($where_clauses, "lastname like '$the_query_string%'");
	array_push($where_clauses, "firstname like '$the_query_string%'");
	array_push($where_clauses, "contactsubdetails.assistant like '$the_query_string%'");
	array_push($where_clauses, "email like '$the_query_string%'");
	array_push($where_clauses, "otheremail like '$the_query_string%'");
	array_push($where_clauses, "yahooid like '$the_query_string%'");
	if (is_numeric($the_query_string)) {
		array_push($where_clauses, "phone like '%$the_query_string%'");
		array_push($where_clauses, "mobile like '%$the_query_string%'");
		array_push($where_clauses, "contactsubdetails.homephone like '%$the_query_string%'");
		array_push($where_clauses, "contactsubdetails.otherphone like '%$the_query_string%'");
		array_push($where_clauses, "fax like '%$the_query_string%'");
		array_push($where_clauses, "contactsubdetails.assistantphone like '%$the_query_string%'");
	}
	
	$the_where = "";
	foreach($where_clauses as $clause)
	{
		if($the_where != "") $the_where .= " or ";
		$the_where .= $clause;
	}

	
	return "( ".$the_where." )";
}


//Used By vtigerCRM Word Add-In
function getColumnNames()
{
	$sql1 = "select fieldlabel from field where tabid=4 and block <> 4";
	$result = $this->db->query($sql1);
	$numRows = $this->db->num_rows($result);
	for($i=0; $i < $numRows;$i++)
	{
	$custom_fields[$i] = $this->db->query_result($result,$i,"fieldlabel");
	$custom_fields[$i] = ereg_replace(" ","",$custom_fields[$i]);
	$custom_fields[$i] = strtoupper($custom_fields[$i]);
	}
	$mergeflds = $custom_fields;
	return $mergeflds;
}
//End 

//Used By vtigerCRM Outlook Add-In
function get_searchbyemailid($username,$emailaddress)
{
	$query = "select contactdetails.lastname as last_name,contactdetails.firstname as first_name,
						contactdetails.contactid as id, contactdetails.salutation as salutation, 
						contactdetails.email as email1,contactdetails.title as title,
						contactdetails.mobile as phone_mobile,account.accountname as account_name,
						account.accountid as account_id  from contactdetails 
						inner join crmentity on crmentity.crmid=contactdetails.contactid 
						inner join users on users.id=crmentity.smownerid  
						left join account on account.accountid=contactdetails.accountid 
						left join contactaddress on contactaddress.contactaddressid=contactdetails.contactid 
						where user_name='" .$username ."' and crmentity.deleted=0  and contactdetails.email like '%".$emailaddress."%'";
						
	return $this->process_list_query1($query);
}

function get_contactsforol($user_name)
{
	$query = "select contactdetails.department department, contactdetails.phone, 
						contactdetails.fax, contactsubdetails.assistant assistant_name,
						contactsubdetails.assistantphone,  
						contactsubdetails.otherphone, contactsubdetails.homephone,
						contactsubdetails.birthday birthdate, contactdetails.lastname last_name,
						contactdetails.firstname first_name,contactdetails.contactid as id, 
						contactdetails.salutation, contactdetails.email,
						contactdetails.title,contactdetails.mobile,
						account.accountname as account_name,account.accountid as account_id, 
						contactaddress.mailingcity, contactaddress.mailingstreet, 
						contactaddress.mailingcountry, contactaddress.mailingstate, 
						contactaddress.mailingzip, contactaddress.othercity,
						contactaddress.otherstreet, contactaddress.othercountry,
						contactaddress.otherstate, contactaddress.otherzip   
						from contactdetails 
						inner join crmentity on crmentity.crmid=contactdetails.contactid 
						inner join users on users.id=crmentity.smownerid 
						left join account on account.accountid=contactdetails.accountid 
						left join contactaddress on contactaddress.contactaddressid=contactdetails.contactid 
						left join contactsubdetails on contactsubdetails.contactsubscriptionid = contactdetails.contactid 
						where users.user_name='" .$user_name ."' and crmentity.deleted=0";
						
	return $query;
}
//End

}

?>
