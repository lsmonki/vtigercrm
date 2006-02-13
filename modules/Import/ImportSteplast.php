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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Import/ImportSteplast.php,v 1.17 2005/07/11 10:31:52 mickie Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Import/ImportContact.php');
require_once('modules/Import/ImportAccount.php');
require_once('modules/Import/ImportOpportunity.php');
require_once('modules/Import/ImportLead.php');
require_once('modules/Import/UsersLastImport.php');
require_once('modules/Import/parse_utils.php');
require_once('include/ListView/ListView.php');
require_once('modules/Contacts/Contact.php');
require_once('include/utils.php');

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
<input type="hidden" name="module" value="<?php echo $_REQUEST['modulename']; ?>">
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
                        <input type="hidden" name="module" value="<?php echo $_REQUEST['modulename']; ?>">
                        <input type="hidden" name="action" value="Import">
                        <input type="hidden" name="step" value="1">
                        <input type="hidden" name="return_id" value="<?php echo $_REQUEST['return_id']; ?>">
                        <input type="hidden" name="return_module" value="<?php echo $_REQUEST['return_module']; ?>">
                        <input type="hidden" name="return_action" value="<?php echo (($_REQUEST['return_action'] != '')?$_REQUEST['return_action']:'index'); ?>">
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

$implict_account = false;
$newForm = null;

$seedUsersLastImport = new UsersLastImport();
$seedUsersLastImport->bean_type = "Contacts";
$contact_query = $seedUsersLastImport->create_list_query($o,$w);
$current_module_strings = return_module_language($current_language, 'Contacts');

/*$seedUsersLastImport->list_fields = Array('id', 'first_name', 'last_name', 'account_name', 'account_id', 'title', 'yahoo_id', 'email1', 'phone_work', 'assigned_user_name', 'assigned_user_id');

$where = "users_last_import.assigned_user_id='{$current_user->id}' AND users_last_import.bean_type='Contacts' and users_last_import.bean_id=contactdetails.contactid AND users_last_import.deleted=0";
*/

$contact = new Contact();
//$seedUsersLastImport->list_fields = $contact->column_fields;
$seedUsersLastImport->list_fields = $contact->list_fields;

$list_result = $adb->query($contact_query);
//Retreiving the no of rows
$noofrows = $adb->num_rows($list_result);

if($noofrows>1) 
{
	$implict_account=true;
	echo get_form_header('Last Imported Contacts','', false);
	$xtpl=new XTemplate ('modules/Contacts/ListView.html');
	$xtpl->assign("MOD", $mod_strings);
	$xtpl->assign("APP", $app_strings);
	$xtpl->assign("IMAGE_PATH",$image_path);


	//Retreiving the start value from request
	if(isset($_REQUEST['start']) && $_REQUEST['start'] != '')
	{
		$start = $_REQUEST['start'];
	}
	else
	{
		$start = 1;
	}

	$adb->println("IMPLST debug start");
	//Retreive the Navigation array
	$navigation_array = getNavigationValues($start, $noofrows, $list_max_entries_per_page);
	$adb->println("IMPLST Naviga");
	$adb->println($navigation_array);
	//Retreive the List View Table Header

	$listview_header = getListViewHeader($contact,"Contacts");
	$xtpl->assign("LISTHEADER", $listview_header);
	
	$adb->println("IMPLST listviewhead");
	$adb->println($listview_header);

	$listview_entries = getListViewEntries($contact,"Contacts",$list_result,$navigation_array);
	$xtpl->assign("LISTHEADER", $listview_header);
	$xtpl->assign("LISTENTITY", $listview_entries);
	$adb->println("GS1");
	if(isset($navigation_array['start']))
	{
		$startoutput = '<a href="index.php?action=index&module=Contacts&start=1"><b>Start</b></a>';
	}
	else
	{
		$startoutput = '[ Start ]';
	}
	if(isset($navigation_array['end']))
	{
		$endoutput = '<a href="index.php?action=index&module=Contacts&start='.$navigation_array['end'].'"><b>End</b></a>';
	}
	else
	{
		$endoutput = '[ End ]';
	}
	if(isset($navigation_array['next']))
	{
		$nextoutput = '<a href="index.php?action=index&module=Contacts&start='.$navigation_array['next'].'"><b>Next</b></a>';
	}
	else
	{
		$nextoutput = '[ Next ]';
	}
	if(isset($navigation_array['prev']))
	{
		$prevoutput = '<a href="index.php?action=index&module=Contacts&start='.$navigation_array['prev'].'"><b>Prev</b></a>';
	}
	else
	{
		$prevoutput = '[ Prev ]';
	}
	$xtpl->assign("Start", $startoutput);
	$xtpl->assign("End", $endoutput);
	$xtpl->assign("Next", $nextoutput);
	$xtpl->assign("Prev", $prevoutput);

	$xtpl->parse("main");

	$xtpl->out("main");

	#$ListView = new ListView();
	#$ListView->initNewXTemplate( 'modules/Contacts/ListView.html',$current_module_strings);
	#$ListView->setHeaderTitle("Last Imported Contacts" );
	#$ListView->setQuery($where, "", "","CONTACT");
	#$ListView->processListView($seedUsersLastImport, "main", "CONTACT");


	echo "<BR>";
}


