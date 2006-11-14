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

class Organization extends CRMEntity {
    var $log;
    var $db;


    var $tab_name = Array('vtiger_organizationdetails');
    var $tab_name_index = Array('vtiger_organizationdetails'=>'organizationname');
    var $column_fields = Array();

    var $sortby_fields = Array('organizationname');
    var $list_fields = Array(
	'Organization Name'=>Array('vtiger_organizationdetails'=>'organizationname'),
	'City'=>Array('vtiger_organizationdetails'=>'city'),
	'Country'=>Array('vtiger_organizationdetails'=>'country'),
	'State'=>Array('vtiger_organizationdetails'=>'state')
	);
    var $list_fields_name = Array(
	'Organization Name'=>'organizationname',
	'City'=>'city',
	'Country'=>'country',
	'State'=>'state',
	);	  			
    var $list_link_field= 'organizationname';

    //Added these variables which are used as default order by and sortorder in ListView
    var $default_order_by = 'organizationname';
    var $default_sort_order = 'DESC';
    var $search_fields = Array('Organization Name'=>Array('vtiger_organizationdetails'=>'organizationname'));
    var $search_fields_name = Array('Organization Name'=>'organizationname');

    function Organization() 
    {
	$this->log = LoggerManager::getLogger('organization');
	$this->db = new PearDatabase();
	$this->column_fields = getColumnFields('Organization');
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

    /** Function to upload the file to the server and add the file details in the attachments table 
      * @param $file_details -- file details array:: Type array
     */	
    function uploadAndSaveFile($file_details)
    {
	global $log;
	$log->debug("Entering into uploadAndSaveFile($file_details) method.");

	// Arbitrary File Upload Vulnerability fix - Philip
	$binFile = $file_details['name'];
	$filename = basename($binFile);
	$filesize = $file_details['size'];
	$filetmp_name = $file_details['tmp_name'];
	
	//get the file path inwhich folder we want to upload the file
	$upload_file_path = 'test/logo/';
	$orgfile = ereg_replace( ' ', '_', trim( $this->id));
	$orgfile .= ".".$filename;

	//upload the file in server
	if( move_uploaded_file($filetmp_name,$upload_file_path.$orgfile)) {
	    if( validateImageFile(&$file_details))
		$this->db->query("update vtiger_organizationdetails set logoname='".$orgfile."' where organizationname='".$this->id."'");
	    else
		$log->debug("Skip the save attachment process.");
	}
	
	$log->debug("Exiting from uploadAndSaveFile($id,$module,$file_details) method.");
	return;
    }

    /**
     *  Save the current organization record
     */
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
	// Create a new organization entry
	if( $this->mode == 'new') {

	    // Duplicate organization name?
	    $sql = "SELECT organizationname FROM vtiger_organizationdetails
		WHERE organizationname='".$this->column_fields["organizationname"]."'";
	    $result = $this->db->query( $sql);
	    if( $this->db->num_rows( $result) >= 1) {
		$isDuplicate = 1;
	    }

	    // Create the insert query otherwise
	    else {
		$sql = "INSERT INTO vtiger_organizationdetails";
		$valstr = "(";
		$fldstr = "(";
		foreach( $this->column_fields as $key=>$value) {
		    if( $key != "logo") {
			if( $valstr == "(") {
			    $valstr .= "'".$value."'";
			    $fldstr .= $key;
			} else {
			    $valstr .= ",'".$value."'";
			    $fldstr .= ",".$key;
			}
		    }
		}
		$fldstr .= ",logoname,logo)";
		$valstr .= ",'','')";
		$sql .= " ".$fldstr." VALUES ".$valstr;
	    }
	} 

	// Update an entry
	else {
	    $sql = "UPDATE vtiger_organizationdetails";
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
	    $sql .= " SET ".$updatestr." WHERE organizationname='".$_REQUEST['record']."'";
	}

	// Avoid duplicates
	if( $isDuplicate == 1) {
	    // POPUP
	    $_REQUEST['return_action'] = 'EditView';
	}

	// Otherwise execute the query
	else {
	    $result = $this->db->query($sql);

	    // The record was successfully inserted/updated
	    // So do we have to update the LOGO?
	    if( isset($_FILES["logo"])) {
		$this->uploadAndSaveFile( $_FILES["logo"]);
	    }

	    // Chnage to the DetailView
	    $_REQUEST['return_action'] = 'DetailView';
	}

	// We're all done
	$log->debug("Exiting Save() method ...");
    }
}
?>
