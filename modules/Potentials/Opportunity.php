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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Potentials/Opportunity.php,v 1.65 2005/04/28 08:08:27 rank Exp $ 
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
require_once('modules/Contacts/Contact.php');
require_once('modules/Activities/Activity.php');
require_once('modules/Notes/Note.php');
require_once('modules/Emails/Email.php');
require_once('include/utils/utils.php');

// potential is used to store customer information.
class Potential extends CRMEntity {
	var $log;
	var $db;
	// Stored fields
	var $id;
	var $potentialid;
	var $potentialname;
	var $amount;
	var $closingdate;
	var $nextstep;
 	 var $private;
  
	var $probability;
	var $stage;
  	var $potentialtype;

	var $leadsource;
	var $description;
  var $deleted;
	

	// These are related
	var $accountname;
	var $accountid;
	var $productname;
	var $productid;
	var $contactid;
	var $taskid;
	var $notesid;
	var $meetingid;
	var $callid;
	var $emailid;
	var $assigned_user_name;

	var $module_name="Potentials";
	var $table_name = "potential";
	var $rel_product_table = "seproductsrel";
	var $rel_opportunity_table = "contpotentialrel";
	var $module_id = "potentialid";
	var $object_name = "potential";

	var $tab_name = Array('crmentity','potential','potentialscf');
	var $tab_name_index = Array('crmentity'=>'crmid','potential'=>'potentialid','potentialscf'=>'potentialid');
	
	var $column_fields = Array();

        var $sortby_fields = Array('potentialname','amount','closingdate','smownerid');

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'accountname', 'accountid', 'productname', 'productid', 'contactid', 'taskid', 'notesid', 'meetingid', 'callid', 'emailid');


	// This is the list of fields that are in the lists.
	var $list_fields = Array(
	'Potential'=>Array('potential'=>'potentialname'),
	'Account Name'=>Array('account'=>'accountname'),	  			
	'Sales Stage'=>Array('potential'=>'sales_stage'),
	'Amount'=>Array('potential'=>'amount'),
	'Expected Close'=>Array('potential'=>'closingdate'),
	'Assigned To'=>Array('crmentity','smownerid')
	);
	
	var $list_fields_name = Array(
	'Potential'=>'potentialname',
	'Account Name'=>'accountid',	  			
	'Sales Stage'=>'sales_stage',	  			
	'Amount'=>'amount',
	'Expected Close'=>'closingdate',
	'Assigned To'=>'assigned_user_id');

	var $list_link_field= 'potentialname';

	var $record_id;
	var $list_mode;
        var $popup_type;

        var $search_fields = Array(
        'Potential'=>Array('potential'=>'potentialname'),
        'Account Name'=>Array('potential'=>'accountid'),
        'Expected Close'=>Array('potential'=>'closedate')
        );

        var $search_fields_name = Array(
        'Potential'=>'potentialname',
        'Account Name'=>'account_id',
        'Expected Close'=>'closingdate'
        );

	var $required_fields =  array(
				"potentialname"=>1,
				"account_id"=>1,
				"closingdate"=>1,
				"sales_stage"=>1,
				"amount"=>1
);

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'potentialname';
	var $default_sort_order = 'ASC';

	function potential() {
		$this->log = LoggerManager::getLogger('potential');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Potentials');
	}

	var $new_schema = true;

	function create_tables () {
		}

	function drop_tables () {
	
	}

	function get_summary_text()
	{
		return "$this->name";
	}

	function create_list_query($order_by, $where)
	{
		// Determine if the account name is present in the where clause.
		$account_required = ereg("accounts\.name", $where);

		if($account_required)
		{
			$query = "SELECT potential.potentialid,  potential.potentialname, potential.dateclosed FROM potential, account ";
			$where_auto = "account.accountid = potential.accountid AND crmentity.deleted=0 ";
		}
		else
		{
			$query = 'SELECT potentialid, potentialname, smcreatorid, closingdate FROM potential inner join crmentity on crmentity.crmid=potential.potentialid ';
			$where_auto = 'AND crmentity.deleted=0';
		}

		if($where != "")
                  $query .= "where $where ".$where_auto;
		else
			$query .= "where ".$where_auto;

		if($order_by != "")
			$query .= " ORDER BY potential.$order_by";
		else
			$query .= " ORDER BY potential.potentialname";



		return $query;
	}