//opps list
$newForm = null;
$seedUsersLastImport = new UsersLastImport();
$seedUsersLastImport->bean_type = "Potentials";

#$seedUsersLastImport->list_fields = Array('id', 'name','account_id','account_name','amount','date_closed','assigned_user_name', 'assigned_user_id');

$current_module_strings = return_module_language($current_language, 'Potentials');
$potential_query = $seedUsersLastImport->create_list_query($o,$w);

$potential = new Potential();
$seedUsersLastImport->list_fields = $potential->list_fields;

$list_result = $adb->query($potential_query);

//Retreiving the no of rows
$noofrows = $adb->num_rows($list_result);

if($noofrows>1)
{
	$implict_account=true;
	echo get_form_header('Last Imported Potentials','', false);
	$xtpl=new XTemplate ('modules/Potentials/ListView.html');
	$xtpl->assign("MOD", $mod_strings);
	$xtpl->assign("APP", $app_strings);
	$xtpl->assign("IMAGE_PATH",$image_path);


	//Retreiving the start value from request
	if(isset($_REQUEST['start']) && $_REQUEST['start'] != '')
	{
		$start = $_REQUEST['start'];
	}
	else
	{
		$start = 1;
	}
	//Retreive the Navigation array
	$navigation_array = getNavigationValues($start, $noofrows, $list_max_entries_per_page);
	
	//Retreive the List View Table Header

	$listview_header = getListViewHeader($potential,"Potentials");
	$xtpl->assign("LISTHEADER", $listview_header);

	$listview_entries = getListViewEntries($potential,"Potentials",$list_result,$navigation_array);
	$xtpl->assign("LISTHEADER", $listview_header);
	$xtpl->assign("LISTENTITY", $listview_entries);

	if(isset($navigation_array['start']))
	{
		$startoutput = '<a href="index.php?action=index&module=Potentials&start=1"><b>Start</b></a>';
	}
	else
	{
		$startoutput = '[ Start ]';
	}
	if(isset($navigation_array['end']))
	{
		$endoutput = '<a href="index.php?action=index&module=Potentials&start='.$navigation_array['end'].'"><b>End</b></a>';
	}
	else
	{
		$endoutput = '[ End ]';
	}
	if(isset($navigation_array['next']))
	{
		$nextoutput = '<a href="index.php?action=index&module=Potentials&start='.$navigation_array['next'].'"><b>Next</b></a>';
	}
	else
	{
		$nextoutput = '[ Next ]';
	}
	if(isset($navigation_array['prev']))
	{
		$prevoutput = '<a href="index.php?action=index&module=Potentials&start='.$navigation_array['prev'].'"><b>Prev</b></a>';
	}
	else
	{
		$prevoutput = '[ Prev ]';
	}
	$xtpl->assign("Start", $startoutput);
	$xtpl->assign("End", $endoutput);
	$xtpl->assign("Next", $nextoutput);
	$xtpl->assign("Prev", $prevoutput);
	
	$xtpl->parse("main");

	$xtpl->out("main");

	//$where = "users_last_import.assigned_user_id='{$current_user->id}' AND users_last_import.bean_type='Potentials' and users_last_import.bean_id=potential.potentialid AND users_last_import.deleted=0";

	#$ListView = new ListView();
	#$ListView->initNewXTemplate( 'modules/Potentials/ListView.html',$current_module_strings);
	#$ListView->setHeaderTitle("Last Imported Potentials" );
	#$ListView->setQuery($where, "", "","POTENTIAL");
	#$ListView->processListView($seedUsersLastImport, "main", "POTENTIAL");
	echo "<BR>";
}


