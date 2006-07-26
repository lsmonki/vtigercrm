<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
require_once('modules/Activities/Activity.php');
require_once('include/logging.php');
require_once("config.php");
require_once('include/database/PearDatabase.php');

$local_log =& LoggerManager::getLogger('index');
$recurring_data = array();
$focus = new Activity();
$activity_mode = $_REQUEST['activity_mode'];
if($activity_mode == 'Events')
{
        $tab_type = 'Events';
}
//echo '<pre>';print_r($_REQUEST);echo '</pre>';
foreach($focus->column_fields as $fieldname => $val)
{
	if(isset($_REQUEST[$fieldname]))
	{
		$value = $_REQUEST[$fieldname];
		$focus->column_fields[$fieldname] = $value;
		if(($fieldname == 'recurringtype') && (isset($_REQUEST['recurringcheck']) && $_REQUEST['recurringcheck'] == 'on'))
			$focus->column_fields["recurringtype"] =  $_REQUEST["recurringtype"];
		else
			$focus->column_fields["recurringtype"] =  '--None--';
	}
}
$focus->save($tab_type);
header("Location: index.php?action=index&module=Calendar&view=".$_REQUEST['view']."&hour=".$_REQUEST['hour']."&day=".$_REQUEST['day']."&month=".$_REQUEST['month']."&year=".$_REQUEST['year']);
?>
