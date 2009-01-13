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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Contacts/Contacts.php,v 1.70 2005/04/27 11:21:49 rank Exp $
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
require_once('modules/Potentials/Potentials.php');
require_once('modules/Calendar/Activity.php');
require_once('modules/Campaigns/Campaigns.php');
require_once('modules/Documents/Documents.php');
require_once('modules/Emails/Emails.php');
require_once('modules/HelpDesk/HelpDesk.php');
require_once('user_privileges/default_module_view.php');


// Contact is used to store customer information.
class Contacts extends CRMEntity {
	var $log;
	var $db;

	var $table_name = "vtiger_contactdetails";
	var $table_index= 'contactid';
	var $tab_name = Array('vtiger_crmentity','vtiger_contactdetails','vtiger_contactaddress','vtiger_contactsubdetails','vtiger_contactscf','vtiger_customerdetails');
	var $tab_name_index = Array('vtiger_crmentity'=>'crmid','vtiger_contactdetails'=>'contactid','vtiger_contactaddress'=>'contactaddressid','vtiger_contactsubdetails'=>'contactsubscriptionid','vtiger_contactscf'=>'contactid','vtiger_customerdetails'=>'customerid');

	var $column_fields = Array();
	
	var $sortby_fields = Array('lastname','firstname','title','email','phone','smownerid','accountname');

	var $list_link_field= 'lastname';

	// This is the list of vtiger_fields that are in the lists.
	var $list_fields = Array(
	'Last Name' => Array('contactdetails'=>'lastname'),
	'First Name' => Array('contactdetails'=>'firstname'),
	'Title' => Array('contactdetails'=>'title'),
	'Account Name' => Array('account'=>'account_id'),
	'Email' => Array('contactdetails'=>'email'),
	'Office Phone' => Array('contactdetails'=>'phone'),
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
		'assistant_phone');

	
	var $list_fields_name = Array(
	'Last Name' => 'lastname',
	'First Name' => 'firstname',
	'Title' => 'title',
	'Account Name' => 'account_id',
	'Email' => 'email',
	'Office Phone' => 'phone',
	'Assigned To' => 'assigned_user_id'
	);

	var $search_fields = Array(
	'Name' => Array('contactdetails'=>'lastname'),
	'Title' => Array('contactdetails'=>'title'),
	'Account Name'=>Array('contactdetails'=>'account_id'),
		);
	
	var $search_fields_name = Array(
	'Name' => 'lastname',
	'Title' => 'title',
	'Account Name'=>'account_id',
	);

	// This is the list of vtiger_fields that are required
	var $required_fields =  array("lastname"=>1);
	
	//Default Fields for Email Templates -- Pavani
	var $emailTemplate_defaultFields = array('firstname','lastname','salutation','title','email','department','phone','mobile','support_start_date','support_end_date');

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'lastname';
	var $default_sort_order = 'ASC';

	function Contacts() {
		$this->log = LoggerManager::getLogger('contact');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Contacts');
	}

