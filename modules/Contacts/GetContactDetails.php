<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *********************************************************************************/
require_once ('modules/Contacts/Contacts.php');
require_once('data/CRMEntity.php');

$module = $_REQUEST['module'];
$recordId = $_REQUEST['record_id'];

$module_focus = new $module();
$module_focus->retrieve_entity_info($recordId, $module);
$module_focus->apply_field_security($module); //Fields Visibility Checking
echo json_encode($module_focus->column_fields);
?>