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

// Account is used to store account information.
class Order extends CRMEntity {
	var $log;
	var $db;


	// Stored fields
	var $id;
	var $mode;
	
		
	var $table_name = "purchaseorder";
	var $tab_name = Array('crmentity','purchaseorder','pobillads','poshipads','purchaseordercf');
	var $tab_name_index = Array('crmentity'=>'crmid','purchaseorder'=>'purchaseorderid','pobillads'=>'pobilladdressid','poshipads'=>'poshipaddressid','purchaseordercf'=>'purchaseorderid');
				
	
	var $entity_table = "crmentity";
	
	var $billadr_table = "pobillads";

	var $object_name = "Order";

	var $new_schema = true;
	
	var $module_id = "purchaseorderid";

	var $column_fields = Array();

	var $sortby_fields = Array('subject','tracking_no','smownerid');		

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'smownerid', 'opportunity_id', 'case_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id', 'parent_name', 'member_id' );

	// This is the list of fields that are in the lists.
	var $list_fields = Array(
				'Order Id'=>Array('crmentity'=>'crmid'),
				'Subject'=>Array('purchaseorder'=>'subject'),
				'Vendor Name'=>Array('purchaseorder'=>'vendorid'), 
				'Tracking Number'=>Array('purchaseorder'=> 'tracking_no'),
				'Assigned To'=>Array('crmentity'=>'smownerid')
				);
	
	var $list_fields_name = Array(
				        'Order Id'=>'',
				        'Subject'=>'subject',
				        'Vendor Name'=>'vendor_id',
					'Tracking Number'=>'tracking_no',
				        'Assigned To'=>'assigned_user_id'
				      );
	var $list_link_field= 'subject';

	var $record_id;
	var $list_mode;
        var $popup_type;

	var $search_fields = Array(
				'Order Id'=>Array('crmentity'=>'crmid'),
				'Subject'=>Array('purchaseorder'=>'subject'), 
				);
	
	var $search_fields_name = Array(
				        'Order Id'=>'',
				        'Subject'=>'subject',
				      );

	// This is the list of fields that are required.
	var $required_fields =  array("accountname"=>1);

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'subject';
	var $default_sort_order = 'ASC';

/** Constructor Function for Order class
 *  This function creates an instance of LoggerManager class using getLogger method
 *  creates an instance for PearDatabase class and get values for column_fields array of Order class.
*/
	function Order() {
		$this->log =LoggerManager::getLogger('PurchaseOrder');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('PurchaseOrder');
	}

/** Function to get activities associated with the id
 *  This function accepts the id as arguments and execute the MySQL query using the id
 *  and sends the query and the id as arguments to renderRelatedActivities() method 
*/
	function get_activities($id)
	{
		global $app_strings;
		require_once('modules/Activities/Activity.php');
		$focus = new Activity();

		$button = '';

		$returnset = '&return_module=PurchaseOrder&return_action=DetailView&return_id='.$id;

		$query = "SELECT contactdetails.lastname, contactdetails.firstname, contactdetails.contactid,activity.*,seactivityrel.*,crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime, users.user_name from activity inner join seactivityrel on seactivityrel.activityid=activity.activityid inner join crmentity on crmentity.crmid=activity.activityid left join cntactivityrel on cntactivityrel.activityid= activity.activityid left join contactdetails on contactdetails.contactid = cntactivityrel.contactid left join users on users.id=crmentity.smownerid left join activitygrouprelation on activitygrouprelation.activityid=crmentity.crmid left join groups on groups.groupname=activitygrouprelation.groupname where seactivityrel.crmid=".$id." and (activitytype='Task' or activitytype='Call' or activitytype='Meeting') and crmentity.deleted=0 and (activity.status is not NULL && activity.status != 'Completed') and (activity.status is not NULL && activity.status != 'Deferred') or (activity.eventstatus != '' &&  activity.eventstatus = 'Planned')";
		return GetRelatedList('PurchaseOrder','Activities',$focus,$query,$button,$returnset);
	}

/** Function to get history associated with the id
 *  This function accepts the id as arguments and execute the MySQL query using the id
 *  and sends the query and the id as arguments to renderRelatedHistory() method
*/
	function get_history($id)
	{
		$query = "SELECT contactdetails.lastname, contactdetails.firstname, contactdetails.contactid,
				activity.* ,seactivityrel.*, crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime,
				crmentity.createdtime, crmentity.description, users.user_name
			from activity
				inner join seactivityrel on seactivityrel.activityid=activity.activityid
				inner join crmentity on crmentity.crmid=activity.activityid
				left join cntactivityrel on cntactivityrel.activityid= activity.activityid
				left join contactdetails on contactdetails.contactid = cntactivityrel.contactid
				inner join users on crmentity.smcreatorid= users.id
				left join activitygrouprelation on activitygrouprelation.activityid=activity.activityid
                                left join groups on groups.groupname=activitygrouprelation.groupname
			where (activity.activitytype = 'Meeting' or activity.activitytype='Call' or activity.activitytype='Task')
				and (activity.status = 'Completed' or activity.status = 'Deferred' or (activity.eventstatus != 'Planned' and activity.eventstatus != '' ))
				and seactivityrel.crmid=".$id;
		//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php

		return getHistory('PurchaseOrder',$query,$id);
	}

/** Function to get attachments associated with the id
 *  This function accepts the id as arguments and execute the MySQL query using the id
 *  and sends the query and the id as arguments to renderRelatedAttachments() method.
*/
	function get_attachments($id)
	{
		// Armando Lüscher 18.10.2005 -> §visibleDescription
		// Desc: Inserted crm2.createdtime, notes.notecontent description, users.user_name
		// Inserted inner join users on crm2.smcreatorid= users.id
		$query = "select notes.title, 'Notes      '  ActivityType, notes.filename,
			attachments.type FileType, crm2.modifiedtime lastmodified,
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
			attachments.name  filename, attachments.type  FileType, crm2.modifiedtime  lastmodified,
			attachments.attachmentsid  attachmentsid, seattachmentsrel.attachmentsid crmid,
			crm2.createdtime, attachments.description, users.user_name
		from attachments
			inner join seattachmentsrel on seattachmentsrel.attachmentsid= attachments.attachmentsid
			inner join crmentity on crmentity.crmid= seattachmentsrel.crmid
			inner join crmentity crm2 on crm2.crmid=attachments.attachmentsid
			inner join users on crm2.smcreatorid= users.id
		where crmentity.crmid=".$id."
		order by createdtime desc";
		return getAttachmentsAndNotes('PurchaseOrder',$query,$id,$sid='purchaseorderid');
	}

}

?>
