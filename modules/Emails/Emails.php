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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Emails/Emails.php,v 1.41 2005/04/28 08:11:21 rank Exp $
 * Description:  TODO: To be written.
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
require_once('modules/Accounts/Accounts.php');
require_once('modules/Potentials/Potentials.php');
require_once('modules/Users/Users.php');

// Email is used to store customer information.
class Emails extends CRMEntity {
	var $log;
	var $db;

	// Stored vtiger_fields
  	// added to check email save from plugin or not
	var $plugin_save = false;

var $rel_users_table = "vtiger_salesmanactivityrel";
var $rel_contacts_table = "vtiger_cntactivityrel";
var $rel_serel_table = "vtiger_seactivityrel";



	var $tab_name = Array('vtiger_crmentity','vtiger_activity');
        var $tab_name_index = Array('vtiger_crmentity'=>'crmid','vtiger_activity'=>'activityid','vtiger_seactivityrel'=>'activityid','vtiger_cntactivityrel'=>'activityid');

	// This is the list of vtiger_fields that are in the lists.
        var $list_fields = Array(
				       'Subject'=>Array('activity'=>'subject'),
				       'Related to'=>Array('seactivityrel'=>'activityid'),
				       'Date Sent'=>Array('activity'=>'date_start'),
				       'Assigned To'=>Array('crmentity','smownerid')
			        );

       var $list_fields_name = Array(
				       'Subject'=>'subject',
				       'Related to'=>'activityid',
				       'Date Sent'=>'date_start',
				       'Assigned To'=>'assigned_user_id'
				    );

       var $list_link_field= 'subject';

	var $column_fields = Array();

	var $sortby_fields = Array('subject','date_start','smownerid');

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'date_start';
	var $default_sort_order = 'ASC';

	/** This function will set the columnfields for Email module 
	*/

	function Emails() {
		$this->log = LoggerManager::getLogger('email');
		$this->log->debug("Entering Emails() method ...");
		$this->log = LoggerManager::getLogger('email');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Emails');
		$this->log->debug("Exiting Email method ...");
	}


	function save_module($module)
	{
		global $adb;
		//Inserting into seactivityrel
		
		  //modified by Richie as raju's implementation broke the feature for addition of webmail to vtiger_crmentity.need to be more careful in future while integrating code
	   	  if($_REQUEST['module']=="Emails" && $_REQUEST['smodule']!='webmails' && (!$this->plugin_save))
		  {
				if($_REQUEST['currentid']!='')
				{
					$actid=$_REQUEST['currentid'];
				}
				else 
				{
					$actid=$_REQUEST['record'];
				}
				$parentid=$_REQUEST['parent_id'];
				if($_REQUEST['module'] != 'Emails' && $_REQUEST['module'] != 'Webmails')
				{
					if(!$parentid) {
						$parentid = $adb->getUniqueID('vtiger_seactivityrel');
					}
					$mysql='insert into vtiger_seactivityrel values('.$parentid.','.$actid.')';
					$adb->query($mysql);
				}
				else
				{	  
					$myids=explode("|",$parentid);  //2@71|
					for ($i=0;$i<(count($myids)-1);$i++)
					{
						$realid=explode("@",$myids[$i]);
						$mycrmid=$realid[0];
						//added to handle the relationship of emails with vtiger_users
						if($realid[1] == -1)
							$mysql='insert into vtiger_salesmanactivityrel values('.$mycrmid.','.$actid.')';
						else	
							$mysql='insert into vtiger_seactivityrel values('.$mycrmid.','.$actid.')';
						$adb->query($mysql);
					}
				}
			}
			else
			{
				if(isset($this->column_fields['parent_id']) && $this->column_fields['parent_id'] != '')
				{
					$this->insertIntoEntityTable('vtiger_seactivityrel', $module);
				}
				elseif($this->column_fields['parent_id']=='' && $insertion_mode=="edit")
				{
					$this->deleteRelation('vtiger_seactivityrel');
				}
			}


			//Insert into cntactivity rel		

			if(isset($this->column_fields['contact_id']) && $this->column_fields['contact_id'] != '')
			{
				$this->insertIntoEntityTable('vtiger_cntactivityrel', $module);
			}
			elseif($this->column_fields['contact_id'] =='' && $insertion_mode=="edit")
			{
				$this->deleteRelation('vtiger_cntactivityrel');
			}
			
			//Inserting into attachment
			
			$this->insertIntoAttachment($this->id,$module);			
		
	}


