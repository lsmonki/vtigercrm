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
require_once('include/utils/utils.php');
require_once('modules/Contacts/Contacts.php');
require_once('modules/Leads/Leads.php');
require_once('user_privileges/default_module_view.php');

class Campaigns extends CRMEntity {
	var $log;
	var $db;
	var $table_name = "vtiger_campaign";


	var $tab_name = Array('vtiger_crmentity','vtiger_campaign','vtiger_campaignscf');
	var $tab_name_index = Array('vtiger_crmentity'=>'crmid','vtiger_campaign'=>'campaignid','vtiger_campaignscf'=>'campaignid');
	var $column_fields = Array();

	var $sortby_fields = Array('campaignname','smownerid','campaigntype','productname','expectedrevenue','closingdate','campaignstatus','expectedresponse','targetaudience','expectedcost');

	var $list_fields = Array(
					'Campaign Name'=>Array('campaign'=>'campaignname'),
					'Campaign Type'=>Array('campaign'=>'campaigntype'),
					'Campaign Status'=>Array('campaign'=>'campaignstatus'),
					'Expected Revenue'=>Array('campaign'=>'expectedrevenue'),
					'Expected Close Date'=>Array('campaign'=>'closingdate'),
					'Assigned To' => Array('crmentity'=>'smownerid')
				);

	var $list_fields_name = Array(
					'Campaign Name'=>'campaignname',
					'Campaign Type'=>'campaigntype',
					'Campaign Status'=>'campaignstatus',
					'Expected Revenue'=>'expectedrevenue',
					'Expected Close Date'=>'closingdate',
					'Assigned To'=>'assigned_user_id'
				     );	  			

	var $list_link_field= 'campaignname';
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'DESC';

	var $groupTable = Array('vtiger_campaigngrouprelation','campaignid');

	var $search_fields = Array(
			'Campaign Name'=>Array('vtiger_campaign'=>'campaignname'),
			'Campaign Type'=>Array('vtiger_campaign'=>'campaigntype'),
			);

	var $search_fields_name = Array(
			'Campaign Name'=>'campaignname',
			'Campaign Type'=>'campaigntype',
			);

	function Campaigns() 
	{
		$this->log =LoggerManager::getLogger('campaign');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Campaigns');
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
			$sorder = (($_SESSION['CAMPAIGN_SORT_ORDER'] != '')?($_SESSION['CAMPAIGN_SORT_ORDER']):($this->default_sort_order));

		$log->debug("Exiting getSortOrder method ...");
		return $sorder;
	}

	/**
	 * Function to get order by
	 * return string  $order_by    - fieldname(eg: 'campaignname')
	 */
	function getOrderBy()
	{
		global $log;
		$log->debug("Entering getOrderBy() method ...");
		if (isset($_REQUEST['order_by']))
			$order_by = $_REQUEST['order_by'];
		else
			$order_by = (($_SESSION['CAMPAIGN_ORDER_BY'] != '')?($_SESSION['CAMPAIGN_ORDER_BY']):($this->default_order_by));

		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}
	// Mike Crowe Mod --------------------------------------------------------

	/**
	 * Function to get Campaign related Contacts
	 * @param  integer   $id      - campaignid
	 * returns related Contacts record in array format
	 */
	function get_contacts($id)
        {
		global $log, $singlepane_view;
		$log->debug("Entering get_contacts(".$id.") method ...");
                global $mod_strings;

                $focus = new Contacts();
		$button = '';

		if($singlepane_view == 'true')
			$returnset = '&return_module=Campaigns&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Campaigns&return_action=CallRelatedList&return_id='.$id;

		$query = 'select vtiger_contactdetails.accountid, vtiger_account.accountname, case when (vtiger_users.user_name not like "") then vtiger_users.user_name else vtiger_groups.groupname end as user_name , vtiger_contactdetails.contactid, vtiger_contactdetails.lastname, vtiger_contactdetails.firstname, vtiger_contactdetails.title, vtiger_contactdetails.department, vtiger_contactdetails.email, vtiger_contactdetails.phone, vtiger_crmentity.crmid, vtiger_crmentity.smownerid, vtiger_crmentity.modifiedtime from vtiger_contactdetails inner join vtiger_campaigncontrel on vtiger_campaigncontrel.contactid = vtiger_contactdetails.contactid inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_contactdetails.contactid left join vtiger_contactgrouprelation on vtiger_contactdetails.contactid=vtiger_contactgrouprelation.contactid left join vtiger_groups on vtiger_groups.groupname=vtiger_contactgrouprelation.groupname left join vtiger_users on vtiger_crmentity.smownerid=vtiger_users.id left join vtiger_account on vtiger_account.accountid = vtiger_contactdetails.accountid where vtiger_campaigncontrel.campaignid = '.$id.' and vtiger_crmentity.deleted=0';
		
		$log->debug("Exiting get_contacts method ...");
                return GetRelatedList('Campaigns','Contacts',$focus,$query,$button,$returnset);
        }

