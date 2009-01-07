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
require_once('user_privileges/default_module_view.php');

// Account is used to store vtiger_account information.
class SalesOrder extends CRMEntity {
	var $log;
	var $db;

	var $table_name = "vtiger_salesorder";
	var $table_index= 'salesorderid';
	var $tab_name = Array('vtiger_crmentity','vtiger_salesorder','vtiger_sobillads','vtiger_soshipads','vtiger_salesordercf','vtiger_invoice_recurring_info');
	var $tab_name_index = Array('vtiger_crmentity'=>'crmid','vtiger_salesorder'=>'salesorderid','vtiger_sobillads'=>'sobilladdressid','vtiger_soshipads'=>'soshipaddressid','vtiger_salesordercf'=>'salesorderid','vtiger_invoice_recurring_info'=>'salesorderid');
	
	var $entity_table = "vtiger_crmentity";
	
	var $billadr_table = "vtiger_sobillads";

	var $object_name = "SalesOrder";

	var $new_schema = true;
	
	var $update_product_array = Array();

	var $column_fields = Array();

	var $sortby_fields = Array('subject','smownerid','accountname','lastname');		

	// This is used to retrieve related vtiger_fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'smownerid', 'opportunity_id', 'case_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id', 'parent_name', 'member_id' );

	// This is the list of vtiger_fields that are in the lists.
	var $list_fields = Array(
				// Module Sequence Numbering
				//'Order No'=>Array('crmentity'=>'crmid'),
				'Order No'=>Array('salesorder','salesorder_no'),
				// END
				'Subject'=>Array('salesorder'=>'subject'),
				'Account Name'=>Array('account'=>'accountid'), 
				'Quote Name'=>Array('quotes'=>'quoteid'), 
				'Total'=>Array('salesorder'=>'total'),
				'Assigned To'=>Array('crmentity'=>'smownerid')
				);
	
	var $list_fields_name = Array(
				        'Order No'=>'salesorder_no',
				        'Subject'=>'subject',
				        'Account Name'=>'account_id',
				        'Quote Name'=>'quote_id',
					'Total'=>'hdnGrandTotal',
				        'Assigned To'=>'assigned_user_id'
				      );
	var $list_link_field= 'subject';

	var $search_fields = Array(
				'Order No'=>Array('salesorder'=>'salesorder_no'),
				'Subject'=>Array('salesorder'=>'subject'),
				'Account Name'=>Array('account'=>'accountid'),
				'Quote Name'=>Array('salesorder'=>'quoteid') 
				);
	
	var $search_fields_name = Array(
					'Order No'=>'salesorder_no',
				        'Subject'=>'subject',
				        'Account Name'=>'account_id',
				        'Quote Name'=>'quote_id'
				      );

	// This is the list of vtiger_fields that are required.
	var $required_fields =  array("accountname"=>1);

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'subject';
	var $default_sort_order = 'ASC';
	//var $groupTable = Array('vtiger_sogrouprelation','salesorderid');

	/** Constructor Function for SalesOrder class
	 *  This function creates an instance of LoggerManager class using getLogger method
	 *  creates an instance for PearDatabase class and get values for column_fields array of SalesOrder class.
	 */
	function SalesOrder() {
		$this->log =LoggerManager::getLogger('SalesOrder');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('SalesOrder');
	}

	function save_module($module)
	{
	
		//Checking if quote_id is present and updating the quote status
		if($this->column_fields["quote_id"] != '')
		{
        		$qt_id = $this->column_fields["quote_id"];
        		$query1 = "update vtiger_quotes set quotestage='Accepted' where quoteid=?";
        		$this->db->pquery($query1, array($qt_id));
		}

		//in ajax save we should not call this function, because this will delete all the existing product values
		if($_REQUEST['action'] != 'SalesOrderAjax' && $_REQUEST['ajxaction'] != 'DETAILVIEW' && $_REQUEST['action'] != 'MassEditSave')
		{
			//Based on the total Number of rows we will save the product relationship with this entity
			saveInventoryProductDetails(&$this, 'SalesOrder');	
		}
		
		// Update the currency id and the conversion rate for the sales order
		$update_query = "update vtiger_salesorder set currency_id=?, conversion_rate=? where salesorderid=?";
		$update_params = array($this->column_fields['currency_id'], $this->column_fields['conversion_rate'], $this->id); 
		$this->db->pquery($update_query, $update_params);
	}	
	