    	// Mike Crowe Mod --------------------------------------------------------Default ordering for us
	/**
	* Function to get sort order
	* return string  $sorder    - sortorder string either 'ASC' or 'DESC'
	*/
	function getSortOrder()
	{	
		global $log;
                $log->debug("Entering getSortOrder() method ...");
		if(isset($_REQUEST['sorder'])) 
			$sorder = $_REQUEST['sorder'];
		else
			$sorder = (($_SESSION['CONTACTS_SORT_ORDER'] != '')?($_SESSION['CONTACTS_SORT_ORDER']):($this->default_sort_order));
		$log->debug("Exiting getSortOrder method ...");
		return $sorder;
	}
	/**
	* Function to get order by
	* return string  $order_by    - fieldname(eg: 'Contactname')
	*/
	function getOrderBy()
	{
		global $log;
	        $log->debug("Entering getOrderBy() method ...");
		if (isset($_REQUEST['order_by'])) 
			$order_by = $_REQUEST['order_by'];
		else
			$order_by = (($_SESSION['CONTACTS_ORDER_BY'] != '')?($_SESSION['CONTACTS_ORDER_BY']):($this->default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}	
	// Mike Crowe Mod --------------------------------------------------------
	/** Function to get the number of Contacts assigned to a particular User.
	*  @param varchar $user name - Assigned to User
	*  Returns the count of contacts assigned to user.
	*/
	function getCount($user_name) 
	{
		global $log;
		$log->debug("Entering getCount(".$user_name.") method ...");
		$query = "select count(*) from vtiger_contactdetails  inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_contactdetails.contactid inner join vtiger_users on vtiger_users.id=vtiger_crmentity.smownerid where user_name=? and vtiger_crmentity.deleted=0";
		$result = $this->db->pquery($query,array($user_name),true,"Error retrieving contacts count");
		$rows_found =  $this->db->getRowCount($result);
		$row = $this->db->fetchByAssoc($result, 0);


		$log->debug("Exiting getCount method ...");
		return $row["count(*)"];
	}       
	/** Function to get the Contact Details assigned to a given User ID which has a valid Email Address.
	* @param varchar $user_name - User Name (eg. Admin)
	* @param varchar $email_address - Email Addr of each contact record.
	* Returns the query.
	*/
  function get_contacts1($user_name,$email_address)
	{   
		global $log;
		$log->debug("Entering get_contacts1(".$user_name.",".$email_address.") method ...");
		$query = "select vtiger_users.user_name, vtiger_contactdetails.lastname last_name,vtiger_contactdetails.firstname first_name,vtiger_contactdetails.contactid as id, vtiger_contactdetails.salutation as salutation, vtiger_contactdetails.email as email1,vtiger_contactdetails.title as title,vtiger_contactdetails.mobile as phone_mobile,vtiger_account.accountname as account_name,vtiger_account.accountid as account_id   from vtiger_contactdetails inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_contactdetails.contactid inner join vtiger_users on vtiger_users.id=vtiger_crmentity.smownerid  left join vtiger_account on vtiger_account.accountid=vtiger_contactdetails.accountid left join vtiger_contactaddress on vtiger_contactaddress.contactaddressid=vtiger_contactdetails.contactid  where user_name='" .$user_name ."' and vtiger_crmentity.deleted=0  and vtiger_contactdetails.email like '". formatForSqlLike($email_address) ."' limit 50";

		$log->debug("Exiting get_contacts1 method ...");
		return $this->process_list_query1($query);
	}
	/** Function to get the Contact Details assigned to a particular User based on the starting count and the number of subsequent records.
	*  @param varchar $user_name - Assigned User
	*  @param integer $from_index - Initial record number to be displayed 
	*  @param integer $offset - Count of the subsequent records to be displayed.
	*  Returns Query.
	*/
    function get_contacts($user_name,$from_index,$offset)
    {   
	global $log;
	$log->debug("Entering get_contacts(".$user_name.",".$from_index.",".$offset.") method ...");
      $query = "select vtiger_users.user_name,vtiger_groups.groupname,vtiger_contactdetails.department department, vtiger_contactdetails.phone office_phone, vtiger_contactdetails.fax fax, vtiger_contactsubdetails.assistant assistant_name, vtiger_contactsubdetails.otherphone other_phone, vtiger_contactsubdetails.homephone home_phone,vtiger_contactsubdetails.birthday birthdate, vtiger_contactdetails.lastname last_name,vtiger_contactdetails.firstname first_name,vtiger_contactdetails.contactid as id, vtiger_contactdetails.salutation as salutation, vtiger_contactdetails.email as email1,vtiger_contactdetails.title as title,vtiger_contactdetails.mobile as phone_mobile,vtiger_account.accountname as account_name,vtiger_account.accountid as account_id, vtiger_contactaddress.mailingcity as primary_address_city,vtiger_contactaddress.mailingstreet as primary_address_street, vtiger_contactaddress.mailingcountry as primary_address_country,vtiger_contactaddress.mailingstate as primary_address_state, vtiger_contactaddress.mailingzip as primary_address_postalcode,   vtiger_contactaddress.othercity as alt_address_city,vtiger_contactaddress.otherstreet as alt_address_street, vtiger_contactaddress.othercountry as alt_address_country,vtiger_contactaddress.otherstate as alt_address_state, vtiger_contactaddress.otherzip as alt_address_postalcode  from vtiger_contactdetails inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_contactdetails.contactid inner join vtiger_users on vtiger_users.id=vtiger_crmentity.smownerid left join vtiger_account on vtiger_account.accountid=vtiger_contactdetails.accountid left join vtiger_contactaddress on vtiger_contactaddress.contactaddressid=vtiger_contactdetails.contactid left join vtiger_contactsubdetails on vtiger_contactsubdetails.contactsubscriptionid = vtiger_contactdetails.contactid left join vtiger_groups on vtiger_groups.groupid=vtiger_crmentity.smownerid left join vtiger_users on vtiger_crmentity.smownerid=vtiger_users.id where user_name='" .$user_name ."' and vtiger_crmentity.deleted=0 limit " .$from_index ."," .$offset;
      
	$log->debug("Exiting get_contacts method ...");
      return $this->process_list_query1($query);
    }


    /** Function to process list query for a given query
    *  @param $query
    *  Returns the results of query in array format 
    */
    function process_list_query1($query)
    {
	global $log;
	$log->debug("Entering process_list_query1(".$query.") method ...");
	  
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
// TODO OPTIMIZE THE QUERY ACCOUNT NAME AND ID are set separetly for every vtiger_contactdetails and hence 
// vtiger_account query goes for ecery single vtiger_account row

                    $list[] = $contact;
                }
        }   

        $response = Array();
        $response['list'] = $list;
        $response['row_count'] = $rows_found;
        $response['next_offset'] = $next_offset;
        $response['previous_offset'] = $previous_offset;


	$log->debug("Exiting process_list_query1 method ...");
        return $response;
    }
    
    
    /** Function to process list query for Plugin with Security Parameters for a given query
    *  @param $query
    *  Returns the results of query in array format 
    */
    function plugin_process_list_query($query)
    {
          global $log,$adb,$current_user;
          $log->debug("Entering process_list_query1(".$query.") method ...");
          $permitted_field_lists = Array();
          require('user_privileges/user_privileges_'.$current_user->id.'.php');
          if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
          {
              $sql1 = "select columnname from vtiger_field where tabid=4 and block <> 75";
			  $params1 = array();
          }else
          {
              $profileList = getCurrentUserProfileList();
              $sql1 = "select columnname from vtiger_field inner join vtiger_profile2field on vtiger_profile2field.fieldid=vtiger_field.fieldid inner join vtiger_def_org_field on vtiger_def_org_field.fieldid=vtiger_field.fieldid where vtiger_field.tabid=4 and vtiger_field.block <> 6 and vtiger_field.block <> 75 and vtiger_field.displaytype in (1,2,4,3) and vtiger_profile2field.visible=0 and vtiger_def_org_field.visible=0";
			  $params1 = array();
			  if (count($profileList) > 0) {
			  	 $sql1 .= " and vtiger_profile2field.profileid in (". generateQuestionMarks($profileList) .")";
			  	 array_push($params1, $profileList);
			  }
          }
          $result1 = $this->db->pquery($sql1, $params1);
          for($i=0;$i < $adb->num_rows($result1);$i++)
          {
              $permitted_field_lists[] = $adb->query_result($result1,$i,'columnname');
          }
          
          $result =& $this->db->query($query,true,"Error retrieving $this->object_name list: ");
          $list = Array();
          $rows_found =  $this->db->getRowCount($result);
          if($rows_found != 0)
          {
              for($index = 0 , $row = $this->db->fetchByAssoc($result, $index); $row && $index <$rows_found;$index++, $row = $this->db->fetchByAssoc($result, $index))
              {
                  $contact = Array();
                  
		  $contact[lastname] = in_array("lastname",$permitted_field_lists) ? $row[lastname] : "";
		  $contact[firstname] = in_array("firstname",$permitted_field_lists)? $row[firstname] : "";
		  $contact[email] = in_array("email",$permitted_field_lists) ? $row[email] : "";

		  
                  if(in_array("accountid",$permitted_field_lists))
                  {
                      $contact[accountname] = $row[accountname];
                      $contact[account_id] = $row[accountid];
                  }else
		  {
                      $contact[accountname] = "";
                      $contact[account_id] = "";
		  }
                  $contact[contactid] =  $row[contactid];
                  $list[] = $contact;
              }
          }   
          
          $response = Array();
          $response['list'] = $list;
          $response['row_count'] = $rows_found;
          $response['next_offset'] = $next_offset;
          $response['previous_offset'] = $previous_offset;
          $log->debug("Exiting process_list_query1 method ...");
          return $response;
    }


