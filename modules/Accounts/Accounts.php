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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Accounts/Accounts.php,v 1.53 2005/04/28 08:06:45 rank Exp $
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
require_once('modules/Contacts/Contacts.php');
require_once('modules/Potentials/Potentials.php');
require_once('modules/Calendar/Activity.php');
require_once('modules/Notes/Notes.php');
require_once('modules/Emails/Emails.php');
require_once('include/utils/utils.php');
require_once('user_privileges/default_module_view.php');

// Account is used to store vtiger_account information.
class Accounts extends CRMEntity {
	var $log;
	var $db;
	var $table_name = "vtiger_account";
	var $tab_name = Array('vtiger_crmentity','vtiger_account','vtiger_accountbillads','vtiger_accountshipads','vtiger_accountscf');
	var $tab_name_index = Array('vtiger_crmentity'=>'crmid','vtiger_account'=>'accountid','vtiger_accountbillads'=>'accountaddressid','vtiger_accountshipads'=>'accountaddressid','vtiger_accountscf'=>'accountid');

	var $entity_table = "vtiger_crmentity";

	var $column_fields = Array();

	var $sortby_fields = Array('accountname','bill_city','website','phone','smownerid');		

	var $groupTable = Array('vtiger_accountgrouprelation','accountid');
	
	// This is the list of vtiger_fields that are in the lists.
	var $list_fields = Array(
			'Account Name'=>Array('vtiger_account'=>'accountname'),
			'City'=>Array('vtiger_accountbillads'=>'bill_city'), 
			'Website'=>Array('vtiger_account'=>'website'),
			'Phone'=>Array('vtiger_account'=> 'phone'),
			'Assigned To'=>Array('vtiger_crmentity'=>'smownerid')
			);

	var $list_fields_name = Array(
			'Account Name'=>'accountname',
			'City'=>'bill_city',
			'Website'=>'website',
			'Phone'=>'phone',
			'Assigned To'=>'assigned_user_id'
			);
	var $list_link_field= 'accountname';

	var $search_fields = Array(
			'Account Name'=>Array('vtiger_account'=>'accountname'),
			'City'=>Array('vtiger_accountbillads'=>'bill_city'), 
			);

	var $search_fields_name = Array(
			'Account Name'=>'accountname',
			'City'=>'bill_city',
			);
	// This is the list of vtiger_fields that are required
	var $required_fields =  array("accountname"=>1);

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'accountname';
	var $default_sort_order = 'ASC';

	function Accounts() {
		$this->log =LoggerManager::getLogger('account');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Accounts');
	}

