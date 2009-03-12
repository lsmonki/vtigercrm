<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
********************************************************************************/

require_once('data/CRMEntity.php');
require_once('data/Tracker.php');

class PBXManager extends CRMEntity {
	var $db, $log; // Used in class functions like CRMEntity

	var $table_name = 'vtiger_pbxmanager';
	var $table_index= 'pbxmanagerid';
	var $column_fields = Array();

	// Mandatory for function getGroupName
	// Array(groupTableName, groupColumnId)
	// groupTableName should have (groupname column)
	//var $groupTable = Array('vtiger_pbxmanagergrouprel','pbxmanagerid');

	// Mandatory table for supporting custom fields
	var $customFieldTable = Array();

	// Mandatory for Saving, Include tables related to this module.
	var $tab_name = Array('vtiger_crmentity', 'vtiger_pbxmanager', 'vtiger_pbxmanagergrouprel', 'vtiger_pbxmanagercf');
	// Mandatory for Saving, Include the table name and index column mapping here.
	var $tab_name_index = Array(
		'vtiger_crmentity' => 'crmid',
		'vtiger_pbxmanager' => 'pbxmanagerid',
	    );

	// Mandatory for Listing
	var $list_fields = Array (
		// Field Label=> Array(tablename, columnname)
		'Call To'=> Array('pbxmanager', 'callto'),
		'Call From'=>Array('pbxmanager', 'callfrom'),
	);
	var $list_fields_name = Array(
		// Field Label=>columnname
		'Call To'=> 'callto',
		'Call From' => 'callfrom'
	);
	var $sortby_fields = Array('callto', 'callfrom', 'callid', 'timeofcall', 'status');
	// Should contain field labels
	var $detailview_links = Array();

	// For alphabetical search
	var $def_basicsearch_col = 'callid';

	// Column value to use on detail view record text display.
	var $def_detailview_recname = '';

	// Required information for enabling Import feature
	var $required_fields = Array();

	// Callback function list during Importing
	var $special_functions =  array();

	var $default_order_by = 'timeofcall';
	var $default_sort_order='DESC';

	function PBXManager() {
		global $log;
		$this->column_fields = getColumnFields('PBXManager');
		$this->db = new PearDatabase();
		$this->log = $log;
	}

	function getSortOrder() {
		global $currentModule;

		$sortorder = $this->default_sort_order;
		if($_REQUEST['sorder']) $sortorder = $_REQUEST['sorder'];
		else if($_SESSION[$currentModule.'_Sort_Order']) 
			$sortorder = $_SESSION[$currentModule.'_Sort_Order'];

		return $sortorder;
	}

	function getOrderBy() {
		global $currentModule;
		$orderby = $this->default_order_by;
		if($_REQUEST['order_by']) $orderby = $_REQUEST['order_by'];
		else if($_SESSION[$currentModule.'_Order_By'])
			$orderby = $_SESSION[$currentModule.'_Order_By'];
		return $orderby;
	}

	function save_module($module) {
	}

	/**
	 * Get list view query.
	 */
	function getListQuery($module) {
		$query = "SELECT $this->table_name.*, vtiger_crmentity.*";
		$query .= " FROM $this->table_name";

		$query .= "	INNER JOIN vtiger_crmentity
						ON vtiger_crmentity.crmid = $this->table_name.$this->table_index
					 LEFT JOIN vtiger_groups
						ON vtiger_groups.groupid = vtiger_crmentity.smownerid";

		// Consider custom table join as well.
		if(!empty($this->customFieldTable)) {
			$query .= " INNER JOIN ".$this->customFieldTable[0]." ON ".$this->customFieldTable[0].'.'.$this->customFieldTable[1] .
				      " = $this->table_name.$this->table_index"; 
		}
		$query .= " LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid ";

		$query .= "	WHERE vtiger_crmentity.deleted = 0";
		$query .= $this->getListViewSecurityParameter($module);
		return $query;
	}

	/**
	 * Apply security restriction (sharing privilege) query part for List view.
	 */
	function getListViewSecurityParameter($module) {
		global $current_user;
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');

		$sec_query = '';
		$tabid = getTabid($module);

		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 
			&& $defaultOrgSharingPermission[$tabid] == 3) {

				$sec_query .= " AND (vtiger_crmentity.smownerid in($current_user->id) OR vtiger_crmentity.smownerid IN 
					(
						SELECT vtiger_user2role.userid FROM vtiger_user2role 
						INNER JOIN vtiger_users ON vtiger_users.id=vtiger_user2role.userid 
						INNER JOIN vtiger_role ON vtiger_role.roleid=vtiger_user2role.roleid 
						WHERE vtiger_role.parentrole LIKE '".$current_user_parent_role_seq."::%'
					) 
					OR vtiger_crmentity.smownerid IN 
					(
						SELECT shareduserid FROM vtiger_tmp_read_user_sharing_per 
						WHERE userid=".$current_user->id." AND tabid=".$tabid."
					) 
					OR 
					(
						vtiger_crmentity.smownerid in (0)";

				if(!empty($this->groupTable)) {
					$sec_query .= " AND 
						(";
		
					// Build the query based on the group association of current user.
					if(sizeof($current_user_groups) > 0) {
						$sec_query .= " vtiger_groups.groupid IN (". implode(",", $current_user_groups) .") OR ";
					}
					$sec_query .= " vtiger_groups.groupid IN 
						(
							SELECT vtiger_tmp_read_group_sharing_per.sharedgroupid 
							FROM vtiger_tmp_read_group_sharing_per
							WHERE userid=".$current_user->id." and tabid=".$tabid."
						)";
					$sec_query .= ") ";
				}

				$sec_query .= ")
				)";
		}
		return $sec_query;
	}