	/** Returns a list of the associated opportunities
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_opportunities($id)
	{
		global $log, $singlepane_view;
		$log->debug("Entering get_opportunities(".$id.") method ...");
		global $mod_strings;

		$focus = new Potentials();
		$button = '';

		if(isPermitted("Potentials",1,"") == 'yes')
		{

			$button .= '<input title="New Potential" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Potentials\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_POTENTIAL'].'">&nbsp;';
		}
		if($singlepane_view == 'true')
			$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Contacts&return_action=CallRelatedList&return_id='.$id;

		$log->info("Potential Related List for Contact Displayed");

		// First, get the list of IDs.
		$query ='select case when (vtiger_users.user_name not like "") then vtiger_users.user_name else vtiger_groups.groupname end as user_name,vtiger_contactdetails.accountid, vtiger_contactdetails.contactid , vtiger_potential.potentialid, vtiger_potential.potentialname, vtiger_potential.potentialtype, vtiger_potential.sales_stage, vtiger_potential.amount, vtiger_potential.closingdate, vtiger_crmentity.crmid, vtiger_crmentity.smownerid, vtiger_account.accountname from vtiger_contactdetails inner join vtiger_contpotentialrel on vtiger_contpotentialrel.contactid=vtiger_contactdetails.contactid left join vtiger_potential on vtiger_potential.potentialid = vtiger_contpotentialrel.potentialid inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_potential.potentialid left join vtiger_account on vtiger_account.accountid=vtiger_contactdetails.accountid left join vtiger_groups on vtiger_groups.groupid=vtiger_crmentity.smownerid left join vtiger_users on vtiger_users.id=vtiger_crmentity.smownerid where vtiger_contactdetails.contactid ='.$id.' and vtiger_contactdetails.accountid = vtiger_potential.accountid and vtiger_crmentity.deleted=0';
		
		if($this->column_fields['account_id'] != 0)
		$log->debug("Exiting get_opportunities method ...");
		return GetRelatedList('Contacts','Potentials',$focus,$query,$button,$returnset);
	}
	
  
	/** Returns a list of the associated tasks
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_activities($id)
	{
	     	global $log, $singlepane_view;
                $log->debug("Entering get_activities(".$id.") method ...");
		global $mod_strings;

    	$focus = new Activity();

		$button = '';

        if(isPermitted("Calendar",1,"") == 'yes')
        {
		$button .= '<input title="New Task" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Calendar\';this.form.activity_mode.value=\'Task\';this.form.return_module.value=\'Contacts\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_TASK'].'">&nbsp;';
		$button .= '<input title="New Event" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Calendar\';this.form.return_module.value=\'Contacts\';this.form.activity_mode.value=\'Events\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_EVENT'].'">&nbsp;';
		}
		if($singlepane_view == 'true')
			$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Contacts&return_action=CallRelatedList&return_id='.$id;

		$log->info("Activity Related List for Contact Displayed");

		$query = "SELECT case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name," .
				" vtiger_contactdetails.lastname, vtiger_contactdetails.firstname,  vtiger_activity.activityid ," .
				" vtiger_activity.subject, vtiger_activity.activitytype, vtiger_activity.date_start, vtiger_activity.due_date," .
				" vtiger_activity.time_start,vtiger_activity.time_end, vtiger_cntactivityrel.contactid, vtiger_crmentity.crmid," .
				" vtiger_crmentity.smownerid, vtiger_crmentity.modifiedtime, vtiger_recurringevents.recurringtype," .
				" case when (vtiger_activity.activitytype = 'Task') then vtiger_activity.status else vtiger_activity.eventstatus end as status, " .
				" vtiger_seactivityrel.crmid as parent_id " .
				" from vtiger_contactdetails " .
				" inner join vtiger_cntactivityrel on vtiger_cntactivityrel.contactid = vtiger_contactdetails.contactid" .
				" inner join vtiger_activity on vtiger_cntactivityrel.activityid=vtiger_activity.activityid" .
				" inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_cntactivityrel.activityid " .
				" left join vtiger_seactivityrel on vtiger_seactivityrel.activityid = vtiger_cntactivityrel.activityid " .
				" left join vtiger_users on vtiger_users.id=vtiger_crmentity.smownerid" .
				" left outer join vtiger_recurringevents on vtiger_recurringevents.activityid=vtiger_activity.activityid" .
				" left join vtiger_groups on vtiger_groups.groupid=vtiger_crmentity.smownerid" .
				" where vtiger_contactdetails.contactid=".$id." and vtiger_crmentity.deleted = 0" .
						" and ((vtiger_activity.activitytype='Task' and vtiger_activity.status not in ('Completed','Deferred'))" .
						" or (vtiger_activity.activitytype Not in ('Emails','Task') and  vtiger_activity.eventstatus not in ('','Held')))";  //recurring type is added in Query -Jaguar
		$log->debug("Exiting get_activities method ...");
		return GetRelatedList('Contacts','Calendar',$focus,$query,$button,$returnset);

	}
	/**
	* Function to get Contact related Task & Event which have activity type Held, Completed or Deferred.
	* @param  integer   $id      - contactid
	* returns related Task or Event record in array format
	*/
	function get_history($id)
	{
		global $log;
		$log->debug("Entering get_history(".$id.") method ...");
		$query = "SELECT vtiger_activity.activityid, vtiger_activity.subject, vtiger_activity.status, vtiger_activity.eventstatus,vtiger_activity.activitytype, vtiger_activity.date_start, vtiger_activity.due_date,vtiger_activity.time_start,vtiger_activity.time_end,vtiger_contactdetails.contactid, vtiger_contactdetails.firstname,vtiger_contactdetails.lastname, vtiger_crmentity.modifiedtime,vtiger_crmentity.createdtime, vtiger_crmentity.description, case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name
				from vtiger_activity
				inner join vtiger_cntactivityrel on vtiger_cntactivityrel.activityid= vtiger_activity.activityid
				inner join vtiger_contactdetails on vtiger_contactdetails.contactid= vtiger_cntactivityrel.contactid
				inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_activity.activityid
				left join vtiger_seactivityrel on vtiger_seactivityrel.activityid=vtiger_activity.activityid
                left join vtiger_groups on vtiger_groups.groupid=vtiger_crmentity.smownerid
				left join vtiger_users on vtiger_users.id=vtiger_crmentity.smownerid
				where (vtiger_activity.activitytype = 'Meeting' or vtiger_activity.activitytype='Call' or vtiger_activity.activitytype='Task')
				and (vtiger_activity.status = 'Completed' or vtiger_activity.status = 'Deferred' or (vtiger_activity.eventstatus = 'Held' and vtiger_activity.eventstatus != ''))
				and vtiger_cntactivityrel.contactid=".$id."
                                and vtiger_crmentity.deleted = 0";
		//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php
		$log->debug("Entering get_history method ...");
		return getHistory('Contacts',$query,$id);
	}
	/**
	* Function to get Contact related Tickets.
	* @param  integer   $id      - contactid
	* returns related Ticket records in array format
	*/
	function get_tickets($id)
	{
		global $log, $singlepane_view;
		global $app_strings;
		$log->debug("Entering get_tickets(".$id.") method ...");
		$focus = new HelpDesk();

		$button = '<td valign="bottom" align="right"><input title="New Ticket" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'HelpDesk\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_TICKET'].'">&nbsp;</td>';
		if($singlepane_view == 'true')
			$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Contacts&return_action=CallRelatedList&return_id='.$id;

		$query = "select case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name,vtiger_crmentity.crmid, vtiger_troubletickets.title, vtiger_contactdetails.contactid, vtiger_troubletickets.parent_id, vtiger_contactdetails.firstname, vtiger_contactdetails.lastname, vtiger_troubletickets.status, vtiger_troubletickets.priority, vtiger_crmentity.smownerid from vtiger_troubletickets inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_troubletickets.ticketid left join vtiger_contactdetails on vtiger_contactdetails.contactid=vtiger_troubletickets.parent_id left join vtiger_users on vtiger_users.id=vtiger_crmentity.smownerid left join vtiger_groups on vtiger_groups.groupid=vtiger_crmentity.smownerid where vtiger_crmentity.deleted=0 and vtiger_contactdetails.contactid=".$id;
		$log->info("Ticket Related List for Contact Displayed");
		$log->debug("Exiting get_tickets method ...");
		return GetRelatedList('Contacts','HelpDesk',$focus,$query,$button,$returnset);
	}