	function insertIntoAttachment($id,$module)
	{
		global $log, $adb;
		$log->debug("Entering into insertIntoAttachment($id,$module) method.");
		
		$file_saved = false;

		//This is to added to store the existing attachment id of the contact where we should delete this when we give new image

		foreach($_FILES as $fileindex => $files)
		{
			if($files['name'] != '' && $files['size'] > 0)
			{
				$file_saved = $this->uploadAndSaveFile($id,$module,$files);
			}
		}

		$log->debug("Exiting from insertIntoAttachment($id,$module) method.");
	}	
	

	/** Returns a list of the associated contacts
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_contacts($id)
	{
		global $log,$adb;
		$log->debug("Entering get_contacts(".$id.") method ...");
		global $mod_strings;
		global $app_strings;

		$focus = new Contacts();

		$button = '';
		$returnset = '&return_module=Emails&return_action=CallRelatedList&return_id='.$id;

		$query = 'select vtiger_contactdetails.accountid, vtiger_contactdetails.contactid, vtiger_contactdetails.firstname,vtiger_contactdetails.lastname, vtiger_contactdetails.department, vtiger_contactdetails.title, vtiger_contactdetails.email, vtiger_contactdetails.phone, vtiger_contactdetails.emailoptout, vtiger_crmentity.crmid, vtiger_crmentity.smownerid, vtiger_crmentity.modifiedtime from vtiger_contactdetails inner join vtiger_cntactivityrel on vtiger_cntactivityrel.contactid=vtiger_contactdetails.contactid inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_contactdetails.contactid left join vtiger_contactgrouprelation on vtiger_contactdetails.contactid=vtiger_contactgrouprelation.contactid left join vtiger_groups on vtiger_groups.groupname=vtiger_contactgrouprelation.groupname where vtiger_cntactivityrel.activityid='.$adb->quote($id).' and vtiger_crmentity.deleted=0';
		$log->info("Contact Related List for Email is Displayed");
		$log->debug("Exiting get_contacts method ...");
		return GetRelatedList('Emails','Contacts',$focus,$query,$button,$returnset);
	}
	
	/** Returns the column name that needs to be sorted
	 * Portions created by vtigerCRM are Copyright (C) vtigerCRM.
	 * All Rights Reserved..
	 * Contributor(s): Mike Crowe
	*/

	function getSortOrder()
	{	
		global $log;
		$log->debug("Entering getSortOrder() method ...");
		if(isset($_REQUEST['sorder'])) 
			$sorder = $_REQUEST['sorder'];
		else
			$sorder = (($_SESSION['EMAILS_SORT_ORDER'] != '')?($_SESSION['EMAILS_SORT_ORDER']):($this->default_sort_order));

		$log->debug("Exiting getSortOrder method ...");
		return $sorder;
	}

	/** Returns the order in which the records need to be sorted
	 * Portions created by vtigerCRM are Copyright (C) vtigerCRM.
	 * All Rights Reserved..
	 * Contributor(s): Mike Crowe
	*/

	function getOrderBy()
	{
		global $log;
		$log->debug("Entering getOrderBy() method ...");
		if (isset($_REQUEST['order_by'])) 
			$order_by = $_REQUEST['order_by'];
		else
			$order_by = (($_SESSION['EMAILS_ORDER_BY'] != '')?($_SESSION['EMAILS_ORDER_BY']):($this->default_order_by));

		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}	
	// Mike Crowe Mod --------------------------------------------------------

