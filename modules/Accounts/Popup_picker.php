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
 * $Header:  vtiger_crm/modules/Accounts/Popup_picker.php,v 1.1 2004/08/17 15:02:56 gjk Exp $
 * Description:  This file is used for all popups on this module
 * The popup_picker.html file is used for generating a list from which to find and 
 * choose one instance.
 ********************************************************************************/

global $theme;
require_once('modules/Accounts/Account.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('XTemplate/xtpl.php');
global $mod_strings;
global $app_strings;

global $list_max_entries_per_page;
global $urlPrefix;
global $currentModule;

$log = LoggerManager::getLogger('account');

$seedAccount = new Account();

$where = "";
if(isset($_REQUEST['query']))
{
	// we have a query
	if(isset($_REQUEST['name'])) $name = $_REQUEST['name'];
	if(isset($_REQUEST['billing_address_city'])) $billing_address_city = $_REQUEST['billing_address_city'];
	if(isset($_REQUEST['phone_office'])) $phone_office = $_REQUEST['phone_office'];

	$where_clauses = Array();

	if(isset($name))
	{
		array_push($where_clauses, "name like '$name%'");
	}
	if(isset($billing_address_city))
	{
		array_push($where_clauses, "billing_address_city like '$billing_address_city%'");
	}

	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}

	$log->info("Here is the where clause for the list view: $where");

}

$current_offset = 0;
if(isset($_REQUEST['current_offset']))
    $current_offset = $_REQUEST['current_offset'];

$response = $seedAccount->get_list("name", $where, $current_offset);

$account_list = $response['list'];
$row_count = $response['row_count'];
$next_offset = $response['next_offset'];
$previous_offset = $response['previous_offset'];

$start_record = $current_offset + 1;

// Set the start row to 0 if there are no rows (adding one looks bad)
if($row_count == 0)
    $start_record = 0;

$end_record = $start_record + $list_max_entries_per_page;

// back up the the last page.
if($end_record > $row_count+1)
{
    $end_record = $row_count+1;
}

// Deterime the start location of the last page
$number_pages = floor(($row_count - 1)  / $list_max_entries_per_page);
$last_page_offset = $number_pages * $list_max_entries_per_page;
$contactList = $response['list'];
$row_count = $response['row_count'];
$next_offset = $response['next_offset'];
$previous_offset = $response['previous_offset'];

$start_record = $current_offset + 1;

// Set the start row to 0 if there are no rows (adding one looks bad)
if($row_count == 0)
    $start_record = 0;

$end_record = $start_record + $list_max_entries_per_page;

// back up the the last page.
if($end_record > $row_count+1)
{
    $end_record = $row_count+1;
}

// Deterime the start location of the last page
if($row_count == 0)
	$number_pages = 0;
else
	$number_pages = floor(($row_count - 1) / $list_max_entries_per_page);

$last_page_offset = $number_pages * $list_max_entries_per_page;


// Create the base URL without the current offset.
// Check to see if the current offset is already there
// If not, add it to the end.

// All of the other values should use a regular expression search
$base_URL = $_SERVER['REQUEST_URI'].'?'.$_SERVER['QUERY_STRING']."&current_offset=";
$start_URL = $base_URL."0";
$previous_URL  = $base_URL.$previous_offset;
$next_URL  = $base_URL.$next_offset;
$end_URL  = $base_URL.$last_page_offset;

$sort_URL_base = $base_URL.$current_offset."&sort_order=";

$log->debug("Offsets: (start, previous, next, last)(0, $previous_offset, $next_offset, $last_page_offset)");

if(0 == $current_offset)
    $start_link = $app_strings['LNK_LIST_START'];
else
    $start_link = "<a href=\"$start_URL\" class=\"listFormHeaderLinks\">".$app_strings['LNK_LIST_START']."</a>";

if($previous_offset < 0)
    $previous_link = $app_strings['LNK_LIST_PREVIOUS'];
else
    $previous_link = "<a href=\"$previous_URL\" class=\"listFormHeaderLinks\">".$app_strings['LNK_LIST_PREVIOUS']."</a>";

if($next_offset >= $end_record)
    $next_link = $app_strings['LNK_LIST_NEXT'];
else
    $next_link = "<a href=\"$next_URL\" class=\"listFormHeaderLinks\">".$app_strings['LNK_LIST_NEXT']."</a>";

if($last_page_offset <= $current_offset)
    $end_link = $app_strings['LNK_LIST_END'];
else
    $end_link = "<a href=\"$end_URL\" class=\"listFormHeaderLinks\">".$app_strings['LNK_LIST_END']."</a>";

    
    
    
    
    
    
    
$image_path = 'themes/'.$theme.'/images';    
    
////////////////////////////////////////////////////////    
// Start the output    
////////////////////////////////////////////////////////    
if (!isset($_REQUEST['html'])) {
	$form =new XTemplate ('modules/Accounts/Popup_picker.html');
	$log->debug("using file modules/Accounts/Popup_picker.html");
}
else {
	$log->debug("_REQUEST['html'] is ".$_REQUEST['html']);
	$form =new XTemplate ('modules/Accounts/'.$_REQUEST['html'].'.html');
	$log->debug("using file modules/Accounts/".$_REQUEST['html'].'.html');
}

