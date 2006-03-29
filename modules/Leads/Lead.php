<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of txhe License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('data/CRMEntity.php');
require_once('modules/Activities/Activity.php');
require_once('modules/Notes/Note.php');
require_once('modules/Emails/Email.php');
require_once('include/ComboUtil.php');
require_once('include/utils/utils.php');

class Lead extends CRMEntity {
	var $log;
	var $db;

	// Stored fields
	var $leadid;
	var $email;
	var $firstname;
	var $salutation;
	var $lastname;
	var $company;
	var $annualrevenue;

	var $industry;
	var $campaign;
	var $rating;
	var $status;
	var $leadsource;
	var $designation;
	var $licencekey;
	var $region;
	var $space;
	var $comments;
	var $priority;
	var $partnercontact;
	var $maildate;
	var $nextstepdate;
	var $fundingsituation;
	var $deleted;

	var $description;
	// These are for related fields
	var $city;
	var $code;
	var $state;
	var $country;
	var $phone;
	var $mobile;
	var $fax;
	var $lane;
	var $leadaddresstype;
	var $currency;
	var $website;
	var $callornot;
	var $readornot;
	var $empct;

	var $accountid;
	var $contactid;
	var $campaignid;
	var $potentialid;

	var $module_id = "leadid";

	var $tab_name = Array('crmentity','leaddetails','leadsubdetails','leadaddress','leadscf');
	var $tab_name_index = Array('crmentity'=>'crmid','leaddetails'=>'leadid','leadsubdetails'=>'leadsubscriptionid','leadaddress'=>'leadaddressid','leadscf'=>'leadid');


	var $entity_table = "crmentity";
	var $table_name = "leaddetails";

	var $object_name = "Lead";

	var $new_schema = true;

	//construct this from database;	
	var $column_fields = Array();

	var $sortby_fields = Array('lastname','firstname','email','phone','company','smownerid');

	var $combofieldNames = Array('leadsource'=>'leadsource_dom'
	,'salutation'=>'salutation_dom'
	,'status'=>'leadstatus_dom'
	,'industry'=>'industry_dom'
	,'rating'=>'rating_dom'
	,'licencekey'=>'licensekey_dom');

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('smcreatorid', 'smownerid', 'contactid','potentialid' ,'crmid');

	// This is the list of fields that are in the lists.
	var $list_fields = Array(
		'Last Name'=>Array('leaddetails'=>'lastname'),
		'First Name'=>Array('leaddetails'=>'firstname'),
		'Company'=>Array('leaddetails'=>'company'),
		'Phone'=>Array('leadaddress'=>'phone'),
		'Website'=>Array('leadsubdetails'=>'website'),
		'Email'=>Array('leaddetails'=>'email'),
		'Assigned To'=>Array('crmentity'=>'smownerid')
	);
	var $list_fields_name = Array(
		'Last Name'=>'lastname',
		'First Name'=>'firstname',
		'Company'=>'company',
		'Phone'=>'phone',
		'Website'=>'website',
		'Email'=>'email',
		'Assigned To'=>'assigned_user_id'
	);
	var $list_link_field= 'lastname';

	var $record_id;
	var $list_mode;
	var $popup_type;

	var $search_fields = Array(
		'Name'=>Array('leaddetails'=>'lastname'),
		'Company'=>Array('leaddetails'=>'company')
	);
	var $search_fields_name = Array(
		'Name'=>'lastname',
		'Company'=>'company'
	);

	var $required_fields =  array("lastname"=>1, 'company'=>1);

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'lastname';
	var $default_sort_order = 'ASC';

	function Lead() {
		$this->log = LoggerManager::getLogger('lead');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Leads');
	}

	