	/** Returns a list of the associated vtiger_users
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_users($id)
	{
		global $log;
		$log->debug("Entering get_users(".$id.") method ...");
		global $adb;
		global $mod_strings;
		global $app_strings;

		$id = $_REQUEST['record'];

		$query = 'SELECT vtiger_users.id, vtiger_users.first_name,vtiger_users.last_name, vtiger_users.user_name, vtiger_users.email1, vtiger_users.email2, vtiger_users.yahoo_id, vtiger_users.phone_home, vtiger_users.phone_work, vtiger_users.phone_mobile, vtiger_users.phone_other, vtiger_users.phone_fax from vtiger_users inner join vtiger_salesmanactivityrel on vtiger_salesmanactivityrel.smid=vtiger_users.id and vtiger_salesmanactivityrel.activityid='.$adb->quote($id);
		$result=$adb->query($query);   

		$noofrows = $adb->num_rows($result);
		$header [] = $app_strings['LBL_LIST_NAME'];

		$header []= $app_strings['LBL_LIST_USER_NAME'];

		$header []= $app_strings['LBL_EMAIL'];

		$header []= $app_strings['LBL_PHONE'];
		while($row = $adb->fetch_array($result))
		{

			global $current_user;

			$entries = Array();

			if(is_admin($current_user))
			{
				$entries[] = $row['last_name'].' '.$row['first_name'];
			}
			else
			{
				$entries[] = $row['last_name'].' '.$row['first_name'];
			}		

			$entries[] = $row['user_name'];
			$entries[] = $row['email1'];
			if($email == '')        $email = $row['email2'];
			if($email == '')        $email = $row['yahoo_id'];

			$entries[] = $row['phone_home'];
			if($phone == '')        $phone = $row['phone_work'];
			if($phone == '')        $phone = $row['phone_mobile'];
			if($phone == '')        $phone = $row['phone_other'];
			if($phone == '')        $phone = $row['phone_fax'];

			//Adding Security Check for User

			$entries_list[] = $entries;
		}

		if($entries_list != '')
			$return_data = array("header"=>$header, "entries"=>$entries);
		$log->debug("Exiting get_users method ...");
		return $return_data;
	}

	/**
	  * Returns a list of the associated vtiger_attachments and vtiger_notes of the Email
	  */
	function get_attachments($id)
	{
		global $log,$adb;
		$log->debug("Entering get_attachments(".$id.") method ...");
		$query = "select vtiger_notes.title,'Notes      '  ActivityType, vtiger_notes.filename,
		vtiger_attachments.type  FileType,crm2.modifiedtime lastmodified,
		vtiger_seattachmentsrel.attachmentsid attachmentsid, vtiger_notes.notesid crmid,
			crm2.createdtime, vtiger_notes.notecontent description, vtiger_users.user_name
		from vtiger_notes
			inner join vtiger_senotesrel on vtiger_senotesrel.notesid= vtiger_notes.notesid
			inner join vtiger_crmentity on vtiger_crmentity.crmid= vtiger_senotesrel.crmid
			inner join vtiger_crmentity crm2 on crm2.crmid=vtiger_notes.notesid and crm2.deleted=0
			left join vtiger_seattachmentsrel  on vtiger_seattachmentsrel.crmid =vtiger_notes.notesid
			left join vtiger_attachments on vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid
			inner join vtiger_users on crm2.smcreatorid= vtiger_users.id
		where vtiger_crmentity.crmid=".$adb->quote($id);
		$query .= ' union all ';
		$query .= "select vtiger_attachments.description title ,'Attachments'  ActivityType,
		vtiger_attachments.name filename, vtiger_attachments.type FileType,crm2.modifiedtime lastmodified,
		vtiger_attachments.attachmentsid  attachmentsid,vtiger_seattachmentsrel.attachmentsid crmid,
			crm2.createdtime, vtiger_attachments.description, vtiger_users.user_name
		from vtiger_attachments
			inner join vtiger_seattachmentsrel on vtiger_seattachmentsrel.attachmentsid= vtiger_attachments.attachmentsid
			inner join vtiger_crmentity on vtiger_crmentity.crmid= vtiger_seattachmentsrel.crmid
			inner join vtiger_crmentity crm2 on crm2.crmid=vtiger_attachments.attachmentsid
			inner join vtiger_users on crm2.smcreatorid= vtiger_users.id
		where vtiger_crmentity.crmid=".$adb->quote($id);
		
		$log->info("Notes&Attachments Related List for Email is Displayed");
		$log->debug("Exiting get_attachments method ...");
		return getAttachmentsAndNotes('Emails',$query,$id);
	}

