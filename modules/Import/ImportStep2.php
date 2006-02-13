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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Import/ImportStep2.php,v 1.16.2.1 2005/09/02 11:11:26 cooljaguar Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Import/ImportContact.php');
require_once('modules/Import/ImportAccount.php');
require_once('modules/Import/ImportOpportunity.php');
require_once('modules/Import/ImportLead.php');
require_once('modules/Import/Forms.php');
require_once('modules/Import/ImportMap.php');
require_once('modules/Import/ImportProduct.php');

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

$xtpl=new XTemplate ('modules/Import/ImportStep2.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMP", $import_mod_strings);

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

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);

if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);

$xtpl->assign("THEME", $theme);

$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);

$xtpl->assign("HEADER", $app_strings['LBL_IMPORT']." ". $mod_strings['LBL_MODULE_NAME']);

$xtpl->assign("MODULE", $_REQUEST['module']);

// see if the source starts with 'custom' 
// if so, pull off the id, load that map, and get the name
if ($_REQUEST['source'] == "outlook")
{
	$xtpl->assign("SOURCE", $_REQUEST['source']);
	$xtpl->assign("SOURCE_NAME","Outlook ");
	$xtpl->assign("HAS_HEADER_CHECKED"," CHECKED");
} 
else if ($_REQUEST['source'] == "act")
{
	$xtpl->assign("SOURCE", $_REQUEST['source']);
	$xtpl->assign("SOURCE_NAME","ACT! ");
	$xtpl->assign("HAS_HEADER_CHECKED"," CHECKED");
}
else if ( strncasecmp("custom:",$_REQUEST['source'],7) == 0)
{
	$id = substr($_REQUEST['source'],7);
	$import_map_seed = new ImportMap();

	$import_map_seed->retrieve($id, false);

	$adb->println($import_map_seed->toString());

	$xtpl->assign("SOURCE_ID", $import_map_seed->id);
	$xtpl->assign("SOURCE_NAME", $import_map_seed->name);
	$xtpl->assign("SOURCE", "custom");

	if ($import_map_seed->has_header)
	{
		$xtpl->assign("HAS_HEADER_CHECKED"," CHECKED");
	}
}
else
{
	$xtpl->assign("HAS_HEADER_CHECKED"," CHECKED");
	$xtpl->assign("SOURCE", $_REQUEST['source']);
}

$xtpl->assign("JAVASCRIPT", get_validate_upload_js());

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

$xtpl->assign("INSTRUCTIONS_TITLE",$mod_strings["LBL_IMPORT_{$lang_key}_TITLE"]);

for ($i = 1; isset($mod_strings["LBL_{$lang_key}_NUM_$i"]);$i++)
{
$xtpl->assign("STEP_NUM",$mod_strings["LBL_NUM_$i"]);
$xtpl->assign("INSTRUCTION_STEP",$mod_strings["LBL_{$lang_key}_NUM_$i"]);
$xtpl->parse("main.instructions.step");
}
$xtpl->parse("main.instructions");

$xtpl->parse("main");

$xtpl->out("main");

?>
