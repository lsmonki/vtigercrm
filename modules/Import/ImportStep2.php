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
 *Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header$
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once('data/Tracker.php');
require_once('modules/Import/ImportContact.php');
require_once('modules/Import/ImportAccount.php');
require_once('modules/Import/ImportOpportunity.php');
require_once('modules/Import/ImportLead.php');
require_once('modules/Import/Forms.php');
require_once('modules/Import/ImportMap.php');
require_once('modules/Import/ImportProduct.php');
require_once('include/utils/CommonUtils.php');

global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user;

global $import_mod_strings;


$focus = 0;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info($mod_strings['LBL_MODULE_NAME'] . " Upload Step 1");

$smarty = new vtigerCRM_Smarty;

$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMP", $import_mod_strings);

$category = getParenttab();
$smarty->assign("CATEGORY", $category);

if ( $_REQUEST['module'] == 'Contacts')
{
	$focus = new ImportContact();
}
else if ( $_REQUEST['module'] == 'Accounts')
{
	$focus = new ImportAccount();
} 
else if ( $_REQUEST['module'] == 'Potentials')
{
	$focus = new ImportOpportunity();
}
else if ( $_REQUEST['module'] == 'Leads')
{
	$focus = new ImportLead();
}
else if ( $_REQUEST['module'] == 'Products')
{
	$focus = new ImportProduct();
}

else
{
 echo "Imports aren't set up for this module type\n";
 exit;
}

if (isset($_REQUEST['return_module'])) $smarty->assign("RETURN_MODULE", $_REQUEST['return_module']);

if (isset($_REQUEST['return_action'])) $smarty->assign("RETURN_ACTION", $_REQUEST['return_action']);

$smarty->assign("THEME", $theme);

$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);

$smarty->assign("HEADER", $app_strings['LBL_IMPORT']." ". $mod_strings['LBL_MODULE_NAME']);

$smarty->assign("MODULE", $_REQUEST['module']);

// see if the source starts with 'custom' 
// if so, pull off the id, load that map, and get the name
if ($_REQUEST['source'] == "outlook")
{
	$smarty->assign("SOURCE", $_REQUEST['source']);
	$smarty->assign("SOURCE_NAME","Outlook ");
	$smarty->assign("HAS_HEADER_CHECKED"," CHECKED");
} 
else if ($_REQUEST['source'] == "act")
{
	$smarty->assign("SOURCE", $_REQUEST['source']);
	$smarty->assign("SOURCE_NAME","ACT! ");
	$smarty->assign("HAS_HEADER_CHECKED"," CHECKED");
}
else if ( strncasecmp("custom:",$_REQUEST['source'],7) == 0)
{
	$id = substr($_REQUEST['source'],7);
	$import_map_seed = new ImportMap();

	$import_map_seed->retrieve($id, false);

	$adb->println($import_map_seed->toString());

	$smarty->assign("SOURCE_ID", $import_map_seed->id);
	$smarty->assign("SOURCE_NAME", $import_map_seed->name);
	$smarty->assign("SOURCE", "custom");

	if ($import_map_seed->has_header)
	{
		$smarty->assign("HAS_HEADER_CHECKED"," CHECKED");
	}
}
else
{
	$smarty->assign("HAS_HEADER_CHECKED"," CHECKED");
	$smarty->assign("SOURCE", $_REQUEST['source']);
}

$smarty->assign("JAVASCRIPT", get_validate_upload_js());

$lang_key = '';

if ($_REQUEST['source'] == "outlook")
{
	$lang_key = "OUTLOOK";
}
else if ($_REQUEST['source'] == "act")
{
	$lang_key = "ACT";
}
else if ($_REQUEST['source'] == "salesforce")
{
	$lang_key = "SF";
}
else 
{
	$lang_key = "CUSTOM";
}

$smarty->assign("INSTRUCTIONS_TITLE",$mod_strings["LBL_IMPORT_{$lang_key}_TITLE"]);

for ($i = 1; isset($mod_strings["LBL_{$lang_key}_NUM_$i"]);$i++)
{
$smarty->assign("STEP_NUM",$mod_strings["LBL_NUM_$i"]);
$smarty->assign("INSTRUCTION_STEP",$mod_strings["LBL_{$lang_key}_NUM_$i"]);

}

$smarty->display("ImportStep1.tpl");

?>