	/** Function to handle module specific operations when saving a entity 
	*/
	function save_module($module)
	{
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
			$sorder = (($_SESSION['ACCOUNTS_SORT_ORDER'] != '')?($_SESSION['ACCOUNTS_SORT_ORDER']):($this->default_sort_order));
		$log->debug("Exiting getSortOrder() method ...");
		return $sorder;
	}
	/**
	 * Function to get order by
	 * return string  $order_by    - fieldname(eg: 'accountname')
 	 */
	function getOrderBy()
	{
		global $log;
                $log->debug("Entering getOrderBy() method ...");
		if (isset($_REQUEST['order_by'])) 
			$order_by = $_REQUEST['order_by'];
		else
			$order_by = (($_SESSION['ACCOUNTS_ORDER_BY'] != '')?($_SESSION['ACCOUNTS_ORDER_BY']):($this->default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}	
	// Mike Crowe Mod --------------------------------------------------------


	/** Returns a list of the associated contacts
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function get_contacts($id)
	{	
		global $log, $singlepane_view;
                $log->debug("Entering get_contacts(".$id.") method ...");
		global $mod_strings;

		$focus = new Contacts();

		$button = '';
		if(isPermitted("Contacts",1,"") == 'yes')
		{
			$button .= '<input title="New Contact" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Contacts\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_CONTACT'].'">&nbsp;</td>';
		}

		if($singlepane_view == 'true')
			$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Accounts&return_action=CallRelatedList&return_id='.$id;

		//SQL
		$query = "SELECT vtiger_contactdetails.*,
			vtiger_crmentity.crmid,
                        vtiger_crmentity.smownerid,
			vtiger_account.accountname,
			case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name
			FROM vtiger_contactdetails
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_contactdetails.contactid
			LEFT JOIN vtiger_account
				ON vtiger_account.accountid = vtiger_contactdetails.accountid
			LEFT JOIN vtiger_contactgrouprelation
				ON vtiger_contactdetails.contactid = vtiger_contactgrouprelation.contactid
			LEFT JOIN vtiger_groups
				ON vtiger_groups.groupname = vtiger_contactgrouprelation.groupname
			LEFT JOIN vtiger_users
				ON vtiger_crmentity.smownerid = vtiger_users.id
			WHERE vtiger_crmentity.deleted = 0
			AND vtiger_contactdetails.accountid = ".$id;
		$log->debug("Exiting get_contacts method ...");
		return GetRelatedList('Accounts','Contacts',$focus,$query,$button,$returnset);
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
			$button .= '<input title="New Potential" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Potentials\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_POTENTIAL'].'">';
		}
		if($singlepane_view == 'true')
			$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Accounts&return_action=CallRelatedList&return_id='.$id;

		$query = "SELECT vtiger_potential.potentialid, vtiger_potential.accountid,
			vtiger_potential.potentialname, vtiger_potential.sales_stage,
			vtiger_potential.potentialtype, vtiger_potential.amount,
			vtiger_potential.closingdate, vtiger_potential.potentialtype, vtiger_account.accountname,
			case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name,vtiger_crmentity.crmid, vtiger_crmentity.smownerid
			FROM vtiger_potential
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_potential.potentialid
			LEFT JOIN vtiger_account
				ON vtiger_account.accountid = vtiger_potential.accountid
			LEFT JOIN vtiger_users
				ON vtiger_crmentity.smownerid = vtiger_users.id
			LEFT JOIN vtiger_potentialgrouprelation
				ON vtiger_potential.potentialid = vtiger_potentialgrouprelation.potentialid
			LEFT JOIN vtiger_groups
				ON vtiger_groups.groupname = vtiger_potentialgrouprelation.groupname
			WHERE vtiger_crmentity.deleted = 0
			AND vtiger_potential.accountid = ".$id;
		$log->debug("Exiting get_opportunities method ...");

		return GetRelatedList('Accounts','Potentials',$focus,$query,$button,$returnset);
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

			$button .= '<input title="New Task" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Calendar\';this.form.return_module.value=\'Accounts\';this.form.activity_mode.value=\'Task\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_TASK'].'">&nbsp;';
			$button .= '<input title="New Event" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Calendar\';this.form.return_module.value=\'Accounts\';this.form.activity_mode.value=\'Events\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_EVENT'].'">&nbsp;</td>';
		}
		if($singlepane_view == 'true')
			$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Accounts&return_action=CallRelatedList&return_id='.$id;

		$query = "SELECT vtiger_activity.*, vtiger_cntactivityrel.*,
			vtiger_seactivityrel.*, vtiger_contactdetails.lastname,
			vtiger_contactdetails.firstname,
			vtiger_crmentity.crmid, vtiger_crmentity.smownerid,
			vtiger_crmentity.modifiedtime,
			case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name,
			vtiger_recurringevents.recurringtype
			FROM vtiger_activity
			INNER JOIN vtiger_seactivityrel
				ON vtiger_seactivityrel.activityid = vtiger_activity.activityid
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_activity.activityid
			LEFT JOIN vtiger_cntactivityrel
				ON vtiger_cntactivityrel.activityid = vtiger_activity.activityid
			LEFT JOIN vtiger_contactdetails
		       		ON vtiger_contactdetails.contactid = vtiger_cntactivityrel.contactid
			LEFT JOIN vtiger_users
				ON vtiger_users.id = vtiger_crmentity.smownerid
			LEFT OUTER JOIN vtiger_recurringevents
				ON vtiger_recurringevents.activityid = vtiger_activity.activityid
			LEFT JOIN vtiger_activitygrouprelation
				ON vtiger_activitygrouprelation.activityid = vtiger_crmentity.crmid
			LEFT JOIN vtiger_groups
				ON vtiger_groups.groupname = vtiger_activitygrouprelation.groupname
			WHERE vtiger_seactivityrel.crmid = ".$id."
			AND vtiger_crmentity.deleted = 0
			AND ((vtiger_activity.activitytype='Task' and vtiger_activity.status not in ('Completed','Deferred')) 
			OR (vtiger_activity.activitytype in ('Meeting','Call') and  vtiger_activity.eventstatus not in ('','Held'))) ";
		$log->debug("Exiting get_activities method ...");
		return GetRelatedList('Accounts','Calendar',$focus,$query,$button,$returnset);

	}
	/**
	 * Function to get Account related Task & Event which have activity type Held, Completed or Deferred.
 	 * @param  integer   $id      - accountid
 	 * returns related Task or Event record in array format
 	 */
	function get_history($id)
	{
		global $log;
                $log->debug("Entering get_history(".$id.") method ...");
		$query = "SELECT vtiger_activity.activityid, vtiger_activity.subject,
			vtiger_activity.status, vtiger_activity.eventstatus,
			vtiger_activity.activitytype, vtiger_activity.date_start, vtiger_activity.due_date,
			vtiger_activity.time_start, vtiger_activity.time_end,
			vtiger_crmentity.modifiedtime, vtiger_crmentity.createdtime,
			vtiger_crmentity.description,case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name
			FROM vtiger_activity
			INNER JOIN vtiger_seactivityrel
				ON vtiger_seactivityrel.activityid = vtiger_activity.activityid
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_activity.activityid
			LEFT JOIN vtiger_activitygrouprelation
				ON vtiger_activitygrouprelation.activityid = vtiger_activity.activityid
			LEFT JOIN vtiger_groups
				ON vtiger_groups.groupname = vtiger_activitygrouprelation.groupname
			LEFT JOIN vtiger_users
				ON vtiger_users.id=vtiger_crmentity.smownerid
			WHERE (vtiger_activity.activitytype = 'Meeting'
				OR vtiger_activity.activitytype = 'Call'
				OR vtiger_activity.activitytype = 'Task')
			AND (vtiger_activity.status = 'Completed'
				OR vtiger_activity.status = 'Deferred'
				OR (vtiger_activity.eventstatus = 'Held'
					AND vtiger_activity.eventstatus != ''))
			AND vtiger_seactivityrel.crmid = ".$id."
			AND vtiger_crmentity.deleted = 0";
		//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php
		$log->debug("Exiting get_history method ...");
		return getHistory('Accounts',$query,$id);
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
						$button .= '<input title="New Email" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Emails\';this.form.email_directing_module.value=\'accounts\';this.form.record.value='.$id.';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_EMAIL'].'">';
		}
		if($singlepane_view == 'true')
			$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Accounts&return_action=CallRelatedList&return_id='.$id;

		$log->info("Email Related List for Account Displayed");
		$query = "SELECT case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name,
			vtiger_activity.activityid, vtiger_activity.subject,
			vtiger_activity.activitytype, vtiger_crmentity.modifiedtime,
			vtiger_crmentity.crmid, vtiger_crmentity.smownerid, vtiger_activity.date_start
			FROM vtiger_activity, vtiger_seactivityrel, vtiger_account, vtiger_users, vtiger_crmentity
			LEFT JOIN vtiger_activitygrouprelation
				ON vtiger_activitygrouprelation.activityid=vtiger_crmentity.crmid
			LEFT JOIN vtiger_groups
				ON vtiger_groups.groupname=vtiger_activitygrouprelation.groupname
			WHERE vtiger_seactivityrel.activityid = vtiger_activity.activityid
				AND vtiger_account.accountid = vtiger_seactivityrel.crmid
				AND vtiger_users.id=vtiger_crmentity.smownerid
				AND vtiger_crmentity.crmid = vtiger_activity.activityid
				AND vtiger_account.accountid = ".$id."
				AND vtiger_activity.activitytype='Emails'
				AND vtiger_crmentity.deleted = 0";
		$log->debug("Exiting get_emails method ...");
		return GetRelatedList('Accounts','Emails',$focus,$query,$button,$returnset);
	}	

