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

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Import/ImportContact.php');
require_once('modules/Import/ImportAccount.php');
require_once('modules/Import/ImportOpportunity.php');
require_once('modules/Import/UsersLastImport.php');
require_once('modules/Import/parse_utils.php');
require_once('include/ListView/ListView.php');

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

echo $_REQUEST['message']; ?>
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
<input type="hidden" name="module" value="<?php echo $_REQUEST['module']; ?>">
<input type="hidden" name="action" value="Import">
<input type="hidden" name="step" value="undo">
<input type="hidden" name="return_module" value="<?php echo $_REQUEST['return_module']; ?>">
<input type="hidden" name="return_id" value="<?php echo $_REQUEST['return_id']; ?>">
<input type="hidden" name="return_action" value="<?php echo $_REQUEST['return_action']; ?>">

<table width="100%" cellpadding="2" cellspacing="0" border="0"><tr>
        <td align="right"><input title="<?php echo $mod_strings['LBL_UNDO_LAST_IMPORT']; ?>" accessKey="" class="button" type="submit" name="button" value="  <?php echo $mod_strings['LBL_UNDO_LAST_IMPORT'] ?>  "></td>
        <td></td>
</tr>
</table>
</form>
<table width="100%" cellpadding="2" cellspacing="0" border="0">
 <form enctype="multipart/form-data" name="Import" method="POST" action="index.php">
                        <input type="hidden" name="module" value="<?php echo $_REQUEST['module']; ?>">
                        <input type="hidden" name="action" value="Import">
                        <input type="hidden" name="step" value="1">
                        <input type="hidden" name="return_id" value="<?php echo $_REQUEST['return_id']; ?>">
                        <input type="hidden" name="return_module" value="<?php echo $_REQUEST['return_module']; ?>">
                        <input type="hidden" name="return_action" value="<?php echo $_REQUEST['return_action']; ?>">
        <tr>
        <td align="right">
<input title="<?php echo $mod_strings['LBL_IMPORT_MORE'] ?>" accessKey="" class="button" type="submit" name="button" value="  <?php echo $mod_strings['LBL_IMPORT_MORE'] ?>  "  onclick="return true;">
<input title="<?php echo $mod_strings['LBL_FINISHED'] ?>" accessKey="" class="button" type="submit" name="button" value="  <?php echo $mod_strings['LBL_FINISHED'] ?>  "  onclick="this.form.action.value=this.form.return_action.value;this.form.return_module.value=this.form.return_module.value;return true;"></td>
        <td></td>
</tr>
</table>

        </form>

<?php

$currentModule = "Import";
global $limit;
global $list_max_entries_per_page;
$newForm = null;
$seedUsersLastImport = new UsersLastImport();
$seedUsersLastImport->bean_type = "Contacts";
$current_module_strings = return_module_language($current_language, 'Contacts');
$seedUsersLastImport->list_fields = Array('id', 'first_name', 'last_name', 'account_name', 'account_id', 'title', 'yahoo_id', 'email1', 'phone_work', 'assigned_user_name', 'assigned_user_id');

$where = "users_last_import.assigned_user_id='{$current_user->id}' AND users_last_import.bean_type='Contacts' and users_last_import.bean_id=contacts.id AND users_last_import.deleted=0";

$ListView = new ListView();
$ListView->initNewXTemplate( 'modules/Contacts/ListView.html',$current_module_strings);
$ListView->setHeaderTitle("Last Imported Contacts" );
$ListView->setQuery($where, "", "","CONTACT");
$ListView->processListView($seedUsersLastImport, "main", "CONTACT");


echo "<BR>";
$newForm = null;
$seedUsersLastImport = new UsersLastImport();
$seedUsersLastImport->bean_type = "Accounts";
$seedUsersLastImport->list_fields = Array('id', 'name', 'website', 'phone_office', 'billing_address_city', 'assigned_user_name', 'assigned_user_id');

$current_module_strings = return_module_language($current_language, 'Accounts');

$where = "users_last_import.assigned_user_id='{$current_user->id}' AND users_last_import.bean_type='Accounts' and users_last_import.bean_id=accounts.id AND users_last_import.deleted=0";

$ListView = new ListView();
$ListView->initNewXTemplate( 'modules/Accounts/ListView.html',$current_module_strings);
$ListView->setHeaderTitle("Last Imported Accounts" );
//$ListView->setQuery($where, "", "name");
$ListView->setQuery($where, "", "","ACCOUNT");
$ListView->processListView($seedUsersLastImport, "main", "ACCOUNT");

echo "<BR>";

//opps list
$newForm = null;
$seedUsersLastImport = new UsersLastImport();
$seedUsersLastImport->bean_type = "Opportunities";
$seedUsersLastImport->list_fields = Array('id', 'name','account_id','account_name','amount','date_closed','assigned_user_name', 'assigned_user_id');

$current_module_strings = return_module_language($current_language, 'Opportunities');

$where = "users_last_import.assigned_user_id='{$current_user->id}' AND users_last_import.bean_type='Opportunities' and users_last_import.bean_id=opportunities.id AND users_last_import.deleted=0";

$ListView = new ListView();
$ListView->initNewXTemplate( 'modules/Opportunities/ListView.html',$current_module_strings);
$ListView->setHeaderTitle("Last Imported Opportunities" );
$ListView->setQuery($where, "", "","OPPORTUNITY");
$ListView->processListView($seedUsersLastImport, "main", "OPPORTUNITY");

?>
