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
 * $Header:  vtiger_crm/sugarcrm/modules/Contacts/Save.php,v 1.1 2004/08/17 15:04:13 gjayakrishnan Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('modules/Contacts/Contact.php');
require_once('include/logging.php');

$local_log =& LoggerManager::getLogger('index');

$focus = new Contact();

if (isset($_REQUEST['new_reports_to_id'])) {
	$focus->retrieve($_REQUEST['new_reports_to_id']);
	$focus->reports_to_id = $_REQUEST['record']; 
}
else {
	$focus->retrieve($_REQUEST['record']);

	foreach($focus->column_fields as $field)
	{
		if(isset($_REQUEST[$field]))
		{
			$focus->$field = $_REQUEST[$field];
			if(get_magic_quotes_gpc() == 1)
			{
				$focus->$field = stripslashes($focus->$field);
			}
		}
	}
	
	foreach($focus->additional_column_fields as $field)
	{
		if(isset($_REQUEST[$field]))
		{
			$value = $_REQUEST[$field];
			$focus->$field = $value;
			if(get_magic_quotes_gpc() == 1)
			{
				$focus->$field = stripslashes($focus->$field);
			}
		}
	}
	if (!isset($_REQUEST['email_opt_out'])) $focus->email_opt_out = 'off';
	if (!isset($_REQUEST['do_not_call'])) $focus->do_not_call = 'off';
}


$focus->save();
$return_id = $focus->id;

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Contacts";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

$local_log->debug("Saved record with id of ".$return_id);

header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
?>