        /**
          * Returns a list of the Emails to be exported
          */
	function create_export_query(&$order_by, &$where)
	{
		global $log;
		global $current_user;
		$log->debug("Entering create_export_query(".$order_by.",".$where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Emails", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT $fields_list FROM vtiger_activity 
			INNER JOIN vtiger_crmentity 
				ON vtiger_crmentity.crmid=vtiger_activity.activityid 
			LEFT JOIN vtiger_users
				ON vtiger_users.id = vtiger_crmentity.smownerid
			LEFT JOIN vtiger_seactivityrel
				ON vtiger_seactivityrel.activityid = vtiger_activity.activityid
			LEFT JOIN vtiger_contactdetails
				ON vtiger_contactdetails.contactid = vtiger_seactivityrel.crmid
			LEFT JOIN vtiger_cntactivityrel
				ON vtiger_cntactivityrel.activityid = vtiger_activity.activityid
				AND vtiger_cntactivityrel.contactid = vtiger_cntactivityrel.contactid
			LEFT JOIN vtiger_activitygrouprelation
				ON vtiger_activitygrouprelation.activityid = vtiger_crmentity.crmid
			LEFT JOIN vtiger_groups
				ON vtiger_groups.groupname = vtiger_activitygrouprelation.groupname
			LEFT JOIN vtiger_salesmanactivityrel
				ON vtiger_salesmanactivityrel.activityid = vtiger_activity.activityid
			LEFT JOIN vtiger_emaildetails
				ON vtiger_emaildetails.emailid = vtiger_activity.activityid
			LEFT JOIN vtiger_seattachmentsrel 
				ON vtiger_activity.activityid=vtiger_seattachmentsrel.crmid 
			LEFT JOIN vtiger_attachments 
				ON vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid 
			WHERE vtiger_activity.activitytype='Emails' AND vtiger_crmentity.deleted=0 ";

		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
		//we should add security check when the user has Private Access

		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1)
		{
			$sec_parameter=getListViewSecurityParameter("Emails");
			$query .= $sec_parameter;	
		}

		$log->debug("Exiting create_export_query method ...");
                return $query;
        }
        
	/**
	* Used to releate email and contacts -- Outlook Plugin
	*/  
	function set_emails_contact_invitee_relationship($email_id, $contact_id)
	{
		global $log;
		$log->debug("Entering set_emails_contact_invitee_relationship(".$email_id.",". $contact_id.") method ...");
		$query = "insert into $this->rel_contacts_table (contactid,activityid) values('$contact_id','$email_id')";
		$this->db->query($query,true,"Error setting email to contact relationship: "."<BR>$query");
		$log->debug("Exiting set_emails_contact_invitee_relationship method ...");
	}
     
