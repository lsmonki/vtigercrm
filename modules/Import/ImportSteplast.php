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
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once('data/Tracker.php');
require_once('modules/Import/ImportContact.php');
require_once('modules/Import/ImportAccount.php');
require_once('modules/Import/ImportOpportunity.php');
require_once('modules/Import/ImportLead.php');
require_once('modules/Import/UsersLastImport.php');
require_once('modules/Import/parse_utils.php');
require_once('include/ListView/ListView.php');
require_once('modules/Contacts/Contact.php');
require_once('include/utils/utils.php');

global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user;
global $theme;
if (! isset( $_REQUEST['module']))
{
	$_REQUEST['module'] = 'Home';
}

if (! isset( $_REQUEST['return_id']))
{
	$_REQUEST['return_id'] = '';
}
if (! isset( $_REQUEST['return_module']))
{
	$_REQUEST['return_module'] = '';
}

if (! isset( $_REQUEST['return_action']))
{
	$_REQUEST['return_action'] = '';
}

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Upload Step 2");

if ( isset($_REQUEST['message']))
{
	?>
	<br>

	<table width="100%" border=1>
	   <tr>
		<td>
		   <br>
		   <?php 
			echo $_REQUEST['message']; 
		   ?>
		   <br>
		   <br>
		</td>
	   </tr>
	</table>
	<?php 
}
?>
<br>
<form name="Import" method="POST" action="index.php">
<input type="hidden" name="module" value="<?php echo $_REQUEST['modulename']; ?>">
<input type="hidden" name="action" value="Import">
<input type="hidden" name="step" value="undo">
<input type="hidden" name="return_module" value="<?php echo $_REQUEST['return_module']; ?>">
<input type="hidden" name="return_id" value="<?php echo $_REQUEST['return_id']; ?>">
<input type="hidden" name="return_action" value="<?php echo $_REQUEST['return_action']; ?>">

<table width="100%" cellpadding="2" cellspacing="0" border="0">
   <tr>
	<td align="right"><input title="<?php echo $mod_strings['LBL_UNDO_LAST_IMPORT']; ?>" accessKey="" class="button" type="submit" name="button" value="  <?php echo $mod_strings['LBL_UNDO_LAST_IMPORT'] ?>  "></td>
        <td></td>
   </tr>
</table>
</form>

<table width="100%" cellpadding="2" cellspacing="0" border="0">
	<form enctype="multipart/form-data" name="Import" method="POST" action="index.php">
		<input type="hidden" name="module" value="<?php echo $_REQUEST['modulename']; ?>">
                <input type="hidden" name="action" value="Import">
                <input type="hidden" name="step" value="1">
                <input type="hidden" name="return_id" value="<?php echo $_REQUEST['return_id']; ?>">
                <input type="hidden" name="return_module" value="<?php echo $_REQUEST['return_module']; ?>">
                <input type="hidden" name="return_action" value="<?php echo (($_REQUEST['return_action'] != '')?$_REQUEST['return_action']:'index'); ?>">
   <tr>
	<td align="right">
		<input title="<?php echo $mod_strings['LBL_IMPORT_MORE'] ?>" accessKey="" class="button" type="submit" name="button" value="  <?php echo $mod_strings['LBL_IMPORT_MORE'] ?>  "  onclick="return true;">
		<input title="<?php echo $mod_strings['LBL_FINISHED'] ?>" accessKey="" class="button" type="submit" name="button" value="  <?php echo $mod_strings['LBL_FINISHED'] ?>  "  onclick="this.form.action.value=this.form.return_action.value;this.form.return_module.value=this.form.return_module.value;return true;">
	</td>
        <td></td>
   </tr>
</table>
</form>

<?php

$currentModule = "Import";

global $limit;
global $list_max_entries_per_page;

$implict_account = false;

$import_modules_array = Array(
				"Contacts"=>"Contact",
				"Potentials"=>"Potential",
				"Leads"=>"Lead",
				"Accounts"=>"Account"
			     );

foreach($import_modules_array as $module_name => $object_name)
{

	$seedUsersLastImport = new UsersLastImport();
	$seedUsersLastImport->bean_type = $module_name;
	$list_query = $seedUsersLastImport->create_list_query($o,$w);
	$current_module_strings = return_module_language($current_language, $module_name);

	$object = new $object_name();
	$seedUsersLastImport->list_fields = $object->list_fields;

	$list_result = $adb->query($list_query);
	//Retreiving the no of rows
	$noofrows = $adb->num_rows($list_result);

	if($noofrows>1) 
	{
		if($module_name != 'Accounts')
		{
			$implict_account=true;
		}

		if($module_name == 'Accounts' && $implict_account==true)
			echo get_form_header('','<b>Newly created Accounts</b>', false);
		else
			echo get_form_header('','<b>Last Imported '.$module_name.'</b>', false);

		$smarty = new vtigerCRM_Smarty;

		$smarty->assign("MOD", $mod_strings);
		$smarty->assign("APP", $app_strings);
		$smarty->assign("IMAGE_PATH",$image_path);
		$smarty->assign("MODULE",$module_name);
		$smarty->assign("SINGLE_MOD",$module_name);
		$smarty->assign("SHOW_MASS_SELECT",'false');

		//Retreiving the start value from request
		if($module_name == $_REQUEST['nav_module'] && isset($_REQUEST['start']) && $_REQUEST['start'] != '')
		{
			$start = $_REQUEST['start'];
		}
		else
		{
			$start = 1;
		}

		$info_message='&recordcount='.$_REQUEST['recordcount'].'&noofrows='.$_REQUEST['noofrows'].'&message='.$_REQUEST['message'].'&skipped_record_count='.$_REQUEST['skipped_record_count'];
		$url_string = '&modulename='.$_REQUEST['modulename'].'&nav_module='.$module_name.$info_message;
		$viewid = '';

		//Retreive the Navigation array
		$navigation_array = getNavigationValues($start, $noofrows, $list_max_entries_per_page);
		$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Import","ImportSteplast",$viewid);

		//Retreive the List View Header and Entries
		$listview_header = getListViewHeader($object,$module_name);
		$listview_entries = getListViewEntries($object,$module_name,$list_result,$navigation_array,"","","EditView","Delete","");

		$smarty->assign("NAVIGATION", $navigationOutput);
		$smarty->assign("LISTHEADER", $listview_header);
		$smarty->assign("LISTENTITY", $listview_entries);

		$smarty->display("ListViewEntries.tpl");
		echo "<BR>";
	}
}

?>
