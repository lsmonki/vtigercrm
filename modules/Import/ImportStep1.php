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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Import/ImportStep1.php,v 1.16.2.1 2005/09/02 11:11:26 cooljaguar Exp $
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


$focus = 0;

global $theme;
$error_msg = '';
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info($mod_strings['LBL_MODULE_NAME'] . " Upload Step 1");

global $import_dir;

$tmp_file_name = $import_dir. "IMPORT_".$current_user->id;

if (file_exists($tmp_file_name))
{
	unlink($tmp_file_name);
}



if (isset($_REQUEST['delete_map_id']))
{
	$import_map = new ImportMap();
	$import_map->mark_deleted($_REQUEST['delete_map_id']);
}

if (isset($_REQUEST['publish']) )
{
	$import_map = new ImportMap();
	$result = 0;

	$import_map = $import_map->retrieve($_REQUEST['import_map_id'], false);

	if ($_REQUEST['publish'] == 'yes')
	{
		$result = $import_map->mark_published($current_user->id,"yes");
		if ($result == -1)
		{
			$error_msg = "Unable to publish. There is another published Import Map by the same name.";
		}
	}
	else if ( $_REQUEST['publish'] == 'no')
	{
	 	// if you don't own this importmap, you do now!
		// unless you have a map by the same name
		$result = $import_map->mark_published($current_user->id,"no");
		if ($result == -1)
		{
			$error_msg = "Unable to un-publish a map owned by another user. You own an Import Map by the same name.";
		}
	}
}

$xtpl=new XTemplate ('modules/Import/ImportStep1.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if ($error_msg != '')
{
	$xtpl->assign("ERROR", $error_msg);
	$xtpl->parse("main.error");
}

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

$xtpl->assign("JAVASCRIPT", get_validate_upload_js());

if ( $_REQUEST['module'] == 'Contacts')
{
$xtpl->parse("main.show_salesforce");
$xtpl->parse("main.show_outlook");
$xtpl->parse("main.show_act");
}
else if ( $_REQUEST['module'] == 'Accounts')
{
$xtpl->parse("main.show_salesforce");
$xtpl->parse("main.show_act");
} 
else if ( $_REQUEST['module'] == 'Potentials')
{
$xtpl->parse("main.show_salesforce");
} 

if ( is_admin($current_user)) 
{
//	$xtpl->parse("main.create_global_map");
}

$query_arr = array('assigned_user_id'=>$current_user->id,'is_published'=>'no','module'=>$_REQUEST['module']);

$import_map_seed = new ImportMap();

$custom_imports_arr = $import_map_seed->retrieve_all_by_string_fields($query_arr);

if ( count($custom_imports_arr) )
{
	foreach ( $custom_imports_arr as $import)
	{
		$xtpl->assign("IMPORT_NAME", $import->name);
		$xtpl->assign("IMPORT_ID", $import->id);
		if ( is_admin($current_user)) 
		{
			$xtpl->parse("main.saved.saved_elem.is_admin");
		}
		$xtpl->parse("main.saved.saved_elem");
	}

	$xtpl->parse("main.saved");
}


$query_arr = array('is_published'=>'yes','module'=>$_REQUEST['module']);

$published_imports_arr = $import_map_seed->retrieve_all_by_string_fields($query_arr);

if ( count($published_imports_arr) )
{
	foreach ( $published_imports_arr as $import)
	{
		$xtpl->assign("IMPORT_NAME", $import->name);
		$xtpl->assign("IMPORT_ID", $import->id);
		if ( is_admin($current_user))
		{	
			$xtpl->parse("main.published.published_elem.is_admin");
		}
		$xtpl->parse("main.published.published_elem");
	}

	$xtpl->parse("main.published");
}


$xtpl->parse("main");

$xtpl->out("main");

?>