	/**
	 * Function to get Campaign related Leads
	 * @param  integer   $id      - campaignid
	 * returns related Leads record in array format
	 */
	function get_leads($id)
        {
		global $log, $singlepane_view;
                $log->debug("Entering get_leads(".$id.") method ...");
                global $mod_strings;

                $focus = new Leads();

                $button = '';
		if($singlepane_view == 'true')
			$returnset = '&return_module=Campaigns&return_action=DetailView&return_id='.$id;
		else
                	$returnset = '&return_module=Campaigns&return_action=CallRelatedList&return_id='.$id;

		$query = 'SELECT vtiger_leaddetails.*, vtiger_crmentity.crmid,vtiger_leadaddress.phone,vtiger_leadsubdetails.website, case when (vtiger_users.user_name not like "") then vtiger_users.user_name else vtiger_groups.groupname end as user_name, vtiger_crmentity.smownerid from vtiger_leaddetails inner join vtiger_campaignleadrel on vtiger_campaignleadrel.leadid=vtiger_leaddetails.leadid inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_leaddetails.leadid inner join vtiger_leadsubdetails  on vtiger_leadsubdetails.leadsubscriptionid = vtiger_leaddetails.leadid inner join vtiger_leadaddress on vtiger_leadaddress.leadaddressid = vtiger_leadsubdetails.leadsubscriptionid left join vtiger_users on vtiger_crmentity.smownerid = vtiger_users.id left join vtiger_leadgrouprelation on vtiger_leaddetails.leadid=vtiger_leadgrouprelation.leadid left join vtiger_groups on vtiger_groups.groupname=vtiger_leadgrouprelation.groupname where vtiger_crmentity.deleted=0 and vtiger_campaignleadrel.campaignid = '.$id;
		$log->debug("Exiting get_leads method ...");
                return GetRelatedList('Campaigns','Leads',$focus,$query,$button,$returnset);
        }

	/**
	 * Function to get Campaign related Potentials
	 * @param  integer   $id      - campaignid
	 * returns related potentials record in array format
	 */
	function get_opportunities($id)
	{
		global $log, $singlepane_view;
		$log->debug("Entering get_opportunities(".$id.") method ...");
		global $mod_strings;

		$focus = new Potentials();

		$button = '';
		if($singlepane_view == 'true')
			$returnset = '&return_module=Campaigns&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Campaigns&return_action=CallRelatedList&return_id='.$id;

		$query = 'select case when (vtiger_users.user_name not like "") then vtiger_users.user_name else vtiger_groups.groupname end as user_name,vtiger_potential.accountid, vtiger_account.accountname, vtiger_potential.potentialid, vtiger_potential.potentialname, vtiger_potential.potentialtype, vtiger_potential.sales_stage, vtiger_potential.amount, vtiger_potential.closingdate, vtiger_crmentity.crmid, vtiger_crmentity.smownerid from vtiger_campaign inner join vtiger_potential on vtiger_campaign.campaignid = vtiger_potential.campaignid inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_potential.potentialid left join vtiger_potentialgrouprelation on vtiger_potential.potentialid=vtiger_potentialgrouprelation.potentialid left join vtiger_groups on vtiger_groups.groupname=vtiger_potentialgrouprelation.groupname left join vtiger_users on vtiger_users.id=vtiger_crmentity.smownerid left join vtiger_account on vtiger_account.accountid = vtiger_potential.accountid where vtiger_campaign.campaignid = '.$id.' and vtiger_crmentity.deleted=0';
		if($this->column_fields['account_id'] != 0)
		$log->debug("Exiting get_opportunities method ...");
		return GetRelatedList('Campaigns','Potentials',$focus,$query,$button,$returnset);

	}

	/**
	 * Function to get Campaign related Activities
	 * @param  integer   $id      - campaignid
	 * returns related activities record in array format
	 */
	function get_activities($id)
	{
		global $log, $singlepane_view;
		$log->debug("Entering get_activities(".$id.") method ...");
		global $app_strings;

		require_once('modules/Calendar/Activity.php');

		$focus = new Activity();

		$button = '';

		if($singlepane_view == 'true')
			$returnset = '&return_module=Campaigns&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Campaigns&return_action=CallRelatedList&return_id='.$id;

		$query = "SELECT vtiger_contactdetails.lastname,
			vtiger_contactdetails.firstname,
			vtiger_contactdetails.contactid,
			vtiger_activity.*,
			vtiger_seactivityrel.*,
			vtiger_crmentity.crmid, vtiger_crmentity.smownerid,
			vtiger_crmentity.modifiedtime,
			case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name,
			vtiger_recurringevents.recurringtype
			FROM vtiger_activity
			INNER JOIN vtiger_seactivityrel
				ON vtiger_seactivityrel.activityid = vtiger_activity.activityid
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid=vtiger_activity.activityid
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
			WHERE vtiger_seactivityrel.crmid=".$id."
			AND vtiger_crmentity.deleted = 0
			AND (activitytype = 'Task'
				OR activitytype = 'Call'
				OR activitytype = 'Meeting')";
		$log->debug("Exiting get_activities method ...");
		return GetRelatedList('Campaigns','Calendar',$focus,$query,$button,$returnset);
	
	}

}
?>
