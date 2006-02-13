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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Import/ImportStepundo.php,v 1.16 2005/05/03 13:18:55 saraj Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Import/ImportContact.php');
require_once('modules/Import/ImportAccount.php');
require_once('modules/Import/UsersLastImport.php');
require_once('modules/Import/parse_utils.php');

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

$log->info("Import Undo");
$last_import = new UsersLastImport();
$ret_value = $last_import->undo($current_user->id);
?>

<br>

<table width="100%" border=1>
<tr>
<td>
<br>
<?php 
if ($ret_value) {
?>
<?php echo $mod_strings['LBL_SUCCESS'] ?><BR>
<?php echo $mod_strings['LBL_LAST_IMPORT_UNDONE'] ?>
<?php 
} 
else 
{
?>
<?php echo $mod_strings['LBL_FAIL'] ?><br>
<?php echo $mod_strings['LBL_NO_IMPORT_TO_UNDO'] ?>
<?php
} 
?>
<br>
<br>
</td>
</tr>
</table>
<br>
<form name="Import" method="POST" action="index.php">
<input type="hidden" name="module" value="<?php echo $_REQUEST['module']; ?>">
<input type="hidden" name="action" value="Import">
<input type="hidden" name="step" value="1">
<input type="hidden" name="return_module" value="<?php echo $_REQUEST['RETURN_MODULE'] ?>">
<input type="hidden" name="return_id" value="<?php echo $_REQUEST['RETURN_ID'] ?>">
<input type="hidden" name="return_action" value="<?php echo $_REQUEST['RETURN_ACTION'] ?>">

<table width="100%" cellpadding="2" cellspacing="0" border="0"><tr>
        <td align="right"><input title="<?php echo $mod_strings['LBL_TRY_AGAIN'] ?>" accessKey="" class="button" type="submit" name="button" value="  <?php echo $mod_strings['LBL_TRY_AGAIN'] ?>  "></td>
        <td></td>
</tr>
</table>
        </form>
