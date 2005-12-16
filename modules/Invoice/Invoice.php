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
require_once('include/utils.php');

// Account is used to store account information.
class Invoice extends CRMEntity {
	var $log;
	var $db;


	// Stored fields
	var $id;
	var $mode;
	
		
	var $table_name = "invoice";
	var $tab_name = Array('crmentity','invoice','invoicebillads','invoiceshipads','invoicecf');
	var $tab_name_index = Array('crmentity'=>'crmid','invoice'=>'invoiceid','invoicebillads'=>'invoicebilladdressid','invoiceshipads'=>'invoiceshipaddressid','invoicecf'=>'invoiceid');
				
	
	var $entity_table = "crmentity";
	
	var $billadr_table = "invoicebillads";

	var $object_name = "Invoice";

	var $new_schema = true;
	
	var $module_id = "invoiceid";

	var $column_fields = Array();

	var $sortby_fields = Array('subject','crmid','invoicestatus','smownerid');		

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'smownerid', 'opportunity_id', 'case_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id', 'parent_name', 'member_id' );

	// This is the list of fields that are in the lists.
	var $list_fields = Array(
				'Invoice Id'=>Array('crmentity'=>'crmid'),
				'Subject'=>Array('invoice'=>'subject'),
				'Sales Order'=>Array('invoice'=>'salesorderid'),
				'Status'=>Array('invoice'=>'invoicestatus'),
				'Total'=>Array('invoice'=>'total'),
				'Assigned To'=>Array('crmentity'=>'smownerid')
				);
	
	var $list_fields_name = Array(
				        'Invoice Id'=>'',
				        'Subject'=>'subject',
				        'Sales Order'=>'salesorder_id',
				        'Status'=>'invoicestatus',
				        'Total'=>'hdnGrandTotal',
				        'Assigned To'=>'assigned_user_id'
				      );
	var $list_link_field= 'subject';

	var $record_id;
	var $list_mode;
        var $popup_type;

	var $search_fields = Array(
				'Invoice Id'=>Array('crmentity'=>'crmid'),
				'Subject'=>Array('purchaseorder'=>'subject'), 
				);
	
	var $search_fields_name = Array(
				        'Invoice Id'=>'',
				        'Subject'=>'subject',
				      );

	// This is the list of fields that are required.
	var $required_fields =  array("accountname"=>1);

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'ASC';

	function Invoice() {
		$this->log =LoggerManager::getLogger('Invoice');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Invoice');
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
	function get_activities($id)
	{
		$query = "SELECT contactdetails.lastname, contactdetails.firstname, contactdetails.contactid, activity.*,seactivityrel.*,crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime, users.user_name from activity inner join seactivityrel on seactivityrel.activityid=activity.activityid inner join crmentity on crmentity.crmid=activity.activityid left join cntactivityrel on cntactivityrel.activityid= activity.activityid left join contactdetails on contactdetails.contactid = cntactivityrel.contactid left join users on users.id=crmentity.smownerid where seactivityrel.crmid=".$id." and (activitytype='Task' or activitytype='Call' or activitytype='Meeting') and crmentity.deleted=0 and (activity.status is not NULL && activity.status != 'Completed') and (activity.status is not NULL && activity.status != 'Deferred') or (activity.eventstatus != '' &&  activity.eventstatus = 'Planned')";
		renderRelatedActivities($query,$id);
	}
	function get_history($id)
	{
		// Armando Lüscher 18.10.2005 -> §visibleDescription
		// Desc: Inserted crmentity.createdtime, activity.description, users.user_name
		// Inserted inner join users on crmentity.smcreatorid=users.id
		// Inserted order by createdtime desc
		$query = "SELECT contactdetails.lastname, contactdetails.firstname, contactdetails.contactid,
			activity.*,seactivityrel.*,crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime,
			crmentity.createdtime, users.user_name
		from activity
			inner join seactivityrel on seactivityrel.activityid=activity.activityid
			inner join crmentity on crmentity.crmid=activity.activityid
			left join cntactivityrel on cntactivityrel.activityid= activity.activityid
			left join contactdetails on contactdetails.contactid = cntactivityrel.contactid
			inner join users on crmentity.smcreatorid= users.id
		where (activity.activitytype = 'Meeting' or activity.activitytype='Call' or activity.activitytype='Task')
			and (activity.status = 'Completed' or activity.status = 'Deferred' or (activity.eventstatus != 'Planned' and activity.eventstatus != ''))
			and seactivityrel.crmid=".$id."
		order by createdtime desc";
		renderRelatedHistory($query,$id);
	}
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
		$query .= "select attachments.description  title ,'Attachments'  ActivityType,
			attachments.name filename, attachments.type FileType, crm2.modifiedtime lastmodified,
			attachments.attachmentsid attachmentsid, seattachmentsrel.attachmentsid crmid,
			crm2.createdtime, attachments.description, users.user_name
		from attachments
			inner join seattachmentsrel on seattachmentsrel.attachmentsid= attachments.attachmentsid
			inner join crmentity on crmentity.crmid= seattachmentsrel.crmid
			inner join crmentity crm2 on crm2.crmid=attachments.attachmentsid
			inner join users on crm2.smcreatorid= users.id
		where crmentity.crmid=".$id;
		renderRelatedAttachments($query,$id);
	}



}

?>
