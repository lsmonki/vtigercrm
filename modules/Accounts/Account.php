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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Accounts/Account.php,v 1.53 2005/04/28 08:06:45 rank Exp $
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
require_once('modules/Contacts/Contact.php');
require_once('modules/Potentials/Opportunity.php');
require_once('modules/Activities/Activity.php');
require_once('modules/Notes/Note.php');
require_once('modules/Emails/Email.php');
require_once('include/utils/utils.php');

// Account is used to store account information.
class Account extends CRMEntity {
	var $log;
	var $db;

	var $table_name = "account";
	var $tab_name = Array('crmentity','account','accountbillads','accountshipads','accountscf');
	var $tab_name_index = Array('crmentity'=>'crmid','account'=>'accountid','accountbillads'=>'accountaddressid','accountshipads'=>'accountaddressid','accountscf'=>'accountid');

	var $entity_table = "crmentity";

	var $billadr_table = "accountbillads";

	var $object_name = "Accounts";
	// Mike Crowe Mod --------------------------------------------------------added for general search
	var $base_table_name = "account";
	var $cf_table_name = "accountscf";

	var $new_schema = true;

	var $module_id = "accountid";

	var $column_fields = Array();

	var $sortby_fields = Array('accountname','city','website','phone','smownerid');		


	// This is the list of fields that are in the lists.
	var $list_fields = Array(
			'Account Name'=>Array('account'=>'accountname'),
			'City'=>Array('accountbillads'=>'city'), 
			'Website'=>Array('account'=>'website'),
			'Phone'=>Array('account'=> 'phone'),
			'Assigned To'=>Array('crmentity'=>'smownerid')
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
			'Account Name'=>Array('account'=>'accountname'),
			'City'=>Array('accountbillads'=>'city'), 
			);

	var $search_fields_name = Array(
			'Account Name'=>'accountname',
			'City'=>'bill_city',
			);

	// This is the list of fields that are required.
	var $required_fields =  array("accountname"=>1);

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'accountname';
	var $default_sort_order = 'ASC';

	function Account() {
		$this->log =LoggerManager::getLogger('account');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Accounts');
	}

	// Mike Crowe Mod --------------------------------------------------------Default ordering for us
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
		global $log;
                $log->debug("Entering get_contacts(".$id.") method ...");
		global $mod_strings;

		$focus = new Contact();