	/**
	 * Function to get Account related Attachments
 	 * @param  integer   $id      - accountid
 	 * returns related Attachment record in array format
 	 */
	function get_attachments($id)
	{
		 global $log;
                 $log->debug("Entering get_attachments(".$id.") method ...");
		// Armando Lüscher 18.10.2005 -> §visibleDescription
		// Desc: Inserted crm2.createdtime, vtiger_notes.notecontent description, vtiger_users.user_name
		// Inserted inner join vtiger_users on crm2.smcreatorid= vtiger_users.id
		$query = "SELECT vtiger_notes.title, vtiger_notes.notecontent AS description,
			vtiger_notes.filename, vtiger_notes.notesid AS crmid,
				'Notes      ' AS ActivityType,
			vtiger_attachments.type AS FileType,
				crm2.modifiedtime AS lastmodified, 
			vtiger_seattachmentsrel.attachmentsid,
			vtiger_users.user_name
			FROM vtiger_notes
			INNER JOIN vtiger_senotesrel
				ON vtiger_senotesrel.notesid = vtiger_notes.notesid
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_senotesrel.crmid
			INNER JOIN vtiger_crmentity crm2
				ON crm2.crmid = vtiger_notes.notesid
				AND crm2.deleted = 0
			LEFT JOIN vtiger_seattachmentsrel
				ON vtiger_seattachmentsrel.crmid = vtiger_notes.notesid
			LEFT JOIN vtiger_attachments
				ON vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid
			INNER JOIN vtiger_users
				ON crm2.smcreatorid = vtiger_users.id
			WHERE vtiger_crmentity.crmid = ".$id."
		 UNION ALL
			SELECT vtiger_attachments.subject AS title, vtiger_attachments.description,
			vtiger_attachments.name AS filename,
			vtiger_seattachmentsrel.attachmentsid AS crmid,
				'Attachments' AS ActivityType,
			vtiger_attachments.type AS FileType,
				crm2.modifiedtime AS lastmodified,
			vtiger_attachments.attachmentsid,
			vtiger_users.user_name
			FROM vtiger_attachments
			INNER JOIN vtiger_seattachmentsrel
				ON vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_seattachmentsrel.crmid
			INNER JOIN vtiger_crmentity crm2
				ON crm2.crmid = vtiger_attachments.attachmentsid
			INNER JOIN vtiger_users
				ON crm2.smcreatorid = vtiger_users.id
			WHERE vtiger_crmentity.crmid = ".$id;
		$log->debug("Exiting get_attachments method ...");
		return getAttachmentsAndNotes('Accounts',$query,$id);
	}
	/**
	* Function to get Account related Quotes
	* @param  integer   $id      - accountid
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
			$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Accounts&return_action=CallRelatedList&return_id='.$id;

		$query = "SELECT case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name,
			vtiger_crmentity.*,
			vtiger_quotes.*,
			vtiger_potential.potentialname,
			vtiger_account.accountname
			FROM vtiger_quotes
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_quotes.quoteid
			LEFT OUTER JOIN vtiger_account
				ON vtiger_account.accountid = vtiger_quotes.accountid
			LEFT OUTER JOIN vtiger_potential
				ON vtiger_potential.potentialid = vtiger_quotes.potentialid
			LEFT JOIN vtiger_quotegrouprelation
				ON vtiger_quotes.quoteid = vtiger_quotegrouprelation.quoteid
			LEFT JOIN vtiger_groups
				ON vtiger_groups.groupname = vtiger_quotegrouprelation.groupname
			LEFT JOIN vtiger_users
				ON vtiger_crmentity.smownerid = vtiger_users.id
			WHERE vtiger_crmentity.deleted = 0
			AND vtiger_account.accountid = ".$id;
		$log->debug("Exiting get_quotes method ...");
		return GetRelatedList('Accounts','Quotes',$focus,$query,$button,$returnset);
	}
	/**
	* Function to get Account related Invoices 
	* @param  integer   $id      - accountid
	* returns related Invoices record in array format
	*/
	function get_invoices($id)
	{
		global $log, $singlepane_view;
                $log->debug("Entering get_invoices(".$id.") method ...");
		global $app_strings;
		require_once('modules/Invoice/Invoice.php');

		$focus = new Invoice();

		$button = '';
		if(isPermitted("Invoice",1,"") == 'yes')
		{
			$button .= '<input title="'.$app_strings['LBL_NEW_INVOICE_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_INVOICE_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Invoice\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_INVOICE_BUTTON'].'">&nbsp;</td>';
		}
		if($singlepane_view == 'true')
			$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Accounts&return_action=CallRelatedList&return_id='.$id;

		$query = "SELECT case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name,
			vtiger_crmentity.*,
			vtiger_invoice.*,
			vtiger_account.accountname,
			vtiger_salesorder.subject AS salessubject
			FROM vtiger_invoice
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_invoice.invoiceid
			LEFT OUTER JOIN vtiger_account
				ON vtiger_account.accountid = vtiger_invoice.accountid
			LEFT OUTER JOIN vtiger_salesorder
				ON vtiger_salesorder.salesorderid = vtiger_invoice.salesorderid
			LEFT JOIN vtiger_invoicegrouprelation
				ON vtiger_invoice.invoiceid = vtiger_invoicegrouprelation.invoiceid
			LEFT JOIN vtiger_groups
				ON vtiger_groups.groupname = vtiger_invoicegrouprelation.groupname
			LEFT JOIN vtiger_users
				ON vtiger_crmentity.smownerid = vtiger_users.id
			WHERE vtiger_crmentity.deleted = 0
			AND vtiger_account.accountid = ".$id;
		$log->debug("Exiting get_invoices method ...");
		return GetRelatedList('Accounts','Invoice',$focus,$query,$button,$returnset);
	}

