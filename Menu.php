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
 * $Header$
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $mod_strings;
global $app_strings;
$module_menu = Array(
	Array("index.php?module=Contacts&action=EditView&return_module=Contacts&return_action=DetailView", $app_strings['LNK_NEW_CONTACT']),
	Array("index.php?module=Leads&action=EditView&return_module=Leads&return_action=DetailView", $app_strings['LNK_NEW_LEAD']),
	Array("index.php?module=Accounts&action=EditView&return_module=Accounts&return_action=DetailView", $app_strings['LNK_NEW_ACCOUNT']),
	Array("index.php?module=Potentials&action=EditView&return_module=Potentials&return_action=DetailView", $app_strings['LNK_NEW_OPPORTUNITY']),
	Array("index.php?module=HelpDesk&action=EditView&return_module=HelpDesk&return_action=DetailView", $app_strings['LNK_NEW_HDESK']),
	Array("index.php?module=Products&action=EditView&return_module=Products&return_action=DetailView", $app_strings['LNK_NEW_PRODUCT']),
	Array("index.php?module=Notes&action=EditView&return_module=Notes&return_action=DetailView", $app_strings['LNK_NEW_NOTE']),
	Array("index.php?module=Emails&action=EditView&return_module=Emails&return_action=DetailView", $app_strings['LNK_NEW_EMAIL']),
	Array("index.php?module=Activities&action=EditView&return_module=Activities&activity_mode=Events&return_action=DetailView", $app_strings['LNK_NEW_EVENT']),
	Array("index.php?module=Activities&action=EditView&return_module=Activities&activity_mode=Task&return_action=DetailView", $app_strings['LNK_NEW_TASK'])
	);

?>