	  /**
	  * Function to get Contact related Quotes
	  * @param  integer   $id  - contactid
	  * returns related Quotes record in array format
	  */
	 function get_quotes($id)
	 {	
		global $log, $singlepane_view;
                $log->debug("Entering get_quotes(".$id.") method ...");
		global $app_strings;
		require_once('modules/Quotes/Quotes.php');		
		$focus = new Quotes();
	
		$button = '';
		if(isPermitted("Quotes",1,"") == 'yes')
        {
		$button .= '<input title="'.$app_strings['LBL_NEW_QUOTE_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_QUOTE_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Quotes\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_QUOTE_BUTTON'].'">&nbsp;</td>';
		}
		if($singlepane_view == 'true')
			$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Contacts&return_action=CallRelatedList&return_id='.$id;
		$query = "select case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name,vtiger_crmentity.*, vtiger_quotes.*,vtiger_potential.potentialname,vtiger_contactdetails.lastname,vtiger_account.accountname from vtiger_quotes inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_quotes.quoteid left outer join vtiger_contactdetails on vtiger_contactdetails.contactid=vtiger_quotes.contactid left outer join vtiger_potential on vtiger_potential.potentialid=vtiger_quotes.potentialid  left join vtiger_account on vtiger_account.accountid = vtiger_quotes.accountid left join vtiger_users on vtiger_users.id=vtiger_crmentity.smownerid left join vtiger_groups on vtiger_groups.groupid=vtiger_crmentity.smownerid where vtiger_crmentity.deleted=0 and vtiger_contactdetails.contactid=".$id;
		$log->debug("Exiting get_quotes method ...");
		return GetRelatedList('Contacts','Quotes',$focus,$query,$button,$returnset);
	  }
	/**
	 * Function to get Contact related SalesOrder 
 	 * @param  integer   $id  - contactid
	 * returns related SalesOrder record in array format
	 */	 
	 function get_salesorder($id)
	 {	
		 global $log, $singlepane_view;
                $log->debug("Entering get_salesorder(".$id.") method ...");
		 require_once('modules/SalesOrder/SalesOrder.php');
		 global $app_strings;
		 $focus = new SalesOrder();
		 $button = '';

		 if(isPermitted("SalesOrder",1,"") == 'yes')
		 {

			 $button .= '<input title="'.$app_strings['LBL_NEW_SORDER_BUTTON_TITLE'].'" accessyKey="O" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'SalesOrder\';this.form.return_module.value=\'Contacts\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_SORDER_BUTTON'].'">&nbsp;';
		 }
		if($singlepane_view == 'true')
			$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Contacts&return_action=CallRelatedList&return_id='.$id;