	function create_export_query(&$order_by, &$where)
	{
		if($this->checkIfCustomTableExists('leadscf'))
		{

			$query = $this->constructCustomQueryAddendum('leadscf','Leads') . " 
				leaddetails.*, ".$this->entity_table.".*, leadsubdetails.*,leadaddress.city city, leadaddress.state state,leadaddress.code code,leadaddress.country country, leadaddress.phone phone, users.user_name, users.status user_status
				FROM ".$this->entity_table."
				INNER JOIN leaddetails
				ON crmentity.crmid=leaddetails.leadid
				LEFT JOIN leadaddress 
				ON leaddetails.leadid=leadaddress.leadaddressid
				LEFT JOIN leadsubdetails
				ON leaddetails.leadid=leadsubdetails.leadsubscriptionid
				LEFT JOIN leadscf 
				ON leadscf.leadid=leaddetails.leadid
				LEFT JOIN users
				ON crmentity.smownerid = users.id ";

		}
		else
		{
			$query = "SELECT 
				leaddetails.*, ".$this->entity_table.".*, leadsubdetails.*,leadaddress.*,users.user_name, users.status user_status FROM ".$this->entity_table."
				INNER JOIN leaddetails
				ON crmentity.crmid=leaddetails.leadid
				LEFT JOIN leadsubdetails
				ON leaddetails.leadid = leadsubdetails.leadsubscriptionid
				LEFT JOIN leadaddress
				ON leaddetails.leadid=leadaddress.leadaddressid
				LEFT JOIN users
				ON crmentity.smownerid = users.id ";
		}

		$where_auto = " users.status='Active'
			AND crmentity.deleted=0 AND leaddetails.converted =0";

		if($where != "")
			$query .= "where ($where) AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

		if(!empty($order_by))
			$query .= " ORDER BY $order_by";

		return $query;
	}


	
	/** Returns a list of the associated tasks
	*/
function get_activities($id)
{
	global $app_strings;

	$focus = new Activity();
	$button = '';

	if(isPermitted("Activities",1,"") == 'yes')
	{
		$button .= '<input title="New Task" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';i;this.form.return_module.value=\'Leads\';this.form.activity_mode.value=\'Task\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_TASK'].'">&nbsp;';
		$button .= '<input title="New Event" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.return_module.value=\'Leads\';this.form.activity_mode.value=\'Events\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_EVENT'].'">&nbsp;</td>';
	}
	$returnset = '&return_module=Leads&return_action=DetailView&return_id='.$id;


	// First, get the list of IDs.
	$query = "SELECT contactdetails.lastname, contactdetails.firstname, contactdetails.contactid, activity.*,seactivityrel.*,crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime, users.user_name,recurringevents.recurringtype from activity inner join seactivityrel on seactivityrel.activityid=activity.activityid inner join crmentity on crmentity.crmid=activity.activityid left join cntactivityrel on cntactivityrel.activityid= activity.activityid left join contactdetails on contactdetails.contactid = cntactivityrel.contactid left join users on users.id=crmentity.smownerid left outer join recurringevents on recurringevents.activityid=activity.activityid left join activitygrouprelation on activitygrouprelation.activityid=crmentity.crmid left join groups on groups.groupname=activitygrouprelation.groupname where seactivityrel.crmid=".$id." and (activitytype='Task' or activitytype='Call' or activitytype='Meeting') and (activity.status is not NULL && activity.status != 'Completed') and (activity.status is not NULL && activity.status != 'Deferred') or (activity.eventstatus !='' && activity.eventstatus = 'Planned')";
	return  GetRelatedList('Leads','Activities',$focus,$query,$button,$returnset);
}

	/** Returns a list of the associated emails
	*/
function get_emails($id)
{
	global $mod_strings;
	require_once('include/RelatedListView.php');

	$focus = new Email();

	$button = '';

	if(isPermitted("Emails",1,"") == 'yes')
	{

		$button .= '<input title="New Email" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Emails\';this.form.email_directing_module.value=\'leads\';this.form.record.value='.$id.';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_EMAIL'].'">&nbsp;';
	}
	$returnset = '&return_module=Leads&return_action=DetailView&return_id='.$id;

	$query ="select activity.activityid, activity.subject, activity.semodule, activity.activitytype, activity.date_start, activity.status, activity.priority, crmentity.crmid,crmentity.smownerid,crmentity.modifiedtime, users.user_name from activity inner join seactivityrel on seactivityrel.activityid=activity.activityid inner join crmentity on crmentity.crmid=activity.activityid inner join users on  users.id=crmentity.smownerid where activity.activitytype='Emails' and crmentity.deleted=0 and seactivityrel.crmid=".$id;
	return GetRelatedList('Leads','Emails',$focus,$query,$button,$returnset);
}

