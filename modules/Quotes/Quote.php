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
require_once('include/utils/utils.php');
require_once('include/RelatedListView.php');
// Account is used to store account information.
class Quote extends CRMEntity {
	var $log;
	var $db;


	// Stored fields
	var $id;
	var $mode;
	
		
	var $table_name = "quotes";
	var $tab_name = Array('crmentity','quotes','quotesbillads','quotesshipads','quotescf');
	var $tab_name_index = Array('crmentity'=>'crmid','quotes'=>'quoteid','quotesbillads'=>'quotebilladdressid','quotesshipads'=>'quoteshipaddressid','quotescf'=>'quoteid');
				
	
	var $entity_table = "crmentity";
	
	var $billadr_table = "quotesbillads";

	var $object_name = "Quote";

	var $new_schema = true;
	
	var $module_id = "quoteid";

	var $column_fields = Array();

	var $sortby_fields = Array('subject','crmid','smownerid');		

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'smownerid', 'opportunity_id', 'case_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id', 'parent_name', 'member_id' );

	// This is the list of fields that are in the lists.
	var $list_fields = Array(
				'Quote Id'=>Array('crmentity'=>'crmid'),
				'Subject'=>Array('quotes'=>'subject'),
				'Quote Stage'=>Array('quotes'=>'quotestage'), 
				'Potential Name'=>Array('quotes'=>'potentialid'),
				'Account Name'=>Array('account'=> 'accountid'),
				'Total'=>Array('quotes'=> 'total'),
				'Assigned To'=>Array('crmentity'=>'smownerid')
				);
	
	var $list_fields_name = Array(
				        'Quote Id'=>'',
				        'Subject'=>'subject',
				        'Quote Stage'=>'quotestage',
				        'Potential Name'=>'potential_id',
					'Account Name'=>'account_id',
					'Total'=>'hdnGrandTotal',
				        'Assigned To'=>'assigned_user_id'
				      );
	var $list_link_field= 'subject';

	var $record_id;
	var $list_mode;
        var $popup_type;

	var $search_fields = Array(
				'Quote Id'=>Array('crmentity'=>'crmid'),
				'Subject'=>Array('quotes'=>'subject'),
				'Account Name'=>Array('quotes'=>'accountid'),
				'Quote Stage'=>Array('quotes'=>'quotestage'), 
				);
	
	var $search_fields_name = Array(
					'Quote Id'=>'',
				        'Subject'=>'subject',
				        'Account Name'=>'account_id',
				        'Quote Stage'=>'quotestage',
				      );

	// This is the list of fields that are required.
	var $required_fields =  array("accountname"=>1);

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'ASC';

	function Quote() {
		$this->log =LoggerManager::getLogger('quote');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Quotes');
	}

	function get_salesorder($id)
	{
		require_once('modules/SalesOrder/SalesOrder.php');
        $focus = new SalesOrder();
 
		$button = '';

		$returnset = '&return_module=Quotes&return_action=DetailView&return_id='.$id;

		$query = "select crmentity.*, salesorder.*, quotes.subject as quotename, account.accountname from salesorder inner join crmentity on crmentity.crmid=salesorder.salesorderid left outer join quotes on quotes.quoteid=salesorder.quoteid left outer join account on account.accountid=salesorder.accountid left join sogrouprelation on salesorder.salesorderid=sogrouprelation.salesorderid left join groups on groups.groupname=sogrouprelation.groupname where crmentity.deleted=0 and salesorder.quoteid = ".$id;
		return GetRelatedList('Quotes','SalesOrder',$focus,$query,$button,$returnset);
	}
	
	function get_activities($id)
	{	
		global $app_strings;
		require_once('modules/Activities/Activity.php');
        $focus = new Activity();

		$button = '';

		$returnset = '&return_module=Quotes&return_action=DetailView&return_id='.$id;

		$query = "SELECT contactdetails.contactid, contactdetails.lastname, contactdetails.firstname, activity.*,seactivityrel.*,crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime, users.user_name,recurringevents.recurringtype from activity inner join seactivityrel on seactivityrel.activityid=activity.activityid inner join crmentity on crmentity.crmid=activity.activityid left join cntactivityrel on cntactivityrel.activityid= activity.activityid left join contactdetails on contactdetails.contactid = cntactivityrel.contactid left join users on users.id=crmentity.smownerid left outer join recurringevents on recurringevents.activityid=activity.activityid left join activitygrouprelation on activitygrouprelation.activityid=crmentity.crmid left join groups on groups.groupname=activitygrouprelation.groupname where seactivityrel.crmid=".$id." and (activitytype='Task' or activitytype='Call' or activitytype='Meeting') and (activity.status is not NULL && activity.status != 'Completed') and (activity.status is not NULL && activity.status != 'Deferred') or (activity.eventstatus !='' && activity.eventstatus = 'Planned')";
		return GetRelatedList('Quotes','Activities',$focus,$query,$button,$returnset);
	}
	function get_history($id)
	{
		$query = "SELECT activity.activityid, activity.subject, activity.status,
				activity.eventstatus, activity.activitytype, contactdetails.contactid,
				contactdetails.firstname,	contactdetails.lastname, crmentity.modifiedtime,
				crmentity.createdtime, crmentity.description, users.user_name
			from activity
				inner join seactivityrel on seactivityrel.activityid=activity.activityid
				inner join crmentity on crmentity.crmid=activity.activityid
				left join cntactivityrel on cntactivityrel.activityid= activity.activityid
				left join contactdetails on contactdetails.contactid= cntactivityrel.contactid
				inner join users on crmentity.smcreatorid= users.id
				left join activitygrouprelation on activitygrouprelation.activityid=activity.activityid
                                left join groups on groups.groupname=activitygrouprelation.groupname
			where (activity.activitytype = 'Meeting' or activity.activitytype='Call' or activity.activitytype='Task')
  				and (activity.status = 'Completed' or activity.status = 'Deferred' or (activity.eventstatus !='Planned' and activity.eventstatus != ''))
	 	        	and seactivityrel.crmid=".$id;
		//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php

		return getHistory('Quotes',$query,$id);	
	}
}

?>