		 $query = "select case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name,vtiger_crmentity.*, vtiger_salesorder.*, vtiger_quotes.subject as quotename, vtiger_account.accountname, vtiger_contactdetails.lastname from vtiger_salesorder inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_salesorder.salesorderid left join vtiger_users on vtiger_users.id=vtiger_crmentity.smownerid left outer join vtiger_quotes on vtiger_quotes.quoteid=vtiger_salesorder.quoteid left outer join vtiger_account on vtiger_account.accountid=vtiger_salesorder.accountid left outer join vtiger_contactdetails on vtiger_contactdetails.contactid=vtiger_salesorder.contactid left join vtiger_groups on vtiger_groups.groupid=vtiger_crmentity.smownerid where vtiger_crmentity.deleted=0 and vtiger_salesorder.contactid = ".$id;
		$log->debug("Exiting get_salesorder method ...");
		 return GetRelatedList('Contacts','SalesOrder',$focus,$query,$button,$returnset);
	 }
	 /**
	 * Function to get Contact related Products 
	 * @param  integer   $id  - contactid
	 * returns related Products record in array format
	 */
	 function get_products($id)
	 {
		 global $log, $singlepane_view;
		$log->debug("Entering get_products(".$id.") method ...");
		 global $app_strings;
		 require_once('modules/Products/Products.php');
		 $focus = new Products();
		 $button = '';

		 if(isPermitted("Products",1,"") == 'yes')
		 {

			 $button .= '<input title="'.$app_strings['LBL_NEW_PRODUCT'].'" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Products\';this.form.return_module.value=\'Contacts\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_PRODUCT'].'">&nbsp;';
		 }
		if($singlepane_view == 'true')
			$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Contacts&return_action=CallRelatedList&return_id='.$id;

		 $query = 'SELECT vtiger_products.productid, vtiger_products.productname, vtiger_products.productcode, 
		 		  vtiger_products.commissionrate, vtiger_products.qty_per_unit, vtiger_products.unit_price,
				  vtiger_crmentity.crmid, vtiger_crmentity.smownerid,vtiger_contactdetails.lastname 
			   FROM vtiger_products 
			   INNER JOIN vtiger_seproductsrel 
			   	ON vtiger_seproductsrel.productid=vtiger_products.productid and vtiger_seproductsrel.setype="Contacts" 
			   INNER JOIN vtiger_crmentity 
			   	ON vtiger_crmentity.crmid = vtiger_products.productid 
			   INNER JOIN vtiger_contactdetails 
			   	ON vtiger_contactdetails.contactid = vtiger_seproductsrel.crmid 
			   WHERE vtiger_contactdetails.contactid = '.$id.' and vtiger_crmentity.deleted = 0';

		$log->debug("Exiting get_products method ...");
		 return GetRelatedList('Contacts','Products',$focus,$query,$button,$returnset);
	 }

	/**
	 * Function to get Contact related PurchaseOrder 
 	 * @param  integer   $id  - contactid
	 * returns related PurchaseOrder record in array format
	 */	 
	 function get_purchase_orders($id)
	 {
		global $log, $singlepane_view;
		$log->debug("Entering get_purchase_orders(".$id.") method ...");
		 global $app_strings;
		 require_once('modules/PurchaseOrder/PurchaseOrder.php');
		 $focus = new PurchaseOrder();

		 $button = '';

		 if(isPermitted("PurchaseOrder",1,"") == 'yes')
		 {

			 $button .= '<input title="'.$app_strings['LBL_PORDER_BUTTON_TITLE'].'" accessyKey="O" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'PurchaseOrder\';this.form.return_module.value=\'Contacts\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_PORDER_BUTTON'].'">&nbsp;';
		 }
		if($singlepane_view == 'true')
			$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;
		else
		 	$returnset = '&return_module=Contacts&return_action=CallRelatedList&return_id='.$id;

		 $query = "select case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name,vtiger_crmentity.*, vtiger_purchaseorder.*,vtiger_vendor.vendorname,vtiger_contactdetails.lastname from vtiger_purchaseorder inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_purchaseorder.purchaseorderid left outer join vtiger_vendor on vtiger_purchaseorder.vendorid=vtiger_vendor.vendorid left outer join vtiger_contactdetails on vtiger_contactdetails.contactid=vtiger_purchaseorder.contactid left join vtiger_users on vtiger_users.id=vtiger_crmentity.smownerid left join vtiger_groups on vtiger_groups.groupid=vtiger_crmentity.smownerid where vtiger_crmentity.deleted=0 and vtiger_purchaseorder.contactid=".$id;
		$log->debug("Exiting get_purchase_orders method ...");
		 return GetRelatedList('Contacts','PurchaseOrder',$focus,$query,$button,$returnset);
	 }

	/** Returns a list of the associated emails
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_emails($id)
	{
		global $log, $singlepane_view;
		$log->debug("Entering get_emails(".$id.") method ...");
		global $mod_strings;

		$focus = new Emails();

		$button = '';

		if(isPermitted("Emails",1,"") == 'yes')
		{	
			$button .= '<input title="New Email" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Emails\';this.form.email_directing_module.value=\'contacts\';this.form.record.value='.$id.';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_EMAIL'].'">';
		}
		if($singlepane_view == 'true')
			$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Contacts&return_action=CallRelatedList&return_id='.$id;

		$log->info("Email Related List for Contact Displayed");

		$query = "select case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name," .
				" vtiger_activity.activityid, vtiger_activity.subject, vtiger_activity.activitytype, vtiger_crmentity.modifiedtime," .
				" vtiger_crmentity.crmid, vtiger_crmentity.smownerid, vtiger_activity.date_start, vtiger_seactivityrel.crmid as parent_id " .
				" from vtiger_activity, vtiger_seactivityrel, vtiger_contactdetails, vtiger_users, vtiger_crmentity" .
				" left join vtiger_groups on vtiger_groups.groupid=vtiger_crmentity.smownerid" .
				" where vtiger_seactivityrel.activityid = vtiger_activity.activityid" .
				" and vtiger_contactdetails.contactid = vtiger_seactivityrel.crmid and vtiger_users.id=vtiger_crmentity.smownerid" .
				" and vtiger_crmentity.crmid = vtiger_activity.activityid  and vtiger_contactdetails.contactid = ".$id." and" .
						" vtiger_activity.activitytype='Emails' and vtiger_crmentity.deleted = 0";
		$log->debug("Exiting get_emails method ...");
		return GetRelatedList('Contacts','Emails',$focus,$query,$button,$returnset);
	}

	/** Returns a list of the associated Campaigns
	  * @param $id -- campaign id :: Type Integer
	  * @returns list of campaigns in array format
	  */
	      
	function get_campaigns($id)
	{
		global $log, $singlepane_view;
		$log->debug("Entering get_campaigns(".$id.") method ...");
		global $mod_strings;

		$focus = new Campaigns();
		if($singlepane_view == 'true')
			$returnset = '&return_module=Contacts&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Contacts&return_action=CallRelatedList&return_id='.$id;
		$button = '';

		$log->info("Campaign Related List for Contact Displayed");
		$query = "SELECT case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name, vtiger_campaign.campaignid, vtiger_campaign.campaignname, vtiger_campaign.campaigntype, vtiger_campaign.campaignstatus, vtiger_campaign.expectedrevenue, vtiger_campaign.closingdate, vtiger_crmentity.crmid, vtiger_crmentity.smownerid, vtiger_crmentity.modifiedtime from vtiger_campaign inner join vtiger_campaigncontrel on vtiger_campaigncontrel.campaignid=vtiger_campaign.campaignid inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_campaign.campaignid left join vtiger_groups on vtiger_groups.groupid=vtiger_crmentity.smownerid left join vtiger_users on vtiger_users.id = vtiger_crmentity.smownerid where vtiger_campaigncontrel.contactid=".$id." and vtiger_crmentity.deleted=0";

		$log->debug("Exiting get_campaigns method ...");
		return GetRelatedList('Contacts','Campaigns',$focus,$query,$button,$returnset);

	}
	/** Function to export the contact records in CSV Format
	* @param reference variable - where condition is passed when the query is executed
	* Returns Export Contacts Query.
	*/
        function create_export_query($where)
        {
		global $log;
		global $current_user;
		$log->debug("Entering create_export_query(".$where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Contacts", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT vtiger_contactdetails.salutation as 'Salutation',$fields_list, vtiger_groups.groupname as 'Assigned To Group',case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name 
                                FROM vtiger_contactdetails
                                inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_contactdetails.contactid
                                LEFT JOIN vtiger_users ON vtiger_crmentity.smownerid=vtiger_users.id and vtiger_users.status='Active' 
                                LEFT JOIN vtiger_account on vtiger_contactdetails.accountid=vtiger_account.accountid
				left join vtiger_contactaddress on vtiger_contactaddress.contactaddressid=vtiger_contactdetails.contactid
				left join vtiger_contactsubdetails on vtiger_contactsubdetails.contactsubscriptionid=vtiger_contactdetails.contactid
			        left join vtiger_contactscf on vtiger_contactscf.contactid=vtiger_contactdetails.contactid
			        left join vtiger_customerdetails on vtiger_customerdetails.customerid=vtiger_contactdetails.contactid
	                        LEFT JOIN vtiger_groups
                        	        ON vtiger_groups.groupid = vtiger_crmentity.smownerid
				LEFT JOIN vtiger_contactdetails vtiger_contactdetails2
					ON vtiger_contactdetails2.contactid = vtiger_contactdetails.reportsto";
		$where_auto = " vtiger_crmentity.deleted = 0 ";

                if($where != "")
                   $query .= "  WHERE ($where) AND ".$where_auto;
                else
                   $query .= "  WHERE ".$where_auto;
		


		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
		//we should add security check when the user has Private Access
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[4] == 3)
		{
			//Added security check to get the permitted records only
			$query = $query." ".getListViewSecurityParameter("Contacts");
		}

                $log->info("Export Query Constructed Successfully");
		$log->debug("Exiting create_export_query method ...");
		return $query;
        }

	
