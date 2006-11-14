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
//require_once('modules/Contacts/Contact.php');
//require_once('modules/Leads/Lead.php');
require_once('user_privileges/default_module_view.php');

class OrgUnit extends CRMEntity {
    var $log;
    var $db;


    var $tab_name = Array('vtiger_orgunit');
    var $tab_name_index = Array(
	'vtiger_orgunit'=>'orgunitid');
    var $column_fields = Array();

    var $sortby_fields = Array('organizationname','name');
    var $list_fields = Array(
	'OrgUnit Name'=>Array('vtiger_orgunit'=>'name'),
	'OrgUnit Type'=>Array('vtiger_orgunit'=>'type'),
	'City'=>Array('vtiger_orgunit'=>'city'),
	'Country'=>Array('vtiger_orgunit'=>'country'),
	'State'=>Array('vtiger_orgunit'=>'state')
	);
    var $list_fields_name = Array(
	'OrgUnit Name'=>'name',
	'OrgUnit Type'=>'type',
	'City'=>'city',
	'Country'=>'country',
	'State'=>'state',
	);	  			
    var $list_link_field= 'name';

    //Added these variables which are used as default order by and sortorder in ListView
    var $default_order_by = 'name';
    var $default_sort_order = 'DESC';
    var $search_fields = Array(
	'OrgUnit Name'=>Array(
	    'vtiger_orgunit'=>'organizationname',
	    'vtiger_orgunit'=>'name'));
    var $search_fields_name = Array('OrgUnit Name'=>'name');

    function OrgUnit() 
    {
	$this->log = LoggerManager::getLogger('OrgUnit');
	$this->db = new PearDatabase();
	$this->column_fields = getColumnFields('OrgUnit');
    }

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
	    $sorder = (($_SESSION['ORGANIZATION_SORT_ORDER'] != '')?($_SESSION['ORGANIZATION_SORT_ORDER']):($this->default_sort_order));

	$log->debug("Exiting getSortOrder method ...");
	return $sorder;
    }

    /**
     * Function to get order by
     * return string  $order_by    - fieldname(eg: 'organizationname')
     */
    function getOrderBy()
    {
	global $log;
	$log->debug("Entering getOrderBy() method ...");
	if (isset($_REQUEST['order_by']))
	    $order_by = $_REQUEST['order_by'];
	else
	    $order_by = (($_SESSION['ORGANIZATION_ORDER_BY'] != '')?($_SESSION['ORGANIZATION_ORDER_BY']):($this->default_order_by));

	$log->debug("Exiting getOrderBy method ...");
	return $order_by;
    }

    /**
     *  Save the current orgunit record
     */
    /*
    function Save()
    {
	global $log;
	$log->debug("Entering Save() method ...");
	$isDuplicate = 0;

	// Edit or create?
	if( isset( $_REQUEST['record']) && $_REQUEST['record'] != '') {
	    $this->mode = 'edit';
	} else {
	    $this->mode = 'new';
	}
	// Create a new orgunit entry
	if( $this->mode == 'new') {

	    // Duplicate orgunit name?
	    $sql = "SELECT organizationname FROM vtiger_orgunit
		WHERE organizationname='".$this->column_fields["organizationname"]."' AND
		      name='".$this->column_fields["name"]."'";
	    $result = $this->db->query( $sql);
	    if( $this->db->num_rows( $result) >= 1) {
		$isDuplicate = 1;
	    }

	    // Create the insert query otherwise
	    else {
		$id = $this->db->getUniqueID("vtiger_orgunit");
		$sql = "INSERT INTO vtiger_orgunit";
		$valstr = "(orgunitid";
		$fldstr = "(".$id;
		foreach( $this->column_fields as $key=>$value) {
		    $valstr .= ",'".$value."'";
		    $fldstr .= ",".$key;
		}
		$fldstr .= ")";
		$valstr .= ")";
		$sql .= " ".$fldstr." VALUES ".$valstr;
	    }
	} 

	// Update an entry
	else {
	    $sql = "UPDATE vtiger_orgunit";
	    $updatestr = "";
	    foreach( $this->column_fields as $key=>$value) {
		if( $key != "organizationname" && $key != "logo") {
		    if( $updatestr == "") {
			$updatestr .= $key."='".$value."'";
		    } else {
			$updatestr .= ",".$key."='".$value."'";
		    }
		}
	    }
	    $sql .= " SET ".$updatestr." WHERE orgunitid=".$_REQUEST['record'];
	}

	// Avoid duplicates
	if( $isDuplicate == 1) {
	    // POPUP
	    $_REQUEST['return_action'] = 'EditView';
	}

	// Otherwise execute the query
	else {
	    $result = $this->db->query($sql);

	    // Change to the DetailView
	    $_REQUEST['return_action'] = 'DetailView';
	}

	// We're all done
	$log->debug("Exiting Save() method ...");
    }
    */
}
?>