	/**
	* Function to get Account related SalesOrder 
	* @param  integer   $id      - accountid
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
			$button .= '<input title="'.$app_strings['LBL_NEW_SORDER_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_SORDER_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'SalesOrder\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_SORDER_BUTTON'].'">&nbsp;</td>';
		}

		if($singlepane_view == 'true')
			$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Accounts&return_action=CallRelatedList&return_id='.$id;

		$query = "SELECT vtiger_crmentity.*,
			vtiger_salesorder.*,
			vtiger_quotes.subject AS quotename,
			vtiger_account.accountname,
			case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name
			FROM vtiger_salesorder
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_salesorder.salesorderid
			LEFT OUTER JOIN vtiger_quotes
				ON vtiger_quotes.quoteid = vtiger_salesorder.quoteid
			LEFT OUTER JOIN vtiger_account
				ON vtiger_account.accountid = vtiger_salesorder.accountid
			LEFT JOIN vtiger_sogrouprelation
				ON vtiger_salesorder.salesorderid = vtiger_sogrouprelation.salesorderid
			LEFT JOIN vtiger_groups
				ON vtiger_groups.groupname = vtiger_sogrouprelation.groupname
			LEFT JOIN vtiger_users
				ON vtiger_crmentity.smownerid = vtiger_users.id
			WHERE vtiger_crmentity.deleted = 0
			AND vtiger_salesorder.accountid = ".$id;
		$log->debug("Exiting get_salesorder method ...");		
		return GetRelatedList('Accounts','SalesOrder',$focus,$query,$button,$returnset);
	}
	/**
	* Function to get Account related Tickets
	* @param  integer   $id      - accountid
	* returns related Ticket record in array format
	*/
	function get_tickets($id)
	{
		global $log, $singlepane_view;
                $log->debug("Entering get_tickets(".$id.") method ...");
		global $app_strings;

		$focus = new HelpDesk();
		$button = '';

		$button .= '<td valign="bottom" align="right"><input title="New TICKET" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'HelpDesk\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_TICKET'].'">&nbsp;</td>';
		if($singlepane_view == 'true')
			$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Accounts&return_action=CallRelatedList&return_id='.$id;

		$query = "SELECT case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name, vtiger_users.id,
			vtiger_troubletickets.title, vtiger_troubletickets.ticketid AS crmid,
			vtiger_troubletickets.status, vtiger_troubletickets.priority,
			vtiger_troubletickets.parent_id,
			vtiger_crmentity.smownerid, vtiger_crmentity.modifiedtime
			FROM vtiger_troubletickets
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_troubletickets.ticketid
			LEFT JOIN vtiger_account
				ON vtiger_account.accountid = vtiger_troubletickets.parent_id
			LEFT JOIN vtiger_contactdetails
			        ON vtiger_contactdetails.contactid=vtiger_troubletickets.parent_id
			LEFT JOIN vtiger_users
				ON vtiger_users.id=vtiger_crmentity.smownerid
			LEFT JOIN vtiger_ticketgrouprelation
				ON vtiger_troubletickets.ticketid = vtiger_ticketgrouprelation.ticketid
			LEFT JOIN vtiger_groups
				ON vtiger_groups.groupname = vtiger_ticketgrouprelation.groupname
			WHERE  vtiger_crmentity.deleted = 0 and ( vtiger_troubletickets.parent_id=".$id." or " ;

		$query .= " vtiger_troubletickets.parent_id in(SELECT vtiger_contactdetails.contactid
			FROM vtiger_contactdetails
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_contactdetails.contactid
			LEFT JOIN vtiger_contactgrouprelation
				ON vtiger_contactdetails.contactid = vtiger_contactgrouprelation.contactid
			LEFT JOIN vtiger_groups
				ON vtiger_groups.groupname = vtiger_contactgrouprelation.groupname
			LEFT JOIN vtiger_users
				ON vtiger_crmentity.smownerid = vtiger_users.id
			WHERE vtiger_crmentity.deleted = 0
			AND vtiger_contactdetails.accountid = ".$id;

			
		//Appending the security parameter
		global $current_user;
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
		$tab_id=getTabid('Contacts');
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
		{
			$sec_parameter=getListViewSecurityParameter('Contacts');
			$query .= ' '.$sec_parameter;

		}

		$query .= ") )";
		
		$log->debug("Exiting get_tickets method ...");
		return GetRelatedList('Accounts','HelpDesk',$focus,$query,$button,$returnset);
	}
	/**
	* Function to get Account related Products 
	* @param  integer   $id      - accountid
	* returns related Products record in array format
	*/
	function get_products($id)
	{
		global $log, $singlepane_view;
                $log->debug("Entering get_products(".$id.") method ...");
		require_once('modules/Products/Products.php');
		global $app_strings;

		$focus = new Products();

		$button = '';

		if(isPermitted("Products",1,"") == 'yes')
		{


			$button .= '<input title="New Product" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Products\';this.form.return_module.value=\'Accounts\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_PRODUCT'].'">&nbsp;';
		}
		if($singlepane_view == 'true')
			$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Accounts&return_action=CallRelatedList&return_id='.$id;

		$query = "SELECT vtiger_products.productid, vtiger_products.productname,
			vtiger_products.productcode, vtiger_products.commissionrate,
			vtiger_products.qty_per_unit, vtiger_products.unit_price,
			vtiger_crmentity.crmid, vtiger_crmentity.smownerid
			FROM vtiger_products
			INNER JOIN vtiger_seproductsrel ON vtiger_products.productid = vtiger_seproductsrel.productid and vtiger_seproductsrel.setype='Accounts'
			INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_products.productid
			INNER JOIN vtiger_account ON vtiger_account.accountid = vtiger_seproductsrel.crmid
			WHERE vtiger_crmentity.deleted = 0 AND vtiger_account.accountid = $id";

		$log->debug("Exiting get_products method ...");
		return GetRelatedList('Accounts','Products',$focus,$query,$button,$returnset);
	}