/** Function to get the Columnnames of the Contacts
* Used By vtigerCRM Word Plugin
* Returns the Merge Fields for Word Plugin
*/
function getColumnNames()
{
	global $log, $current_user;
	$log->debug("Entering getColumnNames() method ...");
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
	{
	 $sql1 = "select fieldlabel from vtiger_field where tabid=4 and block <> 75";
	 $params1 = array();
	}else
	{
	 $profileList = getCurrentUserProfileList();
	 $sql1 = "select vtiger_field.fieldid,fieldlabel from vtiger_field inner join vtiger_profile2field on vtiger_profile2field.fieldid=vtiger_field.fieldid inner join vtiger_def_org_field on vtiger_def_org_field.fieldid=vtiger_field.fieldid where vtiger_field.tabid=4 and vtiger_field.block <> 75 and vtiger_field.displaytype in (1,2,4,3) and vtiger_profile2field.visible=0 and vtiger_def_org_field.visible=0";
	 $params1 = array();
	 if (count($profileList) > 0) {
	 	$sql1 .= " and vtiger_profile2field.profileid in (". generateQuestionMarks($profileList) .") group by fieldid";
  	 	array_push($params1, $profileList);
	 }
  }
	$result = $this->db->pquery($sql1, $params1);
	$numRows = $this->db->num_rows($result);
	for($i=0; $i < $numRows;$i++)
	{
	$custom_fields[$i] = $this->db->query_result($result,$i,"fieldlabel");
	$custom_fields[$i] = ereg_replace(" ","",$custom_fields[$i]);
	$custom_fields[$i] = strtoupper($custom_fields[$i]);
	}
	$mergeflds = $custom_fields;
	$log->debug("Exiting getColumnNames method ...");
	return $mergeflds;
}
//End 
/** Function to get the Contacts assigned to a user with a valid email address.
* @param varchar $username - User Name
* @param varchar $emailaddress - Email Addr for each contact.
* Used By vtigerCRM Outlook Plugin
* Returns the Query 
*/
function get_searchbyemailid($username,$emailaddress)
{
	global $log;
	global $current_user;
	require_once("modules/Users/Users.php");
	$seed_user=new Users();
	$user_id=$seed_user->retrieve_user_id($username);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
	$log->debug("Entering get_searchbyemailid(".$username.",".$emailaddress.") method ...");
	$query = "select vtiger_contactdetails.lastname,vtiger_contactdetails.firstname,
					vtiger_contactdetails.contactid, vtiger_contactdetails.salutation, 
					vtiger_contactdetails.email,vtiger_contactdetails.title,
					vtiger_contactdetails.mobile,vtiger_account.accountname,
					vtiger_account.accountid as accountid  from vtiger_contactdetails 
						inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_contactdetails.contactid 
						inner join vtiger_users on vtiger_users.id=vtiger_crmentity.smownerid  
						left join vtiger_account on vtiger_account.accountid=vtiger_contactdetails.accountid 
						left join vtiger_contactaddress on vtiger_contactaddress.contactaddressid=vtiger_contactdetails.contactid
			      LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid
			      where vtiger_crmentity.deleted=0";
			      if(trim($emailaddress) != '')
			      	        $query .= " and ((vtiger_contactdetails.email like '". formatForSqlLike($emailaddress) ."') or vtiger_contactdetails.lastname REGEXP REPLACE('".$emailaddress."',' ','|') or vtiger_contactdetails.firstname REGEXP REPLACE('".$emailaddress."',' ','|'))  and vtiger_contactdetails.email != ''";
			      else
			      		$query .= " and (vtiger_contactdetails.email like '". formatForSqlLike($emailaddress) ."' and vtiger_contactdetails.email != '')";

  $tab_id = getTabid("Contacts");
  if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
	{
				$sec_parameter=getListViewSecurityParameter("Contacts");
				$query .= $sec_parameter;

	}
	$log->debug("Exiting get_searchbyemailid method ...");
	return $this->plugin_process_list_query($query);
}