		$button = '';
		if(isPermitted("Contacts",1,"") == 'yes')
		{
			$button .= '<input title="New Contact" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Contacts\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_CONTACT'].'">&nbsp;</td>';
		}
		$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

		//SQL
		$query = "SELECT contactdetails.*,
				crmentity.crmid,
                        	crmentity.smownerid,
				users.user_name
			FROM contactdetails
			INNER JOIN crmentity
				ON crmentity.crmid = contactdetails.contactid
			LEFT JOIN contactgrouprelation
				ON contactdetails.contactid = contactgrouprelation.contactid
			LEFT JOIN groups
				ON groups.groupname = contactgrouprelation.groupname
			LEFT JOIN users
				ON crmentity.smownerid = users.id
			WHERE crmentity.deleted = 0
			AND contactdetails.accountid = ".$id;
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
		global $log;
                $log->debug("Entering get_opportunities(".$id.") method ...");
		global $mod_strings;

		$focus = new Potential();
		$button = '';

		if(isPermitted("Potentials",1,"") == 'yes')
		{
			$button .= '<input title="New Potential" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Potentials\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_POTENTIAL'].'">';
		}
		$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

		$query = "SELECT potential.potentialid, potential.accountid,
				potential.potentialname, potential.sales_stage,
				potential.potentialtype, potential.amount,
				potential.closingdate, potential.potentialtype,
				users.user_name,
				crmentity.crmid, crmentity.smownerid
			FROM potential
			INNER JOIN crmentity
				ON crmentity.crmid = potential.potentialid
			LEFT JOIN users
				ON crmentity.smownerid = users.id
			LEFT JOIN potentialgrouprelation
				ON potential.potentialid = potentialgrouprelation.potentialid
			LEFT JOIN groups
				ON groups.groupname = potentialgrouprelation.groupname
			WHERE crmentity.deleted = 0
			AND potential.accountid = ".$id;
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
		global $log;
                $log->debug("Entering get_activities(".$id.") method ...");
		global $mod_strings;

		$focus = new Activity();
		$button = '';
		if(isPermitted("Activities",1,"") == 'yes')
		{

			$button .= '<input title="New Task" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.return_module.value=\'Accounts\';this.form.activity_mode.value=\'Task\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_TASK'].'">&nbsp;';
			$button .= '<input title="New Event" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.return_module.value=\'Accounts\';this.form.activity_mode.value=\'Events\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_EVENT'].'">&nbsp;</td>';
		}
		$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

		$query = "SELECT activity.*,
				seactivityrel.*,
				contactdetails.contactid, contactdetails.lastname,
				contactdetails.firstname,
				crmentity.crmid, crmentity.smownerid,
				crmentity.modifiedtime,
				users.user_name,
				recurringevents.recurringtype
			FROM activity
			INNER JOIN seactivityrel
				ON seactivityrel.activityid = activity.activityid
			INNER JOIN crmentity
				ON crmentity.crmid = activity.activityid
			LEFT JOIN cntactivityrel
				ON cntactivityrel.activityid = activity.activityid
			LEFT JOIN contactdetails
				ON contactdetails.contactid = cntactivityrel.contactid
			LEFT JOIN users
				ON users.id = crmentity.smownerid
			LEFT OUTER JOIN recurringevents
				ON recurringevents.activityid = activity.activityid
			LEFT JOIN activitygrouprelation
				ON activitygrouprelation.activityid = crmentity.crmid
			LEFT JOIN groups
				ON groups.groupname = activitygrouprelation.groupname
			WHERE seactivityrel.crmid = ".$id."
			AND (activitytype='Task'
				OR activitytype='Call'
				OR activitytype='Meeting')
			AND crmentity.deleted = 0
			AND ((activity.status IS NOT NULL
					AND activity.status != 'Completed')
				AND (activity.status IS NOT NULL
					AND activity.status != 'Deferred')
				OR (activity.eventstatus !=''
					AND  activity.eventstatus != 'Held'))";
		$log->debug("Exiting get_activities method ...");
		return GetRelatedList('Accounts','Activities',$focus,$query,$button,$returnset);

	}

	function get_history($id)
	{
		global $log;
                $log->debug("Entering get_history(".$id.") method ...");
		$query = "SELECT activity.activityid, activity.subject,
				activity.status, activity.eventstatus,
				activity.activitytype,
				contactdetails.contactid, contactdetails.firstname,
				contactdetails.lastname,
				crmentity.modifiedtime, crmentity.createdtime,
				crmentity.description,
				users.user_name
			FROM activity
			INNER JOIN seactivityrel
				ON seactivityrel.activityid = activity.activityid
			INNER JOIN crmentity
				ON crmentity.crmid = activity.activityid
			LEFT JOIN cntactivityrel
				ON cntactivityrel.activityid = activity.activityid 
			LEFT JOIN contactdetails
				ON contactdetails.contactid = cntactivityrel.contactid
			LEFT JOIN activitygrouprelation
				ON activitygrouprelation.activityid = activity.activityid
			LEFT JOIN groups
				ON groups.groupname = activitygrouprelation.groupname
			INNER JOIN users
				ON crmentity.smcreatorid = users.id
			WHERE (activity.activitytype = 'Meeting'
				OR activity.activitytype = 'Call'
				OR activity.activitytype = 'Task')
			AND (activity.status = 'Completed'
				OR activity.status = 'Deferred'
				OR (activity.eventstatus = 'Held'
					AND activity.eventstatus != ''))
			AND seactivityrel.crmid = ".$id;
		//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php
		$log->debug("Exiting get_history method ...");
		return getHistory('Accounts',$query,$id);
	}

	function get_attachments($id)
	{
		 global $log;
                 $log->debug("Entering get_attachments(".$id.") method ...");
		// Armando Lüscher 18.10.2005 -> §visibleDescription
		// Desc: Inserted crm2.createdtime, notes.notecontent description, users.user_name
		// Inserted inner join users on crm2.smcreatorid= users.id
		$query = "SELECT notes.title, notes.notecontent AS description,
				notes.filename, notes.notesid AS crmid,
				'Notes      ' AS ActivityType,
				attachments.type AS FileType,
				crm2.modifiedtime AS lastmodified, crm2.createdtime,
				seattachmentsrel.attachmentsid,
				users.user_name
			FROM notes
			INNER JOIN senotesrel
				ON senotesrel.notesid = notes.notesid
			INNER JOIN crmentity
				ON crmentity.crmid = senotesrel.crmid
			INNER JOIN crmentity crm2
				ON crm2.crmid = notes.notesid
				AND crm2.deleted = 0
			LEFT JOIN seattachmentsrel
				ON seattachmentsrel.crmid = notes.notesid
			LEFT JOIN attachments
				ON seattachmentsrel.attachmentsid = attachments.attachmentsid
			INNER JOIN users
				ON crm2.smcreatorid = users.id
			WHERE crmentity.crmid = ".$id."
		 UNION ALL
			SELECT attachments.description AS title, attachments.description,
				attachments.name AS filename,
				seattachmentsrel.attachmentsid AS crmid,
				'Attachments' AS ActivityType,
				attachments.type AS FileType,
				crm2.modifiedtime AS lastmodified, crm2.createdtime,
				attachments.attachmentsid,
				users.user_name
			FROM attachments
			INNER JOIN seattachmentsrel
				ON seattachmentsrel.attachmentsid = attachments.attachmentsid
			INNER JOIN crmentity
				ON crmentity.crmid = seattachmentsrel.crmid
			INNER JOIN crmentity crm2
				ON crm2.crmid = attachments.attachmentsid
			INNER JOIN users
				ON crm2.smcreatorid = users.id
			WHERE crmentity.crmid = ".$id."
			ORDER BY createdtime DESC";
		$log->debug("Exiting get_attachments method ...");
		return getAttachmentsAndNotes('Accounts',$query,$id);
	}
	function get_quotes($id)
	{
		global $log;
                $log->debug("Entering get_quotes(".$id.") method ...");
		global $app_strings;
		require_once('modules/Quotes/Quote.php');

		$focus = new Quote();

		$button = '';
		if(isPermitted("Quotes",1,"") == 'yes')
		{
			$button .= '<input title="'.$app_strings['LBL_NEW_QUOTE_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_QUOTE_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Quotes\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_QUOTE_BUTTON'].'">&nbsp;</td>';
		}
		$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;


		$query = "SELECT users.user_name,
				groups.groupname,
				crmentity.*,
				quotes.*,
				potential.potentialname,
				account.accountname
			FROM quotes
			INNER JOIN crmentity
				ON crmentity.crmid = quotes.quoteid
			LEFT OUTER JOIN account
				ON account.accountid = quotes.accountid
			LEFT OUTER JOIN potential
				ON potential.potentialid = quotes.potentialid
			LEFT JOIN quotegrouprelation
				ON quotes.quoteid = quotegrouprelation.quoteid
			LEFT JOIN groups
				ON groups.groupname = quotegrouprelation.groupname
			LEFT JOIN users
				ON crmentity.smownerid = users.id
			WHERE crmentity.deleted = 0
			AND account.accountid = ".$id;
		$log->debug("Exiting get_quotes method ...");
		return GetRelatedList('Accounts','Quotes',$focus,$query,$button,$returnset);
	}
	function get_invoices($id)
	{
		global $log;
                $log->debug("Entering get_invoices(".$id.") method ...");
		global $app_strings;
		require_once('modules/Invoice/Invoice.php');

		$focus = new Invoice();

		$button = '';
		if(isPermitted("Invoice",1,"") == 'yes')
		{
			$button .= '<input title="'.$app_strings['LBL_NEW_INVOICE_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_INVOICE_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Invoice\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_INVOICE_BUTTON'].'">&nbsp;</td>';
		}
		$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

		$query = "SELECT users.user_name,
				groups.groupname,
				crmentity.*,
				invoice.*,
				account.accountname,
				salesorder.subject AS salessubject
			FROM invoice
			INNER JOIN crmentity
				ON crmentity.crmid = invoice.invoiceid
			LEFT OUTER JOIN account
				ON account.accountid = invoice.accountid
			LEFT OUTER JOIN salesorder
				ON salesorder.salesorderid = invoice.salesorderid
			LEFT JOIN invoicegrouprelation
				ON invoice.invoiceid = invoicegrouprelation.invoiceid
			LEFT JOIN groups
				ON groups.groupname = invoicegrouprelation.groupname
			LEFT JOIN users
				ON crmentity.smownerid = users.id
			WHERE crmentity.deleted = 0
			AND account.accountid = ".$id;
		$log->debug("Exiting get_invoices method ...");
		return GetRelatedList('Accounts','Invoice',$focus,$query,$button,$returnset);
	}
	function get_salesorder($id)
	{
		global $log;
                $log->debug("Entering get_salesorder(".$id.") method ...");
		require_once('modules/SalesOrder/SalesOrder.php');
		global $app_strings;

		$focus = new SalesOrder();

		$button = '';
		if(isPermitted("SalesOrder",1,"") == 'yes')
		{
			$button .= '<input title="'.$app_strings['LBL_NEW_SORDER_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_SORDER_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'SalesOrder\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_SORDER_BUTTON'].'">&nbsp;</td>';
		}

		$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

		$query = "SELECT crmentity.*,
				salesorder.*,
				quotes.subject AS quotename,
				account.accountname,
				users.user_name,
				groups.groupname
			FROM salesorder
			INNER JOIN crmentity
				ON crmentity.crmid = salesorder.salesorderid
			LEFT OUTER JOIN quotes
				ON quotes.quoteid = salesorder.quoteid
			LEFT OUTER JOIN account
				ON account.accountid = salesorder.accountid
			LEFT JOIN sogrouprelation
				ON salesorder.salesorderid = sogrouprelation.salesorderid
			LEFT JOIN groups
				ON groups.groupname = sogrouprelation.groupname
			LEFT JOIN users
				ON crmentity.smownerid = users.id
			WHERE crmentity.deleted = 0
			AND salesorder.accountid = ".$id;
		$log->debug("Exiting get_salesorder method ...");		
		return GetRelatedList('Accounts','SalesOrder',$focus,$query,$button,$returnset);
	}
	function get_tickets($id)
	{
		global $log;
                $log->debug("Entering get_tickets(".$id.") method ...");
		global $app_strings;

		$focus = new HelpDesk();
		$button = '';

		$button .= '<td valign="bottom" align="right"><input title="New TICKET" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'HelpDesk\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_TICKET'].'">&nbsp;</td>';
		$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

		$query = "SELECT users.user_name, users.id,
				troubletickets.title, troubletickets.ticketid AS crmid,
				troubletickets.status, troubletickets.priority,
				troubletickets.parent_id,
				crmentity.smownerid, crmentity.modifiedtime
			FROM troubletickets
			INNER JOIN crmentity
				ON crmentity.crmid = troubletickets.ticketid
			LEFT JOIN account
				ON account.accountid = troubletickets.parent_id
			LEFT JOIN users
				ON users.id=crmentity.smownerid
			LEFT JOIN ticketgrouprelation
				ON troubletickets.ticketid = ticketgrouprelation.ticketid
			LEFT JOIN groups
				ON groups.groupname = ticketgrouprelation.groupname
			WHERE account.accountid = ".$id ;
		//Appending the security parameter
		global $current_user;
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
		$tab_id=getTabid('HelpDesk');
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
		{
			$sec_parameter=getListViewSecurityParameter('HelpDesk');
			$query .= ' '.$sec_parameter;

		}
		$query .= " UNION ALL
			SELECT users.user_name, users.id,
				troubletickets.title, troubletickets.ticketid AS crmid,
				troubletickets.status, troubletickets.priority,
				troubletickets.parent_id,
				crmentity.smownerid, crmentity.modifiedtime
			FROM troubletickets
			INNER JOIN crmentity
				ON crmentity.crmid = troubletickets.ticketid
			LEFT JOIN contactdetails
				ON contactdetails.contactid = troubletickets.parent_id
			LEFT JOIN account
				ON account.accountid = contactdetails.accountid
			LEFT JOIN users
				ON users.id = crmentity.smownerid
			LEFT JOIN ticketgrouprelation
				ON troubletickets.ticketid = ticketgrouprelation.ticketid
			LEFT JOIN groups
				ON groups.groupname = ticketgrouprelation.groupname
			WHERE account.accountid = ".$id;
		$log->debug("Exiting get_tickets method ...");
		return GetRelatedList('Accounts','HelpDesk',$focus,$query,$button,$returnset);
	}

	function get_products($id)
	{
		global $log;
                $log->debug("Entering get_products(".$id.") method ...");
		require_once('modules/Products/Product.php');
		global $app_strings;

		$focus = new Product();

		$button = '';

		if(isPermitted("Products",1,"") == 'yes')
		{


			$button .= '<input title="New Product" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Products\';this.form.return_module.value=\'Accounts\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_PRODUCT'].'">&nbsp;';
		}
		$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

		$query = "SELECT products.productid, products.productname,
				products.productcode, products.commissionrate,
				products.qty_per_unit, products.unit_price,
				crmentity.crmid, crmentity.smownerid
			FROM products
			INNER JOIN seproductsrel
				ON products.productid = seproductsrel.productid
			INNER JOIN crmentity
				ON crmentity.crmid = products.productid
			INNER JOIN account
				ON account.accountid = seproductsrel.crmid
			WHERE account.accountid = ".$id."
			AND crmentity.deleted = 0";
		$log->debug("Exiting get_products method ...");
		return GetRelatedList('Accounts','Products',$focus,$query,$button,$returnset);
	}


	function create_export_query(&$order_by, &$where)
	{
		global $log;
                $log->debug("Entering create_export_query(".$order_by.",".$where.") method ...");
		if($this->checkIfCustomTableExists('accountscf'))
		{

			$query = $this->constructCustomQueryAddendum('accountscf','Accounts') . "
					account.*,
					".$this->entity_table.".*,
					accountbillads.city AS billing_city,
					accountbillads.country AS billing_country,
					accountbillads.code AS billing_code,
					accountbillads.state AS billing_state,
					accountbillads.street AS billing_street,
					accountshipads.city AS shipping_city,
					accountshipads.country AS shipping_country,
					accountshipads.code AS shipping_code,
					accountshipads.state AS shipping_state,
					accountshipads.street AS shipping_street,
					users.user_name,
					users.status AS user_status
				FROM ".$this->entity_table."
				INNER JOIN account
					ON crmentity.crmid = account.accountid
				LEFT JOIN accountbillads
					ON account.accountid = accountbillads.accountaddressid
				LEFT JOIN accountshipads
					ON account.accountid = accountshipads.accountaddressid
				LEFT JOIN accountscf 
					ON accountscf.accountid = account.accountid
				LEFT JOIN users
					ON crmentity.smownerid = users.id ";

		}
		else
		{
			$query = "SELECT account.*,
					".$this->entity_table.".*,
					accountbillads.city AS billing_city,
					accountbillads.country AS billing_country,
					accountbillads.code AS billing_code,
					accountbillads.state AS billing_state,
					accountbillads.street AS billing_street,
					accountshipads.city AS shipping_city,
					accountshipads.country AS shipping_country,
					accountshipads.code AS shipping_code,
					accountshipads.state AS shipping_state,
					accountshipads.street AS shipping_street,
					users.user_name,
					users.status AS user_status
				FROM ".$this->entity_table."
				INNER JOIN account
					ON crmentity.crmid = account.accountid
				LEFT JOIN accountbillads
					ON account.accountid = accountbillads.accountaddressid
				LEFT JOIN accountshipads
					ON account.accountid = accountshipads.accountaddressid
				LEFT JOIN users
					ON crmentity.smownerid = users.id ";
		}

		$where_auto = " users.status = 'Active'
			AND crmentity.deleted = 0 ";

		if($where != "")
			$query .= "WHERE ($where) AND ".$where_auto;
		else
			$query .= "WHERE ".$where_auto;

		if(!empty($order_by))
			$query .= " ORDER BY $order_by";
		$log->debug("Exiting create_export_query method ...");
		return $query;
	}


	//Used By vtigerCRM Word Plugin
	function getColumnNames_Acnt()
	{
		global $log;
                $log->debug("Entering getColumnNames_Acnt() method ...");
		$sql1 = "SELECT fieldlabel FROM field WHERE tabid = 6";
		$result = $this->db->query($sql1);
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