	/** Function to export the account records in CSV Format
	* @param reference variable - where condition is passed when the query is executed
	* Returns Export Accounts Query.
	*/
	function create_export_query($where)
	{
		global $log;
		global $current_user;
                $log->debug("Entering create_export_query(".$where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Accounts", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT $fields_list, vtiger_accountgrouprelation.groupname as 'Assigned To Group',case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name 
	       			FROM ".$this->entity_table."
				INNER JOIN vtiger_account
					ON vtiger_account.accountid = vtiger_crmentity.crmid
				LEFT JOIN vtiger_accountbillads
					ON vtiger_accountbillads.accountaddressid = vtiger_account.accountid
				LEFT JOIN vtiger_accountshipads
					ON vtiger_accountshipads.accountaddressid = vtiger_account.accountid
				LEFT JOIN vtiger_accountscf
					ON vtiger_accountscf.accountid = vtiger_account.accountid
				LEFT JOIN vtiger_accountgrouprelation
                	                ON vtiger_accountgrouprelation.accountid = vtiger_account.accountid
	                        LEFT JOIN vtiger_groups
                        	        ON vtiger_groups.groupname = vtiger_accountgrouprelation.groupname
				LEFT JOIN vtiger_users
					ON vtiger_users.id = vtiger_crmentity.smownerid and vtiger_users.status = 'Active'
				LEFT JOIN vtiger_account vtiger_account2 
					ON vtiger_account2.accountid = vtiger_account.parentid
				";//vtiger_account2 is added to get the Member of account


		$where_auto = " vtiger_crmentity.deleted = 0 ";

		if($where != "")
			$query .= " WHERE ($where) AND ".$where_auto;
		else
			$query .= " WHERE ".$where_auto;

		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
		//we should add security check when the user has Private Access
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[6] == 3)
		{
			//Added security check to get the permitted records only
			$query = $query." ".getListViewSecurityParameter("Accounts");
		}

		$log->debug("Exiting create_export_query method ...");
		return $query;
	}