/** Function to get the Contacts associated with the particular User Name.
*  @param varchar $user_name - User Name
*  Returns query
*/

function get_contactsforol($user_name)
{
	global $log,$adb;
	global $current_user;
	require_once("modules/Users/Users.php");
	$seed_user=new Users();
	$user_id=$seed_user->retrieve_user_id($user_name);
	$current_user=$seed_user;
	$current_user->retrieve_entity_info($user_id, 'Users');
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
	
	if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
  {
    $sql1 = "select tablename,columnname from vtiger_field where tabid=4 and block <> 75 and block <> 6 and vtiger_field.block <> 5";
	$params1 = array();
  }else
  {
    $profileList = getCurrentUserProfileList();
    $sql1 = "select tablename,columnname from vtiger_field inner join vtiger_profile2field on vtiger_profile2field.fieldid=vtiger_field.fieldid inner join vtiger_def_org_field on vtiger_def_org_field.fieldid=vtiger_field.fieldid where vtiger_field.tabid=4 and vtiger_field.block <> 75 and vtiger_field.block <> 6 and vtiger_field.block <> 5 and vtiger_field.displaytype in (1,2,4,3) and vtiger_profile2field.visible=0 and vtiger_def_org_field.visible=0";
	$params1 = array();
	if (count($profileList) > 0) {
		$sql1 .= " and vtiger_profile2field.profileid in (". generateQuestionMarks($profileList) .")";
		array_push($params1, $profileList);
	}
  }
  $result1 = $adb->pquery($sql1, $params1);
  for($i=0;$i < $adb->num_rows($result1);$i++)
  {
      $permitted_lists[] = $adb->query_result($result1,$i,'tablename');
      $permitted_lists[] = $adb->query_result($result1,$i,'columnname');
      if($adb->query_result($result1,$i,'columnname') == "accountid")
      {
        $permitted_lists[] = 'vtiger_account';
        $permitted_lists[] = 'accountname';
      }
  }
	$permitted_lists = array_chunk($permitted_lists,2);
	$column_table_lists = array();
	for($i=0;$i < count($permitted_lists);$i++)
	{
	   $column_table_lists[] = implode(".",$permitted_lists[$i]);
  }
	
	$log->debug("Entering get_contactsforol(".$user_name.") method ...");
	$query = "select vtiger_contactdetails.contactid as id, ".implode(',',$column_table_lists)." from vtiger_contactdetails 
						inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_contactdetails.contactid 
						inner join vtiger_users on vtiger_users.id=vtiger_crmentity.smownerid 
						left join vtiger_account on vtiger_account.accountid=vtiger_contactdetails.accountid 
						left join vtiger_contactaddress on vtiger_contactaddress.contactaddressid=vtiger_contactdetails.contactid 
						left join vtiger_contactsubdetails on vtiger_contactsubdetails.contactsubscriptionid = vtiger_contactdetails.contactid
			      LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid 
						where vtiger_crmentity.deleted=0 and vtiger_users.user_name='".$user_name."'";
  $log->debug("Exiting get_contactsforol method ...");
	return $query;
}


	/** Function to handle module specific operations when saving a entity 
	*/
	function save_module($module)
	{
		$this->insertIntoAttachment($this->id,$module);		
	}	

	/**
	 *      This function is used to add the vtiger_attachments. This will call the function uploadAndSaveFile which will upload the attachment into the server and save that attachment information in the database.
	 *      @param int $id  - entity id to which the vtiger_files to be uploaded
	 *      @param string $module  - the current module name
	*/
	function insertIntoAttachment($id,$module)
	{
		global $log, $adb;
		$log->debug("Entering into insertIntoAttachment($id,$module) method.");
		
		$file_saved = false;
		//This is to added to store the existing attachment id of the contact where we should delete this when we give new image
		$old_attachmentid = $adb->query_result($adb->pquery("select vtiger_crmentity.crmid from vtiger_seattachmentsrel inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_seattachmentsrel.attachmentsid where  vtiger_seattachmentsrel.crmid=?", array($id)),0,'crmid');
		foreach($_FILES as $fileindex => $files)
		{
			if($files['name'] != '' && $files['size'] > 0)
			{
				$files['original_name'] = $_REQUEST[$fileindex.'_hidden'];
				$file_saved = $this->uploadAndSaveFile($id,$module,$files);
			}
		}

		//This is to handle the delete image for contacts
		if($module == 'Contacts' && $file_saved)
		{
			if($old_attachmentid != '')
			{
				$setype = $adb->query_result($adb->pquery("select setype from vtiger_crmentity where crmid=?", array($old_attachmentid)),0,'setype');
				if($setype == 'Contacts Image')
				{
					$del_res1 = $adb->pquery("delete from vtiger_attachments where attachmentsid=?", array($old_attachmentid));
					$del_res2 = $adb->pquery("delete from vtiger_seattachmentsrel where attachmentsid=?", array($old_attachmentid));
				}
			}
		}

		$log->debug("Exiting from insertIntoAttachment($id,$module) method.");
	}

	/**
	 * Move the related records of the specified list of id's to the given record.
	 * @param String This module name
	 * @param Array List of Entity Id's from which related records need to be transfered 
	 * @param Integer Id of the the Record to which the related records are to be moved
	 */
	function transferRelatedRecords($module, $transferEntityIds, $entityId) {
		global $adb,$log;
		$log->debug("Entering function transferRelatedRecords ($module, $transferEntityIds, $entityId)");
		
		$rel_table_arr = Array("Potentials"=>"vtiger_contpotentialrel","Activities"=>"vtiger_cntactivityrel","Emails"=>"vtiger_seactivityrel",
				"HelpDesk"=>"vtiger_troubletickets","Quotes"=>"vtiger_quotes","PurchaseOrder"=>"vtiger_purchaseorder",
				"SalesOrder"=>"vtiger_salesorder","Products"=>"vtiger_seproductsrel","Documents"=>"vtiger_senotesrel",
				"Attachments"=>"vtiger_seattachmentsrel","Campaigns"=>"vtiger_campaigncontrel");
		
		$tbl_field_arr = Array("vtiger_contpotentialrel"=>"potentialid","vtiger_cntactivityrel"=>"activityid","vtiger_seactivityrel"=>"activityid",
				"vtiger_troubletickets"=>"ticketid","vtiger_quotes"=>"quoteid","vtiger_purchaseorder"=>"purchaseorderid",
				"vtiger_salesorder"=>"salesorderid","vtiger_seproductsrel"=>"productid","vtiger_senotesrel"=>"notesid",
				"vtiger_seattachmentsrel"=>"attachmentsid","vtiger_campaigncontrel"=>"campaignid");	
		
		$entity_tbl_field_arr = Array("vtiger_contpotentialrel"=>"contactid","vtiger_cntactivityrel"=>"contactid","vtiger_seactivityrel"=>"crmid",
				"vtiger_troubletickets"=>"parent_id","vtiger_quotes"=>"contactid","vtiger_purchaseorder"=>"contactid",
				"vtiger_salesorder"=>"contactid","vtiger_seproductsrel"=>"crmid","vtiger_senotesrel"=>"crmid",
				"vtiger_seattachmentsrel"=>"crmid","vtiger_campaigncontrel"=>"contactid");
		
		foreach($transferEntityIds as $transferId) {
			foreach($rel_table_arr as $rel_module=>$rel_table) {
				$id_field = $tbl_field_arr[$rel_table];
				$entity_id_field = $entity_tbl_field_arr[$rel_table];
				// IN clause to avoid duplicate entries
				$sel_result =  $adb->pquery("select $id_field from $rel_table where $entity_id_field=? " .
						" and $id_field not in (select $id_field from $rel_table where $entity_id_field=?)",
						array($transferId,$entityId));
				$res_cnt = $adb->num_rows($sel_result);
				if($res_cnt > 0) {
					for($i=0;$i<$res_cnt;$i++) {
						$id_field_value = $adb->query_result($sel_result,$i,$id_field);
						$adb->pquery("update $rel_table set $entity_id_field=? where $entity_id_field=? and $id_field=?", 
							array($entityId,$transferId,$id_field_value));	
					}
				}				
			}
		}
		$log->debug("Exiting transferRelatedRecords...");
	}

	/*
	 * Function to get the secondary query part of a report 
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule){
		$tab = getRelationTables($module,$secmodule);
		
		foreach($tab as $key=>$value){
			$tables[]=$key;
			$fields[] = $value;
		}
		$tabname = $tables[0];
		$prifieldname = $fields[0][0];
		$secfieldname = $fields[0][1];
		$tmpname = $tabname."tmp".$secmodule;
		$condvalue = $tables[1].".".$fields[1];
	
		$query = " left join $tabname as $tmpname on $tmpname.$prifieldname = $condvalue  and $tmpname.$secfieldname IN (SELECT contactid from vtiger_contactdetails)";
		$query .= " left join vtiger_contactdetails as vtiger_contactdetailsContacts on vtiger_contactdetailsContacts.contactid = $tmpname.$secfieldname 
			left join vtiger_crmentity as vtiger_crmentityContacts on vtiger_crmentityContacts.crmid = vtiger_contactdetailsContacts.contactid  and vtiger_crmentityContacts.deleted=0
			left join vtiger_contactdetails on vtiger_contactdetails.contactid = vtiger_crmentityContacts.crmid 
			left join vtiger_contactaddress on vtiger_contactdetails.contactid = vtiger_contactaddress.contactaddressid
			left join vtiger_customerdetails on vtiger_customerdetails.customerid = vtiger_contactdetails.contactid
			left join vtiger_contactsubdetails on vtiger_contactdetails.contactid = vtiger_contactsubdetails.contactsubscriptionid
			left join vtiger_account as vtiger_accountContacts on vtiger_accountContacts.accountid = vtiger_contactdetails.accountid
			left join vtiger_contactscf on vtiger_contactdetails.contactid = vtiger_contactscf.contactid
			left join vtiger_groups as vtiger_groupsContacts on vtiger_groupsContacts.groupid = vtiger_crmentityContacts.smownerid
			left join vtiger_users as vtiger_usersContacts on vtiger_usersContacts.id = vtiger_crmentityContacts.smownerid ";

		return $query;
	}

	/*
	 * Function to get the relation tables for related modules 
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
		$rel_tables = array (
			"Potentials" => array("vtiger_potential"=>array("accountid","potentialid"),"vtiger_contactdetails"=>"accountid"),
			"Calendar" => array("vtiger_cntactivityrel"=>array("contactid","activityid"),"vtiger_contactdetails"=>"contactid"),
			"HelpDesk" => array("vtiger_troubletickets"=>array("parent_id","ticketid"),"vtiger_contactdetails"=>"contactid"),
			"Quotes" => array("vtiger_quotes"=>array("contactid","quoteid"),"vtiger_contactdetails"=>"contactid"),
			"PurchaseOrder" => array("vtiger_purchaseorder"=>array("contactid","purchaseorderid"),"vtiger_contactdetails"=>"contactid"),
			"SalesOrder" => array("vtiger_salesorder"=>array("contactid","salesorderid"),"vtiger_contactdetails"=>"contactid"),
			"Products" => array("vtiger_seproductsrel"=>array("crmid","productid"),"vtiger_contactdetails"=>"contactid"),
			"Campaigns" => array("vtiger_campaigncontrel"=>array("contactid","campaignid"),"vtiger_contactdetails"=>"contactid"),
			"Documents" => array("vtiger_senotesrel"=>array("crmid","notesid"),"vtiger_contactdetails"=>"contactid"),
		);
		return $rel_tables[$secmodule];
	}
//End

}

?>