	/**
	* Used to releate email and salesentity -- Outlook Plugin
	*/
	function set_emails_se_invitee_relationship($email_id, $contact_id)
	{
		global $log;
		$log->debug("Entering set_emails_se_invitee_relationship(".$email_id.",". $contact_id.") method ...");
		$query = "insert into $this->rel_serel_table (crmid,activityid) values('$contact_id','$email_id')";
		$this->db->query($query,true,"Error setting email to contact relationship: "."<BR>$query");
		$log->debug("Exiting set_emails_se_invitee_relationship method ...");
	}
     
	/**
	* Used to releate email and Users -- Outlook Plugin
	*/    
	function set_emails_user_invitee_relationship($email_id, $user_id)
	{
		global $log;
		$log->debug("Entering set_emails_user_invitee_relationship(".$email_id.",". $user_id.") method ...");
		$query = "insert into $this->rel_users_table (smid,activityid) values ('$user_id', '$email_id')";
		$this->db->query($query,true,"Error setting email to user relationship: "."<BR>$query");
		$log->debug("Exiting set_emails_user_invitee_relationship method ...");
	}        


}
/** Function to get the emailids for the given ids form the request parameters 
 *  It returns an array which contains the mailids and the parentidlists
*/

function get_to_emailids($module)
{
	global $adb;
	if(isset($_REQUEST["field_lists"]) && $_REQUEST["field_lists"] != "")
	{
		$query = 'select columnname,fieldid from vtiger_field where fieldid in('.ereg_replace(':',',',$_REQUEST["field_lists"]).')';
		$result = $adb->query($query);
		$columns = Array();
		$idlists = '';
		$mailids = '';
		while($row = $adb->fetch_array($result))
    		{
			$columns[]=$row['columnname'];
			$fieldid[]=$row['fieldid'];
		}
		$columnlists = implode(',',$columns);
		$crmids = ereg_replace(':',',',$_REQUEST["idlist"]);
		switch($module)
		{
			case 'Leads':
				$query = 'select crmid,concat(lastname," ",firstname) as entityname,'.$columnlists.' from vtiger_leaddetails inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_leaddetails.leadid left join vtiger_leadscf on vtiger_leadscf.leadid = vtiger_leaddetails.leadid where vtiger_crmentity.deleted=0 and ((ltrim(vtiger_leaddetails.email) != \'\') or (ltrim(vtiger_leaddetails.yahooid) != \'\')) and vtiger_crmentity.crmid in ('.$crmids.')';
				break;
			case 'Contacts':
				$query = 'select crmid,concat(lastname," ",firstname) as entityname,'.$columnlists.' from vtiger_contactdetails inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_contactdetails.contactid left join vtiger_contactscf on vtiger_contactscf.contactid = vtiger_contactdetails.contactid where vtiger_crmentity.deleted=0 and ((ltrim(vtiger_contactdetails.email) != \'\')  or (ltrim(vtiger_contactdetails.yahooid) != \'\')) and vtiger_crmentity.crmid in ('.$crmids.')';
				break;
			case 'Accounts':
				$query = 'select crmid,accountname as entityname,'.$columnlists.' from vtiger_account inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_account.accountid left join vtiger_accountscf on vtiger_accountscf.accountid = vtiger_account.accountid where vtiger_crmentity.deleted=0 and ((ltrim(vtiger_account.email1) != \'\') or (ltrim(vtiger_account.email2) != \'\')) and vtiger_crmentity.crmid in ('.$crmids.')';
				break;
		}
		$result = $adb->query($query);
		while($row = $adb->fetch_array($result))
		{
			$name = $row['entityname'];
			for($i=0;$i<count($columns);$i++)
			{
				if($row[$columns[$i]] != NULL && $row[$columns[$i]] !='')
				{
					$idlists .= $row['crmid'].'@'.$fieldid[$i].'|'; 
					$mailids .= $name.'<'.$row[$columns[$i]].'>,';	
				}
			}
		}

		$return_data = Array('idlists'=>$idlists,'mailds'=>$mailids);
	}else
	{
		$return_data = Array('idlists'=>"",'mailds'=>"");
	}	
	return $return_data;
		
}
?>