	/**	Function used to get the sort order for Sales Order listview
	 *	@return string	$sorder	- first check the $_REQUEST['sorder'] if request value is empty then check in the $_SESSION['SALESORDER_SORT_ORDER'] if this session value is empty then default sort order will be returned. 
	 */
	function getSortOrder()
	{
		global $log;
                $log->debug("Entering getSortOrder() method ...");	
		if(isset($_REQUEST['sorder'])) 
			$sorder = $_REQUEST['sorder'];
		else
			$sorder = (($_SESSION['SALESORDER_SORT_ORDER'] != '')?($_SESSION['SALESORDER_SORT_ORDER']):($this->default_sort_order));
		$log->debug("Exiting getSortOrder() method ...");
		return $sorder;
	}

	/**	Function used to get the order by value for Sales Order listview
	 *	@return string	$order_by  - first check the $_REQUEST['order_by'] if request value is empty then check in the $_SESSION['SALESORDER_ORDER_BY'] if this session value is empty then default order by will be returned. 
	 */
	function getOrderBy()
	{
		global $log;
                $log->debug("Entering getOrderBy() method ...");
		if (isset($_REQUEST['order_by'])) 
			$order_by = $_REQUEST['order_by'];
		else
			$order_by = (($_SESSION['SALESORDER_ORDER_BY'] != '')?($_SESSION['SALESORDER_ORDER_BY']):($this->default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}	

	/** Function to get activities associated with the Sales Order
	 *  This function accepts the id as arguments and execute the MySQL query using the id
	 *  and sends the query and the id as arguments to renderRelatedActivities() method
	 */
	function get_activities($id)
	{
		global $log,$singlepane_view;
		$log->debug("Entering get_activities(".$id.") method ...");
		global $app_strings;
	       	require_once('modules/Calendar/Activity.php'); 
		$focus = new Activity();

		$button = '';

		if($singlepane_view == 'true')
			$returnset = '&return_module=SalesOrder&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=SalesOrder&return_action=CallRelatedList&return_id='.$id;

		$query = "SELECT case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name,vtiger_contactdetails.lastname, vtiger_contactdetails.firstname, vtiger_contactdetails.contactid, vtiger_activity.*,vtiger_seactivityrel.*,vtiger_crmentity.crmid, vtiger_crmentity.smownerid, vtiger_crmentity.modifiedtime from vtiger_activity inner join vtiger_seactivityrel on vtiger_seactivityrel.activityid=vtiger_activity.activityid inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_activity.activityid left join vtiger_cntactivityrel on vtiger_cntactivityrel.activityid= vtiger_activity.activityid left join vtiger_contactdetails on vtiger_contactdetails.contactid = vtiger_cntactivityrel.contactid left join vtiger_users on vtiger_users.id=vtiger_crmentity.smownerid left join vtiger_groups on vtiger_groups.groupid=vtiger_crmentity.smownerid where vtiger_seactivityrel.crmid=".$id." and activitytype='Task' and vtiger_crmentity.deleted=0 and (vtiger_activity.status is not NULL and vtiger_activity.status != 'Completed') and (vtiger_activity.status is not NULL and vtiger_activity.status !='Deferred')";
		$log->debug("Exiting get_activities method ...");
		return GetRelatedList('SalesOrder','Calendar',$focus,$query,$button,$returnset);
	}

	/** Function to get the activities history associated with the Sales Order
	 *  This function accepts the id as arguments and execute the MySQL query using the id
	 *  and sends the query and the id as arguments to renderRelatedHistory() method
	 */
	function get_history($id)
	{
		global $log;
		$log->debug("Entering get_history(".$id.") method ...");
		$query = "SELECT vtiger_contactdetails.lastname, vtiger_contactdetails.firstname, vtiger_contactdetails.contactid,vtiger_activity.*, vtiger_seactivityrel.*, vtiger_crmentity.crmid, vtiger_crmentity.smownerid, vtiger_crmentity.modifiedtime,	vtiger_crmentity.createdtime, vtiger_crmentity.description, case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name
			from vtiger_activity
				inner join vtiger_seactivityrel on vtiger_seactivityrel.activityid=vtiger_activity.activityid
				inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_activity.activityid
				left join vtiger_cntactivityrel on vtiger_cntactivityrel.activityid= vtiger_activity.activityid
				left join vtiger_contactdetails on vtiger_contactdetails.contactid = vtiger_cntactivityrel.contactid
                                left join vtiger_groups on vtiger_groups.groupid=vtiger_crmentity.smownerid
				left join vtiger_users on vtiger_users.id=vtiger_crmentity.smownerid
			where activitytype='Task'
				and (vtiger_activity.status = 'Completed' or vtiger_activity.status = 'Deferred')
				and vtiger_seactivityrel.crmid=".$id."
                                and vtiger_crmentity.deleted = 0";
		//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php

		$log->debug("Exiting get_history method ...");
		return getHistory('SalesOrder',$query,$id);
	}

	/** Function to get vtiger_attachments associated with the Sales Order
	 *  This function accepts the id as arguments and execute the MySQL query using the id
	 *  and sends the query and the id as arguments to renderRelatedAttachments() method.
	 */
	function get_attachments($id)
	{
		global $log,$current_user;
		$tab_id=getTabid('Documents');
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
        {
			$sec_parameter=getListViewSecurityParameter('Documents');
        }
		$log->debug("Entering get_attachments(".$id.") method ...");

		$query = "select vtiger_notes.title,'Documents      ' as ActivityType, vtiger_notes.filename,
 		vtiger_attachments.type as FileType,crm2.modifiedtime as lastmodified,
 		vtiger_seattachmentsrel.attachmentsid as attachmentsid, vtiger_notes.notesid as crmid,
 		vtiger_notes.notecontent as description, vtiger_users.user_name
		from vtiger_notes
			inner join vtiger_senotesrel on vtiger_senotesrel.notesid= vtiger_notes.notesid
			inner join vtiger_crmentity on vtiger_crmentity.crmid= vtiger_senotesrel.crmid
			inner join vtiger_crmentity crm2 on crm2.crmid=vtiger_notes.notesid and crm2.deleted=0
			LEFT JOIN vtiger_groups
				ON vtiger_groups.groupid = vtiger_crmentity.smownerid			
			left join vtiger_seattachmentsrel  on vtiger_seattachmentsrel.crmid =vtiger_notes.notesid
			left join vtiger_attachments on vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid
			inner join vtiger_users on vtiger_crmentity.smownerid= vtiger_users.id
		where vtiger_crmentity.crmid=".$id;
		$query .= $sec_parameter;
		$query .= ' union all ';

		$query .= "select vtiger_attachments.subject as title ,'Attachments' as ActivityType,
 		vtiger_attachments.name as filename, vtiger_attachments.type as FileType, crm2.modifiedtime as lastmodified,
 		vtiger_attachments.attachmentsid as attachmentsid, vtiger_seattachmentsrel.attachmentsid as crmid,
		vtiger_attachments.description, vtiger_users.user_name
		from vtiger_attachments
			inner join vtiger_seattachmentsrel on vtiger_seattachmentsrel.attachmentsid= vtiger_attachments.attachmentsid
			inner join vtiger_crmentity on vtiger_crmentity.crmid= vtiger_seattachmentsrel.crmid
			inner join vtiger_crmentity crm2 on crm2.crmid=vtiger_attachments.attachmentsid
			inner join vtiger_users on vtiger_crmentity.smcreatorid= vtiger_users.id
		where vtiger_crmentity.crmid=".$id;

		$log->debug("Exiting get_attachments method ...");
		return getAttachmentsAndNotes('SalesOrder',$query,$id,$sid='salesorderid');
	}

	/** Function to get the invoices associated with the Sales Order
	 *  This function accepts the id as arguments and execute the MySQL query using the id
	 *  and sends the query and the id as arguments to renderRelatedInvoices() method.
	 */
	function get_invoices($id)
	{
		global $log,$singlepane_view;	
		$log->debug("Entering get_invoices(".$id.") method ...");
		require_once('modules/Invoice/Invoice.php');

		$focus = new Invoice();
	
		$button = '';
		if($singlepane_view == 'true')
			$returnset = '&return_module=SalesOrder&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=SalesOrder&return_action=CallRelatedList&return_id='.$id;

			$query = "select vtiger_crmentity.*, vtiger_invoice.*, vtiger_account.accountname, vtiger_salesorder.subject as salessubject, case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name
				from vtiger_invoice 
				inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_invoice.invoiceid 
				left outer join vtiger_account on vtiger_account.accountid=vtiger_invoice.accountid 
				inner join vtiger_salesorder on vtiger_salesorder.salesorderid=vtiger_invoice.salesorderid 
				left join vtiger_users on vtiger_users.id=vtiger_crmentity.smownerid
				left join vtiger_groups on vtiger_groups.groupid=vtiger_crmentity.smownerid 
				where vtiger_crmentity.deleted=0 and vtiger_salesorder.salesorderid=".$id;

		$log->debug("Exiting get_invoices method ...");
		return GetRelatedList('SalesOrder','Invoice',$focus,$query,$button,$returnset);
	
	}

	/**	Function used to get the Status history of the Sales Order
	 *	@param $id - salesorder id
	 *	@return $return_data - array with header and the entries in format Array('header'=>$header,'entries'=>$entries_list) where as $header and $entries_list are arrays which contains header values and all column values of all entries
	 */
	function get_sostatushistory($id)
	{	
		global $log;
		$log->debug("Entering get_sostatushistory(".$id.") method ...");

		global $adb;
		global $mod_strings;
		global $app_strings;

		$query = 'select vtiger_sostatushistory.*, vtiger_salesorder.* from vtiger_sostatushistory inner join vtiger_salesorder on vtiger_salesorder.salesorderid = vtiger_sostatushistory.salesorderid inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_salesorder.salesorderid where vtiger_crmentity.deleted = 0 and vtiger_salesorder.salesorderid = ?';
		$result=$adb->pquery($query, array($id));
		$noofrows = $adb->num_rows($result);

		$header[] = $app_strings['Order No'];
		$header[] = $app_strings['LBL_ACCOUNT_NAME'];
		$header[] = $app_strings['LBL_AMOUNT'];
		$header[] = $app_strings['LBL_SO_STATUS'];
		$header[] = $app_strings['LBL_LAST_MODIFIED'];

		//Getting the field permission for the current user. 1 - Not Accessible, 0 - Accessible
		//Account Name , Total are mandatory fields. So no need to do security check to these fields.
		global $current_user;

		//If field is accessible then getFieldVisibilityPermission function will return 0 else return 1
		$sostatus_access = (getFieldVisibilityPermission('SalesOrder', $current_user->id, 'sostatus') != '0')? 1 : 0;
		$picklistarray = getAccessPickListValues('SalesOrder');

		$sostatus_array = ($sostatus_access != 1)? $picklistarray['sostatus']: array();
		//- ==> picklist field is not permitted in profile
		//Not Accessible - picklist is permitted in profile but picklist value is not permitted
		$error_msg = ($sostatus_access != 1)? 'Not Accessible': '-';

		while($row = $adb->fetch_array($result))
		{
			$entries = Array();

			// Module Sequence Numbering			
			//$entries[] = $row['salesorderid'];
			$entries[] = $row['salesorder_no'];
			// END
			$entries[] = $row['accountname'];
			$entries[] = $row['total'];
			$entries[] = (in_array($row['sostatus'], $sostatus_array))? $row['sostatus']: $error_msg;
			$entries[] = getDisplayDate($row['lastmodified']);

			$entries_list[] = $entries;
		}

		$return_data = Array('header'=>$header,'entries'=>$entries_list);

	 	$log->debug("Exiting get_sostatushistory method ...");

		return $return_data;
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
	    $query = " left join $tabname as $tmpname on $tmpname.$prifieldname = $condvalue  and $tmpname.$secfieldname IN (SELECT salesorderid from vtiger_salesorder)";
		$query .= " left join vtiger_salesorder as vtiger_salesorderSalesOrder on vtiger_salesorderSalesOrder.salesorderid = $tmpname.$secfieldname
			left join vtiger_crmentity as vtiger_crmentitySalesOrder on vtiger_crmentitySalesOrder.crmid=vtiger_salesorderSalesOrder.salesorderid and vtiger_crmentitySalesOrder.deleted=0
			left join vtiger_salesorder on vtiger_salesorder.salesorderid = vtiger_crmentitySalesOrder.crmid
			left join vtiger_salesordercf on vtiger_salesorder.salesorderid = vtiger_salesordercf.salesorderid 
			left join vtiger_sobillads on vtiger_salesorder.salesorderid=vtiger_sobillads.sobilladdressid
			left join vtiger_soshipads on vtiger_salesorder.salesorderid=vtiger_soshipads.soshipaddressid
			left join vtiger_groups as vtiger_groupsSalesOrder on vtiger_groupsSalesOrder.groupid = vtiger_crmentitySalesOrder.smownerid
			left join vtiger_users as vtiger_usersSalesOrder on vtiger_usersSalesOrder.id = vtiger_crmentitySalesOrder.smownerid
			left join vtiger_potential as vtiger_potentialRelSalesOrder on vtiger_potentialRelSalesOrder.potentialid = vtiger_salesorder.potentialid
			left join vtiger_contactdetails as vtiger_contactdetailsSalesOrder on vtiger_salesorder.contactid = vtiger_contactdetailsSalesOrder.contactid
			left join vtiger_invoice_recurring_info on vtiger_salesorder.salesorderid = vtiger_invoice_recurring_info.salesorderid
			left join vtiger_quotes as vtiger_quotesSalesOrder on vtiger_salesorder.quoteid = vtiger_quotesSalesOrder.quoteid
			left join vtiger_account as vtiger_accountSalesOrder on vtiger_accountSalesOrder.accountid = vtiger_salesorder.accountid ";

		return $query;
	}

	/*
	 * Function to get the relation tables for related modules 
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
		$rel_tables = array (
			"Calendar" =>array("vtiger_seactivityrel"=>array("crmid","activityid"),"vtiger_salesorder"=>"salesorderid"),
			"Invoice" =>array("vtiger_invoice"=>array("salesorderid","invoiceid"),"vtiger_salesorder"=>"salesorderid"),
		);
		return $rel_tables[$secmodule];
	}
	
}

?>
