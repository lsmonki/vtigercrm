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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Quotes/Quote.php,v 1.11 2005/07/15 12:12:51 mickie Exp $
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
require_once('include/utils.php');

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

	var $sortby_fields = Array('subject','crmid');		

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

	function Quote() {
		$this->log =LoggerManager::getLogger('quote');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Quotes');
	}

	function create_tables () {
          /*
		$query = 'CREATE TABLE '.$this->table_name.' ( ';
		$query .='id char(36) NOT NULL';
		$query .=', date_entered datetime NOT NULL';
		$query .=', date_modified datetime NOT NULL';
		$query .=', modified_user_id char(36) NOT NULL';
		$query .=', assigned_user_id char(36)';
		$query .=', name char(150)';
		$query .=', parent_id char(36)';
		$query .=', account_type char(25)';
		$query .=', industry char(25)';
		$query .=', annual_revenue char(25)';
		$query .=', phone_fax char(25)';
		$query .=', billing_address_street char(150)';
		$query .=', billing_address_city char(100)';
		$query .=', billing_address_state char(100)';
		$query .=', billing_address_postalcode char(20)';
		$query .=', billing_address_country char(100)';
		$query .=', description text';
		$query .=', rating char(25)';
		$query .=', phone_office char(25)';
		$query .=', phone_alternate char(25)';
		$query .=', email1 char(100)';
		$query .=', email2 char(100)';
		$query .=', website char(255)';
		$query .=', ownership char(100)';
		$query .=', employees char(10)';
		$query .=', sic_code char(10)';
		$query .=', ticker_symbol char(10)';
		$query .=', shipping_address_street char(150)';
		$query .=', shipping_address_city char(100)';
		$query .=', shipping_address_state char(100)';
		$query .=', shipping_address_postalcode char(20)';
		$query .=', shipping_address_country char(100)';
		$query .=', deleted bool NOT NULL default 0';
		$query .=', PRIMARY KEY ( id ) )';

		

		$this->db->query($query);
	//TODO Clint 4/27 - add exception handling logic here if the table can't be created.
        */

	}

	function drop_tables () {
          /*
		$query = 'DROP TABLE IF EXISTS '.$this->table_name;

		

		$this->db->query($query);

	//TODO Clint 4/27 - add exception handling logic here if the table can't be dropped.
        */
	}

	function get_summary_text()
	{
		return $this->name;
	}
	function get_salesorder($id)
	{
		$query = "select crmentity.*, salesorder.*, quotes.subject as quotename, account.accountname from salesorder inner join crmentity on crmentity.crmid=salesorder.salesorderid left outer join quotes on quotes.quoteid=salesorder.quoteid left outer join account on account.accountid=salesorder.accountid where crmentity.deleted=0 and salesorder.quoteid = ".$id;
		renderRelatedOrders($query,$id);	
	}
	function get_activities($id)
	{
		$query = "SELECT contactdetails.contactid, contactdetails.lastname, contactdetails.firstname, activity.*,seactivityrel.*,crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime, users.user_name,recurringevents.recurringtype from activity inner join seactivityrel on seactivityrel.activityid=activity.activityid inner join crmentity on crmentity.crmid=activity.activityid left join cntactivityrel on cntactivityrel.activityid= activity.activityid left join contactdetails on contactdetails.contactid = cntactivityrel.contactid left join users on users.id=crmentity.smownerid left outer join recurringevents on recurringevents.activityid=activity.activityid where seactivityrel.crmid=".$id." and (activitytype='Task' or activitytype='Call' or activitytype='Meeting') and ( activity.status is NULL || activity.status != 'Completed' ) and (  activity.eventstatus is NULL ||  activity.eventstatus != 'Held')";
		renderRelatedActivities($query,$id);
	}
	function get_history($id)
	{
		$query = "SELECT activity.activityid, activity.subject, activity.status, activity.eventstatus, activity.activitytype, activity.description, contactdetails.contactid, contactdetails.firstname, contactdetails.lastname, crmentity.modifiedtime from activity inner join seactivityrel on seactivityrel.activityid=activity.activityid inner join crmentity on crmentity.crmid=activity.activityid left join cntactivityrel on cntactivityrel.activityid= activity.activityid left join contactdetails on contactdetails.contactid= cntactivityrel.contactid where (activity.activitytype = 'Meeting' or activity.activitytype='Call' or activity.activitytype='Task') and (activity.status='Completed' or activity.eventstatus='Held') and seactivityrel.crmid=".$id;
		renderRelatedHistory($query,$id);
	}
}

?>