function get_history($id)
{
	$query = "SELECT activity.activityid, activity.subject, activity.status,
		activity.eventstatus, activity.activitytype, contactdetails.contactid,
		contactdetails.firstname, contactdetails.lastname, crmentity.modifiedtime,
		crmentity.createdtime, crmentity.description, users.user_name,activitygrouprelation.groupname
			from activity
			inner join seactivityrel on seactivityrel.activityid=activity.activityid
			inner join crmentity on crmentity.crmid=activity.activityid
			left join cntactivityrel on cntactivityrel.activityid= activity.activityid
			left join contactdetails on contactdetails.contactid= cntactivityrel.contactid
			left join activitygrouprelation on activitygrouprelation.activityid=activity.activityid
			left join groups on groups.groupname=activitygrouprelation.groupname 
			left join users on crmentity.smownerid= users.id
			where (activity.activitytype = 'Meeting' or activity.activitytype='Call' or activity.activitytype='Task')
			and (activity.status = 'Completed' or activity.status = 'Deferred' or (activity.eventstatus != 'Planned' and activity.eventstatus != ''))
			and seactivityrel.crmid=".$id;
	//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php

	return getHistory('Leads',$query,$id);
}

function get_attachments($id)
{
	// Armando Lüscher 18.10.2005 -> §visibleDescription
	// Desc: Inserted crm2.createdtime, notes.notecontent description, users.user_name
	// Inserted inner join users on crm2.smcreatorid= users.id
	$query = "select notes.title,'Notes      ' ActivityType, notes.filename,
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
	$query .= "select attachments.description title ,'Attachments' ActivityType,
		attachments.name filename, attachments.type FileType,crm2.modifiedtime lastmodified,
		attachments.attachmentsid attachmentsid, seattachmentsrel.attachmentsid crmid,
		crm2.createdtime, attachments.description, users.user_name
			from attachments
			inner join seattachmentsrel on seattachmentsrel.attachmentsid= attachments.attachmentsid
			inner join crmentity on crmentity.crmid= seattachmentsrel.crmid
			inner join crmentity crm2 on crm2.crmid=attachments.attachmentsid
			inner join users on crm2.smcreatorid= users.id
			where crmentity.crmid=".$id."
			order by createdtime desc";

	return getAttachmentsAndNotes('Leads',$query,$id);
}
	
function get_products($id)
{
	require_once('modules/Products/Product.php');
	global $mod_strings;
	global $app_strings;

	$focus = new Product();

	$button = '';

	if(isPermitted("Products",1,"") == 'yes')
	{
		$button .= '<input title="New Product" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Products\';this.form.return_module.value=\'Leads\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_PRODUCT'].'">&nbsp;';
	}
	$returnset = '&return_module=Leads&return_action=DetailView&return_id='.$id;

	$query = 'select products.productid, products.productname, products.productcode, products.commissionrate, products.qty_per_unit, products.unit_price, crmentity.crmid, crmentity.smownerid from products inner join seproductsrel on products.productid = seproductsrel.productid inner join crmentity on crmentity.crmid = products.productid inner join leaddetails on leaddetails.leadid = seproductsrel.crmid  where leaddetails.leadid = '.$id.' and crmentity.deleted = 0';
	return  GetRelatedList('Leads','Products',$focus,$query,$button,$returnset);
}

	function get_lead_field_options($list_option)
	{
		$comboFieldArray = getComboArray($this->combofieldNames);
		return $comboFieldArray[$list_option];
	}
	
//Used By vtigerCRM Word Plugin
function getColumnNames_Lead()
{
	$sql1 = "select fieldlabel from field where tabid=7";
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

}

?>
