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

global $mod_strings;
$module_menu = Array(
	Array("index.php?module=Leads&action=EditView&return_module=Leads&return_action=index", $mod_strings['LNK_NEW_LEAD']),
	Array("index.php?module=Contacts&action=EditView&return_module=Contacts&return_action=index", $mod_strings['LNK_NEW_CONTACT']),
	Array("index.php?module=Accounts&action=EditView&return_module=Accounts&return_action=index", $mod_strings['LNK_NEW_ACCOUNT']),
	Array("index.php?module=Opportunities&action=EditView&return_module=Opportunities&return_action=index", $mod_strings['LNK_NEW_OPPORTUNITY']),
	Array("index.php?module=Cases&action=EditView&return_module=Cases&return_action=index", $mod_strings['LNK_NEW_CASE']),
	Array("index.php?module=Notes&action=EditView&return_module=Notes&return_action=index", $mod_strings['LNK_NEW_NOTE']),
	Array("index.php?module=Calls&action=EditView&return_module=Calls&return_action=index", $mod_strings['LNK_NEW_CALL']),
	Array("index.php?module=Emails&action=EditView&return_module=Emails&return_action=index", $mod_strings['LNK_NEW_EMAIL']),
	Array("index.php?module=Meetings&action=EditView&return_module=Meetings&return_action=index", $mod_strings['LNK_NEW_MEETING']),
	Array("index.php?module=Tasks&action=EditView&return_module=Tasks&return_action=index", $mod_strings['LNK_NEW_TASK'])
	);

?>
