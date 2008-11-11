<?php

/**
 * Get module name by id.
 */
function vtlib_getModuleNameById($tabid) {
	global $adb;
	$sqlresult = $adb->query("SELECT name FROM vtiger_tab WHERE tabid = $tabid");
	if($adb->num_rows($sqlresult)) return $adb->query_result($sqlresult, 0, 'name');
	return null;
}

/**
 * Get module names for which sharing access can be controlled.
 * NOTE: Ignore the standard modules which is already handled.
 */
function vtlib_getModuleNameForSharing() {
	global $adb;
	$vtlib_sqlres = $adb->query("SELECT * from vtiger_tab WHERE ownedby = 0 
		AND name NOT IN('Calendar','Leads','Accounts','Contacts','Potentials',
			'HelpDesk','Campaigns','Quotes','PurchaseOrder','SalesOrder','Invoice','Events')");
	$vtlib_numrows = $adb->num_rows($vtlib_sqlres);
	$modules = Array();
	for($idx = 0; $idx < $vtlib_numrows; ++$idx) $modules[] = $adb->query_result($vtlib_sqlres, 0, 'name');
	return $modules;
}

/**
 * Check if module is set active (or enabled)
 */
function vtlib_isModuleActive($module) {
	global $adb;
	
	if(in_array($module, vtlib_moduleAlwaysActive())){
		return true;
	}
	
	$tabres = $adb->query("SELECT presence FROM vtiger_tab WHERE name='$module'");

	$active = false;
	if($adb->num_rows($tabres)) {
		$presence = $adb->query_result($tabres, 0, 'presence');
		if($presence != 1) $active = true;
	}
	return $active;
}

/**
 * Get list module names which are always active (cannot be disabled)
 */
function vtlib_moduleAlwaysActive() {
	$modules = Array ('Administration', 'CustomView', 'Settings', 'Users', 'Migration', 'Utilities', 'uploads', 'Import');
	return $modules;
}

/**
 * Toggle the module (enable/disable)
 */
function vtlib_toggleModuleAccess($module, $enable_disable) {
	global $adb;

	if($enable_disable === true) $enable_disable = 0;
	else if($enable_disable === false) $enable_disable = 1;

	$adb->query("UPDATE vtiger_tab set presence = $enable_disable WHERE name = '$module'");

	create_tab_data_file();
	create_parenttab_data_file();
}

/**
 * Get list of module with current status which can be controlled.
 */
function vtlib_getToggleModuleInfo() {
	global $adb;

	$modinfo = Array();

	$sqlresult = $adb->query("SELECT name, presence FROM vtiger_tab WHERE name NOT IN ('Users') AND presence IN (0,1) ORDER BY name");
	$num_rows  = $adb->num_rows($sqlresult);
	for($idx = 0; $idx < $num_rows; ++$idx) {
		$module = $adb->query_result($sqlresult, $idx, 'name');
		$presence=$adb->query_result($sqlresult, $idx, 'presence');

		$modinfo[$module] = $presence;
	}
	return $modinfo;
}

/**
 * Setup mandatory (requried) module variable values in the module class.
 */
function vtlib_setup_modulevars($module, $focus) {

	$checkfor = Array('table_name', 'table_index', 'related_tables', 'popup_fields');
	foreach($checkfor as $check) {
		if(!isset($focus->$check)) $focus->$check = __vtlib_get_modulevar_value($module, $check);
	}
}
function __vtlib_get_modulevar_value($module, $varname) {
	$mod_var_mapping = 
		Array(
			'Accounts' => 
			Array(
				'table_name'  => 'vtiger_account',
				'table_index' => 'accountid',
				// related_tables variable should define the association (relation) between dependent tables
				// FORMAT: related_tablename => Array ( related_tablename_column[, base_tablename, base_tablename_column] )
				// Here base_tablename_column should establish relation with related_tablename_column
				// NOTE: If base_tablename and base_tablename_column are not specified, it will default to modules (table_name, related_tablename_column)
				'related_tables' => Array( 
					'vtiger_accountbillads' => Array ('accountaddressid', 'vtiger_account', 'accountid'),					
					'vtiger_accountshipads' => Array ('accountaddressid', 'vtiger_account', 'accountid'), 
				),
				'popup_fields' => Array('accountname'), // TODO: Add this initialization to all the standard module
			),
			'Contacts' => 
			Array(
				'table_name'  => 'vtiger_contactdetails',
				'table_index' => 'contactid',
				'related_tables'=> Array( 'vtiger_account' => Array ('accountid' ) ),
				'popup_fields' => Array ('lastname'),
			),
			'Leads' =>
			Array(
				'table_name'  => 'vtiger_leaddetails',
				'table_index' => 'leadid',
				'related_tables' => Array ( 
					'vtiger_leadsubdetails' => Array ( 'leadsubscriptionid', 'vtiger_leaddetails', 'leadid' ),
					'vtiger_leadaddress'    => Array ( 'leadaddressid', 'vtiger_leaddetails', 'leadid' ),		   	
				)	
			),
			'Campaigns' =>
			Array(
				'table_name'  => 'vtiger_campaign',
				'table_index' => 'campaignid',
			),
			'Potentials' =>
			Array(
				'table_name' => 'vtiger_potential',
				'table_index'=> 'potentialid',
				'related_tables' => Array ('vtiger_account' => Array('accountid'))
			),
			'Quotes' =>
			Array(
				'table_name' => 'vtiger_quotes',
				'table_index'=> 'quoteid',
				'related_tables' => Array ('vtiger_account' => Array('accountid'))
			),
			'SalesOrder'=>
			Array(
				'table_name' => 'vtiger_salesorder',
				'table_index'=> 'salesorderid',
				'related_tables'=> Array ('vtiger_account' => Array('accountid'))
			),
			'PurchaseOrder'=>
			Array(
				'table_name' => 'vtiger_purchaseorder',
				'table_index'=> 'purchaseorderid',
			),
			'Invoice'=>
			Array(
				'table_name' => 'vtiger_invoice',
				'table_index'=> 'invoiceid',
			),
			'HelpDesk'=>
			Array(
				'table_name' => 'vtiger_troubletickets',
				'table_index'=> 'ticketid',
				'popup_fields'=> Array('ticket_title')
			),
			'Faq'=>
			Array(
				'table_name' => 'vtiger_faq',
				'table_index'=> 'id',
			),
			'Notes'=>
			Array(
				'table_name' => 'vtiger_notes',
				'table_index'=> 'notesid',
			),
			'Products'=>
			Array(
				'table_name' => 'vtiger_products',
				'table_index'=> 'productid',
			),
			'PriceBooks'=>
			Array(
				'table_name' => 'vtiger_pricebook',
				'table_index'=> 'pricebookid',
			),
			'Vendors'=>
			Array(
				'table_name' => 'vtiger_vendor',
				'table_index'=> 'vendorid',
			)
		);
	return $mod_var_mapping[$module][$varname];
}

/**
 * Convert given text input to singular.
 */
function vtlib_tosingular($text) {
	$lastpos = strripos($text, 's');
	if($lastpos == strlen($text)-1) 
		return substr($text, 0, -1);
	return $text;
}

/**
 * Get picklist values that is accessible by all roles.
 */
function vtlib_getPicklistValues_AccessibleToAll($field_columnname) {
	global $adb;

	$columnname =  mysql_real_escape_string($field_columnname);
	$tablename = "vtiger_$columnname";

	// Gather all the roles (except H1 which is organization role)
	$roleres = $adb->query("SELECT roleid FROM vtiger_role WHERE roleid != 'H1'");
	$roleresCount= $adb->num_rows($roleres);
	$allroles = Array();
	if($roleresCount) {
		for($index = 0; $index < $roleresCount; ++$index) 
			$allroles[] = $adb->query_result($roleres, $index, 'roleid');
	}
	sort($allroles);

	// Get all the picklist values associated to roles (except H1 - organization role).
	$picklistres = $adb->query(
		"SELECT $columnname as pickvalue, roleid FROM $tablename 
		INNER JOIN vtiger_role2picklist ON $tablename.picklist_valueid=vtiger_role2picklist.picklistvalueid
		WHERE roleid != 'H1'");
	
	$picklistresCount = $adb->num_rows($picklistres);

	$picklistval_roles = Array();
	if($picklistresCount) {
		for($index = 0; $index < $picklistresCount; ++$index) {
			$picklistval = $adb->query_result($picklistres, $index, 'pickvalue');
			$pickvalroleid=$adb->query_result($picklistres, $index, 'roleid');
			$picklistval_roles[$picklistval][] = $pickvalroleid;
		}
	}
	// Collect picklist value which is associated to all the roles.
	$allrolevalues = Array();
	foreach($picklistval_roles as $picklistval => $pickvalroles) {
		sort($pickvalroles);
		$diff = array_diff($pickvalroles,$allroles);
		if(empty($diff)) $allrolevalues[] = $picklistval;
	}

	return $allrolevalues;
}

/**
 * Get module specific smarty template path.
 */
function vtlib_getModuleTemplate($module, $templateName) {
	return ("Smarty/templates/modules/$module/$templateName");
}

?>