	/** Function to get the Columnnames of the Account Record
	* Used By vtigerCRM Word Plugin
	* Returns the Merge Fields for Word Plugin
	*/
	function getColumnNames_Acnt()
	{
		global $log,$current_user;
		$log->debug("Entering getColumnNames_Acnt() method ...");
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
		{
			$sql1 = "SELECT fieldlabel FROM vtiger_field WHERE tabid = 6";
			$params1 = array();
		}else
		{
			$profileList = getCurrentUserProfileList();
			$sql1 = "select vtiger_field.fieldid,fieldlabel from vtiger_field inner join vtiger_profile2field on vtiger_profile2field.fieldid=vtiger_field.fieldid inner join vtiger_def_org_field on vtiger_def_org_field.fieldid=vtiger_field.fieldid where vtiger_field.tabid=6 and vtiger_field.displaytype in (1,2,4) and vtiger_profile2field.visible=0 and vtiger_def_org_field.visible=0";
			$params1 = array();
			if (count($profileList) > 0) {
				$sql1 .= " and vtiger_profile2field.profileid in (". generateQuestionMarks($profileList) .")  group by fieldid";
			    array_push($params1,  $profileList);
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
		$log->debug("Exiting getColumnNames_Acnt method ...");
		return $mergeflds;
	}

}

?>