$form->assign("MOD", $mod_strings);
$form->assign("APP", $app_strings);

// the form key is required
if(!isset($_REQUEST['form']))
	die("Missing 'form' parameter");
	
// This code should always return an answer.
// The form name should be made into a parameter and not be hard coded in this file.
if($_REQUEST['form'] == 'TasksEditView')
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(account_id, account_name) {\n";
	$the_javascript .= "	window.opener.document.EditView.parent_name.value = account_name;\n";
	$the_javascript .= "	window.opener.document.EditView.parent_id.value = account_id;\n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<table cellspacing='0' cellpadding='1' border='0'><form border='0' action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<tr><td>&nbsp;</td>";
	$button .= "<td><input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.EditView.parent_name.value = '';window.opener.document.EditView.parent_id.value = ''; window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '>\n";
	$button .= "<td><input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
	$button .= "</td></tr></form></table>\n";
}
elseif(isset($_REQUEST['form_submit']) && $_REQUEST['form'] == 'AccountDetailView' && $_REQUEST['form_submit'] == 'true')
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(account_id, account_name) {\n";
	$the_javascript .= "	window.opener.document.DetailView.member_id.value = account_id; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_module.value = window.opener.document.DetailView.module.value; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_action.value = 'DetailView'; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_id.value = window.opener.document.DetailView.record.value; \n";
	$the_javascript .= "	window.opener.document.DetailView.action.value = 'Save'; \n";
	$the_javascript .= "	window.opener.document.DetailView.submit(); \n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<table cellspacing='0' cellpadding='1' border='0'><form border='0' action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<tr><td>&nbsp;</td>";
	$button .= "<td><input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
	$button .= "</tr></form></table>\n";
}
elseif ($_REQUEST['form'] == 'EditView') 
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(account_id, account_name) {\n";
	$the_javascript .= "	window.opener.document.EditView.account_name.value = account_name;\n";
	$the_javascript .= "	window.opener.document.EditView.account_id.value = account_id;\n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
	$button  = "<table cellspacing='0' cellpadding='1' border='0'><form border='0' action='index.php' method='post' name='form' id='form'>\n";
	$button .= "<tr><td>&nbsp;</td>";
	$button .= "<td><input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.EditView.account_name.value = '';window.opener.document.EditView.account_id.value = ''; window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CLEAR_BUTTON_LABEL']."  '>\n";
	$button .= "<td><input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '>\n";
	$button .= "</td></tr></form></table>\n";
}	

$form->assign("SET_RETURN_JS", $the_javascript);


$form->assign("THEME", $theme);
$form->assign("IMAGE_PATH", $image_path);
$form->assign("MODULE_NAME", $currentModule);
if (isset($_REQUEST['form_submit'])) $form->assign("FORM_SUBMIT", $_REQUEST['form_submit']);
$form->assign("FORM", $_REQUEST['form']);
  
if (isset($_REQUEST['name'])) $form->assign("NAME", $_REQUEST['name']);
if (isset($_REQUEST['billing_address_city'])) $form->assign("BILLING_ADDRESS_CITY", $_REQUEST['billing_address_city']);

insert_popup_header($theme);
// Quick search.

echo get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE'], "", false); 

$form->parse("main.SearchHeader");
$form->out("main.SearchHeader");

echo get_form_footer();

$form->parse("main.SearchHeaderEnd");
$form->out("main.SearchHeaderEnd");

// Reset the sections that are already in the page so that they do not print again later.
$form->reset("main.SearchHeader");
$form->reset("main.SearchHeaderEnd");

// Stick the form header out there.
echo get_form_header($mod_strings['LBL_LIST_FORM_TITLE'], $button, false);

$oddRow = true;
foreach($account_list as $account)
{

    if($oddRow)
    {
        //todo move to themes
        $class = '"oddListRow"';
    }
    else
    {
        //todo move to themes
        $class = '"evenListRow"';
    }
    $oddRow = !$oddRow;

    $account_details = Array("ID"=>$account->id,
    	"NAME"=>$account->name,
    	"ENCODED_NAME"=>htmlspecialchars($account->name, ENT_QUOTES),
    	"BILLING_ADDRESS_CITY"=>$account->billing_address_city);
	$form->assign("ACCOUNT", $account_details);
	$form->assign("CLASS", $class);
    $form->parse("main.row");	
}

$form->assign("START_RECORD", $start_record);
$form->assign("END_RECORD", $end_record-1);
$form->assign("RECORD_COUNT", $row_count);
$form->assign("START_LINK", $start_link);
$form->assign("PREVIOUS_LINK", $previous_link);
$form->assign("NEXT_LINK", $next_link);
$form->assign("END_LINK", $end_link);


$form->parse("main");
$form->out("main");

?>

	<tr><td COLSPAN=7><?php echo get_form_footer(); ?></td></tr>
	</table>
</td></tr></tbody></table>
</td></tr>

<?php insert_popup_footer(); ?>