	/**
	 * Create query to export the records.
	 */
	function create_export_query($where)
	{
		global $current_user;
		$thismodule = $_REQUEST['module'];
		
		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery($thismodule, "detail_view");
		
		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT $fields_list, 'vtiger_groups_groupname as Assigned To Group', 
				CASE WHEN (vtiger_users.user_name NOT LIKE '') THEN vtiger_users.user_name ELSE vtiger_groups.groupname END 
				AS user_name FROM vtiger_crmentity INNER JOIN $this->table_name ON vtiger_crmentity.crmid=$this->table_name.$this->table_index";

		if(!empty($this->customFieldTable)) {
			$query .= " INNER JOIN ".$this->customFieldTable[0]." ON ".$this->customFieldTable[0].'.'.$this->customFieldTable[1] .
				      " = $this->table_name.$this->table_index"; 
		}

		$query .=  
			//"LEFT JOIN " . $this->groupTable[0] . " ON " . $this->groupTable[0].'.'.$this->groupTable[1] . " = $this->table_name.$this->table_index
			"LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid";
		$query .= " LEFT JOIN vtiger_users ON vtiger_crmentity.smownerid = vtiger_users.id and vtiger_users.status='Active'";

		$where_auto = " vtiger_crmentity.deleted=0";

		if($where != '') $query .= " WHERE ($where) AND $where_auto";
		else $query .= " WHERE $where_auto";

		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');

		// Security Check for Field Access
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[7] == 3)
		{
			//Added security check to get the permitted records only
			$query = $query." ".getListViewSecurityParameter($thismodule);
		}
		return $query;
	}

	/**
	 * Initialize this instance for importing.
	 */
	function initImport($module) {
		$this->db = new PearDatabase();
		$this->initImportableFields($module);
	}

	/**
	 * Create list query to be shown at the last step of the import.
	 * Called From: modules/Import/UserLastImport.php
	 */
	function create_import_query($module) {
		global $current_user;
		$query = "SELECT vtiger_crmentity.crmid, vtiger_users.user_name, $this->table_name.* FROM $this->table_name
			INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = $this->table_name.$this->table_index
			LEFT JOIN vtiger_users_last_import ON vtiger_users_last_import.bean_id=vtiger_crmentity.crmid
			LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid
			WHERE vtiger_users_last_import.assigned_user_id='$current_user->id'
			AND vtiger_users_last_import.bean_type='$module'
			AND vtiger_users_last_import.deleted=0
			AND vtiger_users.status = 'Active'";
		return $query;
	}

	/**
	 * Delete the last imported records.
	 */
	function undo_import($module, $user_id) {
		global $adb;
		$count = 0;
		$query1 = "select bean_id from vtiger_users_last_import where assigned_user_id=? AND bean_type='$module' AND deleted=0";
		$result1 = $adb->pquery($query1, array($user_id)) or die("Error getting last import for undo: ".mysql_error()); 
		while ( $row1 = $adb->fetchByAssoc($result1))
		{
			$query2 = "update vtiger_crmentity set deleted=1 where crmid=?";
			$result2 = $adb->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: ".mysql_error()); 
			$count++;			
		}
		return $count;
	}

	/**
	 * Function which will set the assigned user id for import record.
	 */
	function set_import_assigned_user()
	{
		global $current_user, $adb;
		$record_user = $this->column_fields["assigned_user_id"];
		
		if($record_user != $current_user->id){
			$sqlresult = $adb->pquery("select id from vtiger_users where id = ?", array($ass_user));
			if($this->db->num_rows($sqlresult)!= 1) {
				$this->column_fields["assigned_user_id"] = $current_user->id;
			} else {			
				$row = $adb->fetchByAssoc($sqlresult, -1, false);
				if (!empty($row['id']) && $row['id'] != -1) {
					$this->column_fields["assigned_user_id"] = $row['id'];
				} else {
					$this->column_fields["assigned_user_id"] = $current_user->id;
				}
			}
		}
	}

	/**
	 * Default (generic) function to handle the related list for the module.
	 * NOTE: Vtiger_Module::setRelatedList sets reference to this function in vtiger_relatedlists table
	 * if function name is not explicitly specified.
	 */
	function get_related_list($id, $cur_tab_id, $rel_tab_id) {

		global $currentModule, $app_strings;
		$this_module = $currentModule; //vtlib_getModuleNameById($cur_tab_id);

		$related_module = vtlib_getModuleNameById($rel_tab_id);

		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
		
		// Some standard module class doesn't have required variables
		// that are used in the query, they are defined in this generic API
		vtlib_setup_modulevars($related_module, $other);

		$singular_modname = vtlib_toSingular($related_module);

		$button = '';
		if(isPermitted($related_module,1, '') == 'yes') {
			$button .= "<input title='New $related_module' class='crmbutton small edit' onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button' value='$app_strings[LBL_ADD_NEW] $singular_modname'>&nbsp;</td>";
		}

		// To make the edit or del link actions to return back to same view.
		if($singlepane_view == 'true') $returnset = "&return_module=$this_module&return_action=DetailView&return_id=$id";
		else $returnset = "&return_module=$this_module&return_action=CallRelatedList&return_id=$id";

		$query = "SELECT vtiger_crmentity.*, $other->table_name.*";

		
			$query .= ", CASE WHEN (vtiger_users.user_name NOT LIKE '') THEN vtiger_users.user_name ELSE vtiger_groups.groupname END AS user_name";
		

		$more_relation = '';
		if(!empty($other->related_tables)) {
			foreach($other->related_tables as $tname=>$relmap) {
				$query .= ", $tname.*";

				// Setup the default JOIN conditions if not specified
				if(empty($relmap[1])) $relmap[1] = $other->table_name;
				if(empty($relmap[2])) $relmap[2] = $relmap[0];
				$more_relation .= " LEFT JOIN $tname ON $tname.$relmap[0] = $relmap[1].$relmap[2]";
			}
		}

		$query .= " FROM $other->table_name";
		$query .= " INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = $other->table_name.$other->table_index";
		$query .= " INNER JOIN vtiger_crmentityrel ON vtiger_crmentityrel.relcrmid = vtiger_crmentity.crmid";
		$query .= " LEFT  JOIN $this->table_name   ON $this->table_name.$this->table_index = $other->table_name.$other->table_index";
		$query .= $more_relation;
		$query .= " LEFT  JOIN vtiger_users        ON vtiger_users.id = vtiger_crmentity.smownerid";

		
			$query .= " LEFT  JOIN vtiger_groups       ON vtiger_groups.groupid = vtiger_crmentity.smownerid";
		
		$query .= " WHERE vtiger_crmentity.deleted = 0 AND vtiger_crmentityrel.crmid = $id";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);	

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;
		
		return $return_value;
	}

	/**
	 * Save the related module record information. Triggerd from CRMEntity->saveentity method.
	 */
	function save_related_module($module, $crmid, $with_module, $with_crmid) {
		global $adb;
		$adb->query("INSERT INTO vtiger_crmentityrel(crmid, module, relcrmid, relmodule)
		   	VALUES($crmid, '$module', $with_crmid, '$with_module')");
	}

 	/**
	* Invoked when special actions are performed on the module.
	* @param String Module name
	* @param String Event Type
	*/	
	function vtlib_handler($moduleName, $eventType) {
 					
		require_once('include/utils/utils.php');			
		global $adb;
 		
 		if($eventType == 'module.postinstall') {		
			// Add a block and 2 fields for Users module
			$blockid = $adb->getUniqueID('vtiger_blocks');
			$adb->query("insert into vtiger_blocks(blockid,tabid,blocklabel,sequence,show_title,visible,create_view,edit_view,detail_view,display_status)" .
					" values ($blockid,29,'Asterisk Configuration',6,0,0,0,0,0,1)");
			
			$adb->query("insert into vtiger_field(tabid,fieldid,columnname,tablename,generatedtype,uitype,fieldname,fieldlabel,readonly," .
					" presence,selected,maximumlength,sequence,block,displaytype,typeofdata,quickcreate,quickcreatesequence,info_type) " .
					" values (29,".$adb->getUniqueID('vtiger_field').",'asterisk_extension','vtiger_asteriskextensions',1,1,'asterisk_extension'," .
					" 'Asterisk Extension',1,0,0,30,1,$blockid,1,'V~O',1,NULL,'BAS')");
			
			$adb->query("insert into vtiger_field(tabid,fieldid,columnname,tablename,generatedtype,uitype,fieldname,fieldlabel,readonly," .
					" presence,selected,maximumlength,sequence,block,displaytype,typeofdata,quickcreate,quickcreatesequence,info_type) " .
					" values (29,".$adb->getUniqueID('vtiger_field').",'use_asterisk','vtiger_asteriskextensions',1,56,'use_asterisk'," .
					"' Use Asterisk',1,0,0,30,2,$blockid,1,'C~O',1,NULL,'BAS')");
				
			// Mark the module as Standard module
			$adb->pquery('UPDATE vtiger_tab SET customized=0 WHERE name=?', array($moduleName));
			
		} else if($eventType == 'module.disabled') {
		// TODO Handle actions when this module is disabled.
		} else if($eventType == 'module.enabled') {
		// TODO Handle actions when this module is enabled.
		} else if($eventType == 'module.preuninstall') {
		// TODO Handle actions when this module is about to be deleted.
		} else if($eventType == 'module.preupdate') {
		// TODO Handle actions before this module is updated.
		} else if($eventType == 'module.postupdate') {
		// TODO Handle actions after this module is updated.
		}
 	}
}

?>
