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
 * $Header:  vtiger_crm/sugarcrm/modules/Users/Popup.php,v 1.1 2004/08/17 15:06:40 gjayakrishnan Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/
// This file is used for all popups on this module
// The popup_picker.html file is used for generating a list from which to find and choose one instance.

global $theme;
require_once('modules/Users/User.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');

global $mod_strings;
global $app_strings;

global $list_max_entries_per_page;
global $urlPrefix;
global $currentModule;

$log = LoggerManager::getLogger('user');

$seed_object = new User();


$where = "";
if(isset($_REQUEST['query']))
{
	$search_fields = Array("first_name", "last_name", "user_name");

	$where_clauses = Array();

	append_where_clause($where_clauses, "first_name", "users.first_name");
	append_where_clause($where_clauses, "last_name", "users.last_name");
	append_where_clause($where_clauses, "user_name", "users.user_name");

	$where = generate_where_statement($where_clauses);
}

$current_offset = 0;
if(isset($_REQUEST['current_offset']))
    $current_offset = $_REQUEST['current_offset'];

$response = $seed_object->get_list("user_name", $where, $current_offset);

$record_list = $response['list'];
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
$userList = $response['list'];
$row_count = $response['row_count'];
$next_offset = $response['next_offset'];
$previous_offset = $response['previous_offset'];

$start_record = $current_offset + 1;

// Set the start row to 0 if there are no rows (adding one looks bad)
if($row_count == 0)
    $start_record = 0;

$end_record = $start_record + $list_max_entries_per_page;

// back up the the last page.
if($end_record > $row_count + 1)
{
    $end_record = $row_count + 1;
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
	$form =new XTemplate ('modules/Users/Popup_picker.html');
	$log->debug("using file modules/Users/Popup_picker.html");
}
else {
	$form =new XTemplate ('modules/Users/'.$_REQUEST['html'].'.html');
	$log->debug("using file modules/Users/".$_REQUEST['html'].'.html');
	$log->debug("_REQUEST['html'] is ".$_REQUEST['html']);
}

$form->assign("MOD", $mod_strings);
$form->assign("APP", $app_strings);

// the form key is required
if(!isset($_REQUEST['form']))
	die("Missing 'form' parameter");
	
// This code should always return an answer.
// The form name should be made into a parameter and not be hard coded in this file.
if(isset($_REQUEST['form_submit']) && $_REQUEST['form'] == 'DetailView' && $_REQUEST['form_submit'] == 'true')
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(user_id, user_name) {\n";
	$the_javascript .= "	window.opener.document.DetailView.user_id.value = user_id; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_module.value = window.opener.document.DetailView.module.value; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_action.value = 'DetailView'; \n";
	$the_javascript .= "	window.opener.document.DetailView.return_id.value = window.opener.document.DetailView.record.value; \n";
	$the_javascript .= "	window.opener.document.DetailView.action.value = 'Save'; \n";
	$the_javascript .= "	window.opener.document.DetailView.submit(); \n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
}
elseif ($_REQUEST['form'] == 'UsersEditView') 
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(user_id, user_name) {\n";
	$the_javascript .= "	window.opener.document.EditView.reports_to_name.value = user_name;\n";
	$the_javascript .= "	window.opener.document.EditView.reports_to_id.value = user_id;\n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
}
else // ($_REQUEST['form'] == 'EditView') 
{
	$the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
	$the_javascript .= "function set_return(user_id, user_name) {\n";
	$the_javascript .= "	window.opener.document.EditView.user_name.value = user_name;\n";
	$the_javascript .= "	window.opener.document.EditView.user_id.value = user_id;\n";
	$the_javascript .= "}\n";
	$the_javascript .= "</script>\n";
}	

$form->assign("SET_RETURN_JS", $the_javascript);

$form->assign("THEME", $theme);
$form->assign("IMAGE_PATH", $image_path);
$form->assign("MODULE_NAME", $currentModule);
$form->assign("FORM_SUBMIT", $_REQUEST['form_submit']);
$form->assign("FORM", $_REQUEST['form']);

insert_popup_header($theme);

// Quick search.
echo "<form>";
echo get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE'], "", false);

if (isset($_REQUEST['first_name'])) $last_search['FIRST_NAME'] = $_REQUEST['first_name'];
if (isset($_REQUEST['last_name'])) $last_search['LAST_NAME'] = $_REQUEST['last_name'];
if (isset($_REQUEST['user_name'])) $last_search['USER_NAME'] = $_REQUEST['user_name'];
if (isset($last_search)) $form->assign("LAST_SEARCH", $last_search);

$form->parse("main.SearchHeader");
$form->out("main.SearchHeader");

echo get_form_footer();

$form->parse("main.SearchHeaderEnd");
$form->out("main.SearchHeaderEnd");

// Reset the sections that are already in the page so that they do not print again later.
$form->reset("main.SearchHeader");
$form->reset("main.SearchHeaderEnd");

// start the form before the form header to avoid creating a gap in IE
$button  = "<table cellspacing='0' cellpadding='1' border='0'><form border='0' action='index.php' method='post' name='form' id='form'>\n";
$button .= "<tr><td>&nbsp;</td>";
if ($_REQUEST['form_submit'] != 'true') $button .= "<td><input title='".$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accessyKey='".$app_strings['LBL_CLEAR_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.opener.document.EditView.account_name.value = '';window.opener.document.EditView.account_id.value = ''; window.close()\" type='submit' name='button' value='  Clear  '></td>\n";
$button .= "<td><input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessyKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick=\"window.close()\" type='submit' name='button' value='  ".$app_strings['LBL_CANCEL_BUTTON_LABEL']."  '></td>\n";
$button .= "</tr></form></table>\n";


// Stick the form header out there.
echo get_form_header($mod_strings['LBL_LIST_FORM_TITLE'], $button, false);

$oddRow = true;
foreach($record_list as $record)
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

    // Retrieve the extra related fields for a list view
    $record->fill_in_additional_list_fields();
    
    $record_details = Array("ID"=>$record->id,
    	"USER_NAME"=> $record->user_name,
		"FIRST_NAME" => $record->first_name,
		"LAST_NAME" => $record->last_name);
	$form->assign("USER", $record_details);
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
</form>
	</table>
</td></tr></tbody></table>
</td></tr>

<?php insert_popup_footer(); ?>