//method added to construct the query to fetch the custom fields 
	function constructCustomQueryAddendum()
	{		
        
        global $adb;
        	//get all the custom fields created 
		$sql1 = "select columnname,fieldlabel from field where generatedtype=2 and tabid=2";
        	$result = $adb->query($sql1);
		$numRows = $adb->num_rows($result);
		$sql3 = "select ";
		for($i=0; $i < $numRows;$i++)
		{
			$columnName = $adb->query_result($result,$i,"columnname");
			$fieldlable = $adb->query_result($result,$i,"fieldlabel");
			//construct query as below
		       if($i == 0)
		      	{
				$sql3 .= "potentialscf.".$columnName. " '" .$fieldlable."'";
			}
			else
			{	
				$sql3 .= ", potentialscf.".$columnName. " '" .$fieldlable."'";
			}
        	         }
	return $sql3;

		}

//check if the custom table exists or not in the first place
function checkIfCustomTableExists()
{
 $result = $this->db->query("select * from potentialscf");
 $testrow = $this->db->num_fields($result);
	if($testrow > 1)
	{
		$exists=true;
	}
	else
	{
		$exists=false;
	}
return $exists;
}

        function create_export_query($order_by, $where)
        {

		if($this->checkIfCustomTableExists())
		{
 $query = $this->constructCustomQueryAddendum() .",                                potential.*,
                                account.accountname account_name,
                                users.user_name assigned_user_name
                                FROM potential
                                INNER JOIN crmentity
                                ON crmentity.crmid=potential.potentialid
                                LEFT JOIN account on potential.accountid=account.accountid
                                left join potentialscf on potentialscf.potentialid=potential.potentialid
				left join users on crmentity.smcreatorid=users.id where crmentity.deleted=0 ";
		}
		else
		{
                  	$query = "SELECT
                                potential.*,
                                account.accountname account_name,
                                users.user_name assigned_user_name
                                FROM potential inner join crmentity on crmentity.crmid=potential.potentialid                                LEFT JOIN users
                                ON crmentity.smcreatorid=users.id
                                LEFT JOIN account on potential.accountid=account.accountid  LEFT JOIN potentialscf on potentialscf.potentialid=potential.potentialid where crmentity.deleted=0 ";
		}	
                
                return $query;
        
        }



	function save_relationship_changes($is_update)
    {
    	$this->clear_potential_account_relationship($this->id);
    	$this->clear_potential_product_relationship($this->id);

		if($this->account_id != "")
    	{
    		$this->set_potential_account_relationship($this->id, $this->account_id);
    	}
	if($this->product_id != "")
    	{
    		$this->set_potential_product_relationship($this->id, $this->product_id);
    	}
    	if($this->contact_id != "")
    	{
    		$this->set_potential_contact_relationship($this->id, $this->contact_id);
    	}
    	if($this->task_id != "")
    	{
    		$this->set_potential_task_relationship($this->id, $this->task_id);
    	}
    	if($this->note_id != "")
    	{
    		$this->set_potential_note_relationship($this->id, $this->note_id);
    	}
    	if($this->meeting_id != "")
    	{
    		$this->set_potential_meeting_relationship($this->id, $this->meeting_id);
    	}
    	if($this->call_id != "")
    	{
    		$this->set_potential_call_relationship($this->id, $this->call_id);
    	}
    	if($this->email_id != "")
    	{
    		$this->set_potential_email_relationship($this->id, $this->email_id);
    	}
    }

	function set_potential_account_relationship($potential_id, $account_id)
	{
		$query = "insert into accounts_potential (id,potential_id,account_id) values ('".create_guid()."','$potential_id','$account_id')";
		$this->db->query($query, true, "Error setting account to contact relationship: ");

	}

	function clear_potential_account_relationship($potential_id)
	{
		$query = "UPDATE accounts_potential set deleted=1 where potential_id='$potential_id' and deleted=0";
		$this->db->query($query, true, "Error clearing account to potential relationship: ");
	}

	function set_potential_product_relationship($potential_id, $product_id)
	{
		$query = "insert into products_potential (id,potential_id,product_id) values('".create_guid()."','$potential_id','$product_id')";
		$this->db->query($query, true, "Error setting  product to opp relationship: ");

	}

	function clear_potential_product_relationship($potential_id)
	{
		$query = "UPDATE products_potential set deleted=1 where potential_id='$potential_id' and deleted=0";
		$this->db->query($query, true, "Error clearing product to potential relationship: ");
	}

	function set_potential_contact_relationship($potential_id, $contact_id)
	{
		global $app_list_strings;
		$default = $app_list_strings['potential_relationship_type_default_key'];
		$query = "insert into potential_contacts (id,potential_id,contact_id,contact_role) values ('".create_guid()."','$potential_id','$contact_id','$default')";
		$this->db->query($query, true, "Error setting potential to contact relationship: ");
	}

	function clear_potential_contact_relationship($potential_id)
	{
		$query = "UPDATE potential_contacts set deleted=1 where potential_id='$potential_id' and deleted=0";
		$this->db->query($query, true, "Error marking record deleted: ");

	}

	function set_potential_task_relationship($potential_id, $task_id)
	{
		$query = "UPDATE tasks set parent_id='$potential_id', parent_type='Potential' where id='$task_id'";
		$this->db->query($query, true, "Error setting potential to task relationship: ");

	}

	function clear_potential_task_relationship($potential_id)
	{
		$query = "UPDATE tasks set parent_id='', parent_type='' where parent_id='$potential_id'";
		$this->db->query($query, true, "Error clearing potential to task relationship: ");

	}

	function set_potential_note_relationship($potential_id, $note_id)
	{
		$query = "UPDATE notes set parent_id='$potential_id', parent_type='Potential' where id='$note_id'";
		$this->db->query($query, true, "Error setting potential to note relationship: ");
	}

	function clear_potential_note_relationship($potential_id)
	{
		$query = "UPDATE notes set parent_id='', parent_type='' where parent_id='$potential_id'";
		$this->db->query($query, true, "Error clearing potential to note relationship: ");
	}

	function set_potential_meeting_relationship($potential_id, $meeting_id)
	{
		$query = "UPDATE meetings set parent_id='$potential_id', parent_type='Potential' where id='$meeting_id'";
		$this->db->query($query, true,"Error setting potential to meeting relationship: ");
	}

	function clear_potential_meeting_relationship($potential_id)
	{
		$query = "UPDATE meetings set parent_id='', parent_type='' where parent_id='$potential_id'";
		$this->db->query($query, true,"Error clearing potential to meeting relationship: ");
	}

	function set_potential_call_relationship($potential_id, $call_id)
	{
		$query = "UPDATE calls set parent_id='$potential_id', parent_type='Potential' where id='$call_id'";
		$this->db->query($query, true,"Error setting potential to call relationship: ");
	}

	function clear_potential_call_relationship($potential_id)
	{
		$query = "UPDATE calls set parent_id='', parent_type='' where parent_id='$potential_id'";
		$this->db->query($query, true,"Error clearing potential to call relationship: ");
	}

	function set_potential_email_relationship($potential_id, $email_id)
	{
		$query = "UPDATE emails set parent_id='$potential_id', parent_type='Potential' where id='$email_id'";
		$this->db->query($query, true,"Error setting potential to email relationship: ");
	}

	function clear_potential_email_relationship($potential_id)
	{
		$query = "UPDATE emails set parent_id='', parent_type='' where parent_id='$potential_id'";
		$this->db->query($query, true,"Error clearing potential to email relationship: ");
	}

	function mark_relationships_deleted($id)
	{
		$this->clear_potential_contact_relationship($id);
		$this->clear_potential_account_relationship($id);
		$this->clear_potential_product_relationship($id);
		$this->clear_potential_task_relationship($id);
		$this->clear_potential_note_relationship($id);
		$this->clear_potential_meeting_relationship($id);
		$this->clear_potential_call_relationship($id);
		$this->clear_potential_email_relationship($id);
	}

	function fill_in_additional_list_fields()
	{
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);

		$query = "SELECT amount, sales_stage, leadsource FROM potential where potentialid = ".$this->potentialid;
		$result =& $this->db->query($query, true,"Error filling in additional detail fields: ");

		// Get the id and the name.
		$row =  $this->db->fetchByAssoc($result);

		if($row != null)
		{
			$this->leadsource 	= stripslashes($row['leadsource']);
			$this->amount 		= stripslashes($row['amount']);
			$this->sales_stage 	= stripslashes($row['sales_stage']);
		}
		$this->fill_in_additional_detail_fields();
	}

	function fill_in_additional_detail_fields()
	{
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);

		$query = "SELECT acc.accountid, acc.accountname from account acc, potential pt where acc.accountid = pt.accountid and pt.potentialid = '$this->potentialid' and pt.deleted=0";
		$result =& $this->db->query($query, true,"Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		if($row != null)
		{
			$this->accountname = stripslashes($row['accountname']);
			$this->accountid 	= stripslashes($row['accountid']);
		}
		else
		{
			$this->accountname = '';
			$this->accountid = '';
		}

		$query = "SELECT pr.productid, pr.productname from products pr, potential pt where pr.productid = pt.productid and pt.potentialid = '$this->potentialid' and pr.deleted=0 and pt.deleted=0";
		$result =& $this->db->query($query, true,"Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		if($row != null)
		{
			$this->productname = stripslashes($row['productname']);
			$this->productid 	= stripslashes($row['productid']);
		}
		else
		{
			$this->product_name = '';
			$this->product_id = '';
		}


	}


	/** Returns a list of the associated contacts
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
function get_contacts($id)
{
	global $app_strings;

	$focus = new Contact();

	$button = '';

	if(isPermitted("Contacts",3,"") == 'yes')
	{

	$button .= '<input title="Change" accessKey="" tabindex="2" type="button" class="button" value="'.$app_strings['LBL_SELECT_CONTACT_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Contacts&action=Popup&return_module=Potentials&popuptype=detailview&form=EditView&form_submit=false&recordid='.$_REQUEST["record"].'","test","width=600,height=400,resizable=1,scrollbars=1");\'>&nbsp;';
	}
	$returnset = '&return_module=Potentials&return_action=DetailView&return_id='.$id;

	$query = 'select contactdetails.accountid, potential.potentialid, potential.potentialname, contactdetails.contactid, contactdetails.lastname, contactdetails.firstname, contactdetails.title, contactdetails.department, contactdetails.email, contactdetails.phone, crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime from potential inner join contpotentialrel on contpotentialrel.potentialid = potential.potentialid inner join contactdetails on contpotentialrel.contactid = contactdetails.contactid inner join crmentity on crmentity.crmid = contactdetails.contactid where potential.potentialid = '.$id.' and crmentity.deleted=0';
	return GetRelatedList('Potentials','Contacts',$focus,$query,$button,$returnset);
}

	/** Returns a list of the associated calls
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

		$button .= '<input title="New Task" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.activity_mode.value=\'Task\';this.form.return_module.value=\'Potentials\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_TASK'].'">&nbsp;';
		$button .= '<input title="New Event" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.return_module.value=\'Potentials\';this.form.activity_mode.value=\'Events\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_EVENT'].'">&nbsp;';
	}
	$returnset = '&return_module=Potentials&return_action=DetailView&return_id='.$id;

	$query = "SELECT activity.*,seactivityrel.*,crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime, users.user_name, recurringevents.recurringtype, contactdetails.contactid, contactdetails.lastname, contactdetails.firstname from activity inner join seactivityrel on seactivityrel.activityid=activity.activityid inner join crmentity on crmentity.crmid=activity.activityid left join cntactivityrel on cntactivityrel.activityid= activity.activityid left join contactdetails on contactdetails.contactid= cntactivityrel.contactid left join users on users.id=crmentity.smownerid left join activitygrouprelation on activitygrouprelation.activityid=crmentity.crmid left join groups on groups.groupname=activitygrouprelation.groupname left outer join recurringevents on recurringevents.activityid=activity.activityid where seactivityrel.crmid=".$id." and (activitytype='Task' or activitytype='Call' or activitytype='Meeting') and crmentity.deleted=0 and (activity.status is not NULL && activity.status != 'Completed') and (activity.status is not NULL && activity.status != 'Deferred') or (activity.eventstatus != '' &&  activity.eventstatus = 'Planned')";
	return GetRelatedList('Potentials','Activities',$focus,$query,$button,$returnset);

}


function get_products($id)
{
	require_once('modules/Products/Product.php');
	global $app_strings;

	$focus = new Product();

	$button = '';

	if(isPermitted("Products",1,"") == 'yes')
	{


		$button .= '<input title="New Product" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Products\';this.form.return_module.value=\'Potentials\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_PRODUCT'].'">&nbsp;';
	}
	if(isPermitted("Products",3,"") == 'yes')
	{
		$button .= '<input title="Change" accessKey="" tabindex="2" type="button" class="button" value="'.$app_strings['LBL_SELECT_PRODUCT_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Products&action=Popup&return_module=Potentials&popuptype=detailview&form=EditView&form_submit=false&recordid='.$_REQUEST["record"].'","test","width=600,height=400,resizable=1,scrollbars=1");\'>&nbsp;';
	}
	$returnset = '&return_module=Potentials&return_action=DetailView&return_id='.$id;

	$query = 'select products.productid, products.productname, products.productcode, products.commissionrate, products.qty_per_unit, products.unit_price, crmentity.crmid, crmentity.smownerid from products inner join seproductsrel on products.productid = seproductsrel.productid inner join crmentity on crmentity.crmid = products.productid inner join potential on potential.potentialid = seproductsrel.crmid  where potential.potentialid = '.$id.' and crmentity.deleted = 0';
	return GetRelatedList('Potentials','Products',$focus,$query,$button,$returnset);
}
function get_stage_history($id)
{	
	global $theme;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	require_once ($theme_path."layout_utils.php");

	global $adb;
	global $mod_strings;
	global $app_strings;

	$result=$adb->query($query);
	$noofrows = $adb->num_rows($result);


	if($noofrows == 0)
	{
	}
	else
	{	
		if ($noofrows > 15)
		{
			$list .= '<div style="overflow:auto;height:315px;width:100%;">';
		}

		$list .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="100%">';
		$list .= '<tr class="ModuleListTitle" height=20>';

		$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td class="moduleListTitle" height="21">';

		$list .= $app_strings['LBL_AMOUNT'].'</td>';
		$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td class="moduleListTitle">';

		$list .= $app_strings['LBL_SALES_STAGE'].'</td>';
		$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td class="moduleListTitle">';

		$list .= $app_strings['LBL_PROBABILITY'].'</td>';
		$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td class="moduleListTitle">';

		$list .= $app_strings['LBL_CLOSE_DATE'].'</td>';
		$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td class="moduleListTitle">';

		$list .= $app_strings['LBL_LAST_MODIFIED'].'</td>';
		$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td class="moduleListTitle">';

		$list .= '</td>';
		$list .= '</tr>';

		$list .= '<tr><td COLSPAN="12" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td></tr>';

		$i=1;
		while($row = $adb->fetch_array($result))
		{

			if ($i%2==0)
				$trowclass = 'evenListRow';
			else
				$trowclass = 'oddListRow';

			$list .= '<tr class="'. $trowclass.'">';

			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
			$list .= '<td width="15%">'.$row['amount'].'</td>';

			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
			$list .= '<td width="25%" height="21" style="padding:0px 3px 0px 3px;">';
			$list .= $row['stage'];
			$list .= '</td>';

			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
			$list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
			$list .= $row['probability'];
			$list .= '</td>';

			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
			$list .= '<td width="25%" height="21" style="padding:0px 3px 0px 3px;">';
			//changed to show the close date as user date format -- after 4.2 patch2
			$closedate = getDisplayDate($row['closedate']);
			$list .= $closedate;
			$list .= '</td>';

			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
			$list .= '<td width="20%" height="21" style="padding:0px 3px 0px 3px;">';
			//changed to show the last modified date as user date format -- after 4.2 patch2
			$lastmodified = getDisplayDate($row['lastmodified']);
			$list .= $lastmodified;
			$list .= '</td>';

			$list .= '</td>';

			$list .= '</tr>';
			$i++;
		}

		$list .= '<tr><td COLSPAN="12" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td></tr>';
		$list .= '</table>';
		if ($noofrows > 15)
		{
			$list .= '</div>';
		}

	}
	
	$query = 'select potstagehistory.*, potential.potentialname from potstagehistory inner join potential on potential.potentialid = potstagehistory.potentialid inner join crmentity on crmentity.crmid = potential.potentialid where crmentity.deleted = 0 and potential.potentialid = '.$id;
}

function get_history($id)
{
	$query = "SELECT activity.activityid, activity.subject, activity.status,
		activity.eventstatus, activity.activitytype, contactdetails.contactid,
		contactdetails.firstname, contactdetails.lastname, crmentity.modifiedtime,
		crmentity.createdtime, crmentity.description, users.user_name
			from activity
			inner join seactivityrel on seactivityrel.activityid=activity.activityid
			inner join crmentity on crmentity.crmid=activity.activityid
			left join cntactivityrel on cntactivityrel.activityid= activity.activityid
			left join contactdetails on contactdetails.contactid= cntactivityrel.contactid
			inner join users on crmentity.smcreatorid= users.id
			where (activity.activitytype = 'Meeting' or activity.activitytype='Call' or activity.activitytype='Task')
			and (activity.status = 'Completed' or activity.status = 'Deferred' or (activity.eventstatus != 'Planned' and activity.eventstatus != ''))
			and seactivityrel.crmid=".$id;
	//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php

	return getHistory('Potentials',$query,$id);
}

function get_attachments($id)
{
	// Armando Lüscher 18.10.2005 -> §visibleDescription
	// Desc: Inserted crm2.createdtime, notes.notecontent description, users.user_name
	// Inserted inner join users on crm2.smcreatorid= users.id
	$query = "select notes.title,'Notes      '  ActivityType, notes.filename,
		attachments.type  FileType, crm2.modifiedtime lastmodified,
		seattachmentsrel.attachmentsid, notes.notesid crmid,
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
	$query .= "select attachments.description title ,'Attachments'  ActivityType,
		attachments.name filename, attachments.type FileType,crm2.modifiedtime lastmodified,
		attachments.attachmentsid, seattachmentsrel.attachmentsid crmid,
		crm2.createdtime, attachments.description, users.user_name
			from attachments
			inner join seattachmentsrel on seattachmentsrel.attachmentsid= attachments.attachmentsid
			inner join crmentity on crmentity.crmid= seattachmentsrel.crmid
			inner join crmentity crm2 on crm2.crmid=attachments.attachmentsid
			inner join users on crm2.smcreatorid= users.id
			where crmentity.crmid=".$id."
			order by createdtime desc";

	return getAttachmentsAndNotes('Potentials',$query,$id);
}
	
function get_quotes($id)
{
	global $app_strings;
	require_once('modules/Quotes/Quote.php');

	if($this->column_fields['account_id']!='')
		$focus = new Quote();

	$button = '';
	if(isPermitted("Quotes",1,"") == 'yes')
	{
		$button .= '<input title="'.$app_strings['LBL_NEW_QUOTE_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_QUOTE_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Quotes\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_QUOTE_BUTTON'].'">&nbsp;</td>';
	}
	$returnset = '&return_module=Potentials&return_action=DetailView&return_id='.$id;


	$query = "select crmentity.*, quotes.*,potential.potentialname from quotes inner join crmentity on crmentity.crmid=quotes.quoteid left outer join potential on potential.potentialid=quotes.potentialid where crmentity.deleted=0 and potential.potentialid=".$id;
	return  GetRelatedList('Potentials','Quotes',$focus,$query,$button,$returnset);
}
function get_salesorder($id)
{
	require_once('modules/SalesOrder/SalesOrder.php');
	global $mod_strings;
	global $app_strings;

	$focus = new SalesOrder();

	$button = '';
	if(isPermitted("SalesOrder",1,"") == 'yes')
	{
		$button .= '<input title="'.$app_strings['LBL_NEW_SORDER_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_SORDER_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'SalesOrder\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_SORDER_BUTTON'].'">&nbsp;</td>';
	}

	$returnset = '&return_module=Potentials&return_action=DetailView&return_id='.$id;


	$query = "select crmentity.*, salesorder.*, quotes.subject as quotename, account.accountname, potential.potentialname from salesorder inner join crmentity on crmentity.crmid=salesorder.salesorderid left outer join quotes on quotes.quoteid=salesorder.quoteid left outer join account on account.accountid=salesorder.accountid left outer join potential on potential.potentialid=salesorder.potentialid where crmentity.deleted=0 and potential.potentialid = ".$id;
	return GetRelatedList('Potentials','SalesOrder',$focus,$query,$button,$returnset);

}

	function get_list_view_data(){
		global $current_language;
		$app_strings = return_application_language($current_language);
		return  Array(
					'ID' => $this->potentialid,
					'NAME' => (($this->potentialname == "") ? "<em>blank</em>" : $this->potentialname),
					'AMOUNT' => $app_strings['LBL_CURRENCY_SYMBOL'].$this->amount,
					'ACCOUNT_ID' => $this->accountid,
					'ACCOUNT_NAME' => $this->accountname,
					'DATE_CLOSED' => $this->dateclosed,
					'ASSIGNED_USER_NAME' => $this->assigned_user_name,
					"ENCODED_NAME"=>htmlspecialchars($this->name, ENT_QUOTES)
				);
	}
	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string) {
	$where_clauses = Array();
	$the_query_string = addslashes($the_query_string);
	array_push($where_clauses, "potentialname like '$the_query_string%'");

	$the_where = "";
	foreach($where_clauses as $clause)
	{
		if($the_where != "") $the_where .= " or ";
		$the_where .= $clause;
	}


	return $the_where;
}





}



?>