//leads list
$newForm = null;
$seedUsersLastImport = new UsersLastImport();
$seedUsersLastImport->bean_type = "Leads";

#$seedUsersLastImport->list_fields = Array('id', 'name','account_id','account_name','amount','date_closed','assigned_user_name', 'assigned_user_id');

$current_module_strings = return_module_language($current_language, 'Potentials');
$lead_query = $seedUsersLastImport->create_list_query($o,$w);

$lead = new Lead();
$seedUsersLastImport->list_fields = $lead->list_fields;

$list_result = $adb->query($lead_query);

//Retreiving the no of rows
$noofrows = $adb->num_rows($list_result);

if($noofrows>1)
{
	$implict_account=true;
	echo get_form_header('Last Imported Leads','', false);
	$xtpl=new XTemplate ('modules/Leads/ListView.html');
	$xtpl->assign("MOD", $mod_strings);
	$xtpl->assign("APP", $app_strings);
	$xtpl->assign("IMAGE_PATH",$image_path);

	//Retreiving the start value from request
	if(isset($_REQUEST['start']) && $_REQUEST['start'] != '')
	{
		$start = $_REQUEST['start'];
	}
	else
	{
		$start = 1;
	}
	//Retreive the Navigation array
	$navigation_array = getNavigationValues($start, $noofrows, $list_max_entries_per_page);
	
	//Retreive the List View Table Header

	$listview_header = getListViewHeader($lead,"Leads");
	$xtpl->assign("LISTHEADER", $listview_header);

	$listview_entries = getListViewEntries($lead,"Leads",$list_result,$navigation_array);
	$xtpl->assign("LISTHEADER", $listview_header);
	$xtpl->assign("LISTENTITY", $listview_entries);
	
	if(isset($navigation_array['start']))
	{
		$startoutput = '<a href="index.php?action=index&module=Leads&start=1"><b>Start</b></a>';
	}
	else
	{
		$startoutput = '[ Start ]';
	}
	if(isset($navigation_array['end']))
	{
		$endoutput = '<a href="index.php?action=index&module=Leads&start='.$navigation_array['end'].'"><b>End</b></a>';
	}
	else
	{
		$endoutput = '[ End ]';
	}
	if(isset($navigation_array['next']))
	{
		$nextoutput = '<a href="index.php?action=index&module=Leads&start='.$navigation_array['next'].'"><b>Next</b></a>';
	}
	else
	{
		$nextoutput = '[ Next ]';
	}
	if(isset($navigation_array['prev']))
	{
		$prevoutput = '<a href="index.php?action=index&module=Leads&start='.$navigation_array['prev'].'"><b>Prev</b></a>';
	}
	else
	{
		$prevoutput = '[ Prev ]';
	}
	$xtpl->assign("Start", $startoutput);
	$xtpl->assign("End", $endoutput);
	$xtpl->assign("Next", $nextoutput);
	$xtpl->assign("Prev", $prevoutput);

	$xtpl->parse("main");

	$xtpl->out("main");

	//$where = "users_last_import.assigned_user_id='{$current_user->id}' AND users_last_import.bean_type='Potentials' and users_last_import.bean_id=potential.potentialid AND users_last_import.deleted=0";

	#$ListView = new ListView();
	#$ListView->initNewXTemplate( 'modules/Potentials/ListView.html',$current_module_strings);
	#$ListView->setHeaderTitle("Last Imported Potentials" );
	#$ListView->setQuery($where, "", "","POTENTIAL");
	#$ListView->processListView($seedUsersLastImport, "main", "POTENTIAL");
	echo "<BR>";
}


