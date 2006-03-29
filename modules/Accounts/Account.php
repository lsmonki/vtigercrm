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


	// Stored fields
	var $id;
	var $mode;

	// These are for related fields
	var $opportunity_id;
	var $contact_id;
	var $note_id;
	var $email_id;
	var $member_id;
	var $parent_name;
	var $assigned_user_name;

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

	var $record_id;
	var $list_mode;
	var $popup_type;

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
		if(isset($_REQUEST['sorder'])) 
			$sorder = $_REQUEST['sorder'];
		else
			$sorder = (($_SESSION['ACCOUNTS_SORT_ORDER'] != '')?($_SESSION['ACCOUNTS_SORT_ORDER']):($this->default_sort_order));

		return $sorder;
	}

	function getOrderBy()
	{
		if (isset($_REQUEST['order_by'])) 
			$order_by = $_REQUEST['order_by'];
		else
			$order_by = (($_SESSION['ACCOUNTS_ORDER_BY'] != '')?($_SESSION['ACCOUNTS_ORDER_BY']):($this->default_order_by));

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
		global $mod_strings;

		$focus = new Contact();

		$button = '';
		if(isPermitted("Contacts",1,"") == 'yes')
		{
			$button .= '<input title="New Contact" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Contacts\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_CONTACT'].'">&nbsp;</td>';
		}
		$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

		$query = 'SELECT contactdetails.*, crmentity.crmid, crmentity.smownerid from contactdetails inner join crmentity on crmentity.crmid = contactdetails.contactid left join contactgrouprelation on contactdetails.contactid=contactgrouprelation.contactid left join groups on groups.groupname=contactgrouprelation.groupname where crmentity.deleted=0 and contactdetails.accountid = '.$id;

		return GetRelatedList('Accounts','Contacts',$focus,$query,$button,$returnset);
	}

	/** Returns a list of the associated opportunities
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function get_opportunities($id)
	{
		global $mod_strings;

		$focus = new Potential();
		$button = '';

		if(isPermitted("Potentials",1,"") == 'yes')
		{
			$button .= '<input title="New Potential" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Potentials\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_POTENTIAL'].'">';
		}
		$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

		$query = 'select potential.potentialid, potential.accountid, potential.potentialname, potential.sales_stage, potential.potentialtype, potential.amount, potential.closingdate, potential.potentialtype, users.user_name, crmentity.crmid, crmentity.smownerid from potential inner join crmentity on crmentity.crmid= potential.potentialid left join users on crmentity.smownerid = users.id left join potentialgrouprelation on potential.potentialid=potentialgrouprelation.potentialid left join groups on groups.groupname=potentialgrouprelation.groupname where crmentity.deleted=0 and potential.accountid= '.$id ;

		return GetRelatedList('Accounts','Potentials',$focus,$query,$button,$returnset);
	}

	/** Returns a list of the associated tasks
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function get_activities($id)
	{
		global $mod_strings;

		$focus = new Activity();
		$button = '';
		if(isPermitted("Activities",1,"") == 'yes')
		{

			$button .= '<input title="New Task" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.return_module.value=\'Accounts\';this.form.activity_mode.value=\'Task\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_TASK'].'">&nbsp;';
			$button .= '<input title="New Event" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.return_module.value=\'Accounts\';this.form.activity_mode.value=\'Events\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_EVENT'].'">&nbsp;</td>';
		}
		$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

		$query = "SELECT activity.*,seactivityrel.*, contactdetails.contactid,contactdetails.lastname, contactdetails.firstname, crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime, users.user_name,recurringevents.recurringtype from activity inner join seactivityrel on seactivityrel.activityid=activity.activityid inner join crmentity on crmentity.crmid=activity.activityid left join cntactivityrel on cntactivityrel.activityid= activity.activityid left join contactdetails on contactdetails.contactid= cntactivityrel.contactid left join users on users.id=crmentity.smownerid left outer join recurringevents on recurringevents.activityid=activity.activityid left join activitygrouprelation on activitygrouprelation.activityid=crmentity.crmid left join groups on groups.groupname=activitygrouprelation.groupname where seactivityrel.crmid=".$id." and (activitytype='Task' or activitytype='Call' or activitytype='Meeting') and crmentity.deleted=0 and (activity.status is not NULL && activity.status != 'Completed') and (activity.status is not NULL && activity.status != 'Deferred') or (activity.eventstatus !='' &&  activity.eventstatus = 'Planned')";
		return GetRelatedList('Accounts','Activities',$focus,$query,$button,$returnset);

	}

	function get_history($id)
	{
		$query = "SELECT activity.activityid, activity.subject, activity.status, activity.eventstatus,
			activity.activitytype, contactdetails.contactid, contactdetails.firstname,
			contactdetails.lastname, crmentity.modifiedtime, crmentity.createdtime,
			crmentity.description, users.user_name
				from activity
				inner join seactivityrel on seactivityrel.activityid=activity.activityid
				inner join crmentity on crmentity.crmid=activity.activityid
				left join cntactivityrel on cntactivityrel.activityid= activity.activityid 
				left join contactdetails on contactdetails.contactid= cntactivityrel.contactid
				left join activitygrouprelation on activitygrouprelation.activityid=activity.activityid
				left join groups on groups.groupname=activitygrouprelation.groupname
				inner join users on crmentity.smcreatorid=users.id
				where (activity.activitytype = 'Meeting' or activity.activitytype='Call' or activity.activitytype='Task')
				and (activity.status='Completed' or activity.status = 'Deferred'  or (activity.eventstatus != 'Planned' and activity.eventstatus !=''))
				and seactivityrel.crmid=".$id;
		//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php
		return getHistory('Accounts',$query,$id);
	}

	function get_attachments($id)
	{
		// Armando Lüscher 18.10.2005 -> §visibleDescription
		// Desc: Inserted crm2.createdtime, notes.notecontent description, users.user_name
		// Inserted inner join users on crm2.smcreatorid= users.id
		$query = "select notes.title,'Notes      '  ActivityType, notes.filename,	attachments.type  FileType,
			crm2.modifiedtime lastmodified, seattachmentsrel.attachmentsid,	notes.notesid crmid,
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
			attachments.attachmentsid, seattachmentsrel.attachmentsid crmid,
			crm2.createdtime, attachments.description, users.user_name
				from attachments
				inner join seattachmentsrel on seattachmentsrel.attachmentsid= attachments.attachmentsid
				inner join crmentity on crmentity.crmid= seattachmentsrel.crmid
				inner join crmentity crm2 on crm2.crmid=attachments.attachmentsid
				inner join users on crm2.smcreatorid= users.id
				where crmentity.crmid=".$id."
				order by createdtime desc";

		return getAttachmentsAndNotes('Accounts',$query,$id);
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
		$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;


		$query = "select crmentity.*, quotes.*,potential.potentialname,account.accountname from quotes inner join crmentity on crmentity.crmid=quotes.quoteid left outer join account on account.accountid=quotes.accountid left outer join potential on potential.potentialid=quotes.potentialid left join quotegrouprelation on quotes.quoteid=quotegrouprelation.quoteid left join groups on groups.groupname=quotegrouprelation.groupname where crmentity.deleted=0 and account.accountid=".$id;
		return GetRelatedList('Accounts','Quotes',$focus,$query,$button,$returnset);
	}
	function get_invoices($id)
	{
		global $app_strings;
		require_once('modules/Invoice/Invoice.php');

		$focus = new Invoice();

		$button = '';
		if(isPermitted("Invoice",1,"") == 'yes')
		{
			$button .= '<input title="'.$app_strings['LBL_NEW_INVOICE_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_INVOICE_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Invoice\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_INVOICE_BUTTON'].'">&nbsp;</td>';
		}
		$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

		$query = "select crmentity.*, invoice.*, account.accountname, salesorder.subject as salessubject from invoice inner join crmentity on crmentity.crmid=invoice.invoiceid left outer join account on account.accountid=invoice.accountid left outer join salesorder on salesorder.salesorderid=invoice.salesorderid left join invoicegrouprelation on invoice.invoiceid=invoicegrouprelation.invoiceid left join groups on groups.groupname=invoicegrouprelation.groupname where crmentity.deleted=0 and account.accountid=".$id;
		return GetRelatedList('Accounts','Invoice',$focus,$query,$button,$returnset);
	}
	function get_salesorder($id)
	{
		require_once('modules/SalesOrder/SalesOrder.php');
		global $app_strings;

		$focus = new SalesOrder();

		$button = '';
		if(isPermitted("SalesOrder",1,"") == 'yes')
		{
			$button .= '<input title="'.$app_strings['LBL_NEW_SORDER_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_SORDER_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'SalesOrder\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_SORDER_BUTTON'].'">&nbsp;</td>';
		}

		$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

		$query = "select crmentity.*, salesorder.*, quotes.subject as quotename, account.accountname from salesorder inner join crmentity on crmentity.crmid=salesorder.salesorderid left outer join quotes on quotes.quoteid=salesorder.quoteid left outer join account on account.accountid=salesorder.accountid left join sogrouprelation on salesorder.salesorderid=sogrouprelation.salesorderid left join groups on groups.groupname=sogrouprelation.groupname where crmentity.deleted=0 and salesorder.accountid = ".$id;
		return GetRelatedList('Accounts','SalesOrder',$focus,$query,$button,$returnset);
	}
	function get_tickets($id)
	{
		global $app_strings;

		$focus = new HelpDesk();
		$button = '';

		$button .= '<td valign="bottom" align="right"><input title="New TICKET" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'HelpDesk\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_TICKET'].'">&nbsp;</td>';
		$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

		$query = "select users.user_name, users.id, troubletickets.title, troubletickets.ticketid as crmid, troubletickets.status, troubletickets.priority, troubletickets.parent_id, crmentity.smownerid, crmentity.modifiedtime from troubletickets inner join crmentity on crmentity.crmid = troubletickets.ticketid left join account on account.accountid=troubletickets.parent_id left join users on users.id=crmentity.smownerid left join ticketgrouprelation on troubletickets.ticketid=ticketgrouprelation.ticketid left join groups on groups.groupname=ticketgrouprelation.groupname where account.accountid =".$id ;
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
		$query .= " union all ";
		$query .= "select users.user_name, users.id, troubletickets.title, troubletickets.ticketid as crmid, troubletickets.status, troubletickets.priority, troubletickets.parent_id, crmentity.smownerid, crmentity.modifiedtime from troubletickets inner join crmentity on crmentity.crmid = troubletickets.ticketid left join contactdetails on contactdetails.contactid = troubletickets.parent_id left join account on account.accountid=contactdetails.accountid left join users on users.id=crmentity.smownerid left join ticketgrouprelation on troubletickets.ticketid=ticketgrouprelation.ticketid left join groups on groups.groupname=ticketgrouprelation.groupname where account.accountid =".$id;
		return GetRelatedList('Accounts','HelpDesk',$focus,$query,$button,$returnset);
	}

	function get_products($id)
	{
		require_once('modules/Products/Product.php');
		global $app_strings;

		$focus = new Product();

		$button = '';

		if(isPermitted("Products",1,"") == 'yes')
		{


			$button .= '<input title="New Product" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Products\';this.form.return_module.value=\'Accounts\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_PRODUCT'].'">&nbsp;';
		}
		$returnset = '&return_module=Accounts&return_action=DetailView&return_id='.$id;

		$query = 'select products.productid, products.productname, products.productcode, products.commissionrate, products.qty_per_unit, products.unit_price, crmentity.crmid, crmentity.smownerid from products inner join seproductsrel on products.productid = seproductsrel.productid inner join crmentity on crmentity.crmid = products.productid inner join account on account.accountid = seproductsrel.crmid  where account.accountid = '.$id.' and crmentity.deleted = 0';
		return GetRelatedList('Accounts','Products',$focus,$query,$button,$returnset);
	}


	function create_export_query(&$order_by, &$where)
	{
		if($this->checkIfCustomTableExists('accountscf'))
		{

			$query = $this->constructCustomQueryAddendum('accountscf','Accounts') . " 
				account.*, ".$this->entity_table.".*, accountbillads.city  billing_city, accountbillads.country  billing_country, accountbillads.code  billing_code, accountbillads.state  billing_state, accountbillads.street  billing_street, accountshipads.city  shipping_city, accountshipads.country  shipping_country, accountshipads.code  shipping_code, accountshipads.state  shipping_state,  accountshipads.street  shipping_street,
				users.user_name, users.status  user_status
					FROM ".$this->entity_table."
					INNER JOIN account
					ON crmentity.crmid=account.accountid
					LEFT JOIN accountbillads
					ON account.accountid=accountbillads.accountaddressid
					LEFT JOIN accountshipads
					ON account.accountid=accountshipads.accountaddressid
					LEFT JOIN accountscf 
					ON accountscf.accountid=account.accountid
					LEFT JOIN users
					ON crmentity.smownerid = users.id ";

		}
		else
		{
			$query = "SELECT 
				account.*, ".$this->entity_table.".*, accountbillads.city  billing_city, accountbillads.country  billing_country, accountbillads.code  billing_code, accountbillads.state billing_state, accountbillads.street billing_street, accountshipads.city shipping_city, accountshipads.country shipping_country, accountshipads.code shipping_code, accountshipads.state shipping_state,  accountshipads.street shipping_street,
				users.user_name, users.status user_status
					FROM ".$this->entity_table."
					INNER JOIN account
					ON crmentity.crmid=account.accountid
					LEFT JOIN accountbillads
					ON account.accountid=accountbillads.accountaddressid
					LEFT JOIN accountshipads
					ON account.accountid=accountshipads.accountaddressid
					LEFT JOIN users
					ON crmentity.smownerid = users.id ";
		}

		$where_auto = " users.status='Active'
			AND crmentity.deleted=0 ";

		if($where != "")
			$query .= "where ($where) AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

		if(!empty($order_by))
			$query .= " ORDER BY $order_by";

		return $query;
	}


	//Used By vtigerCRM Word Plugin
	function getColumnNames_Acnt()
	{
		$sql1 = "select fieldlabel from field where tabid=6";
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

}

?>
