<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

/*
 * Check for image existence in themes orelse
 * use the common one.
 */
// Let us create cache to improve performance
if(!isset($__cache_vtiger_imagepath)) {
    $__cache_vtiger_imagepath = Array();
}
function vtiger_imageurl($imagename, $themename) {
	global $__cache_vtiger_imagepath;
	if($__cache_vtiger_imagepath[$imagename]) {
        $imagepath = $__cache_vtiger_imagepath[$imagename];
    } else {
        $imagepath = "themes/images/$imagename";
        if(file_exists("themes/$themename/images/$imagename")) {
            $imagepath =  "themes/$themename/images/$imagename";
        }
        $__cache_vtiger_imagepath[$imagename] = $imagepath;
    }
	return $imagepath;
}

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
	$modules = Array (
		'Administration', 'CustomView', 'Settings', 'Users', 'Migration', 
		'Utilities', 'uploads', 'Import', 'System', 'com_vtiger_workflow'
	);
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

	$sqlresult = $adb->query("SELECT name, presence, customized FROM vtiger_tab WHERE name NOT IN ('Users') AND presence IN (0,1) ORDER BY name");
	$num_rows  = $adb->num_rows($sqlresult);
	for($idx = 0; $idx < $num_rows; ++$idx) {
		$module = $adb->query_result($sqlresult, $idx, 'name');
		$presence=$adb->query_result($sqlresult, $idx, 'presence');
		$customized=$adb->query_result($sqlresult, $idx, 'customized');
		$hassettings=file_exists("modules/$module/Settings.php");

		$modinfo[$module] = Array( 'customized'=>$customized, 'presence'=>$presence, 'hassettings'=>$hassettings );
	}
	return $modinfo;
}

/**
 * Get list of language and its current status.
 */
function vtlib_getToggleLanguageInfo() {
	global $adb;

	// The table might not exists!
	$old_dieOnError = $adb->dieOnError;
	$adb->dieOnError = false;

	$langinfo = Array();
	$sqlresult = $adb->query("SELECT * FROM vtiger_language");
	if($sqlresult) {
		for($idx = 0; $idx < $adb->num_rows($sqlresult); ++$idx) {
			$row = $adb->fetch_array($sqlresult);
			$langinfo[$row['prefix']] = Array( 'label'=>$row['label'], 'active'=>$row['active'] );
		}
	}
	$adb->dieOnError = $old_dieOnError;
	return $langinfo;
}

/**
 * Toggle the language (enable/disable)
 */
function vtlib_toggleLanguageAccess($langprefix, $enable_disable) {
	global $adb;

	// The table might not exists!
	$old_dieOnError = $adb->dieOnError;
	$adb->dieOnError = false;

	if($enable_disable === true) $enable_disable = 1;
	else if($enable_disable === false) $enable_disable = 0;

	$adb->pquery('UPDATE vtiger_language set active = ? WHERE prefix = ?', Array($enable_disable, $langprefix));

	$adb->dieOnError = $old_dieOnError;
}

/**
 * Get help information set for the module fields.
 */
function vtlib_getFieldHelpInfo($module) {
	global $adb;
	$fieldhelpinfo = Array();
	if(in_array('helpinfo', $adb->getColumnNames('vtiger_field'))) {
		$result = $adb->pquery('SELECT fieldname,helpinfo FROM vtiger_field '.
			'WHERE tabid = (SELECT tabid FROM vtiger_tab WHERE name =?)', Array($module));
		if($result && $adb->num_rows($result)) {
			while($fieldrow = $adb->fetch_array($result)) {
				$helpinfo = decode_html($fieldrow['helpinfo']);
				if(!empty($helpinfo)) {
					$fieldhelpinfo[$fieldrow['fieldname']] = $helpinfo;
				}
			}
		}
	}
	return $fieldhelpinfo;
}

/**
 * Setup mandatory (requried) module variable values in the module class.
 */
