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
 * Contributor(s): Xavier DUTOIT.
 ********************************************************************************/
/*********************************************************************************
 * $Header:  vtiger_crm/sugarcrm/modules/Notes/Save.php,v 1.2 2004/10/06 09:02:05 jack Exp $
 * Description:  Saves an Account record and then redirects the browser to the
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Notes/Note.php');
require_once('include/logging.php');
/** BEGIN CONTRIBUTION
* Date: 09/07/04
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
* All Rights Reserved.
* Contributor(s): Xavier DUTOIT */
require_once('include/file.php');
/** END CONTRIBUTION */

$local_log =& LoggerManager::getLogger('index');

$focus = new Note();

$focus->retrieve($_REQUEST['record']);

foreach($focus->column_fields as $field)
{
	if(isset($_REQUEST[$field]))
	{
		$value = $_REQUEST[$field];
		$focus->$field = $value;
		$local_log->debug("saving note: $field is $value");
		
	}
}

foreach($focus->additional_column_fields as $field)
{
	if(isset($_REQUEST[$field]))
	{
		$value = $_REQUEST[$field];
		$focus->$field = $value;
		
	}
}

/** BEGIN CONTRIBUTION
* Date: 09/07/04
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
* All Rights Reserved.
* Contributor(s): Xavier DUTOIT */
$file = new File();

if ($file->Upload('filename')) {
	$focus->filename = $file->name;
	if (!empty($_REQUEST['previous_filename']) && !empty($focus->filename)) {
		$file->Delete($focus->id.$_REQUEST['previous_filename']);
	}
}
/** END CONTRIBUTION */
if (empty($file->name) && !empty($_REQUEST['previous_filename'])) {
/** BEGIN CONTRIBUTION
* Date: 09/07/04
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
* All Rights Reserved.
* Contributor(s): Xavier DUTOIT */
	// file is too large
	$focus->filename = $_REQUEST['previous_filename'];
}
/** END CONTRIBUTION */

if (!isset($_REQUEST['date_due_flag'])) $focus->date_due_flag = 'off';

$focus->save();

$return_id = $focus->id;

/** BEGIN CONTRIBUTION
* Date: 09/07/04
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
* All Rights Reserved.
* Contributor(s): Xavier DUTOIT */
$file->Setid ($return_id);
/** END CONTRIBUTION */

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Notes";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

$local_log->debug("Saved record with id of ".$return_id);

header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
?>