$newForm = null;
$seedUsersLastImport = new UsersLastImport();
$seedUsersLastImport->bean_type = "Accounts";
$account_query = $seedUsersLastImport->create_list_query($o,$w);
//$seedUsersLastImport->list_fields = Array('id', 'name', 'website', 'phone_office', 'billing_address_city', 'assigned_user_name', 'assigned_user_id');
//$seedUsersLastImport->list_fields = Array('accountid', 'accountname', 'website', 'phone', 'email1', 'assigned_user_name', 'fax');

$current_module_strings = return_module_language($current_language, 'Accounts');

$account = new Account();
$seedUsersLastImport->list_fields = $account->list_fields;

$list_result = $adb->query($account_query);
//Retreiving the no of rows
$noofrows = $adb->num_rows($list_result);

if($noofrows>1)
{
	if($implict_account==true)
		echo get_form_header('Newly created Accounts','', false);
	else
		echo get_form_header('Last Imported Accounts','', false);
	$xtpl=new XTemplate ('modules/Accounts/ListView.html');
	$xtpl->assign("MOD", $mod_strings);
	$xtpl->assign("APP", $app_strings);
	$xtpl->assign("IMAGE_PATH",$image_path);
	

	//Retreiving the start value from request
	if(isset($_REQUEST['start']) && $_REQUEST['start'] != '')
	{
		$start = $_REQUEST['start'];
	}
	else
	{
		$start = 1;
	}
	//Retreive the Navigation array
	$navigation_array = getNavigationValues($start, $noofrows, $list_max_entries_per_page);

	//Retreive the List View Table Header

	$listview_header = getListViewHeader($account,"Accounts");
	//$xtpl->assign("LISTHEADER", $listview_header);
	
	$listview_entries = getListViewEntries($account,"Accounts",$list_result,$navigation_array);
	$xtpl->assign("LISTHEADER", $listview_header);
	$xtpl->assign("LISTENTITY", $listview_entries);
	
	if(isset($navigation_array['start']))
	{
		$startoutput = '<a href="index.php?action=index&module=Accounts&start=1"><b>Start</b></a>';
	}
	else
	{
		$startoutput = '[ Start ]';
	}
	if(isset($navigation_array['end']))
	{
		$endoutput = '<a href="index.php?action=index&module=Accounts&start='.$navigation_array['end'].'"><b>End</b></a>';
	}
	else
	{
		$endoutput = '[ End ]';
	}
	if(isset($navigation_array['next']))
	{
		$nextoutput = '<a href="index.php?action=index&module=Accounts&start='.$navigation_array['next'].'"><b>Next</b></a>';
	}
	else
	{
		$nextoutput = '[ Next ]';
	}
	if(isset($navigation_array['prev']))
	{
		$prevoutput = '<a href="index.php?action=index&module=Accounts&start='.$navigation_array['prev'].'"><b>Prev</b></a>';
	}
	else
	{
		$prevoutput = '[ Prev ]';
	}
	$xtpl->assign("Start", $startoutput);
	$xtpl->assign("End", $endoutput);
	$xtpl->assign("Next", $nextoutput);
	$xtpl->assign("Prev", $prevoutput);

	$xtpl->parse("main");

	$xtpl->out("main");

	//$where = "users_last_import.assigned_user_id='{$current_user->id}' AND users_last_import.bean_type='Accounts' and users_last_import.bean_id=account.accountid AND users_last_import.deleted=0";

	#$ListView = new ListView();
	#$ListView->initNewXTemplate( 'modules/Accounts/ListView.html',$current_module_strings);
	#$ListView->setHeaderTitle("Last Imported Accounts" );
	#$ListView->setQuery($where, "", "name");
	#$ListView->setQuery($where, "", "","ACCOUNT");
	#$ListView->processListView($seedUsersLastImport, "main", "ACCOUNT");
}

?>