function vtlib_setup_modulevars($module, $focus) {

	$checkfor = Array('table_name', 'table_index', 'related_tables', 'popup_fields', 'IsCustomModule');
	foreach($checkfor as $check) {
		if(!isset($focus->$check)) $focus->$check = __vtlib_get_modulevar_value($module, $check);
	}
}
function __vtlib_get_modulevar_value($module, $varname) {
	$mod_var_mapping = 
		Array(
			'Accounts' => 
			Array(
				'IsCustomModule'=>false,
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
				'IsCustomModule'=>false,
				'table_name'  => 'vtiger_contactdetails',
				'table_index' => 'contactid',
				'related_tables'=> Array( 'vtiger_account' => Array ('accountid' ) ),
				'popup_fields' => Array ('lastname'),
			),
			'Leads' =>
			Array(
				'IsCustomModule'=>false,
				'table_name'  => 'vtiger_leaddetails',
				'table_index' => 'leadid',
				'related_tables' => Array ( 
					'vtiger_leadsubdetails' => Array ( 'leadsubscriptionid', 'vtiger_leaddetails', 'leadid' ),
					'vtiger_leadaddress'    => Array ( 'leadaddressid', 'vtiger_leaddetails', 'leadid' ),		   	
				),
				'popup_fields'=> Array ('lastname'),
			),
			'Campaigns' =>
			Array(
				'IsCustomModule'=>false,
				'table_name'  => 'vtiger_campaign',
				'table_index' => 'campaignid',
				'popup_fields' => Array ('campaignname'),
			),
			'Potentials' =>
			Array(
				'IsCustomModule'=>false,
				'table_name' => 'vtiger_potential',
				'table_index'=> 'potentialid',
				'related_tables' => Array ('vtiger_account' => Array('accountid')),
				'popup_fields'=> Array('potentialname'),
			),
			'Quotes' =>
			Array(
				'IsCustomModule'=>false,
				'table_name' => 'vtiger_quotes',
				'table_index'=> 'quoteid',
				'related_tables' => Array ('vtiger_account' => Array('accountid')),
				'popup_fields'=>Array('subject'),				
			),
			'SalesOrder'=>
			Array(
				'IsCustomModule'=>false,
				'table_name' => 'vtiger_salesorder',
				'table_index'=> 'salesorderid',
				'related_tables'=> Array ('vtiger_account' => Array('accountid')),
				'popup_fields'=>Array('subject'),
			),
			'PurchaseOrder'=>
			Array(
				'IsCustomModule'=>false,
				'table_name' => 'vtiger_purchaseorder',
				'table_index'=> 'purchaseorderid',
				'popup_fields'=>Array('subject'),
			),
			'Invoice'=>
			Array(
				'IsCustomModule'=>false,
				'table_name' => 'vtiger_invoice',
				'table_index'=> 'invoiceid',
				'popup_fields'=> Array('subject'),
			),
			'HelpDesk'=>
			Array(
				'IsCustomModule'=>false,
				'table_name' => 'vtiger_troubletickets',
				'table_index'=> 'ticketid',
				'popup_fields'=> Array('ticket_title')
			),
			'Faq'=>
			Array(
				'IsCustomModule'=>false,
				'table_name' => 'vtiger_faq',
				'table_index'=> 'id',
			),
			'Documents'=>
			Array(
				'IsCustomModule'=>false,
				'table_name' => 'vtiger_notes',
				'table_index'=> 'notesid',
			),
			'Products'=>
			Array(
				'IsCustomModule'=>false,
				'table_name' => 'vtiger_products',
				'table_index'=> 'productid',
				'popup_fields'=> Array('productname'),
			),
			'PriceBooks'=>
			Array(
				'IsCustomModule'=>false,
				'table_name' => 'vtiger_pricebook',
				'table_index'=> 'pricebookid',
			),
			'Vendors'=>
			Array(
				'IsCustomModule'=>false,
				'table_name' => 'vtiger_vendor',
				'table_index'=> 'vendorid',
				'popup_fields'=>Array('vendorname'),				
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
 * Get all picklist values for a non-standard picklist type.
 */
function vtlib_getPicklistValues($field_columnname) {
	global $adb;

	$columnname =  mysql_real_escape_string($field_columnname);
	$tablename = "vtiger_$columnname";

	$picklistres = $adb->query("SELECT $columnname as pickvalue FROM $tablename");
	
	$picklistresCount = $adb->num_rows($picklistres);

	$picklistvalues = Array();
	if($picklistresCount) {
		for($index = 0; $index < $picklistresCount; ++$index) {
			$picklistvalues[] = $adb->query_result($picklistres, $index, 'pickvalue');
		}
	}
	return $picklistvalues;
}

/**
 * Check for custom module by its name.
 */
function vtlib_isCustomModule($moduleName) {
	$moduleFile = "modules/$moduleName/$moduleName.php";
	if(file_exists($moduleFile)) {
		if(function_exists('checkFileAccess')) {
			checkFileAccess($moduleFile);
		}
		include_once($moduleFile);
		$focus = new $moduleName();
		return (isset($focus->IsCustomModule) && $focus->IsCustomModule);
	}
	return false;
}

/**
 * Get module specific smarty template path.
 */
function vtlib_getModuleTemplate($module, $templateName) {
	return ("modules/$module/$templateName");
}

/**
 * Check if given directory is writeable.
 * NOTE: The check is made by trying to create a random file in the directory.
 */
function vtlib_isDirWriteable($dirpath) {
	if(is_dir($dirpath)) {
		do {
			$tmpfile = 'vtiger' . time() . '-' . rand(1,1000) . '.tmp';
			// Continue the loop unless we find a name that does not exists already.
			$usefilename = "$dirpath/$tmpfile";
			if(!file_exists($usefilename)) break;
		} while(true);
		$fh = @fopen($usefilename,'a');
		if($fh) {
			fclose($fh);
			unlink($usefilename);
			return true;
		}
	}
	return false;
}

?>
